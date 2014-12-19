<?php

/**
 *
 * Security.php
 * (c) Apr 5, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class Security {
	
	private $var;

	
	/**
	 * 
	 * We need HTML tags like '<' or '>'
	 * but not the javascript tags
	 * 
	 */
	
	public function noJS($var) {
		$script_str = $var;
		
		$search_arr = array("<script>",
						"</script>".
						"@<script[^>]*?>.*?</script>@si",  // Strip out javascript
               			"@<[\/\!]*?[^<>]*?>@si",            // Strip out HTML tags
               			"@<style[^>]*?>.*?</style>@siU",    // Strip style tags properly
               			"@<![\s\S]*?--[ \t\n\r]*>@",         // Strip multi-line comments including CDATA 
						"js:",
						"javascript:",
						"/\(.*\)/",
						"alert",
						"document.cookie",
						);
		
		$script_str = str_ireplace($search_arr, "", $script_str);
		
		/**
		 * Character escape for injections
		 * http://www.ascii.cl/htmlcodes.htm
		 */
		
		$script_str = str_replace("'", "&#39;", $script_str);
		
		
		return $script_str;
		
	}
	
	/**
	 * Blindar variable
	**/
	
	public function shield($var = "") {

		$this->var = $var;
		
		/**
		 * Nivel de protección.
		 */
		
		$this->Level1($this->var);
		$this->Level2($this->var);
		$this->Level3($this->var);

		return $this->fix();
		
	}

	public function fix() {
		return $this->__toString();
	}
	
	public function Level1($str) {
		$this->var = htmlspecialchars($str);
		//$this->var = $str;
		return $this->var;
	}
	
	public function Level2($str) {
		
		$this->var = $str;
		
		/**
		 * 
		 * Remover caracteres potencialmente peligrosos
		 */
		
		$array = array("<script>",
						"</script>".
						"@<script[^>]*?>.*?</script>@si",  // Strip out javascript
               			"@<[\/\!]*?[^<>]*?>@si",            // Strip out HTML tags
               			"@<style[^>]*?>.*?</style>@siU",    // Strip style tags properly
               			"@<![\s\S]*?--[ \t\n\r]*>@",         // Strip multi-line comments including CDATA
						"js:",
						"javascript:",
						"/\(.*\)/",
						"alert",
						"document.cookie",
						"%20");
		
		$this->var = str_replace($array, "", $this->var);
		
		return $this->var;
	}
	
	public function Level3($str) {
		$this->var = str_replace("document.cookie", "", $str);
		$this->var = str_replace("alert(.*)", "", $str);
		return $this->var;
	}
	
	public function __toString() {
		return (string)$this->var;
	}
	
	public function __destruct() {
		unset ($this->var);
	}
	
}
