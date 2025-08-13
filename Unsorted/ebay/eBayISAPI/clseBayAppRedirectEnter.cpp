/*	$Id: clseBayAppRedirectEnter.cpp,v 1.5 1999/02/21 02:32:36 josh Exp $	*/
#include "ebihdr.h"
#include "eBayExceptions.h"

#include <ctype.h>

// Redirect + cookie
void clseBayApp::RedirectEnter(CEBayISAPIExtension* pServer,
							   CHttpServerContext* pCtxt,
							   char *pLocation,
							   char *pPartnerName)
{
	bool setCookie = true;
	time_t theTime;
	clock_t theClock;
	unsigned long theId;
	unsigned long length;
	char buffer[4096];
	char cookieBuffer[4096];
	unsigned long cookieLength;
	int partnerId;
	vector<const char *>::iterator i;
	char *pStr;

	cookieLength = 4096;

	if (pCtxt->GetServerVariable("HTTP_COOKIE", cookieBuffer, &cookieLength))
	{
		// Already set. Return.
		pStr = strstr(cookieBuffer, "p=");
		if (pStr && ((pStr == cookieBuffer) || isspace(*(pStr - 1))))
		{
			setCookie = false;
		}
	}

	// Get the partners if we don't have them.
	if (mpvPartners == NULL)
	{
		mpvPartners = new vector<const char *>;
		gApp->GetDatabase()->GetPartnerIds(mpvPartners);
	}

	// First, lowercase the partner name
	for (pStr = pPartnerName; *pStr; ++pStr)
		*pStr = tolower(*pStr);

	partnerId = 0;
	for (partnerId = 0, i = mpvPartners->begin(); i != mpvPartners->end(); ++partnerId, ++i)
	{
		if (!*i)
			continue;
		if (!strcmp(pPartnerName, *i))
			break;
	}

	if (i == mpvPartners->end())
	{
		pServer->EbayRedirect(pCtxt, pLocation);
		length = strlen(pLocation);
		// pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP, pLocation,	&length, NULL);

		return;
	}

	gApp->GetDatabase()->IncrementPartnerCount(partnerId);

	if (!setCookie)
	{
		pServer->EbayRedirect(pCtxt, pLocation);
		length = strlen(pLocation);
		// pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP, pLocation,	&length, NULL);
		return;
	}

	theTime = time(NULL);
	theClock = clock();

	// Arbitrary big number less than time()
	theTime -= 800000000;

	theId = (theTime * CLOCKS_PER_SEC) + (theClock % CLOCKS_PER_SEC);

	sprintf(buffer, "Location: %s\r\n" /* Set the URL for redirect */
		"Set-Cookie: p=%d-%lu; " /* Set the value of the cookie */
		"expires=Fri, 01-Oct-2010 00:00:00 GMT; " /* Set the expiration */
		"path=/; domain=.ebay.com;\r\n", /* Set the domain and path */
		pLocation, partnerId, theId);
	length = strlen(buffer);

	pCtxt->ServerSupportFunction(HSE_REQ_SEND_RESPONSE_HEADER,
		"302 Object Moved", &length, (unsigned long *) buffer);

	return;
}
