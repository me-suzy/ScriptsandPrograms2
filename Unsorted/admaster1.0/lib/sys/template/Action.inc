<?
include_once "sys/template/Transferable.inc";



class Action extends Transferable
{
	// actionVar of global variable that can containt the action value
	var $actionVar     = "CatAction";
	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('ExampleDeleteAction' => 'deleteCats', 
								'ExampleAddAction'    => 'addCat',
								'ExampleShowAction'   => 'showCats',);
								
	// default action actionVar
	var $defActionName = 'ExampleShowAction'; 	
	
	var $storage = "";
	
	var $error   = array (),
		$warning = array ();
	
	function Action ($data = -1) 
	{
		$this->Transferable ($data);
		$this->prepare ();
		
		// try to get global 
		global ${$this->actionVar};
		
		$methodName = "unknown method";
		if (isset (${$this->actionVar}))
		{
			$actionVar = ${$this->actionVar};
			$methodName = $this->action [$actionVar];
		}
		
		if (in_array ($actionVar, array_keys ($this->action)) &&
			method_exists ($this, $methodName))
		{
			$this->store (array ($this->actionVar => $actionVar));
			@call_user_method ($methodName, $this);
			new Debug ("Try to call method $methodName.", "Action", "Action", "TEST");
			return;
		}
		$this->store (array ($this->actionVar => $this->defActionName));
		@call_user_method ($this->action [$this->defActionName], $this);
		new Debug ("Try to call default method ".$this->action [$this->defActionName].".", "Action", "Action", "TEST");
	}
	
	function getTemplate ($groupName, $tmplName)
	{
		$db = new DB ();
		
		if (!$db->execute ("select ID from TemplateGroup where Name = '$groupName'") || 
			 $db->getNumRows () == 0)
		{
			new Debug ("Cannt access to template group \"$groupName\"", "Template", "Template", "ERROR");
			return false;
		}
		
		$groupID = $db->recordSet [0][0];
		if (!$db->execute ("select Body from Template where GroupID = $groupID and Name = '$tmplName'") || 
			 $db->getNumRows () == 0)
		{
			new Debug ("Cannt access to template \"$tmplName\" in the group \"$groupName\" (#$groupID).", "Template", "Template", "ERROR");
			return false;
		}
		return stripslashes ($db->recordSet [0][0]);
	}
	function add ($data)
	{
		$this->storage .= $data;
	}
	function replace ($name, $value, $where = "")
	{
		if (strlen ($where) == 0)
			$this->storage = preg_replace ("/\<replace:$name>/i", $value, $this->storage);
		else
			return preg_replace ("/\<replace:$name>/i", $value, $where);
	}
	function jsreplace ($name, $value, $where = "")
	{
		if (strlen ($where) == 0)
			$this->storage = preg_replace ("/\<jsreplace:$name>/i", $value, $this->storage);
		else
			return preg_replace ("/\<jsreplace:$name>/i", $value, $where);
	}
	function display ()
	{
		return $this->storage;
	}
	function addError ($message)
	{
		$this->error [sizeof ($this->error)] = $message;
	}
	function addWarning ($message)
	{
		$this->warning [sizeof ($this->warning)] = $message;
	}
	function displayError ()
	{
		if (sizeof ($this->error) == 0)
			return '';
		
		$mainTmpl    = $this->getTemplate ("Message", "Error");
		$elementTmpl = $this->getTemplate ("Message", "ErrorElement");
		
		$element = ''; 
		for ($i = 0; $i < sizeof ($this->error); $i++)
		{
			$element .= $elementTmpl;
			$element =  $this->replace ('Element',   $this->error [$i],   $element);
		}
		
		return $this->replace ('Element',   $element,   $mainTmpl);
	}
	
	function displayWarning ()
	{
		if (sizeof ($this->warning) == 0)
			return '';
			
		$elementTmpl = $this->getTemplate ("Message", "Warning");
		
		return $this->replace ('Element',   $this->warning [0],   $elementTmpl);
	}
	function prepare () {}
}
?>