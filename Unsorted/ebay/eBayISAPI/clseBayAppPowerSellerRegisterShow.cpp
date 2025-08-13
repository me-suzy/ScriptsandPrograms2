/*	$Id: clseBayAppPowerSellerRegisterShow.cpp,v 1.2.2.1.86.3 1999/08/06 20:31:52 nsacco Exp $	*/
//
//	File:	clseBayAppPowerSellerRegisterShow.cpp
//
//	Class:	clseBayApp
//
//	Author:	Vicki Shu (vicki@ebay.com)
//
//	Function:
//
//		Handle a registration request
//
// Modifications:
//			
//				- 05/10/99 vicki	- display user's info and show PowerSeller's Agreement
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clseBayUserDemoInfoWidget.h"


// Error Messages
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry, you have not yet confirmed your registration."
"You should have received an e-mail with instructions for "
"confirming your registration. "
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register.html\">Registration</a>"
" and re-register "
"(with the same e-mail address) to have it sent to "
"you again.";
*/

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, Registration is blocked for this account. ";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.";
*/


//Powerseller agreement
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *PowerSellerAgreement =
"<P><IMG SRC=\"http://pics.ebay.com/aw/pics/green2.gif\" WIDTH=\"44\" HEIGHT=\"44\">"
"<b>Review the PowerSellers Program Terms and Conditions.</b><BR>"
"<p><h3 ALIGN=\"CENTER\">PowerSellers Member Terms and Conditions</h3>"
"Welcome to the eBay PowerSellers Program. This program gives you the opportunity to display the eBay "
"PowerSellers icon on the eBay Web site and the eBay PowerSellers logo in your physical storefront with "
"the sign provided to you in your welcome kit. To qualify for the use of the eBay PowerSellers Program, "
"you must meet the following criteria: <br>"
"<UL><LI>For each item you place up for sale on eBay, you shall guarantee that the item is in the condition described in the listing."
"<LI>Your eBay account shall be kept in good standing at all times, and you shall pay eBay for all fees due. "
"<LI>You must maintain a minimum feedback rating of 100, 98% positive eBay feedback rating at all times."
"<LI>You must meet or exceed the monthly gross sales minimum for the program for which you qualify (as follows): "
"<TABLE CELLPADDING=\"2\" CELLSPACING=\"0\" BORDER=\"0\" WIDTH=\"60%\">"
"<TR><TD WIDTH=\"23%\"><b>Gold:</b></TD>"
"<TD WIDTH=\"23%\" align=\"right\">$25,000</TD>"
"<TD WIDTH=\"10%\">&nbsp;</TD>"
"<TD WIDTH=\"43%\"><b>Feedback:</b> 100</TD>"
"</TR>"
"<TR><TD><b>Silver:</b></TD>"
"<TD align=\"right\">$10,000</TD>"
"<TD>&nbsp;</TD>"
"<TD><b>Feedback:</b> 100</TD>"
"</TR>"
"<TR><TD><b>Bronze:</b></TD>"
"<TD align=\"right\">$2,000</TD>"
"<TD>&nbsp;</TD>"
"<TD><b>Feedback:</b> 100</TD>"
"</TR>"
"</TABLE>"
"</UL>"
"<P>If you wish to be a member of the PowerSellers Program you must also agree to the guidelines set forth below. If the license terms are acceptable to you, simply continue with the Program. "
"<FONT SIZE=\"-1\">"
"<H4 ALIGN=\"CENTER\">Use Requirements and License Agreement</H4>"
"For the purposes of this Agreement, the Icon and Logo are collectively referred "
"to as the \"Marks\". The eBay PowerSellers icon (Icon) is only for use on the "
"eBay Web site. Unless authorized by eBay, you may not use the Icon or refer to "
"the icon off the eBay Web site. The eBay PowerSellers logo (Logo) is to be used "
"only on materials provided to you by eBay. You may not alter the PowerSellers "
"Logo in any manner including size, proportions, colors, elements, type or in "
"any other respect. You may not animate, morph or otherwise distort the Marks. "
"You may not display the PowerSellers Marks in any way that implies that your "
"business is an eBay office or location, or that your electronic or printed "
"materials emanate from eBay or are otherwise endorsed or sponsored by eBay. "
"The eBay PowerSellers Marks may not appear on any products or product packaging for your "
"company. The eBay name, trademark or any potentially confusing variations thereof may "
"never be incorporated into the name of your company or a product or service of your company. "
"You acknowledge that the Marks contains eBay's proprietary logos, trademarks and service "
"marks and that you will not do anything inconsistent with eBay's ownership of the trademarks. "
"You will not use the Marks in any way that, in eBay's sole discretion, disparages eBay, its products or "
"services, infringes eBay's intellectual property rights, or violates any state, federal or international "
"law. You agree/acknowledge that no joint venture, partnership, employment or agency relationship exists "
"between you and eBay. You acknowledge that you have no rights to use the eBay PowerSellers Marks or eBay "
"trademarks other than as described herein, and that any other use will/may be considered infringement of "
"eBay's trademark rights. You agree that eBay has no liability for your use of the eBay PowerSellers Marks, "
"and you will reimburse eBay for any costs it may incur related to your use of the eBay PowerSellers Marks, "
"or for any breach of this Agreement. These rights are nontransferable. eBay may terminate or modify this "
"limited license at any time for any reason, in its sole discretion, without any liability or obligation to "
"you. eBay may visit your Web site to determine compliance with this Agreement, and you agree to make any "
"modifications eBay may request. If eBay notifies you that this Agreement is terminated for any reason, "
"you will cease use of the Marks in any form within five (5) days after notice is given. "
"Solely for the purpose of communicating with you during your participation in the PowerSellers Program, "
"you agree that eBay may use a third party, bound by a confidentiality agreement, to facilitate email "
"updates and program notices to you.\n" 
"<p>"
"EBAY HEREBY DISCLAIMS ALL WARRANTIES WITH REGARD TO THE EBAY MARKS AND POWERSELLERS LOGO, "
"INCLUDING BUT NOT LIMITED TO ALL IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, "
"AND NONINFRINGEMENT. IN NO EVENT SHALL EBAY BE LIABLE FOR ANY DIRECT, INDIRECT, PUNITIVE, INCIDENTAL, "
"SPECIAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF, OR IN ANY WAY CONNECTED WITH THIS AGREEMENT, WHETHER "
"BASED ON CONTRACT, TORT, STRICT LIABILITY OR OTHERWISE, EVEN IF EBAY HAS BEEN ADVISED OF THE POSSIBILITY "
"OF DAMAGES.\n"
"<p>This Agreeement constitutes the entire agreement between you and eBay with respect to the limited "
"license of the eBay PowerSellers Logo and may not be modified except in writing signed by both parties.</font> " 
"<P><IMG SRC=\"http://pics.ebay.com/aw/pics/green3.gif\" WIDTH=\"44\" HEIGHT=\"44\"> "
"<b>Press \"AGREE\" below to send your application.</b><BR>"
"Or \"DECLINE\" if you prefer to not complete your application at this time. "
"You may return later if you change your mind.  If you have questions about the PowerSellers program, contact us at "
"<A HREF=\"mailto:powersellersinfo@ebay.com\">powersellersinfo@ebay.com</A>"
"<P><table border=\"0\" width=\"75%\">"
"<tr>" 
"<td width=\"23%\">" 
"<input type=\"submit\" name=\"accept\" value=\"Agree\">"
"</td>"
"<td width=\"77%\">" 
"<input type=\"submit\" name=\"decline\" value=\"Decline\">"
"</td>"
"</tr>"
"</table>"
"</form>"
"</TD></TR></TABLE>";
*/

//
// A routine (which others can use) to show registration 
// information.
//
void clseBayApp::ShowRegistrationInfo(char *pUserId,
								  char *pPass)
								 
{
	char						*pCompany;
	char						*pDayPhone;
	char						*pNightPhone;
	char						*pFaxPhone;
	clsCategory					*pCategory;
	CategoryId					categoryId;

	*mpStream <<	"<form method=post action="
			  <<	"\""
			  <<	"eBayISAPI.dll"
			  <<	"\""
			  <<	">"
			  <<	"<INPUT TYPE=HIDDEN "
			  <<	"NAME=\"MfcISAPICommand\" "
			  <<	"VALUE=\"PowerSellerRegister\">"
			  <<	"\n";

	// Emit the email address and password so someone else can't sneak
	// in and help this user...
	*mpStream <<	"<input type=hidden name=userid value=\""
			  <<	pUserId
			  <<	"\">\n"
			  <<	"<input type=hidden name=pass value=\""
			  <<	pPass
			  <<	"\">\n";

	// Showing the User ID with the link to Change User Id Form
	*mpStream <<	"<table border=\"1\" width=\"590\" "
			  <<	"cellspacing=\"0\" cellpadding=\"4\">"
					"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><strong>"
					"E-mail address</strong>"
					"</td>"
					"<td width=\"75%\">"
			  <<	mpUser->GetEmail()
			  <<	"</td></tr>";

	*mpStream <<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><strong>User ID</strong></td>"
					"<td width=\"65%\">"
			  <<	mpUser->GetUserId()
			  <<	"</td></tr>";

	*mpStream <<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\">"
					"<strong>Full name</strong></td>"
					"<td width=\"65%\">"
			  <<	mpUser->GetName()
			  <<	"</td>"
					"</tr>";

	pCompany	= mpUser->GetCompany();
	if (!pCompany)
		pCompany	= "&nbsp; ";

	*mpStream <<	"<tr>"
					"<td width=\"35%\" bgcolor=\"#EFEFEF\"><strong>Company</strong></td>"
					"<td width=\"65%\">"
			  <<	pCompany
			  <<	"</td></tr>"
			  <<	"<tr>"
			  <<	"<td width=\"35%\" bgcolor=\"#EFEFEF\">"
			  <<	"<strong>Address</strong></td>"
			  <<	"<td width=\"65%\">";  
					
	*mpStream <<	mpUser->GetAddress()
			  <<	"</td></tr>";

	*mpStream <<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\">"
					"<strong>City</strong></td>"
					"<td width=\"65%\">"
			  <<	mpUser->GetCity()
			  <<	"</td></tr>";

	*mpStream <<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\">"
					"<strong>State, Province, or Region</strong></td>"
					"<td width=\"65%\">"
			  <<	mpUser->GetState();
								   

	*mpStream <<	"</td></tr>\n";										
					
	*mpStream <<	"<tr>"
					"<td width=\"35%\" bgcolor=\"#EFEFEF\">"
					"<strong>Postal Code (Zip)</strong></td>"
					"<td width=\"65%\">";
			  
	if (mpUser->GetZip())
		*mpStream <<	mpUser->GetZip();

	*mpStream  <<	"</td></tr>";


	*mpStream <<	"<tr> <td width=\"35%\" bgcolor=\"#EFEFEF\">"
					"<strong>Country</strong></td>"
					"<td width=\"65%\">"
			   <<	mpUser->GetCountry();

	*mpStream <<	"</td>"
					"</tr>";

	pDayPhone	= mpUser->GetDayPhone();
	if (!pDayPhone)
		pDayPhone	= "&nbsp;";

	*mpStream <<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\">"
					"<strong>Primary phone #</strong></td>"
					"<td width=\"65%\">"
			  <<	pDayPhone
			  <<	"</td></tr>";

	pNightPhone	= mpUser->GetNightPhone();
	if (!pNightPhone)
		pNightPhone	= "&nbsp;";

	*mpStream <<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><strong>Secondary phone #</strong></td>"
					"<td width=\"65%\">"
			  <<	pNightPhone
			  <<	"</td></tr>";

	pFaxPhone	= mpUser->GetFaxPhone();
	if (!pFaxPhone)
		pFaxPhone	= "&nbsp;";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><strong>Fax #</strong></td>"
					"<td width=\"75%\">"
			  <<	pFaxPhone
			  <<	"</td></tr>";

	// user preferences
	categoryId = mpUser->GetInterests_1();
	*mpStream <<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><strong>Primary category</strong></td>"
					"<td width=\"65%\">";
	pCategory = mpCategories->GetCategory(categoryId, true);
	if (!pCategory)
	{
		*mpStream << "Not Selected";
	}
	else
	{
		*mpStream << pCategory->GetName();
	}

	*mpStream <<	"</td></tr>";

	// user preferences 2nd Category
	categoryId = mpUser->GetInterests_2();
	*mpStream <<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><strong>Secondary category</strong></td>"
					"<td width=\"65%\">";
	pCategory = mpCategories->GetCategory(categoryId, true);
	if (!pCategory)
	{
		*mpStream << "Not Selected";
	}
	else
	{
		*mpStream << pCategory->GetName();
	}

	*mpStream <<	"</td></tr>";

	//end of user info table
	*mpStream <<	"</td></tr></table>";
   
}


void clseBayApp::PowerSellerRegisterShow(CEBayISAPIExtension *pServer,
										char * pUserId,
										char * pPass)
								
{
	int level;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" PowerSeller Registration Form"
					"</TITLE>"
					"</HEAD>";
	*mpStream <<	mpMarketPlace->GetHeader();

	// The last parameter allows the method to check if the password 
	// is the encrypted one stored in the database
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream, true, NULL,
													false, false, false, true);
	if (!mpUser)
	{
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (!mpUser->IsConfirmed())
	{
//		*mpStream <<	ErrorMsgNotConfirmed
//				  <<	"<br>";

// kakiyama 07/05/99		

		*mpStream << clsIntlResource::GetFResString(-1,
					"<h2>Unconfirmed Registration</h2>"
					"Sorry, you have not yet confirmed your registration."
					"You should have received an e-mail with instructions for "
					"confirming your registration. "
					"If you did not receive this e-mail, or if you have lost it, "
					"please return to "
					"<a href=\"%{1:GetHTMLPath}services/registration/register.html\">Registration</a>"
					" and re-register "
					"(with the same e-mail address) to have it sent to "
					"you again.",
					clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
					NULL);
		
		
		*mpStream << "<br>"
				  << mpMarketPlace->GetFooter();
		

		CleanUp();
		return;
	}

	if (mpUser->IsSuspended())
	{
		*mpStream <<	ErrorMsgSuspended
				  <<	"<br>";

		*mpStream <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (!mpUser->IsConfirmed())
	{
	//	*mpStream  <<	ErrorMsgUnknownState

	// kakiyama 07/07/99

		*mpStream  <<   clsIntlResource::GetFResString(-1,
							"<h2>Internal Error</h2>"
							"Sorry, there was a problem confirming your registration. "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)
				   <<	"<br>";

		*mpStream <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (!mpUser->HasDetail())
	{
		*mpStream <<	"<h2>Error</h2>"
						"Our records do not show registration information for "
				  <<	mpUser->GetUserId()
				  <<	" on file. This is an <font color=red><b>error</b></font> "
						"and should be reported to "
				  <<	mpMarketPlace->GetSupportEmail()
				  <<	"."
						"<p>";

		*mpStream <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	level = mpUser->GetTopSellerLevel();

	//TopSeller level
	//not qulified 
	if (level <= 0)
	{
		*mpStream <<	"<h2>Not Qualified for the PowerSellers Program</h2>"
						"We're sorry, but this UserID <strong>"
				  <<	mpUser->GetUserId()
				  <<	"</strong> is not recognized as "
						"qualified for the Powersellers program at this time.  "
						"<p>For details about qualifications, please visit the "
						"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"powersellers.html\">PowerSellers Information page</a>"
						" to review eligibility requirements.  <p>If you have "
						"questions about program membership, please contact "
						"<A HREF=\"mailto:powersellersinfo@ebay.com\">powersellersinfo@ebay.com</a>";

		*mpStream <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	//user already sign the agreement??
	if (level >= 11)
	{
		*mpStream <<	"<h2>eBay has already recorded your membership to Powersellers</h2> "
						"Congratulations! It is not necessary for you to "
						"re-register for the program.";

		*mpStream <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;

	}


	*mpStream <<	"<p><TABLE CELLPADDING=\"0\" CELLSPACING=\"0\" BORDER=\"0\" WIDTH=\"600\">"
					"<TR><TD WIDTH=\"100%\">"
					"<font size=+1>Congratulations on your PowerSellers ";

	if (level == 1)
		*mpStream <<	"<strong>Bronze</strong>";

	if (level == 2)
		*mpStream <<	"<strong>Silver</strong>";

	if (level == 3)
		*mpStream <<	"<strong>Gold</strong>";

	*mpStream <<	" eligibility!  To become a PowerSellers member, please follow the three steps below. </font>"
					"<P><IMG SRC=\""
			  <<	mpMarketPlace->GetImagePath()
			  <<	"green1.gif\" WIDTH=\"44\" HEIGHT=\"44\">"
					"<b>Review your eBay contact information.</b><BR>"  
					"We'll mail your PowerSellers information kit to this address, "
					"so please update it if it is not accurate.  To update this information, "
					"go to the "
					"<a href=\""
			<<		mpMarketPlace->GetHTMLPath()
			<<		"change-registration.html\">"
					"change your Registration Information</a> page. "
					"<P>";
				
	// Allll righty! Let's start emitting the user..
	ShowRegistrationInfo(pUserId, pPass);

	//*mpStream <<	PowerSellerAgreement;

	// kakiyama 07/07/99

	// TODO - check if need to remove /aw/pics? should this be pics path? - 2 places
	*mpStream << clsIntlResource::GetFResString(-1,
												"<P><IMG SRC=\"%{1:GetHTMLPath}/aw/pics/green2.gif\" WIDTH=\"44\" HEIGHT=\"44\">"
												"<b>Review the PowerSellers Program Terms and Conditions.</b><BR>"
												"<p><h3 ALIGN=\"CENTER\">PowerSellers Member Terms and Conditions</h3>"
												"Welcome to the eBay PowerSellers Program. This program gives you the opportunity to display the eBay "
												"PowerSellers icon on the eBay Web site and the eBay PowerSellers logo in your physical storefront with "
												"the sign provided to you in your welcome kit. To qualify for the use of the eBay PowerSellers Program, "
												"you must meet the following criteria: <br>"
												"<UL><LI>For each item you place up for sale on eBay, you shall guarantee that the item is in the condition described in the listing."
												"<LI>Your eBay account shall be kept in good standing at all times, and you shall pay eBay for all fees due. "
												"<LI>You must maintain a minimum feedback rating of 100, 98% positive eBay feedback rating at all times."
												"<LI>You must meet or exceed the monthly gross sales minimum for the program for which you qualify (as follows): "
												"<TABLE CELLPADDING=\"2\" CELLSPACING=\"0\" BORDER=\"0\" WIDTH=\"60%\">"
												"<TR><TD WIDTH=\"23%\"><b>Gold:</b></TD>"
												"<TD WIDTH=\"23%\" align=\"right\">$25,000</TD>"
												"<TD WIDTH=\"10%\">&nbsp;</TD>"
												"<TD WIDTH=\"43%\"><b>Feedback:</b> 100</TD>"
												"</TR>"
												"<TR><TD><b>Silver:</b></TD>"
												"<TD align=\"right\">$10,000</TD>"
												"<TD>&nbsp;</TD>"
												"<TD><b>Feedback:</b> 100</TD>"
												"</TR>"
												"<TR><TD><b>Bronze:</b></TD>"
												"<TD align=\"right\">$2,000</TD>"
												"<TD>&nbsp;</TD>"
												"<TD><b>Feedback:</b> 100</TD>"
												"</TR>"
												"</TABLE>"
												"</UL>"
												"<P>If you wish to be a member of the PowerSellers Program you must also agree to the guidelines set forth below. If the license terms are acceptable to you, simply continue with the Program. "
												"<FONT SIZE=\"-1\">"
												"<H4 ALIGN=\"CENTER\">Use Requirements and License Agreement</H4>"
												"For the purposes of this Agreement, the Icon and Logo are collectively referred "
												"to as the \"Marks\". The eBay PowerSellers icon (Icon) is only for use on the "
												"eBay Web site. Unless authorized by eBay, you may not use the Icon or refer to "
												"the icon off the eBay Web site. The eBay PowerSellers logo (Logo) is to be used "
												"only on materials provided to you by eBay. You may not alter the PowerSellers "
												"Logo in any manner including size, proportions, colors, elements, type or in "
												"any other respect. You may not animate, morph or otherwise distort the Marks. "
												"You may not display the PowerSellers Marks in any way that implies that your "
												"business is an eBay office or location, or that your electronic or printed "
												"materials emanate from eBay or are otherwise endorsed or sponsored by eBay. "
												"The eBay PowerSellers Marks may not appear on any products or product packaging for your "
												"company. The eBay name, trademark or any potentially confusing variations thereof may "
												"never be incorporated into the name of your company or a product or service of your company. "
												"You acknowledge that the Marks contains eBay's proprietary logos, trademarks and service "
												"marks and that you will not do anything inconsistent with eBay's ownership of the trademarks. "
												"You will not use the Marks in any way that, in eBay's sole discretion, disparages eBay, its products or "
												"services, infringes eBay's intellectual property rights, or violates any state, federal or international "
												"law. You agree/acknowledge that no joint venture, partnership, employment or agency relationship exists "
												"between you and eBay. You acknowledge that you have no rights to use the eBay PowerSellers Marks or eBay "
												"trademarks other than as described herein, and that any other use will/may be considered infringement of "
												"eBay's trademark rights. You agree that eBay has no liability for your use of the eBay PowerSellers Marks, "
												"and you will reimburse eBay for any costs it may incur related to your use of the eBay PowerSellers Marks, "
												"or for any breach of this Agreement. These rights are nontransferable. eBay may terminate or modify this "
												"limited license at any time for any reason, in its sole discretion, without any liability or obligation to "
												"you. eBay may visit your Web site to determine compliance with this Agreement, and you agree to make any "
												"modifications eBay may request. If eBay notifies you that this Agreement is terminated for any reason, "
												"you will cease use of the Marks in any form within five (5) days after notice is given. "
												"Solely for the purpose of communicating with you during your participation in the PowerSellers Program, "
												"you agree that eBay may use a third party, bound by a confidentiality agreement, to facilitate email "
												"updates and program notices to you.\n" 
												"<p>"
												"EBAY HEREBY DISCLAIMS ALL WARRANTIES WITH REGARD TO THE EBAY MARKS AND POWERSELLERS LOGO, "
												"INCLUDING BUT NOT LIMITED TO ALL IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, "
												"AND NONINFRINGEMENT. IN NO EVENT SHALL EBAY BE LIABLE FOR ANY DIRECT, INDIRECT, PUNITIVE, INCIDENTAL, "
												"SPECIAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF, OR IN ANY WAY CONNECTED WITH THIS AGREEMENT, WHETHER "
												"BASED ON CONTRACT, TORT, STRICT LIABILITY OR OTHERWISE, EVEN IF EBAY HAS BEEN ADVISED OF THE POSSIBILITY "
												"OF DAMAGES.\n"
												"<p>This Agreeement constitutes the entire agreement between you and eBay with respect to the limited "
												"license of the eBay PowerSellers Logo and may not be modified except in writing signed by both parties.</font> " 
												"<P><IMG SRC=\"%{2:GetHTMLPath}/aw/pics/green3.gif\" WIDTH=\"44\" HEIGHT=\"44\"> "
												"<b>Press \"AGREE\" below to send your application.</b><BR>"
												"Or \"DECLINE\" if you prefer to not complete your application at this time. "
												"You may return later if you change your mind.  If you have questions about the PowerSellers program, contact us at "
												"<A HREF=\"mailto:powersellersinfo@ebay.com\">powersellersinfo@ebay.com</A>"
												"<P><table border=\"0\" width=\"75%\">"
												"<tr>" 
												"<td width=\"23%\">" 
												"<input type=\"submit\" name=\"accept\" value=\"Agree\">"
												"</td>"
												"<td width=\"77%\">" 
												"<input type=\"submit\" name=\"decline\" value=\"Decline\">"
												"</td>"
												"</tr>"
												"</table>"
												"</form>"
												"</TD></TR></TABLE>",
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												NULL);

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();


	CleanUp();
	return;
}

