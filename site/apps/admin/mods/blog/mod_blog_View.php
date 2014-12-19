<?php

/**
 *
 * blog_view.php
 * (c) Jun 12, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class mod_blog_View extends configSettings {
	
	public $mod_name = _MOD_BLOG;
	
	/**
	 * Variable de contenido $content
	 */
	
	public $content = "";
	
	/**
	 * 
	 * Recibir el menu de módulos desde el Router.
	 */
	
	public $menu;
	
	public $min;
	
	/**
	 * Tab Menu Links
	 */
	
	public $tabLinks;
	
	/**
	 * Pagination bar
	 */
	
	public $paginationBar;
	
	public function __construct() {
		
		/**
		 * 
		 * Obtener los registros totales de esta tabla para colocarlos en el titulo de modulo
		 * en el panel izquierdo del menu. Ejemplo Blog (70)
		 */
		
	$this->LoadSettings();
		
		
	}
	
	
	public function new_post_view() {


		/**
		 * @$this->mod_name Nombre de el módulo (Aparecera en el header del contededor)
		 **/
		
		$this->mod_name = _ADD_NEW_POST;
		
		$tip = new MsgBox("", _BLOG_MARKDOWN_REFERENCE, "I");
		$tip_type2_2 = $tip->Show();
		$this->content .= $tip_type2_2;
		
		/**
		 * Build default lang tab
		 */
		
		$htmltab = "";
		$tab = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/mods/blog/html/main_" . $this->editor . ".html");
				
		$array = array(
				'title' => _BLOG_ADD,
				'code' => "*",
				'content' => '',
				
				/**
				 * Labels
				 */
				
				'lbl_new_post' => _BLOG_NEW_POST,
				'lbl_title' => _BLOG_TITLE,
				'lbl_message' => _BLOG_MESSAGE,
				
				/**
				 * Buttons
				 */
				
				'btn_add' => _BLOG_ADD,
				'btn_cancel' => _BLOG_CANCEL
				
		);
		
		$htmltab .= $tab->renderPage($array);
		
		/**
		 * Build html tab block
		 */
			
		$this->content .= $htmltab;

		//$this->Render();
		
	}
	
	public function infoMessage($message) {
		$v_message = new MsgBox("", $message, "I");
		$string_var_message = $v_message->Show($message);
	
		/**
		 *
		 * @$string_var_message se necesita almacenar el contenido en una
		 * variable de tipo String.
		*/
	
		$this->content .= $string_var_message;
	
	}
	
	public function sucessMessage($message) {
		$v_message = new MsgBox("", $message, "S");
		$string_var_message = $v_message->Show($message);
		
		/**
		 * 
		 * @$string_var_message se necesita almacenar el contenido en una
		 * variable de tipo String.
		 */
		
		$this->content .= $string_var_message;
		
	}
	
	public function errorMessage($message) {
		$v_message = new MsgBox("", $message, "E");
		$string_var_message = $v_message->Show();
	
		/**
		 *
		 * @$string_var_message se necesita almacenar el contenido en una
		 * variable de tipo String.
		 */
		
		
		 $this->content .= $string_var_message;
	
	}
	
	public function print_post($rows) {
		
		
		/**
		 * Pasar parametros al renderizador de filas.
		 */
		
		// leemos el parametro, en este caso los datos
	
		//				recorrer datos almacenados en $rows[]
		//				lo hacemos desde la vista:
		
		$html = "";
		$color = "color1";
		$i = $this->min;
		
		foreach ($rows as $row) {
			$i++;
			if($color == "color1") {
				$color = "color2";
			}  else {
				$color = "color1";
			}
	
			// Guardar filas renderizadas en $html
			$html .= $this->RenderTableRows($i, $row['id'], $row['title'], $row['message'], $color);
			
		}
		
		$this->content .= $this->RenderTable($html);
	
	}
	
	public function edit_view($id) {
		
			
		$this->mod_name = _EDIT_POST;

		/**
		 * Build default lang tab
		 */
		
		$htmltab = "";
		//$tab = new ThemeLoader(MODS_DIR . "/blog/html/main_edit_post.html");
		$tab = new ThemeLoader(MODS_DIR . "/blog/html/main_edit_post_" . $this->editor . ".html");
		
		
		$editor = new Form();
			
		// traer datos de modelo
		$model_edit = new mod_blog_Model();
		// regresa el titulo del post unicamente por ID
		$title = $model_edit->return_post_title($id);
		// importar contenido en el editor , todo lo que este dentro de un campo llamado "import" por ID
		$message = $model_edit->return_post_content($id);
				
		$array = array(
								
				/**
				 * Variables
				 */
				
				'id' => $id,
				'code' => "main",
				'title' => $title,
				'message' => $message,
				'edit' => _BLOG_EDIT,
				
				/**
				 * Labels
				 */
				
				'lbl_title' => _BLOG_TITLE,
				'lbl_message' => _BLOG_MESSAGE,
				
				/**
				 * Buttons
				 */
				
				'btn_reset' => _BLOG_RESET,
				'btn_edit' => _BLOG_EDIT,
				'btn_delete' => _BLOG_DELETE,
				
				'content' => $this->buildTab(),
				'codes_loop' => $this->tabLinks
				
		);
		
		$htmltab .= $tab->renderPage($array);
					
		$this->infoMessage(_BLOG_POST_MESSAGE);
		$this->content .= $htmltab;
					
		$this->Render();
		
	}
	
	/**
	 * @truncate_word() Cortar palabras o limitar.
	 * @param String $string cadena
	 * @param INT $limit limite
	 */
	
	public function truncate_word($string, $limit) {
		
		$truncate_string = substr($string, 0, $limit);
		
		$new_string = htmlspecialchars($truncate_string) . "...";
		
		return $new_string;
		
	}
	
	/**
	 *
	 * Renderizar filas de la tabla
	 * Primero renderizamos las filas y posteriormente la tabla con  los datos ya cargados.
	 * 
	 */
	
	public function RenderTableRows($i, $id, $title, $message, $color) {
		/**
		 * 
		 * Usamos el método truncate_word() para limitar palabras.
		 */
		
		
		$title = $this->truncate_word($title, 10);
		$message = $this->truncate_word($message, 25);
		
		/**
		 * 
		 * @Renderizamos las filas y las pasamos a la plantilla de la tabla
		 */
		
		$html_array = array(
				'i'=>$i,
				'id'=>$id,
				'title'=>$title,
				'message'=>$message,
				'color'=>$color
				);
		
		$html_theme = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/mods/blog/html/td_loop.html");
		
		/**
		 * 
		 * @$html Cargar variable con las funciones anteriores para pasarla al sig. diccionario.
		 */
		
		$html = $html_theme->renderPage($html_array);
		
		
	return $html;
	
	
	}
	
	
	public function RenderTable($html) {
		
		/**
		 *
		 * Diccionario (variables de la plantilla).
		 */
		
		$array = array(
				'loop'=>$html,
				'pagination_bar' => $this->paginationBar,
				'edit' => _BLOG_EDIT,
		);
		
		/**
		 *
		 * Renderizamos nuestra tabla finalmente
		 */
		
		$objTheme = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/mods/blog/html/table_post_list.html");
		return $objTheme->renderPage($array);
		
	}
	
	
	public function Render() {
		
		$cfg = new configSettings();
		
		/**
		 * 
		 * Diccionario (variables de la plantilla).
		 */
		
		$array = array(
				'content'=>$this->content,
				'mod_name'=>"Blog",
				'mod_menu'=>$this->menu,
				'basepath'=>$cfg->basepath,
				'username'=>$cfg->user,
				'email'=>$cfg->email
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/dashboard.html");		
		echo $objTheme->renderPage($array);
		
	
	}

	/**
	 * Build html tab block
	 */
	
	private function buildTab() {
	
		$htmltab = "";
		
		/**
		 * Build lang tabs
		 */
		
		if($this->multilang == "yes") {
			
		
			$model = new mod_blog_Model();
			
			
			//$tab = new ThemeLoader(MODS_DIR . "/blog/html/code_edit_post.html");
			$tab = new ThemeLoader(MODS_DIR . "/blog/html/code_edit_post_" . $this->editor . ".html");
			
			$objLangs = new mod_languages_Model();
			$langs = $objLangs->get_lenguages();
			
			$i = 0;
			foreach ($langs as $row) {
			
				$i++;
				$model->return_post_title_multilang($_POST['id'], $row['code']);
				$model->return_post_content_multilang($_POST['id'], $row['code']);
				
				$this->tabLinks .= "<li><a href=\"#code-" . $row['code'] . "\" data-toggle=\"tab\">" . $row['code'] . "</a></li>\n";
			
				$array = array(
						
						/**
						 * Variables
						 */
						
						'id' => $_POST['id'],
						'code' => $row['code'],
						'title' => $model->title,
						'message' => $model->message,
						
						/**
						 * Labels
						 */
						
						'lbl_title' => _BLOG_TITLE,
						'lbl_message' => _BLOG_MESSAGE,
						
						
						/**
						 * Buttons
						 */
						
						'btn_reset' => _BLOG_RESET,
						'btn_edit' => _BLOG_EDIT,
						'btn_delete' => _BLOG_DELETE,
						
						
				);
					
				/**
				 * Build html tab block
				*/
			
				$htmltab .= $tab->renderPage($array);
			
			
			} // for each
				
		} // end if
		
		return $htmltab;
		
	}
	
	
}
