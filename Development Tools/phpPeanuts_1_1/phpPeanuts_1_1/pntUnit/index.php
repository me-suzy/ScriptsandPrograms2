<?php 
// Copyright (c) the partners of MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

	require_once('../classes/pnt/unit/web/classPntUnitPage.php');

	// if you need to override you can instantiate your own subclasses here:
	$model = new PntUnitPage();
	$pntTestNotifier = new PntTestNotifier();

	//specific for testing with phpPeanuts 
	require_once('../classes/classSite.php');
	$site = new Site('pntUnit');
	$site->startSession();
	$site->initDatabaseConnection();
	$filter =& $model->getFileFilter();
	$filter->setIncludePattern('test.*php');
	
	//specific for examples	
//	$model->setDir('../classes/test/examples');

	//protection against evil file inclusion
	$protFilter =& new PntPregFilterExpresson();
	$protFilter->setIncludePattern('test.*php');
	$protFilter->setExcludePatterns(array('.bak', '.bk'));
	$model->protectionFilter =& $protFilter;

	// you can set your own rootDir like this:
	// $model->setRootDir('../classes/pnt/test');

	//this should be AFTER making specific settings:
	$model->handleRequest();
?>