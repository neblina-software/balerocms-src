<?php

/**
* Plantilla de la clase appController para Balero CMS.
* Coloque aqui la entrada/salida de datos.
* Llame desde ésta clase los modelos/vistas 
* correspondientes de cada controlador.
**/

/**
 * Multi-Language Fixes
 */

/**
 * 
 * @author lastprophet
 * Extends ControllerHandler to multilanguage pages
 *
 */

class blog_Controller extends ControllerHandler {
	
	/**
	* Variables para heredar los métodos de el modelo y la vista.
	**/

	public $objModel;
	public $objView;
		
	/**
	 * 
	 * Default language
	 */
	
	public $lang;
	
	/**
	* Los cargamos en el constructor
	**/

	public function __construct() {
				
		try {
			$this->objModel = new blog_Model();
			$this->objView = new blog_View();
		} catch (Exception $e) {
			$this->objView = new blog_View();
		}
				
		/**
		 * get default language if empty is main
		 */
		
		$this->lang = $this->objModel->getLang();
		
		/**
		 * Tell this classes the main language
		 */
		
		$this->objView->lang = $this->lang;
		$this->objModel->lang = $this->lang;
		
		//echo "lang: " . $this->lang;
		
		/**
		 * Automatizar el controlador
		 * $handler = new ControllerHandler($this);
		 * init() controllers
		 */
		
		$this->init($this);
		
		
	}
		
	/**
	* Método principal llamado main() (similar a C/C++)
	**/

	public function main() {
		
		/**
		 * Show full post in main()
		 */
		
		if(isset($_GET['id'])) {
			$this->full_post();
			$this->objView->Render();
			die();
		}
		
		 try {
		 	
		 	/**
		 	 * For main() method
		 	 * Get default lang (from blog table) or
		 	 * Get multilang page (from blog_multilang)
		 	 */
		 	
			$this->all_post();
		
			if(!$this->objModel->rows) {
				throw new Exception();
			}
			
		 } catch (Exception $e) {
		 	
		 	/**
			 * No registers to show message
		 	 */
		 	
		 	$msgBox = new MsgBox(_BLOG_ERROR, _BLOG_ERROR_MESSAGE);
		 	$this->objView->content .= $msgBox->Show();
		 	
		 }
		
		
		$this->objView->Render();	
		
	}
	
	/**
	* Métodos
	**/
	
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
				
			$security = new Security();
			$shield_var = $security->shield($_GET['app']);
			$class_methods = get_class_methods($shield_var . "_Controller");
			//var_dump($class_methods);
	
			
			$mods = new Modloader("languages");
			$ModLangs = array();
			$objModLangs = new mod_languages_Model();
			$ModLangs = $objModLangs->get_lenguages();
			
			
			foreach ($ModLangs as $row) {
				
				
				switch ($sr) {
					case $row['code']:
					if(isset($_GET['id'])) {
						$this->full_post();
					} else {
						$this->all_post();	
					}
					$this->objView->Render();
					break;
				}
					
			}
			
			foreach ($class_methods as $method_name) {

				if(($sr == $method_name)) {
				
					switch($sr) {
					
						case $sr:
							$var->$sr();
							break;
						
	
					} // switch
	
				}
					
			} // for each
	
		} else {
			if((!isset($_GET['sr']))) {
				$var->main();
			}
		}
	
	} // fin de init()
	
	
	public function all_post() {
		
		/**
		 * Call virtual pages menu
		 */
		
		$ldr = new autoloader("virtual_page");
		$vp = new virtual_page_View();
		$vp->lang = $this->lang;
		$this->objView->printVirtualPages = $vp->virtual_pages_menu();
		
		/**
		 * Pagination
		 */
		
			// rows totales
			$total_rows = $this->objModel->total_post();
			// obtener limite de paginación desd las config.
			$limit = $this->objModel->limit();
			// traer paginador
			$p = new Pagination($total_rows, $limit);
				
			// obtener LIMIT (min)
			$min = $p->min();
				
			$this->objModel->get_post($min, $limit);
				
			// resultado de la query en array
			$this->objView->rows = $this->objModel->rows;
			//print_r($this->objModel->rows);
				
			$this->objView->show_all_post();
			
			$this->objView->content .= $p->pretty_nav("blog/" . $this->lang);
			
	}
	
	public function full_post() {
		
		/**
		 * Call virtual pages menu
		 */
		
		$ldr = new autoloader("virtual_page");
		$vp = new virtual_page_View();
		$vp->lang = $this->lang;
		$this->objView->printVirtualPages = $vp->virtual_pages_menu($this->lang);
		
		
		if(isset($_GET['sr'])) {
			$this->lang = $_GET['sr']; // get sr has the language
			$this->objModel->lang = $this->lang;
			$this->objView->lang = $this->lang;
			$this->objView->rows = $this->objModel->rows;
		}
			
			$this->objModel->get_fullpost($_GET['id']);
			$this->objView->rows = $this->objModel->rows;
			$this->objView->full_post_view($this->objModel->rows);
			
	}
	
	/**
	 * We need one controller to manage this lang method class
	 */
	
	public function setlang() {
	
		date_default_timezone_set('UTC');
		$expire = date("Y-m-d", strtotime("+1 day")); // expire in 1 day
	
		$langsList = $this->objModel->getLangList();
		$lang = new Language();
	
		$value = $lang->setLang($langsList, $_GET['lang']);
	
		$this->objModel->setVirtualCookie($_SERVER['REMOTE_ADDR'], $value, $expire);
	
		/**
		 * Reload language public vars
		*/
	
		$this->lang = $this->objModel->getLang();
		$this->objView->lang = $this->lang;
		$this->objModel->lang = $this->lang;
	
		/**
		 * Refresh main()
		 */
	
		$this->main();
	
	}
	
	public function test() {
		
		echo "test";
	}
	

}
