/*	$Id: clseBayAppRegistrationForm.cpp,v 1.5.2.3.40.1 1999/08/01 03:01:25 barry Exp $	*/
//
//	File:	clseBayAppRegistrationForm.cpp
//
//	Class:	clseBayApp
//
//	Author:	Barry Boone (barry@ebay.com)
//
//	Function:
//
//		Shows a (dynamic) registration form
//
// Modifications:
//				- 12/7/98 Barry	- Created
//                        Internationalized registration form -- there are separate,
//                        customized static forms for the countries eBay is
//                        currently targeting. We pass the country name and id
//                        from the country picker to this function so that we
//                        can pass this information to RegsiterPreview.
//				- 06/09/99 nsacco - removed if you are from xxx text
//				- 07/07/99 nsacco - Added siteid and copartnerid hidden fields.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

void clseBayApp::ShowRegistrationForm(CEBayISAPIExtension *pServer,
								      int countryId,
									  int UsingSSL)
{
	clsCountries *pCountries;
	char pCountry[EBAY_MAX_COUNTRY_SIZE];	// petra

	// Setup
	SetUp();

	pCountries = mpMarketPlace->GetCountries();
	pCountries->SetCurrentCountry(countryId);
	pCountries->GetCountryName(countryId, pCountry);

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Registration for "
			  <<    pCountry
			  <<	"</TITLE>"
					"</HEAD>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetHeader();
	else
		*mpStream <<	mpMarketPlace->GetSecureHeader();

	*mpStream <<
	   "\n<table border=1 width=590 cellspacing=0 bgcolor=\"#99CCCC\" cellpadding=2> \n"
  	   "<tr>  \n"
	   "<td>  \n"
	   "<p align=\"center\"><strong><font size=\"5\">eBay Registration for "
	<< pCountry
	<< "</font> \n"

/*
	   "<img src=\""
	<< mpMarketPlace->GetImagePath()
	<< pCountries->GetFlagGif() 
	<< "\" width=60 height=37 align=\"absmiddle\">
*/

	<< "</strong>  \n"
	   "</td> \n"
	   "</tr> \n"
	   "</table> \n";


	*mpStream	<<	"<table border=0 cellpadding=3 cellspacing=3 width=590> \n";

/*
	// In case user is in the wrong country
	*mpStream	<<	"<tr><td width=\"100%\">";
       if (countryId == 225)
		   *mpStream << "<p><font size=\"2\">If you have an APO/FPO address, this is your "
						"registration page. If you don't have an APO/FPO address, you can "
						"also go to country-specific registration pages, such as the ";
		else
		   *mpStream << "<p><font size=\"2\">Are you from the ";

	if (UsingSSL)
	{
		*mpStream << "<a href=\""
				  << mpMarketPlace->GetSSLHTMLPath()
				  << "us/ssl-registration-show.html\">"
				  << "United States</a>, <a href=\""
				  << mpMarketPlace->GetSSLHTMLPath()
				  << "canada/ssl-registration-show.html\">"
				  << "Canada</a>, or <a href=\""
				  << mpMarketPlace->GetSSLHTMLPath()
				  << "uk/ssl-registration-show.html\">"
				  << "United Kingdom</a>?</font></p> \n";
//				  << mpMarketPlace->GetSSLHTMLPath()
//				  << "de/ssl-registration-show.html\">"
//				  << "Germany</a>?</font></p> \n";
	}
	else
	{
		*mpStream << "<a href=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "us/services/registration/register.html\">"
				  << "United States</a>, <a href=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "canada/services/registration/register.html\">"
				  << "Canada</a>, or <a href=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "services/registration/register.html\">"
				  << "United Kingdom</a>?</font></p> \n";
//				  << mpMarketPlace->GetHTMLPath()
//				  << "de/registration-show.html\">"
//				  << "Germany</a>?</font></p> \n";
	}

	*mpStream	<<	"</td></tr>\n";
*/

	*mpStream	<<	"<tr> \n"
	   "<td> <b>How to Register</b> - To register on eBay, follow the registration  \n"
	   "process below. When you complete all three steps, you can begin buying and  \n"
	   "selling on eBay.  \n"
	   "<p> <b>1) Complete the eBay Initial Registration Form </b> - Simply fill  \n"
	   "out the registration form below, review your information for accuracy,  \n"
	   "and click the Submit button.  \n"
	   "<p> <b>2) Receive Confirmation Instructions </b> - eBay will send you an  \n"
	   "e-mail message with a confirmation code.<br> \n"
	   "<font size=\"-1\">If you already have completed step 1 and you need eBay  \n"
	   "to resend your confirmation instructions <a href=\""
	<< mpMarketPlace->GetHTMLPath()
	<< "services/registration/reqtemppass.html\">  \n"
	   "click here</a>. </font>  \n"
	   "<p> <b>3) <a href=\""
	<< mpMarketPlace->GetHTMLPath()
	<< "services/registration/confirm-by-country.html\">Confirm  \n"
	   "Your Registration</a> -</b> Once you have your access code (and your e-mail  \n"
	   "address), finalize your registration by accepting the eBay User Agreement  \n"
	   "and complete the eBay Confirm your Registration form. <br> \n"
	   "<font size=\"-1\"> If you have your confirmation code and you are ready  \n"
	   "to confirm your registration, <a href=\""
	<< mpMarketPlace->GetHTMLPath()
//	<< pCountries->GetCurrentCountryDir()
	<< "services/registration/reg-confirm.html\">click  \n"
	   "here</a>.</font>  \n";

	*mpStream << 
	   "<p>  \n"
	   "<hr> \n"
	   "</td> \n"
	   "</tr> \n"
	   "</table> \n"
	   "<table border=0 cellpadding=3 cellspacing=1 width=590> \n"
	   "<tr>  \n"
	   "<td>  \n"
	   "<p> <b>Please Note:</b> To be eligible to register, you must be over 18  \n"
	   "years of age and provide valid contact information, including a valid  \n"
	   "e-mail address. <strong>eBay</strong> will not use any registration information \n" 
	   "for marketing, nor will we disclose this information to any outside party.  \n"
	   "</td> \n"
	   "<td> \n"
	   "<table border=0 cellspacing=0 bgcolor=\"#EFEFEF\" cellpadding=4> \n"
	   "<tr> \n"
	   "<td> <font size=2>If you would like to read a full explanation of  \n"
	   "our privacy policy, click on the TRUSTe button below:</font> \n"
	   "<p align=center> <a href=\""
	<< mpMarketPlace->GetHTMLPath()
	<< "services/registration/privacy-policy-reg.html\"><img src=\"";
	if (UsingSSL)
		*mpStream << mpMarketPlace->GetSSLImagePath();
	else
		*mpStream << mpMarketPlace->GetImagePath();
	*mpStream << "truste_button.gif\" width=116 height=31 alt=\"TrustE\" border=0></a></p> \n"
	   "</td> \n"
	   "</tr> \n"
	   "</table> \n"
	   "</td> \n"
	   "</tr> \n"
	   "</table> \n"
	   "<p>  \n"
	   "<table border=1 width=590 cellspacing=0 bgcolor=\"#99CCCC\" cellpadding=2> \n"
	   "<tr> \n"
	   "<td> \n"
	   "<p align=\"center\"><strong><font size=5>Step 1 - eBay Initial Registration  \n"
	   "Form</font></strong> \n"
	   "</td> \n"
	   "</tr> \n"
	   "</table> \n"
	   "Simply fill out the information below and click the <b>continue</b> button.<br> \n"
	   "<strong>Required entries </strong>are shown in <font color=\"#006600\"> <strong>green</strong></font>.  \n";

	*mpStream <<
	   "<form method=\"post\" action=\"eBayISAPI.dll\"> \n"
	   "<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"RegisterPreview\"> \n"
	   "<INPUT TYPE=HIDDEN NAME=\"UsingSSL\" VALUE=\"";
	if (UsingSSL)
		*mpStream << "1";
	*mpStream << "\">\n"
	   "<p><table border=1 width=590 cellspacing=0 cellpadding=4> \n"
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><font size=3 color=\"#006600\"> \n"
	   "<strong>E-mail address</strong></font><BR> \n"
	   "<font size=2>e.g, username@aol.com</font></td> \n"
	   "<td width=\"75%\"> \n";

	*mpStream <<
	   "<input type=\"text\" name=\"email\" size=40 maxlength=63> \n"
 	   "<font size=2 color=\"#008000\"> (required)</font><br><font size=2> \n"
	   "<B><I>Note:</I></B> AOL and WebTV Users:  Please remove any spaces from your username and add the domain suffix (<b>@aol.com</b> or <b>@webtv.net</b> to your username). \n"
	   "For example, if your username is <b>joecool</b>, your e-mail address would be <b>joecool@aol.com</b> \n"
	   "</font></td></tr> \n"
	   "<tr> \n"
	   "<td width=\"25%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\"><strong>Full name</strong><br> \n"
	   "<font color=\"#000000\" size=2>e.g. John H. Doe</font></font></td> \n"
	   "<td width=\"75%\"><input type=\"text\" name=\"name\" size=40 maxlength=63> \n"
	   "<font size=2 color=\"#008000\"> (required)</font><br> \n"
	   "<font size=2>Please include your given name, middle initial, and family name</font> </td> \n"
	   "</tr><tr><td width=\"25%\" bgcolor=\"#EFEFEF\">Company</td> \n"
	   "<td width=\"75%\"><input type=\"text\" name=\"company\" size=40 maxlength=63> \n"
	   "<font size=2> (optional)</font></td></tr> \n"
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\"><strong>Address</strong></font></td> \n"
	   "<td width=\"75%\"><input type=\"text\" name=\"address\" size=40 maxlength=\"63\"> \n"
	   "<font size=2 color=\"#008000\"> (required)</font></td></tr> \n"
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\"><strong>City</strong></font></td> \n"
	   "<td width=\"75%\"><input type=\"text\" name=\"city\" size=40 maxlength=63> \n"
	   "<font size=2 color=\"#008000\"> (required)</font></td></tr> \n"
	   "<tr> \n"
	   "<td width=\"25%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\"> <strong>Region</strong></font></td> \n"
	   "<td width=\"75%\"> \n"
	   "<input type=\"text\" name=\"state\" size=40 maxlength=50 ALIGN=\"TOP\"> \n"
	   "<font size=2 color=\"#008000\"> (required)</font></td> \n"
	   "</tr><tr> \n"
	   "<td width=\"25%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\"> <strong>Postal  \n"
 	   "Code</strong></font></td> \n"
	   "<td width=\"75%\"> \n"
	   "<input type=\"text\" name=\"zip\" size=20 maxlength=12> \n"
	   "<font size=2 color=\"#008000\"> (required)</font></td></tr> \n"
	   "<tr> \n"
	   "<td width=\"25%\" bgcolor=\"#EFEFEF\"> <font color=\"#006600\"><strong>Primary  \n"
	   "phone number<br> \n"
	   "</strong></font><font size=2>e.g., (81) (03) 1234-5678 </font><font color=\"#006600\"><strong>  \n"
	   "</strong></font></td> \n"
	   "<td width=\"75%\"><input type=\"text\" name=\"dayphone1\" size=20 maxlength=31> \n"
	   "<font size=2 color=\"#008000\"> (required)</font><font size=2> <br> \n"
	   "Include your country code and city code or area code</font></td> \n"
	   "</tr> \n"
	   "<tr> \n"
	   "<td width=\"25%\" bgcolor=\"#EFEFEF\">Secondary phone number</td> \n"
	   "<td width=\"75%\"> \n"
	   "<input type=\"text\" name=\"nightphone1\" size=20 maxlength=31> \n"
	   "<font size=2> (optional)</font></td></tr> \n"
	   "<tr> \n"
	   "<td width=\"25%\" bgcolor=\"#EFEFEF\">Fax number</td> \n"
	   "<td width=\"75%\"> \n"
	   "<input type=\"text\" name=\"faxphone1\" size=20 maxlength=31> \n"
	   "<font size=2> (optional)</font></td> \n"
	   "</tr> \n"
	   "</table> \n";

	*mpStream <<
	   "<p><table border=1 width=590 cellspacing=0 cellpadding=4> \n"
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"> \n"
	   "<strong><font size=4 color=\"#800000\">Optional Info</font></strong></td><td width=\"75%\">&nbsp; \n"
	   "</td></tr><tr><td width=\"25%\" bgcolor=\"#EFEFEF\"> \n"
	   "<font size=3>How did you first hear about eBay?</font></td><td width=\"75%\">  \n"
	   "<SELECT NAME=\"Q1\"><OPTION VALUE=18>Business Associate</OPTION> \n"
	   "<OPTION VALUE=17>Friend or Family Member</OPTION> \n"
	   "<OPTION VALUE=35>Internet Site</OPTION> \n"
	   "<OPTION VALUE=19>Media News Story</OPTION> \n"
	   "<OPTION VALUE=36>Magazine Ad</OPTION> \n"
	   "<OPTION VALUE=37>Radio Ad</OPTION> \n"
	   "<OPTION VALUE=44>Talk Show</OPTION> \n"
	   "<OPTION VALUE=21>Trade Show or Event</OPTION> \n"
	   "<OPTION VALUE=23>Other</OPTION> \n"
	   "<OPTION selected VALUE=43>Select here</OPTION> \n"
	   "</select></td></tr> \n";

	*mpStream <<
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"> \n"
	   "<font size=3>If you have a promotional priority code, please enter it: \n"
	   "</font></td><td width=\"75%\"> \n"
       "<input type=\"text\" name=\"Q17\" maxlength=2 size=2> \n"
       "&nbsp;-&nbsp; \n"
       "<input type=\"text\" name=\"Q18\" maxlength=2 size=2> \n"
       "&nbsp;-&nbsp; \n"
       "<input type=\"text\" name=\"Q19\" size=4 maxlength=4> \n"
	   "</td></tr> \n";

	*mpStream <<
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"> \n"
	   "<font size=3> \n"
	   "If a friend referred you to eBay, please enter your friend's email address: \n"
	   "</font></td><td width=\"75%\"> \n"
   	   "<input type=\"text\" name=\"Q20\" size=40 maxlength=63><br> \n"
	   "<font size=2>We can only accept e-mail addresses (i.e., ebayfriend@aol.com)</font> \n"
	   "</td></tr> \n"
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"> \n"
	   "<font size=3>Do you use eBay for personal or business purposes? \n"
	   "</font></td><td width=\"75%\"> \n"
	   "<SELECT NAME=\"Q7\"><OPTION VALUE=1>Individual</OPTION> \n"
	   "<OPTION VALUE=2>Business</OPTION> \n"
	   "<OPTION VALUE=3>Both</OPTION> \n"
	   "<OPTION selected VALUE=4>Select here</OPTION> \n"
	   "</SELECT> \n"
	   "</td></tr><tr><td width=\"25%\" bgcolor=\"#EFEFEF\"> \n"
	   "<font size=3>I am most interested in:</font></td><td width=\"75%\"> \n"
	   "<SELECT SIZE=5 NAME=\"Q14\"> <OPTION selected VALUE=0>Not Selected</OPTION> \n"
	   "<OPTION VALUE=353>Antiques (pre-1900)</OPTION> \n"
	   "<OPTION VALUE=1>Collectibles</OPTION> \n"
	   "<OPTION VALUE=160>Computers</OPTION> \n"
	   "<OPTION VALUE=195>Memorabilia</OPTION> \n"
	   "<OPTION VALUE=212>Trading Cards</OPTION> \n"
	   "<OPTION VALUE=220>Toys</OPTION> \n"
	   "<OPTION VALUE=237>Dolls, Figures</OPTION> \n"
	   "<OPTION VALUE=252>Coins</OPTION> \n"
	   "<OPTION VALUE=260>Stamps</OPTION> \n"
	   "<OPTION VALUE=266>Books, Magazines</OPTION> \n"
	   "<OPTION VALUE=281>Jewelry, Gemstones</OPTION> \n"
	   "<OPTION VALUE=99>Miscellaneous</OPTION> \n"
	   "</SELECT></td></tr><tr><td width=\"25%\" bgcolor=\"#EFEFEF\"> \n";

	*mpStream <<
	   "<font size=3>Age</font></td><td width=\"75%\"> \n"
	   "<SELECT NAME=\"Q3\"><OPTION selected VALUE=1>Select an age range</OPTION> \n"
	   "<OPTION VALUE=2>18-24</OPTION> \n"
	   "<OPTION VALUE=3>25-34</OPTION> \n"
	   "<OPTION VALUE=4>35-50</OPTION> \n"
	   "<OPTION VALUE=5>51-65</OPTION> \n"
	   "<OPTION VALUE=6>over 65</OPTION> \n"
	   "</select></td></tr> \n"
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><font size=3>Education</font></td><td width=\"75%\"> \n"
	   "<SELECT NAME=\"Q4\"><OPTION selected VALUE=1>Select an education</OPTION> \n"
	   "<OPTION VALUE =2>High School</OPTION> \n"
	   "<OPTION VALUE =3>College</OPTION> \n"
	   "<OPTION VALUE =4>Graduate School</OPTION> \n"
	   "<OPTION VALUE =5>Other</OPTION> \n"
	   "</select></td></tr> \n"
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><font size=3>Annual Household Income</font></td> \n"
	   "<td width=\"75%\"> \n"
	   "<SELECT NAME=\"Q5\"><OPTION VALUE=1>Under US$25,000</OPTION> \n"
	   "<OPTION VALUE =2>US$25,000-US$35,000</OPTION> \n"
	   "<OPTION VALUE =3>US$36,000-US$49,000</OPTION> \n"
	   "<OPTION VALUE =4>US$50,000-US$75,000</OPTION> \n"
	   "<OPTION VALUE =5>Over US$75,000</OPTION> \n"
	   "<OPTION selected VALUE =6>Select an income range</OPTION> \n"
	   "</select></td></tr> \n"
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"> \n"
	   "<font size=3>Are you interested in participating in an eBay survey?</font></td> \n"
	   "<td width=\"75%\"> \n"
	   "<SELECT NAME=\"Q16\"><OPTION VALUE =1>Yes</OPTION> \n"
	   "<OPTION VALUE =2>No</OPTION> \n"
	   "<OPTION selected VALUE =3>Select here</OPTION> \n"
	   "</select></td></tr> \n"
	   "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">Gender </td><td width=\"75%\"> \n"
	   "<SELECT NAME=\"gender\"><OPTION selected VALUE=\"u\">Unspecified</OPTION> \n"
	   "<OPTION VALUE=\"m\">Male</OPTION> \n"
	   "<OPTION VALUE=\"f\">Female</OPTION> \n"
	   "</SELECT></td></tr></table><p> \n";

	*mpStream <<
	   "<INPUT TYPE=\"HIDDEN\" NAME=\"country\" VALUE=\""
	<< pCountry
	<< "\"> \n"
	   "<INPUT TYPE=\"HIDDEN\" NAME=\"countryid\" VALUE=\""
	<< countryId
	<< "\"> \n";
	
	// nsacco 07/07/99 added siteid and copartnerid
	*mpStream <<
	   "<INPUT TYPE=\"HIDDEN\" NAME=\"siteid\" VALUE=\""
	<< mpMarketPlace->GetCurrentSiteId()
	<< "\"> \n"
	   "<INPUT TYPE=\"HIDDEN\" NAME=\"copartnerid\" VALUE=\""
	<< mpMarketPlace->GetCurrentPartnerId()
	<< "\"> \n";

	*mpStream <<
	   "<table border=0 cellpadding=3 cellspacing=1 width=590> \n"
	   "<tr><td> \n"
	   "<strong>Note to AOL Users:  \n"
	   "</strong>make sure your mail filters are off or set to accept mail from ebay.com<br>  \n"
	   "<p><blockquote><b>Click </b>&nbsp;<input type=submit value=\"continue\">&nbsp;  \n"
	   "<b>to register!</b><p></p></blockquote><p><blockquote>Click &nbsp;  \n"
       "<input type=reset value=\"clear form\">&nbsp; to start over</blockquote>  \n"
   	   "</td></tr></table></form> \n<p>";

	if (UsingSSL == 0)
	{
		*mpStream <<	mpMarketPlace->GetFooter();
	}
	else
	{
		*mpStream <<	mpMarketPlace->GetSecureFooter();
	}

	CleanUp();
	return;
}