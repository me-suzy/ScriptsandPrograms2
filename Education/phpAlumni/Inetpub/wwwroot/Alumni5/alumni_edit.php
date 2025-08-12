<?php
//Include Common Files @1-5471E0F2
define("RelativePath", ".");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @46-DC989187
include(RelativePath . "/Header.php");
//End Include Page implementation

Class clsRecordalm_alumni1 { //alm_alumni1 Class @20-C3E17219

//Variables @20-90DA4C9A

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

//Class_Initialize Event @20-9D2BF74E
    function clsRecordalm_alumni1()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ds = new clsalm_alumni1DataSource();
        $this->Visible = (CCSecurityAccessCheck("") == "success");
        if($this->Visible)
        {
            $this->ComponentName = "alm_alumni1";
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $this->ComponentName);
            $CCSForm = CCGetFromGet("ccsForm", "");
            $this->FormSubmitted = ($CCSForm == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->YearGrad = new clsControl(ccsTextBox, "YearGrad", "Year Grad", ccsText, "", CCGetRequestParam("YearGrad", $Method));
            $this->LastName = new clsControl(ccsTextBox, "LastName", "Last Name", ccsText, "", CCGetRequestParam("LastName", $Method));
            $this->FirstName = new clsControl(ccsTextBox, "FirstName", "First Name", ccsText, "", CCGetRequestParam("FirstName", $Method));
            $this->MaidenName = new clsControl(ccsTextBox, "MaidenName", "Maiden Name", ccsText, "", CCGetRequestParam("MaidenName", $Method));
            $this->MaritalStatus = new clsControl(ccsListBox, "MaritalStatus", "Marital Status", ccsText, "", CCGetRequestParam("MaritalStatus", $Method));
            $this->MaritalStatus_ds = new clsDBAlumni();
            $this->MaritalStatus_ds->SQL = "SELECT *  " .
"FROM alm_mstatus";
            $MaritalStatus_values = CCGetListValues($this->MaritalStatus_ds, $this->MaritalStatus_ds->SQL, $this->MaritalStatus_ds->Where, $this->MaritalStatus_ds->Order, "status", "status");
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
            if(!$this->FormSubmitted) {
                if(!strlen($this->MaritalStatus->GetValue()))
                    $this->MaritalStatus->SetValue(Single);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @20-537EA73F
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["sesUserID"] = CCGetSession("UserID");
    }
//End Initialize Method

//Validate Method @20-4E5416FD
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->YearGrad->Validate() && $Validation);
        $Validation = ($this->LastName->Validate() && $Validation);
        $Validation = ($this->FirstName->Validate() && $Validation);
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

//Operation Method @20-93EEF7EA
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
        $Redirect = "alumni_view.php?" . CCGetQueryString("QueryString", Array("Insert","Update","Delete","ccsForm"));
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

//InsertRow Method @20-313436F2
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        $this->ds->YearGrad->SetValue($this->YearGrad->GetValue());
        $this->ds->LastName->SetValue($this->LastName->GetValue());
        $this->ds->FirstName->SetValue($this->FirstName->GetValue());
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

//UpdateRow Method @20-3B5548A5
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        $this->ds->YearGrad->SetValue($this->YearGrad->GetValue());
        $this->ds->LastName->SetValue($this->LastName->GetValue());
        $this->ds->FirstName->SetValue($this->FirstName->GetValue());
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

//DeleteRow Method @20-A9D87FED
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

//Show Method @20-B7433A5E
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
                    echo "Error in Record alm_alumni1";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->YearGrad->SetValue($this->ds->YearGrad->GetValue());
                        $this->LastName->SetValue($this->ds->LastName->GetValue());
                        $this->FirstName->SetValue($this->ds->FirstName->GetValue());
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
            $Error .= $this->LastName->Errors->ToString();
            $Error .= $this->FirstName->Errors->ToString();
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
        $this->LastName->Show();
        $this->FirstName->Show();
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

} //End alm_alumni1 Class @20-FCB6E20C

class clsalm_alumni1DataSource extends clsDBAlumni {  //alm_alumni1DataSource Class @20-7B1F4B1C

//Variables @20-773CC576
    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;

    // Datasource fields
    var $YearGrad;
    var $LastName;
    var $FirstName;
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

//Class_Initialize Event @20-13D52460
    function clsalm_alumni1DataSource()
    {
        $this->Initialize();
        $this->YearGrad = new clsField("YearGrad", ccsText, "");
        $this->LastName = new clsField("LastName", ccsText, "");
        $this->FirstName = new clsField("FirstName", ccsText, "");
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

//Prepare Method @20-346A343C
    function Prepare()
    {
        $this->wp = new clsSQLParameters();
        $this->wp->AddParameter("1", "sesUserID", ccsInteger, "", "", $this->Parameters["sesUserID"], "");
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "RecordNum", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger));
        $this->wp->AssembledWhere = $this->wp->Criterion[1];
        $this->Where = $this->wp->AssembledWhere;
    }
//End Prepare Method

//Open Method @20-CE57F892
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

//SetValues Method @20-4E32ADC4
    function SetValues()
    {
        $this->YearGrad->SetDBValue($this->f("YearGrad"));
        $this->LastName->SetDBValue($this->f("LastName"));
        $this->FirstName->SetDBValue($this->f("FirstName"));
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

//Insert Method @20-6DCF3399
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $SQL = "INSERT INTO alm_alumni ("
             . "YearGrad, "
             . "LastName, "
             . "FirstName, "
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
             . $this->ToSQL($this->LastName->GetDBValue(), $this->LastName->DataType) . ", "
             . $this->ToSQL($this->FirstName->GetDBValue(), $this->FirstName->DataType) . ", "
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

//Update Method @20-57FFB2D8
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $SQL = "UPDATE alm_alumni SET "
             . "YearGrad=" . $this->ToSQL($this->YearGrad->GetDBValue(), $this->YearGrad->DataType) . ", "
             . "LastName=" . $this->ToSQL($this->LastName->GetDBValue(), $this->LastName->DataType) . ", "
             . "FirstName=" . $this->ToSQL($this->FirstName->GetDBValue(), $this->FirstName->DataType) . ", "
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

//Delete Method @20-4B8A1149
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

} //End alm_alumni1DataSource Class @20-FCB6E20C

//Include Page implementation @47-B991DFB8
include(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-3BDA596C
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

$FileName = "alumni_edit.php";
$Redirect = "";
$TemplateFileName = "alumni_edit.html";
$BlockToParse = "main";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-6AFBAAA1
CCSecurityRedirect("", "", $FileName, CCGetQueryString("QueryString", ""));
//End Authenticate User

//Initialize Objects @1-539F6270
$DBAlumni = new clsDBAlumni();

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$alm_alumni1 = new clsRecordalm_alumni1();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$alm_alumni1->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Execute Components @1-BE216BBC
$Header->Operations();
$alm_alumni1->Operation();
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

//Show Page @1-D3FF10FE
$Header->Show("Header");
$alm_alumni1->Show();
$Footer->Show("Footer");
$Tpl->PParse("main", false);
//End Show Page

//Unload Page @1-AB7622EF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
unset($Tpl);
//End Unload Page



?>
