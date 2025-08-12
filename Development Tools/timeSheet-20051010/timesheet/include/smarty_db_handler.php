<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/


function db_get_template ($tpl_name, &$tpl_source, &$smarty_obj)
{
    $Q="SELECT content 
		FROM templates
        WHERE name='$tpl_name' LIMIT 1";
    $sql = mysql_query($Q);
    if (mysql_num_rows($sql))
	{
        list($tpl_source) = mysql_fetch_row($sql);
        return true;
    } 
	else 
	{
        return false;
    }
}

function db_get_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj)
{
	$Q="SELECT stamp
      	FROM templates
        WHERE name='$tpl_name' LIMIT 1";
    $sql = mysql_query($Q);
	if (mysql_num_rows($sql))
	{
		list($tpl_timestamp) = mysql_fetch_row($sql);
        return true;
    } 
	else 
	{
        return false;
    }
}

function db_get_secure($tpl_name, &$smarty_obj)
{
    // assume all templates are secure
    return true;
}

function db_get_trusted($tpl_name, &$smarty_obj)
{
    // not used for templates
}

// register the resource name "db"
$X->register_resource("db", array("db_get_template",
                                       "db_get_timestamp",
                                       "db_get_secure",
                                       "db_get_trusted"));
?>
