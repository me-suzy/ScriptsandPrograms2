<?php

// searchform.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $auth$
// $Id: searchform.inc.php,v 1.30 2005/06/20 10:51:00 nina Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) die('Please use index.php!');


// Stichwortsuche - keyword search
$out1 = '
<form action="search.php" method="post">
'.(SID ? '    <input type="hidden" name="'.session_name().'" value="'.session_id().'" />' : '').'
    <!-- b><a href="'.$doc.'/search.html" target="_blank">'.$r_search.'</a></b><br / -->
    <input type="hidden" name="searchformcount" value="'.$searchformcount.'" />
    <!-- hint how to combine several phrases -->
    <br />
    <label for="searchterma'.$searchformcount.'" class="center" style="margin-bottom:0px;">'.__('Search term').':</label>
    <!-- input form -->
    <input type="text" name="searchterm'.$searchformcount.'" id="searchterma'.$searchformcount.'" class="search_options" value="'.$searchterm.'" />
    &nbsp;&nbsp;&nbsp;('.__('Put the word AND between several phrases').')
    <br class="clear" />
    <br class="clear" />
    <label for="gebiet" class="center">'.__('Search area').':</label><select name="gebiet" id="gebiet" class="search_options">
<option value="all">'.__('all modules').'</option>
';

if (PHPR_CALENDAR and check_role('calendar') > 0)     $out1 .= '<option value="termine">'.__('Events')."</option>\n";
if (PHPR_FORUM and check_role('forum') > 0)           $out1 .= '<option value="forum">'.__('the forum')."</option>\n";
if (PHPR_FILEMANAGER and check_role('filemanager') > 0) $out1 .= '<option value="files">'.__('the files')."</option>\n";
if (PHPR_CONTACTS and check_role('contacts') > 0)     $out1 .= '<option value="contacts">'.__('Contacts')."</option>\n";
if (PHPR_NOTES and check_role('notes') > 0)           $out1 .= '<option value="notes">'.__('Notes')."</option>\n";
if (PHPR_QUICKMAIL == 2  and check_role('mail') > 0)  $out1 .= '<option value="mails">'.__('in mails')."</option>\n";
if (PHPR_TODO and check_role('todo') > 0)             $out1 .= '<option value="todo">'.__('Todo')."</option>\n";
if (PHPR_BOOKMARKS and check_role('bookmarks') > 0) $out1 .= '<option value="bookmarks">'.__('Bookmark')."</option>\n";
if (PHPR_RTS and check_role('helpdesk') > 0)          $out1 .= '<option value="helpdesk">'.__('Helpdesk')."</option>\n";

$out1 .= '
    </select>
    <br class="clear" />
    <br />
    <span style="align:center">
        <input type="submit" name="submit" value="'.__('Send').'" class="button2" style="align:center;" />
    </span>
</form>
';

$out = '
<form action="../search/search.php" target="_top" style="display:inline;">
    <fieldset class="navsearch">
    <input type="hidden" name="searchformcount" value="'.$searchformcount.'" />
    <input type="hidden" name="module" value="search" />
'.(SID ? '    <input type="hidden" name="'.session_name().'" value="'.session_id().'" />' : '').'
';

// special input box for nn4
if (eregi("4.7|4.6|4.5", $HTTP_USER_AGENT))
    $out .= '    <input type="text" name="searchterm'.$searchformcount.'" id="searchterm'.$searchformcount.'" size="8" value="'.__('Search').'" />';
else
    $out .= '    <input class="navsearchbox" type="text" name="searchterm'.$searchformcount.'" id="searchterm'.$searchformcount.'" value="'.__('Search').'" onfocus="this.value=\'\'" />';

$out .=
    get_go_button('arrow_search', 'image').'
    <input type="hidden" name="gebiet" value="all" />
    </fieldset>
</form>
';


#$out = '
#<form action="../search/search.php" target="_top" style="display:inline;">
#    <fieldset class="navsearch">
#    <input type="hidden" name="searchformcount" value="" />
#    <input type="hidden" name="module" value="search" />
#
#    <input class="navsearchbox" type="text" name="searchterm" id="searchterm" value="'.__('Search').'" onfocus="this.value=\'\'" /><input type="submit" class="navSearchButton" value="" />
#    <input type="hidden" name="gebiet" value="all" />
#    </fieldset>
#</form>
#';


$out = '
<form action="../search/search.php" target="_top">
<fieldset class="navsearch">
<input class="navsearchbox" type="text" name="searchterm" id="searchterm" value="Suche" onfocus="this.value=\'\'"/>
<input type="submit" class="navSearchButton" value="" />
<input type="hidden" name="searchformcount" value="" />
<input type="hidden" name="module" value="search" />
<input type="hidden" name="gebiet" value="all" />
</fieldset>
</form>
';

?>