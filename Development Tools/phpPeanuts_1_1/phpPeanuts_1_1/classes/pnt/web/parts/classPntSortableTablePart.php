<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntTablePart', 'pnt/web/parts');

/** --------------       WebFx SortableTable support    ---------------------------
* to activate, download the sortable table from http://webfx.eae.net/,
* include sortabletable.js and sortabletable.css from ../includes/skinHeader.php
* and subclass ../classes/classPntTablePart.php from this class.
*
* WARNING: One of the licenses available for WebFx SortableTable is GPL. 
* If you use WebFx SortableTable under GPL, code that includes,
* calls or is called by WebFx SortableTable and other code that uses that code 
* recursively must be offered to the rest of the world  by you under GPL,
* unless it is a separate independent work in its own right.
* This may include this code too!
* It may include much more code, depending on how the law works in the 
* country under whose jurisdiction your use of WebFx Sortabletable is licensed.
* Without legal advice it is hard to say where it stops. 
* If you are publishing all your work under GPL anyway that will not be a problem, 
* but if you also have code under other liceses, GPL may prove to be a minefield 
* that is not worth it given the little cost of a commercial license 
* on WebFx Sortabletable.
*/

class PntSortableTablePart extends PntTablePart {
	
	function printBody() 
	{
		parent::printBody();
		$this->printTableSortScript();
	}

	function printTableSortScript()
	{		
		if (count($this->getItems()) < 2)
			return;  //sorting not usefull
?>
	<script type="text/javascript">
		var itemTable = getElement("<?php $this->printTableId() ?>");
		var sortTypes = [<?php $this->printColumnSortTypes($this) ?>];
		var sorter = new SortableTable(itemTable, sortTypes);

		// SortableTable allways sets the image src to 'images/blank.png' ;-(((
		var tds = itemTable.tHead.rows[0].cells;
		for (var t = 0; t < tds.length; t++) {
			tds[t].lastChild.src = '../components/sortabletable/images/blank.png';
		}
		
		// to sort initially ascending when clicking on a different column, 
		// modify line 177 of sortabletable.js to: this.descending = false;
	</script>
<?php
	}		
	
	function printColumnSortTypes(&$table)
	{
		if ($this->itemSelectWidgets)
			print '"None", ';
		$comma = '';
		reset($table->headers);
		while (list($key, $label) = each($table->headers)) {
			$navText =& $this->cells[$key];
			$sortType = $this->getSortType($navText->getContentType());
			print "$comma\"$sortType\"";
			$comma = ', ';
		}
	}

	//returns one of "String", "CaseInsensitiveString", "Number", "Date", "None"
	function getSortType($attributeType)
	{
		$map = array(
			"number" => "Number"
			, "date" => "Date"
		);
		return isSet($map[$attributeType])
			? $map[$attributeType]
			: "CaseInsensitiveString";
	}
	
}