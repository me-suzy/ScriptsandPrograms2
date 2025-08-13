/*	$Id: clsBuildAdvancedSearchPageApp.h,v 1.2 1999/02/21 02:21:19 josh Exp $	*/
//
//	File:	clsBuildAdvancedSearchPageApp.h
//
//	Class:	clsBuildAdvancedSearchPageApp
//
//	Author:	Wen Wen
//
//	Function:
//			Rebuild list function
//
// Modifications:
//				- 07/07/97	Wen - Created
//
#ifndef CLSBuildAdvancedSearchPageAPP_INCLUDED
#define	CLSBuildAdvancedSearchPageAPP_INCLUDED

#include <fstream.h>
#include <stdio.h>
#include <string.h>
#include <time.h>

#include "clsApp.h"
#include "BuildAdvancedSearchPageType.h"
#include "clsCategories.h"
#include "clsItems.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsCategories;
class clsCategory;
class clsItems;
class clsItem;


// 
// This Enum tells us the list of item time
//

class clsFileName;

class clsBuildAdvancedSearchPageApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsBuildAdvancedSearchPageApp();
		~clsBuildAdvancedSearchPageApp();

		// Action
		bool Run(char* TestingPath);

		// Setup
		void Initialize(char* TestingPath);

		// Get Categories
		clsCategories*	GetCategories();

		// Get Marketplace
		clsMarketPlace*	GetMarketPlace();

		// get items
		clsItems*		GetItems();

		// default reference file name
		const char*	GetDefTopCategoryTemplate(TimeCriterion TimeStamp);
		const char*	GetDefaultCategoryTemplate(TimeCriterion TimeStamp);
		const char*	GetDefaultItemTemplate(TimeCriterion TimeStamp);
		const char*	GetDefaultShallowItemTemplate(TimeCriterion TimeStamp);
		const char*	GetDefaultShallowCategoryTemplate(TimeCriterion TimeStamp);

		// Get File name object
		clsFileName*	GetFileName();

		bool BuildHTMLPages();
		void EmitHTMLLeafSelectionSearchList(ostream *pStream,
											  char *pListName,
											  CategoryId selectedValue,
											  char *pUnSelectedValue,
											  char *pUnSelectedLabel,
											  CategoryVector *vCategories);
		
		void EmitCurrentTimeToPage(ostream *pStream);
		void EmitDateSearchToPage(ostream *pStream);
		void EmitPriceSearchToPage(ostream *pStream);
		void EmitStandardSearchToPage(ostream *pStream);

		// Get page creating time
		time_t GetCreatingTime();

		// comparision function for sorting
		static bool EndDescend(clsItem* pItem1, clsItem* pItem2);

		// log a piece of message
		void LogMessage(const char* pMessage);

		double  GetDBTime();

		static void CleanUpVector(CategoryVector* pCategories);
		static void CleanUpVector(ItemVector* pItems);

		bool BuildSearchPage();

	private:
		void IncrementDBTime(double Delta);

		clsDatabase*		mpDatabase;
		clsMarketPlaces*	mpMarketPlaces;
		clsMarketPlace*		mpCurrMarketPlace;

		clsCategories*		mpCategories;
		clsItems*			mpItems;

		clsFileName*		mpFileName;

		time_t				mTime;
		char				mItemReferenceName[_MAX_PATH];

		ofstream			mLogStream;

		double				mDBTime;

		const char	*mpDefHeader;
		const char	*mpDefFooter;
		const char	*mpMarketPlaceName;

};

#endif // CLSBuildAdvancedSearchPageAPP_INCLUDED
