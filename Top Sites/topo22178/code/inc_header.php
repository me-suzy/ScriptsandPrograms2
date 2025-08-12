<?php
//
//  inc_header.php
//	rev003
//
?>
<html>
<head>
<title><?php echo $gTopNombre; ?></title>
<meta http-equiv="PRAGMA" content="no-cache">
<META HTTP-EQUIV="EXPIRES" CONTENT="-1">
<META NAME="RESOURCE-TYPE" CONTENT="DOCUMENT">
<META NAME="DISTRIBUTION" CONTENT="GLOBAL">
<META NAME="AUTHOR" CONTENT="<?php echo $gTopNombre; ?>">
<META NAME="COPYRIGHT" CONTENT="<?php echo $gTopCopyright; ?>">
<META NAME="KEYWORDS" CONTENT="EJ3,TOPo,toplist,rank,<?php echo $gTopMetaTags; ?>>">
<META NAME="DESCRIPTION" CONTENT="TOPo,ej3,Powered by TOPo v2,<?php echo $gTopNombre; ?>">
<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
<META NAME="REVISIT-AFTER" CONTENT="1 DAYS">
<META NAME="RATING" CONTENT="GENERAL">
<META NAME="GENERATOR" CONTENT="EJ3 TOPo <?php echo $gVer; ?> - Copyright 2003 by E.J Jiménez">
<script type="text/javascript" src="code/topo.js"></script>
<script type="text/javascript" src="code/overlib.js"></script>
<link rel="stylesheet" type="text/css" href="themes/<?php echo $gTema; ?>/style.css">
<?php
if($gEstilo!='') echo '<link rel="stylesheet" type="text/css" href="css/'.$gEstilo.'">';
?>
</head>
<body>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>