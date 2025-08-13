/*	$Id: clsUserAppViewAccount.cpp,v 1.11.152.1 1999/08/01 02:51:32 barry Exp $	*/
//
//	File:	clseBayAppViewLeftFeedback.cc
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Contains the methods user to retrieve
//		and show all feedback LEFT by a user..
//
// Modifications:
//				- 05/29/97 michael	- Created
//				- 06/23/98 inna		-fixed not to count account details that 
//									are already included in interim balance, twice
//				-6/29/98 inna		- added CC info to output 
//									(if on file, and first4digits not NULL)	
//				-07/19/99	nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
//
//				

#include "ebihdr.h"
#include "clseBayTimeWidget.h"

//
// ViewAccount
//
void clseBayApp::ViewAccount(CEBayISAPIExtension *pThis,
							 char *pUserId,
							 char *pPass,
							 bool entire,
							 bool sinceLastInvoice,
							 int daysBack,
							 char *pStartDate,
							 char *pEndDate)
{
	clsAccount						*pAccount;

	InterimBalanceList				lBalances;
	InterimBalanceList::iterator	ilBalances;

	AccountDetailVector				vDetail;
	AccountDetailVector::iterator	i;
	clsAccountDetail				*pDetail;

	bool							havePastDueBase;

	bool							startDateValid;
	bool							endDateValid;

	time_t							nowTime;
	time_t							startTime;
	time_t							endTime;
	time_t							nowMinus29Time;

	time_t							detailStartTime;
	double							detailStartBalance;

	double							balance;

	time_t							theTime;
	struct tm						*pTheTimeTM;
	struct tm						theTimeTM;
// petra	char							cTheTime[50];
	clseBayTimeWidget				theTimeWidget (mpMarketPlace,				// petra
												   EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
												   EBAY_TIMEWIDGET_LONG_TIME);	// petra
// petra	TimeZoneEnum					timeZone;
// petra	char							tempTime[30];

	char*							pColor;
	int								ColorSwitch = true;

#ifdef PURIFY_H_VERSION
    int	  num_bytes_leaked = 0;
#endif

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Title
	*mpStream <<	"<html>"
					"<head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Account Status for "
			  <<	pUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";


	if (strcmp(pPass, mpMarketPlace->GetSpecialPassword()) == 0)
	{
		mpUser = 
			mpUsers->GetAndCheckUserAndPassword(pUserId,		// Duh
												pPass,			// Duh
												mpStream,		// Duh
												true,			// Header sent alredy
												NULL,			// NO action
												false,			// Ghosts ok?
												false,			// Feedback needed?
												false,			// Account needed?
												true,			// Test Crypted?
												true);			// Admin Query
	}
	else
	{
		mpUser = 
			mpUsers->GetAndCheckUserAndPassword(pUserId,		// Duh
												pPass,			// Duh
												mpStream,		// Duh
												true,			// Header sent alredy
												NULL,			// NO action
												false,			// Ghosts ok?
												false,			// Feedback needed?
												false,			// Account needed?
												true,			// Test Crypted?
												false);			// Admin Query
	}

	if (!mpUser)
	{
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
	if ( (strcmp(pPass, mpMarketPlace->GetSpecialPassword()) != 0) &&
	   (mpUser->GetUserState() == UserGhost) )
	{
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;

	}


	// Let's get an account and the interim balances
	pAccount	= mpUser->GetAccount();

	// Let's see if there IS an account
	if (!pAccount->Exists())
	{
		*mpStream <<	"<h2>Your account has not been created</h2>"
						"Your "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" account has not been activated yet. Accounts "
						"are not accessible until an actual debit or credit has "
						"first been posted to the account, even though you may "
						"have already filled out our account creation form. "
						"This message confirms to you that you have no credit "
						"or debit balance at this time."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	pAccount->GetInterimBalances(&lBalances);

	// Let's figure the starting and ending times. To preserve
	// backward compatibility, we use the following precedence
	//
	//	- If entire is "true", then we just do the entire account
	//	- If sinceLastInvoice is true, then we do that.
	//	- If daysBack is not ZERO, we use that many days back from
	//	  today.
	//	- If start date and end date are valid, then use them.
	//	- Otherwise, get everything since the last invoice
	//
	// If the called passed the special password, then we'll do an admin
	// check. Otherwise, we won't ;-).
	//
	startDateValid		= true;
	endDateValid		= true;

	if (entire)
	{
		startTime	= 0;
		endTime		= time(0);
	}
	else if (sinceLastInvoice)
	{
		// The interim balances come in sorted, so the last
		// one (if there is one) represents the last time the
		// customer was invoiced.
		if (lBalances.size() == 0)
			startTime	= 0;
		else
		{
			ilBalances	= lBalances.end();
			ilBalances--;
			startTime = ((*ilBalances)->mWhen);
		}

		endTime	= time(0);
	}
	else if (strcmp(pStartDate, "default") != 0 &&
			 strcmp(pEndDate, "default") != 0		)
	{
		if (*(pStartDate + 2) != '/'	||
			*(pStartDate + 5) != '/'		)
		{
			startDateValid	= false;
		}
		else
		{
			memset(&theTimeTM, 0x00, sizeof(theTimeTM));
			theTimeTM.tm_mon	= atoi(pStartDate + 0) - 1;
			theTimeTM.tm_mday	= atoi(pStartDate + 3);
			theTimeTM.tm_year	= atoi(pStartDate + 6);
			theTimeTM.tm_isdst	= -1;

			startTime		= mktime(&theTimeTM);
			startDateValid	= true;
		}

		if (*(pEndDate + 2) != '/'	||
			*(pEndDate + 5) != '/'		)
		{
			endDateValid	= false;
		}
		else
		{
			memset(&theTimeTM, 0x00, sizeof(theTimeTM));
			theTimeTM.tm_mon	= atoi(pEndDate + 0) - 1;
			theTimeTM.tm_mday	= atoi(pEndDate + 3);
			theTimeTM.tm_year	= atoi(pEndDate + 6);
			theTimeTM.tm_isdst	= -1;

			endTime			= mktime(&theTimeTM);
			endDateValid	= true;
		}
	}
	else if (daysBack != 0) 
	{
		// Let's get 00:00 today
		theTime	= time(0);
		pTheTimeTM	= localtime(&theTime);
		memcpy(&theTimeTM, pTheTimeTM, sizeof(theTimeTM));

		theTimeTM.tm_hour	= 0;
		theTimeTM.tm_min	= 0;
		theTimeTM.tm_sec	= 0;

		startTime	= mktime(&theTimeTM);
		startTime	= startTime - (daysBack * 24 * 60 * 60);

		endTime		= time(0);
	}
	else
	{
		// Let's get 00:00 today
		theTime	= time(0);
		pTheTimeTM	= localtime(&theTime);
		memcpy(&theTimeTM, pTheTimeTM, sizeof(theTimeTM));

		theTimeTM.tm_hour	= 0;
		theTimeTM.tm_min	= 0;
		theTimeTM.tm_sec	= 0;

		startTime	= mktime(&theTimeTM);
		startTime	= startTime - (30 * 24 * 60 * 60);

		endTime		= time(0);
	}

	if (!startDateValid)
	{
		*mpStream <<	"<h2>Invalid Start Date!</h2>"
						"Sorry, but the starting date was missing or "
						"invalid. Please go back and try again!"
						"<p>";
	}

	if (!endDateValid)
	{
		*mpStream <<	"<h2>Invalid Ending Date!</h2>"
						"Sorry, but the ending date was missing or "
						"invalid. Please go back and try again!"
						"<p>";
	}

	if (!startDateValid || !endDateValid)
	{
		*mpStream <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}


	detailStartTime		= (time_t)0;
	detailStartBalance	= 0;
	for (ilBalances = lBalances.begin();
		 ilBalances != lBalances.end();
		 ilBalances++)
	{
		if ((*ilBalances)->mWhen > startTime)
			break;

		detailStartTime		= (*ilBalances)->mWhen;
		detailStartBalance	= (*ilBalances)->mBalance;
	}

	// We don't need no stinking interim balances no more ;-)
	for (ilBalances = lBalances.begin();
		 ilBalances != lBalances.end();
		 ilBalances++)
	{
		delete (*ilBalances);
	}

	lBalances.erase(lBalances.begin(), lBalances.end()); 

	// Now, let's either get ALL the account detail (if we were
	// unlucky, and don't have an interim balance), or some it
	// it, if we were.
	if (detailStartTime == (time_t)0)
		pAccount->GetAccountDetail(&vDetail);
	else
		pAccount->GetAccountDetail(&vDetail, detailStartTime - 1);

	// Figure one month ago for when items have "expired"
	nowTime	= time(0);
	nowMinus29Time	= nowTime - (60 * 60 * 24 * 29);

	*mpStream <<	"<h2>Account Status for "
			  <<	mpUser->GetUserId();
	
	if (strcmp(pPass, mpMarketPlace->GetSpecialPassword()) == 0)
	{
		// put in email
		*mpStream << " <a href=\"mailto:"
				  << mpUser->GetEmail()
				  << "\">"
				  << mpUser->GetEmail()
				  << "</a>\n";
	}

	*mpStream <<	" (E"
			  <<	mpUser->GetId()
			  <<	")</h2>";

	// Let's compute the balance
	balance	= detailStartBalance;
	for (i = vDetail.begin();
		 i != vDetail.end();
		 i++)
	{
	//INNA  if this detail amount line is 
	//created at the same time or prior to the 
	//ebay_interim_balances record, skip it
		if ((*i)->mTime <= detailStartTime)
			continue;
		balance	= balance + (*i)->mAmount;
	}

	*mpStream <<	"<font size=+1>"
					"Account Balance: "
					"<b>";

	// Right now, we only accept payments in USD.
	clsCurrencyWidget currencyWidget(mpMarketPlace, Currency_USD, balance);
	currencyWidget.EmitHTML(mpStream);
			
	*mpStream <<	"</b>"
					"</font>"
					"<p>";
				

	if ( mpUser->GetUserId() != NULL )
	{
	*mpStream <<	"<table border=1 width=50% bgcolor=#FFFFFF>"
					"<tr>"
					"<td align=left>"
					"Account Name"
					"</td>"
					"<td align=left>"
			  <<	mpUser->GetUserId()
			  <<	"</td>"
					"</tr>"
					"<tr>"
					"<td align=left>"
					"Account Id"
					"</td>"
					"<td align=left>";

		*mpStream <<	"E"
				  <<	mpUser->GetId();

		*mpStream <<	"</td>"
					"</tr>"
					"<tr>"
					"<td align=left>"
					"Credit Card on file:"
					"</td>"
					"<td align=left>";
	}

	if (mpUser->HasCreditCardOnFile())
	{
		*mpStream <<	"Yes";
	// inna add CC information
		if (pAccount->GetCCIdForUser()!=NULL)
		{
			
			*mpStream <<	"</td>"
							"</tr>"
							"<tr>"
							"<td align=left>"
							"Credit Card Info:"
							"</td>"
							"<td align=left>";

			*mpStream <<	pAccount->GetCCIdForUser();

// petra			theTime	= pAccount->GetCCExpiryDate();
// petra			pTheTimeTM	= localtime(&theTime);


			//samuel au, 4/8/99
// petra			timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra			theTimeWidget.SetTime(theTime);
// petra			theTimeWidget.SetTimeZone(timeZone);
			
// petra			theTimeWidget.BuildDateString(cTheTime);

			theTimeWidget.SetDateTimeFormat (EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
											 EBAY_TIMEWIDGET_NO_TIME);		// petra
			theTimeWidget.SetTime (pAccount->GetCCExpiryDate() );			// petra
			*mpStream <<	"&nbsp "
					  <<	"Exp: ";
			theTimeWidget.EmitHTML (mpStream);								// petra
// petra					  <<	cTheTime;

			
			
			*mpStream <<	"</td>"
							"</tr>"
							"<tr>"
							"<td align=left>"
							"Credit Card Update:"
							"</td>"
							"<td align=left>";

// petra			theTime	= pAccount->GetLastCCUpdate();
// petra			pTheTimeTM	= localtime(&theTime);


			//samuel au, 4/8/99
			// continue using the timeZone set above

			theTimeWidget.SetDateTimeFormat (EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
											 EBAY_TIMEWIDGET_LONG_TIME);	// petra
			theTimeWidget.SetTime (pAccount->GetLastCCUpdate() );			// petra
			theTimeWidget.EmitHTML (mpStream);
		}
	} //end inna
	else
		*mpStream <<	"No";

	*mpStream <<	"</td>"
					"</tr>"
					"</table>"
					"<br>";

	//
	// Secret stuff
	//
	if (strcmp(pPass, mpMarketPlace->GetSpecialPassword()) == 0)
	{
		//
		// Appropriate Actions
		//
//		*mpStream <<	"<b>User Action</b>: ";

		if (mpUser->GetUserState() != UserGhost)
		{
			*mpStream <<	"<b>User Action</b>: ";

			*mpStream <<	"<A HREF="
							"\""
					  <<	mpMarketPlace->GetAdminPath()
					  <<	"eBayISAPI.dll?";
			switch (mpUser->GetUserState())
			{
				case	UserSuspended:
					*mpStream <<	"ReinstateUser"
							  <<	"&userid="
							  <<	mpUser->GetUserId()
							  <<	"\""
							  <<	">"
							  <<	"Reinstate"
							  <<	"</A>";
					break;
				case	UserConfirmed:
					*mpStream <<	"AdminSuspendUserShow"
							  <<	"&userid=&pass=&target="
							  <<	mpUser->GetUserId()
							  <<	"\""
							  <<	">"
							  <<	"Suspend"
							  <<	"</A>";
					break;
				case	UserUnconfirmed:
				case	UserCCVerify:
					*mpStream <<	"ConfirmUser"
							  <<	"&userid="
							  <<	mpUser->GetUserId()
							  <<	"\""
							  <<	">"
							  <<	"Confirm"
							  <<	"</A>";
					break;
				default:
					break;
			}

			*mpStream <<	"<br><br>";
		}
		else
		{
			*mpStream <<	"<b>User Status</b>: ";

			*mpStream <<	"Ghost user"
//						<<	"\""
						<<	"</A>";
		}		

		*mpStream <<	"<table border=1 width=50% bgcolor=#FFCCCC>";

// petra		theTime	= mpUser->GetCreated();
// petra		pTheTimeTM	= localtime(&theTime);

		//samuel au, 4/8/99
// petra		timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra		theTimeWidget.SetTime(theTime);
// petra		theTimeWidget.SetTimeZone(timeZone);
// petra		cTheTime[0] = '\0';
// petra		theTimeWidget.BuildDateString(cTheTime);
// petra		theTimeWidget.BuildTimeString(tempTime);
// petra		strcat(cTheTime, " ");
// petra		strcat(cTheTime, tempTime);
		
		*mpStream <<	"<tr>"
						"<td align=left>"
						"Member Since"
						"</td>"
						"<td align=left>";
		theTimeWidget.SetTime (mpUser->GetCreated() );		// petra
		theTimeWidget.EmitHTML (mpStream);					// petra
// petra				  <<	cTheTime	
		*mpStream  <<	"</td>"								// petra
						"</tr>";

// petra		theTime	= mpUser->GetLastModified();
// petra		pTheTimeTM	= localtime(&theTime);

		//samuel au, 4/8/99
// petra		timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra		theTimeWidget.SetTime(theTime);
// petra		theTimeWidget.SetTimeZone(timeZone);
// petra		cTheTime[0] = '\0';
// petra		theTimeWidget.BuildDateString(cTheTime);
// petra		theTimeWidget.BuildTimeString(tempTime);
// petra		strcat(cTheTime, " ");
// petra		strcat(cTheTime, tempTime);

		//strftime(cTheTime, sizeof(cTheTime),
		//		 "%m/%d/%y %H:%M",
		//		  pTheTimeTM);
		//end

		*mpStream <<	"<tr>"
						"<td align=left>"
						"Last Modified"
						"</td>"
						"<td align=left>";
		theTimeWidget.SetTime (mpUser->GetLastModified() );		// petra
		theTimeWidget.EmitHTML (mpStream);						// petra
// petra				  <<	cTheTime
		*mpStream  <<	"</td>"									// petra
						"</tr>";
		if ( mpUser->GetHost() != NULL )
				*mpStream <<	"<tr>"
						"<td align=left>"
						"Host"
						"</td>"
						"<td align=left>"
				  <<	mpUser->GetHost()
				  <<	"</td>"
						"</tr>";

		*mpStream <<	"<tr>"
						"<td align=left>"
						"Blessed account:"
						"</td>"
						"<td align=left>";

		if (mpUser->HasGoodCredit())
			*mpStream <<	"yes";
		else
			*mpStream <<	"no";

		*mpStream <<	"</td>"
						"</tr>";

		// Past due base Conversion
		theTime	= pAccount->GetPastDueBase();
		if (theTime != 0)
		{
// petra			pTheTimeTM	= localtime(&theTime);

			//samuel au, 4/8/99
// petra			timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra			theTimeWidget.SetTime(theTime);
// petra			theTimeWidget.SetTimeZone(timeZone);
// petra			cTheTime[0] = '\0';
// petra			theTimeWidget.BuildDateString(cTheTime);
// petra			theTimeWidget.BuildTimeString(tempTime);
// petra			strcat(cTheTime, " ");
// petra			strcat(cTheTime, tempTime);
	
			havePastDueBase	= true;
		}
		else
		{
			havePastDueBase	= false;
		}

		*mpStream <<	"<tr>"
						"<td align=left>"
						"Past due base"
						"</td>"
						"<td align=left>";

		if (havePastDueBase)
		{												// petra
			theTimeWidget.SetTime (theTime);			// petra
			theTimeWidget.EmitHTML (mpStream);			// petra
// petra			*mpStream <<	cTheTime;
		}												// petra
		else
			*mpStream <<	"<b>Not Calculated</b>";

		*mpStream <<	"</td>";

		*mpStream <<	"<tr>"
						"<td align=left>"
						"30 Days"
						"</td>"
						"<td align=left>"
				  <<	pAccount->GetPastDue30Days()
				  <<	"</td>"
						"</tr>";

		*mpStream <<	"<tr>"
						"<td align=left>"
						"60 Days"
						"</td>"
						"<td align=left>"
				  <<	pAccount->GetPastDue60Days()
				  <<	"</td>"
						"</tr>";

		*mpStream <<	"<tr>"
						"<td align=left>"
						"90 Days"
						"</td>"
						"<td align=left>"
				  <<	pAccount->GetPastDue90Days()
				  <<	"</td>"
						"</tr>";
		*mpStream <<	"<tr>"
						"<td align=left>"
						"120 Days"
						"</td>"
						"<td align=left>"
				  <<	pAccount->GetPastDue120Days()
				  <<	"</td>"
						"</tr>";

		*mpStream <<	"<tr>"
						"<td align=left>"
						"Over 120 Days"
						"</td>"
						"<td align=left>"
				  <<	pAccount->GetPastDueOver120Days()
				  <<	"</td>"
						"</tr>";

		if (mpUser->GetName())
		{
			*mpStream <<	"<tr>"
							"<td align=left>"
							"Name"
							"</td>"
							"<td align=left>"
					  <<	mpUser->GetName()
					  <<	"</td>"
							"</tr>";
		}

		*mpStream <<	"<tr>"
						"<td align=left>"
						"Address"
						"</td>"
						"<td align=left>";

		if (mpUser->GetAddress())
			*mpStream <<	mpUser->GetAddress();

		*mpStream	<<	",";

		if (mpUser->GetCity())
			*mpStream <<	mpUser->GetCity()
					  <<	",";

		if (mpUser->GetState())
			*mpStream <<	mpUser->GetState();
		
		*mpStream	<<	",";

		if (mpUser->GetZip())
			*mpStream <<	mpUser->GetZip();

		*mpStream	<<	"</td>"
						"</tr>";

		*mpStream	<<	"<tr>"
						"<td align=left>"
						"Phone"
						"</td>"
						"<td align=left>";

		if (mpUser->GetDayPhone())
		{
			*mpStream <<	"Day:"
					  <<	mpUser->GetDayPhone()
					  <<	", ";
		}

		if (mpUser->GetNightPhone())
		{
			*mpStream <<	"Night:"
					  <<	mpUser->GetNightPhone()
					  <<	", ";
		}

		if (mpUser->GetFaxPhone())
		{
			*mpStream <<	"Fax:"
					  <<	mpUser->GetFaxPhone();
		}

		*mpStream <<	"</td>"
						"</tr>"
						"</table>"
						"<br>";
	}

	// Some table heading stuff!
	*mpStream <<	"<table border=1 width=100% cellspacing=0 BGCOLOR=#009900>\n"
					"<tr>"
					"<th width=10% align=center>Ref #</th>"
					"<th width=18% align=center>Date</th>"
					"<th width=30% align=center>Type</th>"
					"<th width=10% align=center>Item</th>"
					"<th width=10% align=center>Credit</th>"
					"<th width=10% align=center>Debit</th>"
					"<th width=10% align=center>Balance</th>"
					"</tr></table>\n";

	balance	= detailStartBalance;

	for (i = vDetail.begin();
		 i != vDetail.end();
		 i++)
	{
		pDetail	= (*i);

		// First, balance adjustment
		// INNA only if this is not a part of interim balance
		// record already
	if ((*i)->mTime > detailStartTime)
		balance	= balance + pDetail->mAmount;

		// Now, see if we want to show it
		if (pDetail->mTime >= startTime	&&
			pDetail->mTime <= endTime			)
		{
			if (ColorSwitch)
			{
				pColor		= "#FFFFCC";
				ColorSwitch	= false;
			}
			else
			{
				pColor		= "#FFFFFF";
				ColorSwitch	= true;
			}

			// Reference
			*mpStream << "<table width=100% border=0 cellspacing=0 bgcolor="
					  << pColor
					  << "><tr>";

			*mpStream << "<td width=10%>"
					  << pDetail->mTransactionId
					  << "</td>";

			// Time Conversion
// petra			theTime		= pDetail->mTime;
// petra			pTheTimeTM	= localtime(&theTime);

			//samuel au, 4/8/99
// petra			timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra			theTimeWidget.SetTime(theTime);
// petra			theTimeWidget.SetTimeZone(timeZone);
// petra			cTheTime[0] = '\0';
// petra			theTimeWidget.BuildDateString(cTheTime);
// petra			theTimeWidget.BuildTimeString(tempTime);
// petra			strcat(cTheTime, " ");
// petra			strcat(cTheTime, tempTime);

			*mpStream <<	"<td width=18% align=center>";
			theTimeWidget.SetTime (pDetail->mTime);			// petra
			theTimeWidget.EmitHTML (mpStream);				// petra
// petra					  <<	cTheTime
			*mpStream  <<	"</td>"							// petra
					  <<	"<td width=30%>"
					  <<	pAccount->GetAccountDetailDescriptor(pDetail->mType)
					  <<	"</td>";

			*mpStream <<	"<td width=10% align=center>";

			if (pDetail->mItemId != 0)
			{
				if (pDetail->mTime > nowMinus29Time)
				{
					*mpStream <<	"<A HREF="
									"\""
							  <<	mpMarketPlace->GetCGIPath(PageViewItem)
							  <<	"eBayISAPI.dll?ViewItem&item="
							  <<	pDetail->mItemId
							  <<	"\""
									">"
							  <<	pDetail->mItemId
							  <<	"</a>";
				}
				else
				{
					*mpStream <<	pDetail->mItemId;
				}
			}
			else if (pDetail->mOldItemId[0] != '\0')
			{
				*mpStream <<	pDetail->mOldItemId;
			}
			else
				*mpStream <<  "&nbsp;";

			*mpStream <<	"</td>";

			if (pDetail->mAmount > 0)
			{
				*mpStream <<	"<td width=10% align=right>"
								"<font color=green>"
						  <<	pDetail->mAmount
						  <<	"</font>"
						  <<	"</td>"
						  <<	"<td width=10% align=right>"
						  <<	"-"
						  <<	"</td>";
			}
			else if (pDetail->mAmount < 0)
			{
				*mpStream <<	"<td width=10% align=right>"
						  <<	"-"
						  <<	"</td>"
						  <<	"<td width=10% align=right>"
						  <<	pDetail->mAmount
						  <<	"</td>";
			}
			else
			{
				*mpStream <<	"<td width=10% align=right>"
								"-"
								"</td>"
								"<td width=10% align=right>"
								"-"
								"</td>";
			}

			*mpStream <<	"<td width=10% align=right>"
					  <<	balance
					  <<	"</td>"
							"</tr>";
			// Reference
			if (pDetail->mpMemo)
			{
				*mpStream <<	"<tr>"
								"<td WIDTH=10%>"
								"&nbsp"
								"</td>"
								"<td WIDTH=18% align=center>"
								"&nbsp"
								"</td>"
								"<td width=30%>"
						  <<	pDetail->mpMemo
						  <<	"</td>"
								"<td width=10% align=right>"
								"&nbsp"
								"</td>"
								"<td width=10% align=right>"
								"&nbsp"
								"</td>"
								"<td width=10% align=right>"
								"&nbsp"
								"</td>"
								"<td width=10% align=right>"
								"&nbsp"
								"</td>"
								"</tr>";
			}
			*mpStream <<	"</table>\n";
		}
	}

	//
	// Always print a balance line
	//
	if (ColorSwitch)
	{
		pColor		= "#FFFFCC";
		ColorSwitch	= false;
	}
	else
	{
		pColor		= "#FFFFFF";
		ColorSwitch	= true;
	}

	*mpStream << "<table width=100% border=0 cellspacing=0 bgcolor="
			  << pColor
			  << "><tr>";

	*mpStream <<	"<td width=10%>"
			  <<	"&nbsp;"
			  <<	"</td>"
			  <<	"<td width=18% align=center>"
			  <<	"&nbsp;"
			  <<	"</td>"
			  <<	"<td width=30%>"
			  <<	"<b>Account Balance</b>"
			  <<	"</td>"
			  <<	"<td width=10% align=center>"
					"&nbsp;"
					"</td>"
					"<td width=10% align=right>"
					"&nbsp;"
					"</td>"
					"<td width=10% align=right>"
					"&nbsp;"
					"</td>"
					"<td width=10% align=right>"
			  <<	balance
			  <<	"</td>"
					"</tr>"
					"</table>";

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	for (i = vDetail.begin();
		 i != vDetail.end();
		 i++)
	{
	
		delete	(*i);
	}

	vDetail.erase(vDetail.begin(), vDetail.end());

	delete	pAccount;

	CleanUp();

	return;
}

