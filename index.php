<?php

/**
 *
 * index.php
 * (c) Feb 26, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 * ============================================
 * Pretty URLs by default on version 0.3+
 *
**/

error_reporting(0); // Debug: Developer (-1) / User (0)
define("_CORE_VERSION", "0.8"); // Version

// DO NOT EDIT
// -----------
$dir = dirname(__FILE__); // Windows Servers
$dir = str_replace("\\", "/", $dir);

define("LOCAL_DIR", $dir); // Current dir
define("APPS_DIR", LOCAL_DIR . "/site/apps/"); // App dir
define("MODS_DIR", LOCAL_DIR . "/site/apps/admin/mods/"); // Mods dir

require_once(LOCAL_DIR . "/core/Router.php"); // Load
require_once(LOCAL_DIR . "/core/CMSHeaders.php");

$objHeaders = new CMSHeaders();
$objRouter = new Router();
$objHeaders->cmsHeaders();
$objRouter->init(); // Do magic
// -----------
