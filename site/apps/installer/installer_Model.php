<?php

/**
 *
 * model.php
 * (c) Feb 26, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class installer_Model {
	
	public $result;
	public $db;
	
	public $error;
	public $rows;
	
	public $mensaje;
	
	public $error_prueba;

	public $dbhost;
	public $dbuser;
	public $dbpass;
	public $dbname;
	
	public $status;
	
	public function __construct() { 
		
		
		$xml = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
		
		$this->dbhost = $xml->Child("database", "dbhost");
		$this->dbuser = $xml->Child("database", "dbuser");
		$this->dbpass = $xml->Child("database", "dbpass");
		$this->dbname = $xml->Child("database", "dbname");
		
		try {
			
			$this->db = new mySQL($this->dbhost, $this->dbuser, $this->dbpass);
			
			/**
			 * 
			 * DB User and PW are correct but dbname doesn't exist create new one
			 * 
			 */
			
			if($this->db->status == TRUE) {
				
				 $this->db->query("CREATE DATABASE IF NOT EXISTS " . $this->dbname . ";");
			
				 
				/**
				 * Re-connect and connect to the database
				 */
				
				$this->db->__destruct();
				$this->db = new mySQL($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
			
			} else {
				
				throw new Exception();
				
			}
			
		} catch(Exception $e) {
			
			throw new Exception($e->getMessage());
			
		}
		
	}
	
	
	public function test() {
	
			try {
				$testDB = new mySQL($this->dbpass, $this->dbuser, $this->dbpass, $this->dbname);
				
				if(mysqli_errno()) {
					throw new Exception(mysql_errno());
				}
				
			} catch (Exception $e) {
				throw  new Exception($e->getMessage());
			}
			
			return $e->getMessage();
	
	}
	
	public function install() {
		// obtener las sentencias sql de el archivo tablas.sql
		$query = file_get_contents(APPS_DIR . "installer/sql/tables.sql");
		$query = str_replace("{dbname}", $this->dbname, $query);
		$this->db->create($query);
		
		$xml = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
		$xml->editChild("/config/system/installed", "yes");
	}
	
	public function createDB() {
		die("error");
		//$this->db->query("CREATE DATABASE IF NOT EXISTS ".$this->dbname.";");
	}
	
	# Método destructor del objeto
 	public function __destruct() {
 		unset($this);
 	}
	
	
	
}
