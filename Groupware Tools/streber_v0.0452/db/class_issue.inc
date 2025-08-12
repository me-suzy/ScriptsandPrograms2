<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html


/**
 * Issue-report (adds additional fields to task)
 *
 * @includedby:     *
 *
 * @author:         Thomas Mann
 * @uses:           
 * @usedby:
 *
 */


global $g_issue_fields;
$g_issue_fields=array();

global $REPRODUCIBILITY_VALUES;
global $SEVERITY_VALUES;


addProjectItemFields(&$g_issue_fields);

foreach(array(
    new FieldInternal(array(    'name'=>'id',
        'default'=>0,
        'in_db_object'=>1,
        'in_db_item'=>1,
    )),
    new FieldInt(array(      'name'=>'reproducibility',
        'default'=> $REPRODUCIBILITY_VALUES['have_not_tried'],
        'view_in_forms'=>false,
    )),
    new FieldInt(array(      'name'=>'severity',
        'default'=> $SEVERITY_VALUES['minor'],
        'view_in_forms'=>false,
    )),
    new FieldString(array(      'name'=>'plattform',
    )),
    new FieldString(array(      'name'=>'os',
    )),
    new FieldString(array(      'name'=>'version',
    )),
    new FieldString(array(      'name'=>'production_build',
    )),
    new FieldText(array(      'name'=>'steps_to_reproduce',
    )),
    new FieldText(array(      'name'=>'expected_result',
    )),
    new FieldText(array(      'name'=>'suggested_solution',
    )),
) as $f) {
    $g_issue_fields[$f->name]=$f;
}



class Issue extends DbProjectItem {

	//=== constructor ================================================
	function __construct ($id_or_array=NULL)
    {
        global $g_issue_fields;
        $this->fields= &$g_issue_fields;

        parent::__construct($id_or_array);
        $this->type= ITEM_ISSUE;
   	}
}




?>