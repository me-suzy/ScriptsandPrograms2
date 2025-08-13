/*	$Id: clsInvoiceApp.cpp,v 1.7.64.1.2.2 1999/08/06 02:26:58 nsacco Exp $	*/
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsInvoiceApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsAccount.h"
#include "clsAnnouncements.h"
#include "clsAnnouncement.h"
#include "clsUtilities.h"
#include "clsMail.h"
#include <clseBayTimeWidget.h>	// petra

#include "hash_map.h"
#include "iterator.h"
#include "tcpstuff.h"

#include "clsInvAndBalAgingState.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

#ifdef _MSC_VER
#include <process.h>
#else
#include <sys/types.h>
#include <unistd.h>
#include <signal.h> 
#endif

#ifdef _MSC_VER
#include <strstrea.h>
#else
#include <strstream.h>
#endif

#ifdef _WIN32
FILE *popen(const char *, const char *);
int pclose(FILE *);
#endif

static const char *FinanceChargeMemo = "$%8.2f on $%8.2f";

static const char *InvoiceMemo		 = "Amount $%8.2f due on %s";

// 
// A version of atof which eats white space
//
//==============================================================================

float atofWithWhiteSpace(char *pIt)
{
	char	*pC;

	for (pC	= pIt;
		 *pC != '\0' && *pC == ' ';
		 pC++)
	{
		;
	}

	return atof(pC);

}  // atofWithWhiteSpace

//===============================================================================
//
// Sort routine
//
static bool sort_account_detail_time(clsAccountDetail *pA, clsAccountDetail *pB)
{
	if (pA->mTime < pB->mTime)
		return true;

	return false;

} // sort_account_detail_time

//===============================================================================

clsInvoiceApp::clsInvoiceApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	mpAnnouncements = (clsAnnouncements *)0;
	return;

}  // clsInvoiceApp::clsInvoiceApp

//==============================================================================

clsInvoiceApp::~clsInvoiceApp()
{
	return;

} // clsInvoiceApp::~clsInvoiceApp

//==================================================================================

void clsInvoiceApp::CurrentInvoiceTime( tm &thisTime, int month )
{
	thisTime.tm_sec			= 59;
	thisTime.tm_min			= 59;
	thisTime.tm_hour		= 23;
	if ( month > 0 )
		thisTime.tm_mon = month;
	if ( thisTime.tm_mon == 0 )
	{
		thisTime.tm_year = thisTime.tm_year-1;
		thisTime.tm_mon = 11;
	}
	else
		thisTime.tm_mon--;	// Month - 1!!!
	thisTime.tm_mday =	
		LastDayOfMonth( thisTime.tm_mon + 1, thisTime.tm_year );
	thisTime.tm_isdst			= -1;

}  // clsInvoiceAapp::CurrentInvoiceTime

//================================================================================

void clsInvoiceApp::PreviousInvoiceTime( tm &previousInvoiceTime )
{
	if ( previousInvoiceTime.tm_mon == 0 )
	{
		previousInvoiceTime.tm_year--;
		previousInvoiceTime.tm_mon = 11;
	}
	else
		previousInvoiceTime.tm_mon--;	// Month - 1!!!
	previousInvoiceTime.tm_mday =
		LastDayOfMonth( previousInvoiceTime.tm_mon + 1, previousInvoiceTime.tm_year );
	previousInvoiceTime.tm_isdst			= -1;

}  // clsInvoiceApp::PreviousInvoiceTime

//============================================================================

int clsInvoiceApp::LastDayOfMonth( int month, int year )
{
	unsigned int days = 0;
	switch ( month )
	{
		case 1: 
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			days = 31;
			break;
		case 4: 
		case 6: 
		case 9: 
		case 11:
			days = 30;
			break;
		case 2:
			{
			if ( LeapYear( year ) )
				days = 29;
			else
				days = 28;
			}
			break;
		default:
			break;
	}
	return days;

}  // clsInvoiceApp::LastDayOfMonth

//=============================================================================

bool clsInvoiceApp::LeapYear( int year )
{
	if ( ( year % 4 ) == 0 ) 
	{
		if ( ( ( year + 1900 ) % 1000 ) == 0 )
			return false;
		else
			return true;
	}
	return false;

}  // clsInvoiceApp::LeapYear

//=============================================================================

void clsInvoiceApp::InitEnvironment()
{
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	if (!mpItems)
		mpItems			= mpMarketPlace->GetItems();

	if (!mpAnnouncements)
		mpAnnouncements = mpMarketPlace->GetAnnouncements();
	return;

}  // clsInvoiceApp::InitEnvironment

//=============================================================================

void clsInvoiceApp::DueDate ( tm &dueDate )
{
	if (dueDate.tm_mon == 11)
		dueDate.tm_mon	= 0;
	else
		dueDate.tm_mon	= dueDate.tm_mon + 1;

	dueDate.tm_mday		= 28;

	if (dueDate.tm_mon == 0)
		dueDate.tm_year	= dueDate.tm_year + 1;

}  // clsInvoiceApp::DueDate

//=============================================================================

void clsInvoiceApp::AddInterimBalance( clsAccount *pAccount, 
									  int id, time_t theTime, float amount )
{
	if ( pAccount )
		pAccount->AddInterimBalance( id, theTime, amount );
	return;

}  // clsInvoiceApp::AddInterimBalance

//=============================================================================

void clsInvoiceApp::AddRawAccountDetail( char *pMemo, int detail,
										double amount, time_t thisInvoiceTime,
										clsAccount *pAccount )
{
	AccountDetailTypeEnum detailType = (AccountDetailTypeEnum)detail;
	clsAccountDetail *pAccountDetail;

	pAccountDetail	= new clsAccountDetail();
	pAccountDetail->mTime			= thisInvoiceTime;
	pAccountDetail->mType			= detailType;
	pAccountDetail->mAmount			= amount;
	pAccountDetail->mpMemo			= pMemo;
	pAccountDetail->mTransactionId	= 0;
	pAccountDetail->mItemId			= 0;
	pAccount->AddRawAccountDetail(pAccountDetail);
	delete pAccountDetail;

	return;

}  // clsInvoiceApp::AddRawAccountDetail

//============================================================================

	//void clsInvoiceApp::Run()
void clsInvoiceApp::Run( vector<unsigned int>& requestedUsers, int month, 
						int idStart, int idEnd)
{
	time_t							theTime;  
	const struct tm				*pTheTime;   
	char								cTheTime[32];  
	clsAnnouncement			*pAnnouncement;

	// This is the vector of userids who've got accounts
	vector<unsigned int>					vUsers;
	vector<unsigned int>::iterator	i;
	int										userCount	= 0;
	int                                     userRecCount = 0;
	int										invoiceCount	= 0;
	double									totalBilled		= 0;

	clsUser							*pUser;
	clsAccount						*pAccount;

	int								AWAccountId;

	AccountDetailVector				vAccount;
	AccountDetailVector::iterator	ii;

	bool							error;

	struct tm						thisInvoiceTimeTM;
	struct tm						*pThisInvoiceTimeTM;
	time_t							thisInvoiceTime;
	char							cThisInvoiceDate[32];
	char							cThisInvoiceTime[32];
// petra - unused	char							cThisInvoiceDateRFC802[128];

	struct tm						dueDateTM;
	char							cDueDate[32];


	struct tm						lastInvoiceTimeTM;
	time_t							lastInvoiceTime;
	time_t							lastInvoiceTimeForUser;

	double							paymentsSinceLastInvoice;

	// Another name for "Past due" ;-)
	double							subjectToFinanceCharge;

	double							financeCharge;

	// Running balance
	double							balance;
	double							interimBalance;

	// Do we need a payment Coupon?
	bool							payCouponNeeded;

//	char							*pTheInvoice;

	int								descriptorMaxLen	=
				clsAccount::GetAccountDetailDescriptorMaxLength();
	int								descriptorLen;
	int								ic;

	//
	// Mailer
	//
	clsMail							*pMail;
	ostrstream						*pM;
	char							subject[512];
	int								mailRc = 0;
	// OUR accouting detail records
	clsAccountDetail				*pAccountDetail;
	char							*pMemo;
	time_t							nowTime;
//	char							**recipients;
//	char							lenamail[14];
	FILE							*pLogFile;
	bool							first = false;

	char							*pSafeText;

	clsInvAndBalAgingState			*pInvAndBalAgingState;
	char							pPid[11];
	int								our_pid;
	PidCheckEnum					stateInfo;
	
// winsock initialisation
#ifdef _MSC_VER
	startwinsockets();
#endif

	// The things we need
	InitEnvironment();
	// First, let's set our own baselines.

	//
	// thisInvoiceTime is the "as-of" for this invoice. 
	// 
	// ** NOTE **
	// Let's make this a parameter
	// ** NOTE **
	//
//  Lena
	memset(&thisInvoiceTimeTM, 0x00, sizeof(thisInvoiceTimeTM));
//	*nowTime	= time(0);
	time( &nowTime );
	thisInvoiceTimeTM = *gmtime( &nowTime );

	CurrentInvoiceTime( thisInvoiceTimeTM, month );
	strftime(cThisInvoiceDate, sizeof(cThisInvoiceDate), "%m/%d/%y",
             &thisInvoiceTimeTM);
// petra	strftime(cThisInvoiceTime, sizeof(cThisInvoiceTime), "%m/%d/%y %H:%M:%Y PDT",
	strftime(cThisInvoiceTime, sizeof(cThisInvoiceTime), "%m/%d/%y %H:%M:%Y",
             &thisInvoiceTimeTM);
	strcat (cThisInvoiceTime, mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale()->GetTimeZone() );	// petra
// petra - unused	strftime(cThisInvoiceDateRFC802, sizeof(cThisInvoiceDateRFC802),
// petra				"%a, %d %b %Y %H:%M:%S %Z",
// petra				&thisInvoiceTimeTM);

	thisInvoiceTime = mktime( &thisInvoiceTimeTM );
//	if ( thisInvoiceTime < nowTime )
//		first = true;


	//Sonya: get pid
	#ifdef _MSC_VER
		our_pid = getpid();
	#else
		our_pid = _getpid();
	#endif
	sprintf(pPid,"%d", our_pid);

	//Sonya: Now we figured out the thisInvoiceTime, pid of this process with the startid, end id and 
	//this invoice time, we can construct a clsInvAndBalAgingState object
	pInvAndBalAgingState = new clsInvAndBalAgingState(thisInvoiceTime, idStart, idEnd, Invoice , pPid);

	stateInfo=pInvAndBalAgingState->GetInvAndBalAgingStateInfo();
	switch( stateInfo)
	{
		case  BatchDown :
			printf("the batch from %d to %d for invoice date %s has been down.\n", idStart, idEnd, 
					cThisInvoiceDate);
			return;

		case  BatchNoClearance :
			printf(" the batch record:  from %d to %d for invoice date %s hasn't been cleaned in the state table.\n",
				idStart, idEnd, cThisInvoiceDate);
			return;

		case  BatchRerunOK :
			time(&nowTime);
			pInvAndBalAgingState->SetStartTime(nowTime);
			pInvAndBalAgingState->IncrementProcessCount();
			if(!(pInvAndBalAgingState->UpdateInvAndBalAgingStateInfo()))
			{
				printf("Can't update the record in the table. Invoice exit\n");
				return;
			}
			
			break;

		case  BatchNotExist :
			
			if(pInvAndBalAgingState->IsRangeOverlap())
			{
				printf(" Current batch has a range from %d to %d overlapped with existing ranges\n",
					pInvAndBalAgingState->GetStartId(), pInvAndBalAgingState->GetEndId());
				return;
			}

			time(&nowTime);
			pInvAndBalAgingState->SetStartTime(nowTime);
			pInvAndBalAgingState->IncrementProcessCount();
			if(!pInvAndBalAgingState->CreateInvAndBalAgingStateInfo())
			{
				printf("Can't create the record in the table. Invoice exit\n");
				return;
			}
			pInvAndBalAgingState->IncrementRangeOverlapCount();

			if(pInvAndBalAgingState->IsRangeOverlap())
			{	
				pInvAndBalAgingState->CleanUpOverlappedRecord();
				printf(" Warning: two instances overlapped on range and run at about the same time!\n");
				return;
			}
			
			
			break;

		default :
			printf(" An error occured when trying to get the state info form state table\n");
			return;
	}
		
		
		
		
	
	//
	// Due Date
	//
	memcpy(&dueDateTM, &thisInvoiceTimeTM, sizeof(dueDateTM));
	DueDate( dueDateTM );
	strftime(cDueDate, sizeof(cDueDate), "%m/%d/%y",
             &dueDateTM);


	//
	// lastInvoiceTime is when we did our last invoice.
	//
	// NORMALLY, this is computed, but it can be forced.
	//
	// ** NOTE **
	// Let's make this a parameter
	// ** NOTE **
	//
	lastInvoiceTimeTM = thisInvoiceTimeTM;
	PreviousInvoiceTime( lastInvoiceTimeTM );
	lastInvoiceTime	= mktime(&lastInvoiceTimeTM);
	lastInvoiceTimeForUser = lastInvoiceTime;


	// Ok, let's get all the users who have accounts...

	if ( requestedUsers.size() > 0 )
		vUsers = requestedUsers;
	else
		mpDatabase->GetUsersWithAccountsNotInvoiced( &vUsers, thisInvoiceTime, idStart, idEnd );
//		mpUsers->GetUsersWithAccounts(&vUsers);


	printf("*** %d users to check for invoicing!\n",
			 vUsers.size());
	pLogFile	= fopen("invoicelog.txt", "w+");

	if (!pLogFile)
	{
		fprintf(stderr,"%s:%d Unable to open log file. \n",
			  __FILE__, __LINE__);

		// cleanup?
		return;
	}
	fprintf(pLogFile, "invoice for month:%d\n", thisInvoiceTimeTM.tm_mon + 1 );
	// Now, we loop through them
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
		userCount++;

		error			= false;
		payCouponNeeded	= true;
		
		paymentsSinceLastInvoice	= 0;
		subjectToFinanceCharge		= 0;
		financeCharge				= 0;
		balance						= 0;
		interimBalance				= 0;
		userRecCount                = 0;
		lastInvoiceTimeForUser = lastInvoiceTime;
		
		pUser	= mpUsers->GetUser((*i));
		if (!pUser)
		{
			printf("** Error ** Can not get user %d\n", (*i));
			continue;
		}

		pAccount	= pUser->GetAccount();

		if (!pAccount)
		{
			printf("** Error ** Could not get account for %s (%d)\n",
					pUser->GetUserId(),
					pUser->GetId());
			continue;
		}
	
		if ( pAccount->GetInterimBalance( pUser->GetId(), 
			lastInvoiceTimeForUser, interimBalance, first ) )
		{
//			pAccount->GetAccountDetail( &vAccount, lastInvoiceTime );
			if ( lastInvoiceTimeForUser >= thisInvoiceTime )
				continue;
			pAccount->GetAccountDetail( &vAccount, lastInvoiceTimeForUser, thisInvoiceTime );
//			lastInvoiceTime = lastInvoiceTimeForUser;
		}
		else
		{ 
		// Let's get all their account records. 
			pAccount->GetAccountDetailUntil(&vAccount, thisInvoiceTime);
		}

		// If there are no detail records, we shouldn't be invoicing them
		// BUT if their balance was negative and there are no records, it 
		// means they didn't pay what they were supposed to, so...
		if ( (vAccount.size() < 1) && ( interimBalance > -0.50 ) ) 
		{
			delete	pAccount;
			delete	pUser;
			continue;
		}
		// and sort them...

		// no need to sort them - they are fetched already sorted
//		if ( vAccount.size() > 0 )
//		sort(vAccount.begin(), vAccount.end(), sort_account_detail_time);
//		else
		if ( vAccount.size() <= 0 )
			balance = interimBalance;

		//
		// Let's start by computing forward to the time of the 
		// last invoice, which will give us the balance due at
		// that time. Then, we apply all payments / credits since
		// then to determine the finance charge. 
		//
		paymentsSinceLastInvoice	= 0;
		if ( interimBalance > -1 )
			subjectToFinanceCharge		= 0;
		else
			subjectToFinanceCharge = interimBalance;
		for (ii = vAccount.begin();
			 ii != vAccount.end();
			 ii++)
		{
			// If it's before the last invoice, just accumulate
			if ((*ii)->mTime <= lastInvoiceTime)
			{
				subjectToFinanceCharge	+= (*ii)->mAmount;
				continue;
			}

			//
			// If we're here, we're "past" the last invoice, and 
			// need to start accruing payments.
			//
			// There's a special "break" built in here, since we don't 
			// qualify payments as having been paid AFTER the current
			// invoice time. We can obviously change this.
			//

			// If it's a payment or credit, accumulate it. Remember,
			// due amounts are < 0, so this bumps them up.

//			if ((*ii)->mAmount > 0)
			if ( ((*ii)->mAmount > 0) && ( (*ii)->mTime < thisInvoiceTime ) )

			{
				paymentsSinceLastInvoice	+= (*ii)->mAmount;
				subjectToFinanceCharge		+= (*ii)->mAmount;
			}
		}

		//
		// If there was an unpaid balance, and we don't have a 
		// credit card on file, compute finance charge.
		//
		if (subjectToFinanceCharge < -.50 &&
			!pUser->HasCreditCardOnFile())
		{
			financeCharge	= subjectToFinanceCharge * .015;
			if (financeCharge > -.50)
				financeCharge	= -.50;
		}
		else
			financeCharge	= 0;


		// 
		// ***********
		//	The GOODS
		// ***********
		//
		//	Here, we actually compose the invoice for mailing
		//	to the user.
		//

		//
		// First, pass over the account once to accumulate a current amount
		// due
		//

//		if ( ( interimBalance > 0 ) && ( vAccount.size() > 0 ) )
		if ( vAccount.size() > 0 )
			balance = interimBalance;

		for (ii = vAccount.begin();
			 ii != vAccount.end();
			 ii++)
		{
			// Too late?
			if ((*ii)->mTime >= thisInvoiceTime)
				break;

			// Adjust balance
			balance	+= (*ii)->mAmount;
			userRecCount++;
		}

		//
		// Apply Finance Charge
		//
		if (financeCharge < 0)
		{
			balance	+=	financeCharge;
		}

		//
		// If the balance is less than a dollar, then we don't need to emit
		// no stinking invoice
		//
		if ( balance > -1.00 ) 
		{
				printf("%d of %d No Invoice Sent for %s. Balance %8.2f\n",
						 userCount,
						 vUsers.size(),
						 pUser->GetUserId(),
						 balance);

      		pMemo = new char[strlen(InvoiceMemo) + 128];

         	sprintf(pMemo, "Balance $%8.2f, No payment due!",
           		     balance);
      		pAccountDetail = new clsAccountDetail();
      		pAccountDetail->mTime         = thisInvoiceTime;
      		pAccountDetail->mType      = AccountDetailInvoiced;
      		pAccountDetail->mAmount       = 0;
      		pAccountDetail->mpMemo        = pMemo;
     		pAccountDetail->mTransactionId   = 0;
     		pAccountDetail->mItemId       = 0;
// For production Test only
      		pAccount->AddRawAccountDetail(pAccountDetail);
// For production Test only
      		delete   pAccountDetail; 
		    AddInterimBalance( pAccount, pUser->GetId(), thisInvoiceTime, balance );

			for (ii = vAccount.begin();
				 ii != vAccount.end();
				 ii++)
			{
				delete (*ii);
			}
			vAccount.erase(vAccount.begin(), vAccount.end());
			fprintf(pLogFile,
				"User id : %d records for user:%d balance:%8.2f:interim %8.2f\n", 
				pUser->GetId(),
				userRecCount, 
				balance,
				interimBalance);

			delete	pAccount;
			delete	pUser;
			continue;
		}
		// Little time conversions
		pThisInvoiceTimeTM	= localtime(&thisInvoiceTime);
// petra		strftime(cThisInvoiceTime, sizeof(cThisInvoiceTime),
// petra				"%m/%d/%y %H:%M",
// petra				pThisInvoiceTimeTM);
		clseBayTimeWidget timeWidget (mpMarketPlace, EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
									  EBAY_TIMEWIDGET_SHORT_TIME, thisInvoiceTime);	// petra
		timeWidget.EmitString (cThisInvoiceTime);									// petra
//
		// Make a nice in-memory stream
//		mpStream	= new strstream();
//      changing to mail class
		pMail	= new clsMail();
		pM	= pMail->OpenStream();
		sprintf(subject,
			"%s invoice for  %s",
			mpMarketPlace->GetCurrentPartnerName(), cThisInvoiceDate );

	// prepare the stream
		pM->setf(ios::fixed, ios::floatfield);
		pM->setf(ios::showpoint, 1);
		pM->precision(2);
	    pM->width(8);

		*pM <<	"\n"
				  <<	"* "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" INVOICE for "
				  <<	pUser->GetUserId()
				  <<	" ("
				  <<	mpMarketPlace->GetHomeURL()
				  <<	") *\n"
						"\n"
						"\n"
						"PLEASE DO NOT REPLY TO THIS MESSAGE: SEE INSTRUCTIONS\n"
						"FOR CONTACTING US AT "
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"contact.html"
				  <<	"\n"
						"\n";   

	// emit general announcements

		pAnnouncement = mpAnnouncements->GetAnnouncement(General,Header,
														mpMarketPlace->GetCurrentPartnerId(),
														mpMarketPlace->GetCurrentSiteId());
		if (pAnnouncement)
		{
			pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
			*pM << pSafeText;
			*pM << "\n";
			delete pAnnouncement;
			delete pSafeText;
		}

	// emit invoice announcements
		pAnnouncement = mpAnnouncements->GetAnnouncement(InvoiceAnn,Header,
														mpMarketPlace->GetCurrentPartnerId(),
														mpMarketPlace->GetCurrentSiteId());
		if (pAnnouncement)
		{
			pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
			*pM << pSafeText;
			*pM << "\n";
			delete pAnnouncement;
			delete pSafeText;
		}

		// *** Put any special messages here to be sent out with invoices ***
		*pM <<	"\n\n";

		// If they have past due, let's ask them to pay.
		if (subjectToFinanceCharge < 0 &&
			!pUser->HasCreditCardOnFile())
		{
			*pM <<	" OUR RECORDS INDICATE YOUR ACCOUNT IS PAST DUE\n"
							"IN THE AMOUNT\n"
							"    OF $"
					  <<	-subjectToFinanceCharge
					  <<	". PLEASE SEND YOUR PAYMENT PROMPTLY.\n";
					  		"    PAST DUE ACCOUNTS CANNOT LIST NEW ITEMS.\n";
							"\n"; 
		}



		//
		// Messages in front!
		//
		if (pUser->HasCreditCardOnFile() &&
			balance <= -1.00)
		{
			
			*pM <<
			"\n"	
			"**********************************************************************\n"
			"*** PLEASE DO NOT PAY. YOUR CREDIT CARD WILL BE BILLED. THANK YOU! ***\n"
			"**********************************************************************\n"
			"\n\n";   

		}
		else if (balance > -1.00)
		{
			*pM <<	">>> LOW-USAGE BILLING -- PAYMENT IS NOT REQUIRED. <<<\n\n"
							"    When your account balance is less than one dollar, payment is not\n"
							"    required, and no finance charges will apply. Your account will\n"
							"    remain open until your balance exceeds one dollar, or 90 days have\n"
							"    elapsed, and you will be billed normally at that time.\n\n"
							"    If you would still like to pay this balance, please use a payment\n"
							"    coupon, which you can find on the Sellers menu.\n\n"
							">>> LOW-USAGE BILLING -- PAYMENT IS NOT REQUIRED. <<<\n\n";
							
		}

		// 
		// Now, we do the body of the invoice, including working up the 
		// current balance due.
		//
		*pM <<	"\n"
						"Negative amounts (preceeded by \'-\') are Debits,"
						"positive amounts are Credits\n"
						"\n"
						"Id     "
						" "
						"Date          "
						" "
						"Type                            "
						" "
						"Item    "
						" "
						"Amount  "
						" "
						"Balance "
						"\n"
						"________________________________________"
						"________________________________________"
						"\n";

		//
		// Rezero Balance
		//

//		balance	= 0;
		balance = interimBalance;

		for (ii = vAccount.begin();
			 ii != vAccount.end();
			 ii++)
		{
			// Tooo early?
			if ((*ii)->mTime < lastInvoiceTime)
			{
				balance	+=	(*ii)->mAmount;
				continue;
			}

			// Too late?
			if ((*ii)->mTime >= thisInvoiceTime)
				break;

			// Adjust balance
			balance	+= (*ii)->mAmount;

			// Ok, let's print it.
// petra			theTime		= (*ii)->mTime;
// petra			pTheTime	= localtime(&theTime);
// petra			strftime(cTheTime, sizeof(cTheTime),
// petra						"%m/%d/%y %H:%M",
// petra						pTheTime);
			clseBayTimeWidget timeWidget (mpMarketPlace, EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
										  EBAY_TIMEWIDGET_SHORT_TIME,					// petra
										  (*ii)->mTime);								// petra
			timeWidget.EmitString (cTheTime);											// petra

			*pM <<	"\n"
					  <<	(*ii)->mTransactionId;

			//
			// Transaction ids range from 6 to 7 digit 
			// numbers. If they're 6 digits, pad them
			//
			if ((*ii)->mTransactionId < 1000000)
				*pM << " ";

		 	*pM <<	" "
					    <<	cTheTime
					    <<	" "
					    <<	pAccount->GetAccountDetailDescriptor((*ii)->mType);

			//
			// Emit the descriptor, and pad out to maximum length
			//
			descriptorLen	= 
					strlen(pAccount->GetAccountDetailDescriptor((*ii)->mType));
			if (descriptorLen < descriptorMaxLen)
			{
				for (ic = 0;
					 ic < (descriptorMaxLen - descriptorLen);
					 ic++)
				{
					*pM <<	" ";
				}
			}
					  

			*pM <<	" ";

			if ((*ii)->mOldItemId[0] != '\0')
			{
				*pM <<	(*ii)->mOldItemId;

				// This looks stoopid, but it's not
				switch (strlen((*ii)->mOldItemId))
				{
					case 0:
						*pM <<	"       ";
						break;
					case 6:
						*pM <<	"   ";
						break;
					case 7:
						*pM <<	"  ";
						break;
					case 8:
						*pM <<	" ";
						break;
					default:
						break;
				}
			} 
			else  if ((*ii)->mItemId != 0)
			{
				*pM <<	(*ii)->mItemId;
				if ((*ii)->mItemId < 10)
					*pM <<	"        ";
				else if ((*ii)->mItemId < 100)
					*pM <<	"       ";
				else if ((*ii)->mItemId < 1000)
					*pM <<	"      ";
				else if ((*ii)->mItemId < 10000)
					*pM <<	"     ";
				else if ((*ii)->mItemId < 100000)
					*pM <<	"    ";	
				else if ((*ii)->mItemId < 1000000)
					*pM <<	"   ";
				else if ((*ii)->mItemId < 10000000)
					*pM <<	"  ";
				else if ((*ii)->mItemId < 100000000)
					*pM <<	" ";
			}
			else
			{
				*pM <<	"         ";
			}
	
			*pM <<	" "
					  <<	(*ii)->mAmount
					  <<	" "
					  <<	balance;
	
		}

		//
		// Now, we're done. Before moving on, see if we're adding
		// a finance charge
		//
		if (financeCharge != 0)
		{
			balance	+=	financeCharge;

			*pM <<	"\n"
							"       "
							" "
					  <<	cThisInvoiceTime
					  <<	" "
							"Finance Charge on "
					  <<	subjectToFinanceCharge
					  <<	" "
					  <<	"$"
					  <<	financeCharge
					  <<	", Balance $ "
					  <<	balance;
		}

		
		//
		// Let's finish off now.
		//
		*pM <<	"\n";

		// 
		// Credit card users need not pay
		//
		if (pUser->HasCreditCardOnFile() &&
			balance <= -1.00)
		{
			*pM <<
					"\n\n"
					"**********************************************************************\n"
					"*** PLEASE DO NOT PAY. YOUR CREDIT CARD WILL BE BILLED. THANK YOU! ***\n"
					"**********************************************************************\n";
				
			payCouponNeeded	= false;
		}
		else if (balance > -1.00)
		{
			*pM <<	">>> LOW-USAGE BILLING -- PAYMENT IS NOT REQUIRED. <<<\n";
			payCouponNeeded	= false;
		}


		//
		// And NOW, the Billing Coupon
		//
		if (payCouponNeeded)
		{
			*pM << "\n"
				"For the most convenient payment, create your "
			<<	mpMarketPlace->GetCurrentPartnerName()
			<<	" account with\n"
				"a credit card by following the link titled "
			<<	mpMarketPlace->GetCurrentPartnerName()
			<<	" account on the\n"
				"Sellers menu. You'll never miss a payment.\n"
				"\n"
				"-------- RETURN THIS PORTION WITH YOUR PAYMENT --------\n"
				"\n"
				"Invoice Date:            "
			<<	cThisInvoiceDate
			<<	"\n"
				"Account Name:            "
			<<	pUser->GetUserId()
			<<	"\n"
				"Account Id:              ";

			AWAccountId = pAccount->GetAWAccountId();

			if (AWAccountId == 0)
				*pM <<	"E"
						  <<	pUser->GetId();
			else
				*pM <<	AWAccountId;

			*pM << "\n"
// Stella asked for it - !!!
			<< "E-mail address:         "
			<< pUser->GetEmail()
			<< "\n"
				"Account Balance:         "
			<<	balance
			<<	"\n"
				"\n"
				"\n"
				"TOTAL DUE:               "
				"$";

			if (balance < 0)
				*pM <<	-balance;
			else
				*pM <<	0.00;

			*pM <<	"\n"
				"PAY BEFORE:              "
			<<	cDueDate
			<<	"\n"
				"\n"
				"\n"
				"                             AMOUNT ENCLOSED: ___________\n"
				"\n"
				"\n"
				"If paying by VISA or MasterCard, please complete the following. You may\n"
				"mail this form to the address below. \n"
				"DO NOT E-MAIL YOUR CREDIT CARD NUMBER!\n"
				"\n"
				"\n"
				"___ Check here to keep my credit card on file for automatic payment of\n"
				"future monthly invoices. I will still receive an e-mail advising\n"
				"me of the charges applied.\n"
				"\n"
				"\n"
				"VISA/MC Card number: ____________________________  Exp. Date: ________\n"
				"\n"
				"\n"
				"\n"
				"\n"
				"Cardholder signature:____________________________\n"
				"\n"
			<<	mpMarketPlace->GetBillingPolicyText()
			<<	"\n"
				"\n"
				"-------- RETURN THIS PORTION WITH YOUR PAYMENT --------\n"
				"\n"
				"This is the only notice you will receive. Please remit your payment promptly\n"
				"to avoid cancellation and finance charges.\n"
				"\n"; 
		}
	
		*pM <<
			"* End of "
			<<	mpMarketPlace->GetCurrentPartnerName()
			<<	" Invoice for "
			<<	pUser->GetUserId()
			<< ". Thank You!!!!! *\n"
				"\n";

	// emit general announcements
		pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer,
														mpMarketPlace->GetCurrentPartnerId(),
														mpMarketPlace->GetCurrentSiteId());
		if (pAnnouncement)
		{
			pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
			*pM << pSafeText;
			*pM << "\n";
			delete pAnnouncement;
			delete pSafeText;
		}

	// emit invoice announcements

		pAnnouncement = mpAnnouncements->GetAnnouncement(InvoiceAnn,Footer,
														mpMarketPlace->GetCurrentPartnerId(),
														mpMarketPlace->GetCurrentSiteId());
		if (pAnnouncement)
		{
			pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
			*pM << pSafeText;
			*pM << "\n";
			delete pAnnouncement;
			delete pSafeText;
		}

		*pM	<<	ends;

//		recipients = new char *[2];

//		recipients[0] = lenamail;
//		recipients[1] = NULL;
		mailRc = 1;
//		mailRc = pMail->Send(recipients[0],
		mailRc = pMail->Send(pUser->GetEmail()	/* "sliang@ebay.com" */, 
				(char *)mpMarketPlace->GetConfirmEmail(),
				subject);

		delete	pMail;
		if ( !mailRc )
		{
			printf("** Error! Sendmail returned %d mailing to %s\n",
				   mailRc, pUser->GetEmail());
			for (ii = vAccount.begin();
				 ii != vAccount.end();
				 ii++)
			{
				delete (*ii);
			}
			vAccount.erase(vAccount.begin(), vAccount.end());

			delete	pAccount;
			delete	pUser;
			continue;

		}  

		// 
		// Log it.
		//
		//
		// If there was a finance charge, we log that first, at one second
		// BEFORE the invoice time.
		//
		if (financeCharge != 0)
		{
			pMemo	= new char[strlen(FinanceChargeMemo) + 
								9 +
								9 +
								1];
			sprintf(pMemo, FinanceChargeMemo,
					financeCharge, subjectToFinanceCharge);
			pAccountDetail	= new clsAccountDetail();
			pAccountDetail->mTime			= thisInvoiceTime - 1;
			pAccountDetail->mType			= AccountDetailFinanceCharge;
			pAccountDetail->mAmount			= financeCharge;
			pAccountDetail->mpMemo			= pMemo;
			pAccountDetail->mTransactionId	= 0;
// For production Test only
			pAccount->AddRawAccountDetail(pAccountDetail);
// For production Test only
			delete	pAccountDetail; 
		}

		// 
		// Now, indicate we invoiced!
		//
		pMemo	= new char[strlen(InvoiceMemo) +
							  128];

		if (balance < -1.00 && !pUser->HasCreditCardOnFile())
		{
			sprintf(pMemo, InvoiceMemo,
					  -balance,
					  cDueDate);
		}
		else if (balance < 1.00 && pUser->HasCreditCardOnFile())
		{
			sprintf(pMemo, 
					  "Do NOT pay. $%8.2f will be billed to your credit card!\n",
					  -balance);
		} 
		else
		{
			sprintf(pMemo, "Balance $%8.2f, No payment due!",
					  balance);
		}
		pAccountDetail	= new clsAccountDetail();
		pAccountDetail->mTime			= thisInvoiceTime;
		pAccountDetail->mType		= AccountDetailInvoiced;
		pAccountDetail->mAmount			= 0;
		pAccountDetail->mpMemo			= pMemo;
		pAccountDetail->mTransactionId	= 0;
		pAccountDetail->mItemId			= 0;
//  For production test only
		pAccount->AddRawAccountDetail(pAccountDetail);
//  For production test only
		delete	pAccountDetail; 
		AddInterimBalance( pAccount, pUser->GetId(), thisInvoiceTime, balance );

		// Accumulate
		invoiceCount++;
		totalBilled	+=	balance;
		
		printf("%d of %d User:%s, %8.2f\n",
				 userCount,
				 vUsers.size(),
				 pUser->GetUserId(),
				 balance);
		fprintf(pLogFile, 
				"User id : %d records for user:%d balance:%8.2f:interim %8.2f\n", 
				pUser->GetId(),
				userRecCount, 
				balance,
				interimBalance);


		// Clean up....
		for (ii = vAccount.begin();
			  ii != vAccount.end();
			  ii++)
		{
			delete (*ii);
		}

		vAccount.erase(vAccount.begin(), vAccount.end());

		delete	pAccount;
		delete	pUser;
	}

		
		fclose(pLogFile);

		time(&nowTime);
		pInvAndBalAgingState->SetEndTime(nowTime);
		pInvAndBalAgingState->MakeInstanceComplete();


#ifdef _MSC_VER
		stopwinsockets();
#endif


}
//=============================================================================

bool clsInvoiceApp::ReadData( char *fileName, int &month, 
							 vector<unsigned int> &requestedUsers,
							 int &idStart, int &idEnd )
{
	char			*pFileName = "user-list.txt";
	FILE			*pFile;
	char			buf[1024];
	char            bufMonth[10];
	int				recLen;
	bool done			= false;
	int currentInvoiceMonth = 0;

	pFileName = fileName;
	pFile	= fopen(pFileName, "r");
	if (!pFile)
	{
		fprintf(stderr,
			"Error %s opening %s\n",
			strerror(errno), 
			pFileName);
		return false;
	}

	do
	{
		if (!fgets(buf, sizeof(buf), pFile))
		{
			done = true;
			break;
		}
	// Remove pesky trailing newline
		if ( buf[0] == '/' )
			continue;
		recLen	= strlen(buf);
		if (buf[recLen - 1] == '\n')
			buf[recLen - 1]	= '\0';
		if ( buf[2] == '/' )
		{
			bufMonth[0] = buf[0];
			bufMonth[1] = buf[1];
			bufMonth[2] = '\0';
			month = atoi( bufMonth );
		}
		else
		{
			if ( buf[0] == 's' )
				idStart = atoi( &buf[1] );
			else
			{
				if ( buf[0] == 'e' )
					idEnd = atoi( &buf[1] );
				else
					requestedUsers.push_back( atoi( buf ) );
			}
		}

	} while (!done);
	return true;

}// clsInvoiceApp::ReadData

void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tInvoice [-d mm/yy -s StartId -e EndId]\n");
	printf("OR\n");
	printf("Usage:\n\tInvoice [-s StartId -e EndId]\n\n");
	printf("NOTE:\t1. mm/yy specifies the invoice month\n");
	printf("\t2. Without -d mm/yy, invoice month will be the last month of current month.\n");
	printf("\t3. StarId must be smaller than the EndId.\n");
}
//===============================================================================

static clsInvoiceApp *pTestApp = NULL;

int main( int argc, char* argv[] )
{
	vector<unsigned int> requestedUsers;
	int currentInvoiceMonth = 0;
	int idStart = 0;
	int idEnd = 0;
	int Index=1;
	char InvoiceMonth[10];
	time_t  startTime, endTime;
	

#ifdef _MSC_VER
	g_tlsindex = 0;
#endif

	if (!pTestApp)
	{
		pTestApp = new clsInvoiceApp(0);
	}

	// Sonya: When invoked with one paramether, it should be a file name, and Invoice will read
	// the range and (invoice month, this is opitional)  input info from the file.
	if ( argc == 2 )
	{
		if ( !pTestApp->ReadData( argv[1], currentInvoiceMonth, requestedUsers, idStart, idEnd ) )
		{
			fprintf(stderr,
				"Error reading data\n",
				strerror(errno) );
			return 1;
		}
	}

	//Sonya: when the Invoice is invoked with more than 2 parameters, the range and invoice month is specified 
	// directly by these  parametes 
	if (argc >2) 
	{
		while (--argc)
		{
			switch (argv[Index][1])
			{
				// Get invoice month, just take the first two character in the
				// parameter following -d and tail it with null character
				case 'd':
					InvoiceMonth[0] = argv[++Index][0];
					InvoiceMonth[1] = argv[Index][1];
					InvoiceMonth[2] = '\0';
					Index++;
					argc--;
					currentInvoiceMonth= atoi(InvoiceMonth);
					if ((currentInvoiceMonth) >12||(currentInvoiceMonth<=0))
					{
						InputError();
						exit(0);
					}
					break;

				case 's':
					idStart=atoi(argv[++Index]);
					Index++;
					argc--;
					break;
				case 'e':
					idEnd=atoi(argv[++Index]);
					Index++;
					argc--;
					break;

				default:
					InputError();
					return 0;
			}
		}

	}
	
	if(idStart>=idEnd)
	{
		InputError();
		exit(0);
	}

	pTestApp->InitShell();

#if _DEBUG
	time( &startTime );
#endif

	pTestApp->Run( requestedUsers, currentInvoiceMonth, idStart, idEnd);
#if _DEBUG
	time( &endTime );
#endif

	return 0;
}
