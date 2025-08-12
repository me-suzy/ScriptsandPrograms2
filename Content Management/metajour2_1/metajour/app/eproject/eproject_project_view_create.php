<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage view
 * $Id: eproject_project_view_create.php,v 1.4 2005/02/02 14:22:13 SYSTEM Exp $
 */

require_once($system_path.'basic_view_create.php');

class eproject_project_view_create extends basic_view_create {

	function customFields() {
		if (isset($this->data['layoutid'])) {
            
			$result .= '<table border="0"><tr><td>Aktiv</td><td>Afdeling/funktion</td><td>Færdig den</td><td>Besked til</td></tr>';
			$sobj = owNew('layoutelement');
			$sobj->listobjects($this->data['layoutid']);
			$z = 0;
			$fields = new basic_field($this);
			$fields->view->context->addHeader("<style type=\"text/css\">@import url(js/calendar/calendar-system.css);</style>");
            $fields->view->context->addHeader("<script     type=\"text/javascript\" src=\"js/calendar/calendar.js\"></script>\n");
            $fields->view->context->addHeader("<script type=\"text/javascript\" src=\"js/calendar/lang/calendar-da.js\"></script>\n");
            $fields->view->context->addHeader("<script type=\"text/javascript\" src=\"js/calendar/calendar-setup.js\"></script>\n");
            
            $r = $fields->listallobjects('user',0);
			while ($z < $sobj->elementscount) {
				$result .= '
				<tr>
				<td nowrap><input type="checkbox" name="aktiv[]" value="'.$z.'"></td>
				<td nowrap><input type="text" name="navn[]" value="'.$sobj->elements[$z]['name'] .'"></td>
				<td nowrap><input id=date'.$z.' type="text" name="afsluttet[]" value="" size="10" readonly><img src="image/cal/cal.gif" class="mButton" onmouseover="this.className=\'mButtonOver\'" onmouseout="this.className=\'mButton\'" style="vertical-align: top;" id="button_'.$z.'"></td>
				<td><select name="messageto[]">'.$r.'</select>
				</td>
				</tr>
				';                
                $fields->view->context->addFooter("<script type=\"text/javascript\">
					Calendar.setup(
					{
						inputField : \"date".$z."\",
						ifFormat : \"%Y-%d-%m\",
						button : \"button_".$z."\"
					}
					);
					</script>");                    
				$z++;
			}
			$result .= '</table>';
			$result = $this->makeField('Projektlayout',$result);
			return $result;
		}
	}
	
	
	function view() {
		if (!isset($this->data['layoutid'])) {
		$this->_obj = owNew($this->otype);
				$this->context->addHeader('<script type="text/javascript">'.$this->relatedFieldsHeader().'</script>');
		$this->_obj->initLayout();
		$result .= $this->viewStart();
		$result .= $this->titleBar();
		$result .= $this->buttonBar();
		$result .= $this->beforeForm();
		$result .= '<form name="metaform" id="metaform" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" style="spacing: 0px; margin: 0px; padding: 0px;">';
		$result .= $this->returnViewPost($this->view);
		$result .= '<input type="hidden" name="MAX_FILE_SIZE" value="2097152">';
		$result .= '<input type="hidden" name="view" value="create">';
		$result .= '<input type="hidden" name="otype" value="'.$this->otype.'">';
		if ($this->parentid) $result .= '<input type="hidden" name="_parentid" value="'.$this->parentid.'">';
		$r .= '<select name="layoutid">';
		$fieldobj = new basic_field($this);
		$r .= $fieldobj->listallobjects('layout',0);
		$r .= '</select>';
		$result .= $this->makeField('Vælg projektlayout',$r);
		$result .= '<br>';
		$result .= $this->endForm();
		$result .= $this->submitButtons();
		$result .= '</form>';
		$result .= $this->afterForm();
		$result .= '<br><br><br>';
		$result .= $this->viewEnd();;
		$result .= '<br><br><br>';
		return $result;
			
		} else {
			return parent::view();
		}
	}

}

?>