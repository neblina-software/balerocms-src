<?php

/**
 *
 * Form.php
 * (c) Apr 3, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class Form {
	
	/**
	 * Parametros de salida.
	 */
	
	private $form;
	
	/**
	 * 
	 * @var string $action acción de formulario.
	 * @var string $method tipo get o post.
	 */
	
	private $action;
	private $method;
	
	/**
	 * 
	 * @var string $file renderizar los campos del formulario. 
	 */
	
	private $file;
	private $field;
	
	/**
	 * 
	 * @param unknown_type $action.
	 * @param unknown_type $method.
	 * Iniciar de forma automatica FormNew().
	 */
	
	public function __construct($action = "", $method = NULL) {
		$this->action = "" . $action;
		if(empty($method)) {
			$method = "POST";
			$this->method = $method;
		} else {
			$this->method = $method;
		}
		//$this->FormNew($action, $method);
	}
	
	public function FormNew($action = "", $method = "") {
		$this->form = "<form action=\"$this->action\" method=\"$this->method\" name=\"blo_form\" id=\"blo_form\">";
	}
	
	/**
	 * 
	 * Métodos de formulario.
	 */
	
	public function TextField($label = "", $name = "", $value = "") {
		$html = "<input type=\"text\" name=\"$name\" value=\"$value\">";
		if (!empty($label)) {
			$tmp = $this->renderField($label, $html);
		} else {
			$tmp = $this->renderField($name, $html);
		}
		$this->form .= $tmp;
	}
	
	public function PasswordField($label = "", $name = "", $value = "") {
		$html = "<input type=\"password\" name=\"$name\" value=\"$value\" >";
		if (!empty($label)) {
			$tmp = $this->renderField($label, $html);
		} else {
			$tmp = $this->renderField($name, $html);
		}
		$this->form .= $tmp;
	}
	
	public function HiddenField($name = "", $value = "") {
		$html = "<input type=\"hidden\" name=\"$name\" value=\"$value\" >";
		$this->form .= $html;
	}
	
	public function SubmitButton($value = "", $name = "") {
		if(empty($name)) {
			$html = "<input type=\"submit\" value=\"$value\" name=\"submit\">";
		} else {
			$html = "<input type=\"submit\" value=\"$value\" name=\"$name\">";
		}
		if (!empty($label)) {
			//$tmp = $this->renderField($label, $html);
			$tmp = $html;
		} else {
			//$tmp = $this->renderField("", $html);
			$tmp = $html;
		}
		$this->form .= $tmp;
	}
	
	public function TextArea($label = "", $name = "", $message = "") {
		$html = "<textarea cols=\"10\" rows=\"10\" name=\"$name\">";
		$html .= $message;
		$html .= "</textarea>";
		if (!empty($label)) {
			$tmp = $this->renderField($label, $html);
		} else {
			$tmp = $this->renderField($name, $html);
		}
		$this->form .= $tmp;
	}
	
	public function RadioButton($label = "", $value = "", $name = "", $checked = "") {
		if($checked == 1) {
			$html = "<input type=\"radio\" name=\"$name\" value=\"$value\" checked=\"checked\"> $label";
		} else {
			$html = "<input type=\"radio\" name=\"$name\" value=\"$value\"> $label";
		}
		if (!empty($label)) {
			//$tmp = $this->renderField($label, $html);
			// no necesita etiqueta a su izquierda
			$tmp = $this->renderField("", $html);
		} else {
			$tmp = $this->renderField($name, $html);
		}
		return $tmp;
	}
	
	
	public function CheckBox($label = "", $name = "", $checked = "") {
		if($checked) {
			$html = "<input type=\"checkbox\" name=\"$name\" value=\"yes\" checked> $label";
		} else {
			$html = "<input type=\"checkbox\" name=\"$name\" value=\"no\"> $label";
		}
		if (!empty($label)) {
			//$tmp = $this->renderField($label, $html);
			// no necesita etiqueta a su izquierda
			$tmp = $this->renderField("", $html);
		} else {
			$tmp = $this->renderField($name, $html);
		}
		$this->form .= $tmp;
	}
	
	
	public function Label($label) {
		$html = "<label>". $label . "</label>";

		if (!empty($label)) {
			$tmp = $this->renderField("", $html);
		} else {
			$tmp = $this->renderField("", $html);
		}
		$this->form .= $tmp;
	}
	
	public function DropDown($array = array(), $name = "", $attributes = "", $selected = "") {
		
		$html = "<select name=\"$name\" $attributes>";
		
		if(count($array) == 0) {
			$html .= "<option>ERROR: ".__FUNCTION__ . " INVALID ARRAY IN " .__CLASS__."</option>";
		} else {
			for($i = 0; $i<count($array); $i++) {
				if($selected == $array[$i]) {
					$html .= "<option value=\"".$array[$i]."\" selected=\"selected\">".$array[$i]."</option>";
				} else {
					$html .= "<option value=\"".$array[$i]."\">".$array[$i]."</option>";
				}
			}
			
		}

		
		$html .= "</select>";
		
		return $html;
		
	}

	
	/**
	 * Mstrar en pantalla
	 */
	
	public function Show() {
		$this->form .= "</form>";
		return $this->form;
	}
	
	/**
	 * 
	 * @param string $field renderizar los campos con salida HTML.
	 */
	
	public function renderField($label, $field) {
		//$this->label = $label;
		//$this->field = $field;

		/**
		 * Static UI files in core
		 */
		
		$file = file_get_contents(LOCAL_DIR . "/core/html/form.html");
		$file = str_replace("{label}", $label, $file);
		$file = str_replace("{field}", $field, $file);
		//echo $file;
		return $file;
	}
	
	public function __destruct() {
		unset ($this->form);
	}
	
}