<?php /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2002  FishNet, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,
USA.

   N. Michael Brennen
   FishNet(R), Inc.
   850 S. Greenville, Suite 102
   Richardson,  TX  75081
   http://www.fni.com/
   mbrennen@fni.com
   voice: 972.669.0041
   fax:   972.669.8972
*/

require('./admin.php');

// this script processes accumulated flat file accumulated batch
// orders that have been created with a script like the 'cust.fixlen'
// scripts in the ~/cust1 and ~/cust2 directories.  The files are
// encrypted and moved to a private FTP directory.

$mln=512;
$headmail="mail -s Headers ORDEREMAIL";
$linemail="mail -s Details ORDEREMAIL";

$t=date("ymdHi",time());
// customize the file path below to fit your installation.
$docpath="/CUSTOMIZE/THIS/PATH/ACCOUNT";
$tperm="444";
$operm="220";

system(EscapeShellCmd("mv $docpath/OHEADFILE $docpath/OHEADFILE.$t"));
system(EscapeShellCmd("chmod $tperm $docpath/OHEADFILE.$t"));
system(EscapeShellCmd("touch $docpath/OHEADFILE"));
system(EscapeShellCmd("chmod $operm $docpath/OHEADFILE"));
// system(EscapeShellCmd("chown ORDACCT.nogroup $docpath/OHEADFILE"));

/*
$op=popen(EscapeShellCmd(stripslashes($headmail)),"w");
$oh=fopen("$docpath/OHEADFILE.$t","r");
$t=fgets($oh,$mln);
while($t){
	fputs($op,$t);
	$t=fgets($oh,$mln);
}
pclose($op);
*/
echo "Order headers processed....<br />\n";

system(EscapeShellCmd("mv $docpath/OLINEFILE $docpath/OLINEFILE.$t"));
system(EscapeShellCmd("chmod $tperm $docpath/OLINEFILE.$t"));
system(EscapeShellCmd("touch $docpath/OLINEFILE"));
system(EscapeShellCmd("chmod $operm $docpath/OLINEFILE"));
// system(EscapeShellCmd("chown ORDACCT.nogroup $docpath/OLINEFILE"));

/*
$op=popen(EscapeShellCmd(stripslashes($linemail)),"w");
$oh=fopen("$docpath/OLINEFILE.$t","r");
$t=fgets($oh,$mln);
while($t){
	fputs($op,$t);
	$t=fgets($oh,$mln);
}
pclose($op);
*/

echo "Order line items processed....<br />\n";

?>
<p>
<a href="/<?php echo $maintdir ?>/index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>">
Return to Central Maintenance
</a><br />
