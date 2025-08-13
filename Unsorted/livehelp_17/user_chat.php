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
?>
<html>
<?
if($use_flush == "no"){
print "<META HTTP-EQUIV=\"refresh\" content=\"6;URL=user_chat.php\">";
}
?>
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 background=images/mid_bk.gif>
<? 

$timeof = 0;
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
}
</SCRIPT>

<? 

  $identity = $REMOTE_ADDR . $HTTP_USER_AGENT;
  $identity = ereg_replace(" ","",$identity);
  $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
  $isnamed = $people[isnamed];


if( ($needname == "YES") && ($isnamed != "Y")){
print $opening;
$mydatabase->close_connect();
exit;
}

if($myid == ""){
  $identity = $REMOTE_ADDR . $HTTP_USER_AGENT;
  $identity = ereg_replace(" ","",$identity);
  $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
}

$abort_counter = 0;
$query = "SELECT * FROM livehelp_messages WHERE saidto='$myid' AND timeof>'$timeof'";	
$messages = $mydatabase->select($query);
if(count($messages) == 0){
?>
<small>(<? print date("h:i:s"); ?> )</small><font color="AAAAAA">Contacting Operator...</font><br>
<?
if( ($use_flush != "no") && ($offset != 2)){ 
flush(); 
}

}
$abort_counter = 1;
if($use_flush == "no"){
$abort_counter_end = 2;
} else {
$abort_counter_end = 25;
}

$query = "UPDATE livehelp_users SET status='chat' WHERE user_id='$myid'";
$mydatabase->sql_query($query);
        
while($abort_counter != $abort_counter_end)
{
	$abort_counter++;
	showmessages($myid,$channel);
        if( ($offset != "") || ($use_flush == "no")){
	 $offset = "";
	 ?><SCRIPT>up(); setTimeout('up()',9);</SCRIPT><?
     	 flush(); 	
	}
	if($use_flush != "no"){ sleep(2); }	
	$query = "SELECT * FROM livehelp_users WHERE user_id='$myid' AND status='chat'";
        $alive = $mydatabase->select($query);
        if( count($alive) == 0){         	
            $query = "DELETE FROM livehelp_visit_track WHERE id='$myid' ";
            $mydatabase->sql_query($query); 
            $query = "DELETE FROM livehelp_users WHERE user_id='$myid' ";
            $mydatabase->sql_query($query);  
            $query = "DELETE FROM livehelp_channels WHERE user_id='$myid' ";
            $mydatabase->sql_query($query);      	
        	
        	?><b><font color=990000>Session CLOSED!!</font></b><SCRIPT>up()</SCRIPT><?        	
        	$abort_counter = 99999;
        	?>
        	<SCRIPT>
        	function redirectme(){
                  window.location.replace("leavemessage.php");
                }
                setTimeOut("redirectme()",2000);
        	</SCRIPT>
        	<?
        	$mydatabase->close_connect();
        	exit; }
}
if($use_flush != "no"){
  ?>
  <br><b>Refreshing...</b><br><br>
  <br>
  <br>  
  <SCRIPT>
  up()
  function reloadit(){
    window.location.replace("user_chat.php?offset=2");	
  }
  setTimeout('reloadit()', 999);
  </SCRIPT>
  <?
}
$mydatabase->close_connect();
exit;

function showmessages($myid,$channel){
   global $use_flush,$timeof,$mydatabase,$REMOTE_ADDR;

$query = "SELECT * FROM livehelp_messages WHERE (saidto='$myid' OR channel='$channel') AND timeof>'$timeof' ORDER by timeof ";	
$messages = $mydatabase->select($query);
	if ( count($messages) != 0) 
	{
		for($i=0;$i<count($messages);$i++){
		  $row = $messages[$i];
	          $message = $row[message];
	          $timeof = $row[timeof];
	          $saidfrom = $row[saidfrom];
                  $saidto = $row[saidto];
	          		
		  if($user_id == $myid){ $txtcolor = "000088"; } else { $txtcolor = "008800"; }
                  
                  // this is in a seprate query because left join queries take too long..
	          $query = "SELECT * FROM livehelp_users WHERE user_id='$saidfrom'";

	          $username_s = $mydatabase->select($query);
	          $username_a = $username_s[0];
	          $username = $username_a[username];
	          if($username == $REMOTE_ADDR){ $username = "You"; }
	          $abort_counter = 0;
	          ?>
                  <small>(<? print date("h:i:s"); ?> )</small> <?= $username ?>: </td><td><font color="#<?= $txtcolor ?>"><?= $message?> </font><br>
                  <?		  
		}		
		if( ($use_flush != "no") && ($offset != 2)){    
		  ?><SCRIPT>up()</SCRIPT><?
		    flush(); }
    }

}
$mydatabase->close_connect();
?>
</body>
</html>