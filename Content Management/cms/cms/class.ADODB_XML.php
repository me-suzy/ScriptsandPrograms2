<?php include_once ("config.php") ?>
<?php
      session_start();
      if (@$_SESSION["status"] <> "login" || ($useSSL && $_SERVER['HTTPS'] != 'on')) header("Location: login.php");
?>
<?php require("class.xml.php"); ?>
<?php
/**
* Class of Manipulation of files XML and SGBD
*
* @author			Olavo Alexandrino <oalexandrino@yahoo.com.br> - 2004
* @originalAuthor		Ricardo Costa <ricardo.community@globo.com>   - 2002
* @based				MySQL to XML - XML to MySQL - <http://www.phpclasses.org/browse/package/782.html>
* @require				Class XMLFile <Olavo Alexandrino version> - <orignal version: http://www.phpclasses.org/browse/package/79.html>
		- function commentary to strtoupper of the methods:
			1. add_attribute
			2. set_name
* @require				ADOdb Database Library for PHP <http://php.weblogs.com/adodb#downloads>
*/

class ADODB_XML 
{
	/**
	*	Object representation:	File XML
	*
	*	@type		objcet
	*	@access 	public
	*/
	var $xml = null; 

	/**
	*	Creating the members
	*
	*	@param		string		Version of file XML
	*	@param		string		Codification to be used 
	*	@access 	public
	*/
	function ADODB_XML($version = "", $encoding = "") 
	{
	  $this->xml = new XMLFile($version, $encoding);
	}
	
	/**
	*	It converts Table of the SGBD into file XML
	*
	*	@param		object			Connection of ADOdb Database Library	
	*	@param		string			Query SQL			
	*	@param		string			Name of existing file XML
	*	@access 	public
	*	@return 	void	
	*/	
	function ConvertToXML($dbConnection, $strSQL, $filename) 
	{
	  $dbConnection->SetFetchMode(ADODB_FETCH_ASSOC);
	  $rs = $dbConnection->Execute($strSQL);
	  
	  $this->xml->create_root();
	  $this->xml->roottag->name = "ROOT";
	  
	  while(!$rs->EOF)
	  {
		 $this->xml->roottag->add_subtag("ROW", array());
		 $tag = &$this->xml->roottag->curtag;
		 
		 for ($i = 0; $i < $rs->_numOfFields ; $i++)
		 {
			list($field, $value) = each($rs->fields);
			$value = preg_replace("/</", ";lt;", $value);
			$value = preg_replace("/>/", ";gt;", $value);
			$tag->add_subtag($field);
			$tag->curtag->cdata = $value;
		 }	  
	  
		 $rs->moveNext();
	  }
	
	  $xml_file = fopen($filename, "w" );
	  $this->xml->write_file_handle( $xml_file );
	}
	
	
	/**
	*	It inserts XML in table of the SGBD
	*
	*	@param		object			Connection of ADOdb Database Library	
	*	@param		string			Name of to be created file XML	
	*	@param		string			Table of BD		
	*	@access 	public
	*	@return 	void	
	*/	
	function InsertIntoDB($dbConnection, $filename, $tablename) 
	{
	
	  $xml_file = fopen($filename, "r"); 
	  $this->xml->read_file_handle($xml_file);
		  
	  $numRows = $this->xml->roottag->num_subtags();
	  
	  for ($i = 0; $i < $numRows; $i++) 
	  {
		   $arrFields = null;
		   $arrValues = null; 
	
		   $row = $this->xml->roottag->tags[$i];
		   $numFields = $row->num_subtags();
	
		   for ($ii = 0; $ii < $numFields; $ii++) 
		   {
			  $field = $row->tags[$ii];
			  $arrFields[] = "`".$field->name."`";
			  $value = $field->cdata;
			  $value = preg_replace("/;lt;/", "<", $value);
			  $value = preg_replace("/;gt;/", ">", $value);
			  $value = addslashes($value);
			  $arrValues[] = "\"".$value."\"";
		   }
	
		   $fields = join($arrFields, ", ");
		   $values = join($arrValues, ", ");
	
		   $strSQL = "INSERT INTO $tablename ($fields) VALUES ($values)";
		   $dbConnection->Execute($strSQL);
	  }
		  
	}
   
}// end class
?>
