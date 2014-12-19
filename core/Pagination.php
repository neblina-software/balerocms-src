<?php

/**
 *
 * Pagination.php
 * (c) Jun 27, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

/**
 * @param $total = total rows.
 * @param $limit = limit results x page.
 * @param $nav = new Pagination($total, $limit);
 *  
 * $this->print_url prints dynamic URL
 * $this->print_pretty prints pretty URL
 * 
 * @author Anibal Gomez -lastprophet-
 *
 */


class Pagination {
	
	public $total;
	public $limit;
	public $pages;
	
	// redondeo
	public $round;
	
	/**
	 * 
	 * @param (int) $total Recibimos el numero total de resultados (valor) para calcular paginas.
	 * @param (int) $limit Limitar el numero de resultados por pagina.
	 */
	
	public function __construct($total, $limit) {
		
		
		$this->total = $total;
		$this->limit = $limit;
				
		/**
		 * Ejemplo si el total de paginas es 2.4
		 * Estas operaciones nos regresaran 2.9
		 * el cual sera redondeado a 3.
		 */
		
		// resultado (float)
		$this->pages = ($this->total / $this->limit);
		
		// redondear decimal (paginas)
		
		$pos = strpos($this->pages, ".");
		
		if ($pos === false) {
			$this->round = $this->pages;
		} else {
			$this->round = $this->round($this->pages);
		}
		
		// pasar valores
		$this->pages = $this->round;
		
		while(empty($_GET['page'])) {
			$_GET['page'] = 1;
		}
		
		$_GET['page'] = htmlspecialchars($_GET['page']);
		
		
	}
	
	/**
	 * Calcular los limites a travez de la página obtenida por $_GET['page']
	 */
	
	/**
	 * SELECT * FROM tabla LIMIT $min, $max
	 */
	
	/**
	 * 
	 * @return number
	 * @method method min() SELECT * FROM 0, min
	 */
	
	public function min() {
	
		// si hay error empezamos a paginar desde LIMIT 0, x
		$min = 0;
		
		if(!isset($_GET['page'])) {
			$_GET['page'] = 1;
		}

		if($_GET['page'] > $this->pages) {
			//echo "paginas:" . $this->pages . "actual" .$_GET['page'] . "<br>";
			return $min = 0;
		}
		
		/**
		 * Hacer calculos a travez de el parametro que se encuentra en get page.
		 */
		
		while($_GET['page'] <= $this->pages) {
			$max = (int)($_GET['page'] * $this->limit);
		
			$min = ($max - $this->limit);
	
			// retorna (int)
			return $min;
		}
		
		return $min;
	
	}
	
	/**
	 * SELECT * FROM tabla LIMIT $min, $max
	 */
	
	/**
	 * 
	 * @return number
	 * @method method max() not tested, this is a beta method, do not use.
	 */
	
	public function max() {
		
		if(!isset($_GET['page'])) {
			$_GET['page'] = 1;
		}
		
		$max = (int)($_GET['page'] * $this->limit);
		
		// retorna (int)
		return $max;
		
	}
	
	/**
	 * Link Inicio
	 */
	
	public function start() {
		
		$start = "<a href='". $this->print_url() ."page=1'>" . _PAGINATION_HOME . "</a>";
		
		// retorna (String)
		return $start;
		
	}
	
	public function pretty_start($app) {
		
		$start = "<a href='". $this->print_pretty($app) ."/page-1'>" . _PAGINATION_HOME . "</a>";
		
		// retorna (String)
		return $start;
		
	}
	
	/**
	 * Link Final
	 */
	
	public function end() {
	
		$end = "<a href='". $this->print_url() ."page=$this->pages'>" . _PAGINATION_LAST . "</a>";
	
		// retorna (String)
		return $end;
		
	}
	
	public function pretty_end($app) {
	
		$end = "<a href='". $this->print_pretty($app) ."/page-$this->pages'>" . _PAGINATION_LAST . "</a>";
	
		// retorna (String)
		return $end;
	
	}
	
	/**
	 * Mostrar barra de navegacion/paginacion completa
	 */
	
	/**
	 * 
	 * dynamic nav
	 */
	
	public function nav() {
		
		// calcular pagina incompleta
		//$this->res = ($this->pages % 2);
		
		//while($this->pages > 1) {
		while($this->pages > 1) {
		$nav = "<div id=\"nav\">";
		$nav .= "<ul class=\"pag-bar\">";
		$nav .= "<li>" . $this->start() . "</li>";
		$nav .= $this->prev();
		
		for($i = 1; $i < $this->pages+1; $i++) {
				if($i == $_GET['page']) {
					$nav .= "" . $i . "";
				} else {
					$nav .= "<li><a href='" . $this->print_url() . "page=$i'>" . $i . "</a></li>";
				}
		}

		$nav .= $this->next();
		$nav .= "<li>" . $this->end() . "</li>";
		$nav .= "</ul>";
		$nav .= "</div>";
		
		// retorna (String)
		return $nav;
		}
		
	}
	

	
	/**
	 * Link siguiente
	 */
	
	public function next() {
	
		if($_GET['page'] != $this->pages) {

			try {
				if($_GET['page'] > $this->pages) {
					throw new Exception();
				}
				$next = "<li><a href='" . $this->print_url() . "page=" . ($_GET['page'] + 1) . "'>" . "&gt&gt" . "</a></li>";
			} catch (Exception $e) {
				$next = "";
			}
			
				// retorna (String)
				return $next;
				
		}
	
	}
	
	public function pretty_next($app) {
	
		if($_GET['page'] != $this->pages) {
	
			try {
				if($_GET['page'] > $this->pages) {
					throw new Exception();
				}
				$next = "<li><a href='" . $this->print_pretty($app) . "/page-" . ($_GET['page'] + 1) . "'>" . "&gt&gt" . "</a></li>";
			} catch (Exception $e) {
				$next = "";
			}
				
			// retorna (String)
			return $next;
	
		}
	
	}
	
	/**
	 * Link anterior
	 */
	
	public function prev() {
	
		$prev = "";
		
		if($_GET['page'] != 1) {
		
			try {
			
				if($_GET['page'] > $this->pages) {
					$prev = "";
					throw new Exception();
				} else {
					$prev = "<li><a href='". $this->print_url() ."page=" . ($_GET['page'] - 1) . "'>" . " &lt;&lt;" . "</a></li>";
				}
				
			} catch (Exception $e) {
				$next = "";
			}
			
			// retorna (String)
			return $prev;
		
		}
	
	}
		
	public function pretty_prev($app) {
	
		$prev = "";
	
		if($_GET['page'] != 1) {
	
			try {
					
				if($_GET['page'] > $this->pages) {
					$prev = "";
					throw new Exception();
				} else {
					$prev = "<li><a href='". $this->print_pretty($app) ."/page-" . ($_GET['page'] - 1) . "'>" . " &lt;&lt;" . "</a></li>";
				}
	
			} catch (Exception $e) {
				$next = "";
			}
				
			// retorna (String)
			return $prev;
	
		}
	
	}
	
	/**
	 * Manejar $_GET[page]
	 */
	
	public function page() {
		
		$page = "";
		
		if(isset($_GET['page'])) {
			$page = $_GET['page']-1;
		} else {
			$page = 1;
		}
		
		// retorna (int)
		return $page;
		
	}
	
	
	/**
	 * otros métodos 
	 */
	
	/**
	 * Aumentar el decimal a mas de 5 para que sea redondeado
	 * a la siguiente página =).
	 * 
	 * Eje: 2.4 a 2.9 = 3
	 *      2.0 a 2.5 = 3
	 *      2.1 a 2.6 = 3
	 *      
	 */
	
	public function round($float) {
		
		$_float = 0.0;
		
		//echo $float;
		
		$cut_float = explode(".", $float);
		
		$int = $cut_float[0];
		$dec = $cut_float[1];
		
		if($dec <= 5) {
			$new_float = ($dec) + 5;
			$_float = round($new_float);
		}
		
		if($dec >= 5) {
			$_float = $dec;
		}
		
		$rounded = $int . "." . $_float;
		
		return round($rounded);
	}
	
	/**
	 * Determinar que URL imprimir y como imprimirla
	 */
	
	/**
	 * 
	 * Prints dynamic URL
	 * for pretty urls off
	 * or
	 * for balero's back-end view
	 * 
	 */
	
	public function print_url() {
		
		// declarar vars
		$var_url = "";
		$var_url_tmp = "";
		
		// url completa
		$url = $_SERVER["REQUEST_URI"];
		
		// si encuentras &page regresame la url sin éste parametro
		$find_page = strpos($url, "&page");
		
		if($find_page === false) {
			
			// no encontre &page voy a hacer otra comparacion por si
			// encuentro ?page
					
			// si encuentras ?page regresame la url sin éste parametro
			 $_find_page = strpos($url, "?page");
			
			 if($_find_page === false) {
			 	$var_url = $url;
			 } else {
			 	$var_url = strstr($url, "?page", true);
			 }

		} else {
			$var_url = strstr($url, "&page", true);
		}
		
		
		
		// la url esta limpia
		
		
		// determina si debes de insertar la url en parametro principal
		// o en parametro secundario
 		$find = strpos($var_url, "&");
		
 		if ($find === false) {
// 			//echo "The string '$findme' was not found in the string '$mystring'";
 			$var_url = $var_url . "?";
 		} else {
// 			//echo "The string '$findme' was found in the string '$mystring'";
 			$var_url = $var_url . "&";
 		}
 		
 		$find = strpos($var_url, "?");
 		
 		if ($find === false) {
 			// 			//echo "The string '$findme' was not found in the string '$mystring'";
 		} else {
 			// 			//echo "The string '$findme' was found in the string '$mystring'";
 			$var_url = $var_url . "&";
 		}
		
 		$var_url = str_replace("?&", "&", $var_url);
 		$var_url = str_replace("&&", "&", $var_url);
 		
		return $var_url;
		
	}
	
	/**
	 * 
	 * Prints pretty URL
	 * for pretty urls on
	 * or
	 * for front-end view
	 * 
	 */
	
	/**
	 * Added pretty_nav
	 */
	
	public function pretty_nav($app) {
	
		// calcular pagina incompleta
		//$this->res = ($this->pages % 2);
	
		//while($this->pages > 1) {
		while($this->pages > 1) {
			$nav = "<div id=\"nav\">";
			$nav .= "<ul class=\"pag-bar\">";
			$nav .= "<li>" . $this->pretty_start($app) . "</li>";
			$nav .= $this->pretty_prev($app);
	
			for($i = 1; $i < $this->pages+1; $i++) {
				if($i == $_GET['page']) {
					$nav .= "" . $i . "";
				} else {
					$nav .= "<li><a href='" . $this->print_pretty($app) . "/page-$i'>" . $i . "</a></li>";
				}
			}
	
			$nav .= $this->pretty_next($app);
			$nav .= "<li>" . $this->pretty_end($app) . "</li>";
			$nav .= "</ul>";
			$nav .= "</div>";
	
			// retorna (String)
			return $nav;
		}
	
	}
	
	public function print_pretty($app) {
		
		/**
		 * Call recursive
		 */
		
		$url = "";
		
		$url = $this->clean_parameter($app);
			
		return $url;
		
	}
	
	/**
	 * 
	 * @var Who is calling pagination bar?
	 * Pagination recursive for pretty URL
	 * 
	 */
	
	public function clean_parameter($var) {
		
		// url completa
		$url = $_SERVER["REQUEST_URI"];
		
		$find_var = strpos($url, $var);
		
		if($find_var === false) {
				
			// no encontre var 
			return $var;
			
		}
		
		return $var;
		
	}
	
	
}
