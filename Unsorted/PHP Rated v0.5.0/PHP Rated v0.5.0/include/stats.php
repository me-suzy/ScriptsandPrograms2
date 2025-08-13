<?

/*
 * $Id: stats.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$yesterday = date("YmdHis", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y")));
$last_week = date("YmdHis", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-7,date("Y")));
$last_month = date("YmdHis", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-31,date("Y")));
$pts_total_count = sql_result(sql_query("select sum(rating) as count from $tb_ratings"), 0, "count") + 0;
$pts_pd_count = sql_result(sql_query("select sum(rating) as count from $tb_ratings where timestamp > $yesterday"), 0, "count") + 0;
$pts_pw_count = sql_result(sql_query("select sum(rating) as count from $tb_ratings where timestamp > $last_week"), 0, "count") + 0;
$pts_pm_count = sql_result(sql_query("select sum(rating) as count from $tb_ratings where timestamp > $last_month"), 0, "count") + 0;
$ra_total_count = sql_result(sql_query("select count(*) as count from $tb_ratings"), 0, "count") + 0;
$ra_pd_count = sql_result(sql_query("select count(*) as count from $tb_ratings where timestamp > $yesterday"), 0, "count") + 0;
$ra_pw_count = sql_result(sql_query("select count(*) as count from $tb_ratings where timestamp > $last_week"), 0, "count") + 0;
$ra_pm_count = sql_result(sql_query("select count(*) as count from $tb_ratings where timestamp > $last_month"), 0, "count") + 0;
$cm_total_count = sql_result(sql_query("select count(*) as count from $tb_comments"), 0, "count") + 0;
$cm_pd_count = sql_result(sql_query("select count(*) as count from $tb_comments where timestamp > $yesterday"), 0, "count") + 0;
$cm_pw_count = sql_result(sql_query("select count(*) as count from $tb_comments where timestamp > $last_week"), 0, "count") + 0;
$cm_pm_count = sql_result(sql_query("select count(*) as count from $tb_comments where timestamp > $last_month"), 0, "count") + 0;
$su_total_count = sql_result(sql_query("select count(*) as count from $tb_users"), 0, "count") + 0;
$su_pd_count = sql_result(sql_query("select count(*) as count from $tb_users where signup > $yesterday"), 0, "count") + 0;
$su_pw_count = sql_result(sql_query("select count(*) as count from $tb_users where signup > $last_week"), 0, "count") + 0;
$su_pm_count = sql_result(sql_query("select count(*) as count from $tb_users where signup > $last_month"), 0, "count") + 0;
$title = "Site Stats";
$site_stats = template("site_stats");
eval("\$site_stats = \"$site_stats\";");
$content = $site_stats;
$final_output .= table($title, $content);

/*
 * $Id: stats.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>