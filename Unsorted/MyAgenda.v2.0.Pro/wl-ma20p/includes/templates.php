<?php
#############################################################################
# myAgenda v2.0																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
function get_file_content($v) {
	$fp = fopen($v, "r" );
	$str = fread($fp, filesize($v));
	fclose($fp);
	return $str;
}

function get_loop_tag($tag, $v) {
	$begin_tag = "<!-- $tag -->";
	$end_tag = "<!-- /$tag -->";
	$end_tag_len = strlen($end_tag);
	$begin_pos = strpos($v, $begin_tag);
	$end_pos = strpos($v, $end_tag) + $end_tag_len;
	if ($begin_pos != false and $end_pos != false) {
		$len_substr = $end_pos - $begin_pos;
		$str = substr($v, $begin_pos, $len_substr);
	}
	return $str;
}
?>