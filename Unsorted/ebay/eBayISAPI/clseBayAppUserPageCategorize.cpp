/* $Id: clseBayAppUserPageCategorize.cpp,v 1.3.236.1.92.2 1999/08/05 20:42:21 nsacco Exp $ */
//
//	File:		clseBayAppUserPageCategorize.cpp
//
//	Class:		clseBayApp
//
//	Author:		Chad
//
//	Function:
//
//				Let's a user 'categorize' their user page 
//              (set where they want it displayed).
//
//	Modifications:
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
// 

#include "ebihdr.h"
#include "clseBayApp.h"
#include "clsUserPage.h"

static const int sMagicCategoryLimit = 6;

void clseBayApp::CategorizeUserPage(char *pUserId,
                                    char *pPassword,
                                    char *pTitle,
                                    bool  remove,
                                    int   category,
                                    int   page)
{
    clsUserPage thePage;
    vector<clsUserPage *> vPages;
    vector<clsUserPage *>::iterator i;

    SetUp();

    // Title
    *mpStream <<	"<html><head>"
				    "<title>"
		      <<	mpMarketPlace->GetCurrentPartnerName()
		      <<	" Categorize User Page for "
		      <<	pUserId
		      <<	"</title>"
				    "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPassword, mpStream);

    if (!mpUser)
    {
        CleanUp();
        return;
    }

    thePage.SetPage(page);
    thePage.SetUserId(mpUser->GetId());
    thePage.LoadPage(false);

    if (!thePage.GetPageSize())
    {
        *mpStream << "<h2>Page was not found.</H2><p>\n"
                  << mpMarketPlace->GetFooter();

        CleanUp();
        return;
    }

    mpDatabase->GetUserPagesByUser(mpUser->GetId(),
        &vPages);

    if (category > 0)
    {
        thePage.SetTitle(pTitle);
        thePage.SetCategory(category);

        if (remove)
        {
            thePage.DeleteFromCategory();
            *mpStream << "Page removed from category.<p>";
        }
        else
        {
            if (vPages.size() > sMagicCategoryLimit)
            {
                *mpStream << "Maximum categorizations reached -- Not added.<br>"
                             "Remove some other categorizations first.<p>";
            }
            else
            {
                clsCategory *pCategory;
                pCategory = mpMarketPlace->GetCategories()->GetCategory(category);

                if (!pCategory)
                {
                    *mpStream << "No such Category -- Not added.<p>";
                }
                else
                {
                    thePage.SaveCategory();
                    *mpStream << "Page entered into category.<p>";
                }
                delete pCategory;

                // Now, duplicate this page and put it into the vector,
                // so it will show up in the list below.
                clsUserPage *pPage = new clsUserPage;
                pPage->SetPage(thePage.GetPage());
                pPage->SetCategory(thePage.GetCategory());
                pPage->SetTitle(thePage.GetTitle());
                pPage->SetUserId(thePage.GetUserId());
                vPages.push_back(pPage);
            }
        }
    }

    *mpStream << "<P><DIV ALIGN=CENTER><TABLE COLS=3 BORDER=1><TR>"
                 "<TH COLSPAN=3>Your Page is Listed in these Categories</TH></TR>"
                 "<TR><TH>Category</TH><TH COLSPAN=2>Title</TH></TR>\n";

    for (i = vPages.begin(); i != vPages.end(); ++i)
    {
        if ((*i)->GetPage() == page)
        {
            *mpStream << "<TR><TD ALIGN=RIGHT>"
                     << (*i)->GetCategory()
                     << "</TD><TD>"
                     << (*i)->GetTitle()
                     << "</TD>"
                        "<TD><FORM ACTION=\""
                     << mpMarketPlace->GetCGIPath(PageCategorizeUserPage)
                     << "eBayISAPI.dll?CategorizeUserPage\" METHOD=POST>"
                        "<INPUT TYPE=\"hidden\" NAME=\"userid\" VALUE=\""
                     << pUserId
                     << "\"><INPUT TYPE=\"hidden\" NAME=\"password\" VALUE=\""
                     << pPassword
                     << "\"><INPUT TYPE=\"hidden\" NAME=\"category\" VALUE=\""
                     << (*i)->GetCategory()
                     << "\">"
                        "<INPUT TYPE=\"hidden\" NAME=\"remove\" VALUE=\"1\">"
                        "<INPUT TYPE=\"submit\" VALUE=\"Delist\"></FORM></TD></TR>";
        }

        delete *i;
    }
    vPages.clear();

    if (vPages.size() < sMagicCategoryLimit)
    {
        *mpStream << "<TR><TD ALIGN=RIGHT><FORM ACTION=\""
                  << mpMarketPlace->GetCGIPath(PageCategorizeUserPage)
                  << "eBayISAPI.dll\" METHOD=POST>"
                     "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"CategorizeUserPage\">"
                     "<INPUT TYPE=\"hidden\" NAME=\"userid\" VALUE=\""
                  << pUserId
                  << "\"><INPUT TYPE=\"hidden\" NAME=\"password\" VALUE=\""
                  << pPassword
                  << "\"><INPUT TYPE=\"text\" NAME=\"category\" SIZE=4>"
                     "<INPUT TYPE=\"hidden\" NAME=\"page\" VALUE=\""
                  << page
                  << "\"></TD><TD>"
                     "<INPUT TYPE=\"text\" NAME=\"title\" SIZE=80></TD><TD>"
                     "<INPUT TYPE=\"submit\" VALUE=\"Add to Category\"></TD></TR>"
                     "</FORM>";
    }

    *mpStream << "</TABLE></DIV><P>";

    *mpStream << mpMarketPlace->GetFooter();

    CleanUp();
    return;
}