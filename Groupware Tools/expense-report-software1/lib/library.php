<?php
/*


	$Id: library.php,v 0.0.1 04/03/2003 17:38:21 kv9 Exp $
	abstract library class
*/

/**
* abstract library class
*
* @library	Library
* @author	kv9 [Dan Rusanu <mars@sercom.ro>]
* @since	
*/
class CLibrary {
	/**
	* unique library identifier
	*
	* @var string
	*
	* @access private
	*/
	var $name;

	/**
	* constructor which sets the lib`s name
	*
	* @param string $name	unique library identifier
	*
	* @return void
	*
	* @acces public
	*/
	function CLibrary($name) {
		$this->name = $name;
	}
}
?>