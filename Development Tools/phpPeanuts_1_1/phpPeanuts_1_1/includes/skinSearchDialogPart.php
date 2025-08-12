<?php $this->printPart('SelectScriptPart')  ?>
<table width=100% height=100%>
<?php $this->printPart('FilterPart')  ?>
 <tr valign=top>
	<td class=pntGroupContent>
		<script>
			 function scaleContent() {
					getElement('itemTableDiv').style.height=(window.document.body.clientHeight)-179<?php 
						if ($this->getFilterPartString()) print -16; 
						if ($this->hasFilterForm()) print -38; ?>;
					getElement('itemTableDiv').style.width=(window.document.body.clientWidth)-20;
			}
		</script>
		<div id=itemTableDiv style='height: 300px; width: 600px; overflow: auto;' valign='top'>
			<form name=dialogForm method=post action='index.php'>
				<input type=hidden value='' name='pntHandler'>
				<input type=hidden value='<?php print $this->getType() ?>' name='pntType'>
				<?php $this->printPart('ItemTablePart') ?>
			</form>
		</div>
		<script> scaleContent(); </script>
	</td>
  </tr>
</table>