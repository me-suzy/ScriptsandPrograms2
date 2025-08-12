<?
$xml_file=$xmlPath . "xml/my_tables.xml";
//if (!$dom = domxml_open_file($xml_file,DOMXML_LOAD_VALIDATING,$error)) {
if (!$dom = domxml_open_file($xml_file)) {
  echo "Error while parsing the document : $xml_file";
  exit;
}
$d_schema = $dom->document_element();
?>
