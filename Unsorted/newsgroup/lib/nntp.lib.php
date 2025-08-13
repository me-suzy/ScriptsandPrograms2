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


//------------------------------------------------------------------//
// nntp.lib.php
//
// Original Author: Florian ArmHein, included in NewsPortal.
// Modified by: Carlos Sánchez
// Created: 08/02/02
// Last Modified: 11/02/02
//
// Description: NNTP Library. Functions to talk with the news
//              server
//
// Note: 	This library should be a generic NNTP library, but
//			it is not. If you're looking for that check Net_NNTP class
//			at PEAR. MyNewsGroups :) should work with that class
//			in future versions.
//
// Status:	New DB update NOT ready.
//------------------------------------------------------------------//

class newsGroupType {
    var $id;
    var $name;
    var $description;
    var $count;
    var $newArticles;
    var $numPosted;
    var $numUnread;
    var $server;
    var $allow_post;
}

// Class for the thread.php page.
class articleHeader{
    
    var $date;
    var $newsgroup;
    var $subject;
    var $sendername;
    var $username;
    var $email;
    var $number;
    var $id;
    var $id_article;
    
}

/*
* Stores the Header of an article
*/
class articleType {
    var $number; // the Number of an article inside a group //Each server has a different one
    var $id;     // Message-ID // This is unique in a Usenet message
    var $from;   // eMail of the author
    var $name;   // Name of the author
    var $subject; // the subject
    var $newsgroups;  // the Newsgroups where the article belongs to
    var $followup;
    var $date;
    var $organization;
    var $references;
    var $content_transfer_encoding;
    var $answers;
    var $isAnswer;
    var $username;
    var $user_agent;
    var $isReply;
    var $isNew;   //Append new articles BUG FIX, not stored in DB
}

//--------------------------------------------------//
// Name:        list_groups
// Task:        make a list of the Groups from news server.
// Description: We are:
//		 - making nntp connection to server
//		 - saying "list" to server
//		 - parsing result and returning an array of strings
//
// In:          $server - Server.
//
// Out:         array of strings
//		where every string is a $group_name - parsed group_name
//
//
// TODO:        delete groupnames that we've got in DB
//
//--------------------------------------------------//

function list_groups($server){
    
    //getting newsserver parameters from DB
    $db=new My_db;
    $db->connect();
    
    $consulta = sprintf("SELECT * from myng_server WHERE serv_host = '%s'",$server);
    $db->query($consulta);
    $db->next_record();
    $server = $db->Record['serv_host'];
    $port = $db->Record['serv_port'];
    $login = $db->Record['serv_login'];
    $passwd = $db->Record['serv_passwd'];
    
    // making newsserver connection
    $ns=OpenNNTPconnection($server,$port,$login,$passwd);
    if ($ns == false){
        $return[0] = "false";
        $return[1] = _MYNGCON_ERROR;
        $return[2] = "server = ".$server.$port.$login.$passwd;
        return $return;
    }
    
    
    // reading result from $ns of the "list" command
    fputs($ns,"list \r\n");
    $list = liesZeile($ns);  // skipping a line
    $list = ""; // array of string to store reply
    
    $neu=liesZeile($ns);                    // Leemos otra linea
    do {                                    // "."
    $list .= $neu."\n";
    if ($neu != ".") $neu=liesZeile($ns);
    } while ($neu != ".");
    
    
    //parsing returned "list" for qouted.. and "\n"
    if ($encoding == "quoted-printable"){
        $list=quoted_printable_decode($list);
    }
    $list=str_replace("\n..\n","\n.\n",ereg_replace("\r\n","\n",$list));
    $list = split("\n",$list);
    
    
    // living only groupnames in result of "list"
    foreach ($list as $string) {
        if ($string != "") {
            $alist[] = preg_replace ("/^\s*(\S*)\s*(\S*)\s*(\S*)\s*(\S*)\s*/","\\1",$string);
        }
    }
    
    return($alist);
    
}



//--------------------------------------------------//
// Name:        add_group
// Task:        Add a new Group to the System.
// Description: We query the server and we get the
//              description of the group and the number
//              of available messages. We also get the
//              first and last article, and store all the
//              articles available in the DB.
//
// In:          $group_name - Name of the newsgroup.
//              $server - Server.
//              $num_articles - Number of articles to get.
//              $zip_articles
//
// Out:         $result
//
// TODO:        Beware with the return! (false|true)
//
//--------------------------------------------------//

function add_group($group_name,$server,$num_articles,$zip_articles,$grp_allow_post_yn){
    
    
    $db=new My_db;
    $db->connect();
    
    $consulta = "SELECT * from myng_server WHERE serv_host = '".$server."'";
    $db->query($consulta);
    $db->next_record();
    
    $server = $db->Record['serv_host'];
    $serv_id = $db->Record['serv_id'];
    $port = $db->Record['serv_port'];
    $login = $db->Record['serv_login'];
    $passwd = $db->Record['serv_passwd'];
    
    $ns=OpenNNTPconnection($server,$port,$login,$passwd);
    if ($ns == false){
        $return[0] = "false";
        $return[1] = _MYNGCON_ERROR;
        return $return;
    }
    
    $gruppe = new newsGroupType;
    $gruppe->name = $group_name;
    
    fputs($ns,"xgtitle $gruppe->name\r\n");   // Mandamos un comando a NNTP xgtitle??
    $response=liesZeile($ns);                 // Recogemos la respuesta
    if (strcmp(substr($response,0,3),"282") == 0) {     // Comprobamos si es 282 (Código de respuesta??)
    $neu=liesZeile($ns);                    // Leemos otra linea
    do {                                    // Leemos hasta que encontramos el final '.'
    $response=$neu;
    if ($neu != ".") $neu=liesZeile($ns);
    } while ($neu != ".");
    $desc=strrchr($response,"\t");
    if (strcmp($response,".") == 0) {
        $desc="-";
    }
    } else {
        $desc = $response;  //We got the description
    }
    if (strcmp(substr($response,0,3),"500") == 0) // 500 - command not recognized
    $desc="-";
    // A veces no los reconoce con el 501
    if (strcmp(substr($response,0,3),"501") == 0) // 501 - command not recognized
    $desc="-";
    
    // Now we get the description of the Group from the newserver, and the number of
    // available articles at the newsserver.
    // If we want to store the description information in the DB, here is
    // where we can do it.
    
    if (strcmp($desc,"") == 0) $desc="-";
    
    // We trim the whitespaces at hte beginning of the Description
    $gruppe->description=ltrim($desc);                  // Hemos conseguido la descripción del grupo
    fputs($ns,"group ".$gruppe->name."\r\n");    // Mandamos el comando 'group' por NNTP
    $response=liesZeile($ns);
    $t=strchr($response," ");
    $t=substr($t,1,strlen($t)-1);
    $gruppe->count=substr($t,0,strpos($t," "));  // Get number of messages
    
    //Now we have to query the NewsServer about the First and last article
    fputs($ns,"group $group_name\r\n");   // select a group
    $groupinfo=explode(" ",liesZeile($ns));
    
    /*
    echo $groupinfo[0]."<br>";
    echo $groupinfo[1]."<br>";
    echo $groupinfo[2]."<br>";
    echo $groupinfo[3]."<br>";
    */
    
    
    if (substr($groupinfo[0],0,1) != 2) {         //We firstly compare the code, if it's not 2xx, error!
    
    $result[0] = "false";
    $result[1] = _MYNGNO_GROUP.$server;
    return $result;
    
    
    flush();
    
    } else {      //Code is correct, we can go on
    
    // With this part, we only get the 'num_articles' ordered from the server
    $first_article = $groupinfo[2] - 1;
    $last_article = $groupinfo[3];
    
    //echo $first_article."   ".$last_article."   ".$num_articles;
    
    $num_articles_available = ($groupinfo[3] - $groupinfo[2]) + 1;
    
    // 0.5 RC3 new specification
    // Newsgroups get registered with the total available number of articles
    // to download, or a fixed number.
    // If we want less articles that the available ones...
    if($num_articles <= $num_articles_available && $num_articles != "*"){
        // We WILL download only the last 'num_articles' articles
        $first_article = ($last_article - $num_articles) + 1;
        $num_articles_available = ($last_article - $first_article);
        $last_article = $first_article;
    }else{
        $last_article = $first_article;
    }
    
    // Won't download any articles now
    $gruppe->count = 0;
    
    $db=new My_db;
    $db->connect();   //Connect to the Database
    $query = "INSERT IGNORE INTO myng_newsgroup (
                				
                				grp_name,
                				grp_description,
                				grp_num_messages,
                				grp_first_article,
                				grp_last_article,
                				grp_num_available,
                				grp_serv_id,
                				grp_allow_post_yn,
                				grp_activity_index                                
				                )VALUES(                				
                				'".$gruppe->name."',
                				'".$gruppe->description."',
                				'".$gruppe->count."',
                				'".$first_article."',
                				'".$last_article."',
                				'".$num_articles_available."',
                				'".$serv_id."',
                				'".$grp_allow_post_yn."',
                				'0.1')";                
    
    $db->query($query);
    
    // The Data about the Group was Inserted.
    $result[0] = "true";
    
    
    }
    
    //Create the table name_of_the_group
    
    $group_name = real2table($group_name);
    
    $ref_table = "myng_ref_".$group_name;
    $table_name = "myng_".$group_name;
    
    // MySQL query to create the table where the articles are stored for this group
    $consulta = sprintf("CREATE TABLE `%s` (
        `id_article` int(10) unsigned NOT NULL auto_increment,
        `id` varchar(120) NOT NULL default '',
        `number` int(10) unsigned NOT NULL default '0',
        `from_header` varchar(30) NOT NULL default '',
        `name` varchar(30) NOT NULL default '',
        `subject` tinytext NOT NULL,
        `newsgroup` varchar(40) NOT NULL default '',
        `newsgroups` tinytext NOT NULL,
        `followup` varchar(120) default NULL,
        `date` varchar(30) NOT NULL default '',
        `organization` varchar(50) default NULL,
        `content_transfer_encoding` varchar(20) NOT NULL default '',
        `parent` varchar(120) default NULL,
        `isanswer` char(3) NOT NULL default '0',
        `username` varchar(30) NOT NULL default '',
        `user_agent` varchar(40) default NULL,
        `isreply` char(1) NOT NULL default '0',
        `num_readings` bigint(20) unsigned DEFAULT '0' NOT NULL,
        `body` text NOT NULL,
        FULLTEXT(`body`,`subject`), FULLTEXT(`subject`),        
        PRIMARY KEY  (`id_article`),     
        UNIQUE KEY `id` (`id`), 	
		KEY `number` (`number`), 
        KEY `date` (`date`), 
        KEY `isanswer` (`isanswer`)
        
        );",addslashes($table_name));
    
    addslashes($consulta);
    
    $db->query($consulta);
    
    // MySQL query to create the table where the references for this group are stored
    $consulta = sprintf("CREATE TABLE `%s` (`id_article` varchar(60) NOT NULL default '',`reference` varchar(60) NOT NULL default '0',PRIMARY KEY  (`id_article`,`reference`))",addslashes($ref_table));
    
    addslashes($consulta);
    
    $db->query($consulta);
    // We change again the name of the group
    $group_name = table2real($group_name);
    
    
    return $result;
    
}// End of function


//--------------------------------------------------//
// Name:        check_new_articles
// Task:        Check if we have new articles in the NewsServer
// Description: We get the 'first_Article' and 'last_article'
//              parameters from the NewsServer and compare them
//              with the ones in the DB. If !=, we update the DB.
//
// In:          $groupname - Name of the newsgroup
//              $num_messages - Number of messages in the group
// Out:         $new_articles
//
//--------------------------------------------------//

function check_new_articles(&$von,$groupname,$last_article_in_db,$num_messages){
    
    //global $_SESSION['debug_text'];
    
    $db=new My_db;
    $db->connect();                                     //Connect to the Database
    
    $query = "SELECT max(number) FROM `myng_" . real2table($groupname)."`";
    $db->query($query);
    $db->next_record();
    $last_article_new = $db->Record[0];
    
    // Check new messages
    // Now we have to query the NewsServer about the First and last article
    fputs($von,"group $groupname\r\n");   // select a group
    $groupinfo=explode(" ",liesZeile($von));
    if (substr($groupinfo[0],0,1) != 2) {         //We firstly compare the code, if it's not 2xx, error!
    
    //********** Error Handling ***********************//
    echo "<p>".$text_error["error:"]."</p>";
    echo "<p>".$text_thread["no_such_group"]."</p>";
    //*************************************************//
    flush();
    
    } else {      //Code is correct, we can go on
    
    // first_article = groupinfo[2]
    // last_article = groupinfo[3]
    //$num_available_articles = ($groupinfo[3] - $groupinfo[2]) + 1;
    
    // Firstly we have to compare the first and last article with the ones in the DB
    // The First article maybe it's different, but we have to leave the first_article
    // parameter remain the same since we got it for the first time.
    // With the 'last_article' parameter we can know how many new articles we got.
    
    //echo "Disponible: ".$groupinfo[3];
    //echo "Last in DB: ".$last_article_in_db;
    
    if($last_article_in_db < $groupinfo[3]){
        // We have new articles! :)
        // We update the newsgroups in the DB
        $num_new_messages = $groupinfo[3] - $last_article_in_db;
        
        //-------- MyNG v 0.5 Hack by Txarly :) ---------------//
        //-------- New Indexing and downloading system --------//
        // We don't have to download ALL the new articles.
        // We'll download only $_SESSION['conf_down_num_articles']
        //echo $num_new_messages." mensajes nuevos.";
        $_SESSION['debug_text'] = $_SESSION['debug_text'].$num_new_messages." new messages. <br>";
        $_SESSION['debug_text'] = $_SESSION['debug_text'].$_SESSION['conf_down_num_articles']." articles to download. <br>";
        //echo $_SESSION['conf_down_num_articles']."Articulos a descargar";
        
        if($num_new_messages > $_SESSION['conf_down_num_articles']){
            // We have too much articles in the server right now.
            // We'll fetch now a few, and more will come later :)
            $num_new_messages = $_SESSION['conf_down_num_articles'];
        }
        //-----------------------------------------------------//
        
        $total_messages = $num_new_messages + $num_messages;
        //echo "Mensajes Totales: ".$total_messages;
        
        /*
        $consulta =
        "UPDATE myng_newsgroup SET
        grp_last_article = '".($last_article_in_db + $num_new_messages)."',
        grp_num_messages='".$total_messages."',
        grp_num_available = grp_num_available - ".$num_new_messages."
        WHERE grp_name = '".$groupname."'";
        $db->query($consulta);
        */
        //echo $consulta;
        
        $new_articles = $num_new_messages;
        
        // Now we have to grab all the new articles and store them in the DB
        // First article to store = $last_article_in_db
        // Last article to store = $groupinfo[3];
        $first_article = $last_article_in_db + 1;
        //$last_article = $groupinfo[3];
        $last_article = $last_article_in_db + $num_new_messages;
        
        //echo "Primero: ".$first_article;
        //echo "Ultimo: ".$last_article;
        
        $_SESSION['debug_text'] = $_SESSION['debug_text']."First: ".$first_article;
        $_SESSION['debug_text'] = $_SESSION['debug_text']."Last: ".$last_article;
        
        
        // Store the new articles
        $spoolopenmodus = "a"; //Append new articles to the system
        
        store_articles($von,$first_article,$last_article,$groupname,$spoolopenmodus);
        
        $query = "SELECT max(number),min(number) FROM `myng_" . real2table($groupname)."`";
        $db->query($query);
        $db->next_record();
        $last_article_new = $db->Record[0];
        
        $consulta =
        "UPDATE myng_newsgroup SET
                        		grp_last_article = '".($last_article_new)."',
                        		grp_num_messages = '" . ($last_article_new - $db->Record[1] + 1) . "',
                        		grp_num_available = '" . ($groupinfo[3] - $last_article_new) ."' 
                       		WHERE grp_name = '".$groupname."'";
        $db->query($consulta);
        
        $new_articles = $num_new_messages;
        
    }
    
    if($last_article_in_db == $groupinfo[3]){
        // We don't have new articles :(
        // We do nothing
        $new_articles = 0;
    }
    
    if($last_article_in_db > $groupinfo[3]){
        // More articles in the System than in the Server ?? O_O
        // Maybe we have changed the NewsServer :(
        $new_articles = 0;
    }
    
    }
    
    return $new_articles;
    
}//function's end



//--------------------------------------------------//
// Name:        store_articles
// Task:        Grab articles from the newsserver and store
//              them in the DB.
// Description: We get all the articles from $firstarticle
//              to $lastarticle.
//
// In:          $firstarticle
//              $lastarticle
//              $von - File Descriptor
//              $group_name
// Out:
//
//--------------------------------------------------//


function store_articles(&$von,$firstarticle,$lastarticle,$group_name,$spoolopenmodus){
    
    //---------------- Grab all the articles from the NewsServer --------------------//
    
    //echo $firstarticle."<br>";
    //echo $lastarticle."<br>";
    
    $db=new My_db;
    $db->connect();                                     //Connect to the Database
    
    fputs($von,"list overview.fmt\r\n");  // find out the format of the
    $tmp=liesZeile($von);                 // xover-command
    $zeile=liesZeile($von);
    while (strcmp($zeile,".") != 0) {
        $overviewfmt[]=$zeile;
        $zeile=liesZeile($von);
    }
    $overviewformat=implode("\t",$overviewfmt);   //This is important for interpreting the overview format
    
    //echo $firstarticle." ".$lastarticle;
    fputs($von,"xover ".($firstarticle)."-".$lastarticle."\r\n");  // Read the overview (with the xover command)
    //fputs($von,"xover ".($firstarticle+1)."-".$lastarticle."\r\n");  // Read the overview (with the xover command)
    $tmp=liesZeile($von);                                        // From the first to the last article available...
    if (substr($tmp,0,3) == "224") {                             // we try to query the news server about the headers
    $zeile=liesZeile($von);                                    // and we format them with the interpretOverviewLine fuction.
    while ($zeile != ".") {
        $article=interpretOverviewLine($zeile,$overviewformat,$group_name);
        
        /* No Append new articles bug in MyNewsgroups??
        //-------- Append New Articles Bug Fix ---------//
        //
        // If we get new articles, then in saveThreadData we
        // rebuild ALL the articles in the DB, breaking all
        // the relations of the articles.
        if($spoolopenmodus == "a"){
        $article->isNew = "1";
        }
        //----------------------------------------------//
        */
        
        $headers[$article->id]=$article;      // So here we fetch the new articles from the server.
        $zeile=lieszeile($von);               // We insert the headers in the DBin the showheaders function, not here.
    }
    }//End of 'if'
    
    //----------- Here Begins the DB integration, second phase ------------------//
    //
    // Here we can query the Newsserver in order to get all the remaining header
    // information and the body of each of new the articles stored at the server.
    // Then we can use this information to store it directly in the DB.
    // Maybe after finish this, we can just get rid of the query about the overview
    // of the articles, and just fetch them all when they are new, ONCE.
    //---------------------------------------------------------------------------//
    
    // We just need the article numbers to query the News Server about the headers and bodies
    for($i=($firstarticle);$i<=($lastarticle);$i++){
        //for($i=($firstarticle+1);$i<=($lastarticle);$i++){
        //echo "Indice: ".$i."<br>";
        $d = readHeader($von,$group_name,$i);
        $cadena="";
        if (strcmp($d,false) != 0) {
            //$body is an array of strings. Each element contains a sentence.
            $body = readBody($von,$i,$d->content_transfer_encoding);
            for ($j=0; $j<=count($body)-1; $j++) {
                $b=textwrap($body[$j],80,"\n");
                if ((strpos(substr($b,0,strpos($b," ")),'>') != false ) | (strcmp(substr($b,0,1),'>') == 0)) {
                    $b = htmlparse(htmlspecialchars($b));
                    
                    //This line is wrong, it doesn't work
                    $cadena = $cadena."<i>".$b."</i>"."\n";
                } else {
                    $b = htmlparse(htmlspecialchars($b));
                    $cadena = $cadena.$b."\n";
                }
            }
        }
        
        //We got the headers and the body, now we try to insert the info in the DB
        //The query for storing the complete headers and the body in the 'article' table
        //echo $group_name;
        
        $group_name_for_table = real2table($group_name);
        
        //*******************************************************
        // BEWARE!! The character '-' is not allowed in a table name, and it appears in some newsgroup
        // names, so we have to modify it also.
        //*******************************************************
        
        //echo $group_name_for_table;
        $ref_table = "ref_".$group_name_for_table;
        
        $consulta = sprintf("INSERT IGNORE INTO `%s` VALUES ('','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','','','%s','%s','','','')","myng_".$group_name_for_table,$d->id,$i,addslashes($d->from),addslashes($d->name),addslashes($d->subject),$group_name,$d->newsgroups,$d->followup,$d->date,addslashes($d->organization),$d->content_transfer_encoding,addslashes($d->username),addslashes($d->user_agent));
        $db->query($consulta);
        //echo $consulta;
        $body = addslashes($cadena);
        if($_SESSION['conf_system_zlib_yn'] == "Y"){
            // Compress the body before storing it
            $body = gzcompress($body);
            $body = addslashes($body);
        }
        
        $consulta = sprintf("UPDATE `%s` SET body = '%s' WHERE newsgroup = '%s' AND number = '%s'","myng_".$group_name_for_table,$body,$group_name,$i);
        $cadena = "";
        $db->query($consulta);
        
    }//End of 'for'
    
    // Update all the information in the DB with the info got with the Overview
    // and necessary fot the system and the visualization
    
    // w mode is not used now! O_O
    if($spoolopenmodus == "w"){     // w - Fetch the articles for the first time
    
    /*
    echo "Eooooooooooooooooooooo!!";
    $article_count=0;
    if ($headers == false) {
    // Muere aquí??
    //echo "kkuuuu".$text_thread["no_articles"];
    } else {
    
    reset($headers);
    $c=current($headers);      // read one article
    for ($i=0; $i<=(count($headers)); $i++) {
    
    //This is just Another FUCKING OOP BUG of PHP4!!
    //FUCKING OOP!!
    $mierda = $c->isAnswer;
    //Look OUT!! isAnswer is false by default
    $ref="";
    
    if (($mierda == "0") && (isset($c->references[0]))) {   // is the article an answer to an
    // other article?
    $o=count($c->references)-1;
    $ref=$c->references[$o];
    while (($o >= 0) && (!isset($headers[$ref]))) {  // try to find a
    $ref=$c->references[$o];                       // matching article
    $o--;                                          // to the reference
    }
    if ($o >= 0) {
    // found a matching article?
    //$c->isAnswer=true;    // so the Article is an answer
    //$c->isAnswer ="1";
    $headers[$c->id]=$c;
    $headers[$c->id]->isAnswer = "1";
    $mierda="1";
    //This is the only thing that we don't store in the DB
    //And we must do it ####!!!!!
    $headers[$ref]->answers[]=$c->id;  // the referenced article gets
    // the ID if the article
    }
    }
    
    // Update all the new data in the DB
    $consulta = sprintf("UPDATE `%s` SET parent = '%s', isanswer = '%s', isreply ='%s' WHERE newsgroup ='%s' AND id ='%s' ","myng_".$group_name_for_table,$ref,$mierda,$c->isReply,$group_name,$c->id);
    //echo "<br>".$consulta;
    $db->query($consulta);
    
    //Now we must store the all the references in the 'ref_article' table
    $o=count($c->references);
    for($j=0; $j<$o; $j++){
    $consulta = sprintf("INSERT IGNORE INTO `%s` VALUES ('%s','%s')","myng_".$ref_table,$c->id,$c->references[$j]);
    //echo "<br>".$consulta;
    $db->query($consulta);
    }
    
    
    $c=next($headers);
    }//End of for
    reset($headers);
    // Update all the new data in the DB
    //$spoolopenmodus = "w";
    //saveThreadData($headers,$group_name,$spoolopenmodus);
    } // End of else
    */
    
    }else{ // spoolopenmodus is "a" (append)
    
    //echo "Heeeeeeeeeeeeeeeey";
    
    $article_count=0;
    if ($headers == false) {
        //echo $text_thread["no_articles"];
    } else {
        
        reset($headers);
        $c=current($headers);      // read one article
        //echo "Artículos: ".count($headers);
        
        for ($i=0;$i<count($headers);$i++) {
            
            //echo "Indice2: ".$i."<br>";
            //This is just Another FUCKING OOP BUG of PHP4!!
            //FUCKING OOP!!
            $mierda = $c->isAnswer;
            $ref="";
            //Look OUT!! isAnswer is false by default
            if (($mierda == "0") && (isset($c->references[0]))) {   // is the article an answer to an
            // other article?
            //echo "Que Patxa...";
            
            $o=count($c->references);
            $ref=$c->references[($o-1)];
            //echo $ref;
            
            
            //echo "Principio: ".$o."<br>";
            
            //--- Append BUG Fix for MyNewsGroups :) ----------------------------------//
            while (($o >= 0) && ($flag == 0)) {
                //while (($o >= 0) && (!isset($headers[$ref]))) {      // try to find a
                //        $ref=$c->references[$o];                       // matching article
                //        $o--;                                          // to the reference
                //echo "Padre:".$c->references[$o-1];
                $consulta = sprintf("SELECT id FROM `%s` WHERE id = '%s'","myng_".$group_name_for_table,$c->references[$o-1]);
                $db->query($consulta);
                if($db->num_rows() != 0){
                    $flag = 1;
                    //echo $flag;
                    $db->next_record();
                    $ref = $db->Record['id'];
                    //echo "Padre??:".$ref;
                }else{
                    $flag = 0;
                    //echo $flag;
                }
                
                $o--;
                //-------------------------------------------------------------------------//
                
            }
            //echo "Final: ".$o."<br>";
            if ($flag == 1) {
                //if ($o >= 0) {
                // found a matching article?
                //$c->isAnswer=true;    // so the Article is an answer
                //$c->isAnswer ="1";
                $headers[$c->id]=$c;
                $headers[$c->id]->isAnswer = "1";
                $mierda = "1";
                //echo "Jodeee";
                //This is the only thing that we don't store in the DB
                //And we must do it ####!!!!!
                $headers[$ref]->answers[]=$c->id;  // the referenced article gets
                // the ID if the article
            }
            }
            
            // Update all the new data in the DB
            $consulta = sprintf("UPDATE `%s` SET parent = '%s', isanswer = '%s', isreply ='%s' WHERE newsgroup ='%s' AND id ='%s' ","myng_".$group_name_for_table,$ref,$mierda,$c->isReply,$group_name,$c->id);
            //echo "<br>".$consulta;
            $db->query($consulta);
            
            //Now we must store the all the references in the 'ref_article' table
            $o=count($c->references);
            for($j=0; $j<$o; $j++){
                $consulta = sprintf("INSERT IGNORE INTO `%s` VALUES ('%s','%s')","myng_".$ref_table,$c->id,$c->references[$j]);
                //echo "<br>".$consulta;
                $db->query($consulta);
            }
            
            $c=next($headers);
        }//End of for
        reset($headers);
        
    } // End of else
    
    
    }
    
}



//******************************************************************//
//-------------------- NewsPortal Functions ------------------------//
//
// Functions that remain nearly the same as NewsPortal
//
//------------------------------------------------------------------//

//--------------------------------------------------------------------//
// This function returns a formatted article, from an overview line
//--------------------------------------------------------------------//

function interpretOverviewLine($zeile,$overviewformat,$groupname) {
    $return="";
    $overviewfmt=explode("\t",$overviewformat);
    //echo " ";                // keep the connection to the webbrowser alive
    flush();                 // while generating the message-tree
    $over=split("\t",$zeile,count($overviewfmt)-1);
    $article=new articleType;
    for ($i=0; $i<count($overviewfmt)-1; $i++) {
        if ($overviewfmt[$i]=="Subject:") {
            $subject=headerDecode($over[$i+1]);
            $article->isReply=splitSubject($subject);
            $article->subject=$subject;
        }
        if ($overviewfmt[$i]=="Date:") {
            $article->date=getTimestamp($over[$i+1]);
        }
        if ($overviewfmt[$i]=="From:") {
            $fromline=adressDecode(headerDecode($over[$i+1]),"nirgendwo");
            if (!isset($fromline[0]["host"])) $fromline[0]["host"]="";
            $article->from=$fromline[0]["mailbox"]."@".$fromline[0]["host"];
            $article->username=$fromline[0]["mailbox"];
            if (!isset($fromline[0]["personal"])) {
                $article->name=$fromline[0]["mailbox"];
                if (strpos($article->name,'%') != false) {
                    $article->name=substr($article->name,0,strpos($article->name,'%'));
                }
                $article->name=strtr($article->name,'_',' ');
            } else {
                $article->name=$fromline[0]["personal"];
            }
        }
        if ($overviewfmt[$i]=="Message-ID:") $article->id=$over[$i+1];
        if (($overviewfmt[$i]=="References:") && ($over[$i+1] != ""))
        $article->references=explode(" ",$over[$i+1]);
    }
    $article->number=$over[0];
    //$article->isAnswer=false;
    $article->isAnswer="0";
    
    //----------- Info for the DB Integration ---------------------//
    //
    // With this function we get the $article objetc filled, and
    // the fields that are important are:
    // $article->isReply
    // $article->subject
    // $article->date
    // $article->from
    // $article->username
    // $article->name
    // $article->references
    // $article->number
    // $article->isAnswer
    //
    //-------------------------------------------------------------//
    
    return($article);
}



/*
* read the header of an article.
* $articleNumber can be the number of an article or its message-id.
*/

// With this function we can enquire a News server about an article
// and we can fill the headers object, with all de data available.

function readHeader(&$von,$group,$articleNumber) {
    global $text_error;
    fputs($von,"group $group\r\n");
    $zeile=lieszeile($von);
    fputs($von,"head $articleNumber\r\n");
    $zeile=lieszeile($von);
    if (substr($zeile,0,3) != "221") {
        //This article has been deleted.
        //It's no more in the group.
        //echo $text_error["article_not_found"];
        $header=false;
        return;
    }
    $hdr=array();
    $zeile=lieszeile($von);
    while(strcmp($zeile,".") != 0) {
        $hdr[]=$zeile;
        $zeile=lieszeile($von);
        
    }
    for ($i=count($hdr)-1; $i>0; $i--)
    if (preg_match("/^(\x09|\x20)/",$hdr[$i]))
    $hdr[$i-1]=$hdr[$i-1].ltrim($hdr[$i]);
    $header = new articleType;
    //Why is isAnswer put false by default?!
    $header->isAnswer="0";
    for ($count=0;$count<count($hdr);$count++) {
        $variable=substr($hdr[$count],0,strpos($hdr[$count]," "));
        $value=trim(substr($hdr[$count],strpos($hdr[$count]," ")+1));
        switch ($variable) {
            case "From:":
            $fromline=adressDecode(headerDecode($value),"nirgendwo");
            if (!isset($fromline[0]["host"])) $fromline[0]["host"]="";
            //We get $header->from
            $header->from=$fromline[0]["mailbox"]."@".$fromline[0]["host"];
            //We get $header->username
            $header->username=$fromline[0]["mailbox"];
            if (!isset($fromline[0]["personal"])) {
                $header->name="";
            } else {
                //We get $header->name
                $header->name=$fromline[0]["personal"];
            }
            break;
            case "Message-ID:":
            //We get $header->id
            $header->id=$value;
            break;
            case "Subject:":
            //We get $header->subject
            $header->subject=headerDecode($value);
            break;
            case "Newsgroups:":
            //We get $header->newsgroups
            //Hey, at last I Think that newsgroups isn't an array ?!
            $header->newsgroups=$value;
            break;
            case "Organization:":
            //We get $header->organization
            $header->organization=$value;
            break;
            case "Content-Transfer-Encoding:":
            //We get $header->content_transfer_encoding
            $header->content_transfer_encoding=trim(strtolower($value));
            break;
            case "References:":
            $ref=trim($value);
            while (strpos($ref,"> <") != false) {
                $header->references[]=substr($ref,0,strpos($ref," "));
                $ref=substr($ref,strpos($ref,"> <")+2);
            }
            $header->references[]=trim($ref);
            //We get $header->references (The whole array?)
            break;
            case "Date:":
            //We get $header->date
            $header->date=getTimestamp(trim($value));
            break;
            case "Followup-To:":
            //We get $header->followup
            $header->followup=trim($value);
            break;
            case "X-Newsreader:":
            case "X-Mailer:":
            case "User-Agent:":
            //We get $header->user_agent
            $header->user_agent=trim($value);
        }
    }
    if (!isset($header->content_transfer_encoding))
    //Hey!, I think that the system only allow text messages to display
    $header->content_transfer_encoding="8bit";
    
    return $header;
}


/*
* read the body of an article.
* $articleNumber can be the number of an article or its message-id.
*/
function readBody(&$von,$articleNumber,$encoding) {
    fputs($von,"body $articleNumber\r\n");
    $zeile=lieszeile($von);
    $zeile=lieszeile($von);
    $body="";
    while(strcmp(trim($zeile),".") != 0) {
        $body .= $zeile."\n";
        $zeile=lieszeile($von);
    }
    
    if ($encoding == "quoted-printable") {
        $body=quoted_printable_decode($body);
    }
    
    $body=str_replace("\n..\n","\n.\n",ereg_replace("\r\n","\n",$body));
    
    // Look out!! Split divide de body into sentences.
    // So $body it's no longer a string it's an array of strings!!
    $body = split("\n",$body);
    
    return($body);
}


/*
* opens the connection to the NNTP-Server
*
* $server: adress of the NNTP-Server
* $port: port of the server
*/

// Modified in order to integrate it with the error page.

function OpenNNTPconnection($server,$port,$login,$passwd) {
    
    global $text_error,$server_auth_user,$server_auth_pass,$readonly,$t;
    $server_auth_user = $login;
    $server_auth_pass = $passwd;
    
    if($port == "563"){
        // Secure Connection
        //echo $port;
        settype($port, "integer");
        $ns = fsockopen ("ssl://".$server, $port, $errno, $errstr, 30);
    }else{
        $ns = fsockopen ($server, $port, $errno, $errstr, 30);
    }
    
    //$ns=fsockopen($server,$port);
    //$ns=fsockopen($server,$port);
    $weg=lieszeile($ns);  // kill the first line
    if (substr($weg,0,2) != "20") {
        $ns=false;
        //echo "<p>".$text_error["error:"]."</p>";
    } else {
       
        if ((isset($server_auth_user)) && (isset($server_auth_pass)) &&
        ($server_auth_user != "")) {
            fputs($ns,"authinfo user $server_auth_user\r\n");
            $weg=lieszeile($ns);
            fputs($ns,"authinfo pass $server_auth_pass\r\n");
            $weg=lieszeile($ns);
            if (substr($weg,0,3) != "281") {
                $ns=false;
                //echo "<p>".$text_error["error:"]."</p>";
                //echo "<p>".$text_error["auth_error"]."</p>";
                $t->set_var("error_message_2",$text_error["auth_error"]);
            }
            // Mode Reader Fix by 'BrianLucas'
            if ($ns != false) {
                fputs($ns,"mode reader\r\n");
                $weg=lieszeile($ns);  // and once more
                if (substr($weg,0,2) != "20") {
                    $ns=false;
                    //echo "<p>".$text_error["error:"]."</p>";
                }
            }            
        }
    }
    if ($ns == false) $t->set_var("error_message",$text_error["connection_failed"]);
    return $ns;
}



/*
* read one line from the NNTP-server
*/

function lieszeile(&$von) {
    ini_set("max_execution_time",600);
    if ($von != false) {
        $t=ereg_replace("\r\n$","",fgets($von,100000));
        return $t;
    }
}

/*
* Close a NNTP connection
*
* $von: the handle of the connection
*/
function closeNNTPconnection(&$von) {
    if ($von != false) {
        fputs($von,"quit\r\n");
        fclose($von);
        //echo "DISCONNECTED!!";
    }
}



/*
* Post an article to a newsgroup
*
* $subject: The Subject of the article
* $from: The authors name and email of the article
* $newsgroups: The groups to post to
* $ref: The references of the article
* $body: The article itself
*/

function verschicken($subject,$from,$newsgroups,$ref,$body,&$ns) {
    
    //echo $subject,$from,$newsgroups,$ref,$body;
    echo $ref;
    
    global $server,$port,$organization,$text_error;
    global $file_footer;
    //flush();
    //$ns=OpenNNTPconnection($server,$port,$login,$passwd);
    if ($ns != false) {
        
        fputs($ns,"post\r\n");
        $weg=lieszeile($ns);
        fputs($ns,"Subject: $subject\r\n");
        fputs($ns,"From: $from\r\n");
        fputs($ns,"Sender: $from\r\n");
        fputs($ns,"Newsgroups: $newsgroups\r\n");
        // OJO!! Aquí tendríamos que cambiar alguna movida del charset!!
        fputs($ns,"Content-Type: text/plain; "._CHARSET."\r\n");
        fputs($ns,"Content-Transfer-Encoding: 8bit\r\n");
        // User Agent MUST be MyNewsGroups :) !!
        fputs($ns,"User-Agent: MyNewsGroups :) v ".MYNG_VERSION."\r\n");
        
        if ($_SESSION['conf_sec_send_poster_host_yn'] == "Y")
        fputs($ns,"X-HTTP-Posting-Host: ".gethostbyaddr(getenv("REMOTE_ADDR"))."\r\n");
        if ($ref!=false) fputs($ns,"References: $ref\r\n");
        if (isset($organization)) fputs($ns,"Organization: $organization\r\n");
        if ((isset($file_footer)) && ($file_footer!="")) {
            $footerfile=fopen($file_footer,"r");
            $body.="\n".fread($footerfile,filesize($file_footer));
            fclose($footerfile);
        }
        $body=ereg_replace("\r",'',$body);
        $body=str_replace("\n.\n","\n..\n",$body);
        $b=split("\n",$body);
        $body="";
        for ($i=0; $i<count($b); $i++) {
            if ((strpos(substr($b[$i],0,strpos($b[$i]," ")),">") != false ) | (strcmp(substr($b[$i],0,1),">") == 0)) {
                $body .= textwrap(stripSlashes($b[$i]),78,"\r\n")."\r\n";
            } else {
                $body .= textwrap(stripSlashes($b[$i]),74,"\r\n")."\r\n";
            }
        }
        
        //----- Here we can add the desired Footer, following the body ------//
        $linea1 = "##-----------------------------------------------##";
        $linea2 = _MYNGFOOTER1;
        $linea3 = _MYNGFOOTER2;
        $linea4 = _MYNGFOOTER3.$newsgroups;
        $linea5 = "##-----------------------------------------------##";
        
        $body = $body."\n\n".$linea1."\n".$linea2."\n".$linea3."\n".$linea4."\n".$linea5;
        //-------------------------------------------------------------------------------------//
        
        fputs($ns,"\r\n".$body."\r\n.\r\n");
        $message=lieszeile($ns);
        //closeNNTPconnection($ns);
        
    } else {
        //echo "komorl";
        $message = _MYNGPOST_FAILED;
    }
    
    return $message;
}










?>
