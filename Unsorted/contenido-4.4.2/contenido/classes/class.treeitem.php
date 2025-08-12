<?php

/*****************************************
* File      :   $RCSfile: class.treeitem.php,v $
* Project   :   Contenido
* Descr     :   Contenido Tree Item Class
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   20.05.2003
* Modified  :   $Date: 2003/10/06 13:55:16 $
*
* © four for business AG, www.4fb.de
*
* $Id: class.treeitem.php,v 1.8 2003/10/06 13:55:16 timo.hummel Exp $
******************************************/

class TreeItem
{
	 
	/**
     * Sub Items for this tree item
     * @var array
     */
	var $subitems;
	
	/**
     * Determinates if this tree item is collapsed
     * @var boolean
     */
	var $collapsed;
	
	/**
     * ID for this item
     * @var string
     */
	var $id;
	
	/**
     * Name for this item
     * @var string
     */
	var $name;
	
	/**
     * Icon for the collapsed item
     * @var string
     */
	var $collapsed_icon;

	/**
     * Icon for the expanded item
     * @var string
     */
	var $expanded_icon;

	/**
     * Contains the level of this item
     * @var integer
     */
	var $level;
	
	/**
     * Contains custom entries
     * @var array
     */
	var $custom;
	
	/**
     * Contains the parent of this item
     * @var array
     */
	var $parent;
			
	function TreeItem($name ="", $id="", $collapsed = false)
	{
		$this->name = $name;
		$this->id = $id;
		$this->collapsed = $collapsed;
		$this->subitems = array();
		$this->collapsed_icon = 'images/but_plus.gif';
		$this->expanded_icon = 'images/but_minus.gif';
		$this->parent = -1;
	}
	
	function addItem(&$item)
	{
		$this->subitems[count($this->subitems)] = &$item;
		$item->parent = $this->id;
	}
	
	function addItemToID($item, $id)
	{
		if ($this->id == $id)
		{
			$this->subitems[count($this->subitems)] = &$item;
			$item->parent = $this->id;
		} else {
			foreach (array_keys($this->subitems) as $key)
			{
				$this->subitems[$key]->addItemToID($item, $id);
			}
		}
	}
	
	function &getItemByID($id)
	{

		if ($this->id == $id)
		{
			return ($this);
		} else {
			foreach (array_keys($this->subitems) as $key)
			{
				$retObj = &$this->subitems[$key]->getItemByID($id);
				if ($retObj->id == $id)
				{
					return ($retObj);
				}
			}
		}
		
		return false;
	}

	
	function removeItem ($id)
	{
		foreach (array_keys($this->subitems) as $key)
		{
			if ($this->subitems[$key]->id  == $id)
			{
				unset($this->subitems[$key]);
			}
		}	
	}

	function markExpanded ($id)
	{
		if ($this->id == $id)
		{
			$this->collapsed = false;
		} else {
			foreach (array_keys($this->subitems) as $key)
			{
				$this->subitems[$key]->markExpanded($id);
			}
		}
	}
	
	function expandAll ($start = -2)
	{
		if ($start != $this->id)
		{
			$this->collapsed = false;
		}
		
		foreach (array_keys($this->subitems) as $key)
		{
			$this->subitems[$key]->expandAll();
		}
	}
	
	function collapseAll ($start = -2)
	{
		if ($start != $this->id)
		{
			$this->collapsed = true;
		}
		
		foreach (array_keys($this->subitems) as $key)
		{
			$this->subitems[$key]->collapseAll();
		}
	}
	function markCollapsed($id)
	{
		
		if ($this->id == $id)
		{
			$this->collapsed = true;
		
		} else {
			foreach (array_keys($this->subitems) as $key)
			{
				$this->subitems[$key]->markCollapsed($id);
			}
		}
	}
	
	function getExpandCollapseButton ()
	{
		global $sess, $PHP_SELF, $frame, $area;
		$selflink = "main.php";
		
		if (count($this->subitems) > 0)
		{
			if ($this->collapsed == true)
			{
				$expandlink = $sess->url($selflink . "?area=$area&frame=$frame&expand=". $this->id);
				return(
					'<a href="'.$expandlink.'" alt="Kategorie öffnen" title="Kategorie öffnen"><img src="'.
						$this->collapsed_icon
						.'" border="0"></a>');
			} else {
				$collapselink = $sess->url($selflink . "?area=$area&frame=$frame&collapse=". $this->id);
				return(
					'<a href="'.$collapselink.'" alt="Kategorie schließen" title="Kategorie schließen"><img src="'.
						$this->expanded_icon
						.'" border="0"></a>');
			}
		} else {
			return '<img src="images/spacer.gif" width="15" height="15">';
		}
	}
	
	function traverse (&$objects, $level = 0)
	{
		$objects[count($objects)] = &$this;
		$this->level = $level;
		
		if ($this->collapsed == false)
		{
			foreach (array_keys($this->subitems) as $key)
			{
				$this->subitems[$key]->traverse($objects, $level + 1);
			}
		}
		
	}		
	
	function getCollapsedList (&$list)
	{
		if ($this->collapsed == true)
		{
			$list[] = $this->id;
		}
		
		foreach (array_keys($this->subitems) as $key)
		{
			$this->subitems[$key]->getCollapsedList($list);
		}
	}

	function getExpandedList (&$list)
	{
		if ($this->collapsed == false)
		{
			$list[] = $this->id;
		}
		
		foreach (array_keys($this->subitems) as $key)
		{
			$this->subitems[$key]->getExpandedList($list);
		}
	}
		
}

?>