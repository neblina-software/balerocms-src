<?php

/**
 *
 * view.php
 * (c) Mar 2, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class installer_View extends configSettings {
	
	
	/**
	 * Variable de contenido $content
	 */

	public $content = "";
	
	public $page;
	
	/**
	 *
	 * Check icon on top
	 */
	
	public $check_db;
	public $check_site;
	public $check_admin;
	
	public $check_icon = "<img src=\"site/apps/installer/html/images/check-icon.png\">";
	
	public function __construct() {
		
		$this->check_db = $this->check_icon;
		$this->check_site = $this->check_icon;
		$this->check_admin = $this->check_icon;
		
		$this->LoadSettings(); //cargar datos XML
		$this->page = _PAGE;
		
	}

	
	/**
	 * Cargar la vista.
	 */
	
	public function Render() {
		
		/**
		 *
		 * @var unknown_type Balero CMS.
		 * Creamos nuestro diccionario desde la vista.
		 * Sintaxis de $array:
		 * 		Primer y último valor sin coma al final:
		 * 		'etiqueta' => 'valor'
		 * 		Los demás valores:
		 * 		'etiqueta' => 'valor',
		 */
		
		$array = array(
				'title'=>$this->title,
				'url'=>$this->url,
				'page'=>$this->page,
				'keywords'=>$this->keywords,
				'description'=>$this->description,
				'content'=>$this->content,
				'virtual_pages'=>'',
				'basepath'=>$this->basepath,
				'langs'=>''
				);
		
		/**
		 * 
		 * Render our page.
		 * Default theme for installer "tundra"
		 * 
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/themes/tundra/main.html");		
		echo $objTheme->renderPage($array);
		
	
	}
	
	
	/**
	 * Metodos
	 */
	
	/**
	 * v.0.5
	 * Pretty URL off on installer app because
	 * there are some incompatibilities in some servers
	 */
	

	public function is_mod_rewrite_enabled() {
		
		if(in_array('mod_rewrite', apache_get_modules())) {
			$msg = new MsgBox(_INSTALLER_MESSAGE_MW_TITLE_OK, _INSTALLER_MESSAGE_MW_MESSAGE_OK, "S");
			$this->content .= $msg->Show();
		} else {
			$msg = new MsgBox(_INSTALLER_MESSAGE_MW_TITLE_ERROR, _INSTALLER_MESSAGE_MW_MESSAGE_ERROR, "E");
			$this->content .= $msg->Show();
		}
		
	}
			
	public function installButton() {

		try {		
	
			if(isset($_POST['submit']) && empty($_POST['passwd'])) {
				throw new Exception();
			}
			
			if(empty($this->pass)) {
				throw new Exception();
			}
			
			$array = array(
					'title' => _INSTALL_TITLE,
					'btn_install' => _INSTALL_BUTTON);
			
			$template = new ThemeLoader(APPS_DIR . "/installer/html/finish_install.html");
			$this->content .= $template->renderPage($array);
	
		} catch(Exception $e) {
			$this->content .= $this->tips_messages();
		}
		
	}
	
		
	/**
	 * vistas de modelo
	 */

	public function progressBar() {
		
		/**
		 * Load basepath for installer
		 */
		
		$array = array(
				'basepath'=>$this->basepath
		);
		
		/**
		 * progress-bar UI
		 */
		
		//echo "progressbar";
		
		$loading = new ThemeLoader(APPS_DIR . "installer/html/UI.html");
		echo $loading->renderPage($array);
		
	}
	
	public function wizard() {
				
		try {
			
			
			if(empty($this->basepath)) {
				$basepath = $this->FullBasepath();
			} else {
				$basepath = $this->basepath;
			}
			
			if(empty($basepath) || empty($this->title) || empty($this->url) || empty($this->description) || empty($this->keywords)) {
				$this->check_site = "";
			}
			
			if(empty($this->user) || empty($this->pass) || empty($this->firstname) || empty($this->lastname) || empty($this->email)) {
				$this->check_admin = "";
			}
						
			
		} catch (Exception $e) {
			

			
		}
				
		
		$array = array(
				
				/**
				 * Objects
				 */
				
				'check_db' => $this->check_db,
				'check_site' => $this->check_site,
				'check_admin' => $this->check_admin,
				
				/**
				 * Labels
				 */
				
				'lbl_dbconfig' => _DB_CONFIG,
				'lbl_dbhost' => _DB_HOST,
				'lbl_dbusername' => _DB_USER,
				'lbl_dbpass' => _DB_PASS,
				'lbl_dbname' => _DB_NAME,
				'lbl_dbname_note' => _DB_IF_NOT_EXIST,
				
				'lbl_siteinfo' => _SITE_INFO,
				'lbl_basepath' => _BASEPATH,
				'lbl_basepath_note' => _NOTE_BASEPATH,
				'lbl_title' => _TITLE,
				'lbl_url' => _URL,
				'lbl_keywords' => _TAGS,
				'lbl_description' => _DESCRIPTION,
				
				'lbl_adminconfig' => _ADMIN_CONFIGURATION,
				'lbl_administrator' => _ADMIN,
				'lbl_pass' => _PASS,
				'lbl_retype' => _RETYPE_PASS,
				'lbl_firstname' => _NAME,
				'lbl_lastname' => _LAST_NAME,
				'lbl_email' => _EMAIL,
				'lbl_newsletter' => _NEWSLETTER,
				
				/**
				 * TextBox
				 */
								
				'txt_dbhost' => $this->dbhost,
				'txt_dbuser' => $this->dbuser,
				'txt_dbpass' => $this->dbpass,
				'txt_dbname' => $this->dbname,
				
				'txt_basepath' => $basepath,
				'txt_title' => $this->title,
				'txt_url' => $this->url,
				'txt_keywords' => $this->keywords,
				'txt_description' => $this->description,
				
				'txt_administrator' => $this->user,
				'txt_pass' => '',
				'txt_retype' => '',
				'txt_firstname' => $this->firstname,
				'txt_lastname' => $this->lastname,
				'txt_email' => $this->email,
				'txt_newsletter' => $this->newsletter,
				
				/**
				 * Buttons
				 */
				
				'btn_save' => _INSTALLER_SAVE
				
						);
		
		$objWizard = new ThemeLoader(APPS_DIR . "installer/html/wizard.html");
		$this->content .= $objWizard->renderPage($array);
		
	}
	
	public function tips_messages() {
			
		$msg = new MsgBox(_NOTE, _INSTALLER_TIP1, "I");
		$this->content .= $msg->Show();
			
	}
	
	public function unknow_database_error() {
			
		$msg = new MsgBox(_DB_DONT_EXIST, _DATABASE_CREATED, "I");
		$this->content .= $msg->Show();
			
	}
	
	public function unknow_database_connect() {
	
		$msg = new MsgBox(_INSTALLER_WARNING, _INSTALLER_WARNING_MESSAGE, "I");
		$this->content .= $msg->Show();
		
	}
	
	public function form_field_error($e) {
	
	$msg = new MsgBox(_ADMIN_CONFIGURATION, _CHECK_FIELDS . $e, "E");
		$this->content .= $msg->Show();
	
	}
	
	public function file_error($e) {
	
		$msg = new MsgBox(_PERMISSIONS_ERROR, _PERMISSIONS_ERROR_MESSAGE . $e, "E");
		$this->content .= $msg->Show();
	
	}

	public function create_db_error($e) {
		
		$msg = new MsgBox(_ERROR_CREATING_DATABASE, _ERROR_CREATING_DATABASE_MESSAGE . " " . $e, "E");
		$this->content .= $msg->Show();
		
	}
	
	public function database_created() {
		
		$msg = new MsgBox(_WARNING, _DATABASE_CREATED, "S");
		$this->content .= $msg->Show();
		
	}
	
	
} // fin clase
