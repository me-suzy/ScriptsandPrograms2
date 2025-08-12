<?php  // pt.inc.php, portugues version, Translation by Paulo Pereira, Brasilian portuguese by Henrique Leandro
// Corrected Translation for Portugal Portuguese by Lopo Lencastre de Almeida
// Completed translation (except Designer Module) and several corrections by Seido Nakanishi

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$name_month = array("", "Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez");
$l_text31a = array("padrão", "15 min.", "30 min.", " 1 horas", " 2 horas", " 4 horas", " 1 dia");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado");
$name_day2 = array("Seg", "Ter", "Qua", "Qui", "Sex", "Sab","Dom");

$_lang['No Entries Found']= "No Entries Found";
$_lang['No Todays Events']= "HNo Todays Events";
$_lang['No new forum postings']= "No new forum postings";
$_lang['in category']= "in category";
$_lang['Filtered']= "Filtered";
$_lang['Sorted by']= "Sorted by";
$_lang['go'] = "submeter";
$_lang['back'] = "voltar";
$_lang['print'] = "imprimir";
$_lang['export'] = "exportar";
$_lang['| (help)'] = "| (ajuda)";
$_lang['Are you sure?'] = "Você tem certeza?";
$_lang['items/page'] = "itens/página";
$_lang['records'] = "registros"; // elements
$_lang['previous page'] = "Próxima Página";
$_lang['next page'] = "Página anterior";
$_lang['first page'] = "first page";
$_lang['last page'] = "last page";
$_lang['Move']  = "Mover";
$_lang['Copy'] = "Copiar";
$_lang['Delete'] = "Apagar";
$_lang['Save'] = "save";
$_lang['Directory'] = "Diretório";
$_lang['Also Delete Contents'] = "Apaga também o conteúdo";
$_lang['Sum'] = "Soma";
$_lang['Filter'] = "Filtro";
$_lang['Please fill in the following field'] = "Preencha o campo seguinte";
$_lang['approve'] = "aprovar";
$_lang['undo'] = "desfazer";// changement pas fait
$_lang['Please select!']="Selecionar, por favor!";// changement por favor selecione  
$_lang['New'] = "Novo";// changement  novo
$_lang['Select all'] = "Selecionar tudo";// changement
$_lang['Printable view'] = "visualizar impressão";// changement
$_lang['New record in module '] = "Novo registro no módulo";// changement
$_lang['Notify all group members'] = "Notificar todos os membros do grupo";// changement
$_lang['Yes'] = "sim";// changement
$_lang['No'] = "não";// changemen
$_lang['Close window'] = "Fechar Janela";
$_lang['No Value'] = "Sem valor";
$_lang['Standard'] = "Padrào";
$_lang['Create'] = "Criar";
$_lang['Modify'] = "Modificar";   
$_lang['today'] = "hoje";

// admin.php
$_lang['Password'] = "Senha";
$_lang['Login'] = "acesso";
$_lang['Administration section'] = "Administração";
$_lang['Your password'] = "Sua senha";
$_lang['Sorry you are not allowed to enter. '] = "Não está autorizado a entrar. ";
$_lang['Help'] = "Ajuda";
$_lang['User management'] = "Administração do membro";
$_lang['Create'] = "criar";
$_lang['Projects'] = "Projectos";
$_lang['Resources'] = "Recursos";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "Favoritos";
$_lang['for invalid links'] = "Verificar a validade das ligações";
$_lang['Check'] = "Verificar";
$_lang['delete Bookmark'] = "eliminar favorito";
$_lang['(multiple select with the Ctrl-key)'] = "(selecção múltipla com a tecla 'Ctrl')";
$_lang['Forum'] = "Forum";
$_lang['Threads older than'] = "temas com mais de";
$_lang[' days '] = " dias ";
$_lang['Chat'] = "<i>Chat</i>";
$_lang['save script of current Chat'] = "Salvar texto do corrente <i>Chat</i>";
$_lang['Chat script'] = "<i>Chat</i> Atual";
$_lang['New password'] = "Nova Senha";
$_lang['(keep old password: leave empty)'] = "(Senha Antiga: Deixe vazio)";
$_lang['Default Group<br> (must be selected below as well)'] = "Grupo padrão<br> (Precisa ser selecionado acima)";
$_lang['Access rights'] = "Tipo de Acesso";
$_lang['Zip code'] = "Código Postal";
$_lang['Language'] = "Idioma";
$_lang['schedule readable to others'] = "Agenda visível a outros utilizadores";
$_lang['schedule invisible to others'] = "Agenda invisível a outros utilizadores";
$_lang['schedule visible but not readable'] = "schedule visible but not readable";
$_lang['these fields have to be filled in.'] = "tem que preencher estas caixas.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "Tem de preencher as seguintes caixas: sobrenome,apelido e senha.";
$_lang['This family name already exists! '] = "Já existe este sobrenome! ";
$_lang['This short name already exists!'] = "Já existe este apelido!";
$_lang['This login name already exists! Please chosse another one.'] = "Este nome de login já existe! Por favor, escolha outro nome.";
$_lang['This password already exists!'] = "Já existe esta senha!";
$_lang['This combination first name/family name already exists.'] = "Esta combinacão nome/sobrenome, já existe !.";
$_lang['the user is now in the list.'] = "o membro foi adicionado.";
$_lang['the data set is now modified.'] = "a informação foi modificada.";
$_lang['Please choose a user'] = "Escolha um membro";
$_lang['is still listed in some projects. Please remove it.'] = "continua listado em vários projetos. Por favor elimine.";
$_lang['All profiles are deleted'] = "Foram eliminados todos os perfis";
$_lang['A Profile with the same name already exists'] = "A profile with the same name already exists";
$_lang['is taken out of all user profiles'] = "foi retirado de todos os perfis";
$_lang['All todo lists of the user are deleted'] = "Foram eliminados todos os itens a fazer do membro";
$_lang['is taken out of these votes where he/she has not yet participated'] = "foi retirado dessa votação onde ele/ela ainda não participaram";
$_lang['All events are deleted'] = "Foram eliminados todos os eventos";
$_lang['user file deleted'] = "Arquivo do membro eliminado";
$_lang['bank account deleted'] = "conta bancária eliminada";
$_lang['finished'] = "terminado";
$_lang['Please choose a project'] = "Escolha um projeto";
$_lang['The project is deleted'] = "O projeto foi eliminado";
$_lang['All links in events to this project are deleted'] = "Todos os eventos e links foram eliminados";
$_lang['The duration of the project is incorrect.'] = "Está errada a duração do projecto.";
$_lang['The project is now in the list'] = "O projeto está agora listado";
$_lang['The project has been modified'] = "O projeto foi alterado com sucesso";
$_lang['Please choose a resource'] = "Escolha um recurso";
$_lang['The resource is deleted'] = "Recurso eliminado";
$_lang['All links in events to this resource are deleted'] = "Todos os links e eventos deste recurso foram eliminados";
$_lang[' The resource is now in the list.'] = " O recurso está agora listado.";
$_lang[' The resource has been modified.'] = " Recurso alterado com sucesso.";
$_lang['The server sent an error message.'] = "O servidor enviou a seguinte mensagem de erro";
$_lang['All Links are valid.'] = "Todos os links são válidos.";
$_lang['Please select at least one bookmark'] = "Selecione no minimo um link";
$_lang['The bookmark is deleted'] = "Favorito eliminado";
$_lang['threads older than x days are deleted.'] = "Todos os temas com mais de x dias foram elimindaos.";
$_lang['All chat scripts are removed'] = "Todos os chats atuais foram eliminados";
$_lang['or'] = "ou";
$_lang['Timecard management'] = "Ponto eletrônico";
$_lang['View'] = "Ver";
$_lang['Choose group'] = "Escolher grupo";
$_lang['Group name'] = "Nome do Grupo";
$_lang['Short form'] = "Formulário pequeno";
$_lang['Category'] = "Categoria";
$_lang['Remark'] = "Anotações";
$_lang['Group management'] = "Gerenciamento do Grupo";
$_lang['Please insert a name'] = "Por favor insira um nome";
$_lang['Name or short form already exists'] = "O nome ou formulário pequeno já existe";
$_lang['Automatic assign to group:'] = "Assinala automaticamente para o grupo:";
$_lang['Automatic assign to user:'] = "Assinala automaticamente para o usuário:";
$_lang['Help Desk Category Management'] = "Helpdesk gerenciamento";
$_lang['Category deleted'] = "Categoria apagada";
$_lang['The category has been created'] = "A categoria foi criada";
$_lang['The category has been modified'] = "A categoria foi modificada";
$_lang['Member of following groups'] = "Membro dos seguintes grupos";
$_lang['Primary group is not in group list'] = "O Grupo padrao não está na lista";
$_lang['Login name'] = "Nome de Acesso";
$_lang['You cannot delete the default group'] = "Voce não pode apagar o grupo padrão";
$_lang['Delete group and merge contents with group'] = "Apaga o grupo e mescla o conteúdo com o grupo";
$_lang['Please choose an element'] = "Por favor escolha um elemento";
$_lang['Group created'] = "Grupo criado";
$_lang['File management'] = "Gerenciamento de Arquivo";
$_lang['Orphan files'] = "Arquivos Órfãos";
$_lang['Deletion of super admin root not possible'] = "Deleção do super admin root não é possível";
$_lang['ldap name'] = "nome ldap";
$_lang['mobile // mobile phone'] = "móvel"; // mobil phone
$_lang['Normal user'] = "Usuário normal";
$_lang['User w/Chief Rights'] = "Coordenador de Projetos";
$_lang['Administrator'] = "Administrador";
$_lang['Logging'] = "Logando";
$_lang['Logout'] = "Sair";
$_lang['posting (and all comments) with an ID'] = "postando (e todos comentários) com um ID";
$_lang['Role deleted, assignment to users for this role removed'] = "Papel deletado, designacão para usuários deste papel foi removido";
$_lang['The role has been created'] = "O papel foi criado";
$_lang['The role has been modified'] = "O papel foi modificado";
$_lang['Access rights'] = "Access rights";
$_lang['Usergroup'] = "Gruppe";
$_lang['logged in as'] = "Angemeldet als";

//chat.php
$_lang['Quit chat']= "Quit chat";

//contacts.php
$_lang['Contact Manager'] = "Gerenciador de endereços:";
$_lang['New contact'] = "Novo contato";
$_lang['Group members'] = "Membros do grupo";
$_lang['External contacts'] = "Contatos externos";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;Novo&nbsp;";
$_lang['Import'] = "Importar";
$_lang['The new contact has been added'] = "O novo contato foi adicionado";
$_lang['The date of the contact was modified'] = "A data do contato foi modificada";
$_lang['The contact has been deleted'] = "O contato foi apagado";
$_lang['Open to all'] = "Abrir para todos";
$_lang['Picture'] = "Figura";
$_lang['Please select a vcard (*.vcf)'] = "Por favor, selecione um vcard (*.vcf)";
$_lang['create vcard'] = "Cria um Vcard";
$_lang['import address book'] = "Importar catálogo de endereços";
$_lang['Please select a file (*.csv)'] = "Por favor selecione um arquivo (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "Dica: Abra seu catalogo de endereços do Outlook Express e selecione 'arquivo'/'exportar'/'outros endereços'<br>
Nomeie o arquivo, selecione todos os campos na próxima tela e 'fim'";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "Abra o Outlook em  'arquivo/importar/exportar no arquivo',<br>
escolha 'virgula separando valores (Win)', então selecione 'contatos' no próximo formulário,<br>
dê o nome de exportação e arquivo e acabou !.";
$_lang['Please choose an export file (*.csv)'] = "Por favor escolha um arquivo para exportar (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Por favor exporte o seu livro de endereço em arquivo separado com vírgulas (.csv),<br>
e modifique as colunas da tabela com planilha de cálculo para este formato<br>
título, nome, sobrenome, empresa, e-mail, e-mail 2, tel 1, tel 2, fax, móvel, endereço, cep, cidade, país, estado, categoria, observação, endereço web.<br>
Delete as colunas no seu arquivo que não estão listados aquí e crie colunas vazias para campos que não existem no seu arquivo";
$_lang['Please insert at least the family name'] = "Por favor insira seu sobrenome";
$_lang['Record import failed because of wrong field count'] = "A importação dos Registros falhou devido a existencia de um campo errado";
$_lang['Import to approve'] = "Importar para aprovar";
$_lang['Import list'] = "Lista de importação";
$_lang['The list has been imported.'] = "A lista foi importada.";
$_lang['The list has been rejected.'] = "A lista foi rejeitada.";
$_lang['Profiles'] = "Perfis";
$_lang['Parent object'] = "Objeto Pai";
$_lang['Check for duplicates during import'] = "Verificar a duplicação durante importação";
$_lang['Fields to match'] = "Campos a combinar";
$_lang['Action for duplicates'] = "Ação para duplicados";
$_lang['Discard duplicates'] = "Descartar duplicados";
$_lang['Dispose as child'] = "Apagar como filho";
$_lang['Store as profile'] = "Armazenar como perfil";    
$_lang['Apply import pattern'] = "Aplicar padrão de importação";
$_lang['Import pattern'] = "Padrão de importação";
$_lang['For modification or creation<br>upload an example csv file'] = "Upload arquivo importado (csv)"; 
$_lang['Skip field'] = "Pular campo";
$_lang['Field separator'] = "Separador de campo";
$_lang['Contact selector'] = "Seletor de Contao";
$_lang['Use doublet'] = "Use doublet";
$_lang['Doublets'] = "Doublets";

// filemanager.php
$_lang['Please select a file'] = "Selecione um Arquivo";
$_lang['A file with this name already exists!'] = "Já existe um Arquivo com este nome!";
$_lang['Name'] = "Nome";
$_lang['Comment'] = "Comentário";
$_lang['Date'] = "Data";
$_lang['Upload'] = "Enviar ";
$_lang['Filename and path'] = "Nome do arquivo e localização";
$_lang['Delete file'] = "Apaga arquivo";
$_lang['Overwrite'] = "Sobrescreve";
$_lang['Access'] = "Acesso";
$_lang['Me'] = "eu";
$_lang['Group'] = "grupo";
$_lang['Some'] = "alguns";
$_lang['As parent object'] = "mesmo que diretório";
$_lang['All groups'] = "todos";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "voce não está autorizado a sobrescrever este arquivo quando alguém estiver enviando";
$_lang['personal'] = "pessoal";
$_lang['Link'] = "Link";
$_lang['name and network path'] = "Adicione o caminho";
$_lang['with new values'] = "Com novos valores";
$_lang['All files in this directory will be removed! Continue?'] = "Todos os arquivos neste diretório serão removidos ! continua ?";
$_lang['This name already exists'] = "Este nome já existe";
$_lang['Max. file size'] = "Tamanho Max. do arquivo";
$_lang['links to'] = "link para";
$_lang['objects'] = "objetos";
$_lang['Action in same directory not possible'] = "Action in same directory not possible";
$_lang['Upload = replace file'] = "Upload = regravar arquivo";
$_lang['Insert password for crypted file'] = "Insira senha para arquivo encriptado";
$_lang['Crypt upload file with password'] = "Encriptar arquivo com senha";
$_lang['Repeat'] = "Repetir";
$_lang['Passwords dont match!'] = "Senhas não conferem!";
$_lang['Download of the password protected file '] = "Download do arquivo protegido por senha ";
$_lang['notify all users with access'] = "notificar todos os usuários com acesso";
$_lang['Write access'] = "Acesso para Escrita";
$_lang['Version'] = "Versão";
$_lang['Version management'] = "Gerenciamento de Versão";
$_lang['lock'] = "bloquear";
$_lang['unlock'] = "desbloquear";
$_lang['locked by'] = "bloqueado por";
$_lang['Alternative Download'] = "Download Alternativo";
$_lang['Download'] = "Download";
$_lang['Select type'] = "Select type";
$_lang['Create directory'] = "Create directory";
$_lang['filesize (Byte)'] = "Filesize (Byte)";

// filter
$_lang['contains'] = 'contém';
$_lang['exact'] = 'exato';
$_lang['starts with'] = 'inicia com';
$_lang['ends with'] = 'termina com';
$_lang['>'] = '>';
$_lang['>='] = '>=';
$_lang['<'] = '<';
$_lang['<='] = '<=';
$_lang['does not contain'] = 'não contém';
$_lang['Please set (other) filters - too many hits!'] = "Por favor defina (outros) filtros - muitos hits!";

$_lang['Edit filter'] = "Edit filter";
$_lang['Filter configuration'] = "Filter configuration";
$_lang['Disable set filters'] = "Disable set filters";
$_lang['Load filter'] = "Load filter";
$_lang['Delete saved filter'] = "Delete saved filter";
$_lang['Save currently set filters'] = "Save currently set filters";
$_lang['Save as'] = "Save as";
$_lang['News'] = 'Nachrichten';

// form designer
$_lang['Module Designer'] = "Module Designer";
$_lang['Module element'] = "Module element"; 
$_lang['Module'] = "Module";
$_lang['Active'] = "Activ";
$_lang['Inactive'] = "Inactiv";
$_lang['Activate'] = "Aktivate";
$_lang['Deactivate'] = "Deaktivate"; 
$_lang['Create new element'] = "Create new element";
$_lang['Modify element'] = "Modify element";
$_lang['Field name in database'] = "Field name in database";
$_lang['Use only normal characters and numbers, no special characters,spaces etc.'] = "Use only normal characters and numbers, no special characters,spaces etc.";
$_lang['Field name in form'] = "Field name in form";
$_lang['(could be modified later)'] = "(could be modified later)"; 
$_lang['Single Text line'] = "Single Text line";
$_lang['Textarea'] = "Textarea";
$_lang['Display'] = "Display";
$_lang['First insert'] = "First insert";
$_lang['Predefined selection'] = "Predefined selection";
$_lang['Select by db query'] = "Select by db query";
$_lang['File'] = "File";

$_lang['Email Address'] = "Email Address";
$_lang['url'] = "url";
$_lang['Checkbox'] = "Checkbox";
$_lang['Multiple select'] = "Multiple select";
$_lang['Display value from db query'] = "Display value from db query";
$_lang['Time'] = "Time";
$_lang['Tooltip'] = "Tooltip"; 
$_lang['Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied'] = "Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied";
$_lang['Position'] = "Position";
$_lang['is current position, other free positions are:'] = "is current position, other free positions are:"; 
$_lang['Regular Expression:'] = "Regular Expression:";
$_lang['Please enter a regular expression to check the input on this field'] = "Please enter a regular expression to check the input on this field";
$_lang['Default value'] = "Default value";
$_lang['Predefined value for creation of a record. Could be used in combination with a hidden field as well'] = "Predefined value for creation of a record. Could be used in combination with a hidden field as well";
$_lang['Content for select Box'] = "Content for select Box";
$_lang['Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type'] = "Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type";
$_lang['Position in list view'] = "Position in list view";
$_lang['Only insert a number > 0 if you want that this field appears in the list of this module'] = "Only insert a number > 0 if you want that this field appears in the list of this module";
$_lang['Alternative list view'] = "Alternative list view";
$_lang['Value appears in the alt tag of the blue button (mouse over) in the list view'] = "Value appears in the alt tag of the blue button (mouse over) in the list view";
$_lang['Filter element'] = "Filter element";
$_lang['Appears in the filter select box in the list view'] = "Appears in the filter select box in the list view";
$_lang['Element Type'] = "Element Type";
$_lang['Select the type of this form element'] = "Select the type of this form element";
$_lang['Check the content of the previous field!'] = "Check the content of the previous field!";
$_lang['Span element over'] = "Span element over";
$_lang['columns'] = "columns";
$_lang['rows'] = "rows"; 
$_lang['Telephone'] = "Telephone";
$_lang['History'] = "History";
$_lang['Field'] = "Field";
$_lang['Old value'] = "Old value";
$_lang['New value'] = "New value";
$_lang['Author'] = "Author"; 
$_lang['Show Date'] = "Show Date";
$_lang['Creation date'] = "Creation date";
$_lang['Last modification date'] = "Last modification date";
$_lang['Email (at record cration)'] = "Email (at record cration)";
$_lang['Contact (at record cration)'] = "Contact (at record cration)"; 
$_lang['Select user'] = "Select user";
$_lang['Show user'] = "Show user";

// forum.php
$_lang['Please give your thread a title'] = "Please give your thread a title";
$_lang['New Thread'] = "New Thread";
$_lang['Title'] = "Title";
$_lang['Text'] = "Text";
$_lang['Post'] = "Post";
$_lang['From'] = "From";
$_lang['open'] = "open";
$_lang['closed'] = "closed";
$_lang['Notify me on comments'] = "Notify me on comments";
$_lang['Answer to your posting in the forum'] = "Answer to your posting in the forum";
$_lang['You got an answer to your posting'] = "You got an answer to your posting \n ";
$_lang['New posting'] = "New posting";
$_lang['Create new forum'] = "Create new forum";
$_lang['down'] ='down';
$_lang['up']= "up";
$_lang['Forums']= "Forums";
$_lang['Topics']="Topics";
$_lang['Threads']="Threads";
$_lang['Latest Thread']="Latest Thread";
$_lang['Overview forums']= "Overview forums";
$_lang['Succeeding answers']= "Succeeding answers";
$_lang['Count']= "Count";
$_lang['from']= "from";
$_lang['Path']= "Path";
$_lang['Thread title']= "Thread title";
$_lang['Notification']= "Notification";
$_lang['Delete forum']= "Delete forum";
$_lang['Delete posting']= "Delete posting";
$_lang['In this table you can find all forums listed']= "In this table you can find all forums listed";
$_lang['In this table you can find all threads listed']= "In this table you can find all threads listed";

// index.php
$_lang['Last name'] = "Last name";
$_lang['Short name'] = "Short name";
$_lang['Sorry you are not allowed to enter.'] = "Sorry you are not allowed to enter.";
$_lang['Please run index.php: '] = "Please run index.php: ";
$_lang['Reminder'] = "Reminder";
$_lang['Session time over, please login again'] = "Session time over, please login again";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Hide read elements";
$_lang['&nbsp;Show read elements'] = "&nbsp;Show read elements";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Hide archive elements";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Show archive elements";
$_lang['Tree view'] = "Tree view";
$_lang['flat view'] = "flat view";
$_lang['New todo'] = "New todo";
$_lang['New note'] = "New note";
$_lang['New document'] = "New document";
$_lang['Set bookmark'] = "Set bookmark";
$_lang['Move to archive'] = "Move to archive";
$_lang['Mark as read'] = "Mark as read";
$_lang['Export as csv file'] = "Export as csv file";
$_lang['Deselect all'] = "Deselect all";
$_lang['selected elements'] = "selected elements";
$_lang['wider'] = "wider";
$_lang['narrower'] = "narrower";
$_lang['ascending'] = "Aufsteigend";
$_lang['descending'] = "descending";
$_lang['Column'] = "Column";
$_lang['Sorting'] = "Sorting";
$_lang['Save width'] = "Save width";
$_lang['Width'] = "Width";
$_lang['switch off html editor'] = "switch off html editor";
$_lang['switch on html editor'] = "switch on html editor";
$_lang['hits were shown for'] = "hits were shown for";
$_lang['there were no hits found.'] = "there were no hits found.";
$_lang['Filename'] = "Filename";
$_lang['First Name'] = "First Name";
$_lang['Family Name'] = "Family Name";
$_lang['Company'] = "Company";
$_lang['Street'] = "Street";
$_lang['City'] = "City";
$_lang['Country'] = "Country";
$_lang['Please select the modules where the keyword will be searched'] = "Please select the modules where the keyword will be searched";
$_lang['Enter your keyword(s)'] = "Enter your keyword(s)";
$_lang['Salutation'] = "Salutation";
$_lang['State'] = "State";
$_lang['Add to link list'] = "Add to link list";

// setup.php
$_lang['Welcome to the setup of PHProject!<br>'] = "Benvindo ao configurador do PHProjekt!<br>";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Por favor anote:<ul>
<li>Uma base de Dados em branco, deve estar disponível
<li>Por favor tenha certeza que o seu Servidor Web sejá capaz de escrever o arquivo  'config.inc.php'<br> (e.g. 'chmod 777')";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>se você encontrar qualquer erro durante a instalação, por favor olhe em  <a href='help/faq_install.html' target=_blank>install faq</a>
or visit the <a href='http://www.PHProjekt.com/forum.html' target=_blank>Forum de Instalação</a></i>";
$_lang['Please fill in the fields below'] = "Preencha as seguintes caixas";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(Em alguns casos o programa jamais responderá.<br>
Nesse caso cancele o programa, feche o browser e tente novamente).<br>";
$_lang['Type of database'] = "Tipo de Banco de Dados";
$_lang['Hostname'] = "Hostname";
$_lang['Username'] = "Username";

$_lang['Name of the existing database'] = "Nome do Banco de Dados existente";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "Não foi encontrado config.inc.php! Deseja mesmo atualizar? Leia INSTALL ...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "Não foi encontrado config.inc.php! Talvez prefira atualizar o PHProjekt? Leia INSTALL ...";
$_lang['Please choose Installation,Update or Configure!'] = "Por favor escolha  'Instalação' ou 'Atualização'! voltar ...";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Não funciona! Verifique as Permissões do Banco de Dados  <br>Repare e reinstale.";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Desculpe, Não está funcionando! <br> Por favor configure DBDATE para 'Y4MD-' ou deixe o Phprojekt mudar está variável de sistema (php.ini)!";
$_lang['Seems that You have a valid database connection!'] = "Parabéns! Dispõe de uma database para ligação!";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "Selecione os módulos que pretende usar.<br> (Pode desativa-los mais tarde no config.inc.php)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "Instalar componente: inserir '1', ou deixe a caixa vazia";
$_lang['Group views'] = "Grupos mostrados";
$_lang['Todo lists'] = "Listas de itens a fazer";

$_lang['Voting system'] = "Sistema de votação";


$_lang['Contact manager'] = "Endereços";
$_lang['Name of userdefined field'] = "Name of userdefined field";
$_lang['Userdefined'] = "Userdefined";
$_lang['Profiles for contacts'] = "Profiles for contacts";
$_lang['Mail'] = "Correio";
$_lang['send mail'] = " send mail";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = " only,<br> &nbsp; &nbsp; full mail client";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1' para mostrar o apontamento na lista em uma janela separadaw,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'2' para alertas adicionais.";
$_lang['Alarm'] = "Alarme";
$_lang['max. minutes before the event'] = "Máximo de minutos antes do evento";
$_lang['SMS/Mail reminder'] = "SMS/Mail reminder";
$_lang['Reminds via SMS/Email'] = "Reminds via SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= Create projects,<br>
&nbsp; &nbsp; '2'= assign worktime to projects only with timecard entry<br>
&nbsp; &nbsp; '3'= assign worktime to projects without timecard entry<br> ";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "Nome do diretório onde os arquivos serão guardados<br>( Sem administração de arquivos: caixa vazia)";
$_lang['absolute path to this directory (no files = empty field)'] = "localização completa do diretório(Sem Arquivos = caixa vazia)";
$_lang['Time card'] = "Cartão de Ponto";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' Sistema de cartão de ponto,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '2' Inserção manual de Registros com cópia para o Chefe";
$_lang['Notes'] = "Notas";
$_lang['Password change'] = "Mudar Senha";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "Novas senhas pelo usuário - 0: nenhum - 1: Somente senha randômicas - 2: Escolha a própria";
$_lang['Encrypt passwords'] = "Senhas encriptadas";
$_lang['Login via '] = "Login via ";
$_lang['Extra page for login via SSL'] = "Pagina para login via SSL";
$_lang['Groups'] = "Grupo";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "Funções de usuários e módulos são atribuidas para os grupos<br>
&nbsp;&nbsp;&nbsp;&nbsp;(recomendada para os numeros de usuarios > 40)";
$_lang['User and module functions are assigned to groups'] = "Funções de usuários e módulos são atribuidas para os grupos";
$_lang['Help desk'] = "Help Desk (RT)";
$_lang['Help Desk Manager / Trouble Ticket System'] = "Help desk Gerenciador / Trouble Ticket System";
$_lang['RT Option: Customer can set a due date'] = "RT Opção: Cliente pode estabelecer uma data devida";
$_lang['RT Option: Customer Authentification'] = "RT Opção: Autenticação do Cliente";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: Aberto para todos, email é suficiente, 1: cliente precisa estar na lista de contatos e inserir seu sobrenome";
$_lang['RT Option: Assigning request'] = "RT Opção: Inserir Solicitação";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: para todos, 1: para todas as pessoas com status de  'chefe'";
$_lang['Email Address of the support'] = "Email do suporte";
$_lang['Scramble filenames'] = "mistura nomes de arquivos";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "cria nomes de arquivos misturados no servidor<br>
assume o nome anterior no download";

$_lang['0: last name, 1: short name, 2: login name'] = "0: Ultimo nome, 1: apelido, 2: login nome";
$_lang['Prefix for table names in db'] = "Prefix for table names in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "Alerta: Não pode criar arquivo 'config.inc.php'!<br>
Instalalação do diretório (chmod 777(unix) directorio e config.inc.php) para obter acesso.";
$_lang['Location of the database'] = "Localização da database";
$_lang['Type of database system'] = "Tipo de database sistema";
$_lang['Username for the access'] = "Username para acesso";
$_lang['Password for the access'] = "Password para acesso";
$_lang['Name of the database'] = "Nome da database";
$_lang['Prefix for database table names'] = "Prefix for database table names";
$_lang['First background color'] = "Primeira cor de fundo";
$_lang['Second background color'] = "Segunda cor de fundo";
$_lang['Third background color'] = "Terceira cor de fundo";
$_lang['Color to mark rows'] = "Color to mark rows";
$_lang['Color to highlight rows'] = "Color to highlight rows";
$_lang['Event color in the tables'] = "Cor do evento nas tabelas";
$_lang['company icon yes = insert name of image'] = "Icone da companhia yes = inserir nome da imagem";
$_lang['URL to the homepage of the company'] = "URL da homepage da companhia";
$_lang['no = leave empty'] = "no = deixe vazio";
$_lang['First hour of the day:'] = "Primeira hora do dia:";
$_lang['Last hour of the day:'] = "Última hora do dia:";
$_lang['An error ocurred while creating table: '] = "Ocorreu o seguinte erro ao criar a tabela: ";
$_lang['Table dateien (for file-handling) created'] = "Tabela 'dateien' (para manuseamento do ficheiro) criada";
$_lang['File management no = leave empty'] = "Administração de ficheiros no = deixe vazio";
$_lang['yes = insert full path'] = "yes = inserir localização completa";
$_lang['and the relative path to the PHProjekt directory'] = "e adicionalmente a localização relativa para root";
$_lang['Table profile (for user-profiles) created'] = "Tabela 'profile' (para perfis de membros) criada";
$_lang['User Profiles yes = 1, no = 0'] = "Perfis yes = 1, no = 0";
$_lang['Table todo (for todo-lists) created'] = "Tabela 'todo' (para itens a fazer) criada";
$_lang['Todo-Lists yes = 1, no = 0'] = "Lista de itens a fazer yes = 1, no = 0";
$_lang['Table forum (for discssions etc.) created'] = "Tabela 'forum' (discussões,etc.) criada";
$_lang['Forum yes = 1, no = 0'] = "Forum yes = 1, no = 0";
$_lang['Table votum (for polls) created'] = "Tabela 'votum' (para votações) criada";
$_lang['Voting system yes = 1, no = 0'] = "Sistema de votação yes = 1, no = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "Tabela 'lesezeichen' (para favoritos) criada";
$_lang['Bookmarks yes = 1, no = 0'] = "Favoritos yes = 1, no = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "Tabela 'ressourcen' (para administração de recursos adicionais) criada";
$_lang['Resources yes = 1, no = 0'] = "Recursos yes = 1, no = 0";
$_lang['Table projekte (for project management) created'] = "Tabela 'projekte' (para administração de projetos) criada";
$_lang['Table contacts (for external contacts) created'] = "Table contacts (for external contacts) created";
$_lang['Table notes (for notes) created'] = "Table notes (for notes) created";
$_lang['Table timecard (for time sheet system) created'] = "Table timecard (for time sheet system) created";
$_lang['Table groups (for group management) created'] = "Table groups (for group management) created";
$_lang['Table timeproj (assigning work time to projects) created'] = "table timeproj (assigning work time to projects) created";
$_lang['Table rts and rts_cat (for the help desk) created'] = "Table rts and rts_cat (for the help desk) created";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created";
$_lang['Table logs (for user login/-out tracking) created'] = "Table logs (for user login/-out tracking) created";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "Tables contacts_profiles und contacts_prof_rel created";
$_lang['Project management yes = 1, no = 0'] = "Administração de projetos yes = 1, no = 0";
$_lang['additionally assign resources to events'] = "Adicionalmente inserir recursos nos eventos";
$_lang['Address book  = 1, nein = 0'] = "Endereços  = 1, nein = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "Correio expresso yes = 1, no = 0";
$_lang['Chat yes = 1, no = 0'] = "Chat yes = 1, no = 0";
$_lang['Name format in chat list'] = "Name format in chat list";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name";
$_lang['Timestamp for chat messages'] = "Timestamp for chat messages";
$_lang['users (for authentification and address management)'] = "'users' (para autenticação e administração de endereços)";
$_lang['Table termine (for events) created'] = "Tabela 'termine' (para eventos) criada";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "Os seguintes membros foram inseridos com sucesso na tabela 'user':<br>
'root' - (Administrador geral)<br>
'test' - (membros regulares, com acesso restrito)";
$_lang['The group default has been created'] = "O grupo 'default' Foi criado";
$_lang['Please do not change anything below this line!'] = "Não altere nada além desta linha!";
$_lang['Database error'] = " Erro da Database";
$_lang['Finished'] = "Terminado";
$_lang['There were errors, please have a look at the messages above'] = "Houveram erros, por favor olhe a mensagem acima";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "todas as tabelas necessárias foram instaladas <br>
o arquivo de configuração 'config.inc.php' foi re-escrito<br>
É uma boa idéia fazer backup deste arquivo.<br>
Feche todas as janelas do seu Navegador agora.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "O administrador 'root' tem a senha 'root'. Por favor mude esta senha:";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "usuario 'test' são membro do grupo 'default'.<br>
Agora você pode criar grupos novos e pode adicionar os novos usuários para o grupo";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "Para executar o programa PHProjekt no seu Browser vá a <b>index.php</b><br>
Teste a configuração, em particular os modulos 'Correio expresso' e 'Arquivos'.";

$_lang['Alarm x minutes before the event'] = "Alarme x minutos antes do evento";
$_lang['Additional Alarmbox'] = "caixa de alarme adicional";
$_lang['Mail to the chief'] = "E-mail para o chefe";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "sair/Voltar conta como : 1: Pausa - 0: Tempo de serviço";
$_lang['Passwords will now be encrypted ...'] = "As senhas serão encriptadas agora";
$_lang['Filenames will now be crypted ...'] = "Filenames will now be crypted ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "Você quer fazer um backup do banco de dados agora? (E compacte junto com o  config.inc.php ...)<br>
Claro que você vai esperar!";
$_lang['Next'] = "próximo";
$_lang['Notification on new event in others calendar'] = "Notificação de um novo evento em outros calendários";
$_lang['Path to sendfax'] = "caminho para o SendFAX";
$_lang['no fax option: leave blank'] = "Sem fax: Deixe em branco";
$_lang['Please read the FAQ about the installation with postgres'] = "Por favor leia a FAQ para instalação com postgres";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "tamanho dos apelidos<br> (Numero de Letras: 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "se você quer instalar o  PHProjekt manualmente, veja em
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>here</a> a mysql dump and a default config.inc.php";
$_lang['The server needs the privilege to write to the directories'] = "O servidor precisa de privilégios para 'escrever' no diretório";
$_lang['Header groupviews'] = "Cabeçalho groupviews";
$_lang['name, F.'] = "nome, F.";
$_lang['shortname'] = "shortname";
$_lang['loginname'] = "loginname";
$_lang['Please create the file directory'] = "Por favor crie o diretório";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "Mode padrão para a estrutura do Fórum: 1 - open, 0 - closed";
$_lang['Currency symbol'] = "Currency symbol";
$_lang['current'] = "actual";      
$_lang['Default size of form elements'] = "Default size of form elements";
$_lang['use LDAP'] = "use LDAP";
$_lang['Allow parallel events'] = "Allow parallel events";
$_lang['Timezone difference [h] Server - user'] = "Timezone difference [h] Server - user";
$_lang['Timezone'] = "Timezone";
$_lang['max. hits displayed in search module'] = "max. hits displayed in search module";
$_lang['Time limit for sessions'] = "Time limit for sessions";
$_lang['0: default mode, 1: Only for debugging mode'] = "0: default mode, 1: Only for debugging mode";
$_lang['Enables mail notification on new elements'] = "Enables mail notification on new elements";
$_lang['Enables versioning for files'] = "Enables versioning for files";
$_lang['no link to contacts in other modules'] = "no link to contacts in other modules";
$_lang['Highlight list records with mouseover'] = "Highlight list records with 'mouseover'";
$_lang['Track user login/logout'] = "Track user login/logout";
$_lang['Access for all groups'] = "Access for all groups";
$_lang['Option to release objects in all groups'] = "Option to release objects in all groups";
$_lang['Default access mode: private=0, group=1'] = "Default access mode: private=0, group=1"; 
$_lang['Adds -f as 5. parameter to mail(), see php manual'] = "Adds '-f' as 5. parameter to mail(), see php manual";
$_lang['end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)'] = "end of line in body; e.g. \\r\\n (conform to RFC 2821 / 2822)";
$_lang['end of header line; e.g. \r\n (conform to RFC 2821 / 2822)'] = "end of header line; e.g. \\r\\n (conform to RFC 2821 / 2822)";
$_lang['Sendmail mode: 0: use mail(); 1: use socket'] = "Sendmail mode: 0: use mail(); 1: use socket";
$_lang['the real address of the SMTP mail server, you have access to (maybe localhost)'] = "the real address of the SMTP mail server, you have access to (maybe localhost)";
$_lang['name of the local server to identify it while HELO procedure'] = "name of the local server to identify it while HELO procedure";
$_lang['Authentication'] = "Authentication";
$_lang['fill out in case of authentication via POP before SMTP'] = "fill out in case of authentication via POP before SMTP";
$_lang['real username for POP before SMTP'] = "real username for POP before SMTP";
$_lang['password for this pop account'] = "password for this pop account"; 
$_lang['the POP server'] = "the POP server";
$_lang['fill out in case of SMTP authentication'] = "fill out in case of SMTP authentication";
$_lang['real username for SMTP auth'] = "real username for SMTP auth";
$_lang['password for this account'] = "password for this account";
$_lang['SMTP account data (only needed in case of socket)'] = "SMTP account data (only needed in case of socket)";
$_lang['No Authentication'] = "No Authentication"; 
$_lang['with POP before SMTP'] = "with POP before SMTP";
$_lang['SMTP auth (via socket only!)'] = "SMTP auth (via socket only!)";
$_lang['Log history of records'] = "Log history of records";
$_lang['Send'] = " Senden";
$_lang['Host-Path'] = "Host-Path";
$_lang['Installation directory'] = "Installation directory";
$_lang['0 Date assignment by chief, 1 Invitation System'] = "0 Date assignment by chief, 1 Invitation System";
$_lang['0 Date assignment by chief,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Invitation System'] = "0 Date assignment by chief,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Invitation System";
$_lang['Default write access mode: private=0, group=1'] = "Default write access mode: private=0, group=1";
$_lang['Select-Option accepted available = 1, not available = 0'] = "Select-Option accepted available = 1, not available = 0";
$_lang['absolute path to host, e.g. http://myhost/'] = "absolute path to host, e.g. http://myhost/";
$_lang['installation directory below host, e.g. myInstallation/of/phprojekt5/'] = "installation directory below host, e.g. myInstallation/of/phprojekt5/";

// l.php
$_lang['Resource List'] = "Lista de recursos";
$_lang['Event List'] = "Lista de eventos";
$_lang['Calendar Views'] = "Grupos mostrados";

$_lang['Personnel'] = "Personal";
$_lang['Create new event'] = "criar &amp; eliminar eventos";
$_lang['Day'] = "dia";

$_lang['Until'] = "até";

$_lang['Note'] = "Nota";
$_lang['Project'] = "Projeto";
$_lang['Res'] = "Recur";
$_lang['Once'] = "Uma";
$_lang['Daily'] = "Diariam.";
$_lang['Weekly'] = "1x/Sem.";
$_lang['Monthly'] = "1x/Mês";
$_lang['Yearly'] = "1x/Ano";

$_lang['Create'] = "criar";

$_lang['Begin'] = "Inicio";
$_lang['Out of office'] = "Saida do Escritorio";
$_lang['Back in office'] = "volta para o Escritorio";
$_lang['End'] = "Final";
$_lang['@work'] = "no trabalho";
$_lang['We'] = "Sem";  //Semana
$_lang['group events'] = "Eventos de Grupo";
$_lang['or profile'] = "ou Perfil";
$_lang['All Day Event'] = "evento para o dia inteiro";
$_lang['time-axis:'] = "tempo-axis:";
$_lang['vertical'] = "vertical";
$_lang['horizontal'] = "horizontal";
$_lang['Horz. Narrow'] = "hor. seta";
$_lang['-interval:'] = "-intervalo:";
$_lang['Self'] = "Eu";

$_lang['...write'] = "...escrever";

$_lang['Calendar dates'] = "Calendar dates";
$_lang['List'] = "List";
$_lang['Year'] = "Year";
$_lang['Month'] = "Month";
$_lang['Week'] = "Week";
$_lang['Substitution'] = "Substitution";
$_lang['Substitution for'] = "Substitution for";
$_lang['Extended&nbsp;selection'] = "Extended&nbsp;selection";
$_lang['New Date'] = "New date entered";
$_lang['Date changed'] = "Date changed";
$_lang['Date deleted'] = "Date deleted";

// links
$_lang['Database table'] = "Database table";
$_lang['Record set'] = "Record set";
$_lang['Resubmission at:'] = "Resubmission at:";
$_lang['Set Links'] = "Links";
$_lang['From date'] = "From date";
$_lang['Call record set'] = "Call record set";


//login.php
$_lang['Please call login.php!'] = "Por favor execute login.php!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Existem outros eventos!<br>o principal apontamento é: ";
$_lang['Sorry, this resource is already occupied: '] = "Este recurso já se encontra ocupado: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = " Este evento não existe.<br> <br> Verifique a data e a hora. ";
$_lang['Please check your date and time format! '] = "Verifique o formato da data e hora! ";
$_lang['Please check the date!'] = "Verifique a data!";
$_lang['Please check the start time! '] = "Verifique a hora de inicio! ";
$_lang['Please check the end time! '] = "Verifique a hora a que termina! ";
$_lang['Please give a text or note!'] = "Introduza texto ou nota!";
$_lang['Please check start and end time! '] = "Verifique hora de inicio e fim! ";
$_lang['Please check the format of the end date! '] = "Verifique o formato do fim da data! ";
$_lang['Please check the end date! '] = "Verifique o fim da data! ";





$_lang['Resource'] = "Recurso";
$_lang['User'] = "Membro";

$_lang['delete event'] = "eliminar evento";
$_lang['Address book'] = "Endereços";


$_lang['Short Form'] = "Apelido";

$_lang['Phone'] = "Telefone";
$_lang['Fax'] = "Fax";



$_lang['Bookmark'] = "Favorito";
$_lang['Description'] = "Descrição";

$_lang['Entire List'] = "Dentro da lista";

$_lang['New event'] = "Novo evento";
$_lang['Created by'] = "Criado por";
$_lang['Red button -> delete a day event'] = "Botão vermelho -> apaga um evento diário";
$_lang['multiple events'] = "eventos múltiplos";
$_lang['Year view'] = "Visão anual";
$_lang['calendar week'] = "semana calendário";

//m2.php
$_lang['Create &amp; Delete Events'] = "Criar &amp; Deletar Eventos";
$_lang['normal'] = "normal";
$_lang['private'] = "privativo";
$_lang['public'] = "público";
$_lang['Visibility'] = "Visibilidade";

//mail module
$_lang['Please select at least one (valid) address.'] = "Por Favor, selecione no minimo 1 endereço válido.";
$_lang['Your mail has been sent successfully'] = "O seu correio foi enviado com sucesso";
$_lang['Attachment'] = "Anexos";
$_lang['Send single mails'] = "Envia e-mails individualizados";
$_lang['Does not exist'] = "Não existe";
$_lang['Additional number'] = "Numero Adicional";
$_lang['has been canceled'] = "Foi cancelado";

$_lang['marked objects'] = "marked objects";
$_lang['Additional address'] = "Endereço adicional";
$_lang['in mails'] = "nos e-mails";
$_lang['Mail account'] = "Conta de Correio";
$_lang['Body'] = "Corpo";
$_lang['Sender'] = "Remetente";

$_lang['Receiver'] = "Destinatário";
$_lang['Reply'] = "Responder";
$_lang['Forward'] = "Reenviar";
$_lang['Access error for mailbox'] = "Erro de acesso para a caixa de correio";  // changement  erro de acesso da caixa de e-mail
$_lang['Receive'] = "Receber"; // changement Recepcao
$_lang['Write'] = "Enviar";  // changement envio
$_lang['Accounts'] = "Conta"; // changement ?
$_lang['Rules'] = "Regras"; // changement  regras
$_lang['host name'] = "nome do hospedeiro"; // changement 
$_lang['Type'] = "Tipo"; // changement tipo
$_lang['misses'] = "Esquecido"; // changement  esquecido
$_lang['has been created'] = "foi criado"; // changement   foi criado
$_lang['has been changed'] = "foi mudado"; // changement foi mudado
$_lang['is in field'] = "está no campo"; // changement ?
$_lang['and leave on server'] = "Receber e-mails e deixá-los no servidor"; // changement ?
$_lang['name of the rule'] = "Nome da regra"; // changement
$_lang['part of the word'] = "parte da palavra";// changement
$_lang['in'] = "dentro"; // changement
$_lang['sent mails'] = "mensagens enviadas"; // changement
$_lang['Send date'] = "Enviar Data";// changement
$_lang['Received'] = "Recebido";// changement
$_lang['to'] = "para";// changement
$_lang['imcoming Mails'] = "e-mails recibidos";// changement
$_lang['sent Mails'] = "e-mails enviados";// changement
$_lang['Contact Profile'] = "Perfis de contatos";// changement
$_lang['unread'] = "não lido";// changement
$_lang['view mail list'] = "ver a lista de correio";// changement
$_lang['insert db field (only for contacts)'] = "inserir campo da base de dados (somente para contatos externos)";// changement
$_lang['Signature'] = "Assinatura";// changement

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "Conta única do cliente";// changement
$_lang['Notice of receipt'] = "Aviso de recepção";
$_lang['Assign to project'] = "Designado ao projeto";
$_lang['Assign to contact'] = "Designado ao contato";  
$_lang['Assign to contact according to address'] = "Designar ao contato de acordo com o endereço";
$_lang['Include account for default receipt'] = "Incluir conta para recepção padrão";
$_lang['Your token has already been used.<br>If it wasnt you, who used the token please contact your administrator.'] = "Your token has already been used.<br>If it wasn't you, who used the token please contact your administrator";
$_lang['Your token has already been expired.'] = "Your token has already been expired";
$_lang['Unconfirmed Events'] = "Unconfirmed Events";
$_lang['Visibility presetting when creating an event'] = "Voreinstellung der Sichtbarkeit beim Anlegen eines Termins";
$_lang['Subject'] = "Subject";
$_lang['Content'] = "Inhalt";
$_lang['answer all'] = "answer to all";
$_lang['Create new message'] = "Create new message";
$_lang['Attachments'] = "Attachments";
$_lang['Recipients'] = "Recipients";
$_lang['file away message'] = "file away message";
$_lang['Message from:'] = "Message from:";

//notes.php
$_lang['Mail note to'] = "E-mail da Nota para";
$_lang['added'] = "adicionado";// changement adicionad
$_lang['changed'] = "alterado";// changement mudado

// o.php
$_lang['Calendar'] = "Calendário";
$_lang['Contacts'] = "Endereços";


$_lang['Files'] = "Arquivos";



$_lang['Options'] = "Opções";
$_lang['Timecard'] = "Ponto Eletrônico";

$_lang['Helpdesk'] = "Help Desk";

$_lang['Info'] = "Info";
$_lang['Todo'] = "Tarefas";// changement  para fazer
$_lang['News'] = "Novidades";// changement  Novidades
$_lang['Other'] = "Outros";// changement outros
$_lang['Settings'] = "Configurações";// changement configuracoes
$_lang['Summary'] = "Sumário";// changement sumario

// options.php
$_lang['Description:'] = "Descrição:";
$_lang['Comment:'] = "Comentário:";
$_lang['Insert a valid Internet address! '] = "Insira um email válido! ";
$_lang['Please specify a description!'] = "Especifique uma descrição!";
$_lang['This address already exists with a different description'] = "Este endereço já existe com uma descrição diferente";
$_lang[' already exists. '] = " já existe. ";
$_lang['is taken to the bookmark list.'] = "Está inserido na lista de favoritos.";
$_lang[' is changed.'] = " foi alterado.";
$_lang[' is deleted.'] = " foi eliminado.";
$_lang['Please specify a description! '] = "Especifique uma descrição! ";
$_lang['Please select at least one name! '] = "Selecione no minimo um nome! ";
$_lang[' is created as a profile.<br>'] = " foi criado como perfil.<br> Assim que o calendário for atualizado o perfil fica activo.";
$_lang['is changed.<br>'] = "foi alterado.<br> Assim que o calendário for atualizado o perfil fica activo.";
$_lang['The profile has been deleted.'] = "O perfil foi eliminado.";
$_lang['Please specify the question for the poll! '] = "Especifique uma questão para votar! ";
$_lang['You should give at least one answer! '] = "Deve inserir uma resposta no minimo! ";
$_lang['Your call for votes is now active. '] = "A votação ficou ativa. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h2>Favoritos</h2>Aqui pode criar, modificar e eliminar favoritos:";
$_lang['Create'] = "criar";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h2>Perfis</h2>Aqui pode criar, modificar e eliminar perfis:";
$_lang['<h2>Voting Formula</h2>'] = "<h2>Formulário de voto</h2>";
$_lang['In this section you can create a call for votes.'] = "Aqui pode por uma questão á votação de outros membros.";
$_lang['Question:'] = "Questão:";
$_lang['just one <b>Alternative</b> or'] = "só uma <b>Alternativa</b> ou";
$_lang['several to choose?'] = "várias á escolha?";

$_lang['Participants:'] = "Participantes:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h3>Mudança de senha</h3> Nesta seção você pode escolher um password aleatório gerado para você.";
$_lang['Old Password'] = "Senha Antiga";
$_lang['Generate a new password'] = "Cria uma nova senha";
$_lang['Save password'] = "Salva senha";
$_lang['Your new password has been stored'] = "Sua nova senha foi armazenada";
$_lang['Wrong password'] = "Senha Errada !";
$_lang['Delete poll'] = "Apaga Voto";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>Apaga assuntos do Fórum</h4> Aqui você pode apagar
seus assuntos <br> Somente assuntos sem comentários aparecerão.";

$_lang['Old password'] = "Senha Antiga";
$_lang['New Password'] = "Nova senha";
$_lang['Retype new password'] = "Redigite a senha";
$_lang['The new password must have 5 letters at least'] = "A nova senha precisa ter no mínimo 5 letras";
$_lang['You didnt repeat the new password correctly'] = "Você não redigitou a nova senha corretamente";

$_lang['Show bookings'] = "Mostra as reservas";// changement ?
$_lang['Valid characters'] = "Caracteres Válidos";
$_lang['Suggestion'] = "Sugestão";// changement 
$_lang['Put the word AND between several phrases'] = "Insira a palavra AND entre cada frase"; // translators: please leave the word AND as it is   // changement 
$_lang['Write access for calendar'] = "Direito de escrever no calendário";// changement 
$_lang['Write access for other users to your calendar'] = "Direito para que outros usuários possam escrever no seus calendário";// changement 
$_lang['User with chief status still have write access'] = "Usuário com estatus de chefe retém ainda direito de escrever";// changement 

// projects
$_lang['Project Listing'] = "Lista de Projetos";
$_lang['Project Name'] = "nome do projeto";


$_lang['o_files'] = "Files";
$_lang['o_notes'] = "Notes";
$_lang['o_projects'] = "Projects";
$_lang['o_todo'] = "Todo";
$_lang['Copyright']="Copyright";
$_lang['Links'] = "Links";
$_lang['New profile'] = "Neuer Verteiler";
$_lang['In this section you can choose a new random generated password.'] = "In this section you can choose a new random generated password.";
$_lang['timescale'] = "timescale";
$_lang['Manual Scaling'] = "Manual scaling";
$_lang['column view'] = "column view";
$_lang['display format'] = "display format";
$_lang['for chart only'] = "For chart only:";
$_lang['scaling:'] = "scling:";
$_lang['colours:'] = "colours";
$_lang['display project colours'] = "display project colours";
$_lang['weekly'] = "weekly";
$_lang['monthly'] = "monthly";
$_lang['annually'] = "annually";
$_lang['automatic'] = "automatic";
$_lang['New project'] = "New project";
$_lang['Basis data'] = "Basis data";
$_lang['Categorization'] = "Categorization";
$_lang['Real End'] = "Fim Real";
$_lang['Participants'] = "Participantes";
$_lang['Priority'] = "Prioridade";
$_lang['Status'] = "Situação";// changement estado
$_lang['Last status change'] = "última <br>alteração";
$_lang['Leader'] = "Líder";// changement  
$_lang['Statistics'] = "Estatisticas";
$_lang['My Statistic'] = "Minha estatística";// changement  minhas estatisticas

$_lang['Person'] = "Pessoa";
$_lang['Hours'] = "Horas";
$_lang['Project summary'] = "Sumário do projeto";
$_lang[' Choose a combination Project/Person'] = " Escolha a combinação Projeto/Pessoa";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(seleção múltipla com a tecla 'Ctrl')";

$_lang['Persons'] = "Pessoas";
$_lang['Begin:'] = "Começa:";
$_lang['End:'] = "Termina:";
$_lang['All'] = "Todos";
$_lang['Work time booked on'] = "tempo de trabalho anotado em";
$_lang['Sub-Project of'] = "Subprojeto de";
$_lang['Aim'] = "Objetivo";// changement  alvo
$_lang['Contact'] = "Contato";
$_lang['Hourly rate'] = "Taxa de horas";
$_lang['Calculated budget'] = "Custo Calculado";
$_lang['New Sub-Project'] = "Novo subprojeto";
$_lang['Booked To Date'] = "anotado até agora";
$_lang['Budget'] = "Orçamento";
$_lang['Detailed list'] = "Lista detalhada";
$_lang['Gantt'] = "Timeline";// changement 
$_lang['offered'] = "oferecido ";// changement 
$_lang['ordered'] = "Ordenado";// changement 
$_lang['Working'] = "em andamento";// changement 
$_lang['ended'] = "terminado";// changement 
$_lang['stopped'] = "parado";// changement 
$_lang['Re-Opened'] = "aberto outra vez";// changement 
$_lang['waiting'] = "aguardando";// changement 
$_lang['Only main projects'] = "Só os projetos principais";// changement só os projetos principais
$_lang['Only this project'] = "Só este projeto";// changement  só esse projeto
$_lang['Begin > End'] = "início > fim";// changement  iniço fim
$_lang['ISO-Format: yyyy-mm-dd'] = "Formato ISO: ano  mês - dia";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "a duração deste projeto deve ser  consistente com a duração do projeto pai, por favor ajuste";// changement  
$_lang['Please choose at least one person'] = "Por favor escolha pelo menos uma pessoa";// changement por favor selecione por menos uma pessoa
$_lang['Please choose at least one project'] = "Por favor selecione pelo menos um projeto"; // changement  por favor selecione por menos uma pessoa
$_lang['Dependency'] = "Dependência";// changement
$_lang['Previous'] = "Anterior";// changement
// changement siguente
$_lang['cannot start before the end of project'] = "não pode começar antes do fim do projeto";// changement 
$_lang['cannot start before the start of project'] = "não pode começar antes do inicio do projeto";// changement
$_lang['cannot end before the start of project'] = "não pode terminar antes do inicio do projeto";// changement
$_lang['cannot end before the end of project'] = "não pode terminar antes do fim do projeto";// changement
$_lang['Warning, violation of dependency'] = "atenção, violação da dependência";// changement
$_lang['Container'] = "Recipiente";// changement
$_lang['External project'] = "Projeto externo";// changement
$_lang['Automatic scaling'] = "Escala automática";// changement
$_lang['Legend'] = "Legenda";// changement
$_lang['No value'] = "Sem valor";// changement
$_lang['Copy project branch'] = "Copiar ramo do projeto";
$_lang['Copy this element<br> (and all elements below)'] = "Copiar este elemento<br> (e todos os elementos abaixo)";
$_lang['And put it below this element'] = "E colocar abaixo deste elemento";
$_lang['Edit timeframe of a project branch'] = "Edite o tempo do ramo do projeto"; 

$_lang['of this element<br> (and all elements below)'] = "deste elemento<br> (e todos os elementos abaixo)";  
$_lang['by'] = "por";
$_lang['Probability'] = "Probabilidade";
$_lang['Please delete all subelements first'] = "Por favor, primeiro delete todos os subprojetos";
$_lang['Assignment'] ="Assignment";
$_lang['display'] = "Display";
$_lang['Normal'] = "Normal";
$_lang['sort by date'] = "Sort by date";
$_lang['sort by'] = "Sort by";
$_lang['Calculated budget has a wrong format'] = "Calculated budget has a wrong format";
$_lang['Hourly rate has a wrong format'] = "Hourly rate has a wrong format";

// r.php
$_lang['please check the status!'] = "verifique a situação!";// changement
$_lang['Todo List: '] = "Lista de itens a fazer: ";// changement
$_lang['New Remark: '] = "Nova entrada ";
$_lang['Delete Remark '] = "eliminar entrada ";
$_lang['Keyword Search'] = "Procura: ";
$_lang['Events'] = "eventos";
$_lang['the forum'] = "no forum";
$_lang['the files'] = "nos arquivos";
$_lang['Addresses'] = "Endereços";
$_lang['Extended'] = "Extendido";
$_lang['all modules'] = "todos os módulos";// changement
$_lang['Bookmarks:'] = "Favoritos:";
$_lang['List'] = "Lista";
$_lang['Projects:'] = "Projetos:";

$_lang['Deadline'] = "Data final";
// changement
$_lang['Polls:'] = "Votos:";

$_lang['Poll created on the '] = "Votação criada em ";


// reminder.php
$_lang['Starts in'] = "Começa em";
$_lang['minutes'] = "minutos";
$_lang['No events yet today'] = "Ainda sem eventos hoje";
$_lang['New mail arrived'] = "Novo e-mail";// changement novo e-mail  (cf comment voir si mail arrivé)

//ress.php

$_lang['List of Resources'] =  "lista de recursos";
$_lang['Name of Resource'] = "nome dos recursos";
$_lang['Comments'] =  "comentário";


// roles
$_lang['Roles'] = "Papéis";// changement
$_lang['No access'] = "Sem Acessocess";// changement
$_lang['Read access'] = "Acesso para leitura";// changement
// changement
$_lang['Role'] = "Papel";

// helpdesk
$_lang['Request'] = "Solicitação";
// changement
$_lang['pending requests'] = "Solicitações Pendentes";
$_lang['show queue'] = "Fila de Espera";
$_lang['Search the knowledge database'] = "Pesquisar no Banco de Dados";
$_lang['Keyword'] = "Palavra-Chave";
$_lang['show results'] = "Resultados";
$_lang['request form'] = "Formulario de Solicitação";
$_lang['Enter your keyword'] = "Digite a palavra-chave";
$_lang['Enter your email'] = "Digite seu E-mail";
$_lang['Give your request a name'] = "Digite o titulo da sua solicitação";
$_lang['Describe your request'] = "Descreva seu problema";

$_lang['Due date'] = "Data da Solicitação";
$_lang['Days'] = "Dias";
$_lang['Sorry, you are not in the list'] = "Desculpe, você não está na lista";
$_lang['Your request Nr. is'] = "O numero do seu chamado é";
$_lang['Customer'] = "Cliente";

// changement
$_lang['Search'] = "Busca";
$_lang['at'] = "em";
$_lang['all fields'] = "Todos os campos";


$_lang['Solution'] = "Solução";
$_lang['AND'] = "e";

$_lang['pending'] = "Pendente";
$_lang['stalled'] = "No Aguardo";
$_lang['moved'] = "Transferido";
$_lang['solved'] = "Resolvido";
$_lang['Submit'] = "Enviar";
$_lang['Ass.'] = "Ass.";// changement
$_lang['Pri.'] = "Pri.";// changement
$_lang['access'] = "Acesso";
$_lang['Assigned'] = "Responsavel";

$_lang['update'] = "Atualizar";
$_lang['remark'] = "Problema apresentado";
$_lang['solve'] = "Resolver";
$_lang['stall'] = "No Aguardo";
$_lang['cancel'] = "Cancelado";
$_lang['Move to request'] = "Transferir para Solicitação";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Prezado Cliente, quando nos contactar, refira-se ao numero do chamado acima, atenderemos seu chamado assim que possivel. Obrigado";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "Sua Solicitacao foi registrada em nosso sistema.<br>
Voce recebera um e-mail de confirmacao em alguns instantes.";
$_lang['n/a'] = "n/d";
$_lang['internal'] = "interno";

$_lang['has reassigned the following request'] = "O seguinte chamado foi redirecionado";
$_lang['New request'] = "Nova Solicitacao";
$_lang['Assign work time'] = "Horario de Trabalho";
$_lang['Assigned to:'] = "Solicitação";
$rts_53 = "Designado para:";// changement
// changement  prioridade
$_lang['Your solution was mailed to the customer and taken into the database.'] = "Sua solução foi enviada ao usuário e incluida na Base de dados.";// changement
$_lang['Answer to your request Nr.'] = "Resposta à sua solicitação No";// changement
$_lang['Fetch new request by mail'] = "Busque o pedido novo pelo correio";// changement
$_lang['Your request was solved by'] = "Resposta à sua solicitação de no.";// changement
// changement
$_lang['Your solution was mailed to the customer and taken into the database'] = "Sua solução foi enviada via e-mail para o cliente e foi armazenada no banco de dados";// changement
$_lang['Search term'] = "Search term";
$_lang['Search area'] = "Search area";
$_lang['Extended search'] = "Extended search";
$_lang['knowledge database'] = "knowledge database";
$_lang['Cancel'] = "Cancel";
$_lang['New ticket'] = "New ticket";
$_lang['Ticket status'] ="Ticket status";

// please adjust this states as you want -> add/remove states in helpdesk.php
$_lang['unconfirmed'] = 'unconfirmed';
$_lang['new'] = 'new';
$_lang['assigned'] = 'assigned';
$_lang['reopened'] = 'reopened';
$_lang['resolved'] = 'resolved';
$_lang['verified'] = 'verified';

// settings.php
$_lang['The settings have been modified'] = "As configurações foram modificadas";// changement
$_lang['Skin'] = "Skin";// changement
$_lang['First module view on startup'] = "Primeiro módulo visto no inicio";// changement
$_lang['none'] = "nenhum";// changement
$_lang['Check for mail'] = "Verifique o seus e-mails";// changement
$_lang['Additional alert box'] = "Additional alert box";// changement
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "Resolução horizontal da telal(i.e. 1024, 800)";// changement
$_lang['Chat Entry'] = "Entrada do Chat";// changement
$_lang['single line'] = "linha simples";
$_lang['multi lines'] = "linhas múltiplas";
$_lang['Chat Direction'] = "Direção do Chat";
$_lang['Newest messages on top'] = "Mensagens mais recentes no topo";
$_lang['Newest messages at bottom'] = "Mensagens mais recents em baixo";
$_lang['File Downloads'] = "Carregamento de arquivos";// changement

$_lang['Inline'] = "Inline";
$_lang['Lock file'] = "Lock file";
$_lang['Unlock file'] = "nlock file";
$_lang['New file here'] = "New file here";
$_lang['New directory here'] = "New directory here";
$_lang['Position of form'] = "Position of form";
$_lang['On a separate page'] = "On a separate page";
$_lang['Below the list'] = "Below the list";
$_lang['Treeview mode on module startup'] = "Treeview mode on module startup";
$_lang['Elements per page on module startup'] = "Elements per page on module startup";
$_lang['General Settings'] = "General Settings";
$_lang['First view on module startup'] = "First view on module startup";
$_lang['Left frame width [px]'] = "Left frame width [px]";
$_lang['Timestep Daywiew [min]'] = "Timestep Dayview [min]";
$_lang['Timestep Weekwiew [min]'] = "Timestep Weekview [min]";
$_lang['px per char for event text<br>(not exact in case of proportional font)'] = "px per char for event text<br>(not exact in case of proportional font)";
$_lang['Text length of events will be cut'] = "Text length of events will be cut";
$_lang['Standard View'] = "Standard View";
$_lang['Standard View 1'] = "Standard View 1";
$_lang['Standard View 2'] = "Standard View 2";
$_lang['Own Schedule'] = "Own Schedule";
$_lang['Group Schedule'] = "Group Schedule";
$_lang['Group - Create Event'] = "Group - Create Event";
$_lang['Group, only representation'] = "Group, only representation";
$_lang['Holiday file'] = "Holiday file";

// summary
$_lang['Todays Events'] = "Eventos de Hoje";// changement
$_lang['New files'] = "Novos arquivos";// changement
$_lang['New notes'] = "Novas notas";// changement
$_lang['New Polls'] = "Novas votações";// changement
$_lang['Current projects'] = "Projetos atuais";// changement
$_lang['Help Desk Requests'] = "Helpdesk";// changement
$_lang['Current todos'] = "Tarefas atuais";// changement
$_lang['New forum postings'] = "Novos mensagens no fórum";// changement
$_lang['New Mails'] = "Novos e-mails";// changement

//timecard

$_lang['Theres an error in your time sheet: '] = "Há um erro na sua planilha de tempo! Por favor dê uma olhada no seu ponto eletrônico .";



                                                                                 
$_lang['Consistency check'] = "Checagem de Consistencia";
$_lang['Please enter the end afterwards at the'] = "Por favor, entre com o apontamento final no";
$_lang['insert'] = "insira";
$_lang['Enter records afterwards'] = "Digite seus Apontamentos";
$_lang['Please fill in only emtpy records'] = "Por favor, preencha apenas os campos em branco";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "insira um periodo, todos os registros do periodo serão atribuidos ao projeto";
$_lang['There is no record on this day'] = "Não há registros para este dia";
$_lang['This field is not empty. Please ask the administrator'] = "Este campo não está vazio, pergunte ao Administrador";
$_lang['There is no open record with a begin time on this day!'] = "As datas estão erradas! por favor verifique.";
$_lang['Please close the open record on this day first!'] = "Por favor insira a hora de inicio primeiro";
$_lang['Please check the given time'] = "Por favor verifique o horario fornecido";
$_lang['Assigning projects'] = "Delegando projetos";
$_lang['Select a day'] = "Selecione um dia";
$_lang['Copy to the boss'] = "Copia para o Supervisor";
$_lang['Change in the timecard'] = "<Muda no ponto Eletrônico";
$_lang['Sum for'] = "Soma para";

$_lang['Unassigned time'] = "tempo disponível";
$_lang['delete record of this day'] = "Apaga o registro deste dia";
$_lang['Bookings'] = "Marcações";// changement

$_lang['insert additional working time'] = "insert additional working time";
$_lang['Project assignment']= "Project assignment";
$_lang['Working time stop watch']= "Working time stop watch";
$_lang['stop watches']= "stop watches";
$_lang['Project stop watch']= "Project stop watch";
$_lang['Overview my working time']= "Overview my working time";
$_lang['GO']= "GO";
$_lang['Day view']= "Day view";
$_lang['Project view']= "Project view";
$_lang['Weekday']= "Weekday";
$_lang['Start']= "Start";
$_lang['Net time']= "Net time";
$_lang['Project bookings']= "Project bookings";
$_lang['save+close']= "save+close";
$_lang['Working times']= "Working times";
$_lang['Working times start']= "Working times start";
$_lang['Working times stop']= "Working times stop";
$_lang['Project booking start']= "Project booking start";
$_lang['Project booking stop']= "Project booking stop";
$_lang['choose day']= "choose day";
$_lang['choose month']= "choose month";
$_lang['1 day back']= "1 day back";
$_lang['1 day forward']= "1 day forward";
$_lang['Sum working time']= "Sum working time";
$_lang['Time: h / m']= "Time: h / m";
$_lang['activate project stop watch']= "activate project stop watch";
$_lang['activate']= "activate";
$_lang['project choice']= "project choice";
$_lang['stop stop watch']= "stop stop watch";
$_lang['still to allocate:']= "still to allocate:";
$_lang['You are not allowed to delete entries from timecard. Please contact your administrator']= "You are not allowed to delete entries from timecard. Please contact your administrator";
$_lang['You cannot delete entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "You cannot delete entries at this date. Since there have been %s days. You just can edit entries not older than %s days.";
$_lang['You cannot delete bookings at this date. Since there have been %s days. You just can edit bookings of entries not older than %s days.']= "You cannot delete bookings at this date. Since there have been %s days. You just can edit bookings of entries not older than %s days.";
$_lang['You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.";
$_lang['You cannot add  bookings at this date. Since there have been %s days. You just can add bookings for entries not older than %s days.']= "You cannot add  bookings at this date. Since there have been %s days. You just can add bookings for entries not older than %s days.";
$_lang['activate+close']="activate+close";

// todos
$_lang['accepted'] = "Aceitos";// changement
$_lang['rejected'] = "Rejeitados";// changement
$_lang['own'] = "pessoal";// changement
$_lang['progress'] = "progresso";// changement
$_lang['delegated to'] = "delegado a";// changement
$_lang['Assigned from'] = "Designado por ";// changement
$_lang['done'] = "feito";// changement
$_lang['Not yet assigned'] = "Não designado ainda";
$_lang['Undertake'] = "Encarregar";
$_lang['New todo'] = "Nova tarefa"; 
$_lang['Notify recipient'] = "Notificar recipiente";

// votum.php
$_lang['results of the vote: '] = "resultados da votação: ";
$_lang['Poll Question: '] = "questão para votação: ";
$_lang['several answers possible'] = "são possiveis várias respostas";
$_lang['Alternative '] = "Resposta ";
$_lang['no vote: '] = "sem voto: ";
$_lang['of'] = "dos";
$_lang['participants have voted in this poll'] = "participantes já votaram";
$_lang['Current Open Polls'] = "Votos abertos atuais";// changement
$_lang['Results of Polls'] = "Resultados da lista de todos os votos";// changement
$_lang['New survey'] ="New survey";
$_lang['Alternatives'] ="Alternatives";
$_lang['currently no open polls'] = "Currently there are no open polls";

// export_page.php
$_lang['export_timecard']       = "Export Timecard";
$_lang['export_timecard_admin'] = "Export Timecard";
$_lang['export_users']          = "Export users of this group";
$_lang['export_contacts']       = "Export contacts";
$_lang['export_projects']       = "Export projectdata";
$_lang['export_bookmarks']      = "Export bookmarks";
$_lang['export_timeproj']       = "Export time-to-project data";
$_lang['export_project_stat']   = "Export projectstats";
$_lang['export_todo']           = "Export todos";
$_lang['export_notes']          = "Export notes";
$_lang['export_calendar']       = "Export all calendarevents";
$_lang['export_calendar_detail']= "Export one calendarevent";
$_lang['submit'] = "submit";
$_lang['Address'] = "Address";
$_lang['Next Project'] = "Next Project";
$_lang['Dependend projects'] = "Dependend projects";
$_lang['db_type'] = "Database type";
$_lang['Log in, please'] = "Log in, please";
$_lang['Recipient'] = "Recipient";
$_lang['untreated'] = "untreated";
$_lang['Select participants'] = "Select participants";
$_lang['Participation'] = "Participation";
$_lang['not yet decided'] = "not yet decided";
$_lang['accept'] = "accept";
$_lang['reject'] = "reject";
$_lang['Substitute for'] = "Substitute for";
$_lang['Calendar user'] = "Kalenderbenutzer";
$_lang['Refresh'] = "Refresh";
$_lang['Event'] = "Event";
$_lang['Upload file size is too big'] = "Upload file size is too big";
$_lang['Upload has been interrupted'] = "Upload has been interrupted";
$_lang['view'] = "view";
$_lang['found elements'] = "found elements";
$_lang['chosen elements'] = "chosen elements";
$_lang['too many hits'] = "The result is bigger than we're able to display.";
$_lang['please extend filter'] = "Please extend your filters.";
$_lang['Edit profile'] = "Edit profile";
$_lang['add profile'] = "add profile";
$_lang['Add profile'] = "Add profile";
$_lang['Added profile'] = "Added profile(s).";
$_lang['No profile found'] = "No profile found.";
$_lang['add project participants'] = "add project participants";
$_lang['Added project participants'] = "Added project participants.";
$_lang['add group of participants'] = "add group of participants";
$_lang['Added group of participants'] = "Added group of participants.";
$_lang['add user'] = "add user";
$_lang['Added users'] = "Added user(s).";
$_lang['Selection'] = "Selection";
$_lang['selector'] = "selector";
$_lang['Send email notification']= "Send&nbsp;email&nbsp;notification";
$_lang['Member selection'] = "Member&nbsp;selection";
$_lang['Collision check'] = "Collision check";
$_lang['Collision'] = "Collision";
$_lang['Users, who can represent me'] = "Users, who can represent me";
$_lang['Users, who can see my private events'] = "Users, who can see<br />my private events";
$_lang['Users, who can read my normal events'] = "Users, who can read<br />my normal events";
$_lang['quickadd'] = "Quickadd";
$_lang['set filter'] = "Set filter";
$_lang['Select date'] = "Select date";
$_lang['Next serial events'] = "Next serial events";
$_lang['All day event'] = "All day event";
$_lang['Event is canceled'] = "Event&nbsp;is&nbsp;canceled";
$_lang['Please enter a password!'] = "Please enter a password!";
$_lang['You are not allowed to create an event!'] = "You are not allowed to create an event!";
$_lang['Event successfully created.'] = "Event successfully created.";
$_lang['You are not allowed to edit this event!'] = "You are not allowed to edit this event!";
$_lang['Event successfully updated.'] = "Event successfully updated.";
$_lang['You are not allowed to remove this event!'] = "You are not allowed to remove this event!";
$_lang['Event successfully removed.'] = "Event successfully removed.";
$_lang['Please give a text!'] = "Please give a text!";
$_lang['Please check the event date!'] = "Please check the event date!";
$_lang['Please check your time format!'] = "Please check your time format!";
$_lang['Please check start and end time!'] = "Please check start and end time!";
$_lang['Please check the serial event date!'] = "Please check the serial event date!";
$_lang['The serial event data has no result!'] = "The serial event data has no result!";
$_lang['Really delete this event?'] = "Really delete this event?";
$_lang['use'] = "Use";
$_lang[':'] = ":";
$_lang['Mobile Phone'] = "Mobile Phone";
$_lang['submit'] = "Submit";
$_lang['Further events'] = "Weitere Termine";
$_lang['Remove settings only'] = "Remove settings only";
$_lang['Settings removed.'] = "Settings removed.";
$_lang['User selection'] = "User selection";
$_lang['Release'] = "Release";
$_lang['none'] = "none";
$_lang['only read access to selection'] = "only write access to selection";
$_lang['read and write access to selection'] = "read and write access to selection";
$_lang['Available time'] = "Available time";
$_lang['flat view'] = "List View";
$_lang['o_dateien'] = "Filemanager";
$_lang['Location'] = "Location";
$_lang['date_received'] = "date_received";
$_lang['subject'] = "Subject";
$_lang['kat'] = "Category";
$_lang['projekt'] = "Project";
$_lang['Location'] = "Location";
$_lang['name'] = "Titel";
$_lang['contact'] = "Kontakt";
$_lang['div1'] = "Erstellung";
$_lang['div2'] = "Änderung";
$_lang['kategorie'] = "Kategorie";
$_lang['anfang'] = "Beginn";
$_lang['ende'] = "Ende";
$_lang['status'] = "Status";
$_lang['filename'] = "Titel";
$_lang['deadline'] = "Termin";
$_lang['ext'] = "an";
$_lang['priority'] = "Priorität";
$_lang['project'] = "Projekt";
$_lang['Accept'] = "Übernehmen";
$_lang['Please enter your user name here.'] = "Please enter your user name here.";
$_lang['Please enter your password here.'] = "Please enter your password here.";
$_lang['Click here to login.'] = "Click here to login.";
$_lang['No New Polls'] = "No New Polls";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Hide read elements";
$_lang['&nbsp;Show read elements'] = "&nbsp;Show read elements";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Hide archive elements";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Show archive elements";
?>