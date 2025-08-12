<?php
session_start();
if(@$_SESSION['admin'] != 1)
{
	header("location: login.php");
	exit();
}
/*Under the terms and condition of GPL license, you may use this software freely
  as long as you retain our copyright. I would like to thanks you for appriciating
  my time and effort contributed to this project.
  ~David Ausman - Hotwebtools.com 2005*/
?>
<html>
<head>
<title>Admin Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="css/text.css">
</head>

<body>
<table width="800" border="0" cellspacing="0" cellpadding="0" class="admin">
  <tr class="topnav"> 
    <td><a href="viewSubs.php" class="topnav">View Subscribers</a> | <a href="manageSubs.php" class="topnav">Manage 
      Subscribers</a> | <a href="createNewsletter.php" class="topnav">Create Newsletter</a> 
      | <a href="changePass.php" class="topnav">Change Password</a></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="10" class="agreement">
<tr> 
          <td>The licenses for most software are designed to take away your freedom 
            to share and change it. By contrast, the GNU General Public License 
            is intended to guarantee your freedom to share and change free software--to 
            make sure the software is free for all its users. This General Public 
            License applies to most of the Free Software Foundation's software 
            and to any other program whose authors commit to using it. (Some other 
            Free Software Foundation software is covered by the GNU Lesser General 
            Public License instead.) You can apply it to your programs, too. 
            <p>When we speak of free software, we are referring to freedom, not 
              price. Our General Public Licenses are designed to make sure that 
              you have the freedom to distribute copies of free software (and 
              charge for this service if you wish), that you receive source code 
              or can get it if you want it, that you can change the software or 
              use pieces of it in new free programs; and that you know you can 
              do these things. </p>
            <p>To protect your rights, we need to make restrictions that forbid 
              anyone to deny you these rights or to ask you to surrender the rights. 
              These restrictions translate to certain responsibilities for you 
              if you distribute copies of the software, or if you modify it. </p>
            <p>For example, if you distribute copies of such a program, whether 
              gratis or for a fee, you must give the recipients all the rights 
              that you have. You must make sure that they, too, receive or can 
              get the source code. And you must show them these terms so they 
              know their rights. </p>
            <p>We protect your rights with two steps: (1) copyright the software, 
              and (2) offer you this license which gives you legal permission 
              to copy, distribute and/or modify the software. </p>
            <p>Also, for each author's protection and ours, we want to make certain 
              that everyone understands that there is no warranty for this free 
              software. If the software is modified by someone else and passed 
              on, we want its recipients to know that what they have is not the 
              original, so that any problems introduced by others will not reflect 
              on the original authors' reputations. </p>
            <p>Finally, any free program is threatened constantly by software 
              patents. We wish to avoid the danger that redistributors of a free 
              program will individually obtain patent licenses, in effect making 
              the program proprietary. To prevent this, we have made it clear 
              that any patent must be licensed for everyone's free use or not 
              licensed at all.<br>
              <a href="http://www.gnu.org/copyleft/gpl.html#TOC3" target="_blank">COPYING, 
              DISTRIBUTION AND MODIFICATION AGREEMENT</a><br>
            </p></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="800" cellpadding="0" cellspacing="0">
  <tr>
    <td class="copyright">Powered By <a href="http://www.hotwebtools.com">Hotwebtools.com</a></td>
  </tr>
  </table>
</body>
</html>
