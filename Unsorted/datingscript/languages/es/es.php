<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               es.php                           #
# File purpose            Spanish language file            #
# File created by         Felix Bueno <fxmo@msn.com>       #
############################################################

define('C_HTML_DIR','ltr'); // HTML direction for this language
define('C_CHARSET', 'iso-8859-1'); // HTML charset for this language

### !!!!! Please read it: RULES for translate!!!!! ###
### 1. Be carefull in translate - don`t use ' { } characters
###    You can use them html-equivalent - &#39; &#123; &#125;
### 2. Don`t translate {some_number} templates - you can only replace it - 
###    {0},{1}... - is not number - it templates
###################################

$w=array(
'<font color=red size=3>*</font>', //0 - Symbol for requirement field
'Security error - #', //1
'Este correo ya est0 en nuestra base de datos. Po favor use otro!', //2
'Su nombre no es valido. Su nombre debe ser {0} - {1} caracteres', //3 - Don`t change {0} and {1} - See rule 2 !!!
'Su apellido no es valido. Su nombre debe ser {0} - {1} caracteres', //4
'Cumpleaños incorrecto', //5
'Clave no valida. La clave debe ser {0} - {1} caracteres', //6
'Sexo', //7
'Elija el sexo que prefiere', //8
'Tipo de relación', //9
'Elija su país', //10
'Correo no valido o inocompleto', //11
'Url no valido', //12
'Número de ICQ incorrecto', //13
'Número incorrecto de AIM', //14
'Teléfono', //15
'Ciudad', //16
'Estado civil', //17
'Sobre sus hijos', //18
'Estatura', //19
'Peso', //20
'Estatura que busca', //21
'Peso que busca', //22
'Color de su pelo', //23
'Color de sus ojos', //24
'Raza', //25
'Religión', //26
'Idioma', //27
'Religion que desea en la persona que busca', //28
'Sobre fumar', //29
'Sobre bebidas', //30
'Nivel de educación', //31
'Sobre su trabajo', //32
'Edad que busca', //33
'Elija como nos encontró', //34
'Escriba su hobby', //35
'Error en hobby. Hobby no debe ser mayor de {0} letras', //36
'Error en Hobby. El número de letras debe ser menor de {0} letras', //37
'Escriba sobre su persona', //38
'Error en cantidad de letras. Debe tener más de {0} letras', //39
'Error en cantidad de letras. Debe tener menos de {0} letras', //40
'Debe incluir una foto!', //41
'Felicitaciones! <br>Su codigo de activación fue enviado a  su correo. <br>Debe revisar su correo y confirmar su registración!', //42 - Message after register if need confirm by email
'Confirme su registración', //43 - Confirm mail subject
'Gracias por registrarse en nuestra pagina...
Favor de entrar este enlace para confirmar su registración:

', //44 - Confirm message
'Gracias por registrarse. Su perfil seráa aprovado en un momento. Favor de regresar mas tarde...', //45 - Message after registering if admin allowing is needed
'Felicidades! <br>Su perfil ya es parte de la base de datos!<br><br>Su identificación de entrada es:', //46
'<br>Su clave:', //47
'Por favor escriba su clave nuevamente', //48
'La clave no es identica', //49
'Usuario Registrado', //50
'Su nombre', //51
'characteres', //52
'Su apellido', //53
'Clave', //54
'Su clave nuevamente', //55
'Cumpleaños', //56
'Sexo', //57
'Tipo de relación', //58
'País', //59
'Correo', //60
'Webpage', //61
'ICQ', //62
'AIM', //63
'Teléfono', //64
'Ciudad', //65
'Estado civil', //66
'Hijos', //67
'Estatura', //68
'Peso', //69
'Color pelo', //70
'Color ojos', //71
'Raza', //72
'Religión', //73
'Fuma', //74
'Bebe', //75
'Educación', //76
'Ocupación', //77
'Hobby', //78
'Descríbase usted y que tipo de persona busca para su relación.', //79
'Buscando por', //80
'De raza', //81
'De religión', //82
'Edad', //83
'De estatura', //84
'De peso', //85
'¿Como nos encontro?', //86
'Foto', //87
'Inicio', //88
'Regístrese', //89
'Area de Miembros', //90
'Buscar', //91
'Escribanos', //92
'FAQ', //93
'Estadisticas', //94
'Menú de ID de miembro#', //95
'Ver mensajes', //96
'Mi habitación', //97
'Mi perfil', //98
'Cambiar perfil', //99
'Cambiar clave', //100
'Remover perfil', //101
'Salir', //102
'Tiepo de procesamiento:', //103
'seg.', //104
'Usuarios en línea :', //105
'Visitantes en línea:', //106
'Powered by <a href="http://www.azdg.com" target="_blank" class="desc">AzDG</a>', //107 - Don`t change link - only for translate - read GPL!!!
'Solo usuarios registrados tienen derecho a búsquedas avanzada', //108
'LO sentimos, "Edad de" debe ser menor de "hasta Edad"', //109
'No se encontró nada', //110
'No', //111 Picture available?
'Si', //112 Picture available?
'No se conecta con el servidor<br>Su usuario de mysql es incorrecto.<br>Revise el archivo Config ', //113
'No se conecta con el servidor<br>Base de datos no existe<br>O cambie la base de datos en config', //114
'Pagínas :', //115
'Resultados', //116
'Total : ', //117 
'Usuario', //118
'Propósito', //119
'Edad', //120
'País', //121
'Ciudad', //122
'Ultima vista', //123
'Fecha de registro', //124
'Búsqueda avanzada', //125
'Usuario ID#', //126
'Nombre', //127
'Apellido', //128
'Simbolo zodiacal', //129
'Estatura', //130
'Peso', //131
'Sexo', //132
'Tipo de relación', //133
'Estado civil', //134
'Hijos', //135
'Color pelo', //136
'Color ojos', //137
'Raza', //138
'Religión', //139
'Fuma', //140
'Bebe', //141
'Educación', //142
'Buscar usaurios por', //143
'Webpage', //144
'ICQ', //145
'AIM', //146
'Teléfono', //147
'Registrado en ', //148
'Ordene los resultados por', //149
'Resultados por pagína', //150
'Búsqueda simple', //151
'Area sólo para miembros', //152
'Area cerrada para perfiles con errores', //153
'Usuario dentro de la base de datos con perfiles malos', //154
'Gracias, Usuario reportado, será revisado por el administrador', //155
'No permitido en habitaciones', //156
'Usuario ya en su habitación', //157
'Gracias, Usuario fue añadido a su habitación', //158
'Su perfil fue enviado para revisión por parte del adminnistrador!', //159
'Su perfil fue agregado a nuestra base de datos', //160
'Error en activación de perfil. Puede ser que ya usted este agregado', //161
'FAQ está vacia', //162
'Respuesta FAQ#', //163
'Todos los espacios deben ser llenados', //164
'Su mensaje fue enviado exitosamente', //165
'Favor de entrar el asunto', //166
'Favor de entrar su mensaje', //167
'Asunto', //168
'Mensage', //169
'Enviar mensaje', //170
'Para miembros', //171
'Usuario #', //172
'Olvidó la clave', //173
'Recomiendanos', //174
'Amigo-{0} email', //175
'De cumpleaños hoy', //176
'Nadie está de cumpleaños', //177
'Bienvenidos', //178 Welcome message header
'Los personales - es una forma fácil y amena de encontrar parejas y amigos, para entretenimiento, citas y largas relaciones. Citar y relacionarse con gente es divertido y seguro. Como siempre es recomendable tomar precauciones cuando va a conocer a alguien por primera vez.<br><<br>', //179 Welcome message
'Ultimo {0} usaurios registrados', //180
'Búsqueda rápida', //181
'Búsqueda Avanzada', //182
'Foto del día', //183
'Estadisticas', //184
'Su ID debe ser numerica', //185
'Usaurio ID# o clave incorrecta', //186
'Acceso cerrado para enviar correo', //187
'Enviar mensaje a  usuario ID#', //188
'No uhay usuarios en línea ', //189
'Página de rocomendar no disponible', //190
'Saludos de {0}', //191 "Recomiendanos" asunto, {0} - usuario
'Saludos de {0}!

Como estas :)

Visita está página - es buena:
{1}', //192 "Recommend Us" message, {0} - usuario, {1} - site url
'Favor escribe correctamente#{0} email', //193
'Entre su nobre y email', //194
'Su clave de {0}', //195 Reming password email subject
'Esta cuenta no existe o esta desactivada.<br>Favor escribir al administrador. Incluya su ID por favor.', //196
'Hola!

Su nombre ID#:{0}
Su clave:{1}

_________________________
{2}', //197 Remind password email message, Where {0} - ID, {1} - password, {2} - C_SNAME(sitename)
'Su clave fue enviada a su cuenta de correo.', //198
'Favor de entrar su ID', //199
'Enviar clave', //200
'Acceso cerrado para enviar mensajes', //201
'Envia mensaje a usuario ID#', //202
'Aviseme cuanod el usuario lea el mensaje', //203
'No usuario en base de datos', //204
'Estadisticas no disponible', //205
'Ese ID de usuario no existe', //206
'Perfil ID#', //207
'Nombre de usuario', //208
'Apellido de usuario', //209
'Cumpleaño', //210
'Correo', //211
'Mensaje del programa', //212 - Subject for email
'Ocupación', //213
'Hobby', //214
'Sobre', //215
'Popularidad', //216
'Enviar correo', //217
'Pefil incorrecto', //218
'Agregar a mi habitación', //219
'O su archivo no subio, <br>o el archivo que suted trató de subir es mayor de {0} Kb permitido. Su archivo tiene {1} Kb', //220
'El tamaño en pixeles de su archivo es más grande {0} px o el tamaño de largo es mas grande de {1} px permitido.', //221
'El tipo de archivo que quiere subir es incorrecto (solo jpg, gif y png es permitido). Su tipo es  - ', //222
'(Max. {0} Kb)', //223
'Estadisticas por paises', //224
'No tiene mensajes', //225
'Total de mensajes - ', //226
'Num', //227 Number
'De', //228
'Fecha', //229
'Borrar', //230 Delete
'<sup>Nuevo</sup>', //231 New messages
'Borrar mensjaes marcados', //232
'Mensaje de - ', //233
'Responder', //234
'Hola, Usted escribió {0}:\n\n_________________\n{1}\n\n_________________', //235 Responder a mensaje {0} - fecha, {1} - mensaje
'Su mensaje fue leido', //236
'Su mensaje:<br><br><span class=dat>{0}</span><br><br>fue leido por {1} [ID#{2}] en {3}', //237 {0} - mensaje, {1} - Usuario, {2} - Usuario ID, {3} - Fecha y hora
'{0} mensajes borrados!', //238
'Enttre clave vieja', //239
'Entre clave nueva', //240
'Clave nuevamente', //241
'Cambie clave', //242
'Clave vieja', //243
'Nueva clave', //244
'Entre clave nueva', //245
'No tiene ningun usuario en su habitación', //246
'Fecha agregado', //247
'Borre usuarios marcados', //248
'¿Estas seguro que quieres borrar su propio perfil?<br>Todos sus mensajes, fotos seran borrados de la base de datos.', //249
'Usuario con ID#={0} fue borrado de la base de datos', //250
'Su perfil será borrado cuanod el administrador revise', //251
'{0} usuarios borrados!', //252
'clave no es identica o contiene errores', //253
'No tiene acceso a borrar clave', //254
'Clave vieja es incorrecta. Favor de regresar y arreglar!', //255
'Su clave fue cambiada exitosamente!', //256
'No puede borrar todas las fotos', //257
'Su perfil fue cambiado correctamente', //258
' - Borrar foto', //259
'Su sección fue destruida. Puede cerrar su browser', //260
'Las banderas no estan disponibles', //261
'Idiomas', //262
'Entrar', //263
'Entrar [3-16 dígitos [A-Za-z0-9_]]', //264
'Entrar', //265
'El usuario debe tener 3-16 caracteres y solo A-Za-z0-9_ es disponible', //266
'Ese usuario ya existe . Favor seleccione otro!', //267
'Total de usuarios - {0}', //268
'The messages are not visible. You should be the privileged user see the messages.<br><br>You can purshase from <a href="'.C_URL.'/members/index.php?l=es&a=r" class=head>here</a>', //269 change l=default to l=this_language_name
'User type', //270
'Purshase date', //271
'Search results position', //272
'Price', //273
'month', //274
'Purshase Last date', //275
'Higher than', //276
'Purshase', //277
'Purshase with', //278
'PayPal', //279
'Thanks for your registration. Payment has been succesfully send and will be checked by admin in short time.', //280
'Incorrect error. Please try again, or contact with admin!', //281
'Send congratulation letter about privilegies activating', //282
'User type has successfully changed.', //283
'Email with congratulations has been send to user.', //284
'ZIP',// 285 Zip code
'Congratulations, 

Your status is changed to {0}. This privilegies will be available in next {1} month.

Now you can check your messages in your box.

__________________________________
{2}', //286 {0} - Ex:Gold member, {1} - month number, {2} - Sitename from config
'Congratulations', //287 Subject
'ZIP code must be numeric', //288
'Keywords', //289
'We are sorry, but the following error occurred:', //290
'', //291
'', //292
'', //293
'', //294
'', //295
'', //296
'', //297
'', //298
'' //299
); 
?>

