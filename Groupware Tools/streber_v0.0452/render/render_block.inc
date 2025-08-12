<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * classes related to rendering blocks which can be collapsed
 *
 * included by: @render_page
 *
 * @author:     Thomas Mann
 * @uses:
 * @usedby:     lists
 */

require_once("render/render_list_column.inc");
require_once("render/render_list_column_special.inc");



/**
* pagefunctions for editing the currently displayed obj (eg. Delete a task)
*
*/
class BlockFunction 
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
    public $active;             # flag if options should be highlighted as active

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
  


/**
* provide html-code for a html-block that can be toggled
*/
class PageBlock extends PageElement 
{
	public $bg_style='bg_misc';
    public $id=NULL;
    public $title='';
    public $title_append_hidden;
    public $block_functions= array();
    

	//== constructor ========================================================================
    public function __construct($args) {
        parent::__construct();

        foreach($args as $key=>$value) {
            is_null($this->$key);   # cause E_NOTICE if member not defined
            $this->$key=$value;
        }

        ### be sure id is present ###
        if(!$this->id) {
            $this->id= preg_replace("/[^\w\d]/","",$this->title);       # remove all special chars
        }

        ### add current page id to id to make block unique for storing toggle-state as cookie ###
        global $PH;
        $this->id= $PH->cur_page->id."_".$this->id;
    }

    /*public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value) {
        $this->$name=$value;
    }*/

	//== render blockStart ========================================================================
	public function render_blockStart($hidden=false)
	{
	    global $auth;

		$title=
		$bg_style="bg_time";      # bg_proj|bg_time etc.


        ### hidden-cookie set? ###
        if(get('b_'.$this->id)=='hidden') {
            $hidden=true;
        }

		#--- collapsed version ----
		{
            if($hidden) {
                $style= "";
            }
            else {
                $style= "style='display:none'" ;
            }
			echo "<!-- start of list-block {$this->id} -->\n";
	  		echo "<div id=b_{$this->id}_short class=\"block {$this->bg_style}_shade closed\" $style>\n";
			echo "<div class=\"header\">\n";
				echo "<h2 onclick=\"showBlock('b_$this->id')\" class=table_name >\n";
				#echo "<img src=\"themes/".getCurTheme()."/img/toggle_block_closed.gif\">";
				echo "$this->title";
                if($this->title_append_hidden) {
                    echo " <span class=append>$this->title_append_hidden</span>";
                }
                echo "</h2>\n";
			echo "</div>";
			echo "</div>";
		}
		#--- expanded -------------
		{
            if($hidden) {
                $style= "style='display:none'" ;
            }
            else {
                $style= "";
            }
			echo "<!-- start of list-block -->\n";
	  		echo "<div id=b_{$this->id}_long class=\"block {$this->bg_style}_shade opened\" $style>";
			echo "<div class=\"header\">";
				echo "<h2 class=table_name onclick=\"hideBlock('b_$this->id')\">";
				#echo "<img src=\"themes/".getCurTheme()."/img/toggle_block_closed.gif\">";
				echo "$this->title";
				echo "</h2>";
				
				if($this->block_functions) {
				    echo "<span class=block_options>";
				    foreach($this->block_functions as $fn) {

                        $style_active=$fn->active
                        ? "class=active"
                        : "";
				        echo "<span $style_active><a href='$fn->url'>$fn->name</a></span>";
				    }
                    echo "</span>";
				}
				
			echo "</div>";

		}
	}


	//== render header ========================================================================
    public function render_blockEnd() 
    {
        echo "\n</div><!-- end {$this->id} -->\n";
    }


    /**
    * add a block function 
    */
    function add_blockFunction(BlockFunction $fn)
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

        ### create key if undefined ###
        if(isset($fn->key)) {

        }
        else {
            if(isset($fn->target)) {
                $fn->key= $fn->target;
            }
            else if(isset($fn->id)){
                $fn->key= strtolower($fn->id);
            }
        }
        

        ### warn, if already defined? ###
        if(isset($this->block_functions[$fn->key])) {
            echo "overwriting function with id '$key'";
        }
        
        ### if not given, get title for page-handle ###
        if(!isset($fn->name)) {
            $phandle=$PH->getValidPage($fn->target);
            $fn->name= $phandle->title;
            
        }

        $this->block_functions[$fn->key]=$fn;
        $fn->parent_block= $this;
    }
}





?>