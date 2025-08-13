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
 File: cp.log.php
-----------------------------------------------------
 Purpose: Logging class
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Logger {



    //-------------------------------------
    // Log an action
    //-------------------------------------    

    function log_action($action = '')
    {
        global $DB, $SESS, $IN, $LOC;

		if ($action == '')
		{
			return;
		}
                
        if (is_array($action))
        {
        	if (count($action) == 0)
        	{
        		return;
        	}
        
            $msg = '';
        
            foreach ($action as $val)
            {
                $msg .= $val."\n";    
            }
            
            $action = &$msg;
        }
                                               
        $DB->query(
                     $DB->insert_string(
                                           'exp_cp_log',
                
                                            array(
                                                    'id'         => '',
                                                    'member_id'  => $SESS->userdata['member_id'],
                                                    'username'   => $SESS->userdata['username'],
                                                    'ip_address' => $IN->IP,
                                                    'act_date'   => $LOC->now,
                                                    'action'     => $action
                                                 )
                                            )
                    );    
    }
    // END



    //-------------------------------------
    // Clear control panel logs
    //-------------------------------------    

    function clear_cp_logs()
    {
        global $DSP, $LANG, $DB;
    
        if ( ! $DSP->allowed_group('can_admin_utilities'))
        {
            return $DSP->no_access_message();
        }
    
        $query = $DB->query("DELETE FROM exp_cp_log");
        
        $this->log_action($LANG->line('cleared_logs'));
        
        return $this->view_logs();
    }
    // END



    //-------------------------------------
    // View control panel logs
    //-------------------------------------

    function view_logs()
    {
        global $DSP, $LANG, $LOC, $IN, $DB;
    
        if ( ! $DSP->allowed_group('can_admin_utilities'))
        {
            return $DSP->no_access_message();
        }
        
        // Number of results per page
        
         $perpage = 100;  
        
        // Fetch the total number of logs for our paginate links
        
        $query = $DB->query("SELECT COUNT(*) as count FROM exp_cp_log");
        
        $total = $query->row['count'];
        
        if ( ! $rownum = $IN->GBL('rownum', 'GP'))
        {        
            $rownum = 0;
        }
        
        // Run the query
            
        $query = $DB->query("SELECT * FROM exp_cp_log ORDER BY act_date desc LIMIT $rownum, $perpage");
        
        // Build the output
        
        $r  = $DSP->heading($LANG->line('view_log_files'));

        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
             
        $r .= $DSP->table('', '0', '0', '100%');
             
        $r .= $DSP->table_qrow('tableHeadingBold',
                              array(
                                    $LANG->line('member_id'),
                                    $LANG->line('username'),
                                    $LANG->line('ip_address'),
                                    $LANG->line('date'),
                                    $LANG->line('action')
                                   )
                             );
        
        $i = 0;
        
        foreach ($query->result as $row)
        {
            $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
        
            $r .= $DSP->table_qrow($style,
                                    array(
                                            $row['member_id'],
                                            $row['username'],
                                            $row['ip_address'],
                                            $LOC->set_human_time($row['act_date']),
                                            nl2br($row['action'])
                                          )
                                   );
        }
        
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();
        
        $r .= $DSP->qdiv('itemWrapper',
              $DSP->qdiv('crumblinks',
              $DSP->pager(
                            BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=view_logs',
                            $total,
                            $perpage,
                            $rownum,
                            'rownum'
                          )));
              
              
        $DSP->title  = $LANG->line('view_log_files');
        $DSP->crumb  = $LANG->line('view_log_files');
        $DSP->rcrumb = $DSP->qdiv('crumblinksR', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=clear_cplogs', $LANG->line('clear_logs')));
        $DSP->body   = &$r;
    }
    // END
}
// END CLASS
?>