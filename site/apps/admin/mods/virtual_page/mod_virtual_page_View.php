<?php

/**
 *
 * mod_virtual_page_View.php
 * (c) Jun 12, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class mod_virtual_page_View extends configSettings {
	
	public $mod_name = _MOD_VIRTUAL_PAGE;
	
	/**
	 * Variable de contenido $content
	 */
	
	public $content = "";
	
	/**
	 * 
	 * Recibir el menu de módulos desde el Router.
	 */
	
	public $menu;
	
	public $tabLinks;
	
	public function __construct() {
		
		/**
		 * 
		 * Obtener los registros totales de esta tabla para colocarlos en el titulo de modulo
		 * en el panel izquierdo del menu. Ejemplo Blog (70)
		 */
		
		$this->LoadSettings();
		
	}
	
	
	public function new_virtual_page_view() {

		/**
		 * 
		 * @$this->mod_name Nombre de el módulo (Aparecera en el header del contededor)
		 **/

		$this->mod_name = _VIRTUAL_PAGE;
				
		/**
		 * Build html tab block
		*/
		
		$htmltab = "";
		//$tab = new ThemeLoader(MODS_DIR . "/virtual_page/html/new_page.html");
		$tab = new ThemeLoader(MODS_DIR . "/virtual_page/html/new_page_" . $this->editor . ".html");
		
		$array = array(
				
				/**
				 * Labels
				 */
				
				'lbl_title' => _VIRTUAL_PAGE_TITLE,
				'lbl_active' => _VIRTUAL_PAGE_ACTIVE,
				'lbl_message' => _VIRTUAL_PAGE_CONTENT,
				
				/**
				 * Variables
				 */
				
				'new_page' => _VIRTUAL_PAGE_NEW,
				'enabled' => _ENABLED_CONTENT,
				'disabled' => _DISABLED_CONTENT,
				'btn_add' => _VIRTUAL_PAGE_CREATE
				
		);
		
		$htmltab .= $tab->renderPage($array);
		
		$this->content .= $htmltab;
		
		
	}
	
	
	public function edit_virtual_page_view($id) {
	
		$form = "";
		$db = new mod_virtual_page_Model();
		
		/**
		 * @$this->mod_name Nombre de el módulo (Aparecera en el header del contededor)
		 **/
	
		//echo "bd" . $db->return_value($id);
		
		$this->mod_name = _EDIT_VIRTUAL_PAGE;
	
		$editor = new Form("index.php?app=admin&mod_controller=virtual_page&sr=edit_page&id=$id");
		$editor->Label(_CONFIG_VIRTUAL_PAGE);
		if($db->return_value($id) == 1) {
			$form .= $editor->RadioButton(_ENABLED_CONTENT, "1", "a", 1);
			$form .= $editor->RadioButton(_DISABLED_CONTENT, "0", "a", 0);
		} else {
			$form .= $editor->RadioButton(_ENABLED_CONTENT, "1", "a", 0);
			$form .= $editor->RadioButton(_DISABLED_CONTENT, "0", "a", 1);
		}
		
		$title = $db->return_virtual_title($id);
		$content = $db->return_virtual_content($id);
		
		/**
		 * Build html tab block main
		*/
		
		$htmltab = "";
		//$tab = new ThemeLoader(MODS_DIR. "/virtual_page/html/main_edit_page.html");
		$tab = new ThemeLoader(MODS_DIR. "/virtual_page/html/main_edit_page_". $this->editor .".html");
		
		$array = array(
				'id' => $id,
				'code' => "main",
				'content' => $this->buildTab(),
				'codes_loop' => $this->tabLinks,
				'option_active' => $form,
				'title' => $title,
				'message' => $content,
				'edit'=>_VIRTUAL_PAGE_EDIT,
				
				/**
				 * Labels
				 */
				
				'lbl_title' => _VIRTUAL_PAGE_TITLE,
				'lbl_active' => _VIRTUAL_PAGE_ACTIVE,
				'lbl_message' => _VIRTUAL_PAGE_CONTENT,
				
				/**
				 * Buttons
				 */
				
				'btn_reset' => _VIRTUAL_PAGE_RESET,
				'btn_edit' => _VIRTUAL_PAGE_EDIT,
				'btn_delete' => _VIRTUAL_PAGE_DELETE,
				'btn_confirm' => _VIRTUAL_PAGE_CONFIRM,
				'btn_cancel' => _VIRTUAL_PAGE_CANCEL,
				
				/**
				 * Messages
				 */
				
				'message_title' => _VIRTUAL_PAGE_NOTE,
				'message_confirm' => _VIRTUAL_PAGE_CONFIRM_MESSAGE
				
		);
		
		$htmltab .= $tab->renderPage($array);
		
		/**
		 * Build lang tabs
		 */
		
		$settings = new configSettings();
		$settings->LoadSettings();
		
		$this->content .= $htmltab;

		$tip = new MsgBox("", _MARKDOWN_REFERENCE, "I");
		$tip_type2 = $tip->Show();
		$this->content .= $tip_type2;
	
	}
	

	public function site_map_view() {
		
		$s = new mod_virtual_page_Model();
		$s->site_map_model();
		
		//$this->content .= _TREE_VIRTUAL_PAGE;
		
		/**
		 * Como renderizar página utilizando ThemeLoader
		 * Para documentación de desarrollador
		 */
		
		// tramos configuracion (variables)
		$cfg = new configSettings();
		$cfg->LoadSettings();
		$this->basepath = $cfg->basepath;
		
		// nueva variable de tipo objeto
		$objTemplate = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/mods/virtual_page/html/sitemap.html");

		// variable para acumular el contenido de los loops (html)
		$html = "";
		
		try {
			
			if(empty($s->rows)) {
				$html = _NO_VIRTUAL_PAGES;
				throw new Exception();
			} 
			
			// recorremos los datos o la query y lo almacenamos en $html
			foreach ($s->rows as $row) {
				// obtener el contenido de loop.html y reemplazarlo con su contenido
				$tmp = file_get_contents(LOCAL_DIR . "/site/apps/admin/mods/virtual_page/html/loop.html");
				$tmp = str_replace("{virtual_title}", $row['virtual_title'], $tmp);
				$tmp = str_replace("{id}", $row['id'], $tmp);
				
				// almacenando página dentro del loop (html)
				$html = $html . $tmp;
			}
		
		} catch (Exception $e) {
			
		}
		
		// creamos diccionario y reemplazamos $html
		$array = array(
				
				'site'=>$cfg->title,
				'loop'=>$html,
				
				/**
				 * Labels
				 */
				
				'sitemap' => _VIRTUAL_PAGE_SITEMAP
				
		);
	
		// metemos el contenido del template en el contenido de la pagina
		$this->content .= $objTemplate->renderPage($array);
		
		/******************************************************/
		
		$msgbox = new MsgBox(_VIRTUAL_PAGE_NOTE, _VIRTUAL_PAGE_NOTE_MESSAGE, "I");
		$this->content .= $msgbox->Show();
		
		// renderizamos finalmente todo el documento
		//$this->Render();
		
	}
	
	
	public function sucessMessage($message) {
		$v_message = new MsgBox("", $message, "S");
		$string_var_message = $v_message->Show();
		
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
		
	
	public function Render() {
		
		$cfg = new configSettings();
		
		/**
		 * 
		 * Diccionario (variables de la plantilla).
		 */
		
		$array = array(
				'content'=>$this->content,
				'mod_name'=>$this->mod_name,
				'mod_menu'=>$this->menu,
				'basepath'=>$cfg->basepath,
				'username'=>$cfg->user,
				'email'=>$cfg->email
				);
				

		try {
			
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

			$objTheme = new ThemeLoader(APPS_DIR . "admin/panel/dashboard.html");		
			echo $objTheme->renderPage($array);
		
		} catch (Exception $e) {
			
			die($e->getMessage());
			
		}
		
	
	}

	private function buildTab() {
	
		$htmltab = "";
	
		/**
		 * Build lang tabs
		 */
	
		if($this->multilang == "yes") {
				
	
			$model = new mod_virtual_page_Model();
				
			//$tab = new ThemeLoader(MODS_DIR . "/virtual_page/html/code_edit_page.html");
			$tab = new ThemeLoader(MODS_DIR . "/virtual_page/html/code_edit_page_". $this->editor .".html");
			
			$objLangs = new mod_languages_Model();
			$langs = $objLangs->get_lenguages();
				
			$i = 0;
			foreach ($langs as $row) {
				
				$i++;
				$title = $model->return_virtual_title_multilang($_GET['id'], $row['code']);
				$message = $model->return_virtual_content_multilang($_GET['id'], $row['code']);

				//$editor = new Form("index.php?app=admin&mod_controller=virtual_page&sr=page_multilang&id=$id");
				$editor = new Form();
				
				//$editor->Label($row['label']);
				
				if($model->return_value($_GET['id']) == 1) {
					$editor->RadioButton(_ENABLED_CONTENT, "1", "a", 1);
					$editor->RadioButton(_DISABLED_CONTENT, "0", "a", 0);
				} else {
					$editor->RadioButton(_ENABLED_CONTENT, "1", "a", 0);
					$editor->RadioButton(_DISABLED_CONTENT, "0", "a", 1);
				}
								
	
				$this->tabLinks .= "<li><a href=\"#code-" . $row['code'] . "\" data-toggle=\"tab\">" . $row['code'] . "</a></li>\n";
					
				$array = array(
	
						/**
						 * Variables
						 */
	
						'id' => $_GET['id'],
						'code' => $row['code'],
						'title' => $title,
						'message' => $message,
	
						/**
						 * Labels
						 */
	
						'lbl_title' => _VIRTUAL_PAGE_TITLE,
						'lbl_message' => _VIRTUAL_PAGE_CONTENT,
	
	
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
