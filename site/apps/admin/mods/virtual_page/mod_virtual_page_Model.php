<?php

/**
* Plantilla de la clase appModel para Balero CMS.
* Declare aqui todas las conexiones a la Base de datos.
**/

class mod_virtual_page_Model extends configSettings {
	
	public $db;
	public $editor_headers;
	public $editor;
	public $rows;
	
	public function __construct() {
		
		
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
	
	public function add_page_model($virtual_title, $virtual_content, $a) {

		date_default_timezone_set('UTC');
		
		
		try {

			//$query = $this->db->query("INSERT INTO blog title, message, info VALUES " . $title . $message,
			//"Por " . $this->user . " @ " . date("Y-m-d H:i:s"));
			
			$date = date("Y-m-d");
	
			$query = $this->db->query("INSERT INTO virtual_page (virtual_title, virtual_content, date, active, visible) VALUES ('".$virtual_title."', '".$virtual_content."', '".$date."', '" . $a ."', '1')");
			
		} catch (Exception $e) {
			$e->getMessage();
		}
		
		
	}
	
		
	/**
	 *
	 * Este metodo es importante si queremos que nuestro total de resultados
	 * se muestre en la barra de titulo de modulos, ejemplo.
	 * Blog (17) = El modulo blog tiene 17 registros.
	 * @param unknown_type $mod_name
	 * @return number|unknown
	 */
	
	public function get_regs() {
	
		/**
		 * Obtener numero de registros
		 **/
	
		/**
		 * Consultar tabla y obtener número de registros (colocar nombre de la tabla)
		 **/
	
		$this->db->query("SELECT * FROM virtual_page");
		$regs_result = $this->db->num_rows();
	
		/**
		 * Retornar número real de registros para el titulo de
		 * el módulo de admin.
		 **/
	
		if($regs_result == 0) {
			return 0;
		} else {
			return $regs_result;
		}
	
	
	}
	
	
	public function site_map_model() {
		
		$this->db->query("SELECT * FROM virtual_page");
		$this->db->get();
		$this->rows = $this->db->rows;
		
	}
	
	public function return_virtual_title($id) {

		$this->db->query("SELECT * FROM virtual_page WHERE id='$id'");
		$this->db->get();
		
		foreach ($this->db->rows as $row) {
			$virtual_title = $row['virtual_title'];
		}
		
		unset($this->db->rows);
		return $virtual_title;
	}
	

	public function return_virtual_title_multilang($id, $code) {
	
		/**
		 * Find post multilang by id;code
		 * Ex: 188;en
		 */
		
		try {
			$this->db->query("SELECT * FROM virtual_page_multilang WHERE page_id='".$id.";".$code."'");
			$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
	
			if(empty($this->db->rows)) {
				throw new Exception();
			}
	
			foreach ($this->db->rows as $row) {
					
				if(empty($row['virtual_title'])) {
					throw new Exception();
				}
					
				if($row['id'] == $id && !empty($row['virtual_title'])) {
					return $row['virtual_title'];
				}
			}
	
	
		} catch (Exception $e) {
			return "";
		}
	
		unset($this->db->rows);
	
	}
	
	
	public function return_virtual_content_multilang($id, $code) {
	
	
		/**
		 * Find post multilang by id;code
		 * Ex: 188;en
		 */
		try {
			$this->db->query("SELECT * FROM virtual_page_multilang WHERE page_id='".$id.";".$code."'");
			$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
	
			if(empty($this->db->rows)) {
				throw new Exception();
			}
	
			foreach ($this->db->rows as $row) {
					
				if(empty($row['virtual_content'])) {
					throw new Exception();
				}
					
				if($row['id'] == $id && !empty($row['virtual_content'])) {
					return $row['virtual_content'];
				}
			}
	
	
		} catch (Exception $e) {
			return "";
		}
	
		unset($this->db->rows);
	
	}
	
	public function return_virtual_content($id) {
	
		$query = "SELECT * FROM virtual_page WHERE id='$id'";
		
		$this->db->query($query);
		$this->db->get();
		
		if(!empty($this->db->rows)) {
		foreach ($this->db->rows as $row) {
			$virtual_content = $row['virtual_content'];
		}
		}
	
		return $virtual_content;
		
	}
	
	public function return_value($id) {
	
		$this->db->query("SELECT * FROM virtual_page WHERE id='$id'");
		$this->db->get();
	
		if(count($this->db->rows) == 0)  {
			throw new Exception(_ID_DONT_EXIST);
		}
		
		foreach ($this->db->rows as $row) {
			$active = $row['active'];
		}
	
		return $active;
	
	}
	
	public function delete_page_confirm_model($id) {
		try {
			$this->db->query("DELETE FROM virtual_page WHERE id = '$id'");
			$this->db->query("DELETE FROM virtual_page_multilang WHERE id = '$id'");	
		} catch (Exception $e) {
			throw new Exception(_ERROR_DELETING_PAGE . " " . _ID_DONT_EXIST . $e->getMessage());
		}
		
	}
	
	public function delete_page_multilang_confirm_model($id) {
		try {
			$this->db->query("DELETE FROM virtual_page_multilang WHERE id = '$id'");
		} catch (Exception $e) {
			throw new Exception(_ERROR_DELETING_PAGE . " " . _ID_DONT_EXIST . $e->getMessage());
		}
	
	}
	
	public function update_virtual_content($id, $title, $content, $active) {
		$this->db->query("UPDATE virtual_page SET virtual_title = '$title', virtual_content = '$content', active = '$active' WHERE id = '$id'");	
	}
	
	public function add_page_multilang($id, $title, $message, $active, $code, $id) {
	
		date_default_timezone_set('UTC');
	
		try {
	
			/**
			 * If exist post update row
			 * if not, add new register (post)
			 */
				
			$date = date("Y-m-d");
			$query = $this->db->query("INSERT INTO `virtual_page_multilang` (`page_id`, `virtual_title`, `virtual_content`, `date`, `active`, `visible`, `code`, `id`) 
									VALUES ('".$id.";".$code."', '".$title."', '".$message."', '".$date."', '".$active."', '1', '".$code."', '".$id."')
									ON DUPLICATE KEY UPDATE
									virtual_title = '".$title."',
									virtual_content = '".$message."',
									date = '".$date."'");
	
		} catch (Exception $e) {
			$e->getMessage();
		}
	
		unset($this->db->rows);
	
	}
	
	# Método destructor del objeto
 	public function __destruct() {
 		unset($this);
 	}	
 	
 	
}
	
