<HEAD>
<TITLE>WebRing</TITLE>
<LINK REL=STYLESHEET HREF=style.css>
</HEAD>
<BODY>
<? require("config.php"); ?>
<DIV CLASS=headline>Get Ring Code</DIV>
<DIV CLASS=Normal>Copy and paste the code below onto your web page, where you want the ring to appear.
<HR>
&lt;DIV ALIGN=CENTER&gt;<BR>
[&lt;A HREF=<? echo $url; ?>&gt;<? echo $ring_name; ?> Home&lt;/A&gt;]&lt;BR><BR>
[&lt;A HREF=<? echo $url; ?>/goring.php?action=prev&id=<? echo $id;?>&gt;Prev&lt;/A&gt;]&nbsp;<BR>
[&lt;A HREF=<? echo $url; ?>/goring.php?action=rand&gt;Random&lt;/A&gt;]&nbsp;<BR>
[&lt;A HREF=<? echo $url; ?>/goring.php?action=all&gt;List All&lt;/A&gt;]&nbsp;<BR>
[&lt;A HREF=<? echo $url; ?>/goring.php?action=next&id=<? echo $id; ?>&gt;Next&lt;/A&gt;]<BR>
&lt;/DIV&gt;<BR>
</DIV>