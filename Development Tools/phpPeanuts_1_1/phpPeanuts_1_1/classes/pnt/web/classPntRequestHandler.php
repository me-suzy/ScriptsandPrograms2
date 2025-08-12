<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


/** Abstract superclass of all http request handlers.
* @package pnt/web
*/
class PntRequestHandler {

	var $requestData; //Array set by handleRequest
	var $information;

	function PntRequestHandler(&$whole, &$requestData)
	{
		$this->whole =& $whole;
		$this->requestData =& $requestData;
	}

	function getDir() {
		if (isSet($this->dir) && $this->dir) 
			return $this->dir;
			
		return $this->whole->getDir();
	}

	function getDomainDir() {
		return $this->whole->getDomainDir();
	}

	function getBaseUrl() {
		return $this->whole->getBaseUrl();
	}

	function &getConverter() {
		return $this->whole->getConverter();
	}

	function getDebugMode()
	{
		return $this->whole->getDebugMode();
	}

	function startSession()
	{
		$this->whole->startSession();
	}

	// make sure to include eventual additional filter classes before calling this method
	function &getGlobalFilters()
	{
		return $this->whole->getGlobalFilters();
	}
	
	function forwardRequest(&$requestData, $information=null) {
		$this->whole->forwardRequest($requestData, $information);
	}

	// This only works if NOTHING has been printed yet!!
	function redirectRequest(&$requestData, $information=null)
	{
		if (count($requestData) > 0)
			while (list($key, $value) = each($requestData))
				$params[] = "$key=". urlencode($value);
		if ($information)
			$params[] = "pntInfo=".urlencode($information);

		$url = $this->getBaseUrl();
		$url .= $this->getDir(). 'index.php?';
		if ($params)
			$url .= implode('&', $params);

		header("Location: $url"); /* Redirect browser */
		exit;
	}

	function getRequestParam($key)
	{
		return isSet($this->requestData[$key]) ?  $this->requestData[$key] : null;
	}

	function &getRequestHandler(&$requestData)
	{
		$dir = $this->getDir();
		$id = isSet($requestData["id"]) ? $requestData["id"] : null;
		$specifiedHandler = $handler = isSet($requestData["pntHandler"])
			?  $requestData["pntHandler"] : null;
		$property = ucFirst(isSet($requestData["pntProperty"]) ? $requestData["pntProperty"] : '');
		$type = isSet($requestData["pntType"]) ? $requestData["pntType"] : null;

		if ($handler=='PropertyPage')
			$handler = "Property$property".'Page';
		elseif (!$handler) {
			if ($property == 'pntList')
				$handler = 'IndexPage';
			elseif ($id !== null)
				$handler = 'EditDetailsPage';
			else
				$handler = 'IndexPage';
		}

		$attempted = array();
		$info = null;
		$handlerClass = "$type$handler";
		$included = $this->tryUseClass($handlerClass, $this->getDir());
		$attempted = array_merge($attempted, $this->getTryUseClassTryParams($handlerClass, $this->getDir()));
		if (!$included && $property) {
			//there is no specific handler for this type and property, try type-handler
				$handler = $specifiedHandler;
				$handlerClass = "$type$handler";
				$included = $this->tryUseClass($handlerClass, $this->getDir());
		$attempted = array_merge($attempted, $this->getTryUseClassTryParams($handlerClass, $this->getDir()));
		}
		if (!$included && $type) {
				//there is no specific handler for this type, try generic handler from same dir
				$handlerClass = "Object$handler";
				$included = $this->tryUseClass($handlerClass, $this->getDir());
		$attempted = array_merge($attempted, $this->getTryUseClassTryParams($handlerClass, $this->getDir()));
		}

		if (!$included) {
			$name = $this->getName();
			$errorMessage = "$name - handler not found: $handler, tried: <BR>\n";
			$errorMessage .= $this->getHandlersTriedString($attempted);
			trigger_error($errorMessage, E_USER_ERROR);
		}
//  print "<BR>Handler: $handlerClass $included";
		$result =& new $handlerClass($this, $requestData);

		if ($this->getDebugMode() == 'verbose') {
			$info = 'Handlers tried<BR>(one of last two succeeded): ';
			$info .= $this->getHandlersTriedString($attempted);
			$result->setInformation($info);
		}
		return $result;
	}

	function getHandlersTriedString($attempted)
	{
		$result = "<TABLE>\n";
		while(list($key, $params) = each($attempted))
			$result .= "<TR><TD class=pntNormal>$params[1]$params[0]</TD></TR>\n";
		$result .= "</TABLE>\n";
		return $result;
	}
	function toString()
	{
		return getOriginalClassName(get_class($this))
			.'('.$this->getLabel().')';
	}

	function getLabel()
	{
		return $this->getTypeLabel()
			." - "
			.$this->getName();
	}

	function getName() {
		return getOriginalClassName(get_class($this));
	}

	/** Warning, it is not safe to use the result from this
    * method for class inclusion without calling checkAlphaNumeric
	*/
	function getThisPntHandlerName()
	{
		$pntHandler = $this->requestData['pntHandler'];
		if ($pntHandler)
			return $pntHandler;

		return $this->getName().'Page';
	}

	function getInformation() {
		if ($this->information !== null)
			return $this->information;

		if (isSet($this->requestData['pntInfo']))
			return stripSlashes($this->requestData['pntInfo']);
		
		return null;
	}

	function setInformation ($value)
	{
		$this->information =& $value;
	}

	function getTypeLabel() {
		$type = $this->getType();

		if (is_subclassOr($type, 'PntObject')) {
			$clsDes =& PntClassDescriptor::getInstance($type);
			return $clsDes->getLabel();
		}

		return $type;
	}

	function getType() {
		if (!isSet($this->requestData['pntType'])) return null;
		$type = $this->requestData['pntType'];
		$this->checkAlphaNumeric($type);
		return $type;
	}

	/** ALLWAYS call this function before including a class
    by name from a request parameter, unless you use
	this-> useClass or this-> tryUseClass
	*/
	function checkAlphaNumeric($type)
	{
		if ($type && preg_match("'[^A-Za-z0-9_]'", $type))
			trigger_error("Non alphanumerical characters in type or handler: $type", E_USER_ERROR);
	}

	/** delegates to $this->whole so that it can be overridden on Site */
	function getTryUseClassTryParams($className, $dir)
	{
		return $this->whole->getTryUseClassTryParams($className, $dir);
	}

	/** delegates to $this->whole so that it can be overridden on Site */
	function getIncludesDir()
	{
		return $this->whole->getIncludesDir();
	}

	function tryUseClass($className, $dir) {
		$this->checkAlphaNumeric($className);
		$params =& $this->getTryUseClassTryParams($className, $dir);
		$included = tryIncludeClass($params[0][0], $params[0][1]);
//print "<BR>tryIncludeClass(".$params[0][0].", ". $params[0][1].") $included";
		if (!$included) {
			$included = tryIncludeClass($params[1][0], $params[1][1]);
//print "<BR>tryIncludeClass(".$params[1][0].", ". $params[1][1].") $included";
		}
		return $included;
	}

	function useClass($className, $dir) {
		if (!$this->tryUseClass($className, $dir)) {
			$label = $this->toString();
			$params = $this->getTryUseClassTryParams($className, $dir);
			trigger_error(
				"$label - useClass: class not found: "
					.$params[0][1]. $params[0][0].", "
					. $params[1][1]. $params[1][0]
				, E_USER_WARNING);
			return false;
		}
		return true;
	}

	function &getRequestedObject() 
	{
		if (isSet($this->object))
			return $this->object;

		$type = $this->getType();
		if (!class_exists($type)) return null;

		$clsDes =& PntClassDescriptor::getInstance($type);
		$id = isSet($this->requestData['id']) ? $this->requestData['id'] : null;
		if (empty($id)) 
			$object =& new $type();
		else {
			$object =& $clsDes->_getPeanutWithId($id);
			if (is_ofType($object, 'PntError')) {
				trigger_error($object->getLabel(), E_USER_WARNING);
				return null;
			}
		}
		$this->object =& $object;
		return $object;
	}

	function setRequestedObject(&$value)
	{
		$this->object =& $value;
	}

	/** @return Array of PntNavValue
	*/
	function &getFormTexts()
	{
		if (!isSet($this->formTexts)) {
			includeClass('PntFormNavValue', 'pnt/web/dom');
			$this->formTexts =& PntFormNavValue::getInstances(
				$this->getConverter()
				, $this->getType()
				, $this->getFormTextPaths()
			);
			while (list($key) = each($this->formTexts)) {
				$formKey = $this->formTexts[$key]->getFormKey();
				if (isSet($this->requestData[$formKey]))
					$this->formTexts[$key]->setMarkup(stripSlashes($this->requestData[$formKey]));
			}
			reset($this->formTexts);
		}
		return $this->formTexts;
	}

	/** Returns array with paths for formtexts.
	* @return Array of String
	*/
	function getFormTextPaths()
	{
		return null;
	}

	function setFormTexts(&$value)
	{
		$this->formTexts =& $value;
	}

}
?>