/*	$Id: ccAuthBuddy.cpp,v 1.3 1999/02/21 02:30:39 josh Exp $	*/
//
//	File: 	ccAuthBuddy.cpp
//
//	Author: Sam Paruchuri
//
//	Function:
//			main()
//
// Modifications:
//			- 06/17/98
// Now an exe but should be run as NT service

#include <stdio.h>
#include "eBayTypes.h"
#include "clsCCAuthBuddy.h"


int main ()
{

	// This has to be included to get global gApp instance
#ifdef _MSC_VER
	 g_tlsindex=0;
#endif /* _MSC_VER */

	clsCCAuthBuddy pApp;

	// Use internal vector to check on the status of the items that were sent
	// for Authorization
	pApp.ProcessEnqueuedRequests();

	return 0;
}
