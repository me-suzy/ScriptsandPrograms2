<?php
//Friday July 6, 2005
//Revised by JF
//Revision Number 8

include("checksession.php");
include_once("config.php");
include_once "./includes/classes.php";
include_once "./includes/settings.php";

mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
mysql_select_db(DB_DBNAME);
setcookie("record1", isset($_GET['record']) ? $_GET['record'] : '' ,time()+1209600);
?><head>
<link href="style.css" rel="stylesheet" type="text/css">
<title>View Details</title>
<p><img src="images/view-details-customer-support.jpg" alt="Help Desk Scheduling Software " width="578" height="208" border="0" usemap="#Map2"> 
  <map name="Map2">
    <area shape="rect" coords="0,152,64,206" href="reportproblem.php">
    <area shape="rect" coords="85,153,161,190" href="helpDeskAccessAllCalls.php">
    <area shape="rect" coords="169,149,279,198" href="DataAccessSearch.php">
    <area shape="rect" coords="291,149,378,202" href="ocm-first.php">
    <area shape="rect" coords="379,150,448,203" href="search.php">
    <area shape="rect" coords="455,150,551,193" href="DataAccess.php">
    <area shape="rect" coords="2,4,261,20" href="DataAccess.php">
  </map>
  <br>
  <map name="Map">
    <area shape="rect" coords="478,145,540,197" href="search.php">
    <area shape="rect" coords="543,151,611,195" href="DataAccess.php">
    <area shape="rect" coords="163,3,248,15" href="DataAccess.php">
    <area shape="rect" coords="280,146,362,194" href="actmgt.php">
    <area shape="rect" coords="188,146,276,196">
    <area shape="rect" coords="127,147,182,197" href="DataAccessSearch.php">
    <area shape="rect" coords="75,147,121,196" href="helpDeskAccessAllCalls.php">
    <area shape="rect" coords="2,146,74,198" href="reportproblem.htm">
  </map></head>
  <form method="post" action="helpDeskupdate.php">
  <?php
  
  //Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com

	function formatPhone($input)
	{
		return substr($input, 0, 3) . "-" . substr($input, 3, 3) . "-" . substr($input, 6);
	}

	if (isset($_GET['record']))
		$dataVar = $record = $_GET["record"];
	elseif (isset($_GET['id']))
		$dataVar = $record = $_GET["id"];
	
	$t = new Ticket($dataVar);

	if($t->get('results'))
	{
		    echo "<table border=1 width=600 cellpadding=3 cellspacing=1>";
			echo "<tr><td>Time & Date: " . $t->get('mainDate') . "</td><td>Support Call ID: " . $t->get('id') . "</td>";
 			echo "</tr>";
 			
			echo "<td>Problem submitted by: " . $t->get('FirstName') . " " . $t->get('LastName') . "</td>";
			if (is_null($t->get('staff')))
				$staff = "Unassigned";
			else {
				$u = $t->get('staff');
				$staff = $u->get('user');
			}
			
			$staff = $t->get('staff');
			$staff = is_null($staff->get('user')) ? 'Unassigned' : $staff->get('user');
			
			echo "<td>I.T. Staff Assigned to: $staff </td></tr>";
			echo "<td colspan=2>Contact's E-Mail Address: " . $t->get('EMail') . "";
			
			//output the phone number if possible
			if (intval($t->get('phoneNumber')) > 0)
			{
				echo "<br/>Phone Number: " . formatPhone($t->get('phoneNumber')) . " ";
				if (intval($t->get('phoneExt')) > 0)
					echo "Ext. " . $t->get('phoneExt');
			}
			
			echo "</td>";
			echo "</tr>";
			echo "<tr>\n
					<td>Platform: " . $t->get('platform') . "</td>
					<td>OS: " . $t->get('os') . "</td>
				  </tr>
				  <tr>
				  	<td>IP Address: " . $t->get('ipaddress') . "</td>
				  	<td>UA String: " . $t->get('uastring') . "</td>
				  </tr>
				  <tr>
				  	<td>Browser: " . $t->get('browser') . "</td>
				  	<td>Browser Version: " . $t->get('bversion') . "</td>
				  </tr>";
			$c = $t->get('PCatagory');
			echo "<tr>
					<td colspan=2 align=left><b>The reported problem is catagorized as:</b> " . $c->get('name', 'stripslashes') . "</td>
				  </tr>
				  <tr>
				  	<td colspan=2><b>Described by the user as:</b>  " . $t->get('descrip', 'nl2br') . " </td>
				  </tr>
				  <tr>
				  	<td colspan=2>
				  		<b>Ticket Visibility: </b>
				  		<select name=\"visibility\" size=\"1\">
				  			<option value=\"0\"" . ($t->get('ticketVisi', 'intval') == 0 ? " selected=\"selected\"" : "") . ">Held</option>\n
							<option value=\"1\"" . ($t->get('ticketVisi', 'intval') == 1 ? " selected=\"selected\"" : "") . ">Published</option>\n
				  		</select>
				  	</td>
				  </tr>
				</table>";
			echo "<br><table border=1 width=600 cellpadding=3 cellspacing=1><tr><td colspan=4 align=left><strong>Resolution to this help desk call</strong></td></tr>";
			echo "<tr><td colspan=\"2\" valign=\"top\">";
			if (count($t->get('resolution'))) {
				foreach ($t->get('resolution') as $index)
					print nl2br($index['resolution']) . " - " . $index['date'] . "<br/><a href=\"res_control.php?action=delete&resid=" . $index['id'] . "&tickno=" . $t->get('id') . "\">Delete</a>&nbsp;&nbsp;<a href=\"res_control.php?resid=" . $index['id'] . "&tickno=" . $t->get('id') . "\">Edit</a><br/><br/>\n\n";
			}
			else {
				echo "No Resolutions";	
			}
			echo "<tr><td height=\"5\"></td></tr>";
			echo "<tr><td colspan=2 align=left>\n<b>File List</b>\n</td></tr>";
			echo "<tr><td colspan=2 valign=top>\n";
			
			if (count($t->get('fileList'))) {
				foreach ($t->get('fileList') as $index)
					echo "<a href=\"./uploaded_files/" . $t->get('id') . "_" . $index . "\">$index</a><br/>\n";
			}
			else {
				echo "No Files Listed";	
			}
			echo "</td></tr>\n";
			echo "</table>";
	}
			
            
    //idiot code - see replacement below
  	//echo "<input type=hidden name=ID value=$IdentTag>"

//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com
	if (isset($_POST['id']))
		echo '<input type="hidden" name="id" value="' . $_POST['id'] . '" />' . chr(10);
	else
		echo '<input type="hidden" name="id" value="' . ( isset($_GET['record']) ? $_GET['record'] : ( isset($_GET['id']) ? $_GET['id'] : '' ) ) . '" />' . chr(10);
?>
   <table width="600" border="1" cellpadding="3" cellspacing="1" bordercolor="">
   <?php
	   //grab the ticketAccessModify setting from the database
	   	if ($OBJ->get('ticketAccessModify'))
	   	{
	?>
		<tr>
			<td>Ticket Visibility:&nbsp;</td>
			<td>
				<select name="ticketVisi" size="1">
					<option value="1"<?php echo ($t->get('ticketVisi')) ? ' selected="selected"' : ''; ?>>Published</option>
					<option value="0"<?php echo (!$t->get('ticketVisi')) ? ' selected="selected"' : ''; ?>>Held</option>
				</select>
			</td>
		</tr>	
	<?php
	   	}
	   	else {
	?>
	<input type="hidden" name="ticketVisi" value="0" />
	<?php
	   	}
   ?>
    <tr> 
      <td width="59%">Call Status: </td>
      <td width="41%">
      		<select name="status" size="1" id="status">
         	<?php
         		$q = "select id from " . DB_PREFIX . "status order by position";
         		$s = mysql_query($q) or die(mysql_error());
         		while ($r = mysql_fetch_assoc($s))
         		{
         			$stat = new Status($r['id']);
         			$_stat = $t->get('status');
         			if ($stat->get('id') == $_stat->get('id'))
         				echo '<option value="' . $stat->get('id') . '" selected="selected">' . $stat->get('name', 'stripslashes') . '</option>' . chr(10);	
         			else 
         				echo '<option value="' . $stat->get('id') . '">' . $stat->get('name', 'stripslashes') . '</option>' . chr(10);
         		}
         	?>
        	</select>
       </td>
    </tr>
    <tr>
      <td>Problem Category:</td>
      <td><select name="PCatagory" size="1">
          <?php
          	$q = "select id from " . DB_PREFIX . "categories order by name";
          	$s = mysql_query($q) or die(mysql_error());
          	while ($r = mysql_fetch_assoc($s))
          	{
          		$c = new Category($r['id']);
          		if ($c->get('id') == $t->get('id'))
         				echo '<option value="' . $c->get('id') . '" selected="selected">' . $c->get('name', 'stripslashes') . '</option>' . chr(10);	
         			else 
         				echo '<option value="' . $c->get('id') . '">' . $c->get('name', 'stripslashes') . '</option>' . chr(10);
          	}
          ?>
        </select></td>
    </tr>
    <tr> 
      <td>I.T. staff member assigned to this problem:</td>
      <td>
      	<select name="itstaff" >
          <option value="0">Any One</option>
          <?php
          	$q = "select id from " . DB_PREFIX . "accounts where securityLevel > " . ENDUSER_SECURITY_LEVEL . " order by user";
          	$s = mysql_query($q) or die(mysql_error());
          	while ($r = mysql_fetch_assoc($s))
          	{
          		$u = new User($r['id']);
          		$_u = $t->get('staff');
          		if ($u->get('id') == $_u->get('id'))
         				echo '<option value="' . $u->get('id') . '" selected="selected">' . $u->get('user') . '</option>' . chr(10);	
         		else 
         				echo '<option value="' . $u->get('id') . '">' . $u->get('user') . '</option>' . chr(10);	
          	}
		  ?>
        </select>
      </td>
    </tr>
    <tr> 
      <td>Help Desk Call Priority :</td>
      <td> <select name="priority" size="1" id="select">
		    <?php
		    	$q = "select pid from " . DB_PREFIX . "priorities order by severity";
		    	$s = mysql_query($q) or die(mysql_error());
		    	while ($r = mysql_fetch_assoc($s))
		    	{
		    		$p = new Priority($r['pid']);
		    		$_p = $t->get('priority');
		    		if ($p->get('pid') == $_p->get('pid'))
         				echo '<option value="' . $p->get('pid') . '" selected="selected">' . $p->get('name') . '</option>' . chr(10);	
         			else 
         				echo '<option value="' . $p->get('pid') . '">' . $p->get('name') . '</option>' . chr(10);	
		    	}
			?>
        </select> </td>
    </tr>
    <tr>
    	<td>Part Association:&nbsp;</td>
    	<td><select name="partNo" size="1">
    		<?php
    			$q = "select ID, serial from " . DB_PREFIX . "excess";
    			$s = mysql_query($q);
    			if (mysql_num_rows($s)) {
    				while ($r = mysql_fetch_assoc($s))
    				{
    					if ($r['ID'] == $t->get('partNo')) {
    						//A small note here - if the partNum is found then the link will be shown - so store the serial locally
    						//so as to decrease the number of times we query the database - performance increase
    						echo '<option value="' . $r['ID'] . '" selected="selected">' . $r['serial'] . '</option>' . chr(10);
    						$serial = $r['serial'];
    					}
    					else 
    						echo '<option value="' . $r['ID'] . '">' . $r['serial'] . '</option>' . chr(10);
    				}
    			}
    			else {
    				echo '<option value="">No Parts Listed</option' . chr(10);
    			}
    		?>
    		</select><br/>
    		<?php #echo (isset($t->get('partNo'), $serial) && !empty($t->get('partNo'))) ? '<a href="showDetails.php?ID=' . $t->get('partNo') . '">' . $serial . '</a>' . chr(10) : ''; ?>
    	</td>
    </tr>
    <tr> 
      <td>Delete this Record? If so enter Help Desk Call ID here:</td>
      <td><input name="idDelete" type="text" id="idDelete" size="5" maxlength="5"></td>
    </tr>
    <?php
    	if (isset($emailsStatus) && $emailsStatus)
    	{
    ?>
	<tr>
		<td>Supress Email Sending:</td>
		<td><input type="checkbox" value="1" name="supressEmail" /> Check Here to Supress</td>
	</tr>    
    <?php
    	}
    ?>
    <tr> 
      <td colspan="2"><p><br>
          Please describe the actions that have been taken to resolve the problem:<br>
          <textarea name="Resolution" cols="50" rows="6" id="textarea"></textarea>
        </p>
        <p align="center"> 
          <input type="hidden" name="ID" value="<?php echo $t->get('id'); ?>">
          <input type="submit" name="Submit" value="Submit" class="button">
        </p></td>
    </tr>
  </table>
  <p><a href="DataAccess.php">Back to the Main Page Help Desk Page</a> </p>
  <p>&nbsp;</p>
  <p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
    2005 Help Desk Reloaded<br>
    <a href="http://www.helpdeskreloaded.com">Today's Help Desk Software for Tomorrows 
    Problem.</a></font><br>
  </p>
  </form>

<p>&nbsp;</p>