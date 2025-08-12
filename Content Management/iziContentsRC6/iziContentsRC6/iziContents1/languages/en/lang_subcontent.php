<?php

//  Form Titles
$GLOBALS["tFormTitle"] = 'Maintain submitted content';
$GLOBALS["tFormTitle2"] = 'Maintain submitted content configuration';
$GLOBALS["tFormTitle3"] = 'Maintain submitted content categories';

//  List Headings
$GLOBALS["tEdit"] = 'Edit';
$GLOBALS["tAddNew"] = 'Add new module';
$GLOBALS["tAddNewCat"] = 'Add new category';
$GLOBALS["tModuleName"] = 'Module name';
$GLOBALS["tModuleTitle"] = 'Module title';
$GLOBALS["tAnonPosting"] = 'Anonymous Posting Allowed';
$GLOBALS["tValReq"] = 'Validation Required';
$GLOBALS["tTablename"] = 'Table name';
$GLOBALS["tCategory"] = 'Category';
$GLOBALS["tCatReference"] = 'Internal Reference';

//  List Functions
$GLOBALS["tConfig"] = 'Configuration';
$GLOBALS["tCategories"] = 'Categories';

//  Form Block Titles
$GLOBALS["thGeneral"] = 'Submitted Content Configuration';
$GLOBALS["thSubmissions"] = 'Submission Details';
$GLOBALS["thSubmissionRules"] = 'Submission Rules';
$GLOBALS["thDisplay"] = 'Display Settings';

//  Form Field Headings
$GLOBALS["tModuleID"] = 'Module ID';
$GLOBALS["tCatParent"] = 'Parent Category';
$GLOBALS["tSText"] = 'Submission Text';
$GLOBALS["tSTextDisplay"] = 'Display Submission Text';
$GLOBALS["tSGraphic"] = 'Submission Graphic';
$GLOBALS["tSGraphicDisplay"] = 'Display Submission Graphic';
$GLOBALS["tRegOnly"] = 'Registered Users Only';
$GLOBALS["tUsergroups"] = 'Usergroups';
$GLOBALS["tValReg"] = 'Validation Required';
$GLOBALS["tUseCategories"] = 'Use Sub-categories';
$GLOBALS["tPerPage"] = 'Per Page';
$GLOBALS["tCatName"] = 'Category Name';
$GLOBALS["tHiddenCat"] = 'Hidden Category';
$GLOBALS["tOrderBy"] = 'Order By';
$GLOBALS["tPostedBy"] = 'Show Posted By';
$GLOBALS["tPostedDate"] = 'Show Posted Date';

// Toggel Modules
$GLOBALS["tConfirmRelease"] = 'activate / deactivate module';
$GLOBALS["tReleaseModule"] = 'activate / deactivate module';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'Use this form to configure user submissions to add-in special content modules.';
$GLOBALS["hModuleName"] = 'The module name, a tag used to identify it internally.';
$GLOBALS["hModuleTitle"] = 'The module title is displayed on the main display page for this type of content.';
$GLOBALS["hSText"] = 'Text to display in header for user submissions.<br>This value will only be used if there is no language-specific text defined in the language files.';
$GLOBALS["hSTextDisplay"] = 'Should submission text (either from the language files, or the \'Display Submission Text\' field on this form) be displayed in header';
$GLOBALS["hSGraphic"] = 'Graphic image to display in header for user submissions';
$GLOBALS["hSGraphicDisplay"] = 'Should submission graphic be displayed in header';
$GLOBALS["hRegOnly"] = 'Can only registered authors/users submit entries? or can any user?';
$GLOBALS["hUsergroups"] = 'Registered users with privilege to submit entries is restricted to the highlighted usergroups.<br />Leave blank to allow any member to submit.';
$GLOBALS["hValReq"] = 'Indicate whether validation is required before submitted entries are released to display, i.e. whether they are pending an administrator release.';
$GLOBALS["hPerPage"] = 'The number of entries to show per page.';
$GLOBALS["hUseCategories"] = 'If this flag is checked, user-submitted articles will be associated with a series of sub-categories that the site administrator can define.';
$GLOBALS["hOrderBy"] = 'Order entries by date or by category/date (if categories are enabled).';
$GLOBALS["hPostedBy"] = 'Show the Username of whoever posted this entry';
$GLOBALS["hPostedDate"] = 'Show the date/time when this entry was posted';
$GLOBALS["hCatName"] = 'Enter a text description for this Category';
$GLOBALS["hCatParent"] = 'Select a \'parent category\' from the list if you want this category to be a \'child category\'.';
$GLOBALS["hHiddenCat"] = 'Set this flag to "Yes" if you want this Category (and all its children) to be hidden in the user\'s drop-down list of categories';

$GLOBALS["toCategory"] = 'Category/Date';
$GLOBALS["toDate"] = 'Date';

//  Error messages
$GLOBALS["eNoModules"] = 'There are no modules for you to install';

$GLOBALS["ePerPageNumber"] = 'Per Page value must be a number';
$GLOBALS["ePerPageZero"] = 'Per Page number must be greater than 0';

?>
