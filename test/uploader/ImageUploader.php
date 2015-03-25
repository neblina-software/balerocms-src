<?php
/**
 * Created by PhpStorm.
 * User: Anibal Gomez
 * Date: 25/03/15
 * Time: 9:13
 */

require_once("../core/Uploader.php");

class ImageUploader {

    public function init($file) {
        try {
            $uploader = new Uploader();
            $uploader->image($file);
            echo "Creating image /uploads/images/" .
                $file['name'] . " Sucess!";
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

}