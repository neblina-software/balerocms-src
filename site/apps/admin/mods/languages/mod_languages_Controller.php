<?php

/**
 * 
 * @author lastprophet
 *
 */

class mod_languages_Controller extends Security {
	
	public $modModel;
	public $modView;
	
	protected $menu;
	
	public function __construct($menu) {
		
		$this->menu = $menu;
		
		// cargar vista de módulo.
		try {
			$this->modModel = new mod_languages_Model();
			$this->modView = new mod_languages_View();
			$this->modView->menu = $this->menu;
		} catch (Exception $e) {
			die("error" . $e->getMessage());
		}
		
		// Automatizar el controlador
		try {
		$handler = new ModControllerHandler($this);
		} catch (Exception $e) {
			die($e->getMessage());
		}
		
// 		switch ($_GET['sr']) {
// 			case "prueba":
// 				echo "funciona";
// 				break;
// 		}
		
	}
	
	
	public function main() {

		$this->modView->setup_view();
		
	}
	
	/**
	 * Methods and controllers
	 */
	

	public function setup() {
			
		if(isset($_POST['submit'])) {
			$this->add_language();
		}elseif (isset($_POST['delete_language'])) {
			$this->delete_language();
		}elseif (isset($_POST['activate'])) {
			$this->activate_module();
		}else {
			$this->modView->setup_view();
		}
		
	}
	
	
	/**
	 * Other methods (not controllers)
	 */
	
	/**
	 * get lang from dropdown list and returns lang code
	 */
	
	public function get_lang($input) {
		
		/**
		 * reads "label (code)" and gets "code"
		 */

    	preg_match("/\((.+)\)/", $input, $output);
    	//echo "array (" . print_r($output) . ")<br>";
    	$lang = $output[0]; // return (en)
    
    	/**
	 	* Remove "(" ")"
     	*/
    
    	$_lang = $this->remove_p($lang);
    
    	/**
     	* returns lang
     	*/
    
    	return $_lang;
    
	}
	
	public function remove_p($input) {
	
		
		/**
		 * Remove "(" ")"
		 */
		
		$vowels = array("(", ")");
		$output = str_replace($vowels, "", $input);
		
		return $output;
		
	}
	
	public function add_language() {
		
		try {
			if(empty($_POST['label'])) {
				throw new Exception(_ERROR_ADD_LANGUAGE);
			}
			
			$label = $this->shield($_POST['label']);
			
			/**
			 * Anti-TamperData
			 */
			
			$code = $this->shield($_POST['code']);
			
			/**
			 * Get language list into array
			 */
			
			$array = $this->modModel->get_lenguages();
			
			/**
			 * Returns TRUE if lang code exist
			 */
			
			if(is_array($array)) {
				if($this->search_language($array, $code)) {
					throw new Exception(_LANG_EXISTS);
				}
			}

			
			$this->modModel->add_language($this->remove_p($label), $code);
			
			
			/**
			 *
			 * To do not use PHP header() or Javascript reloading page
			 * We reload modView class :)
			 * That's why Balero is the next generation CMS.
			 *
			*/
				
			
			unset($this->modView); // clear view
			$this->modView = new mod_languages_View();
			$this->modView->sucessMessage(_SUCESS_ADD_LANGUAGE);
			$this->modView->menu = $this->menu; // load menu
			
		} catch (Exception $e) {
			$this->modView->errorMessage($e->getMessage());
		}
		
		$this->modView->setup_view(); // render page
		
	}
	
	public function delete_language() {
		
		try {
			$lang = $_POST['lang'];
			$code = $this->get_lang($lang);
			$this->modModel->delete_language($code);
			unset($this->modView); // clear view
			$this->modView = new mod_languages_View();
			$this->modView->sucessMessage(_DELETE_SUCESS);
			$this->modView->menu = $this->menu; // load menu
		} catch (Exception $e) {
			$this->modView->errorMessage($e->getMessage());
		}

		$this->modView->setup_view(); // render method
		
	}
	
	public function activate_module() {
		
		$admcfg = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
		
		try {
		
			if(!$_POST['lang_on']) {
				throw new Exception();
			}
			
			$value = "yes";
			$admcfg->editChild("/config/site/multilang", $value);
		
		} catch (Exception $e) {
			
			$value = "no";
			$admcfg->editChild("/config/site/multilang", $value);
			
		}
		
		unset($this->modView);
		$this->modView = new mod_languages_View();
		$this->modView->menu = $this->menu;
		$this->modView->setup_view(); // render page method
		
	}
	
	/**
	 * Search language in array
	 */
	
	public function search_language($input = array(), $string) {
		
		/**
		 * convert input dynamic array to simple array
		*/
		
		foreach ($input as $value) {
			$output[] = $value['code'];
		}
		
		//print_r($output);
		
		/**
		 * Search String (code) in output
		 */
		
		foreach ($output as $value) {
			if($value == $string) {
				//echo "existe $string <br>";
				return 1;
			} else {
				//echo "no existe $string <br>";
			}
		}
		
	}
	
}
