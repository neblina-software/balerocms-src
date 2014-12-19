<?php

/**
 *
 * errorRender.php
 * (c) 2 June, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class errorLDRapp {
	
	
	/**
	 * Variable de contenido $content
	 */

	public $content = "";
	
	
	public function __construct() {
		
	}

	/**
	 * Cargar la vista.
	 */
	
	public function Render() {
		
		$array = array(
				'content'=>$this->content,
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/themes/universe/main.html");		
		echo $objTheme->renderPage($array);
		
	
	}
	
	
	/**
	 * Metodos
	 */
	
	
	public function print_error($e) {
		
		$admMsgBox = new MsgBox("ERROR CARGANDO APP", $e);
		$this->content .= $admMsgBox->Show();
		
	}

	
} // fin clase
