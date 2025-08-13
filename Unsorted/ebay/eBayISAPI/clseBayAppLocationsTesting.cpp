/*	$Id: clseBayAppLocationsTesting.cpp,v 1.2.396.1 1999/08/01 03:01:18 barry Exp $	*/
//
//	File:	clseBayAppLocationsTesting.cpp
//
//	Class:	clseBayAppLocationsTesting
//
//	Author:	Alex Poon (poon@ebay.com)
//
//	Function:
//
//		Functions for testing locations lookup functions
//
// Modifications:
//				- 11/17/98 poon	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

//#define PURIFY_H_VERSION 1
#ifdef PURIFY_H_VERSION
#include "pure.h"
#endif

#include "ebihdr.h"

// Routines for testing the location-related routines

void clseBayApp::LocationsCompareZipToAC(CEBayISAPIExtension* pCtxt, char* zip, int ac)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Comparing "
			  <<	zip
			  <<	" to "
			  <<	ac
			  <<	"</h3><p>";

	*mpStream <<	(mpLocations->DoesACMatchZip(ac, zip) ? "<h1><font color=green>They match</font></h1>" : "<h1><font color=red>They don't match</font></h1>");

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}


void clseBayApp::LocationsCompareZipToState(CEBayISAPIExtension* pCtxt, char* zip, char* state)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Comparing "
			  <<	zip
			  <<	" to "
			  <<	state
			  <<	"</h3><p>";

	*mpStream <<	(mpLocations->DoesZipMatchState(zip, state) ? "<h1><font color=green>They match</font></h1>" : "<h1><font color=red>They don't match</font></h1>");

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}

void clseBayApp::LocationsCompareStateToAC(CEBayISAPIExtension* pCtxt, char* state, int ac)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Comparing "
			  <<	state
			  <<	" to "
			  <<	ac
			  <<	"</h3><p>";

	*mpStream <<	(mpLocations->DoesACMatchState(ac, state) ? "<h1><font color=green>They match</font></h1>" : "<h1><font color=red>They don't match</font></h1>");

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}

void clseBayApp::LocationsCompareZipToCity(CEBayISAPIExtension* pCtxt, char* zip, char* city)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Comparing "
			  <<	zip
			  <<	" to "
			  <<	city
			  <<	"</h3><p>";

	*mpStream <<	(mpLocations->DoesZipMatchCity(zip, city) ? "<h1><font color=green>They match</font></h1>" : "<h1><font color=red>They don't match</font></h1>");

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}

void clseBayApp::LocationsCompareCityToAC(CEBayISAPIExtension* pCtxt, char* city, int ac)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Comparing "
			  <<	city
			  <<	" to "
			  <<	ac
			  <<	"</h3><p>";

	*mpStream <<	(mpLocations->DoesACMatchCity(ac, city) ? "<h1><font color=green>They match</font></h1>" : "<h1><font color=red>They don't match</font></h1>");

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}


void clseBayApp::LocationsIsValidZip(CEBayISAPIExtension* pCtxt, char* zip)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Checking "
			  <<	zip
			  <<	"</h3><p>";

	*mpStream <<	(mpLocations->IsValidZip(zip) ? "<h1><font color=green>Valid</font></h1>" : "<h1><font color=red>Invalid</font></h1>");

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}


void clseBayApp::LocationsIsValidAC(CEBayISAPIExtension* pCtxt, int ac)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Checking "
			  <<	ac
			  <<	"</h3><p>";

	*mpStream <<	(mpLocations->IsValidAC(ac) ? "<h1><font color=green>Valid</font></h1>" : "<h1><font color=red>Invalid</font></h1>");

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}


void clseBayApp::LocationsIsValidCity(CEBayISAPIExtension* pCtxt, char* city)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Checking "
			  <<	city
			  <<	"</h3><p>";

	*mpStream <<	(mpLocations->IsValidCity(city) ? "<h1><font color=green>Valid</font></h1>" : "<h1><font color=red>Invalid</font></h1>");

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}

void clseBayApp::LocationsDistanceZipAC(CEBayISAPIExtension* pCtxt, char* zip, int ac)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Calculating distance between "
			  <<	zip
			  <<	" and "
			  <<	ac
			  <<	"</h3><p>";
	
	double distance = mpLocations->DistanceZipAc(zip, ac);

	if (distance != clsLocations::INVALID_DISTANCE)
		*mpStream <<	"About "	<<	distance << " miles.";
	else
		*mpStream <<	"<h1><font color=red>Invalid info</font></h1>";

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}

void clseBayApp::LocationsDistanceZipZip(CEBayISAPIExtension* pCtxt, char* zip1, char* zip2)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Calculating distance between "
			  <<	zip1
			  <<	" and "
			  <<	zip2
			  <<	"</h3><p>";

	double distance = mpLocations->DistanceZips(zip1, zip2);

	if (distance != clsLocations::INVALID_DISTANCE)
		*mpStream <<	"About "	<<	distance << " miles.";
	else
		*mpStream <<	"<h1><font color=red>Invalid info</font></h1>";


	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}

void clseBayApp::LocationsDistanceACAC(CEBayISAPIExtension* pCtxt, int ac1, int ac2)
{
	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Locations Testing"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<h3>Calculating distance between "
			  <<	ac1
			  <<	" and "
			  <<	ac2
			  <<	"</h3><p>";

	double distance = mpLocations->DistanceACs(ac1, ac2);

	if (distance != clsLocations::INVALID_DISTANCE)
		*mpStream <<	"About "	<<	distance << " miles.";
	else
		*mpStream <<	"<h1><font color=red>Invalid info</font></h1>";


	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}

