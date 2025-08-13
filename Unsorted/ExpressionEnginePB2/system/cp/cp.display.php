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
 File: cp.display.php
-----------------------------------------------------
 Purpose: This class provides all the HTML dispaly
 elements used in the control panel.
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Display {
 
    var $title      	= '';    // Page title
    var $body       	= '';    // Main content area
    var $crumb      	= '';    // Breadcrumb.
    var $rcrumb     	= '';    // Right side breadcrumb
    var $crumbline  	= TRUE;  // Assigns whether to show the line below the breadcrumb
    var $show_crumb 	= TRUE;  // Assigns whether to show the breadcrumb
    var $crumb_ov   	= FALSE; // Crumb Override. Will prevent the "M" variable from getting auto-linked
    var $refresh    	= FALSE; // If set to a URL, the header will contain a <meta> refresh
    var $ref_rate   	= 0;     // Rate of refresh
    var	$body_props		= '';
    
    //-------------------------------------
    //  Constructor
    //-------------------------------------
    
    function Display()
    {
        define('AMP', '&amp;');
        define('BR',  '<br />');
        define('NL',  "\n");
        define('NBS', "&nbsp;");
    }
    // END 
   
  
    //-------------------------------------
    //  Set return data
    //-------------------------------------
  
    function set_return_data($title = '', $body = '', $crumb = '',  $rcrumb = '')
    {
        $this->title  =& $title;
        $this->body   =& $body;        
        $this->crumb  =& $crumb;
        $this->rcrumb =& $rcrumb;
    }  
    // END
 
 
    //-------------------------------------
    //  Group Access Verification
    //-------------------------------------    

    function allowed_group($which = '')
    {
        global $SESS;
        
        if ($which == '')
            return false;
            
        // Super Admins always have access
                    
        if ($SESS->userdata['group_id'] == 1)
        {
            return true;
        }

        if ($SESS->userdata[$which] !== 'y')
            return false;
        else
            return true;
    }
    // END
  

    //-------------------------------------
    // Control panel
    //-------------------------------------    

    function show_full_control_panel()
    {
        global $OUT;
                   
        $OUT->build_queue(
                             $this->html_header()
                            .$this->page_header()
                            .$this->page_navigation()
                            .$this->breadcrumb()
                            .$this->content()
                            .$this->copyright()
                            .$this->content_close()
                            .$this->html_footer()
                          );
    }
    // END    



    //-------------------------------------
    // Show restricted version of CP
    //-------------------------------------    

    function show_restricted_control_panel()
    {
        global $IN, $OUT, $SESS;
        
        $r = $this->html_header();
        
        // We treat the bookmarklet as a special case
        // and show the navigation links in the top right
        // side of the page
        
        if ($IN->GBL('BK') AND $SESS->userdata['admin_sess'] == 1)
        {
            $r .= $this->page_header(1);
        }
        else
        {
            $r .= $this->simple_header();
        }
        
        $r .= $this->content();
                          
        $r .= $this->copyright()
             .$this->content_close()
             .$this->html_footer();
    
        $OUT->build_queue($r);
    }
    // END    


    
    
    //-------------------------------------
    // HTML Header
    //-------------------------------------

    function html_header($title = '')
    {
        global $PREFS;
        
        if ($title == '')
            $title = &$this->title;
                
        $header =
  
        "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n\n".
        "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n\n".
        "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"".$PREFS->ini('xml_lang')."\">\n\n".   
        "<head>\n".
        "<title>".APP_NAME." | $title</title>\n\n".        
        "<meta http-equiv='content-type' content='text/html; charset=".$PREFS->ini('charset')."' />\n".
        "<meta name='MSSmartTagsPreventParsing' content='TRUE' />\n".
        "<meta http-equiv='expires' content='-1' />\n".
        "<meta http-equiv= 'pragma' content='no-cache' />\n";
        
        if ($this->refresh !== FALSE)
        {
            $header .= "<meta http-equiv=\"refresh\" content=\"".$this->ref_rate."; url=".$this->refresh."\">\n";
        }
        
        $header .=
     
        "<style type='text/css'>\n".
        $this->fetch_stylesheet()."\n\n".
        "</style>\n\n".
        "</head>\n\n".
        "<body{$this->body_props}>\n\n";
        
        return $header;
    }
    // END
    
   
    //-------------------------------------
    // Fetch CSS Stylesheet
    //-------------------------------------    

    function fetch_stylesheet()
    {
        global $PREFS, $OUT, $SESS, $LANG;        
        
        $theme = (! isset($SESS->userdata['theme']) || $SESS->userdata['theme'] == '') ? $PREFS->ini('cp_theme') : $SESS->userdata['theme']; 
        
        $file = PATH_THEMES.$theme.'.css';    
        
        if ( ! $fp = @fopen($file, 'rb'))
        {
            $file = PATH_THEMES.'default.css';    
        
            if ( ! $fp = @fopen($file, 'rb'))
            {
                return false;
            }
        }
        
        $theme = fread($fp, filesize($file)); 

        fclose($fp); 
        
        // Remove comments and spaces from CSS file
        $theme =& preg_replace("/\/\*.*?\*\//s", '', $theme);
        $theme =& preg_replace("/\}\s+/s", "}\n", $theme);

        return $theme;    
    }
    // END
   

    //-------------------------------------
    // Page Header
    //-------------------------------------    

    function page_header($pad = 0)
    {
        global $LANG, $SESS, $FNS, $PREFS;
        
        $pad = ($pad == 0) ? 'header' : 'simpleHeader';
        
		$qm = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';
        
        $r = "<div id='topBar'>\n"
             .$this->table('', '', '0', '100%')
             .$this->tr()
             .$this->td('helpLinks')
             .$this->div('helpLinksLeft')
             .$this->anchor($FNS->fetch_site_index().$qm.'', APP_NAME.$this->nbs(2).'v '.APP_VER)
             .$this->div_c()
             .$this->td_c()
             .$this->td('helpLinks');

        $r .= $this->fetch_quicklinks();   
        
        
        $doc_path = $PREFS->ini('doc_url');
                
		if ( ! ereg("/$", $doc_path)) 
			$doc_path .= '/';
                
        $r .= $this->anchor(BASE, $LANG->line('main_menu')).$this->nbs(3).'|'.$this->nbs(3)
             ."<a href='".$doc_path."' target='_blank'>".$LANG->line('user_guide').'</a>'.$this->nbs(3).'|'.$this->nbs(3)
             .$this->anchor(BASE.AMP.'C=logout', $LANG->line('logout'))
             .$this->td_c()
             .$this->tr_c()
             .$this->table_c()
             .$this->div_c()
             ."<div id='".$pad."'>&nbsp;</div>\n";

        return $r;
    }
    // END
  
  
    //-------------------------------------
    // Quicklinks
    //------------------------------------- 

    function fetch_quicklinks()
    {
        global $SESS, $FNS, $PREFS;
            
        if ( ! isset($SESS->userdata['quick_links']) || $SESS->userdata['quick_links'] == '')
        {
            return '';
        }
        
        $r = '';
                 
        foreach (explode("\n", $SESS->userdata['quick_links']) as $row)
        {                
            $x = explode('|', $row);
            
            $title = (isset($x['0'])) ? $x['0'] : '';
            $link  = (isset($x['1'])) ? $x['1'] : '';
            
			$qm = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';
    
            $r .= $this->anchor($FNS->fetch_site_index().$qm.'URL='.$link, $title, '', 1).$this->nbs(3).'|'.$this->nbs(3);
    
        }
            
        return $r;
    }  
    // END
      

    //-------------------------------------
    // Simple version of the Header
    //-------------------------------------    

    function simple_header()
    {
        global $LANG;
         
        return
        
        "<div id='topBar'>\n"
        .$this->table('', '', '0', '100%')
        .$this->table_qrow('helpLinks', $this->qdiv('helpLinksLeft', $this->anchor('', APP_NAME.$this->nbs(2).'v '.APP_VER)))
        .$this->table_c()
        .$this->div_c()
        ."<div id='simpleHeader'>&nbsp;</div>\n";
    }
    // END


    //-------------------------------------
    //  Main control panel navigation
    //-------------------------------------    

    function page_navigation()
    {
        global $IN, $DB, $SESS, $LANG;         
         
        // First we'll gather the navigation menu text in the selected language.
                
        $text = array(  'publish'     => $LANG->line('publish'),
                        'edit'        => $LANG->line('edit'),
                        'design'      => $LANG->line('design'),
                        'communicate' => $LANG->line('communicate'),
                        'modules'     => $LANG->line('modules'),
                        'my_account'  => $LANG->line('my_account'),
                        'admin'       => $LANG->line('admin')        
                      );
      
        // Next, we'll "equalize" the text length by adding non-breaking spaces
        // before/after each line so that they all match.  This enables the
        // navigation buttons to have the same lenghth.
        
         $longest = 0;   
        
         foreach ($text as $val)
         {
            $val = strlen($val);
         
            if ($val > $longest)
                $longest = $val;
         }
                
         foreach ($text as $key => $val)
         {
            $i = $longest - strlen($val);
            
            $i = ceil($i/2);
                            
            $val = $this->nbs($i).$val.$this->nbs($i);
                    
            $text[$key] = $val;
         }
  
                
        // Set the default permission for each menu item         
        // 0 = show   1 = locked
                
        $p_lock =  0;
        $e_lock =  0;
        $d_lock =  0;
        $c_lock =  0;
        $m_lock =  0;
        $y_lock =  0;
        $a_lock =  0;
        
        // We'll dynamically set the width of the right-most table cell
        // so that no matter how many menu items we show the width
        // of each item will be consistent.
        
        $width = 2;
        
        if ( ! $this->allowed_group('can_access_publish'))
        {
            $p_lock = 1;
            
            $width = $width + 14;
        }
        
        if ( ! $this->allowed_group('can_access_edit'))
        {
            $e_lock = 1;
            
            $width = $width + 14;
        }

        if ( ! $this->allowed_group('can_access_design'))
        {
            $d_lock = 1;
            
            $width = $width + 14;
        }
        
        if ( ! $this->allowed_group('can_access_comm'))
        {
            $c_lock = 1;
            
            $width = $width + 14;
        }
        
        if ( ! $this->allowed_group('can_access_modules'))
        {
            $m_lock = 1;
            
            $width = $width + 14;
        }
        
        if ( ! $this->allowed_group('can_access_admin'))
        {
            $a_lock = 1;
            
            $width = $width + 14;
        }
        
         
        // Define which nav item to show based on the group 
        // permission settings and render the finalized navigaion  
                 
        $r =  $this->table('', '0', '0', '100%')
             .$this->tr()
             .$this->td('navCell', '2%')
             .$this->div('cpNavOff')
             .$this->nbs()
             .$this->div_c()
             .$this->td_c();
            
            
        if ($p_lock == 0)
        {       
            $r .= $this->td('navCell');
            $r .= ($IN->GBL('C') != 'publish') ? $this->div('cpNavOff') : $this->div('cpNavOn');
            $r .= $this->anchor(BASE.AMP.'C=publish', $text['publish']).
                  $this->div_c().
                  $this->td_c();
        }
            
        if ($e_lock == 0)
        {        
            $r .= $this->td('navCell');
            $r .= ($IN->GBL('C') != 'edit') ? $this->div('cpNavOff') : $this->div('cpNavOn');
            $r .= $this->anchor(BASE.AMP.'C=edit', $text['edit']).
                  $this->div_c().
                  $this->td_c();
        }
            
        if ($d_lock == 0)
        {       
            $r .= $this->td('navCell');
            $r .= ($IN->GBL('C') != 'templates') ? $this->div('cpNavOff') : $this->div('cpNavOn');
            $r .= $this->anchor(BASE.AMP.'C=templates', $text['design']).
                  $this->div_c().
                  $this->td_c();
        }
        
        if ($c_lock == 0)
        {       
            $r .= $this->td('navCell');
            $r .= ($IN->GBL('C') != 'communicate') ? $this->div('cpNavOff') : $this->div('cpNavOn');
            $r .= $this->anchor(BASE.AMP.'C=communicate', $text['communicate']).
                  $this->div_c().
                  $this->td_c();
        }
        
        if ($m_lock == 0)
        {       
            $r .= $this->td('navCell');
            $r .= ($IN->GBL('C') != 'modules') ? $this->div('cpNavOff') : $this->div('cpNavOn');
            $r .= $this->anchor(BASE.AMP.'C=modules', $text['modules']).
                  $this->div_c().
                  $this->td_c();
        }
        
        // We only want the "MY ACCOUNT" tab highlighted if
        // the profile being viewed belongs to the logged in user
    
        if ($IN->GBL('C') != 'myaccount')
        {
            $tab = $this->div('cpNavOff');
        }
        else
        {
            $id = ( ! $IN->GBL('id', 'GP')) ? $SESS->userdata['member_id'] : $IN->GBL('id', 'GP');
                        
            if ($id != $SESS->userdata['member_id'])
            {
                $tab = $this->div('cpNavOff');
            }
            else
            {
                $tab = $this->div('cpNavOn');
            }
        }
    
        $r .= $this->td('navCell');
        $r .= $tab;
        $r .= $this->anchor(BASE.AMP.'C=myaccount', $text['my_account']).
              $this->div_c().
              $this->td_c();
        
            
        if ($a_lock == 0)
        {       
            $r .= $this->td('navCell');
            $r .= ($IN->GBL('C') != 'admin') ? $this->div('cpNavOff') : $this->div('cpNavOn');
            $r .= $this->anchor(BASE.AMP.'C=admin', $text['admin']).
                  $this->div_c().
                  $this->td_c();
        }
        
        $r .= $this->td('navCell', $width.'%').
              $this->div('cpNavOff').
              $this->nbs().
              $this->div_c().
              $this->td_c().
              $this->tr_c().
              $this->table_c().
              $this->nl(2);
            
        return $r;
    }
    // END 


    //-------------------------------------
    // Content
    //-------------------------------------    

    function content()
    {        
        return $this->nl()."<div id='content'>".$this->nl(2).$this->body.$this->nl(2);
    }
    // END
    
     
    //-------------------------------------
    // Breadcrumb
    //-------------------------------------    

    function breadcrumb()
    {
        global $IN, $PREFS, $SESS, $LANG;
        
        if ($this->show_crumb == FALSE)
        {   
            return;
        }
        
        $link = '';
        
        if ($C = $IN->GBL('C', 'GET'))
        {
            $link .= $this->anchor(BASE, $LANG->line('main_menu'));
        }
        
        // If the "M" variable exists in the GET query string, turn 
        // the variable into the next segment of the breadcrumb
        
        if ($IN->GBL('M') AND $this->crumb_ov == FALSE)
        {            
            // The $special variable let's us add additional data to the query string
            // There are a few occasions where this is necessary
            
            $special = '';
                        
            if ($IN->GBL('weblog_id', 'POST'))
            {
                $special = AMP.'weblog_id='.$IN->GBL('weblog_id', 'POST');
            }
            
            // Build the link
         
            $name = $C;
            
            if ($C == 'myaccount')
            {
                if ($id = $IN->GBL('id', 'GP'))
                {
                    if ($id != $SESS->userdata['member_id'])
                    {
                        $name = $LANG->line('user_account');
                        
                        $special = AMP.'id='.$id;
                    }
                    else
                    {
                        $name = $LANG->line('my_account');
                    }
                }
            }
         
            $link .= $this->nbs(2)."&#8250;".$this->nbs(2).$this->anchor(BASE.AMP.'C='.$C.$special, ucfirst($name));        
        }
        
        // $this->crumb indicates the page being currently viewed.
        // It does not need to be a link.
        
        if ($this->crumb != '')
        {
            $link .= $this->nbs(2)."&#8250;".$this->nbs(2).$this->crumb;
        }

        // This is the right side of the breadcrumb area.

        $data = ($this->rcrumb == '') ? "&nbsp;" : $this->rcrumb;
            
        if ($data == 'OFF')
        {
            $link = '&nbsp;';
            $data = '&nbsp;';
        }
        
        // Define the breadcrump CSS.  On all but the PUBLISH page we use the
        // version of the breadcrumb that has a bottom border
        
        if ($this->crumbline == TRUE)        
        {
            $ret = "<div id='breadcrumb'>";
        }
        else
        {
            $ret = "<div id='breadcrumbNoLine'>";
        }        
                
        $ret .= $this->table('', '0', '6', '100%');
        $ret .= $this->tr();
        $ret .= $this->table_qcell('defaultBold', $this->span('crumblinks').$link.$this->span_c());
        $ret .= $this->table_qcell('breadcrumbRight', $data);
        $ret .= $this->tr_c();
        $ret .= $this->table_c();
        $ret .= $this->div_c();
        
        return $ret;
    }
    // END

 
    //---------------------------------------
    // Adds "breadcrum" formatting to an item
    //---------------------------------------
 
    function crumb_item($item)
    {
        return $this->nbs(2)."&#8250;".$this->nbs(2).$item;
    } 
 

    //-------------------------------------
    // Required field indicator
    //-------------------------------------    

    function required($blurb = '')
    {
        global $LANG;
        
        if ($blurb == 1)
        {
            $blurb = "<span class='default'>".$this->nbs(2).$LANG->line('required_fields').'</span>';
        }
        elseif($blurb != '')
        {
            $blurb = "<span class='default'>".$this->nbs(2).$blurb.'</span>';
        }
    
        return "<span class='alert'>*</span>".$blurb.$this->nl();
    }
    // END


    //-------------------------------------
    // Content closing </div> tag
    //-------------------------------------    

    function content_close()
    {    
        return "</div>".$this->nl();
    }
    // END



    //-------------------------------------
    // Copyright
    //-------------------------------------    

    function copyright()
    {
        global $LANG, $PREFS, $FNS, $DB;
             
		$qm = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';

        return

        "<div class='copyright'>".$this->nl(2).
         $this->anchor($FNS->fetch_site_index().$qm.'', APP_NAME." ".APP_VER)." - &#169; ".$LANG->line('copyright')." 2004 - pMachine, Inc.".BR.$this->nl().
         str_replace("%x", "cp:elapsed_time", $LANG->line('page_rendered')).$this->nbs(3).
         str_replace("%x", $DB->q_count, $LANG->line('queries_executed')).$this->br().
		 $LANG->line('build').$this->nbs(2).APP_BUILD.$this->nl(2).
        "</div>".$this->nl();
    }
    // END
        


    //-------------------------------------
    // HTML Footer
    //-------------------------------------    

    function html_footer()
    {
        return $this->nl().'</body>'.$this->nl().'</html>';
    }
    // END


    //-------------------------------------
    // Error Message
    //-------------------------------------    

    function error_message($message = "", $n = 1)
    {
        global $LANG;
        
        $this->title = $LANG->line('error');
        
        $this->body = 
        
            $this->nl(2).
            "<div id='error'>".$this->nl(2).
            "<div class='errorheading'>".$LANG->line('error')."</div>".$this->nl(2).
            "<div class='errormessage'>".$message."";
            
       if ($n != 0)
           $this->body .= "<br /><br />".$this->nl(2)."<a href='javascript:history.go(-".$n.")' style='text-transform:uppercase;'>&#171; ".$LANG->line('back')."</a>";
            
        $this->body .= $this->div_c().$this->div_c();            
    }
    // END


    //-------------------------------------
    // Unauthorized access message
    //-------------------------------------    

    function no_access_message($message = '')
    {
        global $LANG;
        
        $this->title = $LANG->line('unauthorized');
        
        $msg = ($message == '') ? $LANG->line('unauthorized_access') : $message;
        
        $this->body = $this->qdiv('highlight', BR.$msg);    
    }
    // END



    //-------------------------------------
    // Paginate 
    //-------------------------------------    

    function pager($base_url = '', $total_count = '', $per_page = '', $cur_page = '', $qstr_var = '')
    {
        global $LANG;
        
        // Instantiate the "paginate" class.
  
		if ( ! class_exists('Paginate'))
		{
        	require PATH_CORE.'core.paginate'.EXT;
        }
        
        $PGR = new Paginate();
        
        $PGR->base_url     = BASE.AMP.$base_url;
        $PGR->total_count  = $total_count;
        $PGR->per_page     = $per_page;
        $PGR->cur_page     = $cur_page;
        $PGR->qstr_var     = $qstr_var;
        
        return $PGR->show_links();
    }
    // END



    //-------------------------------------
    // Div
    //-------------------------------------    

    function div($style='default', $align = '')
    {
        if ($align != '')
            $align = " align='{$align}'";
    
        return $this->nl()."<div class='{$style}'{$align}>".$this->nl();
    }
    // END


    //-------------------------------------
    // Div close
    //-------------------------------------    

    function div_c()
    {
        return $this->nl()."</div>".$this->nl();
    }
    // END


    //-------------------------------------
    // Quick div
    //-------------------------------------    

    function qdiv($style='', $data = '')
    {
        if ($style == '')
            $style = 'default';
    
        return $this->nl()."<div class='{$style}'>".$data.'</div>'.$this->nl();
    }
    // END



    //-------------------------------------
    // Span
    //-------------------------------------    

    function span($style='default')
    {
        return "<span class='{$style}'>".$this->nl();
    }
    // END 


    //-------------------------------------
    // Span close
    //-------------------------------------    

    function span_c($style='default')
    {
        return $this->nl()."</span>".$this->nl();
    }
    // END 


    //-------------------------------------
    // Quick span
    //-------------------------------------    

    function qspan($style='', $data = '')
    {
        if ($style == '')
            $style = 'default';
    
        return $this->nl()."<span class='{$style}'>".$data.'</span>'.$this->nl();
    }
    // END


    //-------------------------------------
    // Heading
    //-------------------------------------    

    function heading($data = '', $h = '1')
    {
        return $this->nl()."<h".$h.">".$data."</h".$h.">".$this->nl();
    }
    // END    


    // -------------------------------------------
    //    Anchor Tag
    // -------------------------------------------    
    
    function anchor($url, $name = "", $extra = '', $pop = FALSE)
    {
        if ($name == "" || $url == "")
            return false;
            
        if ($pop != FALSE)
        {
            $pop = " target=\"_blank\"";
        }
    
        return "<a href='{$url}' ".$extra.$pop.">$name</a>";
    }
    // END
    

    // -------------------------------------------
    //    Anchor - pop-up version
    // -------------------------------------------    
    
    function anchorpop($url, $name, $width='500', $height='480')
    {    
        return "<a href='#' onclick=\"window.open('{$url}', '_blank', 'width={$width},height={$height},scrollbars=yes,status=yes,screenx=0,screeny=0')\">$name</a>";
    }
    // END
    
    
    // -------------------------------------------
    //    Anchor - pop-up version - full page
    // -------------------------------------------    
    
    function pagepop($url, $name)
    {    
        return "<a href='#' onclick=\"window.open('{$url}', '_blank')\">$name</a>";
    }
    // END

    
    // -------------------------------------------
    //    Mailto Tag
    // -------------------------------------------    
    
    function mailto($email, $name = "")
    {
        if ($name == "") $name = $email;

        return "<a href='mailto:{$email}'>$name</a>";
    }
    // END



    // -------------------------------------------
    //   "Doc" link (pop-up window help links)
    // -------------------------------------------    
    
    function doc_link($info = '')
    {
    	global $PREFS;
    	
    	$prefs = array(
    					'type'		=>	'mini',
    					'page'		=>	'',
    					'title'		=>	"(<b>?</b>)",
    					'width'		=>	'800',
    					'height'	=>	'560'
    				);					

        if ( is_array($info))
        {
			foreach ($info as $key => $val)
			{
				if (isset($prefs[$key]))
				{	
					if ($key == 'title')
					{
						$prefs[$key] = '<b>'.$val.'</b>';
					}
					else
					{
						$prefs[$key] = $val;
					}
				}
			}
    	}
    	
        $doc_path = $PREFS->ini('doc_url');
        
        $doc_path = str_replace('index.html', '', $doc_path);
        
		if ( ! ereg("/$", $doc_path)) 
			$doc_path .= '/';
    
    	if ($prefs['type'] != 'mini')
    	{
    		return "<a href='".$doc_path.$prefs['page']."' target='_blank'>".$prefs['title']."</a>";	
    	}
    	else
    	{
        	return "<a href='#' onclick=\"window.open('".$doc_path.$prefs['page']."', '_blank', 'width=".$prefs['width'].",height=".$prefs['height'].",scrollbars=yes,status=yes,screenx=0,screeny=0')\">".$prefs['title']."</a>";
    	}
    }
    // END
    


    // -------------------------------------------
    //    <br /> Tags
    // -------------------------------------------    
    
    function br($num = 1)
    {
        return str_repeat("<br />\n", $num);
    }
    // END
    
    
    // -------------------------------------------
    //   "quick" <br /> tag with <div>
    // -------------------------------------------    
    
    function qbr($num = 1)
    {
        return $this->nl().'<div>'.str_repeat("<br />\n", $num).'</div>'.$this->nl();
    }
    // END


    // -------------------------------------------
    //   Item group
    // -------------------------------------------    
    
    function itemgroup($top = '', $bottom = '')
    {
        return $this->div('itemWrapper').
               $this->qdiv('itemTitle', $top).
               $bottom.
               $this->div_c();
    }
    // END


    // -------------------------------------------
    //   Newline characters
    // -------------------------------------------    
    
    function nl($num = 1)
    {
        return str_repeat("\n", $num);
    }
    // END

    
    // -------------------------------------------
    //    &nbsp; entity
    // -------------------------------------------    
    
    function nbs($num = 1)
    {
        return str_repeat("&nbsp;", $num);    
    }
    // END    
    


    // -------------------------------------------
    //    Table start
    // -------------------------------------------    
    
    function table($style='', $cellspacing='0', $cellpadding='0', $width='100%', $border='0', $align='')
    {
        $style   = ($style != '') ? " class='{$style}' " : '';
        $width   = ($width != '') ? " style='width:{$width}' " : '';
        $align   = ($align != '') ? " align='{$align}' " : '';
                                
        if ($border == '')      $border = 0;
        if ($cellspacing == '') $cellspacing = 0;
        if ($cellpadding == '') $cellpadding = 0;
        
        return $this->nl()."<table border='{$border}'  cellspacing='{$cellspacing}' cellpadding='{$cellpadding}'{$width}{$style}{$align}>".$this->nl();
    }
    // END    



    // -------------------------------------------
    //    Table row start
    // -------------------------------------------    
    
    function tr($style='')
    {
        return "<tr>";
    }
    // END
    

    
    // -------------------------------------------
    //    Table data cell
    // -------------------------------------------    
    
    function td($style='', $width='', $colspan='', $rowspan='', $valign = '')
    {
        if ($style  == '') 
            $style = 'default';
        
        if ($style != 'none')
        {
        	$style = " class='".$style."' ";
        }
        
        $width   = ($width   != '') ? " style='width:{$width}'" : '';
        $colspan = ($colspan != '') ? " colspan='{$colspan}'"   : '';
        $rowspan = ($rowspan != '') ? " rowspan='{$rowspan}'"   : '';
        $valign  = ($valign  != '') ? " valign='{$valign}'"     : '';
    
        return $this->nl()."<td ".$style.$width.$colspan.$rowspan.$valign.">".$this->nl();
    }
    // END    
    
    
    
    // -------------------------------------------
    //    Table cell close
    // -------------------------------------------    
    
    function td_c()
    {
        return $this->nl().'</td>';
    }
    // END
    
    // -------------------------------------------
    //    Table row close
    // -------------------------------------------    
    
    function tr_c()
    {
        return $this->nl().'</tr>'.$this->nl();
    }
    // END
    
    
    // -------------------------------------------
    //    Table close
    // -------------------------------------------    
    
    function table_c()
    {
        return '</table>'.$this->nl(2);
    }
    // END


    // -------------------------------------------
    //    Table "quick" row
    // -------------------------------------------    
    
    function table_qrow($style='', $data = '')
    {
        if (is_array($data))
        {
            $r = "<tr>";
            
            foreach($data as $val)
            {
                $r .=  $this->td($style).
                       $val.
                       $this->td_c();    
            }
            
            $r .= "</tr>".$this->nl();
            
            return $r;      
        }
        else
        {
            return
            
                "<tr>".
                $this->td($style).
                $data.
                $this->td_c().
                "</tr>".$this->nl();   
        }
    }
    // END
    


    // -------------------------------------------
    //    Table "quick" cell
    // -------------------------------------------

    function table_qcell($style = '', $data = '', $width = '', $valign = '')
    {
        if (is_array($data))
        {
            $r = '';
            
            foreach($data as $val)
            {
                $r .=  $this->td($style, $width, '', '', $valign).
                       $val.
                       $this->td_c();    
            }
            
            return $r;      
        }
        else
        {
            return
            
                $this->td($style, $width, '', '', $valign).
                $data.
                $this->td_c();    
        }
    }
    // END


    // -------------------------------------------
    //   Form declaration
    // -------------------------------------------    
    
    function form($action, $name = '', $method = 'post', $extras = '')
    {
        if ($name != '')
            $name = " name='{$name}' id='{$name}' ";
            
        if ($method != '')
            $method = 'post';
    
        return $this->nl()."<form method='{$method}' ".$name." action='".BASE.AMP.$action."' $extras>".$this->nl();
    }
    // END
    
    
    // -------------------------------------------
    //   Form close
    // -------------------------------------------    
    
    function form_c()
    {
        return "</form>".$this->nl();
    }
    // END
    

    // -------------------------------------------
    //   Input - hidden
    // -------------------------------------------    
    
    function input_hidden($name, $value='')
    {
        global $REGX;
    
        return "<div class='hidden'><input type='hidden' name='{$name}' value='".$REGX->form_prep($value)."' /></div>".$this->nl();
    }
    // END


    
    // -------------------------------------------
    //   Input - text
    // -------------------------------------------    
    
    function input_text($name, $value='', $size = '90', $maxl = '100', $style='input', $width='100%', $extra = '')
    {
        global $REGX;
            
        return "<input style='width:{$width}' type='text' name='{$name}' id='{$name}' value='".$REGX->form_prep($value)."' size='{$size}' maxlength='{$maxl}' class='{$style}' $extra />".$this->nl();
    }
    // END
 
    // -------------------------------------------
    //   Input - password
    // -------------------------------------------    
    
    function input_pass($name, $value='', $size = '20', $maxl = '100', $style='input', $width='100%')
    {        
        return "<input style='width:{$width}' type='password' name='{$name}' id='{$name}' value='{$value}' size='{$size}' maxlength='{$maxl}' class='{$style}' />".$this->nl();
    }
    // END


    // -------------------------------------------
    //   Input - textarea
    // -------------------------------------------    
    
    function input_textarea($name, $value='', $rows = '20', $style='textarea', $width='100%', $extra = '')
    {
        global $REGX;
        
        return "<textarea style='width:{$width};' name='{$name}' id='{$name}' cols='90' rows='{$rows}' class='{$style}' $extra>".$REGX->form_prep($value)."</textarea>".$this->nl();
    }
    // END


    // -------------------------------------------
    //   Input - pulldown - header
    // -------------------------------------------
    
    function input_select_header($name, $multi = '', $size=3)
    {
        if ($multi != '')
            $multi = " size='".$size."' multiple='multiple'";
            
        if ($multi == '')
            $class = 'select';
        else
            $class = 'multiselect';    
    
        return $this->nl()."<select name='{$name}' class='{$class}'{$multi}>".$this->nl();
    }
    // END

    // -------------------------------------------
    //   Input - pulldown 
    // -------------------------------------------    
    
    function input_select_option($value, $item, $selected = '')
    {    
        global $REGX;        
    
        $selected = ($selected != '') ? " selected='selected'" : '';
    
        return "<option value='".$value."'".$selected.">".$item."</option>".$this->nl();
    }
    // END



    // -------------------------------------------
    //   Input - pulldown - footer
    // -------------------------------------------    
    
    function input_select_footer()
    {    
        return "</select>".$this->nl();
    }
    // END




    // -------------------------------------------
    //   Input - checkbox
    // -------------------------------------------    
    
    function input_checkbox($name, $value='', $checked = '', $extra = '')
    {
        $checked = ($checked == '' || $checked == 'n') ? '' : "checked='checked'";
        
        return "<input class='checkbox' type='checkbox' name='{$name}' value='{$value}' {$checked}{$extra} />".$this->nl();
    }
    // END


    // -------------------------------------------
    //   Input - radio buttons
    // -------------------------------------------    
    
    function input_radio($name, $value='', $checked = 0, $extra = '')
    {
        $checked = ($checked == 0) ? '' : "checked='checked'";
    
        return "<input class='radio' type='radio' name='{$name}' value='{$value}' {$checked}{$extra} />".$this->nl();
    }
    // END


    // -------------------------------------------
    //   Input - submit
    // -------------------------------------------    
    
    function input_submit($value='', $name = '', $extra='')
    {    
        global $LANG;
        
        $value = ($value == '') ? $LANG->line('submit') : $value;
        $name  = ($name == '') ? '' : "name='".$name."'";
        
        if ($extra != '')
            $extra = ' '.$extra;
    
        return $this->nl()."<input $name type='submit' value='{$value}' class='submit'{$extra} />".$this->nl();
    }
    // END
    
    
    // -------------------------------------------
    //   JavaScript checkbox toggle code
    // ------------------------------------------- 
    
    // This lets us check/uncheck all checkboxes in a series

    function toggle()
    {
        ob_start();
    
        ?>
        <script language="javascript" type="text/javascript"> 
        <!--
    
        function toggle(thebutton)
        {
            if (thebutton.checked) 
            {
               val = true;
            }
            else
            {
               val = false;
            }
                        
            var len = document.target.elements.length;
        
            for (var i = 0; i < len; i++) 
            {
                var button = document.target.elements[i];
                
                var name_array = button.name.split("["); 
                
                if (name_array[0] == "toggle") 
                {
                    button.checked = val;
                }
            }
            
            document.target.toggleflag.checked = val;
        }
        
        //-->
        </script>
        <?php
    
        $buffer = ob_get_contents();
                
        ob_end_clean(); 
        
        return $buffer;
    } 
    // END 
    
}
// END CLASS
?>