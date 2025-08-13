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
 File: cp.pm_import.php
-----------------------------------------------------
 Purpose: pMachine Import Utility
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class PM_Import {

    var $PM         = FALSE;
    var $m_batch    = 1000;
    var $e_batch	= 2000;
    var $b_batch    = 100;


    // -------------------------------------------
    //  Constructor
    // -------------------------------------------    
    
    function PM_Import() 
    {
        global $IN, $DSP, $LANG, $SESS, $PREFS;
        
        // You have to be a Super Admin to access this page
        
        if ($SESS->userdata['group_id'] != 1)
        {
            return $DSP->no_access_message();
        }
        
        // Connect to database if the "pm_hostname" config index exists in the config file
        
        if ($PREFS->ini('pm_hostname'))
        {
            $this->pm_db_connect();
        }
        
        // Fetch the language file
                
        $LANG->fetch_language_file('pm_import');
        
    
        switch($IN->GBL('F'))
        {
            case 'configure'                : $this->save_configuration();
                break;
            case 'member_config_form'       : $this->member_config_form();
                break;
            case 'configure_members'        : $this->configure_members();
                break;
            case 'import_members'           : $this->import_members();
                break;
            case 'mailinglist_config_form'  : $this->mailinglist_config_form();
                break;
            case 'configure_mailinglist'    : $this->configure_mailinglist();
                break;
            case 'import_mailinglist'       : $this->import_mailinglist();
                break;
            case 'weblog_config_form'       : $this->weblog_config_form();
                break;
            case 'configure_weblog'         : $this->configure_weblog_prefs();
                break;
            case 'configure_weblog_fields'  : $this->configure_weblog_fields();
                break;
            case 'reset_member_stats'       : $this->reset_member_stats();
                break;
            case 'reset_weblog_stats'       : $this->reset_weblog_stats();
                break;
            case 'import_weblog'            : $this->import_weblog();
                break;
            case 'clear_config_prefs'       : $this->clear_config_prefs();
                break;
            default                         : $this->pm_import_main_page();
                break;
        }
    }
    // END
    
    
    
    // -------------------------------------------
    //  pMachine Import Main Page
    // -------------------------------------------    
    
    function pm_import_main_page($msg = '') 
    {
        global $IN, $DSP, $LANG, $PREFS;
         
        $DSP->title = $LANG->line('pmachine_import_utitity');
        
        if ($DSP->crumb == '')
        {
            $DSP->crumb = $LANG->line('pmachine_import_utitity');
        }
        
        $r  = $DSP->heading($LANG->line('pmachine_import_utitity'));
    
        if ( ! $PREFS->ini('pm_hostname'))
        {
            $r .= $DSP->qdiv('itemWrapper', $DSP->heading($LANG->line('pmachine_import_welcome'), 5));
            
            $r .= $DSP->qdiv('itemWrapper', $DSP->qspan('highlight', $LANG->line('pmachine_import_disclaimer').BR.BR));
        }
            
        if ( ! $PREFS->ini('pm_hostname'))
        {
            $r .= $DSP->qdiv('box450', $this->database_settings_form($msg));
        }
        else
        {            
            $r .= $this->import_grid();
        }    
    
        $DSP->body = &$r;
    } 
    // END
    
    
    
    // -------------------------------------------
    //  Connect to pMachine DB
    // -------------------------------------------    
    
    function pm_db_connect()
    {
        global $PREFS;

        $db_config = array(
                            'hostname'  => $PREFS->ini('pm_hostname'),
                            'username'  => $PREFS->ini('pm_username'),
                            'password'  => $PREFS->ini('pm_password'),
                            'database'  => $PREFS->ini('pm_database'),
                            'prefix'    => $PREFS->ini('pm_prefix'),
                          );
    
        $this->PM = new DB($db_config);
        
        $this->PM->exp_prefix   = 'pm_';
        $this->PM->debug        = FALSE;
        $this->PM->enable_cache = FALSE;

        if ( ! $this->PM->db_connect())
        {
            return false;
        }

        return true;
    }
    // END    
    
    
    
    // -------------------------------------------
    //  Database settings form
    // -------------------------------------------    
    
    function database_settings_form($message = '')
    {
        global $DSP, $IN, $LANG;
        
            $r  = $DSP->heading($LANG->line('database_info'));
            $r .= $DSP->qdiv('itemWrapper', $LANG->line('configuration_blurb'));
            $r .= $DSP->qdiv('itemWrapper', $DSP->qdiv('defaultBold', $LANG->line('configuration_blurb_cont')));
    
            $pm_hostname    = ( ! $IN->GBL('pm_hostname', 'POST'))  ? '' : $_POST['pm_hostname'];
            $pm_username    = ( ! $IN->GBL('pm_username', 'POST'))  ? '' : $_POST['pm_username'];
            $pm_password    = ( ! $IN->GBL('pm_password', 'POST'))  ? '' : $_POST['pm_password'];
            $pm_database    = ( ! $IN->GBL('pm_database', 'POST'))  ? '' : $_POST['pm_database'];
            $pm_prefix      = ( ! $IN->GBL('pm_prefix', 'POST'))    ? '' : $_POST['pm_prefix'];

            $r .= $message;

            $r .= $DSP->form('C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=configure');
            
            $r .= $DSP->div('itemWrapper').BR.
                  $DSP->qdiv('itemTitle', $LANG->line('sql_server')).
                  $DSP->input_text('pm_hostname', $pm_hostname, '40', '70', 'input', '300px').
                  $DSP->div_c();
        
            $r .= $DSP->div('itemWrapper').
                  $DSP->qdiv('itemTitle', $LANG->line('sql_username')).
                  $DSP->input_text('pm_username', $pm_username, '40', '50', 'input', '300px').
                  $DSP->div_c();
        
            $r .= $DSP->div('itemWrapper').
                  $DSP->qdiv('itemTitle', $LANG->line('sql_password')).
                  $DSP->input_text('pm_password', $pm_password, '40', '50', 'input', '300px').
                  $DSP->div_c();
        
            $r .= $DSP->div('itemWrapper').
                  $DSP->qdiv('itemTitle', $LANG->line('sql_database')).
                  $DSP->input_text('pm_database', $pm_database, '40', '50', 'input', '300px').
                  $DSP->div_c();
                  
            $r .= $DSP->div('itemWrapper').
                  $DSP->qdiv('itemTitle', $LANG->line('sql_prefix')).
                  $DSP->qdiv('', $LANG->line('leave_prefix_blank')).
                  $DSP->input_text('pm_prefix', $pm_prefix, '40', '50', 'input', '300px').
                  $DSP->div_c();
                  
            $r .= $DSP->qdiv('itemWrapper', $DSP->input_submit($LANG->line('configure'), 'submit'));
        
            $r .= $DSP->form_c();

        return $r;
    }    
    // END    
    
        
    // -------------------------------------------
    //  Test and save DB configuration settings
    // -------------------------------------------    
    
    function save_configuration()
    {
        global $DSP, $IN, $FNS, $LANG;
        
        // Check for required fields
        
        $required = array('pm_hostname', 'pm_username', 'pm_database');    
    
        foreach ($required as $val)
        {
            if ($_POST[$val] == '')
            {
                return $this->pm_import_main_page($DSP->qdiv('alert', BR.$LANG->line('empty_field_warning')));
            }
        }
        
        // Test the database connection
        
        $db_config = array(
                            'hostname'  => $_POST['pm_hostname'],
                            'username'  => $_POST['pm_username'],
                            'password'  => $_POST['pm_password'],
                            'database'  => $_POST['pm_database'],
                            'prefix'    => ($_POST['pm_prefix'] == '') ? 'pm' : $_POST['pm_prefix']
                          );
    
        $PDB = new DB($db_config);
        
        $PDB->debug = FALSE;

        if ( ! $PDB->db_connect())
        {
            return $this->pm_import_main_page($DSP->qdiv('alert', BR.$LANG->line('no_database_connection')));
        }
        
        // Write the new data to the config file
        
        $pm_config = array(
                            'pm_hostname'           => $_POST['pm_hostname'],
                            'pm_username'           => $_POST['pm_username'],
                            'pm_password'           => $_POST['pm_password'],
                            'pm_database'           => $_POST['pm_database'],
                            'pm_prefix'             => ($_POST['pm_prefix'] == '') ? 'pm' : $_POST['pm_prefix'],
                            'pm_completed_tables'   => ''
                          );
                          
        Admin::append_config_file($pm_config);
        
        
        // -----------------------------------------
        //  Redirect to main import page
        // -----------------------------------------

        $FNS->redirect(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import');
        exit;     
    }
    // END    
    
    
    
    // -----------------------------------------
    //  Import Grid
    // -----------------------------------------

    function import_grid()
    {
        global $DSP, $LANG, $DB, $PREFS;
        
        $pm_db      = $PREFS->ini('pm_database');                   
        $exp_db     = $PREFS->ini('db_name');                   
        $tables     = $PREFS->ini("pm_completed_tables");
        
        if (ereg("^\|", $tables))
        {
            $tables = substr($tables, 1);
        }
        
        $ex = explode("|", $tables);
        
        $completed_tables = array();
        
        for ($i = 0; $i < count($ex); $i++)
        {
            $completed_tables[$ex[$i]] = TRUE;   
        }
        
        $r = BR;
        
        // -----------------------------------------
        //  Step One - Member table
        // -----------------------------------------
        
        $r .= $DSP->heading($LANG->line('step_one'));
        $r .= $DSP->heading($LANG->line('import_members'), 2);
         
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', 
                                array(
                                        $LANG->line('table_name'),
                                        $LANG->line('table_rows'),
                                        $LANG->line('table_status'),
                                        $LANG->line('configure'),
                                        $LANG->line('table_action')
                                     )
                                ).
              $DSP->tr_c();
        
        
        $query = $this->PM->query("SELECT COUNT(*) AS count FROM {$pm_db}.pm_members");
    
        $i = 0;
      
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
        
        $r .= $DSP->tr();
        
        // Table name
        $r .= $DSP->table_qcell($style, $DSP->qdiv('defaultBold', $LANG->line('members')), '20%');

        // Table rows
        $r .= $DSP->table_qcell($style, $query->row['count'], '20%');
        
        // Table Status
        $status = (isset($completed_tables['pm_members'])) ? $DSP->qspan('success', $LANG->line('completed')) : $DSP->qdiv('highlight', $LANG->line('pending')) ;
        $r .= $DSP->table_qcell($style, $status, '20%');  
                
        // Table configure
        $configure = (isset($completed_tables['pm_members'])) ? '--' : $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=member_config_form', $LANG->line('configure'));
        $r .= $DSP->table_qcell($style, $configure, '20%');  
        
        // Table action
        $action = (isset($completed_tables['pm_members'])) ? '--' : $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_members', '<b>'.$LANG->line('import_now').'</b>');
        $r .= $DSP->table_qcell($style, $action, '20%');  
        
        $r .= $DSP->tr_c();      
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();    
             
             
        // -----------------------------------------
        //  Step Two - Weblog tables
        // -----------------------------------------
         
        $r .= BR;
        
        $r .= $DSP->heading($LANG->line('step_two'));
        $r .= $DSP->heading($LANG->line('import_weblog_entries'), 2);
        
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', 
                                array(
                                        $LANG->line('table_name'),
                                        $LANG->line('table_rows'),
                                        $LANG->line('table_status'),
                                        $LANG->line('configure'),
                                        $LANG->line('table_action')
                                     )
                                ).
              $DSP->tr_c();
        
        
        $query = $this->PM->query("SELECT weblog FROM {$pm_db}.pm_multiweblogs ORDER BY weblog");
    
        $i = 0;
        
        if ($query->num_rows > 0)
        {
              
            foreach ($query->result as $row)
            {
                $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
                
                $r .= $DSP->tr();
                
                // Table name
                $r .= $DSP->table_qcell($style, $DSP->qdiv('defaultBold', $row['weblog']), '20%');
    
                // Table rows
                $result = $this->PM->query("SELECT COUNT(*) AS count FROM {$pm_db}.pm_weblog WHERE weblog = '".$row['weblog']."'");
            
                $r .= $DSP->table_qcell($style, $result->row['count'], '20%');
                
                // Table Status
                $status = (isset($completed_tables['pm_weblog_'.$row['weblog']])) ? $DSP->qspan('success', $LANG->line('completed')) : $DSP->qdiv('highlight', $LANG->line('pending')) ;
                
                if ($result->row['count'] == 0)
                {
                    $status = $DSP->qdiv('', $LANG->line('no_table_rows'));
                }
                
                $r .= $DSP->table_qcell($style, $status, '20%');  
                                    
                // Table configure
                $configure = (isset($completed_tables['pm_weblog_'.$row['weblog']])) ? '--' : $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=weblog_config_form'.AMP.'id='.$row['weblog'], $LANG->line('configure'));
                
                if ($result->row['count'] == 0)
                {
                    $configure = '--';
                }
                            
                $r .= $DSP->table_qcell($style, $configure, '20%');  
                
                // Table action
                $action = (isset($completed_tables['pm_weblog_'.$row['weblog']])) ? '--' : $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_weblog'.AMP.'id='.$row['weblog'], '<b>'.$LANG->line('import_now').'</b>');
                
                if ($result->row['count'] == 0)
                {
                    $action = '--';
                }
                            
                $r .= $DSP->table_qcell($style, $action, '20%');  
    
                $r .= $DSP->tr_c();      
            }
        }
        
        $r .= $DSP->table_c();     

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();     


        $r .= BR;
        
        
        // -----------------------------------------
        //  Step Three - Mailing List table
        // -----------------------------------------
        
        $r .= $DSP->heading($LANG->line('step_three'));
        $r .= $DSP->heading($LANG->line('import_mailinglist'), 2);
         
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', 
                                array(
                                        $LANG->line('table_name'),
                                        $LANG->line('table_rows'),
                                        $LANG->line('table_status'),
                                        $LANG->line('configure'),
                                        $LANG->line('table_action')
                                     )
                                ).
              $DSP->tr_c();
        
        
        $query = $this->PM->query("SELECT COUNT(*) AS count FROM {$pm_db}.pm_mailinglist");
    
        $i = 0;
      
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
        
        $r .= $DSP->tr();
        
        // Table name
        $r .= $DSP->table_qcell($style, $DSP->qdiv('defaultBold', $LANG->line('mailinglist')), '20%');

        // Table rows
        $r .= $DSP->table_qcell($style, $query->row['count'], '20%');
        
        // Table Status
        $status = (isset($completed_tables['pm_mailinglist'])) ? $DSP->qspan('success', $LANG->line('completed')) : $DSP->qdiv('highlight', $LANG->line('pending')) ;
        
        if ($query->row['count'] == 0)
        {
        	$status = $DSP->qdiv('highlight', $LANG->line('no_rows_exist'));
        }
        $r .= $DSP->table_qcell($style, $status, '20%');  
                
        // Table configure
        $configure = (isset($completed_tables['pm_mailinglist']) || $query->row['count'] == 0) ? '--' : $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=mailinglist_config_form', $LANG->line('configure'));
        $r .= $DSP->table_qcell($style, $configure, '20%');  
        
        // Table action
        $action = (isset($completed_tables['pm_mailinglist']) || $query->row['count'] == 0) ? '--' : $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_mailinglist', '<b>'.$LANG->line('import_now').'</b>');
        $r .= $DSP->table_qcell($style, $action, '20%');  
        
        $r .= $DSP->tr_c();      
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();    
             


        // -----------------------------------------
        //  Step Four - Recalculate stats
        // -----------------------------------------

        $r .= BR;
             
        $r .= $DSP->heading($LANG->line('step_four'));
        $r .= $DSP->heading($LANG->line('recalculate_statistics'), 2);
        $r .= $DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('reset_statistics_info')));
                      
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBoldNoBot', 
                                array(
                                		$DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=recount_stats', $LANG->line('click_to_reset_statistics'))
                                	)
                                ).
              $DSP->tr_c();        
        
        $r .= $DSP->tr_c();         
             
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();  
             
             
             
             
        // -----------------------------------------
        //  Step Five - Clear config data
        // -----------------------------------------

        $r .= BR;
             
        $r .= $DSP->heading($LANG->line('step_five'));
        $r .= $DSP->heading($LANG->line('clear_preferences'), 2);
        $r .= $DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('clear_preferences_info')));
                      
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBoldNoBot', 
                                array(
                                		$DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=clear_config_prefs', $LANG->line('click_to_clear_prefs'))
                                	)
                                ).
              $DSP->tr_c();        
        
        $r .= $DSP->tr_c();         
             
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        return $r;
    }
    // END
    
    
    
    
    
    
    
    
    
    // -------------------------------------------
    //  Mailinglist Configuration page
    // -------------------------------------------    

    function mailinglist_config_form()
    {
        global $DSP, $LANG, $PREFS, $DB;
                
         
        $DSP->title = $LANG->line('pmachine_import_utitity');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('pmachine_import_utitity')).$DSP->crumb_item($LANG->line('import_members'));
        
        $r = $DSP->heading($LANG->line('import_mailinglist'));
        
        $pm_db = $PREFS->ini('pm_database');                   
        
        $query = $this->PM->query("SELECT count(*) AS count FROM {$pm_db}.pm_mailinglist");
        
        $total = $query->row['count'];
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_emails').NBS.$total);

        // Form declaration
        
        $r .= $DSP->form('C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=configure_mailinglist');
        
        // Batch total form
	
		$r .= $DSP->heading($LANG->line('emails_per_cycle'), 2);
		
		$r .= $DSP->qdiv('box450', $LANG->line('email_instructions'));
		
		$r .= $DSP->qdiv('itemWraper', $DSP->input_text('pm_email_batch', '5000', '20', '5', 'input', '50px'));
        
        $r .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('save_settings'), 'submit'));
        
        $r .= $DSP->form_c();
    
        $DSP->body =& $r;
    }
    // END
    
    
    
    
    
    // -------------------------------------------
    //  Configure Mailinglist Import prefs
    // -------------------------------------------    
    
    function configure_mailinglist()
    {
        global $IN, $DSP, $DB, $SESS, $PREFS, $FNS, $LANG;
                
         
        $DSP->title = $LANG->line('pmachine_import_utitity');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('pmachine_import_utitity')).$DSP->crumb_item($LANG->line('import_members'));
        
        $r = $DSP->heading($LANG->line('import_mailinglist'));
        
        $exp_db = $PREFS->ini('db_name');                   
                
        $pm_db = $PREFS->ini('pm_database');                   
        
        $query = $this->PM->query("SELECT count(*) AS count FROM {$pm_db}.pm_mailinglist");

        $total = $query->row['count'];
        
        $batch = ( ! $IN->GBL('pm_email_batch', 'POST')) ? $this->e_batch : $IN->GBL('pm_email_batch', 'POST');
        
        $data = array(
                        'pm_mailinglislt_batch'      => $batch,
                        'pm_mailinglist_total_done'  => ( ! $PREFS->ini('pm_mailinglist_total_done')) ? 0 : $PREFS->ini('pm_mailinglist_total_done')
                     );
        
        
        if ($PREFS->ini('pm_mailinglist_total_done'))
        {
            Admin::update_config_file($data);
        }
        else
        {
            Admin::append_config_file($data);
        }
        
            $FNS->redirect(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_mailinglist');
            exit;     
    }
    // END
    
    
    
    
    // -------------------------------------------
    //  Import Mailinglist
    // -------------------------------------------    

    function import_mailinglist()
    {
        global $IN, $DSP, $PREFS, $LANG, $FNS, $LOC, $DB;
        
        // If this item is not set we know they have not configured yet 
                
        if (FALSE === ($batch = $PREFS->ini('pm_mailinglislt_batch')))
        {
            return $this->mailinglist_config_form();
        }

        $exp_db     = $PREFS->ini('db_name'); 
        $pm_db      = $PREFS->ini('pm_database');                   
        $completed  = $PREFS->ini('pm_mailinglist_total_done');
        $tables     = $PREFS->ini('pm_completed_tables');
        
        $sql = "SELECT count(*) AS count FROM {$pm_db}.pm_mailinglist";
                
        $query = $this->PM->query($sql);
        
        $total = $query->row['count'];
                
        if ($total < $batch)
        {
            $batch = $total;
        }
        
        $from = ($completed + 1);
        
        $to   = ($completed + $batch);

        if ($from == 1)
        {
            $from = 0;
        }

        if ($to >= $total)
        {
            $to = $total + 1;
        }

        // Import mailinglist        
        
        if ($IN->GBL('id'))
        {
            $sql = "SELECT email FROM {$pm_db}.pm_mailinglist ORDER BY email LIMIT ".$from.", ".$batch;
                    
            $query = $this->PM->query($sql);
            
            $total_done = 0;
            $last_row   = 0;
            
            foreach ($query->result as $row)
            {
            	if ($row['email'] != '')
            	{
					$email = trim($row['email']);
					
					$DB->query("INSERT into {$exp_db}.exp_mailing_list (user_id, email) VALUES ('', '$email') "); 
                }
                
                $total_done++;
            }
            
            if (($total_done + $completed) >= $total)
            {
                $tables .= "|pm_mailinglist";        
            }
            
            $total_done = $total_done + $completed;
                        
            Admin::update_config_file(
                                        array(
                                        		'pm_completed_tables'		 => $tables,
                                                'pm_mailinglist_total_done'  => $total_done
                                             )
                                      );
            
            $FNS->redirect(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_mailinglist');
            exit;     
        }
        
        
                 
        $DSP->title = $LANG->line('pmachine_import_utitity');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('pmachine_import_utitity')).$DSP->crumb_item($LANG->line('import_members'));
        
        $r = $DSP->heading($LANG->line('import_mailinglist'));
                
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_emails').NBS.$total);
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('emails_remaining').NBS.($total - $completed));
        
        if ($completed >= $total)
        {
            $r = $DSP->heading($LANG->line('mailinglist_imported'), 2);
        
            $r .= $DSP->qdiv('itemWrapper', BR.$DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('return_to_main_menu')));         
        }
        else
        {
            if ($to >= $total)
            {
                $to = $total;
            }
        
            $line = $LANG->line('click_to_import_mailinglist');
            
            if ($from > 1)
            {
            	$r .= $DSP->qdiv('success', $LANG->line('batch_complete'));
            }
            
                        
            if ($from == 0) 
                $from = 1;
        
            $line = str_replace("%x", $from, $line);
            $line = str_replace("%y", $to, $line);
            
            $r .= $DSP->qdiv('itemWrapper', $DSP->heading($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_mailinglist'.AMP.'id=1', '<b>'.$line.'</b>'), 5)); 
        }
    
        $DSP->body = $r;
    }
    // END
    
    
    
    
    
    // -------------------------------------------
    //  Member Configuration page
    // -------------------------------------------    

    function member_config_form()
    {
        global $DSP, $LANG, $PREFS, $DB;
                
         
        $DSP->title = $LANG->line('pmachine_import_utitity');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('pmachine_import_utitity')).$DSP->crumb_item($LANG->line('import_members'));
        
        $r = $DSP->heading($LANG->line('import_members'));
        
        $pm_db = $PREFS->ini('pm_database');                   
        
        $query = $this->PM->query("SELECT count(*) AS count FROM {$pm_db}.pm_members");
        
        $total = $query->row['count'];
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_members').NBS.$total);
        
        // Select your account
        
        $r .= $DSP->heading($LANG->line('select_your_account'), 2);
        $r .= $DSP->qdiv('box450', $LANG->line('ignore_instructions'));
        $r .= $DSP->div('itemWrapper');
        
        // Form declaration
        
        $r .= $DSP->form('C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=configure_members');
        
        // member pull-down menu
        
        $r .= $DSP->input_select_header('member_id');
            
        $query = $this->PM->query("SELECT signature, id FROM {$pm_db}.pm_members WHERE pm_members.status > '9'");
    
        foreach ($query->result as $row)
        {
            $r .= $DSP->input_select_option($row['id'], $row['signature']);
        }
    
        $r .= $DSP->input_select_footer();

        $r .= $DSP->div_c();
    
        $r .= $DSP->qdiv('itemWrapper', $DSP->input_checkbox('account_override', 'y').NBS.$LANG->line('i_have_no_account'));
        
        // Member group selection menu
        
        $r .= $DSP->heading(BR.$LANG->line('default_member_group'), 2);
    
        $r .= $DSP->qdiv('box450', $LANG->line('member_group_instructions'));
        
        $exp_db = $PREFS->ini('db_name');                   
        
        $query = $DB->query("SELECT group_id, group_title FROM {$exp_db}.exp_member_groups order by group_title");
        
        $r .= $DSP->input_select_header('member_group');
                    
        foreach ($query->result as $row)
        {

            if ($row['group_id'] == 1 || $row['group_id'] == 2)
            {
                continue;
            }
            
            $sel = ($row['group_id'] == 5) ? 1 : '';
                                
            $r .= $DSP->input_select_option($row['group_id'], $row['group_title'], $sel);
        }
        
        $r .= $DSP->input_select_footer();
        
        // Batch total form
        
        if ($total > $this->m_batch)
        {
            $r .= $DSP->heading(BR.$LANG->line('members_per_cycle'), 2);
            
            $r .= $DSP->qdiv('box450', $LANG->line('cycle_instructions'));
            
            $r .= $DSP->qdiv('itemWraper', $DSP->input_text('pm_member_batch', '1000', '20', '5', 'input', '50px'));
        }        
        
        $r .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('save_settings'), 'submit'));
        
        $r .= $DSP->form_c();
    
        $DSP->body =& $r;
    }
    // END
    
    
    
    
    // -------------------------------------------
    //  Configure Member Import prefs
    // -------------------------------------------    
    
    function configure_members()
    {
        global $IN, $DSP, $DB, $SESS, $PREFS, $FNS, $LANG;
                
         
        $DSP->title = $LANG->line('pmachine_import_utitity');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('pmachine_import_utitity')).$DSP->crumb_item($LANG->line('import_members'));
        
        $r = $DSP->heading($LANG->line('import_members'));
        
        $exp_db = $PREFS->ini('db_name');                   
        
        // If the user selected their account from pMachine
        // we'll update their EE member table with the pMachine ID
        
        $member_id = ( ! $IN->GBL('member_id', 'POST')) ? '' : $IN->GBL('member_id', 'POST');
    
        if ( ! $IN->GBL('account_override', 'POST'))
        {            
            $query = $DB->query("UPDATE {$exp_db}.exp_members SET exp_members.pmember_id = '$member_id' WHERE exp_members.member_id = '".$SESS->userdata['member_id']."'");
        }
        
        $pm_db = $PREFS->ini('pm_database');                   
        
        $query = $this->PM->query("SELECT count(*) AS count FROM {$pm_db}.pm_members");

        $total = $query->row['count'];
        
        $batch = ( ! $IN->GBL('pm_member_batch', 'POST')) ? $this->m_batch : $IN->GBL('pm_member_batch', 'POST');
        
        $data = array(
                        'pm_member_id'          => $member_id,
                        'pm_member_group'       => $IN->GBL('member_group', 'POST'),
                        'pm_member_batch'       => $batch,
                        'pm_member_total_done'  => ( ! $PREFS->ini('pm_member_total_done')) ? 0 : $PREFS->ini('pm_member_total_done'),
                        'pm_member_last_row'    => ( ! $PREFS->ini('pm_member_last_row'))   ? 0 : $PREFS->ini('pm_member_last_row')
                     );
        
        
        if ($PREFS->ini('pm_member_total_done'))
        {
            Admin::update_config_file($data);
        }
        else
        {
            Admin::append_config_file($data);
        }
        
        if ($member_id != '' AND $total == 1)
        {
            $r .= $DSP->qdiv('success', BR.$LANG->line('member_import_complete'));
            $r .= $DSP->qdiv('itemWrapper', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', '<b>'.$LANG->line('return_to_overview').'</b>'));
        
            $tables = $PREFS->ini('pm_completed_tables');
        
            $tables .= '|pm_members';
        
            Admin::update_config_file(array('pm_completed_tables' => $tables));
        }
        else
        {
            $FNS->redirect(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_members');
            exit;     
        }
     
        $DSP->body =& $r;
    }
    // END
    
    
    
    
    // -------------------------------------------
    //  Import Members
    // -------------------------------------------    

    function import_members()
    {
        global $IN, $DSP, $PREFS, $LANG, $FNS, $LOC, $DB;
        
        // If this item is not set we know they have not configured yet 
                
        if (FALSE === ($last_row = $PREFS->ini('pm_member_last_row')))
        {
            return $this->member_config_form();
        }

        $exp_db     = $PREFS->ini('db_name'); 
        $pm_db      = $PREFS->ini('pm_database');                   
        $last_row   = $PREFS->ini('pm_member_last_row');
        $batch      = $PREFS->ini('pm_member_batch');
        $completed  = $PREFS->ini('pm_member_total_done');
        $ignore_id  = $PREFS->ini('pm_member_id');
        $group_id   = $PREFS->ini('pm_member_group');
        $tables     = $PREFS->ini('pm_completed_tables');
        
        
        // Do we have custom fields?
        
        $query = $DB->query("SELECT COUNT(*) AS count FROM exp_member_data");
        
        $md_exists = ($query->row['count'] > 0) ? TRUE : FALSE;
        
        
        $sql = "SELECT count(*) AS count FROM {$pm_db}.pm_members";
        
        if ($ignore_id != '')
        {
            $sql .= "  WHERE id != '$ignore_id' ";
        }
                
        $query = $this->PM->query($sql);
        
        $total = $query->row['count'];
        
        if ($total < $batch)
        {
            $batch = $total;
        }
        
        $from = ($completed + 1);
        $to   = ($completed + $batch);

        if ($from == 1)
        {
            $from = 0;
        }

        if ($to >= $total)
        {
            $to = $total + 1;
        }

        // Import members        
        
        if ($IN->GBL('id'))
        {
            $sql = "SELECT * FROM {$pm_db}.pm_members ";
            
            if ($ignore_id != '')
            {
                $sql .= "  WHERE id != '$ignore_id' ";
            }
            
            $sql .= " ORDER BY id LIMIT ".$from.", ".$batch;
        
            $query = $this->PM->query($sql);
            
            $total_done = 0;
            $last_row   = 0;
            
            foreach ($query->result as $row)
            {
                $last_row = $row['id'];
            
                $accept_email = ($row['get_email'] == 'yes') ? 'y' : 'n';
                
                $data = array(
                                'pmember_id'            => $row['id'],
                                'username'              => $row['username'],
                                'password'              => $row['password'],
                                'ip_address'            => $row['ipaddress'],
                                'unique_id'             => $FNS->random('encrypt'),
                                'join_date'             => $LOC->set_gmt($row['joindate']),
                                'last_entry_date'		=> $LOC->set_gmt($row['lastpost']),                                
                                'email'                 => $row['email'],
                                'screen_name'           => $row['signature'],
                                'url'                   => $row['url'],
                                'location'              => $row['location'],
                                'bday_d'                => $row['bday_day'],
                                'bday_m'                => $row['bday_month'],
                                'bday_y'                => $row['bday_year'],
                                'group_id'              => $group_id,
                                'accept_admin_email'    => $accept_email,
                                'total_entries'         => $row['numentries'],
                                'total_comments'        => $row['numcomments'],
								'icq'  					=> $row['icq'],
								'aol_im'  				=> $row['aol_im'],
								'yahoo_im'  			=> $row['yahoo_im'],
								'msn_im'  				=> $row['msn_im'],
								'occupation'  			=> $row['occupation'],
								'interests'  			=> $row['interests'],
								'bio'			  		=> $row['bio']
                              );
                         
                $sql = $DB->insert_string($exp_db.'.exp_members', $data);         
                
                $DB->query($sql);
                
                $member_id = $DB->insert_id;
                
				// Create a record in the custom field table
				
				if ($md_exists == TRUE)
				{
					$DB->query($DB->insert_string($exp_db.'.exp_member_data', array('member_id' => $member_id)));
				}
				
				// Create a record in the member homepage table
									
				$DB->query($DB->insert_string($exp_db.'.exp_member_homepage', array('member_id' => $member_id)));
                
                $total_done++;
            }
            
            if (($total_done + $completed) >= $total)
            {
                $tables .= "|pm_members";        
            }
            
            $total_done = $total_done + $completed;
            
            Admin::update_config_file(
                                        array(
                                                'pm_completed_tables'   => $tables,
                                                'pm_member_total_done'  => $total_done,
                                                'pm_member_last_row'    => $last_row,
                                             )
                                      );
            
            $FNS->redirect(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_members');
            exit;     
        }
        
        
                 
        $DSP->title = $LANG->line('pmachine_import_utitity');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('pmachine_import_utitity')).$DSP->crumb_item($LANG->line('import_members'));
        
        $r = $DSP->heading($LANG->line('import_members'));
                
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_members').NBS.$total);
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('members_remaining').NBS.($total - $completed));
        
        if ($completed >= $total)
        {
            $r = $DSP->heading($LANG->line('members_imported'), 2);
        
            $r .= $DSP->qdiv('itemWrapper', BR.$DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('return_to_main_menu')));         
        }
        else
        {
            if ($to >= $total)
            {
                $to = $total;
            }
        
            $line = $LANG->line('click_to_import_members');
            
            if ($from > 1)
            {
            	$r .= $DSP->qdiv('success', $LANG->line('batch_complete'));
            }
            
                        
            if ($from == 0) 
                $from = 1;
        
            $line = str_replace("%x", $from, $line);
            $line = str_replace("%y", $to, $line);
            
            $r .= $DSP->qdiv('itemWrapper', $DSP->heading($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_members'.AMP.'id=1', '<b>'.$line.'</b>'), 5)); 
        }
    
        $DSP->body = $r;
    }
    // END
    
       
    
    
    
    // -------------------------------------------
    //  Weblog Configuration form
    // -------------------------------------------    

    function weblog_config_form()
    {
        global $IN, $DSP, $LANG, $PREFS, $DB;
         
        $DSP->title = $LANG->line('pmachine_import_utitity');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('pmachine_import_utitity')).$DSP->crumb_item($LANG->line('import_weblog_entries'));

        $weblog = $IN->GBL('id');
        
        $r = $DSP->heading($LANG->line('import_weblog_entries'));
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('weblog_name').NBS.'<b>'.$weblog.'</b>');
        
        $pm_db = $PREFS->ini('pm_database');                   
        
        $query = $this->PM->query("SELECT count(*) AS count FROM {$pm_db}.pm_weblog WHERE weblog = '$weblog'");
        
        $total = $query->row['count'];
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_weblog_entries').NBS.$total);
        
        // Form declaration
        
        $r .= $DSP->form('C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=configure_weblog'.AMP.'id='.$weblog);
        
        // Select your account
        
        $r .= $DSP->heading($LANG->line('select_destination_blog'), 2);
        
        $r .= $DSP->qdiv('box450', 
        							$LANG->line('destination_instructions').
        							$DSP->qdiv('highlight', $LANG->line('destination_note'))
        				);
                
        $r .= $DSP->div('itemWrapper');
        
        // weblog pull-down menu
        
        $r .= $DSP->input_select_header('weblog_id');
        
        $exp_db = $PREFS->ini('db_name');                   
            
        $query = $DB->query("SELECT weblog_id, blog_title FROM {$exp_db}.exp_weblogs ORDER BY blog_title");
    
        foreach ($query->result as $row)
        {
            $r .= $DSP->input_select_option($row['weblog_id'], $row['blog_title']);
        }
    
        $r .= $DSP->input_select_footer();
                    
        $r .= $DSP->div_c();
                
        
        // Select your image upload desination
        
        $r .= $DSP->heading(BR.$LANG->line('select_upload_blog'), 2);
        
        $r .= $DSP->qdiv('box450', 
        							$LANG->line('upload_instructions').
        							$DSP->qdiv('highlight', $LANG->line('upload_note'))
        				);
              
        $r .= $DSP->div('itemWrapper');
        
        // weblog pull-down menu
        
        $r .= $DSP->input_select_header('upload_id');
        
        $exp_db = $PREFS->ini('db_name');   
        
        
        $query = $DB->query("SELECT id, name FROM {$exp_db}.exp_upload_prefs WHERE is_user_blog = 'n' ORDER BY name");
    
        foreach ($query->result as $row)
        {
            $r .= $DSP->input_select_option($row['id'], $row['name']);
        }
    
        $r .= $DSP->input_select_footer();
                    
        $r .= $DSP->div_c();
        
        
            
        // Batch total form
        
        if ($total > $this->b_batch)
        {
            $r .= $DSP->heading(BR.$LANG->line('entries_per_cycle'), 2);
            
            $r .= $DSP->qdiv('box450', $DSP->qdiv('highlight', $LANG->line('blog_cycle_instructions')));
            
            $r .= $DSP->qdiv('itemWraper', $DSP->input_text('pm_weblog_batch_'.$weblog, $this->b_batch, '20', '5', 'input', '50px'));
        }
        
        
        // Note regarding categories
        
		$r .= $DSP->heading(BR.$LANG->line('note_regarding_categories'), 2);
		
		$r .= $DSP->qdiv('box450', $DSP->qdiv('', $LANG->line('category_note')));
        
        
        $r .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('save_settings'), 'submit'));
        
        $r .= $DSP->form_c();
    
        $DSP->body =& $r;
    }
    // END
    
    
        
    
    // -------------------------------------------
    //  Configure Weblog Import prefs
    // -------------------------------------------    
    
    function configure_weblog_prefs()
    {
        global $IN, $DSP, $DB, $SESS, $PREFS, $FNS, $LANG;
                
         
        $DSP->title = $LANG->line('pmachine_import_utitity');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('pmachine_import_utitity')).$DSP->crumb_item($LANG->line('import_weblog_entries'));
                
        $weblog     = $IN->GBL('id', 'GP');                
        $weblog_id  = $IN->GBL('weblog_id', 'GP');      
        $upload_id  = $IN->GBL('upload_id', 'GP');                        
        
        $batch = ( ! $IN->GBL('pm_weblog_batch_'.$weblog, 'POST')) ? $this->b_batch : $IN->GBL('pm_weblog_batch_'.$weblog, 'POST');
        
        $data = array(
                        'pm_weblog_id_'.$weblog          => $weblog_id,
                        'pm_upload_id_'.$weblog          => $upload_id,
                        'pm_weblog_batch_'.$weblog       => $batch,
                        'pm_weblog_total_done_'.$weblog  => ( ! $PREFS->ini('pm_weblog_total_done_'.$weblog)) ? 0 : $PREFS->ini('pm_weblog_total_done_'.$weblog)
                     );
                         
        if ($PREFS->ini('pm_weblog_dest_'.$weblog))
        {
            Admin::update_config_file($data);
        }
        else
        {
            Admin::append_config_file($data);
        }
        
        $r = $DSP->heading($LANG->line('import_weblog_entries'));
        
        $exp_db = $PREFS->ini('db_name');                   
        $pm_db = $PREFS->ini('pm_database');   
        
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('weblog_name').NBS.'<b>'.$weblog.'</b>');
                
        $query = $this->PM->query("SELECT count(*) AS count FROM {$pm_db}.pm_weblog WHERE weblog = '$weblog'");
        
        $total = $query->row['count'];
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_weblog_entries').NBS.$total);

        // Field mapping
        
        $r .= $DSP->heading($LANG->line('select_destination_fields'), 2);
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('field_destination_instructions'));
        $r .= $DSP->qdiv('itemWrapper', $DSP->qspan('highlight', $LANG->line('field_destination_instructions_two')));
        $r .= $DSP->qdiv('itemWrapper', $DSP->qspan('highlight', $LANG->line('fields_not_unique_warning')));
              
        $r .= $DSP->div('itemWrapper');
        
        // Form declaration
        
        $r .= $DSP->form('C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=configure_weblog_fields'.AMP.'id='.$weblog);
        
        // ----------------------------------------------
        //  Fetch weblog preferences
        // ---------------------------------------------

        $query = $DB->query("SELECT field_group FROM {$exp_db}.exp_weblogs WHERE weblog_id = '$weblog_id'");        
        
        if ($query->num_rows == 0)
        {
            return $DSP->error_message($LANG->line('no_fields_assigned'));
        }

        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }

        $query = $DB->query("SELECT field_id, field_label FROM {$exp_db}.exp_weblog_fields WHERE group_id = '$field_group' AND field_type = 'textarea' ORDER BY field_order");
        
        $fields = array();
        
        foreach ($query->result as $row)
        {
            $fields['field_id_'.$row['field_id']] = $row['field_label'];
        }

        if (count($fields) == 0)
        {
            return $DSP->error_message($LANG->line('no_textarea_fields'));
        }

         
        $r .= $DSP->table('tableBorder', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', 
                                array(
                                        $LANG->line('pmachine_field'),
                                        $LANG->line('ee_field')
                                     )
                                ).
              $DSP->tr_c();
        
        $r .= $DSP->tr();
        
        $i = 0;
      
      
      // Blurb
      
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
                
        $r .= $DSP->table_qcell($style, '<b>'.$LANG->line('blurb').'</b>', '25%');
        $r .= $DSP->td($style, '75%').$DSP->div().$DSP->qspan('highlight', $LANG->line('none')).NBS.$DSP->input_radio('blurb', 'none', 1).NBS.NBS.NBS;
        
        foreach ($fields as $key => $val)
        {
            $r .= $val.NBS.$DSP->input_radio('blurb', $key).NBS.NBS.NBS.NBS.NBS;
        }
        
        $r .= $DSP->div_c().$DSP->td_c().$DSP->tr_c(); 
        
        
        // Body
      
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
                
        $r .= $DSP->table_qcell($style, '<b>'.$LANG->line('body').'</b>', '25%');
        $r .= $DSP->td($style, '75%').$DSP->div().$DSP->qspan('highlight', $LANG->line('none')).NBS.$DSP->input_radio('body', 'none', 1).NBS.NBS.NBS;
        
        foreach ($fields as $key => $val)
        {
            $r .= $val.NBS.$DSP->input_radio('body', $key).NBS.NBS.NBS.NBS.NBS;
        }
        
        $r .= $DSP->div_c().$DSP->td_c().$DSP->tr_c(); 
        
      
      
        //  More
      
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
                
        $r .= $DSP->table_qcell($style, '<b>'.$LANG->line('more').'</b>', '25%');
        $r .= $DSP->td($style, '75%').$DSP->div().$DSP->qspan('highlight', $LANG->line('none')).NBS.$DSP->input_radio('more', 'none', 1).NBS.NBS.NBS;
        
        foreach ($fields as $key => $val)
        {
            $r .= $val.NBS.$DSP->input_radio('more', $key).NBS.NBS.NBS.NBS.NBS;
        }
        
        $r .= $DSP->div_c().$DSP->td_c().$DSP->tr_c(); 
        
      
      
        // Custom1
      
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
                
        $r .= $DSP->table_qcell($style, '<b>'.$LANG->line('custom1').'</b>', '25%');
        $r .= $DSP->td($style, '75%').$DSP->div().$DSP->qspan('highlight', $LANG->line('none')).NBS.$DSP->input_radio('custom1', 'none', 1).NBS.NBS.NBS;
        
        foreach ($fields as $key => $val)
        {
            $r .= $val.NBS.$DSP->input_radio('custom1', $key).NBS.NBS.NBS.NBS.NBS;
        }
        
        $r .= $DSP->div_c().$DSP->td_c().$DSP->tr_c(); 
        
      
        // Custom2
      
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
                
        $r .= $DSP->table_qcell($style, '<b>'.$LANG->line('custom2').'</b>', '25%');
        $r .= $DSP->td($style, '75%').$DSP->div().$DSP->qspan('highlight', $LANG->line('none')).NBS.$DSP->input_radio('custom2', 'none', 1).NBS.NBS.NBS;
        
        foreach ($fields as $key => $val)
        {
            $r .= $val.NBS.$DSP->input_radio('custom2', $key).NBS.NBS.NBS.NBS.NBS;
        }
        
        $r .= $DSP->div_c().$DSP->td_c().$DSP->tr_c(); 
      
        // Custom1
      
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
                
        $r .= $DSP->table_qcell($style, '<b>'.$LANG->line('custom3').'</b>', '25%');
        $r .= $DSP->td($style, '75%').$DSP->div().$DSP->qspan('highlight', $LANG->line('none')).NBS.$DSP->input_radio('custom3', 'none', 1).NBS.NBS.NBS;
        
        foreach ($fields as $key => $val)
        {
            $r .= $val.NBS.$DSP->input_radio('custom3', $key).NBS.NBS.NBS.NBS.NBS;
        }
        
        $r .= $DSP->div_c().$DSP->td_c().$DSP->tr_c(); 
             
        $r .= $DSP->table_c();
        

                    
        $r .= $DSP->div_c();
            
        
        $r .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('save_settings'), 'submit'));
        
        $r .= $DSP->form_c();
     
        $DSP->body =& $r;
    }
    // END
    
    
    
    
    // -------------------------------------------
    //  Configure weblog fields
    // -------------------------------------------    

    function configure_weblog_fields()
    {
        global $IN, $DSP, $LANG, $PREFS, $FNS, $DSP;
        
        $weblog = $IN->GBL('id', 'GP');
    
        $opt = array('blurb', 'body', 'more', 'custom1', 'custom2', 'custom3');
    
        $fields = array();
    
        foreach ($_POST as $key => $val)
        {
            if (in_array($key, $opt) AND $val != 'none')
            {
                $fields[$key] = $val;
            }        
        }
    
        if (count($fields) == 0)
        {
            return $DSP->error_message($LANG->line('you_must_select_fields'));
        }
    
        $unique = array_unique($fields);
        
                
        if (count($unique) != count($fields))
        {
            return $DSP->error_message($LANG->line('fields_not_unique_warning'));
        }

    
        $str = '';

        foreach ($opt as $f)
        {
            if (isset($fields[$f]))
            {
                $str .= $fields[$f].'|';  
            }
            else
            {
                $str .= 'none|';  
            }
        }
        
        $str = substr($str, 0, -1);
        
        $data = array('pm_weblog_field_map_'.$weblog => $str);
    
        if ($PREFS->ini('pm_weblog_field_map_'.$weblog))
        {
            Admin::update_config_file($data);
        }
        else
        {
            Admin::append_config_file($data);
        }


        $FNS->redirect(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_weblog'.AMP.'id='.$weblog);
        exit;         
    }
    // END
    
    
    
    
    
    
    // -------------------------------------------
    //  Import Weblog entries
    // -------------------------------------------    

    function import_weblog()
    {
        global $IN, $DSP, $DB, $LANG, $PREFS, $FNS, $LOC, $REGX;
        
        @set_time_limit(0);
        
        // The pMachine weblog name
        
        $weblog = $IN->GBL('id', 'GP');
        
        // If this item is not set we know they have not configured yet 
                
        if (FALSE === ($field_map = $PREFS->ini('pm_weblog_field_map_'.$weblog)))
        {
            return $this->weblog_config_form();
        }


        $exp_db     = $PREFS->ini('db_name'); 
        $pm_db      = $PREFS->ini('pm_database');   
        $weblog_id  = $PREFS->ini('pm_weblog_id_'.$weblog);
        $upload_id  = $PREFS->ini('pm_upload_id_'.$weblog);
        $batch      = $PREFS->ini('pm_weblog_batch_'.$weblog);
        $completed  = $PREFS->ini('pm_weblog_total_done_'.$weblog);
        $field_map  = $PREFS->ini('pm_weblog_field_map_'.$weblog);
        $tables     = $PREFS->ini('pm_completed_tables');
        
        if ($upload_id != '')
        {
        	$upload_id = '{filedir_'.$upload_id.'}';
        }
        
        
        // Fetch EE categories
        
        $exp_cats = array();
        $pm_cats  = array();
        
        $query = $DB->query("SELECT cat_group FROM {$exp_db}.exp_weblogs WHERE weblog_id = '$weblog_id'");
        
        if ($query->row['cat_group'] != '')
        {
            $query = $DB->query("SELECT cat_id, cat_name	FROM {$exp_db}.exp_categories WHERE group_id = '".$query->row['cat_group']."'"); 

			if ($query->num_rows > 0)
			{
				foreach ($query->result as $row)
				{
					$exp_cats[$row['cat_name']] = $row['cat_id'];
				}
        	}
        }
        
        // Fetch pM categories
        
        if (count($exp_cats) > 0)
        {
        	$sql = "SELECT id, category FROM {$pm_db}.pm_categories WHERE weblog = '$weblog'";
        
            $query = $this->PM->query($sql);
            
            if ($query->num_rows > 0)
            {
				foreach ($query->result as $row)
				{
					$pm_cats[$row['id']] = $row['category'];
				}
        	}
        }
        
        
        $do_cats = (count($exp_cats) > 0 AND count($pm_cats) > 0) ? TRUE : FALSE;
        
        
        list($blurb, $body, $more, $custom1, $custom2, $custom3) = explode("|", $field_map);
        
        $field_map = array(
                            'blurb'     => $blurb,
                            'body'      => $body,
                            'more'      => $more,
                            'custom1'   => $custom1,
                            'custom2'   => $custom2,
                            'custom3'   => $custom3
                          );


        $sql = "SELECT count(*) AS count FROM {$pm_db}.pm_weblog WHERE weblog = '$weblog'";
                        
        $query = $this->PM->query($sql);
        
        $total = $query->row['count'];
                
        if ($total <= $batch)
        {
            $batch = $total + 1;
        }
        
        $from = ($completed);
        
        $to   = ($completed + $batch);

        if ($from == 1)
        {
            $from = 0;
        }

        if ($to >= $total)
        {
            $to = $total + 1;
        }

        // ----------------------------
        // Import weblog entries
        // ----------------------------    

        if ($IN->GBL('z'))
        {
            $sql = "SELECT * FROM {$pm_db}.pm_weblog WHERE weblog = '$weblog' AND preview != '1' ORDER BY post_id LIMIT ".$from.", ".$batch;
        
            $query = $this->PM->query($sql);
            
            $total_done = 0;
            

            foreach ($query->result as $row)
            {
                $post_id = $row['post_id'];            
            
                $result = $DB->query("SELECT member_id, ip_address FROM {$exp_db}.exp_members WHERE pmember_id = '".$row['member_id']."'");
            
                $author_id  = $result->row['member_id'];
                $ip_address = $result->row['ip_address'];
                
                // Create URL Title
            	$url_title = $REGX->create_url_title($row['title']);
                            
                $allow_comments = ($row['showcomments'] ==  1) ? 'y' : 'n';
                
                $expiration_date = ($row['x_stamp'] == '2000000000') ? 0 : $LOC->set_gmt($row['x_stamp']);
                
                $recent_comment_date = ($row['c_date'] == '' || $row['c_date'] == 0) ? 0 :  $LOC->set_gmt($row['c_date']);
            
                $data = array(
                                'pentry_id'             => $row['post_id'],
                                'weblog_id'             => $weblog_id,
                                'author_id'             => $author_id,
                                'ip_address'            => $ip_address,
                                'title'                 => $row['title'],
                                'url_title'             => $url_title,
                                'status'                => $row['status'],
                                'allow_comments'        => $allow_comments,
                                'entry_date'            => $LOC->set_gmt($row['t_stamp']),
                                'year'                  => $LOC->set_partial_gmt('y', $row['year']),
                                'month'                 => $LOC->set_partial_gmt('m', '', $row['month']),
                                'day'                   => $LOC->set_partial_gmt('d', '', '', $row['day']),
                                'expiration_date'       => $expiration_date,
                                'recent_comment_date'   => $recent_comment_date,
                                'comment_total'         => $row['c_total'],
                                'trackback_total'       => $row['tb_total']
                              );
                         
                $sql = $DB->insert_string($exp_db.'.exp_weblog_titles', $data);         
                
                $DB->query($sql); 
                                
                $entry_id = $DB->insert_id;

                //------------------------------------
                // Insert the custom field data
                //------------------------------------
                
                $cust_fields = array('entry_id' => $entry_id, 'weblog_id' => $weblog_id);
                
                $no = TRUE;
                
                foreach ($field_map as $key => $val)
                {
                    if ($val != 'none')
                    {
                        if ($row[$key] != '')
                            $no = FALSE;
                                       
						$row[$key] = preg_replace("/%%dir\[\d+\]%%/", $upload_id, $row[$key]);
                    
                        $cust_fields[$val] = $row[$key];
                    }
                }

                if ($no == FALSE)
                    $DB->query($DB->insert_string($exp_db.'.exp_weblog_data', $cust_fields));
                
                
                //------------------------------------
                // Insert categories
                //------------------------------------

                if ($do_cats AND $row['category'] != '')
                {
					if (isset($pm_cats[$row['category']]))
					{
						$pm_cat_name = $pm_cats[$row['category']];

						if (isset($exp_cats[$pm_cat_name]))
						{
							$ee_cat_id = $exp_cats[$pm_cat_name];
						
                			$DB->query("INSERT INTO {$exp_db}.exp_category_posts (entry_id, cat_id) VALUES ('$entry_id', '$ee_cat_id')");
						}
					}
                }
                
                //------------------------------------
                // Insert the comment data
                //------------------------------------
                
                $sql = "SELECT body, member_id, t_stamp FROM {$pm_db}.pm_comments WHERE post_id = '$post_id' AND preview != '1' ORDER BY comment_id";
        
                $cquery = $this->PM->query($sql);   
                
                if ($cquery->num_rows > 0)
                {
                             
                    foreach ($cquery->result as $crow)
                    {
                        //-----------------------------------------
                        // Fetch the member data for each comment
                        //-----------------------------------------
    
                        $mbr = $crow['member_id'];  
                        
                        $memberflag = 0;
                        
                        if (substr($mbr, 0, 2)=="NM") 
                        {
                            $mbr = substr($mbr,2);
                            $mql = "SELECT signature, email, url, location, ipaddress FROM {$pm_db}.pm_nonmembers WHERE id = '$mbr'";                        
                        } 
                        else 
                        {
                            $memberflag =1;
                            $mql = "SELECT signature, email, url, location, ipaddress FROM {$pm_db}.pm_members WHERE id = '$mbr'";
                        }
                     
                        $mquery = $this->PM->query($mql); 
    
                        //-----------------------------------------
                        // Fetch the notification pref for each comment
                        //-----------------------------------------
    
                        $notify    = 'n';
                        $author_id = 0;
                     
                       if ($memberflag == 1)
                       {
                            $mresult = $DB->query("SELECT member_id FROM {$exp_db}.exp_members WHERE pmember_id = '".$mbr."'");
                        
                            if ($mresult->num_rows == 1)
                            {
                                $author_id  = $mresult->row['member_id'];
                            }                            
                       }                            
                        
                        //-----------------------------------------
                        // Build the comment data
                        //-----------------------------------------                    
                                            
                        $data = array(
                                        'weblog_id'     => $weblog_id,
                                        'entry_id'      => $entry_id,
                                        'author_id'     => $author_id,
                                        'name'          => $mquery->row['signature'],
                                        'email'         => $mquery->row['email'],
                                        'url'           => $mquery->row['url'],
                                        'location'      => $mquery->row['location'],
                                        'comment'       => $crow['body'],
                                        'comment_date'  => $LOC->set_gmt($crow['t_stamp']),
                                        'ip_address'    => $mquery->row['ipaddress'],
                                        'notify'        => 'n'
                                     );
                                                          
                        $DB->query($DB->insert_string($exp_db.'.exp_comments', $data));
         
                    }
                    // END COMMENTS                
                }


                //-----------------------------------------
                // Fetch trackback data
                //-----------------------------------------  

                $sql = "SELECT * FROM {$pm_db}.pm_trackback WHERE post_id = '$post_id' ORDER BY id";
        
                $tquery = $this->PM->query($sql);   
                
                if ($tquery->num_rows > 0)
                {
                             
                    foreach ($tquery->result as $trow)
                    {
                        //-----------------------------------------
                        // Build the trackback data
                        //-----------------------------------------  
                                          
                        $data = array(
                                        'entry_id'       => $entry_id,
                                        'title'          => $trow['entry_title'],
                                        'content'        => $trow['excerpt'],
                                        'weblog_name'    => $trow['blog_name'],
                                        'trackback_url'  => $trow['entry_url'],
                                        'trackback_date' => $LOC->set_gmt($trow['t_stamp']),
                                        'trackback_ip'   => 0
                                     );
                                             
                        $DB->query($DB->insert_string($exp_db.'.exp_trackbacks', $data));
         
                    }
                    // END TRACKBACKS                
                }


                $total_done++;
            }
            
            

            //-----------------------------------------
            // Show message
            //-----------------------------------------                    
            
            
            if (($total_done + $completed) >= $total)
            {
                $tables .= "|pm_weblog_".$weblog;        
            }
            
            $total_done = $total_done + $completed;
            
                        
            Admin::update_config_file(
                                        array(
                                                'pm_completed_tables'            => $tables,
                                                'pm_weblog_total_done_'.$weblog  => $total_done
                                             )
                                      );
            
            $FNS->redirect(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_weblog'.AMP.'id='.$weblog);
            exit;     
        }
        
        
                 
        $DSP->title = $LANG->line('pmachine_import_utitity');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('pmachine_import_utitity')).$DSP->crumb_item($LANG->line('import_weblog_entries'));
        
        $r = $DSP->heading($LANG->line('import_weblog_entries'));
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('weblog_name').NBS.'<b>'.$IN->GBL('id', 'GP').'</b>');
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_weblog_entries').NBS.$total);
        $r .= $DSP->heading(BR.$LANG->line('entries_remaining').NBS.($total - $completed), 5);

        
        if ($completed >= $total)
        {
            $r = $DSP->heading($LANG->line('entries_imported'), 2);
        
            $r .= $DSP->qdiv('itemWrapper', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import', $LANG->line('return_to_main_menu')));         
        }
        else
        {
            if ($to >= $total)
                $to = $total;
 
            if ($from == 0) 
                $from = 1;
                
            if ($to == 1 AND $from == 1)
                $from = 0;    
                
            if ($from > 1)
            {
                $r .= $DSP->qdiv('success', '');
            }    
                

            $line = $LANG->line('click_to_import_entries');
        
            $line = str_replace("%x", $from, $line);
            $line = str_replace("%y", $to, $line);
            
            $r .= $DSP->qdiv('itemWrapper', $DSP->heading($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=pm_import'.AMP.'F=import_weblog'.AMP.'id='.$weblog.AMP.'z=1', '<b>'.$line.'</b>'), 5)); 
        }
    
        $DSP->body = $r;
    }
    // END
    
  
 
    // -------------------------------------------
    //  clear config data
    // -------------------------------------------    
 
 	function clear_config_prefs()
 	{
 		global $DSP, $LANG;
 	
		require CONFIG_FILE;
		
		$newdata = array();
	 
		// -----------------------------------------
		//	Write config backup file
		// -----------------------------------------
				
		$old  = "<?php\n\n";
		$old .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($conf as $key => $val)
		{
			if ( ! ereg("^pm_", $key))
			{
				$newdata[$key] = $val;
			}
		
			$old .= "\$conf['".$key."'] = \"".addslashes($val)."\";\n";
		} 
		
		$old .= '?'.'>';
		
		$bak_path = str_replace(EXT, '', CONFIG_FILE);
		$bak_path .= '_bak'.EXT;
		
		if ($fp = @fopen($bak_path, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $old);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}		
				
		// -----------------------------------------
		//	Write config file as a string
		// -----------------------------------------
		
		$new  = "<?php\n\n";
		$new .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($newdata as $key => $val)
		{
			$new .= "\$conf['".$key."'] = \"".addslashes($val)."\";\n";
		} 
		
		$new .= '?'.'>';
		
		// -----------------------------------------
		//	Write config file
		// -----------------------------------------

		if ($fp = @fopen(CONFIG_FILE, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $new);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}
 	
		$DSP->title = $LANG->line('pmachine_import_utitity');
		$DSP->crumb = $LANG->line('pmachine_import_utitity');
		
        $DSP->body  = $DSP->heading($LANG->line('pmachine_import_utitity'));
        $DSP->body .= $DSP->qdiv('success', $LANG->line('you_are_done_importing'));
 	} 
  	// END
}
// END CLASS
?>