<?php
include 'inc/app.php';
$csvData = [];
$oldRowsCount = 0;
$updatedRowCount = null;
$addedRowCount = null;

if (isset($_POST['fileupload'])) {
    if ($data->uploadCSV()) {
        $csvData = $data->getDataFromCsv();
        $_SESSION['success'] = "CSV file uploaded successfully";

    //insert new data from csv file (if any)
        if ($csvData['new']) {
            if (!$data->insertNewDataIfAny($csvData['new'])) {
            //process data insertio error
                $_SESSION['error'] = "Data insertion failed!";
            }
        }

    // update edits on the old data in csv (if any)
        if ($csvData['modified']) {
            if (!$data->updateDataIfAny($csvData['modified'])) {
                //process data update error
                $_SESSION['error'] = "Data updating failed!";
            }
            $oldRowsCount = count($csvData['modified']);
        }

        $updatedRowCount = count(array_filter($csvData['modified'], function ($x) {
            return !is_null($x);
        }));
        $addedRowCount = count($csvData['new']);
    }
}

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CSV File Task</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>

        <div class="container">
            <h2>CSV File Task</h2>
            <p class="text-success">
                <?= (isset($_SESSION['success']) == true) ? $_SESSION['success'] : "" ?>
            </p>
            <p class="text-danger">
                <?= (isset($_SESSION['error']) == true) ? $_SESSION['error'] : "" ?>
            </p>

            <p class="text-warning">
                <?= (isset($updatedRowCount) == true) ? $updatedRowCount . " row(s) updated.<br>" : ""?>
                <?= (isset($addedRowCount) == true) ? $addedRowCount . " row(s) added from csv." : "" ?>
            </p>
            <p>Table showing data from MySQL Database:</p>  
            <a href="/download.php" class="btn btn-xl btn-primary pull-right">Download CSV</a>          
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>Count</th>
                        <th>Log Date</th>
                        <th>Log Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data->getAllDataAsArray() as $key => $data) {
                        if ($oldRowsCount > 0 && $key + 1 <= $oldRowsCount) {
                            $modifiedRow = $csvData['modified'][$key];
                        }
                        ?>
                    <tr>
                        <?php
                        for ($i = 0; $i < 10; $i++) {
                            $css = "";
                            if ($oldRowsCount > 0 && $key + 1 <= $oldRowsCount) {
                                if (isset($modifiedRow[$i])) {
                                    $css = " class='text-danger bg-dark'";
                                } else {
                                    $css = "";
                                }
                            }

                            echo "<td" . $css . ">" . $data[$i] . "</td>";
                        }
                        ?>
                    </tr>
                        <?php
                    }
                    ?>
  
                </tbody>
            </table>

            <div class="col-md-12">
                <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                    Select CSV file to upload:

                    <input type="file" name="csvFile" id="csvFile">
                    <input type="hidden" name="fileupload" value="1">
                    <br>
                    <button type="submit" class="btn btn-xl btn-success">Upload Updated CSV</button>
                </form>
            </div>

        </div>

    </body>
</html>
