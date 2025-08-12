<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */
require_once('basic_view_list.php');

class basic_view_listcsv extends basic_view_list {
	
	function view() {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("Pragma: no-cache"); 
	header("Content-Type: text/plain");
	header("Content-Disposition: attachment; filename=downloaded.csv");
		$this->context->clearAll();
		#basic_collection::view();
		
		// Create our listing object
		$this->_listobj = owNew($this->otype);
		$obj =& $this->_listobj;
		
		// Apply userfilters (categories, search etc)
		$this->setFilters();
		
		if ($this->parentid) {
			$obj->listobjects($this->parentid);
		} else {
			$obj->listobjects(0);
		}

		$guisession =& $_SESSION['gui'][$this->otype];

### <grim kode> - kræver omstrukturering
if ($guisession['stattype'] == 'optael') {
		$z = 0;
		$res = array();
		$elementscount = $obj->elementscount;
		$xlist = array();
		$ylist = array();
		$mycols = owDatatypeColsDesc($this->otype);
		$field = new basic_field($this);
		while ($z < $elementscount) {
			$res[$obj->elements[$z][$guisession['groupby1']]][$obj->elements[$z][$guisession['groupby2']]] = $obj->elements[$z]['syscount'];
			$z++;
			if (!in_array($obj->elements[$z][$guisession['groupby2']],$xlist) && $obj->elements[$z][$guisession['groupby2']] != '') 
				$xlist[] = $obj->elements[$z][$guisession['groupby2']];

			if (!in_array($obj->elements[$z][$guisession['groupby1']],$ylist) && $obj->elements[$z][$guisession['groupby1']] != '')
				$ylist[] = $obj->elements[$z][$guisession['groupby1']];
		}
		arsort($xlist);
		arsort($ylist);
		// Output headers
		echo $x.";";
		foreach ($xlist as $x) {
			$text = $field->parsefield($mycols[$guisession['groupby2']],$x,IN_LIST);
			if (!$text) $text = '[IKKE ANGIVET]';
			echo $text.';';
		}
		echo "\n";
		
		// Output listing

		foreach ($ylist as $y) {
			$text = $field->parsefield($mycols[$guisession['groupby1']],$y,IN_LIST);
			if (!$text) $text = '[IKKE ANGIVET]';
			echo $text.';';

			foreach ($xlist as $x) {
				echo $res[$y][$x].';';
			}
			echo "\n";			
		}

### </grim kode>


} else {


	
		$z = 0;
		$elementscount = $obj->elementscount;
		
		// Hiddenfields is an array to cache the values from
		// basic_user::isFieldHidden()
		$hiddenfields = array();
		
		$mycols = owDatatypeColsDesc($this->otype);
		$field = new basic_field($this);
		
		$userhandler =& $this->userhandler;
		while ($z < $elementscount) {
			$element =& $obj->elements[$z];
			foreach ($guisession['cols'] as $curcol) {
				if (!isset($hiddenfields[$curcol])) {
					$fieldhidden = $userhandler->isFieldHidden($this->otype, $curcol);
					$hiddenfields[$curcol] = $fieldhidden;
				} else {
					$fieldhidden = $hiddenfields[$curcol];
				}
				
				if (!$fieldhidden) {
					echo '"' . str_replace('"', '""', $field->parsefield($mycols[$curcol],$obj->elements[$z][$curcol],IN_LIST)).'";';
				}
			
			}
			if ($z < $elementscount-1) echo "\n";
			$z++;
		}
		
	}
}

}

?>