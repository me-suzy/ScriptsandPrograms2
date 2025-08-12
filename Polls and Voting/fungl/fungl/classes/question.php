<?php
require_once('error.php');
/*! \class Question
 *  \brief Manages a single question in a poll

    
*/
class Question{

	/** private var, contains the question text */
	var $text		= null;
	
	/** private var, contains the amount of votes this question has got */
	var $votes 		= null;
	
	/** private var, contains the id of the question */
	var $id			= null;
	
	/** private var, contains the error state of the object */
	var $isError 	= false;
	
	/** Constructor
	 * @param $db a reference to a PEAR::DB object
	 * @param $id the if of the question
	 * @param $pollID the id of the poll to associate this question
	 * 
	 * If the construction fails Question::isError() will return an Error object.
	 */
	function Question(&$db, $id = null, $pollID = null){
		if($id != null && !is_numeric($id)){
			$this->isError = new Error("ID is not numeric.");
			return;
		}
		if($pollID != null && !is_numeric($pollID)){
			$this->isError = new Error("Poll ID is not numeric.");
			return;
		}
		if(!is_a($db, 'DB_common')){
			$this->isError = new Error("Database object is not valid");
			return;
		}
		
		// initialize db, reference the db object
		$this->db = &$db;
		
		if($id == null){
			// create the question with default info 
			$res = &$this->db->query('INSERT INTO '.DB_PREFIX.'fungl_questions(question, votes, pollid) ' .
					'VALUES("Question", 0, '.$pollID.')');
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
		$res = &$this->db->query('SELECT question, votes, pollid, id FROM '.DB_PREFIX.'fungl_questions WHERE id='.$id);
		
		// check if the query was ok
		if(PEAR::isError($res)) {
		    $this->isError = new Error("SQL select failed, PEAR::DB message: ".$res->getMessage());
		    return;
		}
		
		// save project data
		$row = &$res->fetchRow();
		$this->text 	= $row[0];
		$this->votes	= $row[1];
		$this->pollID	= $row[2];
		$this->id		= $row[3];
		
		// free the project result data
		$res->free();
	}
	
	/** get the questions ID
	 * @return question ID, int
	 */
	function getID(){
		return $this->id;
	}
	
	/** get the questions text 
	 * @return question text, string
	 */
	function getText(){
		return $this->text;
	}
	
	/** set the questions text
	 * @param $text the questions text, string
	 */
	function setText($text){
		
		$this->text = $text;
		return true;
	}
	
	/** add 1 to the vote amount
	 * @return true on sucess, false on error, on error Question::isError() will 
	 * return an Error object
	 */
	function addVote(){
		$this->votes++;
		return true;
	}
	
	/** Get the amount of votes
	 * @return amount of votes, int
	 */
	function getVotes(){
		return $this->votes;
	}
	
	/** Save all changes to the question
	 * @return true on sucess, false on error, on error Question::isError will 
	 * return an Error object
	 */
	function save(){
		// save project data
		$sql = 'UPDATE '.DB_PREFIX.'fungl_questions SET ' .
				'question='.$this->db->quoteSmart( $this->getText() ).', ' .
				'votes='.$this->db->quoteSmart( $this->getVotes() ).', ' .
				'pollid='.$this->db->quoteSmart( $this->getPollID() ).
				' WHERE id='.$this->getID();
		$res = &$this->db->query($sql);
		if(PEAR::isError($res)){
			$this->isError = new Error("SQL update failed, PEAR::DB message: ".$res->getMessage());
		   	return false;
		}
		$this->isError = false;
		return true;
	}
	
	/** delete the question, after this is called you should call unset() on the object
	 * @return true on success, false on error, on error Question::isError() will return an Error object
	 */
	function delete(){
		$res = &$this->db->query('DELETE FROM '.DB_PREFIX.'fungl_questions WHERE id='.$this->getID());
		if(PEAR::isError($res)){
			$this->isError = new Error("SQL delete failed, PEAR::DB message: ".$res->getMessage());
		   	return false;
		}
		
		$this->isError = false;
		return true;
	}
	
	/** set the id of the poll that this question is owned by
	 * @param $id the id of a poll
	 * @return true
	 */
	function setPollID($id){
		$this->pollID = $id;
		return true;
	}
	
	/** return an Error object if last method call failed else it returns false
	 * @return false or an Error object
	 */
	function isError(){
		return $this->isError;
	}
	
	/** returns the poll id that this question is owned by
	 * @return poll id, int
	 */
	function getPollID(){
		return $this->pollID;
	}
}
?>