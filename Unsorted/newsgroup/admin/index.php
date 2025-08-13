<?

// ---------------------------------------------------------------------------- //
// MyNewsGroups :) 'Share your knowledge'
// Copyright (C) 2002 Carlos Sánchez Valle (yosoyde@bilbao.com)

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------------------------- //


// --------- Servers Administration ------------//
//
// File: index.php
//
// Created: 07/06/2002
//
// Description:
//
// Home of Administration Interface
//
//

// Iniciamos la sesión!!
session_start();

include("../config.php");

// Need DB Connection
$db=new My_db;
$db->connect();

// MyNG setting up...
init();

// Templates
$t = new Template($_SESSION['conf_system_root']."/admin/templates/");


// Check Authentication
if(isset($_SESSION['adm_id'])){

    // Administration Session has started
    header("Location:"."admin_home.php" );
}

if(isset($_POST['login'])){

    // El usuario ha enviado su información
    // de autenticación.

    // Comprobamos que los datos son correctos
    // con la base de datos

    $query = "SELECT adm_id, adm_login FROM myng_admin WHERE
        adm_login = '".$_POST['identificativo']."'
        AND adm_passwd = '".$_POST['password']."'";
    
    $db->query($query);

    if($db->num_rows() == "0"){

        // El Login ha sido incorrecto
        // Mostramos un mensaje de error y el
        // formulario nuevamente.

        $t->set_var("identificativo",$identificativo);
        $t->set_var("mensaje_error","Error: Login Incorrect");

        // Cambiamos la raiz para poder cargar estos archivos        
        $t->set_root($_SESSION['conf_system_root']."/admin/templates/");        

        // Asignamos la plantilla principal a main
        $main = "login_admin.htm";
        $t->set_file("main",$main);
 		// ---- Show the HTML ---- //
    	show_iface($main);
    	// ----------------------- //

    }else{

        // El login se ha producido de manera correcta,
        // Iniciamos la sesión del usuario.

        $db->next_record();

        // Inicializamos las variables de sesión del asociado
        $_SESSION['adm_login'] = $db->Record['adm_login'];
        $_SESSION['adm_id'] = $db->Record['adm_id'];

        //echo $db->Record['adm_id'];
        
        //session_register("Admin");        
        
        header("Location:"."admin_home.php" );

    }


}else{

    // Mostramos el formulario

    $main = "login_admin.htm";
    $t->set_file("main",$main);

    // ---- Show the HTML ---- //
    show_iface($main);
    // ----------------------- //


}


?>

