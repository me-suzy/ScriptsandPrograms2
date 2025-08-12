<HTML><HEAD><TITLE>Picture Gallery</title></Head>

<?php

@$pic = $_GET['pic'];

if (@$pic == "")
{ $pic = 0; }

$picstart = $pic;

//Put the filenames of all images into the array $files.
$handle = opendir("images");
$key=1;
while (false !== ($file = readdir($handle)))
{
 if ($file <> "." AND $file <> ".." AND $file <> "system" AND $file <> "thumbs")
 {
  $files[$key] = $file;
  $key++;
 }
}
sort($files);

?>

<body bgcolor="EEEEEF">

<Center>
<table border="0" cellpadding="0" cellspacing="0">
<TR><TD><img src="images/system/topleft.gif"></TD>
    <TD colspan="5"><img src="images/system/top.gif" width="100%" height="33"></TD>
    <TD><img src="images/system/topright.gif"></TD>
</TR>
<TR><TD rowspan="6" background="images/system/left.gif"></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD rowspan="6" background="images/system/right.gif"></TD>
</TR>
<TR><TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
</TR>
<TR><TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
</TR>
<TR><TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
</TR>
<TR><TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
    <TD><?php if (array_key_exists($pic, $files)){ ?><a href="picture.php?p=<?php echo $pic; ?>"><img src="images/thumbs/<?php echo $files[$pic]; ?>" border="0"></A><BR><?php } $pic++; ?></TD>
</TR>
<TR><TD colspan="5">Click on any picture to enlarge.<BR>
    <?php if ($picstart > 0 AND $picstart > 24) { $picback = $picstart-25; ?> <a href="gallery.php?pic=<?php echo $picback; ?>">Previous 25 pictures</a><?php } ?>
    <?php if ($picstart > 0 AND $picstart <= 24) { $picback = 0; ?> <a href="gallery.php?pic=<?php echo $picback; ?>">Previous 25 pictures</a><?php } ?>

    <?php if (array_key_exists($pic, $files)) { ?> &nbsp; - &nbsp; <a href="gallery.php?pic=<?php echo $pic; ?>">Next 25 pictures</a><?php } ?>
</TR>
<TR><TD><img src="images/system/bottomleft.gif"></TD>
    <TD colspan="5"><img src="images/system/bottom.gif" width="100%" height="33"></TD>
    <TD><img src="images/system/bottomright.gif"></TD>
</TR>
</Table>
<?php
$lastpic=count($files)-1;
if ((array_key_exists($lastpic, $files) AND !file_exists("images/thumbs/$files[$lastpic]")) OR (array_key_exists($picstart, $files) AND !file_exists("images/thumbs/$files[$picstart]")))
{
echo "Thumbnails not loading? <a href=\"install.php?p=$picstart\">Refresh the thumbnails</a><BR>";
}
?>

<font size="1">Picture Displayer: <a href="http://www.stickmanarcade.com/phpicture">PHPicture.</a></font>
</center>

</body></HTML>
