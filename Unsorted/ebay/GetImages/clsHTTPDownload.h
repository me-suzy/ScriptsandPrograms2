/* $Id: clsHTTPDownload.h,v 1.2 1999/02/21 02:22:22 josh Exp $ */
//
// File: clsHTTPDownload
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: For ansynchronously downloading objects specified by
//	an URL. Currently it only supports http.
//

#ifndef clsHTTPDownload_h
#define clsHTTPDownload_h

// This changes the maximum items in an fdset
// This must be defined before winsock.h
#ifdef _MSC_VER
#pragma warning(disable : 4005) // warning C4005: 'FD_SETSIZE' : macro redefinition
#endif
#define FD_SETSIZE      512
#include <winsock.h>
#ifdef _MSC_VER
#pragma warning(default : 4005) // turn it back on
#endif
#include <ostream>
#include "clsGalleryChangedItem.h"

//using namespace std;

struct clsHTTPParser;

struct clsHTTPDownloadStats
{
	int mContinue;
	int mSwitchingProtocols;
	int mOK;
	int mCreated;
	int mAccepted;
	int mNonAuthoritativeInformation;
	int mNoContent;
	int mResetContent;
	int mPartialContent;
	int mMultipleChoices;
	int mMovedPermanently;
	int mMovedTemporarily;
	int mSeeOther;
	int mNotModified;
	int mUseProxy;
	int mBadRequest;
	int mUnauthorized;
	int mPaymentRequired;
	int mForbidden;
	int mNotFound;
	int mMethodNotAllowed;
	int mNotAcceptable;
	int mProxyAuthenticationRequired;
	int mRequestTimeOut;
	int mConflict;
	int mGone;
	int mLengthRequired;
	int mPreconditionFailed;
	int mRequestEntityTooLarge;
	int mRequestURITooLarge;
	int mUnsupportedMediaType;
	int mInternalServerError;
	int mNotImplemented;
	int mBadGateway;
	int mServiceUnavailable;
	int mGatewayTimeOut;
	int mHTTPVersionNotSupported;
	int mUnknown;

	int mFailedConnection;
	int mFailedReading;
	int mFailedDNS;
	int mFailedSendRequest;
	int mFailedGet;
	int mFailedRedirect;
	int mFailedWrittingFile;
	int mServerDisconnect;
	int mSuccess;
	int mTotal;

	clsHTTPDownloadStats();
	void Reset();
	void AddStatusResult(int httpStatusCode);
	void AddFailedDNS()
		{ ++mFailedDNS; ++mTotal; }
	void AddFailedConnection()
		{ ++mFailedConnection; ++mTotal; }
	void AddFailedSendRequest()
		{ ++mFailedConnection; ++mTotal; }
	void AddFailedGet()
		{ ++mFailedGet; ++mTotal; }
	void AddFailedReading()
		{ ++mFailedReading; ++mTotal; }
	void AddFailedWrittingFile()
		{ ++mFailedWrittingFile; ++mTotal; }
	void AddServerDisconnect()
		{ ++mFailedWrittingFile; ++mTotal; }
	void AddFailedRedirect()
		{ ++mFailedRedirect; ++mTotal; }

	void AddSuccess()
		{ ++mSuccess; ++mTotal; }
};

class clsHTTPCompletion
{
public:
	virtual void Complete(int result, int httpResult, const char* location, int callbackParam) = 0;
};

class clsHTTPDownload
{
public:

	enum {
		kDefaultMaxSimultaneousDownloads = 16,
		kDefaultMaxReadWaitTime = 30, // sec
		kDefaultMaxSelectWaitTime = 100, // usec
		kDefaultMaxConnectWaitTime = 30, // sec
		kDefaultMaxRequestWaitTime = 30, // sec
		kDefaultMaxRedirects = 3 // The maximum number of times we will redirect before giving up
	};

	struct Options
	{
		int mMaxSimultaneousDownloads;
		int mSelectWaitTime;				// usec
		int mMaxConnectWaitTime;			// sec
		int mMaxRequestWaitTime;			// sec
		int mMaxReadWaitTime;			// sec
		int mMaxRedirects;				// The maximum number of times we will redirect before giving up
	};

	static int Startup(Options& option);
	static int Shutdown();
	static void SetOptions(Options& options)
	{ 
		mOptions = options; 
	}
	static bool Cycle();
	static int GetActiveCount();
	static int StartDownload(const char* trueHostName, const char* hostName, const char* item, const char* destination, const char* failMessage, int callbackParam);
	static int IsReady();
	static void SetFailStream(std::ostream* failStream) 
	{ 
		mFailLog = failStream; 
	}

	static void SetCompletionRoutine(clsHTTPCompletion* completion)
	{
		mCompletion = completion;
	}

	static clsHTTPDownloadStats gHTTPDownloadStats;

private:
	clsHTTPDownload(const char* trueHostName, const char* hostName, const char* item, const char* destination, const char* failMessage, int callbackParam);
	~clsHTTPDownload();

	void* operator new(size_t size);
	void operator delete(void* item);

	void SetError(const char* errorStr, int errorNum, GalleryResultCode code);
	void SendRequest();

	void EnterConnecting();
	void DoConnecting();
	void EnterRequesting();
	void DoRequesting();
	char* FindHeaderEnd(char* header, int maxChars);
	void EnterRedirect(clsHTTPParser& parser);
	void EnterReading();
	void DoReading();
	void EnterDone();

	void WritePayload();

	enum {
		kMaxHostName = 255,
		kMaxItemName = 255,
		kMaxDestinationName = 255,
		kMaxErrorString = 255 + kMaxItemName, // Leave room for a long item name
		kMaxFailMessage = 1024,
		kMaxReadBufferSize = 1024*128,

	};

	enum State {
		kRaw,
		kConnecting,
		kRequesting,
		kReading,
		kDone,
		kError
	};

	char mTrueHostName[kMaxHostName+1];
	char mHostName[kMaxHostName+1];
	char mItemName[kMaxItemName+1];
	char mDestinationName[kMaxDestinationName+1];
	char mErrorString[kMaxErrorString+1];
	State mState;
	char mBuffer[kMaxReadBufferSize];
	clsHTTPDownload* mNext;
	clsHTTPDownload* mPrior;
	int mTotalToRead;
	int mReadSoFar;
	time_t mLastActionTime;
	sockaddr mAddress;
	SOCKET mSock;
	int mReadPos;
	int mWritePos;
	int mPayloadSize;
	int mPayloadWritten;
	FILE* mOutputFile;
	char mFailMessage[kMaxFailMessage+1];
	int mRedirectCount; // Number of times we have been redirected
	int mCallbackParam;

	static Options mOptions;
	static clsHTTPDownload* mFreeHead;
//	static clsHTTPDownload* mUsedHead;
	static clsHTTPDownload* mItemStorage;
	static fd_set mReadSetIn;
	static fd_set mWriteSetIn;
	static fd_set mReadSetOut;
	static fd_set mWriteSetOut;
	static bool mInited;
	static std::ostream* mFailLog;
	static clsHTTPCompletion* mCompletion;

	
};

#endif // clsHTTPDownload_h