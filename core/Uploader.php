<?php
/**
 * Created by PhpStorm.
 * User: Anibal Gomez
 * Date: 25/03/15
 * Time: 8:56
 */

class Uploader {

    public function image($file) {
        if (!$file['error']) {
            if (!$file['error']) {
                $name = md5(rand(100, 200));
                $ext = explode('.', $file['name']);
                $filename = $name . '.' . $ext[1];
                if(!is_writable("../public/images/")) {
                    throw new Exception("<pre>
                    Directory '/public/images/ is not writable.
                    Set 'chmod permissions to 777'.
                    <b>$ sudo chmod 777 /uploads/images/</b>
                    </pre>");
                }
                $destination = "../public/images/" . $filename; //change this directory
                $location = $file["tmp_name"];
                move_uploaded_file($location, $destination);
            } else {
                throw new Exception("Ooops!  Your upload triggered
                the following error:  " . $file['error']);
            }
        }
    }

}