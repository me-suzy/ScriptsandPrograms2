<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: EditTableAction.php,v 1.10 2004/12/01 14:46:47 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class EditTableAction extends Action {
    
    protected $post;
    
    public function __construct($post = false) {
        parent::__construct();
        $this->post = $post;
    }
    
    public function handle() {
        
        if(!($this->ddl instanceof DDL)) {
            header("Location: index.php");
            return;
        }
        
        if($this->post) {
            $this->updateTable();
            return;
        }
        
        if(!isset($_GET['database']) || !isset($_GET['table'])) {
            header("Location: index.php");
            return;
        }
        
        $table = $this->ddl->getDatabase($_GET['database'])->getTable($_GET['table']);
        if(!$table) {
            header("Location: index.php");
            return;
        }
        
        $fields = $table->getFields();
        $rows = '';
        $i = 1;
        $r = 2;
        foreach ($fields as $field) {
            $rows .= '<tr><td align="center" class="row'.$r.'"><input name="delete[]" type="checkbox" id="delete[]" value="'.$field->getName().'" /></td>' . "\n";
            $rows .= '<td class="row'.$r.'">';
            
            if($field->isPrimaryKey()) {
                $rows .= ' <img src="webclient/images/keyicon.png" width="9" height="13" align="bottom" alt="Primary Key" />';
            }
            
            $rows .= ' <a href="?editField&database='.$_GET['database'].'&table='.$_GET['table'].'&field='.$field->getName().'" title="Edit this field">'.$field->getName().'</a>';
            
            if($field->isRequired()) {
            	$rows .= ' <img src="webclient/images/required.gif" width="12" height="12" align="bottom" alt="Required Field" />';
            }
            if($field->isForeignKey()) {
            	$rows .= ' <img src="webclient/images/foreignkeyicon.png" width="16" height="9" align="bottom" alt="Foreign Key of '.$field->getForeignTable().':'.$field->getForeignKey().'" />
            	<em><a href="?editTable&amp;database='.$_GET['database'].'&amp;table='.$field->getForeignTable().'">'.$field->getForeignTable().'</a>
            	: <a href="?editField&amp;database='.$_GET['database'].'&amp;table='.$field->getForeignTable().'&amp;field='.$field->getForeignKey(). '">'.$field->getForeignKey(). '</a></em>';
            }
            $rows .= '</td><td class="row'.$r.'" style="font-size:12px;">[ <a href="?addField&database='.$_GET['database'].'&table='.$_GET['table'].'&index='.$i.'">Add New Field After This</a> ]</td>' . "\n";
            
            $data = $field->getData();
            $caption = '';
        	if($data instanceof DDLNumericData || $data instanceof  DDLTextData ) {
        		if(!is_null($data->getSize())) {
        			$caption = "[" . $data->getSize() . "]";
        		}
        	}
            
            $rows .= '<td class="row'.$r.'">'.$data->getDataType().' '.$caption.'</td><td class="row'.$r.'">'.$field->getDescription().'</td></tr>';
            if($r == 2) $r = 1;
            else ($r = 2);
            $i++;
        }

        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $template->assign('databaseName', $_GET['database']);
        $template->assign('tableName', $_GET['table']);
        $template->assign('className', $table->getClassName() ? $table->getClassName() : '');
        $template->assign('superClassName', $table->getSuperClassName() ? $table->getSuperClassName() : '');
        $template->assign('description', $table->getDescription() ? $table->getDescription() : '');
        $template->assign('fieldRows', $rows);
        $this->assignSchema($template);
        $template->display('edittable.html');
    }
    
    private function updateTable() {
        if(!isset($_POST['database']) || !isset($_POST['table'])) {
            header("Location: index.php");
            return;
        }
        
        $table = $this->ddl->getDatabase($_POST['database'])->getTable($_POST['table']);
        if(!$table) {
            header("Location: index.php");
            return;
        }
        
        $table->setName($_POST['tableName']);
        if($_POST['className'] != '') {
            $table->setClassName($_POST['className']);
        }
        
        if($_POST['superClassName'] != '') {
            $table->setSuperClassName($_POST['superClassName']);
        }
        
        if($_POST['description'] != '') {
            $table->setDescription($_POST['description']);
        }
        
        
        if(isset($_POST['delete'])) {
            foreach ($_POST['delete'] as $delete) {
                $table->removeField($delete);
            }
        }
        
        $doc = new DOMDocument();
        $root = $doc->createElement('ddl');
        $root->setAttribute('version', '1.0');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
        $root = $doc->appendChild($root);
        
        foreach ($this->ddl->getDatabases() as $database) {
            $database->createXmlElement($doc);
        }
        
        $doc->save(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
        header("Location: index.php?editTable&database=".$_POST['database']."&table=".$_POST['tableName']);
    }
}
?>