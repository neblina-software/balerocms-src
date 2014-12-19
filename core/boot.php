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
 * Cargar funcionalidad del núcleo
 *
 */

class boot {
	
	protected $class;
	
	public function __construct() {
		
		spl_autoload_register(array($this, "core"));
				
	}

	public function core($class) {
	
		$this->class = $class;
		if(file_exists(LOCAL_DIR . "/core/" . $this->class . ".php")) {
			require_once(LOCAL_DIR . "/core/" . $this->class . ".php");
		}
		
	
	}
	
	    
}