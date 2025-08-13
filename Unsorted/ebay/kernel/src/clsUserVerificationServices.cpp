/*	$Id: clsUserVerificationServices.cpp,v 1.2.404.1 1999/08/01 03:02:34 barry Exp $	*/
//
//	File:	clsUserVerificationServices.cpp
//
//	Class:	clsUserVerificationServices
//
//	Author:	Alex Poon
//
//	Function:
//
//			This class encapsulates user-verfication services.
//
//	Modifications:
//				  mm/dd/yy
//				- 11/2/98 alex	- Created
//				- 06/28/99 petra	- work with country id, not name

#include "eBayKernel.h"
#include <math.h>

const int clsUserVerificationServices::UV_RATING_NOT_CALCULATED = -99999;
const int clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE = -99998;


typedef struct
{
	char	*pState2Letter;
	char	*pState;
} StateMapUS;

// This crap is for backwards compatibility with really old registration forms
const StateMapUS StateMapUSArray[] =
{	
	{	"AL",					"Alabama"				},
	{	"AK",					"Alaska"				},
	{	"AZ",					"Arizona"				},
	{	"AR",					"Arkansas"				},
	{	"CA",					"California"			},
	{	"CO",					"Colorado"				},
	{	"CT",					"Connecticut"			},
	{	"DE",					"Delaware"				},
	{	"DC",					"District of Columbia"	},
	{	"FL",					"Florida"				},
	{	"GA",					"Georgia"				},
	{	"HI",					"Hawaii"				},
	{	"ID",					"Idaho"					},
	{	"IL",					"Illinois"				},
	{	"IN",					"Indiana"				},
	{	"IA",					"Iowa"					},
	{	"KS",					"Kansas"				},
	{	"KY",					"Kentucky"				},
	{	"LA",					"Louisiana"				},
	{	"ME",					"Maine"					},
	{	"MD",					"Maryland"				},
	{	"MA",					"Massachusetts"			},
	{	"MI",					"Michigan"				},
	{	"MN",					"Minnesota"				},
	{	"MS",					"Mississippi"			},
	{	"MO",					"Missouri"				},
	{	"MT",					"Montana"				},
	{	"NE",					"Nebraska"				},
	{	"NV",					"Nevada"				},
	{	"NH",					"New Hampshire"			},
	{	"NJ",					"New Jersey"			},
	{	"NM",					"New Mexico"			},
	{	"NY",					"New York"				},
	{	"NC",					"North Carolina"		},
	{	"ND",					"North Dakota"			},
	{	"OH",					"Ohio"					},
	{	"OK",					"Oklahoma"				},
	{	"OR",					"Oregon"				},
	{	"PA",					"Pennsylvania"			},
	{	"RI"			,		"Rhode Island"			},
	{	"SC",					"South Carolina"		},
	{	"SD",					"South Dakota"			},
	{	"TN",					"Tennessee"				},
	{	"TX",					"Texas"					},
	{	"UT",					"Utah"					},
	{	"VT",					"Vermont"				},
	{	"VA",					"Virginia"				},
	{	"WA",					"Washington"			},
	{	"WV",					"West Virginia"			},
	{	"WI",					"Wisconsin"				},
	{	"WY",					"Wyoming"				},
	{	"PR",					"Puerto Rico"			},
	{	"VI",					"Virgin Island"			},
	{	"MP",					"Northern Mariana Islands"	},
	{	"GU",					"Guam"					},
	{	"AS",					"American Samoa"		},
	{	"PW",					"Palau"					},
	{	NULL,					NULL					}
};


// Given a long-name state, return the 2-letter abbreviation
//  Note: linear search isn't the most efficient, but there aren't many, so...
const char* clsUserVerificationServices::GetStateAbbreviation(const char *pState) const
{
	int i=0;

	while (1)
	{
		if ((StateMapUSArray[i].pState) == NULL)
			return "??";	// hit end of the list
		if ((clsUtilities::SmartAlphaNumStringCompare(StateMapUSArray[i].pState, pState)==0))
			return StateMapUSArray[i].pState2Letter;	// found it
		i++;
	}

	return "??";	// should never get there
}

//
// Default Constructor
//
clsUserVerificationServices::clsUserVerificationServices(clsMarketPlace *pMarketPlace)
{
	mpMarketPlace	= pMarketPlace;
}

//
// Destructor
//
clsUserVerificationServices::~clsUserVerificationServices()
{
}

// Given a state, zip, country, and phone number, calculate and return
// a numerical UV rating and a UV detail bitfield.
/// Note: for backwards compatability with old registrants, if pDayPhone2-4 are empty,
//   then this function will attempt to parse out the areacode from pDayPhone1. also,
//   for backwards compatibility, state can be either spelled out, or be the 2-letter abbreviation
void clsUserVerificationServices::CalculateUVRatingAndDetail(
														int * pUVrating,
														int * pUVdetail,
														const char * pCity,
														const char * pState,
														const char * pZip,
//														const char * pCountry,
														const int countryId,	// petra
														const char * pDayPhone1,
														const char * pDayPhone2,
														const char * pDayPhone3,
														const char * pDayPhone4) const
{
	int ac = 0;									// areacode
	int prefix = 0;								// 3digit prefix
	int length = 0;
	int totalNumberOfDigitsInPhoneNumber = 0;
	char *pEntirePhoneNumberTemp = NULL;
	char *pEntirePhoneNumberJustDigits = NULL;
	char pState2Letter[3];
	char pZip5[6];
	double distanceZipAC;

	// safety
	if (!pUVrating || !pUVdetail) return;

	memset(pZip5, 0x00, sizeof(pZip5));
	memset(pState2Letter, 0x00, sizeof(pState2Letter));

	// in case we'll have to abort
	*pUVrating = UV_RATING_NOT_CALCULATED;
	*pUVdetail = 0;

	// paranoid check for missing info (should be impossible because of notnull contraints in Oracle)
	if (!pCity || !pState || !pZip) return;	// petra 06/10/99 remove pCountry

	// For now, return UV_RATING_FOR_COUNTRY_NOT_AVAILABLE for all non-USA users
// petra	if ((clsUtilities::SmartAlphaNumStringCompare(pCountry, "usa") != 0) &&
// petra		(clsUtilities::SmartAlphaNumStringCompare(pCountry, "unitedstates") != 0) &&
// petra		(clsUtilities::SmartAlphaNumStringCompare(pCountry, "United States") != 0))
	if (countryId != Country_US)	// petra
	{
		*pUVrating = UV_RATING_FOR_COUNTRY_NOT_AVAILABLE;
		*pUVdetail = 0;
		return;
	}

	// Extract the areacode from pDayPhone1 if pDayPhone2-4 are empty.
	//	(This is for backwards compatibility with the old registration form)
	if (((!pDayPhone2) && (!pDayPhone3) && (!pDayPhone4)) ||
		((!strlen(pDayPhone2)) && (!strlen(pDayPhone3)) && (!strlen(pDayPhone4))))
	{
		// damn, the pDayPhone1 has the entire phonenumber.
		// the strategy here is to take the first 3 digits as the areacode
		//  unless the first digit is 1, in which case we skip over 1
		char pAc[4];
		char pPrefix[4];
		memset(pAc, 0x00, sizeof(pAc));
		memset(pPrefix, 0x00, sizeof(pPrefix));

		if (pDayPhone1)
		{
			char* pDigitsOnly = clsUtilities::StripEverythingButDigits(pDayPhone1);
			char* p = pDigitsOnly;
			if (pDigitsOnly[0] == '1') p++;		// skip over the 1

			strncpy(pAc, p, 3);					// get the areacode
			strncpy(pPrefix, p+3, 3);			// get the 3digit prefix

			ac = atoi(pAc);						// get the areacode
			prefix = atoi(pPrefix);				// get the 3digit prefix

			delete [] pDigitsOnly;
		}
	}
	// else pDayPhone1 is assumed to be the areacode, and pDayPhone2
	//  is the 3-digit prefix (new registration form)
	else
	{
		ac = atoi(pDayPhone1);
		prefix = atoi(pDayPhone2);
	}

	// Now that we've got the areacode tucked safely away, let's glue everything together to get the entire phonenumber
	//  in order to get the total number of digits in the phone number
	length =	(pDayPhone1 ? strlen(pDayPhone1) : 0) +
				(pDayPhone2 ? strlen(pDayPhone2) : 0) +
				(pDayPhone3 ? strlen(pDayPhone3) : 0) +
				(pDayPhone4 ? strlen(pDayPhone4) : 0);

	pEntirePhoneNumberTemp = new char[length+1];
	strcpy(pEntirePhoneNumberTemp, (pDayPhone1 ? pDayPhone1 : ""));
	strcat(pEntirePhoneNumberTemp, (pDayPhone2 ? pDayPhone2 : ""));
	strcat(pEntirePhoneNumberTemp, (pDayPhone3 ? pDayPhone3 : ""));
	strcat(pEntirePhoneNumberTemp, (pDayPhone4 ? pDayPhone4 : ""));
	
	pEntirePhoneNumberJustDigits = clsUtilities::StripEverythingButDigits(pEntirePhoneNumberTemp);

	totalNumberOfDigitsInPhoneNumber = strlen(pEntirePhoneNumberJustDigits);

	delete [] pEntirePhoneNumberTemp;
	delete [] pEntirePhoneNumberJustDigits;

	// Now convert the state to a two-letter abbreviation
	if (strlen(pState) == 2)			// already 2 letters (normal registration form)
		strcpy(pState2Letter, pState);
	else								// ugh, need to convert (really old registration form)
		strcpy(pState2Letter, GetStateAbbreviation(pState));

	// Now, convert the zip to a 5 digit zip
	strncpy(pZip5, pZip, 5);

	// ----------------------------------------------------------------------------------------
	// FINALLY, we've got all the information we need to calculate the stuff we want!
	/// Specifically, we've got
	//		1. ac (3 digits)
	//		2. prefix (3 digits)
	//		3. pState2Letter
	//		4. totalNumberOfDigitsInPhoneNumber
	//		5. pZip5
	//		6. pCity


	// Let's fill in the UV detail bit field
	*pUVdetail = 0;
	if (totalNumberOfDigitsInPhoneNumber >= 10) *pUVdetail += UVDetailPhoneNumberLength;
	if (mpMarketPlace->GetLocations()->IsValidAC(ac)) *pUVdetail += UVDetailValidAreaCode;
	if (mpMarketPlace->GetLocations()->IsValidZip(pZip5)) *pUVdetail += UVDetailValidZipCode;
	if (mpMarketPlace->GetLocations()->IsValidCity(pCity)) *pUVdetail += UVDetailValidCity;
	if (mpMarketPlace->GetLocations()->DoesZipMatchState(pZip5, pState2Letter)) *pUVdetail += UVDetailZipMatchesState;
	if (mpMarketPlace->GetLocations()->DoesACMatchState(ac, pState2Letter)) *pUVdetail += UVDetailAreaCodeMatchesState;
	if (mpMarketPlace->GetLocations()->DoesZipMatchCity(pZip5, pCity)) *pUVdetail += UVDetailZipMatchesCity;
	if (mpMarketPlace->GetLocations()->DoesACMatchCity(ac, pCity)) *pUVdetail += UVDetailAreaCodeMatchesCity;
	if (mpMarketPlace->GetLocations()->DoesCityMatchState(pCity, pState)) *pUVdetail += UVDetailCityMatchesState;
	if (prefix != 555) *pUVdetail += UVDetailPhonePrefixNot555;
	
	distanceZipAC = mpMarketPlace->GetLocations()->DistanceZipAc(pZip5, ac);
	if ((distanceZipAC != clsLocations::INVALID_DISTANCE) && (distanceZipAC <= 100.0)) *pUVdetail += UVDetailZipCloseToAreaCode;

	// Calculate the numerical UV rating
	*pUVrating = CalculateUVRating(*pUVdetail);
}

// Given a UV Detail, return the numerical rating.
int clsUserVerificationServices::CalculateUVRating(int UVdetail) const
{
	int rating = 0;

	rating += (UVdetail & UVDetailPhoneNumberLength)		 ? +0 : -20;

	rating += (UVdetail & UVDetailValidAreaCode)			 ? +2 : -2;
	rating += (UVdetail & UVDetailValidZipCode)			 ? +2 : -2;
	rating += (UVdetail & UVDetailValidCity)				 ? +2 : -1;

	rating += (UVdetail & UVDetailZipMatchesState)			 ? +1 : -2;
	rating += (UVdetail & UVDetailAreaCodeMatchesState)	 ? +1 : -1;

	rating += (UVdetail & UVDetailZipCloseToAreaCode)		 ? +2 : -1;

	rating += (UVdetail & UVDetailZipMatchesCity)			 ? +1 : -1;
	rating += (UVdetail & UVDetailAreaCodeMatchesCity)		 ? +1 : -1;
	rating += (UVdetail & UVDetailCityMatchesState)		 ? +1 : -1;

	rating += (UVdetail & UVDetailPhonePrefixNot555)		 ? +0 : -20;

	return rating;

}

// Given a UV Detail, will output to the given stream what the record means
//  in English
void clsUserVerificationServices::TranslateUVDetailToText(int UVdetail, ostream *theStream) const
{
	const char * pYes = "<font color=\"darkgreen\"><b>Yes</b></font>";
	const char * pNo = "<font color=\"red\"><b>No</b></font>";

	*theStream <<	"<table border=\"1\" cellspacing=\"0\" width=\"500\">\n";

	*theStream <<	"<tr>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">Areacode exists?</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailValidAreaCode) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">Areacode in State?</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailAreaCodeMatchesState) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">Areacode in City?</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailAreaCodeMatchesCity) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"</tr>\n";

	*theStream <<	"<tr>\n";
	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">Zip exists?</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailValidZipCode) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">Zip in State?</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailZipMatchesState) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">Zip in City?</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailZipMatchesCity) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"</tr>\n";

	*theStream <<	"<tr>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">City exists?</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailValidCity) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">City in State</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailCityMatchesState) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">Zip <i>near</i> Areacode?</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailZipCloseToAreaCode) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"</tr>\n";

	*theStream <<	"<tr>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">Phone not 555?</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailPhonePrefixNot555) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\"><font size=\"2\">&gt;=10 digit Phone?</font></td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	((UVdetail & UVDetailPhoneNumberLength) ? pYes : pNo)
				<<	"</td>\n";

	*theStream <<	"<td width=\"23%\" bgcolor=\"#FFFFEE\">&nbsp;</td>\n";
	*theStream <<	"<td width=\"10%\">"
				<<	"&nbsp;"
				<<	"</td>\n";

	*theStream <<	"</tr>\n";

	*theStream <<	"</table>\n";

}


