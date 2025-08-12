<?php

/** @brief Use this to quickly generate objects of any type and retrieve their properties from the database
	@param integer $type The type of object to generate (OBJECT_TYPE_xxx)
	@param integer $id The ID of the object to generate or 0 to return an empty object
	@return SSObject The object that was generated
 */
function generateObject ($type, $id=0) {

	$object = NULL;
	
	switch ($type) {
		case OBJECT_TYPE_STORY:
			$object = new SSStory;
			break;
		case OBJECT_TYPE_SCENE:
			$object = new SSScene;
			break;
		case OBJECT_TYPE_FORK:
			$object = new SSFork;
			break;
		case OBJECT_TYPE_CLASSIFICATION:
			$object = new SSClassification;
			break;
		case OBJECT_TYPE_RATING:
			$object = new SSRating;
			break;
		case OBJECT_TYPE_BOOKMARK:
			$object = new SSBookmark;
			break;
		case OBJECT_TYPE_GROUP:
			$object = new SSGroup;
			break;
		case OBJECT_TYPE_STREAM:
			$object = NULL;
			break;
		default:
			die ('CRITICAL ERROR: generateObject ($type is '.$type.' and $id is '.$id.')<br>'.errors_backtrace());
			$object = NULL;
			break;
	}
	
	if ($id > 0 && $object) {
		$object->set ('id', $id);
		if (!$object->load ()) {            
			$GLOBALS['APP']->addInternalError (ERROR_OBJECT_NOT_FOUND);
			return false;
		}
	}
	
	return $object;
}

/** Converts all the strings in the given array to lowercase
 *	@return array The given array with all string reduced to lower case.
 */
function array_to_lower ($array) {

	$final = array ();
	foreach ($array as $item) {
		if (is_string ($item)) {
			$final[] = strtolower ($item);
		}
	}
	
	return $final;
}

/** Displays a trace in a friendly format 
 *	@return string The HTML for the backtrace.
 */
function errors_backtrace ()
{
	$s = '';
	if (PHPVERSION() >= 4.3) 
	{
		$MAXSTRLEN = 64;
		
		$s = '<pre align=left>';
		$traceArr = debug_backtrace();
		
		array_shift($traceArr);
		$tabs = sizeof($traceArr)-1;
		
		foreach ($traceArr as $arr) 
		{
			for ($i=0; $i < $tabs; $i++) 
				$s .= ' &nbsp; ';
				
			$tabs -= 1;
			$s .= '<font face="Courier New,Courier">';
			
			if (isset($arr['class'])) 			
			{
				$s .= $arr['class'].'.';
			}
				
			if (isset ($arr['args']))
			{
				foreach($arr['args'] as $v) 
				{
					if (is_null($v)) $args[] = 'null';
					else if (is_array($v)) $args[] = 'Array['.sizeof($v).']';
					else if (is_object($v)) $args[] = 'Object:'.get_class($v);
					else if (is_bool($v)) $args[] = $v ? 'true' : 'false';
					else 
					{
						$v = (string) @$v;
						$str = htmlspecialchars(substr($v,0,$MAXSTRLEN));
						if (strlen($v) > $MAXSTRLEN) 
							$str .= '...';
							
						$args[] = $str;
					}
				}
			}
		
			if (isset ($args))
			{
				$s .= $arr['function'].'('.implode(', ',$args).')';
			}
			
			if (isset ($arr['line']) && 
				isset ($arr['file']))
			{
				$s .= sprintf("</font><font color=#808080 size=-1> # line %4d,".
						 " file: <a href=\"file:/%s\">%s</a></font>",
						$arr['line'],$arr['file'],$arr['file']);
			}
					
			$s .= "\n";
		}
		
		$s .= '</pre>';
	}
	return $s;
}

function copyObject ($objectSrc, &$objectDest)
{
    $vars = get_object_vars ($objectSrc);
    foreach ($vars as $key=>$value)
    {
        $objectDest->$key = $value;
	}	
}
	
?>
