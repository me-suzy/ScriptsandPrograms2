<?php
require_once ("db/db.inc");
require_once ("render/render_fields.inc");

define('FSTATE_UNKNOWN',1);
define('FSTATE_CHANGED',2);
define('FSTATE_SAVED',3);

define('ITEMSTATE_DELETED',-1);
define('ITEMSTATE_NORMAL',1);

define('FDOWNLOAD_ALWAYS',   0);
define('FDOWNLOAD_ONDEMAND', 1);
define('FDOWNLOAD_NEVER',    2); # reservated

/**
* default-initialisation of fields...
*
* - the initilization is done in the db->__construct()
* - actually this mapping just makes sure, that inital-values are valid
* 
*/
define('FINIT_REQUIRED','__FIELD_REQUIRED__');
define('FINIT_TODAY',   '__TODAY_');
define('FINIT_NOW',     '__TIMENOW__');
define('FINIT_NEVER',   '0000-00-00');
define('FINIT_CUR_USER',   '__CUR_USER__');
define('FINIT_RAND_MD5',   '__rand_md5__');






/**
* Field Baseclass for handling db-fields
*
* DbItems are objects representing db-elements. DbItems have fields of different types.
* Some of these field types directly refer to there counterpart in sql. Some provide additional
* layers of abstraction.
*  The Field-class tries to find special methods for rendering end convertion. Actually those
* methods (eg. XXX_renderToForm, where 'XXX' could be 'task') could be members of the derived
* classes, but refering to existing function allows to overwrite already defined classes by
* new themes.
*
* Mental note: I doubt that overwriting an existing function is easier than overwriting a class-method.
*
* @see      FieldHidden, FieldString
* @usedby   all derived DbItem-classes (task, person, etc.)
*
*/
class Field
{
    public      $type;                        #field
    public      $download=FDOWNLOAD_ALWAYS;   #loaded by default, on demand
    public      $name;
    public      $title;                     # label used in forms
    public      $default;

    public      $func_renderToForm;            # function name for rendering the field / automatically defined
    public      $func_parseForm;            # functino name for parse entered data / automatically defined
    public      $func_renderListHead;
    public      $func_renderListRow;
    public      $func_getFormElement;
    public      $view_in_forms;             # TODO: 1-alwayys, 2-details, etc.
    public      $view_in_lists;             # do we see this field in lists?

    /**
    * constructor
    *
    * @param assoc array
    */
    public function __construct($args=NULL)
    {
        #--- set parameters ------
        if(!$args) {
            throw new myException("Can't construct a field without name parameters");
        }
        foreach($args as $key=>$value) {
            empty($this->$key);     # cause php-notification if undefined property
            $this->$key= $value;
        }


        #--- try to automatically assign functions for rendering...
        if($this->view_in_forms) {
            foreach(array(
            '_renderToForm',
            '_parseForm',
            '_getFormElement'
            ) as $fn_append) {
                $fname= $this->type.$fn_append;
                $fn= 'func'.$fn_append;
                if(!$this->$fn) {
                    if(function_exists($fname)) {
                        $this->$fn= $fname;
                    }
                }
            }
        }
        if($this->view_in_lists) {
            foreach(array('_renderListHead','_renderListRow') as $fn_append) {
                $fname= $this->type.$fn_append;
                $fn= 'func'.$fn_append;
                if(!$this->$fn) {
                    if(function_exists($fname)) {
                        $this->$fn= $fname;
                    }
                }
                else {
                    throw new RendermyException("'$fname' is not defined");
                }
            }
        }

        #--- some checks ---
        if(!$this->name) {
            throw new myException("Can't construct a field without name parameters");
        }
        if($this->download != FDOWNLOAD_ALWAYS && $this->download != FDOWNLOAD_ONDEMAND && $this->download != FDOWNLOAD_NEVER) {
            throw new myException("invalid value for download: $this->download");
        }

        #--- try to figure title ----
        if(!$this->title) {
            $this->title= ucwords(str_replace('_',' ',$this->name));
        }
    }

    #---------------------------------------------------------------------
    # converts a string into a valid value for database
    #---------------------------------------------------------------------
    public function value2db($value=FALSE)
    {
        return $value;
    }

    #------------------------------------------------------------------------
    # converts values from db into meaningful string-formats
    #------------------------------------------------------------------------
    public function db2value($value=FALSE)
    {
        return $value;
    }

    public function render2form(&$obj)
    {
        if(isset($this->func_renderToForm)) {
            $fn= $this->func_renderToForm;
            return $fn(&$this,&$obj);
        }
        return false;                   # TODO: add warning
    }

    public function parseForm(&$obj)
    {
        if(isset($this->func_parseForm)) {
            $fn= $this->func_parseForm;
            return $fn(&$this, &$obj);
        }
        return false;                   # TODO: add warning
    }

    /**
    * calls custom-function to return appropropriete form-element
    */
    public function getFormElement(&$obj)
    {
        if(isset($this->func_getFormElement)) {
            $fn= $this->func_getFormElement;
            return $fn(&$this, &$obj);
        }
        else {
            return new Form_CustomHTML('');
        }
    }
}

#================================================================================================================
class FieldHidden extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->default= 0;
        parent::__construct($args);
        $this->view_in_forms=true;
    }
}

#================================================================================================================
class FieldInternal extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->default= 0;
        parent::__construct($args);
        $this->view_in_forms=false;
        $this->view_in_lists=false;
    }
}

#================================================================================================================
class FieldString extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->view_in_forms=true;
        $this->view_in_lists=true;
        parent::__construct($args);
    }
}

#================================================================================================================
class FieldPassword extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->view_in_forms=true;
        $this->view_in_lists=false;
        parent::__construct($args);
    }
}

#================================================================================================================
class FieldBool extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->view_in_forms=true;
        parent::__construct($args);
    }
}

#================================================================================================================
class FieldDate extends Field {
    public function __construct($args=NULL)
    {
        $this->type=__class__;
        $this->view_in_forms=true;
        $this->view_in_lists=true;
        parent::__construct($args);
    }

    #---------------------------------------------------------------------
    # converts a string into a valid value for database
    #---------------------------------------------------------------------
    public function value2db($value=FALSE)
    {
        $value= preg_replace("/(Mon|Tue|Wen|Thu|Fri|Sat|Sun),\s/i","",$value);
        ### format DD.MM.YYYY ###
        if(preg_match("/\b(\d?\d)\.(\d?\d)\.(\d\d\d\d)\b/",$value,$matches)) {
            if(count($matches)==4) {
                $value=$matches[3].'-'.$matches[2].'-'.$matches[1];
            }
            else {
                global $PM;
                $PM->message="WARNING '$value' isn't a known format for date.";
            }
        }
        return $value;
    }

    #------------------------------------------------------------------------
    # converts values from db into meaningful string-formats
    #------------------------------------------------------------------------
    public function db2value($value=FALSE)
    {
        if($value=="0000-00-00") {
            $value="-";
        }
        else {
            $datetime= strtotime($value)+12*60*60;
            $value= gmdate("D, d.m.Y",$datetime);
        }
        return $value;
    }
}

#================================================================================================================
class FieldTime extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->view_in_forms=true;
        $this->view_in_lists=true;
        parent::__construct($args);
    }
}

#================================================================================================================
class FieldPercentage extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->view_in_forms=true;
        $this->view_in_lists=true;
        parent::__construct($args);
    }
}

#================================================================================================================
class FieldDatetime extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->view_in_forms=true;
        $this->view_in_lists=true;
        parent::__construct($args);
    }
}

#================================================================================================================
class FieldInt extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->view_in_forms=true;
        $this->view_in_lists=true;
        parent::__construct($args);
    }
}

#================================================================================================================
class FieldOption extends Field {
    public $options;
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->view_in_forms=true;
        $this->view_in_lists=true;
        $this->options=array();
        parent::__construct($args);
    }
}

#================================================================================================================
class FieldText extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        $this->view_in_forms=true;
        parent::__construct($args);
    }
}

#================================================================================================================
class FieldProject extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        parent::__construct($args);
    }
}

#================================================================================================================
class FieldTask extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        parent::__construct($args);
    }
}
#================================================================================================================
class FieldUser extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        parent::__construct($args);
    }
}
#================================================================================================================
class FieldCompany extends Field {
    public function __construct($args=NULL) {
        $this->type=__class__;
        parent::__construct($args);
    }
}

//====================================================================
// DbItem
// - handles connection of objects to database
//====================================================================
class DbItem {
    public $id=-1;                 # all items need to have an id
    public $_type;              # name of table like "user", etc. Needed to be set by derived class
    public $fields;             # reference to global assoc. array of Field-Objects
    private $field_states;      # assoc. array of field-names and field states. Required for lazy loading.


    #-------------------------------------------------
    # constructor
    #-------------------------------------------------
    public function __construct($id = false)
    {
        if(@!$this->_type) {
            throw new myException("Constructing DbItem requires this->_type to be set");
        }

        #--- create assoc. arroy of field names to speed up __get---
        $this->field_states=array();
        foreach($this->fields as $f) {
            $this->field_states[$f->name]=FSTATE_UNKNOWN;
            $tmp_name=$f->name;

            #--- set default-values ---
            $def_value=$f->default;
            if($def_value === FINIT_NOW) {
                $def_value=date("Y-m-d H:i:s");
            }
            else if($def_value === FINIT_TODAY) {
                $def_value=date("Y-m-d");
            }
            else if($def_value === FINIT_RAND_MD5) {
                 $def_value= md5(time().microtime(). rand(12312,123213). rand(234423,123213)); #@@@ those number don't look very nice
            }
            else if($def_value === FINIT_CUR_USER) {
                global $auth;
                if($auth->cur_user) {
                    $def_value= $auth->cur_user->id;
                }
                else {
                    #$arr= debug_backtrace();
                    #trace("WARNING","current user not defined? (object-type=".get_class($this).")".renderBacktrace($arr));
                }
            }
            $this->$tmp_name=$def_value;
        }

        if(!$id) {
          return;
        }
        #--- if id given, querry important fields from db
        $dbh = new DB_Mysql;
        $download_fields="";
        $delimiter= '';
        $fields=$this->fields;
        foreach($fields as $f) {
            if($f->download==FDOWNLOAD_ALWAYS) {
                $download_fields.= $delimiter.$f->name;
                $delimiter=',';
            }
        }
		$prefix= confGet('DB_TABLE_PREFIX');
        $query = "SELECT $download_fields from {$prefix}$this->_type WHERE id = 1";
        $data = $dbh->prepare($query)->execute($id)->fetch_assoc();
        if($data) {
            foreach( $data as $attr => $value ) {
                $this->$attr = stripslashes($value);
            }
        }
        else {
            unset($this);
            return NULL;     # returning false does not makes sense
        }
    }

    #-------------------------------------------------
    # delete
    #-------------------------------------------------
    public function deleteFull()
    {
        if(!$this->id) {
          throw new myException("Deleting requires id");
        }
		$prefix= confGet('DB_TABLE_PREFIX');
        $query = "DELETE FROM {$prefix}{$this->_type} WHERE id = 1";
        $dbh = new DB_Mysql;
        $dbh->prepare($query)->execute($this->id);

        #--- deleting yourself? ----
        unset($this);
        return true;
    }

    #-------------------------------------------------
    # mark_delete (sets object-state to -1)
    #-------------------------------------------------
    public function delete()
    {
        if(!$this->id) {
          throw new myException("Deleting requires id");
        }
		$prefix= confGet('DB_TABLE_PREFIX');
        $query= "update {$prefix}{$this->_type} SET state=-1 WHERE id= $this->id";
        $dbh = new DB_Mysql;
        $dbh->prepare($query)->execute();

        #--- deleting yourself? ----
        unset($this);
        return true;
    }


    #-------------------------------------------------
    # update()  / write to db
    #-------------------------------------------------
    public function update(&$args=NULL)
    {
        if(!$this->id) {
          throw new myException("needs id to call update()");
        }
        if(!isset($this->_type) || !$this->_type || $this->_type=="") {
            throw new myException("need _type to call update()");
        }
        if(!$args || !sizeof($args)) {
            $args=$this->field_states;
        }
        if(!sizeof($this->field_states)) {
            throw new myException("need members to update to database. e.g. 'firstname,lastname,data'");
        }

        #--- build query-string like "update users SET firstname=:1, lastname=:2 where id=:3" --
        #--- build value-array ----
		$prefix= confGet('DB_TABLE_PREFIX');
        $query = "UPDATE {$prefix}{$this->_type} SET  ";
        $values=array();
        $counter=1;
        $delimiter="";
        $value_str="VALUES(";

        # TODO: filter changed fields before saving to db!
        foreach($this->field_states as $m_key => $m_state) {
            if(!isset($this->$m_key) && $this->$m_key!=NULL) {
                throw new myException("$m_key is not a member of '".get_class($this)."' and can't be passed to db");
            }
            $values[]=  $this->$m_key;
            $query.=    $delimiter.$m_key .'=' .$counter;
            $value_str.=$delimiter." ".$counter;
            ++$counter;
            $delimiter=", ";
        }
        $query.= " WHERE id=".$counter++;
        $values[]=$this->id;

        $dbh = new DB_Mysql;
        $statement=$dbh->prepare($query);
        call_user_func_array(array($statement,'execute'),$values);
        return true;
    }

    #-------------------------------------------------
    # insert()  / write to db
    #-------------------------------------------------
    public function insert()
    {
        if($this->id) {
          throw new myException("User object which already has an id, can't be inserted");
        }
        if(!sizeof($this->field_states)) {
            throw new myException("need members to update to database. e.g. 'firstname,lastname,data'");
        }

        #--- build query-string like "INSERT INTO users (firstname, lastname) VALUES(:1, :2)" --
        #--- build value-array ----
		$prefix= confGet('DB_TABLE_PREFIX');
        $query = "INSERT INTO {$prefix}$this->_type (  ";
        $values=array();
        $counter=1;
        $delimiter="";
        $value_str="VALUES(";
        foreach($this->field_states as $m_key => $m_state) {
            if(!isset($this->$m_key) && $this->$m_key!=NULL) {
                throw new myException("$m_key is not a member of $this and can't be passed to db");
            }
            $values[]=  $this->$m_key;
            $query.=    $delimiter.$m_key;
            $value_str.=$delimiter." ".$counter;
            ++$counter;
            $delimiter=", ";
        }
        $value_str.=")";
        $query.= " )".$value_str;

        $dbh = new DB_Mysql;
        $statement=$dbh->prepare($query);
        call_user_func_array(array($statement,'execute'),$values);
         #$statement->execute($values);
        list($this->id) =  $dbh->prepare("select last_insert_id()")->execute()->fetch_row();
        return true;

    }

    #--------------------------------------------------------------
    # output a name trimmed to a certain length
    #--------------------------------------------------------------
    function getShort()
    {
        if(isset($this->short) && $this->short && $this->short !="") {
            return $this->short;
        }
        $length= confGet('STRING_LENGTH_SHORT');
        ereg("(.{0,$length})(.*)",$this->name,$matches);
        if(!$matches[2]) {
            return $matches[1];
        }
        return $matches[1]."...";
    }


    #--- set --------------------------------------
    function __set($name, $val)
    {
        if (isset($this->$name) || isset($this->field_states[$name])) {
           $this->$name = $val;
       }
       else {
            throw(new myException("can't set dbItem->$name for object of type ".get_class($this)));
       }
    }

    #--- get --------------------------------------
    function __get($name)
    {
       if (isset($this->$name)) {
           return $this->$name;
       }
       else if(isset($this->fields[$name])) {
            throw(new myException("can't read '$name' from object of type ".get_class($this)));
       }
       else {
            //throw(new myException("can't read '$name' from object"));
            trace("WARNING","can't read '$name' from object of type '".get_class($this)."' ",$this,debug_backtrace());
       }
   }
}


global $g_item_fields;
$g_item_fields=array();
foreach(array(
            ### internal fields ###
            new FieldInternal  (array('name'=>'id',
                'default'=>0,
            )),
            new FieldInternal  (array('name'=>'type',
                'default'=>0,
            )),
            new FieldUser     (array('name'=>'created_by',
                'default'=> FINIT_CUR_USER,
                'view_in_forms'=>false,
            )),
            new FieldDatetime( array('name'=>'created',
                'default'=>FINIT_NOW,
                'view_in_forms'=>false,
            )),
            new FieldUser     (array('name'=>'modified_by',
                'default'=> FINIT_CUR_USER,
                'view_in_forms'=>false,
            )),
            new FieldDate     (array('name'=>'modified',
                'default'=>FINIT_NOW,
                'view_in_forms'=>false,
            )),
            new FieldUser     (array('name'=>'deleted_by',
                'view_in_forms'=>false,
            )),
            new FieldDate     (array('name'=>'deleted',
                'default'=>FINIT_NEVER,
                'view_in_forms'=>false,
            )),
            new FieldInternal(array(    'name'=>'pub_level',
                'view_in_forms'=>false,
                'default'=>PUB_LEVEL_OPEN,
            )),
            new FieldInternal  (array('name'=>'state',
                'default'=>1,
            )),
            new FieldInternal  (array('name'=>'project',
                'default'=>0,
            )),

       ) as $f) {
            $g_item_fields[$f->name]=$f;
       }

function addProjectItemFields(&$ref_fields) {
    global $g_item_fields;
    foreach($g_item_fields as $f) {
        $ref_fields[$f->name]=$f;
    }
}

/**
* abstract class for project-related database-objects (tasks, project, etc. )
*
* By establishing the right-managing in the item-table we are possible to traclk
* all items in a project. Each of those items consists of two parts:
*  ITEM
*   - holds information common to all project-related items (id, type, date, ownership, publicity, etc)
*
*   With the item.id and item.type we can get the REST of the items-data by looking into
*   the table of this item-type (likes TASKS, PROJECT, PROJECT_PERSON, etc.)
*
*   There might be other tables, that use 'Fields' but do not refer to the almighty-
*   item-table those are directly derived from DbItem
*
*/
class DbProjectItem extends DbItem 
{

    public $fields_project;

    public function __construct($id_or_array=NULL) 
    {

        /**
        *  this-_type hold a string for the current type
        *  which is used for accessing db-tables and
        *  form-parameter-passing (therefor is has to be lowercase)
        */
        $this->_type=strtolower(get_class($this));


        /**
        * add default fields if not overwritten by derived class
        */
        if(!$this->fields) {
            global $g_item_fields;
            $this->fields=&$g_item_fields;
        }

        if(is_array($id_or_array)) {
            parent::__construct();
			foreach($id_or_array as $key => $value) {
				is_null($this->$key); ### cause E_NOTICE on undefined properties
				$this->$key=$value;
			}
        }
        #--- by id ----------
        else if(is_int($id_or_array) || is_string($id_or_array)){

            parent::__construct();  # call constructor to initialize members from field-array
            $id=$id_or_array;       # construction-param was an id
            if(!$id) {
                return;
            }

            global $g_item_fields;


            #--- try to find in item-table ---
            {
                $dbh = new DB_Mysql;
                $str_download_fields="";
                $str_delimiter= '';
                foreach($g_item_fields as $f) {
                    $str_download_fields.= $str_delimiter . $f->name;
                    $str_delimiter=',';
                }
				$prefix= confGet('DB_TABLE_PREFIX');
                $query = "SELECT $str_download_fields from {$prefix}item WHERE id = $id";
                $data = $dbh->prepare($query)->execute()->fetch_assoc();
                if($data) {
                    foreach( $data as $attr => $value ) {

                        is_null($this->$attr);   # cause E_NOTICE if member not defined
                        $this->$attr = $value;
                    }
                }

                #--- not found in item-table ---
                else {
                    trace("Warning","item id='$id' not found in table");
                    unset($this);                   #@@@ not sure if abort called construction like this works
                    return NULL;
                }
            }

            #--- now find the other fields in the appropriate table ---
            {
                $dbh = new DB_Mysql;
                $str_download_fields="";
                $str_delimiter= '';
                foreach($this->fields as $f) {
                    #--- ignore project item fields ---
                    if(!isset($g_item_fields[$f->name])) {
                        $str_download_fields.= $str_delimiter . $f->name;
                        $str_delimiter=',';
                    }
                }
				$prefix= confGet('DB_TABLE_PREFIX');
                $query = "SELECT $str_download_fields from {$prefix}$this->_type WHERE id = $id";
                $data = $dbh->prepare($query)->execute()->fetch_assoc();
                if($data) {
                    foreach( $data as $attr => $value ) {
                        is_null($this->$attr);   # cause E_NOTICE if member not defined
                        $this->$attr = stripslashes(stripslashes(stripslashes($value))); #@@@ note this is a weird bug
                    }
                }

                #--- not found in item-table ---
                else {
                    trace("warning","project-object not found in db-table");
                    unset($this);                   #@@@ not sure if abort called construction like this works
                    return NULL;
                }
            }

        }
        #--- just empty ----
        else {
            parent::__construct();
        }
    }

    /***************************************************************
    * insert objects to database
    */
    public function insert()
    {
        global $g_item_fields;

        if($this->id) {
          throw new myException("User object which already has an id, can't be inserted");
        }
        if(!sizeof($this->field_states)) {
            throw new myException("need members to update to database. e.g. 'firstname,lastname,data'");
        }

        /**
        * @@@ WE NEED AN AUTHORISATION-CHECK HERE @@@
        *
        * we also should lock those to into ONE transaction
        *
        *
        */

        #--- first write item-fields ---
        #
        # build query-string like "INSERT INTO users (firstname, lastname) VALUES(tom, mann)"
        #
        {
            $dbh = new DB_Mysql;

            $t_fields=array();
            $t_values=array();
            foreach($g_item_fields as $f) {
                $name= $f->name;
                if(!isset($this->$name) && $this->$name!=NULL) {
                    throw new myException("$name is not a member of $this and can't be passed to db");
                }
                $t_fields[]=$name;
                $t_values[]="'".mysql_real_escape_string($this->$name)."'";
            }
			$prefix= confGet('DB_TABLE_PREFIX');
            $str_query= 'INSERT INTO '
                        .$prefix.'item '
                              . '(' . join(', ', $t_fields )   .')'
                        .' VALUES(' . join(', ', $t_values) .')';

            $sth= $dbh->prepare($str_query);
        	$sth->execute("",1);
        }

        #--- extract the id of last inserted item ---
        list($this->id) =  $dbh->prepare("select last_insert_id()")->execute()->fetch_row();

        #--- now write non item-fields ---
        #
        # build query-string like "INSERT INTO users (firstname, lastname) VALUES(tom, mann)"
        #
        {
            $dbh = new DB_Mysql;
            $t_fields=array(); 
            $t_values=array();

            foreach($this->fields as $f) {
                $name= $f->name;

                ### skip project-item fields ###
                if(@$this->fields[$name]->in_db_object || !isset($g_item_fields[$name])) {
                    if(!isset($this->$name) && $this->$name!=NULL) {
                        throw new myException("$name is not a member of $this and can't be passed to db");
                    }
                    $t_fields[]=$name;
                    $t_values[]="'".mysql_real_escape_string($this->$name)."'";
                }
            }
            $str_query= 'INSERT INTO '
                        .$prefix.$this->_type
                              . '(' . join(',', $t_fields  ) .')'
                        .' VALUES(' . join(',', $t_values) .')';

            $sth= $dbh->prepare($str_query);
        	$sth->execute("",1);
        }
        return true;
    }

    /***************************************************************
    * update objects in database
    */
    public function update(&$args=NULL)
    {
        global $auth;
        global $g_item_fields;

        if(!$this->id) {
          throw new myException("User object without id can't be updated");
        }
        if(!sizeof($this->field_states)) {
            throw new myException("need members to update to database. e.g. 'firstname,lastname,data'");
        }
        if($args) {
            throw new myException("updating of some attributes not supported yet");
        }
        

        /**
        * @@@ WE NEED AN AUTHORISATION-CHECK HERE @@@
        *
        * we also should lock those to into ONE transaction
        *
        *
        */
        $this->modified_by= $auth->cur_user->id;
        $this->modified= date("Y-m-d H:i:s");
        

        #--- first write item-fields ---
        #
        #--- build query-string like "update users SET firstname=:1, lastname=:2 where id=:3" --
        {

            $t_pairs=array();
            foreach($g_item_fields as $f) {
                $name= $f->name;
                if(!isset($this->$name) && $this->$name!=NULL) {
                    throw new myException("$name is not a member of $this and can't be passed to db");
                }
                $t_pairs[]= $name."='".mysql_real_escape_string($this->$name)."'";
            }
			$prefix= confGet('DB_TABLE_PREFIX');
            $str_query= 'UPDATE '
                        .$prefix.'item '
                        .'SET ' . join(', ', $t_pairs)
                        .' WHERE id='.$this->id ;

            $dbh = new DB_Mysql;
            $sth= $dbh->prepare($str_query);
        	$sth->execute("",1);
        }

        #--- now write non item-fields ---
        #
        #--- build query-string like "update users SET firstname=:1, lastname=:2 where id=:3" --
        #
        {
            $t_pairs=array();          # the 'id' field is skipped later, because it's defined as project-item-field. so we have to add it here

            foreach($this->fields as $f) {
                $name= $f->name;

                ### skip project-item fields ###
                if(@$this->fields[$name]->in_db_object || !isset($g_item_fields[$name])) {
                    if(!isset($this->$name) && $this->$name!=NULL) {
                        throw new myException("$name is not a member of $this and can't be passed to db");
                    }
                    $t_pairs[]= $name.'='."'".mysql_real_escape_string($this->$name)."'";
                }
            }
            $str_query= 'UPDATE '
                        .$prefix.$this->_type
                        .' SET ' . join(', ', $t_pairs)
                        .' WHERE id='.$this->id ;

            $dbh = new DB_Mysql;
            $sth= $dbh->prepare($str_query);
        	$sth->execute("",1);
        }
        return true;
    }

    #-------------------------------------------------
    # mark_delete (sets object-state to -1)
    #-------------------------------------------------
    public function delete()
    {
        global $auth;
        if(!$this->id) {
          throw new myException("Deleting requires id");
        }


        ### check user-rights ###
        if($pp= $this->getProjectPerson()) {


            $pub_level=  $this->pub_level;

            ### owned ###
            if($this->created_by == $pp->person) {
                $pub_level= PUB_LEVEL_OWNED;
            }

            ### is item editable ?
            if($pub_level >= $pp->level_delete
            ) {
                ### AND below delete-level ###
                if($pub_level >= $pp->level_delete
                ) {
                    $this->state = -1;
                    $this->deleted_by= $auth->cur_user->id;
                    $this->deleted= date("Y-m-d H:i:s");

                    $this->update();

                    #--- deleting yourself? ----
                    return true;

                }
            }
        }
        ### not a proejct-item? ###
        #
        #@@@  be sure that rights deleting project, companies and persons is validated somewhere else
        #
        else if($this->project == 0) {
            
            $this->state = -1;
            $this->deleted_by= $auth->cur_user->id;
            $this->deleted= date("Y-m-d H:i:s");

            $this->update();
        }
        return true;
    }

    /**
    * return the project object the current user has to this item
    */
    public function getProjectPerson() {
        global $auth;

		$prefix= confGet('DB_TABLE_PREFIX');

        #--- get the belonging project-person ---
        $dbh = new DB_Mysql;

        $sth= $dbh->prepare(
                "SELECT i.*, upp.* from {$prefix}item i,  {$prefix}projectperson upp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1
                    AND upp.project = $this->project

                    AND i.type = '".ITEM_PROJECTPERSON."'
                    AND i.id = upp.id
                ");
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();

        ### return nothing if no rights ###
        if(count($tmp) != 1) {
            return NULL;
        }
        require_once("db/class_projectperson.inc");
        $pp=new ProjectPerson($tmp[0]);
        return $pp;
    }

    /**
    * returns an assoc array with pub-levels the current user may set on a given item
    *
    * - reducing the pub_level is like deleting an item for others
    * - raising the pub_level might reveal information to outsiders
    */
    public function getValidUserSetPublevel()
    {
        global $PUB_LEVEL_VALUES;
        global $PUB_LEVEL_NAMES;

        ### get belonging project person ###
        if($pp= $this->getProjectPerson()) {
            #print_r($pp);
            ### can we edit this object ? ###
            if(
                $pp->level_edit <= $this->pub_level
                ||
                $this->created_by == $pp->person
            ) {

                ### get max pub level ###
                $max_pub_level= $pp->level_create;

                ### get min reduce level ###
                $min_pub_level= $pp->level_reduce;

                ### get slice of assoc. array ###
                $levels= array();
                for($i= $min_pub_level; $i < $max_pub_level; $i++) {
                    if(isset($PUB_LEVEL_NAMES[$i])) {
                        $levels["$i"]= $PUB_LEVEL_NAMES[$i];
                    }
                }

                ### add current value ###
                $levels["$this->pub_level"]= $PUB_LEVEL_NAMES[$this->pub_level];


                ### new items might by created as private ###
                if($this->id == 0) {
                    $levels[PUB_LEVEL_PRIVATE]= $PUB_LEVEL_NAMES[PUB_LEVEL_PRIVATE];
                }
                return array_flip($levels);
            }
        }
        return NULL;
    }
}


?>
