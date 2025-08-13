<?
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.0                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : Stive [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2002
/////////////////////////////////////////////////////////////
  $section = "nullified";
  include( "initialize.php" );
  
  checklogged();
  showheader( $section );
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="action" value="save">
  <tr class="header">
    <td colspan="2">Nullification Info</td>
  </tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>Program Name</b><br>
	</td><td>
  <b>EzUpload Pro</b><br>
        </td></tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>Program Version</b><br>
	</td><td>
  <b>v2.0</b><br>
    </td></tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>Program Author</b><br>
	</td><td>
  <b>ScriptsCenter.com</b><br>
        </td></tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>Home Page</b><br>
	</td><td>
  <b>http://www.scriptscenter.com</b><br>
    </td></tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>Retail Price</b><br>
	</td><td>
  <b>$39.00 United States Dollars</b><br>
        </td></tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>WebForum Price</b><br>
	</td><td>
  <b>$00.00 Always 100% Free</b><br>
    </td></tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>xCGI Price</b><br>
	</td><td>
  <b>$00.00 Always 100% Free</b><br>
        </td></tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>Supplied by</b><br>
	</td><td>
  <b>Stive [WTN]</b><br>
    </td></tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>Nullified and tested</b><br>
	</td><td>
  <b>CyKuH [WTN]</b><br>
        </td></tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>Distribution</b><br>
	</td><td>
  <b>via WebForum and Forums File Dumps</b><br>
    </td></tr>
  <tr <?=getaltclass()?>><td width="50%" valign="top">
  <b>Extra Info</b><br>
	</td><td>
  <b>&copy WTN Team `2002</b><br>
    </td></tr>
</table>
<? showfooter($section); ?>
