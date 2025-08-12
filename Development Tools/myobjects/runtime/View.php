<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: View.php,v 1.3 2004/11/02 09:39:29 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsRuntime
*/

/**
* The View interface should be implemented by the classes that will provide
* views for Mapable objects.
*
* @package MyObjectsRuntime
*/
interface View {
    /**
    * Returns the string view of the object
    */
    public function __toString();
    
    /**
    * Sets the model that will be used in the View
    *
    * @param Mapabele $model The model that will be used
    */
    public function setModel(Mapable $model);
}

/**
* Default view Class that demonstrates how a View class can be implemented
*
* This class generates an html table describing the object properties
* For each scalar property defined in the object a row is created in the table
*
* <code>
* $user = new User();
* $user->setName('Erdinc Yilmazel');
* $user->setEmail('erdinc@yilmazel.com');
* $view = new DefaultMapableView();
* echo $user->getView($view);
* </code>
* @see Mapable::getView
* @package MyObjectsRuntime
*/
class DefaultMapableView implements View {
    
    /**
    * @var The Mapable object that will be used as the model
    */
    protected $model;
    
    /**
    * Generates the table descibing the model and returns it
    *
    * @return string The view as a html table string
    */
    public function __toString() {
        if($this->model == null) {
            throw new ModelNotValidException();
        }
        
        return $this->processModel();
    }
    
    /**
    * Sets the Mapable object that will be used as a Model
    *
    * @param Mapable $model The Mapable object that will be used as a Model
    */
    public function setModel(Mapable $model) {
        $this->model = $model;
    }
    
    /**
    * Processes the Model object and generates the table
    *
    * @return string The generated table
    */
    protected function processModel() {
        $out = '<table cellspacing="0" cellpadding="0">' . "\n";
        foreach ($this->model as $propName => $propValue) {
            if(is_scalar($this->model->$propName) && $this->model->propertyType($propName)) {
                $out .= '<tr><td>' . $propName . ': </td><td>' . $propValue . '</td></tr>' . "\n";
            }
        }
        $out .= "</table>\n";
        return $out;
    }
}

/**
* View implementation that outputs the view as an xml string
*
* This class uses the XmlModel class to generate xml views of the strings
*
* <code>
* $user = new User();
* $user->setName('Erdinc Yilmazel');
* $user->setEmail('erdinc@yilmazel.com');
* $view = new XmlView();
* echo '<pre>' . $user->getView($view) . '</pre>';
* </code>
* @see Mapable::getView
* @package MyObjectsRuntime
*/
class XmlView implements View {
    /**
    * @var The Mapable object that will be used as the model
    */
    protected $model;
    
    /**
    * Generates the view of the Mapable model as an Xml string and returns
    * it
    */
    public function __toString() {
        if($this->model == null) {
            throw new ModelNotValidException();
        }
        
        return $this->processModel();
    }
    
    /**
    * Sets the Mapable object that will be used as a Model
    *
    * @param Mapable $model The Mapable object that will be used as a Model
    */
    public function setModel(Mapable $model) {
        $this->model = $model;
    }
    
    /**
    * Processes the Model object and generates the xml string
    *
    * @return string The generated xml string
    */
    private function processModel() {
        $doc = XmlModel::store($this->model);
        return $doc->saveXML();
    }
}
?>