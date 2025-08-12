<?php

require_once("db/class_task.inc");
require_once("db/class_project.inc");
require_once("db/class_projectperson.inc");
require_once("render/render_list.inc");
require_once("lists/list_taskfolders.inc");
require_once("lists/list_projects.inc");
require_once("lists/list_comments.inc");
require_once("lists/list_tasks.inc");
require_once("lists/list_project_team.inc");
require_once("lists/list_efforts.inc");


/**
* build the navigation-options for project view
*
* NOTE since the current project-id is required for this
* we can do this with a global array.
*/
function build_projView_options($project) {
     return array(
        new NaviOption(array('target_id'=>'projView',         'name'=>'Overview', 'target_params'=>array('prj'=>$project->id ))),
        new NaviOption(array('target_id'=>'projViewIssues',   'name'=>'Issues',   'target_params'=>array('prj'=>$project->id ))),
        new NaviOption(array('target_id'=>'projViewEfforts',  'name'=>'Efforts',  'target_params'=>array('prj'=>$project->id ))),
        new NaviOption(array('target_id'=>'projViewTasksClosed',  'name'=>'Closed',  'target_params'=>array('prj'=>$project->id ))),
        new NaviOption(array('target_id'=>'projViewChanges',  'name'=>'History',  'target_params'=>array('prj'=>$project->id ))),
        new NaviOptionWiki()
     );
}
 


/**
* list active projects
*/
function ProjList()
{
    global $PH;
    global $projList_options;
    global $auth;


    ### create from handle ###
    $PH->defineFromHandle();

    ### set up page and write header ####
    {
        $page= new Page();
    	$page->cur_tab='projects';
        $page->title="Your Active Projects";
        if(!($auth->cur_user->user_rights & RIGHT_PROJECT_EDIT)) {
            $page->title_minor="relating to ". $page->title_minor=$auth->cur_user->name;
        }
        else {
            $page->title_minor="admin view";
        }
        $page->type="List";


        # PHP-Bug?
        # ??? actually the list of options should be share for all projList-views BUT
        #    I didn't find a way to define globally in this file (always causing can't create default
        #   object from empty value ???
        #
        $page->options=array(
            new NaviOption(array('target_id'=>'projList',           'name'=>'Active')),
            new NaviOption(array('target_id'=>'projListClosed',     'name'=>'Closed')),
            new NaviOptionSeparator(),
            new NaviOption(array('target_id'=>'projListTemplates',  'name'=>'Templates')),
            new NaviOptionSeparator(),
            new NaviOptionWiki()
        );

    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

	#--- list projects --------------------------------------------------------
	{

        $order_str=get('sort_'.$PH->cur_page->id."_projects");
    	$projects=Project::getActive($order_str);
        $list= new ListBlock_projects();
        $list->title= $page->title;
        if($auth->cur_user->user_rights & RIGHT_PROJECT_CREATE) {
            $warning="";
            if(! ($auth->cur_user->user_rights & RIGHT_PROJECT_VIEWALL)) {
                $warning=" <b>NOTE</b>: Some projects are hidden from your view. Please ask an administrator to adjust you rights to avoid double-creation of projects";
            }
            
            $list->no_items_html= $PH->getLink('projNew','create new project',array('person'=>$auth->cur_user->id)). $warning;
        }
        else {
            $list->no_items_html= "not assigned to a project";
        }
        $list->render_list(&$projects);

	}


    echo(new PageContentClose);
	echo(new PageHtmlEnd);
}


#---------------------------------------------------------------------------
# listProjects (closed)
#---------------------------------------------------------------------------
function ProjListClosed(){
    global $PH;
    global $projList_options;
    global $auth;


    ### create from handle ###
    $PH->defineFromHandle();

    ### set up page and write header ####
    {
        $page= new Page();
    	$page->cur_tab='projects';
        $page->title="Your Closed Projects";
        if(!($auth->cur_user->user_rights & RIGHT_PROJECT_EDIT)) {
            $page->title_minor="relating to ". $page->title_minor=$auth->cur_user->name;
        }
        else {
            $page->title_minor="admin view";
        }
        $page->type="List";


        # PHP-Bug?
        # ??? actually the list of options should be share for all projList-views BUT
        #    I didn't find a way to define globally in this file (always causing can't create default
        #   object from empty value ???
        #
        $page->options=array(
            new NaviOption(array('target_id'=>'projList',           'name'=>'Active')),
            new NaviOption(array('target_id'=>'projListClosed',     'name'=>'Closed')),
            new NaviOptionSeparator(),
            new NaviOption(array('target_id'=>'projListTemplates',  'name'=>'Templates')),
            new NaviOptionSeparator(),
            new NaviOptionWiki()
        );

    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

	#--- list projects --------------------------------------------------------
	{
        $order_str=get('sort_'.$PH->cur_page->id."_projects");
    	$projects=Project::getClosed($order_str);

        $list= new ListBlock_projects();
        unset($list->functions['effortNew']);
        unset($list->functions['projNew']);
        
        $list->title= $page->title;
        $list->render_list(&$projects);

	}


    echo(new PageContentClose);
	echo(new PageHtmlEnd);
}

#---------------------------------------------------------------------------
# listProjects (Templates)
#---------------------------------------------------------------------------
function ProjListTemplates(){
    global $PH;
    global $projList_options;
    global $auth;

    ### create from handle ###
    $PH->defineFromHandle();

    ### set up page and write header ####
    {
        $page= new Page();
    	$page->cur_tab='projects';
        $page->title="Project Templates";
        if(!($auth->cur_user->user_rights & RIGHT_PROJECT_EDIT)) {
            $page->title_minor="relating to ". $page->title_minor=$auth->cur_user->name;
        }
        else {
            $page->title_minor="admin view";
        }
        $page->type="List";


        # PHP-Bug?
        # ??? actually the list of options should be share for all projList-views BUT
        #    I didn't find a way to define globally in this file (always causing can't create default
        #   object from empty value ???
        #
        $page->options=array(
            new NaviOption(array('target_id'=>'projList',           'name'=>'Active')),
            new NaviOption(array('target_id'=>'projListClosed',     'name'=>'Closed')),
            new NaviOptionSeparator(),
            new NaviOption(array('target_id'=>'projListTemplates',  'name'=>'Templates')),
            new NaviOptionSeparator(),
            new NaviOptionWiki()
        );

    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

	#--- list projects --------------------------------------------------------
	{
        $order_str=get('sort_'.$PH->cur_page->id."_projects");
    	$projects=Project::getTemplates($order_str);

        $list= new ListBlock_projects();
        unset($list->functions['effortNew']);
        unset($list->functions['projNew']);

        $list->title= $page->title;
        $list->render_list(&$projects);

	}


    echo(new PageContentClose);
	echo(new PageHtmlEnd);
}


#---------------------------------------------------------------------------
# view Project
#---------------------------------------------------------------------------
function ProjView() {
    global $PH;

	### get current project ###
    $id=getOnePassedId('prj','projects_*');
    $project=new Project($id);
	if(!$project) {
        $PH->abortWarning("invalid project-id");
		return;
	}

    ### validate that project is visible to user ###
    $project->validateView();

    ### define from-handle ###
    $PH->defineFromHandle(array('prj'=>$project->id));

    ### set up page ####
    {
        $page= new Page();
    	$page->cur_tab='projects';
        $page->title=$project->name;
        $page->title_minor="Overview";
        $page->type="Project";
        
        ### page functions ###
        $page->add_function(new PageFunction(array(
            'target'=>'projEdit',
            'params'=>array('prj'=>$project->id),
            'icon'=>'edit',
            'tooltip'=>'Edit this project',
        )));
        $page->add_function(new PageFunction(array(
            'target'=>'projAddPerson',
            'params'=>array('prj'=>$project->id),
            'icon'=>'add',
            'tooltip'=>'Add person as team-member to project',
        )));
        $page->add_function(new PageFunction(array(
            'target'=>'taskNew',
            'params'=>array('prj'=>$project->id,'add_issue'=>1),
            'icon'=>'new',
            'tooltip'=>'Create task with issue-report',
            'name'=>'Add Bugreport'
        )));
        $page->add_function(new PageFunction(array(
            'target'=>'effortNew',
            'params'=>array('prj'=>$project->id),
            'icon'=>'loghours',
            'tooltip'=>'Book effort for this project',
            'name'=>'log'
        )));




        ### breadcrumbs (distinguish active/closed projects ###
    	if($project->status > 3) {
            $page->crumbs=array(
                $PH->getLink('projListClosed'),
        		$PH->getLink('projView',$project->name, array('prj'=>$project->id)),
        	);
        }
        else {
            $page->crumbs=array(
                $PH->getLink('projList'),
        		$PH->getLink('projView',$project->name, array('prj'=>$project->id)),
        	);
        }

        
        $page->options= build_projView_options($project);


    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen_Columns);
    measure_stop('init2');
    measure_start('info');

    #--- write info-block ------------
    {
        $block=new PageBlock(array('title'=>'Summary'));
        $block->render_blockStart();
        echo "<div class=text>";
        if($project->description) {
            $diz=preg_replace("/\n\r/","<br>#",$project->description);
            echo "$diz";
        }
        if($project->company) {
            require_once("db/class_company.inc");
            $company= new Company($project->company);
            echo "<p><label>Client:</label>". $project->getCompanyLink(true) ."</p>";
            if($company->phone) {
                echo "<p><label>Phone:</label>". $company->phone."</p>";
            }
            if($company->email) {
                echo "<p><label>Mail:</label>". url2LinkMail($company->email)."</p>";
            }
        }
        echo "</div>";

        $block->render_blockEnd();
    }




    measure_stop('info');
    measure_start('folders');


	#--- list folders -----------------------------------------------------------
    $list= new ListBlock_taskFolders($project);
    $list->render();

    #--- list team -----------------------------------------------------------
    $team_members=$project->getProjectPersons();
    $list= new ListBlock_projectTeam();
    $list->title='Team members';
    $list->show_icons=true;
    $list->render_list(&$team_members);

    echo(new PageContentNextCol);
    measure_stop('folders');

    #--- list tasks -------------------------------------------------------------
    {
        /**
        * Note: Listing the tasks as hierarchically is still very slow.
        * this should be optimized some day.
        */

        $list_tasks=new ListBlock_tasks();
        $liststyle=get("liststyle_" . $PH->cur_page->id . "_" . $list_tasks->id);
        

        $list_tasks->title="All open tasks";

        
        
        if($liststyle=='list') {
            $flag_tree=false;
            $flag_list=true;
        }
        else {
            $flag_tree=true;
            $flag_list=false;
        }

        ### list style options ###
        $list_tasks->add_blockFunction(new BlockFunction(array(
            'target'=>'changeListStyle',
            'key'=>'list',
            'name'=>'List',
            'params'=>array(
                'style'=>'list', 
                'block_id'=>$list_tasks->id,
                'page_id'=>$PH->cur_page->id,
             ),
            'active'=>$flag_list,           
        )));
        $list_tasks->add_blockFunction(new BlockFunction(array(
            'target'=>'changeListStyle',
            'key'=>'tree',
            'name'=>'Tree',
            'params'=>array(
                'style'=>'tree', 
                'block_id'=>$list_tasks->id,
                'page_id'=>$PH->cur_page->id,
            ),
            'active'=>$flag_tree,
        )));
        
        if($liststyle=='tree') {
            measure_start("getting_tasks1");
            $tasks_unsorted= $project->getTasks();
            measure_stop("getting_tasks1");
            measure_start("getting_tasks2");
            $tasks= Task::sortHierarchical(&$tasks_unsorted);
            measure_stop("getting_tasks2");
            measure_start("rendering_list");

            #unset($list_tasks->columns['label']);
            unset($list_tasks->columns['parent_task']);
            unset($list_tasks->columns['date_closed']);

            $list_tasks->render_list(&$tasks);
        
            measure_stop('rendering_list');
        }
        else {
            $order_str=get("sort_".$PH->cur_page->id ."_tasks");
            $tasks_unsorted= $project->getTasks(false,$order_str);

            $list_tasks->title="All open tasks";
            $list_tasks->no_items_html= 
                 $PH->getLink('taskNew','',array('prj'=>$project->id))
                ." or "
                .$PH->getLink('taskNewFolder','New Folder',array('prj'=>$project->id));
                
            #unset($list_tasks->columns['label']);
            #unset($list_tasks->columns['folder']);
            unset($list_tasks->columns['date_closed']);

            $list_tasks->render_list(&$tasks_unsorted);

        }
    }

    #--- list comments -------------------------------------------------------------
    {
        $comments= $project->getComments();
        $list_comments= new ListBlock_comments;
        $list_comments->title="Comments on project";
        $list_comments->title_append_hidden=
            count($comments)
            ? "(".count($comments).")"
            : '';
        $list_comments->no_items_html=$PH->getLink('commentNew','',array('prj'=>$project->id));
        $list_comments->render_list(&$comments);
    }

    ### HACKING: 'add new task'-field ###
    $PH->go_submit='taskNew';
    echo '<input type="hidden" name="prj" value="'.$project->id.'">';

    echo (new PageQuickNew);

    #echo "<a href=\"javascript:document.my_form.go.value='tasksMoveToFolder';document.my_form.submit();\">move to task-folder</a>";
    echo (new PageContentClose);
	echo (new PageHtmlEnd());
}


#---------------------------------------------------------------------------
# view Project Efforts
#---------------------------------------------------------------------------
function ProjViewEfforts() {
    global $PH;

	### get current project ###
    $id=getOnePassedId('prj','projects_*');
    if(!$project=new Project($id)) {
        $PH->abortWarning("invalid project-id");
		return;
	}


    ### create from handle ###
    $PH->defineFromHandle(array('prj'=>$project->id));

    ### set up page ####
    {
        $page= new Page();
    	$page->cur_tab='projects';
        $page->title="Project Efforts";
        $page->title_minor=$project->name;
        $page->type="Project";

        ### breadcrumbs (distinguish active/closed projects ###
    	if($project->status > 3) {
            $page->crumbs=array(
                $PH->getLink('projListClosed'),
        		$PH->getLink('projView',$project->name, array('prj'=>$project->id)),
        	);
        }
        else {
            $page->crumbs=array(
                $PH->getLink('projList'),
        		$PH->getLink('projView',$project->name, array('prj'=>$project->id)),
        	);
        }
        $page->options= build_projView_options($project);
     



    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen_Columns);

    echo(new PageContentNextCol);


    #--- list efforts --------------------------------------------------------------------------
    {
        $order_by=get('sort_'.$PH->cur_page->id."_efforts");

        $efforts= $project->getEfforts($order_by);
        $list= new ListBlock_efforts();
        $list->render_list(&$efforts);
	}


    ### HACKING: 'add new task'-field ###
    $PH->go_submit='taskNew';
    echo '<input type="hidden" name="prj" value="'.$id.'">';

    #echo (new PageQuickNew);

    #echo "<a href=\"javascript:document.my_form.go.value='tasksMoveToFolder';document.my_form.submit();\">move to task-folder</a>";
    echo (new PageContentClose);
	echo (new PageHtmlEnd());
}



#---------------------------------------------------------------------------
# view Project Closed tasks
#---------------------------------------------------------------------------
function ProjViewTasksClosed() {
    global $PH;

	### get current project ###
    $id=getOnePassedId('prj','projects_*');
    if(!$project=new Project($id)) {
        $PH->abortWarning("invalid project-id");
		return;
	}

    ### create from handle ###
    $PH->defineFromHandle(array('prj'=>$project->id));

    ### set up page ####
    {
        $page= new Page();
    	$page->cur_tab='projects';
        $page->title="Closed Tasks";
        $page->title_minor=$project->name;
        $page->type="Project";

        ### breadcrumbs (distinguish active/closed projects ###
    	if($project->status > 3) {
            $page->crumbs=array(
                $PH->getLink('projListClosed'),
        		$PH->getLink('projView',$project->name, array('prj'=>$project->id))
        	);
        }
        else {
            $page->crumbs=array(
                $PH->getLink('projList'),
        		$PH->getLink('projView',$project->name, array('prj'=>$project->id))
        	);
        }
        $page->options= build_projView_options($project);


    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

    #echo(new PageContentNextCol);


    #--- list tasks --------------------------------------------------------------------------
    {

        $order_str=get("sort_".$PH->cur_page->id ."_tasks");
        $tasks_unsorted= $project->getTasks(false,$order_str, 5,10);
        $list_tasks=new ListBlock_tasks();
        $list_tasks->title="Closed tasks";
        $list_tasks->no_items_html= "no tasks closed yet";
        #unset($list_tasks->columns['label']);
        #unset($list_tasks->columns['folder']);
        $list_tasks->render_list(&$tasks_unsorted);

	}


    ### HACKING: 'add new task'-field ###
    $PH->go_submit='taskNew';
    echo '<input type="hidden" name="prj" value="'.$id.'">';

    #echo (new PageQuickNew);

    #echo "<a href=\"javascript:document.my_form.go.value='tasksMoveToFolder';document.my_form.submit();\">move to task-folder</a>";
    echo (new PageContentClose);
	echo (new PageHtmlEnd());
}






/**
* list changes
*/
function ProjViewChanges() 
{
    global $PH;

	### get current project ###
    $id=getOnePassedId('prj','projects_*');
    if(!$project=new Project($id)) {
        $PH->abortWarning("invalid project-id");
		return;
	}

    ### create from handle ###
    $PH->defineFromHandle(array('prj'=>$project->id));

    ### set up page ####
    {
        $page= new Page();
    	$page->cur_tab='projects';
        $page->title="Changes";
        $page->title_minor=$project->name;
        $page->type="Project";

        ### breadcrumbs (distinguish active/closed projects ###
    	if($project->status > 3) {
            $page->crumbs=array(
                $PH->getLink('projListClosed'),
        		$PH->getLink('projView',$project->name, array('prj'=>$project->id))
        	);
        }
        else {
            $page->crumbs=array(
                $PH->getLink('projList'),
        		$PH->getLink('projView',$project->name, array('prj'=>$project->id))
        	);
        }
        $page->options= build_projView_options($project);


    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

    #echo(new PageContentNextCol);


    #--- list items --------------------------------------------------------------------------
    {
        require_once("lists/list_projectchanges.inc");

        $order_str=get("sort_".$PH->cur_page->id ."_changes");

        $items= $project->getChanges($order_str, false);

        $list=new ListBlock_projectchanges();
        $list->title="changed project-items";
        $list->no_items_html= "no changes yet";
        $list->render_list(&$items);

	}


    ### HACKING: 'add new task'-field ###
    $PH->go_submit='taskNew';
    echo '<input type="hidden" name="prj" value="'.$id.'">';

    #echo (new PageQuickNew);

    #echo "<a href=\"javascript:document.my_form.go.value='tasksMoveToFolder';document.my_form.submit();\">move to task-folder</a>";
    echo (new PageContentClose);
	echo (new PageHtmlEnd());
}







/**
* list issues of project
*/
function ProjViewIssues()
{
    global $PH;

	### get current project ###
    $id=getOnePassedId('prj','projects_*');
    if(!$project=new Project($id)) {
        $PH->abortWarning("invalid project-id");
	}

    ### create from handle ###
    $PH->defineFromHandle(array('prj'=>$project->id));

    ### set up page ####
    {
        $page= new Page();
    	$page->cur_tab='projects';
        $page->title="Project Issues";
        $page->title_minor=$project->name;;

        $page->type="Project";

        ### breadcrumbs (distinguish active/closed projects ###
    	if($project->status > 3) {
            $page->crumbs=array(
                $PH->getLink('projListClosed'),
        		$PH->getLink('projView',$project->name, array('prj'=>$project->id)),
        	);
        }
        else {
            $page->crumbs=array(
                $PH->getLink('projList'),
        		$PH->getLink('projView',$project->name, array('prj'=>$project->id)),
        	);
        }
        $page->options= build_projView_options($project);



    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen_Columns);

    echo(new PageContentNextCol);


    #--- list issues --------------------------------------------------------------------------
    {
        #@@@ later this should be done with a separately defined listblock
        require_once("lists/list_tasks.inc");
        $tasks=$project->getTasks(false);
        $list= new ListBlock_tasks();
        unset($list->functions['taskNew']);
        $list->title="Issues";
        $list->no_items_html=$PH->getLink('taskNew', 'Report Bug'  ,array('prj'=>$project->id,'add_issue'=>1));

        $issues=array();
        foreach($tasks as $t) {
            if($t->issue_report) {
                $issues[]= $t;
            }
        }
        $list->render_list(&$issues);
	}

    echo '<input type="hidden" name="prj" value="'.$id.'">';

    #echo (new PageQuickNew);

    #echo "<a href=\"javascript:document.my_form.go.value='tasksMoveToFolder';document.my_form.submit();\">move to task-folder</a>";
    echo (new PageContentClose);
	echo (new PageHtmlEnd());
}



#----------------------------------------------------------
# projectNew
#----------------------------------------------------------
function projNew() {
    global $PH;

    $company=get('company');

    ### build dummy form ###
    $newproject= new Project(array(
        'id'=>0,
        'name'=>'New Project',
        'state'=>1,
        'company'=>$company,
        'pub_level'=>100,
        )
    );

    $PH->show('projEdit',array('prj'=>$newproject->id),$newproject);
}


#----------------------------------------------------------
# project edit
#----------------------------------------------------------
function projEdit($project=NULL) {
    global $PH;
    global $auth;
    require_once ("db/class_company.inc");

    if($project) {

    }
    else {
        $prj=getOnePassedId('prj','project_*');

        ### get project ####
        if(!$project= new Project($prj)) {
            $PH->abortWarning("could not get Project");
            return;
        }
    }

    ### set up page and write header ####
    {
        $page= new Page(array('use_jscalendar'=>true,'autofocus_field'=>'project_name'));
    	$page->cur_tab='projects';
        $page->type="Edit Project";
        $page->title=$project->name;
        $page->title_minor=$project->short;

    	$page->crumbs=array(
            $PH->getLink('projList'),
    		$project->getShort(),
       	);
    	$page->options=array(
            new NaviOptionWiki()
    	);


        echo(new PageHeader);
    }
    echo (new PageContentOpen);

    ### write form #####
    {
        global $STATUS_NAMES;
        global $STATUS_VALUES;
        global $PRIORITY_VALUES;
        require_once("render/render_form.inc");

        $form=new PageForm();
        $form->button_cancel=true;

        
        $form->add($project->fields['name']->getFormElement(&$project));
        $form->add($project->fields['short']->getFormElement(&$project));
        #$form->add($project->fields['prio']->getFormElement(&$project));
        $form->add(new Form_Dropdown('project_prio',  "Prio",$PRIORITY_VALUES,$project->prio));

        $form->add($project->fields['status']->getFormElement(&$project));
        $form->add($project->fields['status_summary']->getFormElement(&$project));
        $form->add(new Form_Dropdown('project_status',  "Status",$STATUS_VALUES,$project->status));

        $form->add($project->fields['date_start']->getFormElement(&$project));
        $form->add($project->fields['date_closed']->getFormElement(&$project));
        $form->add($project->fields['description']->getFormElement(&$project));

        $form->add($project->fields['show_in_home']->getFormElement(&$project));
        $form->add($project->fields['color']->getFormElement(&$project));
        $form->add($project->fields['wikipage']->getFormElement(&$project));
        $form->add($project->fields['projectpage']->getFormElement(&$project));


        ### build company-list ###
        $companies=Company::getAll();
        $cl_options= array('undefined'=>0);
        foreach($companies as $c) {
            $cl_options[$c->name]= $c->id;
        }
        $form->add(new Form_Dropdown('project_company','Company',$cl_options,$project->company));

        #$form->add(new Form_Dropdown('status',  "Status",array(),0));

        #$form->add(new Form_Date('prj_date_start', 'Started',$project->fields['date_start']->db2value($project->date_start)));
        #$form->add(new Form_Date('prj_date_closed','Closed',$project->fields['date_closed']->db2value($project->date_closed)));
        #$form->add(new Form_Edit('prj_description', 'Description', $project->description));





        ### create another one ###
        if($auth->cur_user->user_rights & RIGHT_PROJECT_CREATE && $project->id == 0) {
            $checked= get('create_another')
            ? 'checked'
            : '';

            $form->form_options[]="<span class=option><input name='create_another' class='checker' type=checkbox $checked>Create another project after submit</span>";     ;
        }


        echo($form);

        $PH->go_submit='projEditSubmit';

        if($return=get('return')) {
            echo "<input type=hidden name='return' value='$return'>";
        }
        echo "<input type=hidden name='prj' value='$project->id'>";
    }
    echo (new PageContentClose);
}


#---------------------------------------------------------------------------
# projectEditSubmit
#---------------------------------------------------------------------------
function projEditSubmit()
{
    global $PH;
    global $auth;

    ### cancel ###
    if(get('do_form_cancel')) {
        if(!$PH->showFromPage()) {
            $PH->show('projView',array('prj'=>$project->id));
        }
        exit;
    }


    ### get task ####
    $prj=getOnePassedId('prj');
    if($prj == 0) {
        $project= new Project();
    }
    else {
        if(!$project= new Project($prj)) {
            $PH->abortWarning("Could not get project");
            return;
        }
    }

    # retrieve all possible values from post-data
    # NOTE:
    # - this could be an security-issue.
    # - TODO: as some kind of form-edit-behaviour to field-definition
    foreach($project->fields as $f) {
        $name=$f->name;
        $f->parseForm(&$project);
    }


    ### write to db ###
    if($project->id ==0) {
        $project->insert();

        ### if new project add creator to team ###
        #
        if($person= new Person($project->created_by)) {

            $pp_new= new ProjectPerson(array(
                'id'        =>0,
                'person'    =>$person->id,
                'project'   =>$project->id,
            ));
            $pp_new->initWithUserProfile('admin');
            $pp_new->insert();
        }
    }
    else {
        $project->update();
    }


    ### automatically view new project ###
    if($prj == 0) {
        ### create another person ###
        if(get('create_another')) {
            $PH->show('projNew');
            exit;
        }
        else {
            $PH->show('projView',array('prj'=>$project->id));
            exit;
        }
    }
    ### otherwise return to from-page
    else {
        ### display taskView ####
        if(!$PH->showFromPage()) {
            $PH->show('projView',array('prj'=>$project->id));
        }
    }
}


#=====================================================================================================
# project delete
#=====================================================================================================
function projDelete(){
    global $PH;

    ### get effort ####
    $ids= getPassedIds('prj','projects_*');

    if(!$ids) {
        $PH->abortWarning("Select some projects to delete");
        return;
    }

    $counter=0;
    $errors=0;
    foreach($ids as $id) {
        if(!$p= new Project($id)) {
            $errors++;
        }
        else {
            if($p->delete()) {
                $counter++;
            }
            else {
                $errors++;
            }
        }
    }
    if($errors) {
        $PH->message="WARNING: Failed to delete $errors projects";
    }
    else {
        $PH->message="Moved $counter projects to dumpster";
    }

    ### display taskView ####
    if(!$PH->showFromPage()) {
        $PH->show('projList');
    }
}

#=====================================================================================================
# project Change status
#=====================================================================================================
function projChangeStatus(){
    global $PH;
    global $STATUS_VALUES;

    ### get effort ####
    $ids= getPassedIds('prj','projects_*');

    if(!$ids) {
        $PH->abortWarning("Select some projects...");
        return;
    }

    $counter=0;
    $errors=0;
    foreach($ids as $id) {
        $e= new Project($id);
        if(!$e || $e->state ==-1) {
            $PH->abortWarning("Invalid project-id!");
        }

        if(!$e->status <= $STATUS_VALUES['open']) {
            $e->status= $STATUS_VALUES['closed'];
            $e->date_closed=  date("Y-m-d",time());
        }
        $e->update();
        $counter++;
    }
    if($errors) {
        $PH->message="WARNING: Failed to change $errors projects";
    }
    else {
        $PH->message="Closed $counter projects";
    }

    ### display taskView ####
    if(!$PH->showFromPage()) {
        $PH->show('projList');
    }
}

/**
* select new people from a list
*
* userRights have been validated by pageHandler()
*
* @@@ add additional project-specific check here?
*/
function projAddPerson() 
{
    global $PH;

    $id= getOnePassedId('prj','');   # WARNS if multiple; ABORTS if no id found
    if(!$project= new Project($id)) {
        $PH->abortWarning("ERROR: could not get Project");
    }

    

    ### set up page and write header ####
    {
        $page= new Page(array('use_jscalendar'=>false, 'autofocus_field'=>'company_name'));
    	$page->cur_tab='projects';
        $page->type="Edit Project";
        $page->title="$project->name";
        $page->title_minor="Select new team members";

    	$page->crumbs=array(
       	);
    	$page->options=array(
            $PH->getWikiLink()
    	);
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

    ### write form #####
    {
        require_once("pages/person.inc");
        require_once("render/render_form.inc");

        ### write list of persons ###
        {
            ### build hash of already added person ###
            $pps= $project->getProjectPersons('', true,false);
            $pp_hash=array();
            foreach($pps as $pp) {
                $pp_hash[$pp->person]= true;
            }
              
            ### filter already added persons ###
            $persons= array();
            foreach(Person::getAll() as $p) {
                if(!isset($pp_hash[$p->id])) {
                    $persons[]=$p;
                }
            }
    
            $list= new ListBlock_persons();
            
            unset($list->columns['personal_phone']);
            unset($list->columns['office_phone']);
            unset($list->columns['mobile_phone']);
            unset($list->columns['companies']);
            $list->no_items_html="found no persons to add";
    
            $list->render_list(&$persons);
        }
        
        

        #@@@ probably add dropdown-list with new project-role here 

        $PH->go_submit='projAddPersonSubmit';
        echo "<input type=hidden name='project' value='$project->id'>";
        echo "<input class=button2 type=submit>";
    }
    echo (new PageContentClose);
}


function projAddPersonSubmit()
{
    global $PH;
    global $g_user_profiles;
    global $g_user_profile_names;


    require_once("db/class_person.inc");

    $id= getOnePassedId('project','');
    if(!$project= new Project($id)) {
        $PH->abortWarning("Could not get object...",ERROR_FATAL);
    }

    ### get persons ###
    $person_ids= getPassedIds('person','persons*');
    if(!$person_ids) {
        $PH->abortWarning("No persons selected...",ERROR_NOTE);
    }

    ### get team (including inactive members)  ###
    $ppersons= $project->getProjectPersons('',false,false); # also  PP with state !=1

    ### go through selected people ###
    foreach($person_ids as $pid) {
        $person= new Person($pid);
        if(!$person) {
            $PH->abortWarning("Could not access person by id");
            return;
        }

        #### person already employed? ###
        $already_in=false;
        $pp=NULL;
        foreach($ppersons as $pp) {
            if($pp->person == $person->id) {
                $already_in= true;
                break;
            }
        }

        ### add ###
        if(!$already_in) {
            $pp_new= new ProjectPerson(array(
                'id'=>0,
                'person'=>$person->id,
                'project'=>$project->id,
            ));
            
            ### add project-right ###
            if($profile_name= $g_user_profile_names[$person->profile]) {

                $pp_new->initWithUserProfile($profile_name);
            }
            else {
                trace("WARNING","person '$person->name' has undefined profile");
            }
            
            
            $pp_new->insert();
        }
        ### reanimate ###
        else if($pp->state != 1) {
            $pp->state=1;
            $pp->update();
            $PH->message="NOTE: reanimated person as team-member";
        }
        ### skip ###
        else {
            $PH->message="NOTE: person already in project";
        }
    }
    ### display taskView ####
    if(!$PH->showFromPage()) {
        $PH->show('projView',array('prj'=>$project->id));
    }
}



/**
* duplicate a project including all belonging items (tasks, efforts, etc.)
*
* - This function is a massive database-process and should be protected
*   from parallel database acesses. Failure of this procedure can lead to
*   inconsistent db-structures. Maybe we should add a db-structure validation
*   somewhere
* - all items (even the already deleted) are duplicated because there might
*   be some relationships (like effort on an deleted task, is still an effort)
*/
function projDuplicate() 
{
    global $PH;
    global $auth;

    $count_items=0;

    $prj=getOnePassedId('prj','project_*'); # aborts on failure

    ### get project ####
    if(!$org_project= new Project($prj)) {
        $PH->abortWarning("could not get Project");
        return;
    }
    if(!$new_project= new Project($prj)) {
        $PH->abortWarning("could not get Project");
        return;
    }           


    ### duplicate project ###
    $new_project->id=0;
    
    $new_project->name= "copy of ".$org_project->name;
    $new_project->created_by= $auth->cur_user->id;
    $new_project->modified_by= $auth->cur_user->id;
    $new_project->date_start= date("Y-m-d H:i:s");
    $new_project->status= 3;    #@@@ avoid majic numbers

    $new_project->state=1;      # be sure project is no deleted
    if(!$new_project->insert()) {
        $PH->abortWarning("Failed to insert new project",ERROR_DATASTRUCTURE);
    }


    $flag_cur_user_in_project=false;
    
    ### copy projectpersons ###
    if($org_ppersons= $org_project->getProjectPersons(
                                     NULL,  # $order_by=NULL,    
                                     false, # $alive_only=true, 
                                     false  # $visible_only= true
    )){
        foreach($org_ppersons as $pp){
            $pp->id=0;
            $pp->project= $new_project->id;
            
            ### make current user project admin ###
            if($pp->person == $auth->cur_user->id) {

                $pp->initWithUserProfile('admin');
                $flag_cur_user_in_project= true;
            }

            if(!$pp->insert()) {
                $PH->abortWarning("Failed to insert new projectproject",ERROR_DATASTRUCTURE);            
            }
            $count_items++;
        }
    }

    ### be sure, current user is admin ###
    if(!$flag_cur_user_in_project) {
        $pp_new= new ProjectPerson(array(
            'id'        =>0,
            'person'    =>$auth->cur_user->id,
            'project'   =>$new_project->id,
        ));
        $pp_new->initWithUserProfile('admin');
        if(!$pp_new->insert()) {
            $PH->abortWarning("Failed to insert new projectproject",ERROR_DATASTRUCTURE);            
        }
    }
    

    ### copy issues ###
    $dict_issues=array(0=>0);

    $org_issues= $org_project->getIssues(NULL,false,false);
    
    foreach($org_issues as $i) {

        $org_issue_id= $i->id;

        $i->project= $new_project->id;
        if(!$i->insert()) {
            $PH->abortWarning("Failed to insert new issue",ERROR_DATASTRUCTURE);            
        }

        $count_items++;
        $dict_issues[$org_issue_id]= $i->id;
    }

    
    ### copy tasks 
    {
        ### pass1 ###
        $dict_tasks=array(0=>0);    # assoc array of old / new task-ids
    
        $new_tasks=array();

        if($org_tasks= $org_project->getTasks(
                                        true,  # ignore folder-structure
                                        "",     # no order
                                        0,      # min-status
                                        10,     # max status
                                        false,  # also show hidden
                                        false   # also show deleted
            )) {
            foreach($org_tasks as $t) {
                
                $org_task_id= $t->id;
                $t->id= 0;
                $t->project= $new_project->id;
                $t->issue_report = $dict_issues[$t->issue_report];

                if(!$t->insert()) {
                    $PH->abortWarning("Failed to insert new task",ERROR_DATASTRUCTURE);            
                }

                $count_items++;
                $dict_tasks[$org_task_id]= $t->id;
                $new_tasks[]=$t;
            }
        }
        
        ### pass2: tasks / parent_task ###
        foreach($new_tasks as $nt) {
            if(isset($dict_tasks[$nt->parent_task])) {
                $nt->parent_task= $dict_tasks[$nt->parent_task];
            }
            else {
                trace("Warning","undefined task-id $nt->parent_task");
            }
            if(!$nt->update()) {
                $PH->abortWarning("Failed to update new task",ERROR_DATASTRUCTURE);            
            }
        }
    }    

        
    ### copy efforts ###
    $dict_efforts=array(0 => 0);

    if($org_efforts= $org_project->getEfforts(
                                      "",  # $order_by=NULL, 
                                      false,  # $visible_only=true, 
                                      false  # $alive_only=true    
    )) {
        foreach($org_efforts as $e) {

            $org_effort_id= $e->id;

            if(isset($dict_tasks[$e->task])) {
                $e->task= $dict_tasks[$e->task];
            }
            else {
                trace("Warning","undefined task-id $e->task");
            }
            $e->id= 0;
            $e->project= $new_project->id;
            if(!$e->insert()) {
                $PH->abortWarning("Failed to insesrt new effort",ERROR_DATASTRUCTURE);            
            }

            $count_items++;
            $dict_efforts[$org_effort_id]= $e->id;
        }
    }
    
    
    ### copy comments ###
    {
        $dict_comments=array(0 => 0);
        $new_comments=array();
    
        if($org_comments= $org_project->getComments(
                                            "",      # $order_by=NULL, 
                                            false,   # $visible_only=true, 
                                            false    # $alive_only=true        
        )) {
            foreach($org_comments as $c) {
    
                $org_comment_id= $c->id;
                
                
                if(isset($dict_tasks[$c->task])) {
                    $c->task= $dict_tasks[$c->task];
                }
                if(isset($dict_efforts[$c->effort])) {
                    $c->effort= $dict_efforts[$c->effort];
                }
                if(isset($dict_effort[$c->effort])){
                    $c->effort= $dict_efforts[$c->effort];
                }
    
                $c->id= 0;
                $c->project = $new_project->id;

                if(!$c->insert()) {
                    $PH->abortWarning("Failed to insert new comment",ERROR_DATASTRUCTURE);            
                }

                $count_items++;
                
                $dict_comments[$org_comment_id]= $c->id;
                $new_comments[]=$c;
            }
        }    
    
        ### pass2: comment / on comment ###
        foreach($new_comments as $nc) {
            $nc->comment= $dict_comments[$nc->comment];
            if(!$nc->update()) {
                $PH->abortWarning("Failed to update new comment",ERROR_DATASTRUCTURE);            
            }
        }
    }    

    $PH->message= "Project duplicated (including $count_items items)";    
    $PH->show('projEdit',array('prj'=>$new_project->id),$new_project);
}

?>