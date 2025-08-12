<?php
//BindEvents Method @1-935D8CAC
function BindEvents()
{
    global $Login;
    $Login->DoLogin->CCSEvents["OnClick"] = "Login_DoLogin_OnClick";
}
//End BindEvents Method

function Login_DoLogin_OnClick() { //Login_DoLogin_OnClick @3-AFF8EBF1

//Login @4-1C5B8B18
    global $Login;
    if(!CCLoginUser($Login->login->Value, $Login->password->Value))
    {
        $Login->Errors->addError("Login or Password is incorrect.");
        $Login->password->SetValue("");
        return false;
    }
    else
    {
        global $Redirect;
        $Redirect = CCGetParam("ret_link", $Redirect);
        return true;
    }
//End Login

} //Close Login_DoLogin_OnClick @3-FCB6E20C


?>
