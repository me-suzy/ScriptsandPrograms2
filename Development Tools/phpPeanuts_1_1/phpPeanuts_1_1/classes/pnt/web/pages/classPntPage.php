<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntRequestHandler', 'pnt/web');

/** Abstract superclass of Page classes. 
* @see http://www.phppeanuts.org/site/index_php/Menu/241
* @package pnt/web/pages
*/
class PntPage extends PntRequestHandler {

	var $infoStyle;
	var $converters;
	var $parts;
	var $filterPartString;

	function PntPage(&$whole, &$requestData)
	{
		$this->PntRequestHandler($whole, $requestData);
	}

	//static return the information style for when a transaction has succeeded
	function getInfoStyleOk()
	{
		return 'pntInfoOk';
	}
	
	// static return the information style for when a transaction erorred
	function getInfoStyleError()
	{
		return 'pntInfoError';
	}

	function getInfoStyle() 
	{
		if ($this->infoStyle)
			return $this->infoStyle;
			
		if (subStr($this->getInformation(), 0, 2) == 'OK')
			return $this->getInfoStyleOk();
			
		return 'pntInfo';
	}
	
	function setInfoStyle($value)
	{
		$this->infoStyle = $value;
	}
		
	function printHeader() {
			//output starts here
			$this->includeSkin('Header');
	}
	
	function printFooter() {
			$this->includeSkin('Footer');
	}

	function printBodyTagIeExtraPiece()
	{
		if (getBrowser()!="Netscape 4.7") 
			print 'scroll=no onResize="scaleContent()"  ONKEYDOWN="metKD(event);" ONKEYPRESS="metKP(event);"';
		
	}

	function includeSkin($name) {
		$dir = $this->getDir();
		$filePath = "../$dir"."skin$name.php";
// print "<BR>includeSkin($name) $dir";
		if (file_exists($filePath)) {
			$included = include($filePath);
			if (!$included) 
				print "\n<BR>last warning was from includeSkin in: ". $this->getName();
		} else {
			$includesDir = $this->getIncludesDir();
			$included = include("../$includesDir/skin$name.php");
			if (!$included) 
				print "\n<BR>". $this->getName(). ' includeSkin could not include just before failure: '. $filePath;
		}
		return $included;
	}

	/** Legacy support, only with CMS beheer.css */
	function printSetTitle($title=null) {
		if ($title===null)
			$title = $this->getLabel();
	?>
		<script>
		document.title='<?php print str_replace("<BR>","",$title); ?>';
		getElement('titel').innerHTML='<?php print $title; ?>';		
		</script>
		
	<?php
			
	}

	function &getButton($caption, $script, $ghost=false, $len=null) 
	{
		return $this->getPart(
			array('ButtonPart'
				, $caption
				, $script
				, $ghost
				, $len
			)
			, false //no cache
		);
	}

	function &getTypeClassDescriptor()
	{
		$type = $this->getType();
		$usable = $this->useClass($type, $this->getDomainDir());
		
		return PntClassDescriptor::getInstance($type);
	}		

	function handleRequest() {
		$this->initForHandleRequest();

		$this->printHeader();
		$this->printBody();
		$this->printFooter();
	}
	
	function initForHandleRequest() {
		// may be overridden by subclass
		$this->useClass($this->getType(), $this->getDomainDir());
		
		//session is needed for filters which may be used by the requestedObject
		$this->startSession();
	}  

	function printBody() {
		$this->includeSkin('Body');
	}

	function printMainPart() {
		$this->printPart($this->getName().'Part');
	}

	function printPart($partName) {
		$debug = $this->getDebugMode();
		if (!$debug) 
			return $this->imp_printPart(func_get_args());

		$this->printPartDebugComment($partName, $debug);
		$this->imp_printPart(func_get_args());
		print "\n<!-- /$partName -->\n";
	}
	
	function imp_printPart(&$args)
	{
		$partName = $args[0];
		$methodName = "print$partName";
		if (method_exists($this, $methodName))
			return $this->$methodName();
		
		$part =& $this->getPart($args);
		if ($part)
			return $part->printBody($args);
	
		$dir = $this->getDir();
		$filePath = "../$dir"."skin".$this->getSpecificPartPrefix()."$partName.php";
// print "<BR>tryinclude $filePath";
		if (file_exists($filePath)) {
			return include($filePath);
		}
		if ($this->includeSkin($partName))
			return;
		
		// report all includes that went wrong
		$params = $this->getPartIncludeTryParams($partName);
		print "\n<BR>". $this->getName(). " could not include first:\n<BR>";
		while (list($key, $paramSet) = each($params)) 
			print "$paramSet[0], $paramSet[1],\n<BR>";
		print $filePath;
	}

	function &getPart($args, $cache=true) 
	{
		$partName = $args[0];
		// try cache first
		if ($cache && isSet($this->parts[$partName]))
			return $this->parts[$partName];

		$className = $this->getSpecificPartPrefix().$partName;
		$included = $this->tryUseClass($className, $this->getDir());

		if (!$included) {
			$className = $partName;
			$included = $this->tryUseClass($className, $this->getDir());
		}
		if ($included) {
			$str = '';
			for ($i=1; $i<count($args); $i++) {			
				$str .= ", \$args[$i]";					
			}
			$part = eval("return new $className(\$this, \$this->requestData$str);");
			if ($cache)
				$this->parts[$partName] =& $part;
			return $part;
		} else {
			return null; 
		}
	}
	
	//returns an Array of arrays with class name and path relative to ../classes
	function &getPartIncludeTryParams($partName)
	{
		$paths = array();
		$paths =& array_merge(
			$paths 
			, $this->getTryUseClassTryParams($this->getSpecificPartPrefix().$partName, $this->getDir())
			, $this->getTryUseClassTryParams($partName, $this->getDir())
		);
		return $paths;
	}	

	function getSpecificPartPrefix() {
		return $this->getType();
	}
	
	function printPartDebugComment($partName, $debug)
	{
		$thisString = $this->toString();
		print "\n<!-- $partName in $thisString";
		if ($debug == 'verbose') {
			print "\n";
			print "options: (first * = succeeded)\n";
			$bullet = method_exists($this, "print$partName") ? '*' : '-';
			print $bullet.' $this->print'."$partName();\n";
			$params = $this->getPartIncludeTryParams($partName);
			$info = '';
			while (list($key, $paramSet) = each($params)) {
				$bullet = file_exists("../classes/$paramSet[1]class$paramSet[0].php")
					? '*' : '-';
				$info .= "$bullet tryIncludeClass('$paramSet[0]', '$paramSet[1]');\n";
			}
			print $info;
			$dir = $this->getDir();
			$fileName = "../$dir"."skin".$this->getSpecificPartPrefix()."$partName.php";
			$bullet = file_exists($fileName) ? '*' : '-';
			print "$bullet tryInclude('$fileName');\n";
			$fileName = "../$dir"."skin$partName.php";
			$bullet = file_exists($fileName) ? '*' : '-';
			print "$bullet tryInclude('$fileName');\n";
			$fileName = "../includes/skin$partName.php";
			$bullet = file_exists($fileName) ? '*' : '-';
			print "$bullet include('$fileName');\n";
		}
			
		print "-->\n";
	}
	
	function printInformationPart() {
		print $this->getInformation();
	}
	
	function printFilterPart()
	{
		print $this->getFilterPartString();
	}
	
	function getFilterPartString()
	{
		if ($this->filterPartString)
			return $this->filterPartString;

		includeClass('PntSqlFilter', 'pnt/db/query');
		global $site;
		$filters = $site->getGlobalFilters();
		reset($filters);
		while (list($key) = each($filters)) {
			$description = $filters[$key]->getDescription($this->getConverter());
			$this->filterPartString .= "<TR><TD colspan = 3 class=pntFilter>$description</TD></TR>\n";
		}

		return $this->filterPartString;
	}
	
	function getButtonsList() {
		return array();
	}

	function addMultiValuePropertyButtons(&$buttons)
	{
		$excludedPropKeys =& $this->getExcludedMultiValuePropButtonKeys();
		$obj =& $this->getRequestedObject();
		$ghost = !$obj || $obj->get('id') == 0;
		
		$clsDes =& PntClassDescriptor::getInstance($this->getType());
		$multiProps =& $clsDes->getMultiValuePropertyDescriptors();
		while (list($key) = each($multiProps))
				if (!isSet($excludedPropKeys[$key]) )
					$buttons[]=$this->getButton(
						ucfirst($multiProps[$key]->getLabel()),
						$this->getMultiValuePropertyButtonScript($key),
						$ghost
					);
	}
	
	/** Return an array with as keys the names of the multi value properties 
    * for which no buttons should be added by addMultiValuePropertyButtons
	* to be overridden by subclasses
	*/
	function getExcludedMultiValuePropButtonKeys()
	{
		return array();
	}

	function getMultiValuePropertyButtonScript($propName)
	{
		$type = $this->getType();
		$id = $this->getRequestParam('id');
		return "document.location.href='index.php?pntHandler=PropertyPage&pntProperty=$propName&pntType=$type&id=$id';";
	}

	function getThisPntContext()
	{
		return '';
	}
	
	function getDetailsHref($dir, $pntType)
	{			
		return "../$dir"."index.php?pntType=$pntType&id=";
	}
	
	/** HACK: should do ui dir lookup on topRequestHandler using the type as key
	* this may be the default if no dir is specified for the type
	*/
	function getLinkDirFromNav(&$nav)
	{
 		$navDir = $nav->getResultClassDir();
 		$navDir = ($navDir && substr($navDir, -1) != '/')
			? $navDir.'/'
			: $navDir;
		return ($this->getDomainDir() == $navDir) 
			? $this->getDir() 
			: $navDir;
	}

	function getNoItemsMessage()
	{
		return "<font class=pntNormal>No Items</font><BR>\n";
	}
	
	function getRequestDuration()
	{
		global $site;
		
		$timeArr = explode(" ",microtime());
		$endTime = $timeArr[1].substr($timeArr[0],1,3);
		return round ($endTime-$site->requestStartTime,2);
		
	}
	
	function getConvert(&$obj, $propName)
	{
		if (!$obj) return '';
		$prop =& $obj->getPropertyDescriptor($propName);
		$value = $prop->_getValueFor($obj);		
		if (is_ofType($value, 'PntError')) {
			trigger_error($value->getLabel(), E_USER_WARNING);
			return null;
		}
		$conv =& $this->getInitConverter($prop);
		return $conv->toHtml($conv->toLabel($value, $prop->getType()));
	}
	
	function &getInitConverter(&$prop)
	{
		$conv =& $this->converters[$prop->getLabel()];
		if ($conv) 
			return $conv;
		
		$conv =& $this->getConverter();
		$conv->initFromProp($prop);
		
		$this->converters[$prop->getLabel()] =& $conv;
		return $conv;
	}


}
?>