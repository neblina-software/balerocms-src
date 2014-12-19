<?php

/**
* Plantilla de la clase appModel para Balero CMS.
* Declare aqui todas las conexiones a la Base de datos.
**/

class admin_Model extends configSettings {
	
	/**
	* Variables globales
	**/
	
	public $result;
	public $db;

	public $regs;

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
		

	/**
	* Metodos
	**/

	public function get_default_theme() {
		try {
			$this->db->query("SELECT * FROM custom_settings WHERE id=1");
			$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
				
			//				recorrer datos almacenados en $rows[]
			//				lo hacemos desde la vista:
			foreach ($this->db->rows as $row) {
				$theme = $row['theme'];
			}
			
			
		} catch (Exception $e) {
			$theme = $e->getMessage();
		}
		
		/**
		 * clean db->rows before return 
		 */
		
		unset($this->db->rows);
		return $theme;
	}
	
	// URL AMIGABLE EN PROXIMA VERSION
// 	public function get_url_friendly_status() {
// 		try {
// 			$this->db->query("SELECT * FROM custom_settings WHERE id=1");
// 			$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
		
// 			//				recorrer datos almacenados en $rows[]
// 			//				lo hacemos desde la vista:
// 			foreach ($this->db->rows as $row) {
// 				$url_friendly = $row['url_friendly'];
// 			}
				
				
// 		} catch (Exception $e) {
// 			$url_friendly = $e->getMessage();
// 		}
		
// 		return $url_friendly;
// 	}
	
	public function get_pagination() {
		try {
			$this->db->query("SELECT * FROM custom_settings WHERE id=1");
			$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
		
			//				recorrer datos almacenados en $rows[]
			//				lo hacemos desde la vista:
			foreach ($this->db->rows as $row) {
				$pages = $row['pagination'];
			}
		
		
		} catch (Exception $e) {
			$pages = $e->getMessage();
		}
		
		
		/**
		 * clean db->rows before return 
		 */
		
		unset($this->db->rows);
		return $pages;
	}
	
	// url firnedly prox. versiones
	//public function save_custom_settings($theme, $url_friendly, $pagination) {
	public function save_custom_settings($theme, $pagination) {
		
		try {
			$this->db->query("UPDATE custom_settings SET 
					theme = '$theme', 
					"//url_friendly = '$url_friendly',
					."pagination = '$pagination' 
					WHERE id='1'");
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
	}
	
	/**
	 * Prueba de como hacer una query en la base de datos.
	 */
	
	public function test_db_model() {
		echo "test db";
		
		//$this->db->query("INSERT INTO blog (title, message, info) VALUES ('Glenn', 'Quagmire', 33)");

		$this->db->query("INSERT INTO blog (title, message, info) VALUES ('prueba10', 'prueba9', 'prueba9')");
		
		
	}

	
	/**
	 * Delete old virtual cookies
	 */
	
	public function deleteExpired() {
		
		date_default_timezone_set('UTC');
		//$delete = date("Y-m-d", strtotime("+1 day"));
		$delete = date("Y-m-d");
		//echo $delete;
		$this->db->query("DELETE FROM cookie WHERE expire < '".$delete."'");
	}
	
	# Método destructor del objeto
 	public function __destruct() {
 		unset($this);
 	}
	
	
	
}
