<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntTestNotification', 'pnt/unit/notifications');

/**
 * @package pnt/unit/notifications
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntAssertionFailure extends PntTestNotification {

	//description      in file .... on line ...     assertEquals `label`
	//message          errormessage                 toCheck was tested against ref
	var $assertionType;
	var $assertionLabel;
	var $checkedValue;
	var $referenceValue;
	var $precision;

	function PntAssertionFailure($assertionType, &$referenceValue, 
			&$checkedValue, $assertionLabel, $precision)
	{
		$this->assertionType = $assertionType;
		$this->assertionLabel = $assertionLabel;
		$this->checkedValue =& $checkedValue;
		$this->referenceValue =& $referenceValue;
		$this->precision = $precision;
	}
	
	function getJudgement()
	{
		return 'Failure';
	}

	function getDescription()
	{
		return $this->getAssertionType()
			. ' `'
			. $this->getAssertionLabel()
			. '`';
	}
	
	function getAssertionType()
	{
		return $this->assertionType;
	}
	
	function getAssertionLabel()
	{
		return $this->assertionLabel;
	}
	
	function &getCheckedValue()
	{
		return $this->checkedValue;
	}
	
	function &getReferenceValue()
	{
		return $this->referenceValue;
	}
	
	function getPrecision()
	{
		return $this->precision;
	}
	
	function defaultHandling()
	{
		global $pntTestNotifier;
		
		if ($this->getPrecision() === null) {
			$precision = '';
			$precisionAnnouncement = '';
		} else {
			$precision = pntToString($this->getPrecision());
			$precisionAnnouncement = $pntTestNotifier->precisionAnnouncement;
		}
		$message = sprintf(
			$pntTestNotifier->failureMessageFormat,
			($this->getTestCase() ? pntToString($this->getTestCase()).' ' : ''),
			$this->getAssertionType(),
			$this->getAssertionLabel(),
			pntToString($this->getCheckedValue()),
			pntToString($this->getReferenceValue()),
			$precisionAnnouncement,
			$precision);

		trigger_error($message, E_USER_WARNING);
	}
}
?>