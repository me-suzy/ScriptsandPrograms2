<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * classes related to rendering html output
 *
 * called from:
 *
 *
 * @author: Thomas Mann
 * @uses:
 * @usedby:
 *
 */


require_once("render/render_misc.inc");
require_once("render/render_block.inc");



/**
* pagefunctions for editing the currently displayed obj (eg. Delete a task)
*
*/
class PageFunction 
{
    public $target;             # pageid without params
    public $params;             # pageid without params
    public $url;                # link-target (pageid including params)
    public $name;               # name
    public $icon;               # name of function icon
    public $parent_block;
    public $tooltip;
    public $context_menu=false; # show in context-menus
    public $key;                # used as id in assoc. array  'functions'

    public function __construct($args=NULL) 
    {
        foreach($args as $key=>$value) {
            is_null($this->$key);   # cause E_NOTICE if member not defined
            $this->$key=$value;
        }
        if(!$this->target) {
            throw new RenderException("PageFunctions require target.  ('params','name','icon' are optional)");
        }
    }

    public function __set($name,$value) 
    {
        if($this->$name) {
            $this->$name= $value;
        }
        else {
            trace("WARNING","setting undefined attribute '$name' of list function  to '$value'");
            $this->$name= $value;
        }
    }
}

#=======================================================================================
# option inside sub-navigation
#=======================================================================================
class NaviOption {
    PUBLIC $name='';
    PUBLIC $target_id='';       # id of internal target-page / used to get url and for automatically highlighting option
    PUBLIC $target_url='';      # target url including parameters / build from id and target_params
    PUBLIC $tooltip;            # optional
    PUBLIC $active=false;       # hightlight as current option
    PUBLIC $target_params=array();      # assoc. array of target-params

    #------------------------------------------------------------------------------------
    public function __construct( $args) {
        global $PH;

        #--- set parameters ---
        foreach($args as $key=>$value) {
            is_null($this->$key);   # cause E_NOTICE if member not defined
            $this->$key= $value;
        }

        #--- hilight active option ---
        if($this->target_id == $PH->cur_page_id) {
            $this->active= true;
        }

        #--- get url if not already defined ------
        if(!$this->target_url) {
            if(!isset($this->target_id)) {
                throw new RenderException("NaviOption::__construct() needs either target_id or target_url");
            }
            $this->target_url= $PH->getUrl($this->target_id, &$this->target_params);
        }
    }

    #------------------------------------------------------------------------------------
    public function render() {
        $str_tooltip= $this->tooltip
            ? "title='$this->tooltip'"
            : '';

        if($this->active) {
            return "<li class=current $str_tooltip><a href='{$this->target_url}' $str_tooltip>{$this->name}</a></li>";
        }
        else {
            return "<li><a href='{$this->target_url}' $str_tooltip>{$this->name}</a></li>";
        }
    }
}

#=======================================================================================
# option separator
#=======================================================================================
class NaviOptionSeparator extends NaviOption {

    public function __construct() {
    }

    public function render() {
        return "<li class=separator>|</li>";
    }
}

#=======================================================================================
# option inside sub-navigation
#=======================================================================================
class NaviOptionWiki extends NaviOption {

    public function __construct() {
        global $PH;

        $this->target_url= "http://wiki.pixtur.de/index.php/{$PH->cur_page_id}";
        $this->name='Wiki+Help';
        $this->tooltip= 'Documentation and Discussion about this page';
    }
}


#========================================================================================
# PageElement
#========================================================================================
class Page {

    #--- members -----
    PUBLIC  $section_scheme;   # color-scheme of the active tab. set by renderHeaderTabs() (effects sub_navigtaion) ('projects'|'time'|etc.)
    PUBLIC  $content_open;    # open content-table
    PUBLIC  $title;
    PUBLIC  $title_minor;
    PUBLIC  $type;
    PUBLIC  $tabs;          # assoc. array with tab-definition
    PUBLIC  $cur_tab;
    PUBLIC  $options;       # assoc. array with options-definition
    PUBLIC  $crumbs;        # assoc. array with breadcrumb-definition
    PUBLIC  $html_close;
    PUBLIC  $content_col;
    PUBLIC  $use_jscalendar=false;
    PUBLIC  $autofocus_field=false;
    PUBLIC  $functions=array();

    #--- constructor ---------------------------
    PUBLIC function __construct($args=NULL) {

        ### set global page-var
        global $_PAGE;
        if(isset($_PAGE) && is_object($_PAGE)) {
            throw(new RenderException("'page' global var already defined!"));
        }
        $_PAGE= $this;

        ### set default-values ###
        $this->section_scheme="misc";   # color-scheme of the active tab. set by renderHeaderTabs() (effects sub_navigtaion) ('projects'|'time'|etc.)
        $this->content_open=false;    # open content-table
        $this->title="";
        $this->title_minor="";
        $this->type="";
        $this->tabs=array(
        	"home"		=>array(
                'target'=>"index.php?go=home",
                'title'=>"<span class=accesskey>H</span>ome",
                'bg'=>"misc"       ,
                'accesskey'=>'h',
                'tooltip'=>'Go to your home. Alt-h / Option-h'
            ),
        	"projects"	=>array(
                'target'=>"index.php?go=projList",
                'title'=>"<span class=accesskey>P</span>rojects",
                'tooltip'=>'Your projects. Alt-P / Option-P',
                'bg'=>"projects",
                'accesskey'=>'p'
            ),
        	"people"    =>array(
                'target'=>"index.php?go=personList",
                'title'=>"People",
                'tooltip'=>'Your related People',
                'bg'=>"people"
            ),
        	"companies"    =>array(
                'target'=>"index.php?go=companyList",
                'title'=>"Companies",
                'tooltip'=>'Your related Companies',
                'bg'=>"people"
            ),
        	#"calendar"  =>array(
            #    'target'=>"index.php?go=error",
            #    'title'=>"Calendar",
            #    'bg'=>"time"
            #),
        	#"reports"   =>array('target'=>"index.php?go=error",     'title'=>"Reports"),
        	"search"    =>array(
                'target'=>"index.php?go=error",
                'title'=>"<span class=accesskey>S</span>earch:&nbsp;",
                'html'=>"<input accesskey=s>",
                'tooltip'=>"Click Tab for complex search or enter something and hit return. Use ALT-S as shortcut. Use 'Search!' for 'Good Luck' "
            )
        );          # assoc. array with tab-definition
        $this->cur_tab="";
        $this->options=array();       # assoc. array with options-definition
        $this->crumbs=array();        # assoc. array with breadcrumb-definition

        ### set params ###
        if($args) {
            foreach($args as $key=>$value) {
                empty($this->$key);        #cause notification for unknown keys
                $this->$key= $value;
            }
        }

        ### put out header, some js-functions and styles for proper error-display
    }
    //--- render()---------------------
    function render() {
        echo $this->headline;
    }

    function __set($nm, $val)   {
        if (isset($this->$nm)) {
           $this->$nm = $val;
       } else {
            throw(new RenderException("can't set page->$nm"));
       }
    }

    #--- get --------------------------------------
    function __get($nm)
    {
       if (isset($this->$nm)) {
           return $r;
       } else {
            throw(new RenderException("can't read $nm"));
       }
   }
   
   
   
   
    /**
    * add a page function 
    */
    function add_function(PageFunction $fn)
    {
        global $PH;
        
        ### cancel, if not enough rights ###
        if(!$PH->getValidPageId($fn->target)) {

            /**
            * it's quiet common that the above statement returns NULL. Do not warn here
            */
            #trace("WARNING","invalid target $fn->target");
            return;
        }

        ### build url ###
        $fn->url= $PH->getUrl($fn->target, $fn->params);

        ### create key ###
        $key=count($this->functions);
        if(isset($fn->target)) {
            $key= $fn->target;
        }
        else if(isset($fn->id)){
            $key= strtolower($fn->id);
        }

        ### warn, if already defined? ###
        if(isset($this->functions[$key])) {
            echo "overwriting function with id '$key'";
        }
        
        ### if not given, get title for page-handle ###
        if(!isset($fn->name)) {
            $phandle=$PH->getValidPage($fn->target);
            $fn->name= $phandle->title;
            
        }

        $this->functions[$key]=$fn;
        $fn->parent_block= $this;
    }
}

#========================================================================================
# PageElement
#========================================================================================
# - all other elements of a page extend this class
# - maps the global var $page
class PageElement {
    public $page;

    #--- constructor--------------------------------
    PUBLIC function __construct() {
        global $_PAGE;
        if(!isset($_PAGE) || !is_object($_PAGE)) {
            throw(new RenderException("Cannot create PageElement s without Page-object"));
        }
        $this->page= $_PAGE;

    }

    #--- get --------------------------------------
    function __get($name)
    {
       if (isset($this->$name)) {
           return $this->$name;
       } else {
            throw(new RenderException("<pre>can't read '$name' in obj '" .@get_class($this). "'"));
       }
   }

    #--- set ---------------------------------------
    function __set($name, $val)   {
        if (isset($this->$name)) {
           $this->$name = $val;
       } else {
#            throw(new Exception("<pre>can't read $name"));
            trace("NOTICE","setting unknown member '$name' in pageElement of type '".get_class($this)."'");
            $this->$name = $val;
       }
    }

    #--- render -------------------------------------
    # note: derived classes should not implement render() but __toString()
    PUBLIC function render(&$arg=false) {
        if($arg) {
            return $this->__toString($arg);
        }
        else {
            return $this->__toString();
        }
    }
}


#========================================================================================
# HTML Start
#========================================================================================
class PageHtmlStart extends PageElement {

    PUBLIC function __toString() 
    {


        ### include theme-config ###
        if(file_exists("themes/".getCurTheme()."/theme_config.inc")) {
            require_once("themes/".getCurTheme()."/theme_config.inc");
        }

        $title= $this->page->title;
        $buffer= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'
        .'<html>'
        .'<head>'
        ."<meta http-equiv='content-type' content='text/html; charset=UTF-8'>"
        .'<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">'
        .'<META HTTP-EQUIV="EXPIRES" CONTENT="-1">'
        .'<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">'
        ."<title>$title</title>
        <link rel='stylesheet' title='top' media='screen' type='text/css' href='themes/".getCurTheme()."/styles.css'>";

        ### link print-style ###
        if(confGet('LINK_STYLE_PRINT')) {
            $buffer.="<link rel='stylesheet' media='print, embossed' type='text/css' href='themes/".getCurTheme()."/styles_print.css'>";
        }
        ### link alternative style ###
        if(0) {
            $buffer.= "<link rel='alternate stylesheet' title='Mozilla Lefthand' media='screen' type='text/css'  href='themes/".getCurTheme()."/styles_left.css'>
            
            ";
        }
        

        $buffer.="<script type=\"text/javascript\">
        <!--


        function rowOver(obj) {
           	obj.style.backgroundColor = \"". confGet('LIST_COLOR_HOVER'). "\";
           	
        }
        function rowOut(obj) {
            updateRowColor(obj);
        }
        function updateRowColor(obj) {
            name_checkbox= obj.id+'_chk';
            checkbox= document.getElementById(name_checkbox);
            if(checkbox.checked) {
                obj.style.backgroundColor = \"" .confGet('LIST_COLOR_SELECTED'). "\";
            }
            else {
               	if(obj.rowIndex%2) {
                    obj.style.backgroundColor = \"" .confGet('LIST_COLOR_ODD'). "\";
                }
                else {
                    obj.style.backgroundColor = \"". confGet('LIST_COLOR_EVEN') . "\";
                }
            }
        }
        function updateTableColor() {
            list= document.getElementsByTagName('input');
            for(i=0 ; i < list.length; i++) {
            
                var tr=list[i].parentNode.parentNode;
                if(tr.tagName== 'TR') {
                    updateRowColor(tr);
                }
            }
        }
        

        function rowToggle(obj) {
            name= obj.id+'_chk';
            checkbox= document.getElementById(name);
            if(checkbox.checked) {
                checkbox.checked=0;
            }
            else {
                checkbox.checked=1;
            }
            updateRowColor(obj);
        }
        function hideBlock(myName) {
        	myHead= document.getElementById(myName+\"_short\");
        	myBody= document.getElementById(myName+\"_long\");
        	myHead.style.display = 'block';
        	myBody.style.display = 'none';

    		var date = new Date();
		    date.setTime(date.getTime()+(30  *24*60*60*1000));
            document.cookie=  myName  +\"=hidden;expires=\"+  date.toGMTString()+  \";\";
        }
        function showBlock(myName) {
        	myHead= document.getElementById(myName+\"_short\");
        	myBody= document.getElementById(myName+\"_long\");
        	myHead.style.display = 'none';
        	myBody.style.display = 'block';

    		var date = new Date();
		    date.setTime(date.getTime()+(-1  *24*60*60*1000));
            document.cookie=  myName  +\"=1;expires=\"+  date.toGMTString()+  \";\";
        }
        function stopEvent(evt) {
            evt.cancelBubble=true;
            return false;
        }

        //-->
        </script>
        <script type='text/javascript' src='js/contextMenus.js'></script>
        <script type='text/javascript'>

        cMenu.menus=new Object();
        </script>

        ";

        ### add calendar-functions for form-pages ###
        # NOTE: including calendar tremedously increases loading time!
        if($this->page->use_jscalendar) {
            $buffer.= '<style type="text/css">@import url(themes/'.getCurTheme().'/calendar-win2k-1.css);</style>'
            . '<script type="text/javascript" src="js/calendar.js"></script>'
            . '<script type="text/javascript" src="js/lang/calendar-en.js"></script>'
            . '<script type="text/javascript" src="js/calendar-setup.js"></script>'
            . '<script type="text/javascript" src="js/dragslider.js"></script>';
        }
        $buffer.= "
        </head>";
        $buffer.='<body onload="';
        if($this->page->autofocus_field) {
             $buffer.='document.my_form.'. $this->page->autofocus_field .'.focus(); document.my_form.'. $this->page->autofocus_field. '.select();';
        }
        else {
            $buffer.="initContextMenus();";
        }
        $buffer.="updateTableColor();";
        $buffer.='">'; # close body tag & onload
        $buffer.= "<noscript>
        <div class=\"errorOutput\"><b>Warning:</B> This page requires java-script to be enabled.
        <br>Please adjust your browser-settings.<br>
        </div>
        </noscript>

";
        return $buffer;
    }
}

#========================================================================================
# HTML End
#========================================================================================
class PageHtmlEnd extends PageElement {

    PUBLIC function __toString() {

        $buffer="";
        $footer= new PageFooter;
        $buffer.= $footer->render();
    	$buffer.= "</body></html>";
        return $buffer;
    }
}

#========================================================================================
# Quick new
#========================================================================================
class PageQuickNew extends PageElement {

    PUBLIC function __toString() {
        $buffer=
            '<div id=quicknew title="Add to selected task(s). Shortcut:ALT-N">'
            .'<input type="hidden" name="noedit" value="0">'
            .'<input type="hidden" name="newtype" value="task">'
            ."<label><span class=accesskey>n</span>ew </label>"
            ."<select name=type>"
            .'<option value="task" selected>Task</option>'
            .'<option value="effort">Effort</option>'
            .'<option value="comment">Comment</option>'
            .'</select>'
            .'<input class=inp accesskey=n size=50 name=new_name>'
            ."<input  class=button type=button value ='Add Now' onclick=\"javascript:document.my_form.go.value='quickNew';document.my_form.noedit.value='1';document.my_form.submit();\">"
            ."<input  class=button type=button value ='Edit'  onclick=\"javascript:document.my_form.go.value='quickNew';document.my_form.submit();\">"
            #."<span class=help><a href=''>help</a></span>"
            ."<span class=hint>E.g. <b>BUG: application crashes on IE !! 4h in 5 days</b>  (Creates task labeled 'bug', prio1, with estimated 4hours and due in 4 days)</span>"
            .'</div>'
            ;
        global $PH;
        $PH->go_submit='quickNew';


        return $buffer;
    }

}




#========================================================================================
# Header
#========================================================================================
class PageHeader extends PageElement {

    PUBLIC function __toString() {
        global $PH;
        global $auth;

        echo(new PageHtmlStart);

        $logout_url=$PH->getUrl('logout');

        $buffer="
                <div id=header>
            	<div id=logo>";
    	$buffer.="<div class=text>"
                ."<a title='" . confGet('APP_NAME') ." - free web based project management' href='http://streber.sourceforge.net'>"
                .confGet('APP_TITLE_HEADER')
                ."</a>"
                ."</div>";
    	$buffer.="<img src='img/logo.gif'>
            	</div>";

        ### account if logged in ###
        if($auth->cur_user) {
            $link_home= $PH->getLink('personView',$auth->cur_user->name,array('person'=>$auth->cur_user->id));;

            $buffer.="
        	<div id=login>
        		<ul class=name>
        			<li class=user>you are
        			<li class=name>$link_home
        		</ul>
        		<ul class=features>";
        		
            ### client-view ###
            if(get('show_client_view') && ($url=$PH->getClientViewUrl(true)) ) {
                $buffer.="<li><a title='Return to normal view' href='".$url."'>Leave Client-View</a>";
        	    $buffer.="<li class=br>|";

            }
            else if($url=$PH->getClientViewUrl()) {

                ### hide for clients ###
                global $g_user_profiles;
                global $g_user_profile_names;
                $profile=$g_user_profiles[ $g_user_profile_names[$auth->cur_user->profile] ];
                
                if(!isset($profile['hide_clientview'])) {
                    $buffer.="<li><a title='How this page looks for clients' href='".$url."'>Client view</a>";
            	    $buffer.="<li class=br>|";
                }
                
            }
        		    
           $buffer.="
        			<li><a href='$logout_url'>Logout</a>
        		</ul>
        	</div>";
        }
        
    	$tabs= new PageHeaderTabs;
        $buffer.= $tabs->render();
        #echo(new PageHeaderTabs);
    	$buffer.="</div>";


        $crumbs= new PageHeaderCrumbs;

        $buffer.=$crumbs->render();

        #--- write message ---
        global $PH;
        if($PH->message) {
            $buffer.="<div id=message>$PH->message</div>";
        }

        $title=new PageTitle;
        $buffer.=$title->render();
        
        $functions= new PageFunctions();
        $buffer.= $functions->render();   # actually this should be a string-context for __toString , but it isn't ???

        return $buffer;
    }
}


#========================================================================================
# Header >> Tabs
#========================================================================================
class PageHeaderTabs extends PageElement {

    PUBLIC function __toString()
    {
    #	global $tabs, $cur_tab, $str, $header_cur_tab_bg;

    	$buffer= '<ul id=tabs>';

    	$tab_found=false;
        if(!isset($this->page->tabs) || !is_array($this->page->tabs)) {
            throw new myException("tabs not defined");
        }
        $page=$this->page;
        foreach($page->tabs  as $tab=>$values){

      		$bg=	isset($values['bg'])
                ? $values['bg']
                : "misc";
    		$active="";
    		$target= isset($values['target']) ? $values['target'] : print "WARNING";

    		#--- current tab ----
    		if($tab === $this->page->cur_tab) {
    			$active="current";
    			$page->section_scheme= $bg;
                $tab_found=true;
    		}
    		else {
                $bg.= "_shade"; # shade non-active tabs
    		}
    		$bg= "bg_$bg";

            $accesskey= isset($values['accesskey'])
                ? $accesskey='accesskey='.$values['accesskey']
                : "";

    		$tooltip= isset($values['tooltip'])
                ? 'title="'. $values['tooltip'].'" '
                : "";

            $html= isset($values['html'])
                ? $html= $values['html']
                : "";

    		$buffer.= "<li id=tab_{$tab} class='{$active} {$bg}' $tooltip>\n";
    		$buffer.= "<a href='$target'  $accesskey>";
    		$buffer.= $values['title'];
    		$buffer.= '</a>';
            $buffer.= $html;
    	}
    	$buffer.= '</ul>';
        if(!$tab_found) {
            trace("NOTICE","Could not find tab '{$this->page->cur_tab}' in list...");
            trace("NOTICE",$this->page->tabs);
        }
        return $buffer;
    }
}   # end of PageHeaderTabs

#========================================================================================
# PageHeaderCrumbs
#========================================================================================
class PageHeaderCrumbs extends PageElement {

    PUBLIC function __toString() {
        $scheme=$this->page->section_scheme;
        $buffer="<div id='nav_sub' class=bg_$scheme><ul>";
    	if($this->page->crumbs) {
            ### breadcrumbs ###
    		$buffer.= "<li>";
            ### go up ###
    		$buffer.="<ul class=breadcrumbs_up><li>";
		    $count=count($this->page->crumbs)-2;
		    while($count >=0) {
		        $str=$this->page->crumbs[$count];
                
		        if(preg_match("/ref=\"([^\"]*)\"/", $str,$matches)) {
		           $buffer.= "<a href='$matches[1]' title='Go to parent / alt-U' accesskey=u>^</a>";
		            break;
		        }
		        $count--;
		    }
    		$buffer.= "</ul>";


    		$buffer.="<ul id=breadcrumbs class={$this->page->section_scheme}>";
    		$buffer.="";
            $sign="";
            $page=$this->page;

            $count=0;       # count added crumbs to mark the last crumb as current
            $style="";
            foreach($page->crumbs as $crumb) {

                if($crumb) {
                    $count++;
                    if($count == count($page->crumbs)) {
                        $style="class='current'";
                    }
                    if($crumb =="|") {
                        $buffer.= "<li class=separator>|";
                    }
                    else {
                        $buffer.= "<li $style>{$sign}$crumb";
                    }
                    $sign=" &gt; ";
                }
            }
    		$buffer.= "</ul>";
    	}

    	### options ###
    	if(@$this->page->options) {
    		$buffer.= "<li><ul id=options>";
    		$buffer.= "<li class=first><b>[</b>";
            $page= $this->page;
            $tmp_counter=0;                 # HACK! just to highlight a dummy breadcrump to test GUI-style

            foreach($page->options as $option) {
                $tmp_counter++;

                if($option instanceOf NaviOption) {
                    $buffer.= $option->render();
                }

     /*           #--- active? ---
                if($tmp_counter == 1) {
                    $buffer.= "<li class=current>$option";
                }
                else {
                    if($option == "") {
                        $buffer.= "<li class=separator>|";
                    }
                    else {
                        $buffer.= "<li>$option";
                    }
                }
                */
            }
    		$buffer.= "<li class=last><b>]</b>";
    		$buffer.= "</ul>";
    	}
        $buffer.="</ul></div>";
    #    $buffer.= "</div>";
    #    $buffer.= "<div id=nav_sub class=$this->page->header_cur_tab_bg> ";
        return $buffer;
    }
}



#========================================================================================
# PageTitle
#========================================================================================
class PageTitle extends PageElement {


    PUBLIC function __toString() {
        $buffer="";

       	$buffer.= "<div id=headline>";
    	if($this->page->type) {
    		$buffer.= "<div class=type>{$this->page->type}</div>";
    	}
    	$buffer.= "<h1 class=title>{$this->page->title}";
    	if($this->page->title_minor) {
    		$buffer.= "<span class=minor>/ {$this->page->title_minor}</span>";
    	}
    	$buffer.= "</h1>";
    	$buffer.= "</div>";


        return $buffer;
    }
}


#========================================================================================
# PageFunctions
#========================================================================================
class PageFunctions extends PageElement {


    PUBLIC function __toString() {
        $buffer="";

        /**
        * @@@ implement page-functions here
        */

        $buffer.="<div class=page_functions2>";
        if($this->page->functions) {
            foreach($this->page->functions as $key=>$fn) {


                if($fn->tooltip) {
            	    $buffer.="<a href='$fn->url' title='$fn->tooltip'>";
                }
                else {
            	    $buffer.="<a href='$fn->url'>";
                }


            	if($fn->icon) {
            	    $buffer.="<img src='themes/".getCurTheme()."/icons/$fn->icon.gif'>";
            	}
            	
            	$buffer.="<span>$fn->name</span></a>";
            }
        }
        $buffer.="</div>";
        return $buffer;
    }
}


#===========================================================================================
# PageContentStart
#===========================================================================================
class PageContentOpen extends PageElement {


    PUBLIC function __toString() {
        global $PH;

        if($this->page->content_open) {
            trace("WARNING","Content-table has already been opened. Wrong HTML-Structure? ");
        }
        $this->page->content_col=1;
        $this->page->content_open=true;
        $buffer="";
        $buffer.= '<form name="my_form" action="index.php" method="post">';

        ### pass from-handle? ###
        if(!$PH->cur_page_md5) {
            if(!($PH->cur_page_md5= get('from')) && !$PH->cur_page->ignore_from_handles) {
                trace("WARNING","this page doesn't have a from-handle");
            }
        }
        else {
            $buffer.="<input type=hidden name=from value='$PH->cur_page_md5'>";
        }

        $buffer.= "<div id=layout>";
        return $buffer;
    }
}

#===========================================================================================
# PageContentStart_Columns
#===========================================================================================
class PageContentOpen_Columns extends PageElement {

    PUBLIC function __toString() {

        global $PH;
        if($this->page->content_open) {
            trace("WARNING","Content-table has already been opened. Wrong HTML-Structure? ");
        }
        $this->page->content_col=1;
        $this->page->content_open=true;
        $buffer="";
        $buffer.= '<form name="my_form" action="index.php" method="post">';

        ### pass from-handle? ###
        if(!$PH->cur_page_md5) {
            if(!$PH->cur_page_md5= get('from')) {
                trace("WARNING","this page doesn't have a from-handle");
            }
            $buffer.="<input type=hidden name=from value='$PH->cur_page_md5'>";
        }
        else {
            $buffer.="<input type=hidden name=from value='$PH->cur_page_md5'>";
        }

        $buffer.= "<div id=c1>";
        return $buffer;
    }
}

#===========================================================================================
# PageContentClose
#===========================================================================================
class PageContentClose extends PageElement {

    PUBLIC function __toString() {
        global $PH;
        if(!$this->page->content_open) {
            trace("WARNING","No content-table to close. Wrong HTML-structure?");
        }
        $this->page->content_open= false;

        $buffer="";
        #$buffer.="<td></tr>";
        #$buffer.="</table>";
        $buffer.= "</div>";
        $go= $PH->go_submit
             ? $PH->go_submit
             : 'home';

        echo "<input type=hidden name='go' value='$go'>";
        $buffer.= "</form>";


        return $buffer;
    }
}

class PageContentNextCol extends PageElement {
    PUBLIC function __toString() {
        if(!$this->page->content_open) {
            trace("WARNING","No content-table to close. Wrong HTML-structure?");
        }
        $this->page->content_col++;
        #$buffer="</td><td id=c{$this->page->content_col}>";
        $buffer="</div><div id=c2>";
        return $buffer;
    }
}

class PageFooter extends PageElement {

    PUBLIC function __toString() {
        global $TIME_START;
        global $DB_ITEMS_LOADED;
        global $time_total;
        $buffer='';
        $TIME_END=microtime(1);
        $time_total= $TIME_END- $TIME_START;
        $time=($TIME_END-$TIME_START)*1000;
        $time_str=sprintf("%.0f",$time);

        $buffer.="<div id=footer>"
            .confGet('APP_NAME')." ".confGet('STREBER_VERSION')." ".confGet('STREBER_VERSION_DATE')
            ."  rendered in $time_str ms  querrying approx. $DB_ITEMS_LOADED db-fields<br>"
            .render_measures()
            ."</div>";
        #$end=new PageHtmlEnd;
        #$buffer.=$end->render();
        return $buffer;
    }
}



?>