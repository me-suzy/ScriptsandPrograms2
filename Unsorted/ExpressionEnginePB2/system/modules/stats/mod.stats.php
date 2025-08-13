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
 File: mod.stats.php
-----------------------------------------------------
 Purpose: Statistics module
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Stats {

	var $return_data 	= '';

	// -----------------------------
	//  Constructor
	// -----------------------------

	function Stats()
	{
		global $TMPL, $LOC, $STAT, $SESS, $DB, $FNS;
		
        // -----------------------------------------
        //  Limit stats by weblog
        // -----------------------------------------
                    
	    // You can limit the stats by any combination of weblogs
	    // - but only if it is not a user blogs request
	    
        if (USER_BLOG === FALSE)
        {	    
            if ($blog_name = $TMPL->fetch_param('weblog'))
            {
                $sql = "SELECT	DISTINCT exp_weblogs.total_entries, 
                				exp_weblogs.total_comments,
                				exp_weblogs.total_trackbacks,
                				exp_weblogs.last_entry_date,
                				exp_weblogs.last_comment_date,
                				exp_weblogs.last_trackback_date 
                        FROM exp_weblogs, exp_weblog_titles 
                        WHERE exp_weblogs.is_user_blog = 'n'
                        AND exp_weblogs.weblog_id = exp_weblog_titles.weblog_id
						AND exp_weblog_titles.entry_date < ".$LOC->now." 
						AND (exp_weblog_titles.expiration_date = 0 || exp_weblog_titles.expiration_date > ".$LOC->now.") "
                       .$FNS->sql_andor_string($blog_name, 'exp_weblogs.blog_name');                       
                                
                $cache_sql = md5($sql);
                                                
                if ( ! isset($STAT->stats_cache[$cache_sql]))
                { 	        
                    $query = $DB->query($sql);
                    
                    $sdata = array(
                    					'total_entries'			=> 0,
                    					'total_comments'		=> 0,
                    					'total_trackbacks'		=> 0,
                    					'last_entry_date'		=> 0,
                    					'last_comment_date'		=> 0,
                    					'last_trackback_date'	=> 0
                    			  );
                    			  
                    
                    if ($query->num_rows > 0)
                    {
                        foreach($query->result as $row)
                        { 
                        	foreach ($sdata as $key => $val)
                        	{
                        		if (substr($key, 0, 5) == 'last_')
                        		{
									if ($row[$key] > $val)
									{
										$sdata[$key] = $row[$key];
									}
								}
								else
								{
									$sdata[$key] = $sdata[$key] + $row[$key];
								}
							}
                        }
					
						foreach ($sdata as $key => $val)
						{                        
                            $STAT->stats[$key] = $val;
                            
                            $STAT->stats_cache[$cache_sql][$key] = $val;
                       	} 
                    }
                }
                else
                {
                    foreach($STAT->stats_cache[$cache_sql] as $key => $val)
                    {
                        $STAT->stats[$key] = $val;
                    }
                }
            }
	    }
	       
		// ----------------------------------------
		//   Parse stat fields
		// ----------------------------------------

		$fields = array('total_members', 'total_entries', 'total_comments', 'total_trackbacks', 'most_visitors', 'total_logged_in', 'total_guests', 'total_anon');

		foreach ($fields as $field)
		{
			if ( isset($TMPL->var_single[$field]))
			{
				$TMPL->tagdata =& $TMPL->swap_var_single($field, $STAT->stats[$field], $TMPL->tagdata);
			}
		}

		// ----------------------------------------
		//   Parse dates
		// ----------------------------------------

		$dates = array('last_entry_date', 'last_comment_date', 'last_trackback_date', 'last_visitor_date', 'most_visitor_date');

		foreach ($TMPL->var_single as $key => $val)
		{   
			foreach ($dates as $date)
			{
				if (ereg("^".$date, $key))
				{
					$TMPL->tagdata =& $TMPL->swap_var_single(
																$key, 
																($STAT->stats[$date] == 0) ? '--' : 
																$LOC->decode_date($val, $STAT->stats[$date]), 
																$TMPL->tagdata
															 );
				}
			}
		}
		
		// ----------------------------------------
		//   {if member_names}
		// ----------------------------------------

		foreach ($TMPL->var_cond as $val)
		{
			if (preg_match("/^if\s+member_names.*/i", $val['0']))
			{			
				$rep = (count($STAT->stats['current_names']) == 0) ? '' : $val['2'];
								
				$TMPL->tagdata = str_replace($val['1'], $rep, $TMPL->tagdata); 
			}				
		}
		
		// ----------------------------------------
		//   Online user list
		// ----------------------------------------

		foreach ($TMPL->var_pair as $key => $val)
		{     
			if (ereg("^member_names", $key))
			{   
				if (count($STAT->stats['current_names']) > 0)
				{
					$names = '';
				
					$chunk =& $TMPL->fetch_data_between_var_pairs($TMPL->tagdata, 'member_names');      
					
        			$params =& $TMPL->assign_parameters($chunk);
				
					foreach ($STAT->stats['current_names'] as $k => $v)
					{
						if ($v['1'] == 'y')
						{
							if ($SESS->userdata['group_id'] == 1)
							{
								$names .= preg_replace("/".LD."name.*?".RD."/", $v['0'].'*', $chunk);
							}
							else
							{
								if ($SESS->userdata['member_id'] == $k)
								{
									$names .= preg_replace("/".LD."name.*?".RD."/", $v['0'].'*', $chunk);
								}
							}
						}
						else
						{
							$names .= preg_replace("/".LD."name.*?".RD."/", $v['0'], $chunk);
						}
					}
					
					if (is_array($params) AND isset($params['backspace']))
					{
						$names =& trim($names);
					
						$names =& substr($names, 0, - $params['backspace']);
					}
					
					$TMPL->tagdata =& preg_replace("/".LD.'member_names'.".*?".RD."(.*?)".LD.SLASH.'member_names'.RD."/s", $names, $TMPL->tagdata);
				}
				else
				{
					$TMPL->tagdata =& $TMPL->delete_var_pairs($key, 'member_names', $TMPL->tagdata);
				}
			}
		}		
		
		$this->return_data =& $TMPL->tagdata;
	}
	// END


}
// END CLASS
?>