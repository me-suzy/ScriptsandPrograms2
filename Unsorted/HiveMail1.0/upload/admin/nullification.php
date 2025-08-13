<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: templateset.php,v $
// | $Date: 2002/11/10 13:26:38 $
// | $Revision: 1.18 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; Nullification Info');

// ############################################################################
// Set the default do
if (!isset($do)) {
	$do = 'modify';
}

// ############################################################################
// List the Info          
starttable('Nullification Info', '90%');
tablerow(array('<b>Program Name         :</b>', ' HiveMail                                       '), true);
tablerow(array('<b>Release Version      :</b>', ' 1.0                                            '), true);
tablerow(array('<b>Program Author       :</b>', ' Chen Avinadav                                  '), true);
tablerow(array('<b>Home Page            :</b>', ' http://www.hivemail.com                        '), true);
tablerow(array('<b>Retail Price         :</b>', ' $130.00 United States Dollars                  '), true);
tablerow(array('<b>WebForum Price       :</b>', ' $000.00 Always 100% Free                       '), true);
tablerow(array('<b>ForumRu Price        :</b>', ' $000.00 Always 100% Free                       '), true);
tablerow(array('<b>xCGI Price           :</b>', ' $000.00 Always 100% Free                       '), true);
tablerow(array('<b>Supplied by          :</b>', ' CyKuH [WTN]                                    '), true);
tablerow(array('<b>Nullified by         :</b>', ' CyKuH [WTN]                                    '), true);
tablerow(array('<b>Tested by            :</b>', ' WTN Team                                       '), true);
tablerow(array('<b>Packaged by          :</b>', ' WTN Team                                       '), true);
tablerow(array('<b>Distribution         :</b>', ' via WebForum, ForumRU and associated file dumps'), true);
tablerow(array('<b>Protection           :</b>', ' Much E-mail CallHome, Refferer and License Check'), true);
tablerow(array('<b>Language             :</b>', ' PHP,MySQL                                      '), true);
tablerow(array('<b>Extra Note           :</b>', ' Read release info                              '), true);
endtable();
echo"<br>";
starttable('', '450');
textrow('<b>Script Status        : Stable</b><BR>
This software contained several callback features!<BR><center>&copy WTN Team `2000 - `2002');
endtable();

cp_footer();
?>