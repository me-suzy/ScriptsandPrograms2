  <table id="main">
    <tr>
      <td>
        <div id="header">qliteNews Panel</div>
      </td>
    </tr>
  </table>

  <table id="main">
    <tr>
      <td id="left" align="left" valign="top">
        <div class="title">+ Admin Menu</div>
        <ul id="nav">
          <li><a href="index.php">Home</a></li>
          <li><a href="?page=options">Options</a></li>
          <li><a href="?page=postnews">Post News</a></li>
          <li><a href="?page=helpinfo">Help/Info</a></li>
        <ul>
      </td>

      <td id="center" valign="top">
        <?php
          switch ($page) {
          //Options
          case "options":
          include('options.php');
          break;
          //Post News
          case "postnews":
          include('postnews.php');
          break;
          //Modify News
          case "modifynews":
          include('modifynews.php');
          break;
          //New Process
          case "newsprocess":
          include('newsprocess.php');
          break;
          //Help/Info
          case "helpinfo":
          include('helpinfo.php');
          break;
          //Default (Current News)
          default:
          include("adminview.php");
          }
        ?>
      </td>

      <td id="right" valign="top">
        <div class="title">+ News Archieve</div>
          <div id="news">
            <?php
              include("config.php");
              $db = mysql_connect($dbhost,$dbuser,$dbpass); 
              mysql_select_db($dbname) or die("Cannot connect to database");
              $query = "SELECT * FROM qlitenews ORDER BY id"; 
              $result = mysql_query($query);
                echo "<ul>\n";
                while ($r = mysql_fetch_array($result)) {
                  echo "<li><a href=\"index.php?page=modifynews&amp;id=$r[id]\">$r[title]</a></li>\n";
                }
                echo "</ul>\n";
            ?>
        </div>
      </td>
    </tr>
  </table> 

<p id="footer"><strong>qliteNews Powered by <a href="http://www.r2xDesign.net" title="Web Scripting Resources - PHP Scripts, PHP Snippets, PHP Tutorials and Free Templates">r2xDesign.net</strong></a></p>