<?php
  /**
    * $Id: HTMLElements.class.php,v 1.13 2005/07/27 13:00:53 carsten Exp $
    *
    * Model for users. See easy_framework for more information
    * about controlers and models.
    * @package common
    */
    
  /**
    *
    */
    class HTMLElements {

        var $tab_nr                     = null;
        var $img_path                   = null;
        var $currentObjectID            = null;
        var $currentObjectType          = null;
        var $currentTab                 = 0;
        
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
        function HTMLElements ($id, $type) { 
            $this->currentObjectID   = $id;
            $this->currentObjectType = $type;
        }
        
        function setImgPath ($path) {
            $this->img_path = $path;    
        }    
                
        function setTabNr ($nr) {
            $this->tab_nr = $nr;    
        }    
        
        function TabHeader ($explain) {
            global $easy;

    	?>
			<tr>
				<td class='narrow' colspan=5><img src='<?=$this->img_path?>shim.gif' height=1></td>
			</tr>
	    <?php if (isset($_SESSION['helpmode']) && $_SESSION['helpmode'] == "true") { ?>		
			<TR class="line">
				<TD colspan=5><?=translate ($explain)?></TD>
			</TR>
			<tr>
				<td class='narrow' colspan=5><img src='<?=$this->img_path?>shim.gif' height=1></td>
			</tr>       
        <?php }   
        }
        
        function echoSelectHTML (&$entry, $name, $translate_key = null) {
            ($translate_key === null) ? $translate = $name : $translate = $translate_key;
            $translation = translate ($translate, null, true);
            
            //$accesskey   = $this->getAccessKey ($entry[$name], $translation);
            $accesskey = NULL;

            echo '<TR class="line">'."\n";
	        echo '<TH class="box" colspan=2>'.$this->underlineAccessKey ($translation, $accesskey).'</TH>'."\n";
		    echo '<TD colspan=3>'.$entry[$name]->getSelectHTML ($name, null, $accesskey).'</TD>'."\n";
            echo '</TR>'."\n";
        }    
        
        function echoInputHTML (&$entry, $name, $translate_key = null) {
            
            ($translate_key === null) ? $translate = $name : $translate = $translate_key;
            $translation = translate ($translate);
            $accesskey   = $this->getAccessKey ($entry[$name], $translation);
            
            echo '<TR class="line">'."\n";
	        echo '<TH class="box" colspan=2>'.$this->underlineAccessKey ($translation, $accesskey).'</TH>'."\n";
		    echo '<TD colspan=3>'.$entry[$name]->getInputHTML ($name, null, $accesskey).'</TD>'."\n";
            echo '</TR>'."\n";
        
        }    

        function echoTextareaHTML (&$entry, $name, $rows, $translate_key = null) {
            
            ($translate_key === null) ? $translate = $name : $translate = $translate_key;
            $translation = translate ($translate);
            
            //$accesskey   = $this->getAccessKey ($entry[$name], $translation);
            $accesskey = NULL;
            
            echo '<TR class="line">'."\n";
	        echo '<TH class="box" colspan=2>'.$this->underlineAccessKey ($translation, $accesskey).'</TH>'."\n";
		    echo '<TD colspan=3>'.$entry[$name]->getTextareaHTML ($name, $rows, null, $accesskey).'</TD>'."\n";
            echo '</TR>'."\n";
        
        }    

        function getAccessKey (&$datatype, $translation) {
            global $easy_accesskeys;
            
            if (is_null ($easy_accesskeys)) $easy_accesskeys = array ();
            
            $str = $translation;
            while (strlen ($str) > 0) {
                $candidate = substr ($str,0,1);
                $str       = substr ($str,1);  
                if (!in_array ($candidate, $easy_accesskeys)) {
                    $easy_accesskeys[] = $candidate;
                    return $candidate;    
                }                  
            }    
            return null;    
        }    
        
        function underlineAccessKey ($str, $key) {
            $pos = strpos ($str, $key);
            if ($pos !== false) {
                return substr ($str,0,$pos)."<u>".substr ($str, $pos, 1)."</u>".substr ($str, $pos+1);
            }
            else 
                return $str;
        }    

        
        function echoBR () {
            echo "<tr>\n";
            echo "    <td colspan=5><img src='".$this->img_path."shim.gif' height=3></td>\n";
            echo "</tr>\n";
        }    

        function echoHR () {
            echo "<tr>\n";
            echo "    <td class='narrow' colspan=5><img src='".$this->img_path."shim.gif' height=1></td>\n";
            echo "</tr>\n";
        }    

        function echoSaveButton (&$fields, $identifier, &$meta_values) {
        	$show_me = true;
        	if (isset($fields['locked']) && (bool)$fields['locked']->get())
        		$show_me = false;
            if ($fields[$identifier]->get() > 0 && !user_may_edit ($meta_values['owner'], $meta_values['grp'], $meta_values['access_level']))
				$show_me = false; 
            if ($show_me)
                echo "<input type=submit class=submit name='submit_me' onClick='javascript:something_changed=false;' value='".translate('save', null, true)."'>&nbsp;";
        }            

        function echoApplyButton (&$fields, $identifier, &$meta_values, $jump_to) {

            if ($fields[$identifier]->get() > 0 && 
                user_may_edit ($meta_values['owner'], $meta_values['grp'], $meta_values['access_level'])) { 
                echo "<input type=submit class=submit name='apply'  onClick='javascript:run_apply (".$jump_to.");' value='".translate('apply', null, true)."'>&nbsp;";
            }
        }            

        function echoClipboardButton (&$fields, $identifier, &$meta_values, $jump_to) {
            echo "<input type=submit class=submit name='serialize' onClick='javascript:serialize_model(\"".translate('object serialized', null, true)."\");' value='".translate('save to clipboard', null, true)."'>&nbsp;";
        }            

        function echoTemplateButton (&$fields, $identifier, &$meta_values, $jump_to) {
            echo "<input type=submit class=submit name='template' onClick='javascript:save_as_template(\"".translate('saved as template', null, true)."\");' value='".translate('save as template', null, true)."'>&nbsp;";
        }

        function echoCopyButton (&$fields, $identifier, &$meta_values, $jump_to) {
        	if ($fields[$identifier]->get() > 0)
	            echo "<input type=button class=submit name='clone' onClick='javascript:clone_me(\"".translate('object cloned', null, true)."\");' value='".translate('make copy')."'>&nbsp;";
        }

        function echoDeleteButton (&$fields, $identifier, &$meta_values, $theme) {
        	if ($fields[$identifier]->get() > 0 && 
        	    isset ($meta_values) && 
        	    user_may_delete ($meta_values['owner'],$meta_values['grp'],$meta_values['access_level'])) { 
            	echo "<input type=button class=submit name='delete' ";
            	echo "onClick='javascript:confirm_deleting(\"".$fields[$theme]->get()."\",";
				echo $fields[$identifier]->get().");' value='".translate('delete', null, true)."'>&nbsp;";
        	}
        }
        
        function exitHere (&$entry, $tab_cnt) {
            ?>
			<script TYPE="text/javascript">
			    window.setTimeout ("changeTab (1,<?=$tab_cnt?>)", 0);
    			window.setTimeout ("changeTab (<?=$entry['goto_tab']->get()?>,<?=$tab_cnt?>)", 1);
			</script>
			</body></html>
			<?php
			die();	
        }    
        
        function startTab ($tab_cnt, $tab_top) {
            $this->currentTab++;
            ?>
            <div id="tab<?=$this->currentTab?>" style='position:absolute; top:<?=$tab_top?>px; left:0px; 
                           width:100%; overflow:visible; z-index:<?=($tab_cnt - $this->currentTab)?>;'>
            <?php                   
        }    
        
        function echoMenu ($model, $identifier, $name, &$meta_values, $jump_to, $img_path) {
            
            if (!(bool)$model->entry['locked']->get()) { ?>
    	        <tr class="line" style='height:27px;vertical-align:bottom'>
    	            <td colspan=3 valign=top>
            	    <?php if ($model->entry[$identifier]->get() == 0 || user_may_edit ($meta_values['owner'], $meta_values['grp'], $meta_values['access_level'])) { ?>
            	        <input type=submit class=submit name='submit_me' onClick='javascript:something_changed=false;' value='<?=translate('save', null, true)?>'>&nbsp;&nbsp;
                        <?php if ($model->entry[$identifier]->get() > 0) { ?>
                            <input type=submit class=submit name='apply'  onClick='javascript:run_apply (<?=$jump_to?>);' value='<?=translate('apply', null, true)?>'>&nbsp;&nbsp;
                        <?php } ?>    
                    <?php } ?>
                        <?php if ($model->entry[$identifier]->get() > 0) { ?>
                	        <input type=button class=submit name='clone'  onClick='javascript:clone_me("<?=translate('object cloned', null, true)?>");' value='<?=translate('make copy', null, true)?>'>&nbsp;&nbsp;
                	        <input type=button class=submit name='clone'  onClick='javascript:location.href="index.php?command=show_entries"' value='<?=translate('back', null, true)?>'>&nbsp;&nbsp;
                	    <?php } ?>    
            	    </td>
            	    <td colspan=2 valign=top align=right>
            	    <?php
            	    if (isset ($meta_values) && user_may_delete ($meta_values['owner'],$meta_values['grp'],$meta_values['access_level'])) { ?>
                	        <input type=button class=submit name='delete' onClick='javascript:confirm_deleting("<?=$model->entry[$name]->get()?>",<?=$model->entry[$identifier]->get()?>);' value='<?=translate('delete', null, true)?>'>&nbsp;&nbsp;	    
                    <?php } else { ?>
            	        &nbsp;
            	    <?php } ?>
            	    </td>
                </tr>
            <?php } ?>
            <tr style='height:1px;'><!-- workaround to span cells -->
        	    <td width='160'><img src='<?=$img_path?>shim.gif' height=1></td>
        	    <td width='20'><img src='<?=$img_path?>shim.gif' height=1></td>
        	    <td width='460'><img src='<?=$img_path?>shim.gif' height=1></td>
        	    <td ><img src='<?=$img_path?>shim.gif' height=1></td>
        	    <td width='90'><img src='<?=$img_path?>shim.gif' height=1></td>
        	</tr>
            <?php
        }
    }

?>