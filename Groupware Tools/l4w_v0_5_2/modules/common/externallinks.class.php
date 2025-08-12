<?php
  /**
    * $Id: externallinks.class.php,v 1.2 2005/04/03 06:30:10 carsten Exp $
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
    class externallinks_tab {

        var $tab_nr                     = null;
        var $locked                     = null;
        var $img_path                   = null;
        var $currentObjectID            = null;
        var $currentObjectType          = null;
        
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
        function externallinks_tab ($id, $type, $locked) { 
            
            $this->locked            = $locked;
            $this->currentObjectID   = $id;
            $this->currentObjectType = $type;
        }
        
        function setImgPath ($path) {
            $this->img_path = $path;    
        }    
                
        function setTabNr ($nr) {
            $this->tab_nr = $nr;    
        }    

        function addLinks () {
            $html = "
            	<TABLE class='frame'>
            	<tr class='line'>
            		<th  class='box' width=200>
            			".translate ('add to external links')."
            		</th>
            	    <td colspan=4>
            	    <a href='javascript:add_external_link(\"".$this->currentObjectType."\", ".$this->currentObjectID.")'>".translate('click here')."</a>
            	    </td>
            	</tr>
            ";
        
            return $html;
        }    

        function getReadonlyLinksLine () {
            $html = "
            	<tr class='line'>
		            <th  class='box' width=200>
            			".translate ('added links')."
            		</th>
            	    <td colspan=4>
                        <select name='added_links' size=4 style='width:500px' readonly>
                        </select>
            	    </td>
            	</tr>
            ";
            return $html;
        }    
        
        function getDescriptionLine () {
            $html = "
            	<tr class='line'>
            		<th  class='box' width=100>
            			".translate ('description')."
            		</th>
            	    <td colspan=4>
                        <input type=text name='ref_desc' style='width:300px' value=''>
            	    </td>
            	</tr>
            ";
            return $html;
        }    

        function getexternallinks () {
        
            $html = "
            	<tr class='line'>
            		<th  class='box' width=100>
            			".translate ('externallinks')."
            		</th>
            	    <td colspan=4>
                        <table border=0>
                    ";
                    
            if ($this->currentObjectID > 0) {
                $query = "SELECT 
                            collection_id, 
                            name, 
                            externallinks.description 
                          FROM ".TABLE_PREFIX."refering 
                          LEFT JOIN ".TABLE_PREFIX."externallinks ON ".TABLE_PREFIX."refering.to_object_id=".TABLE_PREFIX."externallinks.collection_id
                          WHERE from_object_type='".$this->currentObjectType."' AND 
                                from_object_id=".$this->currentObjectID." AND
                                to_object_type='collection'
                          ORDER BY to_object_type";
    
                $res   = mysql_query ($query);
                while ($row = mysql_fetch_array ($res)) {
                
                    // Find out rights about this entry:
                    $col_meta_values    = get_entries_for_primary_key(
                                            "metainfo", 
                                            array ("object_type" => 'collection',
                                                   "object_id"   => $row['collection_id']));
                    
                    // --- sufficient rights ? ------------------------------
                    if (!user_may_read ($col_meta_values['owner'],$col_meta_values['grp'],$col_meta_values['access_level'])) {
                        continue;
                    }
                    $access_pic  = "<img src='".$this->img_path.get_access_icon ($col_meta_values['access_level'])."' 
                                        title='".translate($col_meta_values['access_level'])."' 
                                        align=top>";
                                                            
                    $html .= "<tr>";
                    $html .= "<td><span name='ref_collection_".$row['collection_id']."'>".$access_pic.$row['name']."</span></td>";
                    if (user_may_edit ($col_meta_values['owner'],$col_meta_values['grp'],$col_meta_values['access_level'])) {
                        $html .= "<td><a href='javascript:delete_from_collection(\"".$this->currentObjectType."\",".$this->currentObjectID.",\"collection\",".$row['collection_id'].")'>";
                        $html .= "<img src='".$this->img_path."delete2.gif' border=0 title='".translate ('delete collection')."'></a></td>";
                    }
                    else
                        $html .= "<td>&nbsp;</td>";
                    $html .= "</tr>\n";
                   
                }                              
            }        
            $html .= "
                        </table>
            	    </td>
            	</tr>
                       ";
                                       
            return $html; 
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

        function getHtmlCode () {
        
            $html  = $this->addLinks();
            $html .= $this->getReadonlyLinksLine();    
            $html .= $this->getexternallinks();    
            $html .= $this->getSubmitCode();    
	        $html .= "
            	<tr><td colspan=5 valign='top'><hr></td></tr>
	            </table>
            ";
        
            return $html;    
        }    
        
        function showAddLinksForm () {
            $html = "
            	<TABLE class='frame'>
            	<tr class='line'>
            		<th  class='box' width=200>
            			".translate ('add to external links')."
            		</th>
            	    <td colspan=4>
                        &nbsp;<br><br>
            	    </td>
            	</tr>
            	<tr class='line'>
            		<th  class='box' width=200>
            			".translate ('link')." 1
            		</th>
            	    <td colspan=4>
                        <input type='text' name='link1' value='' size=70>
            	    </td>
            	</tr>
            	<tr class='line'>
            		<th  class='box' width=200>
            			".translate ('link')." 2
            		</th>
            	    <td colspan=4>
                        <input type='text' name='link2' value='' size=70>
            	    </td>
            	</tr>
            	<tr class='line'>
            		<th  class='box' width=200>
            			".translate ('link')." 3
            		</th>
            	    <td colspan=4>
                        <input type='text' name='link3' value='' size=70>
            	    </td>
            	</tr>
            	</table>
            ";
        
            return $html;
        }    

    }

?>
