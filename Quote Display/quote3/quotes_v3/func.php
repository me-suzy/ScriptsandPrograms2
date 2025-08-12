<?php
function outputLine($theQuote, $theRef, $theLink)
	{
    ?>
<div align="center"><?php echo $theQuote; ?> - <a href="<?php echo $theLink; ?>" target="_blank"><?php echo $theRef; ?></a></div>
	<?php
    }
?>

<?php
function outputTable($theQuote, $theRef, $theLink)
	{
    ?>
<table border=1 width=850px align="center">
	<tr>
    <td bgcolor=#E7E7E7><div align="center"><?php echo $theQuote; ?> - <a href="<?php echo $theLink; ?>" target="_blank"><?php echo $theRef; ?></a></div></td>
    </tr>
</table>
	<?php
    }
?>