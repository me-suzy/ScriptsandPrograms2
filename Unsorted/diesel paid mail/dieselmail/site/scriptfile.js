//Start of the function to validate the client side  in home page for member login
function submitForms()
{
    if (isEmail())
        return true;
    else
       return false;
}
function isEmail()
{
    if(frm2.email.value == '')
    {
	 alert ("\n The E-Mail field is blank. \n\n Please enter your E-Mail address.");
	 frm2.email.focus();
	 return false;
    }
    if (frm2.email.value.indexOf ('@',0) == -1 ||
    frm2.email.value.indexOf ('.',0) == -1) {
	alert ("\n The E-Mail field requires a \"@\" and a \".\"be used. \n\nPlease re-enter your E-Mail address.");
	frm2.email.select();
	frm2.email.focus();
	return false;
    }
        return true;
}
function submit()
{
     if(isEmailFormat())
          return true;
     else
          return false;
}
function isEmailFormat()
{
    if (frm3.emailid.value == '')
    {
	alert ("\n The E-Mail field is blank. \n\n Please enter your E-Mail address.");
	frm3.emailid.focus();
	return false;
    }
    if (frm3.email.value.indexOf ('@',0) == -1 ||
    frm3.emailid.value.indexOf ('.',0) == -1) {
	alert ("\n The E-Mail field requires a \"@\" and a \".\"be used. \n\nPlease re-enter your E-Mail address.");
	frm3.emailid.select();
	frm3.emailid.focus();
	return false;
    }
    return true;
}
function submitLogin()
{
   if(isId_Pwd() )
        return true;
   else
        return false;
}
function isId_Pwd()
{
   if(frm1.u.value == "")
   {
        alert ("\n Login Id  field is Left blank. \n\nPlease enter your Your Login details Correctly.");
        frm1.u.focus();
        return false;
   }
   if (frm1.p.value == "")
   {
        if(frm1.todo.value == "sendpass")
            return true;
        else
        {
            alert ("\n password field is Left blank. \n\nPlease enter your Your Login details Correctly.");
            if(frm1.p.value == "")
            {
                frm1.p.focus();
                return false;
            }
        }
   } 
   return true;
}
//Start of the function to validate the client side in member_regn.php page
function submitInfo(frm1) 
{
if (isFname(frm1) && isLname(frm1) && isAddress(frm1) && isCity(frm1) && isState(frm1) && isZip(frm1) && isCountry(frm1) && isPass(frm1) && isTerms(frm1))
    if (confirm("\n You are about to submit your information. \n\nYES to submit.    NO to abort."))
    {
        alert("\nYour submission will now be sent. \n\n\n Thank you for joining!");
        return true;
    }
    else
    {
        alert("\n You have chosen to abort the submission.");
        return false
    }
    else
        return false;
}

function isFname(frm1) {
    if (frm1.first_name.value == "")
    {
        alert ("\n The First Name field is blank. \n\n Please enter your first name.");
        frm1.first_name.focus();
        return false;
    }
    return true;
}

function isLname(frm1) {
    if (frm1.last_name.value == "") {
         alert ("\n The Last Name field is blank. \n\nPlease enter your last name.");
         frm1.last_name.focus();
         return false;
     }
     return true;
}

function isAddress(frm1) {
    if (frm1.address1.value == "") {
         alert ("\n The Address field is blank or it is too short. \n\nPlease enter your address correctly.");
         frm1.address1.focus();
         return false;
    }
    return true;
}

function isCity(frm1)
{
     if (frm1.city.value == "")
     {
         alert ("\n The City field is blank. \n\nPlease enter your city.");
         frm1.city.focus();
         return false;
     }
     return true;
}

function isState(frm1) {
     if (frm1.state.value == "") {
          alert ("\n You did'nt selected your State.\n\nPlease select it correctly from the List.");
          frm1.state.focus();
          return false;
     }
     return true;
}

function isZip(frm1) {
    if (frm1.zip.value == "") {
        alert ("\n The Zip code field is blank. \n\nPlease enter your Zip code.");
        frm1.zip.focus();
        return false;
    }
    return true;
}

function isCountry(frm1) {
    if (frm1.country.value == "") {
        alert ("\n You did'nt selected your Country. \n\nPlease select it correctly from the List.");
        frm1.country.focus();
        return false;
    }
    return true;
}
function isPass(frm1) {
     if (frm1.password1.value == "" || frm1.password2.value == "") {
         alert ("\n The Password Field field or Confirm Password field is  blank. \n\nPlease enter your Password .");
         frm1.password1.focus();
         return false;
     }
     if (frm1.password1.value !=  frm1.password2.value) {
     alert ("\n The value in Password Field and Confirm Password Field does'nt matches. \n\nPlease enter it correctly.");
     frm1.password1.focus();
     return false;
     }
    if (frm1.password1.value.length < 5) {
	alert ("\n The Password length should not be less than 5 charecters.");
	frm1.password1.focus();
    return false;
    }
    if (frm1.password1.value ==  frm1.password2.value) {
        return true;
    }
}
function isTerms(frm1)
{
    if(frm1.terms.checked==false)
    {
	 alert("You can't able to proceed until you accept our Terms and Conditions");
	 return false;
    }
    return true;
}

//#######Start of the function to validate the client side  in advertisement page ###########

function submitAd() {
    if(isName() && isTitle() && isAmt() && isUrl())
	if (confirm("\n You are about to submit your datas. \n\nYES to submit.    NO to abort."))
	{
	     alert("\nYour submission will now be sent. \n\n\n Thank you for placing your Advertisement in Agriya.com!");
	     return true;
	}
    else
    {
          alert("\n You have chosen to abort the submission.");
          return false
    }
    else
          return false;
}

function isName() {
    if (frm1.name.value == "")
    {
	alert ("\n The Name field is left blank. \n\n Please enter your Name.");
	frm1.name.focus();
	return false;
    }
    return true;
}

function isTitle() {
    if (frm1.title.value == "") {
	alert ("\n The Title of the Ad field is blank. \n\nPlease enter it.");
	frm1.title.focus();
	return false;
    }
    return true;
}

function isAmt() {
    if (frm1.amtpclick.value == "" ) {
	alert ("\n The Amount to be paid per Email Click is not specified. \n\nPlease specify it correctly.");
	frm1.amtpclick.focus();
	return false;
    }
    return true;
}

function isUrl()
{
    if (frm1.url.value == "")
    {
	alert ("\n The URL or your site has to be entered. \n\nPlease enter it in correct way.");
	frm1.url.focus();
	return false;
    }
    return true;
}

////############function related to getad.php  page##########

function charge()
{
    switch (frm2.adtype.selectedindex)
    {
	case 0:
		frm2.cost.value="";
		alert("Select the Ad Type from the list");
		break;
	case 1:
		frm2.cost.value="20";
		break;
	case 2:
		frm2.cost.value="50";
		break;
    }
}

function submitAddetails(){
/*if(isEmail() && isname() && isAd() && iscost() && isAgree())
if (confirm("\n You are about to submit your datas. \n\nYES to submit.    NO to abort."))
{
alert("\nYour submission will now be Sent to PayPal.com.You will be receiving a mail \n\t automatically as and when you pay the whole amount");
return true;
}
else
{
alert("\n You have chosen to abort the submission.");
return false
}
else
return false;*/
}

function isname() {
    if (frm2.name.value == "")
    {
         alert ("\n The Name field is left blank. \n\n Please enter your Name.");
         frm2.name.focus();
         return false;
    }
    return true;
}

function iscost() {
    if (frm2.cost.value == "" ) {
	alert ("\n The Amount to be paid per Email Click is not specified. \n\nPlease specify it correctly.");
	frm2.cost.focus();
        return false;
    }
    return true;
}

function isAgree()
{
    if(frm2.agree.checked==false)
    {
	alert("You can't able to proceed until you provide a valid Information.\n\nIf the information is a valid Information please click the Check Box");
	frm2.agree.focus();
	return false;
    }
    return true;
}

function isAd() {
    if (frm2.adtype.value == "") {
	alert ("\n You did'nt selected Advt Type. \n\nPlease select it correctly from the List.");
	frm2.adtype.focus();
	return false;
    }
    return true;
}
function clear()
{
    frm1.u.value="";
    frm1.p.value="";
    frm1.todo.value="login";
}

