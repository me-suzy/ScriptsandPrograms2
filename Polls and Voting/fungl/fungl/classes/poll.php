<?php
require_once('question.php');
require_once('error.php');

/*! \class Poll
 *  \brief Manages a single poll

    This class allows the user to add/remove/fetch questions from the poll, 
    default chart type is pie
    When info is changed in the object the changes is not saved before the Poll::save method is called
*/
class Poll 
{
	/** private var, contain the question object owned by the poll */
	var $questions 	= array();
	
	/** private var, contains the chart object */
	var $chart 		= null;
	
	/** private var, contains the chart type we use to construct the chart object*/
	var $chartType 	= "Pie"; 
	
	/** private var, contains the text used on the vote button on the poll */
	var $voteText	= null;
	
	/** private var, contains the title of the poll */
	var $title		= null;
	
	/** private var, contains the id of the poll */
	var $id			= null;
	
	/** private var, contains the id of the project this poll is owned by */
	var $projectID 	= null;
	
	/** private var, contains either false if no error or 
	 * an Error object if there was an error in the last method call 
	 * se also Error::Error and Poll::isError */
	var $isError 	= false;
	
	/** Constructor
	 * @param $db a reference to a PEAR::DB object
	 * @param $id the id of the poll, must be numeric
	 * @param $projectID the if of the project to associate this poll 
	 * if the id is set to null a new poll will be created
	 * if the id could not be found and is not null, Poll::isError will return an Error object
	 */
	function Poll(&$db, $id = null, $projectID = null){
		if($id != null && !is_numeric($id)){
			$this->isError = new Error("ID is not numeric.");
			return;
		}
		if($projectID != null && !is_numeric($projectID)){
			$this->isError = new Error("Project ID is not numeric.");
			return;
		}
		if(!is_a($db, 'DB_common')){
			$this->isError = new Error("Database object is not valid");
			return;
		}
		
		// initialize db, reference the global db object
		$this->db = &$db;
		
		if($id == null){
			// create the poll with default info 
			$res = &$this->db->query('INSERT INTO '.DB_PREFIX.'fungl_polls(charttype, votetext, title, projectid) ' .
					'VALUES("Pie", "Vote", "FunGL poll", '.$projectID.')');
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
		$sql = 'SELECT title, charttype, votetext, id, projectid FROM '.DB_PREFIX.'fungl_polls WHERE id='.$id;
		$res = &$this->db->query($sql);

		// check if the query was ok
		if(PEAR::isError($res)) {
		    $this->isError = new Error("SQL select failed, PEAR::DB message: ".$res->getMessage());
		    return;
		}
		// did we get any data
		$row = &$res->fetchRow();
		
		if(empty($row)){
			$this->isError = new Error("No poll with the selected ID");
		    return;
		} 
		// save project data
		$this->title 	= $row[0];
		$this->chartType= $row[1];
		$this->voteText	= $row[2];
		$this->id		= $row[3];
		$this->projectID= $row[4];
		
		// free the project result data
		$res->free();

		// fetch question data -- select all question ids that is owned by the poll 
		$res = &$this->db->query('SELECT id FROM '.DB_PREFIX.'fungl_questions WHERE pollid='.$this->id);
		if(!PEAR::isError($res)){
			while ($row = &$res->fetchRow()) {
				$this->questions[] = new Question($GLOBALS['db'], $row[0]); // insert new Poll object in the end of the array
				end($this->questions); // move the array pointer to last element
				$question = &current($this->questions); // fetch last poll
				if($question->isError()){
					// question object created with error
					// silently discarge the question 
					// maybe log the error -- FIXME 
					array_pop($this->questions);
				}
			}
		}
	}
	
	/** Select interval that the poll should be shown
	 * @param $startTime unix timestamp start time
	 * @param $stopTime unix timestamp stop time 
	 */
	function selectInterval($startTime, $stopTime){
		if(!is_numeric($startTime) && !ereg('[0-9]{0,32}', $startTime)){
			$this->isError = new Error("Start time is not valid");
			return false;
		}
		if(!is_numeric($stopTime) && !ereg('[0-9]{0,32}', $stopTime)){
			$this->isError = new Error("Stop time is not valid");
			return false;
		}
		
		// reset weekday time if set
		$sql = 'UPDATE '.DB_PREFIX.'fungl_polls SET weekday=null WHERE id='.$this->id;
		$res = &$this->db->query($sql);
		
		//save time
		$sql = 'UPDATE '.DB_PREFIX.'fungl_polls SET starttime='.$startTime.', endtime='.$stopTime.' WHERE id='.$this->id;
		$res = &$this->db->query($sql);
		
		return true;
	}
	
	/** Selecte the day that the poll should be shown 
	 * monday = 0... sunday = 6
	 * @param $day numeric weekday
	 * 
	 */
	function selectPeriodic($day){
		if(!ereg('[0-6]', $day)){
			$this->isError = new Error("Day is not valid");
			return false;
		}
		
		// save day
		// reset interval time if set
		$sql = 'UPDATE '.DB_PREFIX.'fungl_polls SET starttime=null, endtime=null WHERE id='.$this->id;
		$res = &$this->db->query($sql);
		
		//save time
		$sql = 'UPDATE '.DB_PREFIX.'fungl_polls SET weekday='.$day.' WHERE id='.$this->id;
		$res = &$this->db->query($sql);
		
		return true;
	}
	
	function getInterval(){
		$sql = 'SELECT starttime, endtime FROM '.DB_PREFIX.'fungl_polls WHERE id='.$this->id;
		$res = &$this->db->query($sql);
		
		$row = &$res->fetchRow();
		
		return $row;
	}
	
	function getPeriodic(){
		$sql = 'SELECT weekday FROM '.DB_PREFIX.'fungl_polls WHERE id='.$this->id;
		$res = &$this->db->query($sql);
		
		if(PEAR::isError($res)) {
		    $this->isError = new Error("SQL select failed, PEAR::DB message: ".$res->getMessage());
		    return;
		}
		
		$row = &$res->fetchRow();
		
		return $row[0];
	}
	
	/** Add one to the question with the supplied id
	 * @param $questionID the id of the question, must be numeric
	 */
	function vote($questionID){
		if(!is_numeric($questionID)){
			$this->isError = new Error("ID is not numeric");
			return false;
		}
		$question = $this->getQuestion($questionID);
		if(!$question){
			// error object already set
			return false;
		}
		$question->addVote();
		
		$this->isError = false;
		return true;
	}
	
	/** add a question to the poll, add is not made permanent before the Poll::save is called
	 * @param $questionObject a Question object to add to the poll
	 * @return true on sucess, false on failure, if the call failed Poll::isError() will return an Error object
	 */
	function addQuestion($questionObject){
		if(!is_a($questionObject, 'Question')){
			$this->isError = new Error('Not a question object');
			return false;
		}
		$this->questions[] = $questionObject;
		
		return true;
	}
	
	/** Delete an question from the poll, this deletes all info of the question.
	 * the question must belong to the poll to be deleted
	 * @param $questionID id of the question to remove, must belong to the poll
	 * @return true on sucess, false on failure, if the call failed Poll::isError() will return an Error object
	 */
	function removeQuestion($questionID){
		$question = $this->getQuestion($questionID);
		if(!$poll){
			// the question was not found and $this->isError contains the error object
			return false;
		}
		if(!$question->delete()){
			$msg = $question->isError();
			$this->isError = new Error("The delete failed. Question error msg: ".$msg->getText());
			return false;
		}
		$this->isError = false;
		return true;
		
		// FIXME -- remove the object
	}
	
	/** Return the Question object with the supplied ID if the Question is owned by the poll
	 * @param $questionID id of the question to fetch
	 * @return true on sucess, false on failure, if the call failed Poll::isError() will return an Error object
	 */
	function getQuestion($questionID){
		if(!is_numeric($questionID)){
			$this->isError = new Error("Question ID is not numeric");
			return false;
		}
		// we use a while loop insted of a foreach to avoid copying the objects
		while($question = each($this->questions)){
			if($question['value']->getID() == $questionID){
				reset($this->questions);
				$this->isError = false;
				return $question['value'];
			}
		}
		$this->isError = new Error("Question ID was not found.");
		return false;
	}
	
	/** Return an array of all Question objects owned by the poll
	 * @return array of Question objects in the poll
	 * \verbatim
        Will output the data ordered in this way.
        Array(
            [0] => Object Question
            [1] => Object Question
        )
    	\endverbatim
	 */
	function getQuestions(){
		return $this->questions;
	}
	
	/** Return the title of the poll
	 * @return the title of the poll
	 */
	function getTitle(){
		return $this->title;
	}
	
	/** Set the title of the poll, will not be made permanent before the Poll::save method is called
	 * @param $title the title of the poll
	 * @return true on sucess, false on failure, if the call failed Poll::isError() will return an Error object
	 */
	function setTitle($title){
		$this->title = $title;
		return true;
	}
	
	/** Get the vote buttons text
	 * @return the vote buttons text
	 */
	function getVoteText(){
		return $this->voteText;
	}
	
	/** Set the text of the vote button shown to the users of the poll, will not be made permanent before the Poll::save method is called
	 * @param $text the vote buttons text
	 * @return true on sucess, false on failure, if the call failed Poll::isError() will return an Error object
	 */
	function setVoteText($text){
		$this->voteText = $text;
		return true;
	}
	
	/** Get the if of the poll
	 * @return the id of the poll
	 */
	function getID(){
		return $this->id;
	}
	
	/** Get the Chart object
	 * @return the Chart object owned by the poll
	 */
	function getChart(){
		return $this->chart;
	}
	
	/** Get the chart type, ie. Pie, Colum, Row aso.
	 * @return string with the chart type 
	 */
	function getChartType(){
		return $this->chartType;
	}
	
	/** Change the charttype, the chart object in the poll will change but the 
	 * change will not be made permanent until Poll::save is called
	 * @param $type string with new chart type, eg. Pie, Colum, Row
	 * @return true on sucess, false on error, if error Poll::isError will return an Error object
	 */
	function setChartType($type){
		// FIXME
		$this->chartType = $type;
		return true;
	}
	
	/** sets the projectID of a poll, the poll project id will not be saved before Poll::save is called.
	 * if a new Project object is created with the same id as the one set with this call but before Poll::save is called 
	 * the poll will be included in the Project object
	 * 
	 * @param $id the id of the project to associate the poll
	 * @return true if a project with the id exists, false otherwise, if false Poll::isError will return an Error object
	 */
	function setProjectID($id){
		$this->projectID = $id;
		return true;
	}
	
	function getProjectID(){
		return $this->projectID;
	}
	
	/** If the last method call failed this function will return an Error::Error object 
	 * otherwise it will return false,
	 * 
	 * @return false or an Error::Error object
	 */
	function isError(){
		return $this->isError;
	}
	
	/** save the changes to the poll  
	 * @return false on error, true on sucess. If error Project::isError will return an Error object describing the error
	 */
	function save(){
		// save project data
		$sql = 'UPDATE '.DB_PREFIX.'fungl_polls SET title='.$this->db->quoteSmart( $this->getTitle() ).', ' .
				'charttype='.$this->db->quoteSmart( $this->getChartType() ).', ' .
				'votetext='.$this->db->quoteSmart( $this->getVoteText() ).', ' .
				'projectid='.$this->getProjectID().' ' .
				' WHERE id='.$this->getID();
		$res = &$this->db->query($sql);
		if(PEAR::isError($res)){
			$this->isError = new Error("SQL update failed, PEAR::DB message: ".$res->getMessage());
		   	return false;
		}
		
		// update questions poll IDs and save them
		while($question = each($this->questions)){
			$question['value']->setPollID($this->id);
			if($question['value']->isError()){
				$msg = $question['value']->isError(); 
				$this->isError = new Error("Question set poll ID failed, question error msg: ".$msg->getText() );
				return false;
			}
			$question['value']->save();
			if($question['value']->isError()){
				$msg = $question['value']->isError(); 
				$this->isError = new Error("Question save failed, question error msg: ".$msg->getText() );
				return false;
			}
		}
		
		$this->isError = false;
		return true;
	}
	
	/** Deletes the poll and all question owned by the poll
	 * when this method has been called you should unset() the object if the call sucesseded
	 * 
	 * @return false on error, true on sucess. If error Project::isError will return an Error object describing the error
	 */
	function delete(){
		$res = &$this->db->query('DELETE FROM '.DB_PREFIX.'fungl_polls WHERE id='.$this->getID());
		if(PEAR::isError($res)){
			$this->isError = new Error("SQL delete failed, PEAR::DB message: ".$res->getMessage());
		   	return false;
		}
		
		$this->isError = false;
		return true;
	}
}
?>