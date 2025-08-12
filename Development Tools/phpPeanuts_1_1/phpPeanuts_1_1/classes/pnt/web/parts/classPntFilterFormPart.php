<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPagePart', 'pnt/web/parts');

/** Part used by SearchPage to output html describing search forms.
* The search options are modeled by pnt.db.query.PntSqlSpec objects.
* @see http://www.phppeanuts.org/site/index_php/Pagina/41
*
* This abstract superclass provides behavior for the concrete
* subclass FilterFormPart in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
* @package pnt/web/parts
*/
class PntFilterFormPart extends PntPagePart {

	var $implicitCombiFilter;
	var $allItemsSize;
	var $filters;
	
	function getName() {
		return $this->whole->getFilterFormPartName();
	}

	function getFilter1Id()
	{
		if (isSet($this->filter1Id) ) return $this->filter1Id;
			
		return isSet($this->requestData['pntF1']) ? $this->requestData['pntF1'] : null;
	}

	function getFilter1Cmp()
	{
		if (!isSet($this->requestData['pntF1cmp']) ) return 'LIKE';
		
		return stripSlashes($this->requestData['pntF1cmp']);
	}

	function getFilter1Value1()
	{
		return isSet($this->requestData['pntF1v1']) ? stripSlashes($this->requestData['pntF1v1']) : null;
	}
	
	function getFilter1Value2()
	{
		return isSet($this->requestData['pntF1v2']) ? stripSlashes($this->requestData['pntF1v2']) : null;
	}

	function getAllItemsSize()
	{
		if ($this->allItemsSize !== null)
			return $this->allItemsSize;
			
		return isSet($this->requestData['allItemsSize']) ? $this->requestData['allItemsSize'] : null;
	}
			
	function getPageItemOffset()
	{
		return isSet($this->requestData['pageItemOffset']) ? $this->requestData['pageItemOffset'] : null;
	}

	function printSearchButtonLabel()
	{
		print $this->whole->getSearchButtonLabel();
	}

	function printDivDisplayStyle($divId)
	{
		$show = isSet($this->requestData['advanced']); //empty if no button at all -> advanced is default hidden
		if ($divId == 'simpleFilterDiv')
			$show = !$show;
		if ($show)
			print 'block';
		else
			print 'none';
	}

	function printExtraFormParameters()
	{
		$own =& $this->getOwnFormParameterKeys();
		reset($this->requestData);
		while (list($key, $value) = each($this->requestData)) {
			if ( isFalseOrNull(array_search($key, $own)) ) 
				print "<input type=hidden value='$value' name='$key'>";
		}
	}
	
	function getOwnFormParameterKeys()
	{
		return array('pntType', 'pntHandler', 'pntF1', 'pntF1cmp', 'pntF1v1', 'pntF1v2'
			, 'simple', 'advanced', 'allItemsSize', 'pageItemOffset');
	}

	function printFilterSelectWidget()
	{	
		includeClass('SelectWidget', 'widgets');
		
		$filters =& $this->getFilters();
		$selectedId = $this->getFilter1Id();
		$widget =& new SelectWidget($this, $this->requestData);
		$widget->setFormKey('pntF1');
		$widget->setSelectedId($selectedId);
		$widget->setAutoSelectFirst(true);
		$widget->setOptionsFromObjects($filters, $this->getType());
		$widget->printBody();
	}
	
	function printComparatorSelectWidget()
	{
		
//		we want the comparators to depend on the selected filter (would require javascript)
//		$nav =& PntNavigation->_getInstance('operator', 'PntSqlFilter');

		includeClass('PntComparator', 'pnt/db/query');

		$selectedId = $this->getFilter1Cmp();

		$comparators =& Comparator::getInstances();

		$widget =& new SelectWidget($this, $this->requestData);
		$widget->setFormKey('pntF1cmp');
		$widget->setSelectedId($selectedId);
		$widget->setAutoSelectFirst(true);
		$widget->setOptionsFromObjects($comparators, $this->getType());
		$widget->printBody();
	}

	function &getRequestedObject()
	{
		if (isSet($this->object))
			return $this->object;

		$this->object =& $this->getFilter();
		return $this->object;
	}
	
	function getFormName()
	{
		if ($this->getFilter1Id() == 'All stringfields')
			return 'simpleFilterForm';
		else
			return 'advancedFilterForm';
	}

	function &getFilter()
	{
		$filterId = $this->getFilter1Id();
		if ($filterId == 'All stringfields')
			return $this->getAllStringfieldsFilter();
		if (!$filterId || !$this->getFilter1Cmp())
			return null;
			
		$filters =& $this->getFilters();
		reset($filters);
		while (list($key) = each($filters))
			if ($filters[$key]->getId() == $filterId) {
				$filter = $filters[$key];  //copy the filter
				$this->initFilter($filter);
				return $filter;
			}
				
		return null;
	}

	function &getFilters()
	{
		if (!$this->filters) {
			$clsDes =& $this->whole->getTypeClassDescriptor();
			$this->filters =& $clsDes->getFilters(2); //does includeClass
		}
		return $this->filters;
	}

	function &getAllStringfieldsFilter()
	{
		$clsDes =& $this->whole->getTypeClassDescriptor();
		$filters = $this->getFilters(); //copy 
		$result =& $clsDes->getAllFieldsFilter($filters, 'string');
		$this->initFilter($result, true);
		return $result;
	}

	function initFilter(&$filter, $addWildcards=false)
	{
		if (!$filter) return
			
		$clsDes =& $this->whole->getTypeClassDescriptor();
			
		$conv = $this->getConverter();
		$filter->initConverter($conv);

		$f1v1 = $this->getFilter1Value1();
		if ($addWildcards) 
			$f1v1 = $this->addWildcards($f1v1);
		$filter->set('value1', $conv->fromLabel($f1v1));
		
		$filter->set('value2', $conv->fromLabel(
			$this->getFilter1Value2()
		));
		$filter->set('comparatorId', $this->getFilter1Cmp());
	}

	function addWildcards($filterValue)
	{
		if (strpos($filterValue, '*') !== 0)
			$filterValue = '*'.$filterValue;
		if (strrpos($filterValue, '*') !== strLen($filterValue) - 1)
			$filterValue .= '*';
		return $filterValue;
	}
	
	function &getSort(&$filter)
	{
		$clsDes =& $this->whole->getTypeClassDescriptor();
		$sort =& $clsDes->getLabelSort();
		$sort->setFilter($filter);
		
		if ($filter->canBeSortSpec()) {
			//sort by the filter criterium first if there will be different values for it
			$filtersUniqueValue = array('=' => true, 'IS NULL' => true);
			if (!isSet($filtersUniqueValue[$filter->get('comparatorId')]) ) {
				//for some unknown reason assigning by value does not copy the filter, 
				//so we must create a new one to get rid of the comparatorId without modifying the filter itself
				$sortSpec = PntSqlFilter::getInstance($filter->get('itemType'), $filter->getPath() );
				$sort->addSortSpecFilterFirstOrMoveExistingFirst($sortSpec);
			}
		}
		return $sort;
	}
	
	/** Returns the filter from getRequestedObject combined with the 
   * implicitCombiFilter. Assumed to be called only once!
	*/
	function &getCombinedFilter()
	{
		if (!isSet($this->implicitCombiFilter))
			return $this->getRequestedObject();
			
		$this->implicitCombiFilter->addPart($this->getRequestedObject() );
		return $this->implicitCombiFilter; 
	}
	
	//PREREQUISITE: $this->getRequestedObject() not null
	function &getFilterResult($rowCount=20)
	{
		if (isSet($this->result)) return $this->result;
		
		$filter =& $this->getCombinedFilter();
		$sort =& $this->getSort($filter);
		
		$clsDes =& $this->whole->getTypeClassDescriptor();
		$qh =& $clsDes->getSelectQueryHandler();
		
		$qh->addSqlFromSpec($sort);

		if ($this->getAllItemsSize()) {
			$offset = $this->getPageItemOffset();
			$qh->limit($rowCount, $offset);
			$result =& $clsDes->_getPeanutsRunQueryHandler($qh);
		} else {
			$result =& $this->_runQhStoreAllItemsSize_getItemsLimitedTo($qh, $rowCount);
		}
//print $qh->query;
		if (is_ofType($result, 'PntError')) {
			trigger_error($result->getLabel(), E_USER_WARNING);
			$result = array();
		}
		$this->result =& $result;
		return $result;
	}
	
	function &_runQhStoreAllItemsSize_getItemsLimitedTo(&$qh, $rowCount)
	{
		$clsDes =& $this->whole->getTypeClassDescriptor();
		$qh->_runQuery();
		if ($qh->getError()) 
			return new PntError($this, $qh->getError());
			
		$this->allItemsSize = $qh->getRowCount();

		$result = array();
		$offset = $this->getPageItemOffset();
		if ($this->allItemsSize > $offset) {
			$i = 0;
			$qh->dataSeek($offset);
			while ( $i < $rowCount && ($row=mysql_fetch_assoc($qh->result)) ) {
				$instance =& $clsDes->_getDescribedClassInstanceForData($row, $null);
				if (is_ofType($instance, 'PntError'))
					return $instance;
				$result[] =& $instance;
				$i++;
			}
		}					
		return $result;
	}
	
	function &getImplicitCombiFilter()
	{
		return $this->implicitCombiFilter;
	}

	function setImplicitCombiFilter(&$combiFilter)
	{
		$this->implicitCombiFilter =& $combiFilter;
	}
}
?>