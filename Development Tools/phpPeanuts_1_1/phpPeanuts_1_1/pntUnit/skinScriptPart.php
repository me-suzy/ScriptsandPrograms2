<!-- skinScriptPart -->
<TABLE class=pntGroupPane WIDTH="678" BORDER="0" CELLPADDING="0" CELLSPACING="4">
	<TR>
		<TD WIDTH="100%" ALIGN="left" CELLPADDING="0" CELLSPACING="0">
			<TABLE WIDTH="100%" CELLPADDING="0" CELLSPACING="0" class=pntGroupPane>
			<TR>
				<TD>&nbsp;Include</TD>
				<TD>&nbsp;Exclude</TD>
				<TD>&nbsp;</TD>
				<TD>&nbsp;Folder</TD>
				<TD>&nbsp;Files</TD>
			</TR>
			<TR>
				<TD>
			<INPUT TYPE="TEXT" NAME="FileIncludePattern" SIZE="12" VALUE="<?php 
				$this->printFileIncludePattern() ?>">
				</TD>
				<TD>
			<INPUT TYPE="TEXT" NAME="FileExcludePatterns" SIZE="12" VALUE="<?php 
				$this->printFileExcludePatterns() ?>">
				</TD>
				<TD align="center">
			<INPUT TYPE="SUBMIT" class=funkyButton NAME="!RefreshDirs" VALUE=" => ">
				</TD>
				<TD>
			<SELECT NAME="Dir" id=dirSelect> 
				<?php $this->printDirSelectOptions() ?>
			</SELECT>
				</TD>
				<TD align="center">
			<INPUT TYPE="SUBMIT" class=funkyButton NAME="!AddFiles" VALUE="Add to script">
				</TD>
			</TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD WIDTH="100%" ALIGN="left">
			<TEXTAREA NAME="Script" ROWS="9" COLS="93"><?php print $this->getScript() ?></TEXTAREA>
		</TD>
	</TR>
</TABLE>
<!-- /skinScriptPart -->