/*	$Id: ccBatchSettle.cpp,v 1.3 1999/02/21 02:30:42 josh Exp $	*/
//
//	File: 	ccBatchSettle.cpp
//
//	Author: Sam Paruchuri
//
//	Function:
//			main()
//
// Modifications:
//			- 07/13/98
// Now an exe but should be run as NT service

#include <stdio.h>
#include "eBayTypes.h"
#include "clsCCBatchSettle.h"


int main ()
{
	// This has to be included to get global gApp instance
#ifdef _MSC_VER
	 g_tlsindex=0;
#endif /* _MSC_VER */
	clsBatchSettle pApp;

	// Transaction_State: New and Transaction_type: Settlement
	// Uses clsAuthorize to dispatch Authorization requests
	// Mode:batch
	pApp.DumpSettlementRecordsToFile();

	return 0;
}
