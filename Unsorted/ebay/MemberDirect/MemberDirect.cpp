/*	$Id: MemberDirect.cpp,v 1.4 1999/02/21 02:23:29 josh Exp $	*/
// MEMBERDIRECT.CPP - Implementation file for your Internet Server
//    MemberDirect Filter
#define STRICT
#include <windows.h>
#include <HttpFilt.h>
#include "MemberDirect.h"

#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include <stdlib.h>
#include <stdio.h>
#include <crtdbg.h>

static const char *sBaseURL = "/aw-cgi/eBayISAPI.dll?ViewUserPage&userid=";
static const char *sBasePassURL = "/aw-cgi/eBayISAPI.dll?ChangeSecretPassword&pass=";

BOOL WINAPI GetFilterVersion(PHTTP_FILTER_VERSION pVer)
{
	// Check for good pointers.
	if (IsBadReadPtr(pVer, sizeof(HTTP_FILTER_VERSION)) ||
		IsBadWritePtr(pVer, sizeof(HTTP_FILTER_VERSION)) ||
		IsBadWritePtr(pVer->lpszFilterDesc, SF_MAX_FILTER_DESC_LEN))
		return FALSE;

	// Set up stuff here.
	pVer->dwFilterVersion = HTTP_FILTER_REVISION;

	// Our name.
	lstrcpyn(pVer->lpszFilterDesc,
			 "Member Direct Filter",
			 SF_MAX_FILTER_DESC_LEN);

	// And our notifications.
	pVer->dwFlags = SF_NOTIFY_ORDER_MEDIUM |
					SF_NOTIFY_SECURE_PORT |
					SF_NOTIFY_NONSECURE_PORT |
					SF_NOTIFY_PREPROC_HEADERS;

	return TRUE;
}

DWORD WINAPI HttpFilterProc(PHTTP_FILTER_CONTEXT pFC, DWORD notificationType, LPVOID pvNotification)
{
	// A big buffer for getting the header.
	// If it's longer than that, they don't want what we have anyway.
	char buffer[256];
	char build_buffer[1024];
	char save_buffer[256];
	char member_name[256];
	char *pUrl;
	unsigned long length;
	PHTTP_FILTER_PREPROC_HEADERS pHeaders;

	// We are _only_ interested in preprocessing the headers.
	if (notificationType != SF_NOTIFY_PREPROC_HEADERS)
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	// Otherwise, cast to headers.
	pHeaders = (PHTTP_FILTER_PREPROC_HEADERS) pvNotification;

	// Make sure we have good addresses.
	if (IsBadReadPtr(pFC, sizeof (HTTP_FILTER_CONTEXT)) ||
		IsBadWritePtr(pHeaders, sizeof (HTTP_FILTER_PREPROC_HEADERS)) ||
		IsBadReadPtr(pHeaders, sizeof (HTTP_FILTER_PREPROC_HEADERS)))
		return SF_STATUS_REQ_ERROR;

	// Grab the url from the header.
	length = sizeof (buffer);
	pHeaders->GetHeader(pFC, "url", buffer, &length);
	strcpy(save_buffer, buffer);

	// lowercase the string.
	for (pUrl = buffer; *pUrl; ++pUrl)
		*pUrl = tolower(*pUrl);

	pUrl = buffer;
	if (*pUrl != '/')
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	if (!strncmp(pUrl, "/aboutme/", strlen("/aboutme/")))
	{
		pUrl += strlen("/aboutme/");

		strcpy(member_name, pUrl);

		(void) strtok(member_name, "/");

		sprintf(build_buffer, "%s%s", sBaseURL, member_name);
		pHeaders->SetHeader(pFC, "url", build_buffer);
		return SF_STATUS_REQ_HANDLED_NOTIFICATION;
	}

	if (!strncmp(pUrl, "/aw-cgi/pass/", strlen("/aw-cgi/pass/")))
	{
		char crypted_pass[256];
		pUrl += strlen("/aw-cgi/pass/");

		// Get the mixed-caps version.
		strcpy(crypted_pass, save_buffer + (pUrl - buffer));

		// If we've got our final letter, truncate it.
		if (crypted_pass[strlen(crypted_pass) - 1] == 'a')
			crypted_pass[strlen(crypted_pass) - 1] = '\0';

		sprintf(build_buffer, "%s%s", sBasePassURL, crypted_pass);
		pHeaders->SetHeader(pFC, "url", build_buffer);
		return SF_STATUS_REQ_HANDLED_NOTIFICATION;
	}

	return SF_STATUS_REQ_NEXT_NOTIFICATION;
}

///////////////////////////////////////////////////////////////////////
// If your extension will not use MFC, you'll need this code to make
// sure the extension objects can find the resource handle for the
// module.  If you convert your extension to not be dependent on MFC,
// remove the comments arounn the following AfxGetResourceHandle()
// and DllMain() functions, as well as the g_hInstance global.


static HINSTANCE g_hInstance;

#undef STRICT
#include "afxisapi.h"

HINSTANCE AFXISAPI AfxGetResourceHandle()
{
	return g_hInstance;
}

BOOL WINAPI DllMain(HINSTANCE hInst, ULONG ulReason,
					LPVOID lpReserved)
{
	if (ulReason == DLL_PROCESS_ATTACH)
	{
		g_hInstance = hInst;
	}

	return TRUE;
}

