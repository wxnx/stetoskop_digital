<?php

use App\Models\MachineLearning;

class usr
{
}

$file = new MachineLearning();

$target_path = "/storage/public/";
$target_path = $target_path . basename($_FILES['uploadedfile']['name']);

// $file->pasien_id = ;
$file->name = basename($_FILES['name']);
$file->file_path = $target_path;
$file->save();

if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo "The file" . basename($_FILES['uploadedfile']['name']) . "has been uploaded";
} else {
    echo "There was an error uploading the file, please try again!";
    echo "Filename: " . basename($_FILES['uploadedfile']['name']);
    echo "target_path: " . $target_path;
}
