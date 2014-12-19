<?php

/**
* Plantilla de la clase appView para Balero CMS.
* Declare aqui todas las 'vistas'
**/

/**
 * Multi-Language Fixes
 */

class blog_View extends configSettings {
	
	public $theme;
	
	public $rows;
	
	/**
	 * Variable de contenido $content
	 */

	public $content = "";
	
	/**
	 * Variables de sistema
	 */
	
	public $virtual_pages;
	
	public $objTheme;
	
	public $count;
	
	public $page;
	
	public $lang;
	
	public $printVirtualPages; // multilang or default
	
	public function __construct() {
		
		$this->page = "Blog";
		
		$this->objTheme = new blog_Model();
		$this->theme = $this->objTheme->theme();
		
		// forzar la carga de variables de config
		$this->LoadSettings();
		
	}
	
	public function print_virtual_pages_title() {
		
		$html = "";
		
		foreach ($this->objTheme->get_virtual_pages() as $page) {
			$html .= "<li><a href=\"#\">" . $page['virtual_title'] . "</a></li>";
		}
		
		return $html;
		
	}

	/**
	 * Cargar la vista.
	 */
	
	public function Render() {

		$lang = new Language();
		$lang->multilang = $this->multilang;
		$lang->app = "blog";
		$lang->defaultLang = $this->objTheme->getLang();
		
		// incluir clases fuera de este directorio		
		$ldr = new autoloader("virtual_page");
		
		// trae paginas virtuales
		//$vp = new virtual_page_View();
		
		$array = array(
				'title'=>$this->title,
				'url'=>$this->url,
				'keywords'=>$this->keywords,
				'description'=>$this->description,
				'content'=>$this->content,
				'virtual_pages'=>$this->printVirtualPages,
				'basepath'=>$this->basepath,
				'page'=>$this->page,
				'langs'=>$lang->langList($this->objTheme->getLangList())
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/themes/" . $this->theme . "/main.html");		
		echo $objTheme->renderPage($array);
		
	
	}
	
	public function show_all_post() {
		
		if(empty($this->lang)) {
			$this->lang = "main";
		}
		
		$this->count = 0;
		// debug
		//var_dump($this->rows);
		
		$word_count = 0;
		
		$tpl = new ThemeLoader(APPS_DIR . "blog/html/index.html");
		
		try {
		
			if(empty($this->rows)) {
				throw new Exception();
			}
			
		foreach ($this->rows as $row) {
			
			$this->page = "Blog";
			
			//$blocks = 3;
			
			/**
			 * 
			 * Llamar la clase Markdown.
			 */
			
			$limit = 144;
			$word_count = strlen($row['message']);
			
			//try {
				
				/**
				 * Truncate post content
				 */
				
			$post = $this->truncate_word($row['message'], $limit);
			
			/**
			 * Print "More..." link
			 */
			
			if(!isset($_GET['id'])) {
				if($word_count > $limit) {
					// dynamic
					//$post .= "(<a href=\"index.php?app=blog&sr=full_post&id=".$row['id']."\" class=\"more\">". _MORE ."</a>)";
					$post .= "<a class=\"more\" href=\"./blog/".$this->lang."/id-".$row['id']."\">". _MORE ."</a>";
				} 
			}
			
			/**
			 * Returns post's rendered content
			 */
			
			$render_html = Markdown::defaultTransform($post);
			
			/**
			 * Render post from HTML template
			 * post.html 
			 */
			
			/**
			 * Smart blog only for test (beta)
			 */
			
			//$blocks = $this->block($this->count, 3);
			//$this->count++;
			
			$vars = array("blog_title" => $row['title'],
						"blog_message" => $render_html,
						"blog_info" => $row['info'],
						//"blocks" => $blocks
						);
			

			
			/**
			 * Prints rendered post and template vars
			 */
			
			$this->content .= $tpl->renderPage($vars);
			
		} // end loop
			
			} catch (Exception $e) {

				/**
				 * No action
				 */
				
			}
		
	}
	
	
	/**
	 * Metodos
	 */
	
	/**
	 * @truncate_word() Cortar palabras o limitar.
	 * @param String $string cadena
	 * @param INT $limit limite
	 */
	
	public function full_post_view($array) {
		
		//echo $this->printVirtualPages;
		
		$markdown = new Markdown();
		
		$tpl = new ThemeLoader(APPS_DIR . "blog/html/full_post.html");
		
		foreach ($array as $row) {
			
			$title = $row['title'];
			$this->page = $title;
			$message = $markdown->defaultTransform($row['message']);
			
			$vars = array("blog_title" => $title,
					"blog_message" => $message,
					"blog_info" => $row['info']);
				
			$this->content .= $tpl->renderPage($vars);
		}
		
	}
	
	public function truncate_word($string, $limit) {
	
		if(strlen($string) > $limit) {
			$truncate_string = substr($string, 0, $limit);
			$new_string = $truncate_string . "...";
		} else {
			$new_string = $string;
		}
	
		return $new_string;
		
	}
	
	public function message($message) {
		$msg = new Tips();
		return $msg->red($message);
	}
	
	/**
	 * 
	 * Implementing smart blog
	 * Multi column (only for test)
	 * @param unknown $count
	 * @param unknown $break
	 * @return string|unknown
	 */
	
	public function block($count, $break) {
		echo $count;
		if($count == $break) {
			$this->count = 0-1;
			return "<div class=clear></div>";
		} else {
			return $break;
		}
		
	}
	
} // fin clase
