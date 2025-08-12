<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObjectSelectionReportPage', 'pnt/web/pages');

/** Page showing a TablePart with manually selected objects. 
* Navigation leads to ReportPages. 
* Columns shown in the TablePart can be overridden by creating a 
* getReportColumnPaths method on the type of objects shown in the table.
* totals are shown of columns with values of properties typed as number. 
*
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in superclass. 
* This class may be copied to an application folder to
* make application specific overrides.
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
*/
class ObjectSelectionReportPage extends PntObjectSelectionReportPage {
	

}
?>