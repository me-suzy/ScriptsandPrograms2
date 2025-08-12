<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntHcodePackage', 'pnt/hcode');
	
/** Objects of this class represent classFolders. 
* in order to fit into our CMS hcode classes should 
* support the necessary interface from Menu or Pagina.
*
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in superclass. 
* @package hcode
*/
class HcodePackage extends PntHcodePackage {

}