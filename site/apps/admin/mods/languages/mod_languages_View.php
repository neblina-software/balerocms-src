<?php

/**
 * 
 * @author Anibal Gomez -lastprophet-
 * UPDATE: 20-04-2014
 *
 */

class mod_languages_View extends configSettings {
	
	public $mod_name = _MOD_LANGUAGES;
	
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
	
	public function __construct() {
		
		/**
		 * 
		 * Obtener los registros totales de esta tabla para colocarlos en el titulo de modulo
		 * en el panel izquierdo del menu. Ejemplo Blog (70)
		 */
		
		
		// load configSettings vars
		$this->LoadSettings();
	
		
	}
	
	/**
	 * 
	 * Methods
	 */
	
	public function setup_view() {
		
		$option = "";
		
		$this->content .= $this->errorMessage(_LANG_NOTE);
		$form = new Form();
		$lang_default = $this->multilang;
		
		if($lang_default == "yes") {
			$on = 1;
			$off = 0;
		} else {
			$off = 1;
			$on = 0;
		}
		
		$option .= $form->RadioButton(_LANG_ENABLED, "1", "lang_on", $on);
		$option .= $form->RadioButton(_LANG_DISABLED, "0", "lang_on", $off);
		
		$array = array(
		
				/**
				 * Variables
				 */
		
				'title' => _LANG_SETUP,
				'content' => $option,
		
				/**
				 * Labels
				 */
		
				'lbl_configuration' => _LANG_CONFIG,
		
				/**
				 * Buttons
				 */
		
				'btn_save' => _LANG_SAVE
		
		
		);
		
		$tpl = new ThemeLoader(MODS_DIR . "/languages/html/main.html");
		$template = $tpl->renderPage($array);
		
		$this->content .= $template;
		
		/**
		 * Lang setup form
		 */
		
		if($this->multilang == "yes") {
		
			
					$setup = new Form();
					//$setup->Label(_LIST);
					
					/**
					 * dynamic array
					 */
					
				try {
					
						$mod_model = new mod_languages_Model();
						
						$lang_array = $mod_model->get_lenguages();
					
						/**
						 * convert dynamic array to simple array
						*/
					
						if(is_array($lang_array)) {
							$_lang_array = array();
							foreach ($lang_array as $row) {
								$_lang_array[] = $row['label'] . " (" . $row['code'] . ")";
							}
						} else {
							throw new Exception(_NO_LANGUAGES);
						}
						
						
						$active = 1;
				} catch (Exception $e) {
						$_lang_array = array($e->getMessage());
						$active = 0;
				}
				
					//print_r($lang_array);
					
					$setup->DropDown($_lang_array, "lang", "", "en");
					
					$dropdown_languages = $setup->DropDown($_lang_array, "lang", "class='chzn-select'", "selected"); 
					
					
					if($active) {
						$form->SubmitButton(_DELETE_LANG, "delete_language");
					}
								
					$content = $setup->Show();
					
					$array = array(
					
							/**
							 * Variables
							*/
					
							'list' => _LANG_LABEL,
							'title' => _LANG_SETUP,
							'dropdown_languages' => $dropdown_languages,
							'new_language' => _LANG_ADD_NEW,
					
							/**
							 * Labels
							*/
					
							'lbl_configuration' => _LANG_CONFIG,
							'lbl_list' => _LIST,
							'lbl_avaible' => _LANG_AVAIBLE,
							'lbl_list' => _LANG_SELECT,

					
							/**
							 * Buttons
							*/
					
							'btn_save' => _LANG_SAVE,
							'btn_delete' => _DELETE_LANG
					
					);
					
					$tpl = new ThemeLoader(MODS_DIR . "/languages/html/setup.html");
					$this->content .= $tpl->renderPage($array);
		
					
		} // end if
	
		/**
		 * Load and Render views
		 */
		
		$this->new_language();
		$this->Render();
	}
	
	public function new_language() {
		
		if($this->multilang == "yes") {
			
			$langs = $this->lang_list();
				
			$form = new Form();
			$dropdown_codes = $form->DropDown($langs, "code", "class='chzn-select'", "");
			
			$array = array(
						
					/**
					 * Variables
					 */
						
					'new_language' => _LANG_ADD_NEW,
					'dropdown_codes' => $dropdown_codes,
						
					/**
					 * Labels
					 */
						
					'lbl_list' => _LANG_SELECT,
					'lbl_label' => _LABEL,
					'lbl_example' => _EXAMPLE,
					'lbl_code' => _LANG_CODE,
			
						
					/**
					 * Buttons
					 */
						
					'btn_add' => _ADD_LANG
						
			);
			
			$newLang = new ThemeLoader(MODS_DIR . "/languages/html/new_language.html");
			$tpl = $newLang->renderPage($array);
			$this->content .= $tpl;
		}
		
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
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(APPS_DIR . "/admin/panel/dashboard.html");		
		echo $objTheme->renderPage($array);
		
	
	}

	public function lang_list() {
	
		$list_array = array('af',
				'sq',
				'ap',
				'hy',
				'eu',
				'bn',
				'bg',
				'ca',
				'km',
				'zh',
				'hr',
				'cs',
				'da',
				'nl',
				'en',
				'et',
				'fj',
				'fi',
				'fr',
				'ka',
				'de',
				'el',
				'gu',
				'he',
				'hi',
				'hu',
				'is',
				'id',
				'ga',
				'it',
				'ja',
				'jw',
				'ko',
				'la',
				'lv',
				'lt',
				'mk',
				'ms',
				'ml',
				'mt',
				'mi',
				'mp',
				'mn',
				'ne',
				'no',
				'fa',
				'pl',
				'pt',
				'pa',
				'qu',
				'ro',
				'ru',
				'sm',
				'sp',
				'sk',
				'sl',
				'es',
				'sw',
				'sv',
				'ta',
				'tt',
				'te',
				'th',
				'bo',
				'to',
				'tr',
				'uk',
				'ur',
				'uz',
				'vi',
				'cy',
				'xy');			
			return $list_array;
			
		}
	
}
