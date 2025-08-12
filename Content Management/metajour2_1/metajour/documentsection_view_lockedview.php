<?php

require_once 'basic_view.php';

class documentsection_view_lockedview extends basic_view {

	function view() {
		$obj = owRead($this->objectid[0]);
		return $obj->elements[0]['content'];
	}

}

?>
