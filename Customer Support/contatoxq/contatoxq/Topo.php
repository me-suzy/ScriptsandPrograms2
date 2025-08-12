<?php

class clsGridTopoTopoMenu { //TopoMenu class @2-00110DE8

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

//Class_Initialize Event @2-C5782D49
    function clsGridTopoTopoMenu($RelativePath = "")
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "TopoMenu";
        $this->Visible = True;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid TopoMenu";
        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->ImageLink4 = new clsControl(ccsImageLink, "ImageLink4", "ImageLink4", ccsText, "", CCGetRequestParam("ImageLink4", ccsGet));
        $this->ImageLink4->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
        $this->ImageLink4->Page = "Login.php";
        $this->ImageLink3 = new clsControl(ccsImageLink, "ImageLink3", "ImageLink3", ccsText, "", CCGetRequestParam("ImageLink3", ccsGet));
        $this->ImageLink3->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
        $this->ImageLink3->Page = "Conta.php";
        $this->ImageLink2 = new clsControl(ccsImageLink, "ImageLink2", "ImageLink2", ccsText, "", CCGetRequestParam("ImageLink2", ccsGet));
        $this->ImageLink2->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
        $this->ImageLink2->Page = "Contatar.php";
    }
//End Class_Initialize Event

//Initialize Method @2-5D060BAC
    function Initialize()
    {
        if(!$this->Visible) return;
    }
//End Initialize Method

//Show Method @2-56D74B94
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
        $this->ImageLink4->Show();
        $this->ImageLink3->Show();
        $this->ImageLink2->Show();
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

} //End TopoMenu Class @2-FCB6E20C

class clsTopo { //Topo class @1-944E9E9C

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

//Class_Initialize Event @1-B1DEEA95
    function clsTopo($RelativePath, $ComponentName)
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = $ComponentName;
        $this->RelativePath = $RelativePath;
        $this->Visible = true;
        $this->FileName = "Topo.php";
        $this->Redirect = "";
        $this->TemplateFileName = "Topo.html";
        $this->BlockToParse = "main";
        $this->TemplateEncoding = "ISO-8859-1";
    }
//End Class_Initialize Event

//Class_Terminate Event @1-5B9BE688
    function Class_Terminate()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUnload");
        unset($this->TopoMenu);
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

//Initialize Method @1-2B27342D
    function Initialize()
    {
        global $FileName;
        global $CCSLocales;
        if(!$this->Visible)
            return "";

        // Create Components
        $this->TopoMenu = new clsGridTopoTopoMenu($this->RelativePath);
        $this->BindEvents();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnInitializeView");
    }
//End Initialize Method

//Show Method @1-0A95C3BF
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        $block_path = $Tpl->block_path;
        $Tpl->LoadTemplate("/" . $this->TemplateFileName, $this->ComponentName, $this->TemplateEncoding, "remove");
        $Tpl->block_path = $Tpl->block_path . "/" . $this->ComponentName;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $block_path;
            $Tpl->SetVar($this->ComponentName, "");
            return "";
        }
        $this->TopoMenu->Show();
        $Tpl->Parse();
        $Tpl->block_path = $block_path;
        $Tpl->SetVar($this->ComponentName, $Tpl->GetVar($this->ComponentName));
    }
//End Show Method

} //End Topo Class @1-FCB6E20C


?>
