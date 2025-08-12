<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntHcodeClass', 'pnt/hcode');

/** Instances correspond to classes that reside in files.
* Database is used for caching and searching.
* Instances can be loaded from the database, or
* created from a key. If they are loaded from a 
* database, the persistent fields will all be initialized.
* The source property is not persistent, its content
* is allways retrieved from file.
* If created from a key, only the fields whose values
* are present in the key are initialized, right away, the others are 
* initialized when needed.
*
* In any case, if an array of instances is retrieved from a propery, 
* only instances that really exist on the filessystem
* should be returned. 
* If an instances existance is validated and it no longer exists, 
* it is deleted from the database and all methods are too.
* If source, parent, package or methods are retrieved,
* the instance must be validated and if it is outdated, 
* it must be updated, and the database too. 
* If the database is updated, methods are updated but 
* subclasses are not. Parents are only updated as
* far as necessary to prevent cycles in recursive parent retrieval.
*
* in order to fit into our CMS hcode classes  
* support the necessary interface from Menu or Page.	
*
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in superclass. 
* @package hcode
*/
class HcodeClass extends PntHcodeClass {
	
}