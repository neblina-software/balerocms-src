<?php

/**
 *
 * Blowfish.php
 * (c) May 3, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/


/**
 * 
 * @author Anibal Gomez - lastprophet
 * Encriptar passwords en BlowFish con 'salt'.
 * Balero CMS implementa el sistema llamado BlowFish Login :) :) :)
 * v.0.3+ login form view in Blofish Class
 *
 */

class Blowfish {
	
	private $pwd;
	private $pwd_string;
	
	public $message;
	public $basepath;
	
// referencias en esta pagina
//http://www.the-art-of-web.com/php/blowfish-crypt/#.UbTIRBx38Yw
// verificar                  //el pwd encriptado
// 	if(crypt("texto_plano", $pwd_hashed) == $pwd_hashed) {
// 		echo "pwd correcto";
// 	} else {
// 		echo "pwd incorrecto";
// 	}
	
	public function genpwd($pwd = "") {
		
		/**
		 * 
		 * generar salt
		 */
		
		$salt = ""; 
		$salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9)); 
		
		for($i=0; $i < 22; $i++) {
			$salt .= $salt_chars[array_rand($salt_chars)];
		} 
		
		return crypt($pwd, sprintf('$2a$%02d$', 7) . $salt);
		
	}	
	
	public function verify_hash($text, $hash) {
		
		if(crypt($text, $hash) == $hash) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}
	
	/**
	 * 
	 * require ThemeLoder class
	 */
	
	public function login_form($view) {
			
		
		/**
		 * Debug
		 */
		
		//echo $view;
		
			/**
			 *
			 * Login view {vars}
			 */
			
			$array = array(
					'message'=>$this->message,
					'basepath'=>$this->basepath
			);
			
			
			/**
			 *
			 * Render page
			*/
			
		
			//require_once(LOCAL_DIR . "/core/ThemeLoader.php");
			
			try {

				//if(!file_exists($view)) {
					//throw new Exception(_THEME_DONT_EXIST);
				//}
				
				$objTheme = new ThemeLoader($view);
			
			} catch (Exception $e) {
				
				throw new Exception($e->getMessage());
				
			}
			
			return $objTheme->renderPage($array);
			
	}
	
	/**
	 * Metodos magicos de PHP
	 */
	
	public function __destruct() {
		$this->pwd;
		unset($this->array);
	}

	
	
}