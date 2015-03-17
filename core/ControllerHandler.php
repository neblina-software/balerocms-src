<?php

/**
 *
 * ControllerHandler.php
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
 * Automatizar el controlador 
 * obtener metodos de una clase (Ignorar metodos magicos de PHP)
 * http://php.net/manual/en/function.get-class-methods.php
 *
 */


class ControllerHandler {

	public $class;

    private $objSecurity;
	
	public function __construct($var) {

        $this->objSecurity = new Security();
		$this->init($var);
	
	}
	
	public function init($var) {
	
		/**
		 *
		 * Controlador interno (sr) de una app (sección)
		 * Ejemplo: index.php?app=blog&sr=subrutina
		 * ==============================================
		 * v0.3+ 
		 * ==============================================
		 * Ejemplo: /blog/subrutina
		 */
		
		if(isset($_GET['sr'])) {
				
			/**
			 * 
			 * Problem with CGI/Fast CGI as PHP Server API Fixed
			 */
			
			$sr = $_GET['sr'];
			
			if(!isset($_GET['app'])) {
				die(_GET_APP_DONT_EXIST);
			}
			
			//$class_methods = get_class_methods("appController");
			$shield_var = $this->objSecurity->antiXSS($_GET['app']);
			$class_methods = get_class_methods($shield_var . "_Controller");
			//var_dump($class_methods);
				
			foreach ($class_methods as $method_name) {
				//echo "$method_name\n";
				
				/**
				 * Ignorar metodos magicos
				 */
	
				
				if(($sr == $method_name)) {
 						
					switch($sr) {						
						// llama staticamente
 						//appController::$sr();
 						//appModel::$sr();
 						//AppView::$sr();
						
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
		
	} // fin de init()
	
}
