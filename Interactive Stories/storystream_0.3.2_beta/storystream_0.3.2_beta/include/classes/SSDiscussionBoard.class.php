<?php

class SSDiscussionBoard extends SSObject
{

	// populate the given smarty object.
	function prepareSmartyVariablesForRecentPosts (&$smartyObj)
	{
	
	}
	
	// return the session id for the logged in user
	function login ($userid)
	{
		return 0;
	}
	
	// return the user id of the discussion board user
	function addUser ($username, $password, $email)
	{
		// there's no need to do this for built in discussion board, 
		//	but if we ever decide to use a third-party library, we
		//	can implement this.
		return 0;
	}
	
	function createSceneTopic ($sceneId)
	{
		// return the id of the topic created
		return 0;
	}
	
	function createStoryTopic ($storyId)
	{
		// return the id of the topic created
		return 0;
	}
	
	function getTotalUserPosts ($userId)
	{
		return 0;
	}
	
	function getTopicDiscussion ($topicId)
	{
		// returns an array of arrays containing posts in the thread organized by keyword		
	}
	
	function reply ($topicId, $subject, $body)
	{
		
	}
}
?>