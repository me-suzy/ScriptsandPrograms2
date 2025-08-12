<?php
/*
   +----------------------------------------------------------------------+
   | phpMyViews - mySQL query and view tool
   | http://phpmyviews.sourceforge.net/
   +----------------------------------------------------------------------+
   | Copyright (c) 2001-2003 by Wolfgang Ulmer
   +----------------------------------------------------------------------+
   | This program is free software; you can redistribute it and/or modify
   | it under the terms of the GNU General Public License as published by
   | the Free Software Foundation; either version 2 of the License, or
   | (at your option) any later version.
   |
   | This program is distributed in the hope that it will be useful, but
   | WITHOUT ANY WARRANTY; without even the implied warranty of
   | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
   | General Public License for more details.
   |
   | You should have received a copy of the GNU General Public License
   | along with this program; if not, write to the Free Software
   | Foundation, Inc., 59 Temple Place - Suite 330, Boston,
   | MA  02111-1307, USA.
   +----------------------------------------------------------------------+
   | Original Author: Wolfgang Ulmer (wulmer@users.sourceforge.net)
   +----------------------------------------------------------------------+
*/

function replace_post_variables($subject)
{
    global $_POST;

    if (strpos($subject,"$"))
    {
        preg_match_all('/\$\w*/',$subject,$pmv_matches);
        foreach ($pmv_matches[0] as $pmv_evar)
        {
            $pmv_evar=substr(trim($pmv_evar),1);
            if (isset($_POST[$pmv_evar]))
            {
                $pmv_temp = str_replace('"','\'',$_POST[$pmv_evar]);
                $subject = str_replace('$'.$pmv_evar,$pmv_temp,$subject);
            }
        }
    }
    return $subject;
}

function replace_browse_tag($input, $limit_var_text, $limit_var_value, $limit_max, $limit_step)
{
    if ($limit_var_value>0) 
    {
        $tag .= "<a href=\"".$PHP_SELF."?$limit_var_text=0\">&lt;&lt;&nbsp;</a>";
        $tag .= "<a href=\"".$PHP_SELF."?$limit_var_text=".($limit_var_value-$limit_step)."\">&lt;</a>";
    }
    else
        $tag .= "&lt;&lt;&nbsp;&lt;";

    $tag .= "&nbsp;";

    if ($limit_var_value<$limit_max-$limit_step) 
    {
        $tag .= "<a href=\"$PHP_SELF?$limit_var_text=".($limit_var_value+$limit_step)."\">&gt;</a>";
        $tag .= "<a href=\"$PHP_SELF?$limit_var_text=".($limit_max-$limit_max%$limit_step)."\">&gt;&gt;</a>";
    }
    else
        $tag .= "&gt;&nbsp;&gt;&gt;";
    
    return str_replace("<pmv:browse />",$tag,$input);
}


    $pmv_browse = false;
    $pmv_limit_var = 0;
    $pmv_sql_count = 0;
    $pmv_limit_step = 0;
    
    ### determine action
    if ($pmv_debug) { echo "<b>POST-Variablen</b><br />"; print_r($_POST); echo "<br />\n"; }
    if ($pmv_debug) { echo "<b>GET-Variablen</b><br />"; print_r($_GET); echo "<br />\n"; }
    if (isset($_POST['pmv_action']))
    {
        for($pmv_i=0;$pmv_i<=count($pmv);$pmv_i++)
        {
            if (isset($pmv[$_POST['pmv_action']]))
            {
                $pmv_action = $_POST['pmv_action'];
                break;
            }
            else
            {
                print "<b>ERROR:</b><br />\nunknown pmv_action '".$_POST['pmv_action']."'<br />\n";
                $pmv_action = 'default';
                break;
            }
        }
    }
    else if (isset($_GET['pmv_action']))
    {
        for($pmv_i=0;$pmv_i<=count($pmv);$pmv_i++)
        {
            if (isset($pmv[$_GET['pmv_action']]))
            {
                $pmv_action = $_GET['pmv_action'];
                break;
            }
            else
            {
                print "<b>ERROR:</b><br />\nunknown pmv_action '".$_GET['pmv_action']."'<br />\n";
                $pmv_action = 'default';
                break;
            }
        }
    }
    else
    {
        $pmv_action='default';
    }

    ### check if validation is requested
    $errors_occured = false;
    foreach ($pmv[$pmv_action] as $pmv_vali_action)
    {
        if (isset($pmv_vali_action['validate']))
        {
            if (file_exists($pmv_vali_action['validate']))
            {
                include($pmv_vali_action['validate']);
                $user_func = substr(basename($pmv_vali_action['validate']),0,strpos(basename($pmv_vali_action['validate']),'.'));
                if (($vali_return_array = call_user_func($user_func, $_POST)) != false)
                {
                    if ($errors_occured == false)
                        echo "<ul class=\"errorlist\">\n";
                    foreach ($vali_return_array as $error_desc)
                        echo "  <li class=\"error\">$error_desc</li>\n";
                    $errors_occured = true;
                }
            }
        }
    }
    if ($errors_occured == true)
    {
        echo "</ul>\n";
        return;
    }
  
    ### open database connection
    $link = mysql_connect($pmv_db_host, $pmv_db_user, $pmv_db_pass)
        or  die("Could not connect: " . mysql_error());

    foreach ($pmv[$pmv_action] as $pmv_action)
    {
        if (isset($pmv_action['sql']))
        {
            ### prepare SQL query (replace POST variables)
            $pmv_action['sql'] = replace_post_variables($pmv_action['sql']);

            ### SELECT-LIMIT browsing ?
            if ( (strpos($pmv_action['sql'], "SELECT")!==FALSE) && ( ($lpos=strpos($pmv_action['sql'], "LIMIT"))!==FALSE) )
            {
                $limit = substr($pmv_action['sql'], $lpos);
                $limit_var = substr($limit, strpos($limit, "$")+1, strpos($limit, ",")-strpos($limit, "$")-1);
                $pmv_limit_step = substr($limit, strpos($limit, ",")+1);
                if (!isset($$limit_var)) $$limit_var = 0;
                if ($$limit_var < 0) $$limit_var = 0;
                $pmv_action['sql'] = str_replace('$'.$limit_var, $$limit_var, $pmv_action['sql']);
                $pmv_browse = true;
                $pmv_limit_var = $limit_var;
            }
            
            ### execute SQL query
            mysql_select_db($pmv_db_name);
            if ($pmv_debug) echo "<b>SQL_Query:</b><br />\n".$pmv_action['sql']."<br />\n";
            if ($pmv_browse)
            {
                $count_sql = "SELECT COUNT(*) ".substr($pmv_action['sql'],strpos($pmv_action['sql'],"FROM"));
                $count_sql = preg_replace("/LIMIT [0-9]*,[0-9]*/","",$count_sql);
                $pmv_result = mysql_query($count_sql);
                $pmv_sql_count = (mysql_fetch_row($pmv_result));
                $pmv_sql_count = $pmv_sql_count[0];
            }
            $pmv_result = mysql_query($pmv_action['sql']);
            if ($pmv_result==null) { print("Error: ".mysql_error()); return; }

            ### output
            if ($pmv_browse) $pmv_action['pre'] = replace_browse_tag($pmv_action['pre'], $pmv_limit_var, $$pmv_limit_var, $pmv_sql_count, $pmv_limit_step);
            echo replace_post_variables($pmv_action['pre']);
            if (strlen(stristr($pmv_action['sql'],"SELECT"))>0)
            {
                while ($pmv_row = mysql_fetch_array($pmv_result, MYSQL_ASSOC)) 
                {
                    $pmv_output = $pmv_action['main'];
                    // replace variables with SQL data
                    foreach ($pmv_row as $pmv_assoc => $pmv_data)
                    {
                        $pmv_output = str_replace('$'.trim($pmv_assoc),$pmv_data,$pmv_output);
                    }
                    // replace variables with POST data
                    $pmv_output = replace_post_variables($pmv_output);  
                    echo $pmv_output;
                }
            }
            if ($pmv_browse) $pmv_action['post'] = replace_browse_tag($pmv_action['post'], $pmv_limit_var, $$pmv_limit_var, $pmv_sql_count, $pmv_limit_step);
            echo replace_post_variables($pmv_action['post']);
        }
        else // no sql statement
        {
            echo replace_post_variables($pmv_action['pre']);
            echo replace_post_variables($pmv_action['post']);
        }
    }
?>
