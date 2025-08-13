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
 File: mod.trackback.php
-----------------------------------------------------
 Purpose: Trackback class
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Trackback {
	
    // ----------------------------------------
    //  Constructor
    // ----------------------------------------
    
	function Trackback()
	{
	}


    // ----------------------------------------
    //  Trackback Entries
    // ----------------------------------------

    function entries()
    {
        global $IN, $DB, $TMPL, $LOC, $FNS;
        
        $return = '';
        
        if ($IN->QSTR == '')
        {
            return false;
        }
                
        if ( ! is_numeric($IN->QSTR))
        {
            return false;
        }
        
        $entry_id = &$IN->QSTR;

        
        $switch = array();
            
        // ----------------------------------------
        //  Build query
        // ----------------------------------------
        
        $sql = "SELECT exp_trackbacks.*, exp_weblog_titles.weblog_id, exp_weblog_titles.allow_trackbacks
                FROM   exp_trackbacks
                LEFT   JOIN exp_weblog_titles ON (exp_weblog_titles.entry_id = exp_trackbacks.entry_id)
                WHERE  exp_trackbacks.entry_id = '$entry_id' ";        
        
        $orderby  = ( ! $TMPL->fetch_param('orderby'))  ? 'trackback_date' : $TMPL->fetch_param('orderby');
        
        $sort  = ( ! $TMPL->fetch_param('sort'))  ? 'desc' : $TMPL->fetch_param('sort');
            
        $sql .= "ORDER BY $orderby $sort "; 
        
        if ($TMPL->fetch_param('limit'))
        {
            $sql .= "LIMIT ".$TMPL->fetch_param('limit'); 
        }
        
        $query = $DB->query($sql);
        
        if ($query->num_rows == 0)
        {
            return false;
        }
        
        if ($query->row['allow_trackbacks'] == 'n')
        {
            return false;
        }

        // ----------------------------------------
        //  Start the processing loop
        // ----------------------------------------        
        
        foreach ($query->result as $row)
        {
            $tagdata =& $TMPL->tagdata;     
            
            
            // ----------------------------------------
            //   Parse conditional pairs
            // ----------------------------------------

            foreach ($TMPL->var_cond as $key => $val)
            {                
                // ----------------------------------------
                //   Parse conditional statements
                // ----------------------------------------
                            
                $cond = preg_replace("/^if/", "", $val['0']);
                
                // Since we allow the following shorthand condition: {if username}
                // but it's not legal PHP, we'll correct it by adding:  != ''
                
                if ( ! ereg("\|", $cond))
                {                    
                    if ( ! preg_match("/(\!=|==|<|>|<=|>=|<>)/s", $cond))
                    {
                        $cond .= " != ''";
                    }
                }
								
				if ( isset($row[$val['3']]))
				{  
					$cond =& str_replace($val['3'], "\$row['".$val['3']."']", $cond);
					  
					$cond =& str_replace("\|", "|", $cond);
							 
					eval("\$result = ".$cond.";");
										
					if ($result)
					{
						$tagdata =& str_replace($val['1'], $val['2'], $tagdata);                 
					}
					else
					{
						$tagdata =& str_replace($val['1'], '', $tagdata);                 
					}   
				}
            }
            // END CONDITIONAL PAIRS
            
                        
     
            // ----------------------------------------
            //   Parse "single" variables
            // ----------------------------------------

            foreach ($TMPL->var_single as $key => $val)
            { 
            
				// ----------------------------------------
				//  parse {switch} variable
				// ----------------------------------------
				
				if (ereg("^switch", $key))
				{
					$sparam =& $TMPL->assign_parameters($key);
					
					$sw = '';

					if (isset($sparam['switch']))
					{
						$sopt = explode("|", $sparam['switch']);
						
						if (count($sopt) == 2)
						{
							if (isset($switch[$sparam['switch']]) AND $switch[$sparam['switch']] == $sopt['0'])
							{
								$switch[$sparam['switch']] = $sopt['1'];
								
								$sw = $sopt['1'];									
							}
							else
							{
								$switch[$sparam['switch']] = $sopt['0'];
								
								$sw = $sopt['0'];									
							}
						}
					}
					
					$tagdata =& $TMPL->swap_var_single($key, $sw, $tagdata);
				}
              
              
            
                // ----------------------------------------
                //  parse trackback date
                // ----------------------------------------
                
                if (ereg("^trackback_date", $key))
                {
                        $tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $LOC->decode_date($val, $row['trackback_date']), 
                                                            $tagdata
                                                          );
                }
                                        
                // ----------------------------------------
                //  parse basic fields - {title}, {content}, {trackback_url}, {weblog_name}, {trackback_id}
                // ----------------------------------------
                 
                if (isset($row[$val]))
                {                    
                    $tagdata =& $TMPL->swap_var_single($val, $row[$val], $tagdata);
                }
                    
            }        
        
        
            $return .= $tagdata;
        }
        
        return $return;
    }
    // END
    

   
    // ----------------------------------------
    //  Trackback URL
    // ----------------------------------------

    // Returns the URL to the trackback server along with
    // the ID number of the entry associated with the TB
    
    function url()
    {
        global $FNS, $DB, $LANG, $IN;
        
        if ($IN->QSTR == '' || ! is_numeric($IN->QSTR))
            return;

        // ----------------------------------------
        //   Are trackbacks allowed?
        // ----------------------------------------
        
        $query = $DB->query("SELECT allow_trackbacks FROM exp_weblog_titles WHERE entry_id = '{$IN->QSTR}'");
        
        if ($query->num_rows == 0)
        {
            return '';
        }
        
        if ($query->row['allow_trackbacks'] == 'n')
        {
        	$LANG->fetch_language_file('trackback');

            return $LANG->line('trackbacks_not_allowed');
        }
            
        $action_id = $FNS->fetch_action_id('Trackback_CP', 'receive_trackback');
        
        $server = $FNS->fetch_site_index(1, 0)."trackback/".$IN->QSTR.'/';    
    
        return $server;
    }
    // END
        

}
// End Trackback Class
?>