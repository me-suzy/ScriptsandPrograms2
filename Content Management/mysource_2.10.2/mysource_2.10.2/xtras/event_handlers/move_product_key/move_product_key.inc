<?
    ##############################################
   ### MySource ------------------------------###
  ##- Event Handler Xtra ------ Info ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: xtras/event_handlers/move_product_key/move_product_key.inc
## Desc: Moves a product key from one category to another. 
###############################################################################

global $INCLUDE_PATH;
include_once ("$INCLUDE_PATH/event_handler.inc");

/** An event handler class to do nothing. Use this as a template for creating new event handlers.
  *
 */

class Event_Handler_Move_Product_Key extends EventHandler {
	var $destination_category; # the destination category
	var $buyer_attributes; # array of attributeids for the buyer's details
	var $buyer_email_options;

	var $seller_questions; # array of (section_$sectionid_question_$questionid)'s for the seller's details
	var $seller_email_options;

	/** Constructor.
	  *
	  * @public
	*/
	function Event_Handler_Move_Product_Key () {
		parent::EventHandler();
		$this->parameters = array ("trolley" => "The ECommerce Trolley");
	}

	function print_backend(&$event, $prefix='') {
		$web_system =& get_web_system();
		$notitia =& $web_system->get_extension('notitia');
		$tree =& $notitia->get_category_tree();
		?>
		<div style="background-color : #d0d0d0; border: 1px solid black;">
		<h3>Wannabuy Move Product Key</h3><?
		parent::print_backend($event, $prefix);

		?>
		<h4>Archive category:</h4>
		<p>
		<?=combo_box($prefix."[destination_category]", $tree->get_category_list_with_sticks('', 1000), $this->destination_category) ?>
		</p>

		<h4>Email options</h4>

		<table border=1  cellspacing=0 cellpadding=5>
		<tr bgcolor="#c0c0c0">
		<th width=50%>Buyer attributes</th>
		<th width=50%>Seller questions</th>
		</tr>

		<tr>
		<td align=center>
		<? $this->backend_print_buyer_attributes($prefix) ?>
		</td>
		<td align=center>
		<? $this->backend_print_seller_questions($prefix) ?>
		</td>
		</tr>

		<tr>
		<td><small>These attributes are included in the contact details email.</small>
		</td>
		<td><small>The answers to these questions are included in the contact details email.</small>
		</td>
		</tr>

		</table>

		<h4>Buyer email options</h4>
		<table>
		<tr>
		<th>Subject Line</th><td><?=text_box($prefix."[buyer_email_options][subject]", $this->buyer_email_options['subject'])?></td>
		</tr>
		
		<tr>
		<th>From</th><td><?=text_box($prefix."[buyer_email_options][from]", $this->buyer_email_options['from'])?></td>
		</tr>
		</table>

		<table width=100%>
		<tr>
		<td>Header</td>
		<td>Footer</td>
		</tr>

		<tr>
		<td>
		<textarea name="<?=$prefix?>[buyer_email_options][header]" cols=50 rows=10><?=$this->buyer_email_options['header']?></textarea>
		</td>
		<td>
		<textarea name="<?=$prefix?>[buyer_email_options][footer]" cols=50 rows=10><?=$this->buyer_email_options['footer']?></textarea>
		</td>
		</tr>

		<tr>
		<td>
		<small>This text will be inserted above the seller details.</small></td>
		<td>
		<small>This text will be inserted below the seller details.</small></td>
		</tr>
		</table>

		<h4>Seller email options</h4>

		<table>
		<tr>
		<th>Subject Line</th><td><?=text_box($prefix."[seller_email_options][subject]", $this->seller_email_options['subject'])?></td>
		</tr>
		
		<tr>
		<th>From</th><td><?=text_box($prefix."[seller_email_options][from]", $this->seller_email_options['from'])?></td>
		</tr>
		</table>

		<table width=100%>
		<tr>
		<td>Header</td>
		<td>Footer</td>
		</tr>

		<tr>
		<td>
		<textarea name="<?=$prefix?>[seller_email_options][header]" cols=50 rows=10><?=$this->seller_email_options['header']?></textarea>
		</td>
		<td>
		<textarea name="<?=$prefix?>[seller_email_options][footer]" cols=50 rows=10><?=$this->seller_email_options['footer']?></textarea>
		</td>
		</tr>

		<tr>
		<td>
		<small>This text will be inserted above the seller details.</small></td>
		<td>
		<small>This text will be inserted below the seller details.</small></td>
		</tr>
		</table>

		</div>
		<?

	}

	function backend_print_buyer_attributes($prefix) {
		$notitia = &$this->get_notitia_system();
		$web_system = &get_web_system();
		$manager = &$web_system->get_extension('Wannabuy_Manager');
		$frontitia_page = &$web_system->get_page ($manager->parameters['frontitia_page']);
		$frontitia = $frontitia_page->get_template();
		$root_category = &$notitia->get_category ($frontitia->parameters['root_categoryid']);
		$tree = &$notitia->get_category_tree();
		
		$attribute_options = &$tree->get_context_attributes($root_category->id);
		echo multiple_combo_box ($prefix.'[buyer_attributes]', $attribute_options, $this->buyer_attributes, '', 25, 10);
	}

	function backend_process_buyer_attributes($attributes) {
		$this->buyer_attributes = $attributes;
	}


	function backend_print_seller_questions($prefix) {
		$web_system = &get_web_system();
		$manager = &$web_system->get_extension('Wannabuy_Manager');
		$seller_page = &$web_system->get_page($manager->parameters['sellers_page']);
		$seller_template = &$seller_page->get_template();

		$form = &$seller_template->get_form('form');

		$question_options = array();
		foreach ($form->sections as $sectionid => $section) {
			foreach ($section['questions'] as $questionid => $question) {
				$id = "{$sectionid}_{$questionid}";
				$question_options[$id] = $question->title;
			}
		}
		echo multiple_combo_box ($prefix.'[seller_questions]', $question_options, $this->seller_questions,'',25,10);
	}

	function backend_process_seller_questions($questionids) {
		$this->seller_questions = $questionids;
	}

	function process_backend(&$event, &$handler_options) {
		$this->destination_category = $handler_options['destination_category'];

		$this->backend_process_buyer_attributes ($handler_options['buyer_attributes']);
		$this->buyer_email_options = $handler_options['buyer_email_options'];

		$this->backend_process_seller_questions ($handler_options['seller_questions']);
		$this->seller_email_options = $handler_options['seller_email_options'];

		parent::process_backend($event, $handler_options);
	}

	function &get_notitia_system() {
		$web_system =& get_web_system();
		return $web_system->get_extension('notitia');
	}

	function archive_product_key (&$trolley) {
		$notitia =& $this->get_notitia_system();
		$new_category =& $notitia->get_category($this->destination_category);
		
		# there really only should be one.. but just in case
		foreach ($trolley->get_content_recordids() as $recordid) {
			$this->append_message("Processing record $recordid.");
			# get the record and the associated categories
			$record =& $notitia->get_record($recordid);
			$categoryids = $record->categoryids;

			# associate with new category
			$this->append_message("Associated with category ".$new_category->name." (".$new_category->id.").");
			$new_category->associate_with_record ($recordid);
			$this->append_message();

			# disassociate current categories
			foreach ($categoryids as $categoryid) {
				$category =& $notitia->get_category($categoryid);
				$this->append_message("Disassociated with category ".$category->name." ($categoryid).");
				$category->disassociate_with_record ($recordid);

				if ($this->delete_empty_categories && count($category->associated_records()) == 0) {
					$this->append_message("Deleting empty category ".$category->name." ($categoryid).");
					$category->delete();
				}
			}
		}
	}
	

	function send_contact_details_emails ($buyerid) {
		$web_system =& get_web_system();
		$db =& $web_system->get_db();
		$notitia = &$this->get_notitia_system();

		# get buyer details (the notitia record)
		$sql = 
			"SELECT * FROM xtra_web_extension_Wannabuy_Com_Gateway_linker\n".
			"WHERE buyerid = '".addslashes($buyerid)."' AND\n".
			"buyer_done_deal IS NOT NULL AND seller_done_deal IS NOT NULL";
		$link_record = $db->single_row ($sql);
		$buyer_email = $link_record['buyer_email'];
		$buyer_record = $notitia->get_record ($buyerid);

		# get seller_details (the log)
		$seller_email = $link_record['seller_email'];
		$sellerid = $link_record['sellerid'];
		
		# get seller page from Wannabuy Manager
		$manager =& $web_system->get_extension('Wannabuy_Manager');
		$seller_pageid = $manager->parameters['sellers_page'];
		$seller_page =& $web_system->get_page($seller_pageid);
		$seller_template =& $seller_page->get_template();
		$seller_form =& $seller_template->get_form('form');

		$sql =
			"SELECT answers FROM xtra_page_template_Wannabuy_form_log\n".
			"WHERE logid = '".addslashes($sellerid)."'";

		$answers = $db->single_element($sql);
		$seller_form->import_answers($answers);

		$this->send_buyer_details($buyer_record, $link_record, $seller_email);
		$this->send_seller_details($seller_form, $link_record, $buyer_email);
	}

	function send_buyer_details (&$buyer_record, &$link_record, $to) {
		$notitia =& $this->get_notitia_system();

		$body = "Transaction alias: {$link_record['transaction_alias']}\n\n";
		foreach ($this->buyer_attributes as $attributeid) {
			$attribute =& $notitia->get_attribute ($attributeid);
			$body .= $attribute->name.": ".$buyer_record->get_attribute_formatted_value ($attributeid)."\n";
		}
		$body = $this->seller_email_options['header']."\n".$body."\n".$this->seller_email_options['footer'];
		mail ($to, $this->seller_email_options['subject'], $body, "From: ".$this->seller_email_options['from']);
	}

	function send_seller_details ($seller_form, &$link_record, $to) {

		$body = "Transaction alias: {$link_record['transaction_alias']}\n\n";
		foreach ($this->seller_questions as $full_questionid) {
			list($sectionid, $questionid) = explode ('_', $full_questionid);
			$question =& $seller_form->sections[$sectionid]['questions'][$questionid];
			$body .= $question->title.": ".$question->nice_value(false)."\n";
		}

		$body = $this->buyer_email_options['header']."\n".$body."\n".$this->buyer_email_options['footer'];
		mail ($to, $this->buyer_email_options['subject'], $body, "From: ".$this->buyer_email_options['from']);
	}


	/** Moves product keys and sends emails with contact details.
	  * 
	  * @public
	  * @param array $param Parameters for the handler.
	  * @returns void
	*/
	function execute ($param) {
		$trolley =& $param['trolley'];	
		$notitia =& $this->get_notitia_system();

		# there should only be one record in one category=buyerid
		$recordids = $trolley->get_content_recordids();
		$record =& $notitia->get_record($recordids[0]);
		$categoryids = $record->categoryids;
		$category = $notitia->get_category($categoryids[0]);
		$buyerid = $category->name;

		$this->append_message ("The buyer id associated with this product key: $buyerid");
		
		$this->append_message ("Archiving of product key.");
		$this->archive_product_key ($trolley);
		$this->append_message ("Sending contact emails.");
		$this->send_contact_details_emails ($buyerid);
	}

}
?>
