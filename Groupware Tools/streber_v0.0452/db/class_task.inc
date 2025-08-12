<?php


global $task_fields;
$task_fields=array();
addProjectItemFields(&$task_fields);

foreach(array(
    new FieldInternal(array(    'name'=>'id',
        'default'=>0,
        'in_db_object'=>1,
        'in_db_item'=>1,
    )),
    new FieldString   (array('name'=>'name',
        'view_in_forms'=>true,
        'required'=>true
    )),
    new FieldString   (array('name'=>'short',
        'view_in_forms'=>true
    )),
    new FieldDate     (array('name'=>'date_start',
        'view_in_forms'=>true,
        'default'=>FINIT_TODAY
    )),
    new FieldDate     (array('name'=>'date_closed',
        'view_in_forms'=>true,
        'default'=>FINIT_NEVER
    )),
    new FieldOption   (array('name'=>'status',
        'view_in_forms'=>true,
        'default'=>3
    )),
    new FieldInt      (array('name'=>'prio',
        'view_in_forms'=>true,
        'default'=>3
    )),     # todo: default-status and prio should be project-setting!

    new FieldText     (array('name'=>'description',
        'view_in_forms'=>true
    )),
    new FieldBool(  array('name'=>'is_folder',
        'title'=>'show as folder (may contain other tasks)',
        'view_in_forms'=>true,
        'default'=>false
    )),
    new FieldPercentage(array('name'=>'completion',
        'view_in_forms'=>true,
        'default'=>FINIT_NEVER
    )),
    new FieldHidden(  array('name'=>'parent_task',
        'view_in_forms'=>true,
        'default'=>0
    )),
    new FieldTime(      array('name'=>'estimated',
        'view_in_forms'=>true
    )),
    new FieldHidden(  array('name'=>'issue_report'
    )),
    new FieldOption    (array('name'=>'label',
        'view_in_forms'=>true
    )),
    new FieldDate      (array('name'=>'date_due',
        'view_in_forms'=>true,
        'default'=>FINIT_NEVER
    )),
    new FieldDate      (array('name'=>'date_due_end',
        'view_in_forms'=>true,
        'default'=>FINIT_NEVER
    )),
    new FieldInternal      (array('name'=>'view_collapsed',
        'default'=>0
    )),

) as $f) {
    $task_fields[$f->name]=$f;
}




//====================================================================
// Task
//====================================================================
class Task extends DbProjectItem {

    public $level;              # level if child of parent-tasks
    public $type;

	//=== constructor ================================================
	function __construct ($id_or_array=false)
    {
        global $task_fields;
        $this->fields= &$task_fields;

        parent::__construct($id_or_array);
        $this->type= ITEM_TASK;
   	}

    #------------------------------------------------------------
    # getProject
    #------------------------------------------------------------
    function getProject()
    {
        require_once("db/class_project.inc");
        if(!$project= new Project($this->project)) {
            trace("WARNING","Task:getProject. project-id not set");
        }
        return $project;
    }

    #------------------------------------------------------------
    # getProjectLink
    #------------------------------------------------------------
    function getProjectLink()
    {
        $project= $this->getProject();
        return "<nobr>".$project->getLink().'</nobr>';
    }

    #---------------------------
    # get sub-tasks
    #---------------------------
    function getSubtasks($order_by=NULL) {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');

        $dbh = new DB_Mysql;
        if(!$order_by) {
            $order_by="t.name";
        }


        $sth= $dbh->prepare(
                "SELECT i.*, t.* from {$prefix}item i, {$prefix}task t, {$prefix}projectperson upp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1
                    AND upp.project = $this->project

                    AND i.type = '".ITEM_TASK."'
                    AND i.state = 1
                    AND ( i.pub_level >= upp.level_view
                          OR
                          i.created_by = {$auth->cur_user->id}
                    )

                    AND t.id = i.id
                    AND t.status <= 4
                    AND t.parent_task= $this->id

                ORDER BY $order_by"         );
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
        $sub_tasks=array();
        foreach($tmp as $t) {
            $sub_tasks[]=new Task($t);
        }
        return $sub_tasks;
    }

    #---------------------------
    # get nume sub-tasks
    #---------------------------
    function getNumSubtasks()
    {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');

        $dbh = new DB_Mysql;
        $sth= $dbh->prepare(
        "SELECT COUNT(*) from {$prefix}item i, {$prefix}task t, {$prefix}projectperson upp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1
                    AND upp.project = $this->project

                    AND i.type = '".ITEM_TASK."'
                    AND i.state = 1
                    AND ( i.pub_level >= upp.level_view
                          OR
                          i.created_by = {$auth->cur_user->id}
                    )

                    AND t.id = i.id
                    AND t.status <= 4
                    AND t.parent_task= $this->id"
        );
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
        return $tmp[0]['COUNT(*)'];
    }

    #----------------------------
    # get folder
    #----------------------------
    # returns array of tasks
    function getFolder() {
        $folder=array();
        $cur_task=$this;
        while(intval($cur_task->parent_task)) {
            $cur_task=new Task(intval($cur_task->parent_task));
            $folder[]=$cur_task;
        }
        return array_reverse($folder);
    }

    #----------------------------
    # get folder as Links
    #----------------------------
    # returns array of tasks
    function getFolderLinks() {
        measure_start('col_TaskFolderlinks');
        global $PH;
        $folder=$this->getFolder();
        $buffer="";
        $delimiter='';
        foreach($folder as $f) {
            $buffer.=$delimiter;
            $buffer.=$PH->getLink('taskView',$f->getShort(),array('tsk'=>$f->id));
            $delimiter='>';
        }
        measure_stop('col_TaskFolderlinks');
        if($buffer) {
            return $buffer;
        }
        
        
        
        
        else {
            return NULL;
        }
    }

    #------------------------------------------------------------------------------------------------
    # ungroup subtasks
    # - when deleting a folder (or changing it into a normal task) we need to ungroup the subtasks
    # - returns number of ungrouped tasks
    #------------------------------------------------------------------------------------------------
    public function ungroupSubtasks() {
        $tasks=$this->getSubtasks();
        foreach($tasks as $t) {
            $t->parent_task= $this->parent_task;
            $t->update();
        }
        return count($tasks);
    }

    /**
    * sort task as intended list
    *
    * NOTE: this function is really slow (in long is may take more than 50%
    * of complete computation time!) Sooner or later this should be optimized.
    */
    public static function &sortHierarchical(&$taskfolders) {

        # sort taskfolders #
        $tmp_folder_list=array();
        foreach($taskfolders as $f) {
            if(!$f->parent_task) {
                $tmp_folder_list[]=$f;
                self::sort_folders(&$taskfolders, &$f, 1,&$tmp_folder_list);
            }
            else {
                $dummy=true;;
            }
        }
        return $tmp_folder_list;
    }

    private static function sort_folders(&$taskfolders, Task &$task,$level,&$tmp_folder_list) {
        for($i=0; $i< count($taskfolders); $i++) {
            $f2= $taskfolders[$i];
            if($f2->parent_task == $task->id) {
                $f2->level= $level;
                $tmp_folder_list[]=$f2;
                self::sort_folders(&$taskfolders, $f2,$level+1, &$tmp_folder_list);
            }
            else {
                $dummy=true;
            }
        }
    }

    #----------------------------------------------------
    # getComments()
    #----------------------------------------------------
    function &getComments($order_by=NULL){
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');
        if(!$order_by) {
            $order_by="c.name";
        }
        require_once("db/class_comment.inc");
        $dbh = new DB_Mysql;
        $sth= $dbh->prepare(
            "SELECT i.*, c.* from {$prefix}item i, {$prefix}comment c, {$prefix}projectperson upp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.project = $this->project
                AND upp.state = 1

                AND i.type = '".ITEM_COMMENT."'
                AND i.project = $this->project
                AND i.state = 1
                AND ( i.pub_level >= upp.level_view
                      OR
                      i.created_by = {$auth->cur_user->id}
                )

                AND c.id = i.id
                AND c.task=$this->id    /* only  task-comments*/

            ORDER BY $order_by"
        );
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
    	$comments=array();
        foreach($tmp as $n) {
            $comment=new Comment($n);
            $comments[]= $comment;
        }
        return $comments;
    }


    function &getNumComments($order_by=NULL)
    {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');
        if(!$order_by) {
            $order_by="c.name";
        }
        require_once("db/class_comment.inc");
        $dbh = new DB_Mysql;
        $sth= $dbh->prepare(
            "SELECT COUNT(*) from {$prefix}item i, {$prefix}comment c, {$prefix}projectperson upp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.project = $this->project
                AND upp.state = 1

                AND i.type = '".ITEM_COMMENT."'
                AND i.project = $this->project
                AND i.state = 1
                AND ( i.pub_level >= upp.level_view
                      OR
                      i.created_by = {$auth->cur_user->id}
                )

                AND c.id = i.id
                AND c.task=$this->id    /* only  task-comments*/

            ORDER BY $order_by"
        );
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
        return $tmp[0]['COUNT(*)'];
    }


    /**
    * sum up visible(!) efforts for tasks (including sub-tasks)
    *
    * since this functions obeys pub_level the sum might be less than expected
    *
    * it also checks efforts of deleted subtasks!
    */
    function &getSumEfforts()
    {
        global $auth;
        $sum=0;

		$prefix= confGet('DB_TABLE_PREFIX');
        require_once("db/class_effort.inc");
        $dbh = new DB_Mysql;
        $sth= $dbh->prepare(
            "SELECT SUM(unix_timestamp(e.time_end) - unix_timestamp(e.time_start)) from {$prefix}item i, {$prefix}effort e, {$prefix}projectperson upp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.project = $this->project
                AND upp.state = 1

                AND i.type = '".ITEM_EFFORT."'
                AND i.state=1 
                AND i.project = $this->project
                AND ( i.pub_level >= upp.level_view
                      OR
                      i.created_by = {$auth->cur_user->id}
                )

                AND e.id = i.id
                AND e.task=$this->id    /* only  task-efforts*/
            "
        );
    	$sth->execute("",1);
    	$tmp= $sth->fetch_row();
    	if($tmp) {
    	    $sum+= $tmp[0];
    	}

        ### recursively go through sub-tasks ###
        if($subtasks=$this->getSubtasks()) {
            foreach($subtasks as $st){
                #echo " >>%% recursive into $st->name";
                $sum+= $st->getSumEfforts();
            }
        }

        #echo "%% effort of '$this->name'=$sum<br>";
        return $sum;
    }

    /**
    * sum up visible(!) efforts for tasks (including sub-tasks)
    *
    * since this functions obeys pub_level the sum might be less than expected
    *
    * it also checks efforts of deleted subtasks!
    */
    /*
    function &getSubTasks($show_folders=true, $order_by=NULL, $status_min=2, $status_max=4, $visible_only=true, $alive_only=true)
    {
        global $auth;
        $sum=0;

		$prefix= confGet('DB_TABLE_PREFIX');

        $dbh = new DB_Mysql;
        $sth= $dbh->prepare(
            "SELECT SUM(unix_timestamp(e.time_end) - unix_timestamp(e.time_start)) from {$prefix}item i, {$prefix}effort e, {$prefix}projectperson upp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.project = $this->project
                AND upp.state = 1

                AND i.type = '".ITEM_TASK."'
                AND i.state=1 
                AND i.project = $this->project
                AND ( i.pub_level >= upp.level_view
                      OR
                      i.created_by = {$auth->cur_user->id}
                )

                AND e.id = i.id
                AND e.task=$this->id    
            "
        );
    	$sth->execute("",1);
    	$tmp= $sth->fetch_row();
    	if($tmp) {
    	    $sum+= $tmp[0];
    	}

        ### recursively go through sub-tasks ###
        if($subtasks=$this->getSubtasks()) {
            foreach($subtasks as $st){
                #echo " >>%% recursive into $st->name";
                $sum+= $st->getSumEfforts();
            }
        }

        #echo "%% effort of '$this->name'=$sum<br>";
        return $sum;
    }
    */


    #----------------------------------------------------
    # getHomeTasks($project=false)
    #----------------------------------------------------
    public static function &getHomeTasks($order_by=NULL){
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');
        $dbh = new DB_Mysql;
		

        if(!$order_by) {
            $order_by=" is_folder DESC,
                        parent_task,
                        prio ASC,
                        project,
                        name";
        }

        #--- select all visible tasks in home ---
        $sth= $dbh->prepare(
                "SELECT  i.*, t.* from {$prefix}item i, {$prefix}task t, {$prefix}project p, {$prefix}projectperson upp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1
                    AND upp.project = p.id   /* all user projectpersons */

                    AND p.show_in_home = 1   /* all user projects */
                    AND p.state = 1
                    AND p.status >=1
                    AND p.status <=4

                    AND p.id = i.project

                    AND i.type = '".ITEM_TASK."'
                    AND t.is_folder = 0
                    AND i.pub_level >= upp.level_view
                    AND i.state = 1
                    AND i.project = p.id

                    AND t.id= i.id
                    AND t.status < 4

                ORDER BY $order_by
                " );
        	$sth->execute("",1);
        	$tmp=$sth->fetchall_assoc();

    	$tasks=array();
        foreach($tmp as $t) {

            unset($t['due_sort']);
            $task=new Task($t);

            $tasks[]=$task;
        }
        return $tasks;
    }
}


?>
