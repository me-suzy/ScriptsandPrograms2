<?php
  /**
    * $Id: tickets.class.php,v 1.7 2005/07/18 13:10:30 carsten Exp $
    *
    * Model for users. See easy_framework for more information
    * about controlers and models.
    * @package common
    */
    
  /**
    *
    * Users Model Class
    * @package common
    */
    class tickets_tab {

        var $tab_nr                     = null;
        var $locked                     = null;
        var $img_path                   = null;
        var $currentObjectID            = null;
        
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
        function tickets_tab ($id, $locked) {
            $this->currentObjectID   = $id;
            $this->locked            = $locked;
        }
        
        function setImgPath ($path) {
            $this->img_path = $path;    
        }    
                
        function setTabNr ($nr) {
            $this->tab_nr = $nr;    
        }    
        
        function getSubmitCode () {

            $html = '';
            if (!$this->locked) {
                $html .= "
                    <tr class='line'><td colspan=5><hr></td></tr>
                    <tr class='line'>
                        <td colspan=5>
                            <input type=submit class=submit name='submit_me' onClick='javascript:something_changed=false;' value='".translate('save')."'>&nbsp;&nbsp;
                            <input type=submit class=submit name='apply'     onClick='javascript:run_apply (".$this->tab_nr.");' value='".translate('apply')."'>&nbsp;&nbsp;
                        </td>
                    </tr>
                	";
            }
            return $html;
        }        	
        
       /**
        * 
        * Overwrites column of datagrid accoring to existing rows
        *
        * @access      private
        * @param       mixed array of arrays holding datagrid data
        * @param       mixed array of further paramenters
        * @return      string to be placed in accoring row / column of the datagrid
        */
        function show_headline ($row, $params) {
            
            list ($offset, $img_path) = $params;
    
            $name    = $row[1]['initial_data'];         
            $memo_id = $row[0]['initial_data'];         
            $access  = $row[9]['initial_data'];
    
            $locked_res = mysql_query ("SELECT user_id FROM ".TABLE_PREFIX."useronline WHERE object_type='ticket' 
                                        AND object_id=".$memo_id);
            $locked_row = mysql_fetch_array ($locked_res);
            
            //$link  = "<img src='".$img_path.get_access_icon ($access)."' title='".translate($access)."' align=top>";
            $link = "<a href='../../modules/tickets/index.php?command=edit_entry&entry_id=$memo_id'>";
            $link .= "$name</a>";
            
            if ($locked_row != null) {
                $pic = $img_path."locked.gif";
                if ($locked_row['user_id'] == $_SESSION['user_id'])
                    $pic = $img_path."locked_green.gif";
                $link .= "<img src='$pic' title='".translate ('locked by')." ".get_username_by_user_id ($locked_row['user_id'])."'>";    
            }
    
            return $link;
        }


        function getHtmlCode () {
        
            require_once ("../../modules/tickets/models/tickets_mdl.php");
            require_once ("../../inc/easy_framework/classes/lib/class.datagrid2list.php");
            
            $smarty = null;
            $auth   = null;
            
            $tickets_model = new tickets_model ($smarty, $auth);
            $params = array ('filter_contact_id' => $this->currentObjectID);
            
            $tickets_model->show_entries ($params);
            $dg = $tickets_model->dg;
    
            $dg->hide_column(0); // memo_id
            $dg->setPrimary (0, true);
            
            $dg->SetColumnTitle (1, translate ('headline'));  
            $dg->SetColumnWidth (1, 190);  
            $dg->recalc_column  (1, array ($this, "show_headline"), array (1, $this->img_path));

            $dg->SetColumnTitle (2, translate ('content'));  
            $dg->SetColumnWidth (2, 340);  

            $dg->hide_column(3); // creator
            $dg->hide_column(5); // grp
            $dg->hide_column(6); // grp
            $dg->hide_column(7); // last_changer
            $dg->hide_column(8); // last_changer
            $dg->hide_column(9); // access_level
        
            $dg->hide_column(10); // access_level
            $dg->hide_column(11); // is_dir

            $dg->hide_column(15); // color

            $renderer =  new datagrid2list ($dg);
            $renderer->setTranslations(
                array (
                    "hits"            => translate('hits'),
                    "showing entries" => translate('showing entries from'),
                    "per page"        => translate('per page')
                )
            );
    
            $html  =  "<table><tr><td>";
            $html .= "<a href='../../modules/tickets/index.php?command=add_ticket_view&contact=".$this->currentObjectID."'>";
            $html .= translate ('new ticket')."</a>";
            $html .= "</td></tr></table>\n";
            $html .= $renderer->getHtmlCode(false, "show_entries");
            $html .= $this->getSubmitCode();    
	        $html .= "
            	<tr><td colspan=5 valign='top'><hr></td></tr>
	            </table>
            ";
        
            return $html;    
        }    

    }

?>
