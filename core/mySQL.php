<?php

/**
 *
 * mySQL.php
 * (c) Feb 26, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

/**
 * 
 * @author lastprophet
 * ------------------------------------
 * MySQL / MariaDB Class (19-oct-2013)
 * ------------------------------------
 * Clase MySQLi compatible con salida para la "Vista".
 * Implementada en Balero CMS.
 */

/**
 * 
 * Excepciones.
 *
 */

class errorConnection extends Exception { }

/**
 * 
 * Fin de excepciones.
 *
 */

class mySQL {
	
	/**
	 * 
	 * Valores para base de datos.
	 */
	
	private $host;
	private $user;
	private $pass;
	private $db;
	
	/**
	 * Nueva conexión
	 */
	
	private $conn;
	
	/**
	 * Ejecutar query
	 */
	
	public $result;
	
	/**
	 * 
	 * Almacenar mensajes de errores.
	 */
	
	public $error = FALSE;
	
	
	/**
	 * 
	 * Almacenamos los resultados en un array llamado rows[]. 
	 */
	
	public $rows;
	public $row;
	
	/**
	 * 
	 * Connection status
	 */
	
	public $status = FALSE;
	
	/**
	 * Método constructor
	 **/
	
	public function __construct($host = "", $user = "", $pass = "", $db = "") {
			
			/**
			* 
			* Conectamos a la base de datos.
			*/
		
			try {
				$this->conn = new mysqli($host, $user, $pass, $db);
				$this->status = TRUE;
				if(mysqli_connect_errno()) {
					$this->status = FALSE;
					throw new errorConnection(get_class($this) . ": " . _DB_ERROR . " . " . mysqli_connect_error());
				}
			} catch (errorConnection $e) {
				//$e->getMessage();
				throw new Exception($e->getMessage());
			}
			
	}
		

	/**
	 * 
	 * @param $query Sentencia SQL. Ejemplo: "SELECT id,user,name FROM users"
	 */
	
	public function query($query) {
		
		/**
		 * 
		 * Ejecutar consulta.
		 */
		
		try {
			
		$this->result = $this->conn->query($query);
		
			if(!$this->result) {
				throw new Exception("MYSQL: SYNTAX QUERY ERROR: " . $query);
			}
			
		} catch(Exception $e) {
			
			/**
			 * Siempre hacemos ésta acción para atrapar el error.
			 */
			
			throw new Exception($e->getMessage());
			
		}
		
		
	}	
	
	
	/**
	 * Ciclamos al asignar un valor al array.
	 */
	
	public function get() {
		
		/**
		 * @get() Almacena los resultados de la query en un array
		 * en este caso $this->rows[]
		 */
		
		try {

			if (!$this->result) {
				throw new Exception(_QUERY_ERROR);
			}
				
			/**
			 * Almacenamos los resultados MySQLi en un array ($this->rows[])
			 * Para poder exportarlo a la vista.
			 */
			
			while($row = $this->result->fetch_array()) {
				$this->rows[] = $row;
			}

//				recorrer datos almacenados en $rows[]
//				lo hacemos desde la vista:			
   			//foreach ($this->rows as $row) {
   				//echo $row['id'] . $row['title'];
   			//}

			
		} catch (Exception $e) {
			$e->getMessage();
		}
		
	}


	/** Regresa el numero total de registros de una $query **/

	public function num_rows() {


		/**
		* Obtener el numero total de registros ejemplo: en (modelo)
		* $this->db->query("SELECT * FROM blog");
		* echo $this->db->num_rows();
		**/

		$num_rows = $this->result->num_rows;

		return $num_rows;

	}
	
	
	/**
	 * Crear tablas en la base de datos.
	 * @param string $name Nombre de la tabla.
	 */
	
	public function create($query) {
		try {

		

			$table = mysqli_multi_query($this->conn, $query);
			
				if(!$table) {
					throw new Exception(_CREATE_TABLE_ERROR . $query);
				}
			
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
	}
		
	
	public function queryArray() {
		return $this->rows;
	}
	
	/**
	 * Retornamos el error en un método.
	 */
	
	public function mySQLError() {
		return $this->error;
	}
	
	/**
	 * Cerrar conexión.
	 */
	
	public function __destruct() {
		
		/**
		 * Liberar Y cerrar consulta.
		 */
		
		$this->conn->close();
		
	}

}
