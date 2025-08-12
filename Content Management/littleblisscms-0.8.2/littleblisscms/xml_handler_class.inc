<?php
/*
	<one line to give the program's name and a brief idea of what it does.>
	Copyright (C) 2005  Phillip Berry (Bliss Webhosting)
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*Class handles all of the xml mangling.*/

class xml_handler{
	
	var $xml = FALSE ;
	var $root = FALSE ;
	var $dom = FALSE ;
	var $error = FALSE ;
	var $xmlfile = FALSE ;
	var $tmp_username = FALSE ;
	var $tmp_password = FALSE ;

	/*!
	* \brief Create a DOM tree either from an existing file, or from scratch if there is no file
	* \param[in] $xmlfile String path to a file
	* \param[out] $error Error variable
	* \param[out] $xmlfile Path to XML file 
	* \param[out] $dom Dom object
	* \param[out] $root Root node of dom
	* \return bool 
	*/
	function xml_handler($xmlfile){
		if(!is_readable($xmlfile)){
			$this->error = "Could not read $xmlfile" ;
			echo $this->error ;
			return FALSE ;
		}
		elseif(!is_writable($xmlfile)){
			$this->error = "Could not write to $xmlfile" ;
			echo $this->error ;
			return FALSE ;
		}
		$this->xmlfile = $xmlfile ;
		$existing_contents = file_get_contents($this->xmlfile) ;
		if(!$this->dom = @domxml_open_mem($existing_contents)){
			$this->dom = @domxml_open_mem("<site></site>") ;
		}
		//Gets the top element
		$this->root = $this->dom->document_element() ;
		return TRUE ;
	}
	
	/*!
	* \brief Updates and existing child of the given node
	* \param[in] $node DomNode Node whose child is to be updated
	* \param[in] $data String New data
	* \param[in] $node_name String Name of the child node to be updated
	* \param[out] error
	* \return bool
	*/
	function update_node_data($node,$data,$node_name){
		$child_nodes = $node->child_nodes() ;
		foreach($child_nodes as $child_node){
			if($node_name == $child_node->node_name()){
				$data_node = $child_node ;
			}
		}
		$existing_content_node = $data_node->first_child() ;
		if($existing_content_node != NULL){
			$data_node->remove_child($existing_content_node) ;
		}
		if($data_node->set_content($data)){
			return TRUE ;			
		}
		$this->error = "Could not update node $data" ;
		return FALSE ;
		
	}

	/*!
	* \brief Searches for the defined element (tag), returns the array of elements
	* \param[in] $tag name of the element to search for
	* \return array Array of DomElements
	*/
	function find_tag($tag){	
		if(!$this->test_variables()){
			return FALSE ;
		}
		$elements = $this->dom->get_elements_by_tagname($tag) ;
		return $elements ;
	}

	/*!
	* \brief Searches a defined node attribute for a defined value
	* \param[in] $node DomNode to search
	* \param[in] $attribute String attribute to search for
	* \param[in] $value String value to search for
	* \return bool
	*/
	function search_attribute_value($node,$attribute, $value){
		foreach($node->attributes() as $node_attribute){
			if($node_attribute->name() == $attribute && $node_attribute->value() == $value){
				return TRUE ;
			}
		}
		return FALSE ;
	}

	/*!
	* \brief Retrieves a nodes data and returns it in an array
	* \param[in] $node DomNode Node to retrieve data from
	* \return Array or False
	*/
	function retrieve_node_data($node){
		$node_data_array['node'] =  $node ;
		if($node->has_child_nodes()){
			foreach($node->child_nodes() as $child){
				$node_data_array[$child->node_name()] ;
				$node_data_array[$child->node_name()]['node'] = $child ;
				$node_data_array[$child->node_name()]['data']  = $child->get_content() ;
			}
			return $node_data_array ;
		}		
		return FALSE ;
	}
	
	/*!
	* \brief Extracts all data from the dom tree, returns an array
	* \return $node_array Multidimensional array
	*	- $node_array structure
	*	-$node_array[node 0 attribute][content]\n
	*	-$node_array[node 0 attribute][childnode name][childnode_content]\n
	*/
	function extract_all_data(){
		$node_array ;
		$child_nodes = $this->root->child_nodes() ;
		foreach ($child_nodes as $value) {
			$attributes = $value->attributes() ;
			if(!$value->has_child_nodes()){
				$node_array[$attributes[0]->value] = $value->get_content();
			}
			else{
				$sub_child_nodes = $value->child_nodes() ;
				foreach($sub_child_nodes as $node){
					$node_array[$attributes[0]->value][$node->node_name()] = $node->get_content();
				}
			}
		}				
		return $node_array ;
	}

	/*!
	* \brief deletes and element and all child elements
	* \param[in] $tag String Name of element to delete
	* \param[in] $search String Attribute that identifies element to be deleted
	* \return bool
	*/
	function delete_tag($tag,$search){
		$section_elements = $this->dom->get_elements_by_tagname($tag) ;
		foreach($section_elements as $node){
			foreach($node->attributes() as $attribute){
				if($attribute->value() == $search){
					$parent = $node->parent_node() ;
					if($parent->remove_child($node)){
						return TRUE ;
					}
				}
			}
		}
		return FALSE ;
	}
	
	/*!
	* \brief Function writes the xml defined in this->xml to the file defined in this->xmlfile
	* \return bool
	*/
	function write_xml_file(){
		if(!$this->xml){
			$this->error = "No data available to write" ;
			return FALSE ;
		}
			
		$fp = fopen($this->xmlfile,"w") or $error = "Could not open XML file";
		fputs($fp,$this->xml) or $error = "Could not write XML file";

		if($error){
			$this->error = $error ;
			return FALSE ;
		}
		else{
			return TRUE ;
		}
	}

	/*!
	* \brief Function converts content into hex before being inserted into the file, this saves headaches with escaping characters
	* \param[in] $temp String Ascii string to convert to hex
	* \return String Hex output
	*/
	function asc2hex ($temp) {
		$data ;
    		$len = strlen($temp);
    		for ($i=0; $i<$len; $i++){
			$data.=sprintf("%02x",ord(substr($temp,$i,1)));
		}
    		return $data;
 	}
 
	/*!
	* \brief Functions converts hex data back to ascii
	* \param[in] $temp String Hex data to be converted
	* \return $data Ascii Output
	*/
 	function hex2asc($temp) {		
		$data ;
    		$len = strlen($temp);
    		for ($i=0;$i<$len;$i+=2){
			$data.=chr(hexdec(substr($temp,$i,2)));
		}		
    		return $data ;
 	}

	/*!
	* \brief Function tests the dom tree and root element
	* \return bool
	*/
	function test_variables(){
		if(!$this->dom){
			$this->error = "Could not get dom" ;
			return FALSE ;
		}
		if(!$this->root){
			$this->error = "No root tag available" ;
			return FALSE ;
		}
		return TRUE ;
	}
}

?>
