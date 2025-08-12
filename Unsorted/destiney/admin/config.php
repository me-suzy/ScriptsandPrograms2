<?php

// No trailing slashes
$base_path = "/var/www/html";
$include_path = "$base_path/include";
$cache_path = "$include_path/cache";
$image_path = "$base_path/images";
$font_path = "$base_path/fonts";
$base_url = "http://www.zaxscripts.com/destiney";

// MySQL db connection info
// Ask your web host if unsure
$dbhost = "localhost";
$dbuser = "zax_dwcms";
$dbpasswd = "dwcms";
$dbname = "zax_dwcms";

// Table names in case you need more name space
$tb_sessions = "sessions";
$tb_users = "users";
$tb_ratings = "ratings";
$tb_comments =	 "comments";
$tb_pms = "pms";
$tb_admin = "admin";
$tb_image_types = "image_types";
$tb_user_types = "user_types";
$tb_cookies = "cookies";
$tb_forums = "forums";
$tb_threads = "threads";
$tb_posts = "posts";
$tb_thread_views = "thread_views";
$tb_comment_views = "comment_views";
$tb_comment_threads = "comment_threads";

// Site  and owner info
$site_title = "Site Name";
$owner_name = "admin";
$owner_email = "webmaster@your_domain.com";

// Admin lockdown if you have a static ip
$lock_admin_with_owner_ip = false;
$owner_ip = "12.34.56.78";

// Dates that appear in sql queries
$mysql_dates = "%b %d, %Y %l:%i%p";
$signup_dates = "%M %Y";

// Seconds of inactivity a user is considered online
$online_expire = 900;

// Basic styles
// See include/styles.php for more info
$table_file = "tables.php";
$table_border_color = "yellow";
$table_title_color = "black";
$table_content_color = "white";
$page_bg_color = "#eeeeee";
$alt1_bgcolor = "#dddddd";
$alt2_bgcolor = "#eeeeee";
$base_font = "sans-serif";
$base_font_size = 13;
$base_font_color = "black";
$base_link_color = "black";
$hover_link_color = "yellow";
$hover_link_bg_color = "#996699";
$table_title_text_color = "#CC99CC";
$error_font_color = "red";

// Title image tag
$title_image = <<<EOF
<img src="$base_url/images/title.gif" width="261" height="32" border="0" alt=".: $site_title :." title=".: $site_title :." hspace="5" vspace="2">
EOF;

// Watermark images
// See admin/install.txt for more info
$watermark_images = false;
$watermark_fg_color_r = 238;
$watermark_fg_color_g = 238;
$watermark_fg_color_b = 238;
$watermark_bg_color_r = 153;
$watermark_bg_color_g = 102;
$watermark_bg_color_b = 153;
$watermark_shadow_color_r = 0;
$watermark_shadow_color_g = 0;
$watermark_shadow_color_b = 0;

// Show dynamic graphs
// Requires jpgraph software, not included
// See admin/INSTALL.TXT for more info
$show_graphs = false;
$jpgraph_path = "$base_path/jpgraph";
$graph_gradient_bottom_color = "#CB99CC";
$graph_gradient_top_color = "yellow";
$graph_tab_text_color = "#CC99CC";
$graph_tab_bg_color = "#996699";
$graph_grid_color_color = "gray";
$graph_axis_color_color = "black";
$graph_line_color = "black";
$graph_grad_1 = "#FFFF00";
$graph_grad_2 = "#F8F21A";
$graph_grad_3 = "#EFDF41";
$graph_grad_4 = "#E3C96C";
$graph_grad_5 = "#D9B497";
$graph_grad_6 = "#D2A2BA";
$graph_grad_7 = "#CB99CC";
$graph_grad_8 = "#D384D5";

// Used with view.php when voting
$speed_rate = 1;

// Mini-lists on the right side
$ml_order_types_by_rand = false;
$ml_use_min_rating = false;
$ml_min_rating = 5;
$ml_use_max_count = true;
$ml_count = 12;
$max_un_length = 14;

// Site stats on the left side
$show_site_stats = 1;

// Navigation links span, 3 or 4 is best
$admin_users_np = 4;

// Users per page
$admin_users_pp = 30;

// Column widths
$left_col_width = 156;
$right_col_width = 156;
$main_col_width = 660;

// Users can store images locally
$allow_local_image = 1;

// Users can store images remotely
// Watermarking does not work with remote images
$allow_remote_image = 0;

// Comments per page
$comments_per_page = 30;
$comments_replies_per_page = 10;
$comments_per_user_page = 10;

// Anonymous Comments
$allow_anonymous_comments = true;

// Anonymous Coward
$ac = "Anonymous Coward";

// Toplists users
$toplists_users_per_page = 30;

// Search results
$search_users_per_page = 30;

// Forum posts per page
$posts_per_page = 10;

// Navigation link span, 3 or 4 is best
$np = 3;

// Locallly store image parameters
$max_image_width = 640;
$max_image_height = 640;
$max_image_size = 102400;

// Age Restrictions
$low_age_limit = 18;
$high_age_limit = 88;

// Remove spaces and tabs from final html output
$clean_final_output = true;

// For development use mostly
$debug = false;

?>