<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

require('global.php');
/* --------------------------- */
// configuration
/* --------------------------- */
$conf['total'] = 10; //default value 
/* ------------------------------------------------------
ok, this is pretty simple to use.
---------------------------------------------------------

------------------------------------------------------- */


$ssi = new SSI;
class SSI
{

	function SSI()
	{
		//
	}

	function most_popular($total='')
	{
		global $conf,$admin,$udb,$database,$_GET,$_POST,$settings,$evoLANG,$art;
		
		if ($_GET['js'] == 1)
		{
			$js['f'] = "document.write('";
			$js['b'] = "');";
		}

		$total = $total != '' ? $total : $conf['total'];
		$category = $_GET['cat'] != '' ? " WHERE validated=1 AND pid='$_GET[cat]' ": ' WHERE validated=1';
		$target = $_GET['new'] == 0 ? 'target="blank"':'';

		// ni most popular, ikut views
		$sql = $udb->query("SELECT id,subject,author,views,validated FROM $database[article_article] $category ORDER BY views DESC LIMIT $total");
		while ( $row = $udb->fetch_array($sql) )
		{
			$row = $admin->strip_array($row);

			$c = "<a $target href=\"".$settings['siteurl']."/".$art->link_art($row[id])."\" title=\"$evoLANG[totalviews]: $row[views]\">".($_GET['len'] != '' ? $admin->partial($this->escape($row[subject]),$_GET['len']):$this->escape($row[subject]))."</a> <br />";
			$out .= $_GET['js'] == 1 ? $js['f'].$c.$js['b']:$c;
		}
		return $out;
	}

	function top_rated($total='')
	{
		global $conf,$admin,$udb,$database,$_GET,$_POST,$settings,$evoLANG,$art;

		$total = $total != '' ? $total : $conf['total'];
		$category = $_GET['cat'] != '' ? " WHERE validated=1 AND pid='$_GET[cat]' ": ' WHERE validated=1';
		$target = $_GET['new'] == 0 ? 'target="blank"':'';

		if ($_GET['js'] == 1)
		{
			$js['f'] = "document.write('";
			$js['b'] = "');";
		}
	
		// ni toprated
		$sql = $udb->query("SELECT numvote,totalvotes,id,subject,author,validated,IF(totalvotes>0,totalvotes/numvote,0) AS avg FROM $database[article_article] $category ORDER BY avg DESC LIMIT $total");
		while ( $row = $udb->fetch_array($sql) )
		{
			$row = $admin->strip_array($row);
			$row['rating'] = $art->process_rating($row['numvote'],$row['totalvotes']);
			$c = "<a $target href=\"".$settings['siteurl']."/".$art->link_art($row[id])."\" title=\"$evoLANG[avgrating]: ".$row['avg']."\">".($_GET['len'] != '' ? $admin->partial($this->escape($row[subject]),$_GET['len']):$this->escape($row[subject]))."</a> <br />";
			$out .= $_GET['js'] == 1 ? $js['f'].$c.$js['b']:$c;
		}

		return $out;
	}

	function latest($total='')
	{
		global $conf,$admin,$udb,$database,$_GET,$_POST,$settings,$evoLANG,$art;

		$total = $total != '' ? $total : $conf['total'];
		$category = $_GET['cat'] != '' ? " WHERE validated=1 AND pid='$_GET[cat]' ": ' WHERE validated=1';
		$target = $_GET['new'] == 0 ? 'target="blank"':'';

		if ($_GET['js'] == 1)
		{
			$js['f'] = "document.write('";
			$js['b'] = "');";
		}

		// ni most popular, ikut views
		$sql = $udb->query("SELECT id,subject,author,summary,validated FROM $database[article_article] $category ORDER BY id DESC LIMIT $total");
	
		while ( $row = $udb->fetch_array($sql) )
		{
			$row = $admin->strip_array($row);
			$row['summary'] = str_replace("\"","&quot;",strip_tags($row['summary']));

			$c = "<a $target href=\"".$settings['siteurl']."/".$art->link_art($row[id])."\" >".($_GET['len'] != '' ? $admin->partial($this->escape($row[subject]),$_GET['len']):$this->escape($row[subject]))."</a> <br />";
			$out .= $_GET['js'] == 1 ? $js['f'].$c.$js['b']:$c;
		}

		return $out;
	}
	
	function escape($code)
	{
		$code = htmlspecialchars($code);

		//this a workaround for javascript escape character
		$translate_back = array('\\' => '\\\\' );
		foreach($translate_back as $key => $value)
		{
			$code = str_replace($key,$value,$code);
		}
		
		return addslashes( str_replace('+','\+',$code) );
	}
}

switch ($_REQUEST['get'])
{
	/* ------------------------------- */
	case "popular":
		echo $ssi->most_popular($_GET['total']);
	break;
	/* ------------------------------- */
	case "toprated":
		echo $ssi->top_rated($_GET['total']);
	break;	
	/* ------------------------------- */
	case "latest":
		echo $ssi->latest($_GET['total']);
	break;
	/* ------------------------------- */
}

?>