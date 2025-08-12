<?php
//  +---------------------------------------------------------------------------+
//  | netjukebox, Copyright © 2001-2005  Willem Bartels                         |
//  |                                                                           |
//  | info@netjukebox.nl                                                        |
//  | http://www.netjukebox.nl                                                  |
//  |                                                                           |
//  | This file is part of netjukebox.                                          |
//  | netjukebox is free software; you can redistribute it and/or modify        |
//  | it under the terms of the GNU General Public License as published by      |
//  | the Free Software Foundation; either version 2 of the License, or         |
//  | (at your option) any later version.                                       |
//  |                                                                           |
//  | netjukebox is distributed in the hope that it will be useful,             |
//  | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
//  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
//  | GNU General Public License for more details.                              |
//  |                                                                           |
//  | You should have received a copy of the GNU General Public License         |
//  | along with this program; if not, write to the Free Software               |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
//  +---------------------------------------------------------------------------+



//  +---------------------------------------------------------------------------+
//  | httpq.inc.php                                                             |
//  +---------------------------------------------------------------------------+
if (!isset($cfg['session_id']))
	{
	$query				= mysql_query('SELECT session_id
										FROM configuration_session
										WHERE sid = "' . mysql_real_escape_string(cookie('netjukebox_sid')) . '"
										AND logged_in');
	$cfg['session_id']	= @mysql_result($query, 'session_id');
	}


$query = mysql_query('SELECT
					configuration_httpq.httpq_host,
					configuration_httpq.httpq_port,
					configuration_httpq.httpq_pass,
					configuration_httpq.media_share,
					configuration_httpq.httpq_id
					FROM configuration_httpq, configuration_session
					WHERE configuration_session.session_id = "' . $cfg['session_id'] . '"
					AND configuration_httpq.httpq_id = configuration_session.httpq_id');
$configuration_httpq = mysql_fetch_array($query);
if (!isset($configuration_httpq['httpq_id']))
	{
	$query = mysql_query('SELECT
							httpq_host,
							httpq_port,
							httpq_pass,
							media_share,
							httpq_id
							FROM configuration_httpq
							ORDER BY name');
	$configuration_httpq = mysql_fetch_array($query);
	}


$cfg['httpq_id'] = $configuration_httpq['httpq_id'];
if (!$cfg['httpq_host']	= $configuration_httpq['httpq_host']) $cfg['httpq_host'] = '127.0.0.1';
if (!$cfg['httpq_port'] = $configuration_httpq['httpq_port']) $cfg['httpq_port'] = '4800';
if (!$cfg['httpq_pass'] = $configuration_httpq['httpq_pass']) $cfg['httpq_pass'] = 'pass';
if (!$cfg['media_share'] = $configuration_httpq['media_share']) $cfg['media_share'] = $cfg['media_dir'];



//  +---------------------------------------------------------------------------+
//  | httpQ                                                                     |
//  +---------------------------------------------------------------------------+
function httpq($command, $argument = '')
{
global $cfg;
$result	= '';
$header	= '';

$soket = @fsockopen($cfg['httpq_host'], $cfg['httpq_port'], $error_no, $error_string, 1) or message('error', '<strong>Can\'t connect to Winamp httpQ plugin on:</strong><br>' . $cfg['httpq_host'] . ':' . $cfg['httpq_port']);
if ($argument)	fwrite($soket, 'GET /' . $command . '?p=' . $cfg['httpq_pass'] . '&' . $argument . ' HTTP/1.0' . "\r\n\r\n");
else			fwrite($soket, 'GET /' . $command . '?p=' . $cfg['httpq_pass'] . ' HTTP/1.0' . "\r\n\r\n");

while(!feof($soket))
	{
	$line = fgets($soket, 128);
	if ($line == "\r\n")
		{
		$header = $result;
		$result = '';
		}
	else $result .= $line;
	}
fclose($soket);

if ($header != '')
	{
	$header_array = explode("\r\n", $header);
	foreach ($header_array as $value)
		{
		if (substr($value, 0, 20) == 'Server: Winamp httpQ' && (float) substr($value, -3) < 3)
			message('error', '<strong>Wrong Winamp httpQ plugin</strong><br>Require httpQ 3.0 or higher');
		}
	}

return $result;
}
?>
