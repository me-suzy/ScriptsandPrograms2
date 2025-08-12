<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/SKINS/DEFAULT/HEADER.PHP > 03-11-2005
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>somery admin system: <?php echo $website; ?></title>

<link rel="stylesheet" href="<?php echo $skindir; ?>/style.css" type="text/css" />

<body>

<div id="content">

<h1>somery admin</h1>
<h2><?php echo menu(); ?></h2>