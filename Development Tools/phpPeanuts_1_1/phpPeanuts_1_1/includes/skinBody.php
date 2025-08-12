<!-- skinBody -->
<table width=100% height=100%><tr valign=top>
	<td height=100% width=130>
    	<table height=100% width=100%>
			<tr valign=top height=100>
				<td class=pntMenuPart>
				<font class=pntMenuHead>Menu</font><BR><Br><BR>
				<?php $this->printPart('MenuPart') ?>
				</td>
			</tr>
			<tr valign=top>
				<td class=<?php print $this->getInfoStyle() ?>>
				<font class=pntInfoHead>Information</font><BR><BR><BR>
				<?php $this->printPart('InformationPart') ?>
				
				</td>
			</tr>
		</table> <BR>
	</td>
  	<td height=100%>
  		<table height=100% width=100% >
			<tr>
		    	<td>
		    		<table height=100% width=100% class=pntGroupPane>
						<tr>
					    	<td valign=top class=pntGroupContent>
<?php $this->printPart('MainPart') ?>		    				
				    		</td>		    	
				    	</tr>
					</table>				    	
		    	</td>		    	
		    </tr>
<?php $this->printPart('ButtonsPanel') ?>
		</table>
	</td>
</tr></table>	
<!-- /skinBody -->