<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * pages relating login and account-handling
 *
 * @author: Thomas Mann
 * @uses:
 * @usedby:
 *
 */


require_once("db/class_task.inc");
require_once("db/class_project.inc");
require_once("db/class_person.inc");

require_once("render/render_list.inc");


global $g_tabs_login;
$g_tabs_login= array(
        	"login"	=>array(
                'target'=>"index.php?go=login",
                'title'=>"Login",
                'bg'=>"misc"       ,
                'tooltip'=>'Go to your home. Alt-h / Option-h'
            ),
        	"license"	=>array(
                'target'=>"index.php?go=helpLicense",
                'title'=>"License",
                'tooltip'=>'Your projects. Alt-P / Option-P',
                'bg'=>"projects",
                'accesskey'=>'p'
            )
        );



#=====================================================================================================
# loginForm
#=====================================================================================================
function loginForm() {
    global $PH;
    global $auth;

    if(isset($auth->cur_user)) {
        $auth->cur_user=NULL;
    }

    #
    # note: this page should not create a from-handle, because
    # the last stored from-handle still contains the recently view site
    #

    ### set up page and write header ####
    {
        $page= new Page(array('autofocus_field'=>'login_name'));
        global $g_tabs_login;
        $page->tabs= $g_tabs_login;

    	$page->cur_tab='login';
        $page->type="";
        $page->title='Welcome to streber';
        $page->title_minor='please login';

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
        require_once("render/render_form.inc");

        if($msg= confGet('LOGIN_MESSAGE')) {
            echo 
            "<div class=text>"
            ."$msg"
            ."</div>";
        }
        

        $form=new PageForm();
        $form->add(new Form_Input('login_name',         'Name','') );
        $form->add(new Form_Password('login_password',  'Password','') );
        echo ($form);

        $PH->go_submit='loginFormSubmit';
    }
    echo (new PageContentClose);
}

/**
* login form submit
*/
function loginFormSubmit(){
    global $PH;
    global $auth;

    ### get formdata ####
    $name= get('login_name');
    $password_md5= md5(get('login_password'));

    if($auth->tryLoginUser($name,$password_md5)) {

        ### display taskView ####
        if(!$PH->showFromPage()) {

            ### if user has only one project go there ###
            $projects=$auth->cur_user->getProjects();
            if(count($projects) == 1) {
                $PH->message= sprintf(confGet('MESSAGE_WELCOME_ONEPROJECT'), $auth->cur_user->name,$projects[0]->name);
                $PH->show('projView',array('prj'=>$projects[0]->id));
            }
            else {
                $PH->message=confGet('MESSAGE_WELCOME_HOME');
                $PH->show('home',array());
            }
        }
    }
    else {
        $PH->message="invalid login";
        $PH->show('loginForm');
    }
}

/**
* logout
*/
function logout(){
    global $PH;
    global $auth;

    ### kill cookie ###
    $auth->removeCookie();
    $PH->cur_page_md5=NULL;

    ### go to login-page ####
    #$PH->message="Logged out";
    #$PH->show('loginForm');
    header("location:index.php");
    
}



function helpLicense()
{
    global $PH;


    ### create from handle ###

    ### set up page and write header ####
    {
        $page= new Page(array());

        global $g_tabs_login;
        $page->tabs=$g_tabs_login;

    	$page->cur_tab='license';
        $page->type="";
        $page->title='License';

    	$page->crumbs=array(
       	);
    	$page->options=array(
            $PH->getWikiLink()
    	);
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

    require_once("_docs/license.html");

    echo (new PageContentClose);

}


?>