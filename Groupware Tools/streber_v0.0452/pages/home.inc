<?php


require_once("db/class_task.inc");
require_once("db/class_project.inc");
require_once("render/render_list.inc");
require_once("lists/list_tasks.inc");




#---------------------------------------------------------------------------
# home
#---------------------------------------------------------------------------
function home() {
    global $PH;
    global $auth;

    ### create from handle ###
    $PH->defineFromHandle(array());

    ### set up page ####
    {

        $page= new Page();
    	$page->cur_tab='home';
    	$page->crumbs=array(
    		#"Home",
    	);
    	$page->options=array(
            new NaviOption(array('target_id'=>'home',         'name'=>'Today')),
            #new NaviOption(array('target_id'=>'discussions',  'name'=>'Discussions')),
            #new NaviOption(array('target_id'=>'comments',        'name'=>'Comments')),
            #new NaviOption(array('target_id'=>'reports',      'name'=>'Reports')),
            new NaviOptionSeparator(),
            #new NaviOption(array('target_id'=>'customize',    'name'=>'Customize')),
            #new NaviOption(array('target_id'=>'profile',      'name'=>'Profile')),
            #new NaviOptionSeparator(),
            new NaviOptionWiki()
    	);

        $page->title=$auth->cur_user->name;
        $page->type="At Home";
        $page->title_minor=date('F, jS',time());
        echo(new PageHeader);
    }
    echo (new PageContentOpen_Columns);
    measure_stop('init2');


    #--- help block ------------
    /*
    {
        $block=new PageBlock(array('title'=>'Help'));
        $block->render_blockStart();
        echo "<div class=text>";
        echo "<p class=text>Welcome to streber. Help us to improve it: Play around and
        test. If you have any ideas, use the <a class='extern' href='http://wiki.pixtur.de/'>wiki</a>-link and add your comments.</p>";
        echo "</div>";

        $block->render_blockEnd();
    }
    */

    #--- functions block ------------
    {
        $block=new PageBlock(array('title'=>'Functions'));
        $block->render_blockStart();
        echo "<div class=text>";

        ### write functions ###
        $function_links=array();
        $function_links[]=$PH->getLink('projNew','',array());
        $function_links[]=$PH->getLink('companyNew','',array());
        $function_links[]=$PH->getLink('personNew','',array());
        $function_links[]=$PH->getLink('personViewEfforts','View your efforts',array('person'=>$auth->cur_user->id));
        $function_links[]=$PH->getLink('personEdit','Edit your profile',array('person'=>$auth->cur_user->id));

        if($function_links) {
            echo "<ul>";
            foreach($function_links as $f){
                if($f) {
                    echo "<li>$f" ;
                }
            }
            echo "</ul>";
        }
        echo "</div>";

        $block->render_blockEnd();
    }

	#--- list projects -------------------------------
	{
        measure_start('get_projects');

    	$projects= Project::getActive();
        measure_stop('get_projects');
        measure_start('list_projects');

		$list= new ListBlock("projects", "bg_projects");
		$list->title="Your projects";

		$list->add_col( new ListBlockCol(array(
			'key'=>'_select_col_',
			'name'=>"S",
			'tooltip'=>"This is a tooltip",
            'style'=>'narrow',
		)));
		$list->add_col( new ListBlockColPrio(array(
			'key'=>'prio',
			'name'=>"P",
			'tooltip'=>"This is a tooltip",
			'sort'=>0,
            'style'=>'narrow',

		)));
		/*$list->add_col( new ListBlockColStatus(array(
			'key'=>'status',
			'name'=>"S",
			'tooltip'=>"Task-Status",
			'sort'=>0
		)));*/
        /*$list->add_col( new ListBlockColHtml(array(
			'key'=>'name',
			'name'=>"Name",
			'tooltip'=>"Task name. More Details as tooltips",
			'width'=>'90%',
			'sort'=>0,
			'format'=>'<a href="index.php?go=projView&prj={?id}">{?name}</a>'
		)));*/
    	$list->add_col( new ListBlockColMethod(array(
    		'name'=>"Company",
    		'tooltip'=>"Company",
    		'func'=>'getCompanyLink',
    	)));
		$list->add_col(new ListBlockColMethod(array(
			'name'=>'Project',
			'func'=>'getLink'
		)));
		/*$list->add_col(new ListBlockColMethod(array(
			'key'=>'project',
			'name'=>'Project',
			'func'=>'getProjectLink'
		)));*/

        $list->add_col( new ListBlockCol_OpenTasks);

        #--- functions -------
        $list->add_function(new ListFunction(array(
            'target'=>$PH->getPage('projEdit')->id,
            'name'  =>'Edit',
            'id'    =>'projEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $list->add_function(new ListFunction(array(
            'target'=>$PH->getPage('effortNew')->id,
            'name'  =>'Log hours for a project',
            'id'    =>'effortNew',
            'context_menu'=>'submit',
            'icon' =>'loghours',
        )));

        #$list->show_functions=false;


        $page->crumbs=array(    ### ????
            $PH->getLink('projList')
        );

        ### can user create projects? ###
        if($auth->cur_user->user_rights & RIGHT_PROJECT_CREATE) {
            $list->no_items_html=$PH->getLink('projNew','',array('person'=>$auth->cur_user->id));
        }
        else {
            $list->no_items_html="not assigned to a project";
        }
        $list->render_list($projects);
		#$list->render_header();
		#$list->render_thead();
		#foreach($projects as $p) {
		#	$list->render_trow(&$p);
		#}
		#$list->render_tfoot();
	}
    measure_stop('list_projects');

    echo(new PageContentNextCol);

	#--- list tasks -------------------------------------------------------------
   	{
        measure_start('get_tasks');

        $order_str=get('sort_home_tasks');

        $tasks= Task::getHomeTasks($order_str);

        measure_stop('get_tasks');
        measure_start('list_tasks');


        #--- columns ---
		$list_tasks= new ListBlock("tasks", "bg_projects");
        $list_tasks->show_functions=false;
        $list_tasks->no_items_html="you have no open tasks";

		$list_tasks->title="Open tasks";
		$list_tasks->add_col( new ListBlockCol(array(
			'key'=>'_select_col_',
			'name'=>"S",
			'tooltip'=>"This is a tooltip",
		)));
		$list_tasks->add_col( new ListBlockColPrio(array(
			'key'=>'prio',
			'name'=>"P",
			'tooltip'=>"This is a tooltip",
			'sort'=>1,
            'width'=>1,
		)));
		$list_tasks->add_col( new ListBlockColStatus(array(
			'key'=>'status',
			'name'=>"S",
			'tooltip'=>"Task-Status",
			'sort'=>0,
            'width'=>1,
		)));
		$list_tasks->add_col(new ListBlockColMethod(array(
			'key'=>'project',
			'name'=>'Project',
			'func'=>'getProjectLink'
		)));
        $list_tasks->add_col(new ListBlockColMethod(array(
			'key'=>'parent_task',
			'name'=>'Folder',
			'func'=>'getFolderLinks'
		)));
		/*$list_tasks->add_col( new ListBlock_ColHtml(array(
			'key'=>'name',
			'name'=>"Task",
			'tooltip'=>"Task name. More Details as tooltips",
			'width'=>'90%',
			'sort'=>0,
			'format'=>'<a href="index.php?go=taskView&tsk={?id}">{?name}</a>'
		)));*/

        $list_tasks->add_col( new ListBlockCol_TaskLabel);
        $list_tasks->add_col( new ListBlockCol_TaskName);
	    $list_tasks->add_col( new ListBlockCol_TaskCreatedBy(array('use_short_names'=>false,'indention'=>true)));

        $list_tasks->add_col( new listBlockColDate(array(
			'key'=>'date_start',
            'name'=>'Started'
        )));



		$list_tasks->add_col( new ListBlockCol_TimeDue);
		$list_tasks->add_col( new ListBlockColTime(array(
			'key'=>'estimated',
			'name'=>"Est.",
			'tooltip'=>"Estimated time in hours",
		)));

        /* functions don't work
		$list_tasks->add_col( new ListBlockColHtml(array(
			'name'=>"FN",
			'tooltip'=>"Function for tasks.",
			'width'=>'1',
			'sort'=>0,
			'format'=>'<nobr><a href="index.php?go=tasksDelete&tsk={?id}" title="Delete this task"> X </a>'.
            '<a href="index.php?go=taskEdit&tsk={?id}" title="Edit this task"> E </a>'.
            '<a href="index.php?go=tasksComplete&tsk={?id}" title="Mark as done"> Ø </a></nobr>'
		)));
        */

        #--- functions -------
        $list_tasks->add_function(new ListFunction(array(
            'target'=>$PH->getPage('taskEdit')->id,
            'name'  =>'Edit',
            'id'    =>'taskEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $list_tasks->add_function(new ListFunction(array(
            'target'=>$PH->getPage('tasksComplete')->id,
            'name'  =>'status->Completed',
            'id'    =>'tasksCompleted',
            'icon'  =>'complete',
            'context_menu'=>'submit',
        )));
        $list_tasks->add_function(new ListFunction(array(
            'target'=>$PH->getPage('tasksApproved')->id,
            'name'  =>'status->Approved',
            'id'    =>'tasksApproved',
            'icon'  =>'complete',
            'context_menu'=>'submit',
        )));
        $list_tasks->add_function(new ListFunction(array(
            'target'=>$PH->getPage('tasksDelete')->id,
            'name'  =>'Delete',
            'id'    =>'tasksDelete',
            'icon'  =>'delete',
            'context_menu'=>'submit',
        )));



		$list_tasks->render_header();
        if($tasks || !$list_tasks->no_items_html) {
    		$list_tasks->render_thead();
            if($tasks) {
                $count_estimated=0;
        		foreach($tasks as $t) {
                    $style="";
                    $style.=($t->is_folder)
                        ?' isFolder'
                        :'';
        
                    ### done ###
                    if($t->status>3) {
                        $style.='isDone';
                    }
                    else {
                        $count_estimated+=$t->estimated;
                    }
        			$list_tasks->render_trow(&$t,$style);
                }
            }
            $list_tasks->summary=count($tasks)." tasks with estimated $count_estimated hours of work";
    		$list_tasks->render_tfoot();
        }
        else {
            $list_tasks->render_tfoot_empty();
        }



        measure_stop('list_tasks');
	}

    echo (new PageContentClose);
	echo (new PageHtmlEnd);
}

?>