/*	$Id: clsFixRelistApp.h,v 1.3 1999/02/21 02:22:10 josh Exp $	*/
//
//	File:	clsFixRelistApp.h
//
//	Class:	clsFixRelistApp
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//		Fix relisting mistake
//
//
#ifndef CLSFIXRELISTAPP_INCLUDED

#include "clsApp.h"
// #include "fstream.h"
#include <stdio.h>
#include "clsApp.h"
#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsItem;
class clsUsers;
class clsAccount;

class clsFixRelistApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsFixRelistApp();
		~clsFixRelistApp();
		
		// Runner
		void Run();

	private:
		void EmitAccountNotice(clsItem *pItem,
						FILE			*pStatusLog);

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		clsUsers			*mpUsers;

//		ofstream			*mpStream;
		strstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSFIXRELISTAPP_INCLUDED 1
#endif /* CLSFIXRELISTAPP_INCLUDED */
