/**
 * ProgressBar for jQuery
 *
 * @version 1 (29. Dec 2012)
 * @author Ivan Lazarevic
 * @requires jQuery
 * @see http://workshop.rs
 *
 * @param  {Number} percent
 * @param  {Number} $element progressBar DOM element
 * ==========================================
 * (c) 2013
 * Adaptado para BaleroCMS 0.2+ por Anibal Gomez (anibalgomez@icloud.com)
 * Bifurcacion de "ProgressBar for jQuery" (https://github.com/kopipejst/progressbar)
 * balerocms.com
 *
 */
function progressBar(percent, $element) {
	var progressBarWidth = percent * $element.width() / 100;
	$element.find('div').animate({ width: progressBarWidth }, 30000).html(percent + "%&nbsp;");
}