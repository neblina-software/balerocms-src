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

/**
 * Debug Option
 * Desarrollador / Developer (-1)
 * Usuario / User (0) (default)
 * (Editar)
 */

error_reporting(0);

/**
 * Balero CMS Version
 */

define("_CORE_VERSION", "0.7.2");

/**
 * 
 * Para servidores con Windows.
 * (No editar)
 */

$dir = dirname(__FILE__);
$dir = str_replace("\\", "/", $dir);

/**
 *
 * LOCAL_DIR = Directorio dónde se encuentra nuestro sistema.
 * (No editar)
 */

define("LOCAL_DIR", $dir);

/**
 * Apps directory
 * (No editar)
 */

define("APPS_DIR", LOCAL_DIR . "/site/apps/");

/**
 * Admin mods directory
 * (No editar)
*/

define("MODS_DIR", LOCAL_DIR . "/site/apps/admin/mods/");

/**
 * 
 * Cargamos Balero CMS.
 * (No editar)
 */

require_once(LOCAL_DIR . "/core/Router.php");

/**
 * 
 * Hacemos magia.
 * (No editar)
 */

$objRouter = new Router();
$objRouter->init();
