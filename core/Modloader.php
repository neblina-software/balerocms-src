<?php

/**
 *
 * Modloader.php
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
 * Cargar los mods del admin
 *
 */

class Modloader {
	
	protected $class;
	public $mod;
	
	public function __construct($mod = "") {
		
		$this->mod = $mod;
		spl_autoload_register(array($this, "mods_admin"));
				
	}

	/**
	 * 
	 * Buscar clases dentro de la app
	 */
	
	public function mods_admin($class) {
	
		$this->class = $class;
		
		$this->class = $class;
		if(file_exists(MODS_DIR . $this->mod . "/" . $this->class . ".php")) {
			require_once(MODS_DIR . $this->mod ."/" . $this->class . ".php");
		}
		

	}
	


	    
}
