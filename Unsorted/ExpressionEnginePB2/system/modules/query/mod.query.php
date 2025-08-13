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
 File: mod.query.php
-----------------------------------------------------
 Purpose: Allows direct SQL queries in templates
=====================================================

EXAMPLE:

{exp:query sql="select * from exp_members where username = 'joe' "}

 <h1>{username}</h1>
 
 <p>{email}</p>
 
 <p>{url}</p>

{/exp:query}

*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Query {

    var $return_data = ''; 

    // -------------------------------------
    //  Constructor
    // -------------------------------------

    function Query()
    {        
        $this->query = $this->basic_select();
    }
    // END



    // -------------------------------------
    //  Basic SQL 'select' query
    // -------------------------------------

    function basic_select()
    {
        global $DB, $TMPL;
        
        // Extract the query from the tag chunk
        
        $sql = preg_replace("/".LD.".*?sql\s*=\s*[\"|\"](.*?)[\"|\"]\s*".RD.".*/s", "\\1", $TMPL->tagchunk, 1);
                
        if (FALSE === $sql)
            return false;
             
        if ( ! eregi('^select', $sql))
            return false;
        
        // Run the query
                    
        $query = $DB->query($sql);
        
        if ($query->num_rows == 0)
            return false;

        foreach ($query->result as $row)
        {
            $tagdata = $TMPL->tagdata;
 
            foreach ($TMPL->var_single as $key => $val)
            {              
                if (isset($row[$val]))
                {                    
                    $tagdata = $TMPL->swap_var_single($val, $row[$val], $tagdata);
                }
            }
             
          $this->return_data .= $tagdata;                         
        }
    }
    // END
}
// END CLASS
?>