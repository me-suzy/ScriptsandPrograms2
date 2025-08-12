<? 
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Domain Seller Pro                                 //
// Release Version      : 1.5.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////
$param["width"] = 630;
$param["box_title"] = "Domain Seller Pro :: Admin Control";
$param["loginname"] = $adminemail;
$param["loginstatus"] = "Admin";

$param["menuitem"][1] = "Main";
$param["menulink"][1] = "admin.php?pass=$pass";
$param["menuitem"][2] = "Domains";
$param["menulink"][2] = "admin.php?a=list&pass=$pass";
$param["menuitem"][6] = "Logout";
$param["menulink"][6] = "index.php";

function adminboxstart($param) { 
extract($param);
global $pass;
if (!isset($width)) $width == 600;

$menwidth = ($width - 25)/6;

?>

<TABLE WIDTH=<?=$width?> BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR> 
    <TD> <IMG SRC="images/admin-box_01.gif" WIDTH=13 HEIGHT=6></TD>
    <TD background="images/admin-box_02.gif"> <IMG SRC="images/admin-box_02.gif" WIDTH=17 HEIGHT=6></TD>
    <TD background="images/admin-box_03.gif"> <IMG SRC="images/admin-box_02.gif" WIDTH=17 HEIGHT=6></TD>
    <TD background="images/admin-box_04.gif"> <IMG SRC="images/admin-box_04.gif" WIDTH=12 HEIGHT=6></TD>
  </TR>
  <TR> 
    <TD width="13" background="images/admin-box_05.gif"><IMG SRC="images/admin-box_05.gif" WIDTH=13 HEIGHT=24></TD>
    <TD colspan="2" background="images/admin-box_06.gif" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #FFFFFF"><strong><?=$box_title?></strong></TD>
    <TD width="12" background="images/admin-box_08.gif"> <IMG SRC="images/admin-box_08.gif" WIDTH=12 HEIGHT=24></TD>
  </TR>
  <TR> 
    <TD background="images/admin-box_09.gif"> <IMG SRC="images/admin-box_09.gif" WIDTH=13 HEIGHT=21></TD>
    <TD colspan="2" background="images/admin-box_10.gif" bgcolor="#C8C8C8" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000000">
	<table width="100%" border="0" cellpadding="1">
        <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000000"> 

<? for($i=1;$i < 6;$i++) { 
if (isset($menulink[$i])) { ?>
		  <td width="<?=$menwidth?>" onMouseOver="javascript:mOvr(this,'#666699')" onMouseOut="javascript:mOut(this,'#C8C8C8')"><div align="center"><a href="<? print $menulink[$i];?>"><? print $menuitem[$i]; ?></a></div></td>
<? } else {?>
		  <td width="<?=$menwidth?>" ><div align="center"><? print $menuitem[$i]; ?></div></td>
<? } }?>          
			<td onMouseOver="javascript:mOvr(this,'#666699')" onMouseOut="javascript:mOut(this,'#C8C8C8')" ><div align="center"><a href="<?=$menulink[6]?>"><?=$menuitem[6]?></a></div></td>
        </tr>
      </table></TD>
    <TD width="12" background="images/admin-box_12.gif"> <IMG SRC="images/admin-box_12.gif" WIDTH=12 HEIGHT=21></TD>
  </TR>
  <TR> 
    <TD background="images/admin-box_13.gif"><IMG SRC="images/admin-box_13.gif" WIDTH=13 HEIGHT=3></TD>
    <TD colspan="2" background="images/admin-box_14.gif"><IMG SRC="images/admin-box_14.gif" WIDTH=17 HEIGHT=3></TD>
    <TD background="images/admin-box_16.gif"> <IMG SRC="images/admin-box_16.gif" WIDTH=12 HEIGHT=3></TD>
  </TR>
  <TR> 
    <TD background="images/admin-box_21.gif" bgcolor="#FFFFFF"><IMG SRC="images/admin-box_21.gif" WIDTH=13 HEIGHT=394></TD>
    <TD colspan="2" valign="top" bgcolor="#FFFFFF" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000000"> 
      <br>
 <? } 

function adminboxend($param) { 
global $PHP_AUTH_USER,$pass;
extract($param);	

	 ?>
      <br>
    </TD>
    <TD background="images/admin-box_24.gif" bgcolor="#FFFFFF"> <IMG SRC="images/admin-box_24.gif" WIDTH=12 HEIGHT=394></TD>
  </TR>
  <TR> 
    <TD background="images/admin-box_25.gif"> <IMG SRC="images/admin-box_25.gif" WIDTH=13 HEIGHT=3></TD>
    <TD background="images/admin-box_26.gif"> <IMG SRC="images/admin-box_26.gif" WIDTH=17 HEIGHT=3></TD>
    <TD background="images/admin-box_26.gif"> <IMG SRC="images/admin-box_26.gif" WIDTH=569 HEIGHT=3></TD>
    <TD background="images/admin-box_28.gif"> <IMG SRC="images/admin-box_28.gif" WIDTH=12 HEIGHT=3></TD>
  </TR>
  <TR> 
    <TD background="images/admin-box_29.gif"> <IMG SRC="images/admin-box_29.gif" WIDTH=13 HEIGHT=20></TD>
    <TD colspan="2" background="images/admin-box_30.gif" bgcolor="#C8C8C8" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000000"><? if (isset($loginname)) { ?>Logged In: <?=$PHP_AUTH_USER?> (<?=$loginstatus?>)<? } ?></TD>
    <TD background="images/admin-box_32.gif"> <IMG SRC="images/admin-box_32.gif" WIDTH=12 HEIGHT=20></TD>
  </TR>
  <TR> 
    <TD background="images/admin-box_33.gif"> <IMG SRC="images/admin-box_33.gif" WIDTH=13 HEIGHT=7></TD>
    <TD colspan=2 background="images/admin-box_34.gif"> <IMG SRC="images/admin-box_34.gif" WIDTH=17 HEIGHT=7></TD>
    <TD background="images/admin-box_36.gif"> <IMG SRC="images/admin-box_36.gif" WIDTH=12 HEIGHT=7></TD>
  </TR>
</TABLE>

 <? } 

function adminmenu($param){
	global $t_main,$pass;
	myconnect();
$query = "select ID from dsp_domains where status='1'";      
$result = MYSQL_QUERY($query);
$pendings=mysql_num_rows($result);
@mysql_free_result($result);
$query = "select ID from dsp_offers where status='0' order by ID desc";      
$result = MYSQL_QUERY($query);
$newoffers=mysql_num_rows($result);
?>
    <TABLE cellSpacing=8 cellPadding=0 width="100%" border=0>
          <TBODY> 
          <TR> 
            <TD width="64%" valign="top"> 
              <table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr valign="middle" align="center"> 
            <td height="48" width="33%"> <div align="center"><a href="admin.php?a=list&pass=<?echo $pass;?>"><img src="images/dotcom.gif" width="32" height="32" border="0"></a> 
              </div></td>
            <td width="33%"> <div align="center"><a href="admin.php?a=add&pass=<?echo $pass;?>"><img src="images/Bpush.gif" width="32" height="32" border="0"></a><br>
              </div></td>
            <td> <div align="center"><a href="admin.php?a=import&pass=<?echo $pass;?>"><img src="images/Case3.gif" width="32" height="32" border="0"></a></div></td>
          </tr>
          <tr valign="middle" align="center"> 
            <td height="26"> <div align="center"><a href="admin.php?a=list&pass=<?echo $pass;?>">Available 
                Domains </a></div></td>
            <td> <div align="center"><a href="admin.php?a=add&pass=<?echo $pass;?>">Add 
                New Domain</a></div></td>
            <td> <div align="center"><a href="admin.php?a=import&pass=<?echo $pass;?>">Import 
                Domain List</a></div></td>
          </tr>
          <tr valign="middle" align="center"> 
            <td height="48"> <div align="center"><a href="admin.php?a=o&pass=<?echo $pass;?>"><img src="images/star4.gif" width="33" height="31" border="0"></a></div></td>
            <td> <div align="center"><a href="admin.php?a=oarchive&pass=<?echo $pass;?>"><img src="images/Box2.gif" width="32" height="32" border="0"></a></div></td>
            <td> <div align="center"><a href="admin.php?a=plist&pass=<?echo $pass;?>"><img src="images/contract.gif" width="28" height="32" border="0"></a></div></td>
          </tr>
          <tr valign="middle" align="center"> 
            <td> <div align="center"><a href="admin.php?a=o&pass=<?echo $pass;?>">New 
                Offers</a></div></td>
            <td> <div align="center"><a href="admin.php?a=oarchive&pass=<?echo $pass;?>">Offer 
                Archive</a></div></td>
            <td> <div align="center"><a href="admin.php?a=plist&pass=<?echo $pass;?>">Pending 
                Sales</a></div></td>
          </tr>    <tr valign="middle" align="center"> 
            <td height="48"> <div align="center"><a href="admin.php?a=archive&pass=<?echo $pass;?>"><img src="images/Box.gif" width="32" height="32" border="0"></a></div></td>
            <td> <div align="center"><a href="admin.php?a=users&pass=<?echo $pass;?>"><img src="images/MorePeople.gif" width="32" height="32" border="0"></a></div></td>
            <td> <div align="center"><a href="admin.php?a=process&pass=<?echo $pass;?>"><img src="images/Book2.gif" width="34" height="33" border="0"></a></div></td>
          </tr>
          <tr valign="middle" align="center"> 
            <td> <div align="center"><a href="admin.php?a=archive&pass=<?echo $pass;?>">SOLD 
                Domains</a></div></td>
            <td> <div align="center"><a href="admin.php?a=users&pass=<?echo $pass;?>">View 
                Buyers</a></div></td>
            <td> <div align="center"><a href="admin.php?a=process&pass=<?echo $pass;?>">Uncategorized 
                Domains </a></div></td>
          </tr>
          <tr valign="middle" align="center"> 
            <td height="48"> <div align="center"><a href="admin.php?a=cats&pass=<?echo $pass;?>"><img src="images/Yellowpa.gif" width="32" height="32" border="0"></a></div></td>
            <td> <div align="center"><a href="admin.php?a=chemail&pass=<?echo $pass;?>"><img src="images/Case2.gif" width="32" height="32" border="0"></a></div></td>
            <td> <div align="center"><a href="admin.php?a=chpass&pass=<?echo $pass;?>"><img src="images/Keys.gif" width="32" height="32" border="0"></a></div></td>
          </tr>
          <tr valign="middle" align="center"> 
            <td> <div align="center"><a href="admin.php?a=cats&pass=<?echo $pass;?>">Categories</a></div></td>
            <td> <div align="center"><a href="admin.php?a=chemail&pass=<?echo $pass;?>">Change 
                Admin Email</a></div></td>
            <td> <div align="center"><a href="admin.php?a=chpass&pass=<?echo $pass;?>">Admin 
                Password</a></div></td>
          </tr>
        </table>
              <br>
      </TD>
            <TD align=right width="36%" valign="top"> 
              <div align="left"> <br>
                
          <table width="100%" border="0" cellspacing="3" cellpadding="0">
            <tr> 
              <td width="20"> 
                <?
						if ($newoffers > 5) $color="red";
						elseif ($newoffers > 0) $color="yellow";
						else $color="green";
						?>
                <img src="images/little<?=$color?>.gif" width="14" height="14"> 
              </td>
              <td width="128"> <div align="left"><a href="admin.php?a=o&pass=<?echo $pass;?>"> New Offers</a></div></td>
              <td width="30"> 
                <?=$newoffers?>
              </td>
            </tr>
			     <?
						if ($pendings > 3) $color="red";
						elseif ($pendings > 0) $color="yellow";
						else $color="green";
						?>
			<tr> 
              <td width="20"> 
                <img src="images/little<?=$color?>.gif" width="14" height="14"> 
              </td>
              <td width="128"> <div align="left"><a href="admin.php?a=plist&pass=<?echo $pass;?>"> 
                  Pending Sales</a></div></td>
              <td width="30"> 
               <?print $pendings;?>
              </td>
            </tr>
          </table>
          <br>
		  <table cellspacing=0 cellpadding=0 width="100%" border=0>
<tbody> 
                  <tr bgcolor="#999999"> 
                    <td> 
                      <table cellspacing=1 cellpadding=0 width="100%" border=0>
                        <tbody> 
                        <tr bgcolor="#eeeeee"> 
                          
                        <td><b>&nbsp;Domain Seller Pro Updates</b><br>
                            <!-- CyKuH [WTN]--><center><font color=#999999>Disable by WTN Team
                          </td>
                        </tr>
                        </tbody> 
                      </table>
                    </td>
                  </tr>
                  </tbody> 
                </table>
                 
          <p><br>
            <br>
          </p>
        </div>
            </TD>
          </TR>
          </TBODY> 
        </TABLE>
<?
}

 
 ?>