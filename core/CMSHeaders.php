<?php

/**
 *
 * CMSHeaders.php
 * Date: 19/03/15
 * Time: 11:43
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
 **/

class CMSHeaders {

    /**
     * Disable Problematic Protection
     * no-cache method's Konqueror's fix and other browsers for login page
     */
    public function cmsHeaders() {
        header("X-XSS-Protection: 0");
        header("Expires: Mon, 19 April 1987 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

}