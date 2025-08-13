/*	$Id: clseBayRequestRefund.cpp,v 1.1.2.3.4.1 1999/08/01 03:01:42 barry Exp $	*/
//
//	File:	clseBayAppRequestRefund.cpp
//
//	Class:	clseBayApp
//
//	Author:	Bill Wang (bwang@ebay.com)
//
//	Function:
//
//		Handle an ebay account refund request
//
// Modifications:
//				- 06/24/99 bill	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

static const char *RequestRefundText =
"<body bgcolor=\"#FFFFFF\">\n" 
"<H2>eBay Account Refund Request</H2>\n"
"<br>\n"
"<table width=\"650\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" bgcolor=\"#EFEFEF\">\n"
"  <tr>\n" 
"    <td width=\"210\">\n"
"      <div align=\"right\">Account User ID:</div>\n"
"    </td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">%s</td>\n"
"  </tr>\n"
"  <tr>\n" 
"    <td width=\"210\">\n"
"      <div align=\"right\">Account eMail Address:</div>\n"
"    </td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">%s</td>\n"
"  </tr>\n"
"  <tr>\n"
"    <td width=\"210\">\n"
"      <div align=\"right\">eBay Account Number:</div>\n"
"    </td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">%s</td>\n"
"  </tr>\n"
"  <tr>\n"
"    <td width=\"210\">\n"
"      <div align=\"right\"></div>\n"
"    </td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">&nbsp;</td>\n"
"  </tr>\n"
"  <tr>\n"
"    <td width=\"210\">\n"
"      <div align=\"right\">Customer Name:</div>\n"
"    </td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">%s</td>\n"
"  </tr>\n"
"  <tr>\n"
"    <td width=\"210\">\n"
"      <div align=\"right\">Mailing Address-Street:</div>\n"
"    </td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">%s</td>\n"
"  </tr>\n"
"  <tr>\n"
"    <td width=\"210\">\n"
"      <div align=\"right\">City:</div>\n"
"    </td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">%s</td>\n"
"  </tr>\n"
"  <tr>\n"
"    <td width=\"210\">\n"
"      <div align=\"right\">State:</div>\n"
"    </td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">%s</td>\n"
"  </tr>\n"
"  <tr>\n"
"    <td width=\"210\">\n"
"      <div align=\"right\">Zip Code:</div>\n"
"    </td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">%s</td>\n"
"  </tr>\n"
"  <tr>\n"
"    <td width=\"210\">\n"
"      <div align=\"right\">Phone Number:</div>\n"
"    </td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">%s  (day)</td>\n"
"  </tr>\n"
"  <tr>\n"
"    <td width=\"210\">&nbsp;</td>\n"
"    <td width=\"5\">&nbsp;</td>\n"
"    <td width=\"417\">%s  (night)</td>\n"
"  </tr>\n"
"</table>\n"
"\n"
"<PRE>\n"
"<br>\n"
"<font size=\"3\"><b>Signature:</b>	______________________________________ <font>\n"
"<br>\n"
"<font size=\"3\"><b>Date:</b>		__________________________</font>\n"
"</PRE>\n"
"<br>\n"
"Please refund me $____________ from my eBay account (balance must be $1.00 or more). Please allow 7 to 10 business days to process this request.<br>\n"
"<h3>Important</h3>\n"
"<ul>\n"
"  <li>Refund checks will be mailed to the above address. \n"
"  <li>Please verify and update your address and other contact information before submitting your request. \n"
"  <li>Print, complete, and sign this form. Mailing and faxing information is below. \n"
"  <li>To update your contact information, <a href=\"http://pages.ebay.com/services/myebay/change-registration.html\">click here</a>. \n"
"</ul>\n"
"<br>\n"
"<h3>\n"
"  Mail or fax this form to eBay at:</h3>\n"
"<p>\n"
"\n"
"  eBay Inc. -   Billing Department - Refund Request<br>\n"
"  2005 Hamilton Ave., Suite 350<br>\n"
"  San Jose, CA 95125<br>\n"
"  USA<br><br>\n"
"  FAX: 1-408-558-7404 (no cover page, please)<br>\n"
"</p>\n";


void clseBayApp::RequestRefund(CEBayISAPIExtension *pServer,
						   char * pUserId, char* pPass)
{
	clsAccount	*pAccount;
	char		*pMessage;
	char		cAccountId[16];

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Account Refund Request"
					"</TITLE>"
					"</HEAD>"
			  <<	"\n";

	// Let's validate who we be
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream, false);
	if (!mpUser)
	{
		*mpStream <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Get the user's account
	pAccount	= mpUser->GetAccount();

	// Get account number
	if (pAccount->Exists())
		sprintf(cAccountId, "E%d", mpUser->GetId());
	else
		strcpy(cAccountId, "New Account"); 


	pMessage	= new char[strlen((char *)RequestRefundText) + 256];

	sprintf(pMessage,RequestRefundText,
			mpUser->GetUserId(),
			mpUser->GetEmail(),
			cAccountId,
			mpUser->GetName(),
			mpUser->GetAddress(),
			mpUser->GetCity(),
			mpUser->GetState(),
			mpUser->GetZip(),
			mpUser->GetDayPhone(),
			mpUser->GetNightPhone()
			);
			
	*mpStream <<	pMessage;

	delete	pMessage;
	delete	pAccount;

	CleanUp();
	return;
}

