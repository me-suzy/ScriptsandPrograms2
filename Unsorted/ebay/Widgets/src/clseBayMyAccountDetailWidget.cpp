/*	$Id: clseBayMyAccountDetailWidget.cpp,v 1.3.238.1.80.2 1999/08/09 17:23:53 nsacco Exp $	*/
//
//	File:	clseBayMyAccountDetailWidget.h
//
//	Class:	clseBayMyAccountDetailWidget
//
//	Author:	Charles Manga
//
//	Function:
//			Widget that shows Feedback items for a user using clseBayTableWidget.
//
// Modifications:
//				- 11/4/97	Charles - Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "widgets.h"
#include "clseBayMyAccountDetailWidget.h"


clseBayMyAccountDetailWidget::clseBayMyAccountDetailWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace)
{
	mUserId			= 0;
	mpUser			= NULL;
	mpAccount		= NULL;
	mpvDetail		= NULL;
	mTitleColor[0]	= '\0';
	mColorSwitch	= false;
	mpColor			= NULL;
	mBalance		= 0;
}


clseBayMyAccountDetailWidget::~clseBayMyAccountDetailWidget()
{

	AccountDetailVector::iterator i;
	
	if(mpvDetail)
	{
		for(i=mpvDetail->begin();
		i != mpvDetail->end();
		++i)
		{
			delete *i;
		}
		
		mpvDetail->erase(mpvDetail->begin(),mpvDetail->end());
	}
	
	if(mpAccount && mpAccount->Exists())
	{
		delete mpAccount;
	}
	
	delete mpUser;
	mpvDetail = NULL;
	mpColor = NULL;
	
}



// Initializing the number of cells to display
// and loading the feedback details
bool clseBayMyAccountDetailWidget::Initialize()
{
	int iTotalNumCell = 0;
	
	SetNumCols(EBAY_NUMBER_ACCOUNT_COLUMNS);
	if (!mUserId) return false;
	if (!mpMarketPlace) return false;

	mpUser = mpMarketPlace->GetUsers()->GetUser(mUserId,false,false);
	//
	// Do not continue if there is no user
	//
	if(!mpUser)
	{
		return false;
	}
		// safety


	//
	// Let's get an account with the details 
	//
	mpAccount = mpUser->GetAccount();
	//
	//
	if (mpAccount->Exists())
	{
		//
		// Get the account detail
		//
		mpAccount->GetAccountDetail(mpvDetail);
		//
		// Number of account items
		//
		assert(mpvDetail);
		mNumItems = mpvDetail->end() - mpvDetail->begin(); 
		//
	}

	// Total number of cell to display
	// Number of Items * Number of cell per Item
	// mNumItems is the number of cells of account to display
	iTotalNumCell = mNumItems * EBAY_ACCOUNT_CELLS_PER_ITEM;
	//
	// Changing the number of cells to display
	//
	SetNumItems(iTotalNumCell);
	//
	// Setting the first color to display , the switch flag
	// and initializing the balance
	//
	SetColor("#FFFFFF");
	mColorSwitch = true;
	mBalance = 0.0;
	//
	// end here
	//
	return true;
}


// Before the table create a header 
bool clseBayMyAccountDetailWidget::EmitPreTable(ostream *pStream)
{
	//
	// Write the title and the user id
	//
	if (mTitleColor[0] == '\0')
	{
		// emit begin table tag without the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"BORDER=\""
				 <<		mBorder
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
				 <<		mBorder
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
	// Let's see if there is an account
	//
	assert(mpAccount);
	if (!mpAccount->Exists())
	{
		//
		// There is no account for this user
		// printing a message and quit
		//
		*pStream	<<	"<P><STRONG>Your account has not been created.</STRONG><BR>Your "
				    <<	mpMarketPlace->GetCurrentPartnerName()
				    <<	" account has not been activated yet. Accounts "
					<<	"are not accessible until an actual debit or credit has "
					<<	"first been posted to the account, even though you may "
					<<	"have already filled out our account creation form. "
					<<	"This message confirms to you that you have no credit "
					<<	"or debit balance at this time."
					<<	"</P>";

		*pStream	<<	flush;
		return false;
	}

	//
	// There is an account for this user
	// Write the footer of the header
	//
	*pStream	<<	"<P>"
				<<	"<STRONG>"
				<<	"My most recent transactions:"
				<<	"</STRONG>"
				<<	"</P>";
	//
	// Write the Header of the account detail
	//
	*pStream	<<	"<TABLE BORDER=1 WIDTH=100% CELLSPACING=0 BGCOLOR=#009900>\n"
					"<TR>"
					"<TH WIDTH=10% ALIGN=CENTER>Ref #</TH>"
					"<TH WIDTH=18% ALIGN=CENTER>Date</TH>"
					"<TH WIDTH=30% ALIGN=CENTER>Type</TH>"
					"<TH WIDTH=10% ALIGN=CENTER>Item</TH>"
					"<TH WIDTH=10% ALIGN=CENTER>Credit</TH>"
					"<TH WIDTH=10% ALIGN=CENTER>Debit</TH>"
					"<TH WIDTH=10% ALIGN=CENTER>Balance</TH>"
					"</TR></TABLE>\n";


	*pStream	<<	flush;
	return true;
}


// End the List of Accounts
// Printing the last line ( the balance line )
// And the footer messages
bool clseBayMyAccountDetailWidget::EmitPostTable(ostream *pStream)
{
	//
	// Print the Balance here !
	// Setting the color to use on this row
	//
	if(mColorSwitch)
	{
		SetColor("#FFFFCC");
		mColorSwitch = false;
	}
	else
	{
		SetColor("#FFFFFF");
		mColorSwitch = true;
	}

	//
	// Writing the balance row
	//
	*pStream	<<	"<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 BGCOLOR="
				<<	mColor
				<<	"><TR>";

	*pStream	<<	"<TD WIDTH=10%>"
				<<	"&nbsp;"
				<<	"</TD>"
				<<	"<TD WIDTH=18% ALIGN=center>"
				<<	"&nbsp;"
				<<	"</TD>"
				<<	"<TD WIDTH=30%>"
				<<	"<b>Account Balance</b>"
				<<	"</td>"
				<<	"<TD WIDTH=10% ALIGN=center>"
				<<	"&nbsp;"
				<<	"</td>"
				<<	"<TD WIDTH=10% ALIGN=right>"
				<<	"&nbsp;"
				<<	"</td>"
				<<	"<TD WIDTH=10% ALIGN=right>"
				<<	"&nbsp;"
				<<	"</TD>"
				<<	"<TD WIDTH=10% ALIGN=right>"
				<<	mBalance
				<<	"</TD>"
				<<	"</TR>"
				<<	"</TABLE>";

	//
	// when the balance is > $10 ,
	// test the credit card on file flag !!!!
	//
	if(mBalance > 10.0 && !mpUser->HasCreditCardOnFile())
	{
	//
	// The balance is more than $10 , ask the user to put his credit card on file
	//
		*pStream	<<	"<PRE> <STRONG>"
					<<	"Note: You are above the $10 limit for users without credit cards on file.<BR>"
					<<	"You should either "
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
					<<	"."
					<<	"</STRONG></PRE>";
	}


//
// The footer of MyAccount
//
	*pStream	<<	"<H2>"
				<<	"<A HREF="
				<<	"\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"account-status.html"
				<<	"\""
				<<	">"
				<<	"Full account"
				<<	"</A>"
				<<	"</H2>";

	*pStream	<<	"<PRE>"
				<<	"Up to the minute accounting of all credits, debits and current balance for<BR>"
				<<	" your eBay accounts. Accounts are not created until the first credit or debit<BR>"
				<<	" is posted, so even if you have already created your account, no information<BR>"
				<<	" will be here until the first account activity."
				<<	"</PRE>";

	*pStream	<< flush;
	return true;

}


// This routine have to be called n = 0..mNumItems-1 times 
bool clseBayMyAccountDetailWidget::EmitCell( ostream *pStream, int	n)
{

	time_t		nowTime;
	time_t		nowMinus29Time; // one month ago

	// Interesting formatting things
// petra	time_t		theTimeT;
// petra	struct tm	*pTheTime;
// petra	char		theDate[EBAY_ACCOUNT_STRING_LENGTH];
	int			indiceItem = 0;

	nowTime = time(0);
	nowMinus29Time = nowTime - (60 * 60 * 24 * 29 ) ;


	//
	// Determine the transaction order
	//
	indiceItem = (int) (n / EBAY_ACCOUNT_CELLS_PER_ITEM);

	//
	// If indiceItem is even and the color switch flag is equal to true
	// set the color on "#FFFFCC" (the first color to display)
	//
	if( ((indiceItem % 2) == 0) && (mColorSwitch) )
	{
		SetColor("#FFFFCC");
		//
		// if it is the last cell of the row to display,
		// change the color switch flag to false
		//
		if((n % EBAY_ACCOUNT_CELLS_PER_ITEM) == AccountTransactionBalance) 
		{
			mColorSwitch = false;
		}
	}
	else
	{
		// 
		// The indiceItem is odd and the color switch flag is equal to false
		// set the color on "#FFFFFF" and change the color switch if it is the
		// last cell of the row to display
		//
		SetColor("#FFFFFF");
		if((n % EBAY_ACCOUNT_CELLS_PER_ITEM) == AccountTransactionBalance) 
		{
			mColorSwitch = true;
		}
	}

	assert(mpvDetail);
	switch(n % EBAY_ACCOUNT_CELLS_PER_ITEM)
	{
	case AccountReferenceNumber:
		{
			//
			// Display the reference number of the transaction
			//

			*pStream	<<	"<TD WIDTH=10%>"
				<<	(*mpvDetail)[indiceItem]->mTransactionId
				<<	"</TD>"
				<<	"\n";
			
			break;
		}
		
	case AccountTransactionDate:
		{
			clseBayTimeWidget timeWidget (mpMarketPlace, 1, 0,				// petra
										  (*mpvDetail)[indiceItem]->mTime);	// petra

			//
			// Display the date/time of the transaction
			//
// petra			memset(theDate,0,sizeof(theDate));
// petra			theTimeT	= (*mpvDetail)[indiceItem]->mTime;
// petra			pTheTime	= localtime(&theTimeT);
// petra			strftime(theDate, sizeof(theDate),"%m/%d/%y %H:%M",pTheTime);
			*pStream	<<	"<TD WIDTH=18% ALIGN=CENTER>";
			timeWidget.EmitHTML (pStream);			// petra
// petra				<<	theDate
			*pStream	<<	"</TD>"
				<<	"\n";
			
			break;
		}
		
	case AccountTransactionType:
		{
			//
			// Display the type of the transaction
			//
			*pStream	<<	"<TD WIDTH=30%>"
				<<	mpAccount->GetAccountDetailDescriptor((*mpvDetail)[indiceItem]->mType)	//lint !e1705 ??
				<<	"</TD>"
				<<	"\n";
			break;
		}
		
	case AccountTransactionItem:
		{
			//
			// Open the cell of the Items
			//
			*pStream	<<	"<TD WIDTH=10% ALIGN=CENTER>";
			
			//
			// Check is the Item is new or old
			//
			if((*mpvDetail)[indiceItem]->mItemId != 0)
			{
				//
				// There is a new Item
				//
				if ((*mpvDetail)[indiceItem]->mTime > nowMinus29Time)
				{
					//
					// The new Item have a link, display it !
					//
					*pStream	<<	"<A HREF="
						<<	"\""
						<<	mpMarketPlace->GetCGIPath()
						<<	"eBayISAPI.dll?ViewItem&item="
						<<	(*mpvDetail)[indiceItem]->mItemId
						<<	"\""
						<<	">"
						<<	(*mpvDetail)[indiceItem]->mItemId
						<<	"</A>";
				}
				else
				{
					//
					// The Items don't have links, date > a month
					//
					*pStream	<<	(*mpvDetail)[indiceItem]->mItemId;
				}
				
			}
			else if ((*mpvDetail)[indiceItem]->mOldItemId[0] != '\0')
			{
				//
				// This is an old Item
				//
				if ((*mpvDetail)[indiceItem]->mTime > nowMinus29Time)
				{
					//
					// The old Item have a link, display it !
					//
					// TODO - fix www2
					*pStream	<<	"<A HREF="
						<<	"\""
						<<	"http://www2.ebay.com/aw/itemfast.cgi?item="
						<<	(*mpvDetail)[indiceItem]->mOldItemId
						<<	"\""
						<<	">"
						<<	(*mpvDetail)[indiceItem]->mOldItemId
						<<	"</A>";
				}
				else
				{
					//
					// The old Item don't have a link, date > a month
					//
					*pStream	<<	(*mpvDetail)[indiceItem]->mOldItemId;
				}
			}
			else
			{
				//
				// This transaction is not on an Item, display a blank cell
				//
				*pStream	<< "&nbsp;";
			}
			
			//
			// Close the Item cell
			//
			*pStream	<<	"</TD>"
				<<	"\n";
			break;
		}
		
	case AccountCredit:
		{
			if( (*mpvDetail)[indiceItem]->mAmount > 0)
			{
				//
				// The amount is positive, display it on green font
				//
				*pStream	<<	"<TD WIDTH=10% ALIGN=RIGHT>"
					<<	"<FONT COLOR=green>"
					<<	(*mpvDetail)[indiceItem]->mAmount
					<<	"</FONT>"
					<<	"</TD>";
			}
			else
			{
				//
				// The amount is negative, it is not a credit,
				// display only a "-" sign
				//
				*pStream	<<	"<TD WIDTH=10% ALIGN=RIGHT>"
					<<	"-"
					<<	"</TD>";
			}
			
			break;
		}
		
	case AccountDebit:
		{
			if( (*mpvDetail)[indiceItem]->mAmount < 0)
			{
				//
				// The amount is negative, display it !
				//
				*pStream	<<	"<TD WIDTH=10% ALIGN=RIGHT>"
					<<	(*mpvDetail)[indiceItem]->mAmount
					<<	"</TD>";
			}
			else
			{
				//
				// The amount is positive, it is not a debit,
				// display only a "-" sign
				//
				*pStream	<<	"<TD WIDTH=10% ALIGN=RIGHT>"
					<<	"-"
					<<	"</TD>";
			}
			
			break;
		}
		
	case AccountTransactionBalance:
		{
			//
			// Computation of the balance
			//
			mBalance = mBalance + (*mpvDetail)[indiceItem]->mAmount;
			
			//
			// Display the balance after the operation in a cell
			//
			*pStream	<<	"<TD WIDTH=10% ALIGN=RIGHT>"
				<<	mBalance
				<<	"</TD>";
			
			break;
		}
		
	default:
		{
			return false;
		}
		
	}
	
	
	// Every thing is allrigth !!!
	*pStream	<<	flush;
	return true;

}



