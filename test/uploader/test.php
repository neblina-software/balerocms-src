<?php

/**
 * All unit test right here
 */

// Image Uploader Test
//echo "Running ImageUploader Test...";
if(isset($_FILES['file'])) {
    require_once("./uploader/ImageUploader.php");
    $uploaderTest = new ImageUploader();
    $uploaderTest->init($_FILES['file']);
} else {
    include("./uploader/form.html");
}