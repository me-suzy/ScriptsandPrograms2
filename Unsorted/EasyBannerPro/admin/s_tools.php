<?PHP

#################################################
##                                             ##
##              Easy Banner Pro                ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                 Version 2.8                 ##
##             copyright (c) 2003              ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################


include("./common.php");
check_session ('sponsors');

switch ($HTTP_GET_VARS[action]) {
case 'packages_home'		: packages_home();
case 'package_edit'			: package_edit($HTTP_GET_VARS[number]);
case 'package_delete'		: package_delete($HTTP_GET_VARS[number]);
}

switch ($HTTP_POST_VARS[action]) {
case 'packages_home'	: packages_home();
case 'package_edited'	: package_edited($HTTP_POST_VARS);
case 'package_added'	: package_added($HTTP_POST_VARS);
}

#########################################################################
#########################################################################
#########################################################################

function packages_home() {
global $s;
check_session ('sponsors');
include('./_head.txt');
echo $s[info];
echo iot('Packages (Offers) for Your Sponsors');
?>
<table border="0" width="600" cellspacing="10" cellpadding="2" class="table1"><tr><td align="center">
<table border="0" width="500" cellspacing="1" cellpadding="2">
<form action="s_tools.php" method="post">
<input type="hidden" name="action" value="package_added">
<tr><td align="center" colspan="3"><span class="text13blue"><b>Create a new package</b></span></td></tr>
<tr>
<td align="left"><span class="text13">Description</span></td>
<td align="left" colspan="2"><input class="field1" type="text" size="65" maxlength="100" name="descr"></td></tr>
<tr><td align="center" colspan="3"><span class="text10">Description is the only info your sponsors can see. It means it should contain both price and bonus (if any)</span></td></tr>
<tr><td align="left"><span class="text13">Price</span></td>
<td align="left" colspan="2"><input class="field1" type="text" size="10" maxlength="10" name="price"> <span class="text10">Only number, not a monetary unit</span></td></tr>
<tr><td align="left"><span class="text13">Bonus</span></td>
<td align="left" colspan="2"><input class="field1" type="text" size="10" maxlength="10" name="bonus" value="0"><span class="text13"> %</span></td></tr>
<td align="center" colspan="3"><span class="text13">Complete HTML of the link or button where your sponsor can pay. By clicking on this link or button your sponsors should get page of your payment processor.<br></span>
<span class="text10">All between '&lt;form action.....' and '&lt;/form&gt;' (incl. the form tag) if it should be a button, or anything as '&lt;a href="...."&gt;Click here to pay now&lt;/a&gt;' if it should be a link</span><br>
<textarea class="field1" name="payhtml" rows="15" cols="80"></textarea></td></tr>
<tr><td align="center" colspan="3"><input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</form></table></td></tr></table><br>
<table border="0" width="600" cellspacing="10" cellpadding="2" class="table1"><TR><TD align="center">
<table border="0" width="500" cellspacing="1" cellpadding="2">
<form action="s_tools.php" method="get">
<input type="hidden" name="action" value="package_edit">
<tr><td align="center" colspan="3"><span class="text13blue"><b>View/edit/delete an existing package</b></span></td></tr>
<tr><td align="center"><select class="field1" name="number">
<?PHP
$q = dq("select number,descr from $s[pr]s_packs order by descr");
while ($r = mysql_fetch_row($q)) echo "<option value=\"$r[0]\">$r[1]</option>";
echo '</select><tr><td align="center" colspan="3"><input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</form></table></td></tr></table><br><br>';
echo iot('Info');
echo '<span class="text10"><b>How it works</b><br>You create any number of packages here. Sponsored users can select from these packages when they want to add funds to their accounts. Once a sponsored user orders a package, he/she gets a button or link leading to your payment processor (for example PayPal or any other). This button or link will be based on the HTML which you enter into the "Complete HTML" field above. Once you will be informed by your payment processor that the payment is completed, you have to go into the Admin Area of Easy Banner Pro and mark that order as paid. When it happens, the user can immediately use the purchased funds to create a new ad or to add new impressions or clicks to an existing ad.<br><br>
<b>What is ...<br>
Price</b> A real price which your users pay for a package<br>
<b>Bonus</b> You can add a free bonus to more extensive packages to stimulate users to purchase them. Sample: If the base price of a package is $500 and you add a 20% bonus, then the user will pay $500 but he/she can use impressions or clicks for $600.
<br><br>
<span class="text10blue">We recommend to use <a target="_blank" class="link10" href="http://www.2checkout.com/cgi-bin/aff.2c?affid=56325"><b>2CheckOut</a> as your payment processor. They are quality and cheap.
<br><br></span>
<br><br>';
include('./_footer.txt');
exit;
}

#########################################################################

function package_added($data) {
global $s;
check_session ('sponsors');
if ( (!$data[descr]) OR (!$data[price]) OR (!$data[payhtml]) ) problem('Missing fields. Please try again.');
if ( (!is_numeric($data[price])) OR (!is_numeric($data[bonus])) ) problem('Please enter only numbers to these fields: Price, Bonus.');
if ($data[bonus]) $value = ($data[price]*($data[bonus]/100)) + $data[price]; else $value = $data[price];
$data[descr] = replace_once_text($data[descr]); $data[payhtml] = replace_once_html($data[payhtml]);
$q = dq("insert into $s[pr]s_packs values(NULL,'$data[descr]','$data[price]','$data[bonus]','$value','$data[payhtml]')",1);
$s[info] = iot('A new package has been added');
packages_home();
}

#########################################################################

function package_edit($number) {
global $s;
check_session ('sponsors');
$q = dq("select * from $s[pr]s_packs where number = '$number'",1);
$data = mysql_fetch_assoc($q);
$data[payhtml] = htmlspecialchars(unreplace_once_html($data[payhtml]));
include('./_head.txt');
echo $s[info];
echo iot('Edit an existing sponsor package');
?>
<table border="0" width="600" cellspacing="5" cellpadding="2" class="table1"><tr><td align="center">
<table border="0" width="500" cellspacing="1" cellpadding="2">
<form action="s_tools.php" method="post">
<input type="hidden" name="action" value="package_edited">
<input type="hidden" name="number" value="<?PHP echo $data[number] ?>">
<tr>
<td align="left"><span class="text13">Description</span></td>
<td align="left" colspan="2"><input class="field1" type="text" size="65" maxlength="100" name="descr" value="<?PHP echo $data[descr]; ?>"></td></tr>
<tr><td align="center" colspan="3"><span class="text10">Description is the only info your sponsors can see. It means it should contain both price and bonus (if any)</span></td></tr>
<tr><td align="left"><span class="text13">Price</span></td>
<td align="left" colspan="2"><input class="field1" type="text" size="10" maxlength="10" name="price" value="<?PHP echo $data[price]; ?>"> <span class="text10">Only number</span></td></tr>
<tr><td align="left"><span class="text13">Bonus</span></td>
<td align="left" colspan="2"><input class="field1" type="text" size="10" maxlength="10" name="bonus" value="<?PHP echo $data[bonus]; ?>"> <span class="text13">%</span></td></tr>
<tr><td align="center" colspan="3"><span class="text13">Complete HTML of the link or button where your sponsor can pay. By clicking on this link or button your sponsors should get page of your payment processor.<br></span>
<span class="text10">All between '&lt;form action.....' and '&lt;/form&gt;' (incl. the form tag) if it should be a button, or anything as '&lt;a href="...."&gt;Click here to pay now&lt;/a&gt;' if it should be a link</span><br>
<textarea class="field1" name="payhtml" rows="15" cols="80"><?PHP echo $data[payhtml]; ?></textarea></td></tr>
<tr><td align="center" colspan="3"><input type="submit" name="A1" value="Submit" class="button1">
</td></tr></form></table>
<br><a href="s_tools.php?action=package_delete&number=<?PHP echo $data[number]; ?>">Delete this package</a><br>
</td></tr></table><br><br>
<?PHP
echo iot('Info');
echo '<span class="text10"><b>How it works</b><br>You create any number of packages here. Sponsored users can select from these packages when they want to add funds to their accounts. Once a sponsored user orders a package, he/she gets a button or link leading to your payment processor (for example PayPal or any other). This button or link will be based on the HTML which you enter into the "Complete HTML" field above. Once you will be informed by your payment processor that the payment is completed, you have to go into the Admin Area of Easy Banner Pro and mark that order as paid. When it happens, the user can immediately use the purchased funds to create a new ad or to add new impressions or clicks to an existing ad.<br><br>
<b>What is ...<br>
Price</b> A real price which your users pay for a package<br>
<b>Bonus</b> You can add a free bonus to more extensive packages to stimulate users to purchase them. Sample: If the base price of a package is $500 and you add a 20% bonus, then the user will pay $500 but he/she can use impressions or clicks for $600.
<br></span>
<br><br>';
include('./_footer.txt');
exit;
}

#########################################################################

function package_edited($data) {
global $s;
check_session ('sponsors');
$q = dq("select number from $s[pr]s_packs where number = '$data[number]'",1);
$x = mysql_fetch_row($q);
if (!$x[0]) problem('The selected package does not exist');
if ( (!$data[descr]) OR (!$data[price]) OR (!$data[payhtml]) ) problem('Missing fields. Please try again.');
if ( (!is_numeric($data[price])) OR (!is_numeric($data[bonus])) ) problem('Please enter only numbers to these fields: Price, Bonus.');
$data[descr] = replace_once_text($data[descr]); $data[payhtml] = replace_once_html($data[payhtml]);
if ($data[bonus]) $value = ($data[price]*($data[bonus]/100)) + $data[price]; else $value = $data[price];
$q = dq("update $s[pr]s_packs set descr = '$data[descr]', price = '$data[price]', bonus = '$data[bonus]', value = '$value', payhtml = '$data[payhtml]' where number = '$data[number]'",1);
$s[info] = iot('Selected package has been edited');
package_edit($data[number]);
}

#########################################################################

function package_delete($number) {
global $s;
check_session ('sponsors');
dq("delete from $s[pr]s_packs where number = '$number'",1);
$s[info] = iot('Selected package has been deleted');
packages_home();
}

#########################################################################

?>