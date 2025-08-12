<?php
/***************************************************************************
 *                          admin_statistic_se_log.php
 *                            -------------------
 *   begin                : today
 *   copyright            : (C) FR
 *   email                : fr@php-styles.com
 *
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
    $filename = basename(__FILE__);
    $module['statistic_se_log']['statistic_se_log_watch'] = append_sid($filename);

    return;
}

//
// Include required files, get $phpEx and check permissions
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);


//
// Mode setting
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$mode = "";
}


if ( $mode == "delete" )
     {
  		$sql = "DELETE FROM " . STATISTIC_SE_LOG_TABLE;

        	$result = $db->sql_query( $sql );
            if ( !$result )
                 {
            		message_die(GENERAL_ERROR, "Could not query search-engine logs.", "",__LINE__, __FILE__, $sql);
        	     }
	
            message_die(INFORMATION, $lang['se_log_delete_successful'] , "",__LINE__, __FILE__, $sql);
      
     }

        $sql = "SELECT *
                    FROM " . STATISTIC_SE_LOG_TABLE . "
              ORDER BY statistic_se_log_time DESC";

        $result = $db->sql_query( $sql );
        if ( !$result )
        {
            message_die(GENERAL_ERROR, "Could not query search-engine logs.", "",__LINE__, __FILE__, $sql);
        }

     $total_se_logs = 0;
     while( $row = $db->sql_fetchrow($result) )
     {
         $se_log_rowset[] = $row;
         $total_se_logs++;
     }

     $db->sql_freeresult($result);


// Display page

//    include('./page_header_admin.'.$phpEx);

    $template->set_filenames(array('body' => 'admin/admin_statistic_se_log_body.tpl'));

    $count_se_google_bot = 0;

    for($i = 0; $i < $total_se_logs; $i++)
    {


        if (ereg("Googlebot", $se_log_rowset[$i]['statistic_se_log_se_name']))
             {
                  $count_se_google_bot++;
             }

        $template->assign_block_vars('se_log_row', array(
            'SE_LOG_URL' => $se_log_rowset[$i]['statistic_se_log_file'],
		'U_SE_LOG_URL'=> append_sid($se_log_rowset[$i]['statistic_se_log_file']),
            'SE_LOG_NAME' => $se_log_rowset[$i]['statistic_se_log_se_name'],
            'SE_LOG_TIME' => create_date("m/d/Y - h:i:s", $se_log_rowset[$i]['statistic_se_log_time'], $board_config['board_timezone'])

            ));
    }

     $template->assign_vars(array(
            'L_SE_LOG_ADMIN' => $lang['se_log_admin'],
            'L_SE_LOG_ADMIN_EXPLAIN' => $lang['se_log_admin_explain'],
            'L_SE_LOG_NAME' => $lang['se_log_name'],
            'L_SE_LOG_URL' => $lang['se_log_url'],
            'L_SE_LOG_TIME' => $lang['se_log_time'],
		'L_SE_LOG_DELETE' => $lang['se_log_delete'],
		'U_SE_LOG_DELETE' => append_sid("admin_statistic_se_log.php?mode=delete"),
            'GOOGLE_COUNT'=> $count_se_google_bot
            ));


$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>