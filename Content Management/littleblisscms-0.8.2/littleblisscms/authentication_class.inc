<?php
/*
	<one line to give the program's name and a brief idea of what it does.>
	Copyright (C) 2005  Phillip Berry (Bliss Webhosting)
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*Class handles authentication*/
require_once("xml_handler_class.inc") ;
class authentication extends xml_handler{

	var $error ;
	var $username ;
	var $password ;

	/*!
	* \brief Constructor creates dom from defined file
	* \param $xmlfile String, Path to user XML file
	*/
	function authentication($xmlfile){
		parent::xml_handler($xmlfile) ;
		return TRUE ;
	}

	/*!
	* \brief Test the defined user and password (set as variables outside this function)	
	* \param[out] $error String
	* \param[out] $_SESSION['username'], String
	* \param[out] $_SESSION['authenticated'], bool
	* \return bool
	*/
	function authenticate(){
		if(!$this->password && !$this->username){
			$this->error = "No username or password supplied" ;
			return FALSE ;
		}
		else{
			if($this->find_user()){
				//User passwords are stored as  salted md5s in the userfile, format= "user.password"				
				if($this->tmp_password != md5($this->username.$this->password)){
					$this->error = "Username or password incorrect" ;
					return FALSE ;
				}
				else{
					//Succesful login, begin the session
					session_start() ;
					$_SESSION['username'] = $this->username ;
					$_SESSION['authenticated'] = TRUE ;
					return TRUE ;
				}
			}
			return FALSE ;
		}
		return FALSE ;
	}

	/*!
	* \brief Tests if the user is logged in
	* \param[out] $username String, Username
	* \param[out] $error String
	* \return bool
	*/
	function check_authentication(){
		if($_SESSION['authenticated']){
			$this->username = $_SESSION['username'] ;
			if($this->find_user()){
				return TRUE ;
			}
			else{
				$this->error = "User does not exist" ;
				return FALSE ;
			}
		}
		return FALSE ;
	}

	/*!
	* \brief Check if the logged in user is administrator
	* \return bool
	*/
	function check_is_admin(){
		if($_SESSION['username'] == "administrator"){
			return TRUE ;
		}
		return FALSE ;
	}

	/*!
	* \brief Find a particular user, return the users DomNode, and the users hashed password
	* \param[out] $user_node DomNode, users DomNode
	* \param[out] $tmp_password String, users hashed password
	* \return bool
	*/
	function find_user(){
		$tags = $this->find_tag("user") ;
		foreach($tags as $user){
			if($this->search_attribute_value($user,"username",$this->username)){
				$this->user_node = $user ;
				$node_array = $this->retrieve_node_data($this->user_node) ;
				$this->tmp_password = $node_array['password']['data'] ;
				return TRUE ;
			}
		}
		return FALSE ;
	}
}
?>