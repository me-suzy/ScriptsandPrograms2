<table align="center" border="0" width="640"><tr><td class="s">
<?php
print '<a href="wap.php" style="color:#ffffff" title="Phones &amp; PDA">wap</a> ';
if(stristr($PHP_SELF,'main.php')){
print '<a href="rdf.php?f='.$f.'" style="color:#ffffff" title="RSS/RDF1.0 feed">rss1</a> ';
print '<a href="rss.php?f='.$f.'" style="color:#ffffff" title="RSS2.0 feed">rss2</a> ';
print '<a href="klip.php?f='.$f.'&amp;/forum-'.$f.'.klip" style="color:#ffffff" title="KlipFolio allows a user to subscibe to, configure and then monitor a wide variety of real-time information services on their desktop like weather, stocks, breaking news, rss feeds or auctions. These information services are called Klips. Go to http://klipfarm.com and get KlipFolio!">klip</a>';
}
?>
</td><td class="s" align="right">
<?php 

/* navigation bar with a drop-down menu - multiforum only! */

// if(count($forum_name)>1&&count($forum_data)>1){
// print '<form action="index.php" onsubmit="return false">';
// print '<select onchange="if(this.value.indexOf(\'.php\')!=-1){window.location=this.value}">';
// print '<option value="0">Choose a forum...</option>';
// if(!stristr($PHP_SELF,'admin')){print '<option value="index.php">index -&gt;</option>';}
// for($i=0;$i<count($forum_data);$i++){
// if(stristr($PHP_SELF,'admin')){$page='admin.php';}else{$page='main.php';}
// print "<option value=\"$page?f=$i\">$forum_name[$i]</option>";}
// print '</select></form>';} 


/* navigation bar with links - multiforum only! */

if(count($forum_name)>1&&count($forum_data)>1){
if(!stristr($PHP_SELF,'admin')){print '<a href="index.php" style="color:#ffffff">index</a><span class="w">:</span>';}
for($i=0;$i<count($forum_data);$i++){
if(stristr($PHP_SELF,'admin')){$page='admin.php';}else{$page='main.php';}
print " <a href=\"$page?f=$i\" style=\"color:#ffffff\">$forum_name[$i]</a>";}
}

?></td></tr><tr><td colspan="2">