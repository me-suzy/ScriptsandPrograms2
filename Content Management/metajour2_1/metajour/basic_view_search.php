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

class basic_view_search extends basic_view_list {

	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('basic_view_search');
	}

	function categoryBar() {
	}

	function searchBar($index=0,$colfname='searchcol', $compfname='searchtype', $textfname='search') {
		$guisession =& $_SESSION['gui'][$this->otype];
		echo "<input type='hidden' name='_DONTCONVERT_' value=1>";
		echo $this->makeField($this->gl('sttext_1'),parent::searchBar(0,'searchcol[]','searchtype[]','search[]'));
		echo $this->makeField($this->gl('sttext_2'),parent::searchBar(1,'searchcol[]','searchtype[]','search[]'));
		echo $this->makeField($this->gl('sttext_3'),parent::searchBar(2,'searchcol[]','searchtype[]','search[]'));
		echo $this->makeField($this->gl('sttext_4'),parent::searchBar(3,'searchcol[]','searchtype[]','search[]'));
		echo $this->makeField($this->gl('sttext_5'),parent::searchBar(4,'searchcol[]','searchtype[]','search[]'));
		echo $this->makeField($this->gl('sttext_7'),'<input type="text" style="width: 144px" name="createdfrom" id="createdfrom" value="'.$_SESSION['gui'][$this->otype]['createdfrom'].'" readonly>&nbsp;'
		                    . '<img src="image/cal/cal.gif" id="button_cal1" onmouseover="this.className=\'mButtonOver\'" onmouseout="this.className=\'mButton\'"class="mButton">');
		echo $this->makeField($this->gl('sttext_8'),'<input type="text" style="width: 144px" name="createdto" id="createdto" value="'.$_SESSION['gui'][$this->otype]['createdto'].'" readonly>&nbsp;'
		                    . '<img src="image/cal/cal.gif" id="button_cal2" onmouseover="this.className=\'mButtonOver\'" onmouseout="this.className=\'mButton\'"class="mButton">');
		$statistikvalg = '<select name="stattype" id="stattype" style="width: 151px;">';
		$s = ($guisession['stattype'] == 'liste') ? 'selected':'';
		$statistikvalg .= '<option value="liste" '.$s.'>Liste</option>';
		$s = ($guisession['stattype'] == 'optael') ? 'selected':'';
		$statistikvalg .= '<option value="optael" '.$s.'>Optælling</option>';
		$statistikvalg .= '</select>';
		echo $this->makeField('Statistik type',$statistikvalg);
		echo $this->makeField($this->gl('sttext_6'),$this->colsBar());
		echo $this->makeField('Optæl (X)',$this->colBar('groupby2',$guisession['groupby2']));
		echo $this->makeField('Fordelt på (Y)',$this->colBar('groupby1',$guisession['groupby1']));
	}

	function colsBar($colfname='setlistcolarray[]') {
		$dtcols = owDatatypeColsDesc($this->otype);
		$result = '<select name="'.$colfname.'" id="colsbar" style="width:463px;" multiple size=10>';
		$obj = owNew($this->otype);
		foreach ($dtcols as $cur) {
			if ($cur['inputtype'] != UI_HIDDEN) {
				$s = '';
				if (in_array($cur['name'],$_SESSION['gui'][$this->otype]['cols'])) $s = ' SELECTED';
				$result .= '<option value="'.$cur['name'].'" '.$s.'>Feltet '.$cur['label'].'</option>';
			}
		}
		$result .= '</select>';
		return $result;
	}

	function colBar($colfname,$value) {
		$dtcols = owDatatypeColsDesc($this->otype);
		$result = '<select name="'.$colfname.'" id="'.$colfname.'" style="width:463px;">';
		$obj = owNew($this->otype);
		foreach ($dtcols as $cur) {
			if ($cur['inputtype'] != UI_HIDDEN) {
				$s = '';
				if ($cur['name'] == $value) $s = ' SELECTED';
				$result .= '<option value="'.$cur['name'].'" '.$s.'>Feltet '.$cur['label'].'</option>';
			}
		}
		$result .= '</select>';
		return $result;
	}
	
	function toolBar() {
		ob_start();
		echo '<table border="0" cellpadding="0" cellspacing="0"><tr><td valign="top">';
		echo '<form name="listsearch" method="POST" style="margin:0px; spacing:0px; clear: none;" action="'.$this->ReturnMeUrl().'">';

		$this->categoryBar();
		
		echo $this->searchBar();

		echo '<input type="hidden" name="otype" value="'.$this->otype.'">';
		echo '</form>';
		echo '</td><td valign="top" style="padding-left: 5px;">';
		echo $this->buttonOnClick('search_small.png',$this->gl('img_search'),'document.listsearch.submit()');
		echo $this->buttonOnClick('fileexport.png', $this->gl('img_search_save'), $this->modalDialog('savedsearch', '', '', 'create', 'jswindowclose', '', 'parentotype=' . $this->otype));
		echo $this->buttonOnClick('fileopen.png', $this->gl('img_search_load'), $this->modalDialog('savedsearch', '', '', 'load', 'gotosearch,jswindowclose', '', 'parentotype=' . $this->otype));
		echo $this->button('list_small.png',$this->gl('img_listall'),$this->ReturnMeUrl().'&clearall=1');
		if ($this->offset > 0) echo $this->button('prev_small.png', 'prev', $this->returnMeUrl() . '&offset=' . ($this->offset - LIMIT) . '&count=' . LIMIT);
		if ($this->offset + $this->limit < $this->totalcount) echo $this->button('next_small.png', 'next', $this->returnMeUrl() . '&offset=' . ($this->offset + $this->limit) . '&count=' . $this->limit);
		echo $this->button('listcsv_small.png','CSV export',$this->callgui($this->otype,'','','listcsv'));
		echo '</td></tr></table>';
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}


	function addLookupJS() {
		$result = "<script type=\"text/javascript\">\n";
		$result .= "lookup = new Object();\n";
		$columns = owDatatypeColsDesc($this->otype);
		foreach ($columns as $column) {
			$columnvalues = owGetMasterColumnValues($this->otype, $column['name']);
			if (count($columnvalues) > 0 && count($columnvalues) < 20) {
				$result .= "lookup['" . $column['name'] . "'] = new Array();\n";
				foreach ($columnvalues as $value) {
					$result .= "lookup['" . $column['name'] . "'].push('" . 
						str_replace("'", "\'", str_replace("\n", '\n', str_replace("\r\n", "\n", $value)))
						. "');\n";
				}
			}
		}
				
		$result .= "function createvaluefield(keyfield, valuefield) {\n";
		$result .= "\tvar keyname = keyfield.options[keyfield.selectedIndex].value;\n";
		$result .= "\tvar valuefieldname = valuefield.name;\n";
		$result .= "\tvar origvalue = valuefield.value;\n";
		$result .= "\tif (lookup[keyname]) {\n";
		$result .= "\t\tvar string = '<select id=\"' + valuefieldname + '\" name=\"' + valuefieldname + '\" style=\"width: 160px\">';\n";
		$result .= "\t\tstring = string + '<option></option>';\n";
		$result .= "\t\tfor (var value in lookup[keyname]) {\n";
		$result .= "\t\t\tstring = string + '<option value=\"' + lookup[keyname][value] + '\"'\n";
		$result .= "\t\t\tif (origvalue == lookup[keyname][value]) string = string + 'selected';\n";
		$result .= "\t\t\tstring = string + '>' + lookup[keyname][value] + '</option>';\n";
		$result .= "\t\t}\n";
		$result .= "\t\tstring = string + '</select>';\n";
		$result .= "\t\tvaluefield.outerHTML = string;\n";
		$result .= "\t} else {\n";
		$result .= "\t\t valuefield.outerHTML = '<input type=\"text\" id=\"' + valuefieldname + '\" name=\"' + valuefieldname + '\" style=\"width: 154px\">';\n";
		$result .= "\t}\n";
		$result .= "}\n";
		
		$result .= "function setupLookup() {\n";
		$result .= "\tvar selectfields = document.getElementsByName('searchcol[]');\n";
		$result .= "\tvar valuefields = document.getElementsByName('search[]');\n";
		$result .= "\tselectfields[0].onchange = function() { createvaluefield(selectfields[0], valuefields[0]); }\n";
		$result .= "\tselectfields[1].onchange = function() { createvaluefield(selectfields[1], valuefields[1]); }\n";
		$result .= "\tselectfields[2].onchange = function() { createvaluefield(selectfields[2], valuefields[2]); }\n";
		$result .= "\tselectfields[3].onchange = function() { createvaluefield(selectfields[3], valuefields[3]); }\n";
		$result .= "\tselectfields[4].onchange = function() { createvaluefield(selectfields[4], valuefields[4]); }\n";
		$result .= "\tselectfields[0].onchange()\n";
		$result .= "\tselectfields[1].onchange()\n";
		$result .= "\tselectfields[2].onchange()\n";
		$result .= "\tselectfields[3].onchange()\n";
		$result .= "\tselectfields[4].onchange()\n";
		
		$result .= "\t}\n";
$result .= "
function setupStattype() {
	var typefield = document.getElementById('stattype');
	typefield.onchange = function(){
		if (typefield.options[typefield.selectedIndex].value != 'liste') {
			var listefield = document.getElementById('colsbar');
			listefield.parentElement.parentElement.style.display = 'none';
			var groupby1field = document.getElementById('groupby1');
			groupby1field.parentElement.parentElement.style.display = 'block';
			var groupby2field = document.getElementById('groupby2');
			groupby2field.parentElement.parentElement.style.display = 'block';
		} else {
			var listefield = document.getElementById('colsbar');
			listefield.parentElement.parentElement.style.display = 'block';
			var groupby1field = document.getElementById('groupby1');
			groupby1field.parentElement.parentElement.style.display = 'none';
			var groupby2field = document.getElementById('groupby2');
			groupby2field.parentElement.parentElement.style.display = 'none';
		}
	}
	typefield.onchange();
}
";

		$this->context->addOnload('setupLookup');
		$this->context->addOnload('setupStattype');
		
		
		$result .= "</script>";
		
		
		return $result;
		
	}
	
	function view() {
		$this->context->addHeader("<style type=\"text/css\">@import url(js/calendar/calendar-system.css);</style>");
		$this->context->addHeader("<script type=\"text/javascript\" src=\"js/calendar/calendar.js\"></script>\n");
		$this->context->addHeader("<script type=\"text/javascript\" src=\"js/calendar/lang/calendar-da.js\"></script>\n");
		$this->context->addHeader("<script type=\"text/javascript\" src=\"js/calendar/calendar-setup.js\"></script>\n");
		$this->context->addHeader($this->addLookupJS());
		parent::view();
		$this->context->addFooter("<script type=\"text/javascript\">
		Calendar.setup(
		{
			inputField : \"createdfrom\",
			ifFormat : \"%Y-%m-%d\",
			button : \"button_cal1\"
		}
		);
		Calendar.setup(
		{
			inputField : \"createdto\",
			ifFormat : \"%Y-%m-%d\",
			button : \"button_cal2\"
		}
		);
		</script>\n");
	}
	
}
?>