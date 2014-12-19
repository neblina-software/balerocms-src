<?php

/**
 *
 * XMLHandler.php
 * (c) Apr 17, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

/**
 * 
 * Manejador de archivos XML
 * @author lastprophet
 *
 */

class XMLHandler {
	
	public $file;
	public $obj;
	public $children;
	public $child;

	public $array;

	public $value;
	
	public $node;
	
	public $fix;

	public function __construct($file = "") {
		
		$this->file = $file;
		
		if(!file_exists($file)) {
			throw new Exception(get_class($this) . ": No existe el archivo: " . $file);
		}else {
			$this->readXML($file);
		}

	}
	//http://www.phpeveryday.com/articles/PHP-XML-Read-from-a-File-P410.html
	public function readXML($file = "") {
		
		$this->node = array();
		
		
		/**
		 *
		 * Cargar el cfg como objeto XML.
		 */
		
		if(file_exists($file)) {
				//$this->obj = simplexml_load_file("http://localhost/BaleroCMS/site/balero.config.xml");
			$this->obj = simplexml_load_file($file);
		} else {
			throw new Exception(_FILE_DONT_EXIST . " " .$file);
		}
		
		if(!$this->obj) {
			throw new Exception(_WARNING_LOADING_FILE . " " ."<b>" . $file . "</b>");
		}
			
	}
	

	public function Child($child, $subchild) {
		
		if($this->obj) {

			//$this->array = array();
			$_value = 0;

			foreach($this->obj->$child as $key => $value) {
				//echo "<br>array->key: " . $key . " valor: " . $value->$subchild . "<br>";
				$_value = $value->$subchild;
				if($_value == "_blank") {
					$_value = "";
				}
			}
		} else {
			throw new Exception(_XML_ERROR_CHILD);
		}

		return $_value;

		
	}
	
	/**
	 * Como usar:
	 * editChild($PATH)
	 * Eje: <config>
	 * 			<database>
	 * 				<dbuser>root</dbuser>
	 * 			</database>
	 * 		</config>
	 * 	$PATH = "/config/database/dbuser"
	 */
	
	public function editChild($path, $value) {
		
		//if(file_exists($this->file)) {
			//$this->obj = simplexml_load_file("http://localhost/BaleroCMS/site/balero.config.xml");
			//$this->obj = simplexml_load_file($this->file);
			
			
			//$node = $this->obj->xpath($path);
			
		//var_dump($value);	
		
		try {
				
			$this->node = $this->obj->xpath($path);
			
			//if(empty($value)) {
			if(empty($value)) {
				$this->node[0][0] = "_blank";
			} else {
				//$this->node[0][0] = $value;
				//$this->node[0][0] = $this->value;
				$this->node[0][0] = htmlspecialchars($value);
				//echo "valor: ". $value . " ";
			}
			
			$this->obj->asXML($this->file);
			
		} catch (Exception $e) {
			
		}	
		
	}
	
	
 	public function __destruct() {
 		unset($this);
 	}
	

}
