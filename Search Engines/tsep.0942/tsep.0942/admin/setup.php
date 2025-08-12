<?php
/**
 * @package The Search Engine Project
 * @copyright (C) 2005 by TSEP Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @since TSEP 0943
 * @tables tsep_internal, tsep_iprofile, tsep_iprofile_search, tsep_log, tsep_loghits, tsep_ranksymbols, tsep_search, tsep_stopwords
 * @author Toon Goedhart
 *
 * following will be filled automatically by SubVersion!
 * Do not change by hand!
 *  $LastChangedDate: 2005-09-07 22:32:50 +0200 (Mi, 07 Sep 2005) $
 *  @lastedited $LastChangedBy: toon $
 *  $LastChangedRevision: 322 $
 **/

require_once( "../language/en_US/language.php" ); //temporarily - this needs to be fixed
require_once( "../language/languages.php" );
require_once( "../include/languagehandler.php" );
require_once( "../include/sql_parse.php" );
require_once( "../include/tseptrace.php" );

/***  BEGIN: General routines  **********************************************************/

/**
 * saveSettings()
 * 
 * Saves all the settings from the $_GET var
 * to session vars.
 * 
 * @return void
 **/
function saveSettings() {
		global $tsep_lng;

	if ( isset($_GET["debugtrace"] ) )
		$_SESSION["debugtrace"] =  ( $_GET["debugtrace"] == "on" );		
					
    while ( list( $key, $val ) = each( $_GET ) ) {
		_TsepTrace("saveSettings: &lt;$key&gt; &lt;$val&gt;");
        switch ( $key ) {
            /* DB setup */
                case "dbHost":
                        $_SESSION["dbHost"] = $val;
                        break;
            
                case "dbName":
                        $_SESSION["dbName"] = $val;
                        break;
                
                case "dbUser":
                        $_SESSION["dbUser"] = $val;
                        break;
                
                case "dbPwd":
                        $_SESSION["dbPwd"] = $val;
                        break;
                
                case "createDB":
                        $_SESSION["createDB"] = $val;
                        break;
                
                case "tablePrefixNew":
                        $_SESSION["tablePrefixNew"] = $val;
                        break;
                
                case "tsepRoot":
                /* Strip leading and trailing slashes */
                $val = trim( trim( $val ), "/" );
                        $_SESSION["tsepRoot"] = $val;
                        break;
                
                case "tsepPath":
                /* Strip trailing slash */
                if ( $val[ strlen( $val )-1 ] == "/" ) {
                    $val = substr( $val, 0, strlen( $val )-1 );
                }
					    $val = preg_replace("/\/+/", "/", $val); // remove double-slashes
                        $_SESSION["tsepPath"] = $val;
                        break;
                
                case "tmpPath":
                /* replace backslash by slash */
					    $val = preg_replace('/\\\+/', "/", $val);
                        $_SESSION["tmpPath"] = $val;
                        break;
                
            /* System check */
                case "freshInstall":
                    $_SESSION["freshInstall"] = $val;
                    break;
                
                case "update":
                    $_SESSION["update"] = $val;
                    break;
            
                case "settings":
                    $_SESSION["settings"] = $val;
                    break;
            
                case "profiles":
                    $_SESSION["profiles"] = $val;
                    break;
            
                case "indexes":
                    $_SESSION["indexes"] = $val;
                    break;
            
                case "stopwords":
                    $_SESSION["stopwords"] = $val;
                    break;
            
                case "logs":
                    $_SESSION["logs"] = $val;
                    break;
            
                case "ranksymbols":
                    $_SESSION["ranksymbols"] = $val;
                    break;
            
                case "lang":
                    $_SESSION["lang"] = $val;
                    break;
            
                case "debugtrace":
                    $_SESSION["debugtrace"] = $val;
                    break;
            
        } // switch
    } // while
} // saveSettings


/**
 * setupRollBack()
 * 
 * This routine is used for development.
 * If you are performing tests with update the
 * old tables are renamed and new tables are
 * created and populated.
 * 
 * If you want to delete the new tables and
 * rename the old tables back to their
 * original names you can call this routine.
 * 
 * It will create a PHP file called "rollback.php"
 * which will restore the database to the old
 * settings.
 * 
 * This is provided that you do not let the
 * installer delete the old tables!!!
 * 
 * @return void
 **/
function setupRollBack() {
		global $tsep_lng;	
    $output  = "<?php\n";
    $output .= "\n";
    $output .= "@mysql_connect( '".$_SESSION["dbHost"]."', '".$_SESSION["dbUser"]."', '".$_SESSION["dbPwd"]."' );\n";
    $output .= "@mysql_select_db( '".$_SESSION["dbName"]."' );\n";
    $output .= "\n";
    $output .= "\$SQLResult = @mysql_query( \"DROP TABLE IF EXISTS ".$_SESSION["tablePrefixNew"]."internal\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"DROP TABLE IF EXISTS ".$_SESSION["tablePrefixNew"]."iprofile\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"DROP TABLE IF EXISTS ".$_SESSION["tablePrefixNew"]."iprofile_search\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"DROP TABLE IF EXISTS ".$_SESSION["tablePrefixNew"]."log\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"DROP TABLE IF EXISTS ".$_SESSION["tablePrefixNew"]."loghits\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"DROP TABLE IF EXISTS ".$_SESSION["tablePrefixNew"]."ranksymbols\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"DROP TABLE IF EXISTS ".$_SESSION["tablePrefixNew"]."search\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"DROP TABLE IF EXISTS ".$_SESSION["tablePrefixNew"]."stopwords\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"DROP TABLE IF EXISTS ".$_SESSION["tablePrefixNew"]."user_levels\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"DROP TABLE IF EXISTS ".$_SESSION["tablePrefixNew"]."users\" );\n";
    $output .= "\n";
    $output .= "\$SQLResult = @mysql_query( \"RENAME TABLE ".$_SESSION["tablePrefixTmp"]."internal TO ".$_SESSION["tablePrefixNew"]."internal\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"RENAME TABLE ".$_SESSION["tablePrefixTmp"]."iprofile TO ".$_SESSION["tablePrefixNew"]."iprofile\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"RENAME TABLE ".$_SESSION["tablePrefixTmp"]."iprofile_search TO ".$_SESSION["tablePrefixNew"]."iprofile_search\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"RENAME TABLE ".$_SESSION["tablePrefixTmp"]."log TO ".$_SESSION["tablePrefixNew"]."log\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"RENAME TABLE ".$_SESSION["tablePrefixTmp"]."loghits TO ".$_SESSION["tablePrefixNew"]."loghits\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"RENAME TABLE ".$_SESSION["tablePrefixTmp"]."ranksymbols TO ".$_SESSION["tablePrefixNew"]."ranksymbols\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"RENAME TABLE ".$_SESSION["tablePrefixTmp"]."search TO ".$_SESSION["tablePrefixNew"]."search\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"RENAME TABLE ".$_SESSION["tablePrefixTmp"]."stopwords TO ".$_SESSION["tablePrefixNew"]."stopwords\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"RENAME TABLE ".$_SESSION["tablePrefixTmp"]."user_levels TO ".$_SESSION["tablePrefixNew"]."user_levels\" );\n";
    $output .= "\$SQLResult = @mysql_query( \"RENAME TABLE ".$_SESSION["tablePrefixTmp"]."users TO ".$_SESSION["tablePrefixNew"]."users\" );\n";
    $output .= "\n";
    $output .= "@mysql_close();\n";
    $output .= "\n";
    $output .= "echo \"".$tsep_lng['setup_Rollback_completed']."\";\n";
    $output .= "\n";
    $output .= "?>\n";
    
    $handle = @fopen( "rollback.php", "w" );
    @fwrite( $handle, $output );
    @fclose( $handle );
} // setupRollBack


/**
 * updateProgressBar()
 * 
 * Updates the progressbar.
 * Calculates the percentage done and writes the bar accordingly.
 * 
 * @param integer $done The number of steps on the progressbar (= records) that are completed
 * @param integer $max The number of steps the complete operation will take
 * @return void
 **/
function updateProgressBar( $done, $max ) {
    global $barWidth, $charDone, $tsep_lng;
    
    $progressBar = str_repeat( $charDone, round( $barWidth * ( $done / $max ) ) );
    writeHTML( "<script>UpdateProgressBar( '$progressBar' );</script>\n" );
} // updateProgressBar


/**
 * getTSEPVersion()
 * 
 * Reads the current TSEP version from the insert.sql file.
 * It updates the session var tsepVersion.
 * 
 * @return void
 **/
function getTSEPVersion() {
    global $tsep_lng;

    if ( isset( $_SESSION["tsepPath"] ) ) {
        $versionFile = $_SESSION["tsepPath"]."/admin/insert.sql";
    } else {
        $path = str_replace( "/admin/".basename( $_SERVER["PHP_SELF"] ), '', $_SERVER["DOCUMENT_ROOT"].$_SERVER["PHP_SELF"] );
        $versionFile = $path."/admin/insert.sql";
    }
    
    $fileContents = file_get_contents( $versionFile );

    // \btsepversion\s?['"]\s?,\s?['"]([^'"]*)\s?['"]
    // 
    // Assert position at a word boundary �\b�
    // Match the characters "tsepversion" literally �tsepversion�
    // Match a single character that is a "whitespace character" (spaces, tabs, line breaks, etc.) �\s?�
    //    Between zero and one times, as many times as possible, giving back as needed (greedy) �?�
    // Match a single character present in the list "'"" �['"]�
    // Match a single character that is a "whitespace character" (spaces, tabs, line breaks, etc.) �\s?�
    //    Between zero and one times, as many times as possible, giving back as needed (greedy) �?�
    // Match the character "," literally �,�
    // Match a single character that is a "whitespace character" (spaces, tabs, line breaks, etc.) �\s?�
    //    Between zero and one times, as many times as possible, giving back as needed (greedy) �?�
    // Match a single character present in the list "'"" �['"]�
    // Match the regular expression below and capture its match into backreference number 1 �([^'"]*)�
    //    Match a single character NOT present in the list "'"" �[^'"]*�
    //       Between zero and unlimited times, as many times as possible, giving back as needed (greedy) �*�
    // Match a single character that is a "whitespace character" (spaces, tabs, line breaks, etc.) �\s?�
    //    Between zero and one times, as many times as possible, giving back as needed (greedy) �?�
    // Match a single character present in the list "'"" �['"]�
    //
    // Example: INSERT INTO %tablePrefix%internal (idinternal, description, stringvalue, numericvalue, sortordervalue, valuetype, fieldtype, stringtag, numtag) VALUES (   2,'tsepversion','0.942-279 (WCREV)',NULL,NULL,'s','text','internal',NULL);
    if (preg_match( '/\\btsepversion\\s?[\'"]\\s?,\\s?[\'"]([^\'"]*)\\s?[\'"]/i', $fileContents, $match )) {
        $tsepVersion = $match[1];
    } else {
        $tsepVersion = $tsep_lng['setup_Unknown'];
    }
    
    $_SESSION["tsepVersion"] = $tsepVersion;
} // getTSEPVersion


/**
 * getOldTSEPVersion()
 * 
 * Tries to find the version number of the
 * already installed TSEP version.
 * 
 * @return string Version number or "unknown"
 **/
function getOldTSEPVersion() {
    global $tsep_lng;
    @mysql_connect( $_SESSION["dbHost"], $_SESSION["dbUser"], $_SESSION["dbPwd"] );
    @mysql_select_db( $_SESSION["dbName"] );
    
    $SQLResult = @mysql_query( "SELECT stringvalue FROM ".$_SESSION["tablePrefixNew"]."internal WHERE description='tsepversion'" );
    if ( mysql_errno() == 0 ) {
        list( $ver ) = mysql_fetch_row( $SQLResult );
        $ver = ( $ver == "" ? $tsep_lng['setup_Unknown'] : $ver );
    } else {
        $ver = $tsep_lng['setup_Unknown'];
    }
    
    @mysql_close();
    
    $_SESSION["tsepPrevVersion"] = $ver;
    
    return $ver;
} // getOldTSEPVersion


/**
 * writeScreenBegin()
 * 
 * Builds the HTML code for the header of a page
 * 
 * @param integer $processStep The step the user is in
 * @return string HTML code
 **/
function writeScreenBegin( $processStep, $resetGlobalError=TRUE ) {
    global $processSteps, $tsep_lng;
    
    if ( $resetGlobalError ) {
        $_SESSION["globalErrorCode"] = 0;
        $_SESSION["globalErrorMessage"] = "";
    }
    
    $classActiveCell = "class=\"ActiveCell\"";
    $classActiveText = "class=\"ActiveText\"";
    
    $classInactiveCell = "class=\"InactiveCell\"";
    $classInactiveText = "class=\"InactiveText\"";

    /* Force the browser into UTF-8 display mode */
    $headers  = "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";
    
    header($headers);
    
    $html = "";

    $html .= "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
    $html .= "<html>\n";
    
    $html .= "<head>\n";
    $html .= "  <title>TSEP ".(isset($_SESSION["tsepVersion"])?$_SESSION["tsepVersion"]:"")." - ".$tsep_lng['setup_Setup']." .::. ".$processSteps[$processStep]."</title>\n";
    $html .= "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
    $html .= "  <meta http-equiv=\"expires\" content=\"0\" />\n";
    $html .= "  <link href=\"css/tsep_setup.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
    $html .= "  <script type=\"text/javascript\" src=\"js/overlib.js\">\n    <!-- overLIB (c) Erik Bosrup -->\n  </script>\n";
    $html .= "</head>\n";
    
    $html .= "<body>\n";

    $html .= "  <div id=\"overDiv\" style=\"position:absolute; visibility:hidden; z-index:1000;\"></div>\n";
    
    $html .= "  <div class=\"tsepSetup\">\n";
    $html .= "    <div style=\"padding: 4px;\">\n";

    $html .= "      <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
    $html .= "        <tr>\n";
    $html .= "          <td><img style=\"width: 310px; height: 61px;\" alt=\"{$tsep_lng['tsep']}\" src=\"../graphics/tsep.gif\"></td>\n";
    $html .= "          <td class=\"setupHeader\">{$tsep_lng['setup_Setup']}</td>\n";
    $html .= "        </tr>\n";
    $html .= "      </table>\n";

    $html .= "    </div>\n";

    $html .= "    <table class=\"mainContentTable\" cellpadding=\"0\" cellspacing=\"0\">\n";
    $html .= "      <tr>\n";
    $html .= "        <td style=\"vertical-align: top;\">\n";
    $html .= "          <table style=\"width: 100%; height: 100%; border: 0px none;\" cellpadding=\"10\" cellspacing=\"0\">\n";
    $html .= "            <tr>\n";
    $html .= "              <td ".( ($processStep == "intro") ? $classActiveCell : $classInactiveCell ).">\n";
    $html .= "                <span ".( ($processStep == "intro") ? $classActiveText : $classInactiveText ).">{$tsep_lng['setup_step1']}</span>\n";
    $html .= "              </td>\n";
    $html .= "            </tr>\n";
    $html .= "            <tr>\n";
    $html .= "              <td ".( ($processStep == "dbSetup") ? $classActiveCell : $classInactiveCell ).">\n";
    $html .= "                <span ".( ($processStep == "dbSetup") ? $classActiveText : $classInactiveText ).">{$tsep_lng['setup_step2']}</span>\n";
    $html .= "              </td>\n";
    $html .= "            </tr>\n";
    $html .= "            <tr>\n";
    $html .= "              <td ".( ($processStep == "sysCheck") ? $classActiveCell : $classInactiveCell ).">\n";
    $html .= "                <span ".( ($processStep == "sysCheck") ? $classActiveText : $classInactiveText ).">{$tsep_lng['setup_step3']}</span>\n";
    $html .= "              </td>\n";
    $html .= "            </tr>\n";
    $html .= "            <tr>\n";
    $html .= "              <td ".( ($processStep == "config") ? $classActiveCell : $classInactiveCell ).">\n";
    $html .= "                <span ".( ($processStep == "config") ? $classActiveText : $classInactiveText ).">{$tsep_lng['setup_step4']}</span>\n";
    $html .= "              </td>\n";
    $html .= "            </tr>\n";
    $html .= "            <tr>\n";
    $html .= "              <td ".( ($processStep == "install") ? $classActiveCell : $classInactiveCell ).">\n";
    $html .= "                <span ".( ($processStep == "install") ? $classActiveText : $classInactiveText ).">{$tsep_lng['setup_step5']}</span>\n";
    $html .= "              </td>\n";
    $html .= "            </tr>\n";
    $html .= "            <tr>\n";
    $html .= "              <td ".( ($processStep == "sum") ? $classActiveCell : $classInactiveCell ).">\n";
    $html .= "                <span ".( ($processStep == "sum") ? $classActiveText : $classInactiveText ).">{$tsep_lng['setup_step6']}</span>\n";
    $html .= "              </td>\n";
    $html .= "            </tr>\n";
    $html .= "            <tr>\n";
    $html .= "              <td ".( ($processStep == "feedback") ? $classActiveCell : $classInactiveCell ).">\n";
    $html .= "                <span ".( ($processStep == "feedback") ? $classActiveText : $classInactiveText ).">{$tsep_lng['setup_step7']}</span>\n";
    $html .= "              </td>\n";
    $html .= "            </tr>\n";
    $html .= "          </table>\n";
    $html .= "        </td>\n";
    $html .= "        <td class=\"contentCell\">\n";
    
    if ( isset( $_GET["errorMsg"] ) ) {
        $html .= "        <table style=\"border: 0px none; width: 100%;\" cellpadding=\"4\" cellspacing=\"0\">\n";
        $html .= "          <tr>\n";
        $html .= "            <td class=\"errorMessage\">".addslashes( $_GET["errorMsg"] )."</td>\n";
        $html .= "          </tr>\n";
        $html .= "          <tr>\n";
        $html .= "            <td><br /></td>\n";
        $html .= "          </tr>\n";
        $html .= "        </table>\n";
        
        unset( $_GET["errorMsg"] );
    }
        
    return $html;
} // writeScreenBegin


/**
 * cancelInstallation()
 * 
 * Builds the "are you sure" screen when the user clicks "cancel".
 * 
 * @param integer $currentStep The step the user was in when he clicked cancel
 * @return string HTML code for the page
 **/
function cancelInstallation( $fromStep ) {
	global $tsep_lng, $hintTitle;
    saveSettings();

    $installAbort = "yes";
    $sendNewTSEPVersion = "yes";
    $sendOldTSEPVersion = "yes";
    $domain = "yes";
    $email = "";
    $comment = "";
    $referer = ( $_SERVER["HTTPS"] == "on" ? "https://" : "http://" ).$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
    
    $html  = writeScreenBegin( $fromStep );

    $html .= "\n          <script type=\"text/javascript\">\n";
    $html .= "            function processUpdateClick() {\n";
    $html .= "              var URLString = '';\n";
    $html .= "              if ( document.cancelForm.installAbort[0].checked ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'installAbort=yes';\n";
    $html .= "              }\n";
    $html .= "              if ( document.cancelForm.sendNewTSEPVersion[0].checked ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'ver=".urlencode( $_SESSION["tsepVersion"] )."';\n";
    $html .= "              }\n";
    $html .= "              if ( document.cancelForm.sendOldTSEPVersion[0].checked ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'verprev=".urlencode( $_SESSION["tsepPrevVersion"] )."';\n";
    $html .= "              }\n";
    $html .= "              if ( document.cancelForm.domain[0].checked ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'logreferer=yes';\n";
    $html .= "              }\n";
    $html .= "              if ( document.cancelForm.email.value != '' ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'email=' + encodeURIComponent( document.cancelForm.email.value );\n";
    $html .= "              }\n";
    $html .= "              if ( document.cancelForm.comment.value != '' ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'comment=' + encodeURIComponent( document.cancelForm.comment.value );\n";
    $html .= "              }\n";
    $html .= "              URLString = ( URLString == '' ? '{$tsep_lng['setup_NoURL2Preview']}' : 'http://www.tsep.info' + URLString + ' &referer=".$referer."' );\n";
    $html .= "              document.getElementById( 'theURL' ).replaceChild( document.createTextNode( URLString ), document.getElementById( 'theURL' ).firstChild );\n";
    $html .= "            }\n";
    $html .= "          </script>\n\n";

    $html .= "          <form name=\"cancelForm\" action=\"".$_SERVER["PHP_SELF"]."\" method=\"get\">\n";
    $html .= "            <input type=\"hidden\" name=\"op\" value=\"quit\" />\n";
    $html .= "            <input type=\"hidden\" name=\"fromstep\" value=\"$fromStep\" />\n";
    $html .= "          <table style=\"border: 0px none;\" cellpadding=\"4\" cellspacing=\"0\">\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td colspan=\"3\" style=\"vertical-align: top;\">{$tsep_lng['setup_CancelButtonPressed']}</td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td colspan=\"3\" style=\"vertical-align: top;\">&nbsp;</td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td colspan=\"3\"><b>".$tsep_lng['setup_BeforeCancel']."</b></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left;\" colspan=\"2\">\n";
    $html .= $tsep_lng['setup_cancelText1']."<br />\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: top;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_cancelText2']}', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 75%;\">".$tsep_lng['setup_Let_TSEP_Team_know2']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <input type=\"radio\" name=\"installAbort\" value=\"yes\"".( $installAbort == "yes" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"installAbort_yes\" /><label for=\"installAbort_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"installAbort\" value=\"no\"".( $installAbort == "no" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"installAbort_no\" /><label for=\"installAbort_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_Let_TSEP_Team_know2_Help']}.', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 75%;\">".$tsep_lng['setup_NewVersion']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <input type=\"radio\" name=\"sendNewTSEPVersion\" value=\"yes\"".( $sendNewTSEPVersion == "yes" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"sendNewTSEPVersion_yes\" /><label for=\"sendNewTSEPVersion_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"sendNewTSEPVersion\" value=\"no\"".( $sendNewTSEPVersion == "no" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"sendNewTSEPVersion_no\" /><label for=\"sendNewTSEPVersion_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('".$tsep_lng['setup_NewVersion_Help']."', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 75%;\">".$tsep_lng['setup_OldVersion']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <input type=\"radio\" name=\"sendOldTSEPVersion\" value=\"yes\"".( $sendOldTSEPVersion == "yes" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"sendOldTSEPVersion_yes\" /><label for=\"sendOldTSEPVersion_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"sendOldTSEPVersion\" value=\"no\"".( $sendOldTSEPVersion == "no" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"sendOldTSEPVersion_no\" /><label for=\"sendOldTSEPVersion_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('".$tsep_lng['setup_OldVersion_Help']."', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 75%;\">".$tsep_lng['setup_Referer']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <input type=\"radio\" name=\"domain\" value=\"yes\"".( $domain == "yes" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"domain_yes\" /><label for=\"domain_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"domain\" value=\"no\"".( $domain == "no" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"domain_no\" /><label for=\"domain_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('".$tsep_lng['setup_Referer_Help']."', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 75%;\">".$tsep_lng['setup_NewsLetter']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <input type=\"text\" name=\"email\" value=\"".$email."\" size=\"30\" onkeyup=\"processUpdateClick()\" onblur=\"processUpdateClick()\" onfocus=\"processUpdateClick()\" />\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('".$tsep_lng['setup_NewsLetter_Help']."', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top; text-align: left; width: 75%;\">".$tsep_lng['setup_Why_Aborted']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <textarea name=\"comment\" rows=\"5\" cols=\"25\" onkeyup=\"processUpdateClick()\" onblur=\"processUpdateClick()\" onfocus=\"processUpdateClick()\">$comment</textarea>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: top;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('".$tsep_lng['setup_Why_Aborted_Help']."', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr><td colspan=\"3\" style=\"font-style: italic;\">".$tsep_lng['setup_URLPreview']."</td></tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td colspan=\"3\" style=\"text-align: left; width: 100%; border: 1px dotted rgb(51, 102, 255);\"><span id=\"theURL\" style=\"font-size: 0.8em; color: rgb(51, 102, 255); border-bottom: 1px dashed rgb(0, 192, 0);\">".$tsep_lng['setup_JavaScriptEnabled']."</span></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "          </table>\n";

    $html .= "    <table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 0px none; width: 100%;\">\n";
    $html .= "      <tr>\n";
    $html .= "        <td>&nbsp; </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr>\n";
    $html .= "        <td style=\"width: 100%; text-align: center; vertical-align: middle;\">\n";
    $html .= "          <a href=\"javascript:document.cancelForm.op.value='quit'; document.cancelForm.submit();\"><img src=\"images/apply_f2.png\" title=\"{$tsep_lng['setup_IwantToQuit']}\" />&nbsp;{$tsep_lng['setup_Quit']}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"javascript:document.cancelForm.op.value='$fromStep'; document.cancelForm.submit();\"><img src=\"images/restore_f2.png\" title=\"{$tsep_lng['setup_IwantToContinue']}\" />&nbsp;{$tsep_lng['setup_ContinueSetup']}</a>\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";
    $html .= "    </form>\n";

    // <---  FOOTER  ------------------------------------------------------------------->
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "    <table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 0px none; width: 100%;\">\n";
    $html .= "      <tr>\n";
    $html .= "        <td>&nbsp; </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr>\n";
    $html .= "        <td style=\"width: 100%; text-align: right; vertical-align: middle;\">\n";
    $html .= "          <img src=\"images/back.png\" title=\"{$tsep_lng['setup_ToPreviousStep']}\" />&nbsp;{$tsep_lng['setup_Previous']}&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <img src=\"images/next.png\" title=\"{$tsep_lng['setup_ToNextStep']}\" />&nbsp;{$tsep_lng['setup_Next']}&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <img src=\"images/cancel.png\" title=\"{$tsep_lng['setup_IWantToQuitInstalling']}\" />&nbsp;{$tsep_lng['setup_Cancel']}\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "  </div>\n";
    $html .= "  <script>processUpdateClick();</script>\n";
    $html .= "  <script>document.cancelForm.email.focus();</script>\n";
    
    $html .= "</body>\n";
    $html .= "</html>\n";

    return $html;
} // cancelInstallation

/***  END: General routines  ************************************************************/
/***  BEGIN: Process step: Introduction  ************************************************/

/**
 * writeIntroText()
 * 
 * Builds the HTML code for the intro page.
 * 
 * @return string HTML code for the page
 **/
function writeIntroText() {
		global $tsep_lng, $tsep_language;
		
    $html  = writeScreenBegin( "intro" );
    
    // show list of languages for selection
    $html .= "<form name='frmLanguage' id='frmLanguage' method='post' action='" . $_SERVER["PHP_SELF"] . "'>\n";
    $html .= "<div style='margin:1ex 0ex 1ex 0ex;'>" . $tsep_lng['select_language'] . ":";
    $html .= " <select name='lang' size='1' class='formfieldvalue_combo' onChange=\"document.frmLanguage.submit()\">\n";
	$d = opendir("../language");
	while (($lclLang = readdir($d)) != false) {
		if ( $lclLang != "." && $lclLang != ".." && ( strlen($lclLang) == 2 || strlen($lclLang) == 5 ) ) {
			$lclLangDesc = ( ( isset($tsep_language[$lclLang]) and !empty($tsep_language[$lclLang]) ) ?  $tsep_language[$lclLang] : $lclLang );
		    $html .= "  <option value='$lclLang'" . (($_SESSION['lang'] == $lclLang) ? " selected='selected'>" : ">");
		    $html .= "$lclLangDesc</option>\n";
		}
	}
	closedir($d);
    $html .=  " </select>\n";
    $html .=  " </form>\n";
    $html .= "</div>";
    
    $html .= $tsep_lng['setup_ThanksForConsidering'];
    
    // <---  FOOTER  ------------------------------------------------------------------->
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "    <table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 0px none; width: 100%;\">\n";
    $html .= "      <tr>\n";
    $html .= "        <td>&nbsp; </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr>\n";
    $html .= "        <td style=\"width: 100%; text-align: right; vertical-align: middle;\">\n";
    $html .= "          <img src=\"images/back.png\" title=\"{$tsep_lng['setup_ToPreviousStep']}\" />&nbsp;{$tsep_lng['setup_Previous']}&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"".$_SERVER["PHP_SELF"]."?op=dbSetup\"><img src=\"images/next_f2.png\" title=\"{$tsep_lng['setup_ToNextStep']}\" />&nbsp;{$tsep_lng['setup_Next']}</a>&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"".$_SERVER["PHP_SELF"]."?op=cancel&fromstep=intro\"><img src=\"images/cancel_f2.png\" title=\"{$tsep_lng['setup_IWantToQuitInstalling']}\" />&nbsp;{$tsep_lng['setup_Cancel']}</a>\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "  </div>\n";
    
    $html .= "</body>\n";
    $html .= "</html>\n";

    return $html;
} // writeIntroText

/***  END: Process step: Introduction  **************************************************/
/***  BEGIN: Process step: Database setup  **********************************************/

/**
 * databaseData()
 * 
 * Set's up the form for the input of the database configuration.
 * When displaying the form the routine tries to supply the user
 * with default values. If the form values are already filled
 * (when the user back up through the process) the values are
 * copied into the fields.
 * 
 * @return string HTML code to the form
 **/
function databaseData() {
    global $hintTitle, $tsep_lng;
    
    saveSettings();
    
    $html = writeScreenBegin( "dbSetup" );
    
    if ( !isset( $_SESSION["dbHost"] ) or empty( $_SESSION["dbHost"] ) ) {
        $_SESSION["dbHost"] = "localhost";
    }
    if ( !isset( $_SESSION["dbName"] ) or empty( $_SESSION["dbName"] ) ) {
        $_SESSION["dbName"] = "tsep";
    }
    if ( !isset( $_SESSION["dbUser"] ) ) {
        $_SESSION["dbUser"] = "";
    }
    if ( !isset( $_SESSION["dbPwd"] ) ) {
        $_SESSION["dbPwd"] = "";
    }
    if ( !isset( $_SESSION["tsepPath"] ) or empty( $_SESSION["tsepPath"] ) ) {
        $_SESSION["tsepPath"] = str_replace( "/admin/".basename( $_SERVER["PHP_SELF"] ), '', $_SERVER["DOCUMENT_ROOT"].$_SERVER["PHP_SELF"] );
    }
	$_SESSION["tsepPath"] = preg_replace("/\/+/", "/", $_SESSION["tsepPath"]); // remove double-slashes
    if ( !isset( $_SESSION["tmpPath"] ) or empty( $_SESSION["tmpPath"] ) ) {
        $_SESSION["tmpPath"] = preg_replace("/\/+$/", "", $_ENV["TMP"]) . "/tsep";
    }
    $_SESSION["tmpPath"] = preg_replace('/\\\+/', "/", $_SESSION["tmpPath"]);
    if ( !isset( $_SESSION["tsepRoot"] ) or empty( $_SESSION["tsepRoot"] ) ) {
        $_tsepRoot = str_replace( "/admin/".basename( $_SERVER["PHP_SELF"] ), '', $_SERVER["PHP_SELF"] );
        $_SESSION["tsepRoot"] = substr( $_tsepRoot, 1, strlen( $_tsepRoot ));
    }
    if ( !isset( $_SESSION["tablePrefixNew"] ) or empty( $_SESSION["tablePrefixNew"] ) ) {
        $_SESSION["tablePrefixNew"] = "tsep_";
    }
    if ( !isset( $_SESSION["createDB"] ) ) {
        $createDB = "yes";
    } else {
        $createDB = $_SESSION["createDB"];
    }

    $html .= $tsep_lng['setup_DB_1']."<br />\n";
    $html .= "          <br />\n";
    $html .= "          <form name=\"dbSetupForm\" action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n";
    $html .= "            <input type=\"hidden\" name=\"op\" value=\"\" />\n";
    $html .= "            <input type=\"hidden\" name=\"fromstep\" value=\"dbSetup\" />\n";
    $html .= "          <table style=\"border: 0px none;\" cellpadding=\"4\" cellspacing=\"0\">\n";
    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_DB_2_Host']}</td>\n";
    $html .= "              <td style=\"vertical-align: bottom;\"><input type=\"text\" name=\"dbHost\" value=\"".$_SESSION["dbHost"]."\" size=\"50\" /></td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_DB_2_Host_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_DB_3_Username']}</td>\n";
    $html .= "              <td style=\"vertical-align: bottom;\"><input type=\"text\" name=\"dbUser\" value=\"".$_SESSION["dbUser"]."\" size=\"50\" /></td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_DB_3_Username_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_DB_4_Passwd']}</td>\n";
    $html .= "              <td style=\"vertical-align: bottom;\"><input type=\"text\" name=\"dbPwd\" value=\"".$_SESSION["dbPwd"]."\" size=\"50\" /></td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_DB_4_Passwd_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_DB_5_DBName']}</td>\n";
    $html .= "              <td style=\"vertical-align: bottom;\"><input type=\"text\" name=\"dbName\" value=\"".$_SESSION["dbName"]."\" size=\"50\" /></td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_DB_5_DBName_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_DB_6_ForceCreation']}</td>\n";
    $html .= "              <td style=\"vertical-align: bottom;\">\n";
    $html .= "                <input type=\"radio\" name=\"createDB\" value=\"yes\"".( $createDB == "yes" ? " checked" : "" )." id=\"createDB_yes\" /><label for=\"createDB_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"createDB\" value=\"no\"".( $createDB == "no" ? " checked" : "" )." id=\"createDB_no\" /><label for=\"createDB_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_DB_6_ForceCreation_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_DB_7_Prefix']}</td>\n";
    $html .= "              <td style=\"vertical-align: bottom;\"><input type=\"text\" name=\"tablePrefixNew\" value=\"".$_SESSION["tablePrefixNew"]."\" size=\"50\" /></td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_DB_7_Prefix_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_DB_8_TSEP_Root']}</td>\n";
    $html .= "              <td style=\"vertical-align: bottom;\"><input type=\"text\" name=\"tsepRoot\" value=\"".$_SESSION["tsepRoot"]."\" size=\"50\" /></td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_DB_8_TSEP_Root_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_DB_9_TSEP_AbsPath']}</td>\n";
    $html .= "              <td style=\"vertical-align: bottom;\"><input type=\"text\" name=\"tsepPath\" value=\"".$_SESSION["tsepPath"]."\" size=\"50\" /></td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_DB_9_TSEP_AbsPath_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_DB_10_TSEP_TmpPath']}</td>\n";
    $html .= "              <td style=\"vertical-align: bottom;\"><input type=\"text\" name=\"tmpPath\" value=\"".$_SESSION["tmpPath"]."\" size=\"50\" /></td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_DB_10_TSEP_TmpPath_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    $html .= "          </table>\n";

    // <---  FOOTER  ------------------------------------------------------------------->
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "    <table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 0px none; width: 100%;\">\n";
    $html .= "      <tr>\n";
    $html .= "        <td>&nbsp; </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr>\n";
    $html .= "        <td style=\"width: 100%; text-align: right; vertical-align: middle;\">\n";
    $html .= "          <a href=\"javascript:document.dbSetupForm.op.value='intro'; document.dbSetupForm.submit();\"><img src=\"images/back_f2.png\" title=\"{$tsep_lng['setup_ToPreviousStep']}\" />&nbsp;{$tsep_lng['setup_Previous']}</a>&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"javascript:document.dbSetupForm.op.value='confirm_dbSetup'; document.dbSetupForm.submit();\"><img src=\"images/next_f2.png\" title=\"{$tsep_lng['setup_ToNextStep']}\" />&nbsp;{$tsep_lng['setup_Next']}</a>&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"javascript:document.dbSetupForm.op.value='cancel'; document.dbSetupForm.submit();\"><img src=\"images/cancel_f2.png\" title=\"{$tsep_lng['setup_IWantToQuitInstalling']}\" />&nbsp;{$tsep_lng['setup_Cancel']}</a>\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";
    $html .= "    </form>\n";
    $html .= "    <script>document.dbSetupForm.dbHost.focus();</script>\n";

    $html .= "  </div>\n";
    
    $html .= "</body>\n";
    $html .= "</html>\n";

    return $html;
} // databaseData


/**
 * confirm_dbSetup()
 * 
 * Confirms the settings the user entered in the database setup form.
 * If correct the user is taken to the next step.
 * If incorrect the user is sent back to the database setup screen.
 * 
 * @return void
 **/
function confirm_dbSetup() {
    global $checkFile, $tsep_lng;
    
    saveSettings();

        /* Check connection to the server */
    if ( !@mysql_connect( $_SESSION["dbHost"], $_SESSION["dbUser"], $_SESSION["dbPwd"] )) {

        if ( mysql_errno() == 2005 ) {
            header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_UnknownDBHost'] );
        }
        elseif ( mysql_errno() == 1045 ) {
            header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_NoDBAccess'] );
        } else {
            header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_ConnectionDenied'] );
        }
        
        die();
    }
        
    /* Try to create the database */
    if ( $_SESSION["createDB"] == "yes" ) {
        $result = @mysql_query( "CREATE DATABASE IF NOT EXISTS ".$_SESSION["dbName"] );
        if ( mysql_errno() <> 0 ) {
            mysql_close();
            header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_DBNotExists'] );
            die();
        }
    }

    /* Check connection to the database */
	if ( !@mysql_select_db( $_SESSION["dbName"] )) {
        
        if ( mysql_errno() == 1049 ) {
            mysql_close();
            header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_DBNameWrong'] );
            die();
            
        } else {
            mysql_close();
            header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_DBUnknownError'] );
            die();
        }
    }
    
    mysql_close();
    
    /* Check validity of the root dir */
    if ( $_SESSION["tsepRoot"] != "" ) {
        if ( !file_exists( $_SERVER["DOCUMENT_ROOT"]."/".$_SESSION["tsepRoot"].$checkFile ) ) {
            header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_TSEPRootWrong'] );
            die();
        }
    }
    
    /* Check validity of the absolute path */
    if ( !file_exists( $_SESSION["tsepPath"].$checkFile ) or $_SESSION["tsepPath"] == "" ) {
        header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_TSEPAbsPathWrong'] );
        die();
    }
    
    /* Check validity of the temp path */
    if ( $_SESSION["tmpPath"] == "" ) {
        header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_TSEPTmpPathWrong'] );
        die();
    }
    @mkdir($_SESSION["tmpPath"]);
    if ( !is_dir( $_SESSION["tmpPath"] ) ) {
        header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_TSEPTmpPathWrong'] );
        die();
    }
    @chmod($_SESSION["tmpPath"], 0666);
    $tmpFn = $_SESSION["tmpPath"] . "/setup_check.tmp";
    $htmpFn = @fopen($tmpFn, "w"); @fclose($htmpFn);
    if ( !file_exists( $tmpFn ) ) {
        header( "location: ".$_SERVER["PHP_SELF"]."?op=dbSetup&errorMsg=".$tsep_lng['setup_TSEPTmpPathNotWritable'] );
        die();
    }
    unlink($tmpFn);
    
    header( "location: ".$_SERVER["PHP_SELF"]."?op=sysCheck" );
} // confirm_dbSetup

/***  END: Process step: Database setup  ************************************************/
/***  GEGIN: Process step: System check  ************************************************/

/**
 * checkHTAccess()
 * 
 * Checks if the designated area is protected by
 * an .htaccess file.
 * 
 * @return string "ok" if protected, message if not
 **/
function checkHTAccess( $htAccessFile ) {
		global $tsep_lng;
    $securityString = $tsep_lng['setup_OK'];

    if ( !file_exists( $htAccessFile ) ) {
        $securityString = $tsep_lng['setup_HTAccessNotFound'];
    }

    if ( $securityString == $tsep_lng['setup_OK'] ) {
        $htaccess = @file_get_contents( $htAccessFile );
        if ( !preg_match( '/[\t ]*require[\t ]*user/i', $htaccess ) and 
             !preg_match( '/require[\t ]*valid-user/i', $htaccess ) and
             !preg_match( '/[\t ]*deny[\t ]*from[\t ]*all/i', $htaccess )) {
            $securityString = $tsep_lng['setup_NoProtectionFound'];
        }
    }
    
    return $securityString;
} // checkHTAccess


/**
 * downloadDBConnData()
 * 
 * Creates the code to download the file "global.php"
 * 
 * @return string HTML code to the page
 **/
function downloadDBConnData() {
    global $hintTitle, $tsep_lng;
    
    $connData = nl2br( buildConnData() );
    $_SESSION["globalIsPatched"] = "yes";
    
    $html  = writeScreenBegin( "sysCheck" );
    
    $html .= $tsep_lng['setup_Global_1'];
    $html .= "          <br />\n";
    $html .= $tsep_lng['setup_Global_2'];
    $html .= "          <ol>\n";
    $html .= "            <li>{$tsep_lng['setup_Global_3']}\n";
    $html .= "              <table><tr><td style=\"border: 1px dotted rgb(0, 192, 0); font-family: courier; color: rgb(51, 102, 255); padding: 0.5em;\">$connData</td></tr></table>\n";
    $html .= "            </li>\n";
    $html .= "            <li>{$tsep_lng['setup_Global_3s1']}</li>\n";
    $html .= "            <li>{$tsep_lng['setup_Global_3s21']} \"<span style=\"font-family: courier; color: rgb(51, 102, 255);\">%BEGIN_SETUP_DATABASE_DATA%</span>\" {$tsep_lng['setup_and']} \"<span style=\"font-family: courier; color: rgb(51, 102, 255);\">%END_SETUP_DATABASE_DATA%</span>\"{$tsep_lng['setup_Global_3s22']}</li>\n";
    $html .= "            <li>{$tsep_lng['setup_Global_3s3']}</li>\n";
    $html .= "            <li>{$tsep_lng['setup_Global_3s4']}</li>\n";
    $html .= "            <li>{$tsep_lng['setup_Global_3s5']}</li>\n";
    $html .= "          </ol>\n";
    $html .= "          <br />\n";
    $html .= $tsep_lng['setup_Global_4'];
    $html .= "          <form name=\"downloadForm\" action=\"".$_SERVER["PHP_SELF"]."\" method=\"get\">\n";
    $html .= "            <input type=\"hidden\" name=\"op\" value=\"\" />\n";
    $html .= "            <input type=\"hidden\" name=\"fromstep\" value=\"sysCheck\" />\n";

    // <---  FOOTER  ------------------------------------------------------------------->
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "    <table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 0px none; width: 100%;\">\n";
    $html .= "      <tr>\n";
    $html .= "        <td>&nbsp; </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr>\n";
    $html .= "        <td style=\"width: 100%; text-align: right; vertical-align: middle;\">\n";
    
    $html .= "          <a href=\"javascript:document.downloadForm.op.value='dbSetup'; document.downloadForm.submit();\"><img src=\"images/back_f2.png\" title=\"{$tsep_lng['setup_ToPreviousStep']}\" />&nbsp;{$tsep_lng['setup_Previous']}</a>&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"javascript:document.downloadForm.op.value='config'; document.downloadForm.submit();\"><img src=\"images/next_f2.png\" title=\"{$tsep_lng['setup_ToNextStep']}\" />&nbsp;{$tsep_lng['setup_Next']}</a>&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"javascript:document.downloadForm.op.value='cancel'; document.downloadForm.submit();\"><img src=\"images/cancel_f2.png\" title=\"{$tsep_lng['setup_IWantToQuitInstalling']}\" />&nbsp;{$tsep_lng['setup_Cancel']}</a>\n";
    
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";
    $html .= "    </form>\n";

    $html .= "  </div>\n";
    
    $html .= "</body>\n";
    $html .= "</html>\n";

    return $html;
} // downloadDBConnData


/**
 * systemCheck()
 * 
 * Check several parameters in regard to the system setup.
 * Warns if something isn't up to par.
 * 
 * @return string HTML code to the page
 **/
function systemCheck() {
    global $hintTitle, $tsep_lng;
    
    saveSettings();

    /* TSEP version */
    if ( !isset( $_SESSION["tsepVersion"] ) or $_SESSION["tsepVersion"] == "" ) {
        $_SESSION["tsepVersion"] = "";
        getTSEPVersion();
    }
    
    /* Check if DBConnectionData.php is writable */
    $dbConDataWritable = is_writable( $_SESSION["tsepPath"]."/include/global.php" );
    /* If not writable create the download code */
    if ( !$dbConDataWritable ) {
        $dlLink = "<a href=\"".$_SERVER["PHP_SELF"]."?op=dlDBConnData\"><span style=\"background-color: red; color: white; padding: 0.2em; font-weight: bold;\">{$tsep_lng['setup_patch_manually']}</span></a> <img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_patch_manually_help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" />";
    } else {
        $dlLink = "";
    }
    
    /* Check if the admin area is secure */
    
    /* Check MySQL */
    @mysql_connect( $_SESSION["dbHost"], $_SESSION["dbUser"], $_SESSION["dbPwd"] );
    $MySQLVersion = mysql_get_server_info();
    @mysql_close();
    if ( version_compare( $MySQLVersion, "3.23" ) == -1 ) {
        $MySQLversionString = $tsep_lng['setup_warning'];
    } else {
        $MySQLversionString = $tsep_lng['setup_OK'];
    }
        
    /* Check PHP */
    $PHPVersion = phpversion();
    if ( version_compare( $PHPVersion, "4.2" ) == -1 ) {
        $PHPversionString = $tsep_lng['setup_warning'];
    } else {
        $PHPversionString = $tsep_lng['setup_OK'];
    }
    
    /* Check security */
    $htAccessFile = $_SESSION["tsepPath"]."/admin/.htaccess";
    $adminSecString = checkHTAccess( $htAccessFile );
    $htAccessFile = $_SESSION["tsepPath"]."/include/.htaccess";
    $includeSecString = checkHTAccess( $htAccessFile );
    
    
    $html  = writeScreenBegin( "sysCheck" );
    
    $html .= $tsep_lng['setup_SysChk_1'];
    $html .= "          <br />\n";
    $html .= "          <form name=\"sysCheckForm\" action=\"".$_SERVER["PHP_SELF"]."\" method=\"get\">\n";
    $html .= "            <input type=\"hidden\" name=\"op\" value=\"\" />\n";
    $html .= "            <input type=\"hidden\" name=\"fromstep\" value=\"sysCheck\" />\n";
    $html .= "          <table style=\"border: 0px none;\" cellpadding=\"4\" cellspacing=\"0\">\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_MySQL_version']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">$MySQLVersion (".( $MySQLversionString == $tsep_lng['setup_OK'] ? "<font color=\"green\">$MySQLversionString</font>" : "<font color=\"red\"><b>$MySQLversionString</b></font>" ).")</td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_MySQL_version_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "              <td style=\"vertical-align: top;\"></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_PHP_version']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">$PHPVersion (".( $PHPversionString == $tsep_lng['setup_OK'] ? "<font color=\"green\">$PHPversionString</font>" : "<font color=\"red\"><b>$PHPversionString</b></font>" ).")</td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_PHP_version_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "              <td style=\"vertical-align: top;\"></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_TSEP_oldver']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">".getOldTSEPVersion()."</td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_TSEP_oldver_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "              <td style=\"vertical-align: top;\"></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_TSEP_newver']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">".$_SESSION["tsepVersion"]."</td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_TSEP_newver_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "              <td style=\"vertical-align: top;\"></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_DB_Config_File']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">".( $dbConDataWritable ? "<font color=\"green\">{$tsep_lng['setup_DB_Config_File_Writable']}</font>" : "<font color=\"red\"><b>{$tsep_lng['setup_DB_Config_File_UnWritable']}</b></font>" )."</td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_DB_Config_File_Help_1']}".( !$dbConDataWritable ? "<br /><br />{$tsep_lng['setup_DB_Config_File_Help_2']}" : "" )."', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "              <td style=\"vertical-align: top;\">$dlLink</td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_PHPSafeMode']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">".( ini_get( "safe_mode" ) ? "<font color=\"red\"><b>{$tsep_lng['setup_On']}</b></font>" : "<font color=\"green\">{$tsep_lng['setup_Off']}</font>" )."</td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_PHPSafeMode_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "              <td style=\"vertical-align: top;\"></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_Admin_area_security']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">".( $adminSecString == $tsep_lng['setup_OK'] ? "<font color=\"green\">{$tsep_lng['setup_Protected']}</font>" : "<font color=\"red\"><b>$adminSecString</b></font>" )."</td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_Admin_area_security_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "              <td style=\"vertical-align: top;\"></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_Include_dir_security']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">".( $includeSecString == $tsep_lng['setup_OK'] ? "<font color=\"green\">{$tsep_lng['setup_Protected']}</font>" : "<font color=\"red\"><b>$includeSecString</b></font>" )."</td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_Include_dir_security_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "              <td style=\"vertical-align: top;\"></td>\n";
    $html .= "            </tr>\n";

    $html .= "          </table>\n";

    // <---  FOOTER  ------------------------------------------------------------------->
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "    <table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 0px none; width: 100%;\">\n";
        $html .= "      <tr>\n";
        $html .= "        <td>&nbsp; </td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "        <td style=\"width: 100%; text-align: right; vertical-align: middle;\">\n";
        $html .= "          <a href=\"javascript:document.sysCheckForm.op.value='dbSetup'; document.sysCheckForm.submit();\"><img src=\"images/back_f2.png\" title=\"{$tsep_lng['setup_ToPreviousStep']}\" />&nbsp;{$tsep_lng['setup_Previous']}</a>&nbsp;&nbsp;&nbsp;\n";
        $html .= "          <a href=\"javascript:document.sysCheckForm.op.value='confirm_sysCheck'; document.sysCheckForm.submit();\"><img src=\"images/next_f2.png\" title=\"{$tsep_lng['setup_ToNextStep']}\" />&nbsp;{$tsep_lng['setup_Next']}</a>&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"javascript:document.sysCheckForm.op.value='cancel'; document.sysCheckForm.submit();\"><img src=\"images/cancel_f2.png\" title=\"{$tsep_lng['setup_IWantToQuitInstalling']}\" />&nbsp;{$tsep_lng['setup_Cancel']}</a>\n";
        $html .= "        </td>\n";
        $html .= "      </tr>\n";
    $html .= "    </table>\n";
    $html .= "    </form>\n";

    $html .= "  </div>\n";
    
    $html .= "</body>\n";
    $html .= "</html>\n";

    return $html;
} // systemCheck


/**
 * confirm_sysCheck()
 * 
 * Check the settings made in the sysCheck screen.
 * If incorrect the user is transported back to the sysCheck screen.
 * 
 * @return void
 **/
function confirm_sysCheck() {
		global $tsep_lng;
    /* Check if DBConnectionData.php is writable */
    $dbConDataWritable = is_writable( $_SESSION["tsepPath"]."/include/global.php" );
    
    if ( !$dbConDataWritable ) {
        if ( !@chmod( $filename, 0666 )) {
            header( "location: ".$_SERVER["PHP_SELF"]."?op=sysCheck&errorMsg=".$tsep_lng['setup_DBcfgUnwriteable'] );
            die();
        }
    }
    
    header( "location: ".$_SERVER["PHP_SELF"]."?op=config" );
} // confirm_sysCheck

/***  END: Process step: System check  **************************************************/
/***  BEGIN: Process step: Configuration  ***********************************************/

/**
 * checkUpdateSetting()
 * 
 * Check the update parameter.
 * If the user want's to update the prefix has to match.
 * If the user doesn't want to update the prefix must be unique.
 * 
 * @return boolean TRUE is a previously installed TSEP version exists
 **/
function checkUpdateSetting() {
		global $tsep_lng;
    @mysql_connect( $_SESSION["dbHost"], $_SESSION["dbUser"], $_SESSION["dbPwd"] );
    @mysql_select_db( $_SESSION["dbName"] );
    $result = @mysql_query( "SELECT idlog FROM ".$_SESSION["tablePrefixNew"]."log LIMIT 1" );
    
    if ( !$result or mysql_errno() != 0 ) {
        $prefixExists = FALSE;
    } else {
        $prefixExists =  TRUE;
    }
    @mysql_close();
    
    return $prefixExists;
} // checkUpdateSetting


/**
 * Configuration()
 * 
 * Display the form to set the installation options.
 * 
 * @return string HTML code to the page
 **/
function Configuration() {
    global $hintTitle, $tsep_lng;
    
    saveSettings();
    
    /* Check fresh installation of TSEP */
    if ( !isset( $_SESSION["freshInstall"] ) ) {
        $freshInstall = ( checkUpdateSetting() ? "no" : "yes" );
    } else {
        $freshInstall = $_SESSION["freshInstall"];
    }
    
    /* Check update settings */
    if ( !isset( $_SESSION["update"] ) ) {
        /* Check if we need to update or install a fresh copy of TSEP */
        $update = ( checkUpdateSetting() ? "yes" : "no" );
    } else {
        $update = $_SESSION["update"];
    }
    
    /* Check copy configuration settings */
    if ( !isset( $_SESSION["settings"] ) ) {
        /* Check if we need to update or install a fresh copy of TSEP */
        $settings = $update;
    } else {
        $settings = $_SESSION["settings"];
    }
    
    /* Check copy profile settings */
    if ( !isset( $_SESSION["profiles"] ) ) {
        /* Check if we need to update or install a fresh copy of TSEP */
        $profiles = $update;
    } else {
        $profiles = $_SESSION["profiles"];
    }
    
    /* Check copy index settings */
    if ( !isset( $_SESSION["indexes"] ) ) {
        /* Check if we need to update or install a fresh copy of TSEP */
        $indexes = $update;
    } else {
        $indexes = $_SESSION["indexes"];
    }
    
    /* Check copy stopwords settings */
    if ( !isset( $_SESSION["stopwords"] ) ) {
        /* Check if we need to update or install a fresh copy of TSEP */
        $stopwords = $update;
    } else {
        $stopwords = $_SESSION["stopwords"];
    }
    
    /* Check copy logs settings */
    if ( !isset( $_SESSION["logs"] ) ) {
        /* Check if we need to update or install a fresh copy of TSEP */
        $logs = $update;
    } else {
        $logs = $_SESSION["logs"];
    }
    
    
    $html  = writeScreenBegin( "config" );
    
    $html .= "\n          <script type=\"text/javascript\">\n";
    $html .= "            function processFreshInstallClick() {\n";
    /* If "freshInstall" is set to "no", update must be set to "yes" */
    $html .= "              if ( document.ConfigForm.freshInstall[1].checked ) {\n";
    $html .= "                document.ConfigForm.update[0].checked = true;\n";
    $html .= "              } else {\n";
    /* If "freshInstall" is set to "yes", update must be set to "no" */
    $html .= "                document.ConfigForm.update[1].checked = true;\n";
    $html .= "                processUpdateClick();\n";
    $html .= "              }\n";
    $html .= "            }\n";
    $html .= "            function processUpdateClick() {\n";
    $html .= "              if ( document.ConfigForm.update[1].checked ) {\n";
    /* If update is set to "NO", all other options must be set to "NO" also */
    /* except for freshInstall, which must be set to "YES"                  */
    $html .= "                document.ConfigForm.settings[1].checked = true;\n";
    $html .= "                document.ConfigForm.profiles[1].checked = true;\n";
    $html .= "                document.ConfigForm.indexes[1].checked = true;\n";
    $html .= "                document.ConfigForm.stopwords[1].checked = true;\n";
    $html .= "                document.ConfigForm.logs[1].checked = true;\n";
    $html .= "                document.ConfigForm.ranksymbols[1].checked = true;\n";
    $html .= "                document.ConfigForm.freshInstall[0].checked = true;\n";
    $html .= "              } else {\n";
    $html .= "                document.ConfigForm.freshInstall[1].checked = true;\n";
    $html .= "              }\n";
    $html .= "            }\n";
    $html .= "            function processDetailsClick() {\n";
    /* If any of the options is set to "YES", update must be set to "YES" also */
    /* freshInstall must be set to "NO" in this case                           */
    $html .= "              if ( document.ConfigForm.settings[0].checked ) {\n";
    $html .= "                document.ConfigForm.update[0].checked = true;\n";
    $html .= "                document.ConfigForm.freshInstall[1].checked = true;\n";
    $html .= "              }\n";
    $html .= "              if ( document.ConfigForm.profiles[0].checked ) {\n";
    $html .= "                document.ConfigForm.update[0].checked = true;\n";
    $html .= "                document.ConfigForm.freshInstall[1].checked = true;\n";
    $html .= "              }\n";
    $html .= "              if ( document.ConfigForm.indexes[0].checked ) {\n";
    $html .= "                document.ConfigForm.update[0].checked = true;\n";
    $html .= "                document.ConfigForm.profiles[0].checked = true;\n";
    $html .= "                document.ConfigForm.freshInstall[1].checked = true;\n";
    $html .= "              }\n";
    $html .= "              if ( document.ConfigForm.stopwords[0].checked ) {\n";
    $html .= "                document.ConfigForm.update[0].checked = true;\n";
    $html .= "                document.ConfigForm.freshInstall[1].checked = true;\n";
    $html .= "              }\n";
    $html .= "              if ( document.ConfigForm.logs[0].checked ) {\n";
    $html .= "                document.ConfigForm.update[0].checked = true;\n";
    $html .= "                document.ConfigForm.freshInstall[1].checked = true;\n";
    $html .= "              }\n";
    $html .= "              if ( document.ConfigForm.ranksymbols[0].checked ) {\n";
    $html .= "                document.ConfigForm.update[0].checked = true;\n";
    $html .= "                document.ConfigForm.freshInstall[1].checked = true;\n";
    $html .= "              }\n";
    $html .= "            }\n";
    $html .= "          </script>\n\n";
    
    $html .= $tsep_lng['setup_UpdateOrFresh'];
    $html .= "          <br />\n";
    $html .= "          <form name=\"ConfigForm\" action=\"".$_SERVER["PHP_SELF"]."\" method=\"get\">\n";
    $html .= "            <input type=\"hidden\" name=\"op\" value=\"\" />\n";
    $html .= "            <input type=\"hidden\" name=\"fromstep\" value=\"config\" />\n";
    $html .= "          <table style=\"border: 0px none;\" cellpadding=\"4\" cellspacing=\"0\">\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_Fresh']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">\n";
    $html .= "                <input type=\"radio\" name=\"freshInstall\" value=\"yes\"".( $freshInstall == "yes" ? " checked" : "" )." onclick=\"processFreshInstallClick()\" id=\"freshInstall_yes\" /><label for=\"freshInstall_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"freshInstall\" value=\"no\"".( $freshInstall == "no" ? " checked" : "" )." onclick=\"processFreshInstallClick()\" id=\"freshInstall_no\" /><label for=\"freshInstall_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_Fresh_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_Update']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">\n";
    $html .= "                <input type=\"radio\" name=\"update\" value=\"yes\"".( $update == "yes" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"update_yes\" /><label for=\"update_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"update\" value=\"no\"".( $update == "no" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"update_no\" /><label for=\"update_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_Update_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_CopyOld']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">\n";
    $html .= "                <input type=\"radio\" name=\"settings\" value=\"yes\"".( $settings == "yes" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"settings_yes\" /><label for=\"settings_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"settings\" value=\"no\"".( $settings == "no" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"settings_no\" /><label for=\"settings_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_CopyOld_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_CopyOldProfiles']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">\n";
    $html .= "                <input type=\"radio\" name=\"profiles\" value=\"yes\"".( $profiles == "yes" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"profiles_yes\" /><label for=\"profiles_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"profiles\" value=\"no\"".( $profiles == "no" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"profiles_no\" /><label for=\"profiles_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_CopyOldProfiles_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_CopyOldIndexes']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">\n";
    $html .= "                <input type=\"radio\" name=\"indexes\" value=\"yes\"".( $indexes == "yes" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"indexes_yes\" /><label for=\"indexes_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"indexes\" value=\"no\"".( $indexes == "no" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"indexes_no\" /><label for=\"indexes_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_CopyOldIndexes_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_CopyOldStopwords']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">\n";
    $html .= "                <input type=\"radio\" name=\"stopwords\" value=\"yes\"".( $stopwords == "yes" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"stopwords_yes\" /><label for=\"stopwords_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"stopwords\" value=\"no\"".( $stopwords == "no" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"stopwords_no\" /><label for=\"stopwords_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_CopyOldStopwords_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_CopyOldLogs']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">\n";
    $html .= "                <input type=\"radio\" name=\"logs\" value=\"yes\"".( $logs == "yes" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"logs_yes\" /><label for=\"logs_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"logs\" value=\"no\"".( $logs == "no" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"logs_no\" /><label for=\"logs_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_CopyOldLogs_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top;\">{$tsep_lng['setup_CopyOldRankSymbols']}</td>\n";
    $html .= "              <td style=\"vertical-align: top;\">\n";
    $html .= "                <input type=\"radio\" name=\"ranksymbols\" value=\"yes\"".( $logs == "yes" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"ranksymbols_yes\" /><label for=\"ranksymbols_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"ranksymbols\" value=\"no\"".( $logs == "no" ? " checked" : "" )." onclick=\"processDetailsClick()\" id=\"ranksymbols_no\" /><label for=\"ranksymbols_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_CopyOldRankSymbols_Help']}', CAPTION, '$hintTitle', WIDTH, 175);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";

    $html .= "          </table>\n";

    // <---  FOOTER  ------------------------------------------------------------------->
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "    <table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 0px none; width: 100%;\">\n";
    $html .= "      <tr>\n";
    $html .= "        <td>&nbsp; </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr>\n";
    $html .= "        <td style=\"width: 100%; text-align: right; vertical-align: middle;\">\n";
    $html .= "          <a href=\"javascript:document.ConfigForm.op.value='sysCheck'; document.ConfigForm.submit();\"><img src=\"images/back_f2.png\" title=\"{$tsep_lng['setup_ToPreviousStep']}\" />&nbsp;{$tsep_lng['setup_Previous']}</a>&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"javascript:document.ConfigForm.op.value='confirm_Config'; document.ConfigForm.submit();\"><img src=\"images/next_f2.png\" title=\"{$tsep_lng['setup_ToNextStep']}\" />&nbsp;{$tsep_lng['setup_Next']}</a>&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"javascript:document.ConfigForm.op.value='cancel'; document.ConfigForm.submit();\"><img src=\"images/cancel_f2.png\" title=\"{$tsep_lng['setup_IWantToQuitInstalling']}\" />&nbsp;{$tsep_lng['setup_Cancel']}</a>\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";
    $html .= "    </form>\n";

    $html .= "  </div>\n";
    
    $html .= "</body>\n";
    $html .= "</html>\n";

    return $html;
}  // Configuration


/**
 * confirm_Config()
 * 
 * Check the settings from the config screen.
 * If something is wrong it reverts back.
 * 
 * @return void
 **/
function confirm_Config() {
		global $tsep_lng;
    saveSettings();
    
    /* Check update settings */
    $prefixExists = checkUpdateSetting();
    if ( $prefixExists and $_SESSION["update"] == "no" and $_SESSION["freshInstall"] == "no" ) {
        header( "location: ".$_SERVER["PHP_SELF"]."?op=config&errorMsg=".$tsep_lng['setup_IndicateNoUpdate'] );
        die();
    }
    if ( !$prefixExists and $_SESSION["update"] == "yes" ) {
        header( "location: ".$_SERVER["PHP_SELF"]."?op=config&errorMsg=".$tsep_lng['setup_IndicateUpdate'] );
        die();
    }
    
    header( "location: ".$_SERVER["PHP_SELF"]."?op=install" );
} // confirm_Config

/***  END: Process step: Configuration  *************************************************/
/***  BEGIN: Process step: Installation  ************************************************/

/**
 * writeHTML()
 * 
 * Writes the content of the $html var.
 * 
 * @return void
 **/
function writeHTML( $html ) {
		global $tsep_lng;
    echo $html;
    flush();
    sleep( 0.001 );
} // writeHTML


/**
 * writeFatalError()
 * 
 * Writes a fatal error message to the install screen and dies.
 * 
 * @return void
 **/
function writeFatalError() {
		global $tsep_lng;
    writeHTML( "\n<script>WriteFatalError( '{$tsep_lng['setup_Fatal_Error']} (".$_SESSION["globalErrorCode"].") ".str_replace("'", "\\'", $_SESSION["globalErrorMessage"])."' );</script>\n\n" );
    die();
} // writeFatalError


/**
 * saveOldTables()
 * 
 * Renames all existing tables in the database
 * by changing the table prefix.
 * 
 * @return boolean TRUE on success
 **/
function saveOldTables() {	
    global $charDone, $barWidth, $tsep_lng;
    
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Saving_old_tables']}' );</script>\n<script>UpdateProgressBar( '<br />' );</script>\n" );
    
    $result = TRUE;
    
    $SQLResult = @mysql_query( "SHOW TABLES LIKE '".$_SESSION["tablePrefixNew"]."%'" );
    
    if ( mysql_num_rows( $SQLResult ) > 0 ) {
        $progressBarStep = mysql_num_rows( $SQLResult );
        $progressBar = 0;
        
        while ( list( $tableName ) = mysql_fetch_row( $SQLResult) ) {
                $progressBar++;
            
            $tableNameTmp = preg_replace( "/".$_SESSION["tablePrefixNew"]."/", $_SESSION["tablePrefixTmp"] , $tableName );
            
            $RenameResult = @mysql_query( "RENAME TABLE $tableName TO $tableNameTmp" );
            
            if ( !$RenameResult or mysql_errno() != 0 ) {
                $_SESSION["globalErrorCode"] = mysql_errno();
                $_SESSION["globalErrorMessage"] = mysql_error();
                $result = FALSE;
                return;
            }

            updateProgressBar( $progressBar, $progressBarStep );
        } // while
    }
    
    return $result;
} // saveOldTables


/**
 * buildConnData()
 * 
 * Assembles the connection data.
 * 
 * @return string Connection data
 **/
function buildConnData() {
		global $tsep_lng;
    $output  = "/* URL to your database server */\n";
    $output .= "\$db_server = '".$_SESSION["dbHost"]."';\n";
    $output .= "/* Your database login name */\n";
    $output .= "\$db_usrname = '".$_SESSION["dbUser"]."';\n";
    $output .= "/* Your database password */\n";
    $output .= "\$db_pwd = '".$_SESSION["dbPwd"]."';\n";
    $output .= "/* TSEP database name */\n";
    $output .= "\$db_name = '".$_SESSION["dbName"]."';\n";
    $output .= "/* TSEP table prefix */\n";
    $output .= "\$db_table_prefix = '".$_SESSION["tablePrefixNew"]."';\n";
	
	return $output;
} // buildConnDate


/**
 * saveDBConnectData()
 * 
 * Saves the database config to the DBConnectionData.php file.
 * 
 * @return boolean TRUE on success
 **/
function saveDBConnectData() {
	global $tsep_lng;
	$output = buildConnData();

    /* Read contents of global.php */
    $contents = file_get_contents( $_SESSION["tsepPath"]."/include/global.php" );
    
    /* Parse content of global.php: add DB config data */
    $placeholder_begin = "/* %BEGIN_SETUP_DATABASE_DATA% */";
    $placeholder_end = "/* %END_SETUP_DATABASE_DATA% */";
    $output = "\n$placeholder_begin\n".$output."$placeholder_end";
    
    $placeholder_beginPos = strpos( $contents, $placeholder_begin );
    $placeholder_endPos = strpos( $contents, $placeholder_end );
    
    $contents = substr( $contents, 0, $placeholder_beginPos-1 ).$output.substr( $contents, $placeholder_endPos+strlen( $placeholder_end ) );
    
    /* Write DB config data to global.php */
    if ( !@$handle = fopen( $_SESSION["tsepPath"]."/include/global.php", "w" ) ) {
        $_SESSION["globalErrorCode"] = "TSEP 001";
        $_SESSION["globalErrorMessage"] = $tsep_lng['setup_Can_not_open']." /include/global.php";
        return FALSE;
    }
    if ( @fwrite( $handle, $contents ) === FALSE ) {
        @fclose( $handle );
        $_SESSION["globalErrorCode"] = "TSEP 002";
        $_SESSION["globalErrorMessage"] = $tsep_lng['setup_Can_not_write_to']." /include/global.php";
        return FALSE;
    }
    @fclose( $handle );
    
    return TRUE;
} // saveDBConnectData


/**
 * executeSQL()
 * 
 * Executes the specified SQL file.
 * 
 * @return boolean TRUE on success
 **/
function executeSQL( $sqlFile, $tablePrefix ) {
    global $charDone, $barWidth, $tsep_lng;
    
    $delimiter = ";";
    
    $sql_query = @fread( @fopen( $sqlFile, "r" ), @filesize( $sqlFile ));
                    
    $sql_query = preg_replace( "/%tablePrefix%/", $tablePrefix , $sql_query );
    $sql_query = remove_remarks( $sql_query );
    $sql_query = split_sql_file( $sql_query, $delimiter );    
    
    $result = TRUE;
    writeHTML( "<script>UpdateProgressBar( '<br />' );</script>\n" );
    $progressBarStep = count( $sql_query );
    $progressBar = 0;
        
    for ($i=0; $i<count( $sql_query ); $i++) {
        $progressBar++;
        if ( $sql_query[$i] <> "") {
            $SQLresult = @mysql_query( $sql_query[$i] );
            if ( !$SQLresult or mysql_errno() != 0 ) {
                $_SESSION["globalErrorCode"] = mysql_errno();
                $_SESSION["globalErrorMessage"] = mysql_error() . ": " . preg_replace("/[\n\r]/", " ", $sql_query[$i] );
                return false;
            }
            updateProgressBar( $progressBar, $progressBarStep );
        }
    } // for
    
    return $result;
} // executeSQL


/**
 * copyIndexerRecords()
 * 
 * Because the profiles (stringtag = "indexer") need to be
 * synchronized they are excluded from the initial copying
 * process and stored in an array. In an additional step
 * the profile related records are copied and synchonized.
 * 
 * @return boolean TRUE on success
 **/
function copyIndexerRecords( &$progressBarStep, &$progressBar, &$rowCount ) {
    global $charDone, $barWidth, $tsep_lng;
    
    // Reference or default profile
    $DefProfile = array();
    // Reference profile numtag
    $DefProf = 1;
    // Holds all indexer records for synchronization
    $Profiles = array();
    // Hold all active profile "ids" (numtags)
    $ProfIDs = array();
    
    $result = TRUE;
    
    /*
     * Get the numtags from all indexer records
     * to see which profiles are in use.
     */
    $SQLResult = @mysql_query( "SELECT DISTINCT numtag FROM ".$_SESSION["tablePrefixTmp"]."internal WHERE stringtag='indexer'" );
    
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
        
    } else {
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
       		_TsepTrace( "Getting profile IDs" );
            while ( list( $numtag ) = mysql_fetch_row( $SQLResult ) ) {
                $ProfIDs[] = $numtag;
            }
        }
    }
    
    
    /**
     * Get the records from the reference profile.
     * This is the default profile (numtag=1) from the new table.
     **/
    $SQLResult = @mysql_query( "SELECT * FROM ".$_SESSION["tablePrefixNew"]."internal WHERE stringtag='indexer' AND numtag=$DefProf ORDER BY sortordervalue" );
    
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
        
    } else {
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
       		_TsepTrace( "Building reference profile" );
			while ( list( $id, $description, $stringvalue, $numericvalue, $sortordervalue, $valuetype, $fieldtype, $stringtag, $numtag ) = mysql_fetch_row( $SQLResult )) {
                $DefProfile[$description]["desc"] = addslashes( $description );
                $DefProfile[$description]["sv"] = addslashes( $stringvalue );
                $DefProfile[$description]["nv"] = $numericvalue;
                $DefProfile[$description]["sov"] = $sortordervalue;
                $DefProfile[$description]["vt"] = addslashes( $valuetype );
                $DefProfile[$description]["ft"] = addslashes( $fieldtype );
                $DefProfile[$description]["st"] = addslashes( $stringtag );
            }
        }
    }
    
    
    /* Start processing the indexer records */
    $SQLResult = @mysql_query( "SELECT * FROM ".$_SESSION["tablePrefixTmp"]."internal WHERE stringtag='indexer'" );
    
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
        
    } else {
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
            
       		_TsepTrace( "Gathering profile data and building array" );
            /* Build the array with all profiles in the old table */
            while ( list( $id, $description, $stringvalue, $numericvalue, $sortordervalue, $valuetype, $fieldtype, $stringtag, $numtag ) = mysql_fetch_row( $SQLResult ) ) {
                $Profiles[$numtag][$description]["desc"] = addslashes( $description );
                $Profiles[$numtag][$description]["sv"] = addslashes( $stringvalue );
                $Profiles[$numtag][$description]["nv"] = $numericvalue;
                $Profiles[$numtag][$description]["sov"] = $sortordervalue;
                $Profiles[$numtag][$description]["vt"] = addslashes( $valuetype );
                $Profiles[$numtag][$description]["ft"] = addslashes( $fieldtype );
                $Profiles[$numtag][$description]["st"] = addslashes( $stringtag );
                $Profiles[$numtag][$description]["nt"] = $numtag;
            }
            
            
            /* 
             * Check each profile agains the reference profile
             * and add the records that are missing.
             */
       		_TsepTrace( "Checking profiles agains default and adding records" );
            reset( $ProfIDs );
            while ( list( $key, $i ) = each( $ProfIDs )) {
           		_TsepTrace( "- Checking profile #$i" );
                reset( $DefProfile );
                while ( list( $key, $desc ) = each( $DefProfile )) {
                    // If a record does not exist; create it with the default values
                    if ( !isset( $Profiles[$i][$key]["desc"] )) {
                        $Profiles[$i][$key]["desc"] = $DefProfile[$key]["desc"];
                        $Profiles[$i][$key]["sv"] = $DefProfile[$key]["sv"];
                        $Profiles[$i][$key]["nv"] = $DefProfile[$key]["nv"];
                        $Profiles[$i][$key]["sov"] = $DefProfile[$key]["sov"];
                        $Profiles[$i][$key]["vt"] = $DefProfile[$key]["vt"];
                        $Profiles[$i][$key]["ft"] = $DefProfile[$key]["ft"];
                        $Profiles[$i][$key]["st"] = $DefProfile[$key]["st"];
                        $Profiles[$i][$key]["nt"] = $i;
                    }
                } // while
                
                
                /* Write all records in this profile to the database */
                while ( list( $desc, $fields ) = each( $Profiles[$i] ) ) {
	                $progressBar++;
                
	                $fields["nv"] = ( $fields["nv"] == "" ? 0 : $fields["nv"] );
	                $fields["sov"] = ( $fields["sov"] == "" ? 0 : $fields["sov"] );
	                $sql_numtag = ( $fields["nt"] == "" ? "numtag=NULL" : "numtag=".$fields["nt"] );
	                
	                $NewResult = @mysql_query( "SELECT idinternal FROM ".$_SESSION["tablePrefixNew"]."internal WHERE description='".$fields["desc"]."' AND stringtag='".$fields["st"]."'".( $fields["nt"] == "" ? "" : " AND numtag=".$fields["nt"] ) );

	           		_TsepTrace( "Writing profile #$i to the database" );
	                if ( mysql_num_rows( $NewResult ) > 0 ) {
	               		_TsepTrace( "- Updating older record" );
	                    $NewResult = @mysql_query( "UPDATE ".$_SESSION["tablePrefixNew"]."internal SET description='".$fields["desc"]."', stringvalue='".$fields["sv"]."', numericvalue=".$fields["nv"].", sortordervalue=".$fields["sov"].", valuetype='".$fields["vt"]."', fieldtype='".$fields["ft"]."', stringtag='".$fields["st"]."'".( $fields["nt"] == "" ? "" : ", numtag=".$fields["nt"] )." WHERE description='".$fields["desc"]."' AND stringtag='".$fields["st"]."'".( $fields["nt"] == "" ? "" : " AND numtag=".$fields["nt"] ) );
	                } else {
	               		_TsepTrace( "- Writing new record" );
	                    $NewResult = @mysql_query( "INSERT INTO ".$_SESSION["tablePrefixNew"]."internal SET description='".$fields["desc"]."', stringvalue='".$fields["sv"]."', numericvalue=".$fields["nv"].", sortordervalue=".$fields["sov"].", valuetype='".$fields["vt"]."', fieldtype='".$fields["ft"]."', stringtag='".$fields["st"]."'".( $fields["nt"] == "" ? "" : ", numtag=".$fields["nt"] ) );
	                }
	                
	                if ( !$NewResult or mysql_errno() != 0 ) {
	               		_TsepTrace( "ERROR: Writing profile #$i. MySQL error message: ".mysql_error() );
	                    $_SESSION["globalErrorCode"] = mysql_errno();
	                    $_SESSION["globalErrorMessage"] = mysql_error();
	                    $result = FALSE;
	                    exit;
	                }
                } // while

                updateProgressBar( $progressBar, $progressBarStep );
            }
        }
    }
    
    return $result;
} // copyIndexerRecords


/**
 * copySettings()
 * 
 * Copies the settings from the old table to the new table
 * 
 * Because the profiles (stringtag = "indexer") need to be
 * synchronized they are excluded from the initial copying
 * process and stored in an array. In an additional step
 * the profile related records are copied and synchonized.
 * 
 * @return boolean TRUE on success
 **/
function copySettings() {	
    global $charDone, $barWidth, $tsep_lng;
    
    // Reference or default profile
    $DefProfile = array();
    // Reference profile numtag
    $DefProf = 1;
    // Holds all indexer records for synchronization
    $Profiles = array();
    
    $result = TRUE;
    
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Copying_settings']}' );</script>\n<script>UpdateProgressBar( '<br />' );</script>\n" );
    
    /* Get the total records count for the progressbar */
    $SQLResult = @mysql_query( "SELECT count(idinternal) FROM ".$_SESSION["tablePrefixTmp"]."internal" );
    list( $progressBarStep ) = mysql_fetch_row( $SQLResult );
    
    
    $SQLResult = @mysql_query( "SELECT * FROM ".$_SESSION["tablePrefixTmp"]."internal WHERE stringtag<>'indexer'" );
    
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        return;
        
    } else {
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
            $progressBar = 0;
            $rowCount = 0;
            
            while ( list( $id, $description, $stringvalue, $numericvalue, $sortordervalue, $valuetype, $fieldtype, $stringtag, $numtag ) = mysql_fetch_row( $SQLResult ) ) {
                $copyRow = TRUE;
                $progressBar++;
                
                /* Ajust some values */
                switch ( $description ) {
                    case "tsepdatabaseversion":
                            $copyRow = FALSE;
                            break;
    
                    case "Path":
                            $stringvalue = $_SESSION["tsepRoot"];
                            break;
    
                    case "absPath":
                            $stringvalue = $_SESSION["tsepPath"];
                            break;
    
                    case "tmpPath":
                            $stringvalue = $_SESSION["tmpPath"];
                            break;
                } // switch
                
                if ( $copyRow ) {
                    $rowCount++;
                    
                    $description = addslashes( $description );
                    $stringvalue = addslashes( $stringvalue );
                    $valuetype = addslashes( $valuetype );
                    $fieldtype = addslashes( $fieldtype );
                    $stringtag = addslashes( $stringtag );
                    
                    $numericvalue = ( $numericvalue == "" ? 0 : $numericvalue );
                    $sortordervalue = ( $sortordervalue == "" ? 0 : $sortordervalue );
                    $sql_numtag = ( $numtag == "" ? "numtag=NULL" : "numtag=$numtag" );
                    
                    $NewResult = @mysql_query( "SELECT idinternal FROM ".$_SESSION["tablePrefixNew"]."internal WHERE description='$description' AND stringtag='$stringtag'".( $numtag == "" ? "" : " AND numtag=$numtag" ) );
                    if ( mysql_num_rows( $NewResult ) > 0 ) {
                        $NewResult = @mysql_query( "UPDATE ".$_SESSION["tablePrefixNew"]."internal SET description='$description', stringvalue='$stringvalue', numericvalue=$numericvalue, sortordervalue=$sortordervalue, valuetype='$valuetype', fieldtype='$fieldtype', stringtag='$stringtag'".( $numtag == "" ? "" : ", numtag=$numtag" )." WHERE description='$description' AND stringtag='$stringtag'".( $numtag == "" ? "" : " AND numtag=$numtag" ) );
                    } else {
                        $NewResult = @mysql_query( "INSERT INTO ".$_SESSION["tablePrefixNew"]."internal SET description='$description', stringvalue='$stringvalue', numericvalue=$numericvalue, sortordervalue=$sortordervalue, valuetype='$valuetype', fieldtype='$fieldtype', stringtag='$stringtag'".( $numtag == "" ? "" : ", numtag=$numtag" ) );
                    }
                    
                    if ( !$NewResult or mysql_errno() != 0 ) {
                        $_SESSION["globalErrorCode"] = mysql_errno();
                        $_SESSION["globalErrorMessage"] = mysql_error();
                        $result = FALSE;
                        exit;
                    }
                }
                
                updateProgressBar( $progressBar, $progressBarStep );
            } // while
            
            if ( $_SESSION["profiles"] == "yes" ) {
        		_TsepTrace( "Copying indexer records" );
                $result = copyIndexerRecords( $progressBarStep, $progressBar, $rowCount );
            }
            
        }
        
        /* Add record to stats array with modified rows */
        $_SESSION["stats_settings"] = $rowCount;
    }
    
    return $result;
} // copySettings


/**
 * enterBasicSettings()
 * 
 * These settings should be entered into the database no matter what the admin chooses.
 * 
 * @return boolean TRUE on success
 **/
function enterBasicSettings() {
		global $tsep_lng;
    $result = TRUE;
    
    $NewResult = @mysql_query( "UPDATE ".$_SESSION["tablePrefixNew"]."internal SET stringvalue='".$_SESSION["tsepRoot"]."' WHERE description='Path' AND stringtag='config'" );
    if ( !$NewResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
    }
    
    $NewResult = @mysql_query( "UPDATE ".$_SESSION["tablePrefixNew"]."internal SET stringvalue='".$_SESSION["tsepPath"]."' WHERE description='absPath' AND stringtag='config'" );
    if ( !$NewResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
    }
    
    $NewResult = @mysql_query( "UPDATE ".$_SESSION["tablePrefixNew"]."internal SET stringvalue='".$_SESSION["tmpPath"]."' WHERE description='tmpPath' AND stringtag='config'" );
    if ( !$NewResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
    }
    
    $NewResult = @mysql_query( "UPDATE ".$_SESSION["tablePrefixNew"]."internal SET stringvalue='".$_SESSION["lang"]."' WHERE description='Language' AND stringtag='config'" );
    if ( !$NewResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
    }
    
    return $result;
} // enterBasicSettings


/**
 * copyIndexes()
 * 
 * Copies the indexes from the old table to the new table.
 * Tables worked on are _search and _iprofile_search.
 * 
 * @return boolean TRUE on success
 **/
function copyIndexes() {
	  global $charDone, $barWidth, $tsep_lng;
    
    $result = TRUE;
    
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Copying_indexes']}' );</script>\n<script>UpdateProgressBar( '<br />' );</script>\n" );
    
    $SQLResult = @mysql_query( "SELECT * FROM ".$_SESSION["tablePrefixTmp"]."search" );
    
    $rowCount = 0;
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
    } else {
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
            $progressBarStep = mysql_num_rows( $SQLResult );
            $progressBar = 0;
            
            while ( list( $id, $page_number, $protect_indexentry, $page_title, $page_url, $page_file_size, $indexed_words, $indexed_metawords, $last_indexed, $last_edited, $additional_info ) = mysql_fetch_row( $SQLResult ) ) {
                $rowCount++;
                $progressBar++;
                
                $page_number = ( $page_number == "" ? 0 : $page_number );
                $page_title = addslashes( $page_title );
                $page_url = addslashes( $page_url );
                $page_file_size = addslashes( $page_file_size );
                $indexed_words = addslashes( $indexed_words );
                $indexed_metawords = addslashes( $indexed_metawords );
                $last_indexed = addslashes( $last_indexed );
                $last_edited = addslashes( $last_edited );
                $additional_info = addslashes( $additional_info );
    
                $NewResult = @mysql_query( "INSERT INTO ".$_SESSION["tablePrefixNew"]."search SET id=$id, page_number=$page_number, protect_indexentry='$protect_indexentry', page_title='$page_title', page_url='$page_url', page_file_size='$page_file_size', indexed_words='$indexed_words', indexed_metawords='$indexed_metawords', last_indexed='$last_indexed', last_edited='$last_edited', additional_info='$additional_info'" );
    
                if ( !$NewResult or mysql_errno() != 0 ) {
                    $_SESSION["globalErrorCode"] = mysql_errno();
                    $_SESSION["globalErrorMessage"] = mysql_error();
                    $result = FALSE;
                    exit;
                }
    
                updateProgressBar( $progressBar, $progressBarStep );
            } // while
        }
        
        /* Add record to stats array with modified rows */
        $_SESSION["stats_indexes"] = $rowCount;
    }
    
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Copying_profile2index_links']}' );</script>\n<script>UpdateProgressBar( '<br />' );</script>\n" );
    
    $SQLResult = @mysql_query( "SELECT * FROM ".$_SESSION["tablePrefixTmp"]."iprofile_search" );
    
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
    } else {
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
            $progressBarStep = mysql_num_rows( $SQLResult );
            $progressBar = 0;
            
            while ( list( $id, $idprofile, $idsearch ) = mysql_fetch_row( $SQLResult ) ) {
                    $progressBar++;
                
                $NewResult = mysql_query( "INSERT INTO ".$_SESSION["tablePrefixNew"]."iprofile_search SET idiprofilesearch=$id, idiprofile=$idprofile, idsearch=$idsearch" ) or die("Search 2: ".mysql_error());
                
                if ( !$NewResult or mysql_errno() != 0 ) {
                    $_SESSION["globalErrorCode"] = mysql_errno();
                    $_SESSION["globalErrorMessage"] = mysql_error();
                    $result = FALSE;
                    exit;
                }
    
                updateProgressBar( $progressBar, $progressBarStep );
            } // while
        }
    }
    
    return $result;
} // copyIndexes


/**
 * copyProfiles()
 * 
 * Copies the profiles from the old table to the new table
 * 
 * @return boolean TRUE on success
 **/
function copyProfiles() {
    global $charDone, $barWidth, $tsep_lng;
    
    $result = TRUE;
    
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Copying_profiles']}' );</script>\n<script>UpdateProgressBar( '<br />' );</script>\n" );
    
    $SQLResult = @mysql_query( "SELECT * FROM ".$_SESSION["tablePrefixTmp"]."iprofile" );
    
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
        
    } else {
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
            $progressBarStep = mysql_num_rows( $SQLResult );
            $progressBar = 0;
            $rowCount = 0;
            
            while ( list( $id, $profilename ) = mysql_fetch_row( $SQLResult ) ) {
                $copyRow = TRUE;
                $progressBar++;
                
                /* Ajust some values */
                switch ( $profilename ) {
                    case "demo":
                            $copyRow = FALSE;
                            break;
                } // switch
                
                
                if ( $copyRow ) {
                    $rowCount++;
                    
                    $profilename = addslashes( $profilename );
                    
                    $NewResult = @mysql_query( "INSERT INTO ".$_SESSION["tablePrefixNew"]."iprofile SET idiprofile=$id, profilename='$profilename'" );
                    
                    if ( !$NewResult or mysql_errno() != 0 ) {
                        $_SESSION["globalErrorCode"] = mysql_errno();
                        $_SESSION["globalErrorMessage"] = mysql_error();
                        $result = FALSE;
                        exit;
                    }
                }
    
                updateProgressBar( $progressBar, $progressBarStep );
            } // while
        }
        
        /* Add record to stats array with modified rows */
        if ( $rowCount == 0 ) {
            $_SESSION["stats_profiles"] = 1;
        } else {
            $_SESSION["stats_profiles"] = $rowCount;
        }
    }
    
    return $result;
} // copyProfiles


/**
 * copyLogs()
 * 
 * Copies the log records from the old table to the new table.
 * Tables worked on are _log and _loghits.
 * 
 * @return boolean TRUE on success
 **/
function copyLogs() {
    global $charDone, $barWidth, $tsep_lng;
    
    $result = TRUE;
    
    /* Table _log */
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Copying_log_entries']}' );</script>\n<script>UpdateProgressBar( '<br />' );</script>\n" );
    
    $SQLResult = @mysql_query( "SELECT * FROM ".$_SESSION["tablePrefixTmp"]."log" );
    
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
        
    } else {
        $rowCount = 0;
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
            $progressBarStep = mysql_num_rows( $SQLResult );
            $progressBar = 0;
            
            while ( list( $id, $typeoflog, $logstring, $timeofentry, $ip, $ipresolved, $stopwords ) = mysql_fetch_row( $SQLResult ) ) {
                $rowCount++;
                $progressBar++;
                
                $logstring = addslashes( $logstring );
                $stopwords = addslashes( $stopwords );
    
                    $NewResult = @mysql_query( "INSERT INTO ".$_SESSION["tablePrefixNew"]."log SET idlog=$id, typeoflog=$typeoflog, logstring='$logstring', timeofentry='$timeofentry', ip='$ip', ipresolved='$ipresolved', stopwords='$stopwords'" );
                
                if ( !$NewResult or mysql_errno() != 0 ) {
                    $_SESSION["globalErrorCode"] = mysql_errno();
                    $_SESSION["globalErrorMessage"] = mysql_error();
                    $result = FALSE;
                    exit;
                }
    
                updateProgressBar( $progressBar, $progressBarStep );
            } // while
        }
        
        /* Add record to stats array with modified rows */
        $_SESSION["stats_logs"] = $rowCount;
    }
    
    /* Table _loghits */
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Copying_log_hits']}' );</script>\n<script>UpdateProgressBar( '<br />' );</script>\n" );
    
    $SQLResult = @mysql_query( "SELECT * FROM ".$_SESSION["tablePrefixTmp"]."loghits" );
    
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
        
    } else {
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
            $progressBarStep = mysql_num_rows( $SQLResult );
            $progressBar = 0;
            
            while ( list( $id, $idlog, $nr_hits, $returned_pages ) = mysql_fetch_row( $SQLResult ) ) {
                $progressBar++;
                
                $NewResult = @mysql_query( "INSERT INTO ".$_SESSION["tablePrefixNew"]."loghits SET idloghits=$id, idlog=$idlog, nr_hits=$nr_hits, returned_pages=$returned_pages" );
                
                if ( !$NewResult or mysql_errno() != 0 ) {
                    $_SESSION["globalErrorCode"] = mysql_errno();
                    $_SESSION["globalErrorMessage"] = mysql_error();
                    $result = FALSE;
                    exit;
                }
    
                updateProgressBar( $progressBar, $progressBarStep );
            } // while
        }
    }
    
    return $result;
} // copyLogs


/**
 * copyStopWords()
 * 
 * Copies the stopwords from the old table to the new table
 * 
 * @return boolean TRUE on success
 **/
function copyStopWords() {
    global $charDone, $barWidth, $tsep_lng;
    
    $result = TRUE;
    
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Copying_stopwords']}' );</script>\n<script>UpdateProgressBar( '<br />' );</script>\n" );
    
    $SQLResult = @mysql_query( "SELECT * FROM ".$_SESSION["tablePrefixTmp"]."stopwords" );
    
    $rowCount = 0;
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
        
    } else {
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
            $progressBarStep = mysql_num_rows( $SQLResult );
            $progressBar = 0;
            
            while ( list( $id, $stopword ) = mysql_fetch_row( $SQLResult ) ) {
                $rowCount++;
                $progressBar++;
                
                $stopword = addslashes( $stopword );
                
                $NewResult = @mysql_query( "INSERT INTO ".$_SESSION["tablePrefixNew"]."stopwords SET idstopwords=$id, stopword='$stopword'" );
                
                if ( !$NewResult or mysql_errno() != 0 ) {
                    $_SESSION["globalErrorCode"] = mysql_errno();
                    $_SESSION["globalErrorMessage"] = mysql_error();
                    $result = FALSE;
                    exit;
                }
    
                updateProgressBar( $progressBar, $progressBarStep );
            } // while
        }
        
        /* Add record to stats array with modified rows */
        $_SESSION["stats_stopwords"] = $rowCount;
    }
    
    return $result;
} // copyStopWords


/**
 * copyRankSymbols()
 * 
 * Copies the rank symbols from the old table to the new table
 * 
 * @return boolean TRUE on success
 **/
function copyRankSymbols() {
		global $charDone, $barWidth, $tsep_lng;
    
    $result = TRUE;
    
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Copying_rank_symbols']}' );</script>\n<script>UpdateProgressBar( '<br />' );</script>\n" );
    
    $SQLResult = @mysql_query( "SELECT * FROM ".$_SESSION["tablePrefixTmp"]."ranksymbols" );
    
    if ( !$SQLResult or mysql_errno() != 0 ) {
        $_SESSION["globalErrorCode"] = mysql_errno();
        $_SESSION["globalErrorMessage"] = mysql_error();
        $result = FALSE;
        exit;
        
    } else {
        $rowCount = 0;
        if ( mysql_num_rows( $SQLResult ) > 0 ) {
            $progressBarStep = mysql_num_rows( $SQLResult );
            $progressBar = 0;
            while ( list( $id, $display, $number_of_images, $picture, $filetype, $imagename, $width_image, $height_image, $alt_tag, $valuepercent, $image_show, $start_display ) = mysql_fetch_row( $SQLResult ) ) {
                $rowCount++;
                $progressBar++;
                
                $display = addslashes( $display );
                $filetype = addslashes( $filetype );
                $imagename = addslashes( $imagename );
                $alt_tag = addslashes( $alt_tag );
                $image_show = addslashes( $image_show );
                
                $NewResult = @mysql_query( "INSERT INTO ".$_SESSION["tablePrefixNew"]."ranksymbols SET id_ranksymbols=$id, display='$display', number_of_image=$number_of_images, filetype='$filetype', name_image='$imagename', width_image=$width_image, height_image=$height_image, alt_tag='$alt_tag', valuepercent=$valuepercent, image_show='$image_show', start_display=$start_display" );
                
                if ( !$NewResult or mysql_errno() != 0 ) {
                    $_SESSION["globalErrorCode"] = mysql_errno();
                    $_SESSION["globalErrorMessage"] = mysql_error();
                    $result = FALSE;
                    exit;
                }
    
                updateProgressBar( $progressBar, $progressBarStep );
            } // while
        }
        
        /* Add record to stats array with modified rows */
        $_SESSION["stats_ranksymbols"] = $rowCount;
    }
    
    return $result;
} // copyRankSymbols


/**
 * executeInstall()
 * 
 * Installes TSEP based on the settings made by the admin
 * 
 * @return boolean TRUE on success
 **/
function executeInstall() {
    global $barWidth, $charDone, $tsep_lng;
    
    $html  = writeScreenBegin( "install" );
    
    $html .= "<script type=\"text/javascript\">\n";
    $html .= "  function UpdateAction( newAction ) {\n";    
    $html .= "    document.getElementById( 'installAction' ).replaceChild( document.createTextNode( newAction ), document.getElementById( 'installAction' ).firstChild );\n";
    $html .= "  }\n";
    $html .= "  function UpdateProgressBar( bar ) {\n";
    $html .= "    document.getElementById( 'progressBar' ).replaceChild( document.createTextNode( bar ), document.getElementById( 'progressBar' ).firstChild );\n";
    $html .= "  }\n";
    $html .= "  function InstallFinished() {\n";
    $html .= "    document.getElementById( 'nextImage' ).src = 'images/next_f2.png';\n";
    $html .= "    document.getElementById( 'nextImageLink' ).href = '".$_SERVER["PHP_SELF"]."?op=sum';\n";
    $html .= "    document.getElementById( 'installFinished_1' ).replaceChild( document.createTextNode( '{$tsep_lng['setup_Congratulations']}' ), document.getElementById( 'installFinished_1' ).firstChild );\n";
    $html .= "    document.getElementById( 'installFinished_2' ).replaceChild( document.createTextNode( '{$tsep_lng['setup_Continue2Summary']}' ), document.getElementById( 'installFinished_2' ).firstChild );\n";
    $html .= "  }\n";
    $html .= "  function WriteFatalError( errormessage ) {\n";
    $html .= "    document.getElementById( 'fatalError' ).replaceChild( document.createTextNode( errormessage ), document.getElementById( 'fatalError' ).firstChild );\n";
    $html .= "  }\n";
    $html .= "</script>\n";

    $html .= $tsep_lng['setup_PerformingInstallOfVer']."&nbsp;".$_SESSION["tsepVersion"].". ".$tsep_lng['setup_DoNotInterrupt'];
    $html .= "          <br />\n";
    $html .= "          <table style=\"border: 0px none;\" cellpadding=\"4\" cellspacing=\"0\">\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 1%;\"><b>{$tsep_lng['setup_Progress']}</b></td>\n";
    $html .= "              <td style=\"text-align: left; width: 100%\"><span id=\"installAction\"><br /></span></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td colspan=\"2\" style=\"padding-left: 1em;\">\n";
    $html .= "                <table style=\"border: 1px dotted rgb(0, 192, 0); width: 19.3em;\">\n";
    $html .= "                <tr>\n";
    $html .= "                  <td style\"width: 100%;\"><span id=\"progressBar\" style=\"background-color: rgb(51, 102, 255); color: rgb(51, 102, 255);\"><br /></span></td>\n";
    $html .= "                </tr>\n";
    $html .= "              </table>\n";
    $html .= "              </td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td colspan=\"2\"><br /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td id=\"rowInstallFinished\" colspan=\"2\" style=\"text-align: left; width: 100%; padding: 1em;\"><span id=\"installFinished_1\" style=\"font-weight: bold;\"><br /></span><br /><span id=\"installFinished_2\"><br /></span></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td colspan=\"2\" style=\"text-align: left; width: 100%; padding: 1em;\"><span id=\"fatalError\" style=\"font-weight: bold; color: red;\"><br /></span></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "          </table>\n";

    // <---  FOOTER  ------------------------------------------------------------------->
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "    <table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 0px none; width: 100%;\">\n";
        $html .= "      <tr>\n";
        $html .= "        <td>&nbsp; </td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "        <td style=\"width: 100%; text-align: right; vertical-align: middle;\">\n";
        $html .= "          <img src=\"images/back.png\" title=\"{$tsep_lng['setup_ToPreviousStep']}\" />&nbsp;{$tsep_lng['setup_Previous']}&nbsp;&nbsp;&nbsp;\n";
        $html .= "          <a id=\"nextImageLink\" href=\"#\"><img id=\"nextImage\" src=\"images/next.png\" title=\"{$tsep_lng['setup_ToNextStep']}\" />&nbsp;{$tsep_lng['setup_Next']}</a>&nbsp;&nbsp;&nbsp;\n";
        $html .= "          <img src=\"images/cancel.png\" title=\"{$tsep_lng['setup_IWantToQuitInstalling']}\" />&nbsp;{$tsep_lng['setup_Cancel']}\n";
        $html .= "        </td>\n";
        $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "  </div>\n";
    
    writeHTML( $html );
    
    
    /**
     * Do the actual work
     **/
    @mysql_connect( $_SESSION["dbHost"], $_SESSION["dbUser"], $_SESSION["dbPwd"] );
    @mysql_select_db( $_SESSION["dbName"] );
    
    /* Save database config */
    if ( !isset( $_SESSION["globalIsPatched"] ) or $_SESSION["globalIsPatched"] != "yes" ) {
        if ( !saveDBConnectData() ) {
            writeFatalError();
        }
    }
    
    /* Check if we're updating */
    if ( $_SESSION["update"] == "yes" ) {
        /* Update = yes: save old tables */
        
        /* Generate a unique prefix to save the old tables to */
        $_SESSION["tablePrefixTmp"] = "tsep_".gmdate("YmdHis")."_";
        if ( !saveOldTables() ) {
            writeFatalError();
        }
    } else {
        /* Update = no: drop old tables */
        writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Deleting_old_tables']}' );</script>\n" );
        if ( !executeSQL( "drop.sql", $_SESSION["tablePrefixNew"] ) ) {
            writeFatalError();
        }
    }
    
    /* Call this routine to create a rollback script */
    /* Make sure you don't delete the old tables or */
    /* you won't be able to roll back!!! */
    //setupRollBack();
    
    /* Create new tables */
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Creating_new_tables']}' );</script>\n" );
    if ( !executeSQL( "create.sql", $_SESSION["tablePrefixNew"] ) ) {
        writeFatalError();
    }
    
    /* Create new tables */
    writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Populating_new_tables']}' );</script>\n" );
    if ( !executeSQL( "insert.sql", $_SESSION["tablePrefixNew"] ) ) {
        writeFatalError();
    }
    
    /* Copy the information from the old to the new tables */
    if ( $_SESSION["update"] == "yes" ) {
        
        /* internal */
        if ( $_SESSION["settings"] == "yes" ) {
            if ( !copySettings() ) {
                writeFatalError();
            }
        }
        
        /* search */
        if ( $_SESSION["indexes"] == "yes" ) {
            if ( !copyIndexes() ) {
                writeFatalError();
            }
        }
        
        /* profiles */
        if ( $_SESSION["profiles"] == "yes" ) {
            if ( !copyProfiles() ) {
                writeFatalError();
            }
        }
        
        /* log and loghits */
        if ( $_SESSION["logs"] == "yes" ) {
            if ( !copyLogs() ) {
                writeFatalError();
            }
        }
        
        /* Stopwords */
        if ( $_SESSION["stopwords"] == "yes" ) {
            if ( !copyStopWords() ) {
                writeFatalError();
            }
        }
        
        /* Rank symbols */
        if ( $_SESSION["ranksymbols"] == "yes" ) {
            if ( !copyRankSymbols() ) {
                writeFatalError();
            }
        }
        
    } // if update == yes

    if ( !enterBasicSettings() ) {
        writeFatalError();
    }
    
    /* Drop old tables if needed */
    if ( $_SESSION["update"] == "yes" ) {
        writeHTML( "<script>UpdateAction( '{$tsep_lng['setup_Deleting_old_tables']}' );</script>\n" );
        if ( !executeSQL( "drop.sql", $_SESSION["tablePrefixTmp"] ) ) {
            writeFatalError();
        }
    } // if update = yes
    
    @mysql_close();
    
    writeHTML( "<script>InstallFinished();</script>\n" );
    
    $html  = "</body>\n";
    $html .= "</html>\n";

    writeHTML( $html );

} // executeInstall

/***  END: Process step: Installation  **************************************************/
/***  BEGIN: Process step: Summary  *****************************************************/

function showSummary() {
    global $hintTitle, $tsep_lng;
    
    $html  = writeScreenBegin( "sum" );
    
    $html .= $tsep_lng['setup_FinishedInstalling']." ".$_SESSION["tsepVersion"].".<br />\n";
    $html .= "          <br />\n";
    $html .= "          <table style=\"border: 0px none;\" cellpadding=\"4\" cellspacing=\"0\">\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td colspan=\"2\"><b>{$tsep_lng['setup_Summary_of_installation']}</b></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 1%;\"><b>{$tsep_lng['setup_Settings']}</b></td>\n";
    $html .= "              <td style=\"text-align: left; width: 100%\">".( $_SESSION["settings"] == "yes" ? ( isset( $_SESSION["stats_settings"] ) ? $_SESSION["stats_settings"]."&nbsp;".$tsep_lng['setup_records_copied'] : $tsep_lng['setup_records_copied_Zero'] ) : $tsep_lng['setup_Not_selected_for_update'] )."</td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 1%;\"><b>{$tsep_lng['setup_Profiles']}</b></td>\n";
  $html .= "              <td style=\"text-align: left; width: 100%\">".( $_SESSION["profiles"] == "yes" ? ( isset( $_SESSION["stats_profiles"] ) ? $_SESSION["stats_profiles"]."&nbsp;".$tsep_lng['setup_records_copied'] : $tsep_lng['setup_records_copied_Zero'] ) : $tsep_lng['setup_Not_selected_for_update'] )."</td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 1%;\"><b>{$tsep_lng['setup_Indexes']}</b></td>\n";
  $html .= "              <td style=\"text-align: left; width: 100%\">".( $_SESSION["indexes"] == "yes" ? ( isset( $_SESSION["stats_indexes"] ) ? $_SESSION["stats_indexes"]."&nbsp;".$tsep_lng['setup_records_copied'] : $tsep_lng['setup_records_copied_Zero'] ) : $tsep_lng['setup_Not_selected_for_update'] )."</td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 1%;\"><b>{$tsep_lng['setup_Stopwords']}</b></td>\n";
  $html .= "              <td style=\"text-align: left; width: 100%\">".( $_SESSION["stopwords"] == "yes" ? ( isset( $_SESSION["stats_stopwords"] ) ? $_SESSION["stats_stopwords"]."&nbsp;".$tsep_lng['setup_records_copied'] : $tsep_lng['setup_records_copied_Zero'] ) : $tsep_lng['setup_Not_selected_for_update'] )."</td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 1%;\"><b>{$tsep_lng['setup_Logs']}</b></td>\n";
  $html .= "              <td style=\"text-align: left; width: 100%\">".( $_SESSION["logs"] == "yes" ? ( isset( $_SESSION["stats_logs"] ) ? $_SESSION["stats_logs"]."&nbsp;".$tsep_lng['setup_records_copied'] : $tsep_lng['setup_records_copied_Zero'] ) : $tsep_lng['setup_Not_selected_for_update'] )."</td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 1%;\"><b>{$tsep_lng['setup_Ranksymbols']}</b></td>\n";
    $html .= "              <td style=\"text-align: left; width: 100%\">".( $_SESSION["ranksymbols"] == "yes" ? ( isset( $_SESSION["stats_ranksymbols"] ) ? $_SESSION["stats_ranksymbols"]."&nbsp;".$tsep_lng['setup_records_copied'] : $tsep_lng['setup_records_copied_Zero'] ) : $tsep_lng['setup_Not_selected_for_update'] )."</td>\n";
    $html .= "            </tr>\n";
    
    $html .= "          </table>\n";
    
    $html .= "          <br />\n";
    $html .= "          <span style=\"font-weight: bold; color: red;\">{$tsep_lng['setup_Important']}</span><br />\n";
    $html .= "          <div style=\"padding-left: 0.5em; padding-right: 0.5em;\">{$tsep_lng['setup_Important_Delete']}</div><br />\n";
        
    // <---  FOOTER  ------------------------------------------------------------------->
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "    <table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 0px none; width: 100%;\">\n";
    $html .= "      <tr>\n";
    $html .= "        <td>&nbsp; </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr>\n";
    $html .= "        <td style=\"width: 100%; text-align: right; vertical-align: middle;\">\n";
    $html .= "          <img src=\"images/back.png\" title=\"{$tsep_lng['setup_ToPreviousStep']}\" />&nbsp;{$tsep_lng['setup_Previous']}&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"".$_SERVER["PHP_SELF"]."?op=feedback\"><img src=\"images/next_f2.png\" title=\"{$tsep_lng['setup_ToNextStep']}\" />&nbsp;{$tsep_lng['setup_Next']}</a>&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <img src=\"images/cancel.png\" title=\"{$tsep_lng['setup_IWantToQuitInstalling']}\" />&nbsp;{$tsep_lng['setup_Cancel']}\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";
    $html .= "    </form>\n";

    $html .= "  </div>\n";
    $html .= "  <script>processUpdateClick();</script>\n";
    $html .= "  <script>document.sumForm.email.focus();</script>\n";
    
    $html .= "</body>\n";
    $html .= "</html>\n";

    return $html;
} // showSummary

/***  END: Process step: Summary  *******************************************************/
/***  BEGIN: Process step: Feedback  ****************************************************/

function showFeedback() {
    global $hintTitle, $tsep_lng;
    
    $installOK = "yes";
    $sendNewTSEPVersion = "yes";
    $sendOldTSEPVersion = "yes";
    $domain = "yes";
    $email = "";
    $comment = "";
    $referer = ( $_SERVER["HTTPS"] == "on" ? "https://" : "http://" ).$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
    
    
    $html  = writeScreenBegin( "feedback" );
    
    $html .= "\n          <script type=\"text/javascript\">\n";
    $html .= "            function processUpdateClick() {\n";
    $html .= "              var URLString = '';\n";
    $html .= "              if ( document.sumForm.installOK[0].checked ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'installOK=yes';\n";
    $html .= "              }\n";
    $html .= "              if ( document.sumForm.sendNewTSEPVersion[0].checked ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'ver=".urlencode( $_SESSION["tsepVersion"] )."';\n";
    $html .= "              }\n";
    $html .= "              if ( document.sumForm.sendOldTSEPVersion[0].checked ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'verprev=".urlencode( $_SESSION["tsepPrevVersion"] )."';\n";
    $html .= "              }\n";
    $html .= "              if ( document.sumForm.domain[0].checked ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'logreferer=yes';\n";
    $html .= "              }\n";
    $html .= "              if ( document.sumForm.email.value != '' ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'email=' + encodeURIComponent( document.sumForm.email.value );\n";
    $html .= "              }\n";
    $html .= "              if ( document.sumForm.comment.value != '' ) {\n";
    $html .= "                URLString = URLString + ( URLString == '' ? '?' : ' &' ) + 'comment=' + encodeURIComponent( document.sumForm.comment.value );\n";
    $html .= "              }\n";
    $html .= "              URLString = ( URLString == '' ? '{$tsep_lng['setup_NoURL2Preview']}' : 'http://www.tsep.info' + URLString + ' &referer=".$referer."' );\n";
    $html .= "              document.getElementById( 'theURL' ).replaceChild( document.createTextNode( URLString ), document.getElementById( 'theURL' ).firstChild );\n";
    $html .= "            }\n";
    $html .= "          </script>\n\n";
    
    $html .= "          <form name=\"sumForm\" action=\"".$_SERVER["PHP_SELF"]."\" method=\"get\">\n";
    $html .= "            <input type=\"hidden\" name=\"op\" value=\"finish\" />\n";
    $html .= "            <input type=\"hidden\" name=\"fromstep\" value=\"sum\" />\n";
    $html .= "          <table style=\"border: 0px none;\" cellpadding=\"4\" cellspacing=\"0\">\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td colspan=\"3\"><b>".$tsep_lng['setup_BeforeFinish']."</b></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left;\" colspan=\"2\">\n";
    $html .= $tsep_lng['setup_finishText1']."<br />\n";

    $BeforeFinish_Help = $tsep_lng['setup_finishText2'];
    $BeforeFinish_Help .= "<br /><br />".$tsep_lng['setup_finishText3'];
    $BeforeFinish_Help .= "<br /><br />".$tsep_lng['setup_finishText4'];

    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: top;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('$BeforeFinish_Help', CAPTION, '$hintTitle', WIDTH, 250, LEFT, BELOW);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 75%;\">".$tsep_lng['setup_Let_TSEP_Team_know']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <input type=\"radio\" name=\"installOK\" value=\"yes\"".( $installOK == "yes" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"installOK_yes\" /><label for=\"installOK_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"installOK\" value=\"no\"".( $installOK == "no" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"installOK_no\" /><label for=\"installOK_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('{$tsep_lng['setup_Let_TSEP_Team_know_Help']}.', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 75%;\">".$tsep_lng['setup_NewVersion']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <input type=\"radio\" name=\"sendNewTSEPVersion\" value=\"yes\"".( $sendNewTSEPVersion == "yes" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"sendNewTSEPVersion_yes\" /><label for=\"sendNewTSEPVersion_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"sendNewTSEPVersion\" value=\"no\"".( $sendNewTSEPVersion == "no" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"sendNewTSEPVersion_no\" /><label for=\"sendNewTSEPVersion_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('".$tsep_lng['setup_NewVersion_Help']."', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 75%;\">".$tsep_lng['setup_OldVersion']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <input type=\"radio\" name=\"sendOldTSEPVersion\" value=\"yes\"".( $sendOldTSEPVersion == "yes" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"sendOldTSEPVersion_yes\" /><label for=\"sendOldTSEPVersion_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"sendOldTSEPVersion\" value=\"no\"".( $sendOldTSEPVersion == "no" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"sendOldTSEPVersion_no\" /><label for=\"sendOldTSEPVersion_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('".$tsep_lng['setup_OldVersion_Help']."', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 75%;\">".$tsep_lng['setup_Referer']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <input type=\"radio\" name=\"domain\" value=\"yes\"".( $domain == "yes" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"domain_yes\" /><label for=\"domain_yes\">{$tsep_lng['setup_Yes']}</label>&nbsp;\n";
    $html .= "                <input type=\"radio\" name=\"domain\" value=\"no\"".( $domain == "no" ? " checked" : "" )." onclick=\"processUpdateClick()\" id=\"domain_no\" /><label for=\"domain_no\">{$tsep_lng['setup_No']}</label>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('".$tsep_lng['setup_Referer_Help']."', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"text-align: left; width: 75%;\">".$tsep_lng['setup_NewsLetter']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <input type=\"text\" name=\"email\" value=\"".$email."\" size=\"30\" onkeyup=\"processUpdateClick()\" onblur=\"processUpdateClick()\" onfocus=\"processUpdateClick()\" />\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: middle;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('".$tsep_lng['setup_NewsLetter_Help']."', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td style=\"vertical-align: top; text-align: left; width: 75%;\">".$tsep_lng['setup_Comment']."</td>\n";
    $html .= "              <td style=\"vertical-align: top; width: 25%;\">\n";
    $html .= "                <textarea name=\"comment\" rows=\"5\" cols=\"25\" onkeyup=\"processUpdateClick()\" onblur=\"processUpdateClick()\" onfocus=\"processUpdateClick()\">$comment</textarea>\n";
    $html .= "              </td>\n";
    $html .= "              <td style=\"vertical-align: top;\"><img src=\"images/con_info.png\" onmouseover=\"return overlib('".$tsep_lng['setup_Comment_Help']."', CAPTION, '$hintTitle', WIDTH, 175, LEFT, ABOVE);\" onmouseout=\"return nd();\" /></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "            <tr><td colspan=\"3\" style=\"font-style: italic;\">".$tsep_lng['setup_URLPreview']."</td></tr>\n";
    
    $html .= "            <tr>\n";
    $html .= "              <td colspan=\"3\" style=\"text-align: left; width: 100%; border: 1px dotted rgb(51, 102, 255);\"><span id=\"theURL\" style=\"font-size: 0.8em; color: rgb(51, 102, 255); border-bottom: 1px dashed rgb(0, 192, 0);\">".$tsep_lng['setup_JavaScriptEnabled']."</span></td>\n";
    $html .= "            </tr>\n";
    
    $html .= "          </table>\n";

    // <---  FOOTER  ------------------------------------------------------------------->
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";

    $html .= "    <table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 0px none; width: 100%;\">\n";
    $html .= "      <tr>\n";
    $html .= "        <td>&nbsp; </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr>\n";
    $html .= "        <td style=\"width: 100%; text-align: right; vertical-align: middle;\">\n";
    $html .= "          <img src=\"images/back.png\" title=\"{$tsep_lng['setup_ToPreviousStep']}\" />&nbsp;{$tsep_lng['setup_Previous']}&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <a href=\"javascript:document.sumForm.op.value='finish'; document.sumForm.submit();\"><img src=\"images/next_f2.png\" title=\"{$tsep_lng['setup_TakeMe2Config']}\" />&nbsp;{$tsep_lng['setup_Finish']}</a>&nbsp;&nbsp;&nbsp;\n";
    $html .= "          <img src=\"images/cancel.png\" title=\"{$tsep_lng['setup_IWantToQuitInstalling']}\" />&nbsp;{$tsep_lng['setup_Cancel']}\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "    </table>\n";
    $html .= "    </form>\n";

    $html .= "  </div>\n";
    $html .= "  <script>processUpdateClick();</script>\n";
    $html .= "  <script>document.sumForm.email.focus();</script>\n";
    
    $html .= "</body>\n";
    $html .= "</html>\n";

    return $html;
} // showFeedback

/***  END: Process step: Feedback  ******************************************************/



/*************************************************************************************************
 ****  MAIN CODE  ******************************************************************************** 
 *************************************************************************************************/

/* All config variables are stored in the session var */
session_start();

/* The dbSetupForm is POSTed because the */
/* password is passed in clear text.     */
/* To keep in line with the rest of the  */
/* code, $_POST is copied to $_GET.      */
if ( isset( $_POST ) and count( $_POST ) > 0 ) {
    $_GET = $_POST;
}

if ( !isset($_GET["lang"]) )
	if ( !isset($_SESSION["lang"]) )
		$_GET["lang"] = "en_US";
	else
		$_GET["lang"] = $_SESSION["lang"];

if ( $_GET["lang"] != "en_US" )
	require_once( "../language/" . $_GET["lang"] . "/language.php" );

/* Caption in the hint boxes */
$hintTitle = " TSEP";
/* File to check paths with */
$checkFile = "/search.php";
/* Progress bar */
$barWidth = 30;
$charDone = "#";
/* Steps to take */
$processSteps = array(
                "intro" => $tsep_lng['setup_Steps_1'],
                "dbSetup" => $tsep_lng['setup_Steps_2'],
                "sysCheck" => $tsep_lng['setup_Steps_3'],
                "config" => $tsep_lng['setup_Steps_4'],
                "install" => $tsep_lng['setup_Steps_5'],
                "sum" => $tsep_lng['setup_Steps_6'],
                "feedback" => $tsep_lng['setup_Steps_7']
            );

if ( isset( $_GET["op"] ) ) {
    
    /* Control structure for the complete process */
    switch ( $_GET["op"] ) {
        case "intro":
            saveSettings();
            echo writeIntroText();
            break;

        case "dbSetup":
            echo databaseData();
            break;

        case "confirm_dbSetup":
            confirm_dbSetup();
            break;

        case "sysCheck":
            echo systemCheck();
            break;

        case "confirm_sysCheck":
            confirm_sysCheck();
            break;

        case "config":
            echo Configuration();
            break;

        case "confirm_Config":
            confirm_Config();
            break;

        case "install":
            executeInstall();
            break;

        case "sum":
            echo showSummary();
            break;

        case "feedback":
            echo showFeedback();
            break;

        case "dlDBConnData":
            echo downloadDBConnData();
            break;

        case "finish":
            $protocol = ( $_SERVER["HTTPS"] == "on" ? "https://" : "http://" );
            $location = "";
            $location .= ( $_GET["installOK"] == "yes" ? ( $location == "" ? "?" : "&" )."install=ok" : "" );
            $location .= ( $_GET["sendNewTSEPVersion"] == "yes" ? ( $location == "" ? "?" : "&" )."ver=".urlencode( $_SESSION["tsepVersion"] ) : "" );
            $location .= ( $_GET["sendOldTSEPVersion"] == "yes" ? ( $location == "" ? "?" : "&" )."verprev=".urlencode( $_SESSION["tsepPrevVersion"] ) : "" );
            $location .= ( $_GET["domain"] == "yes" ? ( $location == "" ? "?" : "&" )."logreferer=yes" : "" );
            $location .= ( $_GET["email"] != "" ? ( $location == "" ? "?" : "&" )."email=".urlencode( $_GET["email"] ) : "" );
            $location .= ( $_GET["comment"] != "" ? ( $location == "" ? "?" : "&" )."comment=".urlencode( $_GET["comment"] ) : "" );
            $location .= ( $location != "" and isset( $_GET["fromstep"] ) ? "&step=".urlencode( $_GET["fromstep"] ) : "" );
            $location = ( $location != "" ? "http://www.tsep.info".$location."&referer=".urlencode( $protocol.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"] ) : "configuration.php" );
            session_unset();
            session_destroy();
            header( "location: $location" );
            break;

        case "cancel":
            echo cancelInstallation( $_GET["fromstep"] );
            break;

        case "quit":
            $protocol = ( $_SERVER["HTTPS"] == "on" ? "https://" : "http://" );
            $location = "";
            $location .= ( $_GET["installAbort"] == "yes" ? ( $location == "" ? "?" : "&" )."install=aborted" : "" );
            $location .= ( $_GET["sendNewTSEPVersion"] == "yes" ? ( $location == "" ? "?" : "&" )."ver=".urlencode( $_SESSION["tsepVersion"] ) : "" );
            $location .= ( $_GET["sendOldTSEPVersion"] == "yes" ? ( $location == "" ? "?" : "&" )."verprev=".urlencode( $_SESSION["tsepPrevVersion"] ) : "" );
            $location .= ( $_GET["domain"] == "yes" ? ( $location == "" ? "?" : "&" )."logreferer=yes" : "" );
            $location .= ( $_GET["email"] != "" ? ( $location == "" ? "?" : "&" )."email=".urlencode( $_GET["email"] ) : "" );
            $location .= ( $_GET["comment"] != "" ? ( $location == "" ? "?" : "&" )."comment=".urlencode( $_GET["comment"] ) : "" );
            $location .= ( $location != "" and isset( $_GET["fromstep"] ) ? "&step=".urlencode( $_GET["fromstep"] ) : "" );
            $location = ( $location != "" ? "http://www.tsep.info".$location."&referer=".urlencode( $protocol.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"] ) : "http://www.tsep.info" );
            session_unset();
            session_destroy();
            header( "location: $location" );
            break;

        default:
            saveSettings();
            echo writeIntroText();
    } // switch
    
} else {
    saveSettings();
    echo writeIntroText();
}
?>
