<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jesper Laursen <jl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage model
 */
 require_once('basic_model.php');

class basic_model_deletetrash extends basic_model {
    
    var $msg;
    
    function returnMsg($msg) {
        $this->msg[] = $msg;
        $_SESSION['msg'] = $this->msg;
    }
    
	function model() {
        
        if (!empty($this->data['coreclass'])) {
            $dataarray = array($this->data['coreclass']);
        } else {
            $dataarray = owListCore();
        }
        
        foreach ($dataarray as $datatype) {
            $obj = owNew($datatype);
            $obj->setfilter_deleted(true);
            $obj->listObjects();
                               
            if ($obj->elementscount > 0) {
                foreach($obj->elements as $element) {
                    $objarr[] = $element['objectid'];
                }
                $this->returnMsg(array($datatype => $obj->eraseObject($objarr)));
            } else {
                $this->returnMsg(array($datatype => 0));   
            }
        }        
        return true;
	}
}

?>
