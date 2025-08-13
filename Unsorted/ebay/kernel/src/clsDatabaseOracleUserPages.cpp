/* $Id: clsDatabaseOracleUserPages.cpp,v 1.2 1998/10/16 01:06:00 josh Exp $ */
//
//	File:	clsDatabaseOracleUserPages.cpp
//
//	Class:	clsDatabaseOracle
//
//	Author:	Chad Musick (chad@ebay.com)
//
//	Function: Handles all database access for the user pages functionality.
//
//

#include "eBayKernel.h"
#include "clsUserPage.h"
#include "clsNeighbor.h"

static const char *SQL_CreateUserPage =
"insert into ebay_user_pages"
"	(user_id, page_number,"
"	last_updated,"
"	page_size, page_text_size,"
"	last_viewed, num_views) "
"values"
"	(:user_id, :page_number,"
"	sysdate,"
"	:page_size, :page_text_size,"
"	sysdate, 0)";

static const char *SQL_CreateUserPageText =
"insert into ebay_user_pages_text"
"	(user_id, page_number, data_dict) "
"values"
"	(:user_id, :page_number, :data_dict)";

void clsDatabaseOracle::CreateUserPage(clsUserPage *pPage)
{
	long user_id;
	int page_number;
	long page_size;
	long page_text_size;
	char *pData;

	// First, add the page portion.
	OpenAndParse(&mpCDAOneShot, SQL_CreateUserPage);

	// Fill our variables.
	user_id = pPage->GetUserId();
	page_number = pPage->GetPage();
	page_size = pPage->GetPageSize();
	page_text_size = pPage->GetPageTextSize();
	pData = pPage->GetDataDictionary();

	// Now bind our variables.
	Bind(":user_id", &user_id);
	Bind(":page_number", &page_number);
	Bind(":page_size", &page_size);
	Bind(":page_text_size", &page_text_size);

	// Do it...
	Execute();
	Commit();

	// Done with that statement.
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// If we have no data dictionary, we're done.
	if (!pData)
		return;

	// Now, if we have a data dictionary, let's save it as well.
	OpenAndParse(&mpCDAOneShot, SQL_CreateUserPageText);

	// Our variables are already filled, just bind them.
	Bind(":user_id", &user_id);
	Bind(":page_number", &page_number);
	BindLongRaw(":data_dict", (unsigned char *) pData, page_size);

	// And store it.
	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

static const char *SQL_UpdateUserPage =
"update ebay_user_pages"
"	set"
"	last_updated = sysdate,"
"	page_size = :page_size,"
"	page_text_size = :page_text_size "
"where user_id = :user_id and "
"page_number = :page_number";

static const char *SQL_UpdateUserPageText =
"update ebay_user_pages_text"
"	set"
"	data_dict = :data_dict "
"where user_id = :user_id and "
"page_number = :page_number";

void clsDatabaseOracle::UpdateUserPage(clsUserPage *pPage)
{
	long user_id;
	int page_number;
	long page_size;
	long page_text_size;
	char *pData;

	// First, add the page portion.
	OpenAndParse(&mpCDAOneShot, SQL_UpdateUserPage);

	// Fill our variables.
	user_id = pPage->GetUserId();
	page_number = pPage->GetPage();
	page_size = pPage->GetPageSize();
	page_text_size = pPage->GetPageTextSize();
	pData = pPage->GetDataDictionary();

	// Now bind our variables.
	Bind(":user_id", &user_id);
	Bind(":page_number", &page_number);
	Bind(":page_size", &page_size);
	Bind(":page_text_size", &page_text_size);

	// Do it...
	Execute();

    // If we couldn't update it, we should create it instead.
    if (CheckForNoRowsUpdated())
    {
		ocan((struct cda_def *)mpCDACurrent);
        Close(&mpCDAOneShot);
        SetStatement(NULL);

        CreateUserPage(pPage);
        return;
    }

	Commit();

	// Done with that statement.
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// If we have no data dictionary, we're done.
	if (!pData)
		return;

	// Now, if we have a data dictionary, let's save it as well.
	OpenAndParse(&mpCDAOneShot, SQL_UpdateUserPageText);

	// Our variables are already filled, just bind them.
	Bind(":user_id", &user_id);
	Bind(":page_number", &page_number);
	BindLongRaw(":data_dict", (unsigned char *) pData, page_size);

	// And store it.
	Execute();
	Commit();

	// Done with this statement. 
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

static const char *SQL_GetUserPage =
"select"
"	TO_CHAR(last_updated, 'YYYY-MM-DD HH24:MI:SS'),"
"	page_size,"
"	page_text_size,"
"	TO_CHAR(last_viewed, 'YYYY-MM-DD HH24:MI:SS'),"
"	num_views "
"from ebay_user_pages "
"where user_id = :user_id and "
"page_number = :page_number";

static const char *SQL_GetUserPageText =
"select "
"	data_dict "
"from ebay_user_pages_text "
"where user_id = :user_id and "
"page_number = :page_number";

void clsDatabaseOracle::GetUserPage(clsUserPage *pPage,
                                    bool withDictionary)
{
	// A place to store these things.
	char last_updated[32];
	char last_viewed[32];
	long page_size;
	long page_text_size;
	long num_views;
    long userId;
	int pageNumber;
	time_t theTime;

	// Now, open our cursor.
	OpenAndParse(&mpCDAGetUserPage, SQL_GetUserPage);

    userId = pPage->GetUserId();
    pageNumber = pPage->GetPage();

	// Bind our match variables.
	Bind(":user_id", &userId);
	Bind(":page_number", &pageNumber);

	// Define our output variables.
	Define(1, last_updated, sizeof (last_updated));
	Define(2, &page_size);
	Define(3, &page_text_size);
	Define(4, last_viewed, sizeof (last_viewed));
	Define(5, &num_views);

    Execute();
    Fetch();

	// If we didn't find anything, let's just return that fact.
	if (CheckForNoRowsFound())
    {
		ocan((struct cda_def *)mpCDACurrent);
        Close(&mpCDAGetUserPage); // WAS: mpCDAOneShot); ????????????????????
        SetStatement(NULL);
		return;
    }

	// Clean up.
	Close(&mpCDAGetUserPage);
	SetStatement(NULL);


	// Call all of our set functions.
	pPage->SetUserId(userId);
	pPage->SetPage(pageNumber);
	pPage->SetNumViews(num_views);
	pPage->SetPageSize(page_size);
	pPage->SetPageTextSize(page_text_size);

	// Now translate the dates to set them.
	ORACLE_DATEToTime(last_updated, &theTime);
	pPage->SetLastUpdate(theTime);

	ORACLE_DATEToTime(last_viewed, &theTime);
	pPage->SetLastView(theTime);

	// We're done unless we want the dictionary.
	if (!withDictionary)
		return;

	// Now we fetch the dictionary from the database.
	// We know how big it is already.
	// (Note: While we cannot rely on this number 100%,
	// the failure method if this has changed in the small
	// interval between getting the size and getting the data
	// is that the data will be displayed truncated once.
	// If the size of the data has been reduced, there is no
	// difficulty.)

	if (mUserPageBufferSize < page_size + 1)
	{
		mUserPageBufferSize = page_size + 1;
		delete [] mpUserPageBuffer;
		mpUserPageBuffer = new unsigned char [mUserPageBufferSize];

		// And a check for this -- if we've increased the size of the
		// buffer, we need to force closed the cursor, because it needs
		// to be reparsed, at least according to the Oracle manuals.
		if (mpCDAGetUserPageText)
		{
			// Just in case this matters...
			OpenAndParse(&mpCDAGetUserPageText, SQL_GetUserPageText);
			Close(&mpCDAGetUserPageText, true);
		}
	}

	OpenAndParse(&mpCDAGetUserPageText, SQL_GetUserPageText);

	// Bind our match variables.
	Bind(":user_id", &userId);
	Bind(":page_number", &pageNumber);

	// Define our output.
	DefineLongRaw(1, mpUserPageBuffer, page_size);

	Execute();
    Fetch();
    
	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
        Close(&mpCDAGetUserPageText);
        SetStatement(NULL);

		return;
	}

	Close(&mpCDAGetUserPageText);
	SetStatement(NULL);


	// Null terminate, just in case.
	mpUserPageBuffer[page_size] = '\0';

	pPage->SetDataDictionary((char *) mpUserPageBuffer);
	pPage->DisownUserPage(); // We own this buffer. Tell them so.

	return;
}

static const char *SQL_AddViewToUserPage =
"update ebay_user_pages"
"	set num_views = num_views + 1,"
"	last_viewed = sysdate "
"where user_id = :user_id and "
"page_number = :page_number";

void clsDatabaseOracle::AddViewToUserPage(long userId,
										  int pageNumber)
{
	OpenAndParse(&mpCDAAddViewToUserPage, SQL_AddViewToUserPage);

	Bind(":user_id", &userId);
	Bind(":page_number", &pageNumber);

	Execute();
	Commit();

	Close(&mpCDAAddViewToUserPage);
	SetStatement(NULL);
}

static const char *SQL_RemoveUserPage =
"delete from ebay_user_pages where "
"user_id = :user_id and page_number = :page_number";

static const char *SQL_RemoveUserPageText =
"delete from ebay_user_pages_text where "
"user_id = :user_id and page_number = :page_number";

void clsDatabaseOracle::RemoveUserPage(long userId,
									   int pageNumber)
{
	OpenAndParse(&mpCDAOneShot, SQL_RemoveUserPage);

	Bind(":user_id", &userId);
	Bind(":page_number", &pageNumber);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_RemoveUserPageText);

	Bind(":user_id", &userId);
	Bind(":page_number", &pageNumber);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

    RemoveAllUserPageCategoryListing(userId);
}

static const char *SQL_CreateUserPageCategoryListing =
"insert into ebay_user_pages_categories"
"   (user_id, page_number, page_title, category)"
"values"
"   (:user_id, :page_number, :page_title, :category)";

void clsDatabaseOracle::CreateUserPageCategoryListing(clsUserPage *pPage)
{
    long user_id;
    int page_number;
    long category;

    OpenAndParse(&mpCDAOneShot, SQL_CreateUserPageCategoryListing);

    user_id = pPage->GetUserId();
    page_number = pPage->GetPage();
    category = pPage->GetCategory();

    Bind(":user_id", &user_id);
    Bind(":page_number", &page_number);
    Bind(":category", &category);
    Bind(":page_title", pPage->GetTitle());

    Execute();
    Commit();

    // And we're done.
    Close(&mpCDAOneShot);
    SetStatement(NULL);
}

static const char *SQL_UpdateUserPageCategoryListing =
"update ebay_user_pages_categories"
"   set page_title = :title,"
"   page_number = :page_number "
"where user_id = :user_id and "
"category = :category";

void clsDatabaseOracle::UpdateUserPageCategoryListing(clsUserPage *pPage)
{
    long user_id;
    int page_number;
    long category;

    OpenAndParse(&mpCDAOneShot, SQL_UpdateUserPageCategoryListing);

    user_id = pPage->GetUserId();
    page_number = pPage->GetPage();
    category = pPage->GetCategory();

    Bind(":user_id", &user_id);
    Bind(":page_number", &page_number);
    Bind(":category", &category);
    Bind(":title", pPage->GetTitle());

    Execute();

    // If we couldn't update it, we should create it instead.
    if (CheckForNoRowsUpdated())
    {
		ocan((struct cda_def *)mpCDACurrent);
        Close(&mpCDAOneShot);
        SetStatement(NULL);

        CreateUserPageCategoryListing(pPage);
        return;
    }

    Commit();

    // And we're done.
    Close(&mpCDAOneShot);
    SetStatement(NULL);
}

static const char *SQL_RemoveUserPageCategoryListing =
"delete from ebay_user_pages_categories"
"   where user_id = :user_id and category = :category";

void clsDatabaseOracle::RemoveUserPageCategoryListing(long userId,
                                                      long category)
{
    OpenAndParse(&mpCDAOneShot, SQL_RemoveUserPageCategoryListing);

    Bind(":user_id", &userId);
    Bind(":category", &category);

    Execute();
    Commit();

    Close(&mpCDAOneShot);
    SetStatement(NULL);
}

static const char *SQL_RemoveAllUserPageCategoryListing =
"delete from ebay_user_pages_categories"
"   where user_id = :user_id";

void clsDatabaseOracle::RemoveAllUserPageCategoryListing(long userId)
{
    OpenAndParse(&mpCDAOneShot, SQL_RemoveAllUserPageCategoryListing);

    Bind(":user_id", &userId);

    Execute();
    Commit();

    Close(&mpCDAOneShot);
    SetStatement(NULL);
}

static const int sPagesFetchSize = 20;

static const char *SQL_GetUserPagesByUser =
"select page_number, page_title, category "
"   from ebay_user_pages_categories "
"where user_id = :user_id";

void clsDatabaseOracle::GetUserPagesByUser(long userId,
                                           vector<clsUserPage *> *pvPages)
{
    int page_number[sPagesFetchSize];
    char page_title[sPagesFetchSize][256];
    long category[sPagesFetchSize];
    clsUserPage *pPage;

	int		rowsFetched;
	int		rc;
	int		i, n;

    OpenAndParse(&mpCDAOneShot, SQL_GetUserPagesByUser);

    Bind(":user_id", &userId);

    Define(1, page_number);
    Define(2, page_title[0], sizeof (page_title[0]));
    Define(3, category);

    Execute();

	rowsFetched	= 0;
	do
	{
		assert(mpCDACurrent);
		rc = ofen((struct cda_def *)mpCDACurrent, sPagesFetchSize);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= sNeighborsFetchSize). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

        for (i = 0; i < n; ++i)
        {
            pPage = new clsUserPage;
            pPage->SetTitle(page_title[i]);
            pPage->SetUserId(userId);
            pPage->SetCategory(category[i]);
            pPage->SetPage(page_number[i]);

            pvPages->push_back(pPage);
        }
	} while (!CheckForNoRowsFound());

    Close(&mpCDAOneShot);
    SetStatement(NULL);
}

static const int sCategoryPageFetchSize = 300;

static const char *SQL_GetAllUserCategoryPages =
"select user_id, page_number, page_title, category"
"   from ebay_user_pages_categories";

void clsDatabaseOracle::GetAllUserCategoryPages(vector<clsUserPage *> *pvPages)
{
    int user_id[sCategoryPageFetchSize];
    int page_number[sCategoryPageFetchSize];
    int category[sCategoryPageFetchSize];
    char page_title[sCategoryPageFetchSize][256];
	clsUserPage *pPage;

	int		rowsFetched;
	int		rc;
	int		i, n;

    OpenAndParse(&mpCDAOneShot, SQL_GetAllUserCategoryPages);

    Define(1, user_id);
    Define(2, page_number);
    Define(3, page_title[0], sizeof (page_title[0]));
    Define(4, category);

    Execute();

	rowsFetched	= 0;
	do
	{
		assert(mpCDACurrent);
		rc = ofen((struct cda_def *)mpCDACurrent, sPagesFetchSize);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= sNeighborsFetchSize). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
        rowsFetched += n;

        for (i = 0; i < n; ++i)
        {
            pPage = new clsUserPage;
            pPage->SetTitle(page_title[i]);
            pPage->SetUserId(user_id[i]);
            pPage->SetCategory(category[i]);
            pPage->SetPage(page_number[i]);

            pvPages->push_back(pPage);
        }
	} while (!CheckForNoRowsFound());

    Close(&mpCDAOneShot);
    SetStatement(NULL);
}

static const char *SQL_CreateNeighbor =
"insert into ebay_neighbors"
"	(user_id, friend_id, approved, comment) "
"values"
"	(:user_id, :friend_id, 'N', :comment)";

void clsDatabaseOracle::CreateNeighbor(long userId,
									   long targetUserId,
									   const char *pComment)
{
	OpenAndParse(&mpCDAOneShot, SQL_CreateNeighbor);

	Bind(":user_id", &userId);
	Bind(":friend_id", &targetUserId);
	Bind(":comment", pComment, strlen(pComment) + 1);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
}

static const char *SQL_ApproveNeighbor =
"update ebay_neighbors"
"	set approved = 'Y' "
"where user_id = :user_id and "
"friend_id = :friend_id";

static const char *SQL_DisapproveNeighbor =
"delete from ebay_neighbors "
"where user_id = :user_id and "
"friend_id = :friend_id";

void clsDatabaseOracle::ApproveNeighbor(long userId,
										long targetUserId,
										bool approve)
{
	if (approve)
		OpenAndParse(&mpCDAOneShot, SQL_ApproveNeighbor);
	else
		OpenAndParse(&mpCDAOneShot, SQL_DisapproveNeighbor);

	Bind(":user_id", &userId);
	Bind(":friend_id", &targetUserId);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
}

static const char *SQL_GetNeighbors =
"select friend_id, approved, comment "
"	from ebay_neighbors "
"where user_id = :user_id";

static const int sNeighborsFetchSize = 20;

void clsDatabaseOracle::GetNeighbors(long userId, vector<clsNeighbor *> *pvNeighbors)
{
	long friend_id[sNeighborsFetchSize];
	char approved[sNeighborsFetchSize][1];
	char comment[sNeighborsFetchSize][256];

	int		rowsFetched;
	int		rc;
	int		i, n;

	OpenAndParse(&mpCDAOneShot, SQL_GetNeighbors);

	Bind(":user_id", &userId);

	Define(1, friend_id);
	Define(2, approved[0], sizeof (approved[0]));
	Define(3, comment[0], sizeof (comment[0]));

	Execute();

	rowsFetched	= 0;
	do
	{
		assert(mpCDACurrent);
		rc = ofen((struct cda_def *)mpCDACurrent, sNeighborsFetchSize);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= sNeighborsFetchSize). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
			pvNeighbors->push_back(new clsNeighbor(friend_id[i], (approved[i][0] == 'Y'), comment[i]));
	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;		//lint !e429 Don't worry about clsNeighbor, we know pushBack will eat it
}
