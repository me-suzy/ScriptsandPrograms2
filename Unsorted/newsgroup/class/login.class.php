<?

//      I will translate this comments as far as I can!
//      If you have any doubt, please contact me!

/*      Class Name: User
        Created: 13-10-2000
        Last Update: 14-02-2002
        Author: Carlos Sánchez

        Observaciones:
        1- Falta por completar el control de las entradas del usuario. Hay que vigilar que
        no inserte caracteres raros ni código HTML en el nombre de usuario, ni en el email.
        2- Completar los aspectos marcados con <---A Definir---> en los diferentes métodos
        5- Hay que depurar con mucho cuidado todas las conexiones a la BD. Puede que para logear a un
           usuario hagamos 4 conexiones para 4 consultas, en vez de hacer una conexion y 4 consultas.
                                        

*/


class User{

        // Principal Members of the class
        var $id;
        var $id_user;
        var $passwd;
        var $email;
        var $visible;

        // Secondary members
        var $fst_name;
        var $lst_name;
        var $nick;
        var $birth;
        var $gender;
        var $country;
        var $icq;
        var $id_skin;
        var $user_text;
        var $pic_url;

        // Users Stats members
        var $reg_timestamp;
        var $last_log_timestamp;
        var $num_logs;
        var $num_ads;
        var $perfil;

        // Methods
        function User($id,$hasspasswd="",$email="",$visible="",$country=""){

                //Class Constructor

                //OJO Por ahora este constructor únicamente se dedica a construir objetos Usuario
                //a través de el id_user, el passwd y el email. No se preocupa del resto del perfil
                //Se le pasa directamente el password tratado con el .js de MD5
                //si falta alguno de los argumentos, se le pasa una cadena vacia                               

                $this->id_user=$id;
                $this->passwd=$hasspasswd;
                $this->email=$email;
                $this->visible=$visible;
                $this->country=$country;

        }
        
        // This function get the usr_id off the database.
        function get_usr_id()
        {
	       	$db=new My_db;
            $db->connect();
        	$query="
                SELECT                 
                	usr_id                	                
                FROM myng_user 
                WHERE usr_name = '".$this->id_user."'";
    		$db->query($query);    		
            $db->next_record();
            return($db->Record['usr_id']);        	
        	
        }

        function get_id_user(){
                return $this->id_user;
        }

        function get_num_logs(){
                return $this->num_logs;
        }

        function set_last_log_timestamp($time){
                $this->last_log_timestamp=$time;
        }

        function getdb_passwd(){

                //Devuelve el password de cierto usuario del sistema
                $db=new My_db;
                $db->connect();
                $consulta=sprintf("SELECT usr_passwd FROM myng_user WHERE usr_name='%s'",$this->id_user);                
                $db->query($consulta);
                $db->next_record();
                return($db->Record['usr_passwd']);
        }

        function getdb_stats(){

                //Consulta todas las estadisticas de la Base de Datos
                $db=new My_db;
                $db->connect();
                $consulta=sprintf("SELECT 
                
                	usr_email,
                	usr_passwd,
                	usr_last_log_timestamp,
                	usr_reg_timestamp,
                	usr_num_logs                	
                
                FROM myng_user WHERE usr_name='%s'",$this->id_user);
                
                $db->query($consulta);
                $db->next_record();

                //Actualizamos las variables del objeto
                $this->email=$db->Record['usr_email'];
                $this->reg_timestamp=$db->Record['usr_reg_timestamp'];
                $this->last_log_timestamp=$db->Record['usr_last_log_timestamp'];
                $this->num_logs=$db->Record['usr_num_logs'];
                //$this->num_ads=$db->Record['num_ads'];
                //$this->perfil=$db->Record['perfil'];

        }

        function setdb_stats(){

                //Graba todas las estadísticas en la Base de Datos
                $db=new My_db;
                $db->connect();
                $consulta=sprintf("UPDATE myng_user SET 
                	usr_last_log_timestamp=$this->last_log_timestamp,
                 	usr_num_logs=$this->num_logs                 
                WHERE usr_name='%s'",$this->id_user);
                $db->query($consulta);
        }


        function recoge_perfil(){	//Consulta todo el perfil de la Base de Datos

        }

        function guarda_perfil(){ //Graba todo el perfil en la Base de Datos

        }

        function cambia_password(){

        }

        function cambia_email(){

        }

        function cambia_icq(){

        }

        function cambia_text(){

        }


        // Email validation function taken from zend.com, written by John Coggeshall

        function ValidateEMail($Email) {
            
            global $HTTP_HOST;
            $result = array();

            if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $Email)) {

                // Be careful. We can cheat the system with address like 'user@my.false.domain';
                // mail_server_validation should be always 'true'.
                $result[0] = false;
                $result[1] = $Email._MYNGNOT_PROPERLY_FORMATTED;
                return $result;

            }

            if($_SESSION['conf_sec_validate_email_yn'] == "Y"){

                list ( $Username, $Domain ) = split ("@",$Email);

                if (getmxrr($Domain, $MXHost))  {
                        $ConnectAddress = $MXHost[0];
                } else {
                        $ConnectAddress = $Domain;
                }

                // Optional part (Connect to the server)

                $Connect = fsockopen ( $ConnectAddress, 25 );

                if ($Connect) {

                        if (ereg("^220", $Out = fgets($Connect, 1024))) {

                                fputs ($Connect, "HELO $HTTP_HOST\r\n");
                                $Out = fgets ( $Connect, 1024 );
                                fputs ($Connect, "MAIL FROM: <{$Email}>\r\n");
                                $From = fgets ( $Connect, 1024 );
                                fputs ($Connect, "RCPT TO: <{$Email}>\r\n");
                                $To = fgets ($Connect, 1024);
                                fputs ($Connect, "QUIT\r\n");
                                fclose($Connect);

                                if (!ereg ("^250", $From) ||!ereg ( "^250", $To )) {
                                        $result[0] = false;
                                        $result[1] = _MYNGSERVER_REJECT;
                                        return $result;

                                }

                        }else{

                                $result[0] = false;
                                $result[1] = _MYNGNO_RESPONSE;
                                return $result;
                        }

                }else{

                        $result[0] = false;
                        $result[1] = _MYNGCANT_CONNECT_SERVER;
                        return $result;
                }

            }

            $result[0] = true;
            $result[1] = $Email._MYNGAPPEARS_VALID;

            return $result;

            } // end of function



        function check_email(){

                //Comprueba si existe esa dirección de correo en el sistema
                //todas las direcciones de correo deben ser diferentes

                $db=new My_db;
                $db->connect();
                $consulta=sprintf("SELECT usr_id,usr_passwd,usr_email FROM myng_user WHERE usr_email='%s'",$this->email);
                $db->query($consulta);
                if($db->num_rows()!= 0){
                        //Esa dirección de correo ya la tiene otro usuario
                        $ok=1;
                }else{
                        //La dirección de correo no existe en el sistema
                        $ok=0;
                }
                return($ok);
        }


        function check_id_user(){

                //Comprueba si existe ese id_user dentro del sistema

                $db=new My_db;
                $db->connect();
                $consulta=sprintf("SELECT usr_name FROM myng_user WHERE usr_name='%s'",$this->id_user);
                //echo $consulta;
                $db->query($consulta);
                if($db->num_rows()!=0){
                        //Ese id_user ya lo tiene otro usuario
                        $ok=1;
                }else{
                        //Ese id_user está libre
                        $ok=0;
                        }
                return($ok);
        }

        function go_online($time_in){

                // Insert the user in 'myng_people_online'
                $db=new My_db;
                $db->connect();
                $consulta=sprintf("REPLACE INTO myng_user_online (
                	
                	uonl_usr_name,
                	uonl_session_time,
                	uonl_message_inbox,
                	uonl_message_time,
                	uonl_message_from
                
                	) VALUES('%s','%s','Welcome Home, %s!!','%s','System')",$this->id_user,$time_in,$this->id_user,$time_in);
                $db->query($consulta);

        }

        function go_offline(){

                // Delete the user entry from the myng_people_online table
                $db=new My_db;
                $db->connect();
                $consulta=sprintf("DELETE FROM myng_user_online WHERE uonl_usr_name = '%s'",$this->id_user);
                //echo $consulta;
                $db->query($consulta);

        }

        function is_online(){

                // If after 2 minutes the user has not visitted any page
                // maybe somebody has thrown him from the 'myng_people_online' table.
                // Update the state of the user in 'myng_people_online'.

                $current_time=time();

                $db=new My_db;
                $db->connect();
                $consulta=sprintf("SELECT uonl_usr_name FROM myng_user_online WHERE uonl_usr_name='%s'",$this->id_user);
                $db->query($consulta);
                if($db->num_rows()!=0){
                        // The user is in 'myng_people online'
                        // Update the Session variables in 'myng_people_online'
                        $consulta=sprintf("UPDATE myng_user_online SET uonl_session_time='%s' WHERE uonl_usr_name='%s'",$current_time,$this->id_user);
                        $db->query($consulta);
                }else{
                        // The user is NOT in 'myng_people_online' and he should.
                        // Insert the use in 'myng_people_online'
                        $this->go_online($current_time);
                }
        }


        function new_woop(){

                //Comprobamos si el usuario tiene mensajes woop nuevos
                $db=new My_db;
                $db->connect();
                $consulta=sprintf("SELECT message_inbox,message_from,message_time FROM myng_people_online WHERE id_user='%s'",$this->id_user);
                $db->query($consulta);
                $db->next_record();
                //Si el último mensaje se te ha enviado en los últimos 2 minutos se
                //considera nuevo, y se cambia el dibujo de la cabecera.
                $current_time=time();
                if(($db->Record['message_time']+120)>$current_time){
                        $new_message="1";
                }else{
                        $new_message="0";
                }

                return $new_message;

                //Registramos en la sesion los datos de los mensajes.

                global $Session;
                $Session['woop']['message_inbox']=$db->Record['message_inbox'];
                $Session['woop']['message_time']=$db->Record['message_time'];
                $Session['woop']['message_from']=$db->Record['message_from'];

                session_register('Session');
        }


        function register_user($hasspasswd2){

                // New user registration

                $db=new My_db;
                $db->connect();

                // User entries check
                // <-- To Define -->


                // Check if the 2 passwords are equal
                if($hasspasswd2 == $this->passwd){
                        $passwd_ok = 1;
                }else{
                        $passwd_ok = 0;
                }

                // Check if the password is "" (blank)
                if($this->passwd == "d41d8cd98f00b204e9800998ecf8427e"){
                        $passwd_blank = 1;
                }else{
                        $passwd_blank = 0;
                }

                // Check if the 'id_user' is unique
                if($this->check_id_user($this->id_user)){
                        // The id_user already exists
                        $id_user_ok=0;
                }else{
                        // The id_user is OK
                        $id_user_ok=1;
                }

                // Email Check (Format and Mail server query)

                $email_ok = $this->validateEmail($this->email);
                if(!$email_ok[0]){
                        $email_valid = 0;
                        $email_error = $email_ok[1];
                }else{
                        $email_valid = 1;
                }

                // Check if the email is unique

                if($this->check_email($this->email)){
                        // Email exists
                        $email_ok=0;
                }else{
                        // Email is unique!
                        $email_ok=1;
                }

                if($id_user_ok && $email_ok && $email_valid && $passwd_ok &&!$passwd_blank){

                        // Perform the registration

                        // Take the current time and date
                        $this->reg_timestamp=time();

                        // Define the initial user stats
                        $this->last_log_timestamp=$this->reg_timestamp;
                        $this->num_log = 1;

                        // Send the query
                        $consulta=sprintf("                        
                        INSERT INTO myng_user (
                        
                        	usr_name,
                        	usr_passwd,
                        	usr_email,
                        	usr_email_visible_yn,
                        	usr_reg_timestamp,
                        	usr_last_log_timestamp,
                        	usr_num_logs,
                        	usr_country
                        
                        ) VALUES('%s','%s','%s','%s','%s','%s','%s','%s')",$this->id_user,$this->passwd,$this->email,$this->visible,$this->reg_timestamp,$this->last_log_timestamp,$this->num_logs,$this->country);
                        $db->query($consulta);

                        // Log the user for the first time
                        // Create the asociative array 'Session', and register it into the session
						
                        $_SESSION['usr_id']=$this->get_usr_id();                        
                        $_SESSION['usr_name']=$this->id_user;
                        $_SESSION['usr_email']=$this->email;
                        $_SESSION['usr_visible_yn']=$this->visible;
                        $_SESSION['usr_country']=$this->country;
                        $_SESSION['usr_las_log_timestamp']=$this->last_log_timestamp;
                        $_SESSION['usr_reg_timestamp']=$this->reg_timestamp;
                        
                        // We put the user into 'myng_people_online' table
                        $this->go_online(time());

                        $causa['ok']=1;
                        $causa['message'] = _MYNGREGISTRATION_COMPLETED;
                        return $causa;

                }else{
                        // There's have been an error
                        if(!$id_user_ok){
                                // 'id_user' is not unique
                                $causa['ok']=0;
                                $causa['message'] = _MYNGUSERNAME_TAKEN;
                                return $causa;
                        }
                        if(!$passwd_ok){
                                // Passwords don't match
                                $causa['ok']=0;
                                $causa['message'] = _MYNGINVALID_PASSWORD;
                                return $causa;
                        }
                        if(!$email_ok){
                                // Email is not unique
                                $causa['ok']=0;
                                $causa['message'] = _MYNGEMAIL_TAKEN;
                                return $causa;

                        }
                        if(!$email_valid){
                                // Email is not valid
                                $causa['ok']=0;
                                $causa['message'] = $email_error;
                                return $causa;

                        }
                        if($passwd_blank){
                                // The password is blank
                                $causa['ok']=0;
                                $causa['message'] = _MYNGBLANK_PASSWORD;
                                return $causa;

                        }
                }
        }



        function login_user($challenge,$response){
		        	
        		$db=new My_db();
                $db->connect();
                // Check if the user is registered, and get the hasspassword from the DB
                if($this->check_id_user($this->id_user)){

                        // The id_user IS in the DB, get the password
                        $id_user_ok=1;
                        $this->passwd=$this->getdb_passwd();
                        //echo $this->passwd;
                        // Calculate the 'response' and check if it's the same as the user's has sent.
                        $string=md5($this->id_user.":".$this->passwd.":".$challenge);

                        if($string==$response){

                                // Authentication got well, log in the user
                                $login_ok=1;
                                // Update the user variables
                                // Firstly get the stats fromt he db
                                $this->getdb_stats();
                                // Update them
                                $this->last_log_timestamp=time();
                                $this->num_logs=$this->num_logs+1;
                                // And store them
                                $this->setdb_stats();

                                // Create the asociative array 'Session'
		                        $_SESSION['usr_id']=$this->get_usr_id();		                        
		                        $_SESSION['usr_name']=$this->id_user;
                        		$_SESSION['usr_email']=$this->email;
                        		$_SESSION['usr_visible_yn']=$this->visible;
                        		$_SESSION['usr_country']=$this->country;
                        		$_SESSION['usr_las_log_timestamp']=$this->last_log_timestamp;
                        		$_SESSION['usr_reg_timestamp']=$this->reg_timestamp;                               

                                // Check if it's in 'myng_people_online'
                                $this->is_online();

                                $causa['ok']=1;
                                $causa['message'] = _MYNGLOGGED;
                                return($causa);

                        }else{
                                // Incorrect login. Authentication error
                                $login_ok=0;
                                $causa['ok']=0;
                                $causa['message'] = _MYNGLOGIN_INCORRECT;
                                return $causa;
                        }

                }else{
                		
                        // 'id_user' is not in the system.
                        $id_user_ok=0;
                        $causa['ok']=0;
                        $causa['message'] = _MYNGLOGIN_INCORRECT;
                        return $causa;
                }

}


}


?>