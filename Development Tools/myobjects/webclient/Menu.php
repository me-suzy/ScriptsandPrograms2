<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: Menu.php,v 1.9 2004/11/18 23:05:01 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/


/**
* Creates a horizontal javascript menu using the MyGosumenu
*
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class HorizontalMenu {
    
    /**
    * @var string $id Html id for the menu
    */
    public $id;
    
    /**
    * @var MenuColumn[] Array $columns Columns
    */
    protected $columns;
    
    /**
    * Constructs a HorizontalMenu object
    *
    * @param string $id Html Id for the menu
    * @return void
    */
    public function __construct($id) {
        $this->id = $id;
        $this->columns = array();
    }
    
    /**
    * Adds the specified column to the menu
    *
    * @param MenuColumn $column The column object that will be added
    * @return void
    */
    public function addColumn(MenuColumn $column) {
        array_push($this->columns, $column);
    }
    
    /**
    * Returns the string representation of menu that will be embedded in Html
    *
    * @return string String representation for the menu
    */
    public function __toString() {
        $out = '<table cellspacing="0" cellpadding="0" id="'. $this->id .'" class="XulMenu"><tr>' . "\n";
        foreach ($this->columns as $column) {
            $out .= $column->__toString();
        }
        $out .= '</tr></table>' . "\n";
        return $out;
    }
}

/**
* Represents a column in the horizontal menu
*
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class MenuColumn {
    
    /**
    * @var string $columnName Name of the column
    */
    public $columnName;
    
    /**
    * @var MenuElement[] $rows The array of rows
    */
    protected $rows;
    
    /**
    * Constructs a new column element
    *
    * @param $columnName Name of the column
    * @return void
    */
    public function __construct($columnName) {
        $this->columnName = $columnName;
        $this->rows = array();
    }
    
    /**
    * Appends a new row at the bottom of the column
    *
    * @param MenuElement $row The row that will be added
    * @return void
    */
    public function appendRow(MenuElement $row) {
        array_push($this->rows, $row);
    }
    
    /**
    * Returns the string representation of the menu column
    *
    * @return string String representation of the menu column
    */
    public function __toString() {
        $out = '<td><a class="button" href="javascript:void(0)">'. $this->columnName .'</a>' . "\n";
        $out .= '<div class="section">' . "\n";
        foreach ($this->rows as $row) {
            $out .= $row->__toString();
        }
        $out .= "</div></td>\n";
        return $out;
    }
}

class MenuElement {
    public $elementName;
    public $link;
    protected $childs;
    
    public function __construct($elementName, $link = null) {
        $this->elementName = $elementName;
        $this->link = $link;
        $this->childs = array();
    }
    
    public function appendChild(MenuElement $child) {
        array_push($this->childs, $child);
    }
    
    public function __toString() {
        if(count($this->childs) > 0) {
            $out  = '<a class="item" href="javascript:void(0)">'. $this->elementName .'<img class="arrow" src="images/arrow1.gif" width="4" height="7" alt="" /></a>' . "\n";
            $out .= '<div class="section">' . "\n";
            foreach ($this->childs as $child) {
                $out .= $child->__toString();
            }
            $out .= "</div>\n";
            return $out;
        }
        else {
            if(is_null($this->link)) {
                $this->link = 'javascript:void(0)';
            }
            return '<a class="item" href="'. $this->link .'">'. $this->elementName .'</a>' . "\n";
        }
    }
}

class DynamicTree {
    protected $caption;
    protected $id;
    protected $childs;
    
    public function __construct($id, $caption) {
        $this->id = $id;
        $this->caption = $caption;
        $this->childs = array();
    }
    
    public function appendChild(TreeNode $child) {
        array_push($this->childs, $child);
    }
    
    public function getInit() {
        return '<script type="text/javascript">var '.$this->id.' = new DynamicTree("'.$this->id.'"); '.$this->id.'.foldersAsLinks = true; '.$this->id.'.icons = {"pkey":"webclient/images/fieldicon_pk.png"}; '.$this->id.'.init();</script>';
    }
    
    public function __toString() {
        $out = '<div class="DynamicTree">' . "\n";
        $out .= '<div class="server"><a href="?editServer" title="'. $this->caption .'">'. $this->caption ."</a></div>\n";
        $out .= '<div class="wrap" id="'. $this->id .'">' . "\n";
        
        foreach ($this->childs as $child) {
            $out .= $child->__toString();
        }
        
        $out .= "</div></div>\n";
        return $out;
    }
    
    public function loadDDL(DDL $ddl) {
        foreach ($ddl->getDatabases() as $database) {
            $caption = "Database: " . $database->getName();
            $editLink = '?editDatabase&amp;database=' . $database->getName();
            $databaseNode = new TreeNode($caption, $editLink);
            $index = 0;
            foreach ($database->getTables() as $table) {
            	$index++;
                $caption = "Table: " . $table->getName();
                $editLink = '?editTable&amp;database=' . $database->getName().'&amp;table='.$table->getName();
                $tableNode = new TreeNode($caption, $editLink);
				$index2 = 0;
                foreach ($table->getFields() as $field) {
                    $subclass = '';
                    if ($field->isPrimaryKey()) {
                        $subclass = 'pkey';
                    }
                	$index2++;
                	$data = $field->getData();
                    $caption = $field->getName() . " (" . $data->getDataType() . ")";
					
                	if($data instanceof DDLNumericData || $data instanceof  DDLTextData ) {
                		if(!is_null($data->getSize())) {
                			$caption .= "[" . $data->getSize() . "]";
                		}
                	}
                	
                    $fieldNode = new TreeNode($caption,
                    '?editField&amp;database=' . $database->getName().'&amp;table='.$table->getName() .
                    '&amp;field=' . $field->getName(), $subclass);
                    $tableNode->appendChild($fieldNode);
                }
                $tableNode->appendChild(new TreeNode('Add a new field', '?addField&amp;database=' . $database->getName() .'&amp;table='.$table->getName().'&amp;index='.$index2));
                $databaseNode->appendChild($tableNode);
            }
            $databaseNode->appendChild(new TreeNode('Add a new table', '?addTable&amp;database=' . $database->getName() .'&amp;index='.$index));
            $this->appendChild($databaseNode);
        }
        $addDatabase = new TreeNode("Add new database", "?addDatabase");
        $this->appendChild($addDatabase);
    }
}

class TreeNode {
    public $link;
    public $title;
    public $class;
    public $target;
    public $caption;
    protected $childs;
    
    public function __construct($caption, $link, $class = '', $title = null, $target = '_self') {
        $this->caption = $caption;
        $this->link = $link;
        $this->class = $class;
        $this->target = $target;
        if($title == null) {
            $this->title = $caption;
        } else {
            $this->title = $title;
        }
        
        $this->childs = array();
    }
    
    public function appendChild(TreeNode $child) {
        array_push($this->childs, $child);
    }
    
    public function __toString() {
        if(count($this->childs) > 0) {
            $out = '<div class="folder '. $this->class .'">';
            
            if($this->link != '') {
                $out .= '<a href="'. $this->link .'" title="'. $this->title .'" target="'. $this->target .'">';
            }
            $out .= $this->caption;
            
            if($this->link != '') {
                $out .= "</a>\n";
            }
            
            foreach ($this->childs as $child) {
                $out .= $child->__toString();
            }
            $out .= "</div>\n";
        } else {
            $out = '<div class="doc '. $this->class .'"><a href="'. $this->link .'" title="'. $this->title .'" target="'. $this->target .'">'. $this->caption .'</a></div>' . "\n";
        }
        return $out;
    }
}
?>