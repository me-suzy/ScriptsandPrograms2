<?php
/****************************************************************************
* data/plantillas/login.inc.php
*
* plantilla para la visualización de la pantalla de login
*

PHPfileNavigator versión 2.1.0

Copyright (C) 2004-2005 Lito <lito@eordes.com>

http://phpfilenavigator.litoweb.net/

Este programa es software libre. Puede redistribuirlo y/o modificarlo bajo los
términos de la Licencia Pública General de GNU según es publicada por la Free
Software Foundation, bien de la versión 2 de dicha Licencia o bien (según su
elección) de cualquier versión posterior. 

Este programa se distribuye con la esperanza de que sea útil, pero SIN NINGUNA
GARANTÍA, incluso sin la garantía MERCANTIL implícita o sin garantizar la
CONVENIENCIA PARA UN PROPÓSITO PARTICULAR. Véase la Licencia Pública General de
GNU para más detalles. 

Debería haber recibido una copia de la Licencia Pública General junto con este
programa. Si no ha sido así, escriba a la Free Software Foundation, Inc., en
675 Mass Ave, Cambridge, MA 02139, EEUU. 
*******************************************************************************/

defined('OK') or die();
?>

<h1 id="benvido"><?php echo $conf->t('benvido'); ?></h1>
<?php if ($vars->get('erro')){ ?>
<div class="aviso" style="width: 230px; text-align: center; margin-left: 250px;"><?php echo $conf->t('alerta_login'); ?></div>
<?php }; ?>
<div id="login">
	<form action="comprobar.php" method="post" id="formulario">
	<fieldset>
	<p><label for="login_usuario"><?php echo $conf->t('usuario'); ?>:</label>
	<br /><input type="text" id="login_usuario" name="login_usuario" class="formulario" /></p>

	<script type="text/javascript"><!--

	colocate = MM_findObj('login_usuario');
	colocate.focus();

	//--></script>

	<p><label for="login_contrasinal"><?php echo $conf->t('contrasinal'); ?>:</label>
	<br /><input type="password" id="login_contrasinal" name="login_contrasinal" class="formulario" /></p>
	<p><input type="submit" name="Submit" value=" <?php echo $conf->t('enviar'); ?> " class="boton" /></p>
	</fieldset>
	</form>
</div>
<br />
<p style="text-align: center"><a href="http://validator.w3.org/check?uri=referer" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;"><img src="http://www.w3.org/Icons/valid-xhtml11" alt="Valid XHTML 1.1" height="31" width="88" /></a></p>
