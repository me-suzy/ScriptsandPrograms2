<?php
	function mysql_first_data($q, $rowname)
	{
		global $database;
		$result = mysql_query ($q);
		if (mysql_num_rows($result) > 0)
		{
			$rowname = explode("|", $rowname);
			for ($i = 0; $i < count($rowname); $i++)
			{
				global $$rowname[$i];
				$$rowname[$i] = mysql_result ($result, 0, $rowname[$i]);
			}
		}	
	}
	
	function show_number($numberperpage, $limit, $alltotal, $currenttotal, $link)
	{
		$class_field = "h4";
		
		if (($limit + $currenttotal) > $numberperpage)
		{	
			$next_limit = $limit - $numberperpage;
			print ("<a href=\"$link&limit=$next_limit\" class=\"$class_field\">&lt;&lt; Previous</a>&nbsp;&nbsp;");
		}	
		
		$x = $alltotal;
		
		$limit_x = 0;
		$x_count = 1;
		while ($x > 0)
		{
			if ($limit != $limit_x)
				print ("|<a href=\"$link&limit=$limit_x\" class=\"$class_field\">$x_count</a>");
			else
				print ("|<i class=\"$class_field\">$x_count</i>");
			$x_count++; 
			$limit_x = $limit_x + $numberperpage;
			$x = $x - $numberperpage;
		}
		
		if ($alltotal > ($limit + $currenttotal))
		{	
			$next_limit = $limit + $currenttotal;
			print ("|&nbsp;&nbsp;<a href=\"$link&limit=$next_limit\" class=\"$class_field\">Next &gt;&gt;</a>\n");
		}	
		else
			print ("|");
		print ("<br><br>");
	}

	function htmlreserve ($txt)
	{

		$trans = get_html_translation_table(HTML_ENTITIES);
		$trans = array_flip($trans);
		$txt = strtr($txt, $trans);

		return $txt;
	}
	




?>