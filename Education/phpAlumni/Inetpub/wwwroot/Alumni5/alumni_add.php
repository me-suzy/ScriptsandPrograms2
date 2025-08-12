<?php
//Include Common Files @1-5471E0F2
define("RelativePath", ".");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @28-DC989187
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

//Class_Initialize Event @2-6BA3F7DF
    function clsRecordalm_alumni()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ds = new clsalm_alumniDataSource();
        if($this->Visible)
        {
            $this->ComponentName = "alm_alumni";
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $this->ComponentName);
            $CCSForm = CCGetFromGet("ccsForm", "");
            $this->FormSubmitted = ($CCSForm == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->YearGrad = new clsControl(ccsTextBox, "YearGrad", "Year Grad", ccsText, "", CCGetRequestParam("YearGrad", $Method));
            $this->FirstName = new clsControl(ccsTextBox, "FirstName", "First Name", ccsText, "", CCGetRequestParam("FirstName", $Method));
            $this->LastName = new clsControl(ccsTextBox, "LastName", "Last Name", ccsText, "", CCGetRequestParam("LastName", $Method));
            $this->MaidenName = new clsControl(ccsTextBox, "MaidenName", "Maiden Name", ccsText, "", CCGetRequestParam("MaidenName", $Method));
            $this->MaritalStatus = new clsControl(ccsListBox, "MaritalStatus", "Marital Status", ccsText, "", CCGetRequestParam("MaritalStatus", $Method));
            $this->MaritalStatus_ds = new clsDBAlumni();
            $this->MaritalStatus_ds->SQL = "SELECT *  " .
"FROM alm_mstatus";
            $MaritalStatus_values = CCGetListValues($this->MaritalStatus_ds, $this->MaritalStatus_ds->SQL, $this->MaritalStatus_ds->Where, $this->MaritalStatus_ds->Order, "STATUS", "STATUS");
            $this->MaritalStatus->Values = $MaritalStatus_values;
            $this->Spouse = new clsControl(ccsTextBox, "Spouse", "Spouse", ccsText, "", CCGetRequestParam("Spouse", $Method));
            $this->Children = new clsControl(ccsTextBox, "Children", "Children", ccsText, "", CCGetRequestParam("Children", $Method));
            $this->HomeAddress = new clsControl(ccsTextBox, "HomeAddress", "Home Address", ccsText, "", CCGetRequestParam("HomeAddress", $Method));
            $this->HomeCity = new clsControl(ccsTextBox, "HomeCity", "Home City", ccsText, "", CCGetRequestParam("HomeCity", $Method));
            $this->HomeState = new clsControl(ccsListBox, "HomeState", "Home State", ccsText, "", CCGetRequestParam("HomeState", $Method));
            $this->HomeState_ds = new clsDBAlumni();
            $this->HomeState_ds->SQL = "SELECT *  " .
"FROM alm_states";
            $HomeState_values = CCGetListValues($this->HomeState_ds, $this->HomeState_ds->SQL, $this->HomeState_ds->Where, $this->HomeState_ds->Order, "state", "state");
            $this->HomeState->Values = $HomeState_values;
            $this->Phone = new clsControl(ccsTextBox, "Phone", "Phone", ccsText, "", CCGetRequestParam("Phone", $Method));
            $this->EmailAddy = new clsControl(ccsTextBox, "EmailAddy", "Email Addy", ccsText, "", CCGetRequestParam("EmailAddy", $Method));
            $this->Website = new clsControl(ccsTextBox, "Website", "Website", ccsText, "", CCGetRequestParam("Website", $Method));
            $this->Profession = new clsControl(ccsTextBox, "Profession", "Profession", ccsText, "", CCGetRequestParam("Profession", $Method));
            $this->Employed = new clsControl(ccsTextBox, "Employed", "Employed", ccsText, "", CCGetRequestParam("Employed", $Method));
            $this->About = new clsControl(ccsTextArea, "About", "About", ccsMemo, "", CCGetRequestParam("About", $Method));
            $this->Picture = new clsControl(ccsTextBox, "Picture", "Picture", ccsMemo, "", CCGetRequestParam("Picture", $Method));
            $this->LoginID = new clsControl(ccsTextBox, "LoginID", "Login ID", ccsText, "", CCGetRequestParam("LoginID", $Method));
            $this->Password = new clsControl(ccsTextBox, "Password", "Password", ccsText, "", CCGetRequestParam("Password", $Method));
            $this->Insert = new clsButton("Insert");
            $this->Update = new clsButton("Update");
            $this->Delete = new clsButton("Delete");
        }
    }
//End Class_Initialize Event

//Initialize Method @2-67E17201
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["ses{UserID}"] = CCGetSession("{UserID}");
    }
//End Initialize Method

//Validate Method @2-B603B884
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
        $Validation = ($this->EmailAddy->Validate() && $Validation);
        $Validation = ($this->Website->Validate() && $Validation);
        $Validation = ($this->Profession->Validate() && $Validation);
        $Validation = ($this->Employed->Validate() && $Validation);
        $Validation = ($this->About->Validate() && $Validation);
        $Validation = ($this->Picture->Validate() && $Validation);
        $Validation = ($this->LoginID->Validate() && $Validation);
        $Validation = ($this->Password->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//Operation Method @2-2B5E347B
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
        $Redirect = "alumni_list.php?" . CCGetQueryString("QueryString", Array("Insert","Update","Delete","ccsForm"));
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

//InsertRow Method @2-A6FEB250
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        $this->ds->YearGrad->SetValue($this->YearGrad->GetValue());
        $this->ds->FirstName->SetValue($this->FirstName->GetValue());
        $this->ds->LastName->SetValue($this->LastName->GetValue());
        $this->ds->MaidenName->SetValue($this->MaidenName->GetValue());
        $this->ds->MaritalStatus->SetValue($this->MaritalStatus->GetValue());
        $this->ds->Spouse->SetValue($this->Spouse->GetValue());
        $this->ds->Children->SetValue($this->Children->GetValue());
        $this->ds->HomeAddress->SetValue($this->HomeAddress->GetValue());
        $this->ds->HomeCity->SetValue($this->HomeCity->GetValue());
        $this->ds->HomeState->SetValue($this->HomeState->GetValue());
        $this->ds->Phone->SetValue($this->Phone->GetValue());
        $this->ds->EmailAddy->SetValue($this->EmailAddy->GetValue());
        $this->ds->Website->SetValue($this->Website->GetValue());
        $this->ds->Profession->SetValue($this->Profession->GetValue());
        $this->ds->Employed->SetValue($this->Employed->GetValue());
        $this->ds->About->SetValue($this->About->GetValue());
        $this->ds->Picture->SetValue($this->Picture->GetValue());
        $this->ds->LoginID->SetValue($this->LoginID->GetValue());
        $this->ds->Password->SetValue($this->Password->GetValue());
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

//UpdateRow Method @2-AC9FCC07
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        $this->ds->YearGrad->SetValue($this->YearGrad->GetValue());
        $this->ds->FirstName->SetValue($this->FirstName->GetValue());
        $this->ds->LastName->SetValue($this->LastName->GetValue());
        $this->ds->MaidenName->SetValue($this->MaidenName->GetValue());
        $this->ds->MaritalStatus->SetValue($this->MaritalStatus->GetValue());
        $this->ds->Spouse->SetValue($this->Spouse->GetValue());
        $this->ds->Children->SetValue($this->Children->GetValue());
        $this->ds->HomeAddress->SetValue($this->HomeAddress->GetValue());
        $this->ds->HomeCity->SetValue($this->HomeCity->GetValue());
        $this->ds->HomeState->SetValue($this->HomeState->GetValue());
        $this->ds->Phone->SetValue($this->Phone->GetValue());
        $this->ds->EmailAddy->SetValue($this->EmailAddy->GetValue());
        $this->ds->Website->SetValue($this->Website->GetValue());
        $this->ds->Profession->SetValue($this->Profession->GetValue());
        $this->ds->Employed->SetValue($this->Employed->GetValue());
        $this->ds->About->SetValue($this->About->GetValue());
        $this->ds->Picture->SetValue($this->Picture->GetValue());
        $this->ds->LoginID->SetValue($this->LoginID->GetValue());
        $this->ds->Password->SetValue($this->Password->GetValue());
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

//DeleteRow Method @2-A9D87FED
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

//Show Method @2-387C1E1D
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
                    if(!$this->FormSubmitted)
                    {
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
                        $this->EmailAddy->SetValue($this->ds->EmailAddy->GetValue());
                        $this->Website->SetValue($this->ds->Website->GetValue());
                        $this->Profession->SetValue($this->ds->Profession->GetValue());
                        $this->Employed->SetValue($this->ds->Employed->GetValue());
                        $this->About->SetValue($this->ds->About->GetValue());
                        $this->Picture->SetValue($this->ds->Picture->GetValue());
                        $this->LoginID->SetValue($this->ds->LoginID->GetValue());
                        $this->Password->SetValue($this->ds->Password->GetValue());
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
            $Error .= $this->EmailAddy->Errors->ToString();
            $Error .= $this->Website->Errors->ToString();
            $Error .= $this->Profession->Errors->ToString();
            $Error .= $this->Employed->Errors->ToString();
            $Error .= $this->About->Errors->ToString();
            $Error .= $this->Picture->Errors->ToString();
            $Error .= $this->LoginID->Errors->ToString();
            $Error .= $this->Password->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $this->Insert->Visible = !$this->EditMode;
        $this->Update->Visible = $this->EditMode;
        $this->Delete->Visible = $this->EditMode;
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
        $this->EmailAddy->Show();
        $this->Website->Show();
        $this->Profession->Show();
        $this->Employed->Show();
        $this->About->Show();
        $this->Picture->Show();
        $this->LoginID->Show();
        $this->Password->Show();
        $this->Insert->Show();
        $this->Update->Show();
        $this->Delete->Show();
        $Tpl->parse("", false);
        $Tpl->block_path = "";
    }
//End Show Method

} //End alm_alumni Class @2-FCB6E20C

class clsalm_alumniDataSource extends clsDBAlumni {  //alm_alumniDataSource Class @2-E39EB4D6

//Variables @2-F88D2F31
    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
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
    var $EmailAddy;
    var $Website;
    var $Profession;
    var $Employed;
    var $About;
    var $Picture;
    var $LoginID;
    var $Password;
//End Variables

//Class_Initialize Event @2-335E8836
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
        $this->EmailAddy = new clsField("EmailAddy", ccsText, "");
        $this->Website = new clsField("Website", ccsText, "");
        $this->Profession = new clsField("Profession", ccsText, "");
        $this->Employed = new clsField("Employed", ccsText, "");
        $this->About = new clsField("About", ccsMemo, "");
        $this->Picture = new clsField("Picture", ccsMemo, "");
        $this->LoginID = new clsField("LoginID", ccsText, "");
        $this->Password = new clsField("Password", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @2-F6D9D6A6
    function Prepare()
    {
        $this->wp = new clsSQLParameters();
        $this->wp->AddParameter("1", "ses{UserID}", ccsInteger, "", "", $this->Parameters["ses{UserID}"], "");
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

//SetValues Method @2-EF41EBEF
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
        $this->EmailAddy->SetDBValue($this->f("EmailAddy"));
        $this->Website->SetDBValue($this->f("Website"));
        $this->Profession->SetDBValue($this->f("Profession"));
        $this->Employed->SetDBValue($this->f("Employed"));
        $this->About->SetDBValue($this->f("About"));
        $this->Picture->SetDBValue($this->f("Picture"));
        $this->LoginID->SetDBValue($this->f("LoginID"));
        $this->Password->SetDBValue($this->f("Password"));
    }
//End SetValues Method

//Insert Method @2-0004F34B
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $SQL = "INSERT INTO alm_alumni ("
             . "YearGrad, "
             . "FirstName, "
             . "LastName, "
             . "MaidenName, "
             . "MaritalStatus, "
             . "Spouse, "
             . "Children, "
             . "HomeAddress, "
             . "HomeCity, "
             . "HomeState, "
             . "Phone, "
             . "EmailAddy, "
             . "Website, "
             . "Profession, "
             . "Employed, "
             . "About, "
             . "Picture, "
             . "LoginID, "
             . "Password"
             . ") VALUES ("
             . $this->ToSQL($this->YearGrad->GetDBValue(), $this->YearGrad->DataType) . ", "
             . $this->ToSQL($this->FirstName->GetDBValue(), $this->FirstName->DataType) . ", "
             . $this->ToSQL($this->LastName->GetDBValue(), $this->LastName->DataType) . ", "
             . $this->ToSQL($this->MaidenName->GetDBValue(), $this->MaidenName->DataType) . ", "
             . $this->ToSQL($this->MaritalStatus->GetDBValue(), $this->MaritalStatus->DataType) . ", "
             . $this->ToSQL($this->Spouse->GetDBValue(), $this->Spouse->DataType) . ", "
             . $this->ToSQL($this->Children->GetDBValue(), $this->Children->DataType) . ", "
             . $this->ToSQL($this->HomeAddress->GetDBValue(), $this->HomeAddress->DataType) . ", "
             . $this->ToSQL($this->HomeCity->GetDBValue(), $this->HomeCity->DataType) . ", "
             . $this->ToSQL($this->HomeState->GetDBValue(), $this->HomeState->DataType) . ", "
             . $this->ToSQL($this->Phone->GetDBValue(), $this->Phone->DataType) . ", "
             . $this->ToSQL($this->EmailAddy->GetDBValue(), $this->EmailAddy->DataType) . ", "
             . $this->ToSQL($this->Website->GetDBValue(), $this->Website->DataType) . ", "
             . $this->ToSQL($this->Profession->GetDBValue(), $this->Profession->DataType) . ", "
             . $this->ToSQL($this->Employed->GetDBValue(), $this->Employed->DataType) . ", "
             . $this->ToSQL($this->About->GetDBValue(), $this->About->DataType) . ", "
             . $this->ToSQL($this->Picture->GetDBValue(), $this->Picture->DataType) . ", "
             . $this->ToSQL($this->LoginID->GetDBValue(), $this->LoginID->DataType) . ", "
             . $this->ToSQL($this->Password->GetDBValue(), $this->Password->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
    }
//End Insert Method

//Update Method @2-DF8813F7
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $SQL = "UPDATE alm_alumni SET "
             . "YearGrad=" . $this->ToSQL($this->YearGrad->GetDBValue(), $this->YearGrad->DataType) . ", "
             . "FirstName=" . $this->ToSQL($this->FirstName->GetDBValue(), $this->FirstName->DataType) . ", "
             . "LastName=" . $this->ToSQL($this->LastName->GetDBValue(), $this->LastName->DataType) . ", "
             . "MaidenName=" . $this->ToSQL($this->MaidenName->GetDBValue(), $this->MaidenName->DataType) . ", "
             . "MaritalStatus=" . $this->ToSQL($this->MaritalStatus->GetDBValue(), $this->MaritalStatus->DataType) . ", "
             . "Spouse=" . $this->ToSQL($this->Spouse->GetDBValue(), $this->Spouse->DataType) . ", "
             . "Children=" . $this->ToSQL($this->Children->GetDBValue(), $this->Children->DataType) . ", "
             . "HomeAddress=" . $this->ToSQL($this->HomeAddress->GetDBValue(), $this->HomeAddress->DataType) . ", "
             . "HomeCity=" . $this->ToSQL($this->HomeCity->GetDBValue(), $this->HomeCity->DataType) . ", "
             . "HomeState=" . $this->ToSQL($this->HomeState->GetDBValue(), $this->HomeState->DataType) . ", "
             . "Phone=" . $this->ToSQL($this->Phone->GetDBValue(), $this->Phone->DataType) . ", "
             . "EmailAddy=" . $this->ToSQL($this->EmailAddy->GetDBValue(), $this->EmailAddy->DataType) . ", "
             . "Website=" . $this->ToSQL($this->Website->GetDBValue(), $this->Website->DataType) . ", "
             . "Profession=" . $this->ToSQL($this->Profession->GetDBValue(), $this->Profession->DataType) . ", "
             . "Employed=" . $this->ToSQL($this->Employed->GetDBValue(), $this->Employed->DataType) . ", "
             . "About=" . $this->ToSQL($this->About->GetDBValue(), $this->About->DataType) . ", "
             . "Picture=" . $this->ToSQL($this->Picture->GetDBValue(), $this->Picture->DataType) . ", "
             . "LoginID=" . $this->ToSQL($this->LoginID->GetDBValue(), $this->LoginID->DataType) . ", "
             . "Password=" . $this->ToSQL($this->Password->GetDBValue(), $this->Password->DataType);
        $SQL = CCBuildSQL($SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
    }
//End Update Method

//Delete Method @2-4B8A1149
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $SQL = "DELETE FROM alm_alumni WHERE " . $this->Where;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
    }
//End Delete Method

} //End alm_alumniDataSource Class @2-FCB6E20C

//Include Page implementation @29-B991DFB8
include(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-4D186B84
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

$FileName = "alumni_add.php";
$Redirect = "";
$TemplateFileName = "alumni_add.html";
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
