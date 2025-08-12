<?php
include("../admin/config.php");
include("$include_path/common.php");

if(@file_exists("$jpgraph_path/jpgraph.php")){
	include("$jpgraph_path/jpgraph.php");
	include("$jpgraph_path/jpgraph_line.php");
} else {
	header ("Content-type: image/png");
	$im = @imagecreate(640, 480) or die ("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);
	$text_color = imagecolorallocate($im, 255, 0, 0);
	imagestring($im, 5, 15, 15, "Can't find $jpgraph_path/jpgraph.php, stopping..", $text_color);
	imagepng($im);
	exit();
}

$datay = array();
$days_ago = array();
$tickLabels = array();

for($x=0; $x<=8; $x++){
	$days_ago[] = date("YmdHis", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$x,date("Y")));
	$tickLabels[] = $x;
}

rsort($days_ago);
reset($days_ago);

$high_y = 0;

for($x=0; $x<=7; $x++){
	$x2 = $x+1;
	$sql = "
		select
			count($tb_comments.id) as count
		from
			$tb_comments
		left join
			$tb_comment_threads
		on
			$tb_comments.id = $tb_comment_threads.comment_id
		where
			$tb_comments.user_id = '$_GET[i]'
		and
			$tb_comment_threads.timestamp < '$days_ago[$x]'
		and
			$tb_comment_threads.timestamp >= '$days_ago[$x2]'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$count = (int) mysql_result($query, 0, "count");
	$datay[] = $count;
	if($count > $high_y) $high_y = $count;
}

$graph = new Graph(640,480,"stats_im_user_comments_week_" . $_GET['i'] . ".png",1440);
$graph->SetMarginColor($alt1_bgcolor);
$graph->SetScale("textlin", 0, $high_y + ((int) ($high_y * .1)));
$graph->SetMargin(45, 15, 30, 25);
$graph->SetFrame(true, $alt1_bgcolor);
$graph->SetBackgroundGradient($graph_gradient_top_color, $graph_gradient_bottom_color, GRAD_HOR, BGRAD_PLOT);
$graph->tabtitle->Set(' .: Comments Past Week :. ' );
$graph->tabtitle->SetFont(FF_ARIAL, FS_BOLD, 10);
$graph->tabtitle->SetColor($graph_tab_text_color, $graph_tab_bg_color, $graph_axis_color_color);
$graph->tabtitle->SetCorner(1);
$graph->xgrid->Show();
$graph->xgrid->SetColor($graph_grid_color_color . '@0.5');
$graph->xaxis->SetTickLabels($tickLabels);
$graph->ygrid->SetColor($graph_grid_color_color . '@0.5');
$graph->xaxis->SetTitle('Days Ago ->', 'middle');
$graph->yaxis->SetTitle('Comments ->', 'middle');
$graph ->yaxis->SetTitleMargin(30);
$graph->xaxis->SetColor($graph_axis_color_color, $graph_axis_color_color);
$graph->yaxis->SetColor($graph_axis_color_color, $graph_axis_color_color);
$graph->xaxis->SetTickSide(SIDE_DOWN);
$graph->yaxis->SetTickSide(SIDE_LEFT);
$p = new LinePlot($datay);
$p->SetColor($graph_line_color);
$p->SetWeight(2);
$graph->Add($p);
$graph->Stroke();

?>