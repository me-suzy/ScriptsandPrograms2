/*	$Id: clsListingFileName.cpp,v 1.8.208.4.40.2 1999/08/09 18:45:05 nsacco Exp $	*/
//
//	File:	clsListingFileName.cpp
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
/*
#include "clsApp.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsCategory.h"
*/
#include "eBayKernel.h"
#include "clsListingFileName.h"
#include "stdio.h"

// TODO - fix?
//const char* pTmpListingPath[] = {"http://cayman.ebay.com/aw/listings", "http://salamander.ebay.com/aw/listings"};
const char* pTmpListingPath[] = {"http://listings%s/aw/listings", "http://listings-new.ebay.com/aw/listings"};

//
// constructor
//
clsListingFileName::clsListingFileName(clsMarketPlace* pMarketPlace, char* TestingPath/*=null*/)
{
	char*	pTemp;

	// get listing path
	mpListingPath = new char[_MAX_PATH];
	mpRelativeListingPath = new char[_MAX_PATH];

	if (TestingPath != NULL)
	{
		strcpy(mpListingPath, TestingPath);
		pTemp = strstr(TestingPath, ".com");
		if (pTemp != NULL)
		{
			strcpy(mpRelativeListingPath, pTemp+4);
		}
		else
		{
			strcpy(mpRelativeListingPath, TestingPath);
		}
	}
	else
	{
		strcpy(mpListingPath, pMarketPlace->GetListingPath());
		strcpy(mpRelativeListingPath, pMarketPlace->GetListingRelativePath());

	}

	if (mpListingPath[strlen(mpListingPath)-1] == '/' || 
		mpListingPath[strlen(mpListingPath)-1] == '\\')
	{
		mpListingPath[strlen(mpListingPath)-1] = '\0';
	}
	if (mpRelativeListingPath[strlen(mpRelativeListingPath)-1] == '/' || 
		mpRelativeListingPath[strlen(mpRelativeListingPath)-1] == '\\')
	{
		mpRelativeListingPath[strlen(mpRelativeListingPath)-1] = '\0';
	}

	mpLinkPath = new char[_MAX_PATH];
	mpAdultLinkPath = new char[_MAX_PATH];
	mpOverviewLinkPath = new char[_MAX_PATH];

	GetLinkFormats();

	// this is temporary
	mPathSwitch = 0;
}

//
// destructor
//
clsListingFileName::~clsListingFileName()
{
	int TimeStamp;

	for (TimeStamp = LISTING; TimeStamp <= GOING*2 + 1; TimeStamp += 1)
	{
		delete [] mpLinkFormats[TimeStamp];
		delete [] mpRelativeLinkFormats[TimeStamp];

	}

	delete [] mpLinkPath;
	delete [] mpAdultLinkPath;
	delete [] mpListingPath;
	delete [] mpRelativeListingPath;
	delete [] mpOverviewLinkPath;

}

//
// return the link for a category
//
const char* clsListingFileName::GetLinkName(clsCategory* pCategory, 
							   TimeCriterion TimeStamp /*=LISTING*/,
							   int PageNumber /*=0*/,
								int Day /*=0*/)
{
	return GetLinkName(pCategory ? pCategory->GetId() : 0, 
		TimeStamp, PageNumber, Day);
}

//
// return the link for a category
//
const char* clsListingFileName::GetLinkName(int CategoryId, 
							   TimeCriterion TimeStamp /*=LISTING*/,
							   int PageNumber /*=1*/,
								int Day /*=0*/)
{
	char	PageName[20];
	char	DirectoryName[_MAX_PATH];
	const char* pServerName;
	const char* pScriptName;
	int		SiteId;
	int		PartnerId;
	const char* pDomain;

	// directory name
	GetDirectoryName(CategoryId, DirectoryName);

	// page name
	GetPageName(PageNumber, PageName, Day, TimeStamp);

	// cobrand it
	pServerName = gApp->GetEnvironment()->GetServerName();
	pScriptName = gApp->GetEnvironment()->GetScriptName();

	if ((pServerName && *pServerName) && (pScriptName && *pScriptName))
	{
		clsUtilities::GetSiteIDAndPartnerID(pServerName, 
									pScriptName, 
									SiteId, 
									PartnerId);
	}

	pDomain = clsUtilities::GetDomainToken(SiteId, PartnerId);

	sprintf(mpLinkPath, mpLinkFormats[TimeStamp], pDomain, DirectoryName, PageName);

/*	// This is temporary
	int	Switch;

	if (TimeStamp == COMPLETED)
	{
		Switch = TimeStamp;
	}
	else
	{
		Switch = (mPathSwitch%2) ? TimeStamp : TimeStamp+5;
	}

	// directory name
	GetDirectoryName(CategoryId, DirectoryName);

	// page name
	GetPageName(PageNumber, PageName, Day, TimeStamp);

	sprintf(mpLinkPath, mpLinkFormats[Switch], DirectoryName, PageName);
	
	mPathSwitch++;*/


	return mpLinkPath;
}

//
// return the link for a category
//
const char* clsListingFileName::GetRelativeLinkName(clsCategory* pCategory, 
							   TimeCriterion TimeStamp /*=LISTING*/,
							   int PageNumber /*=1*/,
								int Day /*=0*/)
{
	return GetRelativeLinkName(pCategory ? pCategory->GetId() : 0, 
		TimeStamp, PageNumber, Day);
}

//
// return the link for a category
//
const char* clsListingFileName::GetRelativeLinkName(int CategoryId, 
							   TimeCriterion TimeStamp /*=LISTING*/,
							   int PageNumber /*=1*/,
								int Day /*=0*/)
{
	char	PageName[20];
	char	DirectoryName[_MAX_PATH];

	// directory name
	GetDirectoryName(CategoryId, DirectoryName);

	// page name
	GetPageName(PageNumber, PageName, Day, TimeStamp);

	sprintf(mpLinkPath, mpRelativeLinkFormats[TimeStamp], DirectoryName, PageName);

	return mpLinkPath;
}

//
// Initialize the link format
//
void clsListingFileName::GetLinkFormats()
{
	int	TimeStamp;
/* This is the regular
	for (TimeStamp = LISTING; TimeStamp <= GOING; TimeStamp++)
	{
		mpLinkFormats[TimeStamp] = new char[_MAX_PATH];
		mpRelativeLinkFormats[TimeStamp] = new char[_MAX_PATH];

	}

	sprintf(mpLinkFormats[0], "%s/%s/%s", mpListingPath, LIST_PATH,      "%s%s");
	sprintf(mpLinkFormats[1], "%s/%s/%s", mpListingPath, NEWTODAY_PATH,  "%s%s");
	sprintf(mpLinkFormats[2], "%s/%s/%s", mpListingPath, ENDTODAY_PATH,  "%s%s");
	sprintf(mpLinkFormats[3], "%s/%s/%s", mpListingPath, COMPLETED_PATH, "%s%s");
	sprintf(mpLinkFormats[4], "%s/%s/%s", mpListingPath, GOING_PATH, "%s%s");


	sprintf(mpRelativeLinkFormats[0], "%s/%s/%s", mpRelativeListingPath, LIST_PATH,      "%s%s");
	sprintf(mpRelativeLinkFormats[1], "%s/%s/%s", mpRelativeListingPath, NEWTODAY_PATH,  "%s%s");
	sprintf(mpRelativeLinkFormats[2], "%s/%s/%s", mpRelativeListingPath, ENDTODAY_PATH,  "%s%s");
	sprintf(mpRelativeLinkFormats[3], "%s/%s/%s", mpRelativeListingPath, COMPLETED_PATH, "%s%s");
	sprintf(mpRelativeLinkFormats[4], "%s/%s/%s", mpRelativeListingPath, GOING_PATH, "%s%s");
*/

	// This si for temporary
	for (TimeStamp = LISTING; TimeStamp <= GOING*2 + 1; TimeStamp++)
        {
                mpLinkFormats[TimeStamp] = new char[_MAX_PATH];
                mpRelativeLinkFormats[TimeStamp] = new char[_MAX_PATH];

        }

        sprintf(mpLinkFormats[0], "%s/%s/%s", pTmpListingPath[0], LIST_PATH,      "%s%s");
        sprintf(mpLinkFormats[1], "%s/%s/%s", pTmpListingPath[0], NEWTODAY_PATH,  "%s%s");
        sprintf(mpLinkFormats[2], "%s/%s/%s", pTmpListingPath[0], ENDTODAY_PATH,  "%s%s");
//        sprintf(mpLinkFormats[3], "%s/%s/%s", pTmpListingPath[0], COMPLETED_PATH, "%s%s");
        sprintf(mpLinkFormats[3], "%s/%s/%s", "http://cayman.ebay.com/aw/listings", COMPLETED_PATH, "%s%s");
        sprintf(mpLinkFormats[4], "%s/%s/%s", pTmpListingPath[0], GOING_PATH, "%s%s");

        sprintf(mpLinkFormats[5], "%s/%s/%s", pTmpListingPath[1], LIST_PATH,      "%s%s");
        sprintf(mpLinkFormats[6], "%s/%s/%s", pTmpListingPath[1], NEWTODAY_PATH,  "%s%s");
        sprintf(mpLinkFormats[7], "%s/%s/%s", pTmpListingPath[1], ENDTODAY_PATH,  "%s%s");
//        sprintf(mpLinkFormats[8], "%s/%s/%s", pTmpListingPath[1], COMPLETED_PATH, "%s%s");
        sprintf(mpLinkFormats[8], "%s/%s/%s", "http://cayman.ebay.com/aw/listings", COMPLETED_PATH, "%s%s");
        sprintf(mpLinkFormats[9], "%s/%s/%s", pTmpListingPath[1], GOING_PATH, "%s%s");

        sprintf(mpRelativeLinkFormats[0], "%s/%s/%s", mpRelativeListingPath, LIST_PATH,      "%s%s");
        sprintf(mpRelativeLinkFormats[1], "%s/%s/%s", mpRelativeListingPath, NEWTODAY_PATH,  "%s%s");
        sprintf(mpRelativeLinkFormats[2], "%s/%s/%s", mpRelativeListingPath, ENDTODAY_PATH,  "%s%s");
        sprintf(mpRelativeLinkFormats[3], "%s/%s/%s", mpRelativeListingPath, COMPLETED_PATH, "%s%s");
        sprintf(mpRelativeLinkFormats[4], "%s/%s/%s", mpRelativeListingPath, GOING_PATH, "%s%s");

}

//
// return the link for the adult auction page
//
const char* clsListingFileName::GetAdultLinkName(clsCategory* pCategory,
									TimeCriterion TimeStamp /*=LISTING*/)
{
	return GetAdultLinkName(pCategory ? pCategory->GetId() : 0, TimeStamp);
}

//
// return the link for the adult auction page
//
const char* clsListingFileName::GetAdultLinkName(int CategoryId,
									TimeCriterion TimeStamp /*=LISTING*/)
{
	char	DirectoryName[_MAX_PATH];

	// directory name
	GetDirectoryName(CategoryId, DirectoryName);

	sprintf(mpAdultLinkPath, mpLinkFormats[TimeStamp], DirectoryName, ADULT_PAGE);

	return mpAdultLinkPath;
}

const char* clsListingFileName::GetOverviewLinkName()
{
	sprintf(mpOverviewLinkPath, "%s/%s", mpListingPath, "overview.html");

	return mpOverviewLinkPath;
}

//
// GetDiretoryName
//
void clsListingFileName::GetDirectoryName(int CategoryId, char* pDirName)
{
	// directory name
	if (CategoryId == 0)
	{
		strcpy(pDirName, "");
	}
	else
	{
		sprintf(pDirName, "%s%d/", CATEGORY_PATH, CategoryId);
	}
}

//
// GetDiretoryName
//
void clsListingFileName::GetPageName(int PageNumber, char* pPageName, int Day, TimeCriterion TimeStamp)
{
	if (TimeStamp != COMPLETED)
	{
		if (PageNumber != 1)
		{
			sprintf(pPageName, "%s%d.html", PAGE_PATH, PageNumber);
		}
		else
		{
			strcpy(pPageName, FIRST_CAT_PAGE);
		}
	}
	else
	{
		if (Day == 0)
		{
			strcpy(pPageName, FIRST_CAT_PAGE);
		}
		else
		{
			sprintf(pPageName, "day%d%s%d.html", Day, PAGE_PATH, PageNumber);
		}
	}
}

