<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * classes for rendering list columns
 *
 * @author: Thomas Mann
 * @uses:
 * @usedby:list-definitions in pageFunctions
 *
 */



 /**
 * base-class for list columns is derived
 *
 */
class ListBlockCol {
    public $width='1%';
    public $key;
    public $sort;
    public $tooltip;
    public $parent_block;
    public $style='';
    public $name='';

	//--- constructor ------------------
	function __construct($name_or_arguments=NULL,$key="", $sort=0,$tooltip=false,$width=false) {
  		if($width) {
            $this->width=$width;
        }
		if(!$this->key) { $this->key = $key;};
		$this->sort= $sort;
		if($tooltip) {
            $this->tooltip= $tooltip;
        }
        $this->functions=array();

		//--- either define as param-list or hash ----
		if(is_string($name_or_arguments)) {
			$this->name= $name_or_arguments;
		}
		else if (is_array($name_or_arguments)){
			foreach($name_or_arguments as $key=>$value) {
				$this->$key = $value;
			}
		}
		else {
			return;
		}
    }

	function render_th() 
	{
		global $str;
        global $PH;
        global $auth;

		if($this->key=="_select_col_") {
			echo "<th class=select_col title=\"{$str['list_tooltip_select_col']}\" style='width:1%'>";
			echo "<a href=somehwere>";
			echo "<img src=\"themes/".getCurTheme()."/img/list_check_range.png\">";
			echo "</a>";
			echo "</th>";
		}
		else{
			#--- width ---
			$width="";
			if($this->width) {
				$width= "width=\"$this->width\"";
			}

			#--- sorting ----
			$sort_style="";
			$sort_img="";
			if($this->sort != 0) {
				$sort_style="sort_primary";
				$sort_img= "<img src=\"themes/".getCurTheme()."/img/list_sort_asc_1st.png\">";

			}

			#--- tool tip-----
			$tooltip="";
			if(isset($this->tooltip) && $this->tooltip != "") {
				$tooltip="title='$this->tooltip'";
			}


			#--- text & link -----
    		echo "<th $tooltip class=\"$sort_style\" $width>";
            if($this->key) {
                $link=$PH->getLink('changeSort',$this->name,array(
                    'tableid'=>$this->parent_block->id,
                    'column'=>$this->key,
                    'pageid'=>$PH->cur_page->id));
    			#echo "$sort_img<a href=\"$target\">";
    			#echo $this->name;
    			#echo "</a>";
                echo $link;
            }
            else {
                echo $this->name;
            }
            echo "</th>";

		}
	}

	function render_tr(&$obj, $style='') {
		#--- it's the checkbox -col ---
		if($this->key=="_select_col_"){
			echo "<td width=10 class=select_col onClick=\"stopEvent(event)\"><input  type=checkbox id={$this->parent_block->id}_{$obj->id}_chk name={$this->parent_block->id}_{$obj->id}_chk></td>";
		}
		#--- property of object undefined ?----
		else if(!isset($this->key)) {
			echo "<td>Undefined key</td>";

		}
		#--- normal output ---
		else {
			$style= (isset($this->style) && $this->style!="") ? "class=\"$->style\"" : "";
			echo "<td $style>";

			//--- try to get param ---
			$key= $this->key;
			if(!isset($obj->$key)) {
				print "$key";
			}
			$value= isset($obj->$key) ? $obj->$key : "??$key";

			if(isset($this->func)) {
				echo eval($this->func);
			}
			else {
				echo $value;
			}
			echo "</td>";
		}
	}
}



/***********************************************************
* derived versions of ListBlockCol for special purposes
*/





/*********
* test version of column-rendering using code-eval
*
* early try of custimized field-rendering in lists. This is prone to errors. For most
* purposes of customized rendering, we suggest ListBlockColHtml.
*
* @usedby: not used / obsolete
*/
class ListBlockColEval extends ListBlockCol{

	function render_tr(&$obj, $style='') {
		$style= (isset($this->style) && $this->style!="") ? "class=\"$->style\"" : "";
		echo "<td $style>";

		//--- try to get param ---
		$key= $this->key;
		$value= isset($obj->$key) ? $obj->$key : "??$key";

		if(isset($this->eval)) {
			$tmp="\$value=\"".$this->eval."\";";
			eval($tmp);
			echo $value;
		}
		else {
			echo "<a href=\"\">$value</a>";
		}
		echo "</td>";
	}
}


/**
* render list-columns as with html-formated template using {key} to output data
*
* This is the preferred method for displaying formatted output.
* example:<pre>
*
*  		$this->add_col( new ListBlockColHtml(array(
*			'key'=>'phone',
*			'name'=>"Phone",
*			'tooltip'=>"Phone-Number",
*			'format'=>'<nobr>{?phone}</nobr>'
*		)));
*        </pre>
*
* @usedby:most listBlock defintions
*/
class ListBlockColHtml extends ListBlockCol
{
	function render_tr(&$obj, $style="") {
		if(!isset($obj) || !is_object($obj)) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}
		$key= $this->key;
		$format= $this->format;
		$rest=$this->format;

		while(	ereg("{\?([a-z_]*)}(.*)",$rest, $matches) ) {
			$key=$matches[1];
	  		$rest=$matches[2];
			$value= isset($obj->$key) ? $obj->$key : "??$key";
			$format=ereg_replace("{\?$key}",$value,$format);
		}
		print "<td>$format</td>";
	}
}



/**
* render list-columns by custum-method of object
*
* example:<pre>
*
*    	$this->add_col( new ListBlockColMethod(array(
*           'key'=>'company',
*    		'name'=>"Company",
*    		'tooltip'=>"Company",
*    		'func'=>'getCompanyLink',
*    	)));
*
*
* </pre>
*
* @usedby: most listBlock-defintions
*/
class ListBlockColMethod extends ListBlockCol
{
	function render_tr(&$obj, $style="") {
		if(!isset($obj) || !is_object($obj)) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}
        $out=call_user_func(array($obj,$this->func));
        if($this->style) {
    		print "<td class='$this->style'>$out</td>";
        }
        else {
    		print "<td>$out</td>";
        }
	}
}


/**
* render list-column as time in hours
*
*/
class ListBlockColTime extends ListBlockCol{
    public $style='right';

	function render_tr(&$obj, $style_overwrite="") {
		if(!isset($obj) || !is_object($obj)) {
			trace("WARNING","ListBlockColTime->render_tr() called without valid object");
   			return;
		}
		$key= $this->key;
        $value=$obj->$key;
        $format='';
        if($value && $value!="00:00:00") {
            $format=$value;
            ereg("(..):(..):(..)",$value,$matches);
            list($all,$hh,$mm,$ss)=$matches;
            #$format="$hh:$mm";
            $hours=$hh*1+ ($mm/60);
            $format=round($hours,1)."<span class='entity'>h</span>";
        }
        else {
        }
		print "<td class='$this->style $style_overwrite'>$format</td>";
	}
}


/**
* render list-column as external link (removing http:/ and reduced to sane lenght)
*/
class ListBlockColLinkExtern extends ListBlockCol
{
	function render_tr(&$obj, $style_overwrite="") {
		if(!isset($obj) || !is_object($obj)) {
			trace("WARNING","ListBlockLinkExtern->render_tr() called without valid object");
   			return;
		}
		$key= $this->key;
        $value=$obj->$key;
        if($value) {

            $format= url2linkExtern($value);
    		print "<td class='$this->style $style_overwrite'>$format</td>";
        }
        else {
    		print "<td></td>";
        }
	}
}

/**
* render list-column as date with distance to today as tooltip
*/
class ListBlockColDate extends ListBlockCol{
    public $style='right';

	function render_tr(&$obj, $style="") {
		if(!isset($obj) || !is_object($obj)) {
			trace("WARNING","ListBlockColDate->render_tr() called without valid object");
   			return;
		}
		$key= $this->key;
        $value=$obj->$key;

        $tooltip="";
        if($value && $value!="0000-00-00") {
            $value_str= date("d.m.Y",strtotime($value) );
            $days= floor((time()- strtotime($value))/60/60/24);
            if($days > 0) {
                $days_str="since $days days";
            }
            else if($days<0) {
                $days_str="in $days days";
            }
            else {
                $days_str="Today";
            }
            $tooltip="title='$days_str'";
        }
        else {
            $value_str="-";
        }
		print "<td class='$this->style' $tooltip><nobr>$value_str</nobr></td>";
	}
}





?>