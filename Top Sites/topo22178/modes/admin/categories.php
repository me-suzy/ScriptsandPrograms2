<?php
//
//  modes/admin/categories.php
//	rev012
//  PHP v4.2+
//

//----------------------------------------------------------------
// COMPROBACION DEL CONTEXTO
//----------------------------------------------------------------
if(!stristr($_SERVER['PHP_SELF'],'index.php')) {
	echo $_SERVER['PHP_SELF'];
	echo '<SCRIPT>window.location.href="index.php";</SCRIPT>';
	exit();
}

//----------------------------------------------------------------
// PARAMETROS DE ENTRADA
//----------------------------------------------------------------

//----------------------------------------------------------------
// CONTENIDO
//----------------------------------------------------------------
$indice=new Index;
$categorias=new Categorias;

if($paso=='') {
	$HTML.='<TABLE align="center" class="0" cellpadding="2" cellspacing="1">';
	$HTML.='<TR class="0"><TD align="center" colspan="4"><span class="title">'.$_Categories_.'</span></TD></TR>';
	$HTML.='<TR class="1"><TD align="right" colspan="2"><span class="minititle">'.$_Categories_.'</span></TD>';
	$HTML.='<TD colspan="2"> <SELECT onChange="parent.location.href=\'index.php?m=admin&s=html&t=categories&paso=cambiarCategoria&gCategoriasNEW=\'+ this.options[this.selectedIndex].value;">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gCategorias) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>';
	if($gCategorias) {
		$HTML.='<TR class="1"><TD align="right" colspan="2"><span class="minititle">'.$_AfterVote_.'</span></TD>';
		$HTML.='<TD colspan="2"> <SELECT onChange="location.href=\'index.php?m=admin&s=html&t=categories&paso=cambiarTipoTop&gTipoTopNEW=\'+ this.options[this.selectedIndex].value;">';
		$HTML.='<OPTION value="0">'.$_AllTop_.'</OPTION>';
		$HTML.='<OPTION value="1"';
		if($gTipoTop) $HTML.=' selected';
		$HTML.='>'.$_CategoryTop_.'</OPTION>';
		$HTML.='</SELECT></TD></TR>';
		$HTML.='<TR><TD class="1" align="center" colspan="4"><INPUT id="addCategory" type="text" class="text" size="30" onKeyUp="oBoton=getObject(\'botonAdd\'); if(this.value==\'\') { oBoton.disabled=true; } else { oBoton.disabled=false; }"> <INPUT type="button" ID="botonAdd" class="minibutton" value="'.$_AddCategory_.'" onClick="catNEW=getObject(\'addCategory\'); document.location.href=\'index.php?m=admin&s=html&t=categories&paso=addCategoria&categoriaNEW=\'+ catNEW.value;" DISABLED></TD></TR>';
		$HTML.='<TR class="0"><TD align="center" colspan="4"><span class="minititle">'.str_replace('{NUMBER}',$categorias->numRegistros,$_NumberCategories_).'</span></TD></TR>';
		if(is_array($categorias->registros)) {
			foreach($categorias->registros as $key => $value) {
				$i++;
				$HTML.='<TR class="'.(1+$i%2).'">';
				$HTML.='<TD align="center"><span class="minitext">'.ej3Date('fechaCorta',$key).'</span></TD>';
				$HTML.='<TD id="td_'.$key.'"><INPUT type="button" class="minibutton" value="'.$_Edit_.'" onClick="Editar(\'td_'.$key.'\',\''.$categorias->Leer($key,2).'\',\''.$key.'\')"> '.$categorias->Leer($key,2).'</TD>';
				$HTML.='<TD align="right"><INPUT type="button" class="minibutton" value="'.$_Delete_.'" onClick="Borrar(\''.$key.'\')"></TD>';
				$HTML.='</TR>';
			}
		}
	}
	$HTML.='</TABLE>';
	$HTML.='<SCRIPT>';
	$HTML.='function Editar(idTD,texto,idCategoria) {';
	$HTML.='	oidTD=getObject(idTD);';
	$HTML.='	oidTD.innerHTML=\'<INPUT type="text" class="text" size="30" id="id\'+ idCategoria +\'" value="\'+ texto +\'"> <INPUT type="button" class="minibutton" value="'.$_Save_.'" onClick="Guardar(\'+ idCategoria +\');">\';';
	$HTML.='}';
	$HTML.='function Guardar(idCategoria) {';
	$HTML.='	oidTexto=getObject(\'id\'+ idCategoria);';
	$HTML.='	parent.location.href=\'index.php?m=admin&s=html&t=categories&paso=guardarCategoria&ID=\'+ idCategoria +\'&categoriaNombreNEW=\'+ oidTexto.value;';
	$HTML.='}';
	$HTML.='function Borrar(idCategoria) {';
	$HTML.='	parent.location.href=\'index.php?m=admin&s=html&t=categories&paso=borrarCategoria&ID=\'+ idCategoria;';
	$HTML.='}';
	$HTML.='</SCRIPT>';
}

if($paso=='addCategoria') {	//Añadimos una nueva categoria.
	//Adaptacion de variables para PHP v4.2+
	if(isset($_POST['categoriaNEW'])) $categoriaNEW=$_POST['categoriaNEW']; else $categoriaNEW=$_GET['categoriaNEW'];
	//--------------------------------------
	$aux[0]=ej3Time();
	$aux[1]=1;	//La categoria está "activa" por defecto.
	$aux[2]=$categoriaNEW;
	$categorias->Escribir($aux[0],$aux);	//Guarda automáticamente.
       //Recargamos
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>document.location.href="index.php?m=admin&s=html&t=categories";</script>';
}

if($paso=='guardarCategoria') {
	//Adaptacion de variables para PHP v4.2+
	if(isset($_POST['ID'])) $ID=$_POST['ID']; else $ID=$_GET['ID'];
	if(isset($_POST['categoriaNombreNEW'])) $categoriaNombreNEW=$_POST['categoriaNombreNEW']; else $categoriaNombreNEW=$_GET['categoriaNombreNEW'];
	//--------------------------------------
	$data[0]=$ID;
	$data[1]=$categorias->Leer($ID,1);
	$data[2]=$categoriaNombreNEW;
	$categorias->Escribir($ID,$data);
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>parent.location.href="index.php?m=admin&s=html&t=categories";</script>';
}

if($paso=='borrarCategoria') {
	//Adaptacion de variables para PHP v4.2+
	if(isset($_POST['ID'])) $ID=$_POST['ID']; else $ID=$_GET['ID'];
	//--------------------------------------
	$categorias->Borrar($ID);
	$indice->BorrarCategoria($ID);
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>parent.location.href="index.php?m=admin&s=html&t=categories";</script>';
}

if($paso=='cambiarCategoria') {
	//Adaptacion de variables para PHP v4.2+
	if(isset($_POST['gCategoriasNEW'])) $gCategoriasNEW=$_POST['gCategoriasNEW']; else $gCategoriasNEW=$_GET['gCategoriasNEW'];
	//--------------------------------------
	$old="\$gCategorias=".$gCategorias.";";
	$new="\$gCategorias=".$gCategoriasNEW.";";
	config($old,$new,'data/');
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>parent.location.href="index.php?m=admin&s=html&t=categories";</script>';
}

if($paso=='cambiarTipoTop') {
	//Adaptacion de variables para PHP v4.2+
	if(isset($_POST['gTipoTopNEW'])) $gTipoTopNEW=$_POST['gTipoTopNEW']; else $gTipoTopNEW=$_GET['gTipoTopNEW'];
	//--------------------------------------
	$old="\$gTipoTop=".$gTipoTop.";";
	$new="\$gTipoTop=".$gTipoTopNEW.";";
	config($old,$new,'data/');
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>document.location.href="index.php?m=admin&s=html&t=categories";</script>';
}

?>