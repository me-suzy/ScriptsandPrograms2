<?php
include 'classes/HEADER.PHP';
$head = "INSTALL MYSQL DB FOR THE SIGN WEB PROGRAMS";
myheader($head);
?>
   <FORM NAME='INSTALL' METHOD='GET' ACTION='SETUP.PHP'>
      <TABLE ALIGN='CENTER' BORDER=1 >
        <TR>
            <TD ALIGN='CENTER' COLSPAN='2'><H3>DATABASE INFORMATION</H3>
            </TD>
        </TR>
        <TR>
            <TD ALIGN='RIGHT'>MYSQL HOST NAME
            </TD>
            <TD ALIGN='RIGHT'><INPUT NAME='HOST' TYPE='TEXT' VALUE='localhost'></INPUT>
            </TD>
        </TR>
        <TR>
            <TD ALIGN='RIGHT'>MYSQL USER NAME
            </TD>
            <TD ALIGN='RIGHT'><INPUT NAME='USER' TYPE='TEXT' ></INPUT>
            </TD>
        </TR>
        <TR>
            <TD ALIGN='RIGHT'>MYSQL PASSWORD
            </TD>
            <TD ALIGN='RIGHT'><INPUT NAME='PASS' TYPE='TEXT' ></INPUT>
            </TD>
        </TR>
        <TR>
            <TD ALIGN='RIGHT'>DATABASE NAME
            </TD>
            <TD ALIGN='RIGHT'><INPUT NAME='DB_NAME' TYPE='TEXT' ></INPUT>
            </TD>
        </TR>
        <TR>
            <TD ALIGN='CENTER' COLSPAN='2'><H3>SITE ADMIN DATA</H3>
            </TD>
        </TR>
        <TR>
            <TD ALIGN='RIGHT'>ADMINISTRAITOR
            </TD>
            <TD ALIGN='RIGHT'><INPUT NAME='ADMIN' TYPE='TEXT' VALUE='ADMIN'></INPUT>
            </TD>
        </TR>
        <TR>
            <TD ALIGN='RIGHT'>PASSWORD
            </TD>
            <TD ALIGN='RIGHT'><INPUT NAME='ADMIN_PASS' TYPE='TEXT' ></INPUT>
            </TD>
        </TR>
        <TR>
            <TD ALIGN='RIGHT'><INPUT TYPE='SUBMIT' VALUE='CONNECT TO DATABASE'></INPUT>
            </TD>
            <TD ALIGN='RIGHT'><INPUT TYPE='RESET' VALUE='CLEAR FORM' ></INPUT>
            </TD>
        </TR>
      </TABLE>
   </FORM>
   </BODY>
</HTML>