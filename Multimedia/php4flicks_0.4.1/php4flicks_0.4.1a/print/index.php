<?
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/


require('../config/config.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	// build query from posted vars
	if(sizeof($_POST['medium']) < sizeof($cfg['medium']))
		// not all media selected; where clause necessary.
		$what = ' WHERE medium=\''.implode($_POST['medium'],'\' OR medium=\'').'\'';
	else 
		$what = '';
	$what .= ' ORDER BY '.$_POST['sortBy'][0].' '.$_POST['order'][0].', '.$_POST['sortBy'][1].' '.$_POST['order'][1];
	
	
	switch($cfg['pdfout']){
		case 'htmldoc':
			// create pdf file and send it to client
   			header('Content-Type: application/pdf');
    		header('Content-Disposition: attachment; filename='.$cfg['htmldoc_fname']);
    		passthru($cfg['htmldoc_path'].'/htmldoc -t pdf --quiet --jpeg --webpage --headfootsize 8 --datadir '.$cfg['htmldoc_path'].' http://127.0.0.1'.dirname($_SERVER['PHP_SELF']).'/print_htmldoc.php?what='.urlencode($what));
    		flush();
    		break;
    	case 'ezpdf':
    		?>
				<script type="text/javascript">
					location = window.open(".././print/print_ezpdf.php?what=<?php echo(rawurlencode($what)); ?>");
                	location = "./index.php";
				</script>
			<?
			break;
		default:
			?>
				<script type="text/javascript">
					alert('please configure the pdf export correctly.');
				</script>
			<?
			break;
	}
			


    
} else {
	// display html form
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Print</title>
		<link rel="stylesheet" type="text/css" href="../config/flicks.css"/>
		<!-- another ugly hack because microsoft thinks standards are not for them -->
		<!--[if IE]>
			<style>
			#footer{
				position:absolute;
				left:0px;
				bottom:0px;
			}		
			</style>
		<![endif]-->
		
		<script type="text/javascript"><!--
			stop = new Image();			stop.src = '../pics/stop.gif';
			stop_a = new Image(); 		stop_a.src = '../pics/stop_a.gif';

			go = new Image();			go.src = '../pics/go.gif';
			go_a = new Image(); 		go_a.src = '../pics/go_a.gif';
			
			function swap(imgID,imgObjName) {
				//imgID: old image, imgObjName: new image!
				document.images[imgID].src = eval(imgObjName + ".src");
			}
			
			var olda=2,oldb=0;
			function checkorder(a,b,which){
				//prevent user from doing queries like ORDER BY blah asc, blah desc
				if(a.value == b.value){
					if(which==0) //first column changed
						b.selectedIndex = olda;
					else		//2nd col
						b.selectedIndex = oldb;
				}
				olda = a.selectedIndex; oldb = b.selectedIndex; 
			}
		--></script>
	</head>
	
	<body style="overflow: hidden">
		<div id="header">Print movie list <a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank"><img alt="pdflogo" src="../pics/pdf.gif"/></a></div>
		<div id="mainpar">
		<form name="data" action="" method="post">
			<table id="restable">
				<tr>
					<td class="rowtitle">print...</td>
					<td colspan="5"><select name="medium[]" size="5" multiple="multiple" class="select">
						<?
						foreach($cfg['medium'] as $m)
							echo "<option value=\"$m\" selected=\"selected\">$m</option>";
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="rowtitle">sort&nbsp;by&nbsp;</td>
					<td><select name="sortBy[0]" class="select" onchange="checkorder(this,document.data['sortBy[1]'],0);">
						<option value="movies.name">title</option>
						<option value="cat">category</option>
						<option value="nr" selected="selected">number</option>
						<option value="year">year</option>
						<option value="lang">language</option>
						<option value="format">format</option>
						<option value="sound">sound</option>
						<option value="comment">comment</option>
						</select>
					</td>
					<td>
						<select name="order[0]" class="selectsmall">
						<option value="ASC">ASC</option>
						<option value="DESC">DESC</option>
						</select>
					</td>
					<td>,&nbsp;</td>
					<td>
						<select name="sortBy[1]" class="select" onchange="checkorder(document.data['sortBy[0]'],this,1);">
						<option value="movies.name" selected="selected">title</option>
						<option value="cat">category</option>
						<option value="nr">number</option>
						<option value="year">year</option>
						<option value="lang">language</option>
						<option value="format">format</option>
						<option value="sound">sound</option>
						<option value="comment">comment</option>
						</select>
					</td>
					<td>
						<select name="order[1]" class="selectsmall">
						<option value="ASC">ASC</option>
						<option value="DESC">DESC</option>
						</select>
					</td>
				</tr>
			</table>
		</form>
		</div>
		<div id="footer">
			<img id="stop" alt="stop" src="../pics/stop.gif" onmouseover="swap('stop','stop_a')" onmouseout="swap('stop','stop')" onclick="window.close();"/>
			<img id="go" alt="go" src="../pics/go.gif" onclick="document.data.submit();" onmouseover="swap('go','go_a')" onmouseout="swap('go','go')"/>&nbsp;
		</div>
	</body>
</html>

<?	} ?>

