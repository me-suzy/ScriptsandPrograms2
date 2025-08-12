<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * project
 *
 * @includedby:     *
 *
 * @author:         Thomas Mann
 * @uses:           DbProjectItem
 * @usedby:
 *
 */



global $g_project_fields;
$g_project_fields=array();
addProjectItemFields(&$g_project_fields);

foreach(array(
    new FieldInternal(array(    'name'=>'id',
        'default'=>0,
        'in_db_object'=>1,
        'in_db_item'=>1,
    )),
    new FieldInternal(array(    'name'=>'state',    ### cached in project-table to speed up queries ###
        'default'=>1,
        'in_db_object'=>1,
        'in_db_item'=>1,
    )),
    new FieldString(array(      'name'=>'name',
    )),
    new FieldString(array(      'name'=>'short',
    )),
    new FieldString(array(      'name'=>'status_summary',
    )),
    new FieldString(array(      'name'=>'color',
    )),
    new FieldDate(array(        'name'=>'date_start',
        'default'=>FINIT_TODAY
    )),
    new FieldDate(array(        'name'=>'date_closed',
        'default'=>FINIT_NEVER
    )),
    new FieldOption(array(      'name'=>'status',
        'default'=>3
    )),
    new FieldString(array(      'name'=>'projectpage',
    )),
    new FieldString(array(      'name'=>'wikipage',
    )),
    new FieldInt(array(         'name'=>'prio',
        'default'=>3
    )),     # @@@ todo: default-status and prio should be project-setting!
    new FieldText(array(        'name'=>'description'
    )),
    new FieldInt(array(         'name'=>'company',
    )),
    new FieldBool(array(        'name'=>'show_in_home',
        'default'=>1,
        'title'=>'show tasks in home',
    )),
    new FieldHidden(array(      'name'=>'labels',
        'default'=>'Important,Bug,Request,Research,Organize,Design,3d,Doc,Feature,Idea'
    )),

    new FieldInternal(array(    'name'=>'default_pub_level',    # level of new items
        'view_in_forms'=>false,
        'default'=>PUB_LEVEL_OPEN,
    )),
) as $f) {
    $g_project_fields[$f->name]=$f;
}




/**
* Project
*/
class Project extends DbProjectItem 
{

	//=== constructor ================================================
	function __construct ($id_or_array=NULL)
    {
        global $g_project_fields;
        $this->fields= &$g_project_fields;

        parent::__construct($id_or_array);
        $this->type= ITEM_PROJECT;
   	}

    /**
    * get task folders
    */
    function getFolders($order_by=NULL)
    {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');

        if(!$order_by) {
            $order_by="t.name";
        }
        $dbh = new DB_Mysql;
        try {
      	    $sth= $dbh->prepare(
                    "SELECT i.*, t.* from {$prefix}item i, {$prefix}task t, {$prefix}projectperson upp
                    WHERE
                            upp.person = {$auth->cur_user->id}
                        AND upp.project = $this->id
                        AND upp.state = 1

                        AND i.type = '".ITEM_TASK."'
                        AND i.project = $this->id
                        AND i.state = 1
                        AND ( i.pub_level >= upp.level_view
                              OR
                              i.created_by = {$auth->cur_user->id}
                        )

                        AND t.id = i.id
                        AND t.is_folder = 1
                        AND t.status <= 4

                    ORDER BY $order_by"
            );
        	$sth->execute("",1);
        	$tmp=$sth->fetchall_assoc();

        }
        catch (MysqlException $e) {
        	throw $e;
        }
        $taskfolders=array();
        foreach($tmp as $t) {
            $taskfolders[]=new Task($t);
        }

        return Task::sortHierarchical($taskfolders);
    }

    /**
    * get Efforts
    */
    function getEfforts($order_by=NULL, $visible_only=true, $alive_only=true)
    {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');
        if(!$order_by) {
            $order_by="name";
        }
        require_once('db/class_effort.inc');
        $dbh = new DB_Mysql;

        $str_is_alive= $alive_only
        ? "AND i.state=1"
        : "";

        if($visible_only) {
            $str_query= 
            "SELECT i.*, e.* from {$prefix}item i, {$prefix}effort e, {$prefix}project p, {$prefix}projectperson upp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.project = $this->id
                AND upp.state = 1


                AND i.type = '".ITEM_EFFORT."'
                AND i.project = $this->id
                $str_is_alive
                AND p.id = i.project

                AND ( i.pub_level >= upp.level_view
                      OR
                      i.created_by = {$auth->cur_user->id}
                )

                AND e.id = i.id
            ORDER BY $order_by";
        }
        else {
            $str_query= 
            "SELECT i.*, e.* from {$prefix}item i, {$prefix}effort e, {$prefix}project p
            WHERE

                    i.type = '".ITEM_EFFORT."'
                AND i.project = $this->id
                $str_is_alive
                AND p.id = i.project

                AND e.id = i.id
            ORDER BY $order_by";        }
        

        $sth= $dbh->prepare($str_query);
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
        $efforts=array();
        foreach($tmp as $t) {
            $efforts[]=new Effort($t);
        }
        return $efforts;
    }

    /**
    * get Efforts sum
    */
    function getEffortsSum()
    {
        $efforts= $this->getEfforts();
        $sum=0.0;
        foreach($efforts as $e) {
            $sum+= strtotime($e->time_end)-strtotime($e->time_start);
        }
        return $sum;
    }


    /**
    * getTasks($project=false)
    */
    function &getTasks(
        $show_folders=true, 
        $order_by=NULL, 
        $status_min=2, 
        $status_max=4, 
        $visible_only=true, 
        $alive_only=true)
    {
        global $auth;
		$prefix = confGet('DB_TABLE_PREFIX');

        if(!$order_by) {
            $order_by="is_folder DESC, parent_task, prio ASC,project,name";
        }
        $dbh = new DB_Mysql;
        
        $str_is_alive= $alive_only
            ? "AND i.state=1"
            : "";
        
        if($visible_only) {
            
            ### folders ###
            if($show_folders) {

                $str_query=
                "SELECT i.*, t.* from {$prefix}item i, {$prefix}task t, {$prefix}projectperson upp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.project = $this->id
                    AND upp.state = 1

                    AND i.type = '".ITEM_TASK."'
                    AND i.project = $this->id
                    $str_is_alive
                    AND ( i.pub_level >= upp.level_view
                          OR
                          i.created_by = {$auth->cur_user->id}
                    )

                    AND t.id = i.id
                    AND t.status >= $status_min
                    AND t.status <= $status_max

                ORDER BY $order_by"
                ;
            }
            ### no folders ###
            else {
                $str_query=
            	"SELECT i.*, t.* from {$prefix}item i, {$prefix}task t, {$prefix}projectperson upp
	            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.project = $this->id
                AND upp.state = 1

                AND i.type = '".ITEM_TASK."'
                AND i.project = $this->id
                $str_is_alive
                AND ( i.pub_level >= upp.level_view
                      OR
                      i.created_by = {$auth->cur_user->id}
                )

                AND t.id = i.id
                AND t.is_folder = 0
                AND t.status >= $status_min
                AND t.status <= $status_max

                ORDER BY $order_by"
                ;
            }
        }
        ### show all ###
        else {
            ### folders ###
            if($show_folders) {

                $str_query=
                "SELECT i.*, t.* from {$prefix}item i, {$prefix}task t
                WHERE
                        i.type = '".ITEM_TASK."'
                    AND i.project = $this->id
                    $str_is_alive

                    AND t.id = i.id
                    AND t.status >= $status_min
                    AND t.status <= $status_max

                ORDER BY $order_by"
                ;
            }
            ### no folders ###
            else {
                $str_query=
            	"SELECT i.*, t.* from {$prefix}item i, {$prefix}task t
	            WHERE
                    i.type = '".ITEM_TASK."'
                AND i.project = $this->id
                $str_is_alive

                AND t.id = i.id
                AND t.is_folder = 0
                AND t.status >= $status_min
                AND t.status <= $status_max

                ORDER BY $order_by"
                ;
            }            
        }

        $sth= $dbh->prepare($str_query);

    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
    	$tasks=array();
        foreach($tmp as $t) {
            $task=new Task($t);
            $tasks[]=$task;
        }
        return $tasks;
    }

    /**
    * get num tasks
	*
	* @@@ check for user-rights
    */
    function getNumTasks() 
	{
		$prefix= confGet('DB_TABLE_PREFIX');
        $dbh = new DB_Mysql;
        $sth= $dbh->prepare("SELECT  COUNT(*) FROM {$prefix}item i, {$prefix}task t
            WHERE
                i.project = \"$this->id\"
            AND i.type=  ". ITEM_TASK . "
            AND i.state=1
            AND t.id= i.id
            AND t.status<4" );
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
        return $tmp[0]['COUNT(*)'];
    }


    /**
    * getComments($project=false)
    */
    function &getComments($order_by=NULL, $visible_only=true, $alive_only=true){
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');

        if(!$order_by) {
            $order_by="c.name";
        }
        require_once("db/class_comment.inc");
        $dbh = new DB_Mysql;

        $str_is_alive= $alive_only
        ? "AND i.state=1"
        : "";
        
        if($visible_only) {
            $str_query=
            "SELECT i.*, c.* from {$prefix}item i, {$prefix}comment c, {$prefix}projectperson upp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.project = $this->id
                AND upp.state = 1

                AND i.type = '".ITEM_COMMENT."'
                AND i.project = $this->id
                $str_is_alive
                AND ( i.pub_level >= upp.level_view
                      OR
                      i.created_by = {$auth->cur_user->id}
                )

                AND c.id = i.id
                AND c.task=0     /* ignore task-comments*/

            ORDER BY $order_by";
        }
        else {
            $str_query=
            "SELECT i.*, c.* from {$prefix}item i, {$prefix}comment c
            WHERE
                    i.type = '".ITEM_COMMENT."'
                AND i.project = $this->id
                $str_is_alive

                AND c.id = i.id
                AND c.task=0     /* ignore task-comments*/

            ORDER BY $order_by";

        }        


        $sth= $dbh->prepare($str_query);
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
    	$comments=array();
        foreach($tmp as $n) {
            $comment=new Comment($n);
            $comments[]= $comment;
        }
        return $comments;
    }


    /**
    * getIssues($project=false)
    */
    function &getIssues($order_by=NULL, $visible_only=true, $alive_only=true){

        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');


        if(!$order_by) {
            $order_by="iss.id";
        }

        require_once("db/class_issue.inc");
        $dbh = new DB_Mysql;

        $str_is_alive= $alive_only
            ? "AND state=1"
            : "";


        if($visible_only) {
            $str_query=
            "SELECT i.*, iss.* from {$prefix}item i, {$prefix}issue iss, {$prefix}projectperson upp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.project = $this->id
                AND upp.state = 1

                AND i.type = '".ITEM_ISSUE."'
                AND i.project = $this->id
                $str_is_alive

                AND ( i.pub_level >= upp.level_view
                      OR
                      i.created_by = {$auth->cur_user->id}
                )

                AND iss.id = i.id

                ORDER BY $order_by";
        }
        else {
            $str_query=
            "SELECT i.*, iss.* from {$prefix}item i, {$prefix}issue iss
            WHERE
                    i.type = '".ITEM_ISSUE."'
                AND i.project = $this->id
                $str_is_alive

                AND iss.id = i.id

            ORDER BY $order_by";
        }


        $sth= $dbh->prepare($str_query);
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
    	$issues=array();
        foreach($tmp as $n) {
            $i=new Issue($n);
            $issues[]= $i;
        }
        return $issues;
    }


    /**
    * get project-persons
    */
    function &getProjectPersons($order_by=NULL, $alive_only=true, $visible_only= true)
    {
        global $auth;
		$prefix = confGet('DB_TABLE_PREFIX');
        if(!$order_by) {
            $order_by="name";
        }

        $s_alive_only= $alive_only
            ? "AND i.state=1"
            : "";
            

        ### only visibile for current user ###
        if($visible_only) {
            $s_query=
            "SELECT i.*, pp.* from {$prefix}item i, {$prefix}projectperson pp, {$prefix}projectperson upp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.project = $this->id
                AND upp.state = 1

                AND i.type = '".ITEM_PROJECTPERSON."'
                AND i.project = $this->id
                $s_alive_only
                AND ( i.pub_level >= upp.level_view
                      OR
                      i.created_by = {$auth->cur_user->id}
                )
                AND pp.id = i.id
            ORDER BY $order_by";
        }
        
        ### all including deleted ###
        else {
            $s_query=
            "SELECT i.*, pp.* from {$prefix}item i, {$prefix}projectperson pp
            WHERE
                i.type = '".ITEM_PROJECTPERSON."'
                AND i.project = $this->id
                $s_alive_only
                AND i.id = pp.id
            ";        
        }
        require_once("db/class_projectperson.inc");
        
        $dbh = new DB_Mysql;


        $sth= $dbh->prepare($s_query);
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
    	$ppersons=array();
        foreach($tmp as $n) {
            $pperson=new ProjectPerson($n);
            $ppersons[]= $pperson;
        }
        return $ppersons;
    }


    /**
    * get changes
    */
    function &getChanges($order_by=NULL, $visible_only= false)
    {
        global $auth;
		$prefix = confGet('DB_TABLE_PREFIX');
        if(!$order_by) {
            $order_by="modified";
        }


        ### only visibile for current user ###
        if($visible_only) {
            $s_query=
            "SELECT i.* from 
                                    {$prefix}item i, 
                                    {$prefix}projectperson upp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.project = $this->id
                AND upp.state = 1

                AND i.project = $this->id

                AND i.id = pp.id
                AND i.id = t.id
                AND i.id = e.id
                AND i.id = c.id
                
                AND ( i.pub_level >= upp.level_view
                      OR
                      i.created_by = {$auth->cur_user->id}
                )
            ORDER BY $order_by";
        }
        
        ### all including deleted ###
        else {
            $s_query=
            "SELECT i.*  from 
                                {$prefix}item i
            WHERE
                i.project = $this->id
            ORDER BY $order_by
            ";        
        }
        require_once("db/class_projectperson.inc");

        
        $dbh = new DB_Mysql;


        $sth= $dbh->prepare($s_query);
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();

    	
    	$items= array();
        foreach($tmp as $n) {
            $item= new DbProjectItem($n);
            $items[]= $item;
        }
        return $items;
    }



    /**
    * get persons (team)
    */
    function &getPersons()
	{
        $ppersons= $this->getProjectPersons();
        $persons= array();
        foreach($ppersons as $pp) {
            $persons[]= new Person($pp->person);
        }
        return $persons;
    }


    /**
    * delete together with all belonging tasks
    */
    public function delete() {

        #--- first delete all tasks ---
        foreach($this->getTasks() as $t) {
            $t->delete();
        }

        #--- delete myself ---
        return parent::delete();
    }


    /**
    * returns link to project-view with short name
    */
    public function getLink($show_shortname=true) {
        global $PH;
        if($show_shortname) {
            return '<span class="item project">'.$PH->getLink('projView',$this->getShort(),array('prj'=>$this->id)).'<span>';
        }
        else {
            return '<span class="item project">'.$PH->getLink('projView',$this->name,array('prj'=>$this->id)).'<span>';
        }
    }


    /**
    * getCompanyLink
    */
    function getCompanyLink($show_long=false)
    {
        global $PH;
        if(!$this->company) {
            return "";
        }
        require_once("db/class_company.inc");
        $company= new Company($this->company);
        return "".$company->getLink($show_long).'';
    }

    /**
    * query project-objects from database
    */
    static function &queryFromDb($query_string) 
    {
        $dbh = new DB_Mysql;

        $sth= $dbh->prepare($query_string);

    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
    	$projects=array();
        foreach($tmp as $t) {
            $project=new Project($t);
            $projects[]=$project;
        }
        return $projects;
    }




    /**
    * get all open projects from db
    */
    public static function getAll($order_by=NULL, $state_min=2, $state_max=5)
    {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');

        if(!$order_by) {
             $order_by= "prio, name";
        }

        ### all projects ###
        if($auth->cur_user->user_rights & RIGHT_PROJECT_ASSIGN) {
            $str=
                "SELECT p.* from {$prefix}project p
                WHERE
                       p.status <= $state_max
                   AND p.status >= $state_min
                   AND p.state = 1
                ORDER BY $order_by";
        }

        ### only assigned projects ###
        else {
            $str=
                "SELECT p.* from {$prefix}project p, {$prefix}projectperson upp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1

                    AND p.id = upp.project
                    AND   p.status <= $state_max
                    AND   p.status >= $state_min
                    AND   p.state = 1

                ORDER BY $order_by
                ";
        }
        return self::queryFromDb($str);
    }

    /**
    * get projects from db
    */
    public static function getActive($order_by=NULL)
	{
		$prefix= confGet('DB_TABLE_PREFIX');
        return self::getAll($order_by, 1,4);

    }

    public static function getClosed($order_by=NULL){
        return self::getAll($order_by, 5,10);
    }
    public static function getTemplates($order_by=NULL){
        return self::getAll($order_by, -1,-1);
    }

    /**
    * get current project-person
    *
    * primarily used for validating project-rights
    */
    function &getCurrentProjectPerson()
    {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');

        require_once("db/class_projectperson.inc");
        $dbh = new DB_Mysql;
        $sth= $dbh->prepare(
            "SELECT i.*, pp.* from {$prefix}item i, {$prefix}projectperson pp
            WHERE
                    pp.person = {$auth->cur_user->id}
                AND pp.project = $this->id
                AND pp.state = 1

                AND i.id = pp.id
                AND i.state = 1
                AND i.type = '".ITEM_PROJECTPERSON."'"

        );
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
    	$ppersons=array();
        foreach($tmp as $n) {
            $pperson=new ProjectPerson($n);
            $ppersons[]= $pperson;
        }
        if(count($ppersons) >1 ){
            trace("Warning","internal error: person assigned twice to project");
            return NULL;
        }
        else if (!$ppersons) {
            trace("Warning","internal error: person is not assigned to project");
            return NULL;
        }
        return $ppersons[0];
    }

    /**
    * return the valid new pub
    *
    * aborts page, if current user may not create something in this project
    */
    public function getCurrentLevelCreate()
    {
        global $PH;
        if(!$pp= $this->getCurrentProjectPerson()) {
            $PH->abortWarning('insuffient rights',ERROR_BUG); ## user may never have reached this point
        }
        $new_level= $this->default_pub_level;
        if($new_level > $pp->level_create) {
            $new_level = $pp->level_create;
        }
        return $new_level;

    }


    public function validateEditItem(DbProjectItem $item)
    {
        global $PH;

        if(!$pp= $this->getCurrentProjectPerson()) {
            $PH->abortWarning("insuffient rights",ERROR_RIGHTS);
        }
        $l= $item->pub_level;
        if($item->created_by == $pp->person) {
            $l= PUB_LEVEL_OWNED;
        }
        if($l < $pp->level_edit) {
            $PH->abortWarning("insuffient rights",ERROR_RIGHTS);
        }
        return true;
    }


    /**
    * validate project can be viewed
    */
    public function validateView($state_min=2, $state_max=4)
    {
        global $auth;
        global $PH;
		$prefix= confGet('DB_TABLE_PREFIX');

        ### all projects ###
        if($auth->cur_user->user_rights & RIGHT_PROJECT_VIEWALL) {
            return true;
        }

        $str=
            "SELECT p.* from {$prefix}project p, {$prefix}projectperson upp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.state = 1

                AND p.id = upp.project
                AND p.id = $this->id
                AND   p.status <= $state_max
                AND   p.status >= $state_min
                AND   p.state = 1
        ";

  	    $projects= self::queryFromDb($str);
        if(count($projects) == 1) {
            return true;
        }
        else {
            $PH->abortWarning("insuffient rights",ERROR_RIGHTS);
        }
    }
}






?>
