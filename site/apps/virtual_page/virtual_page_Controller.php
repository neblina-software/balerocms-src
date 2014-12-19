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

class virtual_page_Controller extends ControllerHandler {
	
	/**
	* Variables para heredar los métodos de el modelo y la vista.
	**/

	public $objModel;
	public $objView;
	
	/**
	 * @param $lang default language
	 */
	
	private $lang;
	
	/**
	* Los cargamos en el constructor
	**/

	public function __construct() {
				
		try {
			$this->objModel = new virtual_page_Model();
			$this->objView = new virtual_page_View();
		} catch (Exception $e) {
			$this->objView = new virtual_page_View();
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
		
		
		$this->init($this);
	}
		
	/**
	* Método principal llamado main() (similar a C/C++)
	**/

	public function main() {
	
		try {
			
			if(isset($_GET['id'])) {	
				$shield = new Security();
				$id = $shield->shield($_GET['id']);
				$this->objModel->lang = $_GET['sr'];
				$query_content = $this->objModel->get_virtual_page_by_id($id);
				$this->objView->rows = $this->objModel->rows;
				$md = new Markdown();
				$this->objView->content .= "<div id=\"vp-content\">" . 
											$md->defaultTransform($this->objView->print_virtual_page($query_content))
											. "</div>";
			} else {
				throw new Exception();
			}
			
			
		} catch (Exception $e) {
			$this->objView->page = _NOT_FOUND;
			$msgbox = new MsgBox(_VP, _VP_DONT_EXIST);
			$this->objView->content .= $msgbox->Show();
		}
		
		$this->objView->Render();	
		
	}
	
	/**
	* Métodos
	**/
		
	
	public function init($var) {
		
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
			$security = new Security();
			$shield_var = $security->shield($_GET['app']);
			$class_methods = get_class_methods($shield_var . "_Controller");
			//var_dump($class_methods);
	
				
			$mods = new Modloader("languages");
			$ModLangs = array();
			$objModLangs = new mod_languages_Model();
			$ModLangs = $objModLangs->get_lenguages();

			$cfgSettings = new configSettings();
			
			if($cfgSettings->multilang == "yes") {
			
				foreach ($ModLangs as $row) {
					if($_GET['sr'] == $row['code']) {
							
						try {
							
						/**
						 * Multilgang posts view
						 */
							
						$this->lang = $row['code'];	
						$this->objModel->lang = $this->lang;
						$this->objView->lang = $this->lang;
						
						$total_rows = $this->objModel->total_pages();
						$limit = $this->objModel->limit();
						$p = new Pagination($total_rows, $limit);
						$min = $p->min();
						
						if(isset($_GET['id'])) {
							$shield = new Security();
							$id = $shield->shield($_GET['id']);
							$query_content = $this->objModel->get_virtual_page_by_id($id);
							$this->objView->rows = $this->objModel->rows;
							$shield = new Security();
							$md = new Markdown();
							$this->objView->content .= "<div id=\"vp-content\">" . 
														$md->defaultTransform($this->objView->print_virtual_page($query_content))
														. "</div>";
						} else {
							throw new Exception();
						}
		
						} catch (Exception $e) {
							$msgbox = new MsgBox(_VP, _VP_DONT_EXIST);
							$this->objView->content .= $msgbox->Show();
						}
						$this->objView->Render();
							
						//echo $row['code'];
						die();
							
					}
					
				} // for each
			
			} // end if config settings
				
			foreach ($class_methods as $method_name) {
	
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
	
				}
					
			} // for each
	
		} else {
			if((!isset($_GET['sr']))) {
				$var->main();
			}
		}

		
	} // fin de init()
	
	
}
