<html><head><title><? echo "$seitenname"; ?></title></head><body>
<?php
if(!$id || !$userid)
        exit;
if($text)
{
        include('./prepend.inc.php');
        report($id, $text, $userid);
        ?>
Your report has been sent and will be checked asap. Please reload this site to go on earning points.
        <?php
}else{
        ?>
<form method="post" action="./report.php">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="userid" value="<?php echo $userid; ?>">
<textarea name="text"></textarea>
<input type="submit" value="Submit">
</form>
        <?php
}
?>
</body></html>