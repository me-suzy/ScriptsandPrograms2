<?php



new PageHandle(array('id'=>'home',
    'req'=>'pages/home.inc',
    'title'=>'Home'
));

/**
* project
*/
new PageHandle(array('id'=>'projList',
    'req'=>'pages/proj.inc',
    'title'=>'Active Projects'
));
new PageHandle(array('id'=>'projListClosed',
    'req'=>'pages/proj.inc',
    'title'=>'Closed Projects'
));
new PageHandle(array('id'=>'projListTemplates',
    'req'=>'pages/proj.inc',
    'title'=>'Project Templates',
    'rights_required'=>RIGHT_PROJECT_CREATE,
));

new PageHandle(array('id'=>'projView',
    'req'=>'pages/proj.inc',
    'title'=>'View Project',
));
new PageHandle(array('id'=>'projViewEfforts',
    'req'=>'pages/proj.inc',
    'title'=>'View Project',
));
new PageHandle(array('id'=>'projViewChanges',
    'req'=>'pages/proj.inc',
    'title'=>'View Project',
));
new PageHandle(array('id'=>'projViewTasksClosed',
    'req'=>'pages/proj.inc',
    'title'=>'Closed tasks',
));
new PageHandleFunc(array('id'=>'projNew',
    'req'=>'pages/proj.inc',
    'title'=>'New Project',
    'rights_required'=>RIGHT_PROJECT_CREATE,
));
new PageHandleFunc(array('id'=>'projDuplicate',
    'req'=>'pages/proj.inc',
    'title'=>'Duplicate Project',
    'rights_required'=>RIGHT_PROJECT_CREATE,
));

new PageHandleForm(array('id'=>'projEdit',
    'req'=>'pages/proj.inc',
    'title'=>'Edit Project',
    'rights_required'=>RIGHT_PROJECT_EDIT,
));
new PageHandleSubm(array('id'=>'projEditSubmit',
    'req'=>'pages/proj.inc',
    'rights_required'=>RIGHT_PROJECT_EDIT,
));
new PageHandleFunc(array('id'=>'projDelete',
    'req'=>'pages/proj.inc',
    'title'=>'Delete Project',
    'rights_required'=>RIGHT_PROJECT_DELETE,
));
new PageHandleFunc(array('id'=>'projChangeStatus',
    'req'=>'pages/proj.inc',
    'title'=>'Change Project Status',
    'rights_required'=>RIGHT_PROJECT_EDIT,
));
new PageHandle    (array('id'=>'projAddPerson',
    'req'=>'pages/proj.inc',
    'title'=>'Add Team Member',
    'rights_required'=>RIGHT_PROJECT_EDIT,
));
new PageHandleSubm(array('id'=>'projAddPersonSubmit',
    'req'=>'pages/proj.inc',
    'rights_required'=>RIGHT_PROJECT_EDIT,
));
new PageHandle(array('id'=>'projViewIssues',
    'req'=>'pages/proj.inc',
));

/**
* team-members aka projectperson
*/
new PageHandleFunc(array('id'=>'projectPersonNew',
    'req'       =>'pages/projectperson.inc',
    'title'     =>'Add Team member',
    'rights_required'=>RIGHT_PROJECT_ASSIGN,
));
new PageHandleForm(array('id'=>'projectPersonEdit',
    'req'       =>'pages/projectperson.inc',
    'title'     =>'Edit Team member',
    'rights_required'=>RIGHT_PROJECT_ASSIGN,
));
new PageHandleSubm(array('id'=>'projectPersonEditSubmit',
    'req'       =>'pages/projectperson.inc',
    'rights_required'=>RIGHT_PROJECT_ASSIGN,
));
new PageHandleFunc(array('id'=>'projectPersonDelete',
    'req'       =>'pages/projectperson.inc',
    'title'     =>'Remove from team',
    'rights_required'=>RIGHT_PROJECT_ASSIGN,
));


/**
* task
*/
new PageHandle(array('id'=>'taskView',
    'req'=>'pages/task.inc',
    'title'=>'View Task',
));
new PageHandleForm(array('id'=>'taskEdit',
    'req'=>'pages/task.inc',
    'title'=>'Edit Task',
));
new PageHandleFunc(array('id'=>'tasksDelete',
    'req'=>'pages/task.inc',
    'title'=>'Delete Task(s)',
));
new PageHandleFunc(array('id'=>'tasksUndelete',
    'req'=>'pages/task.inc',
    'title'=>'Restore Task(s)',
));

new PageHandleSubm(array('id'=>'taskEditSubmit',
    'req'=>'pages/task.inc',
));
new PageHandleFunc(array('id'=>'tasksMoveToFolder',
    'req'=>'pages/task.inc',
    'title'=>'Move tasks to folder',
));
new PageHandleFunc(array('id'=>'tasksComplete',
    'req'=>'pages/task.inc',
    'title'=>'Mark tasks as Complete',
));
new PageHandleFunc(array('id'=>'tasksApproved',
    'req'=>'pages/task.inc',
    'title'=>'Mark tasks as Approved',
));
new PageHandleFunc(array('id'=>'taskNew',
    'req'=>'pages/task.inc',
    'title'=>'New Task',
));
new PageHandleFunc(array('id'=>'taskNewFolder',
    'req'=>'pages/task.inc',
    'title'=>'New Task',
));

new PageHandleFunc(array('id'=>'effortNew',
    'req'=>'pages/effort.inc',
    'title'=>'Log hours',
));
new PageHandleForm(array('id'=>'effortEdit',
    'req'=>'pages/effort.inc',
    'title'=>'Edit time effort',
));
new PageHandleSubm(array('id'=>'effortEditSubmit',
    'req'=>'pages/effort.inc',
));
new PageHandleFunc(array('id'=>'effortDelete',
    'req'=>'pages/effort.inc',
));

new PageHandleFunc(array('id'=>'commentNew',
    'req'=>'pages/comment.inc',
    'title'=>'Create comment',
));
new PageHandleForm(array('id'=>'commentEdit',
    'req'=>'pages/comment.inc',
    'title'=>'Edit comment',
));
new PageHandleSubm(array('id'=>'commentEditSubmit',
    'req'=>'pages/comment.inc',
));
new PageHandleFunc(array('id'=>'commentsDelete',
    'req'=>'pages/comment.inc',
    'title'=>'Delete comment',
));
new PageHandleFunc(array('id'=>'commentsMoveToFolder',
    'req'=>'pages/comment.inc',
    'title'=>'Delete comment',
));

new PageHandleFunc(array('id'=>'taskAddIssueReport',
    'req'=>'pages/task.inc',
    'title'=>'Add issue/bug report',
));


/**
* company
*/
new PageHandle(array('id'=>'companyList',
    'req'=>'pages/company.inc',
    'title'=>'List Companies',
));
new PageHandle(array('id'=>'companyView',
    'req'=>'pages/company.inc',
    'title'=>'View Companies',
));
new PageHandleFunc(array('id'=>'companyNew',
    'req'=>'pages/company.inc',
    'title'=>'New Company',
    'rights_required'=>RIGHT_COMPANY_CREATE,
));
new PageHandleForm(array('id'=>'companyEdit',
    'req'=>'pages/company.inc',
    'title'=>'Edit Company',
    'rights_required'=>RIGHT_COMPANY_EDIT,
));
new PageHandleSubm(array('id'=>'companyEditSubmit',
    'req'=>'pages/company.inc',
    'rights_required'=>RIGHT_COMPANY_EDIT,
));
new PageHandleFunc(array('id'=>'companyDelete',
    'req'=>'pages/company.inc',
    'title'=>'Delete Company',
    'rights_required'=>RIGHT_COMPANY_DELETE,
));
new PageHandle(array('id'=>'companyLinkPersons',
    'req'=>'pages/company.inc',
    'title'=>'Link Persons',
    'rights_required'=>RIGHT_COMPANY_EDIT,
));
new PageHandleSubm(array('id'=>'companyLinkPersonsSubmit',
    'req'=>'pages/company.inc',
    'rights_required'=>RIGHT_COMPANY_EDIT,
));

/**
* person
*/
new PageHandle(array('id'=>'personList',
    'req'=>'pages/person.inc',
    'title'=>'List Persons',
));
new PageHandle(array('id'=>'personListAccounts',
    'req'=>'pages/person.inc',
    'title'=>'List Persons',
));
new PageHandle(array('id'=>'personView',
    'req'=>'pages/person.inc',
    'title'=>'View Person',
));
new PageHandleFunc(array('id'=>'personNew',
    'req'=>'pages/person.inc',
    'title'=>'New Person',
    'rights_required'=>RIGHT_PERSON_CREATE,
));
new PageHandleForm(array('id'=>'personEdit',
    'req'=>'pages/person.inc',
    'title'=>'Edit Person',
    'rights_required'=>RIGHT_PERSON_EDIT_SELF,
));
new PageHandleSubm(array('id'=>'personEditSubmit',
    'req'=>'pages/person.inc',
    'rights_required'=>RIGHT_PERSON_EDIT_SELF,
));
new PageHandleForm(array('id'=>'personEditRights',
    'rights_required'=>RIGHT_PERSON_EDIT_RIGHTS,
    'req'=>'pages/person.inc',
    'title'=>'Edit User Rights',
));
new PageHandleSubm(array('id'=>'personEditRightsSubmit',
    'rights_required'=>RIGHT_PERSON_EDIT_RIGHTS,
    'req'=>'pages/person.inc',
));

new PageHandleFunc(array('id'=>'personDelete',
    'req'=>'pages/person.inc',
    'title'=>'Delete Person',
    'rights_required'=>RIGHT_PERSON_DELETE,
));
new PageHandle(array('id'=>'personViewEfforts',
    'req'=>'pages/person.inc',
    'title'=>'View Efforts of Person',
));

/**
* quick new
*/
new PageHandleFunc(array('id'=>'quickNew',
    'req'=>'pages/quicknew.inc',
));


/**
* login
*/
new PageHandle(array('id'=>'loginForm',
    'req'=>'pages/login.inc',
    'title'=>'Login',
    'valid_for_anonymous'=>true,
    'ignore_from_handles'=>true,
));
new PageHandleSubm(array('id'=>'loginFormSubmit',
    'req'=>'pages/login.inc',
    'valid_for_anonymous'=>true,
));
new PageHandleFunc(array('id'=>'logout',
    'req'=>'pages/login.inc',
    'title'=>'Logout',
));
new PageHandleFunc(array('id'=>'helpLicense',
    'req'=>'pages/login.inc',
    'title'=>'License',
    'valid_for_anonymous'=>true,
));

/**
* misc
*/
new PageHandleFunc(array('id'=>'changeSort',
    'req'=>'pages/misc.inc'
));
new PageHandleFunc(array('id'=>'changeListStyle',
    'req'=>'pages/misc.inc'
));


new PageHandle(array('id'=>'error',
    'req'=>'pages/error.inc',
    'title'=>'Error',
    'valid_for_anonymous'=>true,    # without this PH->show() could be trapped in endless loop will crash php-cgi!
    'ignore_from_handles'=>true,
));


?>
