<?php
    
    /**
    * 
    * Show overlib information
    *
    * @access      private
    * @param       mixed array of arrays holding datagrid data
    * @param       mixed array of further paramenters
    * @return      string to be shown in overlib
    */
    function overlib ($row, $params) { //$model, $img_path) {
        
        list ($offset_headline, $offset_text, $img_path, $maxlength) = $params;
        
        $text     = strip_tags ($row[$offset_headline]['initial_data'], null);         
        //$text     = str_replace (array ('&lt;p&gt;','&lt;/p&gt;'), " ", $text);      
        //$text     = preg_replace ("\&lt;", " ", $text);
      
      $search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript 
                "'&lt;[\/\!]*?[^<>]*?&gt;'si",           // Strip out pseudo html tags
                "'<[\/\!]*?[^<>]*?>'si",           // Strip out html tags 
                "'([\r\n])[\s]+'",                 // Strip out white space 
                "'&(quot|#34);'i",                 // Replace html entities 
                "'&(amp|#38);'i", 
                "'&(lt|#60);'i", 
                "'&(gt|#62);'i", 
                "'&(nbsp|#160);'i", 
                "'&(iexcl|#161);'i", 
                "'&(cent|#162);'i", 
                "'&(pound|#163);'i", 
                "'&(copy|#169);'i", 
                "'&#(\d+);'e");                    // evaluate as php 

$replace = array ("", 
                 "", 
                 "",
                 "\\1", 
                 "\"", 
                 "&", 
                 "<", 
                 ">", 
                 " ", 
                 chr(161), 
                 chr(162), 
                 chr(163), 
                 chr(169), 
                 "chr(\\1)"); 

$text = preg_replace ($search, $replace, $text); 

      
      
      
      
        $headline = $row[$offset_text]['initial_data'];         
        if (strlen ($text) > $maxlength) {
            $text = substr ($text, 0, $maxlength)."...";
        }
        $text_long = str_replace ("\n", "<br>", $row[$offset_headline]['initial_data']);
        $text_long = str_replace (chr(10), '', $text_long);
        $text_long = str_replace (chr(13), '', $text_long);
        
        $text = '<a href="javascript:void(0);" 
                    onclick="return overlib(\''.$text_long.'\', STICKY, CAPTION,
                    \''.$headline.'\', CENTER);" onmouseout="nd();">'.$text.'</a>';


        return $text;
    }

    /**
    * 
    * Show overlib information
    *
    * @access      private
    * @param       mixed array of arrays holding datagrid data
    * @param       mixed array of further paramenters
    * @return      string to be shown in overlib
    */
    function checkboxes ($row, $params) { //$model, $img_path) {
        
        list ($name, $offset) = $params;
        $html = "<input type='checkbox' name='".$name."_".$row[$offset]['initial_data']."'>";

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
    function show_actions ($row, $params) {
        
        list (
            $entry_id_index, 
            $name_index, 
            $access_index, 
            $is_dir_index,
            $owner_index, 
            $group_index, 
            $img_path) = $params;

        $entry_id = $row[$entry_id_index]['initial_data'];         
        $name     = $row[$name_index]['initial_data'];         
        $access   = $row[$access_index]['initial_data']; 
        $is_dir   = (bool)$row[$is_dir_index]['initial_data']; 
        $owner    = $row[$owner_index]['initial_data'];
        $group    = $row[$group_index]['initial_data'];
        
        // --- debug ---
        /*echo "entry_id: ".$entry_id."<br>";
        echo "name:     ".$name."<br>";
        echo "access:   ".$access."<br>";
        echo "is_dir:   ".$is_dir."<br>";
        echo "owner:    ".$owner."<br>";
        echo "group:    ".$group."<br>";*/
        
        if (is_null ($owner)) return ''; 
        
        $ret  = '';
        
        if (user_may_delete ($owner, $group, $access)) {
            $ret  = "<a href='javascript:confirm_deleting(\"".$name."\", ".$entry_id.")'>";
            $ret .= "<img src='".$img_path."delete2.gif' ";
            $ret .= "title='".translate('delete', null, true)."' alt='".translate('delete', null, true)."' border=0></a>";
        }
        else {
            $ret .= "<img src='".$img_path."shim.gif' width='16'>";
        }
        
        ($is_dir) ? $new_command = "edit_folder" : $new_command = "edit_entry";
        if (user_may_edit ($owner, $group, $access)) {
            $ret .= "<a href='index.php?command=".$new_command."&entry_id=".$entry_id."'>";
            $ret .= "<img src='".$img_path."edit.gif' ";
            $ret .= "title='".translate('edit', null, true)."' alt='".translate('edit', null, true)."' border=0></a>";
        }
        else {
            //$ret .= "<a href='index.php?command=edit_entry&entry_id=".$entry_id."'>";
            $ret .= "<img src='".$img_path."readonly.gif' ";
            $ret .= "title='".translate('readonly', null, true)."' alt='".translate('readonly', null, true)."' border=0>";
        }
                
        return $ret;
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
    function show_headline ($row, &$params) {
                
        //list ($offset, $img_path, $maxlength) = $params;
        list ($entry_type, 
              $entry_id_index, 
              $name_index, 
              $access_index, 
              $is_dir_index, 
              $color_index, 
              $img_path, 
              $image,
              $maxlength) = $params;
                
        $entry_id = $row[$entry_id_index]['initial_data'];         
        $name     = $row[$name_index]['initial_data'];         
        $access   = $row[$access_index]['initial_data'];
        $color    = $row[$color_index]['initial_data'];
        $is_dir   = (bool)$row[$is_dir_index]['initial_data'];
        
        // --- debug
        /*echo "entry_id: ".$entry_id."<br>";
        echo "name:     ".$name."<br>";
        echo "access:   ".$access."<br>";
        echo "color:    ".$color."<br>";
        echo "is_dir:   ".$is_dir."<br>";*/
        
        $att_query = "
            SELECT count(*) FROM ".TABLE_PREFIX."refering
                WHERE (ref_type=2 OR ref_type=3) AND 
                      from_object_type='".$entry_type."' AND
                      from_object_id=".$entry_id;
        $att_res = mysql_query ($att_query);        
        echo mysql_error();
        $att_row = mysql_fetch_array ($att_res);
        $att_cnt = $att_row[0];
        
        if (strlen ($name) > $maxlength)
            $name = substr ($name, 0, $maxlength)."...";

        $locked_res = mysql_query ("SELECT user_id FROM ".TABLE_PREFIX."useronline WHERE object_type='".$entry_type."' 
                                    AND object_id=".$entry_id);
        $locked_row = mysql_fetch_array ($locked_res);
        
        $link = "";
        
        if ($is_dir) {
        	$link .= "<span id='dnd_".$entry_id."' style='position:absolute;'><img src='".$img_path."openfolder.gif' border=0></span>";
        	$link .= "<img src='".$img_path."shim.gif' width='19' height='1' border=0>";
	        //$link .= "<img src='".$img_path.get_access_icon ($access)."' title='".translate($access, null, true)."' align=top>";
    	    $link .= "<a href='index.php?command=show_entries&parent=$entry_id'>";
        	$link .= "<b>$name</b></a>";
        }
        else {
        	$link .= "<span id='dnd_".$entry_id."' style='position:absolute;'><img src='".$img_path.$image."' border=0></span>";
        	$link .= "<img src='".$img_path."shim.gif' width='19' height='1' border=0>";
	        //$link .= "<img src='".$img_path.get_access_icon ($access)."' title='".translate($access, null, true)."' align=top>";
    	    $link .= "<a href='index.php?command=edit_entry&entry_id=$entry_id'><font color='".$color."'>";
        	$link .= $name." ($att_cnt)</font></a>";
        }

        if ($locked_row != null) {
	        $pic = $img_path."locked.gif";
    	    if ($locked_row['user_id'] == $_SESSION['user_id'])
        	    $pic = $img_path."locked_green.gif";
        	$link .= "<img src='$pic' title='".translate ('locked by', null, true)." ".get_username_by_user_id ($locked_row['user_id'])."'>";    
    	}

        return $link;
    }

    /**
    * 
    * Show formatted date 
    *
    * @access      private
    * @param       mixed array of arrays holding datagrid data
    * @param       mixed array of further paramenters
    * @return      string to be shown in overlib
    */
    function format_date ($row, $params) { 
        
        list ($offset, $format) = $params;
        
        $date     = $row[$offset]['initial_data'];         
        //return $date;
        $date     = @explode (" ", $date);
        $day      = @explode ("-", $date[0]);
        $time     = @explode (":", $date[1]);
        $stamp    = @mktime ($time[0], $time[1], $time[2], $day[1], $day[2], $day[0]);
        return strftime ($format, $stamp);
    }

    /**
    * 
    *
    * @access      private
    * @param       mixed array of arrays holding datagrid data
    * @param       mixed array of further paramenters
    * @return      string to be shown in overlib
    */
    function bold ($row, $offset) { 
        $text = $row[$offset]['initial_data'];         
        return "<b>".$text."</b>";
    }
?>