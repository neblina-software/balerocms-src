<?php

/**
 *
 * blog_controller.php
 * (c) Jun 11, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class mod_blog_Controller extends configSettings {
	
	public $modModel;
	public $modView;
		
	public function __construct($menu) {
		
		// cargar vista de módulo.
		try {
			$this->modModel = new mod_blog_Model();
			$this->modView = new mod_blog_View();
			$this->modView->menu = $menu;
			
		} catch (Exception $e) {
			die("error" . $e->getMessage());
		}
		
		// Automatizar el controlador
		try {
		$handler = new ModControllerHandler($this);
		} catch (Exception $e) {
			die($e->getMessage());
		}
		
// 		switch ($_GET['sr']) {
// 			case "prueba":
// 				echo "funciona";
// 				break;
// 		}
		
	}
	
	
	public function main() {
		
		/**
		 * Load Controllers
		 */
		
		$this->new_post();
		
		/**
		 * Load Views
		 */
		
		$this->initViews();
		
	}
	
	// controlador new_post
	public function new_post() {
		
		if(isset($_POST['submit'])) {
			
			$objSec = new Security();
			$post_title = $objSec->shield($_POST['title']);
			
			/**
			 *
			 * @$plain_text Obtener el texto plano de el contenido que nos pasa el usuario.
			 */
			
			$plain_text = "";
			
			
			/**
			 * 
			 * @function htmlentities() no es compatible con acentos.
			 */
			
			//$plain_text = htmlspecialchars($_POST['content']);
			$plain_text = $objSec->noJS($_POST['content']);
			
			/**
			 * 
			 * Llamar la clase Markdown.
			 */
			
			//$render_html = Markdown::defaultTransform($plain_text);
			
			//$this->modView->content .= $plain_text;
			//$this->modView->content .= "----------------------";
			//$this->modView->content .= $render_html;
			
			
			
			try {
				if(empty($_POST['content'])) {
					$this->modView->errorMessage(_BLOG_POST_ERROR);
				}elseif(empty($_POST['title'])) {
					$this->modView->errorMessage(_BLOG_POST_EMPTY_TITLE);
				} else {
					$this->modModel->add_post($post_title, $plain_text);
					$this->modView->sucessMessage(_ADDED_SUCESSFULLY);
				}
			} catch (Exception $e) {
				$this->modView->errorMessage(_BLOG_POST_ERROR . $e->getMessage());
			}
			
		} // end if
		
			//$this->modView->new_post_view();
			
	}
			

	/**
	 * Controlador edit_post
	 */
	
	public function edit_delete_post() {
		
		//$this->modView->mod_name = "Editar post";
		/**
		 * Mensajes sucess y errores
		 */
		
		try {			
		
			$string_array = "";
			$i = 0;
			
			if(isset($_POST['submit_delete'])) {
				if(isset($_POST['delete_post'])) {
					$delete_post = $_POST['delete_post'];
					for($i = 0; $i < count($delete_post); $i++) {
						$this->modModel->delete_query($delete_post[$i]);
						if($i != count($delete_post)-1) {
							$string_array = $string_array . $delete_post[$i] . ", ";
						} else {
							$string_array = $string_array . $delete_post[$i] . "";
						}
					}
					$message = "Post ID #: " .$string_array." " . _WAS_DELETED_OK;
					$this->modView->sucessMessage($message);
					
				}
			}elseif(isset($_POST['id'])) {
				$this->edit_post();
				die();
			}
		
		} catch (Exception $e) {
			$this->modView->errorMessage(" " . _DELETING_POST_ERROR ." " . $e->getMessage());
		}
		
		/**
		 * Fin de mensajes
		 */
		
		$this->initViews();
		//$this->modView->Render();

		
	}
	
	
	/**
	 * Controlador edit post
	 */
	
	
	public function edit_post() {
		
		try {
		
			/**
			 * Acción para guardar la edición del post
			 */
		
		
			if(isset($_POST['submit'])) {
		
				if(empty($_POST['title'])) {
					throw new Exception(" "._BLOG_POST_EMPTY_TITLE." ");
				}
				if(empty($_POST['content'])) {
					throw new Exception(" "._BLOG_POST_ERROR." ");
				}
				$objShield = new Security();
				$id = $objShield->shield($_POST['id']);
				$this->modModel->edit_post($id, $objShield->shield($_POST['title']), $objShield->noJS($_POST['content']));
				$this->modView->sucessMessage(_SAVED_SUCESSFULLY);
			}
				
			/**
			 * Fin de acción para guardar la edición del post
			 */
		
				
			/**
			 * Acción para mostrar (importar) el editor cargado con el contenido que le corresponde
			 */
		
			$objShield = new Security();
			$id = $objShield->shield($_POST['id']);
			
			$this->modView->edit_view($id);
				
			/**
			 * Fin de acción para mostrar (importar) el editor cargado con el contenido que le corresponde
			*/
		
				
		} catch (Exception $e) {
			$id = new Security();
			$_id = $id->shield($_POST['id']);
			$this->modView->errorMessage($e->getMessage());
			$this->modView->edit_view($_id);
		}
		
		//$this->modView->Render();
			
	}
	
	/**
	 * add or edit multilang post
	 */
	
	public function post_multilang() {
				
		/**
		 * Delete post
		 */
		
		$string_array = "";
		$i = 0;
			
		if(isset($_POST['submit_delete'])) {
			if(isset($_POST['delete_post'])) {
				$delete_post = $_POST['delete_post'];
				for($i = 0; $i < count($delete_post); $i++) {
					echo $delete_post[$i] . " " . $_POST['code'];
					$this->modModel->delete_query_multilang($delete_post[$i], $_POST['code']);
					if($i != count($delete_post)-1) {
						$string_array = $string_array . $delete_post[$i] . ", ";
					} else {
						$string_array = $string_array . $delete_post[$i] . "";
					}
				}
				$message = "Post ID #: " .$string_array." " . _WAS_DELETED_OK;
				$this->modView->sucessMessage($message);
					
			}
		}elseif(isset($_POST['code'])) {

			/**
			 * Add or edit multilang post
			 */
			
			$objShield = new Security();
			
		/**
		 * Add multi-lang pages
		 */
		
			$title = $_POST['title'];
			$content = $_POST['content'];
			
			try {
				
				//$this->modModel->edit_post_multilang($_GET['id'], $objShield->shield($title), $objShield->noJS($content));
				//$this->modView->sucessMessage(_EDIT_MULTI_SUCESS);
				
				$this->modModel->add_post_multilang($_POST['id'], $objShield->shield($title), $objShield->noJS($content), $_POST['code'], $_POST['id']);
				$this->modView->sucessMessage(_ADD_MULTI_SUCESS);
				//$this->modView->Render();
				
				
			} catch (Exception $e) {				
				
				//die("asdf");
				$this->modView->errorMessage($e->getMessage());
				
			}
		
		} //end if
		
		$this->modView->edit_view($_POST['id']);
		
	}
	
	/**
	 * save as multilanguage post
	 */
	
	public function setLangHeaders($code, $content) {
		
		$plain_text = "";
		$openHeaders = "#-/-#-/-#[" . $code . "]#-/-#-/-#\n";
		$closeHeaders = "\n#-/-#-/-#[/" . $code . "]#-/-#-/-#";
		$plain_text = $openHeaders . $content . $closeHeaders;
		
		return $plain_text;
		
	}
	
	public function initViews() {
		
		/**
		 * Views
		 */
		
		$this->modView->new_post_view();
		
		$limit = $this->modModel->limit();
		$total = $this->modModel->total_rows();
		
		$p = new Pagination($total, $limit);
		
		$min = $p->min();
		$this->modView->min = $min;
		//$this->modView->content .= $p->nav();
		
		/**
		 * Blog navigation bar
		 */
		
		$this->modView->paginationBar = $p->pretty_nav("admin/main/mod-blog");
		
		$this->modModel->get_post($min, $limit); // cargara la variable rows[] con datos ($this->modModel->rows).
		$this->modView->print_post($this->modModel->rows); // pasamos datos por medio de un parametro
		$this->modView->Render();
		
	}
	
}
