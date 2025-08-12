<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html


/**
 * person
 *
 * @includedby:     *
 *
 * @author:         Thomas Mann
 * @uses:           DbProjectItem
 * @usedby:
 *
 */




global $g_person_fields;
$g_person_fields=array();
addProjectItemFields(&$g_person_fields);

foreach(array(
    new FieldInternal(array(    'name'=>'id',
        'default'=>0,
        'in_db_object'=>1,
        'in_db_item'=>1,
    )),
    new FieldInternal(array(    'name'=>'state',    ### cached in project-table to speed up queries ###
        'default'=>1,
        'in_db_object'=>1,
        'in_db_item'=>1,
    )),
    new FieldString(array(      'name'=>'name',
        'title'=>'Full name',
        'tooltip'=>'Required. Full name like (e.g. Thomas Mann)'
    )),
    new FieldString(array(      'name'=>'nickname',
        'tooltip'=>'Required. (e.g. pixtur)'
    )),
    new FieldString(array(      'name'=>'tagline',
        'tooltip'=>'Optional: Additnal tagline (eg. multimedia concepts)'
    )),
    new FieldString(array(      'name'=>'mobile_phone',
        'tooltip'=>'Optional: Private Phone (eg. +49-30-12345678)'
    )),

    ### office stuff ###
    new FieldString(array(      'name'=>'office_phone',
        'tooltip'=>'Optional: Private Phone (eg. +49-30-12345678)'
    )),
    new FieldString(array(      'name'=>'office_fax',
        'tooltip'=>'Optional: Private Fax (eg. +49-30-12345678)'
    )),
    new FieldString(array(      'name'=>'office_street',
        'tooltip'=>'Optional:  Private (eg. Poststreet 28)'
    )),
    new FieldString(array(      'name'=>'office_zipcode',
        'tooltip'=>'Optional: Private (eg. 12345 Berlin)'
    )),
    new FieldString(array(      'name'=>'office_homepage',
        'title'=>'Office Page',
        'tooltip'=>'Optional: (eg. http://www.pixtur.de)'
    )),
    new FieldString(array(      'name'=>'office_email',
        'tooltip'=>'Optional: (eg. http://www.pixtur.de/login.php?name=someone)'
    )),


    ### personal stuff ###
    new FieldString(array(      'name'=>'personal_phone',
        'tooltip'=>'Optional: Private Phone (eg. +49-30-12345678)'
    )),
    new FieldString(array(      'name'=>'personal_fax',
        'tooltip'=>'Optional: Private Fax (eg. +49-30-12345678)'
    )),
    new FieldString(array(      'name'=>'personal_street',
        'tooltip'=>'Optional:  Private (eg. Poststreet 28)'
    )),
    new FieldString(array(      'name'=>'personal_zipcode',
        'tooltip'=>'Optional: Private (eg. 12345 Berlin)'
    )),
    new FieldString(array(      'name'=>'personal_homepage',
        'title'=>'Personal Page',
        'tooltip'=>'Optional: (eg. http://www.pixtur.de)'
    )),
    new FieldString(array(      'name'=>'personal_email',
        'tooltip'=>'Optional: (eg. http://www.pixtur.de/login.php?name=someone)'
    )),
    new FieldDate(array(      'name'=>'birthdate',
    )),


    new FieldString(array(      'name'=>'color',
    )),

    new FieldText(array(        'name'=>'comments',
        'tooltip'=>'Optional'
    )),
    new FieldPassword(array(    'name'=>'password',
		'view_in_forms'=>false,
		
    )),

    /**
    * reservated
    */
    new FieldPassword(array(    'name'=>'security_question',
		'view_in_forms'=>false,
    )),

    new FieldPassword(array(    'name'=>'security_answer',
		'view_in_forms'=>false,
    )),

    /**
    * used for...
    * - initializing project-member-roles
    * - custimizing the interface (like hiding advance options to clients)
    */
    new FieldInternal(array(    'name'=>'profile',
		'view_in_forms'=>false,
		'default'=>3,
    )),

    /**
    * @@@ themes are not supported right now not used right now
    */
    new FieldInternal(array(    'name'=>'theme',
		'view_in_forms' =>false,
		'default'       => confGet('THEME_DEFAULT'),
    )),


    /**
    * flag if person has an account
    */
    new FieldInternal(array(    'name'=>'can_login',
    )),

    /**
    * bit-field of user-rights. See "std/auth.inc"
    */
    new FieldInternal(array(    'name'=>'user_rights',
        'tooltip'=>'Optional'
    )),

    /**
    * md5 representation of username/password for validating login    
    */
    new FieldInternal(array(    'name'=>'cookie_string',
    )),


    /**
    * random-identifier for securitry
    *
    * - initialized on creation 
    * - used for identifaction without password (like change password notifications)
    */
    new FieldInternal(array(    'name'=>'identifier',
        'default'=>FINIT_RAND_MD5,
    )),

    /**
    * reservated for non-project public-level (is not implemented / used)
    */
    new FieldInternal(array(    'name'=>'user_level_create',
    )),
    new FieldInternal(array(    'name'=>'user_level_view',
    )),
    new FieldInternal(array(    'name'=>'user_level_edit',
    )),
    new FieldInternal(array(    'name'=>'user_level_reduce',
    )),
) as $f) {
    $g_person_fields[$f->name]=$f;
}


//====================================================================
// Persons
//====================================================================
class Person extends DbProjectItem {
    public $name;
    public $project;


	//=== constructor ================================================
	function __construct ($id_or_array=false)
    {
        global $g_person_fields;
        $this->fields= &$g_person_fields;

        parent::__construct($id_or_array);
        $this->type= ITEM_PERSON;
   	}


    #------------------------------------------------------------
    # returns link to person-view with short name
    #------------------------------------------------------------
    public function getLink() 
    {
        global $PH;
        if($this->nickname) {
            $out='<span class="item person">'.$PH->getLink('personView',$this->nickname,array('person'=>$this->id)).'</span>';
        }
        else {
            $out='<span class="item person">'.$PH->getLink('personView',$this->getShort(),array('person'=>$this->id)).'</span>';
        }
        return $out;
    }

    static function &queryFromDb($query_string) 
    {
        $dbh = new DB_Mysql;

        $sth= $dbh->prepare($query_string);

    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
    	$persons=array();
        foreach($tmp as $t) {
            $person=new Person($t);
            $persons[]=$person;
        }
        return $persons;
    }

    #------------------------------------------------------------
    # getAll
    #------------------------------------------------------------
    public static function getAll($order_by=NULL, $accounts_only=false)
    {
        global $auth;
		$prefix = confGet('DB_TABLE_PREFIX');
        if(!$order_by) {
            $order_by="name";	
        }
        
        $str_accounts_only= $accounts_only
         ?"AND pers.can_login=1"
         :"";
        
        ### all projects ###
        if($auth->cur_user->user_rights & RIGHT_PROJECT_ASSIGN) {
            $str=
                "SELECT pers.* from {$prefix}person pers
                WHERE
                    pers.state = 1
                    $str_accounts_only
                ORDER BY $order_by";
        }

        ### only assigned projects ###
        else {
            $str=
                "SELECT DISTINCT pers.* from {$prefix}person pers, {$prefix}project p, {$prefix}projectperson upp, {$prefix}projectperson pp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1           /* upp all user projectpersons */

                    AND  upp.project = p.id        /* all user projects */
                    AND  p.state = 1
                    AND  p.id = pp.project         /* all projectpersons in user's project*/
                    AND  pp.state = 1
                    AND  pp.person = pers.id      /* all belonging person*/
                    AND  pers.state = 1
                    $str_accounts_only

                ORDER BY $order_by";
        }

  	    return self::queryFromDb($str);
    }

    #------------------------------------------------------------
    # get person by nickname
    #------------------------------------------------------------
    public static function getByNickname($nickname)
	{
		$prefix= confGet('DB_TABLE_PREFIX');
  	    $tmp=self::queryFromDb("SELECT * FROM {$prefix}person WHERE nickname='$nickname'");
        if(!$tmp || count($tmp)!=1) {
            return false;
        }
        return $tmp[0];
    }

    #------------------------------------------------------------
    # get person by cookie_string (md5)
    #------------------------------------------------------------
    public static function getByCookieString($cookie_string)
	{
		$prefix= confGet('DB_TABLE_PREFIX');

  	    $tmp=self::queryFromDb("SELECT * FROM {$prefix}person WHERE cookie_string='$cookie_string'");
        if(!$tmp || count($tmp)!=1) {
            return false;
        }
        return $tmp[0];
    }

    #---------------------------
    # get Employments
    #---------------------------
    function getEmployments()
    {
		$prefix = confGet('DB_TABLE_PREFIX');
        require_once('db/class_employment.inc');
        $dbh = new DB_Mysql;
        $sth= $dbh->prepare("
            SELECT * FROM {$prefix}employment em, {$prefix}item i
            WHERE   i.type = ".ITEM_EMPLOYMENT."
            AND     i.state = 1
            AND     i.id = em.id
            AND     em.person = \"$this->id\" 
            " );
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
        $es=array();
        foreach($tmp as $t) {
            $es[]=new Employment($t);
        }
        return $es;
    }

    /**
    * get project-persons
    */
    /*
    function &getProjectPersons($order_by=NULL)
    {
		$prefix= confGet('DB_TABLE_PREFIX');
        global $auth;
        if(!$order_by) {
            $order_by= "name desc";
        }
        require_once("db/class_projectperson.inc");
        $dbh = new DB_Mysql;
        if($auth->cur_user->user_rights & RIGHT_PROJECT_ASSIGN) {
            $sth= $dbh->prepare(
                "SELECT i.*, pp.* from {$prefix}item i, {$prefix}projectperson pp
                WHERE

                        i.type = '".ITEM_PROJECTPERSON."'
                    AND i.state = 1
                    AND i.project = pp.project

                    AND pp.person = $this->id
                    AND pp.id = i.id

                ORDER BY $order_by"
            );

        }
        else {
            $sth= $dbh->prepare(
                "SELECT i.*, pp.* from {$prefix}item i, {$prefix}projectperson pp, {$prefix}projectperson upp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1

                    AND i.type = '".ITEM_PROJECTPERSON."'
                    AND i.state = 1
                    AND i.project = upp.project

                    AND (
                        i.pub_level >= upp.level_view
                        OR
                        i.created_by= {$auth->cur_user->id}
                    )
                    AND pp.id = i.id
                    AND pp.person = $this->id

                ORDER BY $order_by"
            );
        }
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
    	$ppersons=array();
        foreach($tmp as $n) {
            $pperson=new ProjectPerson($n);
            $ppersons[]= $pperson;
        }
        return $ppersons;
    }
    */

    /**
    * get Projects
    *
    */
    public function getProjects($order_by=NULL, $state_min=2, $state_max=5)
    {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');

        if(!$order_by) {
             $order_by= "prio, name";
        }

        ### all projects ###
        if($auth->cur_user->user_rights & RIGHT_PROJECT_ASSIGN) {
            $str=
                "SELECT p.* from {$prefix}project p, {$prefix}projectperson pp
                WHERE
                       p.status <= $state_max
                   AND p.status >= $state_min
                   AND p.state = 1

                   AND pp.person = $this->id
                   AND pp.project = p.id
                   AND pp.state=1
                ORDER BY $order_by";
        }

        ### only assigned projects ###
        else {
            $str=
                "SELECT p.* from {$prefix}project p, {$prefix}projectperson upp , {$prefix}projectperson pp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1
                    AND upp.project = pp.project

                   AND pp.person = $this->id
                   AND pp.project = p.id
                   AND pp.state=1

                    AND p.id = upp.project
                    AND   p.status <= $state_max
                    AND   p.status >= $state_min
                    AND   p.state = 1

                ORDER BY $order_by";
        }

        $dbh = new DB_Mysql;
        $sth= $dbh->prepare($str);
        $sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();

    	$projects=array();
        foreach($tmp as $n_array) {
            require_once("db/class_project.inc");
            $project=new Project($n_array);
            $projects[]= $project;
        }
        return $projects;
    }

    /**
    *  get user efforts
    *
    * @@@ does NOT check for admin-rights to view all efforts
    */
    function getEfforts($order_by=NULL)
    {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');
        require_once('db/class_effort.inc');
        if(!$order_by) {
            $order_by="created";
        }

        $dbh = new DB_Mysql;
        $sth= $dbh->prepare(
                "SELECT i.*, e.*  from {$prefix}item i, {$prefix}effort e, {$prefix}project p, {$prefix}projectperson upp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1

                    AND i.type = '".ITEM_EFFORT."'
                    AND i.state = 1
                    AND i.project = upp.project
                    AND i.created_by = $this->id
                    AND (
                        i.pub_level >= upp.level_view
                        OR
                        i.created_by= {$auth->cur_user->id}
                    )

                    AND e.id= i.id
                    AND p.id= i.project
                ORDER BY $order_by"
        );
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
        $efforts=array();
        foreach($tmp as $t) {
            $efforts[]=new Effort($t);
        }
        return $efforts;
    }

    #---------------------------
    # get Companies
    #---------------------------
    function getCompanies()
    {
        require_once("db/class_company.inc");
        $emps= $this->getEmployments();
        $cs=array();
        foreach($emps as $e) {
            if($e->company) {
                $c= new Company($e->company);
                if($c) {
                    $cs[]= $c;
                }
            }
        }
        return $cs;
    }


    #---------------------------
    # get CompanyLinks
    #---------------------------
    function getCompanyLinks($show_max_number=3)
    {
        $cs= $this->getCompanies();
        $buffer= '';
        $sep= '';
        $num=0;
        foreach($cs as $c) {
            $buffer.= $c->getLink().$sep;
            if(++$num>$show_max_number) {
                break;
            }
            $sep=", ";
        }
        return $buffer;
    }


    /**
    * validate person can be viewed
    */
    public function validateView()
    {
        global $auth;
        global $PH;
		$prefix= confGet('DB_TABLE_PREFIX');

        ### all  ###
        if($auth->cur_user->user_rights & RIGHT_PERSON_VIEWALL) {
            return true;
        }

        ### self  ###
        if($auth->cur_user->id == $this->id) {
            return true;
        }

        $str=
            "SELECT pers.* from {$prefix}person pers, {$prefix}project p, {$prefix}projectperson upp, {$prefix}projectperson pp
            WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.state = 1           /* upp all user projectpersons */


                AND  p.id= upp.project  /* all user projects */
                AND  pp.project= p.id         /* all projectpersons in user's project*/
                AND  pers.id = pp.person      /* all belonging person*/
                AND  pers.id = $this->id
                AND   p.state = 1
        ";

  	    $persons= self::queryFromDb($str);
        if(count($persons) == 1) {
            return true;
        }
        else {
            $PH->abortWarning("insuffient rights",ERROR_RIGHTS);
        }

    }


}




?>
