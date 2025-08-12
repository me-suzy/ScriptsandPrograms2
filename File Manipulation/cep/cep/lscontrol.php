<?php
/*------------------------------------------------------------------------------
CJG EXPLORER PRO v3.2 - WEB FILE MANAGEMENT - Copyright (C) 2003 CARLOS GUERLLOY
CJGSOFT Software
cjgexplorerpro@guerlloy.com
guerlloy@hotmail.com
carlos@weinstein.com.ar
Buenos Aires, Argentina
--------------------------------------------------------------------------------
This program is free software; you can  redistribute it and/or  modify it  under
the terms   of the   GNU General   Public License   as published   by the   Free
Software Foundation; either  version 2   of the  License, or  (at  your  option)
any  later version. This program  is  distributed in  the hope that  it  will be
useful,  but  WITHOUT  ANY  WARRANTY;  without  even  the   implied  warranty of
MERCHANTABILITY  or FITNESS  FOR A  PARTICULAR  PURPOSE.  See the  GNU   General
Public License for   more details. You  should have received  a copy of  the GNU
General Public License along  with this  program; if   not, write  to the   Free
Software  Foundation, Inc.,  59 Temple Place,  Suite 330, Boston,  MA 02111-1307
USA
------------------------------------------------------------------------------*/

include("config.php");
include("csave/permsfull.php");
include("csave/datefull.php");
include("csave/alternateback.php");
include("csave/previewfiles.php");
include("csave/col_size.php");
include("csave/col_date.php");
include("csave/col_perm.php");
include("csave/col_type.php");
include("csave/col_owner.php");
include("csave/col_group.php");

?>

<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE>CJG</TITLE>
<SCRIPT>
function unyift(d) { var len=this.length; len=(len<0)?0:len; this.reverse(); this[len]=d; this.reverse(); }
function puy(d) { var len=this.length; len=(len<0)?0:len; this[len]=d; }
Array.prototype.unyift=unyift;
Array.prototype.puy=puy;

function his(pfunc,plista,ppwd,parg1,presult,perr) {
this.func=pfunc;
this.lista=plista;
this.pwd=ppwd;
this.arg1=parg1;
this.result=presult;
this.err=perr;
this.hora=new Date(); }

starttime=new Date();

function configobject() {
this.wtop=window.parent;
this.sc=<?php echo("new Array($col_name,$col_size,$col_date,$col_type,$col_perm,$col_owner,$col_group)");?>;
this.alternateback=<?php echo($alternateback);?>;
this.datefull=<?php echo($datefull);?>; 
this.permsfull=<?php echo($permsfull);?>; 
}
oconf=new configobject();

function addbase(d) { var i;
for(i=0;i<hbase.length;i++) if(hbase[i]==d) break;
if(i==hbase.length) { hbase.unyift(d); return; }
for(j=i;j;j--) hbase[j]=hbase[j-1]; hbase[0]=d; }

function addfunc(d) { hfunc.puy(d); }

var hbase=new Array();
var hfunc=new Array();

function hinit() { hfunc=new Array(); }

function treeobject() {
this.valor="";
this.treedeep=0;
this.treeleaves=0;
this.treeheavy=0; }
oroot=new treeobject();

function callmodeless(a,b,h,w) { var c;
if(arguments.length<2) b=0;
if(arguments.length<3) { h=<?php echo $shed_height;?>; w=<?php echo $shed_width;?>; }
if(arguments.length<4) w=<?php echo $shed_width;?>;
c='center:1;DialogHeight:'+h+'px;DialogWidth:'+w+'px;status:0;resizable:1;help:no;edge:raised;';
//alert(a); t=0;
var t=window.showModelessDialog(a,b,c);
return t; }

function ue(u) { return(escape(u).replace(/\+/g,"%2b")); }

</SCRIPT>
</HEAD>
</HTML>
