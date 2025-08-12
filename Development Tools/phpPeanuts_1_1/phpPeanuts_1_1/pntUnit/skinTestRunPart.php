<!-- skinPntUnitTestRunPart -->
	<TR>
		<TD WIDTH="100%">
			<TABLE BORDER="0" WIDTH="100%">
				<TR>
					<TD ALIGN="center" WIDTH="60%">
						Show: 
						<INPUT TYPE="CHECKBOX" NAME="showError" <?php $this->printVisibilityChecked('Error') ?> VALUE="1">
						Error
						<INPUT TYPE="CHECKBOX" NAME="showWarning" <?php $this->printVisibilityChecked('Warning') ?> VALUE="1">
						Warning
						<INPUT TYPE="CHECKBOX" NAME="showFailure" <?php $this->printVisibilityChecked('Failure') ?> VALUE="1">
						Failure
						<INPUT TYPE="CHECKBOX" NAME="showNotice" <?php $this->printVisibilityChecked('Notice') ?> VALUE="1">
						Notice
						<INPUT TYPE="CHECKBOX" NAME="showPass" <?php $this->printVisibilityChecked('Pass') ?> VALUE="1">
						Pass
						<INPUT TYPE="HIDDEN" NAME="visibilities" VALUE="true">
					</TD>
					<TD ALIGN="center" WIDTH="40%">
						<INPUT TYPE="SUBMIT" NAME="!RunScript" VALUE="Include and Run" class=funkyButton>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD WIDTH="100%">
			<TABLE BORDER="0" WIDTH="100%" class=pntItemTable>
				<TR>
					<TD class=pntIth>TestCase</TD>
					<TD class=pntIth>Test</TD>
					<TD class=pntIth>Event</TD>
					<TD class=pntIth>Description</TD>
					<TD class=pntIth>Message</TD>
				</TR>
				<?php $this->printTestResultsPart() ?>
<!-- /skinPntUnitTestRunPart -->