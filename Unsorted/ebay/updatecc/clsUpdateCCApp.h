//
//	File:	clsUpdateCC.h
//
//	Class:	clsUpdateCCApp
//
//	Author:	Sam Paruchuri
//
//	Function:
//			Credit Card Details Update
//
// Modifications:
//				- 02/06/98	Sam - Created
//
#ifndef CLSUPDATECCAPP_INCLUDED
#define	CLSUPDATECCAPP_INCLUDED

#include <stdio.h>
#include <ctype.h>
#include <time.h>

#include "clsApp.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsMarketPlace.h"
#include "clsMarketPlaces.h"
#include "clsMail.h"

// Class forward
class clsDatabase;
class clsUsers;
class clsMarketPlace;
class clsMarketPlaces;
class clsMail;

class clsUpdateCCApp : public clsApp
{
public:
	clsUpdateCCApp();
	~clsUpdateCCApp();

	void InitProfile();
	bool UpdateCC();
	void GenerateExpiryList(int nParms, char **ParmsList);
	void SendEmailToExpiryList();
	int FormatDates();
	void SendAutoMail(char *emailId, strstream *oStr);
	clsUser *IsUserInDB(vector<unsigned int> vUsers, char *userId);
	strstream *SetupStream(strstream *pStream);
	void OutPutData(FILE *fp, strstream *oStr, int nEntries);

protected:
	clsDatabase			*mpDatabase;
	clsMarketPlaces			*mpMarketPlaces;
	clsMarketPlace			*mpMarketPlace;
	clsUsers			*mpUsers;
	bool				meMailOnNewEntry;

};

#endif // CLSUPDATECCAPP_INCLUDED
