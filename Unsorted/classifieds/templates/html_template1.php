<HTML>


<HEAD>

<TITLE> D.E. Classifieds </TITLE>

<?php main_css(); ?>

</HEAD>

<BODY BGCOLOR="#CCCC99" TOPMARGIN="0" LEFTMARGIN="0" >

<CENTER>


<?php main_header(); ?>

<?php top_nav(); ?>

<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" WIDTH="100%" HEIGHT="600">
<TR>
<TD valign="top" width="130" bgcolor="#CCCC99">

<?php display_cats_main(); ?>


</TD>

<TD valign="top" width="100%" BGCOLOR="#FFFFFF">
 <table cellpadding="10" cellspacing="0" border="0" width="100%">
 <tr><td valign="top" width="100%">
 <!-- ********** MAIN TABLE ********** -->

 <?php content($content); ?>

 </td></tr></table>
</TD>

<TD valign="top" width="150" background="<?echo cnfg('deDir');?>images/bg_rightNav.gif">

<?php log_in_form_and_status(); ?>


</TD>
</TR>

<TR>
<TD BGCOLOR="#CCCC99">
&nbsp;
</TD>
<TD BGCOLOR="#CCCC99" VALIGN="TOP">
<CENTER>
<?php main_footer(); ?>
</CENTER>
</TD>
<TD BGCOLOR="#CCCC99">
&nbsp;
</TD>
</TR>
</TABLE>

</CENTER>

</BODY>
</HTML>
