<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<HEAD>
<TITLE>iScramble Examples</TITLE>
<META HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="Content-Language" CONTENT="EN-GB">
<META NAME="author" CONTENT="Ian Willis">
<META NAME="copyright" CONTENT="Copyright Ian Willis. All rights reserved.">
</HEAD>

<?php

// Include the iScramble PHP function
require_once("iscramble.inc.php");

?>

<BODY BGCOLOR="#ffffc0" TEXT="#000000">

<H1>iScramble Examples</H1>

<H2>An email address</H2>

<P>The following email address is scrambled:

<P>

<?php

echo iScramble("private@somewhere.com");

?>

<P>The following mailto: link is scrambled:

<P>

<?php

echo iScramble("<A HREF=\"mailto:private@somewhere.com\">private@somewhere.com</A>");

?>

<H2>Scrambling a paragraph</H2>

<?php

echo iScramble('
    <P>This paragraph contains some
    <FONT COLOR="#ff0000">H</FONT><FONT COLOR="#00ff00">T</FONT><FONT COLOR="#0000ff">M</FONT><FONT COLOR="#ff00ff">L</FONT>
    tricks that I want to keep secret, so it is scrambled with iScramble.
');

?>

</BODY>
</HTML>
