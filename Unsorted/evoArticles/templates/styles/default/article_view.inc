<div style="font-weight:bold;font-size:11px;">$breadcrumbs_nav </div><br /><br />

<h1 class="box">$subject</h1>
<h3>$subtitle</h3>
$author_box
$evoLANG[by] : <a href="$_SERVER[PHP_SELF]?author/id:$author[id]"><b>$author[username]</b></a><br />
$evoLANG[rating] : $article[rating] <br />

<!-- $evoLANG[views] : $article[views]<br /> -->

<br />
<br />

<div id="article">
$article[article]
</div>

<br style="clear:both" />
<br style="clear:both" />



<div align="right">$prevnext</div><br />

$article[related]
$article[support]
$article[customfields]
$article[commentsform]
<div class="border_bottom"></div>
<br />
$pagenav
<!-- options box -->
<a target="_blank" href="$_SERVER[PHP_SELF]?print/id:$id"><img src="$script[imgfolder]print.gif" alt="$evoLANG[print]" /> <b>$evoLANG[print]</b></a> <br />
<a href="$_SERVER[PHP_SELF]?email/id:$id"><img src="$script[imgfolder]email.gif" alt="$evoLANG[sendfriend]" /> <b>$evoLANG[sendfriend]</b></a> $bookmark
<!-- /options box -->
$rating_selector




