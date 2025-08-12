<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntSqlCombiFilter', 'pnt/db/query');

/** Specifies the combination of mutliple PntSqlFilters by OR. 
* Used by FilterFormPart in the simple search.
* part for navigational query specification, part of a PntSqlSpec
* @see http://www.phppeanuts.org/site/index_php/Pagina/170
*
* PntSqlFilters produce what comes after the WHERE clause to retrieve
* some objects as well as a JOIN clause to access related tables.
* Objects of this class combine the JOIN clauses from multiple PntSqlFilters
* from $this->parts and combine their WHERE expressions using their combinator field
*
* Current version is MySQL specific. In future, all SQL generating methods should 
* delegate to PntQueryHandler to support other databases
* @package pnt/db/query
*/
class PntSqlMultiOrFilter extends PntSqlCombiFilter {

	var $combinator = 'OR';

}
?>