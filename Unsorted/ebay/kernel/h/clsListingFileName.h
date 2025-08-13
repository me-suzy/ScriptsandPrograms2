/*	$Id: clsListingFileName.h,v 1.4 1999/02/21 02:46:39 josh Exp $	*/
//
//	File:	clsListingFileName.h
//
//	Class:	clsListingFileName
//
//	Author:	Wen Wen
//
//	Function:
//			Create a file based on the file format, category id, 
//			and current HTML page
//
// Modifications:
//				- 07/07/97	Wen - Created
//
#ifndef CLSLISTINGFILENAME_INCLUDED
#define CLSLISTINGFILENAME_INCLUDED

#define FIRST_CAT_PAGE	"index.html"
#define ADULT_PAGE		"adult.html"

#define LIST_PATH		"list"
#define NEWTODAY_PATH	"newtoday"
#define ENDTODAY_PATH	"endtoday"
#define COMPLETED_PATH	"completed"
#define GOING_PATH	"going"

#define CATEGORY_PATH	"category"
#define PAGE_PATH		"page"

class clsCategory;
class clsMarketPlace;

class clsListingFileName
{
public:
	// Constructor
	clsListingFileName(clsMarketPlace* pMarketPlace, char* TestingPath=NULL);

	// Destrutor
	~clsListingFileName();

	// Get the link of the output file based on the current category ID and
	// printing page
	const char* GetLinkName(clsCategory* pCategory, TimeCriterion TimeStamp = LISTING, int PageNumber = 1, int Day = 0);
	const char* GetLinkName(int pCategoryId, TimeCriterion TimeStamp = LISTING, int PageNumber = 1, int Day = 0);
	const char* GetRelativeLinkName(clsCategory* pCategory, TimeCriterion TimeStamp = LISTING, int PageNumber = 1, int Day = 0);
	const char* GetRelativeLinkName(int pCategoryId, TimeCriterion TimeStamp = LISTING, int PageNumber = 1, int Day = 0);


	const char* GetAdultLinkName(clsCategory* pCategory, TimeCriterion TimeStamp = LISTING);
	const char* GetAdultLinkName(int CategoryId, TimeCriterion TimeStamp = LISTING);

	const char* GetOverviewLinkName();

protected:
	// Get the link formats
	void GetLinkFormats();

	// Get directory name
	void GetDirectoryName(int CategoryId, char* pDirName);

	// Get page name
	void GetPageName(int PageNumber, char* pPageName, int Day, TimeCriterion TimeStamp);

	char*	mpOverviewLinkPath;
	char*	mpListingPath;
	char*	mpRelativeListingPath;

	char*	mpLinkFormats[10];
	char*	mpRelativeLinkFormats[10];
	char*	mpLinkPath;
	char*	mpAdultLinkPath;

	// this is temporary
	int	mPathSwitch;
};

#endif // CLSLISTINGFILENAME_INCLUDED
