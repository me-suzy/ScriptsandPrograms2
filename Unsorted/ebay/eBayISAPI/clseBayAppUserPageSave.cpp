/* $Id: clseBayAppUserPageSave.cpp,v 1.2.348.1.88.2 1999/08/05 20:42:22 nsacco Exp $ */
//
//	File: 		clseBayAppUserPageSave.cpp
//
//	Class:		clseBayApp
//
//	Author:		Chad
//
//	Function:
//
//			    Functions for saving and previewing 
//              of user pages.
//
//	Modifications:
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()   
// 
#include "ebihdr.h"
#include "userpage.h"

static int MAX_ABOUTME_SIZE_K = 96;

// Saves a page into the database given the HTML (pText) for
// the page.
void clseBayApp::SaveUserPage(CEBayISAPIExtension *pThis,
							  char *pUserId,
                              char *pPassword,
                              char *pText,
                              int page)
{
    clsTextToWidgets theWidgetParser(sUserOkWidgets, sNumOkWidgets, "<eBay");
    clsUserPage thePage;
    int			length;
	int			pageSize;
    const char *pData;
    char	   *pDataStore;
	
    SetUp();

    *mpStream <<    "<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Save User Page for "
              <<    pUserId
              <<    "</title>"
                    "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId,
        pPassword, mpStream);

    if (!mpUser)
    {
        CleanUp();
        return;
    }

	pageSize = strlen(pText);
	if (pageSize > MAX_ABOUTME_SIZE_K * 1024)
	{
		*mpStream << "<h2>File Size Limit</h2> \n"
			         "There is a maximum page size limit of "
				  << MAX_ABOUTME_SIZE_K
				  << " kilobytes, which you have exceeded by "
				  << (pageSize/1024) - MAX_ABOUTME_SIZE_K
				  << " kilobytes. \n\nPlese reduce your page size, "
				     "then try saving again.";

		CleanUp();
		return;
	}

    thePage.SetPage(page);
    thePage.SetUserId(mpUser->GetId());
    thePage.SetPageTextSize(pageSize);

    theWidgetParser.SetText(pText);
    pData = theWidgetParser.GetDataDictionary(&length);

    pDataStore = new char [length];
    memcpy(pDataStore, pData, length);

    thePage.SetPageSize(length);
    thePage.SetDataDictionary(pDataStore);
    thePage.SavePage();

    mpUser->SetAboutMePage();

	UserPageShowDonePage(pUserId, pPassword);

    *mpStream << mpMarketPlace->GetFooter();

    CleanUp();
    return;

}

// Save the page into the database given the form entries
// made with template editing.
void clseBayApp::SaveUserPage(CEBayISAPIExtension *pThis,
							  char *pUserId,
                              char *pPassword,
                              TemplateElements *elements,
                              int   page)
{
	
    clsTextToWidgets theWidgetParser(sUserOkWidgets, sNumOkWidgets, "<eBay");
    clsUserPage thePage;
    int			length;
    const char *pData;
    char	   *pDataStore;
	char       *pStr;

	ostrstream  oStream;

    SetUp();

    *mpStream <<    "<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Save User Page for "
              <<    pUserId
              <<    "</title>"
                    "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId,
        pPassword, mpStream);

    if (!mpUser)
    {
        CleanUp();
        return;
    }

	UserPageConvertTemplateToHTML(&oStream, elements, false);

	// Caps off the stream.
	oStream << ends;

    thePage.SetPage(page);
    thePage.SetUserId(mpUser->GetId());
	pStr = oStream.str();
    thePage.SetPageTextSize(strlen(pStr));

    theWidgetParser.SetText(oStream.str());
    pData = theWidgetParser.GetDataDictionary(&length);

    pDataStore = new char [length];
    memcpy(pDataStore, pData, length);

    thePage.SetPageSize(length);
    thePage.SetDataDictionary(pDataStore);
    thePage.SavePage();

    mpUser->SetAboutMePage();

	UserPageShowDonePage(pUserId, pPassword);

    *mpStream << mpMarketPlace->GetFooter();

	delete [] pStr;

    CleanUp();
    return;

}

