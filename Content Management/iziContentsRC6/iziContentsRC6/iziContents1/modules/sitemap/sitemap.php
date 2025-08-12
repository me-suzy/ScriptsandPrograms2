<?php

/***************************************************************************

 sitemap.php
 ------------
 copyright : (C) 2002-2003 The ezContents Development Team

 ***************************************************************************/

/***************************************************************************
 The ezContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding ezContents must remain intact on the
 scripts and in the HTML for the scripts.

 For more info on ezContents,
 visit http://www.ezcontents.org/

/***************************************************************************

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package.
 *
 ***************************************************************************/

global $_SERVER;
if ( (substr($_SERVER["PHP_SELF"],-11) == 'control.php') ||
	 (substr($_SERVER["PHP_SELF"],-10) == 'module.php') ||
	 (substr($_SERVER["PHP_SELF"],-16) == 'showcontents.php') ) {
	 require_once('../moduleSec.php');
} else {
	require_once('../moduleSec.php');
}

if (!isset($GLOBALS["gsLanguage"])) { Header("Location: ".$GLOBALS["rootdp"]."module.php?link=".$GLOBALS["modules_home"]."sitemap/sitemap.php"); }

if ($GLOBALS["gsShowTopMenu"] == 'Y')
{
   // We always list all menu items in the default site language; but if the user language is different we
   //    include any menu items in that language as well, sorted so that the user language items will be
   //    processed first.... then we filter out the default site language items when we inspect the list.
   if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"])
   {
      $strQuery = "SELECT * FROM ".$GLOBALS["eztbTopgroups"]." WHERE topmenuvisible='Y' AND language='".$GLOBALS["gsLanguage"]."' ORDER BY topgrouporderid";
   }
   else
   {
      $lOrder = '';
      if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"])
      {
         $lOrder = ' DESC';
      }
      $strQuery = "SELECT * FROM ".$GLOBALS["eztbTopgroups"]." WHERE topmenuvisible='Y' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY topgrouporderid,language".$lOrder;
   }

   $tresult = dbRetrieve($strQuery,true,0,0);
   $nTopGroupName = '';
   while ($trs = dbFetch($tresult))
   {
      if ($trs["topgroupname"] != $nTopGroupName)
      {
         $nTopGroupName = $trs["topgroupname"];
         if (($GLOBALS["gsPrivateMenus"] == 'L') || ($trs["loginreq"] != 'Y') || ($EZ_SESSION_VARS["PasswordCookie"] != ''))
         {
            $topgroupname  = $trs["topgroupname"];
            $topgroupdesc  = $trs["topgroupdesc"];
            $topgrouplink  = $trs["topgrouplink"];
            $tophovertitle = $trs["tophovertitle"];
            $topopeninpage = $trs["topopeninpage"];
            $toploginreq   = $trs["loginreq"];
            $topusergroups = $trs["usergroups"];

            if ($toploginreq == 'Y')
            {
               if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $topgrouplink = "loginreq.php"; }
               // User is logged in, test against the list of valid user groups for this option
               if ($topusergroups != '')
               {
                  $Menu_Usergroups = explode(',',$topusergroups);
                  if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups))
                  {
                     $topgrouplink = "loginreq2.php";
                  }
               }
               $topopeninpage = 'Y';
            }
            $levels["t"] = $topgroupname;
            BuildSMLink ('t',true,$topgrouplink,$topopeninpage,$levels,'heading',$tophovertitle,$topgroupdesc);
            ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="3" class="headercontent">
                <tr><td class="header">
            <?php

            echo $GLOBALS["aref"].$topgroupdesc.$GLOBALS["atail"];
            if ($toploginreq == 'Y')
            {
               $toplocked = true;
               ?>
               &nbsp;<?php echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsSecureIcon"],$GLOBALS["tPrivateOption"],0,'');
            }
            else
            {
               $toplocked = false;
            }
            if ($tophovertitle != '')
            {
               echo '<br /><font size="-1">'.$tophovertitle.'</font>';
            }
            ?>
            </td></tr>
            <tr><td class="tablecontent">
            <ul>
            <?php

            if ($topgrouplink == '')
            {
               // We always list all menu items in the default site language; but if the user language is different we
               //    include any menu items in that language as well, sorted so that the user language items will be
               //    processed first.... then we filter out the default site language items when we inspect the list.
               if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"])
               {
                  $strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE (topgroupname='".$topgroupname."' or topgroupname='999999999') AND menuvisible='Y' AND language='".$GLOBALS["gsLanguage"]."' ORDER BY grouporderid";
               }
               else
               {
                  $lOrder = '';
                  if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"])
                  {
                     $lOrder = ' DESC';
                  }
                  $strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE (topgroupname='".$topgroupname."' or topgroupname='999999999') AND menuvisible='Y' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY grouporderid,language".$lOrder;
               }

               $mresult = dbRetrieve($strQuery,true,0,0);
               $nGroupName = '';
               while ($mrs = dbFetch($mresult))
               {
                  if ($mrs["groupname"] != $nGroupName)
                  {
                     $nGroupName = $mrs["groupname"];
                     if (($GLOBALS["gsPrivateMenus"] == 'L') || ($mrs["loginreq"] != 'Y') || ($EZ_SESSION_VARS["PasswordCookie"] != '')) {
                        $groupname    = $mrs["groupname"];
                        $groupdesc  = $mrs["groupdesc"];
                        $grouplink  = $mrs["grouplink"];
                        $hovertitle = $mrs["hovertitle"];
                        $openinpage = $mrs["openinpage"];
                        $loginreq   = $mrs["loginreq"];
                        $usergroups = $mrs["usergroups"];

                        if (($toplocked) || ($loginreq == 'Y')) {
                           if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $grouplink = "loginreq.php"; }
                           // User is logged in, test against the list of valid user groups for this option
                           if ($usergroups != '')
                           {
                              $Menu_Usergroups = explode(',',$usergroups);
                              if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups))
                              {
                                 $grouplink = "loginreq2.php";
                              }
                           }
                           $openinpage = 'Y';
                        }
                        $levels["m"] = $groupname;
                        BuildSMLink ('m',true,$grouplink,$openinpage,$levels,'',$hovertitle,$groupdesc);
                        echo '<li>'.$GLOBALS["aref"].$groupdesc.$GLOBALS["atail"];

                        if (($toplocked) || ($loginreq == 'Y')) {
                           $locked = true;
                           ?>&nbsp;<?php echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsSecureIcon"],$GLOBALS["tPrivateOption"],0,'');
                        } else { $locked = false; }
                        if ($hovertitle != '') { echo '<br />'.$hovertitle; }

                        if ($grouplink == '')
                        {
                           echo '<ul>';

                           // We always list all menu items in the default site language; but if the user language is different we
                           //    include any menu items in that language as well, sorted so that the user language items will be
                           //    processed first.... then we filter out the default site language items when we inspect the list.
                           if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"])
                           {
                              $strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname='".$groupname."' AND submenuvisible='Y' AND language='".$GLOBALS["gsLanguage"]."' ORDER BY subgrouporderid";
                           }
                           else
                           {
                              $lOrder = '';
                              if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"])
                              {
                                 $lOrder = ' DESC';
                              }
                              $strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname='".$groupname."' AND submenuvisible='Y' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY subgrouporderid,language".$lOrder;
                           }
                           $sresult = dbRetrieve($strQuery,true,0,0);
                           $nSubgroupName = '';
                           while ($srs = dbFetch($sresult))
                           {
                              if ($srs["subgroupname"] != $nSubgroupName)
                              {
                                 $nSubgroupName = $srs["subgroupname"];
                                 if (($GLOBALS["gsPrivateMenus"] == 'L') || ($srs["loginreq"] != 'Y') || ($EZ_SESSION_VARS["PasswordCookie"] != '')) {
                                    $subgroupname  = $srs["subgroupname"];
                                    $subgroupdesc  = $srs["subgroupdesc"];
                                    $subgrouplink  = $srs["subgrouplink"];
                                    $subhovertitle = $srs["hovertitle"];
                                    $subopeninpage = $srs["openinpage"];
                                    $subloginreq   = $srs["loginreq"];
                                    $subusergroups = $srs["usergroups"];

                                    if (($locked) || ($subloginreq == 'Y')) {
                                       if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $subgrouplink = "loginreq.php"; }
                                       // User is logged in, test against the list of valid user groups for this option
                                       if ($subusergroups != '')
                                       {
                                          $Menu_Usergroups = explode(',',$subusergroups);
                                          if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups))
                                          {
                                             $subgrouplink = "loginreq2.php";
                                          }
                                       }
                                       $subopeninpage = 'Y';
                                    }
                                    $levels["s"] = $subgroupname;
                                    BuildSMLink ('s',true,$subgrouplink,$subopeninpage,$levels,'',$subhovertitle,$subgroupdesc);
                                    echo '<li>'.$GLOBALS["aref"].$subgroupdesc.$GLOBALS["atail"];
                                    if (($locked) || ($subloginreq == 'Y'))
                                    {
                                       ?>&nbsp;<?php echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsSecureIcon"],$GLOBALS["tPrivateOption"],0,'');
                                    }
                                    if ($subhovertitle != '') { echo '<br />'.$subhovertitle; }
                                 }
                              }
                           }
                           dbFreeResult($sresult);
                           $levels["s"] = '';
                           echo '</ul>';
                        }
                     }
                  }
               }
               dbFreeResult($mresult);
            }
            $levels["m"] = '';
            ?></ul></td></tr>
            </table><?php
         }
      }
   }
   dbFreeResult($tresult);
}
else
{
   // We always list all menu items in the default site language; but if the user language is different we
   //    include any menu items in that language as well, sorted so that the user language items will be
   //    processed first.... then we filter out the default site language items when we inspect the list.
   if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"])
   {
      $strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE menuvisible='Y' AND language='".$GLOBALS["gsLanguage"]."' ORDER BY grouporderid";
   }
   else
   {
      $lOrder = '';
      if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"])
      {
         $lOrder = ' DESC';
      }
      $strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE menuvisible='Y' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY grouporderid,language".$lOrder;
   }

   $mresult = dbRetrieve($strQuery,true,0,0);
   $nGroupName = '';
   while ($mrs = dbFetch($mresult))
   {
      if ($mrs["groupname"] != $nGroupName)
      {
         $nGroupName = $mrs["groupname"];
         if (($GLOBALS["gsPrivateMenus"] == 'L') || ($mrs["loginreq"] != 'Y') || ($EZ_SESSION_VARS["PasswordCookie"] != ''))
         {
            $groupname  = $mrs["groupname"];
            $groupdesc  = $mrs["groupdesc"];
            $grouplink  = $mrs["grouplink"];
            $hovertitle = $mrs["hovertitle"];
            $openinpage = $mrs["openinpage"];
            $loginreq   = $mrs["loginreq"];
            $usergroups = $mrs["usergroups"];

            if ($loginreq == 'Y') {
               if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $grouplink = "loginreq.php"; }
               // User is logged in, test against the list of valid user groups for this option
               if ($usergroups != '')
               {
                  $Menu_Usergroups = explode(',',$usergroups);
                  if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups))
                  {
                     $grouplink = "loginreq2.php";
                  }
               }
               $openinpage = 'Y';
            }
            $levels["m"] = $groupname;
            BuildSMLink ('m',false,$grouplink,$openinpage,$levels,'heading',$hovertitle,$groupdesc);
            ?><table border="0" width="100%" cellspacing="0" cellpadding="3" class="headercontent">
            <tr><td class="header"><?php

            echo $GLOBALS["aref"].$groupdesc.$GLOBALS["atail"];
            if ($loginreq == 'Y')
            {
               $locked = true;
               ?>&nbsp;<?php echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsSecureIcon"],$GLOBALS["tPrivateOption"],0,'');
            }
            else
            {
               $locked = false;
            }
            if ($hovertitle != '') { echo '<br /><font size="-1">'.$hovertitle.'</font>'; }
            ?>
            </td></tr>
            <tr><td class="tablecontent">
            <ul>
            <?php

            if ($grouplink == '')
            {
               // We always list all menu items in the default site language; but if the user language is different we
               //    include any menu items in that language as well, sorted so that the user language items will be
               //    processed first.... then we filter out the default site language items when inspect the list.
               if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"])
               {
                  $strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname='".$groupname."' AND submenuvisible='Y' AND language='".$GLOBALS["gsLanguage"]."' ORDER BY subgrouporderid";
               }
               else
               {
                  $lOrder = '';
                  if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"])
                  {
                     $lOrder = ' DESC';
                  }
                  $strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname='".$groupname."' AND submenuvisible='Y' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY subgrouporderid,language".$lOrder;
               }
               $sresult = dbRetrieve($strQuery,true,0,0);
               $nSubgroupName = '';
               while ($srs = dbFetch($sresult))
               {
                  if (($GLOBALS["gsPrivateMenus"] == 'L') || ($srs["loginreq"] != 'Y') || ($EZ_SESSION_VARS["PasswordCookie"] != ''))
                  {
                     if ($srs["subgroupname"] != $nSubgroupName)
                     {
                        $nSubgroupName = $srs["subgroupname"];
                        $subgroupname  = $srs["subgroupname"];
                        $subgroupdesc  = $srs["subgroupdesc"];
                        $subgrouplink  = $srs["subgrouplink"];
                        $subhovertitle = $srs["hovertitle"];
                        $subopeninpage = $srs["openinpage"];
                        $subloginreq   = $srs["loginreq"];
                        $subusergroups = $srs["usergroups"];

                        if (($locked) || ($subloginreq == 'Y')) {
                           if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $subgrouplink = "loginreq.php"; }
                           // User is logged in, test against the list of valid user groups for this option
                           if ($subusergroups != '')
                           {
                              $Menu_Usergroups = explode(',',$subusergroups);
                              if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups))
                              {
                                 $subgrouplink = "loginreq2.php";
                              }
                           }
                           $subopeninpage = 'Y';
                        }
                        $levels["s"] = $subgroupname;
                        BuildSMLink ('s',false,$subgrouplink,$subopeninpage,$levels,'',$subhovertitle,$subgroupdesc);
                        echo '<li>'.$GLOBALS["aref"].$subgroupdesc.$GLOBALS["atail"];
                        if (($locked) || ($loginreq == 'Y'))
                        {
                           ?>&nbsp;<?php echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsSecureIcon"],$GLOBALS["tPrivateOption"],0,'');
                        }
                        if ($subhovertitle != '') { echo '<br />'.$subhovertitle; }
                     }
                  }
               }
               dbFreeResult($sresult);
               $levels["s"] = '';
               echo '</ul>';
            }
            ?>
            </ul>
            </td></tr>
            </table>
            <?php
         }
      }
   }
   dbFreeResult($mresult);
}


function BuildSMLink ($level,$topinc,$glink,$inpage,$levels,$headingclass,$hovertitle,$desc)
{
   global $EZ_SESSION_VARS;

   $GLOBALS["aref"] = $GLOBALS["atail"] = '';
   if ($glink != '')
   {
      $GLOBALS["atail"] = '</a>';
      if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True))
      {
         if ($inpage == 'Y')
         {
            if ((substr($glink,0,7) == 'http://') || (substr($glink,0,8) == 'https://'))
            {
               $GLOBALS["aref"] = '<a href="'.$glink.'" target="contents"';
            }
            else
            {
               $GLOBALS["aref"] = '<a href="'.BuildLink('module.php').'&link='.$glink.Appendlink($topinc,$levels).'" target="contents"';
            }
         }
         else
         {
            $GLOBALS["aref"] = '<a href="'.$glink.'" target="_blank"';
         }
      }
      else
      {
         if ($inpage == 'Y')
         {
            $GLOBALS["aref"] = '<a href="'.BuildLink('control.php').'&link='.$glink.Appendlink($topinc,$levels).'"';
         }
         else
         {
            $GLOBALS["aref"] = '<a href="'.$glink.'" target="_blank"';
         }
      }
   }
   else
   {
      if ($level != 't')
      {
         $GLOBALS["atail"] = '</a>';
         if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True))
         {
            $GLOBALS["aref"] = '<a href="'.BuildLink('showcontents.php').Appendlink($topinc,$levels).'" target="contents"';
         }
         else
         {
            $GLOBALS["aref"] = '<a href="'.BuildLink('control.php').Appendlink($topinc,$levels).'"';
         }
      }
   }
   if ($GLOBALS["aref"] != '')
   {

      if ($hovertitle != '')
      {
         $hovertext= $hovertitle;
      }
      else
      {
         $hovertext= $desc;
      }
      $GLOBALS["aref"] .= ' title="'.$hovertext.'" '.BuildLinkMouseOver($desc);

      if ($headingclass != '')
      {
         $GLOBALS["aref"] .= ' class="'.$headingclass.'"';
      }
      $GLOBALS["aref"] .= '>';
   }
} // function BuildSMLink()


function AppendLink ($topinc,$levels)
{
   $rlink = '';
   if ($topinc)
   {
      $rlink .= '&topgroupname='.$levels["t"];
   }
   if ($levels["m"] != '')
   {
      $rlink .= '&groupname='.$levels["m"];
   }
   if ($levels["s"] != '')
   {
      $rlink .= '&subgroupname='.$levels["s"];
   }
   return $rlink;
} // function AppendLink ()

?>

