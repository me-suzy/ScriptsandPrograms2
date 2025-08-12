{junk}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html><head><title>{title}</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<style type="text/css">
        @import "all.css"; /* just some basic formatting, no layout stuff */

        body {
                margin:10px 10px 0px 10px;
                padding:0px;
                }

        #leftcontent {
                position: absolute;
                left:10px;
                top:100px;
                width:200px;
                background:#fff;
                border:1px solid #000;
                }

        #centercontent {
                background:#fff;
                   margin-left: 199px;
                   margin-right:199px;
                border:1px solid #000;
                /*
                IE5x PC mis-implements the box model. Because of that we sometimes have
                to perform a little CSS trickery to get pixel-perfect display across browsers.
                The following bit of code was proposed by Tantek Celik, and it preys upon a CSS
                parsing bug in IE5x PC that will prematurly close a style rule when it runs
                into the string "\"}\"". After that string appears in a rule, then, we can override
                previously set attribute values and only browsers without the parse bug will
                recognize the new values. So any of the name-value pairs above this comment
                that we need to override for browsers with correct box-model implementations
                will be listed below.

                We use the voice-family property because it is likely to be used very infrequently,
                and where it is used it will be set on the body tag. So the second voice-family value
                of "inherit" will override our bogus "\"}\"" value and allow the proper value to
                cascade down from the body tag.

                The style rule immediately following this rule offers another chance for CSS2
                aware browsers to pick up the values meant for correct box-model implementations.
                It uses a CSS2 selector that will be ignored by IE5x PC.

                Read more at http://www.glish.com/css/hacks.asp
                */

                voice-family: "\"}\"";
                voice-family: inherit;
                   margin-left: 201px;
                   margin-right:201px;
                }
        html>body #centercontent {
                   margin-left: 201px;
                   margin-right:201px;
                }

        #rightcontent {
                position: absolute;
                right:10px;
                top:100px;
                width:200px;
                background:#fff;
                border:1px solid #000;
                }

        #banner {
                background:#fff;
                height:95px;
                border-top:1px solid #000;
                border-right:1px solid #000;
                border-left:1px solid #000;
                voice-family: "\"}\"";
                voice-family: inherit;
                height:39px;
                }
        html>body #banner {
                height:39px;
                }

        p,h1,pre {
                margin:0px 10px 10px 10px;
                }

        h1 {
                font-size:14px;
                padding-top:10px;
                }

        #banner h1 {
                font-size:14px;
                padding:10px 10px 0px 10px;
                margin:0px;
                }

        #rightcontent p {
                font-size:10px
                }

</style>

<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
<!--

if (window != top) top.location.href = location.href;

// -->
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
<!--

if (window != parent) top.location.href = location.href;

// -->

</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
<!--

if (self != top) top.location.href = location.href;

// -->
</SCRIPT>


</head><body>
<div id="banner"><center>{menu}</center><center>{event}</center><center>{pages}</center></div>
<div id="leftcontent">
{left}
<BR>
<script type="text/javascript"><!--
google_ad_client = "pub-6088426709750512";
google_alternate_color = "000000";
google_ad_width = 160;
google_ad_height = 90;
google_ad_format = "160x90_0ads_al_s";
google_ad_type = "text_image";
google_ad_channel ="8498960370";
google_page_url = document.location;
google_color_border = "220033";
google_color_bg = "000000";
google_color_link = "6A55A1";
google_color_url = "6A55A1";
google_color_text = "ffffff";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>

<div id="centercontent">
<table border=0 align=center style="width: 70%; min-width: 500px; max-width: 1000px;"><tr><td>
{logged}


{content}


{lowregion}


{lowchat}


<script type="text/javascript"><!--
google_ad_client = "pub-6088426709750512";
google_alternate_color = "000000";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text_image";
google_ad_channel ="8498960370";
google_page_url = document.location;
google_color_border = "220033";
google_color_bg = "000000";
google_color_link = "6A55A1";
google_color_url = "6A55A1";
google_color_text = "ffffff";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>


<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
<!--

c=0;
h = screen.height;
w = screen.width;
r="?h="+h+"&w="+w+"&id={userid}";
document.write('<img src="getres.php'+r+'">');

// -->
</SCRIPT>
</td></tr></table>
</div>

<div id="rightcontent">
{right}
<BR>
<script type="text/javascript"><!--
google_ad_client = "pub-6088426709750512";
google_alternate_color = "000000";
google_ad_width = 160;
google_ad_height = 90;
google_ad_format = "160x90_0ads_al_s";
google_ad_type = "text_image";
google_ad_channel ="8498960370";
google_page_url = document.location;
google_color_border = "220033";
google_color_bg = "000000";
google_color_link = "6A55A1";
google_color_url = "6A55A1";
google_color_text = "ffffff";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>


<script language="JavaScript" type="text/javascript" src="wz_tooltip.php"></script>
</body>
</html>