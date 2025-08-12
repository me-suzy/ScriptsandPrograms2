<?php
  /**
    * $Id: attachments.class.php,v 1.13 2005/07/31 08:45:17 carsten Exp $
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
    class attachments_tab {

        var $tab_nr                     = null;
        var $locked                     = null;
        var $img_path                   = null;
        var $currentObjectID            = null;
        var $currentObjectType          = null;
        var $model                      = null;
        var $headline                   = null;
        //var $identifier                 = null;
        
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
        function attachments_tab ($id, $type, $locked) { 
            
            $this->locked            = $locked;
            $this->currentObjectID   = $id;
            $this->currentObjectType = $type;
            //$this->identifier        = $identifier;
        }
        
        function setImgPath ($path) {
            $this->img_path = $path;    
        }    
                
        function setTabNr ($nr) {
            $this->tab_nr = $nr;    
        }    
        
        function setModel (&$model) {
            $this->model = $model;    
        }    

		function setHeadline ($text) {
            $this->headline = $text;    
        }  
        
        function getAttachments($res, $ID_identifier, $type_identifier, $asText = false) {
                
            $meta_values    = get_entries_for_primary_key(
                                        "metainfo", 
                                        array ("object_type" => $this->currentObjectType,
                                               "object_id"   => $this->currentObjectID));
            // --- sufficient rights ? ------------------------------
            if (!user_may_read ($meta_values['owner'],$meta_values['grp'],$meta_values['access_level'])) {
                continue;
            }

            $may_delete = false;
            if (user_may_delete ($meta_values['owner'],$meta_values['grp'],$meta_values['access_level'])) 
                $may_delete = true;

            $may_edit = false;
            if (user_may_edit ($meta_values['owner'],$meta_values['grp'],$meta_values['access_level'])) 
                $may_edit = true;
                                               
            $html = "
                ";
            $txt  = translate('attachments').":\n";
            while ($row = mysql_fetch_array ($res)) {
                
                // Find out rights about this entry:
                $entry_values    = get_entries_for_primary_key (
                                        'memos',
                                        array ('memo_id' => $row[$ID_identifier]));
    
                $meta_values_att = get_entries_for_primary_key(
                                        "metainfo", 
                                        array ("object_type" => 'note', //$this->currentObjectType,
                                               "object_id"   => $row[$ID_identifier]));


                //var_dump ($entry_values);
                $html .= "<tr class='line'>\n";
                $html .= "<td>".$meta_values_att['created']."</td>\n";
                $html .= "<td align=right>\n";
                if ($may_edit) {
                    $html .= "<a href='../../modules/notes/index.php?";
                    $html .= "command=edit_att_note&entry_id=".$row[$ID_identifier];
                    $html .= "&return_to=".$this->currentObjectType."_".$this->currentObjectID."'>";
                    $html .= "<img src='".$this->img_path."edit.gif' border=0></a>";
                }
                else
                    $html .= "<img src='".$this->img_path."shim.gif' border=0 width='16'>";
                        
                if ($may_delete)
                    $html .= "<a href='javascript:delete_attachment(\"note\", $row[$ID_identifier])'><img src='".$this->img_path."delete2.gif' border=0></a>";
                else
                    $html .= "<img src='".$this->img_path."shim.gif' border=0 width='16'>";
                
                $html .= "</td>\n";
                $html .= "<td><div  id='att_note_".$row[$ID_identifier]."'>";
                $html .= $entry_values['content']."</div></td>\n";
                $html .= "<td colspan=2>&nbsp;</td>\n";
                $html .= "</tr>\n";
                
                $txt  .= "  ".$entry_values['content']."\n";
            }                              

            if (strlen($txt) == 2+strlen(translate ('attachments'))) 
                $txt = "";
            else
                $txt .= "\n";
                
            if ($asText) 
                return $txt;
            return $html;              
        }    

        function getExternalLinks($res, $ID_identifier, $type_identifier, $asText = false) {
                
            $meta_values    = get_entries_for_primary_key(
                                        "metainfo", 
                                        array ("object_type" => $this->currentObjectType,
                                               "object_id"   => $this->currentObjectID));
            // --- sufficient rights ? ------------------------------
            //var_dump ($meta_values);
            if (!user_may_read ($meta_values['owner'],$meta_values['grp'],$meta_values['access_level'])) {
                continue;
            }

            $may_delete = false;
            if (user_may_delete ($meta_values['owner'],$meta_values['grp'],$meta_values['access_level'])) 
                $may_delete = true;

            $may_edit = false;
            if (user_may_edit ($meta_values['owner'],$meta_values['grp'],$meta_values['access_level'])) 
                $may_edit = true;
                                               
            $html = "
                ";
            $txt  = translate ('external links').":\n";
            while ($row = mysql_fetch_array ($res)) {
                
                $html .= "<tr class='line'>\n";
                $html .= "<td>".$meta_values['created']."</td>\n";
                $html .= "<td align=right>\n";
                // not yet implemented
                /*if ($may_edit) {
                    $html .= "<a href='../../modules/notes/index.php?command=edit_att_note&entry_id=".$row[$ID_identifier]."&return_to=".$this->currentObjectID."'>";
                    $html .= "<img src='".$this->img_path."edit.gif' border=0></a>";
                }
                else*/
                    $html .= "<img src='".$this->img_path."shim.gif' border=0 width='16'>";
                        
                if ($may_delete)
                    $html .= "<a href='javascript:delete_attachment(\"external\", $row[$ID_identifier])'><img src='".$this->img_path."delete2.gif' border=0></a>";
                else
                    $html .= "<img src='".$this->img_path."shim.gif' border=0 width='16'>";
                
                $html .= "</td>\n";
                $html .= "<td><div id='att_external_".$row[$ID_identifier]."'>";
                $html .= "<a href='".$row['scheme']."://".$row['ref_path']."'>".$row['description']."</a></div></td>\n";
                $html .= "<td colspan=2>&nbsp;</td>\n";
                $html .= "</tr>\n";
             
                $txt  .= "  ".$row['description']." (".$row['ref_path'].")\n";   
            }             
            
            if (strlen($txt) == 2+strlen(translate ('external links'))) 
                $txt = "";
            else
                $txt .= "\n";
                                 
            if ($asText) 
                return $txt;
            return $html;                 
        }    


        function getAttachmentsOverview ($asText = false) {
        
            if ($this->currentObjectID > 0) {
                $query = "SELECT * FROM ".TABLE_PREFIX."refering
                          WHERE from_object_type='".$this->currentObjectType."' AND 
                                from_object_id=".$this->currentObjectID." AND
                                ref_type = 2
                          ORDER BY to_object_type";
                $res   = mysql_query ($query);
            }
            
            $txt  = '';
            $html = "
				<tr class='line'>
					<td colspan=5>
					    <a href='javascript:toggleDisplay(\"attachments_span\")'><img src='".$this->img_path."toggledisplay.gif' border=0></a>
					    &nbsp;(".mysql_num_rows ($res)." ".translate('entries')."):&nbsp;
					    ".translate ('add note as attachment')."
					</td>
				</tr>
                <tr>
                    <td colspan=5>
                    
                    <SPAN id='attachments_span' style='display:none'>
                    <table border=0 cellspacing=0 cellpadding=0>
                    <tr class='line'>
                        <th class=box colspan=2>".translate ('attachments')."</th>
                        <td colspan=3><a href='javascript:changeTab(2,6)'>".translate('new')."</a></td>
                    </tr>
            ";
            
            if ($this->currentObjectID > 0) {
                if (mysql_num_rows ($res) > 0) {
                    $tmp = $this->getAttachments(
                        $res, 
                        'to_object_id',
                        'to_object_type', $asText);
                    if ($asText)
                        $txt  .= $tmp;
                    else
                        $html .= $tmp;
                }                              
            }
                
            $html .= "
                    </table>
                    </span>
                    </td>
                </tr>
            ";

            if ($asText) 
                return $txt;
            return $html;   
        }      

        function getExternalLinksOverview ($asText = false) {
        
            if ($this->currentObjectID > 0) {
                $query = "SELECT 
                            to_object_type,
                            to_object_id,
                            ref_scheme,
                            ref_path,
                            scheme,
                            ".TABLE_PREFIX."refering.description
                          FROM ".TABLE_PREFIX."refering 
                          LEFT JOIN ".TABLE_PREFIX."url_schemes ON ".TABLE_PREFIX."url_schemes.scheme_id=".TABLE_PREFIX."refering.ref_scheme
                          WHERE from_object_type='".$this->currentObjectType."' AND 
                                from_object_id=".$this->currentObjectID." AND
                                ref_type = 3
                          ORDER BY to_object_type";
                $res   = mysql_query ($query);
            }

            $txt = '';
            $html = "
				<tr class='line'>
					<td colspan=5>
					    <a href='javascript:toggleDisplay(\"external_links_span\")'><img src='".$this->img_path."toggledisplay.gif' border=0></a>
					    &nbsp;(".mysql_num_rows ($res)." ".translate('entries')."):&nbsp;
					    ".translate ('add external ref as attachment')."
					</td>
				</tr>
                <tr>
                    <td colspan=5>
                    
                    <SPAN id='external_links_span' style='display:none'>
                    <table border=0 cellspacing=0 cellpadding=0>
		    		<tr class='line'>
                        <th class=box colspan=2>".translate ('external links')."</th>
                        <td colspan=3><a href='javascript:changeTab(2,6)'>".translate('new')."</a></td>
                    </tr>
            ";
            
            if ($this->currentObjectID > 0) {
                if (mysql_num_rows ($res) > 0) {
                    $tmp = $this->getExternalLinks(
                        $res, 
                        'to_object_id',
                        'to_object_type',
                        $asText);
                    ($asText) ? $txt .= $tmp : $html .= $tmp;
                }                              
            }
            $html .= "
                    </table>
                    </span>
                    </td>
                </tr>
            ";
           
            if ($asText) 
                return $txt;
            return $html;                   
        }   
        
        function addAttachmentHtml ($entry) {
            ?>
        	<TR class="line">
	            <TH colspan=2 class='box'><?=translate ("headline")?>:</TH>
		        <!--<TD colspan=3><b><?=$this->headline?></b></TD>-->
		        <TD colspan=3><input type='text' name='headline' value='<?=$this->headline?>'></TD>
	        </TR>
        	<TR class="line">
	            <TH colspan=2 class='box'><?=translate ("note")?>:</TH>
        		<TD colspan=3>
        		    <textarea name='attachment_content' 
        		    		  rows="5" style='width:500px;' tabindex=2
        		              class='<?=$entry['content']->class?>'></textarea>
        		</TD>
        	</TR>
        	<tr>
				<td class='narrow' colspan=5><img src='<?=$this->img_path?>shim.gif' height=1></td>
			</tr>
			<TR class="line">
        	    <TD colspan=5><?=translate ("add external ref as attachment")?>:</TD>
        	</TR>
        	<TR class="line">
        	    <TH colspan=2 class='box'><?=translate ("headline")?>:</TH>
        		<TD colspan=3><input type='text' name='external_link_name' value='<?=$entry['external_link_name']->get()?>' style='width:200px;'></TD>
        	</TR>
        	<TR class="line">
        	    <TH colspan=2 class='box'><?=translate ("type")?>:</TH>
        		<TD colspan=3>
                    <select name='scheme' style='width:200px;'>
            	    <?php	        
            	        $scheme_query = "SELECT * FROM ".TABLE_PREFIX."url_schemes ORDER BY order_nr";
            	        $scheme_res   = mysql_query ($scheme_query);
            	        while ($scheme_row = mysql_fetch_array ($scheme_res)) {
            	            ($scheme_row['scheme_id'] == $entry['scheme']->get()) ? $selected = "selected" : $selected = "";
            	            ?>
                            <option value='<?=$scheme_row['scheme_id']?>' <?=$selected?>><?=$scheme_row['scheme']?></option>
            	            <?php
            	        }    
            	    ?>
                    </select>
                </TD>
        	</TR>
        	<TR class="line">
        	    <TH colspan=2 class='box'><?=translate ("path")?>:</TH>
        		<TD colspan=3><input type='text' name='external_link_path' value='<?=$entry['external_link_path']->get()?>' style='width:200px;'></TD>
        	</TR>
        	<tr>
				<td class='narrow' colspan=5><img src='<?=$this->img_path?>shim.gif' height=1></td>
			</tr>	
			<tr>
				<td colspan=5><br></td>
			</tr>
            <?php    
        }    
        
        function addAttachment ($entry, $entry_type, $request) {
            global $easy;
            
            $doc_query = "INSERT INTO ".TABLE_PREFIX."memos (
									headline,
                                    content,
                                    is_dir,
                                    parent		
                              )
                              VALUES (
                                    '".htmlspecialchars($request['headline'], ENT_QUOTES)."',
                                    '".htmlspecialchars($request['attachment_content'], ENT_QUOTES)."',
                                    '0',
                                    '".$this->currentObjectID."'		
                                   )";
            $logger->log ($doc_query, 7);
            if (!$res = $this->ExecuteQuery ($doc_query, 'mysql_error')) return "failure";
            $inserted_id = mysql_insert_id();
            
            // --- add metainfo -------------------------------------
            $meta_query = "INSERT INTO ".TABLE_PREFIX."metainfo (
                            object_type,
                            object_id,
                            creator,
                            owner,
                            grp,
                            state,
                            created,
                            access_level)
                           VALUES (
                            '".$entry_type."',
                            $inserted_id,
                            ".$_SESSION['user_id'].",
                            ".$_SESSION['user_id'].",
                            0,
                            0,
                            now(),
                            ''
                       )";
            $logger->log ($meta_query, 7);
            $res = mysql_query ($meta_query);
            if (mysql_error() != '') {
                $logger->log (mysql_error(),  1);
                mysql_query ("DELETE FROM ".TABLE_PREFIX."memos WHERE memo_id='$inserted_id'");
                $this->error_msg = "Error inserting meta entry to database: ".mysql_error();
                return "failure";
            }
            $ref_query = "INSERT INTO ".TABLE_PREFIX."refering (
                            from_object_type,
                            from_object_id,
                            to_object_type,
                            to_object_id,
                            ref_type)
                          VALUES (
                            '".$this->currentObjectType."',
                            ".$this->currentObjectID.",
                            'note',
                            $inserted_id,
                            2
                          )";
            if (!$this->ExecuteQuery ($ref_query, 'mysql_error')) 
                return "failure";

            return "success";    
        }       

        function deleteAttachments ($params) {
            global $easy;
            
            $del_array = explode ("|", $params['delete_attachments']);
            for ($j=0; $j < -1 + count($del_array); $j++) {
                list ($type, $id) = explode ("_", $del_array[$j]);
                switch ($type) {
                    case 'note': 
                        require_once ('../notes/models/notes_mdl.php');
                        $nm = new notes_model (null, null);
                        $rc = $nm->delete_entry(
                                    array ("entry_id" => $id,
                                           "parent"   => $this->currentObjectID
                                          ),
                                    true,                        // use inheritance
                                    $this->currentObjectType
                                );
                        echo $nm->error_msg;
                        $this->model->error_msg .= $nm->error_msg;
                        break;
                    case 'external': 
                        $del_query = "DELETE FROM ".TABLE_PREFIX."refering WHERE to_object_type='external' AND to_object_id=$id";
                        $rc        = "success";
                        if (!$this->ExecuteQuery ($del_query, 'mysql_error')) 
                            $rc = "failure";
                        break;
                    default: die ("undefined attachment type in ".__FILE__." ".__LINE__);    
                }    
                if ($rc == "failure") 
                    return "failure";        
            }    
            return "success";
        }
             
        function getHtmlCode () {

	        $html = "";

            $html .= $this->getExternalLinksOverview();    
            $html .= "
                <tr>
					<td class='narrow' colspan=5><img src='".$this->img_path."shim.gif' height=1></td>
				</tr>
                     ";
            $html .= $this->getAttachmentsOverview();    
            $html .= "
                <tr>
					<td class='narrow' colspan=5><img src='".$this->img_path."shim.gif' height=1></td>
				</tr>
                     ";

            return $html;    
        }    

        function getAsText () {

	        $txt  = "";

            $txt .= $this->getExternalLinksOverview(true);    
            $txt .= $this->getAttachmentsOverview(true);    

            return $txt;    
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