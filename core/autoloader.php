<?php

/**
 *
 * autoloader.php
 * (c) May 11, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

/**
 * 
 * Cargar clases de nuestra 'app'
 *
 */

class autoloader {
	
	protected $class;
	public $app;
	
	public function __construct($app = "") {
		
		$this->app = $app;
		spl_autoload_register(array($this, "app"));
						
	}

	/**
	 * 
	 * Buscar clases dentro de la app
	 */
	
	public function app($class) {
	
		$this->class = $class;
		if(file_exists(APPS_DIR . $this->app . "/" . $this->class . ".php")) {
			require_once(APPS_DIR . $this->app ."/" . $this->class . ".php");
		}

	}
	
	/**
	 *
	 * Buscar clases dentro de todas las apps
	 */
	
	public function all($class) {
	
		$this->class = $class;
		if(file_exists(APPS_DIR . $this->app . "/" . $this->class . ".php")) {
			require_once(APPS_DIR . $this->app ."/" . $this->class . ".php");
		}
	
	}
	


	    
}
