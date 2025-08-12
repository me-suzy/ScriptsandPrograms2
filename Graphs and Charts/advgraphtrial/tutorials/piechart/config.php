<?php

/* ------------------------------------------------------------------ */
/*                                                                    */
/* This config script is a very simply little script which            */
/* outputs the configuration data for the graph.                      */
/*                                                                    */
/* ------------------------------------------------------------------ */


print "<!-- Chart Switches -->\n";
print "3d:                 true\n";
print "displayPercentages: true\n";
print "labellines:         true\n";

print "quality: high\n";

print "<!-- Segment Labels -->\n";
print "segmentlabels: true\n";
print "segmentlabelfont: medium\n";
print "segmentlabelcolor: dark blue\n";

print "<!-- Chart Characteristics -->\n";
print "width:      500\n";
print "height:     500\n";
print "ndecplaces: 2\n";
print "pecentndecplaces: 0\n";
print "depth3d:    15\n";
print "3dangle:    50\n";

print "<!-- Popup segment Value Pre & Post Symbols -->\n";
print "valuepresym: $\n";

print "<!-- thousand seperater -->\n";
print "thousandseparator: ,\n";

print "<!-- Additional color information -->\n";
print "backgroundcolor:      #F0F0F0\n";

print "<!-- Title --> \n";
print "titletext: Sales by Region\n";
print "titlefont: large\n";
print "titlecolor: #444444\n";
print "titleposition: 5,1\n";

print "<!-- Free Form Text -->\n";
print "<!--  textn         text|font|color|x,y pos -->\n";
print "text1: Product X|medium|#005500|50,80\n";
print "text2: Product Y|medium|#005500|250,205\n";

print "<!-- Legend Information -->\n";
print "legend:            true\n";
print "legendfont:        medium\n";
print "legendposition:    5,20\n";
print "legendtitle:       Sales Region\n";
print "legendbgcolor:     #FFFFFF\n";
print "legendbordercolor: #DDDDDD\n";
print "legendtextcolor:   #202020\n";
print "legendstyle:       horizontal\n";

print "<!-- Pie Data --> \n";
print "<!--  PieN   x,y|size|seperation -->\n";
print "pie1: 115,180|80|0\n";
print "pie2: 290,320|130|10\n";

print "<!-- segment Data --> \n";
print "<!-- segmentN       series color|legend label| -->\n";
print "segment1: red|N America|\n";
print "segment2: green|Europe|\n";
print "segment3: blue|Asia|\n";
print "segment4: orange|Africa|\n";
print "segment5: purple|Australia|\n";
print "segment6: cyan|S America|\n";

?>