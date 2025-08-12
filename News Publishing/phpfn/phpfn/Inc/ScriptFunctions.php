<?

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

// Handle twisties
if ($ShowTwistie == 1)
{
	$Collapsed='4';
	$Expanded='6';
}
if ($ShowTwistie == 2)
{
	$Collapsed='+';
	$Expanded='-';
}

// Include the script for the popup, if required
if ($FullNewsDisplayMode == 3)
{
	?>
	<SCRIPT language="JavaScript" type="text/javascript">
		<!--
		function ViewArticle(ArticleID)
		{
			window.open("<?= $NewsDir ?>/View.php?ArticleID=" + ArticleID, "NewsArticle", "width=<?=$PopupWidth ?>,height=<?=$PopupHeight ?>,resizable=yes,scrollbars=yes")
		}
		//-->
	</SCRIPT>		
	<?php
}

if ($InitiallyShowHeadlinesOnly == 1)
{
	?>
	<SCRIPT language="JavaScript" type="text/javascript">
		function exp(evt, strTag, strAttribute)
		{
			var elem = document.getElementsByTagName(strTag); 
			var e = (window.event) ? window.event : evt;

			var elem1;
			if (e.srcElement)
				elem1 = e.srcElement;
			else if (e.target)
				elem1 = e.target;

			// Iterate through the nodes to toggle the twistie
			for (var i=0;i<elem1.childNodes.length;i++)

			<?php
			if (($ShowTwistie == 1) || ($ShowTwistie == 2) )
			{
				?>
				if ( (elem1.childNodes[i].innerText == "<?=$Collapsed?>") || (elem1.childNodes[i].innerText == "<?=$Expanded?>") )
					elem1.childNodes[i].innerText == "<?=$Collapsed?>" ? elem1.childNodes[i].innerText= "<?=$Expanded?>" : elem1.childNodes[i].innerText = "<?=$Collapsed?>"; 
				<?php
			}
			?>

			// Iterate through the nodes to toggle the visibility
			for (var i =0;i<elem.length;i++)
				if (elem[i].getAttribute(strAttribute)=="yes") 
					elem[i].style.display=='none'? elem[i].style.display='block':elem[i].style.display='none'; 
		}
	</SCRIPT>
	<?php
}
?>