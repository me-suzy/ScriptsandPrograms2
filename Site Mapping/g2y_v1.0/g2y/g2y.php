<?php 
/*
iELLIOTT.com's Google 2 Yahoo Sitemap Converter
Version: 1.0 10/31/2005

Use this script at your own risk.  No Warranty or Support can be provided.
If you use this script, please give credit to iELLIOTT.com and link back to:
http://www.ielliott.com/2005/10/31/google-to-yahoo-sitemap/

This script is subject to the following license:
http://creativecommons.org/licenses/by-sa/2.5/


CUSTOMIZATION
==============
$input_file is the URL location of your exsisting Google sitemap XML file (must end with .xml)  

$output_file is the name of the file you wish to write the Yahoo sitemap data to.  Default is yahoo.txt, but if you've changed the name to something else, it must be reflected here (must end with .txt).  Also be sure that you CHMOD your $output_file to 777 on your server.
*/

$input_file = "http://www.yourdomain.com/sitemap.xml";
$output_file = "yahoo.txt";
?>



<?php

set_time_limit(0);

header("Content-Type: text/plain");
echo " Time: ".gmdate("r")."\n";
echo "-------------------------------------------------------------\n";


$xml = xml_parser_create("UTF-8");

xml_parser_set_option($xml, XML_OPTION_SKIP_WHITE, 1);
xml_parser_set_option($xml, XML_OPTION_CASE_FOLDING, 1);

xml_set_element_handler($xml, "XMLElementStart", "XMLElementEnd");
xml_set_character_data_handler ($xml, "XMLCData");

$xml_level = 0;
$xml_isloc = FALSE;
$i = 0;

$fpop = fopen($output_file, "wb");
$fp = fopen($input_file, "rb");

if ($fp)
{
	while(!feof($fp))
	{
		$s = fgets($fp, 10000);
		xml_parse($xml, $s);
	}
	$ret = xml_parse($xml, "", TRUE);
	fclose($fp);
}
fclose($fpop);

echo "-------------------------------------------------------------\n";
echo " Total $i URLS\n";
echo " Time: ".gmdate("r")."\n";

function XMLElementStart($xml, $element, $attribs)
{
	global $xml_level, $xml_isloc;

	$xml_level++;
	if ($xml_level == 3 && $element == "LOC") $xml_isloc = TRUE;
}

function XMLElementEnd($xml, $element)
{
	global $xml_level, $xml_isloc;

	if ($xml_level == 3 && $element == "LOC") $xml_isloc = FALSE;
	$xml_level--;

}

function XMLCData($xml, $data)
{
	global $xml_level, $xml_isloc, $fpop, $i;

	if($xml_isloc)
	{
		$i++;
		echo " $data\n";
		fwrite($fpop, $data."\n");
	}
}


?>
