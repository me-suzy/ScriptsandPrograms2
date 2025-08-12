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
class ListBlock_comments extends ListBlock {

    public function __construct() {
        global $PH;
        $this->id='comments';
        $this->bg_style='bg_misc';

        $this->no_items_html=$PH->getLink('commentNew');;

		$this->title="Comments";

		$this->add_col( new ListBlockCol(array(
			'key'=>'_select_col_',
			'name'=>"S",
		)));

        #--- columns ----
     	$this->add_col( new ListBlockCol_CommentText());
        $this->add_col( new ListBlockCol_CommentDetails());
        $this->add_col( new ListBlockColPubLevel());


        #--- functions -------
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('commentEdit')->id,
            'name'  =>'Edit',
            'id'    =>'commentEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('commentNew')->id,
            'name'  =>'New',
            'id'    =>'commentNew',
            'icon'  =>'new',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('commentsDelete')->id,
            'name'  =>'Delete',
            'id'    =>'commentsDelete',
            'icon'  =>'delete',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('commentsMoveToFolder')->id,
            'name'  =>'Move to Folder',
            'id'    =>'commentsMoveToFolder',
            'context_menu'=>'submit',
        )));

        }

}



/**
* special list-columns for rendering comment-lists
*/
class ListBlockCol_CommentText extends ListBlockCol{
    public $name='Start';

    public function __construct($args=NULL) {
        parent::__construct($args);
        $this->width='80%';
    }
	function render_tr(&$obj, $style="") {
		if(!isset($obj) || !$obj instanceof Comment) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}
        $diz= preg_replace("/\n\s/","\n&nbsp;",$obj->description);
        $diz= preg_replace("/\n/","<br>",$diz);

        #$value=round((strtotime($obj->time_end)-strtotime($obj->time_start))/60/60,1)."h";
		print "<td class=text><span class=title>$obj->name</span><br><span class=text>$diz</span>
        </td>";
	}
}

class ListBlockCol_CommentDetails extends ListBlockCol{
    public $name='Details';
    public $width='10%';

	function render_tr(&$obj, $style="") {
        global $COMMENTTYPE_NAMES;
		if(!isset($obj) || !$obj instanceof Comment) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}

        #--- set to undefined if out of range ---
        if(!isset($COMMENTTYPE_NAMES[$obj->type])) {
            $obj->type=0;
        }
        $p_type="<span class=type>".$COMMENTTYPE_NAMES[$obj->occasion]."</span>";
        $p_time= ($obj->time && $obj->time != "0000-00-00 00-00-00")
            ? date("D, d.m.Y  H:i",strtotime($obj->time) )
            : '';

        #$value=round((strtotime($obj->time_end)-strtotime($obj->time_start))/60/60,1)."h";

        ### get user ###
        $str_person='';
        if($obj->created_by != 0) {
            require_once("db/class_person.inc");
            $person= new Person($obj->created_by);
            $str_person='<p>by '.$person->getLink().'</p>';
        }
		print "<td class=details>$p_type<span class=date>$p_time</span>$str_person</td>";
	}
}
?>