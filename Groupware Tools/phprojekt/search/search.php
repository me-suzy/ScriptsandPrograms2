<?php

// search.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: search.php,v 1.10 2005/06/17 20:33:46 paolo Exp $

$options_module = 1;

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

$_SESSION['common']['module'] = 'search';

$output = '';
echo set_page_header();
include_once($path_pre.'lib/navigation.inc.php');

$show_form = 1;
$s_var = 'searchterm'.$searchformcount;
if ($searchformcount) $searchterm = $$s_var;
include_once($path_pre.'lib/searchform.inc.php');

echo '
<div class="outer_content">
    <div class="content">
';
include_once('search_view.php');
echo '
    </div>
</div>

</body>
</html>
';

?>
