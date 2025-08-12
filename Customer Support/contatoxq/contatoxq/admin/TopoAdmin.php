<?php
class clsGridTopoAdminTopoAdmin { //TopoAdmin class @2-313B2EE8

//Variables @2-61805F26

    // Public variables
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds;
    var $DataSource;
    var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";

    // Grid Controls
    var $StaticControls;
    var $RowControls;
//End Variables

//Class_Initialize Event @2-3A9BD057
    function clsGridTopoAdminTopoAdmin($RelativePath = "")
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "TopoAdmin";
        $this->Visible = True;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid TopoAdmin";
        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet));
        $this->Link1->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
        $this->Link1->Page = $this->RelativePath . "Listagem.php";
        $this->Link3 = new clsControl(ccsLink, "Link3", "Link3", ccsText, "", CCGetRequestParam("Link3", ccsGet));
        $this->Link3->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
        $this->Link3->Page = $this->RelativePath . "Manual.php";
        $this->Link2 = new clsControl(ccsLink, "Link2", "Link2", ccsText, "", CCGetRequestParam("Link2", ccsGet));
        $this->Link2->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
        $this->Link2->Page = "http://dev.xq.com.br";
    }
//End Class_Initialize Event

//Initialize Method @2-5D060BAC
    function Initialize()
    {
        if(!$this->Visible) return;
    }
//End Initialize Method

//Show Method @2-5D1257D8
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;


        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;

        $Tpl->parse("NoRecords", false);

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Link1->Show();
        $this->Link3->Show();
        $this->Link2->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

//GetErrors Method @2-CA1B3639
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End TopoAdmin Class @2-FCB6E20C

class clsTopoAdmin { //TopoAdmin class @1-9BD280E5

//Variables @1-D089C8BE
    var $FileName = "";
    var $Redirect = "";
    var $Tpl = "";
    var $TemplateFileName = "";
    var $BlockToParse = "";
    var $ComponentName = "";

    // Events;
    var $CCSEvents = "";
    var $CCSEventResult = "";
    var $RelativePath;
    var $Visible;
//End Variables

//Class_Initialize Event @1-78CC174D
    function clsTopoAdmin($RelativePath, $ComponentName)
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = $ComponentName;
        $this->RelativePath = $RelativePath;
        $this->Visible = true;
        $this->FileName = "TopoAdmin.php";
        $this->Redirect = "";
        $this->TemplateFileName = "TopoAdmin.html";
        $this->BlockToParse = "main";
        $this->TemplateEncoding = "UTF-8";
    }
//End Class_Initialize Event

//Class_Terminate Event @1-72909B37
    function Class_Terminate()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUnload");
        unset($this->TopoAdmin);
    }
//End Class_Terminate Event

//BindEvents Method @1-236CCD5D
    function BindEvents()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInitialize");
    }
//End BindEvents Method

//Operations Method @1-7E2A14CF
    function Operations()
    {
        global $Redirect;
        if(!$this->Visible)
            return "";
    }
//End Operations Method

//Initialize Method @1-7B681A46
    function Initialize()
    {
        global $FileName;
        global $CCSLocales;
        if(!$this->Visible)
            return "";

        // Create Components
        $this->TopoAdmin = new clsGridTopoAdminTopoAdmin($this->RelativePath);
        $this->BindEvents();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnInitializeView");
    }
//End Initialize Method

//Show Method @1-623FE58C
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        $block_path = $Tpl->block_path;
        $Tpl->LoadTemplate("/admin/" . $this->TemplateFileName, $this->ComponentName, $this->TemplateEncoding, "remove");
        $Tpl->block_path = $Tpl->block_path . "/" . $this->ComponentName;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $block_path;
            $Tpl->SetVar($this->ComponentName, "");
            return "";
        }
        $this->TopoAdmin->Show();
        $Tpl->Parse();
        $Tpl->block_path = $block_path;
        $Tpl->SetVar($this->ComponentName, $Tpl->GetVar($this->ComponentName));
    }
//End Show Method

} //End TopoAdmin Class @1-FCB6E20C


?>
