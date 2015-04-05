<?php
/**
 *
 * Uploader.php
 * (c) Mar 25, 2015 lastprophet
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
 **/

class Uploader {

    public function image($file, $path) {
        if (!$file['error']) {
            if (!$file['error']) {
                $name = md5(rand(100, 200));
                $ext = explode('.', $file['name']);
                $filename = $name . '.' . $ext[1];
                if(!is_writable($path . "/public/images/")) {
                    throw new Exception("Directory " . $path . "/public/images is not writable.
                    Set chmod permissions to 777.");
                }
                $destination = $path . "/public/images/" . $filename; //change this directory
                $location = $file["tmp_name"];
                move_uploaded_file($location, $destination);
            } else {
                throw new Exception("Ooops!  Your upload triggered
                the following error:  " . $file['error']);
            }
        }
        return $this->getBasepath($path) . "/public/images/" . $filename;
    }

    public function getBasepath($path) {
        $config = simplexml_load_file($path . "/site/etc/balero.config.xml");
        return $config->site[0]->basepath;
    }

}