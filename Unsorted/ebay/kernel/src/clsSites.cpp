//
// File Name: clsSites.cpp
//
// Description: Container object for the clsSites,
//              also does implementation hiding for such
//              things as the 'default site'.
//
// Authors:     Nathan Sacco (nathan@ebay.com)
// Modifications:
//				- 05/27/99 nsacco	- Created


#include "eBayKernel.h"
#include "clsSites.h"
#include "clsEnvironment.h"

#ifdef _MSC_VER
#define strcasecmp(x, y) stricmp(x, y)
#endif


// Constructor.
clsSites::clsSites()
{
	mpvSites = new vector<clsSite *>;
	mpCurrentSite = NULL;
	// wen 06/03/99
	mpDefaultSite = new clsSite("ebay",			// name
								SITE_EBAY_MAIN,	// id
								NULL,			// parsed string
								0,				// time zone id
								0,				// locale id
								Currency_USD);	// listing currency
	mpvSites->push_back(mpDefaultSite);
}

// Destructor.
clsSites::~clsSites()
{
	vector<clsSite *>::iterator i;

	for (i = mpvSites->begin(); i != mpvSites->end(); ++i)
		delete *i;

	mpvSites->erase(mpvSites->begin(), mpvSites->end());
	delete mpvSites;

	return;
}

// Loads up all the sites, and sets the 'default site', which does special
// things.
void clsSites::LoadSites()
{
	clsDatabase *pDatabase;
	vector<clsSite *>::iterator i;

	if (!mpvSites->empty())
	{
		for (i = mpvSites->begin(); i != mpvSites->end(); ++i)
			delete *i;

		mpvSites->erase(mpvSites->begin(), mpvSites->end());
	}

	pDatabase = gApp->GetDatabase();

	pDatabase->LoadSites(mpvSites);

	mpDefaultSite = GetSite("ebay");
	if (!mpDefaultSite)
	{
		// nsacco 06/01/99
		mpDefaultSite = new clsSite("ebay",				// name
									SITE_EBAY_MAIN,		// id
									NULL,				// parsed string
									0,					// time zone id
									0,					// locale id
									Currency_USD);		// listing currency
		mpvSites->push_back(mpDefaultSite);
	}

	return;
}

// Gets the current site, based on the last id set
// (which may be determined from the environment)
clsSite *clsSites::GetCurrentSite()
{
	clsEnvironment *pEnvironment = NULL;
	char *pServerName;
	char *pScriptName;
	int PartnerId;
	int SiteId = INVALID_SITE;

	if (mpCurrentSite == NULL)
	{
		if (gApp->GetEnvironment())
		{
			pServerName = gApp->GetEnvironment()->GetServerName();
			pScriptName = gApp->GetEnvironment()->GetScriptName();
		}
		else
		{
			pServerName = NULL;
			pScriptName = NULL;
		}

		if ((pServerName && *pServerName) && (pScriptName && *pScriptName))
		{
			clsUtilities::GetSiteIDAndPartnerID(pServerName, 
										pScriptName, 
										SiteId, 
										PartnerId);
		}
		mpCurrentSite = GetSite(SiteId);
	}

	return mpCurrentSite;
}

// Gets a site by name.
clsSite *clsSites::GetSite(const char *pName)
{
	vector<clsSite *>::iterator i;
	clsDatabase *pDatabase = NULL;
	clsSite *pSite = NULL;

	// search for the name in the list of sites
	for (i = mpvSites->begin(); i != mpvSites->end(); ++i)
	{
		if (*i && !strcasecmp((*i)->GetName(), pName))
			return (*i);
	}

	// if the name wasn't found, search in the database
	if ((*i) == NULL)
	{
		// load the site from the database
		pDatabase = gApp->GetDatabase();
		pDatabase->LoadSite(pName, &pSite);

		// save the site in mpvSites
		if (!pSite)
		{
			mpvSites->push_back(pSite);
			return pSite;
		}
	}

	return mpDefaultSite;
}

// Gets a site by number.
clsSite *clsSites::GetSite(int id)
{
	// check for invalid id
	if (id < 0)
		return mpDefaultSite;

	vector<clsSite *>::iterator i;
	clsDatabase *pDatabase = NULL;
	clsSite *pSite = NULL;

	// search for the id in the list of sites
	for (i = mpvSites->begin(); i != mpvSites->end(); ++i)
	{
		if (*i && ((*i)->GetId() == id))
			return (*i);
	}

	// if not found, search in the database
	// load the site from the database
	pDatabase = gApp->GetDatabase();
	pDatabase->LoadSite(id, &pSite);

	// save the site in mpvSites
	if (pSite)
	{
		mpvSites->push_back(pSite);
		return pSite;
	}

	return mpDefaultSite;
}

// Copies the whole vector -- this is used
// in site management functions.
void clsSites::GetAllSites(vector<clsSite *> *pvSites)
{
	if (pvSites != NULL && mpvSites != NULL)
		pvSites->insert(pvSites->end(), mpvSites->begin(), mpvSites->end());

	return;
}

// Reset current site
void clsSites::ResetCurrentSite()
{
	clsSite*	pSite;

	mpCurrentSite = NULL;

	// Get the current site
	pSite = GetCurrentSite();

	// Reset the current Partner
	pSite->GetPartners()->ResetCurrentPartner();
}

// Gets a vector of all site names.
void clsSites::GetAllMinimalSites(vector<clsSite *> *pvSites)
{
	if (pvSites != NULL)
		gApp->GetDatabase()->GetAllMinimalSites(pvSites);

	return;
}

// kakiyama 06/23/99
void clsSites::GetForeignSites(vector<clsSite *> *pvSites)
{
	if (pvSites != NULL)
		gApp->GetDatabase()->GetForeignSites(pvSites);
}

//
// petra 07/27/99 
// we need this for the widget parser and/or other batch jobs.
// NO ONE ELSE SHOULD EVER CALL THIS! 
// the site should be determined from the URL in all cases
// .. unless, of course, there is no URL..
// returns true if the site could be set, false if not
//
bool clsSites::SetCurrentSite (int id)
{
	vector<clsSite *>::iterator i;
	clsDatabase *pDatabase = NULL;
	clsSite *pSite = NULL;

	// search for the id in the list of sites
	for (i = mpvSites->begin(); i != mpvSites->end(); ++i)
	{
		if (*i && ((*i)->GetId() == id))
		{
			mpCurrentSite = (*i);
			return true;
		}
	}

	// if not found, search in the database
	// load the site from the database
	pDatabase = gApp->GetDatabase();
	pDatabase->LoadSite(id, &pSite);

	// save the site in mpvSites
	if (pSite)
	{
		mpvSites->push_back(pSite);
		return true;
	}

	return false;
}