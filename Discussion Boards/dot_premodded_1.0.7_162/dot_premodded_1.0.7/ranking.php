<?php
define('IN_PHPBB', true); 
/**********************************************************************************
    ####################################################################
    ## Mod Title:    Ranking                                          ##
    ## Mod Version:  1.0.0                                            ##
    ## Author:       Matthias Suberg < fireball@fastfood-clan.de >    ##
    ## Date:         2002-04-12                                       ##
    ####################################################################

    ####################################################################
    ## Files of this Mod:                                             ##
    ##                                                                ##
    ## < ranking.php >; < ranking.tpl >; < lang_ranking >             ##
    ##                                                                ##
    ##                                                                ##
    ## Please copy the files to the following Directories:            ##
    ##                                                                ##
    ## < ranking.php > has to be placed in the phpBB2 Root-Directory  ##
    ##                                                                ##
    ## < ranking.tpl > has to be placed in youre Templatefolder       ##
    ##  e.g.: "./phpBB2/templates/subSilver/"                         ##
    ##                                                                ##
    ## < lang_ranking > has to be copied to the Languagefolder        ##
    ## e.g.: "./phpBB2/language/lang_english/"                        ##
    ##                                                                ##
    ## If you use subSilver as youre Template you could edit          ##
    ## the "overall_header.tpl" in the                                ##
    ## "./phpBB2/templates/subSilver/"- Folder to add                 ##
    ## a Link to the < ranking.php >.                                 ##
    ##                                                                ##
    ####################################################################

    ####################################################################
    ##                      Special Thanks to:                        ##
    ##                       Dragon Darkhawk                          ##
    ##          for his help with the php-Code of this Mod            ##
    ####################################################################

**********************************************************************************/

$phpbb_root_path = "./";
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_FAQ, $session_length);
init_userprefs($userdata);
//
// End session management
//

include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_ranking.' . $phpEx);


//
// Lets build a page ...
//

$page_title = $lang['Header'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx); //Forum Header landen


    $template->set_filenames(array(
                "body" => "ranking.tpl")
        );

        $sql = "SELECT * FROM " . RANKS_TABLE . "
                ORDER BY rank_min ASC, rank_special ASC";
        if( !$result = $db->sql_query($sql) )
        {
                message_die(GENERAL_ERROR, "Couldn't obtain ranks data", "", __LINE__, __FILE__, $sql);
        }
        $rank_count = $db->sql_numrows($result);

        $rank_rows = $db->sql_fetchrowset($result);

        $template->assign_vars(array(
                "L_RANKS_TITLE" => $lang['Header'],
        "L_IMAGE"=> $lang['RankImage'],
                "L_RANK" => $lang['Rank'],
                "L_RANK_MINIMUM" => $lang['Rank_minimum'],));

        for($i = 0; $i < $rank_count; $i++)
        {
                $rankimage = $rank_rows[$i]['rank_image'];
                $rank = $rank_rows[$i]['rank_title'];
                $rank_min = $rank_rows[$i]['rank_min'];
                $special_rank = $rank_rows[$i]['rank_special'];

                if( $special_rank == 1 )
                {
                        $rank_min = $rank_max = "-";
                }

                $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
                $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

                $rank_is_special = ( $special_rank ) ? $lang['Yes'] : $lang['No'];

                $template->assign_block_vars("ranks", array(
                        "ROW_COLOR" => "#" . $row_color,
                        "ROW_CLASS" => $row_class,
            "IMAGE"=> $rankimage,
                        "RANK" => $rank,
                        "RANK_MIN" => ($special_rank) ? $lang['Rank_special'] : $rank_min,));
        }



$template->pparse("body");

include($phpbb_root_path . 'includes/page_tail.'.$phpEx); //Forum Footer laden

?>