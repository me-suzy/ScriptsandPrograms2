<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntUnitErrorHandlingEvent', 'pnt/unit/notifications');

/**
 * @package pnt/unit/notifications
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntUnitErrorEvent extends PntUnitErrorHandlingEvent {

	function getJudgement()
	{
		return 'Error';
	}

}
?>