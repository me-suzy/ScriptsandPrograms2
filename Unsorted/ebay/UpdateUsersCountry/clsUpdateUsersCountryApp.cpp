/*	$Id: clsUpdateUsersCountryApp.cpp,v 1.5.140.2 1999/08/06 02:26:58 nsacco Exp $	*/
//
//	File:	clsUpdateUsersCountryApp.cpp
//
//	Class:	clsUpdateUsersCountryApp
//
//	Author:	Barry Boone (barry@ebay.com)
//
//	Function:
//
// Modifications:
//				- 12/10/98 Barry	- Created
//				- 06/10/99 nsacco	- Changed Germany code to 77 from 4
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsUpdateUsersCountryApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsCountries.h"

#include "vector.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>


clsUpdateUsersCountryApp::clsUpdateUsersCountryApp(bool updateAllUsers, int maximum)
{
	mUpdateAllUsers = updateAllUsers;
	mMaximum        = maximum;
	return;
}


clsUpdateUsersCountryApp::~clsUpdateUsersCountryApp()
{
	return;
};


struct CountryInfo
{
	char *country;
	int   countryId;	
};

int clsUpdateUsersCountryApp::GetNewCountryInfo(char *country)
{
	int countryId = 0; // Set to 0 for not found at first. 
					   // This might be returned!

	// Current countries in the registration page, and which
	// ids they translate to now.

	CountryInfo OldCountriesArray[]=
	{
		{"other", 0},
		{"argentina", 12},
		{"australia", 15},
		{"austria", 16},
		{"belgium", 23},
		{"brazil", 31},
		{"canada", 2},
		{"carribean", 0}, /* ?? */
		{"chile", 44},
		{"china", 45},
		{"colombia", 46},
		{"czech", 56},
		{"denmark", 57},
		{"europe", 0}, /* ?? */
		{"finland", 70},
		{"france", 71},
		{"germany", 77},    // nsacco 06/10/99 was 4
		{"hong kong", 92},
		{"hk", 92},
		{"hungary", 93},
		{"india", 95},
		{"ireland", 99},
		{"israel", 100},
		{"italy", 101},
		{"japan", 104},
		{"korea", 111}, /* assume south */
		{"latinamerica", 0}, /* ?? */
		{"luxemburg", 122},
		{"malaysia", 127},
		{"mexico", 136},
		{"middleeast", 0}, /* ?? */
		{"netherlands", 146},
		{"newzealand", 149},
		{"northafrica", 0}, /* ?? */
		{"norway", 154},
		{"peru", 161},
		{"poland", 163},
		{"portugal", 164},
		{"russia", 168},
		{"singapore", 180},
		{"slovakia", 181},
		{"slovenija", 182},
		{"slovenia", 182},
		{"southafrica", 185},
		{"spain", 186},
		{"sweden", 192},
		{"switzerland", 193},
		{"taiwan", 196},
		{"thailand", 199},
		{"turkey", 204},
		{"uk", 3},
		{"usa", 1},
		{"us", 1},
		{"uruguay", 211},
		{"uru", 211},
		{"unitedstates", 1},
		{"united states", 1},
		{"america", 1},
		{"venezuela", 215}
	};

	int len = sizeof (OldCountriesArray) / sizeof (CountryInfo);
	int i;

	for (i = 0; i < len; i++)
	{
		if (strcmp(OldCountriesArray[i].country, country) == 0)
		{
			countryId = OldCountriesArray[i].countryId;
			break;
		}
	}
		
	return countryId; 
}

void clsUpdateUsersCountryApp::Run(int minId, int maxId)
{
	clsUsers	 *pUsers;
	clsUser		 *pUser;
	clsCountries *pCountries;
	int			  countryId;
	char          countryName[64];
	int           x = 0;
	int           count;

	// A vector of ALL users' numerical ids
	vector<unsigned int>	vUsers;

	// And it's iterator
	vector<unsigned int>::iterator	i;

	// First, let's get the clsUsers and the clsCountries object.
	pUsers = GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers();
	pCountries = GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries();

	if (!pCountries)
	{
		printf("\nCountries not found!\n");
		return;
	}

	// Fetch all users
	pUsers->GetAllUsers(&vUsers, minId, maxId);

	// Report how many users we're gonna be processing
	printf("\n\n%d users to process.\n", vUsers.size());

	// Start off fresh.
	count = 0;

	// Now, we loop through them all
//	for (i = vUsers.begin(); i != vUsers.end() && count < mMaximum; i++)
	for (i = vUsers.begin(); i != vUsers.end() ; i++)
	{	
		// Report progress
		x++;
		printf("%d) ", x);

		// Get the basic user (no user info just yet)
		pUser	= pUsers->GetUser((*i));

		// Check for non-user (shouldn't happen)
		if (!pUser)
		{
			printf("** Impossible Error ** Can not get user #%d\n", (*i));
			continue;
		}
		if ((pUser->GetUserState() == UserUnknown) || 
			(pUser->GetUserState() == UserGhost))
		{
			printf("** Unknown or ghost ** Can not get userinfo for \"%s\"\n",  
				pUser->GetUserId());
			continue;
		}

		
		// Fetch the user info stuff from the db
		if (!pUser->HasDetail())
		{
			printf("** Error ** Can not get userinfo for \"%s\"\n",  pUser->GetUserId());
			delete pUser;
			continue;
		}	

		// Check if this user already has a countryId and skip this user if that's what we have been told to do
		if ( (!mUpdateAllUsers) &&
			 (pUser->GetCountryId() != clsCountries::COUNTRY_NONE) )
		{
			printf("Skipping user \"%s\"\n", pUser->GetUserId());
			delete pUser;
			continue;
		}



/*
// For debugging only
if (x==298)
{
	printf("%s is %d\n", pUser->GetUserId(), x);
	printf("-->%s,\n",pUser->GetCountry() ? pUser->GetCountry() : "null");

	break;
}
*/
		// Get the country Id given the name currently in the database.
		countryId = GetNewCountryInfo(pUser->GetCountry());
		
		if (countryId == Country_None)
		{
			// Not found, so look in the new countries.
			countryId = pCountries->GetCountryId(pUser->GetCountry());
		}

		// Ok, let's update the country Id based on the country name.
		pUser->SetCountryId(countryId);
		pCountries->GetCountryName(countryId, countryName);
		if (strlen(countryName) == 0) // not null constraint
			strcpy(countryName, "unknown");

		pUser->SetCountry(countryName);

		// Update the db
		pUser->UpdateUser();
		count++;

		// Report it to the stdout in case anyone is interested
		printf("\"%s\" = %d\n", pUser->GetUserId(), pUser->GetCountryId());

		delete pUser;
	}
}

int main(int argc, char *argv[])
{

#ifdef _MSC_VER
	g_tlsindex = 0;			// dunno what this is for!
#endif
	int minId = 0;
	int maxId = 0;

	bool updateAllUsers = false;
	int  maximum        = 0;

	if ((argc<2) || ((strcmp(argv[1], "-some") !=0) && (strcmp(argv[1], "-all") != 0)))
	{
		cerr	<<	"Please specify \"-all\" to update all of the users given the "
			    <<  "country id, or \"-some\" to update the country id"
				<<	"for only those users who don't have one\n";
		return 1;
	}

	if (strcmp(argv[1], "-all")==0)
		updateAllUsers = true;
	else 
		updateAllUsers = false;

	// check for maximum
//	if (((strcmp(argv[1], "-some")==0) || (strcmp(argv[1], "-all")==0)) && (argc == 3))
//		maximum = atoi(argv[2]);
	
	if (argc > 3 )
	{
		minId = atoi(argv[2]);
		maxId = atoi(argv[3]);
	}
	// create the app, and pass whether or not to calculate all users
	clsUpdateUsersCountryApp theApp(updateAllUsers, maximum);

	theApp.InitShell();		// sets mAppType and maps mpStream to cout
	theApp.Run(minId, maxId);

	return 0;
}
