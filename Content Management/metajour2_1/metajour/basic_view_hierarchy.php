<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_collection.php');

class basic_view_hierarchy extends basic_collection {
	var $onclicktype = '';
	var $ondblclicktype = '';
	var $onclickview = 'combi';
	var $ondblclickview = 'combi';
	var $reorder = true;	/* true if objects can be reordered (dropped between other objects) */
	var $moveable = true;	/* true if objects can be moved (dropped on each other) */
	var $obj = null;
	var $listingcol = 'name';
	
	function basic_view_hierarchy() {
		$this->basic_collection();
		$this->menuwidth = 120;
	}

	function loadLanguage() {
		basic_collection::loadLanguage();
		$this->loadLangFile('basic_view_hierarchy');
	}
	
	function contextmenu_single() {
		if ($this->CanView('create')) $result = 'addMenuItem(new menuItem("'.$this->gl('context_createsub').'", "createsub", "code:parent.dialog.location.href=\'gui.php?view=create&otype='.$this->otype.'&_parentid=\' + o_id + \'&_ret=jstreereload\';"));';
		$result .= basic_collection::contextmenu_single();
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_category').'", "category", ""));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_filter').'", "filter", ""));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_advanced').'", "advanced", ""));';
		if ($this->CanView('properties')) 
			$result .= 'addMenuItem(new menuItem("Egenskaber", "properties", "code:'
			.$this->getModalDynamic(array('view'=>'properties','width'=>600,'height'=>600,'scroll'=>'yes')).'"));';
		return $result;
	}
	
	function contextmenu_multiple() {
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_category').'", "category", ""));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_filter').'", "filter", ""));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_advanced').'", "advanced", ""));';
		return $result;
	}
	
	function treelevel($parent, $level=0) {
		if (null == $this->obj) {
			$this->obj = owNew($this->otype);
		}
		
		$obj =& $this->obj;
		$obj->setlistaccess(true);
		$obj->listobjects($parent);
		$elementscount = $obj->elementscount;
		$elements = $obj->elements;
		$z = 0;
		$obj2 = owNew($this->otype);
		$obj2->readallobjectsbyobjectid($parent);
		
		while ($z < $elementscount) {
			$element =& $elements[$z];
			$tree =& $this->tree[$this->cnt];
			$tree[0]=$level;
			$tree[1]= $element[$this->listingcol];
			$beforename = "";
			$aftername = "";
			if (!isset($obj2->elements[$element['objectid']])) {
				$beforename = "<span style='color: #777777'>";
				$aftername = "</span>";
			}
			
			if ($element['active'] == 0) {
				$beforename = "<span class='click' style='color: #FF0000'>";
				$aftername = "</span>";
			
			}
			
			if ( (0 != $element['pageid']) AND (("" == $element[$this->listingcol]) OR (!isset($element[$this->listingcol]))) ) {
				$this->errorhandler->disable();
				$doc = owNew('document');
				$doc->setListaccess(true);
				$doc->readobject($element['pageid']);
				$this->errorhandler->enable();
				if ($doc) {
					$tree[1] = $doc->elements[0]['name'];
	      			unset($doc);
	      		}
	    	}
			
			if ($tree[1] == "") {
		  		$tree[1] = "[-]";
			}
			$tree[1] = $beforename . $tree[1] . $aftername;
	   		$tree[2]=$element['objectid'];
	   		#$tree[3]="dialog";
	   		$tree[4]=0;
	   		$tree[5]=$element['pageid'];
	   		$tree[6]=$element['object']['parentid'];
	   		$tree[7]=$element['object']['childorder'];
	   		#$tree[8] = $element;
	   		
	   		if ($tree[0] > $this->maxlevel) $this->maxlevel=$tree[0];
	   		$this->cnt++;
			if ($element['object']['haschild']) {
		   		$this->treelevel($element['objectid'], $level+1);
			}
			$z++;
		}
	}

	function dropbetween() {
		return '<tr 
		ondragenter="cancelEvent()" 
		ondragleave="style.backgroundColor = \'#F1F4FF\'; " 
		ondragover="style.backgroundColor = \'#000000\'; cancelEvent()" 
		ondrop="drop(\''.$this->tree[$this->cnt][6].'\',\''.$this->tree[$this->cnt][7].'\')"
		ondragstart="return false;" 
		style="height: 2px"><td colspan='.($this->maxlevel).'></td></tr>';
	}
	
	function drag() {
		return '
		ondragenter="cancelEvent()" 
		ondragover="style.backgroundColor = \'#000000\'; style.fontColor = \'#ffffff\'; cancelEvent()" 
		ondragleave="style.backgroundColor = \'#F1F4FF\'; style.textColor = \'#000000\'; cancelEvent()" 
	    ondrop="drop(\''.$this->tree[$this->cnt][2].'\',\''.'1'.'\')"
		ondragstart = "window.event.dataTransfer.setData(\'text\',\''.$this->tree[$this->cnt][6].'\' + \',\' + \''.$this->tree[$this->cnt][7].'\');"
		';
	}

	function onclick() {
		if ($this->onclickview) {
			if ($this->onclicktype == '') $this->onclicktype = $this->otype;
			return "o_id='".$this->tree[$this->cnt][2].
				"'; parent.dialog.location.href='".$this->callgui($this->onclicktype,$this->tree[$this->cnt][2],'',$this->onclickview,'',"jstreereload",$this->tree[$this->cnt][2])."'; return false;";
		} else {
			return "return false;";
		}
	}

	function ondblclick() {
		/*
		@todo should be relocated to basic_view_structureelement
		*/
		if ($this->ondblclicktype == '') $this->ondblclicktype = $this->otype;
		if ($this->tree[$this->cnt][5] != 0 && !$this->userhandler->getRevisionControl()) { /* a pageid is attached to this element */
			return "window.open('gui.php?view=editor&objectid=" . $this->tree[$this->cnt][5] . "','','top=0,left=0,width=970,height=670,toolbar=0,location=0,status=1,scrollbars=1,resizable=1'); return false;";
		} else { 
			return "parent.dialog.location.href='".$this->callgui($this->ondblclicktype,$this->tree[$this->cnt][2],'',$this->ondblclickview,'',"jstreereload",$this->tree[$this->cnt][2])."';
	       	return false;";
		}
	}

	function leaf() {
       	$result = '<td colspan="'.($this->maxlevel-$this->tree[$this->cnt][0]).'"><a id="cm" ';
       	if ($this->moveable) $result .= $this->drag();
       	$result .= ' onclick="'.$this->onclick().'" onmousedown="o_id=\''.$this->tree[$this->cnt][2].'\'" ondblclick="'.$this->ondblclick().'" href="#" target="dialog">'.$this->tree[$this->cnt][1].'</a></td>';
       	return $result;
	}

	function toponclick() {
		if ($this->onclicktype == '') $this->onclicktype = $this->otype;
		$obj = owNew($this->otype);
		$supertype = $obj->getsupertype();
		unset($obj);
		if ($supertype !=  '') {
						if ($_SESSION['guitemp'][$this->otype]['super_object']) {
			        return "
			        o_id ='".$_SESSION['guitemp'][$this->otype]['super_object']."';
			        parent.dialog.location.href='".$this->callgui(owGetDatatype($_SESSION['guitemp'][$this->otype]['super_object']),'','','list')."';
			        return false;";
			      } else {
			        return "
			        o_id ='0';
			        parent.dialog.location.href='".$this->callgui($supertype,'','','list')."';
			        return false;";
			      }
		} else {
		        return "
		        o_id ='0';
		       	parent.dialog.location.href='".$this->callgui($this->onclicktype,'','',$this->onclickview)."';
	        	return false;";
		}
	}

	function selectbox() {
		$obj = owNew($this->otype);
		$supertype = $obj->getsupertype();
		unset($obj);
		if ($supertype !=  '') {
		  	?>
			<table border="0" width="100%" cellspacing="0" cellpadding="0" class="liste">
			<tr class='tdhead'><td class='tdhead'><?php echo $this->gl('select_structure') ?> 
		  	<select name="name" style="width:175px;"  onChange="document.location.href = '<?php echo $_SERVER['PHP_SELF']?>?otype=<?php echo $this->otype; ?>&view=<?php echo $this->view; ?>&_sobj=' + this.options[this.selectedIndex].value">
			<?php
			$tplobj = owNew($supertype);
			$tplobj->setlistaccess(true);
			$tplobj->listobjects();
			$z = 0;
			while ($z < $tplobj->elementscount) {
				if ($_SESSION['guitemp'][$this->otype]['super_object'] == "") {
					$_SESSION['guitemp'][$this->otype]['super_object'] = $tplobj->elements[$z]['objectid'];
				}
		  		$selection = "";
		  		if ($tplobj->elements[$z]['objectid'] == $_SESSION['guitemp'][$this->otype]['super_object']) {
		  			$selection = " SELECTED";
		  		}
				echo '<option value="' . $tplobj->elements[$z]['objectid'] . '"'.$selection.'>' . $tplobj->elements[$z]['name'] . "\n";
				$z++;
			}
			?>
			</select></td></tr></table>
			<BR>
			<?php
		}
	}

	function topelement() {
		?>
	  	<strong><a id="cm" HREF="#" onmousedown="<?php echo $this->toponclick() ?>"><img src="image/folder.png" border="0"> :: TOP</A></strong>
		<?php
	}

	function gettree() {
		if (empty($_SESSION['guitemp'][$this->otype]['super_object']))
			$_SESSION['guitemp'][$this->otype]['super_object'] = 0;
		$this->treelevel($_SESSION['guitemp'][$this->otype]['super_object'],1);
	}

	function title() {
		return '<div class="metatitle">'.$this->shadowtext($this->gl('title').' :: '.$this->gl('name')).'</div>';
	}

	function view() {
		basic_collection::view();
		?>
		<SCRIPT LANGUAGE="JavaScript">
		function cancelEvent() { window.event.returnValue = false;}
		function drop(newparentid, newchildorder) {
			parent.dialog.location='<?php 
				echo $this->callguidynamic('move','jstreereload,'.$this->onclickview,'','jstreereload',$this->tree[$this->cnt][2]) 
				?>&moveobjectparams=' + newparentid + ',' + newchildorder + ',' + window.event.dataTransfer.getData("text");
		}
		</SCRIPT>
		<?php
		echo '<div class="metawindow">';
		echo $this->title();
		echo '<div style="padding-left: 2px; padding-right: 2px; padding-bottom: 2px;">';
		
		$p = @$_GET['p'];
		if(isset($_SERVER['PATH_INFO'])) {
			$script = $_SERVER['PATH_INFO'];
		} else {
			$script	= $_SERVER['SCRIPT_NAME'];
	  	}
	
	  	$img_expand   = "image/tree_expand.gif";
	  	$img_collapse = "image/tree_collapse.gif";
	  	$img_line     = "image/tree_vertline.gif";
	  	$img_split    = "image/tree_split.gif";
	  	$img_end      = "image/tree_end.gif";
	  	$img_leaf     = "image/tree_leaf.gif";
	  	$img_spc      = "image/tree_space.gif";
	
		$this->maxlevel=0;
		$this->cnt=0;	
	
		if (!empty($_REQUEST['_sobj'])) 
			$_SESSION['guitemp'][$this->otype]['super_object'] = $_REQUEST['_sobj'];
	
		$this->selectbox();
		echo '<div style="padding-top: 3px; background-color: #ffffff; border: 1px solid; border-color: ThreeDHighlight ThreeDDarkShadow ThreeDDarkShadow ThreeDHighlight;">';
		$this->topelement();
		$this->gettree();
			
		for ($i=0; $i<count($this->tree); $i++) {
			$expand[$i]=0;
			$visible[$i]=0;
			$levels[$i]=0;
		}
	
		/*********************************************/
		/*  Get Node numbers to expand               */
		/*********************************************/
		$explevels = ($p != '') ? $explevels = explode("|",$p) : array();
	
		$i=0;
		while($i<count($explevels)) {
			$expand[$explevels[$i]]=1;
			$i++;
		}
	
		/*********************************************/
		/*  Find last nodes of subtrees              */
		/*********************************************/
		$lastlevel=$this->maxlevel;
		for ($i=count($this->tree)-1; $i>=0; $i--) {
			if ( $this->tree[$i][0] < $lastlevel ) {
				for ($j=$this->tree[$i][0]+1; $j <= $this->maxlevel; $j++) {
					$levels[$j]=0;
				}
			}
			if ( $levels[$this->tree[$i][0]]==0 ) {
				$levels[$this->tree[$i][0]]=1;
				$this->tree[$i][4]=1;
			} else
				$this->tree[$i][4]=0;
		
			$lastlevel=$this->tree[$i][0];  
		}
	  
		/*********************************************/
		/*  Determine visible nodes                  */
		/*********************************************/
		// all root nodes are always visible
		for ($i=0; $i < count($this->tree); $i++) if ($this->tree[$i][0]==1) $visible[$i]=1;

		for ($i=0; $i < count($explevels); $i++) {
			$n=$explevels[$i];
			if ( ($visible[$n]==1) && ($expand[$n]==1) ) {
				$j=$n+1;
				while ( $this->tree[$j][0] > $this->tree[$n][0] ) {
					if ($this->tree[$j][0]==$this->tree[$n][0]+1) $visible[$j]=1;
					$j++;
				}
			}
		}
	  
		/*********************************************/
		/*  Output nicely formatted tree             */
		/*********************************************/
		for ($i=0; $i<$this->maxlevel; $i++) $levels[$i]=1;
	
		$this->maxlevel++;
	  
		echo "<table cellspacing=0 cellpadding=0 border=0 cols=".($this->maxlevel+3)." width=100%>\n";
		echo '<tr style="height: 2px">';
		for ($i=0; $i<$this->maxlevel; $i++) echo '<td width=16></td>';
		echo '<td width=100%></td></tr>'."\n";
		$this->cnt=0;
		while ($this->cnt<count($this->tree)) {
	    	if ($visible[$this->cnt]) {

				/****************************************/
	      		/* start new row                        */
	      		/****************************************/
				if ($this->reorder) echo $this->dropbetween();
				echo "<tr>";
	      
				/****************************************/
				/* vertical lines from higher levels    */
				/****************************************/
				$i=0;
				while ($i<$this->tree[$this->cnt][0]-1) {
					if ($levels[$i]==1)
						echo "<td><a name='$this->cnt'></a><img src=\"".$img_line."\"></td>";
					else
						echo "<td><a name='$this->cnt'></a><img src=\"".$img_spc."\"></td>";
					$i++;
				}
	      
				/****************************************/
				/* corner at end of subtree or t-split  */
				/****************************************/         
				if ($this->tree[$this->cnt][4]==1) {
					echo "<td><img src=\"".$img_end."\"></td>";
					$levels[$this->tree[$this->cnt][0]-1]=0;
				} else {
					echo "<td><img src=\"".$img_split."\"></td>";                  
					$levels[$this->tree[$this->cnt][0]-1]=1;    
				} 
	      
				/********************************************/
				/* Node (with subtree) or Leaf (no subtree) */
				/********************************************/
				if ($this->tree[$this->cnt+1][0]>$this->tree[$this->cnt][0]) {
	        
					/****************************************/
					/* Create expand/collapse parameters    */
					/****************************************/
					$i=0; $params="?p=";
					while($i<count($expand)) {
						if ( ($expand[$i]==1) && ($this->cnt!=$i) || ($expand[$i]==0 && $this->cnt==$i)) {
							$params=$params.$i;
							$params=$params."|";
						}
						$i++;
					}
	               
					if ($expand[$this->cnt]==0)
						echo "<td><a href=\"".$script.$params."&otype=".$this->otype."&view=".$this->view."#$this->cnt\"><img src=\"".$img_expand."\" border=no></a></td>";
					else
						echo "<td><a href=\"".$script.$params."&otype=".$this->otype."&view=".$this->view."#$this->cnt\"><img src=\"".$img_collapse."\" border=no></a></td>";
				} else {

					/*************************/
					/* Tree Leaf             */
					/*************************/
					echo "<td><img src=\"".$img_leaf."\"></td>";         
				}
	      
				/****************************************/
				/* output item text                     */
				/****************************************/
				if ($this->tree[$this->cnt][2]=="") {
					echo "<td colspan=".($this->maxlevel-$this->tree[$this->cnt][0]).">".$this->tree[$this->cnt][1]."</td>";
				} else {
					echo $this->leaf();
				}
	
				/****************************************/
				/* end row                              */
				/****************************************/
				echo "</tr>\n";      
			}
			$this->cnt++;    
		}
		echo "</table><br>\n";
		echo "</div>";  
		echo "</div>";  
		echo "</div>";  
	} 

}
?>
