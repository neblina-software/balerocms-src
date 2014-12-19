<?php

/**
* Plantilla de la clase appController para Balero CMS.
* Coloque aqui la entrada/salida de datos.
* Llame desde ésta clase los modelos/vistas 
* correspondientes de cada controlador.
**/

class admin_Controller {
	
	/**
	* Variables para heredar los métodos de el modelo y la vista.
	**/

	public $objModel;
	public $objView;
	
	/**
	 * 
	 * Catch value from other class
	 */
	
	protected $menu;
	
		
	/**
	* Los cargamos en el constructor
	**/

	public function __construct($menu) {
		
		$this->menu = $menu;
		
		try {
			$this->objModel = new admin_Model();
			$this->objView = new admin_View();
			$this->objView->menu = $this->menu;
			
		} catch (Exception $e) {
			
		}
		
		// Automatizar el controlador
		$handler = new ControllerHandler($this);
	}
		
	/**
	* Método principal llamado main() (similar a C/C++)
	**/

	public function main() {
	
		try {
			
			$this->settings_controller();
			
			/**
			 * Wait for view
			 */
			
		} catch (Exception $e) {
			
		}
		
	}
	
	/**
	* Métodos
	**/
		
	
	public function settings_controller() {
		
		$this->objModel->deleteExpired();
		
		if(isset($_POST['submit'])) {
			
			try {
			
			// url friendly prox. versiones
			//$this->objModel->save_custom_settings($_POST['themes'], $_POST['url_friendly'], $_POST['pages']);
			
			$this->objModel->save_custom_settings(
								$_POST['themes'], 
								$_POST['pages'])
								;
				
			$admcfg = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
		
			$admcfg->editChild("/config/site/title", $_POST['title']);
			$admcfg->editChild("/config/site/url", $_POST['url']);
			$admcfg->editChild("/config/site/description", $_POST['description']);
			$admcfg->editChild("/config/site/keywords", $_POST['keywords']);
			$admcfg->editChild("/config/site/editor", $_POST['editors']);
			
			/**
			 * Get refresh view (reloading view)
			 */
			
			unset($this->objView);
			$this->objView = new admin_View();
			$this->objView->menu = $this->menu;
			$ok = new MsgBox("", _ADMIN_DATA_OK, "S");
			$this->objView->content .= $ok->Show();
			
			/**
			 * Wait for render() method
			 */
			
			} catch (Exception $e) {
				$admcfg->editChild("/config/site/editor", "markdown");
				$ok = new MsgBox("", _ADMIN_DATA_ERROR . " " . $e->getMessage(), "E");
				$ok->Show();
			}
		}
		
		$this->objView->settings_view();

	}
	
	public function test_db() {
		
		$this->objModel->test_db_model();
		
	}
	
	public function prueba() {
		
		echo "test";
		
	}
	
	public function get_regs_in_controller($name) {
		
		$regs = $this->objModel->get_regs(name);
		
		return $regs;
		
	}
	

	
}
