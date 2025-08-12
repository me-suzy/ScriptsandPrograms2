<?php
/**
* Admin Footer
*
* The main admin interface footer layout file.  Editing
* this file is not recommended unless you know what you're
* doing :)
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
*/
?>
<br>
	<center>
		<span class="footer">
		<a href="http://www.snipegallery.com">Snipe Gallery 
		<?php echo $cfg_program_version ?></a> by 
		<a href="http://www.snipe.net" title="Snipe.Net">Snipe.Net</a>
		</span>
	</center>
</center>
</td>
</tr>
</table>

<?php if ($show_crop_js==1) { ?>

<script type="text/javascript">
<!--

	SET_DHTML("theCrop"+MAXOFFLEFT+0+MAXOFFRIGHT+484+MAXOFFTOP+0+MAXOFFBOTTOM+395+RESIZABLE+MAXWIDTH+<?php echo $max_width; ?>+MAXHEIGHT+<?php echo $max_height; ?>+MINHEIGHT+<?php echo $cfg_minthumb_width; ?>+MINWIDTH+<?php echo $cfg_minthumb_height; ?>,"theImage"+NO_DRAG);

	dd.elements.theCrop.moveTo(dd.elements.theImage.x, dd.elements.theImage.y);
	dd.elements.theCrop.setZ(dd.elements.theImage.z+1);
	dd.elements.theImage.addChild("theCrop");
	dd.elements.theCrop.defx = dd.elements.theImage.x;

	function my_DragFunc()
	{
		dd.elements.theCrop.maxoffr = dd.elements.theImage.w - dd.elements.theCrop.w;
		dd.elements.theCrop.maxoffb = dd.elements.theImage.h - dd.elements.theCrop.h;
		dd.elements.theCrop.maxw    = <?php echo $cfg_maxthumb_width; ?>;
		dd.elements.theCrop.maxh    = <?php echo $cfg_maxthumb_height; ?>;
	}

	function my_ResizeFunc()
	{
		dd.elements.theCrop.maxw = (dd.elements.theImage.w + dd.elements.theImage.x) - dd.elements.theCrop.x;
		dd.elements.theCrop.maxh = (dd.elements.theImage.h + dd.elements.theImage.y) - dd.elements.theCrop.y;
	}
	
	function my_Submit()
	{
		self.location.href = 'crop.php?crop=1&page=<?php echo $_REQUEST['page']; ?>&gallery_id=<?php echo $_REQUEST['gallery_id']; ?>&image_id=<?php echo $_REQUEST['image_id']; ?>&croptype=<?php echo $_REQUEST['croptype']; ?>&sx=' + 
			(dd.elements.theCrop.x - dd.elements.theImage.x) + '&sy=' + 
			(dd.elements.theCrop.y - dd.elements.theImage.y) + '&ex=' +
			((dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w) + '&ey=' +
			((dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h);
	}
	
	function my_SetResizingType(proportional)
	{
		if (!proportional)
		{
			dd.elements.theCrop.scalable  = 0;
			dd.elements.theCrop.resizable = 1;
			
		}
		else
		{
			dd.elements.theCrop.scalable  = 1;
			dd.elements.theCrop.resizable = 0;
		}
	}
	
//-->
</script>

<?php } ?>
</body>
</html>