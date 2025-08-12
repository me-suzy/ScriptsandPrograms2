<?php
//Include Common Files @1-5471E0F2
define("RelativePath", ".");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @22-DC989187
include(RelativePath . "/Header.php");
//End Include Page implementation

Class clsRecordalm_alumni { //alm_alumni Class @2-749F602E

//Variables @2-90DA4C9A

    // Public variables
    var $ComponentName;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $FormSubmitted;
    var $Visible;
    var $Recordset;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $ds;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @2-E1C6E319
    function clsRecordalm_alumni()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ds = new clsalm_alumniDataSource();
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "alm_alumni";
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $this->ComponentName);
            $CCSForm = CCGetFromGet("ccsForm", "");
            $this->FormSubmitted = ($CCSForm == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->YearGrad = new clsControl(ccsLabel, "YearGrad", "Year Grad", ccsText, "", CCGetRequestParam("YearGrad", $Method));
            $this->FirstName = new clsControl(ccsLabel, "FirstName", "First Name", ccsText, "", CCGetRequestParam("FirstName", $Method));
            $this->LastName = new clsControl(ccsLabel, "LastName", "Last Name", ccsText, "", CCGetRequestParam("LastName", $Method));
            $this->MaidenName = new clsControl(ccsLabel, "MaidenName", "Maiden Name", ccsText, "", CCGetRequestParam("MaidenName", $Method));
            $this->MaritalStatus = new clsControl(ccsLabel, "MaritalStatus", "Marital Status", ccsText, "", CCGetRequestParam("MaritalStatus", $Method));
            $this->Spouse = new clsControl(ccsLabel, "Spouse", "Spouse", ccsText, "", CCGetRequestParam("Spouse", $Method));
            $this->Children = new clsControl(ccsLabel, "Children", "Children", ccsText, "", CCGetRequestParam("Children", $Method));
            $this->HomeAddress = new clsControl(ccsLabel, "HomeAddress", "Home Address", ccsText, "", CCGetRequestParam("HomeAddress", $Method));
            $this->HomeCity = new clsControl(ccsLabel, "HomeCity", "Home City", ccsText, "", CCGetRequestParam("HomeCity", $Method));
            $this->HomeState = new clsControl(ccsLabel, "HomeState", "Home State", ccsText, "", CCGetRequestParam("HomeState", $Method));
            $this->Phone = new clsControl(ccsLabel, "Phone", "Phone", ccsText, "", CCGetRequestParam("Phone", $Method));
            $this->Email = new clsControl(ccsLink, "Email", "Email", ccsText, "", CCGetRequestParam("Email", $Method));
            $this->Email->HTML = true;
            $this->Website = new clsControl(ccsLink, "Website", "Website", ccsText, "", CCGetRequestParam("Website", $Method));
            $this->Website->HTML = true;
            $this->Profession = new clsControl(ccsLabel, "Profession", "Profession", ccsText, "", CCGetRequestParam("Profession", $Method));
            $this->Employed = new clsControl(ccsLabel, "Employed", "Employed", ccsText, "", CCGetRequestParam("Employed", $Method));
            $this->About = new clsControl(ccsLabel, "About", "About", ccsMemo, "", CCGetRequestParam("About", $Method));
            $this->Picture = new clsControl(ccsImage, "Picture", "Picture", ccsMemo, "", CCGetRequestParam("Picture", $Method));
        }
    }
//End Class_Initialize Event

//Initialize Method @2-D03EE6CD
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlRecordNum"] = CCGetFromGet("RecordNum", "");
    }
//End Initialize Method

//Validate Method @2-D253048B
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->YearGrad->Validate() && $Validation);
        $Validation = ($this->FirstName->Validate() && $Validation);
        $Validation = ($this->LastName->Validate() && $Validation);
        $Validation = ($this->MaidenName->Validate() && $Validation);
        $Validation = ($this->MaritalStatus->Validate() && $Validation);
        $Validation = ($this->Spouse->Validate() && $Validation);
        $Validation = ($this->Children->Validate() && $Validation);
        $Validation = ($this->HomeAddress->Validate() && $Validation);
        $Validation = ($this->HomeCity->Validate() && $Validation);
        $Validation = ($this->HomeState->Validate() && $Validation);
        $Validation = ($this->Phone->Validate() && $Validation);
        $Validation = ($this->Email->Validate() && $Validation);
        $Validation = ($this->Website->Validate() && $Validation);
        $Validation = ($this->Profession->Validate() && $Validation);
        $Validation = ($this->Employed->Validate() && $Validation);
        $Validation = ($this->About->Validate() && $Validation);
        $Validation = ($this->Picture->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//Operation Method @2-2402FC57
    function Operation()
    {
        global $Redirect;

        $this->ds->Prepare();
        $this->EditMode = $this->ds->AllParametersSet;
        if(!($this->Visible && $this->FormSubmitted))
            return;

        $Redirect = "alumni_view.php?" . CCGetQueryString("QueryString", Array("ccsForm"));
    }
//End Operation Method

//Show Method @2-4FD8489A
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->ds->open();
        $RecordBlock = "Record " . $this->ComponentName;
        $Tpl->block_path = $RecordBlock;
        if($this->EditMode)
        {
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record alm_alumni";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    $this->YearGrad->SetValue($this->ds->YearGrad->GetValue());
                    $this->FirstName->SetValue($this->ds->FirstName->GetValue());
                    $this->LastName->SetValue($this->ds->LastName->GetValue());
                    $this->MaidenName->SetValue($this->ds->MaidenName->GetValue());
                    $this->MaritalStatus->SetValue($this->ds->MaritalStatus->GetValue());
                    $this->Spouse->SetValue($this->ds->Spouse->GetValue());
                    $this->Children->SetValue($this->ds->Children->GetValue());
                    $this->HomeAddress->SetValue($this->ds->HomeAddress->GetValue());
                    $this->HomeCity->SetValue($this->ds->HomeCity->GetValue());
                    $this->HomeState->SetValue($this->ds->HomeState->GetValue());
                    $this->Phone->SetValue($this->ds->Phone->GetValue());
                    $this->Email->SetValue($this->ds->Email->GetValue());
                    $this->Email->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->Email->Page = $this->ds->f("EmailAddy");
                    $this->Website->SetValue($this->ds->Website->GetValue());
                    $this->Website->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->Website->Page = $this->ds->f("Website");
                    $this->Profession->SetValue($this->ds->Profession->GetValue());
                    $this->Employed->SetValue($this->ds->Employed->GetValue());
                    $this->About->SetValue($this->ds->About->GetValue());
                    $this->Picture->SetValue($this->ds->Picture->GetValue());
                    if(!$this->FormSubmitted)
                    {
                    }
                }
                else
                {
                    $this->EditMode = false;
                }
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");

        if($this->FormSubmitted) {
            $Error .= $this->YearGrad->Errors->ToString();
            $Error .= $this->FirstName->Errors->ToString();
            $Error .= $this->LastName->Errors->ToString();
            $Error .= $this->MaidenName->Errors->ToString();
            $Error .= $this->MaritalStatus->Errors->ToString();
            $Error .= $this->Spouse->Errors->ToString();
            $Error .= $this->Children->Errors->ToString();
            $Error .= $this->HomeAddress->Errors->ToString();
            $Error .= $this->HomeCity->Errors->ToString();
            $Error .= $this->HomeState->Errors->ToString();
            $Error .= $this->Phone->Errors->ToString();
            $Error .= $this->Email->Errors->ToString();
            $Error .= $this->Website->Errors->ToString();
            $Error .= $this->Profession->Errors->ToString();
            $Error .= $this->Employed->Errors->ToString();
            $Error .= $this->About->Errors->ToString();
            $Error .= $this->Picture->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $this->YearGrad->Show();
        $this->FirstName->Show();
        $this->LastName->Show();
        $this->MaidenName->Show();
        $this->MaritalStatus->Show();
        $this->Spouse->Show();
        $this->Children->Show();
        $this->HomeAddress->Show();
        $this->HomeCity->Show();
        $this->HomeState->Show();
        $this->Phone->Show();
        $this->Email->Show();
        $this->Website->Show();
        $this->Profession->Show();
        $this->Employed->Show();
        $this->About->Show();
        $this->Picture->Show();
        $Tpl->parse("", false);
        $Tpl->block_path = "";
    }
//End Show Method

} //End alm_alumni Class @2-FCB6E20C

class clsalm_alumniDataSource extends clsDBAlumni {  //alm_alumniDataSource Class @2-E39EB4D6

//Variables @2-3EFC03F9
    var $CCSEvents = "";
    var $CCSEventResult;

    var $wp;
    var $AllParametersSet;

    // Datasource fields
    var $YearGrad;
    var $FirstName;
    var $LastName;
    var $MaidenName;
    var $MaritalStatus;
    var $Spouse;
    var $Children;
    var $HomeAddress;
    var $HomeCity;
    var $HomeState;
    var $Phone;
    var $Email;
    var $Website;
    var $Profession;
    var $Employed;
    var $About;
    var $Picture;
//End Variables

//Class_Initialize Event @2-E9099086
    function clsalm_alumniDataSource()
    {
        $this->Initialize();
        $this->YearGrad = new clsField("YearGrad", ccsText, "");
        $this->FirstName = new clsField("FirstName", ccsText, "");
        $this->LastName = new clsField("LastName", ccsText, "");
        $this->MaidenName = new clsField("MaidenName", ccsText, "");
        $this->MaritalStatus = new clsField("MaritalStatus", ccsText, "");
        $this->Spouse = new clsField("Spouse", ccsText, "");
        $this->Children = new clsField("Children", ccsText, "");
        $this->HomeAddress = new clsField("HomeAddress", ccsText, "");
        $this->HomeCity = new clsField("HomeCity", ccsText, "");
        $this->HomeState = new clsField("HomeState", ccsText, "");
        $this->Phone = new clsField("Phone", ccsText, "");
        $this->Email = new clsField("Email", ccsText, "");
        $this->Website = new clsField("Website", ccsText, "");
        $this->Profession = new clsField("Profession", ccsText, "");
        $this->Employed = new clsField("Employed", ccsText, "");
        $this->About = new clsField("About", ccsMemo, "");
        $this->Picture = new clsField("Picture", ccsMemo, "");

    }
//End Class_Initialize Event

//Prepare Method @2-AA3879E2
    function Prepare()
    {
        $this->wp = new clsSQLParameters();
        $this->wp->AddParameter("1", "urlRecordNum", ccsInteger, "", "", $this->Parameters["urlRecordNum"], "");
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "RecordNum", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger));
        $this->wp->AssembledWhere = $this->wp->Criterion[1];
        $this->Where = $this->wp->AssembledWhere;
    }
//End Prepare Method

//Open Method @2-CE57F892
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM alm_alumni";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-ED109BA5
    function SetValues()
    {
        $this->YearGrad->SetDBValue($this->f("YearGrad"));
        $this->FirstName->SetDBValue($this->f("FirstName"));
        $this->LastName->SetDBValue($this->f("LastName"));
        $this->MaidenName->SetDBValue($this->f("MaidenName"));
        $this->MaritalStatus->SetDBValue($this->f("MaritalStatus"));
        $this->Spouse->SetDBValue($this->f("Spouse"));
        $this->Children->SetDBValue($this->f("Children"));
        $this->HomeAddress->SetDBValue($this->f("HomeAddress"));
        $this->HomeCity->SetDBValue($this->f("HomeCity"));
        $this->HomeState->SetDBValue($this->f("HomeState"));
        $this->Phone->SetDBValue($this->f("Phone"));
        $this->Email->SetDBValue($this->f("EmailAddy"));
        $this->Website->SetDBValue($this->f("Website"));
        $this->Profession->SetDBValue($this->f("Profession"));
        $this->Employed->SetDBValue($this->f("Employed"));
        $this->About->SetDBValue($this->f("About"));
        $this->Picture->SetDBValue($this->f("Picture"));
    }
//End SetValues Method

} //End alm_alumniDataSource Class @2-FCB6E20C

//Include Page implementation @23-B991DFB8
include(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-FFB1B35B
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

$FileName = "alumni_view.php";
$Redirect = "";
$TemplateFileName = "alumni_view.html";
$BlockToParse = "main";
$PathToRoot = "./";
//End Initialize Page

//Initialize Objects @1-2FD9E631
$DBAlumni = new clsDBAlumni();

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$alm_alumni = new clsRecordalm_alumni();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$alm_alumni->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Execute Components @1-149D8157
$Header->Operations();
$alm_alumni->Operation();
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

//Show Page @1-F3EF8DC5
$Header->Show("Header");
$alm_alumni->Show();
$Footer->Show("Footer");
$Tpl->PParse("main", false);
//End Show Page

//Unload Page @1-AB7622EF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
unset($Tpl);
//End Unload Page



?>
