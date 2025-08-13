/*	$Id: clsItemsToTextApp.cpp,v 1.7.2.1.74.1 1999/08/06 02:26:54 nsacco Exp $	*/
//	File:		clsItemsToTextApp.cpp
//
// Class:	clsItemsToTextApp
//
//	Author:	pete helme (pete@ebay.com)
//
//	Function:
//			builds a text file of all or incremental auction items
//
// Modifications:
//				- 07/22/97	pete - Created
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsItemsToTextApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsUtilities.h"
#include <ctype.h>
#include <map.h>

//
// #defines
//

#define ONE_MONTH	(30*24*60*60)	// 30 days
#define ONE_DAY		(24*60*60)	// 24 hours
#define HALF_DAY	(12*60*60)	// 12 hours
#define ONE_HOUR	(60*60)		// 60 minutes
#define HALF_HOUR	(30*60)		// 30 minutes

//
// code
//
char * stripLF(char *str);

//
// Output a time stamp on standard error
//
void
timestamp(const char *header)
{
    struct tm *tm;
    time_t date;

    time(&date);
    tm = localtime(&date);
	// Lena - merge!
	fprintf(stderr, "%-33s -- ", header);
	fprintf(stderr, "%2d/%02d %2d:%02d:%02d\n", tm->tm_mon+1, tm->tm_mday, tm->tm_hour, tm->tm_min, tm->tm_sec);
//	cerr.form("%-33s -- ", header);
//  cerr.form("%2d/%02d %2d:%02d:%02d\n", tm->tm_mon+1, tm->tm_mday, tm->tm_hour, tm->tm_min, tm->tm_sec);
}

//
//
//
bool clsItemsToTextApp::GetStateFile(bool bActive)
{
	// open the state file for reading
	StateStreamIn.open(bActive ? ACTIVE_STATE_FILE : COMPLETE_STATE_FILE, ios::in);
	
	// if the file exists use it
	if(StateStreamIn != NULL) {
		StateStreamIn >>  lastRunTime;
		StateStreamIn.close();
		return true;
	}
	else
		return false;
}

//
//
//
void clsItemsToTextApp::PutStateFile(bool bActive, time_t tmState)
{
	// open the state file for writing
	StateStreamOut.open(bActive ? ACTIVE_STATE_FILE_NEW : COMPLETE_STATE_FILE_NEW, ios::out);

	// if the file exists use it
	if(StateStreamOut != NULL) {
		StateStreamOut << tmState;
		StateStreamOut.close();
	}
}

//
//	clsItemsToTextApp constructor
//
clsItemsToTextApp::clsItemsToTextApp()
{
	mpDatabase			= (clsDatabase *)0;
	mpMarketPlaces		= (clsMarketPlaces *)0;
	mpCurrMarketPlace	= (clsMarketPlace *)0;
	mpCategories		= (clsCategories *)0;
	mpItems				= (clsItems *) 0;
	mpUsers				= (clsUsers *) 0;
	lastRunTime=0;

	// Get the current system time
	time(&mTimeStart);

	return;
}


//
//	clsItemsToTextApp destructor
//
clsItemsToTextApp::~clsItemsToTextApp()
{
	if (mpDatabase)
		delete mpDatabase;

	if (mpMarketPlaces)
		delete mpMarketPlaces;

	if (mpCurrMarketPlace)
		delete mpCurrMarketPlace;

	if (mpCategories)
		delete mpCategories;

	if (mpItems)
		delete mpItems;

	if (mpUsers)
		delete mpUsers;

	return;
}

//
// run
//
void clsItemsToTextApp::Run(int hours)
{
	// Initialize
	Initialize();

	if (getActive)
		ExtractActiveItems();

	if (getModified)
		ExtractNewlyModifiedItems(hours, false);

	if (getStarted)
		ExtractNewlyModifiedItems(hours, true);

	if (getOutdated)
		ExtractOutdatedItems();

	if (getComplete)
		ExtractCompletedItems(daysComplete);
}

//
//	Initialization
//
void clsItemsToTextApp::Initialize()
{

#ifdef _MSC_VER
	// The next few lines is to fix a problem with Oracle client for MSCVER
	// Wherein the first call to connect to the  DB fails and the second one
	// Succeeds
	__try
	{
		mpDatabase = GetDatabase(); 
	}
	__except(1)
	{
		int err = _exception_code();
		cout << "Caught exception" << endl;
		mpDatabase = GetDatabase();
	}
#else // Unix
	mpDatabase = GetDatabase();
#endif

	mpMarketPlaces = GetMarketPlaces();
	mpCurrMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	mpCategories = mpCurrMarketPlace->GetCategories();
	mpItems = mpCurrMarketPlace->GetItems();
	mpUsers = mpCurrMarketPlace->GetUsers();
}

void clsItemsToTextApp::ExtractActiveItems()
{
	ItemVector::iterator iItem, iBase;
	ItemVector Items;
	int numberOfItems, currentItem = 1;

	// create the new output file
	clsFile *theFile = new clsFile(ACTIVE_ITEMS);

	// get total number of items
	if (verbose)
		printf("getting active items...\n");

	if (verbose) {
	    timestamp("Getting item count");

	    printf("getting total # of active items...");
	    numberOfItems = mpDatabase->GetItemsCountOn(mpCurrMarketPlace->GetId(), time(0));
	    printf("%d\n", numberOfItems);
	}

	if(verbose)
		printf("getting active items from the database. this could take a while...\n");

	timestamp("Getting item vector");

	// First, let's get the items
	mpItems->GetActiveItems(&Items);

	timestamp("Writing out items");

	// Now, we loop through them
	for (iBase = Items.begin(); iBase != Items.end(); iBase += min(50, Items.end() - iBase)) {

	    // Fill in the description for 50 more items...
	    mpDatabase->GetManyItemDescriptions(mpCurrMarketPlace->GetId(),
						iBase,
						iBase + min(50, Items.end() - iBase));

	    for (iItem = iBase; iItem != iBase + min(50, Items.end() - iBase); iItem++) {
		// write everything out
		theFile->Print(*iItem, mpDatabase, mpCurrMarketPlace);

		// free the item objects in the vector
		delete *iItem;

		if (verbose && (currentItem % 250) == 0)
			printf("%d of %d\r", currentItem, numberOfItems);

		currentItem++;
	    }
	}
	if (verbose)
		printf("%d of %d\n", currentItem, numberOfItems);

	timestamp("List generated");

	// get rid of the file object
	delete theFile;

}

//
// given a time frame, returns items modified in that span
//
void clsItemsToTextApp::ExtractNewlyModifiedItems(int hours, bool started)
{
	ItemVector::iterator iItem;
	ItemVector Items;
//	clsItem	*pItem;
	time_t modDateStart, modDateEnd;
	char							cStartTime[32];
	char							cEndTime[32];
	struct tm *pTmStart, *pTmEnd;

	// if we have a state file, then use that as the reference for the last time we ran
	if(GetStateFile(true) && (lastRunTime > 0))
	{
		modDateStart = lastRunTime - 60; // last Run Time - 1 min
		modDateEnd = lastRunTime + (ONE_HOUR*hours);
	}
	else
	{	printf("\n\n***WARNING***: STATE FILE MISSING OR CORRUPT \n\n");
		modDateEnd = mTimeStart;
		modDateStart = mTimeStart -  (ONE_HOUR*hours);
	}

	if (modDateEnd > mTimeStart)
		modDateEnd = mTimeStart;

	pTmStart = localtime(&mTimeStart);
	strftime(cStartTime, sizeof(cStartTime), "%m/%d/%y %H:%M:%S",pTmStart);
	printf("Current Time: %.24s (%d) \n",cStartTime, mTimeStart);


	pTmStart = localtime(&modDateStart);
	strftime(cStartTime, sizeof(cStartTime), "%m/%d/%y %H:%M:%S", pTmStart);
	pTmEnd = localtime(&modDateEnd);
	strftime(cEndTime, sizeof(cEndTime), "%m/%d/%y %H:%M:%S", pTmEnd);

	if (started)
		printf("Fetching items STARTED between %.24s (%d) and %.24s (%d) \n ",
                        cStartTime,modDateStart, cEndTime, modDateEnd);
	else
		printf("Fetching items MODIFIED between %.24s (%d) and %.24s (%d) \n ",
                 cStartTime,modDateStart, cEndTime, modDateEnd);
	
	// create the new output file
	clsFile *theFile = new clsFile(MODIFIED_ITEMS);

	timestamp("Getting item vector");

	mpDatabase->GetItemsModifiedAfterMinimal(mpCurrMarketPlace->GetId(), 
		modDateStart, modDateEnd, &Items, started);

	timestamp("Writing out items");

	// Now, we loop through them
	for (iItem = Items.begin(); iItem != Items.end(); iItem++) {

		// If this is a new item or Bid is 0 (Edit unbid option) get the description too
		if (((*iItem)->GetStartTime() >= modDateStart) ||
			((*iItem)->GetBidCount() == 0)) 
		{

			// Add description to the item
			mpDatabase->GetItemDescription(mpCurrMarketPlace->GetId(),
											   (*iItem)->GetId(),
											   *iItem);
		}
		
		// write everything out
		theFile->Print(*iItem, mpDatabase, mpCurrMarketPlace);

		// free the item objects in the vector
		delete *iItem;
	}

	timestamp("List generated");

	// get rid of the file object
	delete theFile;
	// write out the current time
	PutStateFile(true, modDateEnd);
}

void clsItemsToTextApp::ExtractCompletedItems(int daysComplete)
{
	ItemVector::iterator iItem, iBase;
	ItemVector Items;
	time_t modDate, lastDate;
	struct tm *pTm;

	if (daysComplete < 0) {
		// if we have a state file, then use that as the reference for the last time we ran
		if (GetStateFile(false) &&  (lastRunTime > 0))
			modDate = lastRunTime - 60;  // last Run Time - 1 min
		else
		{	printf("\n\n***WARNING***: STATE FILE MISSING OR CORRUPT \n\n");
			modDate = mTimeStart - ONE_DAY - HALF_DAY;
		}

		lastDate = mTimeStart + 36 * ONE_HOUR;
	}
	else {
		modDate = ((mTimeStart - daysComplete * ONE_DAY) / ONE_DAY) * ONE_DAY;
		lastDate = modDate + ONE_DAY;
	}

	// create the new output file
	clsFile *theFile = new clsFile(COMPLETED_ITEMS);

	pTm = localtime(&mTimeStart);
        printf("Current Time: %.24s (%d) \n",asctime(pTm),mTimeStart);

        pTm = localtime(&modDate);
        printf("Fetching items COMPLETED/COMPLETING between %.24s (%d) and ",
                        asctime(pTm),modDate);

        pTm = localtime(&lastDate);
        printf("%.24s (%d)\n ",asctime(pTm),lastDate);

	timestamp("Getting item vector");

	// First, let's get the items that are outdated as of the time we started
	mpItems->GetItemsEnding(&Items, modDate, lastDate);

	// First, let's get the items that are completed as of the time we started
	if(verbose)
		printf("writing items out to disk...\n");

	timestamp("Writing out items");

	// Now, we loop through them
	for (iBase = Items.begin(); iBase != Items.end(); iBase += min(50, Items.end() - iBase)) {

	    // Fill in the description for 50 more items...
		// Hit the ebay_items_desc table
	    mpDatabase->GetManyItemDescriptions(mpCurrMarketPlace->GetId(),
						iBase,
						iBase + min(50, Items.end() - iBase),false);
		// Hit the ebay_items_desc_ended table
	    mpDatabase->GetManyItemDescriptions(mpCurrMarketPlace->GetId(),
						iBase,
						iBase + min(50, Items.end() - iBase),true);

	    for (iItem = iBase; iItem != iBase + min(50, Items.end() - iBase); iItem++) {

			// write everything out
			theFile->Print(*iItem, mpDatabase, mpCurrMarketPlace);
			// free the item objects in the vector
			delete *iItem;
		}
	}

	timestamp("List generated");

	// get rid of the file object
	delete theFile;

	if(verbose)
	  printf("freeing entire item list...\n");

	// write out the current time
	if (daysComplete < 0)
		PutStateFile(false, mTimeStart);
}

void clsItemsToTextApp::ExtractOutdatedItems()
{
	ItemVector::iterator iItem;
	ItemVector Items;

	// create the new output file
	clsFile *theFile = new clsFile(DELETED_ITEMS);

	timestamp("Getting item vector");

	// First, let's get the items that are outdated as of the time we started
	if(verbose)
		printf("loading outdated items from the database into a vector. this could take a while...\n");

	mpItems->GetItemsEnding(&Items, mTimeStart - ONE_MONTH - ONE_DAY * 2, mTimeStart - ONE_MONTH);

	// First, let's get the items that are completed as of the time we started
	if(verbose)
		printf("writing items out to disk...\n");

	timestamp("Writing out items");

	// Now, we loop through them
	for (iItem = Items.begin(); iItem != Items.end(); iItem++) {
	    // write everything out
	    theFile->PrintDeleted(*iItem);

	    // free the item objects in the vector
	    delete *iItem;
	}

	timestamp("List generated");

	// get rid of the file object
	delete theFile;

	if(verbose)
	  printf("freeing entire item list...\n");
}

clsFile::clsFile(char *name)
{
    msDescName = NULL;
    OutputStream.open(name, ios::out);
}

clsFile::~clsFile()
{
    OutputStream.close();
}

void clsFile::WriteIt(char *tempStr)
{
	OutputStream << tempStr;
	OutputStream << "\n";
}

//
// collapses whitespace into a single space
//

void
stripWS(char *str)
{
    char *tmpStr;
    char *source, *dst;

    tmpStr = new char[strlen(str) + 1];
    source = str;
    dst = tmpStr;
    while (*source != '\0') {
	if (isspace(*source & 0xff)) {
	    *dst++ = ' ';
	    while (*source != '\0' && isspace(*source & 0xff))
		source++;
	}
	else
	    *dst++ = *source++;
    }
    *dst++ = '\0';
    strcpy(str, tmpStr);
    delete [] tmpStr;
}

//
// strips LF characters from entire string... yick
//
char *
stripLF(char *str)
{
  char *tempChar, *pos = NULL, *lastPos, *newPos;
  int length;

  // do we have a valid string
  if(strlen(str) != 0) {
    // find first pos
    pos = strstr(str, "\n");

    if (pos != NULL) {
      // make up a new buffer
      length = strlen(str);
      tempChar = new char[length + 1];

      // set up initial ptrs
      lastPos = str;
      newPos = tempChar;

      // loop to find all LFs
      while(pos != NULL) {

	// copy chars up to the LF
	strncpy(newPos, lastPos, (int) (pos - lastPos));
	// upgrade the position in the new buffer
	newPos += pos - lastPos;

	// increment over the offending LF
	lastPos = pos + 1;

	// get the new position
	pos = strstr(lastPos, "\n");
      }

      // copy the remaining chars
      strncpy(newPos, lastPos, (int) ((str + length) - lastPos));
      newPos += (str + length) - lastPos;
      *newPos = '\0';

      // copy over the old string
      strcpy(str, tempChar);

      // dump our buffer
      delete [] tempChar;
    }
  }

  return str;
}

char *
stringCheck(char *str)
{
    if (str == NULL)
	return("");
    else
	return str;
}

void clsFile::Print(clsItem *pItem, clsDatabase *mpDatabase, clsMarketPlace *mpCurrMarketPlace)
{
	char tempStr[8192];

	// the fields we want in the order we want
	int			itemID;
	time_t		sale_start_time;
	time_t		sale_end_time;
	struct tm	*tm;
	float		start_price;
	float		current_price;
	int			category;
	int			bidcount;
	int			iCurrencyId;
	char		*title;
	char		*description;
	char		*pictureURL;
	char            *tempStrPtr;
	char            *oldTitle, *oldDescription;
	char		*ebayTags = NULL;
	char		*ebayItemType = NULL;
	unsigned int	uiItemType = 0;		// To store GiftIcon and Gallery info

	clsCategory *aCategory;
	clsCategories *categories;
	clsQualifiedCategoryName *aCategoryName;

	static map<int, clsQualifiedCategoryName *, less<int> > categoryMap;

	itemID			= pItem->GetId();
	sale_start_time = pItem->GetStartTime();
	sale_end_time   = pItem->GetEndTime();
	start_price		= pItem->GetStartPrice();
	current_price	= pItem->GetPrice();
	category	= pItem->GetCategory();
	bidcount	= pItem->GetBidCount();
	iCurrencyId = pItem->GetCurrencyId();

	tempStrPtr = stringCheck(pItem->GetTitle());
	oldTitle = new char[strlen(tempStrPtr) + 1];
	strcpy(oldTitle, tempStrPtr);
	stripWS(oldTitle);
	title = clsUtilities::RemoveHTMLTag(oldTitle);

	description = pItem->GetDescription();
	if (description != NULL) {
	    tempStrPtr = stringCheck(pItem->GetDescription());
	    description = new char[strlen(tempStrPtr) + 1];
	    strcpy(description, tempStrPtr);
	    stripWS(description);
	    tempStrPtr = clsUtilities::RemoveHTMLTag(description);
	    delete [] description;
	    description = tempStrPtr;
	}

	// we need to get some more info from the category classes
	// the names & Ids are contained in the categoryName
	// category Id's are stored from the top down (i.e. Antiques:Dolls:Barbie)

	if (categoryMap.find(category) == categoryMap.end()) {
		// TODO - this takes a site now as the third param. How does this affect the whole
		// function?
		aCategory = mpDatabase->GetCategoryById(pItem->GetMarketPlaceId(), category);
		categories = mpCurrMarketPlace->GetCategories();
		aCategoryName = categories->GetQualifiedName(aCategory);

		categoryMap[category] = aCategoryName;

		delete aCategory;
	}
	else
		aCategoryName = categoryMap[category];

	int iGiftIconType = pItem->GetGiftIconType();

	if (iGiftIconType > 0)
	{
		ebayTags = new char[80];
		strcpy(ebayTags, "eBayGift");
		//ebayItemType = new char[2];
		//strcpy(ebayItemType, "1");
		ebayItemType = new char[80];	// TODO: pick more accurate size like 11?
                uiItemType |= (1 & 0x0ff);	// Set bit 0 to 7 of ItemType to hold GiftIconType info
		sprintf(ebayItemType, "%d", uiItemType);
	}

	if (ebayTags == NULL)
	{
		ebayTags = new char[80];
		strcpy(ebayTags, "eBayCtry");
	}
	else
		strcat(ebayTags, " eBayCtry");

	char countryId[4];
	sprintf(countryId, "%d", pItem->GetCountryId());
	strcat(ebayTags, countryId);

	strcat(ebayTags, " eBayAvail");
	if (pItem->IsShippingInternationally())
		strcat(ebayTags, "0");
	else
	{
		if (pItem->GetCountryId() == 0)
			strcat(ebayTags, "999");
		else
			strcat(ebayTags, countryId);
	}

	char regionID[4];
	int iRegionID = pItem->GetRegionID();

	if(iRegionID > 0)
	{
		strcat(ebayTags, " eBayReg");
		sprintf(regionID, "%d", iRegionID);
		strcat(ebayTags, regionID);
	}

	// Gallery Info
	GalleryTypeEnum   GalleryType  = pItem->GetGalleryType();
	GalleryResultCode GalleryState = pItem->GetGalleryState();

	if ( (GalleryType == Gallery || GalleryType == FeaturedGallery) &&
	      GalleryState == kGallerySuccess )
	{
		strcat(ebayTags, " eBayGal");
		if (ebayItemType == NULL)
			ebayItemType = new char[80];	// TODO: pick more accurate size like 11?
		// Set 8th bit of ItemType to indicate that item is a 
		// gallery item and has a thumb nail generated
		uiItemType |= 1<<8;
		sprintf(ebayItemType, "%d", uiItemType);
	}

	tempStrPtr = stringCheck(pItem->GetPictureURL());
	pictureURL = new char[strlen(tempStrPtr) + 1];
	strcpy(pictureURL, tempStrPtr);
	stripLF(pictureURL);

	// print the items to the files
	sprintf((char *)&tempStr, "%d", itemID);
	OutputStream << "ItemID:\t\t";
	WriteIt(tempStr);

	tm = localtime(&sale_start_time);
	sprintf((char *)&tempStr, "%d-%d-%d %02d:%02d:%02d",
				  tm->tm_mon + 1, tm->tm_mday, tm->tm_year + 1900,
				  tm->tm_hour, tm->tm_min, tm->tm_sec);
	OutputStream << "StartTime:\t";
	WriteIt(tempStr);

	tm = localtime(&sale_end_time);
	sprintf((char *)&tempStr, "%d-%d-%d %02d:%02d:%02d",
				  tm->tm_mon + 1, tm->tm_mday, tm->tm_year + 1900,
				  tm->tm_hour, tm->tm_min, tm->tm_sec);
	OutputStream << "EndTime:\t";
	WriteIt(tempStr);

	if ((int)(current_price * 100.0) != 0)
		sprintf((char *)&tempStr, "%d", (int)(current_price * 100.0 + 0.5));
	else
		sprintf((char *)&tempStr, "%d", (int)(start_price * 100.0 + 0.5));
	OutputStream << "CurrentPrice:\t";
	WriteIt(tempStr);

	// write out the 4 category levels to the item
	if (aCategoryName->ids[3]) {
		sprintf((char *)&tempStr, "%d", aCategoryName->ids[3]);
		OutputStream << "Category0:\t";
		WriteIt(tempStr);
		sprintf((char *)&tempStr, "%d", aCategoryName->ids[2]);
		OutputStream << "Category1:\t";
		WriteIt(tempStr);
		sprintf((char *)&tempStr, "%d", aCategoryName->ids[1]);
		OutputStream << "Category2:\t";
		WriteIt(tempStr);
		sprintf((char *)&tempStr, "%d", aCategoryName->ids[0]);
		OutputStream << "Category3:\t";
		WriteIt(tempStr);
	}
	else if (aCategoryName->ids[2]) {
		sprintf((char *)&tempStr, "%d", aCategoryName->ids[2]);
		OutputStream << "Category0:\t";
		WriteIt(tempStr);
		sprintf((char *)&tempStr, "%d", aCategoryName->ids[1]);
		OutputStream << "Category1:\t";
		WriteIt(tempStr);
		sprintf((char *)&tempStr, "%d", aCategoryName->ids[0]);
		OutputStream << "Category2:\t";
		WriteIt(tempStr);
		OutputStream << "Category3:\t0\n";
	}
	else if (aCategoryName->ids[1]) {
		sprintf((char *)&tempStr, "%d", aCategoryName->ids[1]);
		OutputStream << "Category0:\t";
		WriteIt(tempStr);
		sprintf((char *)&tempStr, "%d", aCategoryName->ids[0]);
		OutputStream << "Category1:\t";
		WriteIt(tempStr);
		OutputStream << "Category2:\t0\n";
		OutputStream << "Category3:\t0\n";
	}
	else if (aCategoryName->ids[0]) {
		sprintf((char *)&tempStr, "%d", aCategoryName->ids[0]);
		OutputStream << "Category0:\t";
		WriteIt(tempStr);
		OutputStream << "Category1:\t0\n";
		OutputStream << "Category2:\t0\n";
		OutputStream << "Category3:\t0\n";
	}
	else {
		// This should never, ever, happen
		OutputStream << "Category0:\t0\n";
		OutputStream << "Category1:\t0\n";
		OutputStream << "Category2:\t0\n";
		OutputStream << "Category3:\t0\n";
	}

	sprintf((char *)&tempStr, "%d", bidcount);
	OutputStream << "Bids:\t\t";
	WriteIt(tempStr);

	if(title != NULL)
		sprintf((char *)&tempStr, "%s", title);
	else
		tempStr[0] = '\0';
	OutputStream << "Title:\t\t";
	WriteIt(tempStr);

	if(pictureURL != NULL)
		sprintf((char *)&tempStr, "%s", pictureURL);
	else
		tempStr[0] = '\0';
	OutputStream << "PictureURL:\t";
	WriteIt(tempStr);

	if (description != NULL) {
		OutputStream << "Description:\t";
		WriteIt(description);
	}

	if (ebayTags != NULL) 
	{
		OutputStream << "EbayTags:\t";
		WriteIt(ebayTags);
	}
	if (ebayItemType  != NULL)
	{
		OutputStream << "ItemType:\t";
		WriteIt(ebayItemType);
	}

	if(iCurrencyId > 0) // default is 0.. Not used
	{
		OutputStream << "CurrencyID:\t" << iCurrencyId << "\n";
	}

	if(iRegionID > 0)
        {
		OutputStream << "LocID:\t\t" << iRegionID << "\n";
        }


	OutputStream << "<<EOD>>\n";

	// dump strings
	delete [] oldTitle;
	delete [] title;
	delete [] description;
	delete [] pictureURL;
	if (ebayTags) 
		delete [] ebayTags;
	if (ebayItemType) 
		delete []ebayItemType;
}


void clsFile::PrintDeleted(clsItem *pItem)
{
	char tempStr[20];
	int itemID;

	itemID		= pItem->GetId();

	// print the items to the files
	sprintf((char *)&tempStr, "%d", itemID);
	OutputStream << "VdkVgwKey:\t";
	WriteIt(tempStr);
}
