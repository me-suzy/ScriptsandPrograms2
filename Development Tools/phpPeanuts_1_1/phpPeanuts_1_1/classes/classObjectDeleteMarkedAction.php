<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObjectDeleteMarkedAction', 'pnt/web/actions');

/** Action that deletes multiple objects. 
* Used by form from ObjectIndexPage, ObjectSearchPage and ObjectPropertyPage 
* when Delete button is pressed and items are marked.
* Redirects to pntContext or if none, to ObjectIndexPage 
* @see http://www.phppeanuts.org/site/index_php/Pagina/158
*
* This concrete subclass is here to keep de application developers
* code separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in the superclass
* This class may be copied to an application folder to
* make application specific overrides.
*/
class ObjectDeleteMarkedAction extends PntObjectDeleteMarkedAction {

	
}
?>