<?php /* ***** Orca Forum - Body File ************************* */

/* ***************************************************************
* Orca Forum v4.3c
*  A simple threaded forum for a small community
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
************************************************************ */ ?>


<div id="of_main">

  <table cellpadding="2" cellspacing="0" border="0" id="of_controls">
    <tr>
      <td class="of_cleft">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="of_form">
          <div>
            <input type="text" name="s" <?php if (isset($_GET['s'])) echo "value=\"".htmlspecialchars($_GET['s'])."\" "; ?>/>
            <input type="submit" value="<?php echo $lang['panel1']; ?>" <?php if (!$fData['msgtotal']) echo "disabled=\"disabled\" "; ?>/>
          </div>
        </form>
      </td>
      <td class="of_cright">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="of_form">
          <div>
            <label for="of_time"><?php echo $lang['panel2']; ?></label>
            <select name="time" id="of_time" size="1">
              <option value="0"<?php if ($_COOKIE['of_mark'] == 0) echo " selected=\"selected\""; ?>><?php echo $lang['panel3']; ?></option>
              <option value="3600"<?php if ($_COOKIE['of_mark'] == 3600) echo " selected=\"selected\""; ?>><?php echo $lang['panel4']; ?></option>
              <option value="10800"<?php if ($_COOKIE['of_mark'] == 10800) echo " selected=\"selected\""; ?>><?php echo $lang['panel5']; ?></option>
              <option value="21600"<?php if ($_COOKIE['of_mark'] == 21600) echo " selected=\"selected\""; ?>><?php echo $lang['panel6']; ?></option>
              <option value="43200"<?php if ($_COOKIE['of_mark'] == 43200) echo " selected=\"selected\""; ?>><?php echo $lang['panel7']; ?></option>
              <option value="86400"<?php if ($_COOKIE['of_mark'] == 86400) echo " selected=\"selected\""; ?>><?php echo $lang['panel8']; ?></option>
              <option value="259200"<?php if ($_COOKIE['of_mark'] == 259200) echo " selected=\"selected\""; ?>><?php echo $lang['panel9']; ?></option>
              <option value="604800"<?php if ($_COOKIE['of_mark'] == 604800) echo " selected=\"selected\""; ?>><?php echo $lang['panela']; ?></option>
              <option value="1209600"<?php if ($_COOKIE['of_mark'] == 1209600) echo " selected=\"selected\""; ?>><?php echo $lang['panelb']; ?></option>
              <option value="2592000"<?php if ($_COOKIE['of_mark'] == 2592000) echo " selected=\"selected\""; ?>><?php echo $lang['panelc']; ?></option>
            </select>
            <input type="submit" name="command_mark" value="<?php echo $lang['paneld']; ?>" />
          </div>
        </form>
      </td>
    </tr>
    <tr>
      <td class="of_cleft">
        <script type="text/javascript"><!--
          var of_dir = "<?php echo ($fData['threadcollapse']) ? "block" : "none"; ?>";
          function expcol_all() {
            for (var x = 0; x < <?php echo $lData['toprows']; ?>; x++) {
              try {
                document.getElementById('of_gen' + x).style.display = of_dir;
                if (of_dir == "none") {
                  document.getElementById('of_switch' + x).innerHTML = "<?php echo ($iData['showimages']) ? "<img src=\\\"{$iData['plus']}\\\" alt=\\\"+\\\" />" : "+"; ?>";
                } else document.getElementById('of_switch' + x).innerHTML = "<?php echo ($iData['showimages']) ? "<img src=\\\"{$iData['minus']}\\\" alt=\\\"&ndash;\\\" />" : "&ndash;"; ?>";
              } catch(err) {}
            }
            document.getElementById('of_expbutton').value = (of_dir == "none") ? "<?php echo $lang['panele']; ?>" : "<?php echo $lang['panelf']; ?>";
            of_dir = (of_dir == "none") ? "block" : "none";
          }
          document.write("<input type=\"button\" id=\"of_expbutton\" value=\"<?php echo ($fData['threadcollapse']) ? $lang['panele'] : $lang['panelf']; ?>\"<?php echo ($lData['toprows']) ? " onclick=\\\"expcol_all()\\\"" : " disabled=\\\"disabled\\\""; ?> />");
        // --></script>
        <noscript>
          <div>
            <input type="button" disabled="disabled" value="<?php echo ($fData['threadcollapse']) ? $lang['panele'] : $lang['panelf']; ?>" />
          </div>
        </noscript>
      </td>
      <td class="of_cright">
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo (isset($_GET['msg']) || $_POST['command'] == "Preview" || $fData['start'] || isset($fData['search'])) ? $lang['panelg'] : $lang['panelh']; ?></a> |
        <a href="#of_postform"><?php echo (isset($_GET['msg'])) ? $lang['paneli'] : $lang['panelj']; ?></a>
      </td>
    </tr>
  </table>

  <?php if (isset($fData['error'])) { ?> 
    <div id="of_error">
      <?php echo $fData['error']; ?> 
    </div>
  <?php } ?>

  <?php if (isset($fData['success'])) { ?> 
    <div id="of_success">
      <?php echo $fData['success']; ?> 
    </div>
  <?php } ?>

  <?php if (isset($fData['search'])) { ?> 
     <div id="of_search">
       <?php printf($lang['search'], htmlspecialchars($fData['search'])); ?>
     </div>
  <?php } ?>


  <?php if ($_POST['command'] == "Preview" || isset($_GET['msg'])) {

    if (isset($fData['matron'])) { ?> 
      <div id="of_browsing">
        <?php echo $lang['message1']; ?> 
        <a class="of_subject" href="<?php echo $_SERVER['PHP_SELF']."?msg={$fData['matron'][3]}"; ?>"><?php echo htmlspecialchars($fData['matron'][0]); ?></a>
        <span class="of_author" title="<?php echo $fData['matron'][4]; ?>"><?php echo htmlspecialchars($fData['matron'][1]); ?></span>
        <span class="of_date<?php if ($fData['matron'][2] > time() - $_COOKIE['of_mark']) echo " of_new"; ?>"><?php echo dateStamp($fData['matron'][2] + $fData['offset']); ?></span>
      </div>
    <?php } ?>

    <table id="of_message" cellpadding="3" cellspacing="0" border="0">
      <tr>
        <th colspan="2"><?php echo $mData['subject'].(($_POST['command'] == "Preview") ? $lang['message2'] : ""); ?></th>
      </tr>
      <tr>
        <td id="of_message_column">
          <strong id="of_author"><?php echo $mData['author']; ?></strong><br />

          <?php if ($mData['email']) { ?>
            <a id="of_email" href="mailto:<?php echo $mData['email']; ?>" title="<?php printf($lang['message3'], $mData['author']); ?>"><?php echo $lang['message4']; ?></a><br />
          <?php } ?>

          <?php if ($mData['image']) { ?> 
            <img id="of_avatar" src="<?php echo $mData['image']; ?>" alt="<?php echo $lang['message5']; ?>" /><br />
          <?php } ?>

          <small id="of_date">
            <?php echo $lang['message6']; ?><br />
            <?php echo dateStamp($mData['date']); ?> 
          </small>

        </td>
        <td id="of_msgtext" class="of_msgtext">
          <?php echo $mData['message']; ?> &nbsp;
        </td>
      </tr>
      <?php if (isset($mData['prevmsg'])) { ?> 
        <tr>
          <td id="of_msgfooter" colspan="2">
            <?php echo $lang['message7']; ?>  
            <a href="<?php echo $_SERVER['PHP_SELF']."?msg=".$mData['prevpid']; ?>"><strong><?php echo $mData['prevsubject']; ?></strong></a>
            <?php printf($lang['message8'], $mData['prevauthor']); ?> 
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } ?>


  <?php if ($_POST['command'] != "Preview" && $fData['msgtotal']) { ?>
    <div id="of_mainlist">
      <script type="text/javascript"><!--
        function vis_tog(id, swt) {
          if (document.getElementById(id).style.display == "" || document.getElementById(id).style.display == "none") {
            document.getElementById(id).style.display = "block";
            document.getElementById(swt).innerHTML = "<?php echo ($iData['showimages']) ? "<img src=\\\"{$iData['minus']}\\\" alt=\\\"&ndash;\\\" />" : "&ndash;"; ?>";
          } else {
            document.getElementById(id).style.display = "none";
            document.getElementById(swt).innerHTML = "<?php echo ($iData['showimages']) ? "<img src=\\\"{$iData['plus']}\\\" alt=\\\"+\\\" />" : "+"; ?>";
          }
        }
      // --></script>

      <?php /* ***** Print Message List *********************** */

      if ($lData['toprows']) {

        if (isset($_GET['msg'])) { ?> 
          <p><?php echo $lang['list1']; ?></p>
        <?php } ?> 

        <ul class="of_toppost">

          <?php $lData['threadlimit'] = ($fData['threadspp'] + $fData['start'] > $lData['toprows']) ? $lData['toprows'] : $fData['threadspp'] + $fData['start'];

          for ($x = $fData['start']; $x < $lData['threadlimit']; $x++) {
            $lData['replies'] = 0;
            $thisList = "";

            $lData['next'] = get_kids(mysql_result($lData['top'], $x, "pid"));
            if (mysql_num_rows($lData['next'])) {
              ob_start();
              for ($y = 0; $y < mysql_num_rows($lData['next']); $y++) listChildren(mysql_result($lData['next'], $y, "pid"));
              $thisList = ob_get_contents();
              ob_end_clean();
            } ?> 

            <li>
              <script type="text/javascript"><!--
                <?php if (trim($thisList)) { ?> 
                  document.write("<span class=\"of_expand\" id=\"of_switch<?php echo $x; ?>\" onclick=\"vis_tog('of_gen<?php echo $x; ?>', 'of_switch<?php echo $x; ?>');\"><?php echo ($iData['showimages']) ? "<img src=\\\"{$iData[($fData['threadcollapse']) ? 'plus' : 'minus']}\\\" alt=\\\"".(($fData['threadcollapse']) ? "+" : "&ndash;")."\\\" />" : (($fData['threadcollapse']) ? "+" : "&ndash;"); ?></span>");
                <?php } else { ?>
                  document.write("<span class=\"of_expand\"><?php echo ($iData['showimages']) ? "<img src=\\\"{$iData['minus']}\\\" alt=\\\"&ndash;\\\" />" : "&ndash;"; ?></span>");
                <?php } ?>
              // --></script>

              <a class="of_subject" href="<?php echo $_SERVER['PHP_SELF']."?msg=".mysql_result($lData['top'], $x, "pid"); ?>"><?php echo htmlspecialchars(mysql_result($lData['top'], $x, "subject")); ?></a>
              <span class="of_author" title="<?php echo mysql_result($lData['top'], $x, "ip"); ?>"><?php echo htmlspecialchars(mysql_result($lData['top'], $x, "author")); ?></span>
              <span class="of_date<?php if (mysql_result($lData['top'], $x, "date") > time() - $_COOKIE['of_mark']) echo "_new"; ?>"><?php echo dateStamp(mysql_result($lData['top'], $x, "date") + $fData['offset']); ?></span>
              <span class="of_replies">(<?php echo $lData['replies']; ?>)</span>

              <?php if (trim($thisList)) { ?> 
                <ul class="of_sublist" id="of_gen<?php echo $x; ?>" style="display:<?php echo ($fData['threadcollapse']) ? "none" : "block"; ?>;">
                  <?php echo $thisList; ?> 
                </ul>
              <?php } ?> 
            </li>
          <?php } ?> 
        </ul>

        <div id="of_timekeeper">
          <?php echo $lang['time1']; ?> GMT <?php echo (($fData['dstadjust']) ? ($fData['tzoffset'] + date("I")).((date("I")) ? " (DLS)" : "") : $fData['tzoffset'])." / {$fData['timezone']}"; ?> 
        </div>

      <?php } else if (isset($fData['search'])) { ?> 
 
        <p><?php echo $lang['list2']; ?></p>

      <?php } else { ?> 
 
        <p><?php echo $lang['list3']; ?></p>

      <?php } ?> 
    </div>

    <?php if ($fData['start'] != 0 || $lData['toprows'] > $fData['threadspp']) { ?> 
      <div id="of_pagination">
        <div id="of_pagin_prev">
          <?php if ($fData['start'] != 0) {
            $linkPrev = "?";
            if ($fData['start'] - $fData['threadspp'] > 0) $linkPrev .= "start=".($fData['start'] - $fData['threadspp'])."&amp;";
            $linkPrev = paginURI($linkPrev); ?>
            <a href="<?php echo $_SERVER['PHP_SELF'].$linkPrev; ?>" title="<?php echo $lang['pagin1']; ?>">&lt;&lt; <?php echo $lang['pagin2']; ?></a>
          <?php } else echo "&nbsp;"; ?> 
        </div>
        <div id="of_pagin_next">
          <?php if ($fData['start'] + $fData['threadspp'] < $lData['toprows']) {
            $linkNext = "?start=".($fData['start'] + $fData['threadspp'])."&amp;";
            $linkNext = paginURI($linkNext); ?>
            <a href="<?php echo $_SERVER['PHP_SELF'].$linkNext; ?>" title="<?php echo $lang['pagin4']; ?>"><?php echo $lang['pagin5']; ?> &gt;&gt;</a>
          <?php } else echo "&nbsp;"; ?> 
        </div>
        <div id="of_pagin_page">
          <?php for ($x = 0; $x < ceil($lData['toprows'] / $fData['threadspp']); $x++) {
            if ($x * $fData['threadspp'] == $fData['start']) { ?> 
              <strong><?php echo ($x + 1); ?></strong>
            <?php } else {
              $linkPage = "?";
              if ($x != 0) $linkPage .= "start=".($x * $fData['threadspp'])."&amp;";
              $linkPage = paginURI($linkPage); ?>
              <a href="<?php echo $_SERVER['PHP_SELF'].$linkPage; ?>" title="<?php printf($lang['pagin3'], $x + 1); ?>"><?php echo ($x + 1); ?></a>
            <?php }
          } ?> 
        </div>
      </div>
    <?php }
  }

  if (!$fData['msgtotal'] && $_POST['command'] != "Preview") { ?>
    <div id="of_welcome">
      <?php echo $lang['welcome']; ?> 
    </div>

  <?php } ?> 


  <script type="text/javascript"><!--
    function of_validate() {
      if (!document.getElementById('of_postform').message.value) {
        if (!document.getElementById('of_postform').subject.value) {
          alert("<?php echo addcslashes(unhtmlentities($lang['form1']), "\0..\37!@\177..\377"); ?>");
          return false;
        }
        if (!confirm("<?php echo addcslashes(unhtmlentities($lang['form2']), "\0..\37!@\177..\377"); ?>")) return false;
      }

      if (!document.getElementById('of_postform').author.value)
       if (!confirm("<?php echo addcslashes(unhtmlentities($lang['form3']), "\0..\37!@\177..\377"); ?>")) return false;

      if (!document.getElementById('of_postform').subject.value)
        if (!confirm("<?php echo addcslashes(unhtmlentities($lang['form4']), "\0..\37!@\177..\377"); ?>")) return false;

      return true;
    }
  // --></script>

  <form id="of_postform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return of_validate();">
    <table cellpadding="2" cellspacing="0" border="0">
      <thead>
        <tr>
          <th colspan="2">
            <?php echo ($vData['parent'] != "-1") ? $lang['form5'] : $lang['form6']; ?> 
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><label for="of_pstauthor"><?php echo $lang['form7']; ?></label></td>
          <td><input name="author" id="of_pstauthor" type="text" size="14" value="<?php echo $vData['author']; ?>" /></td>
        </tr>
        <tr>
          <td><label for="of_pstemail"><?php echo $lang['form8']; ?></label></td>
          <td><input name="email" id="of_pstemail" type="text" size="25" value="<?php echo $vData['email']; ?>" onkeyup="if(!this.value){document.getElementById('of_pstnotify').disabled='disabled';document.getElementById('of_pstnotify').checked='';}else document.getElementById('of_pstnotify').disabled='';" /></td>
        </tr>
        <tr>
          <td><label for="of_pstsubject"><?php echo $lang['form9']; ?></label></td>
          <td><input name="subject" id="of_pstsubject" type="text" size="50" value="<?php echo $vData['subject']; ?>" /></td>
        </tr>
        <tr>
          <td><label for="of_msgarea"><?php echo $lang['formb']; ?></label></td>
          <td>
            <script type="text/javascript"><!--
              var of_msgarea = document.getElementById('of_msgarea');
              document.write("<button type=\"button\" onclick=\"of_msgarea.value+='[quote] [/quote]';of_msgarea.focus();\"><?php echo $lang['formc']; ?></button> ");
              document.write("<button type=\"button\" onclick=\"of_msgarea.value+='[b] [/b]';of_msgarea.focus();\"><?php echo $lang['formd']; ?></button> ");
              document.write("<button type=\"button\" onclick=\"of_msgarea.value+='[i] [/i]';of_msgarea.focus();\"><?php echo $lang['forme']; ?></button> ");
              document.write("<button type=\"button\" onclick=\"of_msgarea.value+='[link=http://] [/link]';of_msgarea.focus();\"><?php echo $lang['formf']; ?></button> ");
              document.write("<button type=\"button\" onclick=\"of_msgarea.value+='[img=http://]';of_msgarea.focus();\"><?php echo $lang['formg']; ?></button> ");
              document.write("<button type=\"button\" onclick=\"of_msgarea.value+='[code] [/code]';of_msgarea.focus();\"><?php echo $lang['formh']; ?></button> ");
              document.write("<br />");
            // --></script>
            <textarea id="of_msgarea" name="message" rows="7" cols="60"><?php echo $vData['message']; ?></textarea>
          </td>
        </tr>
        <tr>
          <td><label for="of_pstimage"><?php echo $lang['formi']; ?></label></td>
          <td><input name="image" id="of_pstimage" type="text" size="50" value="<?php echo $vData['image']; ?>" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <input name="cookify" id="of_pstcookify" type="checkbox" <?php if ($vData['cookify'] == "Yes") echo "checked=\"checked\" "; ?>/> <label for="of_pstcookify"><?php echo trim($lang['formk'], " :"); ?></label><br />
            <input name="notify" id="of_pstnotify" type="checkbox" <?php if ($vData['notify'] == "yes") echo "checked=\"checked\" "; ?>/> <label for="of_pstnotify"><?php echo strip_tags(str_replace("<br />", " ", $lang['forma'])); ?></label>
            <script type="text/javascript"><!--
              if (!document.getElementById('of_pstemail').value) document.getElementById('of_pstnotify').disabled="disabled";
            // --></script>
          </td>
        </tr>
        <tr>
          <td colspan="2" id="of_subrow">
            <input type="reset" value="<?php echo $lang['formj']; ?>" />
            <input type="hidden" name="parent" value="<?php echo $vData['parent']; ?>" />
            <input type="submit" name="command_prev" value="<?php echo $lang['formn']; ?>" />
            <input type="submit" name="command_post" value="<?php echo $lang['formo']; ?>" />
          </td>
        </tr>
      </tbody>
    </table>
  </form>

  <div style="text-align:center;font:italic 80% Arial,sans-serif;">
    <hr style="width:60%;margin:10px auto 2px auto;" />
    An <a href="http://www.greywyvern.com/" title="GreyWyvern.com">Orca</a> Script
  </div>

</div>
