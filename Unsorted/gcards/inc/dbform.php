<?
class dbform
{
	var $table;
	var $primaryKey;
	var $columns = array();
	var $selectStmt;
	var $conn;
	var $editSelectSQL;
	var $titleClass = '';
	var $addTitle = 'Add New Record';
	var $editTitle = 'Edit Record';
	var $selectTitle = 'Current Records';
	var $limit = 10;
	var $enableEdit = true;
	var $enableAdd = true;
	var $enableView = true;
	var $enableDelete = true;
	var $customAddForm = false;
	var $customEditForm = false;
	var $customAddSQL = false;
	var $customUpdateSQL = false;
	var $customDeleteSQL = false;
	function dbform(&$conn, $table)
	{
		$this->conn = $conn;
		$this->table = $table;
		$columns = $this->conn->MetaColumns($this->table);
		// $this->viewArray($columns); // View columns array from ADODB
		foreach($columns as $column)
		{
			$this->columns[$column->name]['name'] = $column->name;
			$this->columns[$column->name]['title'] = ucfirst($column->name);
			$this->columns[$column->name]['visible'] = true;
			$this->columns[$column->name]['editable'] = true;
			$this->columns[$column->name]['insertable'] = true;
			$this->columns[$column->name]['value'] = false;
			$this->columns[$column->name]['width'] = 50;
			$this->columns[$column->name]['displaywidth'] = 40;
			$this->columns[$column->name]['widget'] = 'text';
			$this->columns[$column->name]['lookupstmt'] = false;
			$this->columns[$column->name]['timestamp'] = false;
			$this->columns[$column->name]['dropdown'] = false;
			if ($column->primary_key)
			{
				$this->primaryKey = $column->name;
				$this->columns[$column->name]['editable'] = false;
				$this->columns[$column->name]['insertable'] = false;
			}
		}
	}
	
	function setColumnTitle($column, $title)	{	$this->columns[$column]['title'] = $title;	}
	function setColumnWidth($column, $width)	{	$this->columns[$column]['width'] = $width;	}
	function setColumnWidget($column, $widget)	{	$this->columns[$column]['widget'] = $widget;	}
	function setColumnLookup($column, $lookup)	{	$this->columns[$column]['lookupstmt'] = $lookup;	}
	function setColumnTimestamp($column, $format)	{	$this->columns[$column]['timestamp'] = $format;	}
	function setLimit($limit)	{	$this->limit = $limit;	}
	function disableAdd()	{	$this->enableAdd = false;	}
	function disableEdit()	{	$this->enableEdit = false;	}
	function disableView()	{	$this->enableView = false;	}
	function disableDelete()	{	$this->enableDelete = false;	}
	function setCustomAddForm($page)	{	$this->customAddForm = $page;	}
	function setCustomEditForm($page)	{	$this->customEditForm = $page;	}
	function setColumnInsertValue($column, $value) { $this->columns[$column]['value'] = $value;	}
	function setTitleClass($class)	{	$this->titleClass = $class;	}
	function setAddTitle($title)	{	$this->addTitle = $title;	}
	function setEditTitle($title)	{	$this->editTitle = $title;	}
	function setSelectTitle($title)	{	$this->selectTitle = $title;	}
	function setColumnDropdown($column, $type, $data)
	{	
		$this->columns[$column]['dropdown'] = $data;
		$this->columns[$column]['widget'] = 'dropdown_'.$type;
	}
	
	function setCustomSQL($type, $sql)
	{
		switch($type)
		{
			case 'add':
				$this->customAddSQL[] = $sql;
				break;
			case 'delete':
				$this->customDeleteSQL[] = $sql;
				break;
				
			case 'update':
				$this->customUpdateSQL[] = $sql;
				break;
		}
	}
	
	function hideColumn($columns)
	{
		$columnsArray = explode("," , $columns);
		foreach($columnsArray as $column)
		{
			$column = trim($column);
			$this->columns[$column]['visible'] = false;
		}
	}
	
	function unEditable($columns)
	{
		$columnsArray = explode("," , $columns);
		foreach($columnsArray as $column)
		{
			$column = trim($column);
			$this->columns[$column]['editable'] = false;
		}
	}
	
	function unInsertable($columns)
	{
		$columnsArray = explode("," , $columns);
		foreach($columnsArray as $column)
		{
			$column = trim($column);
			$this->columns[$column]['insertable'] = false;
		}
	}
	
	function viewTable($whereClause='', $orderby='', $alternate0="EFEFEF", $alternate1="FFFFFF")
	{
		if ($this->enableView)
		{
			if (isset($_GET['row'])) $row = $_GET['row']; else $row = 0;
			$selectCount = "SELECT count(*) from ".$this->table." $whereClause";
			$selectStmt = "SELECT * from ".$this->table." $whereClause $orderby";
			$pager = new pager($this->conn, $row, $this->limit, $selectCount);
			$results = $pager->getrecords($this->conn, $selectStmt, true);
			?>
				<span class="<? echo $this->titleClass;?>"><? echo $this->selectTitle;?> (Showing <? echo $pager->first;?> - <? echo $pager->last;?> of <? echo $pager->numrows;?> Rows)</span><br><br>
				<table>
					<tr>
					<?
					foreach($this->columns as $column)
					{
						if ($column['visible'])
						{
							echo '<th>'.$column['title'].'</th>';
						}
					}
					if ($this->enableDelete || $this->enableEdit)
					{
						?>
							<th colspan="2">Action</th>
						</tr>
						<?
					}
					$i = 1;
					foreach($results as $result)
					{
						?>
						<tr>
						<?
						foreach ($this->columns as $column)
						{
							$columnValue = $result[$column['name']];
							if ($column['timestamp']) $columnValue = date($column['timestamp'], $columnValue);
							if ($column['lookupstmt']) $columnValue = $this->conn->GetOne($column['lookupstmt']."'".$columnValue."'");
							if (strlen($columnValue) > $column['displaywidth']) $columnValue = strip_tags(substr($columnValue, 0, $column['displaywidth'])).'...';
							else $columnValue = strip_tags($columnValue);
							if ($column['visible'])
							{
								echo '<td>'.$columnValue.'</td>';
							}
						}
						if ($this->enableEdit)
						{
							if (!$this->customEditForm)
							{
								?><td><a href="<? $_SERVER['PHP_SELF'];?>?action=edit&<? echo $this->primaryKey;?>=<? echo $result[$this->primaryKey];?>&row=<? echo $row;?>">[ Edit ]</a></td><?
							}
							else
							{
								?><td><a href="<? echo $this->customEditForm;?>?<? echo $this->primaryKey;?>=<? echo $result[$this->primaryKey];?>">[ Edit ]</a></td><?
							}
						}
						if ($this->enableDelete)
						{
						?>
							<td><a href="<? $_SERVER['PHP_SELF']?>?action=delete&<? echo $this->primaryKey;?>=<? echo $result[$this->primaryKey];?>&row=<? echo $row;?>">[ Delete ]</a></td>
						</tr>
						<?
						}
						$i++;
					}
					if ($pager->numrows > $pager->limit)
					{
					?>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><? $pager->showpagernav();?></td>
					</tr>
					<?
					}
			?>
				</table>
			<?
		}
	}
	
	function getEditSelectSQL()
	{
		foreach($this->columns as $column)
		{
			if ($column['editable']) $selectCols[] = $column['name'];
		}
		$selectColsImploded = implode(', ',$selectCols);
		$this->editSelectSQL = "SELECT ".$selectColsImploded." FROM ".$this->table." WHERE ".$this->primaryKey."=".$_REQUEST[$this->primaryKey];
	}
	
	function showEditForm()
	{
		$this->getEditSelectSQL();
		$recordSet = $this->conn->Execute($this->editSelectSQL);
		?>
		<span class="<? echo $this->titleClass;?>"><? echo $this->editTitle;?></span><br><br>
		<table>
			<form action="<? echo $_SERVER['PHP_SELF']?>" method="POST">
			<input type="hidden" name="action" value="update">
			<input type="hidden" name="<? echo $this->primaryKey;?>" value="<? echo $_GET[$this->primaryKey];?>">
			<?
			foreach($this->columns as $column)
			{
				if ($column['editable'])
				{
					?>
					<tr>
						<td><? echo $column['title'];?>:</td>
						<td>
					<?
					switch ($column['widget'])
					{
						case 'text':
							?><input type="text" name="<? echo $column['name'];?>" value="<? echo $recordSet->fields[$column['name']];?>" size="<? echo $column['width'];?>"><?
							break;
						case 'textarea':
							?><textarea name="<? echo $column['name'];?>" cols="60" rows="8"><? echo $recordSet->fields[$column['name']];?></textarea><?
							break;
						case 'dropdown_db':
							$rs = $this->conn->Execute($column['dropdown']);
							print $rs->GetMenu2($column['name'], $recordSet->fields[$column['name']]);
							$rs->Close();
							break;
						case 'dropdown_text':
							$valuesArray = explode(',',$column['dropdown']);
							?><select name="<? echo $column['name'];?>"><option></option><?
							foreach($valuesArray as $value)
							{
								$value = trim($value);
								?><option value="<? echo $value;?>" <? if ($value == $recordSet->fields[$column['name']]) echo 'selected';?>><? echo $value;?></option><?
							}
							?></select><?
							break;
					}
					?>
						</td>
					</tr>
					<?
				}
			}
			?>
			<tr>
				<td align="right" colspan="2"><input type="submit" value="Save"></td>
			</form>
			</tr>
			<form action="<? echo $_SERVER['PHP_SELF'];?>" method="POST">
			<tr>
				<td align="right" colspan="2"><input type="submit" value="Cancel"></td>
				</form>
			</tr>
			
		</table>
		<?
	}
	
	function showAddForm()
	{
		?>
		<span class="<? echo $this->titleClass;?>"><? echo $this->addTitle;?></span><br><br>
		<table>
			<form action="<? echo $_SERVER['PHP_SELF']?>" method="POST">
			<input type="hidden" name="action" value="add">
		<?
		foreach($this->columns as $column)
		{
			if ($column['insertable'] && !$column['value'])
			{
				?>
				<tr>
					<td><? echo $column['title'];?>:</td>
					<td>
				<?
				switch ($column['widget'])
				{
					case 'text':
						?><input type="text" name="<? echo $column['name'];?>" value="" size="<? echo $column['width'];?>"><?
						break;
					case 'textarea':
						?><textarea name="<? echo $column['name'];?>" cols="60" rows="8"></textarea><?
						break;
					case 'dropdown_db':
						$rs = $this->conn->Execute($column['dropdown']);
						print $rs->GetMenu2($column['name']);
						$rs->Close();
						break;
					case 'dropdown_text':
						$valuesArray = explode(',',$column['dropdown']);
						?><select name="<? echo $column['name'];?>"><option></option><?
						foreach($valuesArray as $value)
						{
							$value = trim($value);
							?><option value="<? echo $value;?>"><? echo $value;?></option><?
						}
						?></select><?
						break;
				}
				?>
					</td>
				</tr>
				<?
			}
			elseif ($column['insertable'] && $column['value'])
			{
				?>
				<input type="hidden" name="<? echo $column['name'];?>" value="<? echo $column['value'];?>">
				<?
			}
		}
		?>
			<tr>
				<td colspan="2" align="right"><input type="submit" value="Save"></td>
			</tr>
			</form>
		</table>
		<?
	}
	
	function showForms()
	{
		if (isset($_GET['action']) && ($_GET['action'] == 'edit') && $this->enableEdit) 
		{
			$this->showEditForm();
			echo '<br><br>';
		}
		elseif ($this->enableAdd)
		{
			if (!$this->customAddForm) $this->showAddForm();
			else
			{
				?>
				<a href="<? echo $this->customAddForm;?>">[ <? echo $this->addTitle;?> ]</a>
				<?
			}
			echo '<br><br>';
		}
	}
	
	function processForms()
	{
		if (!(isset($_REQUEST['action']) && (($_REQUEST['action'] == 'delete') || ($_REQUEST['action'] == 'update') || ($_REQUEST['action'] == 'add')))) return false;
		switch ($_REQUEST['action'])
		{
			case 'delete':
				$primaryKey = $this->primaryKey;
				if (!$this->customDeleteSQL)
				{
					$primKeyValue = $_GET[$this->primaryKey];
					$table = $this->table;
					$this->conn->Execute("DELETE FROM $table WHERE $primaryKey=$primKeyValue");
				}
				else
				{
					$$primaryKey = $_GET[$this->primaryKey];
					foreach($this->customDeleteSQL as $sql)
					{
						eval("\$sql = \"$sql\";");
						$this->conn->Execute($sql);
					}
				}
				break;
			case 'update':
				if (!$this->customUpdateSQL)
				{
					$this->getEditSelectSQL();
					$existingRecord = $this->conn->Execute($this->editSelectSQL);
					$newRecord = array();
					foreach($this->columns as $column)
					{
						if ($column['editable']) 
						{
							$newRecord[$column['name']] = $_POST[$column['name']];
						}
					}
					$this->conn->Execute($this->conn->GetUpdateSQL($existingRecord, $newRecord, false, true));
				}
				else
				{
					$primaryKey = $this->primaryKey;
					$$primaryKey = $_POST[$this->primaryKey];
					foreach($this->columns as $column)
					{
						if ($column['editable']) 
						{
							$$column['name'] = $_POST[$column['name']];
						}
					}
					foreach($this->customUpdateSQL as $sql)
					{
						eval("\$sql = \"$sql\";");
						$this->conn->Execute($sql);
					}					
				}
				break;
			case 'add':
				if (!$this->customAddSQL)
				{
					$newRecord = array();
					foreach($this->columns as $column)
					{
						if ($column['insertable'])
						{
							$selectCols[] = $column['name'];
							$newRecord[$column['name']] = $_POST[$column['name']];
						}
					}
					$selectColsImploded = implode(', ',$selectCols);
					$selectStmt = "SELECT ".$selectColsImploded." FROM ".$this->table." WHERE ".$this->primaryKey."= -1";
					$rs = $this->conn->Execute($selectStmt);
					$this->conn->Execute($this->conn->GetInsertSQL($rs, $newRecord, true));
				}
				else
				{
					foreach($this->columns as $column)
					{
						if ($column['insertable'])
						{
							$$column['name'] = $_POST[$column['name']];
						}
					}
					foreach($this->customAddSQL as $sql)
					{
						eval("\$sql = \"$sql\";");
						$this->conn->Execute($sql);
					}	
				}
				break;
		}
	}
	
	function viewArray($var)
	{
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}
}
?>