<?php

class skin_files
{

	function ad_cat_head()
	{
		global $rwdInfo;
		$SHTML = "<table width='100%' border='0' cellspacing='2' cellpadding='2'>
			  <tr class='acptablesubhead'> 
			    <td>Category</td>
			    <td width='50'><div align='center'>Downloads</div></td>
			    <td width='70'><div align='center'>Delete</div></td>
			    <td width='70'><div align='center'>Edit</div></td>
			  </tr>";
		return $SHTML;
	}
	
	function ad_cat_foot()
	{
		global $rwdInfo;
		$SHTML = "</table>";
		
		return $SHTML;
	}
	
	function mirrorrow($data=NULL)
	{
		global $rwdInfo;
		
		$SHTML .= "[ {$data['mirror']} ]&nbsp;";
		
		return $SHTML;
	}

	function ad_cat_row($data)
	{
		global $rwdInfo;
		$SHTML = "<tr class='normalrow'>
    <td valign=top><b>{$data['cat_name']}</b></td>
	<td valign=top><center>
        {$data['cat_dlcount']}</center></td>
    <td valign=top><center>
        [ {$data['cat_delete']} ]</center></td>
    <td valign=top><center>
        [ {$data['cat_edit']} ]</center></td>
	<tr class=normalrow>
	<td colspan=4>{$data['cat_desc']}<br>
      <i>Latest Download: {$data['cat_latest']}</i></td>
    
  </tr>";
		return $SHTML;
	}
	
	function ad_cat_listing_head($data)
	{
		global $rwdInfo;
		$SHTML = "{$data['pages']}
			<form method='post' action='{$data['batchpost']}'>
			<table width='100%' border='0' cellspacing='2' cellpadding='2'>
			<tr class='acptablesubhead'>
			<td colspan=7>{$data['cat_name']}</td>
			</tr><tr class='acptablesubhead'>
			<td width=30>&nbsp;</td>
			<td width=150>Name</td>
			<td width=75>Author</td>
			<td width=75>Date</td>
			<td>Downloads</td>
			<td>Delete</td>
			<td>Edit</td>
			</tr>";
		
		return $SHTML;
	}
	
	function ad_cat_listing_foot($data)
	{
		global $rwdInfo;
		$SHTML = "<tr class='acptablesubhead'>

		<td colspan=4>{$data['pages']}</td>
		<td colspan=3><div align='right'>With selected: 
		<select name='mode'>
		<option value='move' selected>{$rwdInfo->lang['movefiles']}</option>
		<option value='del'>{$rwdInfo->lang['deletefiles']}</option>
		</select>
		<input type='hidden' name='cid' value='{$data['cid']}'>
		<input type='submit' name='batchsubmit' value='{$rwdInfo->lang['submit']}'>
		</div></td>
		</tr>
		</table>
		</form>";
		
		return $SHTML;
	}
	
	function ad_cat_listing_row($data)
	{
		global $rwdInfo;
		$SHTML = "<tr class='normalrow'>
				<td><input type='checkbox' name='dlid[]' value='{$data['id']}'></td>
				<td><b>{$data['name']} {$data['new']} {$data['updated']}</b></td>
				<td>{$data['author']}</td>
				<td>{$data['date']}</td>
				<td><center>{$data['downloads']}</center></td>
				<td><center>[ {$data['delete']} ]</center></td>
				<td><center>[ {$data['edit']} ]</center></td>
			  </tr>";
		
		return $SHTML;
	}
	
	function ad_file_approve_foot()
	{
		global $rwdInfo;
		$SHTML = "<tr class='acptablesubhead'>
			<td width=20><INPUT type='checkbox' id='SelectALL' name='SelectALL' onClick='doselectAll(this)' ></td>
			<td colspan=6>&nbsp;</td>
			</tr>
			</table>
			<div align=right><input type='submit' name='approveChecked' value='{$rwdInfo->lang['approve']}'>&nbsp;
			<input type='submit' name='deleteChecked' value='{$rwdInfo->lang['delete']}'></div>
			</form>";
		return $SHTML;
	}
	
	function ad_file_approve_row($data)
	{
		global $rwdInfo;
		$SHTML = "<tr class='normalrow'>
				<td><input type='checkbox' name='dlid[]' value='{$data['id']}'></td>
				<td width='150'><b>{$data['name']}</b></td>
				<td>{$data['author']}</td>
				<td>{$data['date']}</td>
				<td><div align='center'>[ {$data['edit']} ]</div></td>
			  </tr>";
		return $SHTML;
	}
	
	function desc_block($data)
	{
		global $rwdInfo;
		$SHTML = "<p><strong>{$rwdInfo->lang['description']}</strong><br>
		<hr width='95%' size='1' color='#3B5087' align='left'>
		{$data['description']}</p>";
		return $SHTML;
	}
	
	function ad_file_approve_head($data)
	{
		global $rwdInfo;
		$SHTML = "<script>
			function doselectAll(theBox){
			xState=theBox.checked;
			elm=theBox.form.elements;
			for(i=0;i<elm.length;i++)
			 if(elm[i].type=='checkbox')
			   elm[i].checked=xState;
			}
			</script>
			<form method='post' enctype='multipart/form-data' action='{$data['form_approve']}'>
			<table width='100%' border='0' cellspacing='2' cellpadding='2'>
			<tr class='acptablesubhead'>
			<td colspan='5'>{$data['cat_name']}</td>
			</tr><tr class='acptablesubhead'>
			<td width=20><INPUT type='checkbox' id='SelectALL' name='SelectALL' onClick='doselectAll(this)' ></td>
			<td width=150>Name</td>
			<td width=75>Author</td>
			<td>Date</td>
			<td><div align='center'>Edit</div></td>
			</tr>";
		return $SHTML;
	}
	
	function ad_cat_order_head()
	{
		global $rwdInfo;
		$SHTML = "<table width='100%' border='0' cellspacing='2' cellpadding='2'>
		  <tr class='acptablesubhead'> 
		    <td width='50'><div align='center'>Order</div></td>
		    <td>Category</td>
		  </tr>";
		return $SHTML;
	}
	
	function ad_cat_order_foot()
	{
		global $rwdInfo;
		$SHTML = "</table>";
		return $SHTML;
	}
	
	function ad_cat_order_row($data)
	{
		global $rwdInfo;
		$SHTML = "<tr class='normalrow'>
		    <td valign=top><div align='center'>{$data['cat_order']}</div></td>
			<td valign=top>{$data['cat_name']}<br>
		    {$data['cat_desc']}<br>
		    <i>Sub-cats</i>: {$data['cat_subcats']}</td>
		    
		  </tr>";
		return $SHTML;
	}
	
	function thumb_row($data)
	{
		global $rwdInfo;
		$SHTML = "<tr class='normalrow'><td valign='top'>{$rwdInfo->lang['dlthumb']}</td>
		<td valign='top'>{$data['thumbdata']}</td></tr>";
		return $SHTML;
	}
	
	function download_link($data)
	{
		global $rwdInfo;
		$SHTML = "<tr class='normalrow'><td valign='top'>{$rwdInfo->lang['download']}</td>
		<td valign='top'>{$data['dlinfo']}</td></tr>
		<tr class='normalrow'>
					<td valign='top'>{$rwdInfo->lang['filetype']}:</td>
					<td valign='top'>{$data['typeinfo']}</td>
				</tr>";
		return $SHTML;
	}
	
	function empty_download()
	{
		global $rwdInfo;
		$SHTML = "<tr class='normalrow'><td valign='top'>{$rwdInfo->lang['download']}:<br>{$rwdInfo->lang['dlnote']}</td>
		<td valign='top'><input name='download' type='file' size='30'></td></tr>
		<tr class='normalrow'><td valign='top'>{$rwdInfo->lang['dlurl']}:<br>{$rwdInfo->lang['dlnote']}</td>
		<td valign='top'><input name='downloadurl' type='text' size='30' value='http://'></td></tr>";
		return $SHTML;
	}
	
	function add_file($data)
{
global $rwdInfo;
//--START--//

$SHTML .= <<<RWS
{$data['thumbtable']}
{$data['formpost']}
<div class='tableborder'>
<table width='100%'  border='0 align='center' cellpadding='2' cellspacing='2'>
          <tr class='acptablesubhead'>
            <td colspan=3><b>{$data['title']}</b></td>
          </tr>
          <tr class='normalrow'>
					<td valign='top' width='200'>{$rwdInfo->lang['dlname']}:</td>
					<td valign='top'><input style='width:99%' name='name' type='text' size='30' value='{$data['name']}'></td>
				</tr>
				<tr class='normalrow'>
					<td valign='top'>{$rwdInfo->lang['dldesc']}:</td>
					<td valign='top'><textarea style='width:99%' name='description' cols='24' rows='5'>{$data['description']}</textarea></td>
				</tr>
				<tr class='normalrow'>
					<td valign='top'>{$rwdInfo->lang['author']}:</td>
					<td valign='top'><input style='width:99%' name='author' type='text' size='30' value='{$data['author']}'></td>
				</tr>
				{$data['thumbrows']}
				{$data['downloadrows']}
				<tr class='normalrow'><td valign='top'>{$rwdInfo->lang['filesize']} [{$rwdInfo->lang['optional']}]:<br>{$rwdInfo->lang['sizenote']}</td>
				<td valign='top'><input name='sizenum' type='text' size='10' value='{$data['sizenum']}'>k</td></tr>
				<tr class='normalrow'>
					<td valign='top'>{$rwdInfo->lang['mirror']}<br>{$rwdInfo->lang['fullurl']}</td>
					<td valign='top'>
						<table width='100%'  border='0 align='center' cellpadding='0' cellspacing='0'>
							<tr>
								<td>{$rwdInfo->lang['mirrorurl']}</td>
								<td>{$rwdInfo->lang['mirrorname']}</td>
							</tr><tr>
								<td><textarea style='width:99%' name='mirrors' cols='24' rows='5'>{$data['mirrors']}</textarea></td>
								<td><textarea style='width:99%' name='mirrornames' cols='24' rows='5'>{$data['mirrornames']}</textarea></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class='normalrow'>
					<td valign='top'>{$rwdInfo->lang['version']}:</td>
					<td valign='top'><input style='width:99%' name='version' type='text' size='30' value='{$data['version']}'></td>
				</tr>
				<tr class='normalrow'>
					<td valign='top'>{$rwdInfo->lang['cat']}:</td>
					<td valign='top'>{$data['catlistbox']}</td>
				</tr>
				<tr class='normalrow'>
					<td valign='top' colspan='2'><b><center>{$rwdInfo->lang['warn_upload']}</center></b></td>
				</tr>
			</td>
          </tr>
          <tr class='acptablesubhead'>
            <td colspan=3>
			<input type='hidden' name='did' value='{$data['id']}'>
<input type='hidden' name='oldfile' value='{$data['download']}'>			
<input type='hidden' name='owner' value='{$data['owner']}'>
<input type='hidden' name='oldcat' value='{$data['categoryid']}'>
<input type='hidden' name='filesize' value='{$data['filesize']}'>
<input type='hidden' name='realsize' value='{$data['realsize']}'>
<input type='hidden' name='images' value='{$data['thumbs']}'>
<input type='hidden' name='downloads' value='{$data['downloads']}'>
<input type='hidden' name='oldthumb' value='{$data['oldthumb']}'>
<center><input type='submit' name='preview' value='{$rwdInfo->lang['preview']}'>
<input type='reset' name='reset' value='{$rwdInfo->lang['reset']}'></center>
			</td>
          </tr>
  </table>
</form>
RWS;
//--END--//
return $SHTML;
}
	
	function file_view($data)
	{
		global $rwdInfo;
		$SHTML = "<table width='95%'  border='0' cellspacing='0' cellpadding='3'>
		  <tr>
			<td colspan='2'  class='cathead'>{$data['name']}</td>
			<td class='cathead'>&nbsp;</td>
		  </tr>
		  <tr>
			<td width='150' class='filelistingrow'>{$rwdInfo->lang['filesize']}:</td>
			<td class='filelistingrow'>{$data['filesize']}</td>
		  </tr>
		  <tr>
			<td class='filelistingrow'>{$rwdInfo->lang['author']}:</td>
			<td class='filelistingrow'>{$data['author']}</td>
		  </tr>
		  <tr>
		  <td class='filelistingrow'>{$rwdInfo->lang['downloads']}:</td>
		  <td class='filelistingrow'>{$data['downloads']}</td>
		  </tr>
		  <tr>
		  <td class='filelistingrow'>{$rwdInfo->lang['views']}:</td>
		  <td class='filelistingrow'>{$data['views']}</td>
		  </tr>
		  
		</table>
{$data['description_block']}";
		return $SHTML;
	}
}

?>