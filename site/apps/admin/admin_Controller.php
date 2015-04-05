<?php

/**
 *
 * admin_Controller.php
 * (c) Jun 11, 2013 lastprophet
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
 * 15-03-2015 Multiple Authenticated Blind SQL Injections
 * Reported By Gjoko Krstic <gjoko@zeroscience.mk>
 * Fixed by Anibal Gomez <anibalgomez@icloud.com>
 *
 *
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

    private $objSecurity;

    private $msg;
		
	/**
	* Los cargamos en el constructor
	**/

	public function __construct($menu) {

        $this->objSecurity = new Security();
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
								$this->objSecurity->antiXSS($_POST['themes']),
                                $this->objSecurity->antiXSS($_POST['pages'])
                                );
				
			$admcfg = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
		
			$admcfg->editChild("/config/site/title", $this->objSecurity->antiXSS($_POST['title']));
			$admcfg->editChild("/config/site/url", $this->objSecurity->antiXSS($_POST['url']));
			$admcfg->editChild("/config/site/description", $this->objSecurity->antiXSS($_POST['description']));
			$admcfg->editChild("/config/site/keywords", $this->objSecurity->antiXSS($_POST['keywords']));
			$admcfg->editChild("/config/site/editor", $this->objSecurity->antiXSS($_POST['editors']));
			
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

    public function uploader() {
        try {
            if(!isset($_FILES['file'])) {
                die("input file not exist");
            }
            $uploader = new Uploader();
            echo $uploader->image(
                $_FILES['file'],
                LOCAL_DIR);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

	public function test_db() {
		
		$this->objModel->test_db_model();
		
	}

	public function get_regs_in_controller($name) {
		
		$regs = $this->objModel->get_regs(name);
		
		return $regs;
		
	}
	

	
}
