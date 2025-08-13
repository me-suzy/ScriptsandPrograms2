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
?>
<html>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 background=images/mid_bk.gif>
<? 
if($identity == ""){
 if($REMOTE_ADDR == ""){
    $REMOTE_ADDR = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
 }
 if($HTTP_USER_AGENT == ""){
    $HTTP_USER_AGENT = $HTTP_SERVER_VARS["HTTP_USER_AGENT"]; 
 }
 $identity = $REMOTE_ADDR . $HTTP_USER_AGENT . $rand_id;
 $referer = $HTTP_REFERER;
 $identity = ereg_replace(" ","",$identity);
}
  $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
  $isnamed = $people[isnamed];

if($myid == ""){
  $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
}
  $mytimeof = date("YmdHis");
  $query = "UPDATE livehelp_users set lastaction='$mytimeof' WHERE user_id='$myid' ";
  $mydatabase->sql_query($query);

if($clear == "now"){
  // get the timestamp of the last message sent on this channel.
  $query = "SELECT * FROM livehelp_messages WHERE saidto='$myid' ORDER BY timeof DESC";	
  $messages = $mydatabase->select($query);
  $message = $messages[0];
  $timeof = $message[timeof] - 2;  
  $offset = $message[timeof] - 2; 
  $starttimeof =  $message[timeof] -2; 
} 

if($starttimeof != ""){ 
   $timeof = $starttimeof;
   $offset = $starttimeof;      
} else {   
   $timeof = $offset;  
}

if($offset == ""){ $offset = 2; }

?>
<SCRIPT>
function up(){
  scroll(1,10000000);
}
function openwindow(mypage,myname) {
window.open(mypage,myname,'toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes');
}

</SCRIPT>

<? 

$abort_counter = 0;
$query = "SELECT * FROM livehelp_messages WHERE saidto='$myid'";	
$messages = $mydatabase->select($query);

// get department information...
if($department!=""){ $where = " WHERE recno=$department "; }
$query = "SELECT * FROM livehelp_departments $where ";
$data_d = $mydatabase->select($query);  
$department_a = $data_d[0];

if( ($department_a[requirename] == "Y") && ($isnamed != "Y")){

// check for forced messages.. 
print "<blockquote>";
if(count($messages) == 0){
print $department_a[opening];
} else {
  showmessages($myid,$channel,1);	  
  $query = "DELETE FROM livehelp_messages WHERE saidto='$myid'";	
  $mydatabase->sql_query($query);
}
?>
<FORM action=livehelp.php METHOD=POST TARGET=_top>
<input type=hidden name=department value=<?= $department?>>
<input type=hidden name=makenamed value=Y>
<b>Name:</b><input type=text size=30 name=newusername><br>
<!--<b>E-mail (optional):</b><input type=text size=30 name=newemail>-->
<br><input type=submit value=BEGIN>
</FORM>
<?
print "</blockquote>";
$mydatabase->close_connect();
exit;
}

if($use_flush == "no"){
print "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=user_chat.php?starttimeof=$starttimeof\">";
}

if(count($messages) == 0){
?>
<img src=connecting.gif><br>
<?
sleep(1);
$query = "UPDATE livehelp_users SET status='chat' WHERE user_id='$myid'";
$mydatabase->sql_query($query);

if( ($use_flush != "no") && ($offset < 200)){ 
// load the buffer 
for ($i=0; $i<300; $i++) print ("  "); 
print ("\n");
if (function_exists('ob_flush')) {
ob_flush();
}
flush(); 

// wait till someone answers the call...
$timeout = 0;
 while( ($timeout != 60) && (count($messages) == 0) ){
   $timeout++;
   sleep(1);	
   $query = "SELECT * FROM livehelp_messages WHERE saidto='$myid' AND timeof>'$timeof'";	
   $messages = $mydatabase->select($query);
   if(count($messages) != 0){
   ?>
    <SCRIPT>
    up()
    window.location.replace("user_chat.php?offset=<?= $offset?>&starttimeof=<?=$starttimeof?>");
    </SCRIPT>
    <?
    exit;
    }
  }
if($timeout > 59){
  ?>
    <SCRIPT>
    up()
    window.parent.location.replace("livehelp.php?doubleframe=yes&page=offline.php&department=<?=$department?>&tab=1");       
    </SCRIPT>
    <?
    exit;	
 }
}

}

$abort_counter = 1;
if($use_flush == "no"){
$abort_counter_end = 2;
} else {
$abort_counter_end = 25;
}
// load javascript.
if($printit != "Y"){
?>
<SCRIPT LANGUAGE="JavaScript" SRC="javascript/xLayer.js"></SCRIPT> 
<SCRIPT LANGUAGE="JavaScript" SRC="javascript/xBrowser.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="javascript/staticMenu.js"></SCRIPT> 
<SCRIPT LANGUAGE="JavaScript"> 
NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;

function start() 
{       	
	if (IE4){
	  docWidth = document.body.clientWidth;
	} else {
	  docWidth = window.innerWidth;
	}
	myxvar = docWidth - 200;
	if (myxvar < 5){ 
	  myxvar = 250;
	}
	CreateStaticMenu("MenuDiv",  myxvar, 1);  

} 

function moveto(myblock,x,y)
{  
	myblock.xpos = x
	myblock.ypos = y
	myblock.left = myblock.xpos
	myblock.top = myblock.ypos
}

function expandit() {
  window.parent.resizeTo(window.screen.availWidth - 50,      
  window.screen.availHeight - 50); 
  if(IE4){
    // everything should be ok.. 
  } else {    
    setTimeout('refreshnow()',900);
  }
}
function refreshnow(){
 window.location.replace("user_chat.php?offset=<?=$offset?>&starttimeof=<?=$starttimeof?>");	
}
function printit(){
   url = 'user_chat.php?offset=2&printit=Y';
   window.open(url, 'chat54087', 'width=572,height=320,menubar=yes,scrollbars=1,resizable=1');	
}
</SCRIPT>

<DIV id="MenuDiv" STYLE="position:absolute;left:300;top:10;width:100;"> 
<table width=200 bgcolor=FFFFEE><tr><td valign=top><a href=javascript:expandit()><img src=images/max.gif width=25 height=25 border=0></a>&nbsp;&nbsp;<a href=javascript:printit()><img src=images/print.gif width=25 height=25 border=0></a>&nbsp;&nbsp;<a href=user_chat.php?offset=<?= $offset ?>&starttimeof=<?= $starttimeof ?> ><img src=images/refresh.gif width=25 height=25 border=0></a>&nbsp;&nbsp;<a href=user_chat.php?clear=now><img src=images/clear.gif width=25 height=25 border=0></a>&nbsp;&nbsp;<a href=livehelp.php?action=leave TARGET=_top><img src=images/exit.gif width=25 height=25 border=0></a></td></tr></table>       
</DIV>

<SCRIPT>setTimeout('start()',900);</SCRIPT>
<?
}
if($printit == "Y"){ $use_flush = "no"; }

while($abort_counter != $abort_counter_end)
{
	$abort_counter++;
	showmessages($myid,$channel,0);
        if( ($offset != "") || ($use_flush == "no")){
	 ?><SCRIPT>up(); setTimeout('up()',9);</SCRIPT><?
	 // load the buffer 
for ($i=0; $i<300; $i++) print ("  "); 
print ("\n");
         if (function_exists('ob_flush')) {
ob_flush();
}
     	 flush();
 	
	}
	if($use_flush != "no"){ sleep(2); }	
	$query = "SELECT * FROM livehelp_users WHERE user_id='$myid' AND status='chat'";
        $alive = $mydatabase->select($query);
        if( count($alive) == 0){                        	        	
        	?><b><font color=990000>Session CLOSED!!</font></b><SCRIPT>up()</SCRIPT><?        	
        	$abort_counter = 99999;
        	?>
        	<SCRIPT>
        	function redirectme(){
                  window.location.replace("leavemessage.php");
                }
                window.parent.close();
        	</SCRIPT>
        	<?
        	$mydatabase->close_connect();
        	exit; }
}
if($printit == "Y"){
  print "<br><br><font color=999999>Printing Page..</font><br>";
  print "<SCRIPT>up(); setTimeout('window.print()',300);</SCRIPT>";
  print "<a href=javascript:window.close()>Close window</a>";
  exit;	
}
if($use_flush != "no"){
  ?>
  <br><b>Refreshing...</b><br><br>
  <br>
  <br>  
  <SCRIPT>
  up()
  refreshnow();
  </SCRIPT>
  <?= $abort_counter_end ?>
  <?
}
$mydatabase->close_connect();
exit;

function showmessages($myid,$channel,$skipname){
   global $use_flush,$timeof,$mydatabase,$REMOTE_ADDR;

$query = "SELECT * FROM livehelp_messages WHERE (saidto='$myid' OR channel='$channel') AND timeof>'$timeof' ORDER by timeof ";	
$messages = $mydatabase->select($query);

	if ( count($messages) != 0) 
	{
		for($i=0;$i<count($messages);$i++){
		  $row = $messages[$i];
	          $message = $row[message];
	          $timeof = $row[timeof];
                  $id_num = $row[id_num];
 	          $saidfrom = $row[saidfrom];
                  $saidto = $row[saidto];                  
	          		                  
                  // this is in a seprate query because left join queries take too long..
	          $query = "SELECT * FROM livehelp_users WHERE user_id='$saidfrom'";

	          $username_s = $mydatabase->select($query);
	          $username_a = $username_s[0];
	          $username = $username_a[username];
	          if($username == $REMOTE_ADDR){ $username = "You"; }
	          $abort_counter = 0;
	          if ($skipname != 1){?>
                  <?= $username ?>:
                  <?}
                  // if we are sending javascript we only want to send it once.
                  if( ereg("\[SCRIPT\]",$message) ){ 
                    	$message = ereg_replace("\[SCRIPT\]","<SCRIPT>",$message);
                    	$message = ereg_replace("\[/SCRIPT\]","</SCRIPT>",$message);
                      $query = "UPDATE livehelp_messages set message='PUSHED URL' Where id_num='$id_num' ";
                      $mydatabase->sql_query($query);
                  }     
                  if($saidfrom == $myid){ print "<font color=000077>"; } else { print "<font color=007700><b>"; } 
                  ?>            
                  <?= $message?> </font></b><br>
                  <?	
                  $mytimeof = date("YmdHis");
                  $query = "UPDATE livehelp_users set lastaction='$mytimeof' WHERE user_id='$myid' ";
                  $mydatabase->sql_query($query);	  
		}		
		if( ($use_flush != "no") && ($offset != 2)){    
		  ?><SCRIPT>up()</SCRIPT><?
	 // load the buffer 
for ($i=0; $i<300; $i++) print ("  "); 
print ("\n");
   if (function_exists('ob_flush')) {
      ob_flush();
   }
		    flush();
		      }
    }

}
$mydatabase->close_connect();
?>
</body>
</html>