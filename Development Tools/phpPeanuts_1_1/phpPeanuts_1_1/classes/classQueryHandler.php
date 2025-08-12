<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntQueryHandler', 'pnt/db');

/** Objects of this class are used for generating and executing database queries.
* This class is the database abstraction layer.
* Currently only mySQL is supported by PntQueryHandler, in future other 
* superclasses may support more databases.   
* @see http://www.phppeanuts.org/site/index_php/Pagina/50
*
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in superclass. 
* This class may be copied to an application folder to
* make application specific overrides.
*/
class QueryHandler extends PntQueryHandler {


}
?>