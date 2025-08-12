<?php

/***************************************************************************

 m_authors.php
 --------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

include_once ("rootdatapath.php");

$GLOBALS["form"] = 'authors';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','authors');


force_page_refresh();
frmAuthors();
$form_search = "";

function frmAuthors()
{
        global $_GET, $EzAdmin_Style;

        adminheader();
        admintitle(7,$GLOBALS["tFormTitle"]);
        adminbuttons($GLOBALS["tViewAuthor"],$GLOBALS["tAddNewAuthor"],$GLOBALS["tEditAuthor"],$GLOBALS["tDeleteAuthor"]);
        $GLOBALS["iRelease"] = adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["ReleaseIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tReleaseUser"],0,'rel_button.gif');
	$iVisible	= lsimagehtmltag($GLOBALS["icon_home"],'green_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tVisible"],0);
	$iHidden	= lsimagehtmltag($GLOBALS["icon_home"],'red_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tHidden"],0);

        if ($_GET["filtergroupname"] != "") {
                $strQuery = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." WHERE usergroup='".$_GET["filtergroupname"]."'";
          }
        else {
                $strQuery = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." WHERE authorid > '0'";
          }
        if ($_GET["form_search"] != "") {
          $form_search = $_GET["form_search"];
          $strQuery .= "AND (login like '%".$form_search."%' OR authorname like '%".$form_search."%')";
          }

        $result = dbRetrieve($strQuery,true,0,0);
        $rs        = dbFetch($result);
        $lRecCount = dbRowsReturned($result);
        dbFreeResult($result);

        $nCurrentPage = 0;
        if ($_GET["sort"] == '') { $_GET["sort"] = 2; }
        if ($_GET["page"] != '') { $nCurrentPage = $_GET["page"]; }
        $nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
        if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
        $lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

        ?>
        <tr class="teaserheadercontent">
                <td colspan="7" align="<?php echo $GLOBALS["left"]; ?>" nowrap>
                  <form action="<?php echo $GLOBALS["REQUEST_URI"]; ?>" method="GET" enctype="multipart/form-data" style="margin:0px;padding:0px;">
                        <b><?php echo $GLOBALS["tAuthorFilter"]; ?>:</b>&nbsp;
                        <select name="filtergroupname" size="1" onChange="submit();">
                                <?php RenderGroups($_GET["filtergroupname"]); ?>
                        </select>&nbsp;
                        <input type="image" name="submit" src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>go.gif" alt="Go" value="Go">
                  &nbsp;&nbsp;&nbsp;
                        <b>Filter:</b>&nbsp;
                        <input name="form_search" type="text" value="<?php echo htmlentities($form_search); ?>" size="40" maxlength="30">
                        <input type="image" name="submit" src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>go.gif" alt="Go" value="Go">
                        <input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
                        <input type="hidden" name="page" value="0">
                  </form>
                </td>
        </tr>
        <?php

        adminHdFt('authors',7,$nCurrentPage,$nPages,'&filtergroupname='.$_GET["filtergroupname"].'&form_search='.$_GET["form_search"]);
        ?>
        <tr class="teaserheadercontent">
                <?php
                adminlistitem(10,$GLOBALS["tEditDelRel"],'c');
                adminlistitem(15,$GLOBALS["tLogin"],'',1);
                adminlistitem(23,$GLOBALS["tUsername"],'',2);
                adminlistitem(27,$GLOBALS["tEMail"],'',3);
                adminlistitem(10,$GLOBALS["tUsergroup"],'',4);
                adminlistitem(10,$GLOBALS["tCountry"],'',5);
                adminlistitem(5,$GLOBALS["tEnabled"],'c',6);
                ?>
        </tr>
        <?php

        switch ($_GET["sort"]) {
                case '1' :        $sort = 'a.login';
                                        break;
                case '2' :        $sort = 'a.authorname';
                                        break;
                case '3' :        $sort = 'a.authoremail,a.authorname';
                                        break;
                case '4' :        $sort = 'u.usergroupdesc,a.authorname';
                                        break;
                case '5' :        $sort = 'c.countryname,a.authorname';
                                   break;
                case '6' :        $sort = 'a.disuser DESC,a.authorname';
                                        break;
                default  :        $sort = 'a.authorname';
        }
        if ($_GET["filtergroupname"] != "") {
                $sqlQuery = "SELECT a.authorid as authorid,a.login as login,a.authorname as authorname,a.usergroup as usergroup,a.authoremail as authoremail,a.disuser as disuser,u.usergroupdesc as usergroupdesc,c.countryname as country,c.flag as flag FROM ".$GLOBALS["eztbAuthors"]." a, ".$GLOBALS["eztbUsergroups"]." u LEFT JOIN ".$GLOBALS["eztbCountries"]." c ON c.countrycode=a.countrycode WHERE u.usergroupname=a.usergroup AND u.language='".$GLOBALS["gsLanguage"]."' AND usergroup='".$_GET["filtergroupname"]."'";
        } else {
                $sqlQuery = "SELECT a.authorid as authorid,a.login as login,a.authorname as authorname,a.usergroup as usergroup,a.authoremail as authoremail,a.disuser as disuser,u.usergroupdesc as usergroupdesc,c.countryname as country,c.flag as flag FROM ".$GLOBALS["eztbAuthors"]." a, ".$GLOBALS["eztbUsergroups"]." u LEFT JOIN ".$GLOBALS["eztbCountries"]." c ON c.countrycode=a.countrycode WHERE u.usergroupname=a.usergroup AND u.language='".$GLOBALS["gsLanguage"]."'";
        }
        if ($form_search != "") {
          $sqlQuery .= " AND (a.login like '%".$form_search."%' OR a.authorname like '%".$form_search."%')";
          }
        $sqlQuery .= " ORDER BY ".$sort;
        $result = dbRetrieve($sqlQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
        while ($rs = dbFetch($result)) {
                ?>
                <tr class="teasercontent">
                        <td align="center" valign="top" class="content">
                                <?php
                                admineditcheck('authorsform','AuthorID',$rs["authorid"],$rs["authorid"]);
                                // Can't delete administrator accounts
                                if ($rs["usergroup"] == $GLOBALS["gsAdminPrivGroup"]) { echo $GLOBALS["iBlank"];
                                } else { admindeletecheck('DelAuthor','AuthorID',$rs["authorid"]); }
                                userreleasecheck($rs["authorid"]);
                                ?>
                        </td>
                        <td valign="top" class="content">
                                <?php echo $rs["login"]; ?>
                        </td>
                        <td valign="top" class="content">
                                <?php echo $rs["authorname"]; ?>
                        </td>
                        <td valign="top" class="content">
                                <?php echo $rs["authoremail"]; ?>
                        </td>
                        <td valign="top" class="content">
                                <?php echo $rs["usergroupdesc"]; ?>
                        </td>
                        <td valign="top" class="content">
                                <?php
                                echo $rs["country"];
                                if ($rs["flag"] != '') {
                                        echo imagehtmltag($GLOBALS["icon_home"].'flags/',$rs["flag"].'_small.gif',$rs["countryname"],0,'');
                                } else { echo '&nbsp'; }
                                ?>
                        </td>
                        <td valign="top" align="center" class="content">
                                <?php
                                if ($rs["disuser"] == '1') { echo $iHidden; } else { echo $iVisible; }
                                ?>
                        </td>
                </tr>
                <?php
        }
        dbFreeResult($result);

        adminHdFt('authors',7,$nCurrentPage,$nPages,'&filtergroupname='.$_GET["filtergroupname"].'&form_search='.$_GET["form_search"]);
        ?>
        </table>
        </body>
        </html>
        <?php
} // function frmAuthors()


function RenderGroups($GroupName)
{
        $sqlQuery = "SELECT usergroupname,usergroupdesc FROM ".$GLOBALS["eztbUsergroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' ORDER BY usergroupdesc";
        $result = dbRetrieve($sqlQuery,true,0,0);
        echo '<option value="">-- '.$GLOBALS["tShowAll"].' --</option>';
        while ($rs = dbFetch($result)) {
                echo '<option ';
                if ($GroupName == $rs["usergroupname"]) { echo 'selected '; }
                echo 'value="'.$rs["usergroupname"].'">'.$rs["usergroupdesc"];
        }
        dbFreeResult($result);
} // function RenderGroups()


function userreleasecheck($UserID)
{
        global $_GET;

        if ($GLOBALS["canedit"] == False) { echo $GLOBALS["iBlank"];
        } else {
                ?>
                &nbsp;<a href="javascript:RelAuthor('UserID=<?php echo $UserID; ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>');" <?php echo BuildLinkMouseOver($GLOBALS["tReleaseUser"]); ?>>
                <?php echo $GLOBALS["iRelease"]; ?></a><?php
        }
} // function themereleasecheck()


?>
<script language="Javascript" type="text/javascript">
        <!-- Begin
        function DelAuthor(sParams) {
                if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
                        location.href='<?php echo BuildLink('m_authorsdel.php'); ?>&' + sParams;
                }
        }

        function RelAuthor(sParams) {
                if (window.confirm('<?php echo $GLOBALS["tConfirmRelease"]; ?>')) {
                        location.href='<?php echo BuildLink('m_authorsrel.php'); ?>&' + sParams;
                }
        }
        //  End -->
</script>
