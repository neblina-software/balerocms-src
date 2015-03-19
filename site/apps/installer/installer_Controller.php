<?php

/**
 *
 * installer_Controller.php
 * (c) Mar 2, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class installer_Controller {
	
	public $objModel;
	public $objView;
		
	private $cfgFile;
	
	private $objConfig;
	
	/**
	 *
	 * Check icon on top
	 */
	
	public function __construct() {
				
		/*
		 * Load balero.config.xml config file
		 */
		
		$this->cfgFile = LOCAL_DIR . "/site/etc/balero.config.xml";
		
		try {
			// Iniciar vista
			$this->objView = new installer_View();
			$this->objModel = new installer_Model();
						
			$this->objView->installButton();
		} catch (Exception $e) {
			$this->objView = new installer_View();
			
			if(!is_writable($this->cfgFile)) {
				$MsgBox = new MsgBox(_ERROR, _CHMOD_ERROR);
				$this->objView->content .= $MsgBox->Show();
			}
			
			if(strpos($e->getMessage(), _UNKNOW_DATABASE)) {
				$this->objView->unknow_database_error();
				$this->objModel->createDB();
				$this->objView->check_db = "";
			} else {
				$this->objView->unknow_database_connect();
				$this->objView->check_db = "";
			}
			
		}
		
		$this->objConfig = new configSettings();
		$this->initBasePath();
		
		$handler = new ControllerHandler($this);

	
	}
		
	/**
	 * Methods
	 */
		
	/**
	 * Set basepath for first time
	 */
	
	public function initBasePath() {
		
		/**
		 * Read basepath value
		 */
		
		$basepath = $this->objConfig->basepath;
		
		
		if(empty($basepath)) {
		
			/**
		 	* Write basepath field
		 	*/
		
			$cfg = new XMLHandler($this->cfgFile);
			$cfg->editChild("/config/site/basepath", $this->objConfig->FullBasepath());
		
		}
		
	}
	
	/**
	 * First block
	 */
	
	public function formDBInfo() {
				
		if(isset($_POST['submit'])) {	
		
			try {
				
			$cfg = new XMLHandler($this->cfgFile);
		
			/**
			* 
			* editChild(PATH)
			* Eje: <config>
			* 			<database>
			* 				<dbuser>root</dbuser>
			* 			</database>
			* 		</config>
			* 	PATH = "/config/database/dbuser"
			*/
		
			$cfg->editChild("/config/database/dbhost", $_POST['dbhost']);
			$cfg->editChild("/config/database/dbuser", $_POST['dbuser']);
			$cfg->editChild("/config/database/dbpass", $_POST['dbpass']);
			$cfg->editChild("/config/database/dbname", $_POST['dbname']);
		
			$cfg->editChild("/config/system/firsttime", "no");

			//http://www.orenyagev.com/application-configuration-and-php
			
			$this->objView->check_db = $this->objView->check_icon;
			
			$cfg->__destruct();
				unset($cfg);
			
			} catch (Exception $e) {
				$this->objView->check_db = "";
				$this->objView->file_error($e->getMessage());
			}
		}
		
		header("Location: index.php");

	}
	
	
	/**
	 * Second block 
	 */
	
	public function formSiteInfo() {
					
		//echo "formsiteinfo";
				
		try {
								
			if(isset($_POST['submit'])) {
				
				//echo "edit";
				
				$admcfg = new XMLHandler($this->cfgFile);
		
				$admcfg->editChild("/config/site/title", $_POST['title']);
				$admcfg->editChild("/config/site/url", $_POST['url']);
				$admcfg->editChild("/config/site/description", $_POST['description']);
				$admcfg->editChild("/config/site/keywords", $_POST['keywords']);
				$admcfg->editChild("/config/site/basepath", $_POST['basepath']);	
							
			}
						
		} catch (Exception $e) {
			
			
		}
	
		header("Location: index.php");
	
	}
	
	public function formadminInfo() {
	
		if(isset($_POST['submit'])) {
		try {
			
			$admcfg = new XMLHandler($this->cfgFile);
			
			if(empty($_POST['username'])) {
				throw new Exception(_EMPTY_USERNAME);
			}
			if(empty($_POST['passwd'])) {
				throw new Exception(_EMPTY_PASSWORD);
			}
			if($_POST['passwd'] != $_POST['passwd2']) {
				throw new Exception(_PASSWORDS_DONT_MATCH);
			}
			
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				throw new Exception(_INDALID_EMAIL);
			}
						
			$admcfg->editChild("/config/admin/firstname", $_POST['firstname']);
			$admcfg->editChild("/config/admin/lastname", $_POST['lastname']);
			$admcfg->editChild("/config/admin/newsletter", $_POST['newsletter']);
			$admcfg->editChild("/config/admin/username", $_POST['username']);
			$admcfg->editChild("/config/admin/email", $_POST['email']);
				
			$obj = new Blowfish(); // crear objeto
			$pwd = $obj->genpwd($_POST['passwd']); // generar passwd encriptado
			$admcfg->editChild("/config/admin/passwd", $pwd);
			
			header("Location: index.php");
			
		} catch (Exception $e) {
			$this->objView->check_admin = "";
			$this->objView->form_field_error($e->getMessage());
			$this->main();
		}
					
		} else {
			header("Location: index.php");
		}
		
		
		
	}
		
	public function main() {
				
		// no hay modelo
		// form db settings
		//$this->objView->formDBInfo();
		// form site info
		//$this->objView->formSiteInfo();
		// form admin settings
		//$this->objView->formadminInfo();

		$this->objView->is_mod_rewrite_enabled();
		$this->objView->wizard();
		// renderizar plantilla
		$this->objView->Render(); // renderizar pagina
		
	}
	
	public function progressBar() {
		
		// vista

		if(isset($_POST['submit']) && (!preg_match("/_blank/", $this->objView->pass))) {
			
			try {
				
				//if(isset($_POST['newsletter'])) {
					mail("support@balerocms.com", 'newsletter e-mail', "New suscriber: " . $this->objView->email);
				//}
				
				$this->objView->progressBar();
				$this->objModel->install();
				
			} catch (Exception $e) {
				
			}
			
		} else {
			
			/**
			 * Dynamic index.php?app=installer
			 */
			
			header("Location: index.php?app=installer");
		}
		
	}
	
	public function validate($field) {
		if(empty($field)) {
			throw new Exception(_EMPTY_FIELD . " " . $field);
			return false;
		}
		return true;
	}
	
	public function test_app() {
		
		$this->objView->content .= "prueba";
		//$this->main();
		$this->objView->Render();
	}
	
}
