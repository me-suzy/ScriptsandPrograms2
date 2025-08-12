<table width=100% >
<?php $this->printPart('FilterPart')  ?>
 <tr valign=top>
	<td class=pntGroupContent>

<?php /*  Switch this html on when menu, info and buttons (remove php tag)
*/ ?>
		<script>
			 function scaleContent() {
					getElement('itemTableDiv').style.height=(window.document.body.clientHeight)-175;	
					getElement('itemTableDiv').style.width=(window.document.body.clientWidth)-130;
			}
			// pop up report, overrides general.js
			function tdl(obj, itemId) {
				popUpWindowAutoSizePos(tdlGetHref(obj, itemId)+'&pntHandler=ReportPage');
			}
		</script>
		<div id=itemTableDiv style='height: 330px; width: 600px; overflow: auto;'>
			<form name=itemTableForm method=post action='index.php'>
				<input type=hidden value='SelectionReportPage' name='pntHandler'>
				<input type=hidden value='<?php print $this->getType() ?>' name='pntType'>
				<?php $this->printPart('ItemTablePart') ?>
			</form>

<?php /* Switch this html on when menu, info and buttons
*/ ?>	
	</div>
		<script> scaleContent(); </script>

	</td>
  </tr>
</table>