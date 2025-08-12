<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingLite                Version 1.1.0                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
include "config.inc.php";
include "templates/secure.php";
include "templates/header.php";
if (isset($id)) {
if ($page == send) {
$sql = "SELECT * FROM $mysql_table WHERE id = '$id'";
$result = mysql_db_query($mysql_base, $sql, $mysql_link);
while ($i = mysql_fetch_array($result)) {
mail($i[email], "Message from $name", $message,
     "From: $mail\nReply-To: $mail\nX-Mailer: PHP/" . phpversion());
}
echo $err_mes_top.$lang[39].$suc_mes_bottom;
include "templates/footer.php";
die;
} else {
?>
<form action="email.php?l=<?php echo $l; ?>" method="post">
<input class=input type=hidden name="id" value="<?php echo $id; ?>">
<input class=input type=hidden name="page" value="send">
<center><span class=head><?php echo $lang[38]; ?><?php echo $user; ?></span>
<Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black bgcolor=<?php echo $color3; ?>>
<tr><td><?php echo $lang[40]; ?></td><td><input class=input type=text name=name></td></tr>
<tr><td><?php echo $lang[41]; ?></td><td><input class=input type=text name=mail></td></tr>
<tr><td><?php echo $lang[42]; ?></td><td><textarea class=textarea name=message cols=40 rows=15></textarea></td></tr>
<tr><td colspan=2 align=right><input class=input type=submit value="<?php echo $lang[43]; ?>"></td></tr>
</table>
</form>
<?php
}
} else {
if ($action == feedback) {
mail($adminmail, "Feedback from $name", $message,
     "From: $mail\nReply-To: $mail\nX-Mailer: PHP/" . phpversion());
echo $err_mes_top.$lang[44].$suc_mes_bottom;
include "templates/footer.php";
die;
    }
?>
<form action="email.php?l=<?php echo $l; ?>" method="post">
<input class=input type=hidden name="action" value="feedback">
<center><span class=head><?php echo $lang[23]; ?></span>
<Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black bgcolor=<?php echo $color3; ?>>
<tr><td><?php echo $lang[40]; ?></td><td><input class=input type=text name=name></td></tr>
<tr><td><?php echo $lang[41]; ?></td><td><input class=input type=text name=mail></td></tr>
<tr><td><?php echo $lang[42]; ?></td><td><textarea class=textarea name=message cols=40 rows=15></textarea></td></tr>
<tr><td colspan=2 align=right><input class=input type=submit value="<?php echo $lang[43]; ?>"></td></tr>
</table>
</form>
<?php
}
include "templates/footer.php";
?>