<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

	
class PntPagerButtonsListBuilder {
	
	var $page;
	var $itemCount;
	var $pageItemOffset;
	var $pageItemCount = 20;
	var $maxPageButtonCount = 9;
	var $pageButtonSize = 40;

	function PntPagerButtonsListBuilder(&$page)
	{
		$this->page =& $page;
	}

	function setItemCount($value)
	{
		$this->itemCount = $value;
	}

	function setPageItemOffset($value)
	{
		$this->pageItemOffset = $value;
	}

	function setPageItemCount($value)
	{
		$this->pageItemCount = $value;
	}

	function setMaxPageButtonCount($value)
	{
		$this->maxPageButtonCount = $value;
	}

	function setPageButtonSize($value)
	{
		$this->pageButtonSize = $value;
	}

	function getItemCount()
	{
		return $this->itemCount;
	}
			
	function getPageItemOffset()
	{
		return $this->pageItemOffset;
	}
	
	function getPageItemCount()
	{
		return $this->pageItemCount;
	}
	
	function getMaxPageButtonCount()
	{
		return $this->maxPageButtonCount;
	}
	
	function getPageButtonSize()
	{
		return $this->pageButtonSize;
	}
	
	function addPageButtonsTo(&$buttons)
	{
		$current = $this->getPageItemOffset();
		$maxButtonItemRange = $this->getMaxPageButtonCount() * $this->getPageItemCount();
		$i = min(ceil($this->getItemCount()/$this->getPageItemCount()) * $this->getPageItemCount() - $maxButtonItemRange
			, $current - floor($this->getMaxPageButtonCount() / 2) * $this->getPageItemCount());
		$firstButtonItemOffset = $i = max(0,$i);
		$afterLastButtonItemOffset = min($this->getItemCount(), $firstButtonItemOffset + $maxButtonItemRange);

		$buttons[] = $this->page->getButton(
			'<'
			, $this->page->getPageButtonScript($current - $this->getPageItemCount())
			, $current < ($firstButtonItemOffset + $this->getPageItemCount())
			, $this->getPageButtonSize());
		
		while ($i < $afterLastButtonItemOffset) {
			$buttons[] = $this->page->getButton(
				floor($i/$this->getPageItemCount())+1
				, $this->page->getPageButtonScript($i)
				, $current >= $i && $current < ($i + $this->getPageItemCount())
				, $this->getPageButtonSize());
			$i += $this->getPageItemCount();
		} 

		$buttons[] = $this->page->getButton(
			'>'
			, $this->page->getPageButtonScript($current + $this->getPageItemCount())
			, $current >= ($this->getItemCount() - $this->getPageItemCount())
			, $this->getPageButtonSize());
	}

}

?>