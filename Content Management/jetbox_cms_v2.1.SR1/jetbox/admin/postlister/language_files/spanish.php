<?php

// Postlister spanish file

// Alfredo Rahn (arahn@yahoo.com)

// 03/06/2000 - 21:58:10



$s1 = "Ayuda";

$s2 = "Nuevo";

$s3 = "Añadir/borrar subscriptores";

$s4 = "Editar una lista";

$s5 = "Crear/borrar listas";

$s6 = "Ahora crearemos la tabla principal del Postlister. Esto solo debe ser hecho una vez. Has escogido llamar a la tabla <i>$mainTable</i>. Si quieres cambiar este nombre, deberás abrir el archivo <i>settings.php</i> y cambiar la variable <i>\$mainTable</i>. De otro modo, solo debes pulsar el botón abajo para crear la tabla.";

$s7 = "Crear la table";

$s8 = "Ha ocurrido un error";

$s9 = "Atrás";

$s10 = "El nombre de la tabla es inválido. Solo puede contener letras y números -- no se permiten espacios ni caracteres especiales.";

$s11 = "La tabla principal del Postlister (<i>$mainTable</i>) ha sido creada. Ahora puedes comenzar a <a href=lists.php>crear listas de correo</a>.";

$s12 = "Selecciona una lista:";

$s13 = "OK";

$s14 = "No hay listas disponibles.";

$s15 = "Crear la lista";

$s16 = "Crear una lista";

$s17 = "Nombre de la lista:";

$s18 = "Escoge un nombre para la nueva lista. El nombre no puede ser mayor de 20 caracteres, y no puede contener espacios u otros caracteres especiales -- solo las letras a-z y los números.";

$s19 = "Borrar una lista";

$s20 = "¿Cual lista desea borrar?";

$s21 = "Borrar";

$s22 = "La lista <i>$listeOpret</i> ha sido creada. Ahora puedes <a href=edit.php?liste=$listeOpret>editar la lista</a>.";

$s23 = "¿Estas seguro que deseas borrar la lista llamada <i>$listeSlet</i>? Si lo haces, perderás todas las direcciones de correo subscritas a ella.";

$s24 = "Cancelar";

$s25 = "Borrar la lista";

$s26 = "La lista <i>$listeSletBekraeft</i> ha sido borrada.";

$s27 = "Dirección de envío, p.e. <i>Tu nombre &lt;tu.nombre@$SERVER_NAME&gt;</i>:";

$s28 = "La firma a ser insertada al final de los correos que son enviados a la lista:";

$s29 = "El mensaje de subscripción -- el mensaje que será enviado a aquellos que deseen subscribirse a la lista.";

$s30 = "Salvar los cambios";

$s31 = "El mensaje de subscripción <b>debe</b> contener la palabra <i>[SUBSCRIBE_URL]</i>.";



# The following variable will go into an email body. Therfore, you need to break all lines after 72 characters.

$s32 = "Has recibido este correo porque tu u otra persona

te ha inscrito en la lista $listeOpret en

http://$HTTP_HOST.

Antes de que podamos aceptar tu dirección email en nuestra lista,

necesitamos estar seguros de que tu, el dueño de esa dirección email,

efectivamente deseas ser agregado a la lista. Por tanto, te pedimos

que visites el siguiente URL si quieres confirmar tu solicitud de

ingreso:



<[SUBSCRIBE_URL]>



Gracias.";

$s33 = "Los cambios a la lista <i>$liste</i> han sido salvados.";

$s34 = "Añadir dirección email";

$s35 = "Eliminar dirección email";

$s36 = "Añadir";

$s37 = "Ingrese la nueva dirección email a ser añadida a la lista -- p.e. <i>pablo@ejemplo.com</i>:";

$s38 = "<i>$epostadresseTilfoej</i> no es una dirección email válida.";

$s39 = "La dirección <i>$epostadresseTilfoej</i> ha sido añadida a la lista <i>$liste</i>.";

$s40 = "Aparentemente la dirección <i>$epostadresseTilfoej</i> ya existe en la lista.";

$s41 = "Mostrar";

$s42 = "todos los subscriptores";

$s43 = "aprobados";

$s44 = "no aprobados";

$s45 = "comenzando por";

$s46 = "conteniendo";

$s47 = "No hay resultado.";

$s48 = "aprobados";

$s49 = "no aprobados";

$s50 = "La dirección <i>$sletDenne</i> ha sido eliminada de la lista <i>$liste</i>.";

$s51 = "Escribe un mensaje para la lista <i>$liste</i>";

$s52 = "De:";

$s53 = "Tópico:";

$s54 = "Cuerpo:";

$s55 = "Retorno de carro a los 72 caractéres";

$s56 = "Previsualizar";

$s57 = "Imprimir";

$s58 = "Conteo de palabras";

$s59 = "Funciones";

$s60 = "Número de caracteres:";

$s61 = "Número de palabras:";

$s62 = "Necesitas el username y el password correctos para tener acceso a esta página.";

$s63 = "Puedes usar las siguientes variables en el cuerpo del mensaje:";

$s64 = "La dirección email del destinatario.";

$s65 = "El URL de retiro -- el URl que el subscriptor debe visitar para retirarse de la lista.";

$s66 = "Para:";

$s67 = "Enviar";

$s68 = "Atrás -- Quiero editar el correo";

$s69 = "Listas de Correo";

$s70 = "Subscríbete a la(s) lista(s):";

$s71 = "Tu dirección e-mail:";

$s72 = "Escoge una lista:";

$s73 = "Subscribirse";

$s74 = "Retirarse";

$s75 = "<i>$email</i> no es una dirección email válida.";

$s76 = "No especificaste si deseabas subscribirte o retirarte de la lista de correo. El problema puede haber ocurrido a consecuencia de un error en la forma que enviaste. Por favor, contacta al administrador de este sitio.";

$s77 = "Subscripción a la lista $list";

$s78 = "Retiro de la lista $list";

$s79 = "Gracias por subscribirse a la lista <i>$list</i>. Antes de añadirte a la lista necesitamos que confirmes tu solicitud de ingreso. Dentro de pocos minutos recibirás un email con un URL el cual debes visitar para confirmar tu solicitud de subscripción.";

$s80 = "A modo de retirarse de la lista <i>$list</i> necesitamos que confirmes tu solicitud de retiro. Dentro de pocos minutos recibirás un email con un URL el cual debes visitar para confirmar tu solicitud de retiro.";



# The following variable will go into an email body. Therfore, you need to break all lines after 72 characters.

$s81 = "Has recibido este correo porque tu u otra persona

te ha retirado de la lista $listeOpret en

http://$HTTP_HOST.

Antes de que podamos retirar tu dirección email de nuestra lista,

necesitamos estar seguros de que tu, el dueño de esa dirección email,

efectivamente deseas ser eliminado de la lista. Por tanto, te pedimos

que visites el siguiente URL si quieres confirmar tu solicitud de

retiro:



<[UNSUBSCRIBE_URL]>



Gracias.";



$s82 = "El mensaje de retiro <b>debe</b> contener la palabra <i>[UNSUBSCRIBE_URL]</i>.";

$s83 = "El mensaje de retiro -- el mensaje que se envia a aquellos que desean retirarse de la lista.";

$s84 = "Aparentemente, la dirección <i>$email</i> ya existe en la lista.";

$s85 = "Listo! El correo ha sido enviado a todas las direcciones de la lista.";

$s86 = "Postlister está enviando el email número";

$s87 = "hasta";

$s88 = "No cierres esta ventana! No toques nada mientras el programa está despachando los mensajes restantes.";

$s89 = "La dirección <i>$email</i> no está en la lista. Por tanto, no puedes retirarla.";

$s90 = "No ha sido especificada la dirección email.";

$s91 = "No especificaste si querias subscribirte o retirarte de la lista.";

$s92 = "No especificaste el ID de la dirección email.";

$s93 = "No especificaste la lista.";

$s94 = "No especificaste el ID correcto para la dirección email <i>$epost</i>.";

$s95 = "Listo! Has sido ahora añadido a la lista <i>$liste</i>.";

$s96 = "Has sido retirado de la lista <i>$liste</i>, y no recibirás más correos de la misma.";

$s97 = "de";

$s98 = "Importar direcciones email";

$s99 = "Abrir e importar";

$s100 = "El archivo <i>$importfil</i> no fue encontrado.";

$s101 = "Listo! Todas las direcciones email en el archivo <i>$importfil</i> han sido importados en la lista <i>$liste</i>.";

$s102 = "Si tienes un archivo contentivo de cierta cantidad de direcciones email, puedes importar las direcciones en la lista <i>$liste</i>. Empero, es importante que el archivo solo contenga una dirección email por línea, y que no contenga nada sino direcciones email. En otras palabras, el formato del archivo debe ser algo como:<p><i>pepe@trueno.com<br>Pepe Trueno &lt;pedro@ejemplo.com&gt;<br>php@php.net</i>";

$s103 = "Archivo:";

$s104 = "Volver a la Página Principal";

$s105 = "Importar/exportar";

$s106 = "Exportar direcciones email";

$s107 = "Exportar";

$s108 = "Utilizando esta función puedes exportar las direcciones email en la lista <i>$liste</i>. Esto es, todas las direcciones email serán escritas en un archivo - una dirección por línea. El nombre del archivo será <i>postlister-$liste.txt</i>, y será coloado en el directorio especificado abajo. <b>Es muy impotante que el directorio en el cual el archivo será colocado tenga los permisos apropiados. Esto significa que deberás hacer chmod 777 en el directorio utilizando un cliente FTP o SSH/telnet.</b>";

$s109 = "El directorio en el cual deseas colocar el archivo:";

$s110 = "<i>$eksport</i> no es un directorio. Debes especificar el directorio en el cual quieres colocar el archivo con las direcciones email.";

$s111 = "Listo! Todas las direcciones email en la lista <i>$liste</i> han sido escritas en el archivo <i>$eksport/postliste-$liste.txt</i>.";

?>

