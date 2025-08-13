/*	$Id: clsPSSearches.cpp,v 1.2.158.1 1999/08/01 02:51:31 barry Exp $	*/
//
//	File:	clsPSSearches.cpp
//
//	Class:	clsPSSearches
//
//	Author:	Wen Wen
//
//	Function:
//		This class is to encapturate the functions provided by NetMind
//							
// Modifications:
//				- 2/6/99	Wen - Created
//				- 07/26/99	petra	- changed to use time widget
//
#include "ebihdr.h"

#include "nmstring.h"
#include "url.h"
#include "clsPSSearch.h"
#include "clsPSSearches.h"
#include <clseBayTimeWidget.h>	// petra

static const char MSG_SEARCH_DESC[] =
"Item Title and Description";

static const char MSG_SEARCH_TITLE[] =
"Item Title Only";

static const char MSG_SEARCH_DAILY[] =
"Daily";

static const char MSG_SEARCH_THREEDAY[] =
"Every 3 days";

static const int MAX_REQUEST_LENGTH = 1000;

void clsPSSearches::SetProps()
{
	// IMPORTANT - these values have been hardcoded. They should be defined in a config file
	//************************
	mProps.addProperty("mpNonceSecret",		"ebay");
	mProps.addProperty("responder_url",		"http://216.32.120.118/responder");
	mProps.addProperty("timeout",			"60");
	//************************

	mpResponderUrl = mProps.getProperty("responder_url");
	if (!mpResponderUrl) 
	{
//		throw EbayException(UNDEFINED_RESPONDER_URL);
	}
	
	char* pTimeOutStr = mProps.getProperty("timeout", "60");
	mTimeOut = (unsigned) atoi(pTimeOutStr);

}

//
// Add a new PS search
//
bool clsPSSearches::AddPSSearch(clsPSSearch* pPSSearch)
{
	char		RequestBuf[MAX_REQUEST_LENGTH];
	char*		pReplyData = 0;
	ulong		ReplyDataLength = 0;
	char		RequestType[] = "text/plain";

	char		Query[512];
	const char*	pSearchScope;
	const char* pSearchFrequency;
	char		pPriceRange[30];
	char		pTmpMnemonic[MAX_REQUEST_LENGTH];
	char		pMnemonic[MAX_REQUEST_LENGTH];
	char		pNMQuery[MAX_REQUEST_LENGTH];
	int			i;
	int			j;
	time_t		Today;
// petra	struct tm*	TodayTm;
	char		EndingDate[50];
	clsMarketPlace * mpMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();	// petra

	// replace special charactors in the search string to escaped charactors
	nm_nencode_url((char*)pPSSearch->GetURLForSearch(), Query, sizeof(Query));

	// Get Search scope
	if ((pPSSearch->GetSearchDesc())[0] == 'y' || (pPSSearch->GetSearchDesc())[0] == 'Y')
		pSearchScope = MSG_SEARCH_DESC;
	else
		pSearchScope = MSG_SEARCH_TITLE;

	if ((pPSSearch->GetMaxPrice())[0] == 0 && (pPSSearch->GetMinPrice())[0] == 0)
	{
		strcpy(pPriceRange, "Over $0.00");
	}
	else if ((pPSSearch->GetMaxPrice())[0] == 0)
	{
		sprintf(pPriceRange, "Over $%s", pPSSearch->GetMinPrice());
	}
	else
	{
		sprintf(pPriceRange, "$%s - $%s", pPSSearch->GetMinPrice(), pPSSearch->GetMaxPrice());
	}

	if ((pPSSearch->GetEmailFrequency())[0] == '1')
	{
		pSearchFrequency = MSG_SEARCH_DAILY;
	}
	else
	{
		pSearchFrequency = MSG_SEARCH_THREEDAY;
	}

	// adding \ in font of " in the query string
	j = 0;
	for (i = 0; (pPSSearch->GetQuery())[i] != '\0'; i++)
	{
		if ((pPSSearch->GetQuery())[i] == '"')
		{
			pNMQuery[j++] = '\\';
		}
		pNMQuery[j++] = (pPSSearch->GetQuery())[i];
	}
	pNMQuery[j] = '\0';

	// Get the email ending date
	Today = time(0) + atoi(pPSSearch->GetEmailDuration()) * ONE_DAY;
// petra	TodayTm = localtime(&Today);
// petra	strftime(EndingDate, sizeof(EndingDate)-1, "%B %d, %Y", TodayTm);
// petra I don't have that exact time format in my choice of formats -
// petra so I use Dayname, Monthname day, Year
	clseBayTimeWidget timeWidget (mpMarketPlace,				// petra
								  EBAY_TIMEWIDGET_LONG_DATE,	// petra
								  EBAY_TIMEWIDGET_NO_TIME,		// petra
								  Today);						// petra

	// create mnemonic for netmind
	sprintf(pTmpMnemonic, "\"%s\" \"%s\" \"%s\" \"%s\" \"%s days (ending on %s %s)\"", 
		pNMQuery, 
		pSearchScope, 
		pPriceRange,
		pSearchFrequency,
		pPSSearch->GetEmailDuration(),
		EndingDate,
		mpMarketPlace);	// petra
// petra		(TodayTm->tm_isdst ? "PDT" : "PST"));
// petra I don't really understand - this outputs the date, and the time zone,
// petra but not the time..??

	nm_nencode_url(pTmpMnemonic, pMnemonic, sizeof(pMnemonic));

	// build the request for NetMind responder
	sprintf(RequestBuf, "OPERATION=%s&USERNAME=%s&PASSWORD=%s&FORMAT=false&SILENT=true&URL=%s&NOTICE_DIFF=true&UPDATE_PERIOD=%s&DURATION=%s&MNEMONIC=%s",
							 "add", 
							 pPSSearch->GetEmail(), 
							 TruncatePassword(pPSSearch->GetPassword()),
							 Query, 
							 pPSSearch->GetEmailFrequency(),
							 pPSSearch->GetEmailDuration(),
							 pMnemonic);

	/*
	* Make the request to  responder. The result is placed in replyData.
	*/
	ReplyDataLength = strlen(RequestBuf);
	if (ServerRequest(RequestBuf, 
					  &pReplyData, 
					  &ReplyDataLength)) 
	{
		//Netmind object for translating the return data into name/value pairs.
		NmList nmList;
		if (!list_init_from_string(&nmList, pReplyData)) 
		{
			free(pReplyData);
			return false;
		}
		free(pReplyData);

		//If the operation failed, display the ERROR string in an HTML window.
		if (list_find(&nmList, "ERR"))
		{
			return false;
		}

		return true;
	}


	return false;
}

//
// Delete a PS search
//
bool clsPSSearches::DeletePSSearch(const char *pEmail,
								const char *pPassword,
								const char *pReg)
{
	char		RequestBuf[MAX_STRING];
	char*		pReplyData = 0;
	ulong		ReplyDataLength = 0;
	char		RequestType[] = "text/plain";

	// build the request for NetMind responder
	sprintf(RequestBuf, "OPERATION=%s&USERNAME=%s&PASSWORD=%s&REG=%s",
	                     "cancel", 
						 pEmail,
						 TruncatePassword(pPassword),
						 pReg);

	/*
	* Make the request to responder. The result is placed in replyData.
	*/
	ReplyDataLength = strlen(RequestBuf);
	if (ServerRequest(RequestBuf, 
					  &pReplyData, 
					  &ReplyDataLength)) 
	{
		//Netmind object for translating the return data into name/value pairs.
		NmList nmList;
		if (!list_init_from_string(&nmList, pReplyData)) 
		{
			free(pReplyData);
			return false;
		}
		free(pReplyData);

		//If the operation failed, display the ERROR string in an HTML window.
		if (list_find(&nmList, "ERR")) 
		{
			return false;
		}
	//	RequestRedirect(pCtxt, mpWebServerUrl, ebayProps->getProperty("deleted_url"), none);

		return true;
	}

	return false;
}

//
// change user email and/or password
//
bool clsPSSearches::ChangeEmailPassword(const char* pEmail,
									 const char* pPassword,
									 const char* pNewEmail,
									 const char* pNewPassword)
{
	char		RequestBuf[MAX_STRING];
	char*		pReplyData = 0;
	ulong		ReplyDataLength = 0;
	char		RequestType[] = "text/plain";
	char		ShortPass[16];

	strcpy(ShortPass, TruncatePassword(pPassword));

	// build the request for NetMind responder
	sprintf(RequestBuf, "OPERATION=%s&USERNAME=%s&PASSWORD=%s",
	                     "set", 
						 pEmail, 
						 ShortPass);

	if (pNewEmail && strcmp(pEmail, pNewEmail)) 
	{
		sprintf(RequestBuf + strlen(RequestBuf), "&USER=%s&NEW_DESKTOP_EMAIL=%s", ShortPass, pNewEmail);
		if (pNewPassword && strcmp(pPassword, pNewPassword))
			sprintf(RequestBuf + strlen(RequestBuf), "&NEW_PASSWORD=%s", TruncatePassword(pNewPassword));
	}
	else if (pNewPassword && strcmp(pPassword, pNewPassword))
	{
		sprintf(RequestBuf + strlen(RequestBuf), "&USER=%s&NEW_PASSWORD=%s", ShortPass, TruncatePassword(pNewPassword));		
	}

	/*
	* Make the request to responder. The result is placed in replyData.
	*/
	ReplyDataLength = strlen(RequestBuf);
	if (ServerRequest(RequestBuf, 
					  &pReplyData, 
					  &ReplyDataLength)) 
	{
		//Netmind object for translating the return data into name/value pairs.
		NmList nmList;
		if (!list_init_from_string(&nmList, pReplyData)) 
		{
			free(pReplyData);
			return false;
		}
		free(pReplyData);

		//If the operation failed, display the ERROR string in an HTML window.
		if (list_find(&nmList, "ERR")) 
		{
			return false;
		}

	//	RequestRedirect(pCtxt, mpWebServerUrl, ebayProps->getProperty("updated_url"), none);

		return true;
	}

	return false;
}

//
// Retrieve searches
//
bool clsPSSearches::GetSearches(const char* pEmail, 
							 const char* pPassword, 
							 PSSearchVector* pvSearches)
{
	char		RequestBuf[MAX_STRING];
	char*		pReplyData = 0;
	ulong		ReplyDataLength = 0;
	char		RequestType[] = "text/plain";
	int			i;
	clsPSSearch*	pPSSearch;

	// build the request for NetMind responder
	sprintf(RequestBuf, "OPERATION=%s&USERNAME=%s&PASSWORD=%s&FORMAT=false",
	                     "list", 
						 pEmail, 
						 TruncatePassword(pPassword));

	/*
	* Make the request to responder. The result is placed in replyData.
	*/
	ReplyDataLength = strlen(RequestBuf);
	if (ServerRequest(RequestBuf, 
					  &pReplyData, 
					  &ReplyDataLength)) 
	{
		//Netmind object for translating the return data into name/value pairs.
		SearchList searchList;
		searchList.load(pReplyData);

		// load the search here
		for (i = 0; i < searchList.length(); i++)
		{
			pPSSearch = new clsPSSearch;
			pPSSearch->SetEmail(pEmail);
			pPSSearch->SetURLForSearch(searchList.getUrl(i));
			pPSSearch->SetReg(searchList.getRegID(i));
			pPSSearch->SetEmailFrequency(searchList.getUpdatePeriod(i));
			pPSSearch->SetEmailDuration(searchList.getDuration(i));
			pPSSearch->SetStartingDate(searchList.getDate(i));

			pvSearches->push_back(pPSSearch);
		}

		free(pReplyData);
		return true;
	}
	else
	{
		NmList nmList;
		if (!list_init_from_string(&nmList, pReplyData)) 
		{
			free(pReplyData);
			return false;
		}
		free(pReplyData);

		//If the operation failed, display the ERROR string in an HTML window.
		char* buf = list_find(&nmList, "ERR");
		if (buf) 
		{
			return false;
		}
	}


	return false;

}

//
// modifiy an existing search
//
bool clsPSSearches::ModifyPSSearch(clsPSSearch* pPSSearch)
{
	// delete the exiting one
	if (DeletePSSearch( pPSSearch->GetEmail(), 
						pPSSearch->GetPassword(), 
						pPSSearch->GetReg()))
	{
		if (AddPSSearch(pPSSearch))
		{
			return true;
		}
	}

	return false;

}

//
// Server Request
//
bool clsPSSearches::ServerRequest(const char* pRequest, 
							   char ** ppReply, 
							   ulong *pReplyLength) 
{

    CUrl url(mTimeOut);

	// set params
	url.SetLocation(mpResponderUrl);
	url.SetMethod("POST", "text/plain", pRequest, *pReplyLength);
	url.SetUserAgent("Netmind/Custom-Client V1.0");
	url.SetAcceptEncoding("application/x-www-form-urlencoded");

	// remove it when it is in production
	//url.SetProxyServerLocation("http://209.1.128.86");

	if(!url.Invoke(ppReply, pReplyLength)) 
	{
		delete [] mpServerRequestErrorMessage;
		mpServerRequestErrorMessage = new char [strlen(url.GetError().major) + 1];
		strcpy(mpServerRequestErrorMessage, url.GetError().major);
		EDEBUG('*', "Personal Shopper (Error: %s, Request: %s)", mpServerRequestErrorMessage, pRequest);
		return false;
	}

	return true;
}

// convert the password to less than 16 bytes
const char* clsPSSearches::TruncatePassword(const char* pPassword)
{
	strncpy(mTruncatedPassword, pPassword, 15);
	mTruncatedPassword[15] = '\0';

	return mTruncatedPassword;
}
