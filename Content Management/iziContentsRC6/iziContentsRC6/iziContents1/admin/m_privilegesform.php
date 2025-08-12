<?php

/***************************************************************************

 m_privilegesform.php
 ---------------------
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

// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//    have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'privileges';
$validaccess = VerifyAdminLogin3("UsergroupName");

includeLanguageFiles('admin','privileges','adminmenu');


// If we've been passed the request from the usergroups list, then we
//    read the privilege data from the database for an edit request, or use
//    the default if this is an 'add new' request.
RenderFunctions();
if ($_GET["UsergroupName"] != '') {
   $_POST["UsergroupName"] = $_GET["UsergroupName"];
   $_POST["page"] = $_GET["page"];
   GetGlobalData('edit',$_GET["UsergroupName"]);
} else {
   if ($_POST["submitted"] != "yes") {
      $_POST["page"] = $_GET["page"];
      GetGlobalData('add',$GLOBALS["gsAdminPrivGroup"]);
   } else {
      GetGlobalData('fix',$_POST["UsergroupName"]);
   }
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AddPrivilege();
      Header("Location: ".BuildLink('m_privileges.php')."&page=".$_POST["page"]);
   }
}
frmPrivilegeForm();


function frmPrivilegeForm()
{
   global $_POST;

   if ($_POST["UsergroupName"] != '') {
      $n = 'usergroupname';
   } else {
      $n = 'usergroupcode';
   }
   adminformheader();
   adminformopen($n);
   adminformtitle(6,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(6); }
   ?>
   <tr class="tablecontent">
       <?php uFieldHeading($GLOBALS["tPrivilegesFor"]); ?>
       <td colspan="5" valign="top" class="content">
           <?php
           if ($_POST["UsergroupName"] != '') {
              ?>
              <input type="text" name="usergroupcode" size="32" value="<?php echo $GLOBALS["fsUsergroupCode"]; ?>" maxlength="32" disabled>
              <?php
           } else {
              ?>
              <input type="text" name="usergroupcode" size="32" value="<?php echo $GLOBALS["fsUsergroupCode"]; ?>" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>>
              <?php
           }
           ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php uFieldHeading($GLOBALS["tUsergroupname"]); ?>
       <td colspan="5" valign="top" class="content">
           <input type="text" name="usergroupname" size="48" value="<?php echo $GLOBALS["fsUsergroupName"]; ?>" maxlength="48"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
	<tr class=tablecontent>
	<td></td>
	<td><input class=submit type="button" value="Check all" onclick="checkAll();"></td>
	<td><input class=submit type="button" value="Uncheck all" onclick="uncheckAll();"></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
   <?php
   $cgroupname = '';
   if ($GLOBALS["groups"]) reset($GLOBALS["groups"]);
   $numofcheckboxes = 0;
   while (list($i,$val) = each($GLOBALS["groups"])) {
      if ($GLOBALS["groups"][$i]["groupname"] != $cgroupname) {
         $cgroupname = $GLOBALS["groups"][$i]["groupname"];
         adminsubheader(6,$GLOBALS["groups"][$i]["groupdesc"]);
      }
      ?>
      <tr class="tablecontent">
          <?php uFieldHeading($GLOBALS["groups"][$i]["functiondesc"]); ?>
          <td valign="top" class="content">
              <input type="checkbox" name="<?php echo $GLOBALS["groups"][$i]["functionname"]; ?>-view" value="<?php if ($GLOBALS["groups"][$i]["view"] == 'X') { echo 'X'; } else { echo 'Y'; } ?>" <?php if ($GLOBALS["groups"][$i]["view"] == 'Y') echo "checked"; ?> <?php if ($GLOBALS["groups"][$i]["view"] == 'X') { echo "disabled"; } else { echo $GLOBALS["fieldstatus"]; } ?>><?php echo $GLOBALS["tView"]; ?>
          </td>
          <td valign="top" class="content">
              <input type="checkbox" name="<?php echo $GLOBALS["groups"][$i]["functionname"]; ?>-edit" value="<?php if ($GLOBALS["groups"][$i]["edit"] == 'X') { echo 'X'; } else { echo 'Y'; } ?>" <?php if ($GLOBALS["groups"][$i]["edit"] == 'Y') echo "checked"; ?> <?php if ($GLOBALS["groups"][$i]["edit"] == 'X') { echo "disabled"; } else { echo $GLOBALS["fieldstatus"]; } ?>><?php echo $GLOBALS["tEdit"]; ?>
          </td>
          <td valign="top" class="content">
              <input type="checkbox" name="<?php echo $GLOBALS["groups"][$i]["functionname"]; ?>-add" value="<?php if ($GLOBALS["groups"][$i]["add"] == 'X') { echo 'X'; } else { echo 'Y'; } ?>" <?php if ($GLOBALS["groups"][$i]["add"] == 'Y') echo "checked"; ?> <?php if ($GLOBALS["groups"][$i]["add"] == 'X') { echo "disabled"; } else { echo $GLOBALS["fieldstatus"]; } ?>><?php echo $GLOBALS["tAdd"]; ?>
          </td>
          <td valign="top" class="content">
              <input type="checkbox" name="<?php echo $GLOBALS["groups"][$i]["functionname"]; ?>-delete" value="<?php if ($GLOBALS["groups"][$i]["delete"] == 'X') { echo 'X'; } else { echo 'Y'; } ?>" <?php if ($GLOBALS["groups"][$i]["delete"] == 'Y') echo "checked"; ?> <?php if ($GLOBALS["groups"][$i]["delete"] == 'X') { echo "disabled"; } else { echo $GLOBALS["fieldstatus"]; } ?>><?php echo $GLOBALS["tDelete"]; ?>
          </td>
          <td valign="top" class="content">
              <input type="checkbox" name="<?php echo $GLOBALS["groups"][$i]["functionname"]; ?>-translate" value="<?php if ($GLOBALS["groups"][$i]["translate"] == 'X') { echo 'X'; } else { echo 'Y'; } ?>" <?php if ($GLOBALS["groups"][$i]["translate"] == 'Y') echo "checked"; ?> <?php if ($GLOBALS["groups"][$i]["translate"] == 'X') { echo "disabled"; } else { echo $GLOBALS["fieldstatus"]; } ?>><?php echo $GLOBALS["tTranslate"]; ?>
          </td>
      </tr>
      <?php
	$numofcheckboxes = $numofcheckboxes + 5;
   }

   adminformsavebar(6,'m_privileges.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(6);
      ?><input type="hidden" name="UsergroupName" value="<?php echo $_POST["UsergroupName"]; ?>"><?php
   }
   adminformclose();
} // function frmPrivilegeForm()


function AddPrivilege()
{
   global $_POST, $EZ_SESSION_VARS;

   $sUsergroupCode = dbString($_POST["usergroupcode"]);
   $sUsergroupName = dbString($_POST["usergroupname"]);

   if ($sUsergroupCode == '') $sUsergroupCode = $_POST["UsergroupName"];

   reset($GLOBALS["groups"]);
   while (list($i,$val) = each($GLOBALS["groups"])) {
      $functioname       = $GLOBALS["groups"][$i]["functionname"];
      $functionview      = $GLOBALS["groups"][$i]["view"];
      $functionedit      = $GLOBALS["groups"][$i]["edit"];
      $functionadd       = $GLOBALS["groups"][$i]["add"];
      $functiondelete    = $GLOBALS["groups"][$i]["delete"];
      $functiontranslate = $GLOBALS["groups"][$i]["translate"];
      if ($functionview != 'X') $functionview = $_POST[$functioname.'-view'];
      if ($functionedit != 'X') $functionedit = $_POST[$functioname.'-edit'];
      if ($functionadd != 'X') $functionadd = $_POST[$functioname.'-add'];
      if ($functiondelete != 'X') $functiondelete = $_POST[$functioname.'-delete'];
      if ($functiontranslate != 'X') $functiontranslate = $_POST[$functioname.'-translate'];
      if ($_POST["UsergroupName"] != "") {
         $strQuery = "UPDATE ".$GLOBALS["eztbPrivileges"]." SET usergroupname='".$sUsergroupCode."', accessview='".$functionview."', accessedit='".$functionedit."', accessadd='".$functionadd."', accessdelete='".$functiondelete."', accesstranslate='".$functiontranslate."' WHERE functionname='".$functioname."' AND usergroupname='".$_POST["UsergroupName"]."'";
      } else {
         $strQuery = "INSERT INTO ".$GLOBALS["eztbPrivileges"]." VALUES('', '".$sUsergroupCode."', '".$functioname."', '".$functionview."', '".$functionedit."', '".$functionadd."', '".$functiondelete."', '".$functiontranslate."')";
      }
      $result = dbExecute($strQuery,true);
   }

   if ($_POST["UsergroupName"] != "") {
      if ($_POST["UsergroupName"] != $sUsergroupName) {
         $strQuery = "UPDATE ".$GLOBALS["eztbUsergroups"]." SET usergroupdesc='".$sUsergroupName."' WHERE language='".$GLOBALS["gsLanguage"]."' AND usergroupname='".$_POST["UsergroupName"]."'";
         $result = dbExecute($strQuery,true);
      }
   } else {
      $strQuery = "SELECT languagecode FROM ".$GLOBALS["eztbLanguages"]." WHERE enabled='Y'";
      $lresult = dbRetrieve($strQuery,true,0,0);
      while ($lrs = dbFetch($lresult)) {
         $strQuery = "INSERT INTO ".$GLOBALS["eztbUsergroups"]." VALUES('', '".$sUsergroupName."', '".$sUsergroupCode."', '".$lrs["languagecode"]."', '".$EZ_SESSION_VARS["UserID"]."')";
         $result = dbExecute($strQuery,true);
      }
      dbFreeResult($lresult);
   }

   dbCommit();
} // function AddPrivilege()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($_POST["UsergroupName"] == '') {
      if ($_POST["usergroupcode"] == "") { $GLOBALS["strErrors"][] = $GLOBALS["eNoUsergroupCode"];
      } else {
         $strQuery="SELECT * FROM ".$GLOBALS["eztbUsergroups"]." WHERE usergroupname='".$_POST["usergroupcode"]."'";
         $sresult = dbRetrieve($strQuery,true,0,0);
         $sRecCount = dbRowsReturned($sresult);
         dbFreeResult($sresult);
         if ($sRecCount <> 0) { $GLOBALS["strErrors"][] = $GLOBALS["eCodeInUse"]; }
      }
   }
   if ($_POST["usergroupname"] == "") { $GLOBALS["strErrors"][] = $GLOBALS["eNoUsergroupName"]; }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function GetGlobalData($type,$UsergroupName)
{
   global $_POST;

   if ($type == 'edit') {
      $strQuery = "SELECT * FROM ".$GLOBALS["eztbUsergroups"]." WHERE usergroupname='".$UsergroupName."' AND language='".$GLOBALS["gsLanguage"]."'";
      $result = dbRetrieve($strQuery,true,0,0);
      $rs = dbFetch($result);
      $GLOBALS["fsUsergroupCode"] = $rs["usergroupname"];
      $GLOBALS["fsUsergroupName"] = $rs["usergroupdesc"];
      if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
         $GLOBALS["specialedit"] = True;
         $GLOBALS["fieldstatus"] = '';
      }
      dbFreeResult($result);
   } elseif ($type == 'add') {
      $GLOBALS["fsUsergroupCode"] = '';
      $GLOBALS["fsUsergroupName"] = '';
   } elseif ($type == 'fix') {
      $GLOBALS["fsUsergroupCode"] = $_POST["usergroupcode"];
      $GLOBALS["fsUsergroupName"] = $_POST["usergroupname"];
   }

   if ($UsergroupName == '') $UsergroupName = 'administrator';
   $strQuery="SELECT * FROM ".$GLOBALS["eztbPrivileges"]." WHERE usergroupname='".$UsergroupName."'";
   $result = dbRetrieve($strQuery,true,0,0);
   while ($rs = dbFetch($result)) {
      reset($GLOBALS["groups"]);
      while (list($i,$val) = each($GLOBALS["groups"])) {
         if ($GLOBALS["groups"][$i]["functionname"] == $rs["functionname"]) {
            if ($type != 'fix') {
               $GLOBALS["groups"][$i]["view"]      = $rs["accessview"];
               $GLOBALS["groups"][$i]["edit"]      = $rs["accessedit"];
               $GLOBALS["groups"][$i]["add"]       = $rs["accessadd"];
               $GLOBALS["groups"][$i]["delete"]    = $rs["accessdelete"];
               $GLOBALS["groups"][$i]["translate"] = $rs["accesstranslate"];
            } else {
               $GLOBALS["groups"][$i]["view"]   = $_POST[$rs["functionname"].'-view'];
               if ($rs["accessview"] == 'X') $GLOBALS["groups"][$i]["view"] = 'X';
               $GLOBALS["groups"][$i]["edit"]   = $_POST[$rs["functionname"].'-edit'];
               if ($rs["accessedit"] == 'X') $GLOBALS["groups"][$i]["edit"] = 'X';
               $GLOBALS["groups"][$i]["add"]    = $_POST[$rs["functionname"].'-add'];
               if ($rs["accessadd"] == 'X') $GLOBALS["groups"][$i]["add"] = 'X';
               $GLOBALS["groups"][$i]["delete"] = $_POST[$rs["functionname"].'-delete'];;
               if ($rs["accessdelete"] == 'X') $GLOBALS["groups"][$i]["delete"] = 'X';
               $GLOBALS["groups"][$i]["translate"] = $_POST[$rs["functionname"].'-translate'];;
               if ($rs["accesstranslate"] == 'X') $GLOBALS["groups"][$i]["translate"] = 'X';
            }
         }
      }
	  
   }
   dbFreeResult($result);

} // function GetGlobalData()


function RenderFunctions()
{
   $strQuery = "SELECT g.groupname as groupname,f.functionname as functionname FROM ".$GLOBALS["eztbFunctions"]." f, ".$GLOBALS["eztbFunctiongroups"]." g WHERE g.groupname=f.groupname ORDER BY g.grouporderid,f.functionorderid";
   $result = dbRetrieve($strQuery,true,0,0);
   $i = 0;
   while ($rs = dbFetch($result)) {
      $GLOBALS["groups"][$i]["groupname"]    = $rs["groupname"];
      $GLOBALS["groups"][$i]["groupdesc"]    = $GLOBALS["tg".$GLOBALS["groups"][$i]["groupname"]];
      $GLOBALS["groups"][$i]["functionname"] = $rs["functionname"];
      $GLOBALS["groups"][$i]["functiondesc"] = $GLOBALS["tf".$GLOBALS["groups"][$i]["functionname"]];
      $GLOBALS["groups"][$i]["view"]         = '';
      $GLOBALS["groups"][$i]["edit"]         = '';
      $GLOBALS["groups"][$i]["add"]          = '';
      $GLOBALS["groups"][$i]["delete"]       = '';
      $GLOBALS["groups"][$i]["translate"]    = '';
      $i++;
   }
   dbFreeResult($result);
} // function RenderFunctions()


function uFieldHeading($field)
{
   ?>
   <td valign="top" class="content">
       <b><?php echo $field; ?>:</b>
   </td>
   <?php
} // function uFieldHeading()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function checkAll() {
for (var j = 1; j <= document.MaintForm.length; j++) {
	box = eval("document.MaintForm.elements[" + j +"]");
	type = box.type;
	dis = box.disabled;
		if (type == "checkbox" && dis == false) {
			if (box.checked == false) { box.checked = true; }
   		}
}
}

function uncheckAll() {
for (var j = 1; j <= document.MaintForm.length; j++) {
	box = eval("document.MaintForm.elements[" + j +"]");
	type = box.type;
	dis = box.disabled;
		if (type == "checkbox" && dis == false) {
			if (box.checked == true) { box.checked = false; }
   		}
}
}

//  End -->
</script>