<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObjectSearchPage', 'pnt/web/pages');

/** Kind of IndexPage with FilterFormPart for searching for objects. 
* Results are shown in a TablePart. Paging buttons are created by 
* a PntPagerButtonsListBuilder, whose classfolder is pnt/web/helpers.
* Columns of the TablePart can be specified in metadata on the class
* specified by pntType request parameter, 
* @see http://www.phppeanuts.org/site/index_php/Pagina/61
*
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in superclass. 
* This class may be copied to an application folder to
* make application specific overrides.
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
*/
class ObjectSearchPage extends PntObjectSearchPage {

}
?>