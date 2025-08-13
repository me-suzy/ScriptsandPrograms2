/*	$Id: clsCalculateUVRatingsApp.cpp,v 1.2.356.1 1999/08/01 02:51:07 barry Exp $	*/
//
//	File:	clsCalculateUVRatingsApp.cpp
//
//	Class:	clsCalculateUVRatingsApp
//
//	Author:	Alex Poon (poon@ebay.com)
//
//	Function:
//
// Modifications:
//				- 12/04/98 Alex	- Created
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsCalculateUVRatingsApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsUserVerificationServices.h"

#include "vector.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

// dunno what this is for
#ifdef _WIN32
FILE *popen(const char *, const char *);
int pclose(FILE *);
#endif

clsCalculateUVRatingsApp::clsCalculateUVRatingsApp(bool recalculateExistingUVRatings)
{
	mRecalculateExistingUVRatings = recalculateExistingUVRatings;
	return;
}


clsCalculateUVRatingsApp::~clsCalculateUVRatingsApp()
{
	return;
};


void clsCalculateUVRatingsApp::Run()
{
	clsUsers	*pUsers;
	clsUser		*pUser;
	int			UVrating;
	int			UVdetail;
	clsUserVerificationServices *pUserVerificationServices;
	int x = 0;

	// A vector of ALL users' numerical ids
	vector<unsigned int>	vUsers;

	// And it's iterator
	vector<unsigned int>::iterator	i;

	// First, let's get the clsUsers and the clsUserVerificationServices
	pUsers = GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers();
	pUserVerificationServices = GetMarketPlaces()->GetCurrentMarketPlace()->GetUserVerificationServices();

	// Fetch all users
	pUsers->GetAllUsers(&vUsers);

	// Report how many users we're gonna be processing
	printf("\n\n%d users to process.\n", vUsers.size());

	// Now, we loop through them all
	for (i = vUsers.begin(); i != vUsers.end(); i++)
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

		// Check if this user already has a UV rating and skip this user if that's what we have been told to do
		if ((!mRecalculateExistingUVRatings) &&
			(pUser->GetUVRating() != clsUserVerificationServices::UV_RATING_NOT_CALCULATED) &&
			(pUser->GetUVRating() != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE))
		{
			printf("Skipping user \"%s\"\n", pUser->GetUserId());
			delete pUser;
			continue;
		}

		// Fetch the user info stuff from the db
		if (!pUser->HasDetail())
		{
			printf("** Error ** Can not get userinfo for \"%s\"\n",  pUser->GetUserId());
			delete pUser;
			continue;
		}

/*
// For debugging only
if (x==298)
{
	printf("%s is %d\n", pUser->GetUserId(), x);
	printf("-->%s, %s, %s, %s, %s\n",pUser->GetCity() ? pUser->GetCity() : "null",
								pUser->GetState() ? pUser->GetState() : "null",
								pUser->GetZip() ? pUser->GetZip() : "null",
								pUser->GetCountry() ? pUser->GetCountry() : "null",
								pUser->GetDayPhone() ? pUser->GetDayPhone() : "null");

	break;
}
*/
		
		// Ok, let's calculate the UV rating and detail based on the stuff in user info
		pUserVerificationServices->CalculateUVRatingAndDetail(
														&UVrating,
														&UVdetail,
														pUser->GetCity(),
														pUser->GetState(),
														pUser->GetZip(),
														pUser->GetCountryId(), 	// petra
														pUser->GetDayPhone(),
														NULL,
														NULL,
														NULL);


		// Set the UV rating and UV details
		pUser->SetUVRating(UVrating);
		pUser->SetUVDetail(UVdetail);

		// Update the db
		pUser->UpdateUser();

		// Report it to the stdout in case anyone is interested
		printf("\"%s\" = %d\n", pUser->GetUserId(), pUser->GetUVRating());

		delete pUser;
	}
}

int main(int argc, char *argv[])
{

#ifdef _MSC_VER
	g_tlsindex = 0;			// needed to avoid GPF in NT
#endif

	bool recalculateExistingUVRatings = false;

	if ((argc<2) || ((strcmp(argv[1], "-some") !=0) && (strcmp(argv[1], "-all") != 0)))
	{
		cerr	<<	"Please specify \"-all\" to recalculate UV ratings for everyone, or \"-some\" to "
				<<	"calculate UV's for only those users who don't already have them\n";
		return 1;
	}

	if (strcmp(argv[1], "-all")==0)
		recalculateExistingUVRatings = true;
	else 
		recalculateExistingUVRatings = false;
	
	// create the app, and pass whether or not to calculate all users
	clsCalculateUVRatingsApp theApp(recalculateExistingUVRatings);

	theApp.InitShell();		// sets mAppType and maps mpStream to cout
	theApp.Run();

	return 0;
}
