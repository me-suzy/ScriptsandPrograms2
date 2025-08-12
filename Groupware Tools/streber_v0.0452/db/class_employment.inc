<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html



/**
* employments / jointable between company and person
*
* linking persons to companies is not required with work with projects. It's only
* purpose now, is to display additional information in personView and projView.
*
*
* @includedby:     *
*
* @author:         Thomas Mann
* @uses:           DbProjectList
* @usedby:
*
*/



global $g_employment_fields;
$g_employment_fields=array();


addProjectItemFields(&$g_employment_fields);

foreach(array(
    new FieldInternal(array(    'name'=>'id',
        'default'=>0,
        'in_db_object'=>1,
        'in_db_item'=>1,
    )),
    new FieldInternal(array(    'name'=>'person',
    )),
    new FieldInternal(array(    'name'=>'company',
    )),
    new FieldString(array(      'name'=>'comment',
    )),
) as $f) {
    $g_employment_fields[$f->name]=$f;
}



class Employment extends DbProjectItem {
    public $name;
    public $project;

	//=== constructor ================================================
	function __construct ($id_or_array=NULL)
    {
        global $g_employment_fields;
        $this->fields= &$g_employment_fields;

        parent::__construct($id_or_array);
        $this->type= ITEM_EMPLOYMENT;
   	}
}




?>