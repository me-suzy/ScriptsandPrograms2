<?php
require ("presentation.inc.php");
HAUTPAGEWEB("Discoman - Top");
require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_trad.inc.php";
require($lang_filename);
?>

    <script language="javascript">
function affichemenu(numero) {
	if (numero==1) {//layer10
		document.all["Layer11"].style.visibility='hidden';
		document.all["Layer12"].style.visibility='hidden';
		document.all["Layer13"].style.visibility='hidden';
        if (document.all["Layer10"].style.visibility=='visible') document.all["Layer10"].style.visibility='hidden';
		else
		document.all["Layer10"].style.visibility='visible';
		}
	if (numero==2) {//layer11
		document.all["Layer10"].style.visibility='hidden';
		document.all["Layer12"].style.visibility='hidden';
		document.all["Layer13"].style.visibility='hidden';
        if (document.all["Layer11"].style.visibility=='visible') document.all["Layer11"].style.visibility='hidden';
		else
		document.all["Layer11"].style.visibility='visible';
		}
	if (numero==3) {//layer12
		document.all["Layer10"].style.visibility='hidden';
		document.all["Layer11"].style.visibility='hidden';
		document.all["Layer13"].style.visibility='hidden';
        if (document.all["Layer12"].style.visibility=='visible') document.all["Layer12"].style.visibility='hidden';
		else
		document.all["Layer12"].style.visibility='visible';
		}
	if (numero==4) {//layer13
	 	document.all["Layer10"].style.visibility='hidden';
	 	document.all["Layer11"].style.visibility='hidden';
	 	document.all["Layer12"].style.visibility='hidden';
        if (document.all["Layer13"].style.visibility=='visible') document.all["Layer13"].style.visibility='hidden';
		else
	 	document.all["Layer13"].style.visibility='visible';
		}
	if (numero==5) {
	 //	document.all["Layer79"].style.visibility='hidden';
	 //	document.all["Layer80"].style.visibility='hidden';
	 //	document.all["Layer81"].style.visibility='hidden';
	 //	document.all["Layer82"].style.visibility='hidden';
		}
	}
    	</script>

<div id='Layer1' style='position:absolute; width:800px; height:124px; z-index:1; left: 10; top: 0;background-image: url(images_site/fond04.gif); layer-background-image: url(images_site/fond04.gif); border: 0px none #000000'>
  <p>&nbsp;</p>
  <div id='Layer2' style='position:absolute; width:313px; height:24; z-index:2; left: 2; top: 0'><b><font face='Times New Roman, Times, serif' size='6'>Discographies</font></b></div>

<div id='Layer5' style='position:absolute; width:520px; height:37px; z-index:4; left: 265; top: 10'>
	<div align="right">
		<table>
        	<tr>
            	<td><input type="button" id="style1" value="<?php echo "$txt_consultation" ?>" onClick="affichemenu(2)"></td>
            	<td><input type="button" id="style1" value="<?php echo "$txt_connexion" ?>" onClick="affichemenu(3)"></td>
            	<td><input type="button" id="style1" value="?" onClick="affichemenu(4)"></td>
        	</tr>
        </table>
    </div>
</div>

  <div id="Layer10" style="position:absolute; width:76px; height:15px; z-index:16; left: 466px; top: 35px; background-color: #FFFFFF; border: 1px none #000000; visibility: hidden; overflow: hidden">
    <a href="add.php" onClick="affichemenu(1)" onMouseOver="this.style.background='#336699'; this.style.color='#FFFFFF'" onMouseOut="this.style.background='#FFFFFF'; this.style.color='#0000FF'"><b>&nbsp;<? echo "$txt_ajouter" ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
  </div>
  <div id="Layer11" style="position:absolute; width:76px; height:60px; z-index:16; left: 546px; top: 35px; background-color: #FFFFFF; border: 1px none #000000; visibility: hidden; overflow: hidden">
    <a href="search.php" onClick="affichemenu(2)" onMouseOver="this.style.background='#336699'; this.style.color='#FFFFFF'" onMouseOut="this.style.background='#FFFFFF'; this.style.color='#0000FF'"><b>&nbsp;<? echo "$txt_rechercher"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
    <a href="main7.php?search=%" onClick="affichemenu(2)" onMouseOver="this.style.background='#336699'; this.style.color='#FFFFFF'" onMouseOut="this.style.background='#FFFFFF'; this.style.color='#0000FF'"><b>&nbsp;<? echo "$txt_recents"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
    <a href="main2.php?search=%" onClick="affichemenu(2)" onMouseOver="this.style.background='#336699'; this.style.color='#FFFFFF'" onMouseOut="this.style.background='#FFFFFF'; this.style.color='#0000FF'"><b>&nbsp;<? echo "$txt_artistes"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
    <a href="main4.php" onClick="affichemenu(2)" onMouseOver="this.style.background='#336699'; this.style.color='#FFFFFF'" onMouseOut="this.style.background='#FFFFFF'; this.style.color='#0000FF'"><b>&nbsp;<? echo "$txt_infos"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
  </div>
  <div id="Layer12" style="position:absolute; width:76px; height:15px; z-index:16; left: 626px; top: 35px; background-color: #FFFFFF; border: 1px none #000000; visibility: hidden; overflow: hidden">
    <a href="admin.php" onClick="affichemenu(3)" onMouseOver="this.style.background='#336699'; this.style.color='#FFFFFF'" onMouseOut="this.style.background='#FFFFFF'; this.style.color='#0000FF'"><b>&nbsp;<? echo "$txt_admin"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
  </div>
  <div id="Layer13" style="position:absolute; width:76px; height:30px; z-index:16; left: 706px; top: 35px; background-color: #FFFFFF; border: 1px none #000000; visibility: hidden; overflow: hidden">
    <a href="help.php" onClick="affichemenu(4)" onMouseOver="this.style.background='#336699'; this.style.color='#FFFFFF'" onMouseOut="this.style.background='#FFFFFF'; this.style.color='#0000FF'"><b>&nbsp;<? echo "$txt_aide"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
    <a href="about.php" onClick="affichemenu(4)" onMouseOver="this.style.background='#336699'; this.style.color='#FFFFFF'" onMouseOut="this.style.background='#FFFFFF'; this.style.color='#0000FF'"><b>&nbsp;<? echo "$txt_a_propos"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
  </div>

<div id='Layer3' style='position:absolute; width:770px; height:70px; z-index:3; left: 15; top: 55; overflow: hidden'>
	<table class="Mtable" border="0" width="100%" cellpadding="0" cellspacing="0">
     	<th><? echo "$txt_recherche_rapide"?></th>
  	 </table>
     <table class="Stable" border="0" style="border-color:#000000;" width="100%" cellpadding="0" cellspacing="0">
     	<tr align="center">
        	<td width="40%">
        		<table>
                <form action="main.php" method="GET" name="mform" id="mform">
        			<tr>
        				<td>

							<div align="center">
								<select size="1" name="type">
          							<option selected value="1"><? echo "$txt_artiste"?></option>
          							<option value="2"><? echo "$txt_titre"?></option>
                            		<option value="3"><? echo "$txt_ref"?></option>
                            		<option value="4"><? echo "$txt_com"?></option>&nbsp;
        						<input type="text" name="search" value="<?php  if(!empty($_GET['search']))echo $_GET['search']; ?>">&nbsp;
   								<input type="submit" id="style1" value="<? echo "$txt_chercher"?>">
							</div>

        				</td>
      				</tr>
                    </form>
				</table>
        	</td>
     		<td width="60%">
    			<table>
					<tr>
    					<td align="right"><? echo "$txt_artistes"?> >>></td>
      					<td bgcolor="#FFFFFF" align="center">
						<b>
          				<a href="main2.php?search=a">A</a>
          				<a href="main2.php?search=b">B</a>
          				<a href="main2.php?search=c">C</a>
          				<a href="main2.php?search=d">D</a>
          				<a href="main2.php?search=e">E</a>
          				<a href="main2.php?search=f">F</a>
         	 			<a href="main2.php?search=g">G</a>
          				<a href="main2.php?search=h">H</a>
          				<a href="main2.php?search=i">I</a>
          				<a href="main2.php?search=j">J</a>
          				<a href="main2.php?search=k">K</a>
          				<a href="main2.php?search=l">L</a>
          				<a href="main2.php?search=m">M</a>
          				<a href="main2.php?search=n">N</a>
          				<a href="main2.php?search=o">O</a>
          				<a href="main2.php?search=p">P</a>
          				<a href="main2.php?search=q">Q</a>
          				<a href="main2.php?search=r">R</a>
          				<a href="main2.php?search=s">S</a>
          				<a href="main2.php?search=t">T</a>
          				<a href="main2.php?search=u">U</a>
          				<a href="main2.php?search=v">V</a>
          				<a href="main2.php?search=w">W</a>
          				<a href="main2.php?search=x">X</a>
          				<a href="main2.php?search=y">Y</a>
          				<a href="main2.php?search=z">Z</a>
          				<a href="main5.php?">0-9</a>
						</b>
						</td>
            		</tr>
        		</table>
        	</td>
		</tr>
	</table>
</div>
</div>

</body>

</html>