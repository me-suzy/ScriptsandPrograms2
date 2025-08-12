<?php checkKBVisibility(); ?>
<table cellpadding="0" cellspacing="0" border="0">
<form method="post" action="std_getresults.php">
	<tr><th colspan="3" align="left">
		Ticket Search Criteria	
	</th></tr>
	
	<!-- First Name Data Searech -->
	<tr>
		<th align="left" valign="top">First Name:&nbsp;</th>
		<td valign="top">
			<input type="radio" name="fnameOpt" value="starts" />Starts With&nbsp;
			<input type="radio" name="fnameOpt" value="contains" checked="checked" />Contains&nbsp;<br/>
			<input type="radio" name="fnameOpt" value="ends" />Ends With&nbsp;&nbsp;
			<input type="radio" name="fnameOpt" value="is" />Is&nbsp;

		</td>
		<td valign="top">
			<input type="text" name="fnameVal" size="20" maxlength="40" />
		</td>
	</tr>
	
	<tr><td colspan="3">
		<hr width="75%" />
	</td></tr>
	
	<!-- Last Name Data Search Criteria -->
	<tr>
		<th align="left" valign="top">Last Name:&nbsp;</th>
		<td valign="top">
			<input type="radio" name="lnameOpt" value="starts" />Starts With&nbsp;
			<input type="radio" name="lnameOpt" value="contains" checked="checked" />Contains&nbsp;<br/>
			<input type="radio" name="lnameOpt" value="ends" />Ends With&nbsp;&nbsp;
			<input type="radio" name="lnameOpt" value="is" />Is&nbsp;&nbsp;
		</td>
		<td valign="top">
			<input type="text" name="lnameVal" size="20" maxlength="40" />
		</td>
	</tr>
	<tr><td colspan="3">
		<hr width="75%" />
	</td></tr>
		
		<!-- Email Data Search Criteria -->
	<tr>
		<th align="left" valign="top">Email Address:&nbsp;</th>
		<td valign="top">
			<input type="radio" name="emailOpt" value="starts" />Starts With&nbsp;
			<input type="radio" name="emailOpt" value="contains" checked="checked" />Contains&nbsp;<br/>
			<input type="radio" name="emailOpt" value="ends" />Ends With&nbsp;&nbsp;
			<input type="radio" name="emailOpt" value="is" />Is&nbsp;
		</td>
		<td valign="top">
			<input type="text" name="emailVal" size="20" maxlength="40" />
		</td>
	</tr>
	<tr><td colspan="3">
		<hr width="75%" />
	</td></tr>
		
	<!-- PC Catagory Data Criteria Search -->
	<tr>
		<th align="left" valign="top">PC Catagory:&nbsp;</th>
		<td valign="top">
			<input type="radio" name="pccatOpt" value="starts" />Starts With&nbsp;
			<input type="radio" name="pccatOpt" value="contains" checked="checked" />Contains&nbsp;<br/>
			<input type="radio" name="pccatOpt" value="ends" />Ends With&nbsp;&nbsp;
			<input type="radio" name="pccatOpt" value="is" />Is&nbsp;
		</td>
		<td valign="top">
			<input type="text" name="pccatVal" size="20" maxlength="40" />
		</td>
		<tr><td colspan="3">
			<hr width="75%" />
		</td></tr>
	</tr>
	
	<!-- Priority Filter -->
	<tr>
		<th align="left">Priority Level:&nbsp;</th>
		<td>Is</td>
		<td>
		<?php
			/*
				What we would like to do here is simply make the user select a blank for a value found in the priorities table.
				However, since the priority table is not in use, we will use a quick fix that will give us only what is available
				to use through the data table. This should be upgraded in future versions.
			*/
			$q = "select distinct priority from " . DB_PREFIX . "data where priority <> ''";	//note that upon analysis priorities are by
																								//default empty, that is not a valid option
			$s = mysql_query($q) or die("Query Error Getting Priorities");
		?>
			<select name="priority" size="1">
				<option value="" selected="selected"></option>
			<?php
				while ($r = mysql_fetch_assoc($s))
					echo '<option value="' . $r['priority'] . '">' . $r['priority'] . '</option>' . chr(10);
			?>
			</select>
		</td>
	</tr>
	<tr><td colspan="3">
		<hr width="75%" />
	</td></tr>
	
	<!-- Page View Data Criteria -->
	<tr>
		<th align="left">At Least:&nbsp;</th>
		<td align="center"><input type="text" name="pageViewVal" size="10" maxlength="9" /></td>
		<th align="left">&nbsp;Page Views</th>
	</tr>
	<tr><td colspan="3">
		<hr width="75%" />
	</td></tr>
	
	<!-- Status Data Criteria -->
	<tr>
		<th align="left" colspan="2">Status Is</th>
		<td>
			<select name="statusVal" size="1">
				<option value=""></option>
			<?php
				$q = "select distinct status from " . DB_PREFIX . "data order by status";
				$s = mysql_query($q) or die("MYSQL Error Retrieving Status Values");
				while ($r = mysql_fetch_assoc($s))
					echo '<option value="' . $r['status'] . '">' . $r['status'] . '</option>' . chr(10);
			?>
			</select>
		</td>
	</tr>
	<tr><td colspan="3">
		<hr width="75%" />
	</td></tr>
	
	<!-- Date Criteria -->
	<tr>
		<th align="left">Date Between:&nbsp;</th>
		<td colspan="2">
			<input type="text" name="date1" size="20" maxlength="30" />&nbsp;&amp;
			&nbsp;<input type="text" name="date2" size="20" maxlength="30" />
		</td>
	</tr>
	<tr><td colspan="3">
		<hr width="75%" />
	</td></tr>
	
	<!-- Button -->
	<tr><td align="center" colspan="3">
		<input type="submit" name="submit" value="Submit" class="button" />
	</td></tr>
</form>
</table>