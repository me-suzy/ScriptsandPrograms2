<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title> <?php echo ($data['title']); ?> </title>
</head>

<body>

<h2 align="center"><?php echo ($data['message']); ?></h2>

<table align="center">
<tr>
<td colspan="2">PHP elements used by AwesomeTemplateEngine:</td>
</tr>

<?php
foreach ($data['table'] as $row) {
?>

<tr>
<td><?php echo ($row['item']); ?></td>
<td><a href="<?php echo ($row['url']); ?>">Read More</a></td>
</tr>

<?php
}
?>

</table>

<p align="center"><?php echo ($data['poweredby']); ?>

<p align="center">The AwesomeTemplateEngine was inspired by 
<a href="http://www.sitepointforums.com/showthread.php?s=&threadid=67849">this discussion at Sitepointforums</a>.
Pay close attention to comments by <b>voostind</b>
<p align="center">Flames to the AwesomeTemplateEngine author <a href="http://forum.pinkgoblin.com/viewtopic.php?p=325#325">here</a>
</body>
</html>