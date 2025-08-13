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
 File: mcp.referrer.php
-----------------------------------------------------
 Purpose: Referrer class - CP
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Referrer_CP {

    var $version = '1.1';


    // -------------------------
    //  Constructor
    // -------------------------
    
    function Referrer_CP( $switch = TRUE )
    {
        global $IN;
        
        if ($switch)
        {
            switch($IN->GBL('P'))
            {
                case 'view'   			:  $this->view_referrers();
                    break;	
                case 'clear'	  		:  $this->clear_referrers();
                    break;
                case 'pmachine'   		:  $this->pmachine_blacklist();
                    break;
                case 'view_blacklist'   :  $this->view_blacklist();
                    break;
                case 'update_blacklist' :  $this->update_blacklist();
                    break;
                default       			:  $this->referrer_home();
                    break;
            }
        }
    }
    // END
    

    // -------------------------
    //  Referrer Home Page
    // -------------------------
    
    function referrer_home()
    {
        global $DSP, $DB, $LANG, $PREFS;
                        
        $DSP->title = $LANG->line('referrers');
        $DSP->crumb = $LANG->line('referrers');    
        

        $DSP->body .= $DSP->heading($LANG->line('referrers'));   
    
        $query = $DB->query("SELECT count(*) AS count FROM exp_referrers");
    
        $DSP->body .= $DSP->qdiv('itemWrapper', $DSP->heading($LANG->line('total_referrers').NBS.NBS.$query->row['count'], 5));
        $DSP->body .= $DSP->qdiv('itemWrapper', $DSP->heading($DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=referrer'.AMP.'P=view', $LANG->line('view_referrers')), 5));
        $DSP->body .= $DSP->qdiv('itemWrapper', $DSP->heading($DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=referrer'.AMP.'P=clear', $LANG->line('clear_referrers')), 5));
		//$DSP->body .= $DSP->qdiv('itemWrapper', $DSP->heading($DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=referrer'.AMP.'P=add_to_blacklist', $LANG->line('ref_add_to_blacklist')), 5));
		$DSP->body .= $DSP->qdiv('itemWrapper', $DSP->heading($DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=referrer'.AMP.'P=view_blacklist', $LANG->line('ref_view_blacklist')), 5));
       
    	}
    }
    // END
    


    // -------------------------
    //  View Referrers
    // -------------------------
    
    function view_referrers()
    {
        global $IN, $DSP, $LANG, $FNS, $DB, $LOC, $PREFS;
                
        if ( ! $rownum = $IN->GBL('rownum', 'GP'))
        {        
            $rownum = 0;
        }
        
        $perpage = 100;

		$qm = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';
                        
        $DSP->title = $LANG->line('referrers');
        $DSP->crumb  = $DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=referrer', $LANG->line('referrers'));
        $DSP->crumb .= $DSP->crumb_item($LANG->line('view_referrers'));    

        $r = $DSP->heading($LANG->line('view_referrers'));     
        
        
        $query = $DB->query("SELECT count(*) AS count FROM exp_referrers WHERE user_blog = ''");

        if ($query->row['count'] == 0)
        {
            $r .= $DSP->qdiv('itemWrapper', $LANG->line('no_referrers'));
        
            $DSP->body .= $r;        

            return;
        }        
        
        $total = $query->row['count'];
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_referrers').NBS.NBS.$total);
        
        $query = $DB->query("SELECT * FROM exp_referrers WHERE user_blog = '' ORDER BY ref_id desc LIMIT $rownum, $perpage");

		$r .= <<<EOT

<script type="text/javascript">
function showHide(entryID, htmlObj, linkType) {

extTextDivID = ('extText' + (entryID));
extLinkDivID = ('extLink' + (entryID));

if (linkType == 'close')
{
	document.getElementById(extTextDivID).style.display = "none";
	document.getElementById(extLinkDivID).style.display = "block";
	htmlObj.blur();
}
else
{
	document.getElementById(extTextDivID).style.display = "block";
	document.getElementById(extLinkDivID).style.display = "none";
	htmlObj.blur();
}

}
</script>

EOT;

        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', 
                                array(
                                        $LANG->line('referrer_from'),
                                        $LANG->line('referrer_to'),
                                        $LANG->line('referrer_ip'),
                                        $LANG->line('referrer_date'),
                                        $LANG->line('ref_user_agent')
                                     )
                                ).
              $DSP->tr_c();


        $i = 0;
        
		$site_url =& $PREFS->ini('site_url');
        
        foreach($query->result as $row)
        {
            $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';;
                      
            $r .= $DSP->tr();
            
            // From
            
            $r .= $DSP->table_qcell($style, $DSP->anchor($FNS->fetch_site_index().$qm.'URL='.$row['ref_from'], $row['ref_from'], '', 1), '28%');
        
            // To
            
            $to = str_replace($site_url, '', $row['ref_to']);
            
            $r .= $DSP->table_qcell($style, $DSP->anchor($FNS->fetch_site_index().$qm.'URL='.$row['ref_to'], $to, '', 1), '22%');
        
        	// IP
        	$ip = ($row['ref_ip'] != '' AND $row['ref_ip'] != 0) ? $row['ref_ip'] : '-';
        	
        	$r .= $DSP->table_qcell($style, $ip, '15%');
        	
        	// Date
        	
        	$date = ($row['ref_date'] != '' AND $row['ref_date'] != 0) ? $LOC->set_human_time($row['ref_date']) : '-';
        	
        	$r .= $DSP->table_qcell($style, $date, '15%');
        	
        	
        	// Agent
        	$agent = ($row['ref_agent'] != '') ? $row['ref_agent'] : '-';
        	
        	if (strlen($agent) > 11) 
        	{
        		$agent2 = "<a href=\"javascript:void(0);\" name=\"ext{$i}\" onclick=\"showHide({$i},this,'close');return false;\">[-]</a>".NBS.NBS.$agent;
        		
        		$agent = "<div id='extLink{$i}'><a href=\"javascript:void(0);\" name=\"ext{$i}\" onclick=\"showHide({$i},this,'open');return false;\">[+]</a>".NBS.NBS.preg_replace("/(.+?)\s+.*/", "\\1", $agent)."</div>"
        		.'<div id="extText'.$i.'" style="display: none; padding:0;">'.$agent2.'</div>';
        	}
        	
        	$r .= $DSP->table_qcell($style, $agent, '20%');
        
            $r .= $DSP->tr();
        }

        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        // Pass the relevant data to the paginate class so it can display the "next page" links
        
        $r .=  $DSP->div('itemWrapper').
               $DSP->pager(
                            BASE.AMP.'C=modules'.AMP.'M=referrer'.AMP.'P=view',
                            $total,
                            $perpage,
                            $rownum,
                            'rownum'
                          ).
              $DSP->div_c();


        $DSP->body .= $r;        
    }
    // END
    

    // -------------------------
    //  Clear Referrers
    // -------------------------
    
    function clear_referrers()
    {
        global $IN, $DSP, $LANG, $DB;
                
        $DSP->title = $LANG->line('referrers');
        $DSP->title = $LANG->line('referrers');
        $DSP->crumb  = $DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=referrer', $LANG->line('referrers'));
        $DSP->crumb .= $DSP->crumb_item($LANG->line('clear_referrers'));        

        $r = $DSP->heading($LANG->line('clear_referrers'));
        
        $save = ( ! isset($_POST['save'])) ? '' : $_POST['save'] - 1;
        
        if (is_numeric($save) AND $save >= 0)
        {
            $query = $DB->query("SELECT count(*) AS count FROM exp_referrers");
        
            $total = $query->row['count'];
            
            if ($total > $save)
            {            
                $query = $DB->query("SELECT ref_id FROM exp_referrers ORDER BY ref_id desc LIMIT 1");
            
                $id = $query->row['ref_id'] - $save;
                
                $DB->query("DELETE FROM exp_referrers WHERE ref_id < $id");
            }
            
            $r .= $DSP->qdiv('success', $LANG->line('referrers_deleted'));
            
            $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_referrers').NBS.NBS.($save +1));
        }
        else
        {
            $r .= $DSP->form('C=modules'.AMP.'M=referrer'.AMP.'P=clear')
                 .$DSP->div()
                 .$DSP->qdiv('itemWrapper', $LANG->line('save_instructions'))
                 .$DSP->input_text('save', '100', '6', '4', 'input', '50px')
                 .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('submit')))
                 .$DSP->div_c()
                 .$DSP->form_c();
        }
        
        $DSP->body =& $r;
    }
    // END
    
    
    
    // -------------------------
    //  Update Blacklisted Items
    // -------------------------
    
    function update_blacklist()
    {
    	global $IN, $DB, $DSP, $LANG;
    	
    	if ( ! in_array($DB->prefix.'blacklisted', $DB->fetch_tables()))
        {
        	$r = $DSP->error_message($LANG->line('ref_no_blacklist_table'));
        	$DSP->body .= $r;        
            return;
        }
    	
    	$default = array('ip', 'agent', 'url');
        
        foreach ($default as $val)
        {
			if (isset($_POST[$val]) AND $_POST[$val] != '')
			{
				 $_POST[$val] = str_replace('[-]', '', $_POST[$val]);
				 $_POST[$val] = str_replace('[+]', '', $_POST[$val]);
				 $_POST[$val] = trim(stripslashes($_POST[$val]));
				 
				 $new_values = explode(NL,strip_tags($_POST[$val]));
				 
				 // Clean out user mistakes
				 foreach ($new_values as $key => $value)
				 {
					if (trim($value) == "" || trim($value) == NL)
					{
						unset($new_values[$key]);
					}
				 }
				 
				 $_POST[$val] = implode("|",$new_values);
				 
				 $DB->query("DELETE FROM exp_blacklisted WHERE blacklisted_type = '{$val}'");
				 
				 $data = array(	'blacklisted_type' 	=> $val,
								'blacklisted_value'	=> $_POST[$val]);
								
				 $DB->query($DB->insert_string('exp_blacklisted', $data));			     
			}
        } 		
		
		return $this->view_blacklist($DSP->qdiv('success', $LANG->line('blacklist_updated')));
    }
    // End
    
    
    
    // -------------------------
    //  Update Blacklist
    // -------------------------
    
    function pmachine_blacklist()
    {
        global $DSP, $LANG, $PREFS, $DB;
        
        
        $DSP->title = $LANG->line('referrers');
        $DSP->title = $LANG->line('referrers');
        $DSP->crumb  = $DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=referrer', $LANG->line('referrers'));
        $DSP->crumb .= $DSP->crumb_item($LANG->line('pmachine_blacklist'));  
        
        $r = '';  
        
        if ( ! in_array($DB->prefix.'blacklisted', $DB->fetch_tables()))
        {
        	$r = $DSP->error_message($LANG->line('ref_no_blacklist_table'));
        	$DSP->body .= $r;        
            return;
        }      
        
        if ( ! class_exists('XML_RPC'))
		{
			require PATH_CORE.'core.xmlrpc'.EXT;
		}
		                
        
        
        // -----------------------------------------
        // Add Current Blacklisted
        // -----------------------------------------
        
        $query = $DB->query("SELECT * FROM exp_blacklisted");
        
        if ($query->num_rows > 0)
        {
        	foreach($query->result as $row)
        	{
        		$old_values = explode('|',$row['blacklisted_value']);
        		for ($i=0; $i < sizeof($old_values); $i++)
        		{
        			$new[$row['blacklisted_type']][] = $old_values[$i]; 
        		}       	
        	}
        }
        
        
        // -----------------------------------------
        // Check for uniqueness and sort
        // -----------------------------------------        
        
        $new['url'] 	= array_unique($new['url']);
        $new['agent']	= array_unique($new['agent']);
        $new['ip']		= array_unique($new['ip']);
        
        sort($new['url']);
        sort($new['agent']);
        sort($new['ip']); 
        
        
        // -----------------------------------------
		//	Put blacklist info back into database
		// -----------------------------------------
		
		$DB->query("DELETE FROM exp_blacklisted");
		
		foreach($new as $key => $value)
		{
			$blacklisted_value = implode('|',$value);
			
			$data = array(	'blacklisted_type' 	=> $key,
							'blacklisted_value'	=> $blacklisted_value);
								
			$DB->query($DB->insert_string('exp_blacklisted', $data));
		}
        
        
        // -----------------------------------------
		//	Blacklist updated message
		// -----------------------------------------
		$r .= $DSP->heading($LANG->line('pmachine_blacklist'));   
		$r .= $DSP->qdiv('success', $LANG->line('blacklist_updated')); 
        
        $DSP->body =& $r;
    }
    // END
    
    
    
    // -------------------------
    //  View Blacklisted
    // -------------------------
    
    function view_blacklist($msg = '')
    {
        global $IN, $DSP, $LANG, $FNS, $DB, $PREFS;

		$qm = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';
                        
        $DSP->title = $LANG->line('referrers');
        $DSP->crumb  = $DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=referrer', $LANG->line('referrers'));
        $DSP->crumb .= $DSP->crumb_item($LANG->line('ref_view_blacklist'));     
        
        if ( ! in_array($DB->prefix.'blacklisted', $DB->fetch_tables()))
        {
        	$r = $DSP->error_message($LANG->line('ref_no_blacklist_table'));
        	$DSP->body .= $r;        
            return;
        }
        
        $r = $DSP->heading($LANG->line('ref_view_blacklist')); 
        
        $r .= $msg;
        
        $rows = array();
        $default = array('ip', 'url','agent');
        foreach ($default as $value)
        {
        	$rows[$value] = '';
        }
        
        // Store by type with | between values       
        $query = $DB->query("SELECT * FROM exp_blacklisted ORDER BY blacklisted_type asc");
        
        if ($query->num_rows != 0)
        {
        	foreach($query->result as $row)
        	{
        		$rows[$row['blacklisted_type']] = $row['blacklisted_value'];	
        	}
        }

		$r .= $DSP->form('C=modules'.AMP.'M=referrer'.AMP.'P=update_blacklist', 'target');
		
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
              
        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', 
                                array(
                                        $LANG->line('ref_type'),
                                        $LANG->line('ref_blacklisted')
                                     )
                                ).
              $DSP->tr_c();


        $i = 0;
        
        //sort($rows);
        foreach($rows as $key => $value)
        {
            $style = ($i++ % 2) ? 'tableCellOneBold' : 'tableCellTwoBold';
                      
            $r .= $DSP->tr();
            
            // Type
            switch($key)
            {
            	case 'ip' :
            		$name = $LANG->line('ref_ip');
            	break;
            	case 'agent' :
            		$name = $LANG->line('ref_user_agent');
            	break;
            	default:
            		$name = $LANG->line('ref_url');
            	break;
            }

        	$r .= $DSP->table_qcell($style, $name,'35%','top');
        	
        	// Value
        	$value = str_replace('|',NL,$value); 
        	$r .= $DSP->table_qcell($style, $DSP->input_textarea($key, $value, 15, 'textarea', '100%'));
        	
        }

        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

    	$r .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('update')));             
        
       	$r .= $DSP->form_c();

        $DSP->body .= $r;        
    }
    // END


    // -------------------------
    //  Module installer
    // -------------------------

    function referrer_module_install()
    {
        global $DB;        
        
        $sql[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Referrer', '$this->version', 'y')";
    	$sql[] = "CREATE TABLE IF NOT EXISTS  `exp_blacklisted` (
				`blacklisted_type` VARCHAR( 20  )  NOT  NULL ,
				`blacklisted_value` TEXT  NOT  NULL);";
    
        foreach ($sql as $query)
        {
            $DB->query($query);
        }
        
        return true;
    }
    // END
    
    
    // -------------------------
    //  Module de-installer
    // -------------------------

    function referrer_module_deinstall()
    {
        global $DB;    

        $query = $DB->query("SELECT module_id FROM exp_modules WHERE module_name = 'Referrer'"); 
                
        $sql[] = "DELETE FROM exp_module_member_groups WHERE module_id = '".$query->row['module_id']."'";
        $sql[] = "DELETE FROM exp_modules WHERE module_name = 'Referrer'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Referrer'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Referrer_CP'";
        //$sql[] = "DELETE TABLE IF EXISTS `exp_blacklisted`";

        foreach ($sql as $query)
        {
            $DB->query($query);
        }

        return true;
    }
    // END



}
// END CLASS
?>