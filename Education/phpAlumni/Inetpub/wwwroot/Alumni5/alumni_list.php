<?php
//Include Common Files @1-5471E0F2
define("RelativePath", ".");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @20-DC989187
include(RelativePath . "/Header.php");
//End Include Page implementation

Class clsRecordalm_alumniSearch1 { //alm_alumniSearch1 Class @22-606B0D6D

//Variables @22-90DA4C9A

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

//Class_Initialize Event @22-753A8D3B
    function clsRecordalm_alumniSearch1()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "alm_alumniSearch1";
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $this->ComponentName);
            $CCSForm = CCGetFromGet("ccsForm", "");
            $this->FormSubmitted = ($CCSForm == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "s_keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method));
            $this->DoSearch = new clsButton("DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @22-F230E30A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//Operation Method @22-DFA69DD3
    function Operation()
    {
        global $Redirect;

        $this->EditMode = false;
        if(!($this->Visible && $this->FormSubmitted))
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = "DoSearch";
            if(strlen(CCGetParam("DoSearch", ""))) {
                $this->PressedButton = "DoSearch";
            }
        }
        $Redirect = "alumni_list.php?" . CCGetQueryString("Form", Array("DoSearch","ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "DoSearch") {
                if(!CCGetEvent($this->DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "alumni_list.php?" . CCGetQueryString("Form", Array("DoSearch"));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @22-2C40AB48
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $RecordBlock = "Record " . $this->ComponentName;
        $Tpl->block_path = $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");

        if($this->FormSubmitted) {
            $Error .= $this->s_keyword->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $this->s_keyword->Show();
        $this->DoSearch->Show();
        $Tpl->parse("", false);
        $Tpl->block_path = "";
    }
//End Show Method

} //End alm_alumniSearch1 Class @22-FCB6E20C

class clsGridalm_alumni { //alm_alumni class @5-E60D0339

//Variables @5-EDB307A6

    // Public variables
    var $ComponentName;
    var $Visible; var $Errors;
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    // Grid Controls
    var $StaticControls; var $RowControls;
    var $Sorter_RecordNum;
    var $Sorter_YearGrad;
    var $Sorter_FirstName;
    var $Sorter_LastName;
    var $Sorter_MaidenName;
    var $Navigator;
//End Variables

//Class_Initialize Event @5-CCF7BBC7
    function clsGridalm_alumni()
    {
        global $FileName;
        $this->ComponentName = "alm_alumni";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ds = new clsalm_alumniDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("alm_alumniOrder", "");
        $this->SorterDirection = CCGetParam("alm_alumniDir", "");

        $this->RecordNum = new clsControl(ccsLink, "RecordNum", "RecordNum", ccsInteger, "", CCGetRequestParam("RecordNum", ccsGet));
        $this->YearGrad = new clsControl(ccsLabel, "YearGrad", "YearGrad", ccsText, "", CCGetRequestParam("YearGrad", ccsGet));
        $this->FirstName = new clsControl(ccsLabel, "FirstName", "FirstName", ccsText, "", CCGetRequestParam("FirstName", ccsGet));
        $this->LastName = new clsControl(ccsLabel, "LastName", "LastName", ccsText, "", CCGetRequestParam("LastName", ccsGet));
        $this->MaidenName = new clsControl(ccsLabel, "MaidenName", "MaidenName", ccsText, "", CCGetRequestParam("MaidenName", ccsGet));
        $this->Sorter_RecordNum = new clsSorter($this->ComponentName, "Sorter_RecordNum", $FileName);
        $this->Sorter_YearGrad = new clsSorter($this->ComponentName, "Sorter_YearGrad", $FileName);
        $this->Sorter_FirstName = new clsSorter($this->ComponentName, "Sorter_FirstName", $FileName);
        $this->Sorter_LastName = new clsSorter($this->ComponentName, "Sorter_LastName", $FileName);
        $this->Sorter_MaidenName = new clsSorter($this->ComponentName, "Sorter_MaidenName", $FileName);
        $this->alm_alumni_Insert = new clsControl(ccsLink, "alm_alumni_Insert", "alm_alumni_Insert", ccsText, "", CCGetRequestParam("alm_alumni_Insert", ccsGet));
        $this->alm_alumni_Insert->Page = "alumni_add.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @5-383CA3E0
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
        $this->ds->AbsolutePage = $this->PageNumber;
    }
//End Initialize Method

//Show Method @5-CBEC2B27
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");
        $this->ds->Parameters["expr28"] = admin;
        $this->ds->Prepare();
        $this->ds->Open();

        $GridBlock = "Grid " . $this->ComponentName;
        $Tpl->block_path = $GridBlock;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");


        $is_next_record = $this->ds->next_record();
        if($is_next_record && $ShownRecords < $this->PageSize)
        {
            do {
                    $this->ds->SetValues();
                $Tpl->block_path = $GridBlock . "/Row";
                $this->RecordNum->SetValue($this->ds->RecordNum->GetValue());
                $this->RecordNum->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->RecordNum->Parameters = CCAddParam($this->RecordNum->Parameters, "RecordNum", $this->ds->f("RecordNum"));
                $this->RecordNum->Page = "alumni_view.php";
                $this->YearGrad->SetValue($this->ds->YearGrad->GetValue());
                $this->FirstName->SetValue($this->ds->FirstName->GetValue());
                $this->LastName->SetValue($this->ds->LastName->GetValue());
                $this->MaidenName->SetValue($this->ds->MaidenName->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->RecordNum->Show();
                $this->YearGrad->Show();
                $this->FirstName->Show();
                $this->LastName->Show();
                $this->MaidenName->Show();
                $Tpl->block_path = $GridBlock;
                $Tpl->parse("Row", true);
                $ShownRecords++;
                $is_next_record = $this->ds->next_record();
            } while ($is_next_record && $ShownRecords < $this->PageSize);
        }
        else // Show NoRecords block if no records are found
        {
            $Tpl->parse("NoRecords", false);
        }

        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_RecordNum->Show();
        $this->Sorter_YearGrad->Show();
        $this->Sorter_FirstName->Show();
        $this->Sorter_LastName->Show();
        $this->Sorter_MaidenName->Show();
        $this->alm_alumni_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse("", false);
        $Tpl->block_path = "";
    }
//End Show Method

} //End alm_alumni Class @5-FCB6E20C

class clsalm_alumniDataSource extends clsDBAlumni {  //alm_alumniDataSource Class @5-E39EB4D6

//Variables @5-8704F0D6
    var $CCSEvents = "";
    var $CCSEventResult;

    var $CountSQL;
    var $wp;

    // Datasource fields
    var $RecordNum;
    var $YearGrad;
    var $FirstName;
    var $LastName;
    var $MaidenName;
//End Variables

//Class_Initialize Event @5-93B55F17
    function clsalm_alumniDataSource()
    {
        $this->Initialize();
        $this->RecordNum = new clsField("RecordNum", ccsInteger, "");
        $this->YearGrad = new clsField("YearGrad", ccsText, "");
        $this->FirstName = new clsField("FirstName", ccsText, "");
        $this->LastName = new clsField("LastName", ccsText, "");
        $this->MaidenName = new clsField("MaidenName", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @5-315CB4E0
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "YearGrad";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_RecordNum" => array("RecordNum", ""), 
            "Sorter_YearGrad" => array("YearGrad", ""), 
            "Sorter_FirstName" => array("FirstName", ""), 
            "Sorter_LastName" => array("LastName", ""), 
            "Sorter_MaidenName" => array("MaidenName", "")));
    }
//End SetOrder Method

//Prepare Method @5-4265FF20
    function Prepare()
    {
        $this->wp = new clsSQLParameters();
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "");
        $this->wp->AddParameter("2", "expr28", ccsText, "", "", $this->Parameters["expr28"], "");
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "YearGrad", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText));
        $this->wp->Criterion[2] = $this->wp->Operation(opNotEqual, "LoginID", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText));
        $this->wp->AssembledWhere = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
        $this->Where = $this->wp->AssembledWhere;
    }
//End Prepare Method

//Open Method @5-9AACD3B8
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM alm_alumni";
        $this->SQL = "SELECT RecordNum, YearGrad, LastName, FirstName, MaidenName  " .
        "FROM alm_alumni";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @5-F1C68C31
    function SetValues()
    {
        $this->RecordNum->SetDBValue($this->f("RecordNum"));
        $this->YearGrad->SetDBValue($this->f("YearGrad"));
        $this->FirstName->SetDBValue($this->f("FirstName"));
        $this->LastName->SetDBValue($this->f("LastName"));
        $this->MaidenName->SetDBValue($this->f("MaidenName"));
    }
//End SetValues Method

} //End alm_alumniDataSource Class @5-FCB6E20C

//Include Page implementation @21-B991DFB8
include(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-B9B96250
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

$FileName = "alumni_list.php";
$Redirect = "";
$TemplateFileName = "alumni_list.html";
$BlockToParse = "main";
$PathToRoot = "./";
//End Initialize Page

//Initialize Objects @1-496DAAE7
$DBAlumni = new clsDBAlumni();

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$alm_alumniSearch1 = new clsRecordalm_alumniSearch1();
$alm_alumni = new clsGridalm_alumni();
$Logout = new clsControl(ccsLink, "Logout", "Logout", ccsText, "", CCGetRequestParam("Logout", ccsGet));
$Logout->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
$Logout->Parameters = CCAddParam($Logout->Parameters, "Logout", "True");
$Logout->Page = "alumni_list.php";
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$alm_alumni->Initialize();

// Events
include("./alumni_list_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Execute Components @1-0CBAEF86
$Header->Operations();
$alm_alumniSearch1->Operation();
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

//Show Page @1-B69BF86F
$Header->Show("Header");
$alm_alumniSearch1->Show();
$alm_alumni->Show();
$Logout->Show();
$Footer->Show("Footer");
$Tpl->PParse("main", false);
//End Show Page

//Unload Page @1-AB7622EF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
unset($Tpl);
//End Unload Page



?>
