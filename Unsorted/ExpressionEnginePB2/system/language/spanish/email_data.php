<?php


//---------------------------------------------------
//	Admin Notification of New Member Registrations
//--------------------------------------------------

function admin_notify_reg_title()
{
return <<<EOF
Notificacion de registro de nuevo miembro
EOF;
}

function admin_notify_reg()
{
return <<<EOF
La siguiente persona ha enviado una solicitud registro para ser miembro {name}

At: {site_name}

Tu panel de control: {control_panel_url}
EOF;
}
// END


//---------------------------------------------------
//	Admin Notification of New Comment
//--------------------------------------------------

function admin_notify_comment_title()
{
return <<<EOF
Has recibido un comentario
EOF;
}

function admin_notify_comment()
{
return <<<EOF
Has recibido un comentario del siguiente weblog:
{weblog_name}

El titulo del articulo es:
{entry_title}

Located at: 
{comment_url}
EOF;
}
// END


//---------------------------------------------------
//	Admin Notification of New Trackback
//--------------------------------------------------

function admin_notify_trackback_title()
{
return <<<EOF
Has recibido un trackback
EOF;
}

function admin_notify_trackback()
{
return <<<EOF
Has recibido un trackback para el siguiente articulo:
{entry_title}

Ubicado en: 
{comment_url}

El trackback re ha recibido desde el weblog siguiente:
{sending_weblog_name}

Titulo del articulo:
{sending_entry_title}

Weblog URL:
{sending_weblog_url}
EOF;
}
// END


//---------------------------------------------------
//	Membership Activation Instructions
//--------------------------------------------------

function mbr_activation_instructions_title()
{
return <<<EOF
En este mensaje esta tu codigo de activacion
EOF;
}

function mbr_activation_instructions()
{
return <<<EOF
Gracias por registrarte.

Para activar tu cuenta, visita el enlace siguiente:

{activation_url}

Gracias!

{site_name}

{site_url}
EOF;
}
// END


//---------------------------------------------------
//	Member Forgotten Password Instructions
//--------------------------------------------------

function forgot_password_instructions_title()
{
return <<<EOF
Informacion de acceso
EOF;
}

function forgot_password_instructions()
{
return <<<EOF
{name},

Para reiniciar tu password, visita:

{reset_url}

Tu password actual se substituira por otra que te enviamos por email.

Si no quiers cambiar tu passsword, ignora este mensaje. IExpira en 24 horas.

{site_name}
{site_url}
EOF;
}
// END



//---------------------------------------------------
//	Reset Password Notification
//--------------------------------------------------

function reset_password_notification_title()
{
return <<<EOF
Nueva informacion de acceso
EOF;
}

function reset_password_notification()
{
return <<<EOF
{name},

Aqui esta tu neuva informacion de acceso:

Usuario: {username}
Password: {password}

{site_name}
{site_url}
EOF;
}
// END



//---------------------------------------------------
//	Validated Member Notification
//--------------------------------------------------

function validated_member_notify_title()
{
return <<<EOF
Tu cuenta ya esta activada
EOF;
}

function validated_member_notify()
{
return <<<EOF
{name},

Tu cuenta ya esta activada y list apara usar.

Gracias!

{site_name}
{site_url}
EOF;
}
// END




//---------------------------------------------------
//	Mailinglist Activation Instructions
//--------------------------------------------------

function mailinglist_activation_instructions_title()
{
return <<<EOF
Confirmacion
EOF;
}

function mailinglist_activation_instructions()
{
return <<<EOF
Gracias por apuntarte a la lista de correo

Porfavor, click en este enlace para confirmar tusubscripcion.

Si no deseas pertenecer a la lista, ignora este mensaje.

{activation_url}

Thank You!Gracia!
{site_name}
EOF;
}
// END



//---------------------------------------------------
//	Comment Notification
//--------------------------------------------------

function comment_notification_title()
{
return <<<EOF
Alguien ha respondido a tu comentariont
EOF;
}

function comment_notification()
{
return <<<EOF
Alguien a respondido a tu articulo en:
{weblog_name}

El titulo de articulo es:
{entry_title}

Puedes ver el comentario en este enlace:
{comment_url}

Pra dejar de recibir notificaciones, click aqui:
{notification_removal_url}
EOF;
}
// END


?>