<?php
/*
layout.php
Author : Thomas Whitecotton
Email  : admin@ciamosbase.com
Website: http://www.ciamosbase.com
*/
class siteLayout {
	
	var $layout = "";

	function dieMessage($sql,$error) {
		$this->layout = NULL;

		$this->pageHead();
		$content = $this->layout;
		$content .= '
	<body>
		<table>
			<tr>
				<td class="blue">Error</td>
			</tr>
			<tr>
				<td class="grey">Could not perform the SQL command: '.$sql.'<br />
								'.$error.'</td>
			</tr>
		</table>
	</body>
	</html>';
		return($content);
	}

	function pageHead() {
		$this->layout .= '<html>
<head>
<title>simplyDBtoXML</title>
<link href="includes/style.css" rel=StyleSheet type="text/css" media=screen>
</head>';
	}

	function pageTop() {
		$this->layout .= '
<body>
<table width=100% border=0>
	<tr>
		<td width=150 valign=top align=center nowrap>';
	}

	function makeMenu($title,$menu) {
		$this->layout .= '
			<p>
			<table border=0 width="95%" cellspacing=0 cellpadding=0 align=center>
				<tr>
					<td bgcolor="#000000">
						<table border=0 width="100%" cellspacing=1 cellpadding=4>
							<tr>
								<td class="blue">
									'.$title.'
								</td>
							</tr>
							<tr>
								<td class="grey">
									'.$menu.'	
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
	}

	function pageCenter($title,$content) {
		$this->layout .= '
		<td width=100% valign=top>
			<table border=0 width="95%" cellspacing=0 cellpadding=0 align=center>
				<tr>
					<td class="black">
						<table border=0 width="100%" cellspacing=1 cellpadding=4>
							<tr>
								<td class="blue">
									'.$title.'
								</td>
							</tr>
							<tr>
								<td class="grey">
									'.$content.'
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>';
	}

	function pageBottom() {
		$this->layout .= '
</body>
</html>';
	}

	function compile() {
		echo $this->layout;
	}
}
?>