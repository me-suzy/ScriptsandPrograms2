<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jesper Laursen <jl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */
 
require_once('basic_view.php');

class sys_view_cleartrashcan extends basic_view {

	function loadLanguage() {
		basic_view::loadLanguage();
		$this->loadLangFile('sys_view_cleartrashcan');
	}
    
    function titleBar() {
		return '<div class="metatitle">'.$this->shadowtext($this->gl('title')).'</div>';
	}

	function viewStart() {
		return '<div class="metawindow">';
	}
	
	function viewEnd() {
		return '</div>';
	}
    
    function getMsg() {
        $return = '';
        if (!empty($_SESSION['msg'])) {            
            $return .= '<b>'.$this->gl('text_2').': </b><table>';
            foreach($_SESSION['msg'] as $datatype) {                
                $return .= sprintf('<tr><td>%s:</td><td>%s elmenter</td></tr>', key($datatype), $datatype[key($datatype)]);
            }
            $return .= '</table>';
            $_SESSION['msg'] = '';
        }        
        return $return;
    }
    
    function listCoreClass($value = '') {
		$res = '<option value="">'.$this->gl('select_all').'</option>';
	    $datatypes = owListCore();
	    $dt = array();
		foreach ($datatypes as $cur) {
			$idx = owDatatypeDesc($cur);
			$dt[$idx]['datatype'] = $cur;
			$dt[$idx]['datatypename'] = $idx;
			$dt[$idx]['selected'] = '';
			if ($cur == $value) $dt[$idx]['selected'] = ' SELECTED';
		}
		ksort($dt);
		foreach ($dt as $cur) {
			$res .='<option value="'.$cur['datatype'].'"'.$cur['selected'].'>'.$cur['datatypename']."\n";
		}
		return $res;
	}
    
    function view() {
        $result .= $this->viewStart();
		$result .= $this->titleBar();
        $result .= '<div style="margin: 10px;"><br />';
        
        $result .= $this->getMsg();
        
        $result .= '<form name="metaform" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data"  style="margin: 0px; padding: 0px;">';
        $result .= '<input type="hidden" name="cmd" value="deletetrash">';
        $result .= '<input type="hidden" name="otype" value="sys">';
		$result .= $this->returnMePost();
               
        $result .= '<select name="coreclass">';
        $result .= $this->listCoreClass($this->data['coreclass']);
        $result .= '</select>';
        
        $result .= '<div style="padding-bottom: 14px;">';
		$result .= '<input id="submit1" name="submit1" type="submit" class="mformsubmit" value="'.$this->gl('text_1').'">';
		$result .= '</div>';
        $result .= '</form>';
        
        $result .= '<br /><br /><br /></div>';
        $result .= $this->viewEnd();
        return $result;
    }
}
?>
