<?php
        
//File Description @0-D2828888
//======================================================
//
//  This file contains the following classes:
//      class clsSQLParameters
//      class clsSQLParameter
//      class clsControl
//      class clsField
//      class clsButton
//      class clsErrors
//
//======================================================
//End File Description

//Constant List @0-D80E6D25

// ------- Controls ---------------
define("ccsLabel",       "00001");
define("ccsLink",        "00002");
define("ccsTextBox",     "00003");
define("ccsTextArea",    "00004");
define("ccsListBox",     "00005");
define("ccsRadioButton", "00006");
define("ccsButton",      "00007");
define("ccsCheckBox",    "00008");
define("ccsImage",       "00009");
define("ccsImageLink",   "00010");
define("ccsHidden",      "00011");

// ------- Operators --------------
define("opEqual", "00001");
define("opNotEqual", "00002");
define("opLessThan", "00003");
define("opLessThanOrEqual", "00004");
define("opGreaterThan", "00005");
define("opGreaterThanOrEqual", "00006");
define("opBeginsWith", "00007");
define("opNotBeginsWith", "00008");
define("opEndsWith", "00009");
define("opNotEndsWith", "00010");
define("opContains", "00011");
define("opNotContains", "00012");
define("opIsNull", "00013");
define("opNotNull", "00014");

// ------- Datasource types -------
define("dsTable", 1);
define("dsSQL", 2);
define("dsProcedure", 3);
define("dsListOfValues", 4);
define("dsEmpty", 5);

// ------- CheckBox states --------
define("ccsChecked", true);
define("ccsUnchecked", false);


//End Constant List

//clsSQLParameters Class @0-8C61AF9E

class clsSQLParameters
{
  
  var $Connection;
  var $Criterion;
  var $AssembledWhere;
  var $Errors;
  var $DataSource;
  var $AllParametersSet;

  var $Parameters;

  function clsSQLParameters()
  {
  }

  function SetParameters($strName, $strNewParam)
  {
    $this->Parameters[$strName] = $strNewParam;
  }

  function AddParameter($ParameterID, $ParameterSource, $DataType, $Format, $DBFormat, $InitValue, $DefaultValue)
  {
    $this->Parameters[$ParameterID] = new clsSQLParameter($ParameterSource, $DataType, $Format, $DBFormat, $InitValue, $DefaultValue);
  }

  function AllParamsSet()
  {
    $blnResult = true;

    reset($this->Parameters);
    while ($blnResult && list ($key, $Parameter) = each ($this->Parameters)) 
    {
      if(!strlen($Parameter->GetValue())) 
        $blnResult = false;
    }
    return $blnResult;
  }

  function GetDBValue($ParameterID)
  {
    return $this->Parameters[$ParameterID]->GetDBValue();
  }

  function opAND($Brackets, $strLeft, $strRight)
  {
    $strResult = "";
    if (strlen($strLeft))
    {
      if (strlen($strRight)) 
      {
        $strResult = $strLeft . " AND " . $strRight;
        if ($Brackets) 
          $strResult = " (" . $strResult . ") ";
      }
      else
      {
        $strResult = $strLeft;
      }
    }
    else
    {
      if (strlen($strRight)) 
        $strResult = $strRight;
    }
    return $strResult;
  }

  function opOR($Brackets, $strLeft, $strRight)
  {
    $strResult = "";
    if (strlen($strLeft))
    {
      if (strlen($strRight))
      {
        $strResult = $strLeft . " OR " . $strRight;
        if ($Brackets) 
          $strResult = " (" . $strResult . ") ";
      }
      else
      {
        $strResult = $strLeft;
      }
    }
    else
    {
      if (strlen($strRight))
        $strResult = $strRight;
    }
    return $strResult;
  }

  function Operation($Operation, $FieldName, $DBValue, $SQLText)
  {
    $Result = "";

    if(strlen($DBValue) || $DBValue === false)
    {
      $SQLValue = $SQLText;
      if(substr($SQLValue, 0, 1) == "'")
        $SQLValue = substr($SQLValue, 1, strlen($SQLValue) - 2);

      switch ($Operation)
      {
        case opEqual:
          $Result = $FieldName . " = " . $SQLText;
          break;
        case opNotEqual:
          $Result = $FieldName . " <> " . $SQLText;
          break;
        case opLessThan:
          $Result = $FieldName . " < " . $SQLText;
          break;
        case opLessThanOrEqual:
          $Result = $FieldName . " <= " . $SQLText;
          break;
        case opGreaterThan:
          $Result = $FieldName . " > " . $SQLText;
          break;
        case opGreaterThanOrEqual:
          $Result = $FieldName . " >= " . $SQLText;
          break;                                
        case opBeginsWith:
          $Result = $FieldName . " like '" . $SQLValue . "%'";
          break;
        case opNotBeginsWith:
          $Result = $FieldName . " not like '" . $SQLValue . "%'";
          break;
        case opEndsWith:
          $Result = $FieldName . " like '%" . $SQLValue . "'";
          break;
        case opNotEndsWith:
          $Result = $FieldName . " not like '%" . $SQLValue . "'";
          break;
        case opContains:
          $Result = $FieldName . " like '%" . $SQLValue . "%'";
          break;
        case opNotContains:
          $Result = $FieldName . " not like '%" . $SQLValue . "%'";
          break;
        case opIsNull:
          $Result = $FieldName . " IS NULL";
          break;
        case opNotNull:
          $Result = $FieldName . " IS NOT NULL";
          break;
      }
    }

    return $Result;
  }
}
//End clsSQLParameters Class

//clsSQLParameter Class @0-9934C702
class clsSQLParameter
{
  var $Errors;
  var $DataType;
  var $Format;
  var $DBFormat;
  var $Link;
  var $Caption;

  var $Value;
  var $DBValue;
  var $Text;

  function clsSQLParameter($ParameterSource, $DataType, $Format, $DBFormat, $InitValue, $DefaultValue)
  {
    $this->Caption = $ParameterSource;
    $this->DataType = $DataType;
    $this->Format = $Format;
    $this->DBFormat = $DBFormat;
    if(strlen($InitValue))
      $this->SetText($InitValue);
    else
      $this->SetValue($DefaultValue);
    $this->Errors = new clsErrors;
  }

  function GetParsedValue($ParsingValue, $Format)
  {
    $varResult = "";

    if (strlen($ParsingValue))
    {
      switch ($this->DataType)
      {
        case ccsDate:
          if (CCValidateDate($ParsingValue, $Format))
            $varResult = CCParseDate($ParsingValue, $Format);
          else
          {
            if (is_array($Format))
              echo "The value in field " . $this->Caption . " is not valid. Use the following format: " . join("", $this->Format) . " ($ParsingValue)";
            else 
              echo "The value in field " . $this->Caption . " is not valid. ($ParsingValue)";
            exit;
          }
          break;
        case ccsBoolean:
          $varResult = CCParseBoolean($ParsingValue, $Format);
          break;
        case ccsInteger:
          if (CCValidateNumber($ParsingValue, $Format))
            $varResult = CCParseInteger($ParsingValue, $Format);
          else
          {
            echo "The value in field " . $this->Caption . " is not valid. ($ParsingValue)";
            exit;
          }
          break;
        case ccsFloat:
          if (CCValidateNumber($ParsingValue, $Format) )
            $varResult = CCParseFloat($ParsingValue, $Format);
          else 
          {
            echo "The value in field " . $this->Caption . " is not valid. ($ParsingValue)";
            exit;
          }
          break;
        case ccsText:
        case ccsMemo:
          $varResult = strval($ParsingValue);
          break;
      }
    }

    return $varResult;
  }

  function GetFormatedValue($Format)
  {
    $strResult = "";
    switch($this->DataType)
    {
      case ccsDate:
        $strResult = CCFormatDate($this->Value, $Format);
        break;
      case ccsBoolean:
        $strResult = CCFormatBoolean($this->Value, $Format);
        break;
      case ccsInteger:
      case ccsFloat:
        $strResult = CCFormatNumber($this->Value, $Format);
        break;
      case ccsText:
      case ccsMemo:
        $strResult = strval($this->Value);
        break;
    }
    return $strResult;
  }

  function SetValue($Value)
  {
    $this->Value = $Value;
    $this->Text = $this->GetFormatedValue($this->Format);
    $this->DBValue = $this->GetFormatedValue($this->DBFormat);
  }

  function SetText($Text)
  {
    $this->Text = $Text;
    $this->Value = $this->GetParsedValue($this->Text, $this->Format);
    $this->DBValue = $this->GetFormatedValue($this->DBFormat);
  }

  function SetDBValue($DBValue)
  {
    $this->DBValue = $DBValue;
    $this->Value = $this->GetParsedValue($this->DBValue, $this->DBFormat);
    $this->Text = $this->GetFormatedValue($this->Format);
  }

  function GetValue()
  {
    return $this->Value;
  }

  function GetText()
  {
    if(!strlen($this->Text) && strlen($this->Value))
      $this->Text = $this->GetFormatedValue($this->Format);
    return $this->Text;
  }

  function GetDBValue()
  {
    if(!strlen($this->DBValue) && strlen($this->Value))
      $this->DBValue = $this->GetFormatedValue($this->DBFormat);
    return $this->DBValue;
  }

}

//End clsSQLParameter Class

//clsControl Class @0-CC7327A0
Class clsControl
{
  var $Errors;
  var $DataType;
  var $Format;
  var $DBFormat;
  var $Caption;
  var $ControlType;
  var $Name;
  var $HTML;
  var $Required;
  var $CheckedValue;
  var $UncheckedValue;
  var $State;

  var $Page;
  var $Parameters;

  var $Value;
  var $Text;
  var $Values;

  var $CCSEvents;
  var $CCSEventResult;

  function clsControl($ControlType, $Name, $Caption, $DataType, $Format, $InitValue)
  {
    $this->Value = "";
    $this->Text = "";
    $this->Page = "";
    $this->Parameters = "";
    $this->CCSEvents = "";
    $this->Values = "";

    $this->Required = false;
    $this->HTML = false;

    $this->Errors = new clsErrors;

    $this->Name = $Name;
    $this->ControlType = $ControlType;
    $this->DataType = $DataType;
    $this->Format = $Format;
    $this->Caption = $Caption;
    if(strlen($InitValue))
      $this->SetText($InitValue);
  }

  function Validate()
  {
    $validation = true;
    if($this->Required && $this->Value == "" && $this->Errors->Count() == 0)
    {
      $FieldName = strlen($this->Caption) ? $this->Caption : $this->Name;
      $this->Errors->addError("The value in field " . $FieldName . " is required.");
    }
    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
    return ($this->Errors->Count() == 0);
  }

  function GetParsedValue()
  {
    $varResult = "";

    if(strlen($this->Text))
    {
      switch ($this->DataType)
      {
        case ccsDate:
          if (CCValidateDate($this->Text, $this->Format))
          {
            $varResult = CCParseDate($this->Text, $this->Format);
          }
          else
          {
            if (is_array($this->Format))
              $this->Errors->addError("The value in field " . $this->Caption . " is not valid. Use the following format: " . join("", $this->Format) . "");
            else 
              $this->Errors->addError("The value in field " . $this->Caption . " is not valid.");
          }
          break;
        case ccsBoolean:
          $varResult = CCParseBoolean($this->Text, $this->Format);
          break;
        case ccsInteger:
          if (CCValidateNumber($this->Text, $this->Format))
            $varResult = CCParseInteger($this->Text, $this->Format);
          else 
            $this->Errors->addError("The value in field " . $this->Caption . " is not valid.");
          break;
        case ccsFloat:
          if (CCValidateNumber($this->Text, $this->Format))
            $varResult = CCParseFloat($this->Text, $this->Format);
          else 
            $this->Errors->addError("The value in field " . $this->Caption . " is not valid.");
          break;
        case ccsText:
        case ccsMemo:
          $varResult = strval($this->Text);
          break;
      }
    }

    return $varResult;
  }

  function GetFormatedValue()
  {
    $strResult = "";
    switch($this->DataType)
    {
      case ccsDate:
        $strResult = CCFormatDate($this->Value, $this->Format);
        break;
      case ccsBoolean:
        $strResult = CCFormatBoolean($this->Value, $this->Format);
        break;
      case ccsInteger:
      case ccsFloat:
        $strResult = CCFormatNumber($this->Value, $this->Format);
        break;
      case ccsText:
      case ccsMemo:
        $strResult = strval($this->Value);
        break;
    }
    return $strResult;
  }

  function Show()
  {
    global $Tpl;
    $this->EventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
    if(!strlen($this->Text) && strlen($this->Value))
      $this->Text = $this->GetFormatedValue($this->Format);
    switch($this->ControlType)
    {
      case ccsLabel:
        if ($this->HTML)
          $Tpl->SetVar($this->Name, $this->Text);
        else 
          $Tpl->SetVar($this->Name, nl2br(htmlspecialchars($this->Text)));
        break;
      case ccsTextBox:
      case ccsTextArea:
      case ccsImage:
      case ccsHidden:
        if ($this->HTML)
          $Tpl->SetVar($this->Name, $this->Text);
        else 
          $Tpl->SetVar($this->Name, htmlspecialchars($this->Text));
        break;
      case ccsLink:
        if ($this->HTML)
          $Tpl->SetVar($this->Name, $this->Text);
        else 
          $Tpl->SetVar($this->Name, nl2br(htmlspecialchars($this->Text)));
        $Tpl->SetVar($this->Name . "_Src", $this->GetLink());
        break;
      case ccsImageLink:
        if ($this->HTML)
          $Tpl->SetVar($this->Name . "_Src", $this->Text);
        else 
          $Tpl->SetVar($this->Name . "_Src", htmlspecialchars($this->Text));
        $Tpl->SetVar($this->Name, $this->GetLink());
        break;
      case ccsCheckBox:
        if($this->Value == $this->CheckedValue)
          $Tpl->SetVar($this->Name, "checked");
        else
          $Tpl->SetVar($this->Name, "");
        break;
      case ccsRadioButton:
        $BlockToParse = "RadioButton " . $this->Name;
        $Tpl->SetBlockVar($BlockToParse, "");
        if(is_array($this->Values))
        {
          for($i = 0; $i < sizeof($this->Values); $i++)
          {
            $Value = $this->Values[$i][0];
            $Text = $this->Values[$i][1];
            $Selected = ($Value == $this->Value) ? " checked" : "";
            $Tpl->SetVar("Value", $Value);
            $Tpl->SetVar("Check", $Selected);
            $Tpl->SetVar("Description", $Text);
            $Tpl->Parse($BlockToParse, true);
          }
        }
        break;
      case ccsListBox:
        $Options = "";
        if(is_array($this->Values))
        {
          for($i = 0; $i < sizeof($this->Values); $i++)
          {
            $Value = $this->Values[$i][0];
            $Text = $this->Values[$i][1];
            $Selected = ($Value == $this->Value) ? " selected" : "";
            $Options .= "<option value=\"" . $Value . "\" " . $Selected . ">" . $Text . "</option>";
          }
        }
        $Tpl->SetVar($this->Name . "_Options", $Options);
        break;

    }
    
  }

  function SetValue($Value)
  {
    $this->Value = $Value;
    $this->Text = $this->GetFormatedValue();
  }

  function SetText($Text)
  {
    $this->Text = $Text;
    $this->Value = $this->GetParsedValue();
  }

  function GetValue()
  {
    if($this->ControlType == ccsCheckBox)
      $this->Value = ($this->Value == $this->CheckedValue) ? $this->CheckedValue : $this->UncheckedValue;

    if(!strlen($this->Value) && strlen($this->Text))
      $this->Value = $this->GetParsedValue();
    return $this->Value;
  }

  function GetText()
  {
    if(!strlen($this->Text) && strlen($this->Value))
      $this->Text = $this->GetFormatedValue();
    return $this->Text;
  }

  function GetLink()
  {
    if($this->Parameters == "")
      return $this->Page;
    else
      return $this->Page . "?" . $this->Parameters;
  }

  function SetLink($Link)
  {
    if(!strlen($Link))
    {
      $this->Page = "";
      $this->Parameters = "";
    }
    else
    {
      $LinkParts = split("?", $Link);
      $this->Page = $LinkParts[0];
      $this->Parameters = (sizeof($LinkParts) == 2) ? $LinkParts[1] : "";
    }
  }

}

//End clsControl Class

//clsField Class @0-AB4536BD
class clsField
{
  var $DataType;
  var $DBFormat;
  var $Name;
  var $Errors;

  var $Value;
  var $DBValue;

  function clsField($Name, $DataType, $DBFormat)
  {
    $this->Value = "";
    $this->DBValue = "";

    $this->Name = $Name;
    $this->DataType = $DataType;
    $this->DBFormat = $DBFormat;

    $this->Errors = new clsErrors;
  }

  function GetParsedValue()
  {
    $varResult = "";

    if (strlen($this->DBValue))
    {
      switch ($this->DataType)
      {
        case ccsDate:
          if (CCValidateDate($this->DBValue, $this->DBFormat))
          {
            $varResult = CCParseDate($this->DBValue, $this->DBFormat);
          }
          else
          {
            if (is_array($this->DBFormat))
              $this->Errors->addError("The value in field " . $this->Name . " is not valid. Use the following format: " . join("", $this->DBFormat) . "");
            else 
              $this->Errors->addError("The value in field " . $this->Name . " is not valid.");
          }
          break;
        case ccsBoolean:
          $varResult = CCParseBoolean($this->DBValue, $this->DBFormat);
          break;
        case ccsInteger:
          if (CCValidateNumber($this->DBValue, $this->DBFormat))
            $varResult = CCParseInteger($this->DBValue, $this->DBFormat);
          else 
            $this->Errors->addError("The value in field " . $this->Name . " is not valid.");
          break;
        case ccsFloat:
          if (CCValidateNumber($this->DBValue, $this->DBFormat) )
            $varResult = CCParseFloat($this->DBValue, $this->DBFormat);
          else 
            $this->Errors->addError("The value in field " . $this->Name . " is not valid.");
          break;
        case ccsText:
        case ccsMemo:
          $varResult = strval($this->DBValue);
          break;
      }
    }

    return $varResult;
  }

  function GetFormatedValue()
  {
    $strResult = "";
    switch($this->DataType)
    {
      case ccsDate:
        $strResult = CCFormatDate($this->Value, $this->DBFormat);
        break;
      case ccsBoolean:
        $strResult = CCFormatBoolean($this->Value, $this->DBFormat);
        break;
      case ccsInteger:
      case  ccsFloat:
        $strResult = CCFormatNumber($this->Value, $this->DBFormat);
        break;
      case ccsText:
      case ccsMemo:
        $strResult = strval($this->Value);
        break;
    }
    return $strResult;
  }

  function SetDBValue($DBValue)
  {
    $this->DBValue = $DBValue;
    $this->Value = $this->GetParsedValue();
  }

  function SetValue($Value)
  {
    $this->Value = $Value;
    $this->DBValue = $this->GetFormatedValue();
  }

  function GetValue()
  {
    if(!strlen($this->Value) && strlen($this->DBValue))
      $this->Value = $this->GetParsedValue();
    return $this->Value;
  }

  function GetDBValue()
  {
    if(!strlen($this->DBValue) && strlen($this->Value))
      $this->DBValue = $this->GetFormatedValue();
    return $this->DBValue;
  }
}

//End clsField Class

//clsButton Class @0-7305BAD5
Class clsButton
{
  var $Name;
  var $Visible;

  var $CCSEvents = "";
  var $CCSEventResult;

  function clsButton($Name)
  {
    $this->Name = $Name;
    $this->CCSEvents["OnClick"] = true;
    $this->Visible = true;
  }

  function Show()
  {
    global $Tpl;
    if($this->Visible)
    {
      $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
      $Tpl->Parse("Button " . $this->Name, false);
    }
  }

}

//End clsButton Class

//clsErrors Class @0-49BD6ECA
class clsErrors
{
  var $Errors;
  var $ErrorsCount;
  var $ErrorDelimiter;

  function clsErrors()
  {
    $this->Errors = array();
    $this->ErrorsCount = 0;
    $this->ErrorDelimiter = "<br>";
  }

  function addError($Description)
  {
    if (strlen($Description))
    {
      $this->Errors[$this->ErrorsCount] = $Description; 
      $this->ErrorsCount++;
    }
  }

  function AddErrors($Errors)
  {
    for($i = 0; $i < $Errors->Count(); $i++)
      $this->addError($Errors[$i]);
  }

  function Clear()
  {
    $this->ErrorsCount = 0;
    unset ($this->Errors);
  }

  function Count()
  {
    return $this->ErrorsCount;
  }

  function ToString()
  {

    if(sizeof($this->Errors) > 0)
      return join($this->ErrorDelimiter, $this->Errors) . $this->ErrorDelimiter;
    else
      return "";
  }

}
//End clsErrors Class


?>
