<?php
require('config.php');
require('func.php');
IsAdminLogin();
SiteMenu();

if ($_GET['action'] == 'search') {
	Search();
} elseif ($_GET['action'] == 'view') {
	ViewWord();
} elseif ($_GET['action'] == 'show') {
	ShowWord();
}




include('_header.php');
echo $GLOBALS['content'];
include('_footer.php');

mysql_close();


function ShowWord() {
	$q = "SELECT * FROM words WHERE word_title = '" . str_replace('_', ' ', addslashes($_GET['word'])) . "';";
	$res = mysql_query($q);
	if (!($row = mysql_fetch_assoc($res))) {
		$c.= '<br /><div align="center" class="error">Error... no entry found</div>';
	} else {
		$GLOBALS['page_title'] = htmlesc($row['word_title']) .  ' - ' . $GLOBALS['page_title'];
		$c.= '<table align="center" cellspacing="0" cellpadding="1">';
		$c.= '<tr><td height="10"></td></tr>';
		$c.= '
			<tr>
				<td valign="top" class="word">' . htmlesc($row['word_title']) . '</td>
				<td width="30"></td>
				<td valign="top" width="400">' . LNtoBR($row['word_desc']) . '</td>
			</tr>
			<tr>
				<td colspan="3" height="10"></td>
			</tr>
			<tr>
				<td colspan="3">
					<b>Additional comments : </b><br />
					' . LNtoBR($row['word_comments']) . '
				</td>
			</tr>
		';
		$c.= '<tr><td height="10"></td></tr>';
		$c.= '</table>';
	}
	$GLOBALS['content'].= $c;
	$GLOBALS['content'].= '<br /><br /><div align="center"><a href="display.php?' . $GLOBALS['tofrom'] . '">Back</a></div>';
}

function ViewWord() {

	$q = "SELECT * FROM words WHERE word_id = " . ($_GET['id']+0);
	$res = mysql_query($q);
	if (!($row = mysql_fetch_assoc($res))) {
		$c.= '<br /><div align="center" class="error">Error... no entry found</div>';
	} else {
		$GLOBALS['page_title'] = htmlesc($row['word_title']) .  ' - ' . $GLOBALS['page_title'];
		$c.= '<table align="center" cellspacing="0" cellpadding="1">';
		$c.= '<tr><td height="10"></td></tr>';
		$c.= '
			<tr>
				<td valign="top" class="word">' . htmlesc($row['word_title']) . '</td>
				<td width="30"></td>
				<td valign="top" width="400">' . LNtoBR($row['word_desc']) . '</td>
			</tr>
			<tr>
				<td colspan="3" height="10"></td>
			</tr>
			<tr>
				<td colspan="3">
					<b>Additional comments : </b><br />
					' . LNtoBR($row['word_comments']) . '
				</td>
			</tr>
		';
		$c.= '<tr><td height="10"></td></tr>';
		$c.= '</table>';
	}
	$GLOBALS['content'].= $c;
	$GLOBALS['content'].= '<br /><br /><div align="center"><a href="display.php?' . $GLOBALS['tofrom'] . '">Back</a></div>';
}

function Search() {
	$_GET['word'] = trim(str_replace(Array('%', '_'), Array('', ''), $_GET['word']));
	if ($_GET['by']) {
		$word = $_GET['by'];
		$type = '';
	} elseif ($_GET['word']) {
		$word = $_GET['word'];
		if ($_GET['type'] == 'full') $type = 'full';
		else $type = '';
	} else {
		$word = '';
		$type = '';
	}
	
	if ($word != '') {
		$GLOBALS['page_title'] = $word .  ' - ' . $GLOBALS['page_title'];
		if ($type == 'full') {
			$select = "*, IF(word_title = '" . sqlesc($word) . "', 1, 0) AS found ";
			$where = "word_title = '" . sqlesc($word) . "' OR MATCH (word_desc) AGAINST ('" . sqlesc($word) . "') ";
			$order = "found DESC, MATCH (word_desc) AGAINST ('" . sqlesc($word) . "') DESC ";
		} else {
			$select = '* ';
			$where = "word_title LIKE '" . sqlesc($word) . "%' ";
			$order = "word_title ASC ";
		}
		$q = "SELECT $select FROM words WHERE $where ORDER BY $order";
	} else {
		Redirect('index.php');
	}
	
	$q.= Navig($q, 25);
	
	
	$c = $GLOBALS['navig'];
	
	$c.= '<table align="center" cellspacing="0" cellpadding="1">';
	$c.= '<tr><td height="10"></td></tr>';
	$res = mysql_query($q);
	while ($row = mysql_fetch_assoc($res)) {
		$c.= '<tr>';
		if ($GLOBALS['admin']) {
			$c.= '
				<td>
					<a href="admin.php?action=edit&id=' . $row['word_id'] . '&from=' . $GLOBALS['from'] . '"><img src="images/button_edit.gif" border="0" title="Edit" /></a>
					<a href="admin.php?action=delete&id=' . $row['word_id'] . '&from=' . $GLOBALS['from'] . '" OnClick="return confirm(\'Are you sure you want to delete this word?\');"><img src="images/button_delete.gif" border="0" title="Delete" /></a>
					&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
			';
		}
		//$c.= '<td><a href="display.php?action=view&id=' . $row['word_id'] . '&from=' . $GLOBALS['from'] . '">' . htmlesc($row['word_title']) . '</a></td>';
		$c.= '<td><a href="' . str_replace(' ', '_', htmlesc($row['word_title'])) . '.html">' . htmlesc($row['word_title']) . '</a></td>';
		$c.= '<td width="20">&nbsp;</td>';
		$c.= '<td>' . htmlesc(ShortW($row['word_desc'], 10)) . '</td>';
		$c.= '</tr>';
	}
	$c.= '<tr><td height="10"></td></tr>';
	$c.= '</table>';
	
	
	$c.= $GLOBALS['navig'];
	$GLOBALS['content'].= $c;
}







?>