<?
function HAUTPAGEWEB($title)
{
echo "<html>

<head>
<title>$title</title>";
require ("meta.php");
echo "<base target=\"middle\">
<link rel=\"stylesheet\" href=\"style2.css\" type=\"text/css\">
<script language=\"JavaScript\">

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+\".location='\"+args[i+1]+\"'\");
}
//-->
</script>
</head>

<body style=\"scrollbar-face-color:white; scrollbar-base-color:white\";>\n";
}

function LAYERS()//layers principaux pour un affichage sur toute la page
{
echo "
<div id='Layer5' style='position:absolute; width:800px; height:430px; z-index:1; left: 10; top: 0; background-image: url(images_site/fond02.gif); layer-background-image: url(images_site/fond02.gif);  border: 1px none #000000; overflow: hidden'>\n";

echo "<div id='Layer6' style='position:absolute; width:770px; height:380px; z-index:1; left: 15; top:10; border: 1px none #000000; overflow: auto;'>\n";//layer inclus dans le 5
}

// LAYERS 2 ET 3 SONT IDENTIQUES !!!!!!!!!!!!!!!!!!!!!!!!

function LAYERS2()
{
echo "<div id='Layer5' style='position:absolute; width:800px; height:430px; z-index:1; left: 10; top: 0; background-image: url(images_site/fond02.gif); layer-background-image: url(images_site/fond02.gif); border: 1px none #000000; overflow: hidden'>\n";//layer principal

echo "<div id='Layer6' style='position:absolute; width:550px; height:390px; z-index:1; left: 15; top:10; border: 1px none #000000; overflow: auto;'>\n";//layer inclus dans le 5
}

function LAYERS3()//layer pour record_update
{
echo "<div id='Layer5' style='position:absolute; width:800px; height:430px; z-index:1; left: 10; top: 0; background-image: url(images_site/fond02.gif); layer-background-image: url(images_site/fond02.gif);  border: 1px none #000000; overflow: hidden'>\n";//layer principal

	echo "<div id='Layer6' style='position:absolute; width:550px; height:390px; z-index:1; left: 15; top:10; border: 1px none #000000; overflow: hidden;'>\n";//layer inclus dans le 5
	}

function LAYERS4()//layer pour record_update : affichage du tableau des résultats
{
	echo "<div id='Layer8' style='position:relative; width:550px; height:350px; z-index:1; left: 0; top:0; border: 1px none #000000; overflow: auto;'>";
	}

function LAYERS5() {//layer pour title.php : affichage de l'enregistrement
	echo "<div id='Layer8' style='position:relative; width:550px; height:355px; z-index:1; left: 0; top:0; border: 1px none #000000; overflow: auto;'>";
	}

function LAYERPAGEDEB() {//layer pour afficher les pages - largeur : 550
	echo "<div id='Layer7' style='position:absolute; width:550px; height:30px; z-index:1; left: 20; top: 400; border: 0px none #000000; overflow: auto;'>\n<div align=\"left\">\n"; //layer inclus dans le 6 pour afficher le nbre de résultats, les pages, le "back previous page"
     }

function LAYERPAGEDEB2() {//layer pour afficher les pages - largeur : 770
	echo "<div id='Layer7' style='position:absolute; width:770px; height:30px; z-index:1; left: 15; top: 400; border: 0px none #000000; overflow: auto;'>\n<div align=\"left\">\n"; //layer inclus dans le 6 pour afficher le nbre de résultats, les pages, le "back previous page"
     }

function LAYERPAGEDEB3($nombre) {//layer pour afficher back + imprimante - largeur : 550
	echo "<div id='Layer7' style='position:absolute; width:550px; height:30px; z-index:1; left: 15; top: 400; border: 0px none #000000; overflow: auto;'>\n<div align=\"left\">\n"; //layer inclus dans le 6 pour afficher les pages, le "back previous page et l'icône imprimante."
	echo "<table width='100%'>
	<tr>
        <td align='left'><a href=\"javascript:history.go(".$nombre.");\">[<< back to previous page]</a></td>
        <td align='right'>
        <img src=\"images_site/print.gif\" onClick='javascript:window.print()'></td>
    </tr>
</table></div></div>";
     }

function LAYERPAGEDEB4($nombre) {//layer pour afficher back sans imprimante
	echo "
    <div id='Layer7' style='position:absolute; width:550px; height:30px; z-index:1; left: 20; top: 400; border: 0px none #000000; overflow: auto;'>\n
	    <table width='100%'>
			<tr>
        		<td align='left'><a href=\"javascript:history.go(".$nombre.");\">[<< back to previous page]</a></td>
    		</tr>
		</table>
    </div>";
    }

function LAYERPAGEDEB5($curlevel) {//layer pour revenir au menu admin
	echo "
    <div id='Layer7' style='position:absolute; width:550px; height:30px; z-index:1; left: 20; top: 400; border: 0px none #000000; overflow: auto;'>\n
	    <table width='100%'>
			<tr>
        		<td align='left'><a href=\"record_update.php?curlevel=$curlevel\">[<< back to select artist]</a></td>
    		</tr>
		</table>
    </div>";
    }

function LAYERINTERNE() {//layer interne au 6 pour afficher les données existantes pour add (main9.php) et dans queries.php (pour "no record found")
     echo "<div id='Layer8' style='position:relative; width:100%; height:330px; z-index:1; left: 0; top:0; border: 1px none #000000; overflow: auto;'>";
     }

function LAYERINTERNE2() {//layer interne au 6 pour afficher les données existantes (update => main10.php, delete => main11.php)
     echo "<div id='Layer8' style='position:relative; width:100%; height:280px; z-index:1; left: 0; top:0; border: 1px none #000000; overflow: auto;'>";
     }

function LAYERINTERNE3() {//layer interne au 6 pour afficher les données existantes pour add (main9.php) et dans queries.php (pour "no record found")
     echo "<div id='Layer8' style='position:relative; width:100%; height:80px; z-index:1; left: 0; top:0; border: 1px none #000000; overflow: auto;'>";
     }

function LAYERINTERNE4($nombre) {//
     echo "<div id='Layer8' style='position:relative; width:100%; height:".$nombre."px; z-index:1; left: 0; top:0; border: 1px none #000000; overflow: auto;'>";
     }



function LAYERPAGEFIN() {
	echo"</div> </div>";
    }//fermeture du centrage et fin du layer 7

function BASPAGEWEB()
{
  echo "</div>
</div>
<div id='Layer10' style='position:absolute; width:800px; height:25px; z-index:1; left: 10; top: 430; background-image: url(images_site/fond03.gif); layer-background-image: url(images_site/fond03.gif); border: 1px none #000000; overflow: hidden'>";
echo "<div align=\"center\">DiscoMan &copy; 2004 - 2005 E.R. - <a href=\"http://www.the-mirror-of-dreams.com\" target=\"_blank\">www.the-mirror-of-dreams.com</a></div></div>


</body>
</html>";
}

function BASPAGEWEB2()
{
  echo "
</div>
<div id='Layer10' style='position:absolute; width:800px; height:25px; z-index:1; left: 10; top: 430; background-image: url(images_site/fond03.gif); layer-background-image: url(images_site/fond03.gif); border: 1px none #000000; overflow: hidden'>";
echo "<div align=\"center\">DiscoMan &copy; 2004 - 2005 E.R. - <a href=\"http://www.the-mirror-of-dreams.com\" target=\"_blank\">www.the-mirror-of-dreams.com</a></div></div>
</body>
</html>";
}

//function CREDIT() {//3ème frame en bas
//echo "<div id='Layer5' style='position:absolute; width:800px; height:25px; z-index:1; left: 10; top: 0; background-image: url(images_site/fond03.gif); layer-background-image: url(images_site/fond03.gif); border: 1px none #000000; overflow: hidden'>";
//}

?>