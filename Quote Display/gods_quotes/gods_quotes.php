<?php
$quotes=array(
         1=>"Let's meet at my house Sunday before the game. -God",
        2=>"What part of 'Thou shalt not...' didn't you understand? -God",
        3=>"We need to talk. -God",
        4=>"Keep using my name in vain, I'll make rush hour longer. -God",
        5=>"Loved the wedding, invite me to the marriage. -God",
        6=>"That 'Love thy neighbor' thing...I meant it. -God",
        7=>"Will the road you're on get you to my place? -God",
        8=>"Follow me. -God",
        9=>"Don't make me come down there. -God",
        10=>"I don't question YOUR existence. -God",
        11=>"I saw that. -God",
        12=>"I'm also making a list and checking it twice. -God",
        13=>"I exist, therefore you are. -God",
        14=>"Talk to me...listen to your kids. -God",
        15=>"Do you have any idea where you're going? -God",
        16=>"I've missed you. -God",
        17=>"Big bang theory, you've got to be kidding. -God",
        18=>"Need directions? -God",
        19=>"You think it's hot here? -God",
        20=>"Have you read my #1 best seller? There will be a test. -God",
        21=>"It's a small world.  I know...I made it. -God",
        22=>"Life is short.  Eternity isn't. -God",
        23=>"The real supreme court meets up here. -God",
        24=>"One nation under me. -God",
        25=>"If you must curse, use your own name! -God",
        26=>"All I know is...everything. -God",
        27=>"Feeling lost?  My book is your map. -God",
        28=>"As my apprentice, you're never fired. -God",);
$random_number=rand(1, count($quotes));
$random_quote=$quotes[$random_number];
echo "<style type='text/css'>
<!--
.blackBack {
        background-color: #000000;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        font-weight: bold;
        color: #FFFFFF;
        vertical-align: middle;
        text-align: center;
}
.silverBack {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
        font-weight: bold;
        background-color: #CCCCCC;
        text-align: center;
}
.noBack {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
        font-weight: bold;
        text-align: center;
}
-->
</style><br>\n";
echo "<table width='150' border='0' cellspacing='0' cellpadding='0'>";
echo "<tr class='silverBack'>\n";
echo "<td width='5'>&nbsp;</td>\n";
echo "<td><a href='http://www.godspeaks.com' target='_blank'>God
Speaks</a></td>\n";
echo "<td width='5'>&nbsp;</td>\n";
echo "</tr>\n";
echo "<tr class='blackBack'>\n";
echo "<td width='5'><img src='http://clarksco.com/resources/images/spacer.gif' height='50' width='5'></td>\n";
echo "<td>$random_quote</td>\n";
echo "<td width='5'><img src='http://clarksco.com/resources/images/spacer.gif' height='50' width='5'></td>\n";
echo "</tr>\n";
echo "<tr class=noBack>\n";
echo "<td width='5'>&nbsp;</td>\n";
echo "<td><a href='http://clarksco.com/blog/?p=4' target='_blank'>Add to your site</a></td>\n";
echo "<td width='5'>&nbsp;</td>\n";
echo "</tr>\n";
echo "</table>";
?>
