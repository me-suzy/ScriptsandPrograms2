<?php
  /**
    * $Id: workflow.class.php,v 1.6 2005/07/05 10:36:05 carsten Exp $
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
    class workflowHTML {

        var $tab_nr                     = null;
        var $img_path                   = null;
        var $currentObjectID            = null;
        var $currentObjectType          = null;
        var $identifier                 = null;
        
       /**
        * Constructor.
        *
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @package      easy_framework
        * @since        0.4.6
        * @version      0.4.6
        */
        function workflowHTML ($id, $type, $identifier = 'memo_id') { 
            $this->currentObjectID   = $id;
            $this->currentObjectType = $type;
            $this->identifier        = $identifier;
        }
        
        function setImgPath ($path) {
            $this->img_path = $path;    
        }    
                
        function setTabNr ($nr) {
            $this->tab_nr = $nr;    
        }    
        

        function showWorkflowSelection ($entry) {
            global $easy;

            if (($entry[$this->identifier]->get() > 0) && (module_enabled('workflow'))) {
                $selection = get_workflow_options ($this->currentObjectType, $entry['state']->get());
                if (is_null($selection)) {
                    echo "<input type=hidden name='state' value='-1'>\n";    
                    return;
                }      
            ?>
            
            	<!--<tr class="line"><td colspan=5><hr></td></tr>-->
            	<tr class="line">
            	    <td colspan=5>
            	        <a href='javascript:toggleDisplay("workflow_span")'><img src='<?=$this->img_path?>toggledisplay.gif' border=0></a>
					    &nbsp;<?=translate ('explain workflow')?>
            	    </td>
            	</tr>
            	
            	
            	
            	<tr>
                    <td colspan=5>
                    
                    <SPAN id='workflow_span' style='display:none'>
                    <table border=0 cellspacing=0 cellpadding=0>
                        <tr class="line">
                        <th class='box' colspan=2><?=translate('workflow')?>:</th>
            	        <td colspan=3>
            	            <select name='state' class='formular'>
            	            <?php
                                echo $selection;
            	            ?>
            	            </select>
            	            <a href='../../modules/stats/index.php?command=show_workflow_history&type=<?=$this->currentObjectType?>&id=<?=$this->currentObjectID?>' target='_blank'><img src='<?=$this->img_path?>stats.gif' align=top border=0></a>
            	        </td>
            	        </tr>
                    </table>
            	    </span>
            	    
            	    </td>
            	</tr>
                <tr>
				    <td class='narrow' colspan=5><img src='<?=$this->img_path?>shim.gif' height=1></td>
				</tr>            	
            	
            <?php
            }
        }
             
        
        /**
        * Execute queries and handle errors
        *
        * private function which should be called to execute database
        * queries. In case of an error the execution can be stopped and
        * a message can be assigned to the models error_msg attribute.
        * Same function as in leads4web_model
        * 
        * @access       private
        * @param        string query to execute
        * @param        string message to show in case of error
        * @param        boolean should execution be stopped in case of error
        * @return       ressource database resource on success, false on failure
        * @since        0.4.7
        * @version      0.4.7
        */
        function ExecuteQuery ($query, $msg, $stop_execution = true) {
            
            $res = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);

            if (mysql_error() != '') {
                if ($stop_execution) {
                    $this->error_msg = translate ($msg)." [".mysql_error()."]";
                    return false;
                }    
                else {
                    $this->info_msg = translate ($msg);                    
                }     
            }
    
           return $res;
        }


    }

?>