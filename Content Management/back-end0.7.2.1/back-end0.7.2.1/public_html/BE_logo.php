<?php
global $_BE,$_PSL;

$pageTitle   = 'Logos';
$xsiteobject = 'Logo Page';  #Defines The META TAG Page Type

require('./config.php');

if(!empty($_GET['login'])) {
  $auth->login_if(true);
}

/****************
* INITIALISATION
*****************/
$ary['section'] = 'Services';

// Required to clean the QUERY_STRING field in the template
$ary['query'] = clean($_GET['query']);
$ary['min']   = clean($_GET['min']);

/****************
* CONSTRUCT PAGE
*****************/

$content = '';

$localNameField = 'localName';
$contactNameField = 'contactName';
$contactEmailField = 'contactEmail';
$languageRequestedField = 'languageRequested[]';
$fileFormatField = 'fileFormat';
$programUsedField = 'programUsed';
$commentsField = 'comments';
$submitVariable = 'action';
$submitValue = pslgettext('Request Logos');

global $languageRequested;

if ($_POST[$submitVariable] == $submitValue) {
   $template = pslNew('slashTemplate',$this->psl['templatedir'],'remove');
   $template->set_file(array('logoThanks' => 'BE_logoThanks.tpl'));
   $content = $template->parse('OUT', 'logoThanks', TRUE);

   $submittedLocalName = clean($_POST[$localNameField]);
   $submittedContactName = clean($_POST[$contactNameField]);
   $submittedContactEmail = clean($_POST[$contactEmailField]);
   // $submittedLanguageRequested = $_POST[$languageRequestedField];
   $submittedLanguageRequested = implode(', ', clean($_POST['languageRequested']));
   $submittedFileFormat = clean($_POST[$fileFormatField]);
   $submittedProgramUsed = clean($_POST[$programUsedField]);
   $submittedComments = clean($_POST[$commentsField]);

   $msg = "Logo Request From: $submittedContactName ($submittedContactEmail)\n";
   $msg .= "Request For Local: $submittedLocalName\n";
   $msg .= "Languages Requested: $submittedLanguageRequested\n";
   $msg .= "File Format: $submittedFileFormat\n";
   $msg .= "Program To Be Used: $submittedProgramUsed\n";
   $msg .= "Additional Comments: $submittedComments\n";

   $headers = "From: " . clean($_POST[$contactNameField]) . " <" . clean($_POST[$contactEmailField]) . ">\r\n";
   $headers = "Reply-To: " . clean($_POST[$contactNameField]) . " <" . clean($_POST[$contactEmailField]) . ">\r\n";

   //  Send ian a message saying that feedback was sent.
   mail('jturmel@cupe.ca','Logo Request',$msg,$headers);

} else {
   $template=pslNew('slashTemplate',$this->psl['templatedir'],"remove");
   $template->set_file(array("logo" => "BE_logo.tpl"));

   $startLogoForm = '<form name="logo" action="' . $_SERVER['PHP_SELF'] . '" method="post">';
   $localNameFieldInput = '<input name="' . $localNameField . '" type="text">';
   $contactNameFieldInput = '<input name="' . $contactNameField . '" type="text">';
   $contactEmailFieldInput = '<input name="' . $contactEmailField . '" type="text">';
   $programUsedFieldInput = '<input name="' . $programUsedField . '" type="text">';
   $fileFormatFieldInput = '<select name="' . $fileFormatField . '"> <option>JPEG</option> <option>TIFF</option> <option>EPS</option> </select>';
   $englishLanguageInput = "<input type='checkbox' name='$languageRequestedField' value='English'>";
   $frenchLanguageInput = "<input type='checkbox' name='$languageRequestedField' value='French'>";
   $bilingualLanguageInput = "<input type='checkbox' name='$languageRequestedField' value='Bilingual'>";
   $commentsFieldInput = "<textarea name='$commentsField' cols=20 style='width:100%;height:90px;'></textarea>";
   $submitInput = "<input type='submit' value='$submitValue' name='$submitVariable'>";
   $endLogoForm = '</form>';

   $template->set_var(array(
      'START_LOGO_FORM'           =>  $startLogoForm,
      'LOCAL_NAME_INPUT'          =>  $localNameFieldInput,
      'CONTACT_NAME_INPUT'        =>  $contactNameFieldInput,
      'CONTACT_EMAIL_INPUT'       =>  $contactEmailFieldInput,
      'PROGRAM_USED_INPUT'        =>  $programUsedFieldInput,
      'FILE_FORMAT_INPUT'         =>  $fileFormatFieldInput,
      'ENGLISH_LANGUAGE_INPUT'    =>  $englishLanguageInput,
      'FRENCH_LANGUAGE_INPUT'     =>  $frenchLanguageInput,
      'BILINGUAL_LANGUAGE_INPUT'  =>  $bilingualLanguageInput,
      'COMMENTS_INPUT'            =>  $commentsFieldInput,
      'SUBMIT_INPUT'              =>  $submitInput,
      'END_LOGO_FORM'             =>  $endLogoForm
   ));

   $content = $template->parse('OUT', 'logo', TRUE);
}

// If templates are defined, checks if they exist and formats them correctly

$sectionObj = pslNew('BE_Section');
$breadcrumb = $sectionObj->breadcrumb($ary['section']);
$sectionRec = $sectionObj->extractSection($ary['section']);
$sectionTemplate = $sectionRec['template'];
$chosenTemplate = getUserTemplates('',$sectionTemplate);

// render the standard header
$_PSL['metatags']['object'] = $xsiteobject;

$_BE['currentSection']        = $ary['section'];

// generate the page
generatePage($ary, $pageTitle, $breadcrumb, $content);

page_close();

?>
