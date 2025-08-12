<?php
include("../admin/config.php");
include("$include_path/common.php");

if(@file_exists("$jpgraph_path/jpgraph.php")){
	include("$jpgraph_path/jpgraph.php");
	include("$jpgraph_path/jpgraph_pie.php");
	include("$jpgraph_path/jpgraph_pie3d.php");
} else {
	header ("Content-type: image/png");
	$im = @imagecreate(640, 480) or die ("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);
	$text_color = imagecolorallocate($im, 255, 0, 0);
	imagestring($im, 5, 15, 15, "Can't find $jpgraph_path/jpgraph.php, stopping..", $text_color);
	imagepng($im);
	exit();
}

$types = array('Active', 'Inactive');
$counts = array();
$colors = array($graph_grad_1, $graph_grad_8);

$sql = "
	select
		count(*) as count
	from
		$tb_users
	where
		image_status = 'approved'
";
$query = mysql_query($sql) or die(mysql_error());
$active = (int) mysql_result($query, 0, "count");
$counts[] = $active;

$sql = "
	select
		count(*) as count
	from
		$tb_users
";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_result($query, 0, "count")){
	$total = (int) mysql_result($query, 0, "count");
	$counts[] = $total - $active;
} else {
	$types = array("No users");
	$counts = array(1);
}

reset($types);
reset($counts);

$graph = new PieGraph(640,480,"auto",1440);
$graph->SetFrame(true, $alt1_bgcolor, 1);
$graph->SetColor($alt1_bgcolor);
$graph->legend->SetAbsPos(35, 40, 'right', 'top');
$p = new PiePlot3D($counts);
$p->SetSliceColors($colors);
$p->SetLegends($types);
$p->SetAngle(60);
$p->SetHeight(24);
$p->SetLabelType(PIE_VALUE_PER);
$p->value->SetFormat("%d%%");
$p->SetCenter(0.44,0.50);
$graph->Add($p);
$graph->Stroke();

?>