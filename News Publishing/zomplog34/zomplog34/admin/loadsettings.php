<?

/* Don't change anything below here */

$settings = loadSettings($link,$table_settings);
$limit = $settings['max'];
$scroll = $settings['scroll'];
$date = date('YmdHis'); // UNIX timestamp
$userdate = $settings['date']; // to convert timestamp to user-formatting for date
?>