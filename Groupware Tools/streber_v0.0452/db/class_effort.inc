<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * effort object
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

    global $effort_fields;
    $effort_fields=array();
    addProjectItemFields(&$effort_fields);

            foreach(array(
                new FieldInternal(array(    'name'=>'id',
                    'default'=>0,
                    'in_db_object'=>1,
                    'in_db_item'=>1,
                )),
                new FieldInternal(array(    'name'=>'project',
                    'default'=>0,
                    'in_db_object'=>1,
                    'in_db_item'=>1,
                )),
                new FieldString(array(      'name'=>'name',
                    'tooltip'=>'optional if tasks linked to this effort'
                )),

                new FieldInternal(array(      'name'=>'task',
                )),

                new FieldDatetime(array(    'name'=>'time_start',
                    'default'=>FINIT_NOW
                )),
                new FieldDatetime(array(    'name'=>'time_end',
                    'default'=>FINIT_NOW
                )),
                new FieldInternal(array(    'name'=>'person'
                )),
                new FieldText(array(        'name'=>'description'
                )),

            ) as $f) {
                $effort_fields[$f->name]=$f;
            }




/**
* class for handling project - efforts
*/
class Effort extends DbProjectItem
{
    public $level;              # level if child of parent-tasks
    public $type;

	//=== constructor ================================================
	function __construct ($id_or_array=NULL)
    {
        global $effort_fields;
        $this->fields= &$effort_fields;

        parent::__construct($id_or_array);
        $this->type= ITEM_EFFORT;
   	}


    public static function getDateCreatedLast()
    {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');

        require_once('db/class_effort.inc');
        $dbh = new DB_Mysql;
        $sth= $dbh->prepare(
            "SELECT MAX(e.time_end)
                 from {$prefix}item i,  {$prefix}effort e
                WHERE   i.created_by={$auth->cur_user->id}
                    AND i.type = '".ITEM_EFFORT."'
                    AND e.id = i.id
                    AND i.state = 1
                "
        )->execute();
    	$tmp=$sth->fetchall_assoc();
        if($tmp) {
            $tmp_values=array_values($tmp[0]);
            return $tmp_values[0];
        }
        else {
            return false;
        }
    }


    #------------------------------------------------------------
    # getProject
    #------------------------------------------------------------
    function getProject()
    {
        require_once("db/class_project.inc");
        if(!$this->project) {
            #trace("WARNING","Task:getProject. project-id not set");
            return NULL;
        }
        $project= new Project($this->project);
        return $project;
    }

    #------------------------------------------------------------
    # getProjectLink
    #------------------------------------------------------------
    function getProjectLink()
    {
        if($project= $this->getProject()) {
            return "<nobr>".$project->getLink().'</nobr>';
        }
        else {
            return NULL;
        }
    }


    function getPerson()
    {
        require_once("db/class_person.inc");
        $person= new Person($this->created_by);
        return $person;
    }

    function getPersonLink()
    {
        if($person= $this->getPerson()) {
            return "<nobr>".$person->getLink().'</nobr>';
        }
        else {
            return NULL;
        }
    }

}




?>
