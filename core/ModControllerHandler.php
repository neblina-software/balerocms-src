<?php

/**
 *
 * ModControllerManager.php
 * (c) May 10, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/


/**
 * 
 * @author lastprophet
 * Automaticar el controlador
 * obtener metodos de una clase (Ignorar metodos magicos de PHP)
 * http://php.net/manual/en/function.get-class-methods.php
 *
 */


class ModControllerHandler {

	public $class;

    private $objSecurity;

	public function __construct($var) {

        $this->objSecurity = new Security();

		try {
		$this->init($var);
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	
	}
	
	

	
	public function init($var) {
	
		/**
		 *
		 * Controlador interno (sr) de una app (sección)
		 * Ejemplo: index.php?app=blog&sr=subrutina
		 */
		try {
		if(isset($_GET['sr'])) {
				
			/**
			 *
			 * Problem with CGI/Fast CGI as PHP Server API Fixed
			 */
				
			$sr = $this->objSecurity->antiXSS($_GET['sr']);
			
			if(!isset($_GET['mod'])) {
				die();
			}

			$var_shield = $this->objSecurity->antiXSS($_GET['mod']);
			$class_methods = get_class_methods("mod_" . $var_shield . "_Controller");
				
			foreach ($class_methods as $method_name) {
				//echo "$method_name\n";
				
				/**
				 * Ignorar metodos magicos
				 **/
	
				if($sr == $method_name) {
	
					switch($sr) {						
						// llama staticamente
// 						appController::$_GET['sr']();
// 						appModel::$_GET['sr']();
// 						AppView::$_GET['sr']();
						
						// llamar dinamicamente
						case $sr:
						$var->$sr();
						break;
					
						
					} // switch
	
				} // if
					
			} // for each
	
		} else {
			if((!isset($_GET['sr']))) {
					$var->main();
			}
		}
		} catch (Exception $e) {
			echo "Error: " . __CLASS__ ." / ". __FUNCTION__ . " " . _METHOD_DONT_EXIST;
		}
	
	} // fin de manager()
	
	
}
