<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */


class celeste_expr_parser {

	/**
	 * expression
	 */
	var $expr = '';

	/**
	 * length of expression
	 */
	var $leng = 0;

	/**
	 * parser pointer
	 */
	var $offset = 0;

	/**
	 * sub programs
	 */
	var $sub = array();


	function celeste_expr_parser($expr) {
		$this->expr = $expr;
		$this->leng = strlen($expr);
		
		$this->_filtSpace();
	}

	function sub_parser() {

		while( $this->offset < $this->leng ) {
			if(substr($this->expr, $this->offset, 4) != 'sub')) {
				/**
				 * end of all sub programs
				 */
				break;
			}

			

			$this->_filtSpace();
		}

	} // end of function 'sub_parser'

	function _filtSpace() {
		while( $this->expr[ ++$this->offset ]!=" " && $this->expr[$this->offset]!="\t"
			&& $this->expr[$this->offset]!="\n"    && $this->expr[$this->offset]!="\r" );
	}

	function _readWord() {
		while( 49 < ord($this->expr[ ++$this->offset ]) < xx );
	}

} // end of class 'celeste_expr_parser'

?>