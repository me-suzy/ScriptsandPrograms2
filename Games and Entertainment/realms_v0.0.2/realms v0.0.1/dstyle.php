<?

$useie = strpos("$_SERVER[HTTP_USER_AGENT]", 'MSIE');
if($useie>0){
$msie=1;
}

?>


<style type="text/css">

body {
        background: black;
        margin: 0;
        padding: 0;
        border: 0;
        font-family: "Verdana", sans-serif;
        font-size: 11px;
        color: #76858B;
        }



p {
        padding: 3px 0px;
        margin: 8px 0px 0px 0px;
        }

a:link {
        color: #68BACA;
        text-decoration: underline;
                font-weight: bold;
        }

a:visited {
        color: #ACC5CA;
        text-decoration: underline;
                font-weight: bold;
        }

a:hover {
        text-decoration: none;
                font-weight: bold;
        }

a:active {
        color: #ACC5CA;
        text-decoration: none;
        font-weight: bold;
        }

h1, h2, h3 {
        font-weight: bold;
        }

h1 {
        font-size: 30px;
        }

h2 {
        font-size: 16px;
        }

h3 {
        font-size: 11px;
        }

.topborder1 {
<?
if($msie==1){
        print"position: absolute;";
}else{
        print"position: fixed;";
}
?>
        background: transparent url(img/dlogo.gif) no-repeat top right;
        width: 100%;
        min-width: 700px;
        height: 184px;
        z-index: 90;
        }



.background {
<?
if($msie==1){
        print"position: absolute;";
}else{
        print"position: fixed;";
}
?>
        background: transparent url(img/dbg.gif) repeat-x <?
        if($msie==1){
        print"scroll";
}else{
        print"fixed";
} ?> 0% 0%;
        top: 0px;
        left: 0px;
        padding: 0px;
        height: 200px;
        width: 100%;
        min-width: 750px;
        z-index: 35;
        }





.menu {
<?
if($msie==1){
        print"position: absolute;";
}else{
        print"position: fixed;";
}
?>
        top: 0px;
        left: 0px;
        padding: 10px;
        height: 200px;
        width: 60%;
        z-index: 100;
        }

.menu a:link {
        color: #68BACA;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        }

.menu a:visited {
        color: #ACA5AA;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        }

.menu a:hover {
        color: #DDC5CA;
        text-decoration: none;
        font-size: 16px;
        font-weight: normal;
        }

.menu a:active {
        color: #ACC5CA;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        }






.topborder2 {
<?
if($msie==1){
        print"position: absolute;";
}else{
        print"position: fixed;";
}
?>
        top: 126px;
        left: 0px;
        background: #000 url(img/dbordtop.gif) repeat-x;
        width: 100%;
        min-width: 700px;
        height: 44px;
        z-index: 60;
        }

.maincontent {
        position: absolute;
        background: #000;
        top: 200px;
        left: 210px;
        right: 210px;
        padding-left: 12px;
        padding-top: 12px;
        padding-right: 12px;
        padding-bottom: 12px;
        margin-bottom: 10px;
        border: solid 1px #E4EDF0;
        z-index: 34;
        }


.popcontent {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        margin-bottom: 10px;
        padding-bottom: 25px;
        border: solid 1px #E4EDF0;
        z-index: 34;
        }



.rightbar {
<?
if($msie==1){
        print"position: absolute;";
}else{
        print"position: fixed;";
}
?>
        top: 200px;
        right: 10px;
        width: 200px;
        padding: 5px 5px 5px 5px;
        border: solid 1px #E4EDF0;

        width: 180px;
        voice-family: "\"}\"";
        voice-family:inherit;
        width: 150px;
        z-index: 30;
        }
html>body .rightbar {
          width: 150px;
        }







.leftbar {
<?
if($msie==1){
        print"position: absolute;";
}else{
        print"position: fixed;";
}
?>
        top: 200px;
        left: 10px;
        width: 200px;
        padding: 5px 5px 5px 5px;
        border: solid 1px #E4EDF0;
        width: 180px;
        voice-family: "\"}\"";
        voice-family:inherit;
        width: 150px;
        z-index: 30;
        }

html>body .leftbar {
          width: 150px;
        }

</style>




