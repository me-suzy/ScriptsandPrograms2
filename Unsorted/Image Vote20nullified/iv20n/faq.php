<? 
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Image Vote - Photo Rating System                  //
// Release Version      : 2.0.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////
if (!$go) { ?>

<html>
<head>
<title>Submit Your Picture For Votes!</title>
</head>
<body bgcolor="#ffffff" text="#000000" link="#006699" alink="#000000" vlink="#000000" marginheight="0" marginwidth="0" topmargin=0 leftmargin=0 rightmargin=0>
<table border=0 cellpadding=0 cellspacing=0 width="760" align="center">
  <center>
    <tr bgcolor="#375288"> 
      <td> 
      <? } ?>
        <table border=0 cellspacing=1 cellpadding=4 width="100%" align="center">
          <tr> 
            <td valign="top" colspan="2" bgcolor="#f7f7f7"> 
              <div align="left"> 
                <p><font size="2" face="Verdana, Arial" color="black"> </font><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><b>All 
                  pictures are reviewed before being added to the <?=$sitename?>
                  site.</b></font></p>
                <div align="center"> </div>
                <p align="left"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">What 
                  pictures are not accepted?</font></b></p>
                <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Pictures 
                  are inappropriate for this site if they contain nudity, celebrates, 
                  jokes, URLs, or if the picture is not of a person. </font></p>
                <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>What 
                  if I see a photo on this site that is inappropriate?</b></font></p>
                <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Click 
                  the link below the photo to report this violation. The photo 
                  will be re-reviewed by the staff. If you notice a photo that 
                  you know to be copyrighted or should not be on here for some 
                  other reason, please e-mail us at <?=$admin?></font></p>
                <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>What 
                  is my Photo URL? </b></font></p>
                <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Your 
                  photo URL is the location on the Internet where your picture 
                  is stored. If you need to upload your picture somewhere, here 
                  are a few places you can do so: <a href="http://www.photopoint.com">Photopoint</a> 
                  - <a href="http://www.facelink.com">Facelink</a></font></p>
                <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Can 
                  I take my picture off the site once it has been submitted?</b> 
                  </font></p>
                <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Yes, 
                  once you log in, you can remove your picture at any time. Click 
                  on the "remove picture" link from the members menu.</font></p>
                <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>How 
                  can I contact someone who's picture I see on this site?</b></font></p>
                <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">You 
                  can click the &quot;Message Me&quot; button underneath any picture 
                  you see to send a message to the person. They will be able to 
                  view your message next time they log into this site. Under no 
                  circumstances will we give out the e-mail address or contact 
                  information of anyone listed on this site. </font></p>
                <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Any 
                  other questions?</b></font></p>
                <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">E-mail 
                  us at:<br>
                  </font><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="mailto:<?=$admin?>"><?=$admin?></a></font></p>
              </div>
              </td>
          </tr>
        </table>
<? if (!$go) { ?>
      </td>
    </tr>
  </center></table>
</body>
</html>
<? } ?>
