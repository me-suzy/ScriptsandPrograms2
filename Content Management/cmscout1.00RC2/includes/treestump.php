<?php
/**************************************************************************
    FILENAME        :   treestump.php
    PURPOSE OF FILE :   Database logging class (Not used)
    LAST UPDATED    :   08 June 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
class treestump {
	function treestump () {
		return 0;
	}
    
	function getbranch($username, $team) {
		if ($username == "All") {
			if ($team == "All") {
				$sqlquery = "SELECT * FROM the_tree";
			}
			$sqlquery = "SELECT * FROM the_tree WHERE team='$team'";
		} else {
			$sqlquery = "SELECT * FROM the_tree WHERE uname='$username'";
		}
		$query = mysql_query($sqlquery) or die(mysql_error());
		if (!$query) {
		 return "no log";
		}
		$logs = array();
		$logs[] = mysql_fetch_assoc($query);
		do {
		} while ($logs[] = mysql_fetch_assoc($query));
		return $logs;
	}
		
	function countbranches($username, $team) {
		if ($username == "All") {
			if ($team == "All") {
				$sqlquery = "SELECT * FROM the_tree";
			}
			$sqlquery = "SELECT * FROM the_tree WHERE team='$team'";
		} else {
			$sqlquery = "SELECT * FROM the_tree WHERE uname='$username'";
		}
		$query = mysql_query($sqlquery)  or die(mysql_error());
		if (!$query) {
		 return "no log";
		}
		return mysql_num_rows($query);
	}
	function growbranch($username, $team, $page, $descs) {
		if (!isset($username)) {
			return "no uname";
		}
		if (!isset($team)) {
			return "no team";
		}
		if (!isset($page)) {
			return "no page";
		}
		if (!isset($descs)) {
			return "no desc";
		}
        if (!isset($sqlq)) {
			return "no sql";
		}
		$currentdatetime = mysql_query('select now()');
		$date = mysql_result($currentdatetime,0);
		$sql = "INSERT INTO the_tree VALUES ('', '$username', '$team', '$page', '$descs', '$date')";
		$query = mysql_query($sql) or die(mysql_error());
		if ($query) {
			return 1;
		} else {
			return mysql_error();
		}
	}
	
	function prunebranch($id) {
		if (!isset($id)) {
		 return "no id";
		}
		$sql = "DELETE FROM the_tree WHERE id=$id";
		$query = mysql_query($sql) or die(mysql_error());
		if ($query) {
			return 1;
		} else {
			return 0;
		}
	}
}
?>