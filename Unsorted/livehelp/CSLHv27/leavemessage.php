<?
//****************************************************************************************/
//  Crafty Syntax Live Help (CSLH)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/
// Copyright (C) 2003  Eric Gerdes 
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program in a file named LICENSE.txt .
// if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------  
// MODIFICATIONS: 
// ---------------------------------------------------------  
// [ Programmers who change this code should cause the  ]
// [ modified changes here and the date of any change.  ]
//======================================================================================
//****************************************************************************************/
require("globals.php");
include("config.php");
function whatdep($id){
  global $mydatabase;
  $query = "SELECT * FROM livehelp_departments WHERE recno='$id'";
  $dat = $mydatabase->select($query);
  $myrow = $dat[0];
  return  $myrow[nameof];
}
// get department information...
if( ($department!=0) && ($department!="")){ $where = " WHERE recno='$department' "; }
$query = "SELECT * FROM livehelp_departments $where ";
$data_d = $mydatabase->select($query);  
$department_a = $data_d[0];
$department = $department_a[recno];
$messageemail = $department_a[messageemail];

if($identity == ""){
 if($REMOTE_ADDR == ""){
    $REMOTE_ADDR = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
 }
 if($HTTP_USER_AGENT == ""){
    $HTTP_USER_AGENT = $HTTP_SERVER_VARS["HTTP_USER_AGENT"]; 
 }
 $identity = $REMOTE_ADDR . $HTTP_USER_AGENT . $rand_id;
 $identity = ereg_replace(" ","",$identity);
}
sleep(1);


$query = "UPDATE livehelp_users set status='message' WHERE identity='$identity'";	
$mydatabase->sql_query($query);

if($action == "send"){
  $errors = "";
  if($email == ""){
    $errors = "<li>You did not provide a valid e-mail address";
  }
  if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$email)){
      // valid email
  } else {
      $errors .= "<li>You did not provide a valid e-mail address";
  }
  if($comments == ""){
    $errors = "<li>You did not provide any message to send.";
  }
  if($errors == ""){
   $heading .= "\nReferer : $referer \n ";
   $heading .= "Department : " . whatdep($department) ." \n ";
   $heading .= "identity : $identity \n ";   
   $heading .= "=======================================================\n ";      
   $comments = $heading . " $comments\n";
   mail("$messageemail","CSLH Contact Submission","$comments","From: $email");
   ?>
   <body bgcolor=FFFFC0><center><br><br>
   <h2><font color=009900 size=+2>THANK YOU</font></h2>
     <hr>
  message has been sent...
  <br><br>
  <?
  } else {
   $action = "";	
  }
} 

if($action == ""){
if($errors != ""){
	print "<font color=990000>$errors</font><br>";
	}
// if the operator requested anyone. Turn it off for them..
$query = "UPDATE livehelp_users set status='Visit' Where status='request'";
$mydatabase->sql_query($query);
	
	
?>
<HTML>
<HEAD>
<META NAME="robots" CONTENT="noindex">
<TITLE>Leave a Message</TITLE>

<STYLE>
        BODY {
                                background-color: #FFFFC0;
                        }

        .norm {font-size:10pt; color:#333333; font-family:Arial, Helvetica; font-weight: normal;}
        .normBold {font-size:10pt; color:#333333; font-family:Arial, Helvetica; font-weight: bold;}
        .med {font-size:9pt; color:#333333; font-family:Arial, Helvetica;}
        .sm {font-size:8pt; color:#333333; font-family:Arial, Helvetica; font-weight: normal;}
        .butMargin { margin-top:7px; margin-bottom:7px; }
        .wh { color:#000077; font-size:10pt; font-family:Arial, Helvetica; }
        .butMargin { margin-top:10px; }
        .bold { font-weight: bold; }
        .disableField { background-color:rgb( 245,245,245 ); color: rgb( 51,51,51 ); }
        .on{ display:inline; }
        .off{ display:none; }
</STYLE>
</HEAD>
<BODY>
<TABLE ALIGN="center" BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH=100% HEIGHT=100%>
        <TR>
                <TD>

<!--**************************** BEGIN FORM TAGS ****************************-->

<FORM NAME="form" METHOD="POST" ACTION="leavemessage.php">
<input type=hidden name=referer value="<?= $referer ?>" >
<input type=hidden name=department value="<?= $department ?>" >
<INPUT TYPE="hidden" NAME="action" VALUE="send">
<TABLE BGCOLOR="#000000" ALIGN="center" BORDER="0" CELLPADDING="1" CELLSPACING="0">
        <TR>
                <TD ALIGN="center">

<TABLE BGCOLOR="#ffffff" ALIGN="center" BORDER="0" CELLPADDING="0" CELLSPACING="0">
        <TR>
                <TD BGCOLOR="#f5f5f5" ALIGN="center">
<DIV CLASS="on">
        <TABLE BGCOLOR="#f5f5f5" ALIGN="center" BORDER="0" CELLPADDING="3" CELLSPACING="3">

                <TR ALIGN="center">
                        <TD COLSPAN=2 BGCOLOR="#DDDDDD" CLASS="normBold">
                                <SPAN CLASS="wh"><?= $lang_leave_mess ?></SPAN>
                        </TD>
                </TR>

                <TR>
                <TD COLSPAN=2 HEIGHT=6><?= $lang_leave_text ?></TD>
                </TR>

                <TR ALIGN="center">
                        <TD CLASS="normBold">
                                
                        </TD>
                        <TD>
                                <TEXTAREA NAME="comments" ROWS=8 COLS=38 maxlength=300 WRAP="soft" onFocus="self.status='REQUIRED: Describe your problem in detail...'" onBlur="self.status=''" TITLE="REQUIRED: Describe your problem in detail..."><?= $comments ?></TEXTAREA><br>
                                <b>From E-mail:</b><input type=text size=30 name=email>
                        </TD>
                </TR>

                <TR ALIGN="center">
                        <TD COLSPAN=2 CLASS="smRed">
                               <INPUT TYPE="submit" VALUE=" Send Message " NAME="submit_button" CLASS="butMargin">
                        </TD>
                </TR>

        </TABLE>
</DIV>



                </TD>
        </TR>
</TABLE>

                </TD>
        </TR>
</TABLE>
                </TD>
        </TR>
</TABLE>
</form></body></html>
	
	
<? } 

$mydatabase->close_connect();
?>