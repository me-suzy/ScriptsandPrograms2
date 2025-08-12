<?
/*
 * gCards - a web-based eCard application
 * Copyright (C) 2003 Greg Neustaetter
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

if ($dropShadow == 'yes')
{
?>
	<table align="center" cellspacing="5" cellpadding="10" width="400">
		<tr>
			<td>

				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td><img src="images/<?=rawurlencode($imagepath);?>" border="0"></td>
						<td valign="top" background="images/siteImages/dropshadow/ds_right.gif"><img src="images/siteImages/dropshadow/ds_topright.gif" alt="" width="7" height="10" border="0"></td>
					</tr>
					<tr>
						<td background="images/siteImages/dropshadow/ds_bottom.gif"><img src="images/siteImages/dropshadow/ds_bottomleft.gif" alt="" width="7" height="7" border="0"></td>
						<td><img src="images/siteImages/dropshadow/ds_corner.gif" alt="" width="7" height="7" border="0"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>	
	<br><br>
	<table align="center" cellspacing="0" cellpadding="0" border="0" width="520">
		<tr>
			<td colspan="5" bgcolor="white">
				<img src="images/siteImages/shim.gif" alt="" width="1" height="10" border="0">
			</td>
			<td valign="top" background="images/siteImages/dropshadow/ds_right.gif" rowspan="3"><img src="images/siteImages/dropshadow/ds_topright.gif" alt="" width="7" height="10" border="0"></td>
		</tr>
		<tr>
			<td width="5" bgcolor="white"><img src="images/siteImages/shim.gif" alt="" width="5" height="1" border="0"></td>
			<td bgcolor="white" width="400" valign="top">
				<p><?=strip_tags(checkStripSlashes($cardtext),'<br><br/><br /><p><font><strong><emu><u><img>');?></p>
			</td>
			<td width="12" bgcolor="white"><img src="images/siteImages/shim.gif" alt="" width="12" height="200" border="0"></td>
			<td width="98" bgcolor="white" valign="top">
				<div align="right">
					<img src="images/siteImages/stamps/<?=$stampImage;?>" alt="" border="0"><br><br><br>
				</div>
				<div align="center">
					<?=$showcard01;?> <a href="mailto:<?=$from_email; ?>"><?=$from_name; ?></a>	
				</div>
			</td>
			<td width="5" bgcolor="white"><img src="images/siteImages/shim.gif" alt="" width="5" height="1" border="0"></td>
		</tr>
		<tr>
			<td colspan="5" bgcolor="white">
				<img src="images/siteImages/shim.gif" alt="" width="1" height="10" border="0">
			</td>
		</tr>
		<tr>
			<td background="images/siteImages/dropshadow/ds_bottom.gif" colspan="5"><img src="images/siteImages/dropshadow/ds_bottomleft.gif" alt="" width="7" height="7" border="0"></td>
			<td><img src="images/siteImages/dropshadow/ds_corner.gif" alt="" width="7" height="7" border="0"></td>
		</tr>
	</table>
<?
}
else
{
?>
	<table align="center" cellspacing="5" cellpadding="10" width="400">
		<tr>
			<td bgcolor="white">
				<?=$showcard01;?> <b><?=checkStripSlashes($from_name); ?></b> (<a href="mailto:<?=$from_email; ?>"><?=$from_email; ?></a>)<br><br>
				<img src="images/<?=rawurlencode($imagepath); ?>" border="0">
				
				<p><?=strip_tags(checkStripSlashes($cardtext),'<br><p><font><strong><emu><u>');?></p>
			</td>
		</tr>
	</table>

	
<?
}

if (!empty($music) && ($music != 'none'))
{
?>
<div align="center">
<br><br>
<EMBED SRC="sound/<?=strip_tags($music);?>" AUTOSTART="true" LOOP="true" WIDTH="290" HEIGHT="55" ALIGN="CENTER">
</EMBED>
</div>
<?
}
?>


