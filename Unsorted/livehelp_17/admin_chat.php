<?
//****************************************************************************************/
//  Crafty Syntax Live Help (CS Live Help)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// CS LIVE HELP http://www.craftysyntax.com/livehelp/
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


include("config.php");
include("user_access.php");
$username = $username;
checkuser();

$timeof = date("YmdHis");
$prev = mktime ( date("H"), date("i")-30, date("s"), date("m"), date("d"), date("Y") );
$oldtime = date("YmdHis",$prev);
 
if(($use_flush == "no") || ($offset != "")){ $timeof = $oldtime; }
 
if($myid == ""){
  // get the if of this user.. 
  $query = "SELECT * FROM livehelp_users WHERE username='$username'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
}

?>
<style>
body, td
{
	font-family: Verdana, Arial;
	font-size:13px;
	color:black;
}
a,a:visited,a:hover
{
	color:black;
}
a.jsnavi, a.jsnavi:visited
{
	font-family: Verdana, Arial;
	font-size:11px;
	color:#6060ff;
	font-weight: bold;
	text-decoration: none;
}
.jsnavi:hover
{
	font-family: Verdana, Arial;
	font-size:11px;
	color: blue;
	font-weight: bold;
	text-decoration: none;
}

small
{
	font-size:11px;
	color:#555555;
}
.topic
{
	font-size:16px;
	font-weight:bold;
	color:#555555;
}
</style>

<SCRIPT>
function up(){
  scroll(1,10000000);
  if(skipfocus == 0){
    window.parent.bottomof.shouldifocus();
  }
}
<? if( ($use_flush == "no") || ($offset != "") ){ ?>
skipfocus = 1;
setTimeout('skipfocus=0;',2999);
<? } else { ?>
skipfocus = 0;
<? } ?>
</SCRIPT>

<?
if($use_flush == "no"){
print "<META HTTP-EQUIV=\"refresh\" content=\"6;URL=admin_chat.php\">";
}
?>
<body bgcolor=FFFFEE>
<?

// lets do a little cleanning ..  
$query = "SELECT * FROM livehelp_users WHERE isoperator='N' AND lastaction<$oldtime";
$old_people = $mydatabase->select($query);
$query = "DELETE FROM livehelp_messages WHERE timeof<'$timeof'";
$mydatabase->sql_query($query); 
for($i=0;$i< count($old_people); $i++){
  $people_row = $old_people[$i];
  $query = "DELETE FROM livehelp_visit_track WHERE id='$people_row[user_id]' ";
  $mydatabase->sql_query($query); 
  $query = "DELETE FROM livehelp_users WHERE user_id='$people_row[user_id]' ";
  $mydatabase->sql_query($query);  
  $query = "DELETE FROM livehelp_channels WHERE user_id='$people_row[user_id]' ";
  $mydatabase->sql_query($query);  
}
$abort_counter = 1;
if($use_flush == "no"){
$abort_counter_end = 2;
} else {
$abort_counter_end = 25;
}


if( ($use_flush != "no") && ($offset == "") ){ flush(); }
while($abort_counter != $abort_counter_end)
{
	showmessages($channel,$myid);
	if($use_flush != "no"){ sleep(2); }
	if( ($offset != "") || ($use_flush == "no")){
	 $offset = "";
	 ?><SCRIPT>up(); setTimeout('up()',9);</SCRIPT><?
     	 flush(); 	
	}
	$abort_counter++;	
}
if($use_flush != "no"){
  ?>
  <br><b>Refreshing...</b><br><br>
  <br>
  <br>  
  <SCRIPT>
  skipfocus = 1;
  up()
  function reloadit(){
    window.location.replace("admin_chat.php?offset=2");	
  }
  setTimeout('reloadit()', 999);
  </SCRIPT>
  <?
}
$mydatabase->close_connect();
exit;

function showmessages($channel,$myid){
   global $abort_counter,$timeof,$mydatabase,$use_flush;
	
$query = "SELECT livehelp_messages.*,livehelp_operator_channels.bgcolor FROM livehelp_messages,livehelp_operator_channels WHERE livehelp_operator_channels.user_id='$myid' AND livehelp_messages.channel=livehelp_operator_channels.channel AND timeof>'$timeof' ORDER by timeof";	

$messages = $mydatabase->select($query);
	if ( count($messages) != 0) 
	{
		for($i=0;$i<count($messages);$i++){
		  $row = $messages[$i];
	          $message = $row[message];
	          $timeof = $row[timeof];	          
	          $saidfrom = $row[saidfrom];			
	          $saidto = $row[saidto];
	          $txtcolor = $row[bgcolor];	  

	          $query = "SELECT * FROM livehelp_users WHERE user_id='$saidfrom'";
	          $username_f = $mydatabase->select($query);
	          $username_a = $username_f[0];
	          $from = $username_a[username];
	          $whowhat = "$from :"; 
	          
	          if($saidto != 0){
	           $query = "SELECT * FROM livehelp_users WHERE user_id='$saidto'";
	           $username_f = $mydatabase->select($query);
	           $username_a = $username_f[0];
	           $to = $username_a[username];          
	           $whowhat = " $from said to <font color=\"#$txtcolor\"><b>$to</b></font>";  
	          }
	        
	          $abort_counter = 0;
	          ?><?= $whowhat ?>:</td><td width=99%><font color="#<?= $txtcolor ?>"><?= $message?> </font><br><?		  
		}
     if( ($use_flush != "no") && ($offset != 2)){  
     	   ?><SCRIPT>up()</SCRIPT><?
     	   flush(); 
       }
	}

}
?>
