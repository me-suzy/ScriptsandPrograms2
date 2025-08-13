/*	$Id: clsInvoiceApp.h,v 1.4.202.1 1999/07/29 19:41:49 sliang Exp $	*/

//
//	File:	clsInvoiceApp.h
//
//	Class:	clsInvoiceApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Generates the input for the "daily status" 
//		mailer app. 
//
//		*** NOTE ***
//		If we ever do e-Boxes, this would be the place
//		to invoke clsMail and "do the right thing" for
//		each report
//		*** NOTE ***
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#ifndef CLSINVOICEAPP_INC

#include "clsApp.h"
#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif

#include "vector.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsUsers;
class clsAnnouncements;
class clsAccount;


class clsInvoiceApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsInvoiceApp(unsigned char *pRequestRec);
		~clsInvoiceApp();
		
		// Runner
//		void Run();
		void CurrentInvoiceTime( tm &time, int month = 0 );
		void PreviousInvoiceTime( tm &currentInvoiceTime );
		int LastDayOfMonth( int month, int year );
		bool LeapYear( int year );
		void InitEnvironment();
		void DueDate ( tm &dueDate );

		void Run( vector<unsigned int>& requestedUsers, int month, 
			int idStart, int idEnd);
		void AddInterimBalance( clsAccount *pAccount, 
							int id, time_t theTime, float amount );
		void AddRawAccountDetail( char *pMemo, int detailType,
								double amount, time_t thisInvoiceTime,
								clsAccount *pAccount );
		bool ReadData( char *fileName, int &month, 
							 vector<unsigned int> &requestedUsers,
							 int &idStart, int &idEnd );

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		clsUsers			*mpUsers;
		clsAnnouncements	*mpAnnouncements;

		strstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSINVOICEAPP_INC 1
#endif /* CLSINVOICEAPP_INC */
