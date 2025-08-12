<?


class log_action {

	/**
	 * data
	 */
	var $properties = array('username' => '', 'password' => '', 'action' => '', 'time' => 0, 'ipaddress' => '');

	/**
	 * database controller
	 */
	var $d, $f = 'object';

	function log_action() {
		global $DB, $celeste, $user, $thisprog;

		$this->d = &$DB;
		$this->f = &$celeste;

		if(is_object($user)) {
      $this->setProperty('username', $user->username);
      $this->setProperty('password', $user->getProperty('password') );
    }
		$this->setProperty('ipaddress', $this->f->ipaddress);
    $this->setProperty('time', $this->f->timestamp);
    $this->setProperty('action', $thisprog);
	}

	function setProperty($name, $value) {
		$this->properties[$name] = $value;
	}

	function log() {

    $query = 'INSERT INTO celeste_log SET ';
    foreach( $this->properties as $key=>$val ) {
      $query .= $key."='$val',";
    }
    
    $query =& substr( $query, 0, -1);
    $this->d->update($query);

	}
} // end of class log_action
