<?php
/*
--------------------------------------------------------------------------------
PhpDig 1.6.x
This program is provided under the GNU/GPL license.
See LICENSE file for more informations
All contributors are listed in the CREDITS file provided with this package

PhpDig Website : http://phpdig.toiletoine.net/
Contact email : phpdig@toiletoine.net
Author and main maintainer : Antoine Bajolet (fr) bajolet@toiletoine.net
--------------------------------------------------------------------------------
*/
//the main purpose for this functions is to strip the slashes for all output
addbstack('', 'Search', 'search');
addbstack('', 'Home');

$t->set_file("block", "main_tpl_no_nav.html");
$t->set_var("breadcrum", $breadcrumstack);
$t->set_var("itemtitle", "Search");		    
$t->set_var("pagetitle", $sitename." - Search");

$formt = new Template("./");

ob_start();
$relative_script_path='includes/phpdig';
include ("includes/phpdig/includes/config.php");
include ("includes/phpdig/libs/search_function.php");

// extract vars
extract(phpdigHttpVars(
     array('query_string'=>'string',
           'template_demo'=>'string',
           'refine'=>'integer',
           'refine_url'=>'string',
           'site'=>'integer',
           'limite'=>'integer',
           'option'=>'string',
           'search'=>'string',
           'lim_start'=>'integer',
           'browse'=>'integer',
           'path'=>'string'
           )
     ));

phpdigSearch($id_connect, $query_string, $option, $refine,
              $refine_url, $lim_start, $limite, $browse,
              $site, $path, $relative_script_path, $template);


$containera = ob_get_contents(); 
ob_end_clean();

if ($query_string=='') {
    /*
		$pageadd="
		<table cellspacing=2 cellpadding=0 width=\"100%\" border=0>
			<tr bgcolor=\"#C5C7B5\" > 
				<td colspan=2><img height=1 src=\"".$absolutepath."images/clearpixel.gif\" width=2></td>
			</tr>
			<tr> 
				<td colspan=2><img height=10 src=\"".$absolutepath."images/clearpixel.gif\" width=2></td>
			</tr>
			<tr> 
				<td valign=top><img height=22 alt=\"\" hspace=3 src=\"".$absolutepath."images/lb_pijl.gif\" width=22 vspace=3></td>
				<td valign=top width=\"100%\"> 
					<table cellspacing=2 cellpadding=0 border=0>
						<tr> 
							<td><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=15></td>
							<td><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=2></td>
							<td><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=2></td>
							<td><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=2></td>
							<td><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=2></td>
							<td><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=2></td>
							<td><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=2></td>
							<td><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=2></td>
							<td width=\"100%\"><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=2></td>
						</tr>
						<tr> 
							<td><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=15></td>
							<td><img height=2 src=\"".$absolutepath."images/clearpixel.gif\" width=5></td>
							<td colspan=7><p><a href=\"or search here\" target=\"_blank\">Or search here</a></p><b>- Or - </b></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>";
		*/
$containera=$pageadd.$containera;
}
$t->set_var("leftnav", $leftnav);
$t->set_var("containera", $containera);
?>