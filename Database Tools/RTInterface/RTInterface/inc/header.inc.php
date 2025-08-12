<?php 
require("./inc/initialize.inc.php"); //Initialize all the session vars and include all the required files
?>

<HTML>
<head>
<title><?php global $page_title; echo $page_title;?></title>
<link href="./inc/cfg.inc.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php showTitleImage()?>
<table width="80%" border="0" align=center>
  <tr>
    <td>
    <?php showTableName();  ?>

