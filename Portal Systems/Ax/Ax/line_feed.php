<?php
Global $line,$bus;
$file = "data/central/data.xml";

function startElement($parser, $name, $attrs) {
 global $map_array,$write,$code;
 switch ($name)
 {
     case 'TITLE':
     $write=true;
     $code=0;
     break;
     
     case 'URL':
     $write=true;
     $code=1;
     break;
     default:
     $write=false;
 }


}
function endElement($parser, $name) {
 global $map_array,$write;


}
function characterData($parser, $data) {
 global $write,$code,$line,$bus;

 if ($code==0) { if ($write) {

     $data=ereg_replace("Ã¨","è",$data);
     $data=ereg_replace("Ã","à",$data);
     $data=ereg_replace("à©","é",$data);
     $data=ereg_replace("à§","ç",$data);
     $data=stripslashes($data);
 $bus.=$data;}}
 
 if ($code==1) { if ($write) {$bus.="<a href=ax.php"."$data>....</a><br><br>";}}

}
 $bus="";

$xml_parser = xml_parser_create();
// use case-folding so we are sure to find the tag in $map_array
xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, TRUE);
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");
if (!($fp = fopen($file, "r"))) {
 die("could not open XML input");
}
while ($data = fread($fp, 4096)) {

 if (!xml_parse($xml_parser, $data, feof($fp))) {
 die(sprintf("XML error: %s at line %d",
 xml_error_string(xml_get_error_code($xml_parser)),
 xml_get_current_line_number($xml_parser)));

 }

}
xml_parser_free($xml_parser);
$bus1="<marquee id=\"scroller\" scrollamount=\"1\" direction=\"up\" width=\"135\" height=\"120\"
onmouseover=\"javascript:scroller.stop()\" onmouseout=\"javascript:scroller.start()\">";
$bus2="</marquee>";
$bus=$bus1.$bus.$bus2;
echo"<table width=100% border=0 cellpadding=0 cellspacing=0 bordercolor=black>";
echo"<tr bgcolor=#0080C1>";
echo"<td align=left background=\"themes/Odebi/images/table-title.gif\" width=\"138\" height=\"20\">";
Echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=white><b>Headlines</b></font>";
Echo"</td>";
Echo"</tr>";
Echo"<tr>";
echo"<td>";
echo $bus;
Echo"</td>";
Echo"</Tr>";
Echo"</table>";
Echo"<br>";
?>
