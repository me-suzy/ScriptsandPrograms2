/*	$Id: clsMail.cpp,v 1.7 1999/04/17 20:22:43 wwen Exp $	*/
//
//	File:	clsMail.cpp
//
//	Class:	clsMail
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Makes mailing easy
//
// Modifications:
//				- 05/09/97 michael	- Created
//
#include "eBayKernel.h"
#include "clsMail.h"
#include "smsmtp.h"

#include <time.h>

//
// CTOR
//
clsMail::clsMail()
{
	mpStream		= NULL;
}

//
// DTOR
//
clsMail::~clsMail()
{
	char	*pStr;

	// It's up to us to delete any stream buffer
	// that happens  to be around
	if (mpStream)
	{
		pStr	= mpStream->str();
		delete	pStr;
		delete	mpStream;
	}
}


//
// OpenStream
//
ostrstream *clsMail::OpenStream()
{
	mpStream	= new ostrstream;

	if (mpStream)
		return	mpStream;
	else
		return	NULL;
}
//
//number of mail severs
//

//
// Send
//
int clsMail::Send(char *pTo,
				   char *pFrom,
				   char *pSubject,
				   char **pvCC,
				   char **pvBCC,
				   int MailPooling)
{
	int			rc;
	time_t			theTime;
	struct tm		*tm;

	smtp			*pSmtp;
	char			*pMessage;
	char			*pHeader;
	ostrstream		*pHeaderStr;
	char			**pvTo;
	char			**pv;
	char			strDate[40];

	clsMarketPlace *pMarketPlace;

	// Get the marketplace's clsItems object
	pMarketPlace = NULL;
	pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();

	pMessage	= new char[mpStream->pcount() + 1];
	memcpy(pMessage, mpStream->str(), mpStream->pcount());
	*(pMessage + mpStream->pcount()) = '\0';

	pSmtp = new smtp;
	if (pvBCC == NULL)
		pHeaderStr = NULL;
	else
		pHeaderStr = new ostrstream;

	theTime	= time(0);
	tm = localtime(&theTime);

	rc = 0;
	if (pTo != NULL)
	{
		rc++;
		if (pHeaderStr != NULL)
			*pHeaderStr << "To: " << pTo << "\n";
	}
	if (pvCC != NULL)
	{
		pv = pvCC;
		while (*pv != NULL)
		{
			if (pHeaderStr != NULL)
				*pHeaderStr << "Cc: " << *pv << "\n";
			rc++;
			pv++;
		}
	}
	if (pvBCC != NULL)
	{
		pv = pvBCC;
		while (*pv++ != NULL)
			rc++;
	}
	pvTo = new char *[rc + 1];
	pv = pvTo;
	if (pTo != NULL)
	    *pv++ = pTo;
	while (pvCC != NULL && *pvCC != NULL)
		*pv++ = *pvCC++;
	while (pvBCC != NULL && *pvBCC != NULL)
		*pv++ = *pvBCC++;
	*pv = NULL;
	pSmtp->setrecipients(pvTo);
	pSmtp->setsender(pFrom);
	pSmtp->setsubject(pSubject);
	if (pHeaderStr != NULL)
	{
		*pHeaderStr << "From: " << pFrom << "\n";

		*pHeaderStr << "Subject: " << pSubject << "\n";

		strftime(strDate, sizeof(strDate), "%a, %d %b %Y %T %Z", tm);
		*pHeaderStr << "Date: " << strDate << "\n";

		if (pTo == NULL &&
		    pvCC == NULL)
			*pHeaderStr << "To: \"Undisclosed Recipients\"\n";

		pHeader	= new char[pHeaderStr->pcount() + 1];
		memcpy(pHeader, pHeaderStr->str(), pHeaderStr->pcount());
		pHeader[pHeaderStr->pcount()] = '\0';

		pSmtp->setheader(pHeader);

		delete pHeader;
	}
	pSmtp->setmessage(pMessage);

	//choose mail pool
	//this is not good for permanent, but we don't wnat to do too much of change at last min.
	if (strcmp(pFrom, pMarketPlace->GetRegistrationEmail()) == 0)
	{
		rc = pSmtp->sendmail(1);
	}
	else if (strcmp(pTo, pMarketPlace->GetSupportEmail()) == 0)
	{
		rc = pSmtp->sendmail(2);
	}
	else
	{
	  rc = pSmtp->sendmail(0); // (MailPooling)
	}

	// CleanUp
	delete	pSmtp;
	if (pHeaderStr != NULL)
		delete  pHeaderStr;
	delete	pMessage;

	delete	[] pvTo;

	return rc;
}
