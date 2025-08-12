<?
	/*
	Silentum Boards v1.4.3
	administrator_directory.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("function_list.php");
	require_once("settings.php");
	require_once("permission.php");
	administrator();

	if($user_logged_in != 1 || $user_data['status'] != 1) {
	$logging = explode(',',$config['record_options']);
	if(in_array(2,$logging)) {
	record("2","%1: Control Panel Access Attempt [IP: %2]");
	}
	header("Location: index.php");
	exit;
	}
	else {

	function cmpid($a,$b) {
	if($a['id']  == $b['id']) return 0;
	return ($a['id'] > $b['id']) ? 1 : -1;
	}

	function cmpkarma($a,$b) {
	if($a['karma'] == $b['karma']) return 0;
	return ($a['karma'] > $b['karma']) ? -1 : 1;
	}

	function cmpposts($a,$b) {
	if($a['posts'] == $b['posts']) return 0;
	return ($a['posts'] > $b['posts']) ? -1 : 1;
	}

	function cmpstatus($a,$b) {
	if($a['status']  == $b['status']) {
	if($a['karma']  == $b['karma']) return 0;
	return ($a['karma'] > $b['karma']) ? -1 : 1;
	}
	return ($a['status'] > $b['status']) ? 1 : -1;
	}

	function cmpname($a,$b) {
	return strcasecmp($a['name'],$b['name']);
	}

	function cmpemail($a,$b) {
	return strcasecmp($a['email'],$b['email']);
	}

	$x = 0;

	$membernumber = myfile("objects/id_users.txt"); $membernumber = $membernumber[0] + 1;

	for($i = 1; $i < $membernumber; $i++) {
	if($act_member = myfile("members/$i.txt")) {
	if(killnl($act_member[4]) != 5) {
	$member[$x]["name"] = killnl($act_member[0]);
	$member[$x]["id"] = killnl($act_member[1]);
	$member[$x]["email"] = killnl($act_member[3]);
	$member[$x]["status"] = killnl($act_member[4]);
	$member[$x]["posts"] = killnl($act_member[5]);
	$member[$x]["karma"] = killnl($act_member[16]);
	$member[$x]["possiblekarma"] = killnl($act_member[14]);
	$x++;
	}
	}
	}

	$membernumber = sizeof($member);
	if(!isset($sortmethod)) $sortmethod = 'id';
	switch($sortmethod) {
	case "name":
	usort($member,"cmpname");
	break;
	case "id":
	nix();
	break;
	case "email":
	usort($member,"cmpemail");
	break;
	case "karma":
	usort($member,"cmpkarma");
	break;
	case "posts":
	usort($member,"cmpposts");
	break;
	case "status":
	usort($member,"cmpstatus");
	break;
	default:
	nix();
	break;
	}

	$member_per_page = 50;

	$pagenumber = ceil($membernumber/$member_per_page);

	if(!$z) $z = 1; $z2 = $z * $member_per_page; $y = $z2-$member_per_page; if($z2 > $membernumber) $z2 = $membernumber;

	for($i = 1; $i < $pagenumber+1; $i++) {
	if($i != $z) {
	$pagedisplay[($i-1)] = "<a href=\"administrator_directory.php?sortmethod=$sortmethod&amp;z=$i\">$i</a>";
	}
	else $pagedisplay[$i-1] = $i;
	}
	$pagedisplay = sprintf($txt['Pages'],implode(" ",$pagedisplay));

	for($i = 1; $i < $pagenumber+1; $i++) {
	if($i != $z) {
	$pagedisplay2[($i-1)] = "<a href=\"administrator_directory.php?sortmethod=$sortmethod&amp;z=$i\">$i</a>";
	}
	else $pagedisplay2[$i-1] = $i;
	}
	$pagedisplay2 = sprintf($txt['Pages'],implode(" ",$pagedisplay2));

	include('board_top.php');
	echo navigation("<a href=\"user_control_panel.php\">User Control Panel</a>\tDirectory ".$pagedisplay);

	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("10","%1: Directory Viewed [IP: %2]");
	}
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="3"><span class="heading">Directory</span></td>
		<td class="heading1" colspan="4" style="text-align: right"><span class="heading"><?=$pagedisplay2?></span></td>
	</tr>
	<tr>
		<td class="heading2" style="text-align: center; width: 5%"><span class="heading">Rank</span></td>
		<td class="heading2" style="text-align: center; width: 10%"><span class="heading"><a href="administrator_directory.php?sortmethod=id&amp;z=<?=$z?>">User ID</a></span></td>
		<td class="heading2" style="text-align: center; width: 20%"><span class="heading"><a href="administrator_directory.php?sortmethod=name&amp;z=<?=$z?>">User Name</a></span></td>
		<td class="heading2" style="text-align: center; width: 20%"><span class="heading"><a href="administrator_directory.php?sortmethod=email&amp;z=<?=$z?>">Registration E-mail Address</a></span></td>
		<td class="heading2" style="text-align: center; width: 20%"><span class="heading"><a href="administrator_directory.php?sortmethod=status&amp;z=<?=$z?>">Status</a></span></td>
		<td class="heading2" style="text-align: center; width: 15%"><span class="heading"><a href="administrator_directory.php?sortmethod=karma&amp;z=<?=$z?>">Karma</a></span></td>
		<td class="heading2" style="text-align: center; width: 10%"><span class="heading"><a href="administrator_directory.php?sortmethod=posts&amp;z=<?=$z?>">Posts</a></span></td>
	</tr>
<?
	for($i = $y; $i < $z2; $i++) {
	if($member[$i]["status"] != "5") {

	if($user_data['status'] == "1") {
	$rows_per_color = 1;
	switch($ctr++) {
	case 0:
	$bgcolor = "one";
	break;
	case ($rows_per_color):
	$bgcolor = "two";
	break;
	case ($rows_per_color * 2):
	$bgcolor = "one";
	$ctr = 1;
	break;
	}
?>
	<tr>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><? if($member[$i]["status"] != 4) echo ""?><?=$i+1?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$member[$i]["id"]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: left"><span class="normal"><a href="index.php?page=profile&amp;id=<?=$member[$i]["id"]?>"><?=$member[$i]["name"]?></a></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$member[$i]["email"]?></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><strong><? if($member[$i]["id"] == 1) echo $config['status_host']?><? if($member[$i]["id"] != 1) { ?><?=morph_status($member[$i][status],$member[$i][karma])?> <? }?></strong></span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$member[$i]["karma"]?> (<?=$member[$i][possiblekarma]?> Pos.)</span></td>
		<td class="<?=$bgcolor?>" style="text-align: center"><span class="normal"><?=$member[$i]["posts"]?></span></td>
	</tr>
<?
	}
	}
	}
?>
	<tr>
		<td class="heading1" colspan="7" style="text-align: right"><span class="heading"><?=$pagedisplay2?></span></td>
	</tr>
</table>
</object><br />
<?
	include('board_bottom.php');
	}
?>