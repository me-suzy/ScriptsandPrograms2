		<script>
			 function scaleContent() {
					getElement('itemTableDiv').style.height=(window.document.body.clientHeight)-175;	
					getElement('itemTableDiv').style.width=(window.document.body.clientWidth)-130;
			}
		</script>
		<div id=itemTableDiv style='height: 300px; width: 600px; overflow: auto;'>
			<form name=detailsForm method=post action='index.php'>
				<input type=hidden name='pntHandler' value='SaveAction'>
				<input type=hidden name='id' value='<?php print $this->getRequestParam('id') ?>'>
				<input type=hidden name='pntType' value='<?php print $this->getType() ?>'>
				<input type=hidden name='pntProperty' value=''>
				<input type=hidden name='pntContext' value='<?php print $this->getRequestParam('pntContext')?>'>

				<?php $this->includeOrPrintDetailsTable() ?>
			</form>
		</div>
		<script> scaleContent(); </script>