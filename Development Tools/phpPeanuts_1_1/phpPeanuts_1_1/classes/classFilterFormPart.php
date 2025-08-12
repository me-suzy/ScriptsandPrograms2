<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntFilterFormPart', 'pnt/web/parts');

/** part used by SearchPage to output html describing search forms.
* The search options are modeled by pnt.db.query.PntSqlSpec objects.
* @see http://www.phppeanuts.org/site/index_php/Pagina/41
*
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in the superclass. 
* This class may be copied to an application folder to
* make application specific overrides.
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
*/
class FilterFormPart extends PntFilterFormPart {

}
?>