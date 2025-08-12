<?
/*
.author {
	name: Vlad;
	surname: Roman;
	email: vlad@afian.com;
	web: http://www.afian.com;
}
*/


class iconList {

var $icons = array();
var $listProp = array();
var $iconProp = array("height" => "60", "width" => "75", "listExtra" => "");
var $checkboxName;
var $iconsCount = 0;
var $emptyListTxt = "List empty.";

	function iconlist($listProp, $checkboxName, $emptyListTxt) {
		$this->listProp[width] = $listProp[width];
		$this->listProp[height] = $listProp[height];
		$this->listProp[listExtra] = $listProp[listExtra];
		$this->checkboxName = $checkboxName;
		$this->emptyListTxt = $emptyListTxt;
	}
	
	function startList() {
?>
<div style="width:<?echo $this->listProp[width]?>;height:<?echo $this->listProp[height]?>;" class="iconList" <?echo $this->listProp[listExtra]?>>
<?
	}
//draw icons
	function drawIcons($type="icons") {
		if (sizeof($this->icons) == 0) {
echo $this->emptyListTxt;
		} else {
		for ($i = 0 ; $i < sizeof($this->icons); $i++) {
?>


<!-- ICON "<?echo $this->icons[$i][title];?>" -->
	<div class="icon" title="<?echo $this->icons[$i]['divtitle'];?>" <?echo $this->icons[$i]['divExtra']?>><?echo $this->icons[$i]['iconExtra']?><img src="<?echo $this->icons[$i]['img']?>" <?if ($type == "list") {?>align="middle"<?}?> title="<?echo $this->icons[$i]['alt']?>" <?echo $this->icons[$i]['imgExtra']?>><?if ($type == "icons") {?> <br> <?}?><span class="iconTitle"><a title="<?echo $this->icons[$i]['title'];?>" <?echo $this->icons[$i]['ancorExtra']?>><?echo $this->icons[$i]['title'];?></a></span><input type="checkbox" class="iconCheckbox" name="<?echo $this->checkboxName?>" value="<?echo $this->icons[$i]['inputValue']?>" <?echo $this->icons[$i]['inputExtra']?>></div>
<?if ($type == "list") {?>
<span class="objectInfo">
<?echo $this->icons[$i]['listViewDetails']?>
</span>
<br>
<?}?>

<!-- END ICON "<?echo $this->icons[$i][title];?>" -->


<?
		}
		} // sizeof(icons) != 0
	}

//set icon
	function setIcon($properties) {
			$this->iconsCount++;
			array_push($this->icons, $properties);
	}

//end table
	function endList() {
?>
<br>
</div>
<?
	}
}//end class
?>