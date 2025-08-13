/*	$Id: clsUpdateUsersCountryApp.h,v 1.3 1999/01/26 23:33:20 lena Exp $	*/
//
//	File:	UpdateUsersCountry.h
//
//	Class:	UpdateUsersCountry
//
//	Author:	Barry Boone (barry@ebay.com)
//
//	Function:
//
//	Sets the countryId field in ebay_users from the country name stored
//  in ebay_user_info.
//
// Modifications:
//				- 12/10/98 Barry	- Created
//
#ifndef UpdateUsersCountry_INCLUDE

#include "clsApp.h"
#include "fstream.h"

// Class forwards go here


class clsUpdateUsersCountryApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsUpdateUsersCountryApp(bool updateAllUsers, int max);
		~clsUpdateUsersCountryApp();
		
		// Runner
		void Run(int minId, int maxId);
		int GetNewCountryInfo(char *country);

	private:
		bool mUpdateAllUsers;
		int  mMaximum;
		
};

#define UpdateUsersCountry_INCLUDE 1
#endif /* UpdateUsersCountry_INCLUDE */
