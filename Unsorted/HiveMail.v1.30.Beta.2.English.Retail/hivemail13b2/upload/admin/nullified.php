<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: nullified.php,v $ - $Revision: 1.32 $
// | $Date: 2003/12/28 00:00:00 $
// | $Author: CyKuH [WTN] $
// +-------------------------------------------------------------+
error_reporting(E_ALL & ~E_NOTICE);
@set_time_limit(0);
require_once('./global.php');
// Set the default do
default_var($cmd, 'ok');
if ($cmd == 'null') {
// ############################################################################
cp_header(' &raquo; Nullification info');
cp_nav('null');
starttable('Nullified Info', '600');
tablerow(array('<b>Program Name         </b>', ': HiveMail'), true);
tablerow(array('<b>Release Version      </b>', ': 1.3 Beta 2 English'), true);
tablerow(array('<b>Developed by         </b>', ': Chen Avinadav'), true);
tablerow(array('<b>Contributions by     </b>', ': Kevin Schumacher<br />Chris Padfield<br />Richard Heyes'), true, true);
tablerow(array('<b>Designed by:         </b>', ': Everaldo Coelho'), true);
tablerow(array('<b>Home Page            </b>', ': http://www.hivemail.com'), true);
tablerow(array('<b>Retail Price         </b>', ': $130.00 United States Dollars'), true);
tablerow(array('<b>WebForum Price       </b>', ': $000.00 Always 100% Free'), true);
tablerow(array('<b>ForumRu Price        </b>', ': $000.00 Always 100% Free'), true);
tablerow(array('<b>xCGI Price           </b>', ': $000.00 Always 100% Free'), true);
tablerow(array('<b>Supplied by          </b>', ': Scoons[WTN]'), true);
tablerow(array('<b>Nullified by         </b>', ': CyKuH [WTN]'), true);
tablerow(array('<b>Distribution         </b>', ': via Via forumru and other forums'), true);
tablerow(array('<b>Protection           </b>', ': Much E-mail CallHome, Refferer and License Check'), true);
tablerow(array('<b>Language             </b>', ': PHP,MySQL'), true);
tablerow(array('<b>Respect        	</b>', ': TNO,WST,CGIHEAVEN Crew,WDYL Group,CHTeam'), true);
endtable();
// ############################################################################
	cp_footer();
}

if ($cmd == 'ok') {
// ############################################################################
 cp_header(' &raquo; None');
echo "<!-- CyKuH [WTN] -->";
	cp_footer();
}

?>