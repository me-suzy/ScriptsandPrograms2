<?

/*
 * $Id: styles.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$styles = <<<EOF
<style type="text/css">
body {
	font-family: $base_font;
	font-size: $medium_font;
	font-weight: normal;
	color: $base_font_color;
}
a {
	font-family: $base_font;
	font-size: $medium_font;
	font-weight: normal;
	color: $base_link_color;
}
a:active {
	font-family: $base_font;
	font-size: $medium_font;
	font-weight: normal;
	color: $base_link_color;
}
a:visited {
	font-family: $base_font;
	font-size: $medium_font;
	font-weight: normal;
	color: $base_link_color;
}
a:hover {
	font-family: $base_font;
	font-size: $medium_font;
	font-weight: normal;
	color: $hover_link_color;
	background-color: $hover_link_bg_color;
}
a.small {
	font-family: $base_font;
	font-size: $small_font;
	font-weight: normal;
	color: $base_font_color;
}
a:active.small {
	font-family: $base_font;
	font-size: $small_font;
	font-weight: normal;
	color: $base_font_color;
}
a:visited.small {
	font-family: $base_font;
	font-size: $small_font;
	font-weight: normal;
	color: $base_font_color;
}
a:hover.small {
	font-family: $base_font;
	font-size: $small_font;
	font-weight: normal;
	color: $hover_link_color;
	background-color: $hover_link_bg_color;
}
.title {
	font-family: $base_font;
	font-size: $large_font;
	font-weight: bold;
	color: $table_title_text_color;
}
.regular {
	font-family: $base_font;
	font-size: $medium_font;
	font-weight: normal;
	color: $base_font_color;
}
.bold {
	font-family: $base_font;
	font-size: $medium_font;
	font-weight: bold;
	color: $base_font_color;
}
.smallregular {
	font-family: $base_font;
	font-size: $small_font;
	font-weight: normal;
	color: $base_font_color;
}
.error {
	font-family: $base_font;
	font-size: $medium_font;
	font-weight: normal;
	color: $error_font_color;
}
.tb_header {
	color: $table_title_text_color;
	background-color: $table_title_color;
	background: url("$base_url/$tb_header_img");
	background-repeat: repeat-x;
}
input, textarea, select {
	font-family: $base_font;
	font-size: $medium_font;
	font-weight: normal;
	color: $base_font_color;
}
</style>
EOF;

/*
 * $Id: styles.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>