/*	$Id: clseBayMyBalanceWidget.cpp,v 1.6.2.5.80.1 1999/08/01 02:51:27 barry Exp $	*/
//
//	File:	clseBayMyAccountDetailWidget.h
//
//	Class:	clseBayMyBalanceWidget
//
//	Author:	Charles Manga
//
//	Function:
//			Widget that shows the balance for a user using clseBayTableWidget.
//
// Modifications:
//				- 11/19/97	Charles - Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "widgets.h"
#include "clseBayMyBalanceWidget.h"


clseBayMyBalanceWidget::clseBayMyBalanceWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace)
{
	mpUser			= NULL;
	mpAccount		= NULL;
	myBalance		= 0.0;
	mLimit			= 10.0; // US Dollars!
	mNumCols		= 1;
	mNumItems		= 0;
	mTitleColor[0]	= '\0';
	mPassWord[0]	= '\0';
	mpTheDate		= NULL;
}


clseBayMyBalanceWidget::~clseBayMyBalanceWidget()
{
	assert(mpAccount);
	// Destroy the account
	if(mpAccount->Exists())
	{
		delete mpAccount;
	}

	delete [] mpTheDate;
	mpUser = NULL;
	mpAccount = NULL;

}

// Initializing the number of cells to display
// and loading the feedback details
bool clseBayMyBalanceWidget::Initialize()
{
// petra	time_t		 lastUpdate;
// petra	struct tm	*pTheDate;
	int			 iTotalNumCell = 0;
	//samuel au, 4/8/99
// petra	clseBayTimeWidget	lastUpdateTimeWidget;
// petra	TimeZoneEnum		timeZone;
// petra	char				tempTime[30];
// petra	char				tempDate[30];
	//end
	
	mpTheDate = new char[EBAY_BALANCE_STRING_LENGTH];
	memset(mpTheDate,0,EBAY_BALANCE_STRING_LENGTH);

	if (!mpMarketPlace)
		return false;

	// Safety
	if(!mpUser)
		return false;

	// Let's get the account 
	mpAccount = mpUser->GetAccount();
	if( mpAccount->Exists() )
	{
		//
		// Get the account detail
		//
		myBalance	= mpAccount->GetBalance();
// petra		lastUpdate  = mpAccount->GetLastUpdate();
// petra		pTheDate	= localtime(&lastUpdate);
		//samuel au, 4/8/99
// petra		timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra		lastUpdateTimeWidget.SetTime(lastUpdate);
// petra		lastUpdateTimeWidget.SetTimeZone(timeZone);
		clseBayTimeWidget lastUpdateTimeWidget (mpMarketPlace, 1, 2, mpAccount->GetBalance());	// petra
		//strftime(mpTheDate,EBAY_BALANCE_STRING_LENGTH,"%m/%d/%y %H:%M:%S",pTheDate);
// petra		lastUpdateTimeWidget.BuildDateString(tempDate);
// petra		lastUpdateTimeWidget.BuildTimeString(tempTime);
// petra		strcpy(mpTheDate, tempDate);
// petra		strcat(mpTheDate, ", ");
// petra		strcat(mpTheDate, tempTime);
		lastUpdateTimeWidget.EmitString (mpTheDate);
	}


	// Total number of cell to display
	// Number of Items * Number of cell per Item
	// mNumItems is the number of cells of account to display
	iTotalNumCell = 1;
	//
	// Changing the number of cells to display
	//
	SetNumItems(iTotalNumCell);
	//
	// end here
	// 
	return true;
}


// Before the table create a header 
bool clseBayMyBalanceWidget::EmitPreTable(ostream *pStream)
{
	//
	// Write the title and the user id
	//

	// Open the table
	if (mTitleColor[0] == '\0')
	{
		// emit begin table tag without the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"BORDER=\""
				 <<		1
				 <<		"\" "
				 <<		"CELLPADDING=\""
				 <<		mCellPadding
				 <<		"\" "
				 <<		"CELLSPACING=\""
				 <<		mCellSpacing
				 <<		"\" "
				 <<		"WIDTH=\""
				 <<		mTableWidth
				 <<		"%\""
				 <<		">"
				 <<		"\n";
	}
	else
	{
		// emit begin table tag with the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"BORDER=\""
				 <<		1
				 <<		"\" "
				 <<		"CELLPADDING=\""
				 <<		mCellPadding
				 <<		"\" "
				 <<		"CELLSPACING=\""
				 <<		mCellSpacing
				 <<		"\" "
				 <<		"WIDTH=\""
				 <<		mTableWidth
				 <<		"%\" "
				 <<		"BGCOLOR=\""
				 <<		mTitleColor
				 <<		"\""
				 <<		">"
				 <<		"\n";
	}

	//
	// The account title
	//
	*pStream	<<	"\n"
				<<	"<TR><TH ALIGN=CENTER>"
				<<	"<FONT face=\"arial, helvetica\" size=\"3\">"
				<<	"My Account"
				<<	"</FONT>"
				<<	"</TH></TR>"
				<<	"</TABLE>";

	//
	// Let's see if there is an account
	//
	assert(mpAccount);
	if (!mpAccount->Exists())
	{
		//
		// There is no account for this user
		// print this message and quit
		//
		*pStream	<<	"<P><STRONG>Your account has not been created.</STRONG><BR>Your "
				    <<	mpMarketPlace->GetCurrentPartnerName()
				    <<	" account has not been activated yet. Accounts "
					<<	"are not accessible until an actual debit or credit has "
					<<	"first been posted to the account, even though you may "
					<<	"have already filled out our account creation form. "
					<<	"This message confirms to you that you have no credit "
					<<	"or debit balance at this time."
					<<	"</STRONG></P>";

		*pStream	<<	flush;
		return true;
	}

	*pStream	<<	flush;
	return true;
}


// End the List of Accounts
// Printing the footer messages
//
bool clseBayMyBalanceWidget::EmitPostTable(ostream *pStream)
{
	assert(mpAccount);
	if (mpAccount->Exists())
	{
		// when the balance is > $10 or the limit ,
		// test the credit card on file flag !!!!
		if((myBalance < (-mLimit)) && 
			(!mpUser->HasCreditCardOnFile()))
		{
			// Internationalization note: This will eventually be set
			// per country as appropriate, and will not always be $10!

			// The balance is more than $10 , ask the user to put his credit card on file

			*pStream	<<	"<P><STRONG>"
			// PH		<<	"<font color=\"red\">Note:</font> You are above the $"
			// PH		<<	(double) mLimit
						<<	"<font color=\"red\">Note:</font> You are above the ";
			// PH added 04/28/99 >>
			clsCurrencyWidget currencyWidget(mpMarketPlace, Currency_USD, mLimit); // for now
			currencyWidget.EmitHTML(pStream);
			// <<
			*pStream	<<	" limit for users without credit cards on file."
						<<	" You should either "
						<<	"<A HREF="
						<<	"\""
						<<	mpMarketPlace->GetHTMLPath()
						<<	"pay-coupon.html"
						<<	"\""
						<<	">"
						<<	"make a payment"
						<<	"</A>"
						<<	" now, or put your credit card on file by "
						<<	"<A HREF="
						<<	"\""
						<<	mpMarketPlace->GetHTMLPath()
						<<	"help/basics/n-account.html"
						<<	"\""
						<<	">"
						<<	"creating an eBay account"
						<<	"</A>"
						<<	". "
						<<	"</STRONG></P>";
		}
		
		//
		// The footer of MyAccount
		//
		
		*pStream	<<	"<P><B>Full account status:</B>&nbsp;&nbsp;"
			<<	"<A HREF="
			<<	"\""
			<<	mpMarketPlace->GetCGIPath(PageViewAccount)
			<<	"ebayISAPI.dll?ViewAccount"
			<<	"&userid="
			<<	mpUser->GetUserId()
			<<	"&pass="
			<<	mPassWord
			<<	"&sincelastinvoice=1&entire=0"
			<<	"\""
			<<	">"
			<<	"since my last invoice"
			<<	"</A>"
			<<	"&nbsp;&nbsp;"
			<<	"-"
			<<	"&nbsp;&nbsp;"
//			<<	"<A HREF="
//			<<	"\""
//			<<	mpMarketPlace->GetCGIPath(PageViewAccount)
//			<<	"ebayISAPI.dll?ViewAccount"
//			<<	"&userid="
//			<<	mpUser->GetUserId()
//			<<	"&pass="
//			<<	mPassWord
//			<<	"&sincelastinvoice=0&daysback=14&entire=0"
//			<<	"\""
//			<<	">"
//			<<	"last 2 months only"
//			<<	"</A>"
//			<<	"&nbsp;&nbsp;"
//			<<	"-"
//			<<	"&nbsp;&nbsp;"
			<<	"<A HREF="
			<<	"\""
			<<	mpMarketPlace->GetCGIPath(PageViewAccount)
			<<	"ebayISAPI.dll?ViewAccount"
			<<	"&userid="
			<<	mpUser->GetUserId()
			<<	"&pass="
			<<	mPassWord
			<<	"&entire=1"
			<<	"\""
			<<	">"
			<<	"entire account"
			<<	"</A> <font size=2>(takes a while; please be patient)</font>"
			<<	"<br>";
		
		*pStream	<<	"Up to the minute accounting of all credits, debits and current balance for"
			<<	" your eBay accounts. Accounts are not created until the first credit or debit"
			<<	" is posted, so even if you have already created your account, no information"
			<<	" will be here until the first account activity.";
	}
	
	// Useful links about accounts
	*pStream	<<	"<p>"
				<<	"<font size=\"2\">"
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/sellerguide/selling-fees.html\">"
				<<	"Fees&nbsp;&amp;&nbsp;credits"
				<<	"</a>"
				<<	" - "
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/myinfo/billing-payment.html\">"
				<<	"Payment&nbsp;terms"
				<<	"</a>"
				<<	" - "
				<<	"<a href=\""
				<<	mpMarketPlace->GetSecureHTMLPath()
				<<	"cc-update.html\">"
				<<	"Use&nbsp;a&nbsp;credit&nbsp;card&nbsp;for&nbsp;automatic&nbsp;billing"
				<<	"</a>"
				<<	" - "
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/buyandsell/finalfee.html\">"
				<<	"Credit&nbsp;request"
				<<	"</a>"
				<<	" - "
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/buyandsell/account-refund.html\">"
				<<	"Refunds"
				<<	"</a>"
				<<	" - "
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/buyandsell/pay-coupon.html\">"
				<<	"Make&nbsp;a&nbsp;one&nbsp;time&nbsp;payment"
				<<	"</a>"
				<<	"</font>"
				<<	"</p>";

	*pStream	<< flush;
	return true;

}


// This routine have to be called n = 0..mNumItems-1 times
// here mNumItems = 1 so it will be called once 
bool clseBayMyBalanceWidget::EmitCell( ostream *pStream, int n)
{
	int accountCurrencyId = Currency_USD; // for now.

	assert(mpAccount);
	if ((mpAccount->Exists()) && (n == 0))
	{
		//
		// Writing the balance row
		//
		if(mColor[0] == '\0')
		{
			*pStream	<<	"<TD ALIGN=\"left\">";
		}
		else
		{
			*pStream	<<	"<TD ALIGN=\"CENTER\" BGCOLOR="
						<<	"\""
						<<	mColor
						<<	"\""
						<<	">";
		}

		*pStream	<<	"As of "
					<<	"<B>"
					<<	mpTheDate
					<<	"</B>"
					<<	", my account balance is:  ";

		clsCurrencyWidget currencyWidget(mpMarketPlace, accountCurrencyId, myBalance);
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(pStream);

		*pStream	<<	"</TD>";

		*pStream	<< flush;
	}

	return true;

}

