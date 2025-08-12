<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view.php');
require_once('basic_field.php');

class sys_view_statistics extends basic_view {

	function loadLanguage() {
		basic_view::loadLanguage();
		$this->loadLangFile('sys_view_statistics');
	}
	
	function titleBar() {
		return '<div class="metatitle">'.$this->shadowtext($this->gl('heading')).'</div>';
	}

	function viewStart() {
		return '<div class="metawindow">';
	}
	
	function viewEnd() {
		return '</div>';
	}

	function beforeForm() {
		return '<div class="metabox">';
	}
	
	function afterForm() {
		return '</div>';
	}
	
	function stats($site, $date1, $date2, $groupby, $sumwhat, $wherecol, $whereval, $col1_sort, $col2_sort) {
		if ($sumwhat == 'pageid') $col2_name = $this->gl('label_sum_pageid');
		if ($sumwhat == 'sessionid') $col2_name = $this->gl('label_sum_sessionid');
		if ($sumwhat == 'userid') $col2_name = $this->gl('label_sum_userid');
		if ($groupby == 'pageid') $col1_name = $this->gl('label_group_pageid');
		if ($groupby == 'ip') $col1_name = $this->gl('label_group_ip');
		if ($groupby == 'sessionid') $col1_name = $this->gl('label_group_sessionid');
		if ($groupby == 'userid') $col1_name = $this->gl('label_group_userid');
		if ($groupby == 'browser') $col1_name = $this->gl('label_group_browser');
		if ($groupby == 'browserlanguage') $col1_name = $this->gl('label_group_browserlanguage');
		if ($groupby == 'usercreated') $col1_name = $this->gl('label_group_usercreated');
		if ($groupby == 'referer') $col1_name = $this->gl('label_group_referer');
		if ($groupby == 'useragent') $col1_name = $this->gl('label_group_useragent');
		if ($groupby == 'host') $col1_name = $this->gl('label_group_host');
		if ($groupby == 'tld') $col1_name = $this->gl('label_group_tld');
		if ($groupby == 'date') $col1_name = $this->gl('label_group_tld');
		if ($groupby == 'dayname') $col1_name = $this->gl('label_group_dayname');
		if ($groupby == 'dayofmonth') $col1_name = $this->gl('label_group_dayofmonth');
		if ($groupby == 'monthname') $col1_name = $this->gl('label_group_monthname');
		if ($groupby == 'quarter') $col1_name = $this->gl('label_group_quarter');
		if ($groupby == 'year') $col1_name = $this->gl('label_group_year');
		if ($groupby == 'hour') $col1_name = $this->gl('label_group_hour');

		$cdate1 = $date1." 00:00:00";
		$cdate2 = $date2." 23:59:59";
	
		$group = $groupby;
		$set = 1;
		if (($groupby == 'dayname') OR ($groupby == 'monthname') OR ($groupby == 'quarter') OR ($groupby == 'hour') OR ($groupby == 'dayofmonth') OR ($groupby == 'year')) {
			$group = $groupby."(timestamp)";
			$set = 0;
		}
		if (($groupby == 'date')) {
			$group = "date_format(timestamp,'%Y-%m-%d')";
			$set = 0;
		}
		if ($groupby == 'pageid') {
			$part1 = ", document.name as name ";
			$part2 = "document, ";
			$part3 = " document.objectid = stats.pageid AND ";
			if ($col1_sort <> "") {
				$orderby = " ORDER BY document.name $col1_sort";
			}
		}

		if (!empty($wherecol)) {
			$where .= "$wherecol ='$whereval' AND ";
		}
		
		$sum = $sumwhat;
		if ($sumwhat == 'sessionid') $sum = ' distinct sessionid';
		if ($sumwhat == 'userid') $sum = ' distinct userid';
	
		$orderby = "";
		if ($col1_sort <> "") {
			$orderby = " ORDER BY samler $col1_sort";
		}
		if ($col2_sort <> "") {
			$orderby = " ORDER BY antal $col2_sort";
		}
		
		$baseurl = $this->callgui('sys','','','statistics','','','','stat=stat&date1='.$date1.'&date2='.$date2.'&groupby='.$groupby.'&sumwhat='.$sumwhat.'&wherecol='.$wherecol.'&whereval='.$whereval.'&ulang='.$_REQUEST['ulang']);
		$sort1_asc = '<A HREF="'.$baseurl.'&col1_sort=ASC">+</A>';
		$sort1_desc = '<A HREF="'.$baseurl.'&col1_sort=DESC">-</A>';
		$sort2_asc = '<A HREF="'.$baseurl.'&col2_sort=ASC">+</A>';
		$sort2_desc = '<A HREF="'.$baseurl.'&col2_sort=DESC">-</A>';

		ob_start();
		$db =& getdbconn();
		echo $this->gl('label_date').$date1 .$this->gl('label_to').$date2;
		if ($wherecol <> "") {
			echo "<BR>".$this->gl('label_limitto').$wherecol ." = ".$whereval;
		}
		echo "<BR>".$this->gl('label_groupby').$col1_name;
		echo "<BR>".$this->gl('label_sumby').$col2_name;
		
		$iplimit = '';
		if ($this->userhandler->getStatExclIp() != '')
			$iplimit = " AND ip NOT LIKE '".$this->userhandler->getStatExclIp()."%'";
			
		$ulanglimit = '';
		if (!empty($_REQUEST['ulang']))
			$ulanglimit = " AND userlanguage = '".$_REQUEST['ulang']."'";
		
		$sql = "select $group as samler, count($sum) as antal $part1 from $part2 document_statistics stats where $where $part3 stats.site='$site' AND timestamp >= '$cdate1' AND timestamp <= '$cdate2' $iplimit $ulanglimit group by $group $orderby";

		$res =& $db->query($sql);
		if (!$res) {
			fatalerror($sql, $db->errormsg());
		}
		?>
		<table class="metalist" style="width: 100%">
		<tr class="metalistheadrow"><td width="60%"><?php echo $col1_name ?> [<?php echo $sort1_asc ?>/<?php echo $sort1_desc ?>]</td><td align="right"><?php echo $col2_name ?> [<?php echo $sort2_asc ?>/<?php echo $sort2_desc ?>]</td></tr>
		<?php
		$z = 0;
		$totalsum = 0;
		while ($row = $res->fetchrow()) {
			$samler = $row['samler'];
			$totalsum = $totalsum + $row['antal'];
			if ($groupby == 'pageid') {
				$name = $db->getone("select name from document where objectid = ".$samler);
				$samler = ($name) ? $name : '[unknown/deleted document]';
			}
			if ($groupby == 'userid') {
				$name = $db->getone("select name from user where objectid = ".$samler);
				$samler = ($name) ? $name : '[unknown/deleted user]';
			}
			
			if ($groupby == 'userid') {
				$name = $db->getone('select name from user where objectid = ' . $samler);
				$name = ($name) ? $name : '[unknown/deleted user]';
			}
			
			if ($groupby == 'hour') {
				$samler = $samler.":00 - ".$samler.":59";
			}
	  	
	  	$r = $z % 2;
	  	$tdclass = ($r == 1) ? 'mlodd' : 'mleven';
			echo '<tr class="'.$tdclass.'"><td>'.$samler."</td><td align=right>". $row['antal']."</td></tr>";
			$z++;
		}

		echo '<tr class="metalistheadrow"><td>';
		if ($set == 1) {
			echo $this->gl('label_distinct').$res->numrows();
		}
		echo "</td><td align=right>SUM: ". $totalsum."</td></tr>";
		?>
		</table>
		
		<?php
		$theview = ob_get_contents();
		ob_end_clean();
		return $theview;
	}
	

	function showform($site) {
		ob_start();
		$db =& getdbconn();
		$date1 = $this->data['date1'];
		$date2 = $this->data['date2'];
		$groupby = $this->data['groupby'];
		$sumwhat = $this->data['sumwhat'];
		$wherecol = $this->data['wherecol'];
		$whereval = $this->data['whereval'];
		$ulang = $_REQUEST['ulang'];
		
		?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="statistik">
		<?php
		echo $this->returnMePost();
		?>
		<input type="hidden" name="stat" value="stat">
		<input type="hidden" name="wherecol" value="<?php echo $wherecol ?>">
		<input type="hidden" name="whereval" value="<?php echo $whereval ?>">
		<table>
		<tr>
		<td><?php echo $this->gl('label_count'); ?></td>
		<td><select name="sumwhat" style="width: 150px">
		<option value="pageid"><?php echo $this->gl('label_sum_pageid') ?></option>
		<option value="sessionid"><?php echo $this->gl('label_sum_sessionid') ?></option>
		<option value="userid"><?php echo $this->gl('label_sum_userid') ?></option>
		</select></td>
		</tr>
		<tr>
		<td><?php echo $this->gl('label_grouped'); ?></td>
		<td><select name="groupby" style="width: 150px">
		<option value="pageid" <?php if ($groupby == 'pageid') echo ' selected'; ?>><?php echo $this->gl('label_group_pageid') ?></option>
		<option value="ip" <?php if ($groupby == 'ip') echo ' selected'; ?>><?php echo $this->gl('label_group_ip') ?></option>
		<option value="sessionid" <?php if ($groupby == 'sessionid') echo ' selected'; ?>><?php echo $this->gl('label_group_sessionid') ?></option>
		<option value="userid" <?php if ($groupby == 'userid') echo ' selected'; ?>><?php echo $this->gl('label_group_userid') ?></option>
		<option value="browser" <?php if ($groupby == 'browser') echo ' selected'; ?>><?php echo $this->gl('label_group_browser') ?></option>
		<option value="browserlanguage" <?php if ($groupby == 'browserlanguage') echo ' selected'; ?>><?php echo $this->gl('label_group_browserlanguage') ?></option>
		<option value="referer" <?php if ($groupby == 'referer') echo ' selected'; ?>><?php echo $this->gl('label_group_referer') ?></option>
		<option value="useragent" <?php if ($groupby == 'useragent') echo ' selected'; ?>><?php echo $this->gl('label_group_useragent') ?></option>
		<option value="host" <?php if ($groupby == 'host') echo ' selected'; ?>><?php echo $this->gl('label_group_host') ?></option>
		<option value="tld" <?php if ($groupby == 'tld') echo ' selected'; ?>><?php echo $this->gl('label_group_tld') ?></option>
		<option value="hour">- <?php echo $this->gl('label_group_hour') ?></option>
		<option value="date">- <?php echo $this->gl('label_group_date') ?></option>
		<option value="dayname">- <?php echo $this->gl('label_group_dayname') ?></option>
		<option value="dayofmonth">- <?php echo $this->gl('label_group_dayofmonth') ?></option>
		<option value="monthname">- <?php echo $this->gl('label_group_monthname') ?></option>
		<option value="quarter">- <?php echo $this->gl('label_group_quarter') ?></option>
		<option value="year">- <?php echo $this->gl('label_group_year') ?></option>
		</select></td>
		</tr>
		<tr>
		<td><?php echo $this->gl('label_ulang'); ?></td>
		<td><select name="ulang" style="width: 150px">
			<?php
			$field = new basic_field($this);
			echo $field->listAllLanguages($ulang);
			?>
		</select></td>
		</tr>
		<tr>
		<td><?php echo $this->gl('label_fromdate') ?>&nbsp;&nbsp;</td>
		<td>
			<input type="text" name="date1" size="10" value="<?php echo $date1 ?>">
		</td>
		</tr>
		<tr>
		<td><?php echo $this->gl('label_todate') ?>&nbsp;&nbsp;</td>
		<td>
			<input type="text" name="date2" size="10" value="<?php echo $date2 ?>">
		</td>
		</tr>
		<tr>
		<td></td>
		<td><input value="<?php echo $this->gl('label_generate') ?>" type="button" class="mformsubmit" onclick="statistik.stat.value='stat'; statistik.submit(); return false;"></td>
		</tr>
		</table>
		</form>
		<?php
		$theview = ob_get_contents();
		ob_end_clean();
		return $theview;
	}

	function view() {
		$result .= $this->viewStart();
		$result .= $this->titleBar();
		$result .= $this->beforeForm();
		if (!isset($this->data['date1'])) $this->data['date1'] = date("Y-m-d",time()-2592000);
		if (!isset($this->data['date2'])) $this->data['date2'] = date("Y-m-d");
		$result .= $this->showform($this->userhandler->getSite());

		if ($this->data['stat'] == 'stat')
		$result .= $this->stats($this->userhandler->getSite(),$this->data['date1'],$this->data['date2'],$this->data['groupby'],$this->data['sumwhat'], $this->data['wherecol'], $this->data['whereval'], $this->data['col1_sort'], $this->data['col2_sort']);

		$result .= '<br>';
		$result .= $this->afterForm();
		$result .= $this->viewEnd();;
		return $result;
	}
}

?>