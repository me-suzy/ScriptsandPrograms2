<?php
//Include Common Files @1-5471E0F2
define("RelativePath", ".");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @2-DC989187
include(RelativePath . "/Header.php");
//End Include Page implementation

//Include Page implementation @3-B991DFB8
include(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-BCB7B4AC
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "alumni_admin.php";
$Redirect = "";
$TemplateFileName = "alumni_admin.html";
$BlockToParse = "main";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-45814D90
CCSecurityRedirect("2", "", $FileName, CCGetQueryString("QueryString", ""));
//End Authenticate User

//Initialize Objects @1-FECE197A

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$Marital_Status = new clsControl(ccsLink, "Marital_Status", "Marital_Status", ccsText, "", CCGetRequestParam("Marital_Status", ccsGet));
$Marital_Status->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
$Marital_Status->Page = "mstatus_admin.php";
$States = new clsControl(ccsLink, "States", "States", ccsText, "", CCGetRequestParam("States", ccsGet));
$States->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
$States->Page = "states_admin.php";
$Logout = new clsControl(ccsLink, "Logout", "Logout", ccsText, "", CCGetRequestParam("Logout", ccsGet));
$Logout->Parameters = CCGetQueryString("QueryString", Array("RecordNum", "ccsForm"));
$Logout->Parameters = CCAddParam($Logout->Parameters, "Logout", "True");
$Logout->Page = "alumni_list.php";
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();

// Events
include("./alumni_admin_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Execute Components @1-351F985C
$Header->Operations();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-BEB91355
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Show Page @1-523271B5
$Header->Show("Header");
$Marital_Status->Show();
$States->Show();
$Logout->Show();
$Footer->Show("Footer");
$Tpl->PParse("main", false);
//End Show Page

//Unload Page @1-AB7622EF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
unset($Tpl);
//End Unload Page


?>
