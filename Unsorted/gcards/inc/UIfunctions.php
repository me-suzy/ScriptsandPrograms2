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

class pagebuilder
{
	var $relpath; // relative path to the main directory
	var $windowtitle;
	var $pagetitle;
	var $bodyargs;
	var $headervalues;
	var $languageredirect;
	var $langargs;
	var $langdir = 'ltr';
	function pagebuilder($relpath = '', $languageredirect='')
	{
		global $defaultLang;
		$this->relpath = $relpath;
		$this->windowtitle = $GLOBALS['siteName'];
		$this->pagetitle = $GLOBALS['siteName'];
		if ($languageredirect != '') $this->languageredirect = $languageredirect;
		else $this->languageredirect = $_SERVER['PHP_SELF'];
		if (!isset($_SESSION['setLang'])) $_SESSION['setLang'] = $defaultLang;
	}
	function showHeader($pagetitle = '')
	{
		global $lang;
		if ($pagetitle != '') $this->pagetitle = $pagetitle;
		if (isset($GLOBALS['langdir'])) $this->langdir = $GLOBALS['langdir'];
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<html dir="<? echo $this->langdir;?>">
		<head>
		<? echo $this->headervalues;?>
		<title><? echo $this->windowtitle;?></title>
		<link href="<? echo $this->relpath;?>css/style.css" rel="stylesheet" type="text/css">
		<body <? echo $this->bodyargs;?>>
		<table width="100%">
			<tr>
				<td><span class="title"><? echo $this->pagetitle;?></span></td>
				<td align="right">
					<?
						if (isset($_SESSION['auth_user']))
						{
							echo $GLOBALS['uifunc01'].$_SESSION['auth_user']?>&nbsp;&nbsp;<a href="<? echo $this->relpath;?>admin/admin.php">[<? echo $GLOBALS['nav05'];?>]</a>&nbsp;&nbsp;<a href="<? echo $this->relpath;?>admin/changePass.php">[<? echo $GLOBALS['nav06'];?>]</a>&nbsp;&nbsp;<a href="<? echo $this->relpath;?>logout.php">[<? echo $GLOBALS['nav07'];?>]</a><?
						}
						else
						{
							if ($GLOBALS['showLoginLink'] == 'yes')
							{
								?><a href="login.php">[<? echo $GLOBALS['nav08'];?>]</a><?
							}
							else echo "&nbsp;";
						}
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><? $this->drawLine(); ?></td>
			</tr>
			<?
			// Display flags for each language if more than one language file is setup in config.php
			if (count($lang) > 1)
			{
				?><tr><td colspan="2" align="right"><?
				foreach($lang as $langid=>$langvalue)
				{
				?>
				<a href="<? echo $this->languageredirect;?>?setLang=<? echo $langid.$this->langargs;?>"><img src="<? echo $this->relpath;?>images/siteImages/flags/<? echo $langvalue['flag']?>" border="0" title="<? echo $langvalue['desc']?>"></a>
				<?
				}
				?></td></tr></table><?
			}
			else
			{
		?></table><br><?	
			}
	}
	
	function showFooter()
	{
		?>
		<br><br>
		<table width="100%">
			<tr>
				<td colspan="2"><? $this->drawLine();?></td>
			</tr>
			<tr>
				<td><? echo $GLOBALS['nav09'];?> <a href="http://www.gregphoto.net/gcards/index.php">gCards</a> v<? echo $GLOBALS['gCardsVersion'];?></td><td align="right"><? $this->showLink($this->relpath.'index.php','['.$GLOBALS['siteName'].' '.$GLOBALS['nav03'].']');?></td>
			</tr>
		</table>
		
		</body>
		</html>
		<?
	}
	
	function drawLine()
	{
		?>
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td class="horizontalLine"><img src="<? echo $this->relpath;?>images/siteImages/shim.gif" border="0" height="2" width="1"></td>
			</tr>
		</table>
		<?
	}
	
	function showLink($link, $linktext, $target='')
	{
		if ($target) $target = "target=\"$target\"";
		echo "<a href=\"$link\" $target>$linktext</a>";
	}
} 

/*
************************************
***** END PAGE BUILDER *************
************************************
*/

function deleteFromSession($sessionvars)
{
	$vararry = explode(",",$sessionvars);
	foreach($vararry as $var)
	{
		$var = trim($var);
		$_SESSION[$var] = NULL;
	}
}

function createLocalFromSession($var)
{
	if (isset($_SESSION[$var])) 
	{
		global $$var;
		$$var = $_SESSION[$var];
	}
}

function showVar($var)
{
	if(isset($GLOBALS[$var])) echo $GLOBALS[$var];
}
?>