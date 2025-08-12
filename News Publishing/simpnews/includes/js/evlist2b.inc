<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language=JavaScript>
<!--
var openEntry=0;
function entryAction(id)
{
	if(openEntry==id)
		collapseEntry(id);
	else
		expandEntry(id);
}
function expandEntry(id)
{
	if(openEntry>0 && openEntry!=id)
	{
		collapseEntry(id);
	}
	document.getElementById('entry'+id).style.display='';
	document.getElementById('entry'+id).style.visibility='visible';
	document.getElementById('entryimg'+id).src='<?php echo $url_gfx?>/<?php echo $collapsepic?>';
	document.getElementById('entryimg'+id).title='<?php echo $l_collapse?>';
	openEntry=id;
}
function collapseEntry(id)
{
	document.getElementById('entry'+id).style.display='none';
	document.getElementById('entry'+id).style.visibility='hidden';
	document.getElementById('entryimg'+id).src='<?php echo $url_gfx?>/<?php echo $expandpic?>';
	document.getElementById('entryimg'+id).title='<?php echo $l_expand?>';
	openEntry=0;
}
function picpreload()
{
	expandImg = new Image();
	expandImg.src = '<?php echo $url_gfx?>/<?php echo $expandpic?>';
	collapseImg = new Image();
	collapseImg.src = '<?php echo $url_gfx?>/<?php echo $collapsepic?>';
}
//  End -->
</script>
