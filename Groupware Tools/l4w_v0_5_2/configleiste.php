<?php

    /*=====================================================================
    // $Id: configleiste.php,v 1.9 2005/08/04 19:57:31 carsten Exp $
    // copyright evandor media Gmbh 2004
    //=====================================================================*/

    // --- Standard Inclusions --------------------------------------
    include ("inc/pre_include_standard.inc.php");
    //include ("modules/common/standard_inclusions.inc.php");
   
    // --- PHPGACL --------------------------------------------------
    require_once ('extern/phpgacl/gacl.class.php');
    require_once ('extern/phpgacl/gacl_api.class.php');
    require_once ('inc/acl.inc.php');
    
    $gacl_api = new gacl_api($gacl_options);
    
	// --- pagestats ------------------------------------------------
	set_page_stats(__FILE__);

    // --- Header ---------------------------------------------------
	include ("inc/header.inc.php");

?>

    <script language=javascript type="text/javascript">
        function change_compress (aktuell) {
            link = "change_compress.php?aktuell="+aktuell;
            document.location.href=link;
        }       
        
        function switch_help_mode () {
            if (document.progress.helpmode.checked) 
                parent.executeframe.document.location.href="change_helpmode.php?active=true";
            else
                parent.executeframe.document.location.href="change_helpmode.php?active=false";        
            return false;
        }    
    </script>

	<form name=progress action="#">
    <table border=0 cellpadding=0 cellspacing=0 width='100%'>
        <tr>
        	<!-- progressbar -->
            <td align="left" valign="baseline"
            	width='200' height=20 
            	class=leiste background='<?=$img_path?>leiste_bg_left_bottom.jpg'
            	style="">
                <img src='<?=$img_path?>bar.gif' width=0 height=16 name=progress_gif>
            </td>
            <!-- progress: #percent -->
        	<td width="60" background='<?=$img_path?>leiste_bg_unten.gif'>
            	<b><span id='percent_num'> </span></b>
            </td>
            <td width="280" background='<?=$img_path?>leiste_bg_unten.gif'>
            	&nbsp;
            	<?=translate ("help mode")?>:&nbsp;
            	<?php
            	    (isset ($_SESSION['helpmode']) && $_SESSION['helpmode'] == "true") ? $help_checked = "checked" : $help_checked = ""; 
            	?>
            	<input type=checkbox onClick='javascript:switch_help_mode();' name='helpmode' value='on' <?=$help_checked?>>
            	&nbsp;
            	<?=translate ("compress data")?>:&nbsp;
            	<?php
                	$res = mysql_query ("SELECT compression FROM ".TABLE_PREFIX."user_details WHERE user_id='$user_id'");
                	logDBError (__FILE__, __LINE__, mysql_error());
                	$row = mysql_fetch_array ($res);
                	$comp_checked = "";
                	if ($row[0] == "t") $comp_checked = "checked";
            	?>
            	<input type=checkbox onClick='javascript:change_compress("<?=$comp_checked?>");' name='use_compress' value='on' <?=$comp_checked?>>
		        &nbsp;
        	    Online:&nbsp;
            	<iframe name='online_iframe' valign=top width=18 height=15 src='online_iframe.php' scrolling="no" frameborder="0"></iframe>
            	<!--&nbsp;&nbsp;&nbsp;
            	<a href='javascript:open_chat();' class="linkspecial">Chat</a> -->
	        </td>
    	    <td width="200" background='<?=$img_path?>leiste_bg_unten.gif'>
        		<span id='message'> </span>
			</td>
        	<td align=right height=20 background='<?=$img_path?>leiste_bg_unten.gif'>
            	<a href='http://217.172.179.216/mantis/login_page.php' target='_blank' class="link">&nbsp;<b>Bugtracker</b> </a>&nbsp;
            	<a href='http://217.172.179.216/foren/'                target='_blank' class="link">&nbsp;<b>Forum</b> </a>&nbsp;
            	<!--<a href='doc/manual.html'                              target='_blank' class="link">&nbsp;<b>Help</b> </a>&nbsp;-->
        	</td>
        </tr>
    </table>
	</form>
	
</body>
</html>