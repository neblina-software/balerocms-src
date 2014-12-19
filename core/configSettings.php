<?php

/**
 *
 * configSettings.php
 * (c) May 26, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

/**
 * Obtener datos de config en XML
 */

class configSettings {

	/**
	 *
	 * Propiedades XML
	 */
	
	/**
	 * DB test
	 */
	
	public $dbhost;
	public $dbuser;
	public $dbpass;
	public $dbname;
	
	/**
	 *
	 * Banderas / Flags
	 */
	
	public $firsttime;
	
	/**
	 *
	 * Datos del admin
	 */
	
	public $user;
	public $pass;
	
	public $email;
	
	public $firstname;
	public $lastname;
	
	/**
	 * 
	 * Site Info vars
	 */
	
	public $title;
	public $description;
	public $url;
	public $keywords;
	
	
	/**
	 * Newsletter
	 */
	
	public $newsletter;
	
	/**
	 * Multilanguage
	 */
	
	public $multilang;
	
	/**
	 *  Baepath
	 */
	
	public $basepath;
	
	/**
	 * Default editor
	 */
	
	public $editor;
	
	public function __construct() {
				
		$this->LoadSettings(); // Cargar datos XML
		
	}
	
	/**
	 * 
	 * @function LoadSettings() Forzar la carga de variables de configuración.
	 * 
	 */
	
	public function LoadSettings() {
	
		/**
		 *
		 * Leer el archivo de configuracion XML y almacenarlo en una variable.
		 * <nodo>
		 *    <subnodo>
		 * Ejemplo: Child(""nodo,"subnodo");
		 *
		 */
	
		//echo LOCAL_DIR . "/site/etc/balero.config.xml";
	
		try {
			
			/**
			 * Loading vars from XML
			 */
			
			$xml = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
	
			$this->dbhost = $xml->Child("database", "dbhost");
			$this->dbhost = $xml->Child("database", "dbhost");
			$this->dbuser = $xml->Child("database", "dbuser");
			$this->dbpass = $xml->Child("database", "dbpass");
			$this->dbname = $xml->Child("database", "dbname");
	
			$this->firsttime = $xml->Child("system", "firsttime");
	
			$this->user = $xml->Child("admin", "username");
			$this->pass = $xml->Child("admin", "passwd");
			$this->firstname = $xml->Child("admin", "firstname");
			$this->lastname = $xml->Child("admin", "lastname");
			$this->email = $xml->Child("admin", "email");
	
			$this->newsletter = $xml->Child("admin", "newsletter");
				
			$this->title = $xml->Child("site", "title");
			$this->url = $xml->Child("site", "url");
			$this->description = $xml->Child("site", "description");
			$this->keywords = $xml->Child("site", "keywords");
			
			/**
			 * Loading Basepath var
			 */
			
			$this->basepath = $xml->Child("site", "basepath");
			
			/**
			 * Multilanguage
			 */
			
			$this->multilang = $xml->Child("site", "multilang");
			
			/**
			 * Default editor
			 */
			
			$this->editor = $xml->Child("site", "editor");
	
		} catch (Exception $e) {
			$title = "ERROR IN CLASS: " . get_class($this);
			$test = new MsgBox($title, $e->getMessage());
			$this->content .= $test->Show();
		}
	
	}

	/**
	 * Get Full Basepath
	 */
	
	public function FullBasepath() {
	
		/**
		 * Based on: http://stackoverflow.com/questions/6768793/php-get-the-full-url
		 */
	
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		$uri = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
		$segments = explode('?', $uri, 2);
		$url = str_replace("index.php", "", $segments[0]);
	
		return $url;
	
	}

}