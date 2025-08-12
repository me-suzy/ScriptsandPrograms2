<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntValueValidator', 'pnt');


/** An object that checks values against its constraint settings and returns error messages.
* @see http://www.phppeanuts.org/site/index_php/Pagina/131
* 
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in superclass. 
* This class may be copied to an application folder to
* make application specific overrides.
*/
class ValueValidator extends PntValueValidator {


}