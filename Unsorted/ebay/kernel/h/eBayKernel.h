/* $Id: eBayKernel.h,v 1.8.130.1 1999/08/05 18:59:13 nsacco Exp $ */

// Precompiled header for the eBay Kernel.
//
#ifndef _EBAYKERNEL_H_
#define _EBAYKERNEL_H_

#include "eBayTypes.h"
#include "clsMarketPlace.h"
#include "clsApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "eBayDebug.h"
#include "clsDatabaseOracle.h"
#include "clsUtilities.h"
#include "clsLog.h"
#include "eBayExceptions.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsUserValidation.h"
#include "clsItems.h"
#include "clsListingItem.h"
#include "clsLocations.h"
#include "clsUserVerificationServices.h"
#include "clsCountries.h"
#include "clsInternationalUtilities.h"
#include "clsItemsCache.h"
#ifdef _MSC_VER
#include "clsMailControl.h"
#endif

#include "clsIntlResource.h"

#include "ocidfn.h"
extern "C"
{
#include "ociapr.h"
}

//#define UK_ONLY

#endif
