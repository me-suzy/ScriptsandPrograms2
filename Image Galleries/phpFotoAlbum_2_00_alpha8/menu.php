<?php echo $str["menu_show"];?>:
<a href="index.php?show=list" class="button"><?php echo $str["menu_show_list"];?></a>
<a href="index.php?show=thumb" class="button"><?php echo $str["menu_show_thumb"];?></a>
&nbsp;&nbsp;<?php echo $str["menu_sort"];?>:
<a href="index.php?sort=name" class="button"><?php echo $str["menu_sort_name"];?></a>
<a href="index.php?sort=time" class="button"><?php echo $str["menu_sort_time"];?></a>
<a href="index.php?sort=type" class="button"><?php echo $str["menu_sort_type"];?></a>
<a href="index.php?sort2=asc">
<img src="skin/<?php echo $_SESSION["s_data"]["skin"];?>/sort_asc.png" width="16" height="16" alt="asc" border="0" /></a>
<a href="index.php?sort2=desc">
<img src="skin/<?php echo $_SESSION["s_data"]["skin"];?>/sort_desc.png" width="16" height="16" alt="desc" border="0" /></a>
<?php
if ($slideshow_enabled){
echo "&nbsp;&nbsp;<a href=\"image_show_slideshow.php\" target=\"slideshow\" OnClick=\"NewWindow('','slideshow',(screen.width-10),(screen.height-60),'no','x');\" class=\"button\">".$str["menu_slideshow"]."</a>";
}
?>
&nbsp;&nbsp;<a href="index.php?page=setup" class="button_setup"><?php echo $str["menu_setup"];?></a>