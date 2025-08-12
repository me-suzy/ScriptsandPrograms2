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
//  | cover.php                                                                 |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');
authenticate('access_cover', false, false);

if (!function_exists('pdf_set_info'))
	if ($cfg['windows'])	message('error', '<strong>PDF extension not loaded</strong><ul class="compact"><li>Enable php_pdf.dll in the php.ini</li><li>Restart webserver</li></ul>');
	else					message('error', '<strong>PDF not supported</strong><br>Compile PHP with PDF support<br>or use a loadable module in the php.ini<br>(extension="libpdf_php.so")<br>For more information: http://www.pdflib.com');



$album_id = get('album_id');
$command  = get('command');

$query = mysql_query('SELECT cd_front, cd_back, album_id FROM bitmap WHERE album_id = "' . mysql_real_escape_string($album_id) . '"');
$bitmap = mysql_fetch_array($query);

$query = mysql_query('SELECT artist, album FROM album WHERE album_id = "' . mysql_real_escape_string($album_id) . '"');
$album = mysql_fetch_array($query);



//  +---------------------------------------------------------------------------+
//  | Initialize PDF                                                            |
//  +---------------------------------------------------------------------------+
$pdf = pdf_new();
pdf_open_file($pdf, '');

pdf_set_info($pdf, 'Title', $album['artist'] . ' - ' . $album['album']);
pdf_set_info($pdf, 'Creator', 'netjukebox ' . $cfg['netjukebox_version']);

pdf_begin_page($pdf, 595, 842);		//A4
$scale = 2.834645676;				//mm to dtp-point (1 point = 1/72 inch)
pdf_scale($pdf, $scale, $scale);
pdf_setlinewidth ($pdf, .1);



//  +---------------------------------------------------------------------------+
//  | PDF Back Cover                                                            |
//  +---------------------------------------------------------------------------+
$x0 = 30;
$y0 = 22;
pdf_translate($pdf, $x0, $y0);

pdf_moveto($pdf, 0, -1);
pdf_lineto($pdf, 0, -11);
pdf_moveto($pdf, 6.5, -1);
pdf_lineto($pdf, 6.5, -11);
pdf_moveto($pdf, 144.5, -1);
pdf_lineto($pdf, 144.5, -11);
pdf_moveto($pdf, 151, -1);
pdf_lineto($pdf, 151, -11);
pdf_moveto($pdf, 0, 119);
pdf_lineto($pdf, 0, 129);
pdf_moveto($pdf, 6.5, 119);
pdf_lineto($pdf, 6.5, 129);
pdf_moveto($pdf, 144.5, 119);
pdf_lineto($pdf, 144.5, 129);
pdf_moveto($pdf, 151, 119);
pdf_lineto($pdf, 151, 129);
pdf_moveto($pdf, -11, 0);
pdf_lineto($pdf, -1 , 0);
pdf_moveto($pdf, -11, 118);
pdf_lineto($pdf, -1 , 118);
pdf_moveto($pdf, 152, 0);
pdf_lineto($pdf, 162, 0);
pdf_moveto($pdf, 152, 118);
pdf_lineto($pdf, 162, 118);
pdf_stroke($pdf);

if ($bitmap['cd_back'])
	{
	$extension = substr(strrchr($bitmap['cd_back'], '.'), 1);
	$extension = strtolower($extension);
	if ($extension == 'jpg')	$pdfdfimage = pdf_open_image_file($pdf, 'jpeg', $bitmap['cd_back'], '', 0);
	if ($extension == 'png')	$pdfdfimage = pdf_open_image_file($pdf, 'png', $bitmap['cd_back'], '', 0);
	if ($extension == 'gif')	$pdfdfimage = pdf_open_image_file($pdf, 'gif', $bitmap['cd_back'], '', 0);
	$sx = 151 / pdf_get_value($pdf, 'imagewidth', $pdfdfimage); 
	$sy = 118 / pdf_get_value($pdf, 'imageheight', $pdfdfimage);
	pdf_scale($pdf, $sx, $sy);
	pdf_place_image($pdf, $pdfdfimage, 0, 0 , 1);
	pdf_scale($pdf, (1 / $sx), (1 / $sy)); //Reset scale
	}
else
	{
	$same_artist = false;
	$query = mysql_query('SELECT artist FROM track WHERE album_id = "' . mysql_real_escape_string($album_id) . '" GROUP BY artist');
	if (mysql_num_rows($query) == 1) $same_artist = true;
	
	$temp = '';
	$previous_cd = 1;
	$query = mysql_query('SELECT title, artist, cd FROM track WHERE album_id = "' . mysql_real_escape_string($album_id) . '" ORDER BY relative_file');
	while ($track = mysql_fetch_array($query))
		{
		if ($previous_cd != $track['cd'])	$temp .= "\n";
		if ($same_artist) 				$temp .= $track['title'] . "\n";
		else 							$temp .= $track['artist'] . ' - ' . $track['title'] . "\n";
		$previous_cd = $track['cd'];
		}
	$font = pdf_findfont($pdf, 'Helvetica', 'winansi', 0); 
	pdf_setfont($pdf, $font, 3);
	pdf_show_boxed($pdf, $temp, 6.5, 0, 138, 108, 'center', '');
	
	$query = mysql_query('SELECT artist, album FROM album WHERE album_id = "' . mysql_real_escape_string($album_id) . '"');
	$album = mysql_fetch_array($query);

	pdf_setfont($pdf, $font, 4);
	pdf_set_text_pos($pdf,2,-4.5); //y,-x
	pdf_rotate($pdf, 90);
	pdf_show($pdf, $album['artist'] . ' - ' . $album['album']);
	pdf_rotate($pdf, -90);
	
	pdf_setfont($pdf, $font, 4);
	pdf_set_text_pos($pdf,-116 ,151-4.5); //-y,x
	pdf_rotate($pdf, -90);
	pdf_show($pdf, $album['artist'] . ' - ' . $album['album']);
	pdf_rotate($pdf, 90);
	}



//  +---------------------------------------------------------------------------+
//  | PDF Front Cover                                                           |
//  +---------------------------------------------------------------------------+
$x0 = 44 - $x0;
$y0 = 160 - $y0;
pdf_translate($pdf, $x0, $y0);

pdf_moveto($pdf, 0, -1);
pdf_lineto($pdf, 0, -11);
pdf_moveto($pdf, 121, -1);
pdf_lineto($pdf, 121, -11);
pdf_moveto($pdf, 0, 121);
pdf_lineto($pdf, 0, 131);
pdf_moveto($pdf, 121, 121);
pdf_lineto($pdf, 121, 131);
pdf_moveto($pdf, -1, 0);
pdf_lineto($pdf, -11, 0);
pdf_moveto($pdf, -1, 120);
pdf_lineto($pdf, -11, 120);
pdf_moveto($pdf, 122, 0);
pdf_lineto($pdf, 132, 0);
pdf_moveto($pdf, 122, 120);
pdf_lineto($pdf, 132, 120);
pdf_stroke($pdf);

if ($bitmap['cd_front'])
	{
	$extension = substr(strrchr($bitmap['cd_front'], '.'), 1);
	$extension = strtolower($extension);
	if ($extension == 'jpg')	$pdfdfimage = pdf_open_image_file($pdf, 'jpeg', $bitmap['cd_front'], '', 0);
	if ($extension == 'png')	$pdfdfimage = pdf_open_image_file($pdf, 'png', $bitmap['cd_front'], '', 0);
	if ($extension == 'gif')	$pdfdfimage = pdf_open_image_file($pdf, 'gif', $bitmap['cd_front'], '', 0);
	$sx = 121 / pdf_get_value($pdf, 'imagewidth', $pdfdfimage);
	$sy = 120 / pdf_get_value($pdf, 'imageheight', $pdfdfimage);
	
	pdf_scale($pdf, $sx, $sy);
	pdf_place_image($pdf, $pdfdfimage, 0, 0 , 1);
	}
else
	{
	}



//  +---------------------------------------------------------------------------+
//  | Close PDF                                                                 |
//  +---------------------------------------------------------------------------+
pdf_end_page($pdf);
pdf_close($pdf);
$buffer = pdf_get_buffer($pdf);
if ($command == 'download')
	{
	$file = EncodeEscapeCharacters($album['artist']) . ' - ' . EncodeEscapeCharacters($album['album']) . '.pdf';
	header('Content-Type: application/force-download');
	header('Content-Transfer-Encoding: binary');
	header('Content-Disposition: attachment; filename="' . $file . '"'); //rawurlencode not needed for header
	}
else
	{
	header('Content-Type: application/pdf');
	header('Content-Disposition: inline; filename=cover.pdf');
	}
if (!(bool)ini_get('zlib.output_compression')) 
	{
	//Content_Length is not correct when GZIP is enabled
	header('Content-Length: ' . strlen($buffer)); 
	}
echo $buffer;
pdf_delete($pdf);
?>
