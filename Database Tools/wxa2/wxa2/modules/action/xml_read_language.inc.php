<?
$xml_file=$xmlPath . "xml/local/" . $g_language . ".xml";
//if (!$dom = domxml_open_file($xml_file,DOMXML_LOAD_VALIDATING,$error)) {
if (!$dom_lan = domxml_open_file($xml_file)) {
  echo "Error while parsing the document : $xml_file";
  exit;
}
$language_node = $dom_lan->document_element();
 $ctx_languages = xpath_new_context($language_node);
 $xpath_languages="/msg";
 if ($xpo = @$ctx_languages->xpath_eval($xpath_languages))
   while(list($index, $message) = each($xpo->nodeset))
   	{
		$arr_messages[$message->get_attribute("id")]=$message->get_content();
	}
?>
