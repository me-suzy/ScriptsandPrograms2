<?php 

/*************************************************************************** 

 m_content.php 
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

$GLOBALS["form"] = 'content'; 
$GLOBALS["validaccess"] = VerifyAdminLogin(); 

includeLanguageFiles('admin','content'); 


//  Set the default filter language to the user's language, unless it's been set 
//      by the filter already. 
if ((!isset($_GET["filterlangname"])) || ($_GET["filterlangname"] == "")) { 
   $_GET["filterlangname"] = $GLOBALS["gsLanguage"]; 
} 

force_page_refresh(); 
frmContents(); 


function frmContents() 
{ 
   global $_SERVER, $_GET, $EzAdmin_Style; 

   adminheader(); 
   admintitle(10,$GLOBALS["tFormTitle"]); 
   adminbuttons($GLOBALS["tViewContent"],$GLOBALS["tAddNewContent"],$GLOBALS["tEditContent"],$GLOBALS["tDeleteContent"]); 
   $GLOBALS["iTranslate"]   = adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["EditIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tTranslate"],0,'edit_button.gif'); 
   $GLOBALS["iTick"]      = lsimagehtmltag($GLOBALS["icon_home"],'tick.gif',$GLOBALS["gsLanguage"],$GLOBALS["tTranslated"],0); 
   $GLOBALS["iCross"]      = lsimagehtmltag($GLOBALS["icon_home"],'cross.gif',$GLOBALS["gsLanguage"],$GLOBALS["tNotTranslated"],0); 

   $timenow   = time(); 
   $isodate   = strftime("%Y-%m-%d",$timenow); 
   $iRed      = lsimagehtmltag($GLOBALS["icon_home"],'red_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tExpired"],0); 
   $iAmber      = lsimagehtmltag($GLOBALS["icon_home"],'orange_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tPending"],0); 
   $iGreen      = lsimagehtmltag($GLOBALS["icon_home"],'green_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tActive"],0); 
   $iEnabled   = lsimagehtmltag($GLOBALS["icon_home"],'green_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tEnabled"],0); 
   $iDisabled   = lsimagehtmltag($GLOBALS["icon_home"],'red_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tDisabled"],0); 
   $ileftcol   = lsimagehtmltag($GLOBALS["icon_home"],'colleft.gif',$GLOBALS["gsLanguage"],$GLOBALS["tLeft"],0); 
   $irightcol   = lsimagehtmltag($GLOBALS["icon_home"],'colright.gif',$GLOBALS["gsLanguage"],$GLOBALS["tRight"],0); 

   //   We want the count of all content items, not just those in the current language 
   //      so we use the site default language for this check. 
        if ($_GET["filtercontentname"] != "") { 
         if ($_GET["filtergroupname"] != "") { 
          $strQuery = "SELECT DISTINCT contentname FROM ".$GLOBALS["eztbContents"]." WHERE groupname='".$_GET["filtergroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."' AND title LIKE '%".$_GET["filtercontentname"]."%'"; 
           } else { 
          $strQuery = "SELECT DISTINCT contentname FROM ".$GLOBALS["eztbContents"]." WHERE language='".$GLOBALS["gsDefault_language"]."' AND title LIKE '%".$_GET["filtercontentname"]."%'"; 
           } 
        } else { 
         if ($_GET["filtergroupname"] != "") { 
          $strQuery = "SELECT DISTINCT contentname FROM ".$GLOBALS["eztbContents"]." WHERE groupname='".$_GET["filtergroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."'"; 
           } else { 
          $strQuery = "SELECT DISTINCT contentname FROM ".$GLOBALS["eztbContents"]." WHERE language='".$GLOBALS["gsDefault_language"]."'"; 
           } 
        } 

   $result = dbRetrieve($strQuery,true,0,0); 
   $rs     = dbFetch($result); 
   $lRecCount = dbRowsReturned($result); 
   dbFreeResult($result); 

   $nCurrentPage = 0; 
   if ($_GET["sort"] == '') { $_GET["sort"] = 1; } 
   if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; } 
   $nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1; 
   if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; } 
   $lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"]; 

   ?> 
   <form action="<?php echo $GLOBALS["REQUEST_URI"]; ?>" method="GET" enctype="multipart/form-data"> 
   <tr class="teaserheadercontent"> 
      <td colspan="10" align="<?php echo $GLOBALS["left"]; ?>" nowrap> 
         <b><?php echo $GLOBALS["tMenuFilter"]; ?>:</b>&nbsp; 
         <select name="filtergroupname" size="1" onChange="submit();"> 
            <?php RenderGroups($_GET["filtergroupname"]); ?> 
         </select>&nbsp; 
            <b><?php echo $GLOBALS["tTitle"]; ?>:</b>&nbsp;<input type="text" size="40" name="filtercontentname"/> 
         <?php 
         if ($GLOBALS["gsMultiLanguage"] == 'Y') { 
            ?> 
            &nbsp; 
            <b><?php echo $GLOBALS["tLangFilter"]; ?>:</b>&nbsp; 
            <select name="filterlangname" size="1" onChange="submit();"> 
               <?php RenderLanguages($_GET["filterlangname"]); ?> 
            </select>&nbsp; 
            <?php 
         } 
         ?> 
         <input type="image" name="submit" src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>go.gif" alt="Go" value="Go"> 
         <input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>"> 
         <input type="hidden" name="page" value="<?php echo $_GET["page"]; ?>"> 
         <input type="hidden" name="sort" value="<?php echo $_GET["sort"]; ?>"> 
      </td> 
   </tr> 
   </form> 
   <?php 

   frmContentHdFt(10,$nCurrentPage,$nPages); 
   ?> 
   <tr class="teaserheadercontent"><?php 
      adminlistitem(8,$GLOBALS["tEditDel"],'c'); 
      adminlistitem(26,$GLOBALS["tTitle"],'',4); 
      adminlistitem(18,$GLOBALS["tMenu"],'',3); 
      adminlistitem(18,$GLOBALS["tSubmenu"],''); 
      adminlistitem(15,$GLOBALS["tPageRef"],'',2);
      adminlistitem(10,$GLOBALS["tLeftRight"],'c');
	  adminlistitem(3,$GLOBALS["tRSSen"],'c');
	  adminlistitem(5,$GLOBALS["tSearchen"],'c'); 
      adminlistitem(10,$GLOBALS["tActive"],'c',5); 
      adminlistitem(5,$GLOBALS["toOrderID"],'',1); 
   ?> 
   </tr> 
   <?php 

   if ($_GET["filterlangname"] == $GLOBALS["gsDefault_language"]) { 
      //   If we're working in the site default language, it's a simple sql statement to 
      //      create the paged list. 
           if ($_GET["filtercontentname"] != "") { 
             if ($_GET["filtergroupname"] != "") { 
               $strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE groupname='".$_GET["filtergroupname"]."' AND language='".$GLOBALS["gsLanguage"]."' AND title LIKE '%".$_GET["filtercontentname"]."%' ORDER BY groupname,subgroupname,orderid"; 
            } else { 
               $strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE language='".$GLOBALS["gsLanguage"]."' AND title LIKE '%".$_GET["filtercontentname"]."%' ORDER BY groupname,subgroupname,orderid"; 
            } 
                } else { 
                      if ($_GET["filtergroupname"] != "") { 
               $strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE groupname='".$_GET["filtergroupname"]."' AND language='".$GLOBALS["gsLanguage"]."' ORDER BY groupname,subgroupname,orderid"; 
            } else { 
               $strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE language='".$GLOBALS["gsLanguage"]."' ORDER BY groupname,subgroupname,orderid"; 
            } 
                } 
      $result = dbRetrieve($strQuery,true,$lStartRec,0,0); 
   } else { 
      //   Things get slightly more complex if we want to display entries in the filter 
      //      language where they're available, but in the base language where they're not. 
      //      We build the list using a select in the base language first for the paging 
      //      counts, and generate an array containing all the contentnames to be 
      //      displayed on this page. 
                if ($_GET["filtercontentname"] != "") { 
              if ($_GET["filtergroupname"] != "") { 
              $sqlQuery = "SELECT contentname FROM ".$GLOBALS["eztbContents"]." WHERE groupname='".$_GET["filtergroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."' AND title LIKE '%".$_GET["filtercontentname"]."%' ORDER BY groupname,subgroupname,orderid"; 
              } else { 
              $sqlQuery = "SELECT contentname FROM ".$GLOBALS["eztbContents"]." WHERE language='".$GLOBALS["gsDefault_language"]."' AND title LIKE '%".$_GET["filtercontentname"]."%' ORDER BY groupname,subgroupname,orderid"; 
              } 
                 } else { 
              if ($_GET["filtergroupname"] != "") { 
              $sqlQuery = "SELECT contentname FROM ".$GLOBALS["eztbContents"]." WHERE groupname='".$_GET["filtergroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY groupname,subgroupname,orderid"; 
              } else { 
              $sqlQuery = "SELECT contentname FROM ".$GLOBALS["eztbContents"]." WHERE language='".$GLOBALS["gsDefault_language"]."' ORDER BY groupname,subgroupname,orderid"; 
              } 
                 } 

      $inlist = ""; 
      $result = dbRetrieve($sqlQuery,true,$lStartRec,0,0); 
      while ($rs = dbFetch($result)) { $inlistelements[] = "'".$rs["contentname"]."'"; } 
      dbFreeResult($result); 
      if (isset($inlistelements)) { $inlist = "contentname IN (". implode(',',$inlistelements).") AND"; } 
      $lOrder = ''; 
      if ($_GET["filterlangname"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; } 
                if ($_GET["filtercontentname"] != "") { 
              if ($_GET["filtergroupname"] != "") { 
                $strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ".$inlist." groupname='".$_GET["filtergroupname"]."' AND (language='".$_GET["filterlangname"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND contentname LIKE '".$_GET["filtercontentname"]."' ORDER BY groupname,subgroupname,orderid,language".$lOrder; 
               } else { 
                $strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ".$inlist." (language='".$_GET["filterlangname"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND contentname LIKE '".$_GET["filtercontentname"]."' ORDER BY groupname,subgroupname,orderid,language".$lOrder; 
              } 
                } else { 
              if ($_GET["filtergroupname"] != "") { 
                $strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ".$inlist." groupname='".$_GET["filtergroupname"]."' AND (language='".$_GET["filterlangname"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY groupname,subgroupname,orderid,language".$lOrder; 
               } else { 
                $strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ".$inlist." (language='".$_GET["filterlangname"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY groupname,subgroupname,orderid,language".$lOrder; 
              } 
                } 

      $result = dbRetrieve($strQuery,true,0,0); 
   } 

   //   Transfer our SQL query results to another array ($Articles) filtering out the duplicates as we do so 
   //      This is the array we'll use to handle the actual sorting and then reduce it to a single page of 
   //      entries for display 
   $a = 0; 
   $nArticleName = ''; 
   while ($rs = dbFetch($result)) { 
      //   Filter out default language entries where we have duplicates. 
      //      This will only apply if we're filtering on a language other than the default. 
      //   We also filter out previous versions of the same article through this routine. 
      if ($rs["contentname"] != $nArticleName) { 
         $nArticleName = $rs["contentname"]; 

         $Articles[$a]["contentname"]   = $rs["contentname"]; 
         $Articles[$a]["language"]      = $rs["language"]; 
         $Articles[$a]["authorid"]      = $rs["authorid"]; 
         $Articles[$a]["groupname"]      = $rs["groupname"]; 
         $Articles[$a]["subgroupname"]   = $rs["subgroupname"]; 
         $Articles[$a]["title"]         = $rs["title"]; 
         $Articles[$a]["publishdate"]   = $rs["publishdate"]; 
         $Articles[$a]["expiredate"]      = $rs["expiredate"]; 
         $Articles[$a]["contentactive"]   = $rs["contentactive"]; 
         $Articles[$a]["orderid"]      = $rs["orderid"];
		 $Articles[$a]["rssvisible"]			= $rs["rssvisible"];
		 $Articles[$a]["searchvisible"]	= $rs["searchvisible"]; 
         $Articles[$a]["leftright"]      = $rs["leftright"];
         $a++; 
      } 
   } 
   dbFreeResult($result); 

   if (isset($Articles)) { 
      switch ($_GET["sort"]) { 
         case '1' : $Articles = array_csort($Articles,'groupname','subgroupname','orderid'); 
                  break; 
         case '2' : $Articles = array_csort($Articles,'contentname'); 
                  break; 
         case '3' : $Articles = array_csort($Articles,'groupname','subgroupname','contentname'); 
                  break; 
         case '4' : $Articles = array_csort($Articles,'title','contentname'); 
                  break; 
         case '5' : $Articles = array_csort($Articles,'expiredate','publishdate','contentname'); 
                  break; 
         default  : $Articles = array_csort($Articles,'groupname','subgroupname','orderid'); 
      } 
   } 

   $i = $lStartRec; 
   $j = $lStartRec + $GLOBALS["RECORDS_PER_PAGE"]; 
   if ($j > $a) { $j = $a; } 
   for ($c=$i; $c<$j; $c++) { 
      ?> 
      <tr class="teasercontent"> 
         <td align="center" valign="top"> 
            <?php 
            if ($_GET["filterlangname"] != $GLOBALS["gsDefault_language"]) { 
               admintranslatecheck('tcontentform','ContentName',$Articles[$c]["contentname"],'LanguageCode',$_GET["filterlangname"]); 
            } else { 
               admineditcheck('contentform','ContentName',$Articles[$c]["contentname"],$Articles[$c]["authorid"]); 
            } 
            admindeletecheck('Delcontnt','ContentName',$Articles[$c]["contentname"]); 
            ?> 
         </td> 
         <td valign="top" class="content"> 
            <?php 
            if ($_GET["filterlangname"] != $GLOBALS["gsDefault_language"]) { 
               if ($Articles[$c]["language"] != $_GET["filterlangname"]) { echo $GLOBALS["iCross"].'&nbsp;'; } else { echo $GLOBALS["iTick"].'&nbsp;'; } 
            } 
            echo $Articles[$c]["title"]; 
            ?> 
         </td> 

         <td valign="top" class="content"> 
            <?php if ($Articles[$c]["groupname"] == '999999999') { echo $GLOBALS["tAllMenus"]; } else { echo sGetGroupName($Articles[$c]["groupname"]); } ?> 
         </td> 
         <td valign="top" class="content"> 
            <?php echo sGetSubGroupName($Articles[$c]["subgroupname"]); ?> 
         </td> 
           <td valign="top" class="content"> 
            <?php echo $Articles[$c]["contentname"]; ?> 
         </td>
		<td align=center class=content>
            <?php 
            if ($Articles[$c]["leftright"] == "L") { echo $ileftcol; } 
            elseif ($Articles[$c]["leftright"] == "R") { echo $irightcol; } 
            else { echo $ileftcol; } 
            ?> 
		</td>
			<td valign="top" align="center" class="content">
				<?php
				if ($Articles[$c]["rssvisible"] == 'Y') { echo $iGreen; }
				else { echo $iRed; }
				?>
			</td>
			<td valign="top" align="center" class="content">
				<?php
				if ($Articles[$c]["searchvisible"] == 'Y') { echo $iGreen; }
				else { echo $iRed; }
				?>
			</td>
        <td valign="top" align="center" class="content"> 
            <?php 
            if (substr($Articles[$c]["publishdate"], 0, 10) > $isodate) { echo $iAmber; } 
            elseif (substr($Articles[$c]["expiredate"], 0, 10) <= $isodate) { echo $iRed; } 
            else { echo $iGreen; } 
            ?> 
         </td> 
         <td align="center" valign="top" class="content"> 
            <?php 
            if ($_GET["sort"] == 1) { 
               adminmovecheck('up','ContentMove','ContentName',$Articles[$c]["contentname"]); 
               adminmovecheck('down','ContentMove','ContentName',$Articles[$c]["contentname"]); 
            } 
            ?> 
         </td> 
      </tr> 
      <?php 
   } 

   dbFreeResult($result); 

   frmContentHdFt(10,$nCurrentPage,$nPages); 
   ?> 
   </table> 
   </form> 
   </body> 
   </html> 
   <?php 
} // function frmContents() 


function sGetGroupName($GroupName) 
{ 
   $groupname = "---"; 
   $strQuery = "SELECT groupdesc FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$GroupName."' AND language='".$GLOBALS["gsLanguage"]."'"; 
   $result = dbRetrieve($strQuery,true,0,0); 
   if ($rs = dbFetch($result)) { $groupname = $rs["groupdesc"]; } 
   dbFreeResult($result); 
   return $groupname; 
} // function sGetGroupName() 


function sGetSubGroupName($SubGroupName) 
{ 
   $subgroupname = "---"; 
   $strQuery = "SELECT subgroupdesc FROM ".$GLOBALS["eztbSubgroups"]." WHERE subgroupname='".$SubGroupName."' AND language='".$GLOBALS["gsLanguage"]."'"; 
   $result = dbRetrieve($strQuery,true,0,0); 
   if ($rs = dbFetch($result)) { $subgroupname = $rs["subgroupdesc"]; } 
   dbFreeResult($result); 
   return $subgroupname; 
} // function sGetSubGroupName() 


function RenderGroups($GroupName) 
{ 
   if ($GLOBALS["gsShowTopMenu"] == 'Y') { 
      $sqlQuery = "SELECT g.groupname AS groupname,g.groupdesc AS groupdesc,t.topgroupdesc AS topgroupdesc FROM ".$GLOBALS["eztbGroups"]." g LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language=g.language WHERE g.language='".$GLOBALS["gsLanguage"]."' AND g.grouplink='' ORDER BY t.topgrouporderid,g.grouporderid"; 
   } else { 
      $sqlQuery = "SELECT groupname,groupdesc FROM ".$GLOBALS["eztbGroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' AND grouplink='' ORDER BY grouporderid"; 
   } 
   $result = dbRetrieve($sqlQuery,true,0,0); 
   echo '<option value="">-- '.$GLOBALS["tShowAll"].' --</option>'; 
   while ($rs = dbFetch($result)) { 
      echo '<option '; 
      if ($GroupName == $rs["groupname"]) { echo 'selected '; } 
      echo 'value="'.$rs["groupname"].'">'; 
      if ($GLOBALS["gsShowTopMenu"] == 'Y') { echo $rs["topgroupdesc"].' - '; } 
      echo $rs["groupdesc"]; 
   } 
   dbFreeResult($result); 
} // function RenderGroups() 


function RenderLanguages($LanguageCode) 
{ 
   $sqlQuery = "SELECT * FROM ".$GLOBALS["eztbLanguages"]." WHERE enabled='Y' ORDER BY languagename"; 
   $result = dbRetrieve($sqlQuery,true,0,0); 
   while ($rs = dbFetch($result)) { 
      echo '<option '; 
      if ($LanguageCode == $rs["languagecode"]) { echo 'selected '; } 
      echo 'value="'.$rs["languagecode"].'">'.$rs["languagename"]; 
   } 
   dbFreeResult($result); 
} // function RenderLanguages() 

?> 


<script language="Javascript" type="text/javascript"> 
   <!-- Begin 
   function Delcontnt(sParams) { 
      if(window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) { 
         location.href='<?php echo BuildLink('m_contentdel.php'); ?>&' + sParams; 
      } 
   } 

   function ContentMove(sParams) { 
      location.href='<?php echo BuildLink('m_contentmove.php'); ?>&' + sParams; 
   } 
   //  End --> 
</script> 

<?php 

function frmContentHdFt($colspan,$nCurrentPage,$nPages) 
{ 
   global $_GET; 

   $pLink = BuildLink('m_content.php'); 
   $fLink = BuildLink('m_contentform.php'); 
   $linkmod = '&filterlangname='.$_GET["filterlangname"].'&filtergroupname='.$_GET["filtergroupname"]; 
   $hlink = '<a href="'.$fLink.$linkmod.'&page='.$nCurrentPage.'&sort='.$_GET["sort"].'" title="'.$GLOBALS["tAddNew"].'" '.BuildLinkMouseOver($GLOBALS["tAddNew"]).'>'; 
   echo '<form name="PagingForm" action="'.$pLink.'" method="GET">'; 
   ?> 
   <tr class="topmenuback"> 
      <td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>"> 
         <table height="100%" width="100%" cellspacing="0" cellpadding="0"> 
            <tr><?php 
               //   Add new is only permitted in the site default language 
               if ($_GET["filterlangname"] == $GLOBALS["gsDefault_language"]) { 
                  if ($GLOBALS["canadd"] === True) { 
                     ?><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php 
                     echo displaybutton('addbutton','content',$GLOBALS["tAddNew"].'...',$hlink); 
                     ?></td><?php 
                  } 
               } 
               ?> 
               <td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom"><?php 
                  if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=0&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tFirstPage"]); ?>><?php echo $GLOBALS["iFirst"]; ?></a><?php } else { echo $GLOBALS["iFirst"]; } 
                  echo '&nbsp;'; 
                  if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nCurrentPage - 1; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tPrevPage"]); ?>><?php echo $GLOBALS["iPrev"]; ?></a><?php } else { echo $GLOBALS["iPrev"]; } 
                  $nCPage = $nCurrentPage + 1; 
                  echo RenderPageList($nCPage,$nPages,'m_content.php',$linkmod); 
                  if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nCurrentPage + 1; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tNextPage"]); ?>><?php echo $GLOBALS["iNext"]; ?></a><?php } else { echo $GLOBALS["iNext"]; } 
                  echo '&nbsp;'; 
                  if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nPages - 1; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tLastPage"]); ?>><?php echo $GLOBALS["iLast"]; ?></a><?php } else { echo $GLOBALS["iLast"]; } ?> 
               </td> 
            </tr> 
         </table> 
      </td> 
   </tr> 
   <?php 
   echo '</form>'; 
} // function frmContentHdFt() 

?> 