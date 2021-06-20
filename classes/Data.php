<?php
include 'lib/DbConnect.php';

class Data
{
    // Db Property
    private $db;

    private $csvFile;

    // Db __construct Method
    public function __construct()
    {
        $this->db = new DbConnect();
    }

    /**
     * Select All Users Method
     *
     * @return object
     */
    public function getAllData()
    {
        $sql = "SELECT * FROM info";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * get all users data as array
     *
     * @return array
     */
    public function getAllDataAsArray()
    {

        $dataArray = array();

        foreach ($this->getAllData() as $data) {
            $cellData[]        = $data->name;
            $cellData[]       = $data->email;
            $cellData[]     = $data->address;
            $cellData[] = $data->description;
            $cellData[]      = $data->amount;
            $cellData[]     = $data->balance;
            $cellData[]       = $data->count;
            $cellData[]    = $data->log_date;
            $cellData[]    = $data->log_time;
            $cellData[]      = $data->status;

            array_push($dataArray, $cellData);
            $cellData = [];
        }

        return $dataArray;
    }


    /**
     * upload the csv file
     *
     * @return boolean
     */
    public function uploadCSV()
    {

        $target_dir = realpath(dirname(__FILE__))."/../uploads/";
    
        // Check if the uploaded file is a csv or not
        $mimes = array('text/csv');
        if (~$_FILES['csvFile']['type']=='text/csv') {
            $_SESSION['error'] = "Invalid file format. File must be CSV";
            return false;
        } else {
            // target file to save the csv file
            $target_file = $target_dir . date('Ymdhis') . '.csv';

              // Check if file already exists
            if (file_exists($target_file)) {
                $_SESSION['error'] = "file already exists";
                return false;
            }

            // Check file size
            if ($_FILES["csvFile"]["size"] > 500000) {
                $_SESSION['error'] = "file is too large";
                return false;
            }

            // try to upload
            if (!move_uploaded_file($_FILES["csvFile"]["tmp_name"], $target_file)) {
                $_SESSION['error'] = "file uploading failed";
                return false;
            } else {
               //file upload is ok
                $this->csvFile = $target_file;
                return true;
            }
        }
    }
    
    /**
     * get data from the uploaded csv file
     *
     * @return array
     */
    public function getDataFromCsv()
    {
        $file = fopen($this->csvFile, 'r');

        $uploadedData = array();

        //loop through each line
        while (($line = fgetcsv($file)) !== false) {
            //$line is an array of the csv elements
            array_push($uploadedData, $line);
        }

        // get old data collection from database
        $oldDataCollection = $this->getAllDataAsArray();
        $newAddedData = array();
        $modified = [];

        // loop through the uploaded data to get modifications
        foreach ($uploadedData as $key => $newData) {
            if (isset($oldDataCollection[$key])) {
                //get old data for the row
                $oldData = $oldDataCollection[$key];

                //check if data modified
                if ($difference = array_diff($newData, $oldData)) {
                    $modified[$key] = $difference;
                } else {
                    $modified[$key] = null;
                }
            } else {
                // these are newly added data
                array_push($newAddedData, $newData);
            }
        }
        
        //close the file
        fclose($file);

        $data['modified'] = $modified;
        $data['new'] = $newAddedData;
        return $data;
    }

    /**
     * insert newly added data from csv file
     *
     * @return boolean
     */
    public function insertNewDataIfAny($csvRows)
    {
        $sql = "INSERT INTO info(name, email, address, description, amount, balance, count, log_date, log_time, status) VALUES(:name, :email, :address, :description, :amount, :balance, :count, :log_date, :log_time, :status)";
        
        // prepare statement
        $statement = $this->db->pdo->prepare($sql);

        // bind values to statement in loop for each new rows
        foreach ($csvRows as $row) {
            $key = 0;
            $statement->bindValue(':name', $row[$key++]);
            $statement->bindValue(':email', $row[$key++]);
            $statement->bindValue(':address', $row[$key++]);
            $statement->bindValue(':description', $row[$key++]);
            $statement->bindValue(':amount', $row[$key++]);
            $statement->bindValue(':balance', $row[$key++]);
            $statement->bindValue(':count', $row[$key++]);
            $statement->bindValue(':log_date', $row[$key++]);
            $statement->bindValue(':log_time', $row[$key++]);
            $statement->bindValue(':status', $row[$key]);
            $result = $statement->execute();
        }

        if ($result) {
            return true;
        } else {
            $_SESSION['error'] = "Data insertion failed! Please check if your csv file. May be your have missed something or your cell data is wrong.";
            return false;
        }
    }

    /**
     * update edited data from csv file
     *
     * @return boolean
     */
    public function updateDataIfAny($editedCsvRows)
    {
        //check if there is any edited row in csv file's data
        if ($editedCsvRows) {
            // loop through each row to process update
            foreach ($editedCsvRows as $key => $editedRow) {
                if ($editedRow) {
                    $sql = "UPDATE info SET ";

                    $updateSql = "";

                    if (isset($editedRow[0])) {
                        $updateSql .= " name = :value0 ";
                    }

                    if (isset($editedRow[1])) {
                        if ($updateSql!="") {
                              $updateSql .= ",";
                        }
                        $updateSql .= " email = :value1 ";
                    }

                    if (isset($editedRow[2])) {
                        if ($updateSql!="") {
                            $updateSql .= ",";
                        }
                        $updateSql .= " address = :value2 ";
                    }

                    if (isset($editedRow[3])) {
                        if ($updateSql!="") {
                            $updateSql .= ",";
                        }
                          $updateSql .= " description = :value3 ";
                    }

                    if (isset($editedRow[4])) {
                        if ($updateSql!="") {
                            $updateSql .= ",";
                        }
                        $updateSql .= " amount = :value4 ";
                    }

                    if (isset($editedRow[5])) {
                        if ($updateSql!="") {
                            $updateSql .= ",";
                        }
                        $updateSql .= " balance = :value5 ";
                    }

                    if (isset($editedRow[6])) {
                        if ($updateSql!="") {
                            $updateSql .= ",";
                        }
                        $updateSql .= " count = :value6 ";
                    }

                    if (isset($editedRow[7])) {
                        if ($updateSql!="") {
                            $updateSql .= ",";
                        }
                        $updateSql .= " log_date = :value7 ";
                    }

                    if (isset($editedRow[8])) {
                        if ($updateSql!="") {
                            $updateSql .= ",";
                        }
                        $updateSql .= " log_time = :value8 ";
                    }

                    if (isset($editedRow[9])) {
                        if ($updateSql!="") {
                            $updateSql .= ",";
                        }
                        $updateSql .= " status = :value9 ";
                    }

                    $updateSql .= " WHERE id = :id";

                    // prepare statement
                    $statement = $this->db->pdo->prepare($sql.$updateSql);

                    // bind values to statement
                    for ($i=0; $i < 10; $i++) {
                        if (isset($editedRow[$i])) {
                            $statement->bindValue(':value'.$i, $editedRow[$i]);
                        }
                    }

                    // bind row's id to statement
                    $statement->bindValue(':id', $key+1);

                    //execute the statement
                    $result = $statement->execute();

                    if (!$result) {
                        $_SESSION['error'] = "Data editing failed! Please check if your csv file. May be your have missed something or your cell data is wrong.";
                        return false;
                    }
                }
            }

            return true;
        }
        $_SESSION['error'] = "Nothing to update!";
        return false;
    }
}
