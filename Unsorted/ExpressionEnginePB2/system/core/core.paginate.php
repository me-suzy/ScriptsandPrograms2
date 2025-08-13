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
 File: core.paginate.php
-----------------------------------------------------
 Purpose: This class creates links like this: 

    First < 3 4 [5] 6 7 > Last
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Paginate {

        var $base_url     	= ''; // The page we are linking to (when using this class in the CP)
        var $path			= ''; // The page we are linking to (when using this class in a public page)
		var $prefix			= ''; // A custom prefix added to the path.
        var $qstr_var     	= ''; // The name of the query string variable containing current page number
        var $cur_page     	= ''; // The current page being viewed
        var $total_count  	= ''; // Total number of items (database results)
        var $per_page     	= ''; // Max number of items you want shown per page
        var $max_links    	=  2; // Number of "digit" links to show before/after the currently viewed page
        var $first_page   	= 'First';
        var $last_page    	= 'Last';
        var $first_marker	= '&laquo;';
        var $last_marker	= '&raquo;';
        var $first_url		= ''; // Alternative URL for the Fist Page.

    // ----------------------------------------
    //  Constructor
    // ----------------------------------------

    function Paginate()
    {
        global $LANG;
               
        $this->first_page = $this->first_marker.' '.$LANG->line('first');   
        $this->last_page  = $LANG->line('last').' '.$this->last_marker;   
    }
    // END

        
    // ----------------------------------------
    //  Show links
    // ----------------------------------------

    function show_links()
    {
        global $IN, $FNS;
        
		// ----------------------------------------
		//  Do we have links to show?
		// ----------------------------------------
                    
        // If our item count or per-page total is zero there is no need to continue
        
        if ($this->total_count == 0 || $this->per_page == 0)
        {
           return;
    	}
    	
		// ----------------------------------------
		//  Define the base path
		// ----------------------------------------
		
		// Since we can use this class in the CP or with public pages we need
		// to set up the path formatting a little different for each.  The CP
		// allows normal query strings but page URIs do not.
                
        $path  = ($this->path == '') ? $this->base_url.AMP.$this->qstr_var.'=' : $this->path;
        $slash = ($this->path == '') ? '' : '/';
    	    	
		// ----------------------------------------
        //  Determine the total number of pages
		// ----------------------------------------
        
        $num_pages = intval($this->total_count / $this->per_page);
        
		// ----------------------------------------
        //  Do we have an odd number of pages?
		// ----------------------------------------
                        
        // Use modulus to see if our division has a remainder.
        // If so, add one to our page number
        
        if ($this->total_count % $this->per_page) 
        {
            $num_pages++;
        }
        
		// ----------------------------------------
        //  Bail out if we only have one page
		// ----------------------------------------
        
        if ($num_pages == 1)
        {
            return;
        }
        
		// ----------------------------------------
        // Determine the current page number
		// ----------------------------------------
		
		// We'll round down the result, since certain combinations
		// can produce a fraction, messing up the links.
                  
		$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
                
		// ----------------------------------------
        //  Calculate the start and end numbers
		// ----------------------------------------

        // These determine which number to start and end the digit links with.
                        
        $start = (($this->cur_page - $this->max_links) > 0) ? $this->cur_page - ($this->max_links - 1) : 1;
        $end   = (($this->cur_page + $this->max_links) < $num_pages) ? $this->cur_page + $this->max_links : $num_pages;
        
        $output = '';
            
		// ----------------------------------------
        //  Render the "First" link
		// ----------------------------------------
                
        if  ($this->cur_page > $this->max_links)
        {
        	$first_link = ($this->first_url == '') ? $path : $this->first_url;
            $output .= '<a href="'.$first_link.'">'.$this->first_page.'</a>&nbsp;';
        }
        
		// ----------------------------------------
        //  Render the "previous" link
		// ----------------------------------------

        if  (($this->cur_page - $this->max_links) >= 0)
        {
        	$i = ($start * $this->per_page) - $this->per_page;
        	
			if ($this->path != '' AND $i == 0 AND REQ == 'CP') $i = '';
        
            $output .= '&nbsp;<a href="'.$path.$this->prefix.$i.$slash.'">&lt;</a>&nbsp;';
        }
        
		// ----------------------------------------
        //  Write the digit links
		// ----------------------------------------

        for ($loop = $start -1; $loop <= $end; $loop++) 
        {
			$i = ($loop * $this->per_page) - $this->per_page;
			
			if ($this->path != '' AND $i == 0 AND REQ == 'CP') $i = '';
		
			if ($i >= 0)
			{
				if ($this->cur_page == $loop)
				{
					$output .= '&nbsp;<b>'.$loop.'</b>'; // Current page
				}
				else
				{
					$output .= '&nbsp;<a href="'.$path.$this->prefix.$i.$slash.'">'.$loop.'</a>';
				}
			}
        } 
        
		// ----------------------------------------
        //  Render the "next" link
		// ----------------------------------------

        if ($this->cur_page < $num_pages)
        {  
            $output .= '&nbsp;<a href="'.$path.$this->prefix.($this->cur_page * $this->per_page).$slash.'">&gt;</a>';        
        }
        
		// ----------------------------------------
        //  Render the "Last" link
		// ----------------------------------------

        if (($this->cur_page + $this->max_links) < $num_pages)
        {
            $i = (($num_pages * $this->per_page) - $this->per_page);
        
            $output .= '&nbsp;&nbsp;<a href="'.$path.$this->prefix.$i.$slash.'">'.$this->last_page.'</a>';
        }
        
		// ----------------------------------------
        //  Return the result
		// ----------------------------------------
    
    	// Note: when using this class in public pages, the
    	// "previous" link can end up with a double slash in the
    	// penultimate link.  For that reason we will run the output
    	// through the "remove double slashes" function
        
        return $FNS->remove_double_slashes($output);                        
    }
    // END
}
// END CLASS
?>