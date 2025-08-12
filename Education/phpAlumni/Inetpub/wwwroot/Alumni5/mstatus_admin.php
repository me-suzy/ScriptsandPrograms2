<?php
//Include Common Files @1-5471E0F2
define("RelativePath", ".");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @14-DC989187
include(RelativePath . "/Header.php");
//End Include Page implementation

class clsGridalm_mstatus { //alm_mstatus class @2-60E26219

//Variables @2-4CE52B9F

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
    var $Sorter_STATUS;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-C1CF174D
    function clsGridalm_mstatus()
    {
        global $FileName;
        $this->ComponentName = "alm_mstatus";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ds = new clsalm_mstatusDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("alm_mstatusOrder", "");
        $this->SorterDirection = CCGetParam("alm_mstatusDir", "");

        $this->STATUS = new clsControl(ccsLink, "STATUS", "STATUS", ccsText, "", CCGetRequestParam("STATUS", ccsGet));
        $this->Sorter_STATUS = new clsSorter($this->ComponentName, "Sorter_STATUS", $FileName);
        $this->alm_mstatus_Insert = new clsControl(ccsLink, "alm_mstatus_Insert", "alm_mstatus_Insert", ccsText, "", CCGetRequestParam("alm_mstatus_Insert", ccsGet));
        $this->alm_mstatus_Insert->Parameters = CCGetQueryString("QueryString", Array("STATUS", "ccsForm"));
        $this->alm_mstatus_Insert->Page = "mstatus_admin.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @2-383CA3E0
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
        $this->ds->AbsolutePage = $this->PageNumber;
    }
//End Initialize Method

//Show Method @2-C4314EF3
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

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
                $this->STATUS->SetValue($this->ds->STATUS->GetValue());
                $this->STATUS->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->STATUS->Parameters = CCAddParam($this->STATUS->Parameters, "STATUS", $this->ds->f("STATUS"));
                $this->STATUS->Page = "mstatus_admin.php";
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->STATUS->Show();
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
        $this->Sorter_STATUS->Show();
        $this->alm_mstatus_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse("", false);
        $Tpl->block_path = "";
    }
//End Show Method

} //End alm_mstatus Class @2-FCB6E20C

class clsalm_mstatusDataSource extends clsDBAlumni {  //alm_mstatusDataSource Class @2-22E2B65E

//Variables @2-DEAD622A
    var $CCSEvents = "";
    var $CCSEventResult;

    var $CountSQL;
    var $wp;

    // Datasource fields
    var $STATUS;
//End Variables

//Class_Initialize Event @2-61B8A6EB
    function clsalm_mstatusDataSource()
    {
        $this->Initialize();
        $this->STATUS = new clsField("STATUS", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-44D03CEC
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_STATUS" => array("undefined", "")));
    }
//End SetOrder Method

//Prepare Method @2-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @2-EDF60E0C
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM alm_mstatus";
        $this->SQL = "SELECT *  " .
        "FROM alm_mstatus";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-A9C2FBAA
    function SetValues()
    {
        $this->STATUS->SetDBValue($this->f("STATUS"));
    }
//End SetValues Method

} //End alm_mstatusDataSource Class @2-FCB6E20C

Class clsRecordalm_mstatus1 { //alm_mstatus1 Class @8-AA708CF2

//Variables @8-90DA4C9A

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

//Class_Initialize Event @8-652CC239
    function clsRecordalm_mstatus1()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ds = new clsalm_mstatus1DataSource();
        if($this->Visible)
        {
            $this->ComponentName = "alm_mstatus1";
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $this->ComponentName);
            $CCSForm = CCGetFromGet("ccsForm", "");
            $this->FormSubmitted = ($CCSForm == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->STATUS = new clsControl(ccsTextBox, "STATUS", "STATUS", ccsText, "", CCGetRequestParam("STATUS", $Method));
            $this->STATUS->Required = true;
            $this->Insert = new clsButton("Insert");
            $this->Update = new clsButton("Update");
            $this->Delete = new clsButton("Delete");
        }
    }
//End Class_Initialize Event

//Initialize Method @8-D73E998B
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlSTATUS"] = CCGetFromGet("STATUS", "");
    }
//End Initialize Method

//Validate Method @8-C6FFA834
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->STATUS->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//Operation Method @8-BF88D1D2
    function Operation()
    {
        global $Redirect;

        $this->ds->Prepare();
        $this->EditMode = $this->ds->AllParametersSet;
        if(!($this->Visible && $this->FormSubmitted))
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Update" : "Insert";
            if(strlen(CCGetParam("Insert", ""))) {
                $this->PressedButton = "Insert";
            } else if(strlen(CCGetParam("Update", ""))) {
                $this->PressedButton = "Update";
            } else if(strlen(CCGetParam("Delete", ""))) {
                $this->PressedButton = "Delete";
            }
        }
        $Redirect = "mstatus_admin.php?" . CCGetQueryString("QueryString", Array("Insert","Update","Delete","ccsForm"));
        if($this->PressedButton == "Delete") {
            if(!CCGetEvent($this->Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Insert") {
                if(!CCGetEvent($this->Insert->CCSEvents, "OnClick") || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Update") {
                if(!CCGetEvent($this->Update->CCSEvents, "OnClick") || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//InsertRow Method @8-33A857C6
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        $this->ds->STATUS->SetValue($this->STATUS->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        if($this->ds->Errors->Count() > 0)
        {
            echo "Error in Record " . $this->ComponentName . " / Insert Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return ($this->Errors->Count() == 0);
    }
//End InsertRow Method

//UpdateRow Method @8-C115FA39
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        $this->ds->STATUS->SetValue($this->STATUS->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        if($this->ds->Errors->Count() > 0)
        {
            echo "Error in Record " . $this->ComponentName . " / Update Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return ($this->Errors->Count() == 0);
    }
//End UpdateRow Method

//DeleteRow Method @8-A9D87FED
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete");
        $this->ds->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete");
        if($this->ds->Errors->Count())
        {
            echo "Error in Record " . ComponentName . " / Delete Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return ($this->Errors->Count() == 0);
    }
//End DeleteRow Method

//Show Method @8-0C169326
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
                    echo "Error in Record alm_mstatus1";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->STATUS->SetValue($this->ds->STATUS->GetValue());
                    }
                }
                else
                {
                    $this->EditMode = false;
                }
            }
        }
        if(!$this->FormSubmitted)
        {
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");

        if($this->FormSubmitted) {
            $Error .= $this->STATUS->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $this->Insert->Visible = !$this->EditMode;
        $this->Update->Visible = $this->EditMode;
        $this->Delete->Visible = $this->EditMode;
        $this->STATUS->Show();
        $this->Insert->Show();
        $this->Update->Show();
        $this->Delete->Show();
        $Tpl->parse("", false);
        $Tpl->block_path = "";
    }
//End Show Method

} //End alm_mstatus1 Class @8-FCB6E20C

class clsalm_mstatus1DataSource extends clsDBAlumni {  //alm_mstatus1DataSource Class @8-98BD3C0C

//Variables @8-53545736
    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;

    // Datasource fields
    var $STATUS;
//End Variables

//Class_Initialize Event @8-C5C2849B
    function clsalm_mstatus1DataSource()
    {
        $this->Initialize();
        $this->STATUS = new clsField("STATUS", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @8-2F856DE5
    function Prepare()
    {
        $this->wp = new clsSQLParameters();
        $this->wp->AddParameter("1", "urlSTATUS", ccsText, "", "", $this->Parameters["urlSTATUS"], "");
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "STATUS", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText));
        $this->wp->AssembledWhere = $this->wp->Criterion[1];
        $this->Where = $this->wp->AssembledWhere;
    }
//End Prepare Method

//Open Method @8-73EA8490
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM alm_mstatus";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @8-A9C2FBAA
    function SetValues()
    {
        $this->STATUS->SetDBValue($this->f("STATUS"));
    }
//End SetValues Method

//Insert Method @8-A2368897
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $SQL = "INSERT INTO alm_mstatus ("
             . "STATUS"
             . ") VALUES ("
             . $this->ToSQL($this->STATUS->GetDBValue(), $this->STATUS->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
    }
//End Insert Method

//Update Method @8-31875AC8
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $SQL = "UPDATE alm_mstatus SET "
             . "STATUS=" . $this->ToSQL($this->STATUS->GetDBValue(), $this->STATUS->DataType);
        $SQL = CCBuildSQL($SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
    }
//End Update Method

//Delete Method @8-36CBE0E9
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $SQL = "DELETE FROM alm_mstatus WHERE " . $this->Where;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
    }
//End Delete Method

} //End alm_mstatus1DataSource Class @8-FCB6E20C

//Include Page implementation @15-B991DFB8
include(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-ABCCC645
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

$FileName = "mstatus_admin.php";
$Redirect = "";
$TemplateFileName = "mstatus_admin.html";
$BlockToParse = "main";
$PathToRoot = "./";
//End Initialize Page

//Initialize Objects @1-A7D772C0
$DBAlumni = new clsDBAlumni();

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$alm_mstatus = new clsGridalm_mstatus();
$alm_mstatus1 = new clsRecordalm_mstatus1();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$alm_mstatus->Initialize();
$alm_mstatus1->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Execute Components @1-7B4DD552
$Header->Operations();
$alm_mstatus1->Operation();
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

//Show Page @1-FB9C26BA
$Header->Show("Header");
$alm_mstatus->Show();
$alm_mstatus1->Show();
$Footer->Show("Footer");
$Tpl->PParse("main", false);
//End Show Page

//Unload Page @1-AB7622EF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
unset($Tpl);
//End Unload Page



?>
