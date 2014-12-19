<?php

/**
 * 
 * @author lastprophet
 *
 */

class mod_languages_Model extends configSettings {
	
	public $db;
	public $editor_headers;
	public $editor;
	public $rows;
	
		
	public function __construct() {
		
		$this->tabla_name = "languages"; // SELECT * FROM 
		
		/**
		 * Heredar datos XML, pero como no podemos heredar lo que hay dentro de un
		 * constructor (extends), forzamos la cargar con $this->LoadSettings()
		 * 
		 */
		
		$this->LoadSettings();
		
		try {
			$this->db = new mySQL($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
		
	}
	

	/**
	* Metodos
	**/
	
	/**
	 * get language list
	 * @return string
	 */
	
	public function get_lenguages() {
	
		$lang_array = array();
	
		try {
			$this->db->query("SELECT * FROM languages");
			$this->db->get();
					
			$lang_array = $this->db->rows;
			
			if(empty($this->db->rows)) {
				throw new Exception();
			}
				
		} catch (Exception $e) {
			//$lang_array = _NO_LANGUAGES;
		}
	
			
		/**
		 * clean db->rows before return
		 */
	
		unset($this->db->rows);
		return $lang_array;
	
	}
	
	
	public function add_language($label, $code) {
		try {
			$this->db->query("INSERT INTO languages (label, code) VALUES ('$label', '$code')");
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public function delete_language($code) {
		try {
			$this->db->query("DELETE FROM languages WHERE code='$code'");
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	

	public function get_regs() {
		
		if($this->multilang == "yes") {
			return "ON"; // nothing to show
		} else {
			return "OFF"; // nothing to show
		}
		
	}
	
 	# Método destructor del objeto
 	public function __destruct() {
 		unset($this);
 	}
 	
 	
}
	
