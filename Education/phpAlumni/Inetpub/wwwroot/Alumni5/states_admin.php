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

class clsGridalm_states { //alm_states class @2-FDC03245

//Variables @2-2A2E3937

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
    var $Sorter_state;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-EF6592C7
    function clsGridalm_states()
    {
        global $FileName;
        $this->ComponentName = "alm_states";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ds = new clsalm_statesDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("alm_statesOrder", "");
        $this->SorterDirection = CCGetParam("alm_statesDir", "");

        $this->state = new clsControl(ccsLink, "state", "state", ccsText, "", CCGetRequestParam("state", ccsGet));
        $this->Sorter_state = new clsSorter($this->ComponentName, "Sorter_state", $FileName);
        $this->alm_states_Insert = new clsControl(ccsLink, "alm_states_Insert", "alm_states_Insert", ccsText, "", CCGetRequestParam("alm_states_Insert", ccsGet));
        $this->alm_states_Insert->Parameters = CCGetQueryString("QueryString", Array("state", "ccsForm"));
        $this->alm_states_Insert->Page = "states_admin.php";
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

//Show Method @2-DFB960C6
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
                $this->state->SetValue($this->ds->state->GetValue());
                $this->state->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->state->Parameters = CCAddParam($this->state->Parameters, "state", $this->ds->f("state"));
                $this->state->Page = "states_admin.php";
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->state->Show();
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
        $this->Sorter_state->Show();
        $this->alm_states_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse("", false);
        $Tpl->block_path = "";
    }
//End Show Method

} //End alm_states Class @2-FCB6E20C

class clsalm_statesDataSource extends clsDBAlumni {  //alm_statesDataSource Class @2-6B26CB9F

//Variables @2-DBAB8385
    var $CCSEvents = "";
    var $CCSEventResult;

    var $CountSQL;
    var $wp;

    // Datasource fields
    var $state;
//End Variables

//Class_Initialize Event @2-979D6565
    function clsalm_statesDataSource()
    {
        $this->Initialize();
        $this->state = new clsField("state", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-76864444
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "state";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_state" => array("undefined", "")));
    }
//End SetOrder Method

//Prepare Method @2-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @2-93C89BFC
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM alm_states";
        $this->SQL = "SELECT *  " .
        "FROM alm_states";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-820C5DDF
    function SetValues()
    {
        $this->state->SetDBValue($this->f("state"));
    }
//End SetValues Method

} //End alm_statesDataSource Class @2-FCB6E20C

Class clsRecordalm_states1 { //alm_states1 Class @8-9A49823F

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

//Class_Initialize Event @8-341FB402
    function clsRecordalm_states1()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ds = new clsalm_states1DataSource();
        if($this->Visible)
        {
            $this->ComponentName = "alm_states1";
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $this->ComponentName);
            $CCSForm = CCGetFromGet("ccsForm", "");
            $this->FormSubmitted = ($CCSForm == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->state = new clsControl(ccsTextBox, "state", "State", ccsText, "", CCGetRequestParam("state", $Method));
            $this->state->Required = true;
            $this->Insert = new clsButton("Insert");
            $this->Update = new clsButton("Update");
            $this->Delete = new clsButton("Delete");
        }
    }
//End Class_Initialize Event

//Initialize Method @8-8B08D189
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlstate"] = CCGetFromGet("state", "");
    }
//End Initialize Method

//Validate Method @8-A31711D9
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->state->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//Operation Method @8-2CDE5415
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
        $Redirect = "states_admin.php?" . CCGetQueryString("QueryString", Array("Insert","Update","Delete","ccsForm"));
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

//InsertRow Method @8-B42524E3
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        $this->ds->state->SetValue($this->state->GetValue());
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

//UpdateRow Method @8-D928D904
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        $this->ds->state->SetValue($this->state->GetValue());
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

//Show Method @8-A0D8C25A
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
                    echo "Error in Record alm_states1";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->state->SetValue($this->ds->state->GetValue());
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
            $Error .= $this->state->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $this->Insert->Visible = !$this->EditMode;
        $this->Update->Visible = $this->EditMode;
        $this->Delete->Visible = $this->EditMode;
        $this->state->Show();
        $this->Insert->Show();
        $this->Update->Show();
        $this->Delete->Show();
        $Tpl->parse("", false);
        $Tpl->block_path = "";
    }
//End Show Method

} //End alm_states1 Class @8-FCB6E20C

class clsalm_states1DataSource extends clsDBAlumni {  //alm_states1DataSource Class @8-74970A57

//Variables @8-2A936A9A
    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;

    // Datasource fields
    var $state;
//End Variables

//Class_Initialize Event @8-87B1C76A
    function clsalm_states1DataSource()
    {
        $this->Initialize();
        $this->state = new clsField("state", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @8-A71B7E2B
    function Prepare()
    {
        $this->wp = new clsSQLParameters();
        $this->wp->AddParameter("1", "urlstate", ccsText, "", "", $this->Parameters["urlstate"], "");
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "state", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText));
        $this->wp->AssembledWhere = $this->wp->Criterion[1];
        $this->Where = $this->wp->AssembledWhere;
    }
//End Prepare Method

//Open Method @8-CCED5FAC
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM alm_states";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @8-820C5DDF
    function SetValues()
    {
        $this->state->SetDBValue($this->f("state"));
    }
//End SetValues Method

//Insert Method @8-C43D2E04
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $SQL = "INSERT INTO alm_states ("
             . "state"
             . ") VALUES ("
             . $this->ToSQL($this->state->GetDBValue(), $this->state->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
    }
//End Insert Method

//Update Method @8-6233B4C3
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $SQL = "UPDATE alm_states SET "
             . "state=" . $this->ToSQL($this->state->GetDBValue(), $this->state->DataType);
        $SQL = CCBuildSQL($SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
    }
//End Update Method

//Delete Method @8-83156083
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $SQL = "DELETE FROM alm_states WHERE " . $this->Where;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
    }
//End Delete Method

} //End alm_states1DataSource Class @8-FCB6E20C

//Include Page implementation @15-B991DFB8
include(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-437F1D0E
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

$FileName = "states_admin.php";
$Redirect = "";
$TemplateFileName = "states_admin.html";
$BlockToParse = "main";
$PathToRoot = "./";
//End Initialize Page

//Initialize Objects @1-D57448FF
$DBAlumni = new clsDBAlumni();

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$alm_states = new clsGridalm_states();
$alm_states1 = new clsRecordalm_states1();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$alm_states->Initialize();
$alm_states1->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Execute Components @1-E5DC78F3
$Header->Operations();
$alm_states1->Operation();
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

//Show Page @1-F7CE586B
$Header->Show("Header");
$alm_states->Show();
$alm_states1->Show();
$Footer->Show("Footer");
$Tpl->PParse("main", false);
//End Show Page

//Unload Page @1-AB7622EF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
unset($Tpl);
//End Unload Page



?>
