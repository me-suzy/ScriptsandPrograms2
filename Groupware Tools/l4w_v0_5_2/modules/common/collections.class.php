<?php
  /**
    * $Id: collections.class.php,v 1.11 2005/07/14 06:01:22 carsten Exp $
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
    class collections_tab {

        var $tab_nr                     = null;
        var $locked                     = null;
        var $img_path                   = null;
        var $currentObjectID            = null;
        var $currentObjectType          = null;
        var $selectedCategories         = null;
        
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
        function collections_tab ($id, $type, $locked) { 
            
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

		function loadActiveCategories () {
			$arr = array ();
			
			if ($this->currentObjectID > 0) {
                $query = "SELECT 
                            collection_id, 
                            name, 
                            ".TABLE_PREFIX."collections.description 
                          FROM ".TABLE_PREFIX."refering 
                          LEFT JOIN ".TABLE_PREFIX."collections ON ".TABLE_PREFIX."refering.to_object_id=".TABLE_PREFIX."collections.collection_id
                          WHERE from_object_type='".$this->currentObjectType."' AND 
                                from_object_id=".$this->currentObjectID." AND
                                to_object_type='collection'
                          ORDER BY to_object_type";

                $res   = mysql_query ($query);
                //$i     = 0;
                while ($row = mysql_fetch_array ($res)) {	
                	//$arr[$i]['collection_id'] = $row['collection_id'];
                	//$arr[$i]['name']          = $row['name'];
                	//$arr[$i]['description']   = $row['description'];
                	$arr[] = $row['collection_id'];
                	//$i++;
                }
			}
			
			$this->selectedCategories = $arr;
		}
		
        function getCollectionsSelection () {
            $html = '';
            
            // get main categories
            $query = "
                SELECT col.* FROM ".TABLE_PREFIX."collections col
                LEFT JOIN ".TABLE_PREFIX."category_component cc ON col.collection_id = cc.category_id
                LEFT JOIN ".TABLE_PREFIX."components com ON com.id=cc.component_id
                WHERE 
                	col.mandator=".$_SESSION['mandator']." AND 
                	parent=0 AND
                	module_name='".$this->currentObjectType."s'
                ORDER BY parent
                ";
            $main_res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);

            if (mysql_num_rows ($main_res) == 0) {
                return "no collections defined";   
            }    
            
            while ($main_row = mysql_fetch_array ($main_res)) {
            
                $html .= "
                	<tr class='line'>
                		<th  class='box' width=200>
                			".translate ($main_row['name'])."
                		</th>
                	    <td colspan=4>
                            <select name='addto_collection[]' size=4 style='width:300px' multiple>
                        ";
                
                $query = "
                    SELECT * FROM ".TABLE_PREFIX."collections
                    WHERE mandator=".$_SESSION['mandator']." AND
                          parent=".$main_row['collection_id']."
                    ORDER BY parent
                    "
                    ;
                        
                $res   = mysql_query ($query);
                logDBError (__FILE__, __LINE__, mysql_error(), $query);
                while ($row = mysql_fetch_array ($res)) {
                    $col_id = $row['collection_id'];
                    $chain  = $this->getCategoryChain ($col_id);
                    (in_array($col_id, $this->selectedCategories)) ?
                    	$sel = "selected" : $sel = "";
    				$html .= "<option value='$col_id' $sel>".$chain."</option>\n";
                }    
                
                $html .= "
                            </select>
                	    </td>
                	</tr>
                ";
            }
                    
            return $html;
        }    
        
        function getCategoryChain ($cat_id, $str = '') {

        	$query = "
                SELECT * FROM ".TABLE_PREFIX."collections
                WHERE collection_id=$cat_id 
                ";
            //echo $query."<br>";
            $res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            $row   = mysql_fetch_array($res);

            if ($row['parent'] == 0) 
            	return substr ($str,0,-4);

			$str = $row['name']." -> ".$str;
                       
            return $this->getCategoryChain ($row['parent'], $str);
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

        function getCollections () {
        
            $html = "
            	<tr class='line'>
            		<th  class='box' width=100>
            			".translate ('collections')."
            		</th>
            	    <td colspan=4>
                        <table border=0>
                    ";
                    
            if ($this->currentObjectID > 0) {
                $query = "SELECT 
                            collection_id, 
                            name, 
                            ".TABLE_PREFIX."collections.description 
                          FROM ".TABLE_PREFIX."refering 
                          LEFT JOIN ".TABLE_PREFIX."collections ON ".TABLE_PREFIX."refering.to_object_id=".TABLE_PREFIX."collections.collection_id
                          WHERE from_object_type='".$this->currentObjectType."' AND 
                                from_object_id=".$this->currentObjectID." AND
                                to_object_type='collection'
                          ORDER BY to_object_type";

                $res   = mysql_query ($query);
                while ($row = mysql_fetch_array ($res)) {
                                    
                    // --- sufficient rights ? ------------------------------
                    /*if (!user_may_read ($col_meta_values['owner'],$col_meta_values['grp'],$col_meta_values['access_level'])) {
                        continue;
                    }*/
                                                            
                    $html .= "<tr>";
                    $html .= "<td><span name='ref_collection_".$row['collection_id']."'>".$row['name']."</span></td>";
                    //if (user_may_edit ($col_meta_values['owner'],$col_meta_values['grp'],$col_meta_values['access_level'])) {
                        $html .= "<td><a href='javascript:delete_from_collection(\"".$this->currentObjectType."\",".$this->currentObjectID.",\"collection\",".$row['collection_id'].")'>";
                        $html .= "<img src='".$this->img_path."delete2.gif' border=0 title='".translate ('delete collection')."'></a></td>";
                    //}
                    $html .= "</tr>\n";
                    
                    // Show other entries in collection (if any):
                    /*$ref_query = "SELECT * FROM refering 
                              WHERE to_object_type='collection' AND 
                                    to_object_id=".$row['collection_id']." 
                              ORDER BY from_object_type";
                    $ref_res = mysql_query ($ref_query);
                    while ($ref_row = mysql_fetch_array ($ref_res)) {
                        // Find out rights about this entry:
                        $entry_values   = get_entries_for_primary_key (
                                                "memos", 
                                                array ("memo_id" => $ref_row['from_object_id']));
    
                        $ref_meta_values    = get_entries_for_primary_key(
                                                "metainfo", 
                                                array ("object_type" => 'note',
                                                       "object_id"   => $ref_row['from_object_id']));
    
                        // --- sufficient rights ? ------------------------------
                        if (!user_may_read ($ref_meta_values['owner'],$ref_meta_values['grp'],$ref_meta_values['access_level'])) {
                            continue;
                        }
                        $access_pic  = "<img src='".$img_path.get_access_icon ($ref_meta_values['access_level'])."' 
                                            title='".translate($ref_meta_values['access_level'])."' 
                                        align=top>";
                        echo "<tr>";
                        echo "<td colspan=2>&nbsp;&nbsp;&nbsp;&rarr;".$access_pic." ".translate ($ref_row['from_object_type'])."&nbsp;";
                        echo "<a ";
                        echo "href='../../modules/notes/index.php?command=edit_entry&entry_id=".$ref_row['from_object_id']."'>";
                        echo $entry_values['headline']."</a></td>";
                        //echo "<td>".translate ($ref_row['description'])."</td>";
                        echo "</tr>\n";
                    }*/                              
                        
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
        
        	$this->loadActiveCategories();
	        $html  = $this->getCollectionsSelection();    
            //$html .= $this->getCollections();    
            $html .= "
                <tr>
		            <td class='narrow' colspan=5><img src='".$this->img_path."shim.gif' height=1></td>
            	</tr>
                ";        
            return $html;    
        }    

    }

?>
