<?
/*
mod.ftpdb.php - LAN party FTP server list system - Version 27/jan/2002
Copyright (C) 2001-2002 Jochen Kupperschmidt <jochen@kupperschmidt.de>

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
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
*/

class ftpdb
{
  /* mysql data */
  var $db_host = "localhost";
  var $db_user = "username";
  var $db_pass = "password";
  var $db_name = "database";
  var $db_tbl  = "ftpdb";


  /* will be filled within constructor */
  var $url;
  var $color_line;
  var $status;
  var $label;


  /* constructor - executed on object creation */
  function ftpdb()
  {
    // links
    $this->url["add"]  = "mod.ftpdb-example-add.php";
    $this->url["list"] = "mod.ftpdb-example-list.php";

    // every second row will be filled with this color
    $this->color_line = "#e4c494";

    // status labels and colors
    $this->status[0]["label"] = "neu";
    $this->status[0]["color"] = "#555555";
    $this->status[1]["label"] = "online";
    $this->status[1]["color"] = "#006600";
    $this->status[2]["label"] = "offline";
    $this->status[2]["color"] = "#aa0000";

    // labels
    $this->label["list_status"] = "Status";
    $this->label["list_server"] = "Server";
    $this->label["list_port"]   = "Port";
    $this->label["list_user"]   = "Login";
    $this->label["list_pass"]   = "Passwort";
    $this->label["list_descr"]  = "Beschreibung";
    $this->label["list_browse"] = "Browse";
    $this->label["add_host"]    = "IP-Adresse";
    $this->label["add_port"]    = "Port";
    $this->label["add_user"]    = "Login";
    $this->label["add_pass"]    = "Passwort";
    $this->label["add_descr"]   = "Kurzbeschreibung";
    $this->label["add_submit"]  = "Eintragen";
    $this->label["add_confirm"] = "Der Server wurde gespeichert!";
    $this->label["add_tolist"]  = "Liste anzeigen";

    // connect to mysql and select database
    $link = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
    mysql_select_db($this->db_name, $link);
  }


  /* general list */
  function show_list()
  {
    $result = mysql_query("SELECT * FROM " . $this->db_tbl . " ORDER BY id");

    echo "<table cellspacing=0 cellpadding=2 border=0>\n";
    echo "  <tr>\n";
    echo "    <td width=40><b>" . $this->label["list_status"] . "</b></td>\n";
    echo "    <td width=50>&nbsp;</td>\n";
    echo "    <td width=90><b>" . $this->label["list_server"] . "</b></td>\n";
    echo "    <td width=40><b>" . $this->label["list_port"] . "</b></td>\n";
    echo "    <td width=80><b>" . $this->label["list_user"] . "</b></td>\n";
    echo "    <td width=80><b>" . $this->label["list_pass"] . "</b></td>\n";
    echo "    <td width=180><b>" . $this->label["list_descr"] . "</b></td>\n";
    echo "  </tr>\n";

    $line = 1;
    while ($row = mysql_fetch_assoc($result)) {
      if ($line % 2 == 1)
        echo "  <tr bgcolor=" . $this->color_line . ">\n";
      else
        echo "  <tr>\n";

      for ($i = 0; $i < 3; $i++)
        if ($row["status"] == $i)
          echo "    <th><font color=" . $this->status[$i]["color"] . ">" . $this->status[$i]["label"] . "</font></th>\n";

      $ftp_link = "";
      echo "    <th><a href=\"ftp://" . $row["user"] . ":" . $row["pass"] . "@" . $row["host"] . ":" . $row["port"] . "\" target=\"_blank\">" . $this->label["list_browse"] . "</a></th>\n";
      echo "    <td>" . $row["host"] . "</td>\n";
      echo "    <td>" . $row["port"] . "</td>\n";
      echo "    <td>" . $row["user"] . "</td>\n";
      echo "    <td>" . $row["pass"] . "</td>\n";
      echo "    <td>" . $row["descr"] . "</td>\n";

      echo "  </tr>\n";
      $line++;
    }

    echo "</table>\n";
    echo "<br><br>" . $this->copyright . "<br>\n";
  }


  /* seperate status count */
  function show_num($num="")
  {
    $num = strval($num);
    if ($num != "")
      $where = " WHERE status=" . $num;
    $result = @mysql_query("SELECT * FROM " . $this->db_tbl . $where);

    return @mysql_num_rows($result);
  }


  /* status refresh */
  function refresh()
  {
    $result = mysql_query("SELECT host, port FROM " . $this->db_tbl);

    echo "<table cellspacing=0 cellpadding=2 border=0>\n";
    echo "  <tr>\n";
    echo "    <td><b>" . $this->label["list_server"] . "</b></td>\n";
    echo "    <td><b>" . $this->label["list_port"] . "</b></td>\n";
    echo "    <td><b>" . $this->label["list_status"] . "</b></td>\n";
    echo "  </tr>\n";

    $line = 1;
    while ($row = mysql_fetch_assoc($result)) {
      if ($line % 2 == 1)
        echo "  <tr bgcolor=" . $this->color_line . ">\n";
      else
        echo "  <tr>\n";

      echo "    <td>" . $row["host"] . "</td>\n";
      echo "    <td align=right>" . $row["port"] . "</td>\n";

      // check if server is online and port is open
      if ($fp = fsockopen($row["host"], $row["port"], &$errno, &$errstr, 3)) {
        $status = 1;
        @fclose($fp);
      } else $status = 2;

      $result = mysql_query("UPDATE " . $this->db_tbl . " SET status='$status' WHERE host='$host'");
      echo "    <td><font color=" . $this->status[$status]["color"] . "><b>" . $this->status[$status]["label"] . "</b></font></td>\n";

      echo "  </tr>\n";
      $line++;
    }

    echo "</table>\n";
    echo "<br><br>" . $this->copyright . "<br>\n";
  }


  /* add form */
  function add_form() {
?>

<form action="<? echo $this->url_add; ?>" method=post>
<br><table cellspacing=0 cellpadding=2 border=0>
  <tr>
    <td><u><?= $this->label["add_host"]; ?></u>, <u><?= $this->label["add_port"]; ?></u>:</td>
    <td>
      <table width=100% cellspacing=0 cellpadding=0 border=0>
        <tr>
          <td align=left><input name="host" size=15 maxlength=15></td>
          <td align=right><input name="port" size=5 maxlength=5 value="21"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><u><?= $this->label["add_user"]; ?></u>, <u><?= $this->label["add_pass"]; ?></u>:</td>
    <td>
      <table width=100% cellspacing=0 cellpadding=0 border=0>
        <tr>
          <td align=left><input name="user" size=10 maxlength=16 value="anonymous"></td>
          <td align=right><input name="pass" size=10 maxlength=16 value="anonymous"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><u><?= $this->label["add_descr"]; ?></u>:</td>
    <td><input name="descr" size=25 maxlength=40></td>
  </tr>
  <tr>
    <td colspan=2 align=center><br><input type=submit value="<?= $this->label["add_submit"]; ?>"></td>
  </tr>
</table>
</form>

<?
  }


  /* add process */
  function add_res($host, $port, $user, $pass, $descr) {
    $result = mysql_query("INSERT INTO " . $this->db_tbl . " (host, port, user, pass, descr) VALUES ('$host', '$port', '$user', '$pass', '$descr')");
    echo $this->label["add_confirm"] . "<br>\n";
    echo '<br><a href="' . $this->url["list"] . "\">" . $this->label["add_tolist"] . "</a><br>\n";
  }


  /* please do not remove this copyright - this is gpl software (and it was hard work, too...) */
  var $copyright = '<span style="font-size: 8pt">copyright &copy; 2001 jochen kupperschmidt aka Y0Gi</span>';
}
?>