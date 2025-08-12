<TR><TD height=1px>
<table width=100% class=pntGroupPane>
 <tr valign=top>
	<td height=100% class=pntGroupContent>
	<DIV id='simpleFilterDiv' style='display:<?php $this->printDivDisplayStyle('simpleFilterDiv') ?>'>
		<form name=simpleFilterForm method=GET action='index.php'>
			<table align=left><tr valign=top>
				<td>
					<input type=hidden value='<?php print $this->getType() ?>' name='pntType'>
					<input type=hidden value='<?php print $this->getThisPntHandlerName() ?>' name='pntHandler'>
					<input type=hidden value='0' name='allItemsSize'>
					<input type=hidden value='0' name='pageItemOffset'>
					<?php $this->printExtraFormParameters() ?>
					<input type=hidden value='All stringfields' name='pntF1'>
					<input type=hidden value='LIKE' name='pntF1cmp'>
					<input type=text value='<?php print $this->getFilter1Value1() ?>' name='pntF1v1'>
				</td>	
				<td>
					<input type=submit style="height: 20px" class="funkyButton" value='<?php $this->printSearchButtonLabel() ?>' name='simple'>
				</td>
				<td class='normaal' align='right' width='100%'>
					<script>
						function showAdvanced() {
							getElement('advancedFilterDiv').style.display='block';
							getElement('simpleFilterDiv').style.display='none';
						}
					</script>
					<A HREF="javascript:showAdvanced();">advanced</A>
				</td>
			</tr></table>
		</form>
	</DIV>
	<DIV id='advancedFilterDiv' style='display:<?php $this->printDivDisplayStyle('advancedFilterDiv') ?>'>
		<form name=advancedFilterForm method=GET action='index.php'>
			<table align=left><tr>
				<td>
					<input type=hidden value='<?php print $this->getType() ?>' name='pntType'>
					<input type=hidden value='<?php print $this->getThisPntHandlerName() ?>' name='pntHandler'>
					<input type=hidden value='0' name='allItemsSize'>
					<input type=hidden value='0' name='pageItemOffset'>
					<?php $this->printExtraFormParameters() ?>
					<?php $this->printFilterSelectWidget() ?>
				</td>	
				<td>
					<?php $this->printComparatorSelectWidget() ?>
				</td>	
				<td>
					<input type=text value='<?php print $this->getFilter1Value1() ?>' name='pntF1v1'>
				</td>	
				<td>
					<input type=text value='<?php print $this->getFilter1Value2() ?>' name='pntF1v2'>
				</td>	
				<td>
					<input type=submit style="height: 20px" class="funkyButton" value='<?php $this->printSearchButtonLabel() ?>' name='advanced'>
				</td>	
				<td class='normaal' align='right' width='100%'>
					<script>
						function showSimple() {
							getElement('simpleFilterDiv').style.display='block';
							getElement('advancedFilterDiv').style.display='none';
						}
					</script>
					<A HREF="javascript:showSimple();">simple</A>
				</td>
			</tr></table>
		</form>
	</DIV>
	</td>
  </tr>
</table>	
</TR></TD>