<table width=100% height=100% class=pntGroupContent>
<?php $this->printPart('FilterPart')  ?>
 <tr valign=top>
	<td height=100%>
		<script>
			 function scaleContent() {
					getElement('itemTableDiv').style.height=(window.document.body.clientHeight)-179<?php 
						if ($this->getFilterPartString()) print -16; ?>;
					getElement('itemTableDiv').style.width=(window.document.body.clientWidth)-130;
			}
		</script>
		<div id=itemTableDiv style='height: 300px; width: 600px; overflow: auto;'>
			<form name=itemTableForm method=post action='index.php'>
				<input type=hidden value='DeleteMarkedAction' name='pntHandler'>
				<input type=hidden value='<?php print $this->getPropertyType() ?>' name='pntType'>
				<input type=hidden value='<?php print $this->getThisPntContext() ?>' name='pntContext'>
				<?php $this->printPart('ItemTablePart') ?>
		</form>
		</div>
  </tr>
		<script> scaleContent(); </script>
	</td>
</table>