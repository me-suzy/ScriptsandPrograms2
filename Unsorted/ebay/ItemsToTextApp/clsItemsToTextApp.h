/*	$Id: clsItemsToTextApp.h,v 1.3 1999/02/21 02:23:00 josh Exp $	*/
//
//	File:	clsItemsToTextApp.h
//
//	Class:	clsItemsToTextApp
//
//	Author:	pete helme (pete@ebay.com)
//
//	Function:
//			builds a text file of all or incremental auction items
//
// Modifications:
//				- 07/22/97	pete - Created
//
#ifndef CLSITEMSTOTEXTAPP_INCLUDED
#define	CLSITEMSTOTEXTAPP_INCLUDED

#include <fstream.h>
#include <stdio.h>
#include <string.h>
#include <time.h>

#include "clsApp.h"
#include "clsCategories.h"
#include "clsItems.h"
#include "clsUsers.h"

#define	ALL_ITEMS "AllSearchItems.txt"
#define	ACTIVE_ITEMS "ActiveSearchItems.txt"
#define	MODIFIED_ITEMS "ModifiedSearchItems.txt"
#define	COMPLETED_ITEMS "CompletedSearchItems.txt"
#define	DELETED_ITEMS "DeletedSearchItems.txt"

#define ACTIVE_STATE_FILE	"ActiveState.txt"
#define ACTIVE_STATE_FILE_NEW	"ActiveState.new"

#define COMPLETE_STATE_FILE	"CompleteState.txt"
#define COMPLETE_STATE_FILE_NEW	"CompleteState.new"


// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsCategories;
class clsCategory;
class clsItems;
class clsItem;
class clsUsers;


//
// This Enum tells us the list of item time
//

class clsFile
{
	public:

		clsFile(char *name);
		~clsFile();

		void WriteIt(char *tempStr);
		void Print(clsItem *pItem, clsDatabase *mpDatabase, clsMarketPlace *mpCurrMarketPlace);
		void PrintDeleted(clsItem *pItem);

	private:
		char *msDescName;

		ofstream OutputStream;
};

class clsItemsToTextApp : public clsApp
{
	public:

		// Constructor, Destructor
		clsItemsToTextApp();
		~clsItemsToTextApp();

		// Action
		void Run(int hours);

		// Setup
		void Initialize();

		// which routine to call
		bool				getActive;
		bool				getModified;
		bool				getOutdated;
		bool				getComplete;
		bool				getStarted;
		bool				verbose;
		int					daysComplete;

	private:
		void ExtractActiveItems();
		void ExtractNewlyModifiedItems(int hours, bool started = false);
		void ExtractOutdatedItems();
		void ExtractCompletedItems(int daysComplete);
		bool GetStateFile(bool bActive);
		void PutStateFile(bool bActive, time_t tmState);

		clsDatabase*		mpDatabase;
		clsMarketPlaces*	mpMarketPlaces;
		clsMarketPlace*		mpCurrMarketPlace;
		clsCategories*		mpCategories;
		clsItems*			mpItems;
		clsUsers*			mpUsers;

		time_t				mTimeStart;
		time_t				lastRunTime;
		
		ifstream			StateStreamIn;
		ofstream			StateStreamOut;


};

#endif // CLSITEMSTOTEXTAPP_INCLUDED
