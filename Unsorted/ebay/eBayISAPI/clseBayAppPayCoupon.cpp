/*	$Id: clseBayAppPayCoupon.cpp,v 1.8.94.4.42.1 1999/08/01 03:01:21 barry Exp $	*/
//
//	File:	clseBayAppUserQuery.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Handle a registration request
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 02/24/99 mason - completely changed layout.
//				- 03/19/99 mason - changed PDT to not be hardcoded
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

static const char *PayCouponAcctInfo =
"<p>\n"
"<center><table width=\"505\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"EFEFEF\">\n"
"<tr><td width=\"250\" align=right><b>Account User ID:</b></td>\n"
"<td width=\"5\">&nbsp;</td>\n"
"<td width=\"250\" align=left>%s</td></tr>\n"
"<tr><td width=\"250\" align=right><b>Account Email Address:</b></td>\n"
"<td width=\"5\">&nbsp;</td>\n"
"<td width=\"250\" align=left>%s</td></tr>\n"
"<tr><td width=\"250\" align=right><b>eBay Account Number:</b></td>\n"
"<td width=\"5\">&nbsp;</td>\n"
"<td width=\"250\" align=left>%s</td></tr></table></center>\n";


void clseBayApp::PayCoupon(CEBayISAPIExtension *pServer,
						   char * pUserId, char* pPass, char* pymtType)
{
	clsAccount	*pAccount;
//	int			AWAccountId;
	char		*pMessage;
//	time_t		theTimeT;      
//	char		cTheDate[40];				   
	char		cAccountId[16];

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Payment Coupon"
					"</TITLE>"
					"</HEAD>"
			  <<	"\n";

	// Let's validate who we be
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream, false);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Get the user's account
	pAccount	= mpUser->GetAccount();

		//Figure out what time it is...	 removed this since they don't want to the
		//time on the sheet anymore - Mason - 6/10/99
//	theTimeT	= time(NULL);				   
//	clsUtilities::GetDateTime(theTimeT, cTheDate);

	if (pAccount->Exists())
	{
//		Do not use AW account anymore 
//		AWAccountId = pAccount->GetAWAccountId();
//
//		if (AWAccountId == 0)
//			sprintf(cAccountId, "E%d", mpUser->GetId());
//		else
//			sprintf(cAccountId, "%d", AWAccountId);

		sprintf(cAccountId, "E%d", mpUser->GetId());
	}
	else
		strcpy(cAccountId, "New Account"); 

	pMessage	= new char[strlen((char *)PayCouponAcctInfo) + 256];
	sprintf(pMessage,
			PayCouponAcctInfo,
			mpUser->GetUserId(),
			mpUser->GetEmail(),
			cAccountId);

	if (strcmp(pymtType, "Check or Money Order Payment") == 0)
	{
		*mpStream	<<	"<body bgcolor=\"#FFFFFF\">\n"
						"<b><center>Please print and send the entire payment coupon "
						"with your payment to ensure accurate and timely processing."
						"<p><p>\n"
						"<font size=\"+1\">Check or Money Order Payment Coupon</font></b>"
						"<p></center>\n";
		*mpStream	<<	pMessage;

		*mpStream	<<	"<p>Please make check or money order payable to "
						"<b><i>eBay, Inc.</i></b> and mail payment to:\n"
						"<p>\n"
						"<table border=\"0\"><tr><td width=\"40\"></td>\n"
						"<td align=left>\n"
						"eBay, Inc.<br>\n"
						"P.O. Box 200945<br>\n"
						"Dallas, Texas 75320-0945\n"
						"</td></tr></table><p>\n"
						"<ul>\n"
						"<li> It is important to write your <u><i>account number</i></u>"
						" and/or <u><i>e-mail address</i></u> on your check.\n"
						"<li> Please, no third party checks. Check information must match "
						"seller account information for proper processing.\n"
						"<li> Do not send Cash payments.</li>\n"
						"<li> Do not send seller payments to eBay. Payments for winning "
						"auctions must be sent directly to the seller and not eBay.\n"
						"</li></ul>\n";
						"<p>\n";
    }	
	else
	{
		*mpStream	<<	"<body bgcolor=\"#FFFFFF\">\n"
						"<b><center>Please print this form, fill in the information "
						"and send the entire payment coupon to the address listed "
						"below to ensure accurate and timely processing."
						"<p><p>\n"
						"<font size=\"+1\">One-Time Credit Card Payment Coupon<br>"
						"Visa & MasterCard Only</font></b>"
						"<p></center>\n";
		*mpStream	<<	pMessage;

		*mpStream	<<	"<p>\n"
						"<table width=\"600\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" height=\"38\">\n"
						"<tr> \n"
						"<td width=\"200\">VISA / MasterCard Number:</td>\n"
						"<td width=\"100\"> \n"
						"<table width=\"80\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#666666\">\n"
						"<tr> \n"
						"<td>&nbsp;</td>\n"
						"</tr>\n"
						"</table>\n"
						"</td>\n"
						"<td width=\"100\"> \n"
						"<table width=\"80\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#666666\">\n"
				        "<tr> \n"
						"<td>&nbsp;</td>\n"
						"</tr>\n"
						"</table>\n"
						"</td>\n"
						"<td width=\"100\"> \n"
						"<table width=\"80\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#666666\">\n"
						"<tr> \n"
						"<td>&nbsp;</td>\n"
						"</tr>\n"
						"</table>\n"
						"</td>\n"
						"<td width=\"100\"> \n"
						"<table width=\"80\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#666666\">\n"
						"<tr> \n"
						"<td>&nbsp;</td>\n"
						"</tr>\n"
						"</table>\n"
						"</td>\n"
						"</tr>\n"
						"</table>\n"
						"<br>\n"
						"<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
						"<tr> \n"
						"<td width=\"150\"> Expiration Date: </td>\n"
						"<td width=\"450\" valign=\"top\">_____________________________</td>\n"
						"</tr>\n"
						"<tr><td width=\"150\">&nbsp;</td>\n"
						"<td width=\"450\">&nbsp;</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">Card Holder Name:</td>\n"
						"<td width=\"450\">____________________________________________________</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">&nbsp;</td>\n"
						"<td width=\"450\">&nbsp;</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">Signature:</td>\n"
						"<td width=\"450\">____________________________________________________</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">&nbsp;</td>\n"
						"<td width=\"450\">&nbsp;</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">Billing Address:</td>\n"
						"<td width=\"450\">Street _______________________________________________</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">&nbsp;</td>\n"
						"<td width=\"450\">&nbsp;</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">&nbsp;</td>\n"
						"<td width=\"450\">Apt. ________________________________________________</td>\n"
						"</tr>\n"
						"<tr>\n"
						"<td width=\"150\">&nbsp;</td>\n"
						"<td width=\"450\">&nbsp;</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">&nbsp;</td>\n"
						"<td width=\"450\">City ________________________________________________</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">&nbsp;</td>\n"
						"<td width=\"450\">&nbsp;</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">&nbsp;</td>\n"
						"<td width=\"450\">State ______________________ Zip ______________________</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">&nbsp;</td>\n"
						"<td width=\"450\">&nbsp;</td>\n"
						"</tr>\n"
						"<tr> \n"
						"<td width=\"150\">Payment Amount:</td>\n"
						"<td width=\"450\">$_________________________</td>\n"
						"</tr>\n"
						"</table>\n"
						"<p>Please send credit card payment to:</p>\n"
						"<table border=\"0\"><tr><td width=\"40\"></td>\n"
						"<td align=left>\n"
						"eBay, Inc.<br>\n"
						"Customer Accounts Department<br>\n"
						"2125 Hamilton Avenue<br>\n"
						"San Jose, CA 95125\n"
						"</td></tr></table><p>\n";
	}

	*mpStream	<<	"<P><STRONG>Payment and processing policies:</STRONG>\n"
					"<UL><LI>Checks or money orders drawn on foreign banks must include an additional US $10.00 to cover bank fees.\n"
					"<LI>Returned checks are subject to $15.00 service charge.\n"
					"<LI>Past due accounts are billed 1.5% monthly finance charge, minimum $0.50.\n"
					"<LI>Past due accounts subject to termination and $5.00 reactivation fee.\n"
					"<LI>eBay reserves the right to recover costs of collection.\n"
					"<LI>To preserve your rights, all billing inquiries should be made in writing.\n"
					"<LI>Fees and policies are subject to change without notice.</LI></UL></P>\n"
					"</TD></TR></TABLE>\n"
					"<P>\n"
					"</body></html>\n";

	delete	pMessage;
	delete	pAccount;

	CleanUp();
	return;
}

