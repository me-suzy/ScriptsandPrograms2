/*	$Id: clsBalanceAgingApp.h,v 1.3.202.1 1999/07/29 19:44:18 sliang Exp $	*/

//
//	File:	clsBalanceAgingApp.h
//
//	Class:	clsBalanceAgingApp
//
//	Author:	inna markov (inna@ebay.com)
//
//	Function:
//
//	creates end of month financila data and aging existing data
//
// Modifications:
//				- 08/20/98 inna	- Created
//
#ifndef CLSBALANCEAGINGAPP_INCLUDED

#include "clsApp.h"
#include "fstream.h"
#include "vector.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsUsers;

class clsBalanceAgingApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsBalanceAgingApp(unsigned char *pRequestRec);
		~clsBalanceAgingApp();
		
		// Runner
		void Run(vector<unsigned int>& requestedUsers, int month, 
						int idStart, int idEnd);

		bool ReadData( char *fileName, int &month, vector<unsigned int> &requestedUsers,int &idStart, int &idEnd );
	private:
		
		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsUsers			*mpUsers;

		ofstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSBALANCEAGINGAPP_INCLUDED 1
#endif /* CLSBALANCEAGINGAPP_INCLUDED */
