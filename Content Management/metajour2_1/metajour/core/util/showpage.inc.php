<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage util
 */

##################################################################################
#  TIMER
##################################################################################
class c_Timer {
	var $t_start = 0;
	var $t_stop = 0;
	var $t_elapsed = 0;

	function start() { $this->t_start = microtime(); }

	function stop()  { $this->t_stop  = microtime(); }

	function elapsed() {
		if ($this->t_elapsed) {
			return $this->t_elapsed;
		} else {
			$start_u = substr($this->t_start,0,10); $start_s = substr($this->t_start,11,10);
			$stop_u  = substr($this->t_stop,0,10);  $stop_s  = substr($this->t_stop,11,10);
			$start_total = doubleval($start_u) + $start_s;
			$stop_total  = doubleval($stop_u) + $stop_s;

			$this->t_elapsed = $stop_total - $start_total;

			return $this->t_elapsed;
        }
    }
}

##################################################################################


function myaddslashes($string) {
	return (get_magic_quotes_gpc()) ? $string : addslashes($string);
}

function output_http500($msg = "") {
	header("HTTP/1.0 500 Server Error");
	?>
	<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
	<HTML><HEAD><TITLE>500 Internal Server Error</TITLE></HEAD>
	<BODY><H1>500 Internal Server Error</H1><?php echo $msg ?><P></BODY>
	</HTML>
	<?php
	die();
}

function output_http404($msg = "") {
	header("HTTP/1.0 404 Not Found");
	?>
	<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
	<HTML><HEAD><TITLE>404 Page Not Found</TITLE></HEAD>
	<BODY><H1>404 Page Not Found</H1><?php echo $msg ?><P></BODY>
	</HTML>
	<?php
	die();
}

?>