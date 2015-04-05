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
 * 13-03-2015 Reported By Gjoko Krstic <gjoko@zeroscience.mk>
 * ------------------------------------------------
 * Multiple Authenticated Blind SQL Injections
 * Stored XSS on 'x' POST parameter
 * CSRF vulnerability is present in the entire CMS
 * XSS on the cookie 'counter'
 * ------------------------------------------------
 * 16-03-2015 Fixed by Anibal Gomez <anibalgomez@icloud.com>
 *
 *
**/

class Security {
	
	private $var;

    /**
     * Anti-XSS Method
     * @param $val Input String
     * @param int $rich Is Rich Text?
     * @return Proccesed String
     */
    public function antiXSS($val, $rich = 0) {

        //$val = preg_replace("/<(.*)s(.*)c(.*)r(.*)i(.*)p(.*)t(.*)>(.*)/i", "(xss-tag-detected)", $val);
        if($rich == 0) {
            $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '(xss-code-detected)', $val);
        }
        $val = str_replace('\'', '\&#39;', $val);

        $search = '[a-zA-Z0-9]';
        $search .= '!@#$%^&*()';
        $search .= '~`";:?+/={}[]-\\_|\'';
        for ($i = 0; $i < strlen($search); $i++) {
            $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
            $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
        }
        $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'object', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'cript', 'scri', 'scrip', 'cript');
        $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);

        $found = true;
        while ($found) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
                $val = preg_replace($pattern, $replacement, $val);
                if ($val_before == $val) {
                    $found = false;
                }
            }
        }
        return $val;
    }

    /**
     * Security Fix
     * @param $var
     * @return int
     */
    public function toInt($var) {
        $this->var = $var;
        $this->var = preg_replace('/[^0-9,.]+/i', '', $this->var);
        $this->var = htmlentities($this->var);
        return (int) $this->var;
    }
	
	public function __toString() {
		return (string)$this->var;
	}
	
	public function __destruct() {
		unset ($this->var);
	}
	
}
