<?

/*

 * SQL Management for PHP

 *

 * Copyright (c) 1998-2000 NetUSE AG

 *                    Boris Erdmann, Kristian Koehntopp

 *

 * Modified by Svetlin Staev (2002/01/24)

 *

 * $Id: sql_manager.php,v 1.2 2000/07/12 18:22:34 Exp $

 *

 */



class SQL

   {

      /* public: connection parameters */

      var $Host     = "";

      var $Database = "";

      var $User     = "";

      var $Password = "";



      /* public: configuration parameters */

      var $Auto_Free     = 1;

      var $Debug         = 0;

      var $Halt_On_Error = "yes"; // "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)

      var $Seq_Table     = "db_sequence";

      var $Record        = array();

      var $Errno         = 0;

      var $Error         = "";

      var $type          = "mysql";

      var $revision      = "1.2";

      var $Link_ID       = 0;

      var $Query_ID      = 0;

      var $Row;



      /* public: constructor */

      function SQL($query = "")

         {

            $this->query($query);

         }



      /* public: some trivial reporting */

      function link_id()

         {

            print  $this->Link_ID;

            return $this->Link_ID;

         }



      function query_id()

         {

            return $this->Query_ID;

         }



      /* public: connection management */

      function connect($Database = "", $Host = "", $User = "", $Password = "")

         {

            /* Handle defaults */

            if ("" == $Database) $Database = $this->Database;

            if ("" == $Host)     $Host     = $this->Host;

            if ("" == $User)     $User     = $this->User;

            if ("" == $Password) $Password = $this->Password;



         /* establish connection, select database */

            if ( 0 == $this->Link_ID )

               {

                  $this->Link_ID=mysql_pconnect($Host, $User, $Password);

                  if (!$this->Link_ID)

                     {

                        $this->halt("pconnect($Host, $User, \$Password) failed.");

                        return 0;

                     }

                  if (!@mysql_select_db($Database,$this->Link_ID))

                     {

                        $this->halt("cannot use database ".$this->Database);

                        return 0;

                     }

               }

            return $this->Link_ID;

         }



      /* public: discard the query result */

      function free()

         {

            @mysql_free_result($this->Query_ID);

            $this->Query_ID = 0;

         }



      /* public: perform a query */

      function query($Query_String)

         {

            /* No empty queries, please, since PHP4 chokes on them. */

            if ($Query_String == "") return 0;

            if (!$this->connect())

               {

                  return 0;

               };



            if ($this->Query_ID)

               {

                  $this->free();

               }



            if ($this->Debug)

            printf("Debug: query = %s<br>\n", $Query_String);

            $this->Query_ID = @mysql_query($Query_String,$this->Link_ID);

            $this->Row      = 0;

            $this->Errno    = mysql_errno();

            $this->Error    = mysql_error();

            if (!$this->Query_ID)

               {

                  $this->halt("Invalid SQL: ".$Query_String);

               }

            return $this->Query_ID;

         }



      /* public: walk result set */

      function next_record()

         {

            if (!$this->Query_ID)

               {

                  $this->halt("next_record called with no query pending.");

                  return 0;

               }

            $this->Record = @mysql_fetch_array($this->Query_ID);

            $this->Row   += 1;

            $this->Errno  = mysql_errno();

            $this->Error  = mysql_error();

            $stat         = is_array($this->Record);



            if (!$stat && $this->Auto_Free)

               {

                  $this->free();

               }

            return $stat;

         }



      /* public: position in result set */

      function seek($pos = 0)

         {

            $status = @mysql_data_seek($this->Query_ID, $pos);

            if ($status)

               {

                  $this->Row = $pos;

               }

            else

               {

                  $this->halt("seek($pos) failed: result has ".$this->num_rows()." rows");

                  @mysql_data_seek($this->Query_ID, $this->num_rows());

                  $this->Row = $this->num_rows;

                  return 0;

               }

            return 1;

         }



      /* public: table locking */

      function lock($table, $mode="write")

         {

            $this->connect();

            $query="lock tables ";

            if (is_array($table))

               {

                  while (list($key,$value)=each($table))

                     {

                        if ($key=="read" && $key!=0)

                           {

                              $query.="$value read, ";

                           }

                        else

                           {

                              $query.="$value $mode, ";

                           }

                     }

                  $query=substr($query,0,-2);

               }

            else

               {

                  $query.="$table $mode";

               }

            $res = @mysql_query($query, $this->Link_ID);

            if (!$res)

               {

                  $this->halt("lock($table, $mode) failed.");

                  return 0;

               }

            return $res;

         }



      function unlock()

         {

            $this->connect();



            $res = @mysql_query("unlock tables");

            if (!$res)

               {

                  $this->halt("unlock() failed.");

                  return 0;

               }

            return $res;

         }





      /* public: evaluate the result (size, width) */

      function affected_rows()

         {

            return @mysql_affected_rows($this->Link_ID);

         }



      function num_rows()

         {

            return @mysql_num_rows($this->Query_ID);

         }



      function num_fields()

         {

            return @mysql_num_fields($this->Query_ID);

         }



      /* public: shorthand notation */

      function rows()

         {

            return $this->num_rows();

         }



      function prows()

         {

            print $this->num_rows();

         }



      function get($Name)

         {

            if(isset($this->Record[$Name])) return $this->Record[$Name];

            else                            return "";

         }



      function write($Name)

         {

            if(isset($this->Record[$Name])) print $this->Record[$Name];

            else                            print "";

         }



      /* public: sequence numbers */

      function nextid($seq_name)

         {

            $this->connect();



            if ($this->lock($this->Seq_Table))

               {

                  $q   = sprintf("select nextid from %s where seq_name = '%s'", $this->Seq_Table, $seq_name);

                  $id  = @mysql_query($q, $this->Link_ID);

                  $res = @mysql_fetch_array($id);



                  if (!is_array($res))

                     {

                        $currentid = 0;

                        $q         = sprintf("insert into %s values('%s', %s)", $this->Seq_Table, $seq_name, $currentid);

                        $id        = @mysql_query($q, $this->Link_ID);

                     }

                  else

                     {

                        $currentid = $res["nextid"];

                     }

                  $nextid = $currentid + 1;

                  $q      = sprintf("update %s set nextid = '%s' where seq_name = '%s'", $this->Seq_Table, $nextid, $seq_name);

                  $id     = @mysql_query($q, $this->Link_ID);

                  $this->unlock();

               }

            else

               {

                  $this->halt("cannot lock ".$this->Seq_Table." - has it been created?");

                  return 0;

               }

            return $nextid;

         }



      /* private: error handling */

      function halt($msg)

         {

			global $_DEV, $_PHPLIB, $_Config;

            $this->Error = @mysql_error($this->Link_ID);

            $this->Errno = @mysql_errno($this->Link_ID);



            if ($this->Halt_On_Error == "no") return;

			if ($_DEV)

				{

					$this->haltmsg($msg);

				}

			else

				{

					include($_PHPLIB["maindir"]."ihtml/site_error.ihtml");

				}

            if ($this->Halt_On_Error != "report") 

				{

					endPage();

					exit;

				}

         }



      function haltmsg($msg)

         {

            printf("<div align=center><p align=justify style=\"padding:10px;width:520px;border:1px solid #78ACFF;background-color:F2F9FF\"><b style=color:darkblue>DATABASE ERROR:</b> %s<br>\n", $msg);

            printf("<b style=color:darkblue>MYSQL ERROR:</b> %s (%s)<br></p></div>\n", $this->Errno, $this->Error);

         }



      function table()

         {

            $this->query("SHOW TABLES");

            $i=0;

            while ($info=mysql_fetch_row($this->Query_ID))

               {

                  $return[$i]["table_name"]= $info[0];

                  $return[$i]["tablespace_name"]=$this->Database;

                  $return[$i]["database"]=$this->Database;

                  $i++;

               }

            return $return;

         }

   }

$dc = new SQL();

$rc = new SQL();

$ac = new SQL();

$bc = new SQL();



      $dc->Database = $db_database;

      $dc->User     = $db_user    ;

      $dc->Password = $db_password;

      $dc->Host     = $db_host    ;



      $rc->Database = $db_database;

      $rc->User     = $db_user    ;

      $rc->Password = $db_password;

      $rc->Host     = $db_host    ;



      $ac->Database = $db_database;

      $ac->User     = $db_user    ;

      $ac->Password = $db_password;

      $ac->Host     = $db_host    ;



      $bc->Database = $db_database;

      $bc->User     = $db_user    ;

      $bc->Password = $db_password;

      $bc->Host     = $db_host    ;



$dc->connect();

?>

