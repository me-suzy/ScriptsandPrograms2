<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/



$page = $_REQUEST['page'];

$width = intval(100 / (count($menuitem) + 1));
$X->assign('width',$width);
$X->assign('config',$config);
$X->assign('pagedesc',$menuitem[$page]);

foreach ($menuitem as $item=>$desc)
{
	if ($page == $item)
	{
		$_SESSION['page_cat'] = $page;
		break;
	}
}

foreach($menuitem as $item=>$desc)
{
	$i++;
	$items[$i]['item'] = $item;
	$items[$i]['desc'] = $desc;
	if ($item == $page)
	{
		$_SESSION['page_cat'] = $page;
	}
	if ($item == $_SESSION['page_cat'])
	{
		$_SESSION['page_cat'] = $item;
		$items[$i]['img_type'] = '';
		$items[$i]['class'] = 'navLink';
	}	
	else
	{
		$items[$i]['img_type']	= '_lolight';
		$items[$i]['class']		= 'navLink';
	}
}

$X->assign('menu',$items);
?>
