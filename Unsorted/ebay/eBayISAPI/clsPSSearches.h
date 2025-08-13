/*	$Id: clsPSSearches.h,v 1.2 1999/05/19 02:34:14 josh Exp $	*/
//
//	File:	clsPSSearches.h
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
//

#ifndef CLSPSSEARCHES_INCLUDE
#define CLSPSSEARCHES_INCLUDE

#include "common.h"
#include "clsPSSearch.h"

class clsPSSearches
{
public:
	clsPSSearches(){ mpServerRequestErrorMessage = NULL;}
	~clsPSSearches(){delete mpServerRequestErrorMessage;}

	void SetProps();

	bool AddPSSearch(clsPSSearch* pPSSearch);

	bool DeletePSSearch(const char *pEmail,
						const char *pPassword,
						const char *pReg);

	bool ChangeEmailPassword(const char* pEmail,
							 const char* pPassword,
							 const char* pNewEmail,
							 const char* pNewPassword);

	bool GetSearches(const char* pEmail, const char* pPassword, PSSearchVector* pvSearches);

	bool ModifyPSSearch(clsPSSearch* pPSSearch);

	const char * GetErrorMessage() { return mpServerRequestErrorMessage; }

protected:
	bool ServerRequest(const char*	pRequest, 
					   char **		ppReply, 
					   unsigned long *pReplyLength);

	const char* TruncatePassword(const char* pPassword);

	NMProps	mProps;
	char *	mpNonceSecret;
	char *	mpResponderUrl;
	char *	mpWebServerUrl;
	int		mTimeOut;
	char	mTruncatedPassword[16];
	char*	mpServerRequestErrorMessage;

};

#endif //CLSPSSEARCHES_INCLUDE