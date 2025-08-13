// THUMBSERVE.CPP - Implementation file for your Internet Server
//    ThumbServe Extension

#include "stdafx.h"
#include "ThumbServe.h"
#include "clsThumbDB.h"
#include "clsSynchronize.h"
#include "clsDirectoryWalker.h"
#include <sys/stat.h>

///////////////////////////////////////////////////////////////////////
// The one and only CWinApp object
// NOTE: You may remove this object if you alter your project to no
// longer use MFC in a DLL.

CWinApp theApp;

static const char* gCurrentThumbDBDir = "c:\\InUse\\";
static const char* gNewThumbDBDir = "c:\\thumbdb\\";
static const char* gStockImage = "c:\\StockImage1.jpg";
static const int kCheckForNewThumbDBFrequency = 60 * 1; // Seconds * minutes

// Sample url to talk to this dll
// http://marty/aw-cgi/thumbServe.dll?Thumb?item=26211021

///////////////////////////////////////////////////////////////////////
// command-parsing map

BEGIN_PARSE_MAP(CThumbServeExtension, CHttpServer)
	// TODO: insert your ON_PARSE_COMMAND() and 
	// ON_PARSE_COMMAND_PARAMS() here to hook up your commands.
	// For example:

	ON_PARSE_COMMAND(Default, CThumbServeExtension, ITS_EMPTY)
	DEFAULT_PARSE_COMMAND(Default, CThumbServeExtension)

	ON_PARSE_COMMAND(Head, CThumbServeExtension, ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item")

	ON_PARSE_COMMAND(ModifiedSince, CThumbServeExtension, ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item modifiedsince")

	ON_PARSE_COMMAND(Thumb, CThumbServeExtension, ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item")

	ON_PARSE_COMMAND(SwitchDB, CThumbServeExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("switchDB")

	ON_PARSE_COMMAND(MakeNoisy, CThumbServeExtension, ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item")

	ON_PARSE_COMMAND(MakeUnNoisy, CThumbServeExtension, ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item")

	ON_PARSE_COMMAND(GetNoisyThumbCount, CThumbServeExtension, ITS_EMPTY)
	DEFAULT_PARSE_COMMAND(Default, CThumbServeExtension)

END_PARSE_MAP(CThumbServeExtension)


///////////////////////////////////////////////////////////////////////
// The one and only CThumbServeExtension object

CThumbServeExtension theExtension;

static clsGroupThumbDB* gCurrentThumbDB = NULL;
static time_t gLastCheckForNewDB = 0;
//static char gCurrentThumbDBName[64];
static clsSynchronizeable* gSyncThumbDB = NULL;
static clsSynchronizeable* gSyncNoisyThumbs = NULL;
static bool gSwitchDB = true;


///////////////////////////////////////////////////////////////////////
// Supporting routines

int DeleteDirectoryContents(const char* directory)
{
	clsDirectoryWalker directoryWalker(directory, "*.map");

	while (directoryWalker.GetNextItem())
	{
		char fullPath[1024];
		strcpy(fullPath, directory);
		strcat(fullPath, directoryWalker.GetName());

		int removeResult = remove(fullPath);		
		if (removeResult)
			return removeResult;
	}

	return 0;
}

int DeleteDirectory(const char* directory)
{
	struct _stat statBuf;
	char delDir[512];

	strcpy(delDir, directory);
	if (delDir[strlen(delDir)-1] == '\\')
		delDir[strlen(delDir)-1] = '\0';

	int statResult = _stat(delDir, &statBuf);
	if (statResult)
		return 0;

	int deleteContentsResult = DeleteDirectoryContents(directory);
	if (deleteContentsResult)
		return deleteContentsResult;

	// Delete the directory
	char commandString[512];

	strcpy(commandString, "rmdir ");
	strcat(commandString, delDir);
	int systemReturnValue = system(commandString);
	if (systemReturnValue == 2)
		systemReturnValue = 0;

	return systemReturnValue;
}

bool DoesDirectoryExist(const char* directory)
{
	struct _stat statBuf;
	char newDir[512];

	strcpy(newDir, directory);
	if (newDir[strlen(newDir)-1] == '\\')
		newDir[strlen(newDir)-1] = '\0';

	return ! _stat(newDir, &statBuf);
}


void CThumbServeExtension::NewDBCheck()
{
	if (gSwitchDB)
	{
		gLastCheckForNewDB = time(NULL);

		if (!DoesDirectoryExist(gNewThumbDBDir)
			&& DoesDirectoryExist(gCurrentThumbDBDir)
			&& !gCurrentThumbDB)
		{
			try
			{
				gCurrentThumbDB = new clsGroupThumbDB(gCurrentThumbDBDir, true, gStockImage);
				gCurrentThumbDB->Open();
			}
			catch(...)
			{
				gCurrentThumbDB = NULL;
			}

			gSwitchDB = false;
			return;
		}

		try
		{
			if (!DoesDirectoryExist(gNewThumbDBDir))
				return;

			delete gCurrentThumbDB;
			gCurrentThumbDB = NULL;

			int deleteResult = DeleteDirectory(gCurrentThumbDBDir);
			if (deleteResult)
				return;

			// Rename the latest backup
			char oldName[512];
			char newName[512];

			strcpy(oldName, gNewThumbDBDir);
			if (oldName[strlen(oldName)-1] == '\\')
				oldName[strlen(oldName)-1] = '\0';

			strcpy(newName, gCurrentThumbDBDir);
			if (newName[strlen(newName)-1] == '\\')
				newName[strlen(newName)-1] = '\0';

			int moveResult = rename(oldName, newName);
			if (moveResult)
				throw moveResult;

			gCurrentThumbDB = new clsGroupThumbDB(gCurrentThumbDBDir, true, gStockImage);
			gCurrentThumbDB->Open();
		}
		catch(int error)
		{
			int i = error;
		//	cout << "Failed opening " << newestThumbDB << " - err " << error;
		}
		catch(...)
		{
			int i = GetLastError();
		}

	}

	gSwitchDB = false;
}


const char* const CThumbServeExtension::GetThumb(int itemID, int& size)
{
	unsigned long lockResult = gSyncThumbDB->Lock(1000*10); // Wait up to 10 seconds for a lock
	if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
		return NULL;

	NewDBCheck();

	if (!gCurrentThumbDB)
		return NULL;

	long lockResultNoisy = gSyncNoisyThumbs->Lock(1000*10); // Wait up to 10 seconds for a lock
	if (lockResultNoisy == WAIT_FAILED || lockResultNoisy == WAIT_TIMEOUT)
		return NULL;

	const char* imageData = gCurrentThumbDB->GetThumb(itemID, size);

	gSyncNoisyThumbs->Unlock();

	gSyncThumbDB->Unlock();

	return imageData;
}

///////////////////////////////////////////////////////////////////////
// CThumbServeExtension implementation

CThumbServeExtension::CThumbServeExtension()
{
	if (!gSyncThumbDB)
	{
		gSyncThumbDB = new clsSynchronizeable();
	}

	long lockResult = gSyncThumbDB->Lock(1000*10); // Wait up to 10 seconds for a lock
	if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
		return;

	NewDBCheck();

	gSyncThumbDB->Unlock();

	// Noisy sync object
	if (!gSyncNoisyThumbs)
	{
		gSyncNoisyThumbs = new clsSynchronizeable();
	}
}

CThumbServeExtension::~CThumbServeExtension()
{
	// mlh 8/19/98 someone need to clean up gSyncThumbDB
	//   will probably have to keep a refcount on the threads
	//   to know when to delete
	// mlh 10/13/98 because this is only called when IIS is shutdown, we are going
	//  to ignore the memory leak of gSyncThumbDB
}

BOOL CThumbServeExtension::GetExtensionVersion(HSE_VERSION_INFO* pVer)
{
	// Call default implementation for initialization
	CHttpServer::GetExtensionVersion(pVer);

	// Load description string
	TCHAR sz[HSE_MAX_EXT_DLL_NAME_LEN+1];
	ISAPIVERIFY(::LoadString(AfxGetResourceHandle(),
			IDS_SERVER, sz, HSE_MAX_EXT_DLL_NAME_LEN));
	_tcscpy(pVer->lpszExtensionDesc, sz);
	return TRUE;
}

///////////////////////////////////////////////////////////////////////
// CThumbServeExtension command handlers

void CThumbServeExtension::Default(CHttpServerContext* pCtxt)
{
	StartContent(pCtxt);
	WriteTitle(pCtxt);

	*pCtxt << _T("This default message was produced by the Internet");
	*pCtxt << _T(" Server DLL Wizard. Edit your CThumbServeExtension::Default()");
	*pCtxt << _T(" implementation to change it.\r\n");

	EndContent(pCtxt);
}

//static const TCHAR szResponse[] = _T("HTTP/1.0 200 OK\r\n");
//static const TCHAR szServer[] = _T("Server: Microsoft-IIS/3.0\r\n");
static const TCHAR szContentType[] = _T("Content-Type: image/jpeg\r\n");
//static const TCHAR szContentLength[] = _T("Content-Length: ");

int CThumbServeExtension::WriteHeader(CHttpServerContext* pCtxt, int contentLength)
{
	// 70 minute expire, for the Expires header.
	time_t theTime = time(NULL) + 70 * 60;
	char expireTime[64];
	struct tm *pTime = gmtime(&theTime);
	strftime(expireTime, sizeof(expireTime), "%a, %d %b %Y %H:%M:%S GMT", pTime);

	// For the Last-Modified field.
	char lastModifiedTime[64];
	theTime = time(NULL);
	pTime = gmtime(&theTime);
	strftime(lastModifiedTime, sizeof(lastModifiedTime), "%a, %d %b %Y %H:%M:%S GMT", pTime);

	char buf[512];

    sprintf(buf, 
		"Content-Type: image/jpeg\r\n"
        "Expires: %s\r\n"
        "Last-Modified: %s\r\n"
        "Content-Length: %d\r\n",
        expireTime,
        lastModifiedTime,
		contentLength);

	AddHeader(pCtxt, buf);

	return 0;
}

int CThumbServeExtension::Thumb(CHttpServerContext* pCtxt, int item)
{
	int size;
	
#if 0
	vector<int>::iterator j;

	// is this item considered noisy?
	j = find(mNoisyThumbs.begin(), mNoisyThumbs.end(), item);
	if (j != mNoisyThumbs.end()) {
		// if we found it, just return
		size = gCurrentThumbDB->mMissingImageSize;
		image = gCurrentThumbDB->mMissingImage;
	} else
#endif

	const char* const image = GetThumb(item, size);

	if (image)
	{
		WriteHeader(pCtxt, size);

		CHtmlStream data(reinterpret_cast<unsigned char*>(const_cast<char*>(image)), size);
		*pCtxt << data;
	}

	return callOK;
}

int CThumbServeExtension::SwitchDB(CHttpServerContext* pCtxt, const char* /*newDB*/)
{
	AddHeader(pCtxt, szContentType);
//	StartContent(pCtxt);
	WriteTitle(pCtxt);

	gSwitchDB = true;

	long lockResult = gSyncThumbDB->Lock(1000*1000); // Wait up to 1000 seconds for a lock
	if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
		return callOK;

	NewDBCheck();

	gSyncThumbDB->Unlock();

	EndContent(pCtxt);

	return callOK;
}

int CThumbServeExtension::Head(CHttpServerContext* pCtxt, int item)
{
	int size;
	const char* const image = GetThumb(item, size);

	if (image)
	{
		WriteHeader(pCtxt, size);
	}

	return callOK;
}

int CThumbServeExtension::ModifiedSince(CHttpServerContext* pCtxt, int item, const char* date)
{
	return Thumb(pCtxt, item);
}

//
// akin to making this thumb a "noise" word. don't display it if asked.
// here we'll add this Id to a vector of other noisy photos. if the DB object is reinitalized
// or the server is restarted, the vector will go back to being empty
//
// we actually get here via a redirect from the ISAPI ItemInfo admin function
//
int CThumbServeExtension::MakeNoisy(CHttpServerContext* pCtxt, int item)
{
#if 0
	mNoisyThumbs.push_back(item);
#endif
	
	char str[255];
	int thumbCount;
	
	// add item to vector
	if(gCurrentThumbDB) {
		long lockResult = gSyncNoisyThumbs->Lock(1000*10); // Wait up to 10 seconds for a lock
		if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
			return -1;
		
		gCurrentThumbDB->MakeNoisy(item);
		
		thumbCount = gCurrentThumbDB->NoisyThumbCount();
		
		gSyncNoisyThumbs->Unlock();
		
		// notify admin of what just happened
		StartContent(pCtxt);
		WriteTitle(pCtxt);
		
		*pCtxt << _T("The Gallery picture for item ");
		sprintf(str, "%d has been removed.\r\n", item);	
		*pCtxt << _T(str);
		sprintf(str, "<br>The current noisy thumb count is: %d.\r\n", thumbCount);	
		*pCtxt << _T(str);
		
		*pCtxt << _T("<br><br>Press the back button on your browser to continue.");
		
		//	this->EbayRedirect(pCtxt, newURL);
		
		EndContent(pCtxt);	
	}
	
	return callOK;
}

//
// akin to making this thumb a "noise" word. don't display it if asked.
// here we'll remove this Id from the vector of other noisy photos. if the DB object is reinitalized
// or the server is restarted, the vector will go back to being empty
//
// we actually get here via a redirect from the ISAPI ItemInfo admin function
//
int CThumbServeExtension::MakeUnNoisy(CHttpServerContext* pCtxt, int item)
{	
	char str[255];
	int result = 0, thumbCount;
	
	// add item to vector
	if(gCurrentThumbDB) {
		// notify admin of what just happened
		StartContent(pCtxt);
		WriteTitle(pCtxt);
		
		// sync
		long lockResult = gSyncNoisyThumbs->Lock(1000*10); // Wait up to 10 seconds for a lock
		if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
			return -1;
		
		result = gCurrentThumbDB->MakeUnNoisy(item);
		
		thumbCount = gCurrentThumbDB->NoisyThumbCount();
		
		gSyncNoisyThumbs->Unlock();
		
		if(result == 0) {
			
			*pCtxt << _T("The Gallery picture for item ");
			sprintf(str, "%d has been removed from the Galley noise pool. This item will now be visible.\r\n", item);	
			*pCtxt << _T(str);
			sprintf(str, "<br>The current noisy thumb count is: %d.\r\n", thumbCount);	
			*pCtxt << _T(str);
			*pCtxt << _T("<br><br>Press the back button on your browser to continue.");
			
			//	this->EbayRedirect(pCtxt, newURL);
			
		} else {
			*pCtxt << _T("The Gallery picture for item ");
			sprintf(str, "%d could not be found in the noise pool.\r\n", item);	
			*pCtxt << _T(str);
			sprintf(str, "<br>The current noisy thumb count is: %d.\r\n", thumbCount);	
			*pCtxt << _T(str);
			*pCtxt << _T("<br><br>Press the back button on your browser to continue.");
		}
		EndContent(pCtxt);		
	}
	return callOK;
}

// returns current count of noisy thumbnails
int CThumbServeExtension::GetNoisyThumbCount(CHttpServerContext* pCtxt)
{
	char str[255];
	int thumbCount;
	
	if(gCurrentThumbDB) {
		long lockResult = gSyncNoisyThumbs->Lock(1000*10); // Wait up to 10 seconds for a lock
		if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
			return -1;
		
		thumbCount = gCurrentThumbDB->NoisyThumbCount();
		
		gSyncNoisyThumbs->Unlock();
		
		// notify admin of what just happened
		StartContent(pCtxt);
		WriteTitle(pCtxt);
		sprintf(str, "<br>The current noisy thumb count is: %d.\r\n", thumbCount);	
		*pCtxt << _T(str);
		*pCtxt << _T("<br><br>Press the back button on your browser to continue.");
		EndContent(pCtxt);		
	}
	
	return callOK;
}

// CURRENTLY UNUSED & UNTESTED!!!
// Issues a redirect command to the IIS server. For some reason,
//  IIS4's HSE_REQ_SEND_URL_REDIRECT_RESP doesn't work, so we
//  use the more general HSE_REQ_SEND_RESPONSE_HEADER instead.
// Returns whether or not successful
int CThumbServeExtension::EbayRedirect(CHttpServerContext *pCtxt, const char* pURL)
{
	char	pEntireCommand[1024];
	unsigned long length;

	// safety
	if ((!pCtxt) || (!pURL)) return false;

	// create the command
	sprintf(pEntireCommand, "Location: %s\r\n", pURL);
	length = strlen(pEntireCommand);

	// do it
	return pCtxt->ServerSupportFunction(HSE_REQ_SEND_RESPONSE_HEADER, 
		"302 Object Moved", &length, (unsigned long *) pEntireCommand);
}

// Do not edit the following lines, which are needed by ClassWizard.
#if 0
BEGIN_MESSAGE_MAP(CThumbServeExtension, CHttpServer)
	//{{AFX_MSG_MAP(CThumbServeExtension)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()
#endif	// 0




///////////////////////////////////////////////////////////////////////
// The one and only CThumbServeFilter object

CThumbServeFilter theFilter;


///////////////////////////////////////////////////////////////////////
// CThumbServeFilter implementation

CThumbServeFilter::CThumbServeFilter()
{
}

CThumbServeFilter::~CThumbServeFilter()
{
}

BOOL CThumbServeFilter::GetFilterVersion(PHTTP_FILTER_VERSION pVer)
{
	// Check for good pointers.
	if (IsBadReadPtr(pVer, sizeof(HTTP_FILTER_VERSION)) ||
		IsBadWritePtr(pVer, sizeof(HTTP_FILTER_VERSION)) ||
		IsBadWritePtr(pVer->lpszFilterDesc, SF_MAX_FILTER_DESC_LEN))
		return FALSE;

	// Call default implementation for initialization
	CHttpFilter::GetFilterVersion(pVer);

	// Clear the flags set by base class
	pVer->dwFlags &= ~SF_NOTIFY_ORDER_MASK;

	// Set the flags we are interested in
	pVer->dwFlags |= SF_NOTIFY_ORDER_MEDIUM | SF_NOTIFY_SECURE_PORT | SF_NOTIFY_NONSECURE_PORT
			 | SF_NOTIFY_PREPROC_HEADERS;

	// Load description string
	TCHAR sz[SF_MAX_FILTER_DESC_LEN+1];
	ISAPIVERIFY(::LoadString(AfxGetResourceHandle(),
			IDS_FILTER, sz, SF_MAX_FILTER_DESC_LEN));
	_tcscpy(pVer->lpszFilterDesc, sz);
	return TRUE;
}

DWORD CThumbServeFilter::OnPreprocHeaders(CHttpFilterContext* pCtxt,
	PHTTP_FILTER_PREPROC_HEADERS pHeaders)
{
	// TODO: React to this notification accordingly and
	// return the appropriate status code

	// http://marty.corp.ebay.com/aw-cgi/thumbServe.dll?Thumb?item=35087490

	// A big buffer for getting the header.
	// If it's longer than that, they don't want what we have anyway.
	char buffer[1024];
	unsigned long length;

	// Make sure we have good addresses.
	if (IsBadReadPtr(pCtxt->m_pFC, sizeof (HTTP_FILTER_CONTEXT)) ||
		IsBadWritePtr(pHeaders, sizeof (HTTP_FILTER_PREPROC_HEADERS)) ||
		IsBadReadPtr(pHeaders, sizeof (HTTP_FILTER_PREPROC_HEADERS)))
		return SF_STATUS_REQ_ERROR;

	// Grab the url from the header.
	length = sizeof (buffer);
	pHeaders->GetHeader(pCtxt->m_pFC, "url", buffer, &length);

	char* updateSectionOfURL = strstr(buffer, "SwitchDB");
	if (updateSectionOfURL)
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	char* noisySectionOfURL = strstr(buffer, "MakeNoisy");
	if (noisySectionOfURL)
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	char* UnNoisySectionOfURL = strstr(buffer, "MakeUnNoisy");
	if (UnNoisySectionOfURL)
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	char* GetNoisySectionOfURL = strstr(buffer, "GetNoisy");
	if (GetNoisySectionOfURL)
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	char* pictSectionOfURL = strstr(buffer, "/pict/");
	if (!pictSectionOfURL)
		// this will cause HTTP server errors for any other server acces. we don't play well with others. :)
//		return SF_STATUS_REQ_ERROR; replace for checkin!!!!
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	long itemID = atol(pictSectionOfURL + strlen("/pict/"));
	if (!itemID)
		return SF_STATUS_REQ_ERROR;

	length = sizeof (buffer);
	*buffer = '\0';
	pHeaders->GetHeader(pCtxt->m_pFC, "method", buffer, &length);
	if (!strcmpi(buffer, "head"))
	{
		try
		{
			sprintf(buffer, "/ed/thumbServe.dll?Head&item=%ld", itemID);
			pHeaders->SetHeader(pCtxt->m_pFC, "url", buffer);

			return SF_STATUS_REQ_HANDLED_NOTIFICATION;
		}
		catch (...)
		{
			return SF_STATUS_REQ_FINISHED;
		}
	}
	else
	{
		char modifiedDate[128];
		*modifiedDate = '\0';

		length = sizeof(modifiedDate);
		pHeaders->GetHeader(pCtxt->m_pFC, "If-Modified-Since:", modifiedDate, &length);
		
		if (length && *modifiedDate)
		{
			try
			{
				sprintf(buffer, "/ed/thumbServe.dll?ModifiedSince&item=%ld&modifiedsince=%s", itemID, modifiedDate);
				pHeaders->SetHeader(pCtxt->m_pFC, "url", buffer);

				return SF_STATUS_REQ_HANDLED_NOTIFICATION;
			}
			catch (...)
			{
				return SF_STATUS_REQ_FINISHED;
			}
		}
	}

    try
    {
		sprintf(buffer, "/ed/thumbServe.dll?Thumb&item=%ld", itemID);
		pHeaders->SetHeader(pCtxt->m_pFC, "url", buffer);

		return SF_STATUS_REQ_HANDLED_NOTIFICATION;
    }
    catch (...)
    {
        return SF_STATUS_REQ_FINISHED;
    }
	
	return SF_STATUS_REQ_NEXT_NOTIFICATION;
}

// Do not edit the following lines, which are needed by ClassWizard.
#if 0
BEGIN_MESSAGE_MAP(CThumbServeFilter, CHttpFilter)
	//{{AFX_MSG_MAP(CThumbServeFilter)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()
#endif	// 0


///////////////////////////////////////////////////////////////////////
// If your extension will not use MFC, you'll need this code to make
// sure the extension objects can find the resource handle for the
// module.  If you convert your extension to not be dependent on MFC,
// remove the comments arounn the following AfxGetResourceHandle()
// and DllMain() functions, as well as the g_hInstance global.

/****

static HINSTANCE g_hInstance;

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

****/
