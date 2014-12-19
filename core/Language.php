<?php

/**
 *
 * Language.php
 * (c) Jul 7, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class Language {
	
	/**
	 * 
	 * default lang from this class
	 */
	
	public $defaultLang;
	
	
	/**
	 * Default language files
	 */
	
	public $defaultFile;
	
	/**
	 * Give multilang value
	 */
	
	public $multilang = "no";
	
	
	
	public function __construct() {
			
		$this->defaultFile = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		//$this->init();
		
	
	}
	
	public function init() {
		
		
		$this->defaultLang = "main";

		/**
		 * Core language
		 */
		
		$path = LOCAL_DIR . "/core/lang/";
		
		switch ($this->defaultFile) {
			case $this->defaultFile:
				$this->include_lang($path, $this->defaultFile . ".php");
				break;
		}
		
	}
	
	/**
	 *
	 * for init() method
	 */
	
	public function include_lang($path, $lang) {
		if(file_exists($path . $lang)) {
			include_once($path . $lang);
		} else {
			include_once($path . "en.php");
		}
	}
	
	/**
	 * 
	 * Ins
	 */
	
	/**
	 * 
	 * @param array $langArray
	 * @param string $in
	 * @return language if exist, if not returns main as main language
	 */
	
	public function setLang($langArray, $in) {
		
		$this->defaultFile = $in;
		
		try {
			for ($i = 0; $i < count($langArray); $i++) {
				if($langArray[$i] == $in) {
					$this->defaultLang = $in;
				}
			}
		} catch (Exception $e) {
			$this->defaultLang = "main";
		}
		
		return $this->defaultLang;
		
	}
	
	/**
	 * 
	 * Outs
	 */
	
	/**
	 * Get default lang from the virtual cookie
	 */
	
	public function getLang() {
		return $this->defaultLang;
	}
	
	/**
	 * init() modules lang
	 * @param String $mod
	 */
	
	public function init_mods_lang($mod) {
		$path = LOCAL_DIR . "/site/apps/admin/mods/" . $mod . "/lang/";
		
		
		switch ($this->defaultLang) {
			case $this->defaultLang;
			$this->include_lang($path, $this->defaultLang . ".php");
			break;
			
			default:
			$this->include_lang($path, "en" . ".php");
		}
		
	}
	
	/**
	 * init() apps lang
	 * @param String $app name
	 */
	
	public function init_apps_lang($app) {
	
		if(isset($_GET['lang'])) {
			$this->defaultFile = $_GET['lang'];
		}
		
		$path = APPS_DIR . $app."/lang/";		
		
		try {	
	
		$this->app = $app;
		
		switch ($this->defaultFile) {
			case $this->defaultFile;
			$this->include_lang($path, $this->defaultFile . ".php");
			break;
			
		}
		} catch (Exception $e) {
			
		}
	
	}
	
	
	
	/**
	 *
	 * @array language list (In)
	 * @return Ambigous <string, unknown, mixed>
	 * Lang bar
	 */
	
	public function langList($array) {
				
		$links = "";
		$template = "";
		
			if($this->multilang == "yes") {
				
				//if(is_array($array)) {
					$links = "<li>";
					for($i = 0; $i < count($array); $i++) {
						if($array[$i] == $this->defaultLang) {
							$links .= "\t<a href=\"".$this->app."/setlang/lang-main\"><i class=\"icon-ok\"></i> ".$array[$i]."</a>\n";
						} else {
							$links .= "\t<a href=\"".$this->app."/setlang/lang-" . $array[$i] ."\">".$array[$i]."</a>\n";
						}
					}
					$links .= "</li>";
				//}
				
				$tpl = new ThemeLoader(LOCAL_DIR . "/core/html/lang_bar.html");
				$template = $tpl->renderPage($array = array('code' => $links));
				
			}

		return $template;
	
	}
		
}