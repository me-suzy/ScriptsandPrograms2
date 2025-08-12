<?php /*  Switch this html on when menu, info and buttons (remove php tag)
		<script>
			 function scaleContent() {
					this.itemTableDiv.style.height=(window.document.body.clientHeight)-175;	
					this.itemTableDiv.style.width=(window.document.body.clientWidth)-130;
			}
		</script>
		<div id=itemTableDiv style='height: 330px; width: 600px; overflow: auto;'>
*/ ?>
				<script>
					// report table data link, overrides general.js
					function tdl(obj, itemId) {
						document.location.href = tdlGetHref(obj, itemId)+'&pntHandler=ReportPage';
					}
				</script>
				<?php $this->printPart('LabelPart') ?>
				<?php $this->includeOrPrintDetailsTable() ?>
				<?php $this->printPart('MultiPropsPart') ?>
<?php /* Switch this html on when menu, info and buttons
		</div>
		<script> scaleContent(); </script>
*/ ?>