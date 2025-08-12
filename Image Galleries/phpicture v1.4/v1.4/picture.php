<!-- Picture displayer created by Crashthatch (Fletcher). Please do not remove this line. Thank You -->
<?php

@$p = $_GET['p'];
@$screenheight = $_GET['screenheight'];
@$autoscroll = $_GET['autoscroll'];
@$scrolltime = $_GET['scrolltime'];

if (@$p == "")
{ $p = 0; }

$o = $p-1;
$q = $p+1;

//Find what the first picture in the directory is.
$handle = opendir("images");
$key=1;
while (false !== ($file = readdir($handle)))
{
 if ($file <> "." AND $file <> ".." AND $file <> "system" AND $file <> "thumbs")
 {
  $filesa[$key] = $file;
  $key++;
 }
}
sort($filesa);

if (!array_key_exists($p, $filesa))
{
 $p = 0;
 $o = $p-1;
 $q = $p+1;
}
$size = getimagesize ("images/$filesa[$p]");

$fullheight=@$screenheight*0.7;
if (@$screenheight<=200)
{
$fullheight=$size[1];
}

$multiplier=$fullheight/$size[1];
$fullwidth=$size[0]*$multiplier;

//set captions
if (file_exists("captions.txt")){
        $cap = null;
        $capfile = file_get_contents("./captions.txt");
        $capcaps = explode("\n",$capfile);
        foreach ($capcaps as $curcap){
                $curcapex = explode("|",$curcap);
                if ($curcapex[0] == $filesa[$p]){
                        $cap = $curcapex[1];
                        break;
                }
        }
}
?>

<HTML><HEAD><TITLE>Picture Gallery</title>
<?php
//Insert the auto-scroll text.
if (@$scrolltime == "") {$scrolltime=3;}
if (@$autoscroll=="1" AND array_key_exists($q, $filesa))
{
echo"<META HTTP-EQUIV=\"refresh\" CONTENT=\"$scrolltime; url=picture.php?p=$q&autoscroll=1&scrolltime=$scrolltime&screenheight=$screenheight\">";
}
?>
</head>


<body bgcolor="EEEEEF">
<center><table cellspacing="0" boarder="0" cellpadding="0">

<?php
//Show caption.
if (isset($cap)){
?>
<TR>
  <TD colspan="5" align="center">
                <b><big><? echo $cap; ?></big></b>
  </TD>
</TR>
<?
}
?>

<TR><TD></TD>
    <TD><img src="images/system/topleft.gif"></TD>
    <TD><img src="images/system/top.gif" width="100%" height="33"></TD>
    <TD><img src="images/system/topright.gif"></TD>
    <TD></TD>
</TR>
<TR><TD><?php
/* Check if previous picture exists */
if (array_key_exists($o, $filesa))
{ ?>
        <a href="picture.php?p=<?php echo $o; ?>&screenheight=<?php echo @"$screenheight";?>"><img src="images/system/previous.gif" border="0"></a>
<?php } ?>
        <BR><BR><img src="images/system/home.gif" border="0"></TD>
    <TD><img src="images/system/left.gif" height="100%" width="36"></TD>
    <TD height="<?php echo "$fullheight";?>"><img src="images/<?php echo $filesa[$p]; ?>" height="100%" width="<?php echo "$fullwidth"; ?>"></TD>
    <TD><img src="images/system/right.gif" height="100%" width="36"></TD>
    <TD>
<?php /* Check if next picture exists */
if (array_key_exists($q, $filesa))
{
$qexists=1; ?>
<a href="picture.php?p=<?php echo $q; ?>&screenheight=<?php echo @"$screenheight";?>"><img src="images/system/next.gif" border="0"></a>
<?php } ?>
        <BR><BR><a href="gallery.php"><img src="images/system/gallery.gif" border="0"></a></TD>
</TR>
<TR><TD></TD>
    <TD><img src="images/system/bottomleft.gif"></TD>
    <TD><img src="images/system/bottom.gif" width="100%" height="33"></TD>
    <TD><img src="images/system/bottomright.gif"></TD>
    <TD></TD>
</TR>
<TR>
  <TD colspan="5" align="center">
  <?php
  if (@$qexists == 1)
  { ?>
    <form style="display:inline;" action="picture.php">
    <input type="hidden" name="autoscroll" value="1">
    <input type="hidden" name="p" value="<?php echo "$p"; ?>">
    <input type="hidden" name="screenheight" value="<?php echo @"$screenheight"; ?>">
    <font size="2">Scroll-time (secs):</font><input type="text" name="scrolltime" value="<?php echo "$scrolltime"; ?>" size="3" maxlength="2">
    <input type="submit" value="Start">
    </form>
    <form style="display:inline;" action="picture.php">
    <input type="hidden" name="p" value="<?php echo "$p"; ?>">
    <input type="hidden" name="screenheight" value="<?php echo @"$screenheight"; ?>">
    <input type="submit" value="Stop">
    </form>
   <?php
   }
   else
   { ?>
    <form style="display:inline;" action="picture.php">
    <input type="hidden" name="p" value="0">
    <input type="hidden" name="autoscroll" value="1">
    <input type="hidden" name="scrolltime" value="<?php echo "$scrolltime"; ?>">
    <input type="hidden" name="screenheight" value="<?php echo @"$screenheight"; ?>">
    <input type="submit" value="Restart slideshow">
    </form>
   <?php
   } ?>




    <BR>

    <form style="display:inline;" action="picture.php">
    <input type="hidden" name="p" value="<?php echo "$p"; ?>">
    <input type="hidden" name="autoscroll" value="<?php echo @"$autoscroll"; ?>">
    <input type="hidden" name="scrolltime" value="<?php echo @"$scrolltime"; ?>">
    <?php
    if ($screenheight == 0)
    { ?>
    <script>
    document.write('<input type="hidden" name="screenheight" value="'+screen.height+'">');
    </script>
    <?php
    }
    ?>
    <input type="submit" value="Full-screen">
    </form>
  </TD>
</TR>
</Table>

<font size="1">Picture Displayer: <a href="http://www.stickmanarcade.com/phpicture">PHPicture.</a></font>
<!-- Picture displayer created by Crashthatch. Please do not remove this line. Thank You -->

</body></Html>
