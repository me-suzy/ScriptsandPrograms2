<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * company object
 *
 * @includedby:     *
 *
 * @author:         Thomas Mann
 * @uses:           DbProjectList
 * @usedby:
 *
 */

global $g_company_fields;
$g_company_fields=array();
addProjectItemFields(&$g_company_fields);

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
    new FieldString(array(
        'name'=>'name',
        'tooltip'=>'Required. (e.g. pixtur ag)'
    )),
    new FieldString(array(
        'name'=>'tagline',
        'tooltip'=>'Optional: Additnal tagline (eg. multimedia concepts)'
    )),
    new FieldString(array(
        'name'=>'short',
        'tooltip'=>'Optional: Short name shown in lists (eg. pixtur)'
    )),
    new FieldString(array(
        'name'=>'phone',
        'tooltip'=>'Optional: Phone (eg. +49-30-12345678)'
    )),
    new FieldString(array(
        'name'=>'fax',
        'tooltip'=>'Optional: Fax (eg. +49-30-12345678)'
    )),
    new FieldString(array(
        'name'=>'street',
        'tooltip'=>'Optional:  (eg. Poststreet 28)'
    )),
    new FieldString(array(
        'name'=>'zipcode',
        'tooltip'=>'Optional: (eg. 12345 Berlin)'
    )),
    new FieldString(array(
        'name'=>'homepage',
        'tooltip'=>'Optional: (eg. http://www.pixtur.de)'
    )),
    new FieldString(array(
        'name'=>'intranet',
        'tooltip'=>'Optional: (eg. http://www.pixtur.de/login.php?name=someone)'
    )),
    new FieldString(array(
        'name'=>'email',
        'tooltip'=>'Optional: (eg. http://www.pixtur.de/login.php?name=someone)'
    )),
    new FieldText(array(
        'name'=>'comments',
        'tooltip'=>'Optional'
    )),
) as $f) {
    $g_company_fields[$f->name]=$f;
}





class Company extends DbProjectItem
{

	//=== constructor ================================================
	function __construct ($id_or_array=NULL)
    {
        global $g_company_fields;
        $this->fields= &$g_company_fields;

        parent::__construct($id_or_array);
        $this->type= ITEM_COMPANY;
   	}

    #------------------------------------------------------------
    # returns link to company-view with short name
    #------------------------------------------------------------
    public function getLink($show_long=false) {
        global $PH;
        if($show_long) {
            $out='<span class="item company">'. $PH->getLink('companyView',$this->name,array('company'=>$this->id)).'</span>';
        }
        else {
            $out='<span class="item company">'. $PH->getLink('companyView',$this->getShort(),array('company'=>$this->id),'item company').'</span>';
        }
        return $out;
    }

    static function &queryFromDb($query_string) 
	{
        $dbh = new DB_Mysql;

        $sth= $dbh->prepare($query_string);

    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
    	$companies=array();
        foreach($tmp as $t) {
            $company=new Company($t);
            $companies[]=$company;
        }
        return $companies;
    }

    #------------------------------------------------------------
    # get companies from db
    #------------------------------------------------------------
    public static function getAll($order_by=NULL)
    {
        global $auth;
		$prefix = confGet('DB_TABLE_PREFIX');

        if(!$order_by) {
            $order_by= "c.name";
        }

        ### show all ###
        if($auth->cur_user->user_rights & RIGHT_PROJECT_ASSIGN) {
            $str=
                "SELECT c.* from {$prefix}company c
                WHERE
                      c.state = 1
                ORDER BY $order_by";
        }

        ### only assigned projects ###
        else {
               $str= "SELECT c.* from {$prefix}company c, {$prefix}project p, {$prefix}projectperson upp
                 WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1         /* upp all user projectpersons */

                    AND  p.id = upp.project   /* all user projects */
                    AND  c.id = p.company     /* all companies */
                    AND  c.state = 1
                ORDER BY $order_by";
        }



  	    return self::queryFromDb($str);
    }

    #---------------------------
    # get nume tasks
    #---------------------------
    function getNumOpenProjects() 
	{
		$prefix= confGet('DB_TABLE_PREFIX');
        $dbh = new DB_Mysql;
        $sth= $dbh->prepare("SELECT  COUNT(*) FROM {$prefix}project WHERE company = \"$this->id\" AND state=1 AND status<4" );
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
        return $tmp[0]['COUNT(*)'];
    }

    #---------------------------
    # get projects of company
    #---------------------------
    function getProjects($order_by=NULL, $state_min=2, $state_max=4)
    {
        global $auth;
		$prefix= confGet('DB_TABLE_PREFIX');

        if(!$order_by) {
            $order_by="name";
        }

       #"SELECT * FROM {$prefix}project WHERE company = \"$this->id\" AND state=1 ORDER BY name"

        ### all projects ###
        if($auth->cur_user->user_rights & RIGHT_PROJECT_ASSIGN) {
            $str=
                "SELECT p.* from {$prefix}project p
                WHERE
                       p.status <= $state_max
                   AND p.status >= $state_min
                   AND p.company =  $this->id
                   AND p.state = 1

                ORDER BY $order_by";
        }

        ### only assigned projects ###
        else {
            $str=
                "SELECT p.* from {$prefix}project p, {$prefix}projectperson upp
                WHERE
                        upp.person = {$auth->cur_user->id}
                    AND upp.state = 1       /* all projectpersons of user */


                    AND   p.id  = upp.project       /* all projects of user */
                    AND   p.company = $this->id     /* all project of this company */
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
        foreach($tmp as $t) {
            $projects[]=new Project($t);
        }
        return $projects;
    }

    #---------------------------
    # get Employments
    #---------------------------
    function getEmployments()
    {
		$prefix= confGet('DB_TABLE_PREFIX');
        require_once('db/class_employment.inc');

        $dbh = new DB_Mysql;
        $sth= $dbh->prepare(
            "SELECT em.* FROM {$prefix}employment em, {$prefix}item i
              WHERE  i.type= ".ITEM_EMPLOYMENT."
              AND    i.state=1
              AND   i.id = em.id
              AND   em.company = $this->id 
              " 
        );
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
        $es=array();
        foreach($tmp as $t) {
            $es[]=new Employment($t);
        }
        return $es;
    }

    /**
    * get Persons
    *
    * does NOT validate user-rights
    */
    function getPersons()
    {
		$prefix= confGet('DB_TABLE_PREFIX');
        require_once('db/class_person.inc');
        require_once('db/class_employment.inc');
        $dbh = new DB_Mysql;
        $sth= $dbh->prepare(
						"SELECT p.* FROM {$prefix}person p,{$prefix}employment em, {$prefix}item i
						WHERE   i.type= ".ITEM_EMPLOYMENT."
						AND     i.state=1
						AND     i.id= em.id
						AND     em.company = \"$this->id\" 
						AND     em.person= p.id 
						AND     p.state=1" 
		);
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();
        $es=array();
        foreach($tmp as $t) {
            $es[]=new Person($t);
        }
        return $es;
    }

    #---------------------------
    # get PersonLinks
    #---------------------------
    function getPersonLinks($show_max_number=3)
    {
        $ps= $this->getPersons();
        $buffer= '';
        $sep= '';
        $num=0;
        foreach($ps as $p) {
            $buffer.= $p->getLink().$sep;
            if(++$num>$show_max_number) {
                break;
            }
            $sep=", ";
        }
        return $buffer;
    }

    #---------------------------
    # is company visible to user?
    #---------------------------
    function validateView()
    {
        global $auth;
        global $PH;
		$prefix= confGet('DB_TABLE_PREFIX');

        ### all ###
        if($auth->cur_user->user_rights & RIGHT_COMPANY_VIEWALL) {
            return true;
        }


           $str= "SELECT COUNT(*) from {$prefix}company c, {$prefix}project p, {$prefix}projectperson upp
             WHERE
                    upp.person = {$auth->cur_user->id}
                AND upp.state = 1         /* upp all user projectpersons */

                AND  p.id = upp.project   /* all user projects */
                AND  c.id = p.company     /* all companies */
                AND  c.id = $this->id
                AND  c.state = 1
            ";

        $dbh = new DB_Mysql;
        $sth= $dbh->prepare($str);
    	$sth->execute("",1);
    	$tmp=$sth->fetchall_assoc();

        $count= $tmp[0]['COUNT(*)'];

        if($count == 1) {
            return true;
        }
        else if($count > 1) {
            $PH->abortWarning("more than expected",ERROR_BUG);
        }
        else{
            $PH->abortWarning("not available",ERROR_RIGHTS);
        }
    }




}




?>
