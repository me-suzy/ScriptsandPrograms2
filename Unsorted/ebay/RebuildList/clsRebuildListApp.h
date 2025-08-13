/*	$Id: clsRebuildListApp.h,v 1.3 1999/02/21 02:24:04 josh Exp $	*/
//
//	File:	clsRebuildListApp.h
//
//	Class:	clsRebuildListApp
//
//	Author:	Wen Wen
//
//	Function:
//			Rebuild list function
//
// Modifications:
//				- 07/07/97	Wen - Created
//
#ifndef CLSREBUILDLISTAPP_INCLUDED
#define	CLSREBUILDLISTAPP_INCLUDED

#include <fstream.h>
#include <stdio.h>
#include <string.h>
#include <time.h>

#include "clsApp.h"
#include "RebuildListType.h"
#include "clsCategories.h"
#include "clsItems.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsCategories;
class clsCategory;
class clsItems;
class clsListingItem;
class clsTemplate;


// 
// This Enum tells us the list of item time
//

class clsFileName;

class clsRebuildListApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsRebuildListApp();
		~clsRebuildListApp();

		// Action
		bool Run(char* TestingPath, bool BuildingCompleted=false);

		// Setup
		void Initialize(char* TestingPath, bool BuildCompleted = false);

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

		const char* GetCategoryTemplate(char* pFileReference, TimeCriterion TimeStamp);
		const char* GetItemTemplate(char* pFileReference, TimeCriterion TimeStamp);

		// Get File name object
		clsFileName*	GetFileName();

		bool BuildHTMLPages();
		bool BuildTopPages();
		bool BuildGoingTopPage(CategoryVector* pCategories);
		bool BuildCategoryPages(clsCategory* pCategory, int TimeStamp);
		bool BuildItemPages(clsCategory* pCategory);

		// completed pages
		bool BuildCompletedPages();
		bool BuildCompletedItemPages(clsCategory* pCategory);
		bool BuildTopCompletedPages();

		bool BuildCategoryOverview();

		// Get page creating time
		time_t GetCreatingTime();

		// comparision function for sorting
		static bool SaleEndDescend(clsListingItem* pItem1, clsListingItem* pItem2);
		static bool SaleEndAscend(clsListingItem* pItem1, clsListingItem* pItem2);
		static bool SaleStartDescend(clsListingItem* pItem1, clsListingItem* pItem2);


		// log a piece of message
		void LogMessage(const char* pMessage);

		static void CleanUpVector(CategoryVector* pCategories);
		static void CleanUpVector(ListingItemVector* pItems);

		// this a temp solution for templates
		clsTemplate*	GetTemplate(const char* pTemplateName);

		// set build time
		void SetBuildDate(time_t BuildDate) { mTime = BuildDate; }

		// Get days
		char*	GetPreviousDayString() { return mpPreviousDayString; }
		char*	GetNextDayString() { return mpNextDayString; }
		char*	GetTodayString() { return mpTodayString; }
		int	GetBuildDay() { return mBuildDay;}
		int	GetPrevDay() { return mPrevDay; }
		int	GetNextDay() { return mNextDay; }

		double				mDBTime;

	private:
		void ExtractNewItems(ListingItemVector* pItems, ListingItemVector* pSubItems);
		void ExtractItemsEndingInHours( ListingItemVector* pItems, 
										ListingItemVector* pSubItems,
										int	Hours);

		void RemoveAdultItems(ListingItemVector* pItems);

		void ReorderCategories(CategoryVector* pInCategories, CategoryVector* pOutCategories);

		// check whether the file exist
		bool FileExist(char* pFile);

		clsDatabase*		mpDatabase;
		clsMarketPlaces*	mpMarketPlaces;
		clsMarketPlace*		mpCurrMarketPlace;

		clsCategories*		mpCategories;
		clsItems*			mpItems;

		clsFileName*		mpFileName;

		time_t				mTime;
		char				mItemReferenceName[_MAX_PATH];

		ofstream			mLogStream;

		// set max number templates to 30
		clsTemplate*	mpTemplates[30];
		char*			mpTemplateNames[30];
		int				mTemplateCount;

		bool			mTimeToBuildCompleted;
		char*			mpTemplateFileName;

		int	mBuildDay;
		int	mPrevDay;
		int 	mNextDay;
		char*	mpTodayString;
		char*	mpPreviousDayString;
		char*	mpNextDayString;

};

#endif // CLSREBUILDLISTAPP_INCLUDED
