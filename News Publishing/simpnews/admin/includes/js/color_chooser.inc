<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
<!--
function color_picker(fieldname)
{
	document.<?php echo $ccform?>.colorfield.value=fieldname;
	openWindow3('colorwheel.php?<?php echo "$langvar=$act_lang"?>','Colors',20,20,450,400);
}
function color_chooser(fieldname)
{
	document.<?php echo $ccform?>.colorfield.value=fieldname;
	openWindow3('colorchooser.php?<?php echo "$langvar=$act_lang"?>','Colors',20,20,450,400);
}
function setcolor(colorcode)
{
	eval('document.<?php echo $ccform?>.' + document.<?php echo $ccform?>.colorfield.value + '.value=\'' + colorcode + '\'');
	eval(document.<?php echo $ccform?>.colorfield.value + 'prev.bgColor=\'' + colorcode + '\'');
}
function color_prev(fieldname)
{
	eval('colorcode=document.<?php echo $ccform?>.'+fieldname+'.value');
<?php
	if(is_ns6())
	{
		echo "prevfield=document.getElementById(fieldname+'prev');\n";
		echo "prevfield.bgColor=colorcode;\n";
	}
	else
	{
		echo "eval(fieldname + 'prev.bgColor=' + '\''+colorcode+'\'');\n";

	}
?>
	return;
}
// -->
</script>
