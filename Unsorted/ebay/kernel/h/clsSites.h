//
// File Name: clsSites.h
//
// Description: Header for the clsSites object.
//
// Author:  Nathan Sacco
//
//			05/27/99 nsacco	- Created
//			08/02/99 petra	- add SetCurrentSite
//
#ifndef CLSSITES_INCLUDE
#define CLSSITES_INCLUDE

#include "clsSite.h"

#include "vector.h"

#define INVALID_SITE -1

class clsSites
{
public:
	clsSites();
	~clsSites();

	clsSite *GetCurrentSite();

	clsSite *GetSite(const char *pName);
	clsSite *GetSite(int id);

	void GetAllSites(vector<clsSite *> *pvSites);

	void ResetCurrentSite();

	void GetAllMinimalSites(vector<clsSite *> *pvSites);

	// kakiyama 06/23/99
	void GetForeignSites(vector<clsSite *> *pvSites);

	bool SetCurrentSite (int id);		// petra

private:
	void LoadSites();
	clsSite* mpCurrentSite;
	vector<clsSite *> *mpvSites;	
	clsSite *mpDefaultSite;
};
#endif