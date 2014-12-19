<?php

/**
 *
 * ThemeLoader.php
 * (c) Mar 3, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

require_once(LOCAL_DIR . "/core/MsgBox.php");

class ThemeLoader {
	
	/**
	 * 
	 * @fix http://www.php.net/manual/en/language.oop5.decon.php#91013
	 */

	public $file = "";
	public $array = array();

	public $template,$entries = array();

	private $_template;

        function __construct($file = "/themes/") {
			$this->file = $file;
			$this->html = $this->file;
		}//end constructor
		
		/**
		 * Obtener campos del código HTML y obtener valores de los mismos.
		 * Asignar valores en un array[].
		 */
		
		function renderFields($label, $value) {
			$this->fields[$label] = $value;
		}
		
		/**
		 * 
		 * @function renderPage().
		 * Le damos vista a nuestra página.
		 */
		
		function renderPage($array) {
			
			
			/**
			 * Debug
			 */
			
			// echo "renderPage test";
			
			$template = "";
			$this->array = $array;
			
			try {
				
				if(!file_exists($this->file)) {
					throw new Exception(_THEME_DONT_EXIST . ": " . $this->file);
				}
				
				$template = file_get_contents($this->file);
				
			} catch (Exception $e) {
				
				throw new Exception($e->getMessage());
				
			}
			
	
			foreach ($array as $key => $value) {
				$template = str_replace("{" . $key . "}", $value, $template);
			}
	
			return $template;
			
		}


		
}
