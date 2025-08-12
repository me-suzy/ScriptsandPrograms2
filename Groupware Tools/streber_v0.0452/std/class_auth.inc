<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * classes related to user authentication
 *
 *
 * @author: Thomas Mann
 * @uses
 * @usedby
 *
 */






class Auth {

    public $cur_user=NULL;  #@@@ this object should not be used for data-manipulation

    /**
    * constructor
    */
    public function __construct() {

    }

    /**
    * check if valid cookie is set for a user
    *
    * @return       false if no valid cookie found (invalid, unknown user, etc.)
    *               $user-object on success
    */
    public function getUserByCookie() {
        require_once("db/class_person.inc");

        if(!$cookie_string= get('NORD_UID')) {
            return false;
        }

        if(!$user=Person::getByCookieString($cookie_string)) {
            return false;
        }
        if(!$user->can_login) {
            return false;
        }

        ### success ###
        $this->cur_user= $user;
        
        return $user;
    }

    /**
    * perform login for user/password
    *
    * - increases false_login_counter
    * - on success:
    *    - sets current_user
    *    - set cookie
    *    - return current user
    *
    * @return       false if login wasn't successfull
    */
    public function tryLoginUser($name,$password_md5) {

        if(!$user=Person::getByNickname($name)) {
            return false;
        }
        else if ($user->password != $password_md5 || !$user->can_login) {
            return false;
        }
       


        ### success ###
        $this->cur_user=    $user;

        $t_cookie= md5($name.$password_md5);
        $user->cookie_string= $t_cookie;
        $user->update();


        setcookie(
            'NORD_UID',
            $t_cookie,
            time()+60*60*24*30,
            '',
            '',
            0);

        return $user;


    }




    /**
    * remove cookie
    *
    * @usedby       pages/login.inc >> logout()
    */
    public function removeCookie() {

        if(get('NORD_UID')) {
            setcookie('NORD_UID','',time()+60*60*24*30,'','',1);
        }
    }


}

$auth= new Auth();
