<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntTestNotification', 'pnt/unit/notifications');

/**
 * @package pnt/unit/notifications
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntUnitResultNotification extends PntTestNotification {

	var $children;
	var $subject;
	
	function PntUnitResultNotification(&$subject)
	{
		$this->subject = $subject;
		$this->children = array();
	}
	
	function &getSubject()
	{
		return $this->subject;
	}
	
	function &getChildren()
	{
		return $this->children;
	}
	
	function addChild(&$notification)
	{
		$this->children[] =& $notification;
	}

	
	function &countJudgements(&$notifications)
	{
		$counters = array();
		reset($notifications);
		while (list($key) = each($notifications)) {
			$counterName = $notifications[$key]->getJudgement();
			if ( isSet($counters[$counterName]) )
				$counters[$counterName]++;
			else
				$counters[$counterName] = 1;
		}
		return $counters;
	}

	function isPass()
	{
		return $this->getJudgement() == 'Pass';
	}
	
	function getJudgement()
	{
		global $pntTestNotifier;
		$map =& $pntTestNotifier->getSeveritiesByJudgement();
		$severity = 0;
		
		reset($this->children);
		while (list($key) = each($this->children)) {
			$severity = max($severity, $map[ $this->children[$key]->getJudgement() ]);
		}
		
		return array_search($severity, $map);
	}
	
}
?>