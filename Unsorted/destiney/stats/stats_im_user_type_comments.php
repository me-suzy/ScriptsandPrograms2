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

$types = array();
$counts = array();
$colors = array($graph_grad_1, $graph_grad_2, $graph_grad_3, $graph_grad_4, $graph_grad_5, $graph_grad_6, $graph_grad_7, $graph_grad_8);

$sql = "
	select
		$tb_user_types.user_type as type,
		count($tb_comments.id) as count
	from
		$tb_user_types
	left join
		$tb_users
	on
		$tb_user_types.id = $tb_users.user_type
	left join
		$tb_comments
	on
		$tb_comments.user_id = $tb_users.id
	group by
		$tb_user_types.user_type
	order by
		$tb_user_types.order_by
";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($query)){
	while($array = mysql_fetch_array($query)){
		if($array["count"] > 0){
			$types[] = $array["type"];
			$counts[] = $array["count"];
		}
	}
}

if(!sizeof($counts)){
	$types[] = "No users";
	$counts[] = 1;
}

reset($types);
reset($counts);

$graph = new PieGraph(640,480,"auto",1440);
$graph->SetFrame(true, $alt1_bgcolor, 1);
$graph->SetColor($alt1_bgcolor);
$graph->legend->SetAbsPos(8, 8, 'right', 'top');
$p = new PiePlot3D($counts);
$p->SetSliceColors($colors); 
$p->SetLegends($types);
$p->SetAngle(60);
$p->SetHeight(24);
$p->SetLabelType(PIE_VALUE_PER);
$p->value->SetFormat("%d%%");
$p->SetCenter(0.42,0.55);
$graph->Add($p);
$graph->Stroke();

?>