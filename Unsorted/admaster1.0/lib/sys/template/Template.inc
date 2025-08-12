<?
include_once "sys/template/Displayable.inc";

class Template extends Displayable
{
	function Template ($groupName = "", $tmplName = "")
	{
		// construct parent object
		$this->Displayable ();
		
		if (strlen ($groupName) == 0 && 
			strlen ($tmplName) == 0)
			return;

		// here may be checking input params 
		//////////////////////////
		
		$body = $this->getTmpl ($groupName, $tmplName);

		if ($body)
		{
			$this->add ($body);
			$this->parse ();
		}
	}

	function getTmpl ($groupName, $tmplName)
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
	function parse ()
	{
		$this->parseActionTags  ();
		$this->parseTmplTags ();
		$this->parseReplaceTags ();	
	}
	
	function parseActionTags ()
	{
		// process group tag
		if (!preg_match ("/\<action:([A-Za-z0-9_\.\\/]+):([A-Za-z0-9_]+)>/i", $this->body, $regs) || sizeof ($regs) == 0)
			return true;

		$pathName   = $regs [1];
		$actionName = $regs [2];

		//if (file_exists ($pathName) && is_readable ($pathName))
		//{
			include_once "$pathName";
			
			if (class_exists ($actionName))
			{
				$action = new $actionName ();

				if (!method_exists ($action, "display"))
					$this->replace ($regs [0], "");
				else
					$this->replace ($regs [0], $action->display ());
			}
			else
				$this->replace ($regs [0], "");
		//}
		//else
		//	$this->replace ($regs [0], "");
		
		$this->parseActionTags ();	
	}
	
	function parseTmplTags ()
	{
		// process group tag
		if (!preg_match ("/\<template:([A-Za-z]+):([A-Za-z]+)>/i", $this->body, $regs) || sizeof ($regs) == 0)
			return true;
		
		$template = new Template ($regs [1], $regs [2]); 
		$this->replace ($regs [0], $template->display ());
		$this->parseTmplTags ();
	}
	
	function parseReplaceTags ()
	{
		// process replace tag
		$this->body = preg_replace ("/\<replace:([A-Za-z]+)>/i", "", $this->body);
	}
}
?>