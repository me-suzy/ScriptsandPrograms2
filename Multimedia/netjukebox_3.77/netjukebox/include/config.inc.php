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
//  | Media directory                                                           |
//  +---------------------------------------------------------------------------+
//  | Always use a UNIX style path name without a trailing slash                |
//  | 'D:/Media'        good                                                    |
//  | 'D:/Media/Music'  good                                                    |
//  | 'D:/Media/'       wrong                                                   |
//  | 'D:\Media'        wrong                                                   |
//  |                                                                           |
//  | The media directory must be on the local server harddisk, and NOT on a    |
//  | network share.                                                            |
//  +---------------------------------------------------------------------------+
$cfg['media_dir']                  = 'D:/Media';
$cfg['convert_undersquare']        = false;



//  +---------------------------------------------------------------------------+
//  | Media extensions                                                          |
//  +---------------------------------------------------------------------------+
$cfg['media_extension'][]          = 'aac';
$cfg['media_extension'][]          = 'ape';
$cfg['media_extension'][]          = 'flac';
$cfg['media_extension'][]          = 'm4a';
$cfg['media_extension'][]          = 'mp3';
$cfg['media_extension'][]          = 'mpc';
$cfg['media_extension'][]          = 'ofr';
$cfg['media_extension'][]          = 'ofs';
$cfg['media_extension'][]          = 'ogg';
$cfg['media_extension'][]          = 'wma';
$cfg['media_extension'][]          = 'wmv';
$cfg['media_extension'][]          = 'wv';
$cfg['media_extension'][]          = 'avi';
$cfg['media_extension'][]          = 'mpeg';
$cfg['media_extension'][]          = 'mpg';



//  +---------------------------------------------------------------------------+
//  | Skin                                                                      |
//  +---------------------------------------------------------------------------+
$cfg['skin']                       = 'default';
$cfg['img']                        = 'skin/' . $cfg['skin'] . '/img';
$cfg['css']                        = 'skin/' . $cfg['skin'] . '/css';



//  +---------------------------------------------------------------------------+
//  | Download                                                                  |
//  +---------------------------------------------------------------------------+
$cfg['download_timeout']           = 3600 * 2;
$cfg['download_longfilename']      = false;



//  +---------------------------------------------------------------------------+
//  | Example for recording with Goldenhawk DAO (32-bit console version)        |
//  | a free demo version with 1x speed recording can be downloaded at:         |
//  | http://www.goldenhawk.com                                                 |
//  | For Goldenhawk DAO a working ASPI driver is required!                     |
//  |                                                                           |
//  | Also other console recording software can be used,                        |
//  | as long as it support cue files                                           |
//  +---------------------------------------------------------------------------+
$cfg['record']                     = 'D:\Console\DAO\DAO.EXE %cuefile /NOUNDERRUN /CACHE=2 /SPEED=16 /BATCH /EJECT';



//  +---------------------------------------------------------------------------+
//  | Stream prefix                                                             |
//  +---------------------------------------------------------------------------+
//  | netjukebox uses stream prefix to see the difference between local/shared  |
//  | files and streaming media.                                                |
//  +---------------------------------------------------------------------------+
$cfg['stream_prefix'][]            = 'ftp://';
$cfg['stream_prefix'][]            = 'http://';
$cfg['stream_prefix'][]            = 'icy://';
$cfg['stream_prefix'][]            = 'mms://';
$cfg['stream_prefix'][]            = 'sc://';
$cfg['stream_prefix'][]            = 'shout://';
$cfg['stream_prefix'][]            = 'unsv://';
$cfg['stream_prefix'][]            = 'uvox://';



//  +---------------------------------------------------------------------------+
//  | Mime Type                                                                 |
//  +---------------------------------------------------------------------------+
$cfg['mime_type']['aac']          = 'application/x-aac';
$cfg['mime_type']['avi']          = 'video/x-msvideo';
$cfg['mime_type']['mid']          = 'audio/midi';
$cfg['mime_type']['mov']          = 'video/quicktime';
$cfg['mime_type']['mp1']          = 'audio/mpeg';
$cfg['mime_type']['mp2']          = 'audio/mpeg';
$cfg['mime_type']['mp3']          = 'audio/mpeg';
$cfg['mime_type']['mp4']          = 'audio/mpeg';
$cfg['mime_type']['mpc']          = 'audio/x-musepack';
$cfg['mime_type']['mpg']          = 'video/mpeg';
$cfg['mime_type']['mpeg']         = 'video/mpeg';
$cfg['mime_type']['ofr']          = 'application/x-ofr';
$cfg['mime_type']['ofs']          = 'application/x-ofs';
$cfg['mime_type']['ogg']          = 'application/x-ogg';
$cfg['mime_type']['qt']           = 'video/quicktime';
$cfg['mime_type']['spx']          = 'application/x-speex';
$cfg['mime_type']['vqf']          = 'audio/x-twinvq';
$cfg['mime_type']['wav']          = 'audio/x-wav';
$cfg['mime_type']['wma']          = 'audio/x-ms-wma';



//  +---------------------------------------------------------------------------+
//  | Decode to stdout (for streaming & recording)                              |
//  |                                                                           |
//  | Known Musepack pipe problem: (mppdec.exe version 1.95z6)                  |
//  | When skipping from Musepack files cmd.exe and mppdec.exe are not closed.  |
//  |                                                                           |
//  | Monkey's Audio doesn't support pipe. (mac.exe version 3.99)               |
//  | A special Monkey's Audio compile from the shnutils website supports pipe: |
//  | http://www.etree.org/shnutils/shntool/                                    |
//  +---------------------------------------------------------------------------+
$cfg['decode_stdout']['ape']        = 'D:\Console\Codec\MACpipe.exe %source - -d';
$cfg['decode_stdout']['flac']       = 'D:\Console\Codec\flac.exe --decode --silent --stdout %source';
$cfg['decode_stdout']['mp3']        = 'D:\Console\Codec\lame.exe --decode --silent %source -';
$cfg['decode_stdout']['mpc']        = 'D:\Console\Codec\mppdec.exe --silent %source -';
$cfg['decode_stdout']['ofr']		= 'D:\Console\Codec\ofr.exe --decode --silent %source --output -'; 
$cfg['decode_stdout']['ofs']		= 'D:\Console\Codec\ofs.exe --decode --silent %source --output -'; 
$cfg['decode_stdout']['ogg']        = 'D:\Console\Codec\oggdec.exe --quiet %source --output -';
$cfg['decode_stdout']['wma']        = 'D:\Console\Codec\wmadec.exe -w %source';
$cfg['decode_stdout']['wv']         = 'D:\Console\Codec\wvunpack.exe -q %source -';



//  +---------------------------------------------------------------------------+
//  | Stream                                                                    |
//  |                                                                           |
//  | Winamp 5.08 and older streams OGG Vorbis files only good from IIS 5.1 and |
//  | IIS 6.0, Winamp 5.09 can also stream Ogg Vorbis from the Apache webserver.|
//  | Foobar 2000 can also stream Musepack.                                     |
//  |                                                                           |
//  | For ID TAGS the following variables can be used:                          |
//  | %artist, %title & %comment                                                |
//  | Use only tages that are compatible with streaming (begin of the stream)   |
//  |                                                                           |
//  | The following variables are in most situations not needed:                |
//  | %bits_per_sample, %sample_rate & %channels                                |
//  +---------------------------------------------------------------------------+
$cfg['stream_extension'][]          = 'mp3';
$cfg['stream_name'][]               = 'MP3 @ 64 kbps';
$cfg['stream_encode'][]             = 'D:\Console\Codec\lame.exe --abr 64 --id3v2-only --ta %artist --tt %title --tc %comment --noreplaygain -t --silent - -';
$cfg['stream_max_channels'][]       = 2;
$cfg['stream_transcode_treshold'][] = 65000;

$cfg['stream_extension'][]          = 'mp3';
$cfg['stream_name'][]               = 'MP3 @ Portable';
$cfg['stream_encode'][]             = 'D:\Console\Codec\lame.exe -V5 --vbr-new --id3v2-only --ta %artist --tt %title --tc %comment --noreplaygain -t --silent - -';
$cfg['stream_max_channels'][]       = 2;
$cfg['stream_transcode_treshold'][] = 130000;

$cfg['stream_extension'][]          = 'mp3';
$cfg['stream_name'][]               = 'MP3 @ High quality';
$cfg['stream_encode'][]             = 'D:\Console\Codec\lame.exe -V2 --vbr-new --id3v2-only --ta %artist --tt %title --tc %comment --noreplaygain -t --silent - -';
$cfg['stream_max_channels'][]       = 2;
$cfg['stream_transcode_treshold'][] = 250000;

/*
$cfg['stream_extension'][]          = 'ogg';
$cfg['stream_name'][]               = 'OGG @ 64 kbps';
$cfg['stream_encode'][]             = 'D:\Console\Codec\oggenc.exe --quiet --quality 0 --raw-bits=%bits_per_sample --raw-rate=%sample_rate --raw-chan=%channels --title %title --artist %artist --comment comment=%comment --output - -';
$cfg['stream_max_channels'][]       = 2;
$cfg['stream_transcode_treshold'][] = 65000;

$cfg['stream_extension'][]          = 'ogg';
$cfg['stream_name'][]               = 'OGG @ 128 kbps';
$cfg['stream_encode'][]             = 'D:\Console\Codec\oggenc.exe --quiet --quality 4 --raw-bits=%bits_per_sample --raw-rate=%sample_rate --raw-chan=%channels --title %title --artist %artist --comment comment=%comment --output - -';
$cfg['stream_max_channels'][]       = 2;
$cfg['stream_transcode_treshold'][] = 130000;

$cfg['stream_extension'][]          = 'ogg';
$cfg['stream_name'][]               = 'OGG @ 224 kbps';
$cfg['stream_encode'][]             = 'D:\Console\Codec\oggenc.exe --quiet --quality 7 --raw-bits=%bits_per_sample --raw-rate=%sample_rate --raw-chan=%channels --title %title --artist %artist --comment comment=%comment --output - -';
$cfg['stream_max_channels'][]       = 2;
$cfg['stream_transcode_treshold'][] = 250000;

//Musepack APE 2 tags are on the end of the file and therefore not usefull for streaming.
$cfg['stream_extension'][]			= 'mpc';
$cfg['stream_name'][]				= 'Musepack @ standard'; 
$cfg['stream_encode'][]				= 'D:\Console\Codec\mppenc.exe --silent --standard - -'; 
$cfg['stream_max_channels'][]		= 2; 
$cfg['stream_transcode_treshold'][]	= 190000;
*/



//  +---------------------------------------------------------------------------+
//  | MySQL configuration                                                       |
//  +---------------------------------------------------------------------------+
$cfg['mysql_host']                 = 'localhost';
$cfg['mysql_db']                   = 'netjukebox';
$cfg['mysql_user']                 = 'root';
$cfg['mysql_password']             = '';



//  +---------------------------------------------------------------------------+
//  | Cookie                                                                    |
//  +---------------------------------------------------------------------------+
$cfg['cookie_lifetime']             = 2114377200;
$cfg['cookie_expire']               = time() - 3600 * 24 * 365;



//  +---------------------------------------------------------------------------+
//  | Authenticate                                                              |
//  +---------------------------------------------------------------------------+
$cfg['authenticate_anonymous_user'] = 'anonymous';
$cfg['authenticate_expire']         = 3600 * 24 * 7;
?>