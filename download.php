<?php

include 'inc/app.php';

$filename = 'users.csv';

// file creation
$file = fopen($filename, "w");

// write each row as line to csv file
foreach ($data->getAllDataAsArray() as $line) {
    fputcsv($file, $line);
}

// close the file
fclose($file);

// download
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$filename);
header("Content-Type: application/csv; ");

readfile($filename);

// deleting file after download
unlink($filename);
exit();
