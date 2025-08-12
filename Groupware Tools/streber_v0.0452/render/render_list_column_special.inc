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




class ListBlockCol_ProjectEffortSum extends ListBlockCol{
    public $name='Efforts';
    public $tooltip="Not cleared project efforts in hours";

    public function __construct($args=NULL) {
        parent::__construct($args);
    }
	function render_tr(&$obj, $style="") {
		if(!isset($obj) || !$obj instanceof Project) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}

        $value= round($obj->getEffortsSum()/60/60,1);
		if($value) {
            print "<td>{$value}h</td>";
        }
        else {
            print "<td></td>";
        }
	}
}



class ListBlockCol_TimeDue extends ListBlockCol{
    public $name='Due';
    public $tooltip="Days until Date Due";
    public $key='date_due';

    public function __construct($args=NULL) {
        parent::__construct($args);
    }

	function render_tr(&$obj, $style="") {
        measure_start('col_timedue');
        $due_str=$obj->date_due;
        if($due_str=="0000-00-00") {
		    print "<td></td>";
        }
        else {
            $due_days= floor( (strtotime($obj->date_due) - time())/24/60/60)+1;
            if($due_days==0) {
                $value="Today";
                $title="title='Hurry up!'";
                $class='';
            }
            else {
                $class='';
                if($due_days<0) {
                    $class='overDue';
                }
                $value="$due_days<span class='entity'>D</span>";
                if(!$obj->date_due_end || $obj->date_due_end !='0000-00-00') {
                    $title="title='".date( "D, d.m.Y",strtotime($obj->date_due))." - ".date( "D, d.m.Y",strtotime($obj->date_due_end)).  "'";
                    $value.="+";
                }
                else {
                    $title="title='Due on ".date( "D, d.m.Y",strtotime($obj->date_due))."'";
                }
            }
		    print "<td class='timeDue $class' $title>$value</td>";
        }
        measure_stop('col_timedue');

	}
}


class ListBlockCol_OpenTasks extends ListBlockCol{
    public $name='Tasks';
    public $tooltip="Number of open tasks is hilighted if shown home.";

    public function __construct($args=NULL) {
        parent::__construct($args);
    }
    function render_tr(&$project,$style='') {
        $num_tasks= $project->getNumTasks();
        if($project->show_in_home) {
            echo "<td class='right'>$num_tasks</td>";
        }
        else {
            echo "<td class='right notShown' title='Tasks of this project are not shown in home'>($num_tasks)</td>";
        }
    }
}


class ListBlockColPrio extends ListBlockCol{

    public $style='narrow';
	function render_tr(&$obj,$style="") 
	{
        global $auth;
        
		//--- try to get param ---
        if(!isset($obj->prio) || !intval($obj->prio)) {
            $out="?";
        }
        else {
        	$out= "<img src=\"themes/".getCurTheme()."/img/prio_{$obj->prio}.png\">";
        }
        $class= $this->style
            ?"class='$this->style'"
            :'';
		echo "<td $class>";
		echo $out;
		echo "</td>";
	}
}

class ListBlockColStatus extends ListBlockCol{

    public $style='narrow';

	function render_tr(&$obj, $style="") 
	{
        global $auth;
        
		//--- try to get param ---
   		$status= $obj->status;
        global $STATUS_NAMES;

        $class= $this->style
            ?"class='$this->style'"
            :'';
        $tooltip=$STATUS_NAMES[$status];

		echo "<td $class title='Status is $tooltip'>";
		echo "<img src=\"themes/".getCurTheme()."/img/status_$status.png\">";
		echo "</td>";
	}
}


class ListBlockColPubLevel extends ListBlockCol{

    public $style='narrow';
    public $key='pub_level';
    public $tooltip="Item is public to";
    public $name="Pub";

	function render_tr(&$obj, $style="")
    {

        global $PUB_LEVEL_NAMES;
        global $PUB_LEVEL_SHORT_NAMES;
		//--- try to get param ---
   		$pub_level= $obj->pub_level;

        $class= $this->style
            ?"class='$this->style'"
            :'';
        $str_level=$PUB_LEVEL_SHORT_NAMES[$pub_level];

		echo "<td $class title='Public to $PUB_LEVEL_NAMES[$pub_level]'>";
		echo $str_level;
		echo "</td>";
	}
}







?>