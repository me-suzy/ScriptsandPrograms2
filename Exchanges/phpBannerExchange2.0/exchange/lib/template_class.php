<?
$file_rev="041305";
////////////////////////////////////////////////////////
//                 phpBannerExchange                  //
//                   by: Darkrose                     //
//              (darkrose@eschew.net)                 //
//                                                    //
// You can redistribute this software under the terms //
// of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of  //
// the License, or (at your option) any later         //
// version.                                           //
//                                                    //
// You should have received a copy of the GNU General //
// Public License along with this program; if not,    //
// write to the Free Software Foundation, Inc., 59    //
// Temple Place, Suite 330, Boston, MA 02111-1307 USA //
//                                                    //
//     Copyright 2004 by eschew.net Productions.      //
//   Please keep this copyright information intact.   //
////////////////////////////////////////////////////////

class Page
{
var $page;

function Page($template = 'std.tpl') {
if (file_exists($template))
$this->page = join('', file($template));
else
die("Template file $template not found.");
}

function parse($file) {
ob_start();
include($file);
$buffer = ob_get_contents();
ob_end_clean();
return $buffer;
}

function replace_tags($tags = array()) {
if (sizeof($tags) > 0)
foreach ($tags as $tag => $data) {
$data = (@file_exists($data)) ? $this->parse($data) :
$data;
$this->page = eregi_replace('\{' . $tag . '\}', $data,
$this->page);
}
else
die('No tags designated for replacement.');
}

function output() {
print($this->page);
}
}
?>