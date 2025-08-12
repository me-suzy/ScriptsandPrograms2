<?php
require_once('error.php');
require_once('poll.php');

/*! \class Project
 *  \brief The project class contains all polls related to the project.

    The project class allows you to add, remove and retrive polls related to the project.
    It also allows you to setup permissions for what site the polls are allowed to be used on.
    When info is changed in the object the changes is not saved before the Project::save method is called
    
    if Project::isError() returns an Error object after the object was created, 
    the object is in an undefined state
*/
class Project{
	/** private var, contain the title of the project, se also Project::setTitle and Project::getTitle */
	var $title 	= null;
	
	/** private var, contains the domain that the poll is allowed to be used on, se also Project::setSite and Project::getSite */
	var $site 	= null;
	
	/** private var, contains the polls associated with this project, 
	 * se also Project::getPoll Project::getPolls Project::addPoll Project::removePoll */
	var $polls 	= array();
	
	/** private var, contains either false if no error or 
	 * an Error object if there was an error in the last method call 
	 * se also Error::Error and Project::isError */
	var $isError= false;
	
	/** private var, contains the id of the project 
	 * se also Project::getID */
	var $id		= null;
	
	/** private var, reference to a PEAR::DB object */
	var $db 	= null;
	
	/** private var, contains the username of the users that owns the project */
	var $user 	= null;
	
	/** Constructor
	 * @param $db a PEAR::DB object
	 * @param $id the id of the project, must be numeric
	 * if the id is set to null a new project will be created
	 * if the id could not be found and is not null, Project::isError will return an Error object
	 */
	function Project(&$db, $id = null){
		if($id != null && !is_numeric($id)){
			$this->isError = new Error("ID is not numeric.");
			return;
		}
		if(!is_a($db, 'DB_common')){
			$this->isError = new Error("Database object is not valid");
			return;
		}
		// initialize db, reference the global db object
		$this->db = &$db;
		
		if($id == null){
			// create the project with default info 
			$res = &$this->db->query('INSERT INTO '.DB_PREFIX.'fungl_projects(title) VALUES("FunGL Project")');
			if(PEAR::isError($res)){
				$this->isError = new Error("SQL insert failed, PEAR::DB message: ".$res->getMessage());
		    	return;
			}
			// retrive the new id
			$res = &$this->db->query('SELECT last_insert_id()');
			if(PEAR::isError($res)){
				$this->isError = new Error("Retival of new ID failed, PEAR::DB message: ".$res->getMessage());
		    	return;
			}
			list($id) = $res->fetchRow();
		}
		
		// fetch project info from the database
		$res = &$this->db->query('SELECT title, site, id, userid FROM '.DB_PREFIX.'fungl_projects WHERE id='.$id);
		
		// check if the query was ok
		if(PEAR::isError($res)) {
		    $this->isError = new Error("SQL select failed, PEAR::DB message: ".$res->getMessage());
		    return;
		}
		
		// save project data
		$row = &$res->fetchRow();
		$this->title 	= $row[0];
		$this->site 	= $row[1];
		$this->id		= $row[2];
		$this->user		= $row[3];
		
		// free the project result data
		$res->free();
		
		// fetch poll data -- select all poll ids that is owned by the project 
		$res = &$this->db->query('SELECT id FROM '.DB_PREFIX.'fungl_polls WHERE projectid='.$this->id);
		if(!PEAR::isError($res)){
			while ($row = &$res->fetchRow()) {
				$this->polls[] = new Poll($GLOBALS['db'], $row[0]); // insert new Poll object in the end of the array
				end($this->polls); // move the array pointer to last element
				$poll = &current($this->polls); // fetch last poll
				if($poll->isError()){
					// poll object created with error
					// silently discarge the poll 
					// maybe log the error -- FIXME 
					array_pop($this->polls);
				}
			}
		}
	}
	
	/** Returns the projects id
	 * @return int with the projects ID
	 */
	function getID(){
		return $this->id;
	}
	
	/** fetch the poll object with the id 
	 * if the id is not numeric the Project::isError() will return an Error::Error object explaining the error
	 * if the id is not found in the polls in the projects the Project::isError() will return an Error::Error object
	 * 
	 * @param $poolID the id of the poll to fetch
	 * @return Pool::Pool object or false, if false the Project::isError() will return an Error::Error object
	 */
	function getPoll($pollID){
		if(!is_numeric($pollID)){
			$this->isError = new Error("PoolID is not numeric");
			return false;
		}
		// we use a while loop insted of a foreach to avoid copying the poll objects
		while($poll = each($this->polls)){
			if($poll['value']->getID() == $pollID){
				reset($this->polls);
				$this->isError = false;
				return $poll['value'];
			}
		}
		$this->isError = new Error("Poll id was not found.");
		return false;
	}
	
	/** find the poll to show today
	 * if a poll have set an interval that overlaps now it overrides 
	 * polls that is shown on specific weekdays
	 * @return Poll object or false on error
	 */
	function selectPoll(){
		// first we search for polls that have a intervalt that match with now
		reset($this->polls); 
		while($poll = each($this->polls)){
			list($start, $end) = $poll['value']->getInterval();
			if($start <= time() && $end >= time()){
				return $poll['value'];
			}
		}
		// search for polls that is to be shown this day
		$day = date('w')-1;
		reset($this->polls);
		while($poll = each($this->polls)){
			$n = $poll['value']->getPeriodic();
			if($day == $n){
				return $poll['value'];
			}
		}
		$this->isError = new Error('No poll found');
		return false;
	}
	
	/** get all polls owned by the project
	 * 
	 * @return Array with all poll objects in the project
	 * \verbatim
        Will output the data ordered in this way.
        Array(
            [0] => Object Poll
            [1] => Object Poll
        )
    	\endverbatim
	 */
	function getPolls(){
		return $this->polls;
	}
	
	/** Add a poll object to the project, the add is not permanent before Project::save is called
	 * @param $pollObject a Poll::Poll object
	 * @return true on sucess, or false on error Project::isError will then 
	 * return an Error::Error object discribing the error
	 */
	function addPoll($pollObject){
		if(!is_a($pollObject, 'Poll')){
			$this->isError = new Error("The parameter is not a Poll object");
			return false;
		}
		$this->polls[] = &$pollObject;
		$this->isError = false;
		return true;
	}
	
	/** Delete the poll, remove the poll from the project and delete all info about the poll
	 * @param $pollID id of the poll to remove
	 * @return true on sucess, false on error and Project::isError() will return 
	 * an Error::Error object that describes the error
	 */
	function removePoll($pollID){
		$poll = $this->getPoll($pollID);
		if(!$poll){
			// the poll was not found and $this->isError contains the error object
			return false;
		}
		if(!$poll->delete()){
			$msg = $poll->isError();
			$this->isError = new Error("The delete failed. Poll error msg: ".$msg->getText());
			return false;
		}
		$this->isError = false;
		return true;
		// FIXME -- remove the object
	}
	
	/** Gets the site that the project is locked to.
	 * @return string with the domain ie. apple.com, google.com, bar.foo.com aso.
	 */
	function getSite(){
		return $this->site;
	}
	
	/** Sets the site that the project should be locked to
	 * @param $site string ie. apple.com foo.bar.com aso.
	 * @return true on sucess, false on error and the Project::isError() will return an Error::Error object
	 */
	function setSite($site){
		if(!is_string($site)){
			$this->isError = new Error("The supplied site is not a string");
			return false;
		}
		if($site == gethostbyname($site)){ // could cause problems, test -- FIXME
			$this->isError = new Error("The site doesn't exist");
			return false;
		}
		$this->site = $site;
		$this->isError = false;
		return true;
	}
	
	/** Gets the user that the project is owned by.
	 * @return string with the username
	 */
	function getUser(){
		return $this->user;
	}
	
	/** Sets the user that the project should be locked to
	 * @param $username string username
	 * @return true on sucess, false on error and the Project::isError() will return an Error::Error object
	 */
	function setUser($username){
		if(!is_string($username)){
			$this->isError = new Error("The supplied username is not a string");
			return false;
		}
		
		$this->user = $username;
		$this->isError = false;
		return true;
	}
	
	/** Sets the title of the project
	 * @param $title string, the title of the project
	 * @return true on sucess, false on error and the Project::isError() will return an Error::Error object
	 */
	function setTitle($title){
		if(!is_string($title)){
			$this->isError = new Error("The supplied title is not a string");
			return false;
		}
		$this->title = $title;
		$this->isError = false;
		return true;
	}
	
	/** Gets the title of the project
	 * 
	 * @return string with project title
	 */
	function getTitle(){
		return $this->title;
	}

	/** If the last method call failed this function will return an Error::Error object 
	 * otherwise it will return false,
	 * 
	 * @return false or an Error::Error object
	 */
	function isError(){
		return $this->isError;
	}
	
	/** save all changes to the project
	 * 
	 * @return false on error, true on sucess. If error Project::isError will return an Error object describing the error
	 */
	function save(){
		// save project data
		$sql = 'UPDATE '.DB_PREFIX.'fungl_projects SET '.
				'title='.$this->db->quoteSmart( $this->getTitle() ).', ' .
				'site='.$this->db->quoteSmart( $this->getSite() ).', ' .
				'userid='.$this->db->quoteSmart( $this->getUser() ).
				' WHERE id='.$this->getID();
		$res = &$this->db->query($sql);
		if(PEAR::isError($res)){
			$this->isError = new Error("SQL update failed, PEAR::DB message: ".$res->getMessage());
		   	return false;
		}
		
		// update poll projectIDs and save them
		while($poll = each($this->polls)){
			$poll['value']->setProjectID($this->id);
			if($poll['value']->isError()){
				$msg = $poll['value']->isError(); 
				$this->isError = new Error("Poll set project ID failed, poll error msg: ".$msg->getText() );
				return false;
			}
			$poll['value']->save();
			if($poll['value']->isError()){
				$msg = $poll['value']->isError(); 
				$this->isError = new Error("Poll save failed, poll error msg: ".$msg->getText() );
				return false;
			}
		}
		
		$this->isError = false;
		return true;
	}
	
	/** Deletes the project and all polls owned by the project
	 * when this method has been called you should unset() the object if the call sucesseded
	 * 
	 * @return false on error, true on sucess. If error Project::isError will return an Error object describing the error
	 */
	function delete(){
		$res = &$this->db->query('DELETE FROM '.DB_PREFIX.'fungl_projects WHERE id='.$this->getID());
		if(PEAR::isError($res)){
			$this->isError = new Error("SQL delete failed, PEAR::DB message: ".$res->getMessage()." - ".$this->getID());
		   	return false;
		}
		
		$this->isError = false;
		return true;
	}
}
?>