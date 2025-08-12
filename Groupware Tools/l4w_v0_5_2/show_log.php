<?php

   	/*=====================================================================
	// $Id: show_log.php,v 1.8 2005/08/01 14:55:12 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

    // --- Standard Inclusions --------------------------------------
    include ("inc/pre_include_standard.inc.php");

    // --- PHPGACL --------------------------------------------------
    require_once ('extern/phpgacl/gacl.class.php');
    require_once ('extern/phpgacl/gacl_api.class.php');
    require_once ('inc/acl.inc.php');
    
    $gacl_api = new gacl_api($gacl_options);
    $gacl     = new gacl    ($gacl_options);

    // --- Security -------------------------------------------------
    if (!$gacl->acl_check('Use Leads4web','Show Logfile','Person',$_SESSION['user_id']))
        die ("Security alert in ".__FILE__);

    // --- pagestats ------------------------------------------------
    set_page_stats(__FILE__);
    
    // --- Header ---------------------------------------------------
    include ("inc/header.inc.php");
	
	// --- Headline -------------------------------------------------
	$headline  = "<img src='".$img_path."logfile.gif' align=top alt='logfile'>&nbsp;";
    $headline .= '<b>'.translate ("logfile")."</b>:&nbsp;"; 
    $headline .= $LOGGING_OUTPUT_FILE;
    $headline .= " (Level: $LOGGING_DEFAULT_LEVEL)";   
    
    if (isset($_REQUEST['delete_log'])) {
    	$fh = fopen ($LOGGING_OUTPUT_FILE, "w+");
    	//fwrite ($fh, "");
    	fclose ($fh);
    	$logger->log ("Logging File emptied by user $user_id",4);
    	?>
    		<script language=javascript>
    			document.location.href='show_log.php';
    		</script>
    	<?php	
    	die ();
    }

	if (!($fh = @fopen ($LOGGING_OUTPUT_FILE, "rb"))) {
	    die ($LOGGING_OUTPUT_FILE." could not be opened");    
	}
	$contents = fread ($fh, filesize ($LOGGING_OUTPUT_FILE));
	fclose ($fh);
	$lines    = explode ("\n", $contents);
?>
    <form action='show_log.php' method='post'>

    <?php
        include ("modules/common/headline.php");
    ?>

    <table class='adminframe'>
    <tr><td>

        <table border=0>
        <?php
        if ($gacl->acl_check('Use Leads4web','Show Logfile','Person',$_SESSION['user_id'])) {
            $restricted_access_html = get_restricted_access_html (
                                        'Use Leads4web', 
                                        'Show Logfile', 
                                        'Edit Permissions',
                                        'show_log.php',
                                        $img_path);
        ?>        
        
        <tr>
        	<td align=left class='restricted'>
        	    <?=$restricted_access_html?>
        	</td>
        </tr>
        <tr>
        	<td align=left>
                &nbsp;
        	</td>
        </tr>
        <?php if ($gacl->acl_check('Use Leads4web','Delete Logfile','Person',$_SESSION['user_id'])) { ?>
        <tr>
        	<td align=left>
        		<input type=submit name='delete_log' value='Delete Logfile' class='buttonstyle'>
        	</td>
        </tr>
        <?php } ?>
        <tr>
            <td><hr></td>
        </tr>
    <?php } ?>
        <tr>
        	<td align=left>
    <?php 
    		foreach ($lines as $key => $line) 
    			echo $line."<br>";
    ?>
    		</td>
    	</tr>
    	</table>

    </td></tr>
    </table>
	</form>
	
</html>
</body>