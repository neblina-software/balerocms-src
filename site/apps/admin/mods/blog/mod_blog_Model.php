<?php

/**
* Plantilla de la clase appModel para Balero CMS.
* Declare aqui todas las conexiones a la Base de datos.
**/

class mod_blog_Model extends configSettings {
	
	public $db;
	public $editor_headers;
	public $editor;
	public $rows;
	
	/**
	 * 
	 * Multilang post message content
	 */
	
	public $message;
		
	/**
	 *
	 * Multilang post title content
	 */
	
	public $title;
	
	public function __construct() {
		
		$this->tabla_name = "blog"; // SELECT * FROM "blog"
		
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
	
	public function add_post($title, $message) {
				
		date_default_timezone_set('UTC');
		
		try {

			//$query = $this->db->query("INSERT INTO blog title, message, info VALUES " . $title . $message,
			//"Por " . $this->user . " @ " . date("Y-m-d H:i:s"));
			
			$date = $this->user . " @ " . date("Y-m-d H:i:s");
	
			$query = $this->db->query("INSERT INTO blog (title, message, info) VALUES ('".$title."', '".$message."', '".$date."')");
				
		} catch (Exception $e) {
			$e->getMessage();
		}
		
		unset($this->db->rows);
		
	}
	
	public function add_post_multilang($post_id, $title, $message, $code, $id) {
	
		date_default_timezone_set('UTC');
	
		try {

			/**
			 * If exist post update row
			 * if not, add new register (post)
			 */
			
			$date = $this->user . " @ " . date("Y-m-d H:i:s");
			$query = $this->db->query("INSERT INTO blog_multilang (post_id, title, message, info, code, id) 
									VALUES ('".$post_id.";".$code."', '".$title."', '".$message."', '".$date."', '".$code."', '".$id."')
									ON DUPLICATE KEY UPDATE 
									title = '".$title."', 
									message = '".$message."',
									info = '".$date."'");
						
		} catch (Exception $e) {
			$e->getMessage();
		}
	
		unset($this->db->rows);
	
	}
	
	public function get_post($min, $limit) {
				
		
			$this->db->query("SELECT * FROM blog ORDER BY id DESC LIMIT $min, $limit");
			$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
			
			if(empty($this->db->rows)) {
				$this->rows = array();
			} else {
				$this->rows = $this->db->rows;
			}
			
			
			//				recorrer datos almacenados en $rows[]
			//				lo hacemos desde la vista:
			//foreach ($this->db->rows as $row) {
				//$this->content .= $row['id'] . $row['title'];
			//}
			
			unset($this->db->rows);
		
	}
	
	public function return_post_content($id) {
	
	
		$this->db->query("SELECT * FROM blog WHERE id='$id'");
		$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
			
		$this->rows = $this->db->rows;

		$post_content = "";
			
		//				recorrer datos almacenados en $rows[]
		//				lo hacemos desde la vista:
		foreach ($this->db->rows as $row) {
			$post_content = $row['message'];
		}
		
		unset($this->db->rows);
		return $post_content;
		
	
	}
	
	public function return_post_title($id) {
	
	if(empty($id)) {
		die();
	}
		
		$this->db->query("SELECT * FROM blog WHERE id='$id'");
		$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
			
		$this->rows = $this->db->rows;
	
		$post_title = "";
			
		
		
		
		//				recorrer datos almacenados en $rows[]
		//				lo hacemos desde la vista:
		
		if(count($this->db->rows) == 0) {
			die(_ID_DONT_EXIST);
		}
		
		foreach ($this->db->rows as $row) {
			$post_title = $row['title'];
		}
		
		return $post_title;
		unset($this->db->rows);
	
	}
	
	/**
	 * 
	 * Multilang
	 */
	
	public function return_post_content_multilang($id, $code) {
	
		
		/**
		 * Find post multilang by id;code
		 * Ex: 188;en
		 */
		try {
		$this->db->query("SELECT * FROM blog_multilang WHERE post_id='".$id.";".$code."'");
		$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
		
		/**
		 * No results
		 */
		
		if(empty($this->db->rows)) {
			throw new Exception();
		}
		
		foreach ($this->db->rows as $row) {
			
			if(empty($row['message'])) {
				throw new Exception();
			}
			
			if($row['id'] == $id && !empty($row['message'])) {
				$this->message = $row['message'];
			}
		}
		
		} catch (Exception $e) {
			$this->message = "";
		}
	

		unset($this->db->rows);
		
	}
	
	public function return_post_title_multilang($id, $code) {
	
		
		/**
		 * Find post multilang by id;code
		 * Ex: 188;en
		 */
		try {
		$this->db->query("SELECT * FROM blog_multilang WHERE post_id='".$id.";".$code."'");
		$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
		
		if(empty($this->db->rows)) {
			throw new Exception();
		}
		
		foreach ($this->db->rows as $row) {
			
			if(empty($row['title'])) {
				throw new Exception();
			}
			
			if($row['id'] == $id && !empty($row['title'])) {
				$this->title = $row['title'];
			}
		}
		
		
		} catch (Exception $e) {
			$this->title = "";
		}
	
		unset($this->db->rows);
	
	}
	
	public function delete_query($id) {
		$this->db->query("DELETE FROM blog WHERE id='$id'");
		$this->db->query("DELETE FROM blog_multilang WHERE id='$id'");
		unset($this->db->rows);
	}
	
	public function delete_query_multilang($id, $code) {
		$this->db->query("DELETE FROM blog_multilang WHERE id='$id' AND code='$code'");
		unset($this->db->rows);
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
	
		$this->db->query("SELECT * FROM blog");
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
		
		unset($this->db->rows);
	
	
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
	
 	public function edit_post($id, $title, $content) {
 		$this->db->query("UPDATE blog SET title = '$title', message = '$content' WHERE id= '$id'");
 	}
 	
 	public function total_rows() {
 		$this->db->query("SELECT * FROM blog");
 		$this->db->get();
 		$total = $this->db->num_rows();
 		
 		unset($this->db->rows);
 		return $total;
 	}

 	# Método destructor del objeto
 	public function __destruct() {
 		unset($this);
 	}
 	
 	
}
	
