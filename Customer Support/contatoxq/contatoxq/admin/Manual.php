<?php
//Include Common Files @1-5075FE32
define("RelativePath", "..");
define("PathToCurrentPage", "/admin/");
define("FileName", "Manual.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

//Include Page implementation @3-947EEF87
include_once(RelativePath . "/admin/TopoAdmin.php");
//End Include Page implementation

class clsGridManual { //Manual class @2-B9BE693B

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

//Class_Initialize Event @2-4103C0AD
    function clsGridManual($RelativePath = "")
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "Manual";
        $this->Visible = True;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid Manual";
        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

    }
//End Class_Initialize Event

//Initialize Method @2-5D060BAC
    function Initialize()
    {
        if(!$this->Visible) return;
    }
//End Initialize Method

//Show Method @2-0E2D4BAD
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

} //End Manual Class @2-FCB6E20C

//Initialize Page @1-EFBED916
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

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "Manual.html";
$BlockToParse = "main";
$TemplateEncoding = "ISO-8859-1";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-A1D50BCE

// Controls
$TopoAdmin = new clsTopoAdmin("", "TopoAdmin");
$TopoAdmin->Initialize();
$Manual = new clsGridManual();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

$Charset = $Charset ? $Charset : "iso-8859-1";
if ($Charset)
    header("Content-Type: text/html; charset=" . $Charset);
//End Initialize Objects

//Initialize HTML Template @1-7A834252
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "ISO-8859-1");
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-8B488E39
$TopoAdmin->Operations();
//End Execute Components

//Go to destination page @1-E9EB3F43
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    header("Location: " . $Redirect);
    $TopoAdmin->Class_Terminate();
    unset($TopoAdmin);
    unset($Manual);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-64F34EB6
$TopoAdmin->Show();
$Manual->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
echo $main_block;
//End Show Page

//Unload Page @1-6688C65D
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$TopoAdmin->Class_Terminate();
unset($TopoAdmin);
unset($Manual);
unset($Tpl);
//End Unload Page


?>
