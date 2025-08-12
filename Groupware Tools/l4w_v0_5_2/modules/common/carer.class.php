<?php
  /**
    * $Id: carer.class.php,v 1.2 2005/05/27 08:00:22 carsten Exp $
    *
    * Model for users. See easy_framework for more information
    * about controlers and models.
    * @package common
    */
    
  /**
    *
    * Users Model Class
    
    */
    class carerHTML {

        var $tab_nr                     = null;
        var $img_path                   = null;
        var $currentObjectID            = null;
        var $currentObjectType          = null;
        //var $identifier                 = null;
        
       /**
        * Constructor.
        *
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @package      easy_framework
        * @since        0.4.7
        * @version      0.4.7
        */
        function carerHTML ($id, $type) { 
            $this->currentObjectID   = $id;
            $this->currentObjectType = $type;
        }
        
        function setImgPath ($path) {
            $this->img_path = $path;    
        }    
                
        function setTabNr ($nr) {
            $this->tab_nr = $nr;    
        }    

        function showCarerSelection ($meta_values, $entry) {
            global $easy, $gacl_api;
            
            ?>
            
            <tr class="line">
				<th  class='box' width=100>
					<?=translate ('carer')?>
				</th>
	    		<td colspan=4>
	    		
		        	<?php   if ($meta_values['owner'] == $_SESSION['user_id']) { ?>
		        	<select name='owner'>
		            	<?php
		            		list ($valid_owner, $cnt) = get_carer_options ($entry['owner']->get());
		            		echo $valid_owner;
		            	?>
		        	</select>
		        	<?php } else { 
		                echo get_username_by_user_id ($meta_values['owner']);
		            } ?>
	    		
	    		</td>
			</tr>	
            <?php
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