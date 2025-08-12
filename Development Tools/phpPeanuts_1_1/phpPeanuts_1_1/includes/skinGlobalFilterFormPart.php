<!-- skinGlobalFilterFormPart -->
<TR><TD>
<table width=100% class="pntNormal" bgcolor="#ddefff">
 <tr valign=top>
	<td height=100% bgcolor=white>
		<form name=filterForm method=POST action='index.php'>
			<table align=left>
				<tr><td>
					<input type=hidden value='<?php print $this->getType() ?>' name='pntType'>
					<input type=hidden value='GlobalAction' name='pntHandler'>
					<?php $this->printFilterSelectWidget() ?>
				</td></tr>
				<tr><td>
					<?php $this->printComparatorSelectWidget() ?>
				</td></tr>
				<tr><td>
					<input type=text value='<?php print $this->getFilter1Value1() ?>' name='pntF1v1'>
				</td></tr>
				<tr><td>
					<input type=text value='<?php print $this->getFilter1Value2() ?>' name='pntF1v2'>
				</td></tr>
				<tr><td>
					<input type=submit style="width: 55px" class="funkyButton" value='Zet' name='!set'>
					<input type=submit style="width: 55px" class="funkyButton" value='Wis' name='!remove'>
				</td></tr>
			</table>
		</form>
	</td>
  </tr>
</table>	
</TR></TD>
<!-- /skinGlobalFiltersFormPart -->