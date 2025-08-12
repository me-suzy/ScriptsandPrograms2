<?php
  /**
    * $Id: references.class.php,v 1.14 2005/07/18 13:10:30 carsten Exp $
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
    class references_tab {

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
        function references_tab ($id, $type, $locked) { 
            
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

        function getReferenceSelection () {
            $html = "
                <tr class='line'>
		            <th  class='box' width=100>
			            ".translate ('add reference')."
            		</th>
	                <td colspan=4>
                        <select name='reference'>
                            <option value='note'>".translate ('note', null, true)."</option>
                    ";        
            if (module_enabled ('tickets')) 
                $html .= "<option value='ticket'>".translate ('ticket', null, true)."</option>";
            if (module_enabled ('todos')) 
                $html .= "<option value='todo'>".translate ('todo', null, true)."</option>";
            $html .= "
                        </select>
                    <a href='javascript:add_reference(".$this->currentObjectID.")'>
                        <img src='".$this->img_path."run.gif' border=0 title='".translate ('execute', null, true)."' align=top>
	                </a>
            	    </td>
	            </tr>";
        
            return $html;
        }    
        
        function getReadonlyCollectionLine () {
            $html = "
            	<tr class='line'>
		            <th  class='box' width=200>
            			".translate ('added references')."
            		</th>
            	    <td colspan=4>
                        <select name='added_references' size=4 style='width:300px' readonly>
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

        function getReferences($text, $res, $ID_identifier, $type_identifier) {
                
            $html = "
                	<tr class='line'>
                		<th  class='box'>".$text."</th>
    	                <td colspan=4>
                            <table border=0 cellpadding=2>
                ";
            while ($row = mysql_fetch_array ($res)) {
                
                switch ($row[$type_identifier]) {
                    case "note": 
                        $type_pic   = "notes.gif";
                        $tablename  = "memos"; 
                        $primaryKey = "memo_id";
                        $headline   = "headline";
                        $href       = "../../modules/notes/index.php";
                        break;
                    case "ticket": 
                        $type_pic   = "tickets.gif";
                        $tablename  = "tickets"; 
                        $primaryKey = "ticket_id";
                        $headline   = "theme";
                        $href       = "../../modules/tickets/index.php";
                        break;
                    case "todo": 
                        $type_pic   = "todos.gif";
                        $tablename  = "memos"; 
                        $primaryKey = "memo_id";
                        $headline   = "headline";
                        $href       = "../../modules/todos/index.php";
                        break;
                    default: 
                        echo "*".$type_identifier;
                        var_dump ($row);
                        die();
                        $type_pic  = "";
                        $tablename = "";
                        break;
                }                                 
                
                // Find out rights about this entry:
                $entry_values   = get_entries_for_primary_key (
                                        $tablename,
                                        array ($primaryKey => $row[$ID_identifier]));

                $ref_meta_values    = get_entries_for_primary_key(
                                        "metainfo", 
                                        array ("object_type" => $row[$type_identifier],
                                               "object_id"   => $row[$ID_identifier]));
                
                // --- sufficient rights ? ------------------------------
                if (!user_may_read ($ref_meta_values['owner'],$ref_meta_values['grp'],$ref_meta_values['access_level'])) {
                    continue;
                }
                $access_pic  = "<img src='".$this->img_path.get_access_icon ($ref_meta_values['access_level'])."' 
                                    title='".translate($ref_meta_values['access_level'])."' 
                                    align=top>";
                
                $html .= "<tr>";
                $html .= "<td><img src='".$this->img_path.$type_pic."' title='".translate ($row[$type_identifier])."'></td>";
                $html .= "<td width='160'>".$access_pic."<a name='ref_".$row[$type_identifier]."_".$row[$ID_identifier]."' style='' ";
                $html .= "href='".$href."?command=edit_entry&entry_id=".$row[$ID_identifier]."'>";
                $html .= $entry_values[$headline]."</a></td>";
                $html .= "<td width='160'>".$row['description']."</td>";
                // Referenz löschen?
                if (user_may_edit ($ref_meta_values['owner'],$ref_meta_values['grp'],$ref_meta_values['access_level'])) {
                    $html .= "<td><a href='javascript:delete_reference(\"note\",".$this->currentObjectID.",\"note\",".$row[$ID_identifier].")'>";
                    $html .= "<img src='".$this->img_path."delete2.gif' border=0 title='".translate ('delete reference')."'></a></td>";
                }
                else
                    $html .= "<td>&nbsp;</td>";
                $html .= "</tr>\n";
            }                              
            $html .= "
                    </table>
        	    </td>
        	</tr>
            ";
            
            return $html;                
        }    

        function getReferencesOverview () {
        
            $html = "";
            
            if ($this->currentObjectID > 0) {
                $query = "SELECT * FROM ".TABLE_PREFIX."refering 
                          WHERE from_object_type='".$this->currentObjectType."' AND 
                                from_object_id=".$this->currentObjectID." AND
                                to_object_type != 'collection' AND
                                ref_type != 2 and ref_type != 3
                          ORDER BY to_object_type";
                $res   = mysql_query ($query);

                if (mysql_num_rows ($res) > 0) {
                    $html .= $this->getReferences(
                        translate ('references to'), 
                        $res, 
                        'to_object_id',
                        'to_object_type');
                }                              
            }
                
            return $html; 
        }      

        function getReferencedByOverview () {
        
            $html = "";
            
            if ($this->currentObjectID > 0) {
                $query = "SELECT * FROM ".TABLE_PREFIX."refering 
                          WHERE to_object_type='".$this->currentObjectType."' AND 
                                to_object_id=".$this->currentObjectID." AND
                                to_object_type != 'collection'
                          ORDER BY to_object_type";
                $res   = mysql_query ($query);

                if (mysql_num_rows ($res) > 0) {
                    $html .= $this->getReferences(
                        translate ('references from'), 
                        $res,
                        'from_object_id',
                        'from_object_type'
                    );
                }
            }
                
            return $html; 
        }
        
       /* function getSubmitCode () {

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
        }    */    	

        function getHtmlCode () {
        
	        $html  = $this->getReferenceSelection();    
	        $html .= $this->getDescriptionLine();    
	        $html .= $this->getReadonlyCollectionLine();    
	        
	        $html .= "	
	            <input type=hidden name='new_references' value=''>
            ";
            $html .= $this->getReferencesOverview();    
            $html .= $this->getReferencedByOverview();
            
            $html .= "
                <tr>
		            <td class='narrow' colspan=5><img src='".$this->img_path."shim.gif' height=1></td>
            	</tr>
            	"; 
            //if ($submit)   
	        //    $html .= $this->getSubmitCode();    
	        /*$html .= "
            	<tr><td colspan=5 class='line'><hr></td></tr>
            ";*/
        
            return $html;    
        }    


    }

?>