<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * classes related to rendering lists
 *
 * included by: @render_page
 *
 * @author:     Thomas Mann
 * @uses:
 * @usedby:     most pages
 */

require_once("render/render_list_column.inc");
require_once("render/render_list_column_special.inc");






/**
* list-functions provide ways of manipulating entries of a list (like add/edit/delete).
* They are rendered as icons, drop-downmenu or context-menu.
*
* how they work:
* - List-Function submit the current page (yes, the complete page has only ONE form).
* before this they write the target-value into the go-field (which is always rendered
* by PageContentClose(). This makes passing of addition parameters to the parameters really tricky.
*
* One solution for this problem would be to collect all params used by added functions and
* append hidden-fields at the of of the page (writing them after each list would not work
* because more then one list might use the same param). Then those paremter hat to be written
* with jave-script.
*
* This definately has to be done for cascade context-menus. 
*
* Currently ListFunction only work for pageHandles without additional parameters.
* Sooner or later addition functions or flags should be implemented (like creating
* new tasks with an issue_report and setting status to a certain value
*
* @usedby   ListBlock
*/
class ListFunction {
    public $target;                     # link-target
    public $active_for_single=true;
    public $active_for_multiple=true;
    public $active_always=false;        #
    public $name;                       # name/Tootip
    public $id;                         # id inside html-struction (for icons)
    public $icon;                       # name of function icon
    public $parent_block;
    public $tooltip;
    public $context_menu=false;      # show in context-menus
    public $dropdown_menu=true;      # show in dropdown


    public function __construct($args=NULL) 
    {
        foreach($args as $key=>$value) {
            is_null($this->$key);   # cause E_NOTICE if member not defined
            $this->$key=$value;
        }
        if(!$this->target || !$this->id || !$this->name) {
            throw new RenderException("ListFunctions require name,id, and target");
        }
        
        ### add to dropdown-menu ? ###
        #if(!$this->icon) {
        #    $this->dropdown_menu=true;
        #}
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

/**
* provide front-end for rendering and manimpulating lists
*
* usage: <pre>
*
    $list= new ListBloc('example');                 #create instances
	$list->title="Open tasks";                      #set title
	$list_tasks->add_col( new ListBlockCol(array(   #add columns
		'key'=>'_select_col_',
	)));

    $list_tasks->add_function(new ListFunction(array( #add functions
        'target'=>$PH->getPage('taskEdit')->id,
        'name'  =>'Edit',
        'id'    =>'taskEdit',
        'icon'  =>'edit',
        'context_menu'=>'submit',
    )));

    $list_tasks->render();                          # render default output (simple) or...

    ### complex rendering to calculate summary and make custom-style-assigments
	$list->render_header();
	$list->render_thead();

    $count_estimated=0;
	foreach($tasks as $t) {
        $count_estimated+=$t->estimated;
		$list_tasks->render_trow(&$t,$style);
	}
    $list->summary= count($tasks)." tasks with estimated $count_estimated hours of work";
	$list->render_tfoot();

* @uses     ListFunction
* @usedby   most pages
*/
class ListBlock extends PageBlock
{
	public $columns=array();
    public $functions=array();
	public $row_count=0;
    public $show_functions=false;           # is set true, when adding functions without icon
    public $show_pages=false;
    public $show_items=false;
    public $show_icons=false;
    public $summary;
    public $no_items_html='';     # should be overwritten by 'create first link'

	//=== constructor ================================================
	function __construct($id,$bg_style="bg_misc",$title="") {
        parent::__construct(array('id'=>$id, 'bg_style'=>$bg_style, 'title'=>$title));  // actually we don't need to pass those
	}


    public function render_header() {
        parent::render_blockStart();

		#--- start table (needs to be closed later)
        echo "<table cellpadding=0 cellspacing=0 id=$this->id class='list'"
        .">"; # required by Safari  & IE 5.2 MAC)

    }

	//== render thead ==========================================================
	function render_thead() {
		global $str;

		echo "<thead>";
		echo "<tr>";

		foreach($this->columns as $c) {
			$c->render_th();
		}
		echo "</tr>";
		echo "</thead>";

	}

	//=== render row ===========================================================
	function render_trow($obj, $style='') {
		$this->row_count++;
		$oddeven=($this->row_count %2) ? "odd" : "even";
        if(isset($obj->pub_level)) {
            $level=$obj->pub_level;
            global $PUB_LEVEL_NAMES;
            $style.=" ".$PUB_LEVEL_NAMES[$level];
        }

		echo "\n<tr id={$this->id}_{$obj->id} class='$style $oddeven' onMouseover=\"rowOver(this)\" onClick=\"rowToggle(this);\" onMouseout=\"rowOut(this)\">";
		foreach($this->columns as $c) {
			$c->render_tr($obj);
		}
		echo "</tr>";
	}

	//=== render tfoot =========================================================
	function render_tfoot() {
        global $PH;
		echo "</table>";
        #--- footer extras ----

        $context_menu_def="";
        $context_menu_rows="";

        if($this->show_pages || $this->show_functions || $this->show_icons) {
            echo "<div class=footer>";

            #--- icons --------
            if($this->show_icons && $this->functions) {
                echo "<span class=icons>";
                foreach($this->functions as $f) {
                    if($f->icon) {

                        $tooltip=$f->tooltip
                            ? "title='$f->tooltip'"
                            : "title='$f->name'";
                        echo "<a $tooltip href=\"javascript:document.my_form.go.value='$f->target';document.my_form.submit();\"><img src='themes/".getCurTheme()."/icons/$f->icon.gif'></a>";
                    }
                }
                echo "</span>";
            }

            #--- menu ------------------
            if($this->show_functions && $this->functions) {
                echo "<span class=functions>";
                echo "";
                echo "<select class=menu name='func' size=1 onChange=\"javascript:document.my_form.go.value=document.my_form.func.options[document.my_form.func.selectedIndex].value;document.my_form.submit();\">";
                echo "<option value='home' selected>do...</option>";
                foreach($this->functions as $f) {
                    if($this->show_icons && $f->icon) {
                    }
                    else {
                        echo "<option value='$f->target'>$f->name</option>";
                    }
                }
                echo "</select>";
                echo "</span>";
            }

            #--- context menu ------------
            {

                foreach($this->functions as $f) {
                    if($f->context_menu) {
                        $context_menu_def.="{type:'submit', name:'$f->name',   go:'$f->target'},\n";
                        $context_menu_rows.="<tr><td class=menuItem>$f->name</td></tr>";
                    }
                }
            }

            #--- summary --------------------
            if($this->summary) {
                echo "<span class=summary>$this->summary</span>";
            }

            if($this->show_pages) {
                echo "<span class=items>20 of 234&nbsp;&nbsp;&nbsp;</span>";
                echo "<span class=pages>  Page 1 2 3 4</span>";
            }
            echo "</div>";


        }
        parent::render_blockEnd();


        #--- write context-menu-definition -------------
        if($context_menu_def) {

            echo "<script  type='text/javascript'>
                    cMenu.menus['$this->id']=
                    {   menuID:'contextMenu_{$this->id}',
                        items:[
                        $context_menu_def
                    ] };</script>";

            echo "<div id='contextMenu_{$this->id}' class='contextMenus' onclick='hideContextMenus( )'
                onmouseup='execMenu(event)' onmouseover='toggleHighlight(event)'
                onmouseout='toggleHighlight(event)' style='display:none'>
                <table><tbody>
                $context_menu_rows
                </tbody></table>
                </div>";
        }
	}

    /**
    * if no items to show, display alternative content
    */
    public function render_tfoot_empty() {
        echo "</table><div class=empty>{$this->no_items_html}</div></div>";
    }

    /**
    * render complete list-block automatically
    *
    * @@@this should RETURN as string not PRINT one
    */
    public function render_list(&$list=NULL) {
   		$this->render_header();
        if($list || !$this->no_items_html) {
    		$this->render_thead();
            if($list) {
    		    foreach($list as $e) {
        			$this->render_trow($e);
    		    }
            }
    		$this->render_tfoot();
        }
        else {
            $this->render_tfoot_empty();
        }
    }

    #=== add column =========================
    function add_col(ListBlockCol $col) {
        if(!$col || !($col instanceof ListBlockCol)) {
            throw new myException("add_col requires column-object");
        }
        $key=count($this->columns);
        if($col->key) {
            $key= $col->key;
        }
        else if(isset($col->id)){
            $key= strtolower($col->id);
        }
        #else if(isset($col->name)){
        #    $key= strtolower($col->name);
        #}
        $this->columns[$key]=$col;
        $col->parent_block= $this;
    }

    /**
    * add a function to the list as icon, menu or context-menus
    */
    function add_function(ListFunction $fn)
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


        $key=count($this->functions);
        if(isset($fn->target)) {
            $key= $fn->target;
        }
        else if(isset($fn->id)){
            $key= strtolower($fn->id);
        }

        ### already defined? ###

        if(isset($this->functions[$key])) {
            echo "overwriting function with id '$key'";
        }
        $this->functions[$key]=$fn;
        $fn->parent_block= $this;
        if(!$fn->icon && !$fn->dropdown_menu==false) {
            $this->show_functions=true;
        }
        else {
            $this->show_icons=true;
        }
    }
}







?>