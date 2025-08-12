<?php
  /**
    * $Id: parentchain.class.php,v 1.1 2005/03/24 12:13:40 carsten Exp $
    *
    * Model for users. See easy_framework for more information
    * about controlers and models.
    * @package stats
    */
    
  /**
    *
    * Users Model Class
    * @package stats
    */
    class parentChain {

        var $tab_nr      = null;
        var $img_path    = null;
        var $myModel     = null;
        
       /**
        * Constructor.
        *
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @package      common
        * @since        0.4.7
        * @version      0.4.7
        */
        function parentChain (&$model) { 
            $this->myModel = $model;
        }
        
        function setImgPath ($path) {
            $this->img_path = $path;    
        }    
                
        function setTabNr ($nr) {
            $this->tab_nr = $nr;    
        }    
        

        function getHTML ($start) {
            global $easy;
            
            $html = "
                <table>
                <tr>
        	        <td>";
        	    
			$chain = $this->myModel->getParentChain($start);
			
			$cnt = count($chain);
       		$chain[$cnt]['name']   = '<img src="'.$this->img_path.'openfolder.gif" border=0>';
            $chain[$cnt]['id']     = 0;     
        			
            for ($i=count($chain)-1; $i>=0; $i--) {
            	if ($i == count($chain)-1) {
                    $html .= " <a href='index.php?command=show_entries&";
                    $html .=  "parent_id=".$chain[$i]['id']."'>".$chain[$i]['name']."</a>";    
            	}
            	elseif ($i==0){
                    $html .= " &rarr; <font color='#000066'><b>".$chain[$i]['name']."</b></font>";    
            	}
            	else {
                    $html .= " &rarr; <a href='index.php?command=show_entries&";
                    $html .="parent_id=".$chain[$i]['id']."'>".$chain[$i]['name']."</a>";    
            	}
             }
            
        	$html .= "	
        	        </td> 	
                </tr>
                </table>
                ";
                
            return $html;
        }
                     
    }

?>