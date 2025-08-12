<?php
class clsHeader { //Header class @1-CC982CB1

//Variables @1-E1CF4CAE
    var $FileName = "";
    var $Redirect = "";
    var $Tpl = "";
    var $TemplateFileName = "";
    var $BlockToParse = "";
    var $ComponentName = "";

    // Events;
    var $CCSEvents = "";
    var $CCSEventResult = "";
    var $TemplatePath;
    var $Enabled;
//End Variables

//Class_Initialize Event @1-D7A9B295
    function clsHeader()
    {
        $this->Enabled = true;
        if($this->Enabled)
        {
            $this->FileName = "Header.php";
            $this->Redirect = "";
            $this->TemplateFileName = "Header.html";
            $this->BlockToParse = "main";

            // Create Components
            $this->alumni_list = new clsControl(ccsLink, "alumni_list", "alumni_list", ccsText, "", CCGetRequestParam("alumni_list", ccsGet));
            $this->alumni_list->Page = "alumni_list.php";
            $this->mstatu_list = new clsControl(ccsLink, "mstatu_list", "mstatu_list", ccsText, "", CCGetRequestParam("mstatu_list", ccsGet));
            $this->mstatu_list->Page = "alumni_admin.php";
            $this->Edit = new clsControl(ccsLink, "Edit", "Edit", ccsText, "", CCGetRequestParam("Edit", ccsGet));
            $this->Edit->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
            $this->Edit->Parameters = CCAddParam($this->Edit->Parameters, "{UserID}", CCGetSession("RecordNum"));
            $this->Edit->Page = "alumni_edit.php";
        }
    }
//End Class_Initialize Event

//Class_Terminate Event @1-A3749DF6
    function Class_Terminate()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUnload");
    }
//End Class_Terminate Event

//BindEvents Method @1-236CCD5D
    function BindEvents()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInitialize");
    }
//End BindEvents Method

//Operations Method @1-F24547FA
    function Operations()
    {
        global $Redirect;
        if(!$this->Enabled)
            return "";
    }
//End Operations Method

//Initialize Method @1-61B81EE0
    function Initialize()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnInitializeView");
        if(!$this->Enabled)
            return "";
    }
//End Initialize Method

//Show Method @1-FDD628AE
    function Show($Name)
    {
        global $Tpl;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Enabled)
            return "";
        $Tpl->LoadTemplate($this->TemplatePath . $this->TemplateFileName, $Name);
        $this->alumni_list->Show();
        $this->mstatu_list->Show();
        $this->Edit->Show();
        $Tpl->Parse($Name, false);
        $Tpl->SetVar($Name, $Tpl->GetVar($Name));
    }
//End Show Method

} //End Header Class @1-FCB6E20C


?>
