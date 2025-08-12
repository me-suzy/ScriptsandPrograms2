<?php

############################################################################
############################################################################
##                                                                        ##
## This script is copyright Rupe Parnell (Starsol.co.uk) 2003 - 2005.     ##
##                                                                        ##
## Distribution of this file, and/or any other files in this package, via ##
## any means, withour prior written consent of the author is prohibited.  ##
##                                                                        ##
## Starsol.co.uk takes no responsibility for any damages caused by the    ##
## usage of this script, and does not guarantee compatibility with all    ##
## servers.                                                               ##
##                                                                        ##
## Please use the contact form at                                         ##
## http://www.starsol.co.uk/support.php if you need any help or have      ##
## any questions about this script.                                       ##
##                                                                        ##
############################################################################
############################################################################

require_once('faq-functions.php');

if ($_POST[t]){
	$t = $_POST[t];
} elseif ($_GET[t]){
	$t = $_GET[t];
} elseif ($_SERVER['QUERY_STRING']){
	$t = $_SERVER['QUERY_STRING'];
} else {
	$t = "index";
}

if ($t != "process_login" AND $t != "logout"){
	admin_header();
}

connect_to_mysql();

switch ($t) {

	case "index":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}

		echo'<h1>'.$site_name.' FAQ Admin Area</h1>';

		admin_faq_links(0);

		admin_misc_links(0);

		echo'<p><a href="'.$_SERVER[PHP_SELF].'?t=logout">Log Out</a></p>';

	break;

	case "faq_rating_reset_process":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_POST[uin]){
			error_message('Sorry, no question UIN was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		@mysql_query('DELETE FROM '.$db_prefix.'ratings WHERE qu="'.$_POST[uin].'"') or deal_with_mysql_error('Ratings Deletion MySQL Error (faq_rating_reset_process). '.mysql_error(),'admin');
		count_faq($_POST[uin]);

		$result = @mysql_fetch_array(mysql_query('SELECT qu FROM '.$db_prefix.'q WHERE uin="'.$_POST[uin].'"')) or deal_with_mysql_error('Question Data Retrieval Error (faq_rating_reset_process). '.mysql_error(),'admin');

		if (strlen($result[qu]) > 77){
			$result[qu] = substr($result[qu], 0, 75) . "...";
		}

		echo'<h1>Rating Reset</h1>'."\n\n";

		echo'<p>The ratings for <i>'.$result[qu].'</i> have been reset.</p>'."\n\n";

		echo'<p>Please note that users who have previously rated this FAQ will not be able to rate this FAQ again if they still have the <i>starsol_faq_ratings</i> cookie on their computer.</p>'."\n\n";

		echo'<p> - <a href="'.$_SERVER[PHP_SELF].'?t=faq_list">Return to the list of all frequently asked questions currently in the database.</a><br /><br />'."\n".' - <a href="'.$_SERVER[PHP_SELF].'?t=index">Go to the '.$site_name.' FAQ admin area index.</a></p>'."\n\n";

	break;

	case "faq_rating_reset":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_GET[uin]){
			error_message('Sorry, no question UIN was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		$result = @mysql_fetch_array(mysql_query('SELECT qu,rating,rc FROM '.$db_prefix.'q WHERE uin="'.$_GET[uin].'"')) or deal_with_mysql_error('Question Data Retrieval Error (faq_rating_reset). '.mysql_error(),'admin');

		if (strlen($result[qu]) > 77){
			$result[qu] = substr($result[qu], 0, 75) . "...";
		}

		echo'<h1>Reset FAQ Rating?</h1>'."\n\n";

		echo'<p style="text-align: center;">Are you certain you want to reset the ratings ('.$result[rating].'% helpful from '.$result[rc].' votes) for the <i>'.$result[qu].'</i> question?</p>'."\n\n";

		echo'<table style="float: center; width: 50%; border: 0px; padding: 5px;">'."\n".'<tr>';
		echo'<td style="text-align: center;">'."\n".'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="faq_rating_reset_process" />'."\n".'<input type="hidden" name="uin" value="'.$_GET[uin].'" />'."\n".'<input type="submit" value="Yes, Reset" />'."\n".'</form>'."\n".'</td>'."\n";
		echo'<td style="text-align: center;">'."\n".'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="faq_list" />'."\n".'<input type="submit" value="No, Cancel" />'."\n".'</form>'."\n".'</td>'."\n";
		echo'</tr>'."\n".'</table>'."\n\n";

	break;

	case "faq_delete_process":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_POST[uin]){
			error_message('Sorry, no question UIN was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		@mysql_query('DELETE FROM '.$db_prefix.'q WHERE uin="'.$_POST[uin].'" LIMIT 1') or deal_with_mysql_error('Question Deletion MySQL Error (faq_delete_process). '.mysql_error(),'admin');

		echo'<h1>Question Deleted</h1>'."\n\n";

		echo'<p>The question has been successfully deleted.</p>'."\n\n";

		echo'<p> - <a href="'.$_SERVER[PHP_SELF].'?t=faq_list">Return to the list of all frequently asked questions currently in the database.</a><br /><br />'."\n".' - <a href="'.$_SERVER[PHP_SELF].'?t=index">Go to the '.$site_name.' FAQ admin area index.</a></p>'."\n\n";

	break;

	case "faq_delete":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_GET[uin]){
			error_message('Sorry, no question UIN was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		$result = @mysql_fetch_array(mysql_query('SELECT qu FROM '.$db_prefix.'q WHERE uin="'.$_GET[uin].'"')) or deal_with_mysql_error('Question Data Retrieval Error (faq_delete). '.mysql_error(),'admin');

		if (strlen($result[qu]) > 77){
			$result[qu] = substr($result[1], 0, 75) . "...";
		}

		echo'<h1>Delete Question?</h1>'."\n\n";

		echo'<p style="text-align: center;">Are you certain you want to delete the <i>'.$result[qu].'</i> question?</p>'."\n\n";

		echo'<table style="float: center; width: 50%; border: 0px; padding: 5px;">'."\n".'<tr>';
		echo'<td style="text-align: center;">'."\n".'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="faq_delete_process" />'."\n".'<input type="hidden" name="uin" value="'.$_GET[uin].'" />'."\n".'<input type="submit" value="Yes, Delete" />'."\n".'</form>'."\n".'</td>'."\n";
		echo'<td style="text-align: center;">'."\n".'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="faq_list" />'."\n".'<input type="submit" value="No, Cancel" />'."\n".'</form>'."\n".'</td>'."\n";
		echo'</tr>'."\n".'</table>'."\n\n";

	break;

	case "faq_edit_process":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_POST[uin]){
			error_message('Sorry, no question UIN was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if (!$_POST[question]){
			error_message('Sorry, you forgot to enter anything in the <i>question</i> field. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if (!$_POST[answer]){
			error_message('Sorry, you forgot to enter anything in the <i>answer</i> field. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if (!$_POST[category]){
			error_message('Sorry, no category was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		@mysql_query('UPDATE '.$db_prefix.'q SET qu="'.$_POST[question].'",an="'.$_POST[answer].'",category="'.$_POST[category].'" WHERE uin="'.$_POST[uin].'"') or deal_with_mysql_error('FAQ Updation MySQL Error (faq_edit_process). '.mysql_error(),'admin');

		echo'<h1>Question Edited</h1>'."\n\n";

		echo'<p>The question has been edited successfully.</p>';

		echo'<p> - <a href="'.$_SERVER[PHP_SELF].'?t=faq_list">Return to the list of all frequently asked questions currently in the database.</a><br /><br />'."\n\n".' - <a href="'.$_SERVER[PHP_SELF].'?t=index">Go to the '.$site_name.' FAQ admin area index.</a></p>'."\n\n";

	break;

	case "faq_edit":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}

		admin_faq_links();

		if (!$_GET[uin]){
			error_message('Sorry, no question UIN was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		echo'<h1>Edit Question</h1>'."\n\n";

		$result = @mysql_fetch_array(mysql_query('SELECT * FROM '.$db_prefix.'q WHERE uin="'.$_GET[uin].'" LIMIT 1')) or deal_with_mysql_error('Question Data Retrieval MySQL Error (faq_edit). '.mysql_error(),'admin');

		echo'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="uin" value="'.$_GET[uin].'" />'."\n".'<input type="hidden" name="t" value="faq_edit_process" />'."\n";
		echo'Question:<br /><textarea name="question" rows="8" cols="60">'.$result[1].'</textarea><br /><br />'."\n";
		echo'Answer:<br /><textarea name="answer" rows="8" cols="60">'.$result[2].'</textarea><br /><br />'."\n";
		echo'Category:<br />'."\n".'<select name="category" size="1">'."\n".'<option selected>'.$result[3].'</option>'."\n";
		$categories = @mysql_query('SELECT name FROM '.$db_prefix.'c ORDER BY uin ASC') or deal_with_mysql_error('Categories Data Retrieval MySQL Error (faq_edit_process). '.mysql_error(),'admin');
		while ($row = mysql_fetch_array($categories, MYSQL_NUM)) {
			echo'<option>'.$row[0].'</option>'."\n";
		}
		echo'</select><br /><br />'."\n";
		echo'<input type="submit" value="Edit Question">'."\n".'</form>'."\n\n";

	break;

	case "faq_list":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_GET[category]){
			$_GET[category] = 'All Categories';
		}
		if (!$_GET[o]){
			$_GET[o] = 'qu';
		}
		if (!$_GET[aod]){
			$_GET[aod] = 'ASC';
		}
		if (!$_GET[limit]){
			$_GET[limit] = '50';
		}

		echo'<h1>Questions List</h1>'."\n\n";

		echo'<table class="list-table"><tr>'."\n";
		echo'<form action="'.$_SERVER[PHP_SELF].'" method="get">'."\n".'<input type="hidden" name="t" value="faq_list" />'."\n";

		echo'<td class="list-cell">Category:<br />'."\n".'<select name="category" size="1">'."\n".'<option selected>'.$_GET[category].'</option>'."\n";
		if ($_GET[category] != 'All Categories'){
			echo'<option>All Categories</option>'."\n";
		}
		$categories = @mysql_query('SELECT name FROM '.$db_prefix.'c ORDER BY uin ASC') or deal_with_mysql_error('Categories Data Retrieval MySQL Error (faq_list). '.mysql_error(),'admin');
		while ($row = mysql_fetch_array($categories, MYSQL_NUM)) {
			echo'<option>'.$row[0].'</option>'."\n";
		}
		echo'</select>'."\n".'</td>'."\n";

		echo'<td class="list-cell">Containing:<br />'."\n".'<input type="text" size="10" value="'.$_GET[containing].'" name="containing">'."\n".'</td>'."\n";

		echo'<td class="list-cell">Order By:<br />'."\n".'<select name="o" size="1">'."\n";
		if ($_GET[o] == "qu"){echo'<option selected value="qu">Question</option>'."\n"; $o_show = 'Question';}else{echo'<option value="qu">Question</option>'."\n";}
		if ($_GET[o] == "an"){echo'<option selected value="an">Answer</option>'."\n"; $o_show = 'Answer';}else{echo'<option value="an">Answer</option>'."\n";}
		if ($_GET[o] == "rating"){echo'<option selected value="rating">Rating</option>'."\n"; $o_show = 'Rating';}else{echo'<option value="rating">Rating</option>'."\n";}
		if ($_GET[o] == "uin"){echo'<option selected value="uin">UIN</option>'."\n"; $o_show = 'UIN';}else{echo'<option value="uin">UIN</option>'."\n";}
		echo'</select>'."\n".'&nbsp;';

		echo'<select name="aod" size="1">'."\n";
		if ($aod == "ASC"){echo'<option selected value="ASC">Ascending</option>'."\n";}else{echo'<option value="ASC">Ascending</option>'."\n";}
		if ($aod == "DESC"){echo'<option selected value="DESC">Descending</option>'."\n";}else{echo'<option value="DESC">Descending</option>'."\n";}
		echo'</select>'."\n".'</td>'."\n";

		echo'<td class="list-cell">Limit:<br />'."\n".'<input type="text" size="4" value="'.$_GET[limit].'" name="limit">'."\n".'</td>'."\n";

		echo'<td class="list-cell"><input type="submit" value="Change Filter" /></td>'."\n";

		echo'</form>'."\n".'</tr>'."\n".'</table>'."\n\n";

		if ($_GET[category] AND $_GET[category] != 'All Categories'){
			if (mysql_num_rows(mysql_query('SELECT uin FROM '.$db_prefix.'c WHERE name="'.$_GET[category].'"')) == '0'){
				error_message('The category specified does not exist. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
				admin_footer();
				@mysql_close();
				exit;
			} else {
				if ($where){
					$where .= ' AND category="'.$_GET[category].'"';
				} else {
					$where = 'WHERE category="'.$_GET[category].'"';
				}
			}
		}
		if ($_GET[o] == 'an'){
			$qoa = 'Answer';
			$sqoa = 'an';
		} else {
			$qoa = 'Question';
			$sqoa = 'qu';
		}
		if ($_GET[containing]){
			if ($where){
				$where .= ' AND '.$sqoa.' LIKE "%'.$_GET[containing].'%"';
			} else {
				$where = 'WHERE '.$sqoa.' LIKE "%'.$_GET[containing].'%"';
			}
		}

		echo'<table class="list-table" style="margin-top: 10px;">'."\n";
		echo'<tr><td class="list-heading">'.$qoa.'</td><td class="list-heading" nowrap="nowrap">Category</td><td class="list-heading">Rating</td><td class="list-heading" nowrap="nowrap">Tools</td></tr>'."\n";

		$faq_query = 'SELECT uin,'.$sqoa.',category,rating,rc FROM '.$db_prefix.'q '.$where.' ORDER BY '.$_GET[o].' '.$_GET[aod].' LIMIT '.$_GET[limit];
		$result = @mysql_query($faq_query) or deal_with_mysql_error('FAQs Data Retrieval MySQL Error (faq_list). '.mysql_error(),'admin');
		if (mysql_num_rows($result) == "0"){
			echo'<tr><td class="list-cell" colspan="4">There are no frequently asked questions in the database matching the criteria in the filter above. <a href="'.$_SERVER[PHP_SELF].'?t=faq_new">Add a new frequently asked question</a>.</td></tr>'."\n";
		} else {
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				if (strlen($row[1]) > 77){
					$row[1] = substr($row[1], 0, 75).'...';
				}
				if (!$row[4]){
					$row[3] = 'None';
				} else {
					$row[3] .= '% of '.$row[4];
				}
				echo'<tr><td class="list-cell">'.$row[1].'</td><td class="list-cell">'.htmlentities($row[2], ENT_QUOTES).'</td><td class="list-cell">'.$row[3].'</td><td class="list-cell"><a href="'.$_SERVER[PHP_SELF].'?t=faq_edit&amp;uin='.$row[0].'">Edit</a>. <a href="'.$_SERVER[PHP_SELF].'?t=faq_rating_reset&amp;uin='.$row[0].'">Reset Rating</a>. <a href="'.$_SERVER[PHP_SELF].'?t=faq_delete&amp;uin='.$row[0].'">Delete</a>.</td></tr>'."\n";
			}
		}
		echo'<tr><td class="list-cell" style="text-align: center;" colspan="4"><a href="'.$_SERVER[PHP_SELF].'?t=faq_new">Add a new FAQ.</a></td></tr>'."\n".'</table>'."\n\n";

		if ($debug){
			echo'<p>MySQL Query was:<br />'.$faq_query.'</p>'."\n\n";
		}

	break;

	case "faq_new_process":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_POST[question]){
			error_message('Sorry, you forgot to enter anything in the <i>question</i> field. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if (!$_POST[answer]){
			error_message('Sorry, you forgot to enter anything in the <i>answer</i> field. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if (!$_POST[category]){
			error_message('Sorry, no category was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		@mysql_query('INSERT INTO '.$db_prefix.'q VALUES("","'.$_POST[question].'","'.$_POST[answer].'","'.$_POST[category].'","0","0")') or deal_with_mysql_error('Question Insertion MySQL Error (faq_new). '.mysql_error(),'admin');

		echo'<h1>Question Added</h1>'."\n\n";

		echo'<p>The question was added to the database successfully.</p>'."\n\n";

		echo'<p> - <a href="'.$_SERVER[PHP_SELF].'?t=faq_new">Add another frequently asked question.</a><br /><br />'."\n".' - <a href="'.$_SERVER[PHP_SELF].'?t=faq_list">View the list of all frequently asked questions currently in the database.</a><br /><br />'."\n".' - <a href="'.$_SERVER[PHP_SELF].'?t=index">Go to the '.$site_name.' FAQ admin area index.</a></p>'."\n\n";

	break;

	case "faq_new":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		$result = mysql_query('SELECT * FROM '.$db_prefix.'c') or deal_with_mysql_error("Error: " . mysql_error());
		if (mysql_num_rows($result) == 0) { 
			error_message('Sorry, you need to add at least one <a href="'.$_SERVER[PHP_SELF].'?t=cat_new">category</a> before you can add a new question.');
			admin_footer();
			exit;
		}

		echo'<h1>Add New Question</h1>'."\n\n";

		echo'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="faq_new_process" />'."\n";
		echo'Question:<br /><textarea name="question" rows="8" cols="60">'.$_GET[question].'</textarea><br /><br />'."\n";
		echo'Answer:<br /><textarea name="answer" rows="8" cols="60">'.$_GET[answer].'</textarea><br /><br />'."\n";
		echo'Category:<br /><select name="category" size="1">';
		$result = @mysql_query('SELECT name FROM '.$db_prefix.'c ORDER BY name ASC') or deal_with_mysql_error('Categories Data Retrieval MySQL Error (faq_new). '.mysql_error(),'admin');
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			echo'<option>'.$row[0].'</option>';
		}
		echo'</select><br /><br />'."\n";
		echo'<input type="submit" value="Add New Question" />'."\n".'</form>'."\n\n";

	break;

	case "cat_delete_process":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_POST[uin]){
			error_message('Sorry, no FAQ category UIN was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		@mysql_query('DELETE FROM '.$db_prefix.'c WHERE uin="'.$_POST[uin].'"') or deal_with_mysql_error('Category Deletion MySQL Error (cat_delete). '.mysql_error(),'admin');

		echo'<h1>Category Deleted</h1>'."\n\n";

		echo'<p style="text-align: center;">The category has been successfully deleted.</p>'."\n\n";

		echo'<p> - <a href="'.$_SERVER[PHP_SELF].'?t=cat_list">Return to the list of all FAQ categories currently in the database.</a><br /><br />'."\n".' - <a href="'.$_SERVER[PHP_SELF].'?t=index">Return to the '.$site_name.' FAQ admin area index.</a></p>'."\n\n";

	break;

	case "cat_delete":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_GET[uin]){
			error_message("Sorry, no FAQ category UIN was specified. Please go back and try again. If the problem persists, please contact technical <a href='$_SERVER[PHP_SELF]?t=support'>support</a>.");
			admin_footer();
			@mysql_close();
			exit;
		}

		$result = @mysql_fetch_array(mysql_query('SELECT * FROM '.$db_prefix.'c WHERE uin="'.$_GET[uin].'"')) or deal_with_mysql_error('Category Data Retrieval MySQL Error (cat_delete). '.mysql_error(),'admin');

		if (mysql_num_rows(mysql_query('SELECT uin FROM '.$db_prefix.'q WHERE category="'.$result[1].'"')) != 0) { 
			error_message('Sorry, you may only delete a category that does not have any questions allocated to it. Please delete or change the category for all questions that are currently allocated to '.$result[1].'.');
			admin_footer();
			@mysql_close();
			exit;
		}			

		echo'<h1>Delete Category?</h1>'."\n\n";

		echo'<p style="text-align: center;">Are you certain you want to delete the <b>'.$result[1].'</b> category?</p>'."\n\n";

		echo'<table style="float: center; width: 50%; border: 0px; padding: 5px;">'."\n".'<tr>';
		echo'<td style="text-align: center;">'."\n".'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="cat_delete_process" />'."\n".'<input type="hidden" name="uin" value="'.$_GET[uin].'" />'."\n".'<input type="submit" value="Yes, Delete" />'."\n".'</form>'."\n".'</td>'."\n";
		echo'<td style="text-align: center;">'."\n".'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="cat_list" />'."\n".'<input type="submit" value="No, Cancel" />'."\n".'</form>'."\n".'</td>'."\n";
		echo'</tr>'."\n".'</table>'."\n\n";

	break;

	case "cat_edit_process": 

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_POST[uin]){
			error_message('Sorry, no FAQ category UIN was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		if (!$_POST[name]){
			error_message('Sorry, you need to enter something for the category to be renamed to. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}

		@mysql_query('UPDATE '.$db_prefix.'c SET name="'.$_POST[name].'" WHERE uin="'.$_POST[uin].'"') or deal_with_mysql_error('Category Updation MySQL Error (cat_edit_process). '.mysql_error(),'admin');
		@mysql_query('UPDATE '.$db_prefix.'q SET category="'.$_POST[name].'" WHERE category="'.$_POST[cat_old].'"') or deal_with_mysql_error('Category Name in Questions Table Updation MySQL Error (cat_edit_process). '.mysql_error(),'admin');

		echo'<h1>Category Renamed</h1>'."\n\n";

		echo'<p>The renaming of '.$_POST[cat_old].' to <b>'.$_POST[cat_new].'</b> was successful.</p>';

		echo'<p> - <a href="'.$_SERVER[PHP_SELF].'?t=cat_new">Add a new FAQ category.</a><br /><br />'."\n".' - <a href="'.$_SERVER[PHP_SELF].'?t=cat_list">View a list of all FAQ categories currently in the database.</a><br /><br />'."\n".' - <a href="'.$_SERVER[PHP_SELF].'?t=index">Return to the '.$site_name.' FAQ admin area index.</a></p>'."\n\n";

	break;

	case "cat_edit":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_GET[uin]){
			error_message('Sorry, no FAQ category UIN was specified. Please go back and try again. If the problem persists, please contact technical <a href="'.$_SERVER[PHP_SELF].'?t=support">support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		$result = @mysql_fetch_array(mysql_query('SELECT * FROM '.$db_prefix.'c WHERE uin="'.$_GET[uin].'"')) or deal_with_mysql_error("Category Data Retrieval MySQL Error (cat_edit).".mysql_error(),'admin');

		echo'<h1>Rename Category</h1>'."\n\n";

		echo'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="uin" value="'.$_GET[uin].'">'."\n".'<input type="hidden" name="cat_old" value="'.$result[1].'">'."\n".'<input type="hidden" name="t" value="cat_edit_process">'."\n";
		echo'Rename <b>'.$result[1].'</b> to the following:<br /><br />'."\n".'<input type="text" name="name" value="'.$result[1].'" maxlength="255" /><br /><br />'."\n";
		echo'<input type="submit" value="Rename Category" />'."\n".'</form>'."\n\n";

	break;

	case "cat_list":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		echo'<h1>FAQ Category List</h1>'."\n\n";

		echo'<table class="list-table">'."\n";
		echo'<tr><td class="list-heading">Category Name</td><td class="list-heading">Tools</td></tr>'."\n";

		$cat_query = 'SELECT uin,name FROM '.$db_prefix.'c ORDER BY uin ASC';
		$result = @mysql_query($cat_query) or deal_with_mysql_error('Categories Data Retrieval MySQL Error (cat_list). '.mysql_error(),'admin');
		if (mysql_num_rows($result) == "0"){
			echo'<tr><td class="list-cell" colspan="2">There are no FAQ categories in the database at the present time. <a href="'.$_SERVER[PHP_SELF].'?t=cat_new">Add a new FAQ category</a>.</td></tr>'."\n";
		} else {
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				echo'<tr><td class="list-cell">'.$row[1].'</td><td class="list-cell"><a href="'.$_SERVER[PHP_SELF].'?t=cat_edit&amp;uin='.$row[0].'">Rename</a>. <a href="'.$_SERVER[PHP_SELF].'?t=cat_delete&amp;uin='.$row[0].'">Delete</a>.</td></tr>'."\n";
			}
		}
		echo'<tr><td class="list-cell" colspan="2"><a href="'.$_SERVER[PHP_SELF].'?t=cat_new">Add a new FAQ category.</a></td></tr>'."\n";
		echo'</table>'."\n\n";

		if ($debug){
			echo'<p>MySQL Query was:<br />'.$cat_query.'</p>'."\n\n";
		}

	break;

	case "cat_new_process":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_faq_links();

		if (!$_POST[name]){
			error_message('Sorry, you forgot to enter a name for your new category, please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}

		@mysql_query('INSERT INTO '.$db_prefix.'c VALUES("","'.$_POST[name].'")') or deal_with_mysql_error('Category Insertion MySQL Error (cat_new_process). '.mysql_error(),'admin');	

		echo'<h1>Category Added</h1>'."\n\n";

		echo'<p>The category <b>'.$_POST[name].'</b> has been successfully added.</p>';

		echo'<p> - <a href="'.$_SERVER[PHP_SELF].'?t=cat_new">Add another FAQ category.</a><br /><br />'."\n".' - <a href="'.$_SERVER[PHP_SELF].'?t=cat_list">View a list of all FAQ categories currently in the database.</a><br /><br />'."\n\n".' - <a href="'.$_SERVER[PHP_SELF].'?t=index">Return to the '.$site_name.' FAQ admin area index.</a></p>'."\n\n";

	break;

	case "cat_new":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}

		admin_faq_links();

		echo'<h1>Add FAQ Category</h1>'."\n\n";

		echo'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="cat_new_process">'."\n";
		echo'Please enter the name of the category you would like to add:<br /><br />'."\n";
		echo'<input type="text" name="name" value="'.$_GET[name].'" /><br /><br />'."\n";
		echo'<input type="submit" value="Add Category" />'."\n".'</form>'."\n\n";

	break;

	case "logout":

		setcookie("faq_username", NULL, time()-86400, "/", ".$site_domain", 0);
		$_COOKIE["faq_username"] = NULL;

		setcookie("faq_password", NULL, time()-86400, "/", ".$site_domain", 0);
		$_COOKIE["faq_password"] = NULL;

		admin_header();

		echo'<p>You are now logged out of the '.$site_name.' FAQ manager admin area.</p>'."\n\n";

	break;

	case "process_login":

		if (!admin_login_check($_POST[admin_u],md5($_POST[admin_p]))){
			admin_header();
			error_message('Login attempt failed.');
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}

		setcookie("faq_username", $_POST[admin_u], time()+60*60*24*365, "/", ".$site_domain", 0);
		$_COOKIE["faq_username"] = $_POST[admin_u];

		setcookie("faq_password", md5($_POST[admin_p]), time()+60*60*24*365, "/", ".$site_domain", 0);
		$_COOKIE["faq_password"] = md5($_POST[admin_p]);

		admin_header();

		echo'<p>You have now successfully logged in to the '.$site_name.' FAQ manager admin area. Cookies have been sent on your computer which will keep you logged in, until you <a href="'.$_SERVER[PHP_SELF].'?t=logout">log out</a>.<br /><br />'."\n".'<a href="'.$_SERVER[PHP_SELF].'?t=index">Click here</a> to proceed to the index page of the '.$site_name.' FAQ manager admin area.</p>';

	break;

	case "settings_edit_process":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_misc_links();

		if (!$_POST[db_location]){
			error_message('Sorry, no MySQL database location was specified. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if (!$_POST[db_username]){
			error_message('Sorry, no MySQL database username was specified. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if ($_POST[db_password]){
			$db_password = $_POST[db_password];
		}
		if (!$_POST[db_database]){
			error_message('Sorry, no MySQL database name was specified. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if (!$_POST[admin_username]){
			error_message('Sorry, no admin login username was specified. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if ($_POST[admin_password]){
			$admin_password = $_POST[admin_password];
		}
		if (!$_POST[site_domain]){
			error_message('Sorry, no website domain name was specified. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if (!$_POST[site_name]){
			error_message('Sorry, no website name was specified. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}
		if ($_POST[rating_switch] != '0' AND $_POST[rating_switch] != '1'){
			error_message('Sorry, no rating switch specified. Please go back and try again.');
			admin_footer();
			@mysql_close();
			exit;
		}

		/* Change quotation marks to their HTML equivalents for those with quotation marks in their website names. */
		$_POST[site_name] = str_replace(array('"', "'"), array('&quot;', '&#039;'), stripslashes($_POST[site_name]));

		$new_entry = "<?php\n\n\$db_location = '$_POST[db_location]';\n\$db_username = '$_POST[db_username]';\n\$db_password = '$db_password';\n\$db_database = '$_POST[db_database]';\n\$db_prefix = '$_POST[db_prefix]';";
		$new_entry .= "\n\n\$site_name = '$_POST[site_name]';\n\$site_domain = '$_POST[site_domain]';";
		$new_entry .= "\n\n\$rating_switch = '$_POST[rating_switch]';";
		$new_entry .= "\n\n\$admin_username = '$_POST[admin_username]';\n\$admin_password = '$admin_password';\n\n?>";

		$fl=fopen('faq-variables.php','w'); 
		fwrite($fl,$new_entry); 
		fclose($fl);

		$num_changes = "0";
		$info_changes = NULL;

		if ($_POST[db_location] != $db_location){
			$num_changes = $num_changes + 1;
			$info_changes .= 'The database location was changed from '.$db_location.' to <b>'.$_POST[db_location].'</b>.<br />'."\n";
		}
		if ($_POST[db_username] != $db_username){
			$num_changes = $num_changes + 1;
			$info_changes .= 'The database username was changed from '.$db_username.' to <b>'.$_POST[db_username].'</b>.<br />'."\n";
		}
		if ($_POST[db_password]){
			$num_changes = $num_changes + 1;
			$info_changes .= 'The database password was changed.<br />'."\n";
		}
		if ($_POST[db_database] != $db_database){
			$num_changes = $num_changes + 1;
			$info_changes .= 'The database name was changed from '.$db_database.' to <b>'.$_POST[db_database].'</b>.<br />'."\n";
		}
		if ($_POST[db_prefix] != $db_prefix){
			$num_changes = $num_changes + 1;
			$info_changes .= 'The database table prefix was changed from '.$db_prefix.' to <b>'.$_POST[db_prefix].'</b>.<br />';
		}
		if ($_POST[admin_username] != $admin_username){
			$num_changes = $num_changes + 1;
			$info_changes .= 'The admin area login username was changed from '.$admin_username.' to <b>'.$_POST[admin_username].'</b>. (You will now need to <a href="'.$_SERVER[PHP_SELF].'?t=login">login</a> again).<br />'."\n";
		}
		if ($_POST[admin_password]){
			$num_changes = $num_changes + 1;
			$info_changes .= 'The admin area login password was changed. (You will now need to <a href="'.$_SERVER[PHP_SELF].'?t=login">login</a> again).<br />'."\n";
		}
		if ($_POST[site_domain] != $site_domain){
			$num_changes = $num_changes + 1;
			$info_changes .= 'The website domain name was changed from '.$site_domain.' to <b>'.$_POST[site_domain].'</b>.<br />'."\n";
		}
		if ($_POST[site_name] != $site_name){
			$num_changes = $num_changes + 1;
			$info_changes .= 'The website name was changed from '.$site_name.' to <b>'.$_POST[site_name].'</b>.<br />'."\n";
		}
		if ($_POST[rating_switch] != $rating_switch){
			$num_changes = $num_changes + 1;
			if ($_POST[rating_switch]){
				$info_changes .= 'The FAQ ratings feature was switched <b>on.<br />'."\n";
			} else {
				$info_changes .= 'The FAQ ratings feature was switched <b>off.<br />'."\n";
			}
		}

		if ($num_changes == "0"){
			echo'<p>No settings were changed.</p>'."\n\n";
		} else {
			echo'<h1>Settings Edited</h1>'."\n\n";
			echo'<p>The settings for the FAQ Manager were changed successfully. The changes made were as follows:<br /><br />'.$info_changes.'</p>'."\n\n";
		}

	break;

	case "settings_edit":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}

		admin_misc_links();

		echo'<h1>View / Edit Settings</h1>'."\n\n";

		echo'<p>The settings for the FAQ Manager may be changed using the form below:</p>';

		echo'<table class="list-table">'."\n".'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="settings_edit_process" />';

		echo'<tr><td class="list-heading" colspan="2">Admin Login Settings</td></tr>'."\n";

		echo'<tr><td class="list-cell">Username</td><td class="list-cell"><input type="text" size="20" name="admin_username" value="'.$admin_username.'" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Password <span class="smallprint">(Only enter if changing password)</span></td><td class="list-cell"><input type="password" size="20" name="admin_password" value="" /></td></tr>'."\n";

		echo'<tr><td class="list-heading" colspan="2">MySQL Database Settings</td></tr>'."\n";

		echo'<tr><td class="list-cell">Database Location</td><td class="list-cell"><input type="text" size="20" name="db_location" value="'.$db_location.'" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Database Username</td><td class="list-cell"><input type="text" size="20" name="db_username" value="'.$db_username.'" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Database Password <span class="smallprint">(Only enter if changing password)</span></td><td class="list-cell"><input type="password" size="20" name="db_password" value="" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Database Name</td><td class="list-cell"><input type="text" size="20" name="db_database" value="'.$db_database.'" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Database Prefix</td><td class="list-cell"><input type="text" size="20" name="db_prefix" value="'.$db_prefix.'" /></td></tr>'."\n";

		echo'<tr><td class="list-heading" colspan="2">Website Settings</td></tr>'."\n";

		echo'<tr><td class="list-cell">Site Domain <span class="smallprint">(Examples: starsol.co.uk, mojoo.com)</span></td><td class="list-cell"><input type="text" size="20" name="site_domain" value="'.$site_domain.'" /></td></tr>'."\n";
		echo'<tr><td class="list-cell">Site Name <span class="smallprint"> (Examples: Starsol Scripts, Mojoo Directory)</span></td><td class="list-cell"><input type="text" size="20" name="site_name" value="'.$site_name.'" /></td></tr>'."\n";

		echo'<tr><td class="list-heading" colspan="2">Other Settings</td></tr>'."\n";

		echo'<tr><td class="list-cell">Ratings</span></td><td class="list-cell"><select name="rating_switch">';
		if ($rating_switch){
			echo'<option value="1" selected>On</option><option value="0">Off</option>';
		} else {
			echo'<option value="1">On</option><option value="0" selected>Off</option>';
		}
		echo'</select></td></tr>'."\n";

		echo'<tr><td class="list-heading" colspan="2"><input type="submit" value="Edit Settings" /></td></tr>'."\n";

		echo'</form>'."\n".'</table>'."\n\n";

	break;

	case "login":

		admin_login_form();

	break;

	case "support":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_misc_links();

		echo'<h1>Contact Technical Support</h1>'."\n\n";

		echo'<p>If you are having problems with the '.$product.' script, please contact the author, Rupe Parnell, using the form on <a href="http://www.starsol.co.uk/support.php?email='.urlencode($admin_email).'&amp;domain='.urlencode($site_domain).'&amp;url='.urlencode($site_url).'&amp;product='.urlencode($product.' '.$version).'" target="_blank">this page</a>.<br /><br />'."\n".'Please supply as much information as you can about your server, such as the versions of PHP and MySQL you are running.</p>'."\n\n";

	break;

	case "version":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_misc_links();

		if (!$version){
			error_message('Sorry, no version information could be found. If you have not manually edited the <i>faq_includes.php</i> file, please <a href="'.$_SERVER[PHP_SELF].'?t=support">contact technical support</a>.');
			admin_footer();
			@mysql_close();
			exit;
		}

		echo'<h1>Version Information</h1>'."\n\n";

		echo'<p>You are currently running version <b>'.$version.'</b> of the '.$product.' script.<br /><br />'."\n".'The latest version is <img src="http://www.starsol.co.uk/images/versions/faq.gif" />.<br /><br />'."\n".'You may download the latest version from Starsol Scripts at <a href="http://www.starsol.co.uk/scripts/" target="_blank">www.starsol.co.uk/scripts</a>.</p>'."\n\n";

	break;

	case "ratings":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_misc_links();			

		echo'<h1>FAQ Ratings Tools</h1>'."\n\n";

		if ($rate_switch){
			echo'<p>FAQ ratings are currently switched <b>on</b>, allowing readers of your FAQ to rate whether they found a question/answer helpful or not. If you would prefer to not include this feature in your FAQ, you may switch it off on the <a href="'.$_SERVER[PHP_SELF].'?t=settings_edit">edit settings</a> page.</p>'."\n\n";
		} else {
			echo'<p>FAQ ratings are currently switched <b>off</b>. If you would like to allow readers of your FAQ to rate whether they found a question/answer helpful or not, you may switch the ratings feature on using the <a href="'.$_SERVER[PHP_SELF].'?t=settings_edit">edit settings</a> tools.</p>'."\n\n";
		}

		$tr = @mysql_num_rows(mysql_query('SELECT uin FROM '.$db_prefix.'ratings')) or $tr='0';
		$th = @mysql_num_rows(mysql_query('SELECT uin FROM '.$db_prefix.'ratings WHERE rating="1"')) or $th='0';
		$tnh = @mysql_num_rows(mysql_query('SELECT uin FROM '.$db_prefix.'ratings WHERE rating="0"')) or $nh='0';

		echo'<p>There is currently a total of <b>'.$tr.'</b> ratings (<b>'.$th.'</b> helpful, <b>'.$tnh.'</b> not helpful) in the '.$site_name.' FAQ database.</p>'."\n\n";

		echo'<p>If you would like to reset the ratings for every FAQ, <a href="'.$_SERVER[PHP_SELF].'?t=ratings_reset">click here</a>.</p>'."\n\n";

	break;

	case "ratings_reset_process":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_misc_links();

		if ($_POST[t] != 'ratings_reset_process'){
			error_message('Sorry, the <i>t</i> variable needs to be a post for this tool to operate.');
			admin_footer();
			@mysql_close();
			exit;
		}

		@mysql_query('DELETE FROM '.$db_prefix.'ratings') or deal_with_mysql_error('Empty Ratings Table MySQL Error (ratings_reset_process). '.mysql_error(),'admin');

		$the_faqs = @mysql_query('SELECT uin FROM '.$db_prefix.'q') or deal_with_mysql_error('FAQs Data Retrieval MySQL Error (ratings_reset_process). '.mysql_error(),'admin');
		while ($row = mysql_fetch_array($the_faqs, MYSQL_NUM)) {
			count_faq($row[0]);
		}

		echo'<h1>All Ratings Reset</h1>'."\n\n";

		echo'<p>The ratings for all FAQs have now been reset.</p>'."\n\n";

		echo'<p>Please note that users who have rated any FAQs will not be able to rate the FAQs again if they still have the <i>starsol_faq_ratings</i> cookie on their computer.</p>'."\n\n";

	break;

	case "ratings_reset":

		if (!admin_login_check($_COOKIE[faq_username],$_COOKIE[faq_password])){
			admin_login_form();
			admin_footer();
			@mysql_close();
			exit;
		}
		admin_misc_links();

		echo'<h1>Reset Ratings for all FAQs?</h1>'."\n\n";

		echo'<p style="text-align: center;">Are you certain you want to reset the ratings for all the FAQs in the '.$site_name.' FAQ database?</p>'."\n\n";

		echo'<table style="float: center; width: 50%; border: 0px; padding: 5px;">'."\n".'<tr>';
		echo'<td style="text-align: center;">'."\n".'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="ratings_reset_process" />'."\n".'<input type="submit" value="Yes, Reset" />'."\n".'</form>'."\n".'</td>'."\n";
		echo'<td style="text-align: center;">'."\n".'<form action="'.$_SERVER[PHP_SELF].'" method="post">'."\n".'<input type="hidden" name="t" value="ratings" />'."\n".'<input type="submit" value="No, Cancel" />'."\n".'</form>'."\n".'</td>'."\n";
		echo'</tr>'."\n".'</table>'."\n\n";

	break;

}

admin_footer();

@mysql_close();

?>