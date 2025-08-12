<?php

/***************************************************************************

 poll_summary.php
 -----------------
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


global $_SERVER;
if ( (substr($_SERVER["PHP_SELF"],-11) == 'control.php') ||
	 (substr($_SERVER["PHP_SELF"],-10) == 'module.php') ||
	 (substr($_SERVER["PHP_SELF"],-16) == 'showcontents.php') ) {
	 require_once('../moduleSec.php');
} else {
	require_once('../moduleSec.php');
}

include_once ($GLOBALS["admin_home"]."compile.php");
if (!(function_exists('GetPollName'))) {
	include_once($GLOBALS["modfiledir"]."/pollfunctions.php");
}

$GLOBALS["PollName"] = GetPollName();


$isodate = sprintf ("%04d-%02d-%02d", strftime("%Y"), strftime("%m"), strftime("%d"));
$strQuery = ModuleDataQuery($_POST["catcode"],"expiredate>='".$isodate."'");


$countres = dbRetrieve($strQuery,true,0,0);
$lRecCount = dbRowsReturned($countres);
dbFreeResult($countres);

$nCurrentPage = 0;
if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
$nPages = intval(($lRecCount - 0.5) / $GLOBALS["scPerPage"]) + 1;
$lStartRec = $nCurrentPage * $GLOBALS["scPerPage"];
if ($nPages > 1) { SubModuleHdFt($nCurrentPage,$nPages);
} else { echo '<br />'; }


$result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["scPerPage"]);


?><center><?php
SubFormHeader('Poll');

if ($lRecCount == 0) { ModuleNoEntries();
} else {
	while ($rsContent = dbFetch($result)) {
		$canvote = False;
		// We display all options rather than a paged set of options, so we retrieve the full option count for the main query
		$strQuery = "SELECT count(*) AS optscount FROM ".$GLOBALS["scTable"]."options WHERE pollid='".$rsContent["pollid"]."'";
		$countresult = dbRetrieve($strQuery,true);
		$rsPollCount = dbFetch($countresult);
		$optscount = $rsPollCount["optscount"];
		dbFreeResult($countresult);

		if ($_POST["submit"]) {
			if ($rsContent["polltype"] == 'M') {
				$strQuery = "SELECT * FROM ".$GLOBALS["scTable"]."options WHERE pollid='".$rsContent["pollid"]."'";
				$v = 0;
				$oresult = dbRetrieve($strQuery,true,0,$optscount);
				while ($rsOption = dbFetch($oresult)) {
					$votetest = 'vote-'.$rsContent["pollid"].'-'.$rsOption["polloptionid"];
					if ($_POST[$votetest] == 'yes') {
						RegisterVote($rsContent["pollid"],$rsOption["polloptionid"]);
						$v++;
					}
				}
				dbFreeResult($oresult);
				if ($v > 0 ) {
					CompleteVote($rsContent["pollid"]);
					$rsContent["pollvotes"]++;
				}
			} else {
				$votetest = 'vote-'.$rsContent["pollid"];
				if ($_POST[$votetest] != '') {
					RegisterVote($rsContent["pollid"],$_POST[$votetest]);
					CompleteVote($rsContent["pollid"]);
					$rsContent["pollvotes"]++;
				}
			}
		}

		$bEncodeHTML = true;
		?>
		<table border="1" width="100%" cellspacing="1" cellpadding="3" class="teasercontent">
		<tr><td class="header">
			<?php 
			echo ext_print(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$rsContent["question"].$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], 'N', 'N', 'L'),$bEncodeHTML, 'L');
			?>
		</td></tr>
		<tr><td class="tablecontent" valign="top">
		<table border="0" width="100%" cellspacing="1" cellpadding="3" class="tablecontent"><?php

		if ($GLOBALS["PollName"] != '') {
			$strQuery = "SELECT pollresult FROM ".$GLOBALS["scTable"]."results WHERE pollid='".$rsContent["pollid"]."' AND userid='".$GLOBALS["PollName"]."'";
			$presult = dbRetrieve($strQuery,true,0,0);
			$rsResult = dbFetch($presult);
			$pollresult = $rsResult["pollresult"];
			dbFreeResult($presult);
		}

		$i = 0;
		$strQuery = "SELECT * FROM ".$GLOBALS["scTable"]."options WHERE pollid='".$rsContent["pollid"]."' ORDER BY optioncount DESC,polloption";
		$oresult = dbRetrieve($strQuery,true,0,$optscount);
		while ($rsOption = dbFetch($oresult)) {
			echo '<tr>';
			if (($pollresult == '') && ($GLOBALS["PollName"] != '') && (!($GLOBALS["inline"]))) {
				echo '<td width="5%">';
				$canvote = True;
				if ($rsContent["polltype"] == 'M'){
					echo '<input type="checkbox" name="vote-'.$rsContent["pollid"].'-'.$rsOption["polloptionid"].'" value="yes">';
				} else {
					echo '<input type="radio" name="vote-'.$rsContent["pollid"].'" value="'.$rsOption["polloptionid"].'">';
				}
				echo '</td>';
			}

			echo '<td>';
			echo ext_print(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$rsOption["polloption"].$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], 'N', 'N', 'L'),$bEncodeHTML, 'L');
			echo '</td>';

			if (($pollresult != '') || ($GLOBALS["PollName"] == '') || ($GLOBALS["inline"])) {
				if (!($GLOBALS["inline"])) {
					echo '<td align="right">';
					echo $rsOption["optioncount"].'/'.$rsContent["pollvotes"];
					echo '</td><td align="right">';
					if ($rsContent["pollvotes"] == 0) { echo '0.00%'; } else { $display = number_format($rsOption["optioncount"] / $rsContent["pollvotes"] * 100, "2"); echo $display.'%'; }
					echo '</td>';
				}
				echo '<td>';
				echo GraphValue($rsOption["optioncount"],$rsContent["pollvotes"],$i);
				echo '</td>';
			}

			echo '</tr>';
			$i++;
		}
		dbFreeResult($oresult);

		if ($canvote) {
			?>
			<tr><td><input type="Submit" name="submit" value="<?php echo $GLOBALS["tVoteButton"]; ?>"></td></tr>
			<?php
		}
	   ?></table>
	   </table><br /><?php
	}
}
dbFreeResult($result);

SubFormFooter();

?></center><?php

if ($nPages > 1) { SubModuleHdFt($nCurrentPage,$nPages); }


?>
