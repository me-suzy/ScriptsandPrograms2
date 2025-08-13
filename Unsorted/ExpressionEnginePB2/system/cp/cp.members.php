<?php

/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: cp.members.php
-----------------------------------------------------
 Purpose: Member management functions
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Members {


    // Default member groups.  We used these for translation purposes
    
    var $english = array('Guests', 'Banned', 'Members', 'Pending', 'Super Admins');
    
    var $perpage = 50;  // Number of results on the "View all member" page
    
    var $no_delete = array('1', '2', '3', '4'); // Member groups that can not be deleted

	var $member_theme_dir = 'member/themes/';
	

    // -----------------------------
    //  Constructor
    // -----------------------------   

    function Members()
    {
        global $LANG;
        
        // Fetch the language files
        
        $LANG->fetch_language_file('myaccount');
        $LANG->fetch_language_file('members');
    }
    // END
    
        
    // -----------------------------
    //  View all members
    // -----------------------------   
    
    function view_all_members($message = '')
    {  
        global $IN, $LANG, $DSP, $LOC, $DB;
                
        // These variables are only set when one of the pull-down menus is used
        // We use it to construct the SQL query with
        
        $group_id   = $IN->GBL('group_id', 'GP');
        $order      = $IN->GBL('order', 'GP');        
        
        $query = $DB->query("SELECT COUNT(*) AS count FROM exp_members");
              
        $total_members = $query->row['count'];
        
        // Begin building the page output
        
        $r = $DSP->heading($LANG->line('view_members'));
        
        if ($message != '')
        {
            $r .= $message;
        }
        
        // Declare the "filtering" form
        
        $r .= $DSP->form(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=view_members');
        
        
        // Table start
                
                
        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->td('itemWrapper', '', '5').NL;
        
        // Member group selection pull-down menu
        
        $r .= $DSP->input_select_header('group_id').
              $DSP->input_select_option('', $LANG->line('member_groups')).
              $DSP->input_select_option('', $LANG->line('all'));
        
        // Fetch the names of all member groups and write each one in an <option> field
        
        $query = $DB->query("SELECT group_title, group_id FROM exp_member_groups order by group_title");
             
        foreach ($query->result as $row)
        {
            $selected = ($group_id == $row['group_id']) ? 1 : '';          
        
            $r .= $DSP->input_select_option($row['group_id'], $row['group_title'], $selected);
        }        

        $r .= $DSP->input_select_footer().
              $DSP->nbs(2);   
                               
        
        // "display order" pull-down menu
        
              $sel_1  = ($order == 'desc')              ? 1 : '';          
              $sel_2  = ($order == 'asc')               ? 1 : '';          
              $sel_3  = ($order == 'username')          ? 1 : '';          
              $sel_4  = ($order == 'username_desc')     ? 1 : '';          
              $sel_5  = ($order == 'screen_name')       ? 1 : '';          
              $sel_6  = ($order == 'screen_name_desc')  ? 1 : '';          
              $sel_7  = ($order == 'email')             ? 1 : '';          
              $sel_8  = ($order == 'email_desc')        ? 1 : '';          
                
        
        $r .= $DSP->input_select_header('order').
              $DSP->input_select_option('desc',  $LANG->line('sort_order'), $sel_1).
              $DSP->input_select_option('asc',   $LANG->line('ascending'), $sel_2).
              $DSP->input_select_option('desc',  $LANG->line('descending'), $sel_1).
              $DSP->input_select_option('username_asc', $LANG->line('username_asc'), $sel_3).
              $DSP->input_select_option('username_desc', $LANG->line('username_desc'), $sel_4).
              $DSP->input_select_option('screen_name', $LANG->line('screen_name_asc'), $sel_5).
              $DSP->input_select_option('screen_name_desc', $LANG->line('screen_name_desc'), $sel_6).
              $DSP->input_select_option('email_asc', $LANG->line('email_asc'), $sel_7).
              $DSP->input_select_option('email_desc', $LANG->line('email_desc'), $sel_8).
              $DSP->input_select_footer().
              $DSP->nbs(2);
                
        
        // Submit button and close filtering form

        $r .= $DSP->input_submit($LANG->line('submit'), 'submit');
                            
        $r .= $DSP->td_c().
              $DSP->td('defaultRight', '', 2).
              $DSP->heading($LANG->line('total_members').NBS.NBS.$total_members.NBS.NBS.NBS.NBS.NBS, 5).
              $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();

        $r .= $DSP->form_c();
        
        // Build the SQL query as well as the query string for the paginate links
        
        $pageurl = BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=view_members';
        
        $sql = "SELECT DISTINCT 
                       exp_members.username,
                       exp_members.member_id,
                       exp_members.screen_name,
                       exp_members.email,
                       exp_members.join_date,
                       exp_members.last_visit,
                       exp_member_groups.group_title
                FROM   exp_members, exp_member_groups";
                
                
        $sql .= " WHERE exp_members.group_id = exp_member_groups.group_id"; 
        
        
        if ($group_id)
        {
            $sql .= " AND exp_members.group_id = $group_id";
            
            $pageurl .= AMP.'group_id='.$group_id;
        }
                
        $sql .= " ORDER BY ";        
        
        if ($order)
        {
            $pageurl .= AMP.'order='.$order;
        
            switch ($order)
            {
                case 'asc'              : $sql .= "join_date asc";
                    break;
                case 'desc'             : $sql .= "join_date desc";
                    break;
                case 'username_asc'     : $sql .= "username asc";
                    break;
                case 'username_desc'    : $sql .= "username desc";
                    break;
                case 'screen_name_asc'  : $sql .= "screen_name asc";
                    break;
                case 'screen_name_desc' : $sql .= "screen_name desc";
                    break;
                case 'email_asc'        : $sql .= "email asc";
                    break;
                case 'email_desc'       : $sql .= "email desc";
                    break;
                default                 : $sql .= "join_date desc";
            }
        }
        else
        {
            $sql .= "join_date desc";
        }
      
        // Run the query the first time.
        // We need to run this query twice. The first time without the LIMIT
        // clause so that we can determine the total number of available results
        // and pass this number to the paginate class. Then we'll add the LIMIT
        // clause the SQL statement and run it again and use that to render
        // the page output.  Obviously doing this twice isn't ideal but I don't
        // know of any other way to accomplish this.
    
        $query = $DB->query($sql);  
        
        // No result?  Show the "no results" message
        
        $total_count = $query->num_rows;
        
        if ($total_count == 0)
        {            
            $r .= $DSP->qdiv('', BR.$LANG->line('no_members_matching_that_criteria'));        
        
            return $DSP->set_return_data(   $LANG->line('view_members'),
                                            $r,
                                            $LANG->line('view_members')
                                        );    
        }
        
        // Get the current row number and add the LIMIT clause to the SQL query
        
        if ( ! $rownum = $IN->GBL('rownum', 'GP'))
        {        
            $rownum = 0;
        }
                        
        $sql .= " LIMIT ".$rownum.", ".$this->perpage;
        
        // Run the query              
    
        $query = $DB->query($sql); 
        
		// "select all" checkbox

        $r .= $DSP->toggle();

        // Declare the "delete" form
        
        $r .= $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=mbr_del_conf', 'target');

        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        // Build the table heading       
        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', $LANG->line('username')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('screen_name')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('email')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('join_date')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('last_visit')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('member_group')).
              $DSP->table_qcell('tableHeadingBold', $DSP->input_checkbox('toggleflag', '', '', "onclick=\"toggle(this);\"").NBS.$LANG->line('delete')).
              $DSP->tr_c();
                
        // Loop through the query result and write each table row 
               
        $i = 0;
        
        foreach($query->result as $row)
        {
            $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
                      
            $r .= $DSP->tr();
            
            // Username
            
            $r .= $DSP->table_qcell($style, 
                                    $DSP->anchor(
                                                  BASE.AMP.'C=myaccount'.AMP.'id='.$row['member_id'], 
                                                  '<b>'.$row['username'].'</b>'
                                                )
                                    );
            // Screen name
            
            $screen = ($row['screen_name'] == '') ? "--" : '<b>'.$row['screen_name'].'</b>';
            
            $r .= $DSP->table_qcell($style, $screen);
            
             
            // Email
            
            $r .= $DSP->table_qcell($style, 
                                    $DSP->mailto($row['email'], $row['email'])
                                    );

            // Join date

            $r .= $DSP->td($style).
                  $LOC->convert_timestamp('%Y', $row['join_date']).'-'.
                  $LOC->convert_timestamp('%m', $row['join_date']).'-'.
                  $LOC->convert_timestamp('%d', $row['join_date']).
                  $DSP->td_c();
                  
            // Last visit date

            $r .= $DSP->td($style);
            
                if ($row['last_visit'] != 0)
                {            
                    $r .= $LOC->set_human_time($row['last_visit']);
                }
                else
                {
                    $r .= "--";               
                }
                                      
            $r .= $DSP->td_c();
            
            // Member group
            
            $r .= $DSP->td($style);
            
            $r .= $row['group_title'];
                
            $r .= $DSP->td_c();
            
            // Delete checkbox
            
            $r .= $DSP->table_qcell($style, $DSP->input_checkbox('toggle[]', $row['member_id']));
                  
            $r .= $DSP->tr_c();
            
        } // End foreach
        

        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
                        
        $r .= $DSP->table('', '0', '', '98%');
        $r .= $DSP->tr().
              $DSP->td();
               
        // Pass the relevant data to the paginate class so it can display the "next page" links
        
        $r .=  $DSP->div('crumblinks').
               $DSP->pager(
                            $pageurl,
                            $total_count,
                            $this->perpage,
                            $rownum,
                            'rownum'
                          ).
              $DSP->div_c().
              $DSP->td_c().
              $DSP->td('defaultRight');
        
        // Delete button
        
        $r .= $DSP->input_submit($LANG->line('delete')).
              $DSP->td_c().
              $DSP->tr_c();
              
        // Table end
        
        $r .= $DSP->table_c().
              $DSP->form_c();
        

        // Set output data        

        $DSP->title = $LANG->line('view_members');
        $DSP->crumb = $LANG->line('view_members');
        $DSP->body  = &$r;                                 
    }
    // END



    //-----------------------------------------------------------
    //  Delete Member (confirm)
    //-----------------------------------------------------------
    // Warning message if you try to delete members
    //-----------------------------------------------------------

    function member_delete_confirm()
    { 
        global $IN, $DSP, $LANG;
        
        
        if ( ! $DSP->allowed_group('can_delete_members'))
        {
            return $DSP->no_access_message();
        }
                        
        if ( ! $IN->GBL('toggle', 'POST'))
        {
            return $this->view_all_members();
        }

        $r  = $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=mbr_delete');
        
        $i = 0;
        
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'toggle') AND ! is_array($val))
            {
                $r .= $DSP->input_hidden('delete[]', $val);
                
                $i++;
            }        
        }
        
        $r .= $DSP->heading($LANG->line('delete_member'));
        $r .= $DSP->div();
        
        if ($i == 1)
            $r .= '<b>'.$LANG->line('delete_member_confirm').'</b>';
        else
            $r .= '<b>'.$LANG->line('delete_members_confirm').'</b>';
            
        $r .= $DSP->br(2).
              $DSP->qdiv('alert', $LANG->line('action_can_not_be_undone')).BR.
              $DSP->input_submit($LANG->line('delete')).
              $DSP->div_c().
              $DSP->form_c();


        $DSP->title = $LANG->line('delete_member');
        $DSP->crumb = $LANG->line('delete_member');         
        $DSP->body  = &$r;
    }
    // END   
    
    
    
    //-----------------------------------------------------------
    //  Delete Members
    //-----------------------------------------------------------
    // Kill the specified members
    //-----------------------------------------------------------

    function member_delete()
    { 
        global $IN, $DSP, $LANG, $SESS, $FNS, $DB, $STAT;
        

        if ( ! $DSP->allowed_group('can_delete_members'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $IN->GBL('delete', 'POST'))
        {
            return $this->view_all_members();
        }
            
        //---------------------------------------------
        // Fetch member ID numbers and build the query
        //---------------------------------------------

        $ids = array();
                
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'delete') AND ! is_array($val))
            {
                $ids[] = "member_id = '".$val."'";
            }        
        }
        
        $IDS = implode(" OR ", $ids);
        
        // SAFETY CHECK
        // Let's fetch the Member Group ID of each member being deleted
        // If there is a Super Admin in the bunch we'll run a few more safeties
                
        $super_admins = 0;
                
        $query = $DB->query("SELECT group_id FROM exp_members WHERE ".$IDS);        
        
        foreach ($query->result as $row)
        {
            if ($query->row['group_id'] == 1)
            {
                $super_admins++;              
            }
        }        
        
        if ($super_admins > 0)
        {
            // You must be a Super Admin to delete a Super Admin
        
            if ($SESS->userdata['group_id'] != 1)
            {
                return $DSP->error_message($LANG->line('must_be_superadmin_to_delete_one'));
            }
            
            // You can't detete the only Super Admin   
                
            $query = $DB->query("SELECT COUNT(*) AS count FROM exp_members WHERE group_id = '1'");
            
            if ($super_admins >= $query->row['count'])
            {
                return $DSP->error_message($LANG->line('can_not_delete_super_admin'));
            }
        }
        
        // If we got this far we're clear to delete the members
    
        $DB->query("DELETE FROM exp_members WHERE ".$IDS);
        $DB->query("DELETE FROM exp_member_data WHERE ".$IDS);
        $DB->query("DELETE FROM exp_member_homepage WHERE ".$IDS);
        $DB->query("DELETE FROM exp_member_data WHERE ".$IDS);          
        
        // Update global stat
        
		$STAT->update_member_stats();
            
        $message = (count($ids) == 1) ? $DSP->qdiv('success', $LANG->line('member_deleted')) :
                                        $DSP->qdiv('success', $LANG->line('members_deleted'));

        return $this->view_all_members($message);
    }
    // END    
     


    // -----------------------------
    //  Member group overview
    // -----------------------------   
    
    function member_group_manager($message = '')
    {  
        global $LANG, $DSP, $DB;
        
    
        if ( ! $DSP->allowed_group('can_admin_mbr_groups'))
        {
            return $DSP->no_access_message();
        }

        $sql = "SELECT exp_member_groups.group_id, exp_member_groups.group_title, exp_member_groups.can_access_cp, exp_member_groups.is_locked,
                COUNT(exp_members.member_id) as count FROM exp_member_groups
                LEFT JOIN exp_members ON (exp_members.group_id = exp_member_groups.group_id)
                GROUP BY exp_member_groups.group_id ORDER BY exp_member_groups.group_title";
        
        $query = $DB->query($sql);
        
        $DSP->body .= $DSP->heading($LANG->line('member_groups'));
                        
        $DSP->body .= $message;
        
        $DSP->body .= $DSP->qdiv('', $DSP->qspan('alert', '*').NBS.$LANG->line('member_has_cp_access').$DSP->br(2));
        

        $DSP->body .= $DSP->table('tableBorder', '0', '0', '100%').
                      $DSP->tr().
                      $DSP->td('tablePad'); 

        $DSP->body .= $DSP->table('', '0', '', '100%').
                      $DSP->tr().
                      $DSP->table_qcell('tableHeadingBold', 
                                        array(
                                                $LANG->line('group_title'),
                                                $LANG->line('edit_group'),
                                                $LANG->line('security_lock'),
                                                $LANG->line('group_id'),
                                                $LANG->line('mbrs'),
                                                $LANG->line('delete')
                                             )
                                        ).
                      $DSP->tr_c();
        
        
        $i = 0;
                
        foreach($query->result as $row)
        {
            $group_name = $row['group_title'];
                    
            if (in_array($group_name, $this->english))
            {
                $group_name = $LANG->line(strtolower(str_replace(" ", "_", $group_name)));
            }
        
            $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
            
            $DSP->body .= $DSP->tr();
            
            $title = ($row['can_access_cp'] == 'y') ? $DSP->qdiv('highlight', $DSP->required().NBS.$group_name) : $group_name;
                        
            $DSP->body .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $title), '25%');

            $DSP->body .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=edit_mbr_group'.AMP.'group_id='.$row['group_id'], $LANG->line('edit_group')), '18%');

            $status = ($row['is_locked'] == 'y') ? $DSP->qdiv('highlight', $LANG->line('locked')) : $DSP->qdiv('highlight_alt', $LANG->line('unlocked'));
                        
            $DSP->body .= $DSP->table_qcell($style, $status, '17%');
            
            $DSP->body .= $DSP->table_qcell($style, $row['group_id'], '15%');

            $DSP->body .= $DSP->table_qcell($style, $row['count'], '15%');

            $delete = ( ! in_array($row['group_id'], $this->no_delete)) ? $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=mbr_group_del_conf'.AMP.'group_id='.$row['group_id'], $LANG->line('delete')) : '--';

            $DSP->body .= $DSP->table_qcell($style,  $delete, '10%');

            $DSP->body .= $DSP->tr_c();
        }
        
        $DSP->body .= $DSP->table_c();

        $DSP->body .= $DSP->td_c()   
                     .$DSP->tr_c()      
                     .$DSP->table_c();      

        
        $DSP->body .= BR;
        
        $DSP->body .= $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=edit_mbr_group').
        
        $DSP->table('tableBorder', '0', '', '100%').
        $DSP->tr().
        $DSP->td('tableHeadingBoldNoBot', '', '', '', 'middle');
        
        $DSP->body .= $LANG->line('create_group_based_on_old').$DSP->nbs(3);
                 
        
        $DSP->body .= $DSP->input_select_header('clone_id');
        
        foreach($query->result as $row)
        {
            $DSP->body .= $DSP->input_select_option($row['group_id'], $row['group_title']);
        }
        
        $DSP->body .= $DSP->input_select_footer();
        $DSP->body .= $DSP->nbs(2).$DSP->input_submit();
                      
        $DSP->body .= $DSP->td_c();                
        $DSP->body .= $DSP->tr_c();
        $DSP->body .= $DSP->table_c();
        $DSP->body .= $DSP->form_c();
        
        $DSP->title  = $LANG->line('member_groups');    
        $DSP->crumb  = $LANG->line('member_groups');    
        $DSP->rcrumb = $DSP->qdiv('crumbLinksR',$DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=edit_mbr_group', $LANG->line('create_new_member_group')));
    }
    // END
    
    
    
    // ----------------------------------
    //  Edit/Create a member group form
    // ----------------------------------   
    
    function edit_member_group_form()
    {  
        global $IN, $DSP, $DB, $SESS, $LANG;

        // ----------------------------------------------------
        // Only super admins can administrate member groups
        // ----------------------------------------------------
                    
        if ($SESS->userdata['group_id'] != 1)
        {
            return $DSP->no_access_message($LANG->line('only_superadmins_can_admin_groups'));
        }
        
        
        $group_id = $IN->GBL('group_id', 'GET');
        $clone_id = $IN->GBL('clone_id', 'POST');
        
        $id = ( ! $group_id) ? '3' : $group_id;
        
  
        // Assign the page title

        $title = ($group_id != '') ? $LANG->line('edit_member_group') : $LANG->line('create_member_group');
                    
        // ----------------------------------
        //  Fetch the member group data
        // ----------------------------------
        
        if ($clone_id != '') $id = $clone_id;
          
        $query = $DB->query("SELECT * FROM exp_member_groups WHERE group_id = '$id'");
        
        $result = ($query->num_rows == 0) ? FALSE : TRUE;
        
        $group_data = array();
        
        foreach ($query->row as $key => $val)
        {
            $group_data[$key] = $val;
        }
                
        
                
        // ----------------------------------
        //  Translate the group title 
        // ----------------------------------
        
        // We only translate this if it has not been edited
        
        $group_title = ($group_id == '') ? '' : $group_data['group_title']; 
            
        if (isset($this->english[$group_title]))
        {
            $group_title = $LANG->line(strtolower(str_replace(" ", "_", $group_title)));
        }
        
        // ----------------------------------
        //  Declare form and page heading
        // ----------------------------------
        
        $r = $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=update_mbr_group');
        
        if ($clone_id != '')
        {
            $group_title = '';
        }
        
        $r .= $DSP->input_hidden('group_id', $group_id);
        $r .= $DSP->heading($title);
        
        // ----------------------------------
        //  Top section of page
        // ----------------------------------
        
        if ($group_id == 1)
        {
            $r .= $DSP->div().
                  $LANG->line('super_admin_edit_note').
                  $DSP->br(2).
                  $DSP->div_c();
        }
        else
        {
            $r .= $DSP->div('itemWrapper').
                  $DSP->qspan('alert', $LANG->line('warning')).
                  $DSP->nbs(2).$LANG->line('be_careful_assigning_groups').
                  $DSP->br().
                  $DSP->div_c();
        }
        
        // ----------------------------------
        //  Group name form field
        // ----------------------------------
        
        $r .= $DSP->table('tableBorder', '0', '', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingBoldNoBot', '50%').
              $LANG->line('group_name', 'group_title').
              $DSP->td_c().
              $DSP->td('tableHeadingBoldNoBot', '50%').
              $DSP->input_text('group_title', $group_title, '50', '70', 'input', '100%').
              $DSP->td_c().   
              $DSP->tr_c().
              $DSP->table_c();

              
        // --------------------------------------
        //  Super Admin Group can not be edited
        // --------------------------------------
              
        // If the form being viewed is the Super Admin one we only allow the name to be changed.
        
        if ($group_id == 1)
        {
            $r .= $DSP->qdiv('', $DSP->br(2));
            $r .= $DSP->input_submit($LANG->line('update'));
            $r .= $DSP->form_c();
        
            return $DSP->set_return_data( 
                                          $DSP->title = $LANG->line($title),
                                          
                                          $r,
                                          
                                          $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=mbr_group_manager', $LANG->line('member_groups')).$DSP->crumb_item($title)
                                        );
        }      
              

        $r .= $DSP->qdiv('', $DSP->br(2));


        // ----------------------------------
        //  Group lock
        // ----------------------------------
        
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold', '', '2').$LANG->line('group_lock').
              $DSP->td_c().
              $DSP->tr_c();
                
        $r .= $DSP->tr().
              $DSP->td('tableCellTwo', '60%').
              $DSP->qdiv('alert', $LANG->line('enable_lock')).
              $DSP->qdiv('itemWrapper', $LANG->line('lock_description')).
              $DSP->td_c().
              $DSP->td('tableCellTwo', '40%');
                 
              $selected = ($group_data['is_locked'] == 'y') ? 1 : '';
            
        $r .= $LANG->line('locked').NBS.
              $DSP->input_radio('is_locked', 'y', $selected).$DSP->nbs(3);

              $selected = ($group_data['is_locked'] == 'n') ? 1 : '';
            
        $r .= $LANG->line('unlocked').NBS.
              $DSP->input_radio('is_locked', 'n', $selected).$DSP->nbs(3);

        $r .= $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();

        $r .= $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();

        $r .= $DSP->qdiv('', $DSP->br(2));
        
        // ----------------------------------------------------
        //  Fetch the names and IDs of all weblogs
        // ----------------------------------------------------              
        
        $query = $DB->query("SELECT weblog_id, blog_title FROM exp_weblogs WHERE is_user_blog = 'n' ORDER BY blog_title");
        
        $res   = $DB->query("SELECT weblog_id FROM exp_weblog_member_groups WHERE group_id = '$group_id' ");

        $blog_names = array();
        $blog_ids   = array();
        
        if ($res->num_rows > 0)
        {
            foreach ($res->result as $row)
            {
                $blog_ids[$row['weblog_id']] = TRUE;
            }
        }
                                
        foreach($query->result as $row)
        {
            $status = (isset($blog_ids[$row['weblog_id']])) ? 'y' : 'n';
        
            $blog_names['weblog_id_'.$row['weblog_id']] = $row['blog_title'];
        
            $group_data['weblog_id_'.$row['weblog_id']] = $status;            
        }
        
        
        // ----------------------------------------------------
        //  Fetch the names and IDs of all modules
        // ----------------------------------------------------              
        
        $query = $DB->query("SELECT module_id, module_name FROM exp_modules WHERE has_cp_backend = 'y' ORDER BY module_name");
        
        $res   = $DB->query("SELECT module_id FROM exp_module_member_groups WHERE group_id = '$group_id' ");

        $module_names = array();
        $module_ids   = array();

        if ($res->num_rows > 0)
        {
            foreach ($res->result as $row)
            {
                $module_ids[$row['module_id']] = TRUE;
            }
        }
                        
        foreach($query->result as $row)
        {
            $status = (isset($module_ids[$row['module_id']])) ? 'y' : 'n';
                
            $module_names['module_id_'.$row['module_id']] = $row['module_name'];
        
            $group_data['module_id_'.$row['module_id']] = $status;            
        }
        
        
        // ----------------------------------------------------
        //  Fetch the names and IDs of all template groups
        // ----------------------------------------------------              
        
        $query = $DB->query("SELECT group_id, group_name FROM exp_template_groups WHERE is_user_blog = 'n' ORDER BY group_name");
        
        $res   = $DB->query("SELECT template_group_id FROM exp_template_member_groups WHERE group_id = '$group_id' ");

        $template_names = array();
        $template_ids   = array();

        if ($res->num_rows > 0)
        {
            foreach ($res->result as $row)
            {
                $template_ids[$row['template_group_id']] = TRUE;
            }
        }
                        
        foreach($query->result as $row)
        {
            $status = (isset($template_ids[$row['group_id']])) ? 'y' : 'n';
                
            $template_names['template_id_'.$row['group_id']] = $row['group_name'];
        
            $group_data['template_id_'.$row['group_id']] = $status;            
        }
        
        
        // ----------------------------------------------------
        // Assign clusters of member groups
        // ----------------------------------------------------     
                 
        // NOTE: the associative value (y/n) is the default setting used
        // only when we are showing the "create new group" form

        $G = array(
                    
                'site_access' 		=> array (
                                                'can_view_online_system'	=> 'n',
                                                'can_view_offline_system'	=> 'n'
                                             ),
                                             
                'mbr_profile_privs' => array (
                                                'can_view_profiles'			=> 'n',
                                                'can_email_from_profile'	=> 'n',
                                             ),
                                                                                          
                'site_access' 		=> array (
                                                'can_view_online_system'	=> 'n',
                                                'can_view_offline_system'	=> 'n'
                                             ),
                                             
                'commenting_privs' => array (
                                                'can_post_comments'			=> 'n'
                                             ),
                                             
                'search_privs'		=> array (
                                                'can_search'				=> 'n',
                                                'search_flood_control'		=> '30',
                                             ),

                'global_cp_access'  => array (
                                                'can_access_cp'         	=> 'n'
                                             ),
        
                'cp_section_access' => array (
                                                'can_access_publish'    	=> 'n',
                                                'can_access_edit'       	=> 'n',
                                                'can_access_design'     	=> 'n',
                                                'can_access_comm'       	=> 'n',
                                                'can_access_modules'    	=> 'n',
                                                'can_access_admin'      	=> 'n'
                                             ),
        
                'cp_admin_privs'    => array (
                                                'can_admin_weblogs'     	=> 'n',
                                                'can_admin_templates'   	=> 'n',
                                                'can_admin_members'     	=> 'n',
                                                'can_admin_mbr_groups'  	=> 'n',
                                                'can_admin_mbr_templates'  	=> 'n',
                                                'can_delete_members'    	=> 'n',
                                                'can_ban_users'         	=> 'n',
                                                'can_admin_utilities'   	=> 'n',
                                                'can_admin_preferences' 	=> 'n',
                                                'can_admin_modules'     	=> 'n'
                                             ),
                                             
                'cp_email_privs' => array (
                                                'can_send_email'			=> 'n',
                                                'can_email_members'     	=> 'n',
                                                'can_email_member_groups'	=> 'n',
                                                'can_email_mailinglist'		=> 'n',
                                                'can_send_cached_email'		=> 'n',                                                
                                             ),                                             
                                             
                'cp_weblog_privs'   =>  array(
                                                'can_view_other_entries'   => 'n',
                                                'can_delete_self_entries'  => 'n',
                                                'can_edit_other_entries'   => 'n',
                                                'can_delete_all_entries'   => 'n',
                                                'can_assign_post_authors'  => 'n'
                                             ),

                'cp_weblog_post_privs'   =>  $blog_names,

                                             
                'cp_comment_privs' => array (
                                                'can_moderate_comments'   	=> 'n',
                                                'can_view_other_comments'   => 'n',
                                                'can_edit_own_comments'     => 'n',
                                                'can_delete_own_comments'   => 'n',
                                                'can_edit_all_comments'     => 'n',
                                                'can_delete_all_comments'   => 'n'
                                             ),
                                             
                                             
                                             
                'cp_template_access_privs'   =>  $template_names,
                
                
                'cp_module_access_privs'   =>  $module_names
                                             
                   );

        // ---------------------------------------
        // Assign items we want to highlight
        // ---------------------------------------  
        
        $alert = array(
                        'can_view_offline_system',
                        'can_access_cp',
                        'can_admin_weblogs', 
                        'can_admin_templates',
                        'can_delete_members',
                        'can_admin_mbr_groups', 
                        'can_admin_mbr_templates',
                        'can_ban_users', 
                        'can_admin_members', 
                        'can_admin_preferences', 
                        'can_admin_modules', 
                        'can_admin_utilities'
                      );


        // ---------------------------------------
        // Items that should be shown in an input box
        // ---------------------------------------  
        
        $tbox = array(
                        'search_flood_control'
                      );


        // ---------------------------------------
        // Render the group matrix
        // ---------------------------------------  

        foreach ($G as $g_key => $g_val)
        {
            $r .= $DSP->table('tableBorder', '0', '0', '100%').
                  $DSP->tr().
                  $DSP->td('tablePad'); 

            $r .= $DSP->table('', '0', '', '100%').
                  $DSP->tr().
                  $DSP->td('tableHeadingLargeBold', '', '2').
                  $LANG->line($g_key).
                  $DSP->td_c().
                  $DSP->tr_c();
    
            $i = 0;
            
            foreach($g_val as $key => $val)
            {
                if ($result == FALSE)
                    $group_data[$key] = $val;
            
                $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
           
                $line = $LANG->line($key);                
                
                if (substr($key, 0, 10) == 'weblog_id_')
                {
                    $line = $LANG->line('can_post_in').NBS.NBS.$DSP->qspan('alert', $blog_names[$key]);
                }
                
                if (substr($key, 0, 10) == 'module_id_')
                {
                    $line = $LANG->line('can_access_mod').NBS.NBS.$DSP->qspan('alert', $module_names[$key]);
                }
                
                if (substr($key, 0, 12) == 'template_id_')
                {
                    $line = $LANG->line('can_access_tg').NBS.NBS.$DSP->qspan('alert', $template_names[$key]);
                }

                                    
                $mark = (in_array($key, $alert)) ?  $DSP->qspan('alert', $line) : $DSP->qspan('defaultBold', $line);
            
                $r .= $DSP->tr().
                      $DSP->td($style, '60%').
                      $mark;
                                            
                $r .= $DSP->td_c().
                      $DSP->td($style, '40%');
                  
                if (in_array($key, $tbox)) 
                {
					$r .= $DSP->input_text($key, $group_data[$key], '15', '5', 'input', '100px');
                }
                else
                {
					$r .= $LANG->line('yes').NBS.
						  $DSP->input_radio($key, 'y', ($group_data[$key] == 'y') ? 1 : '').$DSP->nbs(3);
	
					$r .= $LANG->line('no').NBS.
						  $DSP->input_radio($key, 'n', ($group_data[$key] == 'n') ? 1 : '').$DSP->nbs(3);
				}
				
                $r .= $DSP->td_c();
                $r .= $DSP->tr_c();
            }
    
            $r .= $DSP->table_c();
    
            $r .= $DSP->td_c()   
                 .$DSP->tr_c()      
                 .$DSP->table_c();      
    
            $r .= $DSP->qdiv('', $DSP->br(2));
        }      
        
        // ---------------------------------------
        // Submit button
        // ---------------------------------------  
       
        if ($group_id == '')
        {
            $r .= $DSP->qdiv('', $DSP->input_submit($LANG->line('submit')));
        }
        else
        {
            $r .= $DSP->qdiv('', $DSP->input_submit($LANG->line('update')));
        }

        $r .= $DSP->form_c();


        // ---------------------------------------
        // Assign output data
        // ---------------------------------------

        $DSP->title = $LANG->line($title);  
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=mbr_group_manager', $LANG->line('member_groups')).$DSP->crumb_item($title);
        $DSP->body .= $r;    
    }
    // END    
    
  
  
    // -----------------------------
    //  Create/update a member group
    // -----------------------------   
    
    function update_member_group()
    {  
        global $IN, $DSP, $DB, $SESS, $LOG, $LANG;
  
        // ----------------------------------------------------
        // Only super admins can administrate member groups
        // ----------------------------------------------------
                    
        if ($SESS->userdata['group_id'] != 1)
        {
            return $DSP->no_access_message($LANG->line('only_superadmins_can_admin_groups'));
        }
        
        $group_id = $IN->GBL('group_id', 'POST');
                
        // Only super admins can edit the "super admin" group

        if ($group_id == 1  AND $SESS->userdata['group_id'] != 1)
        {
            return $DSP->no_access_message();
        }
    
        // No group name
        
        if ( ! $IN->GBL('group_title', 'POST'))
        {
            return $DSP->error_message($LANG->line('missing_group_title'));
        }
        
        
        // Update weblog posting privs
        
        $weblogs = array();
        
        $DB->query("DELETE FROM exp_weblog_member_groups WHERE group_id = '$group_id'");
        
        foreach ($_POST as $key => $val)
        {
            if (substr($key, 0, 10) == 'weblog_id_')
            {
                if ($val == 'y')
                {
                    $DB->query("INSERT INTO exp_weblog_member_groups (group_id, weblog_id) VALUES ('$group_id', '".substr($key, 10)."')");
                }
                        
                unset($_POST[$key]);
            }
        }
        
              
        // Update module posting privs
        
        $weblogs = array();
        
        $DB->query("DELETE FROM exp_module_member_groups WHERE group_id = '$group_id'");
        
        foreach ($_POST as $key => $val)
        {
            if (substr($key, 0, 10) == 'module_id_')
            {
                if ($val == 'y')
                {
                    $DB->query("INSERT INTO exp_module_member_groups (group_id, module_id) VALUES ('$group_id', '".substr($key, 10)."')");
                }
                        
                unset($_POST[$key]);
            }
        }
        
        
        // Update template group posting privs
        
        $weblogs = array();
        
        $DB->query("DELETE FROM exp_template_member_groups WHERE group_id = '$group_id'");
        
        foreach ($_POST as $key => $val)
        {
            if (substr($key, 0, 12) == 'template_id_')
            {
                if ($val == 'y')
                {
                    $DB->query("INSERT INTO exp_template_member_groups (group_id, template_group_id) VALUES ('$group_id', '".substr($key, 12)."')");
                }
                        
                unset($_POST[$key]);
            }
        }
        
                
        if ($group_id == '')
        {
            unset($_POST['group_id']);
        
            $str = $DB->insert_string('exp_member_groups', $_POST);
            
            $DB->query($str);
            
            $group_id = $DB->insert_id;
            
            $uploads = $DB->query("SELECT exp_upload_prefs.id FROM exp_upload_prefs");
            
             if ($uploads->num_rows > 0)
             {
                  foreach($uploads->result as $yeeha)
                  {
                       $DB->query("INSERT INTO exp_upload_no_access (upload_id, upload_loc, member_group) VALUES ('".$yeeha['id']."', 'cp', '{$group_id}')");
                  }
             }  
             
            $message = $LANG->line('member_group_created').$DSP->nbs(2).$_POST['group_title'];            
        }
        else
        {
            unset($_POST['group_id']);
        
            $str = $DB->update_string('exp_member_groups', $_POST, "group_id='$group_id'");
            
            $DB->query($str);
            
            $message = $LANG->line('member_group_updated').$DSP->nbs(2).$_POST['group_title'];
        }
        
        $LOG->log_action($message);            
   
  
        return $this->member_group_manager($DSP->qdiv('success', $message));
  
    }  
    // END    
    
    
    //-----------------------------------------------------------
    // Delete member group confirm
    //-----------------------------------------------------------
    // Warning message shown when you try to delete a group
    //-----------------------------------------------------------

    function delete_member_group_conf()
    {  
        global $DSP, $IN, $DB, $SESS, $LANG;
  
        // ----------------------------------------------------
        // Only super admins can delete member groups
        // ----------------------------------------------------
                    
        if ($SESS->userdata['group_id'] != 1)
        {
            return $DSP->no_access_message($LANG->line('only_superadmins_can_admin_groups'));
        }
        

        if ( ! $group_id = $IN->GBL('group_id'))
        {
            return false;
        }
        
        // You can't delete these groups
                
        if (in_array($group_id, $this->no_delete))
        {
            return $DSP->no_access_message();
        }

        $query = $DB->query("SELECT group_title FROM exp_member_groups WHERE group_id = '$group_id'");
        
        $DSP->title = $LANG->line('delete_member_group');
        
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=group_manager', $LANG->line('member_groups')).$DSP->crumb_item($LANG->line('delete_member_group'));


        $DSP->body = $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=delete_mbr_group'.AMP.'group_id='.$group_id)
                    .$DSP->input_hidden('group_id', $group_id)
                    .$DSP->heading($LANG->line('delete_member_group'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_member_group_confirm').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['group_title'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END    
    
    
    
    
    // -----------------------------------
    //  Delete Member Group
    // -----------------------------------   
    
    function delete_member_group()
    {  
        global $DSP, $IN, $DB, $LANG, $SESS;

        // ----------------------------------------------------
        // Only super admins can delete member groups
        // ----------------------------------------------------
                    
        if ($SESS->userdata['group_id'] != 1)
        {
            return $DSP->no_access_message($LANG->line('only_superadmins_can_admin_groups'));
        }

        if ( ! $group_id = $IN->GBL('group_id', 'POST'))
        {
            return false;
        }
                
        if (in_array($group_id, $this->no_delete))
        {
            return $DSP->no_access_message();
        }

        $DB->query("DELETE FROM exp_member_groups WHERE group_id = '$group_id'");
        
        return $this->member_group_manager($DSP->qdiv('success', $LANG->line('member_group_deleted')));
    }    
    // END
    
    
    
    // -----------------------------------
    //  Create a member profile form
    // -----------------------------------   
    
    function new_member_profile_form()
    {  
        global $IN, $DSP, $DB, $LANG, $SESS;
        
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }
        
        $DSP->body_props = " onLoad=\"document.forms[0].username.focus();\"";
        
        $title = $LANG->line('register_member');
        
        // Build the output
        
        $r  = $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=register_member');
        
        $r .= $DSP->heading($title);
                                            
        $r .= $DSP->itemgroup(
                                $DSP->required().NBS.$LANG->line('username', 'username'),
                                $DSP->input_text('username', '', '35', '32', 'input', '300px')
                              );
                              
        $r .= $DSP->itemgroup(
                                $DSP->required().NBS.$LANG->line('password', 'password'),
                                $DSP->input_pass('password', '', '35', '32', 'input', '300px')
                              );
                              
        $r .= $DSP->itemgroup(
                                $DSP->required().NBS.$LANG->line('password_confirm', 'password_confirm'),
                                $DSP->input_pass('password_confirm', '', '35', '32', 'input', '300px')
                              );
        
        $r .= $DSP->itemgroup(
                                $DSP->required().NBS.$LANG->line('screen_name', 'screen_name'),
                                $DSP->input_text('screen_name', '', '40', '50', 'input', '300px')
                              );
        
        $r .= $DSP->td_c().
              $DSP->td('', '45%', '', '', 'top');
     
        $r .= $DSP->itemgroup(
                                $DSP->required().NBS.$LANG->line('email', 'email'),
                                $DSP->input_text('email', '', '35', '100', 'input', '300px')
                              );
     
                              
           
        // Member groups assignment
                       
        if ($DSP->allowed_group('can_admin_mbr_groups'))
        {
            if ($SESS->userdata['group_id'] != 1)
            {
                $sql = "SELECT group_id, group_title FROM exp_member_groups WHERE is_locked = 'n' order by group_title";
            }
            else
            {
                $sql = "SELECT group_id, group_title FROM exp_member_groups order by group_title";
            }

            $query = $DB->query($sql);
            
            if ($query->num_rows > 0)
            {            
				$r .= $DSP->qdiv(
								 'itemWrapperTop', 
								  $DSP->qdiv('defaultBold', $LANG->line('member_group_assignment'))
								 );
					  
				$r .= $DSP->input_select_header('group_id');
										
				foreach ($query->result as $row)
				{            
					$selected = ($row['group_id'] == 5) ? 1 : '';
					
					if ($row['group_id'] == 1 AND $SESS->userdata['group_id'] != 1)
					{
						continue;
					}
	
					$group_title = $row['group_title'];
							
					if (in_array($group_title, $this->english))
					{
						$group_title = $LANG->line(strtolower(str_replace(" ", "_", $group_title)));
					}
	
					
					$r .= $DSP->input_select_option($row['group_id'], $group_title, $selected);
				}
				
				$r .= $DSP->input_select_footer();
			}
        }        
        
        // Submit button   
        
        $r .= $DSP->itemgroup( '',
                                $DSP->required(1).$DSP->br(2).$DSP->input_submit($LANG->line('submit'))
                              );

        $r.=  $DSP->form_c();
        
        
        $DSP->title = &$title;
        $DSP->crumb = &$title;
        $DSP->body  = &$r;
    }
    // END



    // ----------------------------------
    //  Create a member profile
    // ----------------------------------
    
    function create_member_profile()
    {  
        global $IN, $DSP, $DB, $SESS, $PREFS, $FNS, $REGX, $LOC, $LOG, $LANG, $STAT;
        
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }
        
        $data = array();
        
        if ($IN->GBL('group_id', 'POST'))
        {        
            if ( ! $DSP->allowed_group('can_admin_mbr_groups'))
            {
                return $DSP->no_access_message();
            } 
            
            $data['group_id'] = $_POST['group_id'];
        }   
            

		// If the screen name field is empty, we'll assign is
		// from the username field.              
               
		if ($_POST['screen_name'] == '')
			$_POST['screen_name'] = $_POST['username'];              

        // -------------------------------------
        //  Instantiate validation class
        // -------------------------------------

		if ( ! class_exists('Validate'))
		{
			require PATH_CORE.'core.validate'.EXT;
		}
		
		$VAL = new Validate(
								array( 
										'member_id'			=> '',
										'val_type'			=> 'new', // new or update
										'fetch_lang' 		=> TRUE, 
										'require_cpw' 		=> FALSE,
									 	'enable_log'		=> TRUE,
										'username'			=> $_POST['username'],
										'cur_username'		=> '',
										'screen_name'		=> $_POST['screen_name'],
										'cur_screen_name'	=> '',
										'password'			=> $_POST['password'],
									 	'password_confirm'	=> $_POST['password_confirm'],
									 	'cur_password'		=> '',
									 	'email'				=> $_POST['email'],
									 	'cur_email'			=> ''
									 )
							);
		
		$VAL->validate_username();
		$VAL->validate_screen_name();
		$VAL->validate_password();
		$VAL->validate_email();

        // -------------------------------------
        //  Display error is there are any
        // -------------------------------------

         if (count($VAL->errors) > 0)
         {            
            return $VAL->show_errors();
         }
         
        // Assign the query data
         
        $data['username']    = $_POST['username'];
        $data['password']    = $FNS->hash(stripslashes($_POST['password']));
        $data['ip_address']  = $IN->IP;
        $data['unique_id']   = $FNS->random('encrypt');
        $data['join_date']   = $LOC->now;
        $data['email']       = $_POST['email'];
        $data['screen_name'] = $_POST['screen_name'];
                      
        // Was a member group ID submitted?
        
        $data['group_id'] = ( ! $IN->GBL('group_id', 'POST')) ? 2 : $_POST['group_id'];

        $DB->query($DB->insert_string('exp_members', $data)); 
        
        $member_id = $DB->insert_id;  
        
        // Create a record in the custom field table
                                       
        $DB->query($DB->insert_string('exp_member_data', array('member_id' => $member_id)));
        
        // Create a record in the member homepage table
                            
        $DB->query($DB->insert_string('exp_member_homepage', array('member_id' => $member_id)));
        
        $message = $LANG->line('new_member_added');
        
        // Write log file
        
        $LOG->log_action($message.$DSP->nbs(2).stripslashes($data['username']));
        
        // Update global stat
        
		$STAT->update_member_stats();
        
        // Build success message
        
        return $this->view_all_members($DSP->qspan('success', $message).NBS.'<b>'.stripslashes($data['username']).'</b>');   
    }
    // END
    
    
    
    // -----------------------------
    //  Member banning forms
    // -----------------------------   
    
    function member_banning_forms()
    {  
        global $IN, $LANG, $DSP, $PREFS, $DB;
        
        if ( ! $DSP->allowed_group('can_ban_users'))
        {
            return $DSP->no_access_message();
        }
        
        $banned_ips   = $PREFS->ini('banned_ips');
        $banned_emails  = $PREFS->ini('banned_emails');
        $banned_usernames = $PREFS->ini('banned_usernames');
        $banned_screen_names = $PREFS->ini('banned_screen_names');
        
        $out    	= '';
        $ips    	= '';
        $email  	= '';
        $users  	= '';
        $screens	= '';
        
        if ($banned_ips != '')
        {            
            foreach (explode('|', $banned_ips) as $val)
            {
                $ips .= $val.NL;
            }
        }
        
        if ($banned_emails != '')
        {                        
            foreach (explode('|', $banned_emails) as $val)
            {
                $email .= $val.NL;
            }
        }
        
        if ($banned_usernames != '')
        {                        
            foreach (explode('|', $banned_usernames) as $val)
            {
                $users .= $val.NL;
            }
        }

        if ($banned_screen_names != '')
        {                        
            foreach (explode('|', $banned_screen_names) as $val)
            {
                $screens .= $val.NL;
            }
        }

        $r  = $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=save_ban_data').
              $DSP->heading($LANG->line('user_banning'));
        
        if ($IN->GBL('U'))
        {
            $r .= $DSP->qdiv('success', $LANG->line('ban_preferences_updated'));
        }
        
        $r .= $DSP->table('', '', '', '100%', '').
              $DSP->tr().
              $DSP->td('', '50%', '', '', 'top');        
        
        $r .= $DSP->heading($LANG->line('ip_address_banning', 'banned_ips'), 5).
              $DSP->qdiv('itemWrapper', $DSP->qspan('highlight', $LANG->line('ip_banning_instructions'))).
              $DSP->qdiv('itemWrapper', $LANG->line('ip_banning_instructions_cont')).              
              $DSP->input_textarea('banned_ips', stripslashes($ips), '18', 'textarea', '82%').BR.BR; 
               
        $r .= $DSP->heading($LANG->line('ban_options'), 5);
        
              $selected = ($PREFS->ini('ban_action') == 'restrict') ? 1 : '';   
              
        $r .= $DSP->div('itemWrapper').
              $DSP->input_radio('ban_action', 'restrict', $selected).NBS. $LANG->line('restrict_to_viewing').BR.
              $DSP->div_c();
              
              $selected    = ($PREFS->ini('ban_action') == 'message') ? 1 : '';
        
        $r .= $DSP->div('itemWrapper').
              $DSP->input_radio('ban_action', 'message', $selected).NBS.$LANG->line('show_this_message', 'ban_message').BR.
              $DSP->input_text('ban_message', $PREFS->ini('ban_message'), '50', '100', 'input', '80%').
              $DSP->div_c();
              
              $selected    = ($PREFS->ini('ban_action') == 'bounce') ? 1 : '';
              $destination = ($PREFS->ini('ban_destination') == '') ? 'http://' : $PREFS->ini('ban_destination');
              
        $r .= $DSP->div('itemWrapper').
              $DSP->input_radio('ban_action', 'bounce', $selected).NBS.$LANG->line('send_to_site', 'ban_destination').BR.
              $DSP->input_text('ban_destination', $destination, '50', '70', 'input', '80%').
              $DSP->div_c();

        $r .= $DSP->div().BR.
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c();
              
        $r .= $DSP->td_c().        
              $DSP->td('', '50%', '', '', 'top');        
        
        $r .= $DSP->heading($LANG->line('email_address_banning', 'banned_emails'), 5).
              $DSP->qdiv('itemWrapper', $DSP->qspan('highlight', $LANG->line('email_banning_instructions'))).
              $DSP->qdiv('itemWrapper', $LANG->line('email_banning_instructions_cont')).
              $DSP->input_textarea('banned_emails', stripslashes($email), '9', 'textarea', '90%');

        $r .= $DSP->heading(BR.$LANG->line('username_banning', 'banned_usernames'), 5).
              $DSP->qdiv('itemWrapper', $DSP->qspan('highlight', $LANG->line('username_banning_instructions'))).
              $DSP->input_textarea('banned_usernames', stripslashes($users), '9', 'textarea', '90%');

        $r .= $DSP->heading(BR.$LANG->line('screen_name_banning', 'banned_screen_names'), 5).
              $DSP->qdiv('itemWrapper', $DSP->qspan('highlight', $LANG->line('screen_name_banning_instructions'))).
              $DSP->input_textarea('banned_screen_names', stripslashes($screens), '9', 'textarea', '90%');

        $r .= $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();        

		$r .= $DSP->form_c();

        $DSP->title = $LANG->line('user_banning');
        $DSP->crumb = $LANG->line('user_banning');
        $DSP->body  = &$r;
    }
    // END


    // -----------------------------
    //  Update banning data
    // -----------------------------   
    
    function update_banning_data()
    {
        global $IN, $DSP;
    
        if ( ! $DSP->allowed_group('can_ban_users'))
        {
            return $DSP->no_access_message();
        }
                
		foreach ($_POST as $key => $val)
		{ 
			$_POST[$key] = stripslashes($val);		
		}        

        $banned_ips    			= str_replace(NL, '|', $_POST['banned_ips']);
        $banned_emails 			= str_replace(NL, '|', $_POST['banned_emails']);
        $banned_usernames 		= str_replace(NL, '|', $_POST['banned_usernames']);
        $banned_screen_names 	= str_replace(NL, '|', $_POST['banned_screen_names']);
        
        $destination = ($_POST['ban_destination'] == 'http://') ? '' : $_POST['ban_destination'];
        
        $data = array(	
                        'banned_ips'      		=> $banned_ips,
                        'banned_emails'   		=> $banned_emails,
                        'banned_emails'   		=> $banned_emails,
                        'banned_usernames'		=> $banned_usernames,
                        'banned_screen_names'	=> $banned_screen_names,
                        'ban_action'      		=> $_POST['ban_action'],
                        'ban_message'     		=> $_POST['ban_message'],
                        'ban_destination' 		=> $destination
                     );
               
        Admin::update_config_file(
                                    $data,  
                                    BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=member_banning'.AMP.'U=1'
                                  );       
    }
    // END
    
    
    

    //-----------------------------------------------------------
    //  Custom profile fields
    //-----------------------------------------------------------
    // This function show a list of current member fields and the
    // form that allows you to create a new field.
    //-----------------------------------------------------------

    function custom_profile_fields($group_id = '')
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }

        // Fetch language file
        // There are some lines in the publish administration language file
        // that we need.

        $LANG->fetch_language_file('publish_ad');

        
        // Build the output of the left side of the page
     
        $r = $DSP->heading($LANG->line('custom_member_fields'));
        
        if ($IN->GBL('U'))
        {
        	$r .= $DSP->qdiv('success', $LANG->line('field_updated'));
        }
     
        $r .= $DSP->div('tableBorder'); 
        $r .= $DSP->div('tablePad'); 

        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingBold', '', '3').
              $LANG->line('current_fields').
              $DSP->td_c().
              $DSP->tr_c();

        $query = $DB->query("SELECT m_field_id, m_field_order, m_field_label FROM  exp_member_fields ORDER BY m_field_order");
        
  
        if ($query->num_rows == 0)
        {
            $r .= $DSP->tr().
                  $DSP->td('tableCellTwo', '', '3').
                  $DSP->qdiv('highlight', $LANG->line('no_custom_profile_fields')).
                  $DSP->qdiv('defaultBold', BR.$DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=edit_field', $LANG->line('create_new_profile_field'))).
                  $DSP->td_c().
                  $DSP->tr_c();
        }  

        $i = 0;
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;

                $r .= $DSP->tr();
                $r .= $DSP->table_qcell($style, $row['m_field_order'].$DSP->nbs(2).$DSP->qspan('defaultBold', $row['m_field_label']), '40%');
                $r .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=edit_field'.AMP.'m_field_id='.$row['m_field_id'], $LANG->line('edit')), '30%');      
                $r .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=del_field_conf'.AMP.'m_field_id='.$row['m_field_id'], $LANG->line('delete')), '30%');      
                $r .= $DSP->tr_c();
            }
        }
        
        $r .= $DSP->table_c();

        $r .= $DSP->div_c();      
        $r .= $DSP->div_c();      

        if ($query->num_rows > 0)
        {
            $r .= $DSP->qdiv('paddedWrapper', BR.$DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=edit_field_order', $LANG->line('edit_field_order')));
        }
                
        $DSP->title  = $LANG->line('custom_member_fields');
        $DSP->crumb  = $LANG->line('custom_member_fields');
        $DSP->rcrumb = $DSP->qdiv('crumblinksR', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=edit_field', $LANG->line('create_new_profile_field')));
        $DSP->body   = &$r;  
    }
    // END  
    
  


    //-----------------------------------------------------------
    // Edit field form
    //-----------------------------------------------------------
    // This function lets you edit an existing custom field
    //-----------------------------------------------------------

    function edit_profile_field_form()
    {  
        global $DSP, $IN, $DB, $REGX, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }

        $type = ($m_field_id = $IN->GBL('m_field_id')) ? 'edit' : 'new';
        
        // Fetch language file
        // There are some lines in the publish administration language file
        // that we need.

        $LANG->fetch_language_file('publish_ad');
        
        $total_fields = '';
        
        if ($type == 'new')
        {
            $query = $DB->query("SELECT count(*) AS count FROM exp_member_fields");
            
            $total_fields = $query->row['count'] + 1;
        }
        
        $DB->fetch_fields = TRUE;
        
        $query = $DB->query("SELECT * FROM exp_member_fields WHERE m_field_id = '$m_field_id'");
        
        if ($query->num_rows == 0)
        {
            foreach ($query->fields as $f)
            {
                $$f = '';
            }
        }
        else
        {        
            foreach ($query->row as $key => $val)
            {
                $$key = $val;
            }
        }
        
        
        $title = ($type == 'edit') ? 'edit_member_field' : 'create_member_field';

		$i = 0;
		
        // Form declaration
        
        $r  = $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=update_profile_fields'.AMP.'U=1');
        $r .= $DSP->input_hidden('m_field_id', $m_field_id);
        $r .= $DSP->input_hidden('cur_field_name', $m_field_name);
        
        $r .= $DSP->div('tableBorder');
        $r .= $DSP->div('tablepad');
        
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold', '', '2').$LANG->line($title).$DSP->td_c().
              $DSP->tr_c();
              
                
        //---------------------------------
        // Field name
        //---------------------------------
        
		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $DSP->required().NBS.$LANG->line('fieldname', 'm_field_name')).$DSP->qdiv('itemWrapper', $LANG->line('fieldname_cont')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('m_field_name', $m_field_name, '50', '60', 'input', '300px'), '60%');
		$r .= $DSP->tr_c();		

        //---------------------------------
        // Field label
        //---------------------------------

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $DSP->required().NBS.$LANG->line('fieldname', 'm_field_label')).$DSP->qdiv('itemWrapper', $LANG->line('for_profile_page')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('m_field_label', $m_field_label, '50', '60', 'input', '300px'), '60%');
		$r .= $DSP->tr_c();		
		 
		 
        //---------------------------------
        // Field order
        //---------------------------------
        
        if ($type == 'new')
            $m_field_order = $total_fields;
            
		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('field_order', 'm_field_order')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('m_field_order', $m_field_order, '4', '3', 'input', '30px'), '60%');
		$r .= $DSP->tr_c();		
              
        //---------------------------------
        // Field type
        //---------------------------------
        
        $sel_1 = ''; $sel_2 = ''; $sel_3 = '';

        switch ($m_field_type)
        {
            case 'text'     : $sel_1 = 1;
                break;
            case 'textarea' : $sel_2 = 1;
                break;
            case 'select'   : $sel_3 = 1;
                break;
        }
        
		$typemenu  = $DSP->input_select_header('m_field_type')
					.$DSP->input_select_option('text', 		$LANG->line('text_input'),	$sel_1)
					.$DSP->input_select_option('textarea', 	$LANG->line('textarea'),  	$sel_2)
					.$DSP->input_select_option('select', 	$LANG->line('select_list'), $sel_3)
					.$DSP->input_select_footer();

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
		
		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('field_type')), '40%');
		$r .= $DSP->table_qcell($style, $typemenu, '60%');
		$r .= $DSP->tr_c();		
		
        //---------------------------------
        // Field formatting
        //---------------------------------
                
        $sel_1 = ''; $sel_2 = ''; $sel_3 = '';
        
        switch ($m_field_fmt)
        {
            case 'none'  : $sel_1 = 1;
                break;
            case 'br'    : $sel_2 = 1;
                break;
            case 'xhtml' : $sel_3 = 1;
                break;
            default		 : $sel_3 = 1;
                break;
        }
        
		$typemenu  = $DSP->input_select_header('m_field_fmt')
					.$DSP->input_select_option('none', $LANG->line('none'), $sel_1)
					.$DSP->input_select_option('br', $LANG->line('auto_br'), $sel_2)
					.$DSP->input_select_option('xhtml', $LANG->line('xhtml'), $sel_3)
					.$DSP->input_select_footer();
					
		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
		
		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('field_format')).$DSP->qdiv('itemWrapper', $LANG->line('text_area_rows_cont')), '40%');
		$r .= $DSP->table_qcell($style, $typemenu, '60%');
		$r .= $DSP->tr_c();		

        //---------------------------------
        // Is field required?
        //---------------------------------
              
        if ($m_field_required == '') $m_field_required = 'n';

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('is_field_required')), '40%');
		$r .= $DSP->table_qcell($style, $LANG->line('yes').$DSP->nbs().$DSP->input_radio('m_field_required', 'y', ($m_field_required == 'y') ? 1 : '').$DSP->nbs(3).$LANG->line('no').$DSP->nbs().$DSP->input_radio('m_field_required', 'n', ($m_field_required == 'n') ? 1 : ''), '60%');
		$r .= $DSP->tr_c();		
             
      
        //---------------------------------
        // Is field public?
        //---------------------------------
              
        if ($m_field_public == '') $m_field_public = 'y';

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('is_field_public')).$DSP->qdiv('itemWrapper', $LANG->line('is_field_public_cont')), '40%');
		$r .= $DSP->table_qcell($style, $LANG->line('yes').$DSP->nbs().$DSP->input_radio('m_field_public', 'y', ($m_field_public == 'y') ? 1 : '').$DSP->nbs(3).$LANG->line('no').$DSP->nbs().$DSP->input_radio('m_field_public', 'n', ($m_field_public == 'n') ? 1 : ''), '60%');
		$r .= $DSP->tr_c();		

        //---------------------------------
        // Is field visible in reg page?
        //---------------------------------
        
        if ($m_field_reg == '') $m_field_reg = 'n';

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('is_field_reg')).$DSP->qdiv('itemWrapper', $LANG->line('is_field_public_cont')), '40%');
		$r .= $DSP->table_qcell($style, $LANG->line('yes').$DSP->nbs().$DSP->input_radio('m_field_reg', 'y', ($m_field_reg == 'y') ? 1 : '').$DSP->nbs(3).$LANG->line('no').$DSP->nbs().$DSP->input_radio('m_field_reg', 'n', ($m_field_reg == 'n') ? 1 : ''), '60%');
		$r .= $DSP->tr_c();		

        //---------------------------------
        // Is field searchable?
        //---------------------------------
        /*     
        if ($m_field_search == '') $m_field_search = 'n';

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('is_field_searchable')), '40%');
		$r .= $DSP->table_qcell($style, $LANG->line('yes').$DSP->nbs().$DSP->input_radio('m_field_search', 'y', ($m_field_search == 'y') ? 1 : '').$DSP->nbs(3).$LANG->line('no').$DSP->nbs().$DSP->input_radio('m_field_search', 'n', ($m_field_search == 'n') ? 1 : ''), '60%');
		$r .= $DSP->tr_c();		              
        */
              
        //---------------------------------
        // Field width
        //---------------------------------

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
		
		if ($m_field_width == '')
			$m_field_width = '100%';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('field_width', 'm_field_width')).$DSP->qdiv('itemWrapper', $LANG->line('field_width_cont')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('m_field_width', $m_field_width, '8', '6', 'input', '60px'), '60%');
		$r .= $DSP->tr_c();		

        //---------------------------------
        // Field max length
        //---------------------------------

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
		
		if ($m_field_maxl == '')
			$m_field_maxl = '100';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('max_length', 'm_field_maxl')).$DSP->qdiv('itemWrapper', $LANG->line('max_length_cont')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('m_field_maxl', $m_field_maxl, '5', '3', 'input', '60px'), '60%');
		$r .= $DSP->tr_c();		

        //---------------------------------
        // Textarea rows
        //---------------------------------

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
		
		if ($m_field_ta_rows == '')
			$m_field_ta_rows = '10';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('text_area_rows', 'm_field_ta_rows')).$DSP->qdiv('itemWrapper', $LANG->line('text_area_rows_cont')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('m_field_ta_rows', $m_field_ta_rows, '4', '3', 'input', '60px'), '60%');
		$r .= $DSP->tr_c();		

        //---------------------------------
        // Field list items
        //---------------------------------
            
		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style,  $DSP->qdiv('itemWrapper', $DSP->qspan('defaultBold', $LANG->line('pull_down_items', 'm_field_list_items')))
										.$DSP->qdiv('itemWrapper', $LANG->line('pull_down_items_cont'))
										.$DSP->qdiv('itemWrapper', $LANG->line('pull_down_instructions')), '40%', 'top');
		$r .= $DSP->table_qcell($style, $DSP->input_textarea('m_field_list_items', $m_field_list_items, 10, 'textarea', '400px'), '60%');
		$r .= $DSP->tr_c();		


		$r .= $DSP->table_c();
		$r .= $DSP->div_c();
		$r .= $DSP->div_c();



        $r .= $DSP->div('itemWrapper');
		$r .= $DSP->required(1).BR.BR;
        
        if ($type == 'edit')        
            $r .= $DSP->input_submit($LANG->line('update'));
        else
            $r .= $DSP->input_submit($LANG->line('submit'));
              
        $r .= $DSP->div_c();
        
        $r .= $DSP->form_c();
                
        $DSP->title = $LANG->line('edit_member_field');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=profile_fields', $LANG->line('custom_member_fields')).$DSP->crumb_item($LANG->line('edit_member_field'));
        $DSP->body  = &$r;
    }
    // END  
    

    //-----------------------------------------------------------
    // Create/update custom fields
    //-----------------------------------------------------------
    // This function alters the "exp_member_data" table, adding
    // the new custom fields.
    //-----------------------------------------------------------

    function update_profile_fields()
    {  
        global $DSP, $IN, $DB, $REGX, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }        
        
        $LANG->fetch_language_file('publish_ad');
        
        // If the $field_id variable is present we are editing an
        // existing field, otherwise we are creating a new one
        
        $edit = (isset($_POST['m_field_id']) AND $_POST['m_field_id'] != '') ? TRUE : FALSE;
        
                
        // Check for required fields

        $error = array();
        
        if ($_POST['m_field_name'] == '')
        {
            $error[] = $LANG->line('no_field_name');
        }
        
        if ($_POST['m_field_label'] == '')
        {
            $error[] = $LANG->line('no_field_label');
        }
        
        // Does field name have invalid characters?
        
        if ( ! eregi("^[a-zA-z0-9\_\-]+$", $_POST['m_field_name'])) 
        {
            $error[] = $LANG->line('invalid_characters');
        }
                  
        // Is the field name taken?

        $sql = "SELECT count(*) as count FROM exp_member_fields WHERE m_field_name = '".$DB->escape_str($_POST['m_field_name'])."'";
        
        $query = $DB->query($sql);        
      
        if (($edit == FALSE || ($edit == TRUE && $_POST['m_field_name'] != $_POST['cur_field_name']))
            && $query->row['count'] > 0)
        {
            $error[] = $LANG->line('duplicate_field_name');
        }
        
        unset($_POST['cur_field_name']);        
        
        
        // Are there errors to display?
        
        if (count($error) > 0)
        {
            $str = '';
            
            foreach ($error as $msg)
            {
                $str .= $msg.BR;
            }
            
            return $DSP->error_message($str);
        }
        
        
        if ($_POST['m_field_list_items'] != '')
        {
            $_POST['m_field_list_items'] = $REGX->convert_quotes($_POST['m_field_list_items']);
        }
             
        // Construct the query based on whether we are updating or inserting
   
        if ($edit === TRUE)
        {
            $n = $_POST['m_field_maxl'];
        
            if ($_POST['m_field_type'] == 'text')
            {
                if ( ! is_numeric($n) || $n == '' || $n == 0)
                {
                    $n = '100';
                }
            
                $f_type = 'varchar('.$n.') NOT NULL';
            }
            else
            {
                $f_type = 'text NOT NULL';
            }
        
            $DB->query("ALTER table exp_member_data CHANGE m_field_id_".$_POST['m_field_id']." m_field_id_".$_POST['m_field_id']." $f_type");            
                    
            $id = $_POST['m_field_id'];
            unset($_POST['m_field_id']);
            
            $DB->query($DB->update_string('exp_member_fields', $_POST, 'm_field_id='.$id));  
        }
        else
        {
            if ($_POST['m_field_order'] == 0 || $_POST['m_field_order'] == '')
            {
                $query = $DB->query("SELECT count(*) AS count FROM exp_member_fields");
            
                $total = $query->row['count'] + 1;
            
                $_POST['m_field_order'] = $total; 
            }
            
            unset($_POST['$m_field_id']);
        
            $sql = $DB->insert_string('exp_member_fields', $_POST);
            
            $DB->query($sql);
            
            $sql = "ALTER table exp_member_data add column m_field_id_{$DB->insert_id} text NOT NULL";
                        
            $DB->query($sql);  
            
            $sql = "SELECT exp_members.member_id
                    FROM exp_members
                    LEFT JOIN exp_member_data ON exp_members.member_id = exp_member_data.member_id
                    WHERE exp_member_data.member_id IS NULL
                    ORDER BY exp_members.member_id";
            
            $query = $DB->query($sql);
            
            if ($query->num_rows > 0)
            {
				foreach ($query->result as $row)
				{
					$DB->query("INSERT INTO exp_member_data (member_id) values ('{$row['member_id']}')");
				}
			}
        }


        return $this->custom_profile_fields();
    }
    // END 
 


    //-----------------------------------------------------------
    // Delete field confirm
    //-----------------------------------------------------------
    // Warning message if you try to delete a custom profile field
    //-----------------------------------------------------------

    function delete_profile_field_conf()
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $m_field_id = $IN->GBL('m_field_id'))
        {
            return false;
        }
        
        $LANG->fetch_language_file('publish_ad');

        $query = $DB->query("SELECT m_field_label FROM exp_member_fields WHERE m_field_id = '$m_field_id'");
        
        $DSP->title = $LANG->line('delete_field');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=profile_fields', $LANG->line('custom_member_fields')).$DSP->crumb_item($LANG->line('edit_member_field'));
        
        $DSP->body = $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=delete_field'.AMP.'m_field_id='.$m_field_id)
                    .$DSP->input_hidden('m_field_id', $m_field_id)
                    .$DSP->heading($LANG->line('delete_field'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_field_confirmation').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['m_field_label'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END    
    
   
   
    //-----------------------------------------------------------
    // Delete member profile field
    //-----------------------------------------------------------

    function delete_profile_field()
    {  
        global $DSP, $IN, $DB, $LOG, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $m_field_id = $IN->GBL('m_field_id'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT m_field_label FROM exp_member_fields WHERE m_field_id = '$m_field_id'");
        
        $m_field_label = $query->row['m_field_label'];
                
        $DB->query("ALTER table exp_member_data DROP column m_field_id_".$m_field_id);
                
        $DB->query("DELETE FROM exp_member_fields WHERE m_field_id = '$m_field_id'");
        
        $LOG->log_action($LANG->line('profile_field_deleted').$DSP->nbs(2).$m_field_label);        

        return $this->custom_profile_fields();
    }
    // END    
 
  
    //-----------------------------------------------------------
    // Edit field order
    //-----------------------------------------------------------

    function edit_field_order_form()
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }

        $LANG->fetch_language_file('publish_ad');
        
        $query = $DB->query("SELECT m_field_label, m_field_name, m_field_order FROM exp_member_fields ORDER BY m_field_order");
        
        if ($query->num_rows == 0)
        {
            return false;
        }
                
        $r  = $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=update_field_order');
        
        $r .= $DSP->heading($LANG->line('edit_field_order'));
        
        $r .= $DSP->table('tableBorder', '0', '0', '30%').
              $DSP->tr().
              $DSP->td('tablePad'); 
        
        $r .= $DSP->table('', '0', '10', '100%');
                
        foreach ($query->result as $row)
        {
            $r .= $DSP->tr();
            $r .= $DSP->table_qcell('tableCellOne', $row['m_field_label']);
            $r .= $DSP->table_qcell('tableCellOne', $DSP->input_text($row['m_field_name'], $row['m_field_order'], '4', '3', 'input', '30px'));      
            $r .= $DSP->tr_c();
        }
        
        $r .= $DSP->tr();
        $r .= $DSP->td('', '', '2');
        $r .= $DSP->qdiv('itemWrapperTop', $DSP->input_submit($LANG->line('update')));
        $r .= $DSP->td_c();
        $r .= $DSP->tr_c();
        $r .= $DSP->table_c();
        $r .= $DSP->td_c();
        $r .= $DSP->tr_c();
        $r .= $DSP->table_c();
                
        $r .= $DSP->form_c();

        $DSP->title = $LANG->line('edit_field_order');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=profile_fields', $LANG->line('custom_member_fields')).$DSP->crumb_item($LANG->line('edit_field_order'));

        $DSP->body  = &$r;
    }
    // END    
 
 
 
 
    //-----------------------------------------------------------
    // Update field order
    //-----------------------------------------------------------
    // This function receives the field order submission
    //-----------------------------------------------------------

    function update_field_order()
    {  
        global $DSP, $IN, $DB, $LANG;

        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }
                
        foreach ($_POST as $key => $val)
        {
            $DB->query("UPDATE exp_member_fields SET m_field_order = '$val' WHERE m_field_name = '".$DB->escape_str($key)."'");    
        }
        
        return $this->custom_profile_fields();
    }
    // END
    

    // -----------------------------
    //  Member search form
    // -----------------------------   
    
    function member_search_form($message = '')
    {  
        global $LANG, $DSP, $DB;

        
        $DSP->body  = $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=do_member_search');

        $DSP->body .= $DSP->heading($LANG->line('member_search'));
        
        $DSP->body .= $DSP->qdiv('itemWrapper', $LANG->line('member_search_instructions'));
        
        $DSP->body .= $message;
        
        $DSP->body .= $DSP->div('box320');

        $DSP->body .= $DSP->itemgroup(
                                        $LANG->line('username', 'username'),
                                        $DSP->input_text('username', '', '35', '100', 'input', '100%')
                                     );
             
        $DSP->body .= $DSP->itemgroup(
                                        $LANG->line('email', 'email'),
                                        $DSP->input_text('email', '', '35', '100', 'input', '100%')
                                     );
                              
        $DSP->body .= $DSP->itemgroup(
                                        $LANG->line('screen_name', 'screen_name'),
                                        $DSP->input_text('screen_name', '', '35', '100', 'input', '100%')
                                     );
                              
        $DSP->body .= $DSP->itemgroup(
                                        $LANG->line('url', 'url'),
                                        $DSP->input_text('url', '', '35', '100', 'input', '100%')
                                     );
                              
        $DSP->body .= $DSP->itemgroup(
                                        $DSP->qdiv('defaultBold', $LANG->line('member_group'))
                                     );
                              
        // Member group select list

        $query = $DB->query("SELECT group_id, group_title FROM exp_member_groups ORDER BY group_title");
              
        $DSP->body .= $DSP->input_select_header('group_id');
        
        $DSP->body .= $DSP->input_select_option('any', $LANG->line('any'));
                                
        foreach ($query->result as $row)
        {                                
            $DSP->body.= $DSP->input_select_option($row['group_id'], $row['group_title']);
        }
        
        $DSP->body .= $DSP->input_select_footer();
        
        // END select list
        
        $DSP->body .= $DSP->div_c();
        
        $DSP->body .= $DSP->itemgroup(BR, $DSP->input_submit($LANG->line('submit')));
                
        $DSP->body .= $DSP->form_c();

        $DSP->title = $LANG->line('member_search');
        $DSP->crumb = $LANG->line('member_search');
    }
    // END



    // -----------------------------
    //  Member search
    // -----------------------------   
    
    function do_member_search()
    {  
        global $IN, $LANG, $DSP, $FNS, $LOC, $DB;
        
        $pageurl = BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=do_member_search';
        
		// -----------------------------
		//  Homepage source?
		// -----------------------------   
		
		// Since we allow a simplified member search field to be displayed
		// on the Control Panel homepage, we need to set the proper POST variable

        if (isset($_POST['criteria']))
        {
        	if ($_POST['keywords'] == '')
        	{
				$FNS->redirect(BASE);
				exit;    
        	}
        
			$_POST[$_POST['criteria']] = $_POST['keywords'];
			
			unset($_POST['keywords']);
			unset($_POST['criteria']);
        }
        // Done...
                
		// --------------------------------
		//  Parse the GET or POST request
		// --------------------------------
        
        if ($Q = $IN->GBL('Q', 'GET'))
        {
            $Q = stripslashes(urldecode($Q));
        }
        else  
        {
        	if (	$_POST['username'] 		== '' &&
        			$_POST['screen_name'] 	== '' &&
        			$_POST['email'] 		== '' &&
        			$_POST['url'] 			== ''
        		) 
        		{
					$FNS->redirect(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=member_search');
					exit;    
        		}
                  
            $search_query = array();
    
            foreach ($_POST as $key => $val)
            {
                if ($key == 'group_id')
                {
                    if ($val != 'any')
                    {
                        $search_query[] = " group_id ='".$_POST['group_id']."'";
                    }
                }
                else
                {
                    if ($val != '')
                    {
                        $search_query[] = $key." LIKE '%".$val."%'";
                    }
                }
            }
            
            if (count($search_query) < 1)
            {
                return $this->member_search_form();
            }
                        
            $Q = implode(" AND ", $search_query);            
        }      

        $pageurl .= AMP.'Q='.urlencode(stripslashes($Q));
                
        $sql = "SELECT DISTINCT 
                       exp_members.username,
                       exp_members.member_id,
                       exp_members.screen_name,
                       exp_members.email,
                       exp_members.join_date,
                       exp_members.last_visit,
                       exp_member_groups.group_title
                FROM   exp_members, exp_member_groups";
                
                
        $sql .= " WHERE exp_members.group_id = exp_member_groups.group_id AND ".$Q;                 
        
        $query = $DB->query($sql);
                
        // No result?  Show the "no results" message
        
        $total_count = $query->num_rows;
        
        if ($total_count == 0)
        {
            return $this->member_search_form($DSP->qdiv('itemWrapper', $DSP->qdiv('alert', $LANG->line('no_search_results'))));
        }
        
        // Get the current row number and add the LIMIT clause to the SQL query
        
        if ( ! $rownum = $IN->GBL('rownum', 'GP'))
        {        
            $rownum = 0;
        }
                        
        $sql .= " LIMIT ".$rownum.", ".$this->perpage;
        
        // Run the query              
    
        $query = $DB->query($sql);  
        
        // Build the table heading  
        
        $r  = $DSP->heading($LANG->line('member_search_results'));    
        
		// "select all" checkbox

        $r .= $DSP->toggle();
        
        // Declare the "delete" form
        
        $r .= $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=mbr_del_conf', 'target');
        
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
        
        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', $LANG->line('username')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('screen_name')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('email')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('join_date')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('last_visit')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('member_group')).
              $DSP->table_qcell('tableHeadingBold', $DSP->input_checkbox('toggleflag', '', '', "onclick=\"toggle(this);\"").NBS.$LANG->line('delete')).
              $DSP->tr_c();
        
               
        // Loop through the query result and write each table row 
               
        $i = 0;
        
        foreach($query->result as $row)
        {
            $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
                      
            $r .= $DSP->tr();
            
            // Username
            
            $r .= $DSP->table_qcell($style, 
                                    $DSP->anchor(
                                                  BASE.AMP.'C=myaccount'.AMP.'id='.$row['member_id'], 
                                                  '<b>'.$row['username'].'</b>'
                                                )
                                    );
            // Screen name
            
            $screen = ($row['screen_name'] == '') ? "--" : $row['screen_name'];
            
            $r .= $DSP->table_qcell($style, $screen);
             
            // Email
            
            $r .= $DSP->table_qcell($style, 
                                    $DSP->mailto($row['email'], $row['email'])
                                    );
            // Join date

            $r .= $DSP->td($style).
                  $LOC->convert_timestamp('%Y', $row['join_date']).'-'.
                  $LOC->convert_timestamp('%m', $row['join_date']).'-'.
                  $LOC->convert_timestamp('%d', $row['join_date']).
                  $DSP->td_c();
                  
            // Last visit date

            $r .= $DSP->td($style);
            
                if ($row['last_visit'] != 0)
                {            
                    $r .= $LOC->set_human_time($row['last_visit']);
                }
                else
                {
                    $r .= "--";               
                }
                                      
                  $DSP->td_c();
                  
            // Member group
            
            $r .= $DSP->td($style);
            
            $r .= $row['group_title'];
                
            $r .= $DSP->td_c();

            // Delete checkbox
            
            $r .= $DSP->table_qcell($style, $DSP->input_checkbox('toggle[]', $row['member_id']));
                  
            $r .= $DSP->tr_c();
            
        } // End foreach
        

        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
                        
        $r .= $DSP->table('', '0', '', '98%');
        $r .= $DSP->tr().
              $DSP->td();
               
        // Pass the relevant data to the paginate class so it can display the "next page" links
        
        $r .=  $DSP->div('crumblinks').
               $DSP->pager(
                            $pageurl,
                            $total_count,
                            $this->perpage,
                            $rownum,
                            'rownum'
                          ).
              $DSP->div_c().
              $DSP->td_c().
              $DSP->td('defaultRight');
        
        // Delete button
        
        $r .= $DSP->input_submit($LANG->line('delete')).
              $DSP->td_c().
              $DSP->tr_c();
              
        // Table end
        
        $r .= $DSP->table_c().
              $DSP->form_c();
        
        $DSP->title = $LANG->line('member_search');
        $DSP->crumb = $LANG->line('member_search');
        $DSP->body  = &$r;
    }
    // END
    
    
    
    // ---------------------------------
    //  Member Validation
    // ---------------------------------     
    
    function member_validation()
    {
    	global $DSP, $DB, $LANG, $LOC;
    	
    	        
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }
    	
        $title = $LANG->line('member_validation');
        
        $DSP->title = $title;
        $DSP->crumb = $title;
        
        $DSP->body = $DSP->heading($title);
        
		$query = $DB->query("SELECT member_id, username, screen_name, email, join_date FROM exp_members WHERE group_id = '4' ORDER BY join_date");

		if ($query->num_rows == 0)
		{
			$DSP->body .= $DSP->qdiv('highlight', $LANG->line('no_members_to_validate'));
		
			return;
		}
        
        
        $DSP->body .= 	$DSP->toggle();
        
        $DSP->body .= 	$DSP->form('C=admin'.AMP.'M=members'.AMP.'P=validate_members', 'target');
        
        $DSP->body .= 	$DSP->table('tableBorder', '0', '0', '100%').
						$DSP->tr().
						$DSP->td('tablePad'); 

        $DSP->body .=	$DSP->table('', '0', '0', '100%').
						$DSP->tr().
						$DSP->table_qcell('tableHeadingBold', 
										array(
												NBS,
												$DSP->input_checkbox('toggleflag', '', '', "onclick=\"toggle(this);\""),
												$LANG->line('username'),
												$LANG->line('screen_name'),
												$LANG->line('email'),
												$LANG->line('join_date')
											 )
										).
						$DSP->tr_c();

        
        $i = 0;
		$n = 1;
		      
        foreach ($query->result as $row)
        {
            $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
            
            $DSP->body .= $DSP->tr();

            $DSP->body .= $DSP->table_qcell($style, $DSP->qspan('', $n++), '1%');
            
            
            // Checkbox
            
            $DSP->body .= $DSP->table_qcell($style, $DSP->input_checkbox('toggle[]', $row['member_id']), '3%');
            
            // Username          
            
            $DSP->body .= $DSP->table_qcell($style, 
                                    $DSP->anchor(
                                                  BASE.AMP.'C=myaccount'.AMP.'id='.$row['member_id'], 
                                                  '<b>'.$row['username'].'</b>',
                                                  '24%'
                                                )
                                    );
            // Screen name
            
            $screen = ($row['screen_name'] == '') ? "--" : '<b>'.$row['screen_name'].'</b>';
            
            $DSP->body .= $DSP->table_qcell($style, $screen, '24%');
            
            // Email        
                                                 
            $DSP->body .= $DSP->table_qcell($style, $DSP->mailto($row['email']), '24%');
        
            // Join Date        
                                                 
            $DSP->body .= $DSP->table_qcell($style, $LOC->set_human_time($row['join_date']), '24%');
            
            $DSP->body .= $DSP->tr_c();      
        }

        $DSP->body .= $DSP->table_c();

        $DSP->body.= $DSP->td_c()   
					.$DSP->tr_c()      
					.$DSP->table_c();
             
        $DSP->body.= $DSP->qdiv('itemWrapper', BR.$DSP->input_checkbox('send_notification', 'y', 1).NBS.$LANG->line('send_email_notification').BR); 

		$DSP->body .= $DSP->div('itemWrapper');
        $DSP->body .= $DSP->input_select_header('action');
		$DSP->body .= $DSP->input_select_option('activate', $LANG->line('validate_selected'), 1);
		$DSP->body .= $DSP->input_select_option('delete', $LANG->line('delete_selected'), '');
        $DSP->body .= $DSP->input_select_footer();
        $DSP->body .= $DSP->div_c();
        
        $DSP->body .= $DSP->qdiv('itemWrapper', $DSP->input_submit($LANG->line('submit')));
        
        
		$DSP->body .= $DSP->form_c(); 
    }
    // END
        
        
    // ---------------------------------
    //  Validate/Delete Selected Members
    // ---------------------------------     
    
    function validate_members()
    {
		global $IN, $DSP, $DB, $LANG, $PREFS, $REGX, $FNS;
		    	        
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }
        
        if ( ! $DSP->allowed_group('can_delete_members'))
        {
        	if ($_POST['action'] == 'delete')
        	{
				return $DSP->no_access_message();
			}
        }

        if ( ! $IN->GBL('toggle', 'POST'))
        {
            return $this->member_validation();
        }

		$email = (isset($_POST['send_notification'])) ? TRUE : FALSE;
		
		if ($email == TRUE AND $_POST['action'] == 'activate')
		{
			$template = $FNS->fetch_email_template('validated_member_notify');
		
			require PATH_CORE.'core.email'.EXT;
			
            $email = new EEmail;
            $email->wordwrap = true;
		}
		
		
		$group_id = $PREFS->ini('default_member_group');
        
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'toggle') AND ! is_array($val))
            {
            	if ($_POST['action'] == 'activate')
            	{
					$DB->query("UPDATE exp_members SET group_id = '$group_id' WHERE member_id = '".$DB->escape_str($val)."'");
            	}
            	else
            	{
					$DB->query("DELETE FROM exp_members WHERE member_id = '$val'");
					$DB->query("DELETE FROM exp_member_data WHERE member_id = '$val'");
					$DB->query("DELETE FROM exp_member_homepage WHERE member_id = '$val'");          
            	}
                   
				if ($email == TRUE AND $_POST['action'] == 'activate')
				{
					$query = $DB->query("SELECT username, screen_name, email FROM exp_members WHERE member_id = '$val'");    
														
					$name = ($query->row['screen_name'] != '') ? $query->row['screen_name'] : $query->row['username'];

					$swap = array(
									'name'		=> $name,
									'site_name'	=> $PREFS->ini('site_name'),
									'site_url'	=> $PREFS->ini('site_url')
								 );
					
					
					$email_msg = $FNS->var_replace($swap, $template['data']);
					
									
					$email->initialize();
					$email->from($PREFS->ini('webmaster_email'));	
					$email->to($query->row['email']); 
					$email->subject($template['title']);	
					$email->message($REGX->entities_to_ascii($email_msg));		
					$email->Send();
				}
			}
        }

        $title = $LANG->line('member_validation');
        
        $DSP->title = $title;
        $DSP->crumb = $title;
        
        $DSP->body = $DSP->heading($title);
        
        $msg = ($_POST['action'] == 'activate') ? $LANG->line('members_are_validated') : $LANG->line('members_are_deleted');

		$DSP->body .= $DSP->qdiv('success', $msg);
	}
	// END
	
	
	
    // ---------------------------------
    //  View Email Console Logs
    // ---------------------------------     
	
	function email_console_logs($message = '')
	{
    	global $IN, $DB, $LANG, $DSP, $LOC;
    
		if ( ! $DSP->allowed_group('can_admin_members'))
		{     
			return $DSP->no_access_message();
		}
    
    
		// -----------------------------
    	//  Define base variables
		// -----------------------------   		
    	
		$i = 0;

		$s1 = 'tableCellOne';
		$s2 = 'tableCellTwo';
		
		$row_limit 	= 100;
		$paginate	= '';
		$row_count	= 0;
		
        $DSP->title = $LANG->line('email_console_log');
        $DSP->crumb = $LANG->line('email_console_log');
		
		$DSP->body  = $DSP->heading($LANG->line('email_console_log'));
		
        if ($message != '')
        {
			$DSP->body .= $DSP->qdiv('success', $message);
        }
		
		
		// -----------------------------
    	//  Run Query
		// -----------------------------   		
		
		$sql = "SELECT	cache_id, member_id, member_name, recipient_name, cache_date, subject
				FROM	exp_email_console_cache
				ORDER BY cache_id desc";
		
		$query = $DB->query($sql);
		
		if ($query->num_rows == 0)
		{
			if ($message == '')
				$DSP->body	.=	$DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('no_cached_email')));             
			
			return;
		}		
		
		// -----------------------------
    	//  Do we need pagination?
		// -----------------------------   		
		
		if ($query->num_rows > $row_limit)
		{ 
			$row_count = ( ! $IN->GBL('row')) ? 0 : $IN->GBL('row');
						
			$url = 'C=admin'.AMP.'M=members'.AMP.'P=email_console_logs';
						
			$paginate = $DSP->pager(  $url,
									  $query->num_rows, 
									  $row_limit,
									  $row_count,
									  'row'
									);
			 
			$sql .= " LIMIT ".$row_count.", ".$row_limit;
			
			$query = $DB->query($sql);    
		}
    			
		
		
        $DSP->body .= $DSP->toggle();
        $DSP->body .= $DSP->form(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=delete_email_console', 'target');

        $DSP->body .= $DSP->table('tableBorder', '0', '0', '100%').
					  $DSP->tr().
					  $DSP->td('tablePad'); 

        $DSP->body .= $DSP->table('', '0', '0', '100%').
					  $DSP->tr().
					  $DSP->table_qcell('tableHeadingBold',
										array(
												NBS,
												$LANG->line('email_title'), 
												$LANG->line('from'), 
												$LANG->line('to'), 
												$LANG->line('date'),
												$DSP->input_checkbox('toggleflag', '', '', "onclick=\"toggle(this);\"").NBS.$LANG->line('delete').NBS.NBS
											  )
											).
              $DSP->tr_c();
              
		// -----------------------------
    	//  Table Rows
		// ----------------------------- 
		
		$row_count++;  		
              
		foreach ($query->result as $row)
		{			
			$DSP->body	.=	$DSP->table_qrow( ($i++ % 2) ? $s1 : $s2, 
									array(
											$row_count,
													
                  							$DSP->anchorpop(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=view_email'.AMP.'id='.$row['cache_id'].AMP.'Z=1', '<b>'.$row['subject'].'</b>', '600', '580'),
											
											$DSP->qspan('defaultBold', $row['member_name']),
											
											$DSP->qspan('defaultBold', $row['recipient_name']),
											
											$LOC->set_human_time($row['cache_date']),
																																																							
											$DSP->input_checkbox('toggle[]', $row['cache_id'])

										  )
									);
			$row_count++;  		
		}	
        
        $DSP->body .= $DSP->table_c(); 

        $DSP->body .= $DSP->td_c().  
					  $DSP->tr_c().
					  $DSP->table_c();  
					  

    	if ($paginate != '')
    	{
    		$DSP->body .= $DSP->qdiv('itemWrapper', $DSP->qdiv('defaultBold', $paginate));
    	}
    
		$DSP->body .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')));             
        
        $DSP->body .= $DSP->form_c();
	}
	// END
	
	

	// -----------------------------
	//  View Email
	// -----------------------------   		

	function view_email()
	{
    	global $IN, $DB, $LANG, $DSP, $LOC;
    
		if ( ! $DSP->allowed_group('can_admin_members'))
		{     
			return $DSP->no_access_message();
		}
		
		$id = $IN->GBL('id');
		
		// -----------------------------
    	//  Run Query
		// ----------------------------- 
				
		$query = $DB->query("SELECT subject, message, recipient, recipient_name, member_name, ip_address FROM exp_email_console_cache WHERE cache_id = '$id' ");
		
		if ($query->num_rows == 0)
		{
			$DSP->body .= $DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('no_cached_email')));             
			
			return;
		}
	
		// -----------------------------
    	//  Render output
		// -----------------------------   		
				
		$DSP->body .= $DSP->heading(BR.$query->row['subject']);
				
		// ----------------------------------------
		//  Instantiate Typography class
		// ----------------------------------------        
	  
		if ( ! class_exists('Typography'))
		{
			require PATH_CORE.'core.typography'.EXT;
		}
            
		$TYPE = new Typography;
		
		$DSP->body .= $TYPE->parse_type( $query->row['message'], 
								 array(
											'text_format'   => 'xhtml',
											'html_format'   => 'all',
											'auto_links'    => 'y',
											'allow_img_url' => 'y'
									   )
								);
										
		$DSP->body	.= $DSP->qdiv('', BR); 	
        $DSP->body	.= $DSP->table('tableBorderNoBot', '0', '10', '100%');
		$DSP->body	.= $DSP->tr();
		$DSP->body 	.= $DSP->table_qcell('tableCellTwo', $DSP->qspan('defaultBold', $LANG->line('from')));
		$DSP->body 	.= $DSP->table_qcell('tableCellOne', $DSP->qspan('defaultBold', $query->row['member_name']));
		$DSP->body 	.= $DSP->table_qcell('tableCellOne', $DSP->qspan('defaultBold', $query->row['ip_address']));
		$DSP->body 	.= $DSP->tr_c();
		$DSP->body 	.= $DSP->tr();
		$DSP->body 	.= $DSP->table_qcell('tableCellTwo', $DSP->qspan('defaultBold', $LANG->line('to')));
		$DSP->body 	.= $DSP->table_qcell('tableCellOne', $DSP->qspan('defaultBold', $query->row['recipient_name']));
		$DSP->body 	.= $DSP->table_qcell('tableCellOne', $DSP->qspan('defaultBold', $DSP->mailto($query->row['recipient'])));
		$DSP->body 	.= $DSP->tr_c();		
		$DSP->body 	.= $DSP->table_c(); 
	}
	// END
	 
     
    // -------------------------------------------
    //   Delete Emails
    // -------------------------------------------    

    function delete_email_console_messages()
    { 
        global $IN, $DSP, $LANG, $DB;
        
		if ( ! $DSP->allowed_group('can_admin_members'))
		{     
			return $DSP->no_access_message();
		}
        
        if ( ! $IN->GBL('toggle', 'POST'))
        {
            return $this->email_console_logs();
        }

        $ids = array();
                
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'toggle') AND ! is_array($val))
            {
                $ids[] = "cache_id = '".$val."'";
            }        
        }
        
        $IDS = implode(" OR ", $ids);
        
        $DB->query("DELETE FROM exp_email_console_cache WHERE ".$IDS);
    
        return $this->email_console_logs($LANG->line('email_deleted'));
    }
    // END 
    
    
    
    
    // -----------------------------
    //  Member Profile Templates
    // -----------------------------   

	// Template Overview

    function profile_templates()
    {
        global $DSP, $IN, $PREFS, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_mbr_templates'))
        {
            return $DSP->no_access_message();
        }
        
        $r  = $DSP->table('tableBorder', '0', '0', '50%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold', '', '2').
              $LANG->line('profile_templates').
              $DSP->td_c().
              $DSP->tr_c();              
	  
		$path = ($PREFS->ini('member_theme') == '') ? 'default' : $PREFS->ini('member_theme');
		
		$path = PATH_MOD.$this->member_theme_dir;
		
		$themes = array();
		
        if ($fp = @opendir($path))
        { 
            while (false !== ($file = readdir($fp)))
            {
                if (is_dir($path.$file) && $file !== '.' && $file !== '..') 
                {                    
					$themes[] = $file;
                }
            }         
			
			closedir($fp); 
        } 		        
  
        if (count($themes) == 0)
        {
            $r .= $DSP->tr().
                  $DSP->td('tableCellTwo', '', '2').
                  '<b>'.$LANG->line('unable_to_find_templates').'</b>'.
                  $DSP->td_c().
                  $DSP->tr_c();
        }  

        $i = 0;
        
        if (count($themes) > 0)
        {
            foreach ($themes as $set)
            {
                $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
                
				$template_name = ucfirst(str_replace("_", " ", $set));

                $r .= $DSP->tr();
                $r .= $DSP->table_qcell($style, $i.$DSP->nbs(2).$DSP->qspan('defaultBold', $template_name), '60%');
                $r .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=list_templates'.AMP.'name='.$set, $LANG->line('edit')), '40%');      
                $r .= $DSP->tr_c();
            }
        }
        
        $r .= $DSP->table_c();
        
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
                
        $DSP->title  = $LANG->line('profile_templates');
        $DSP->crumb  = $LANG->line('profile_templates');
        $DSP->body   = &$r;  
    }
	// END    
    
    
    // -----------------------------
    //  List Templates within a set
    // -----------------------------   
    
    function list_templates()
    {
    	global $IN, $PREFS, $LANG, $DSP;
    	
        if ( ! $DSP->allowed_group('can_admin_mbr_templates'))
        {
            return $DSP->no_access_message();
        }
            
		$path = ($PREFS->ini('member_theme') == '') ? 'default' : $PREFS->ini('member_theme');
		
		$path = PATH_MOD.$this->member_theme_dir.$IN->GBL('name').'/member_skin'.EXT;
		
		if ( ! file_exists($path))
		{
            return $DSP->no_access_message($LANG->line('unable_to_find_templates'));
		}
    
		if ( ! class_exists('Member_skin'))
		{
            require $path;
		}
		
		$template_name = ucfirst(str_replace("_", " ", $IN->GBL('name')));
		
		$class_methods = get_class_methods('Member_skin');
		
		$methods = array();
		
    	foreach ($class_methods as $val)
		{
			$methods[$LANG->line($val)] = $val;  
    	}
    	
    	ksort($methods);

    
        $r  = $DSP->table('tableBorder', '0', '0', '').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold').
              $LANG->line('template_set').NBS.NBS.$template_name.
              $DSP->td_c().
              $DSP->tr_c();              
	          
	    $i = 0;
	    
    	foreach ($methods as $key => $val)
		{
			$style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;

			$r .= $DSP->tr();
			$r .= $DSP->table_qcell($style, $DSP->qspan('default', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=edit_template'.AMP.'group='.$IN->GBL('name').AMP.'name='.$val, $key)));      
			$r .= $DSP->tr_c();
		}
        
        $r .= $DSP->table_c();
        
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
                
        $DSP->title  = $template_name;
        $DSP->crumb  = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=profile_templates', $LANG->line('profile_templates')).$DSP->crumb_item($template_name);
        $DSP->body   = &$r;  
    }
    // END
    
    
    
    
    // -----------------------------
    //  Edit Profile Template
    // -----------------------------   
    
    function edit_template($group = '', $function = '', $template_data = '')
    {
    	global $IN, $DSP, $LANG, $SESS, $PREFS;
    	
        if ( ! $DSP->allowed_group('can_admin_mbr_templates'))
        {
            return $DSP->no_access_message();
        }
        
        $update = ($function != '' AND $group != '') ? TRUE : FALSE;
    	
    	if ($function == '')
    	{
			$function = $IN->GBL('name');
    	}
    	
    	if ($group == '')
    	{
			$group = $IN->GBL('group');
    	}
    			
		$path = PATH_MOD.$this->member_theme_dir.$group.'/member_skin'.EXT;
		
		if ( ! file_exists($path))
		{
            return $DSP->no_access_message($LANG->line('unable_to_find_template_file'));
		}
    
		if ( ! class_exists('Member_skin'))
		{
            require $path;
		}
		
		$MS = new Member_skin;
						
    	$r = $DSP->heading($LANG->line($function));
    	
    	if ($update)
    	{
    		$r .= $DSP->qdiv('success', $LANG->line('template_updated'));
    	}
    	
    	if ( ! is_writable($path))
    	{
    		$r .= $DSP->div('box');
    		
    		$r .= $DSP->qdiv('itemWrapper', $DSP->qspan('alert', $LANG->line('file_not_writable')));
    		$r .= $DSP->qdiv('itemWrapper', $LANG->line('file_writing_instructions'));
    		
    		$sys_folder = $PREFS->ini('system_folder');
    		$path = substr($path, strpos($path, $sys_folder)-1);
    		
    		$r .= $DSP->qdiv('itemWrapper', $DSP->qspan('defaultBold', $path));
    		$r .= $DSP->div_c();
    	}
    
        $r .= $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=save_template')
             .$DSP->input_hidden('group', $group)
             .$DSP->input_hidden('function', $function);
             
		if ($update == FALSE)
		{		
			$template_data = $MS->$function();
		}
      
        $r .= $DSP->div('itemWrapper')  
             .$DSP->input_textarea('template_data', stripslashes($template_data), $SESS->userdata['template_size'], 'textarea', '100%')
             .$DSP->div_c();
             
        $r .= $DSP->input_submit($LANG->line('update'))
             .$DSP->form_c();
             
		$group_name = ucfirst(str_replace("_", " ", $group));
        
        $DSP->title  = $LANG->line($function);
        $DSP->crumb  = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=profile_templates', $LANG->line('profile_templates')).
        $DSP->crumb  = $DSP->crumb_item($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=members'.AMP.'P=list_templates'.AMP.'name='.$group, $group_name)).
        $DSP->crumb_item($LANG->line($function));
        $DSP->body   = &$r;  
    }
    // END
    
    
    
    // -----------------------------
    //  Save Template
    // -----------------------------   
    
    function save_template()
    {  
    	global $IN, $DSP, $LANG, $SESS, $FNS, $PREFS;
    	
        if ( ! $DSP->allowed_group('can_admin_mbr_templates'))
        {
            return $DSP->no_access_message();
        }
    
    	$function		= $IN->GBL('function',  'POST');
    	$group			= $IN->GBL('group', 'POST');
    	$template_data	= $IN->GBL('template_data', 'POST');
		$path = PATH_MOD.$this->member_theme_dir.$group.'/member_skin'.EXT;
		
		if ( ! file_exists($path))
		{
            return $DSP->no_access_message($LANG->line('unable_to_find_templates'));
		}
    
		if ( ! class_exists('Member_skin'))
		{
            require $path;
		}
		
		$MS = new Member_skin;
		
		$class_methods = get_class_methods('Member_skin');
		
		$methods = array();
		
    	foreach ($class_methods as $val)
		{
			if ($val == $function)
			{
				$methods[$val] = stripslashes($template_data);  
			}
			else
			{
				$methods[$val] = stripslashes($MS->$val());  
			}
    	}
		
		$str  = "<?php\n\n";
		$str .= '/*'."\n";
		$str .= '====================================================='."\n";
		$str .= ' ExpressionEngine - by pMachine'."\n";
		$str .= '-----------------------------------------------------'."\n";
		$str .= ' Copyright (c) 2003 - 2004 pMachine, Inc.'."\n";
		$str .= '====================================================='."\n";
		$str .= ' THIS IS COPYRIGHTED SOFTWARE'."\n";
		$str .= ' PLEASE READ THE LICENSE AGREEMENT'."\n";
		$str .= '====================================================='."\n";
		$str .= ' File: member_skin.php'."\n";
		$str .= '-----------------------------------------------------'."\n";
		$str .= ' Purpose: Member Profile Skin Elements'."\n";
		$str .= '====================================================='."\n";
		$str .= '*/'."\n\n";
		$str .= "if ( ! defined('EXT')){\n\texit('Invalid file request');\n}\n\n";
		$str .= "class Member_skin {\n\n"; 
		 
		foreach ($methods as $key => $val)
		{
			$str .= '//-------------------------------------'."\n";
			$str .= '//  '.$LANG->line($key)."\n";
			$str .= '//-------------------------------------'."\n\n";

			$str .= 'function '.$key.'()'."\n{\nreturn <<< EOF\n";
			$str .= $val;
			$str .= "\nEOF;\n}\n// END\n\n\n\n\n";
		} 
			 
		$str .= "}\n";
		$str .= '// END CLASS'."\n";
		$str .= '?'.'>';
           
               
		if ( ! $fp = @fopen($path, 'wb'))
		{
            return $DSP->no_access_message($LANG->line('error_opening_template'));
		}
			flock($fp, LOCK_EX);
			fwrite($fp, $str);
			flock($fp, LOCK_UN);
			fclose($fp);
		
		
        // Clear cache files
        
        $FNS->clear_caching('all');
		
		$this->edit_template($group, $function, $template_data);		
	}
	// END
	
	    
       
}// END                              
?>