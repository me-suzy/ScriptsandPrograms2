<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * comments object
 *
 * @includedby:     *
 *
 * @author:         Thomas Mann
 * @uses:           DbProjectList
 * @usedby:
 *
 */


/**
*  setup the database fields for comment-object as global assoc-array
*/
    global $comment_fields;
    global $COMMENTTYPE_VALUES;
    $comment_fields= array();
    addProjectItemFields(&$comment_fields);

    foreach(array(
        new FieldInternal(array(    'name'=>'id',
            'default'=>0,
            'in_db_object'=>1,
            'in_db_item'=>1,
        )),
            new FieldString(array(      'name'=>'name',
            )),
            new FieldDatetime(array(    'name'=>'time',
                'default'=>FINIT_NOW,
                'view_in_forms'=>false,
            )),
            new FieldHidden(array(      'name'=>'person',
                'view_in_forms'=>true
            )),
            new FieldHidden(array(      'name'=>'comment',
                'view_in_forms'=>true
            )),
            new FieldHidden(array(      'name'=>'task',
                'view_in_forms'=>true
            )),
            new FieldHidden(array(      'name'=>'effort'
            )),
            new FieldInternal(array(      'name'=>'view_collapsed'
            )),

            new FieldHidden(array(      'name'=>'file'
            )),
            new FieldBool(array(        'name'=>'starts_discussion',
                'title'=>'Starts Project Discussion',
                'view_in_forms'=>false,         # save discussions as a later feature

            )),
            new FieldText(array(        'name'=>'description',
            )),
            new FieldHidden(array(         'name'=>'occasion',
                'default'=>$COMMENTTYPE_VALUES['Comment'],
            )),
    ) as $f) {
        $comment_fields[$f->name]=$f;
    }


/**
* class for handling project - comments
*/
class Comment extends DbProjectItem
{
    public $level;              # level if child of parent-tasks
    public $type;

	//=== constructor ================================================
	function __construct ($id_or_array=false)
    {
        global $comment_fields;
        $this->fields= &$comment_fields;

        parent::__construct($id_or_array);
        $this->type= ITEM_COMMENT;
   	}

}




?>