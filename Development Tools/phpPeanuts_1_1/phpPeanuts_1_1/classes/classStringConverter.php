<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntStringConverter', 'pnt/web');

/** Object of this class convert strings to values and back according to their format settings.
* All user interface String conversions are and should be delegated to StringConverters
* to make override possible. 
* 
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in superclass. 
* This class may be copied to an application folder to
* make application specific overrides.
*/
class StringConverter extends PntStringConverter {

}