<?php
/*! \class Error
 *  \brief Error object, is returned by all object on error

    When there is an error in a method call the isError function in the objects 
    will return an Error object describing the error.
*/
class Error{
	/** Private var, Holds the error text */
	var $text = "Unknown error";
	
	/** constructor, takes the error msg as parameter */
	function Error($text){
		$this->text = $text;
	}
	
	/** returns the error text 
	 * @return string, the error text
	 */
	function getText(){
		return $this->text;
	}
}
?>