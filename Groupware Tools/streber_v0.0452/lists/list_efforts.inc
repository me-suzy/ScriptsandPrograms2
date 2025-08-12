<?php
# streber - a php based project management system
# Copyright (c) 2005 Thomas Mann - thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in docs/license.txt

/**
 * derived ListBlock-class for listing efforts
 *
 * @includedby:     pages/*
 *
 * @author:         Thomas Mann
 * @uses:           ListBlock
 * @usedby:
 *
 */

class ListBlock_efforts extends ListBlock {

    public function __construct() {

        global $PH;
        $this->id='efforts';
        $this->bg_style='bg_time';

		$this->title="Efforts";

		$this->add_col( new ListBlockCol(array(
			'key'=>'_select_col_',
			'name'=>"S",
			'tooltip'=>"This is a tooltip",
		)));
		$this->add_col(new ListBlockColMethod(array(
			'key'=>'p.name',
			'name'=>'Project',
			'func'=>'getProjectLink'
		)));
		$this->add_col( new ListBlockCol_EffortTask);

		$this->add_col(new ListBlockColMethod(array(
			'key'=>'person',
			'name'=>'person',
			'func'=>'getPersonLink'
		)));
		$this->add_col( new ListBlockColHtml(array(
			'key'=>'name',
			'name'=>"Name",
			'tooltip'=>"Task name. More Details as tooltips",
			'width'=>'50%',
			'sort'=>0,
			'format'=>'{?name}'
		)));
		$this->add_col( new ListBlockCol_EffortDate);
		$this->add_col( new ListBlockCol_EffortDateEnd);
		$this->add_col( new ListBlockCol_EffortAmount);
		$this->add_col( new ListBlockCol_DayGraph);

        #---- functions ----
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('effortEdit')->id,
            'name'  =>'Edit effort',
            'id'    =>'effortEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('effortNew')->id,
            'name'  =>'New effort',
            'id'    =>'effortNew',
            'icon'  =>'new',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('effortDelete')->id,
            'name'  =>'Delete',
            'id'    =>'effortDelete',
            'icon'  =>'delete',
            'context_menu'=>'submit',
        )));
    }

    /**
    * render complete
    */
    public function render_list(&$efforts=NULL) {
		$this->render_header();
        if(!$efforts && $this->no_items_html) {
            $this->render_tfoot_empty();
        }
        else {

    		$this->render_thead();
            $sum=0.0;

            $day_last=0;
    		foreach($efforts as $e) {
                $sum +=(strtotime($e->time_end)-strtotime($e->time_start))/60/60 * 1.0;

                $day= date('z',strtotime($e->time_end))*1;
                if($day != $day_last) {
                    $day_last= $day;
    			    $this->render_trow(&$e,'isNewDay');
                }
                else {
    			    $this->render_trow(&$e);
                }
            }
               		#$this->render_trow(&$t);
            $sum=round($sum,1);
            $this->summary=count($efforts)." efforts / {$sum} h";
    		$this->render_tfoot();
        }
    }
}


class ListBlockCol_EffortTask extends ListBlockCol
{
    public $name='Task';
    public $key='task';

    public function __construct($args=NULL) {
        parent::__construct($args);
    }
	function render_tr(&$obj, $style="") 
	{
	    global $PH;
	    
        if(isset($obj->task)) {
            if($task= new Task($obj->task)) {
                $str= $PH->getLink('taskView',$task->getShort(),array('tsk'=>$task->id));
    		    print "<td><nobr>$str</nobr></td>";
    		}
		}
	}
}

class ListBlockCol_EffortDate extends ListBlockCol
{
    public $name='Start';
    public $key='time_start';


    public function __construct($args=NULL) {
        parent::__construct($args);
    }
	function render_tr(&$obj, $style="") {
		if(!isset($obj) || !$obj instanceof Effort) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}
        $value= date("D, d.m.Y",strtotime($obj->time_start) );
        $value.="&nbsp;&nbsp;".date("H:i",strtotime($obj->time_start) );
		print "<td><nobr>$value</nobr></td>";
	}
}

class ListBlockCol_EffortDateEnd extends ListBlockCol{
    public $name='End';
    public $key='time_end';

    public function __construct($args=NULL) {
        parent::__construct($args);
    }
	function render_tr(&$obj, $style="") {
		if(!isset($obj) || !$obj instanceof Effort) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}
        $value=date("H:i",strtotime($obj->time_end) );
		print "<td>$value</td>";
	}
}

class ListBlockCol_EffortAmount extends ListBlockCol{
    public $name='len';

    public function __construct($args=NULL) {
        parent::__construct($args);
    }
	function render_tr(&$obj, $style="") {
		if(!isset($obj) || !$obj instanceof Effort) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}
        $value=round((strtotime($obj->time_end)-strtotime($obj->time_start))/60/60,1)."h";
		print "<td>$value</td>";
	}
}



class ListBlockCol_DayGraph extends ListBlockCol
{
    public $name='Daygraph';

    public function __construct($args=NULL) {
        parent::__construct($args);
    }
	function render_tr(&$obj, $style="") {
		if(!isset($obj) || !$obj instanceof Effort) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}
        $tmp=mysqlDatetime2utc($obj->time_start);
        $day_time_start= 8*60*60;
        $day_time_end= 22*60*60;

        $stretch= 200/ ($day_time_end - $day_time_start);

        $time_start= round((($tmp['hour']*60*60+$tmp['min']*60+ $tmp['sec']) - $day_time_start)* $stretch,0);
        if($time_start<0) {
            $time_start=0;
        }


        $tmp=mysqlDatetime2utc($obj->time_end);
        $time_end= round((($tmp['hour']*60*60+$tmp['min']*60+ $tmp['sec']) - $day_time_start)*$stretch,0);
        if($time_end< $time_start) {
            $time_end=0;
        }
        $time_len= $time_end - $time_start;

        echo "<td>";
        echo "<nobr>";
        echo "<img src='themes/".getCurTheme(). "/img/pixel.gif' style='width:{$time_start}px;height:3px;'>";
        echo "<img src='themes/".getCurTheme() ."/img/pixel.gif' style='width:{$time_len}px;height:12px;background-color:#f00;'>";
		echo "</nobr>";
		echo "</td>";
	}
}


?>