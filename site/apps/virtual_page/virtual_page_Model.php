<?php

/**
* Plantilla de la clase appModel para Balero CMS.
* Declare aqui todas las conexiones a la Base de datos.
**/

/**
 * Multi-Language Fixes
 */

class virtual_page_Model extends configSettings {
	
	/**
	* Variables globales
	**/
	
	public $result;
	public $db;

	public $dbhost;
	public $dbuser;
	public $dbpass;
	public $dbname;
	
	public $rows; // pasar variable a vista
	
	public $lang;

	/**
	* Conectar a la base de datos en el constructor.
	**/
	
	public function __construct() { 
		
		$this->LoadSettings();
		
		try {
			$this->db = new mySQL($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		
	}
	
	
	public function theme() {
		
		$admin_god = 1;
		
		$this->db->query("SELECT * FROM custom_settings WHERE id = '$admin_god'");
		$this->db->get();
		
		foreach ($this->db->rows as $row) {
			$theme = $row['theme'];
		}
		
		/**
		 * Siempre (siempre) debemos de matar la variable $rows despues de una consulta,
		 * para limpiar los datos y esten limpios en la siguiente consulta.
		 */
		
		unset($this->db->rows);
		return $theme;
		
	}
	
	/**
	 * Obtener solo una pagina especifica
	 * @return array
	 */
	
	public function get_virtual_page_by_id($id) {

			if(empty($this->lang) || $this->lang == "main") {
				$virtual_pages = array();
				$this->db->query("SELECT * FROM virtual_page WHERE id = '$id'");
				$this->db->get();
				$virtual_pages = $this->db->rows;
			} else {
				$virtual_pages = array();
				$this->db->query("SELECT * FROM virtual_page_multilang WHERE id = '$id' AND code = '".$this->lang."'");
				$this->db->get();
				$virtual_pages = $this->db->rows;
			}
			
		unset($this->db->rows);
		return $virtual_pages;
	
	}
	
	/**
	 * Obtener todas las paginas virtuales
	 * @return array
	 */
	
			
	public function get_virtual_pages() {
		
		$virtual_pages = array();
		
		if(empty($this->lang) || $this->lang == "main") {
			
			/**
			 * Get Virtual Pages
			 */
			
			$this->db->query("SELECT * FROM virtual_page WHERE active = '1'");
			$this->db->get();
		
			if(empty($this->db->rows)) {
				$virtual_pages = "";
			} else {
				$virtual_pages = $this->db->rows;
			}
		
			unset($this->db->rows);
			return $virtual_pages;
			
		} else {
			
			/**
			 * Get Multi-Lang Virtual Pages
			 */
			
			$this->db->query("SELECT * FROM virtual_page_multilang WHERE code = '$this->lang'");
			$this->db->get();
				
			if(empty($this->db->rows)) {
				$virtual_pages = "";
			} else {
				$virtual_pages = $this->db->rows;
			}
				
			unset($this->db->rows);
			return $virtual_pages;
		}
		
	}

	
	/**
	* Metodos
	**/
	
	public function getLangList() {
		$array = array();
		$this->db->query("SELECT * FROM languages");
		$this->db->get();
	
		//print_r($this->db->rows);
	
		
		try {
			
			if(empty($this->db->rows)) {
				throw new Exception();
			}
			
			foreach ($this->db->rows as $row) {
				$array[] = $row['code'];
			}
			
		} catch (Exception $e) {
			
			/**
			 * Np actions
			 */
			
		}
	
		unset($this->db->rows);
		return $array;
	}
	
	/**
	 * 
	 * @return default language 
	 */
	
	public function getLang() {
		
		$defaultLang = "";
		
		$this->db->query("SELECT * FROM cookie WHERE name = '".$_SERVER['REMOTE_ADDR']."'");
		$this->db->get();
		
		try {
			
			if(empty($this->db->rows) || empty($defaultLang)) {
				throw new Exception();
			}
			
			foreach ($this->db->rows as $row) {
				$defaultLang = $row['value'];
				//echo $defaultLang;
			}
			
		} catch (Exception $e) {
			
			/**
			 * NULL
			 */
			
			$defaultLang = "main";
			
		}
		
		unset($this->db->rows);
		return $defaultLang;
		
	}
	
	public function total_pages() {
		
		$rows = array();
		
		if(empty($this->lang) || $this->lang == "main") {
			$this->db->query("SELECT * FROM virtual_page WHERE active = '1'");
			$this->db->get();
			$rows = $this->db->num_rows();
			unset($this->db->rows);
			return $rows;
		} else {
			$this->db->query("SELECT * FROM virtual_page_multilang WHERE code = '$this->lang'");
			$this->db->get();
			$rows = $this->db->num_rows();
			unset($this->db->rows);
			return $rows;
		}
		
	}
	
	public function limit() {
	
		$admin_god = 1;
	
		$this->db->query("SELECT * FROM custom_settings WHERE id = '$admin_god'");
		$this->db->get();
	
		foreach ($this->db->rows as $row) {
			$limit = $row['pagination'];
		}
	
		/**
		 * Siempre (siempre) debemos de matar la variable $rows despues de una consulta,
		 * para limpiar los datos y esten limpios en la siguiente consulta.
		 */
	
		unset($this->db->rows);
		return $limit;
	
	}
	
	# Método destructor del objeto
 	public function __destruct() {
 		unset($this);
 	}
	
	
	
}
