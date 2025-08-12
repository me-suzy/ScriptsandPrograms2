<?php


require_once("db/class_task.inc");
require_once("db/class_project.inc");
require_once("db/class_company.inc");
require_once("render/render_list.inc");
require_once("lists/list_companies.inc");



#=====================================================================================================
# companyList
# - requires prj or task or tsk_*
#=====================================================================================================
function companyList() {
    global $PH;
    global $auth;

    ### create from handle ###
    $PH->defineFromHandle();


    ### set up page and write header ####
    {
        $page= new Page();
    	$page->cur_tab='companies';
        $page->title="Companies";
        if(!($auth->cur_user->user_rights & RIGHT_PROJECT_EDIT)) {
            $page->title_minor="relating projects of ". $page->title_minor=$auth->cur_user->name;
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
            #new NaviOption(array('target_id'=>'personList',           'name'=>'People')),
            #new NaviOption(array('target_id'=>'companyList',     'name'=>'Companies')),
            #new NaviOptionSeparator(),
            new NaviOptionWiki()
        );



    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

	#--- list projects --------------------------------------------------------
	{


        $list= new ListBlock_companies();
        
        ### may user create companies? ###
        if($auth->cur_user->user_rights & RIGHT_COMPANY_CREATE) {
            $list->no_items_html=$PH->getLink('companyNew','',array('person'=>$auth->cur_user->id));
        }
        else {
            $list->no_items_html="no companies";
        }
        
        
        $order_str= get("sort_".$PH->cur_page->id."_".$list->id);

        $order_str= str_replace(",",", ", $order_str);
    	$companies=Company::getAll($order_str);

        $list->title= $page->title;
        $list->render_list(&$companies);
	}

    echo(new PageContentClose);
	echo(new PageHtmlEnd);
}

#=====================================================================================================
# companyView
#=====================================================================================================
function companyView()
{
    global $PH;
    global $auth;

	### get current company ###
    $id=getOnePassedId('company','companies_*');
    $company= new Company($id);
	if(!$company) {
        $PH->abortWarning("invalid company-id");
		return;
	}

    $company->validateView();

    ### create from handle ###
    $PH->defineFromHandle(array('company'=>$company->id));

    ### set up page ####
    {
        $page= new Page();
    	$page->cur_tab='companies';
        $page->title=$company->name;
        $page->title_minor="Overview";
        $page->type="Company";


        ### add company-id ###
        # note: some pageFunctions like personNew can use this for automatical linking
        #
        echo "<input type=hidden name=company_id value='$company->id'>";

        ### breadcrumbs (distinguish active/closed companies ###
        $page->crumbs=array(
            $PH->getLink('companyList','Companies'),
            $PH->getLink('companyView',$company->name,array('company'=>$company->id))
    	);

        $page->options=array(
            new NaviOptionWiki()
        );


        ### page functions ###
        $page->add_function(new PageFunction(array(
            'target'=>'companyEdit',
            'params'=>array('company'=>$company->id),
            'icon'=>'edit',
            'tooltip'=>'Edit this company',
            'name'=>'edit',
        )));
        $page->add_function(new PageFunction(array(
            'target'=>'personNew',
            'params'=>array('company'=>$company->id),
            'icon'=>'new',
            'tooltip'=>'Create new person for this company',
            'name'=>'Person',
        )));
        $page->add_function(new PageFunction(array(
            'target'=>'projNew',
            'params'=>array('company'=>$company->id),
            'icon'=>'new',
            'tooltip'=>'Create new project for this company',
            'name'=>'Project',
        )));
        $page->add_function(new PageFunction(array(
            'target'=>'companyLinkPersons',
            'params'=>array('company'=>$company->id),
            'icon'=>'add',
            'tooltip'=>'Add existing persons to this company',
            'name'=>'Persons',
        )));
        
        

    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen_Columns);

    #--- write info block ------------
    {
        $block=new PageBlock(array('title'=>'Summary'));
        $block->render_blockStart();
        echo "<div class=text>";
        if($company->comments) {
            echo "<p>$company->comments</p>";
        }
        if($company->street) {
            echo "<p><label>Adress:</label>$company->street</p>";
        }
        if($company->zipcode) {
            echo "<p><label></label>$company->zipcode</p>";
        }
        if($company->phone) {
            echo "<p><label>Phone:</label>$company->phone</p>";
        }
        if($company->fax) {
            echo "<p><label>Fax:</label>$company->fax</p>";
        }


        if($company->homepage) {
            echo "<p><label>Web:</label>".url2linkExtern($company->homepage)."</p>";
        }
        if($company->intranet) {
            echo "<p><label>Intra:</label>".url2linkExtern($company->intranet)."</p>";
        }
        if($company->email) {
            echo "<p><label>Mail:</label>".url2linkMail($company->email)."</p>";
        }

        echo "</div>";

        $block->render_blockEnd();
    }

    echo(new PageContentNextCol);

    #--- list projects------------------------------------------------------------
    {
        require_once("pages/proj.inc");
        $list= new ListBlock_projects();
        $projects= $company->getProjects();
        $list->title="Active projects";
        unset($list->columns['company']);
        unset($list->functions['projNew']);
        unset($list->functions['projDelete']);

        if($auth->cur_user->user_rights & RIGHT_PROJECT_CREATE) {
            $list->no_items_html=$PH->getLink('projNew','Create new project',array('company'=>$company->id)).
            " Hint: for already existing projects please edit those and adjust company-setting.";
        }
        else {
            $list->no_items_html="on projects yet";
        }


        $list->render_list(&$projects);

    }

    #--- list persons -------------------------------
    {
        require_once("pages/person.inc");
        $list= new ListBlock_persons();
        $persons= $company->getPersons();
        $list->title="related Persons";
        unset($list->functions['personNew']);
        unset($list->functions['personDelete']);
        if($auth->cur_user->user_rights & RIGHT_COMPANY_EDIT) {
            $list->no_items_html=
                $PH->getLink('companyLinkPersons','link existing Person',array('company'=>$company->id))
                ." or "
                .$PH->getLink('personNew','create new',array('company'=>$company->id));
        }
        else {
            $list->no_items_html="no persons related";
        }

        $list->render_list(&$persons);
    }




    ### HACKING: 'add new task'-field ###
    #echo '<input type="hidden" name="prj" value="'.$id.'">';

    #echo (new PageQuickNew);

    #echo "<a href=\"javascript:document.my_form.go.value='tasksMoveToFolder';document.my_form.submit();\">move to task-folder</a>";
    echo (new PageContentClose);
	echo (new PageHtmlEnd());

}


#=====================================================================================================
# companyNew
# - requires prj or task or tsk_*
#=====================================================================================================
function companyNew() {
    global $PH;

    $name=get('new_name')
        ? get('new_name')
        :"New Company";


    ### build new object ###
    $newCompany= new Company(array(
        'id'=>0,
        'name'=>$name,
        )
    );
    $PH->show('companyEdit',array('company'=>$newCompany->id),$newCompany);
}


#=====================================================================================================
# company edit
#=====================================================================================================
function companyEdit($company=NULL) {
    global $PH;
    global $auth;

    ### use object or get from database ###
    if($company) {

    }
    else {
        $id= getOnePassedId('company','companies*');   # WARNS if multiple; ABORTS if no id found
        $company= new Company($id);
        if(!$company) {
            $PH->abortWarning("ERROR: could not get Company");
            return;
        }
    }

    ### set up page and write header ####
    {
        $page= new Page(array('use_jscalendar'=>true, 'autofocus_field'=>'company_name'));
    	$page->cur_tab='companies';
        $page->type="Edit Company";
        $page->title=$company->name;

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
        global $STATUS_NAMES;
        require_once("render/render_form.inc");

        $form=new PageForm();
        $form->button_cancel=true;

        foreach($company->fields as $field) {
            $form->add($field->getFormElement(&$company));
        }

        ### create another  ###
        if($auth->cur_user->user_rights & RIGHT_COMPANY_CREATE && $company->id == 0) {
            $checked= get('create_another')
            ? 'checked'
            : '';

            $form->form_options[]="<span class=option><input name='create_another' class='checker' type=checkbox $checked>Create another company after submit</span>";     ;
        }


        echo ($form);

        $PH->go_submit='companyEditSubmit';
        echo "<input type=hidden name='company' value='$company->id'>";
    }
    echo (new PageContentClose);
}

#=====================================================================================================
# companyEditSubmit
#=====================================================================================================
function companyEditSubmit()
{
    global $PH;
    global $auth;

    ### cancel ###
    if(get('form_do_cancel')) {
        if(!$PH->showFromPage()) {
            $PH->show('home',array());
        }        
        exit;
    }


    ### get company ####
    $id= getOnePassedId('company');

    ### temporary object ###
    if($id == 0) {
        $company=new Company();
    }
    ### get from db ###
    else {
        $company= new Company($id);
        if(!$company) {
            $PH->abortWarning("Could not get company");
            return;
        }
    }

    # retrieve all possible values from post-data
    # NOTE:
    # - this could be an security-issue.
    # - TODO: as some kind of form-edit-behaviour to field-definition
    foreach($company->fields as $f) {
        $name=$f->name;
        $f->parseForm(&$company);
    }

    ### write to db ###
    if($company->id == 0) {
        $company->insert();

        ### show 'create another' -form
        if(get('create_another')) {
            $PH->show('companyNew',array());
            exit;
        }
    }    
    else {
        $company->update();
    }

    ### display taskView ####
    if(!$PH->showFromPage()) {
        $PH->show('home',array());
    }
}

#=====================================================================================================
# Link Persons to company
#=====================================================================================================
function companyLinkPersons() {
    global $PH;

    $id= getOnePassedId('company','companies*');   # WARNS if multiple; ABORTS if no id found
    $company= new Company($id);
    if(!$company) {
        $PH->abortWarning("ERROR: could not get Company");
        return;
    }

    ### set up page and write header ####
    {
        $page= new Page(array('use_jscalendar'=>true, 'autofocus_field'=>'company_name'));
    	$page->cur_tab='companies';
        $page->type="Edit Company";
        $page->title="Edit $company->name";
        $page->title_minor="Add persons employed or related";

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
        $persons= Person::getAll();
        $list= new ListBlock_persons();
        $list->show_functions=false;
        $list->show_icons=false;
        

        $list->render_list(&$persons);

        $PH->go_submit='companyLinkPersonsSubmit';
        echo "<input type=hidden name='company' value='$company->id'>";
        echo "<input class=button2 type=submit>";

    }
    echo (new PageContentClose);
}




#=====================================================================================================
# companyLinkPersonsSubmit
#=====================================================================================================
function companyLinkPersonsSubmit(){
    global $PH;
    require_once("db/class_person.inc");

    $id= getOnePassedId('company','companies*');
    $company= new Company($id);
    if(!$company) {
        $PH->abortWarning("Could not get object...");
    }

    $person_ids= getPassedIds('person','persons*');
    if(!$person_ids) {
        $PH->abortWarning("NOTE: No persons selected...");
    }

    $employments= $company->getEmployments();

    foreach($person_ids as $pid) {
        if(!$person= new Person($pid)) {
            $PH->abortWarning("Could not access person by id");
        }

        #### person already employed? ###
        $already_in=false;
        foreach($employments as $e) {
            if($e->person == $person->id) {
                $already_in= true;
                break;
            }
        }
        if(!$already_in) {
            $e_new= new Employment(array(
                'id'=>0,
                'person'=>$person->id,
                'company'=>$company->id,
            ));
            $e_new->insert();
        }
        else {
            $PH->message="NOTE person already related to company";
        }
    }
    ### display taskView ####
    if(!$PH->showFromPage()) {
        $PH->show('companyView',array('company'=>$company->id));
    }
}



#=====================================================================================================
# companyDelete
#=====================================================================================================
function companyDelete(){
    global $PH;

    ### get company ####
    $ids= getPassedIds('company','companies_*');

    if(!$ids) {
        $PH->abortWarning("Select some companies to delete");
        return;
    }

    $counter=0;
    $errors=0;
    foreach($ids as $id) {
        $e= new Company($id);
        if(!$e) {
            $PH->abortWarning("Invalid company-id!");
        }
        if($e->delete()) {
            $counter++;
        }
        else {
            $errors++;
        }
    }
    if($errors) {
        $PH->message="WARNING: Failed to delete $errors companies";
    }
    else {
        $PH->message="Moved $counter companies to dumpster";
    }

    ### display taskView ####
    if(!$PH->showFromPage()) {
        $PH->show('projView',array('prj'=>$company->project));
    }
}


?>