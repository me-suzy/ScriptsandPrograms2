/* $Id: clsHTTPDownload.cpp,v 1.2 1999/02/21 02:22:21 josh Exp $ */
//
// File: clsHTTPDownload
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: See .h for description.
//

#define FD_SETSIZE      512
#include <winsock.h>
#include <stdio.h>
#include <time.h>
#include <assert.h>
#include <ctype.h>
#include "clsHTTPDownload.h"
#include "clsHTTPParser.h"
#include "clsURL.h"
#include "clsGalleryChangedItem.h"
#pragma comment(lib, "wsock32.lib")


clsHTTPDownload::Options clsHTTPDownload::mOptions;
clsHTTPDownload* clsHTTPDownload::mFreeHead = NULL;
//clsHTTPDownload* clsHTTPDownload::mUsedHead = 0;
clsHTTPDownload* clsHTTPDownload::mItemStorage;
fd_set clsHTTPDownload::mReadSetIn;
fd_set clsHTTPDownload::mWriteSetIn;
fd_set clsHTTPDownload::mReadSetOut;
fd_set clsHTTPDownload::mWriteSetOut;
bool clsHTTPDownload::mInited = false;
std::ostream* clsHTTPDownload::mFailLog = NULL;
clsHTTPCompletion* clsHTTPDownload::mCompletion = NULL;

#define DESIRED_WINSOCK_VERSION	0x0101  // we'd like winsock ver 1.1... 
#define MINIMUM_WINSOCK_VERSION	0x0101  // ...but we'll take ver 1.1

void ReportState(const char* description, clsHTTPDownload* item)
{
#if 0
	printf("%Xh %s\n", item, description);
#endif
}

// The following routine taken from Stevens

/* 
 * Check whether "cp" is a valid ascii representation
 * of an Internet address and convert to a binary address.
 * Returns 1 if the address is valid, 0 if not.
 * This replaces inet_addr, the return value from which
 * cannot distinguish between failure and a local broadcast address.
 */

int inet_aton(const char *cp, struct in_addr *ap)
{
    int dots = 0;
	u_long acc = 0;
	u_long addr = 0;

    do 
	{
		char cc = *cp;

		switch (cc) 
		{
			case '0':
			case '1':
			case '2':
			case '3':
			case '4':
			case '5':
			case '6':
			case '7':
			case '8':
			case '9':
				acc = acc * 10 + (cc - '0');
				break;

			case '.':
				if (++dots > 3)
				{
					return 0;
				}
				/* Fall through */

			case '\0':
				if (acc > 255)
				{
					return 0;
				}

				addr = addr << 8 | acc;
				acc = 0;
				break;

			default:
				return 0;
		}
    } while (*cp++) ;

    /* Normalize the address */
    if (dots < 3) 
	{
		addr <<= 8 * (3 - dots) ;
    }

    /* Store it if requested */
    if (ap) 
	{
		ap->s_addr = htonl(addr);
    }

    return 1;    
}

// The following routine taken from Stevens
// Fills in an inet address
// Takes AF_INET as first arguement
// strptr points to a dotted internet address
// addrptr points to a in_addr
int
inet_pton(int family, const char *strptr, void *addrptr)
{
    if (family == AF_INET) {
    	struct in_addr  in_val;

        if (inet_aton(strptr, &in_val)) {
            memcpy(addrptr, &in_val, sizeof(struct in_addr));
            return (1);
        }
		return(0);
    }

    return (-1);
}




clsHTTPDownloadStats::clsHTTPDownloadStats()
{
	Reset();
}

void clsHTTPDownloadStats::Reset()
{
	memset(this, 0, sizeof(clsHTTPDownloadStats));
}

void clsHTTPDownloadStats::AddStatusResult(int httpStatusCode)
{
	switch (httpStatusCode)
	{
	case clsHTTPParser::kContinue:
		++mContinue;
		break;
	case clsHTTPParser::kSwitchingProtocols:
		++mSwitchingProtocols;
		break;
	case clsHTTPParser::kOK:
		++mOK;
		break;
	case clsHTTPParser::kCreated: 
		++mCreated;
		break;
	case clsHTTPParser::kAccepted: 
		++mAccepted;
		break;
	case clsHTTPParser::kNonAuthoritativeInformation: 
		++mNonAuthoritativeInformation;
		break;
	case clsHTTPParser::kNoContent: 
		++mNoContent;
		break;
	case clsHTTPParser::kResetContent: 
		++mResetContent;
		break;
	case clsHTTPParser::kPartialContent: 
		++mPartialContent;
		break;
	case clsHTTPParser::kMultipleChoices: 
		++mMultipleChoices;
		break;
	case clsHTTPParser::kMovedPermanently: 
		++mMovedPermanently;
		break;
	case clsHTTPParser::kMovedTemporarily: 
		++mMovedTemporarily;
		break;
	case clsHTTPParser::kSeeOther: 
		++mSeeOther;
		break;
	case clsHTTPParser::kNotModified: 
		++mNotModified;
		break;
	case clsHTTPParser::kUseProxy: 
		++mUseProxy;
		break;
	case clsHTTPParser::kBadRequest: 
		++mBadRequest;
		break;
	case clsHTTPParser::kUnauthorized: 
		++mUnauthorized;
		break;
	case clsHTTPParser::kPaymentRequired: 
		++mPaymentRequired;
		break;
	case clsHTTPParser::kForbidden: 
		++mForbidden;
		break;
	case clsHTTPParser::kNotFound: 
		++mNotFound;
		break;
	case clsHTTPParser::kMethodNotAllowed: 
		++mMethodNotAllowed;
		break;
	case clsHTTPParser::kNotAcceptable: 
		++mNotAcceptable;
		break;
	case clsHTTPParser::kProxyAuthenticationRequired: 
		++mProxyAuthenticationRequired;
		break;
	case clsHTTPParser::kRequestTimeOut: 
		++mRequestTimeOut;
		break;
	case clsHTTPParser::kConflict: 
		++mConflict;
		break;
	case clsHTTPParser::kGone: 
		++mGone;
		break;
	case clsHTTPParser::kLengthRequired: 
		++mLengthRequired;
		break;
	case clsHTTPParser::kPreconditionFailed: 
		++mPreconditionFailed;
		break;
	case clsHTTPParser::kRequestEntityTooLarge: 
		++mRequestEntityTooLarge;
		break;
	case clsHTTPParser::kRequestURITooLarge: 
		++mRequestURITooLarge;
		break;
	case clsHTTPParser::kUnsupportedMediaType: 
		++mUnsupportedMediaType;
		break;
	case clsHTTPParser::kInternalServerError: 
		++mInternalServerError;
		break;
	case clsHTTPParser::kNotImplemented: 
		++mNotImplemented;
		break;
	case clsHTTPParser::kBadGateway: 
		++mBadGateway;
		break;
	case clsHTTPParser::kServiceUnavailable: 
		++mServiceUnavailable;
		break;
	case clsHTTPParser::kGatewayTimeOut: 
		++mGatewayTimeOut;
		break;
	case clsHTTPParser::kHTTPVersionNotSupported: 
		++mHTTPVersionNotSupported;
		break;
	default:
		++mUnknown;
	}
}

clsHTTPDownloadStats clsHTTPDownload::gHTTPDownloadStats;


// Copies an http string and escapes spaces
int HTTPStringCopy(char* dst, const char* src, int max)
{
	while (*src && max)
	{
		if (*src == ' ')
		{
			*dst++ = '%';
			if (!--max)
				return -1;
			*dst++ = '2';
			if (!--max)
				return -1;
			*dst++ = '0';
			--max;
			++src;
			continue;
		}

		*dst++ = *src++;
		--max;
	}

	if (!max)
		return -1;

	*dst = 0;

	if (*src)
		return -1;
	else
		return 0;
}


int clsHTTPDownload::Startup(Options& options)
{
	if (mInited) 
		return -1;

	mOptions = options;

	mFreeHead = NULL;
//	mUsedHead = 0;
	mItemStorage = NULL;

	int error;
	int storageSize = sizeof(clsHTTPDownload) * mOptions.mMaxSimultaneousDownloads;

	mItemStorage = reinterpret_cast<clsHTTPDownload*>(new char[storageSize]);
	if (!mItemStorage) 
		return -1;

	memset(mItemStorage, 0, storageSize);

	for (int i = 0; i < mOptions.mMaxSimultaneousDownloads; i++)
	{
		mItemStorage[i].mNext = mFreeHead;
		mFreeHead = &mItemStorage[i];
	}

	FD_ZERO(&mReadSetIn);
	FD_ZERO(&mWriteSetIn);

	FD_ZERO(&mReadSetOut);
	FD_ZERO(&mWriteSetOut);

	WSADATA wsadata;

	error = WSAStartup(DESIRED_WINSOCK_VERSION, &wsadata);
	if (error) 
		return error;

	if (wsadata.wVersion < MINIMUM_WINSOCK_VERSION)
		return -1;

	mInited = true;

#if 0
	mOptions.mMaxSimultaneousDownloads = kDefaultMaxSimultaneousDownloads;
	mOptions.mSelectWaitTime = kDefaultMaxSelectWaitTime;
	mOptions.mMaxConnectWaitTime = kDefaultMaxConnectWaitTime;
	mOptions.mMaxRequestWaitTime = kDefaultMaxRequestWaitTime;
	mOptions.mMaxReadWaitTime = kDefaultMaxReadWaitTime;
	mOptions.mMaxRedirects = kDefaultMaxRedirects;
#endif

	ReportState("started up", 0);
	return 0;
}

int clsHTTPDownload::Shutdown()
{
	if (!mInited) 
		return -1;

	delete [] reinterpret_cast<char*>(mItemStorage);

	WSACleanup();

	mInited = false;

	ReportState("shut down", 0);

	return 0;
}

int clsHTTPDownload::StartDownload(const char* trueHostName, 
								const char* hostName, 
								const char* item, 
								const char* destination,
								const char* failMessage,
								int callbackParam)
{
	if (!mFreeHead)
		return -1;

	clsHTTPDownload* httpDownload = new clsHTTPDownload(trueHostName, hostName, item, destination, failMessage, callbackParam);

	if (httpDownload)
		return 0;
	else 
		return -1;
}

int clsHTTPDownload::IsReady()
{
	return mFreeHead != NULL;
}

void* clsHTTPDownload::operator new(size_t /* size */)
{
	if (!mFreeHead) 
		return NULL;

	clsHTTPDownload* returnValue = mFreeHead;

	mFreeHead = mFreeHead->mNext;

#if 0
	if (mUsedHead)
		mUsedHead->mPrior = returnValue;

	returnValue->mNext = mUsedHead;
	returnValue->mPrior = NULL;

	mUsedHead = returnValue;
#endif

	ReportState("new", returnValue);
	return returnValue;
}

void clsHTTPDownload::operator delete(void* item)
{
	ReportState("delete", reinterpret_cast<clsHTTPDownload*>(item) );
	memset(item, 0, sizeof(clsHTTPDownload));

	clsHTTPDownload* removeMe = reinterpret_cast<clsHTTPDownload*>(item);

#if 0
	if (mUsedHead == removeMe)
	{
		mUsedHead = removeMe->mNext;
		if (mUsedHead)
			mUsedHead->mPrior = NULL;
	}
	else
	{
		removeMe->mPrior->mNext = removeMe->mNext;

		if (removeMe->mNext)
			removeMe->mNext->mPrior = removeMe->mPrior;
	}
#endif

	removeMe->mNext = mFreeHead;
	mFreeHead = removeMe;
}


clsHTTPDownload::clsHTTPDownload(const char* trueHostName,
						   const char* hostName, const char* item,
						   const char* destination, const char* failMessage,
						   int callbackParam) :
	mCallbackParam(callbackParam)
{
	ReportState("initializeing", this);

	try
	{
		
		if (strlen(trueHostName) > kMaxHostName) 
			throw "true host name too long";

		if (strlen(hostName) > kMaxHostName) 
			throw "host name too long";

		if (strlen(item) > kMaxItemName) 
			throw "item name too long";

		if (strlen(destination) > kMaxDestinationName) 
			throw "destination name too long";

		if (strlen(failMessage) > kMaxFailMessage) 
		{
			strncpy(mFailMessage, failMessage, kMaxFailMessage);
			mFailMessage[kMaxFailMessage] = '\0';
		}
		else
		{
			strcpy(mFailMessage, failMessage);
		}

		strncpy(mTrueHostName, trueHostName, kMaxHostName);
		strncpy(mHostName, hostName, kMaxHostName);

		int httpStringCopyResult = HTTPStringCopy(mItemName, item, kMaxItemName);
		if (httpStringCopyResult)
			throw "redirect item name too long";

		strncpy(mDestinationName, destination, kMaxDestinationName);

		EnterConnecting();

	}
	catch (char* errorString)
	{
		SetError(errorString, 0, kGalleryBadURL);
	}
	catch (...)
	{
	}
}

int IsNumericIPAddress(const char* address)
{
	while (*address)
	{
		if (!isdigit(*address) && *address != '.')
			return 0;
		if (*address == '.')
			return 1;
		++address;
	}

	return 0;
}

void clsHTTPDownload::EnterConnecting()
{
	int error = 0;
	GalleryResultCode code = kGalleryBadURL;

	try
	{
		ReportState("enter connecting", this);
		
		mSock = INVALID_SOCKET;
		mLastActionTime = time(NULL);

		HOSTENT* he;

		// Cast our generic address into the inet address that it is.
		sockaddr_in* socketInternetAddress = reinterpret_cast<sockaddr_in*>(&mAddress);

		socketInternetAddress->sin_family = AF_INET;

		if (IsNumericIPAddress(mHostName))
		{
			if (inet_pton(AF_INET, mHostName, &socketInternetAddress->sin_addr) != 1)
			{
				code = kGalleryBadURL;
				throw "bad address";
			}
		}
		else
		{
			he = gethostbyname(mHostName);
			if (!he)
			{
				code = kGalleryHostNameDoesNotExist;
				throw "couldn't find host";
			}

			memcpy(reinterpret_cast<char*>(&(socketInternetAddress->sin_addr)), he->h_addr, he->h_length);
		}

		socketInternetAddress->sin_port = htons(80);

		mSock = socket(AF_INET, SOCK_STREAM, IPPROTO_IP);
		if (mSock == INVALID_SOCKET)
		{
			code = kGalleryServerNotAvailable;
			throw "couldn't make socket";
		}

		// Make the socket non-blocking
		unsigned long data = 1;

		error = ioctlsocket(mSock, FIONBIO, &data);
		if (error)
		{
			code = kGalleryInternalError;
			throw "couldn't make socket non-blocking";
		}

		// We will try and set the recieve buffer size
		// We will ignore failure because we can work with the default buffer
		int optLen = sizeof(data);
		int optionResult;
#if 0
		optionResult = getsockopt(mSock, SOL_SOCKET, SO_RCVBUF, reinterpret_cast<char*>(&data), &optLen);
		error = WSAGetLastError();
#endif
		data = 1024 * 64; // 64k buffers
		optionResult = setsockopt(mSock, SOL_SOCKET, SO_RCVBUF, reinterpret_cast<char*>(&data), sizeof(data));
#if 0
		if (optionResult)
		{
			cout << "Warning: couldn't set receive buffer size." << endl;
		}
#endif

		error = connect(mSock, &mAddress, sizeof(mAddress));
		if (error == SOCKET_ERROR)
		{
			error = WSAGetLastError();

			if (error != WSAEWOULDBLOCK)
				throw "connect error";

			FD_SET(mSock, &mWriteSetIn);
			mState = kConnecting;
		}
		else
		{
			EnterRequesting();
		}
	}
	catch (char* errorString)
	{
		gHTTPDownloadStats.AddFailedDNS();
		SetError(errorString, error, code);
	}
	catch (...)
	{
	}
}


clsHTTPDownload::~clsHTTPDownload()
{
	ReportState("destruct", this);
	int error;

	if (mSock != INVALID_SOCKET)
		error = closesocket(mSock);

	FD_CLR(mSock, &mWriteSetIn);
	FD_CLR(mSock, &mReadSetIn);

	if (mOutputFile)
	{
		fclose(mOutputFile);
		mOutputFile = NULL;
	}

	if (mState != kDone)
		int removeResult = remove(mDestinationName);
}

int clsHTTPDownload::GetActiveCount()
{
	int count = 0;

	for (int i = 0; i < mOptions.mMaxSimultaneousDownloads; i++)
	{
		if (mItemStorage[i].mState != kRaw)
			++count;
	}

	return count;
}

bool clsHTTPDownload::Cycle()
{
	timeval timeToWait;

	timeToWait.tv_sec = 3;
	timeToWait.tv_usec = 0;//kSelectWaitTime;

	mReadSetOut = mReadSetIn;
	mWriteSetOut = mWriteSetIn;

	int ready = select(1, &mReadSetOut, &mWriteSetOut, NULL, &timeToWait);
	if (ready == SOCKET_ERROR)
	{
		int error = WSAGetLastError();
		if (error == WSAEINVAL)
		{
			ReportState("empty select", 0);
		}
	}
	
	for (int i = 0; i < mOptions.mMaxSimultaneousDownloads; i++)
	{
		clsHTTPDownload* item = &mItemStorage[i];

		switch (item->mState)
		{
		case kRaw:
			break;
		case kConnecting:
			item->DoConnecting();
			break;
		case kRequesting:
			item->DoRequesting();
			break;
		case kReading:
			item->DoReading();
			break;
		case kDone:
			delete item;
			break;
		case kError:
			delete item;
			break;
		default:
			break;
		}

	}

	return GetActiveCount() != 0;
}

void clsHTTPDownload::DoConnecting()
{
	ReportState("do connecting", this);
	int error = 0;

	try
	{
		if (FD_ISSET(mSock, &mWriteSetOut))
		{
			int len = sizeof(error);

			int optError = getsockopt(mSock, SOL_SOCKET, SO_ERROR, 
				reinterpret_cast<char*>(&error), &len);

			if (optError)
				throw "getsockopt error in DoConnecting";

			if (error)
				throw "async connect error in DoConnecting";

			FD_CLR(mSock, &mWriteSetIn);

			EnterRequesting();
		}
		else if ((time(NULL) - mLastActionTime) > mOptions.mMaxConnectWaitTime)
		{
			throw "connect timed out";
		}
	}
	catch (char* errorString)
	{
		gHTTPDownloadStats.AddFailedConnection();
		SetError(errorString, error, kGalleryServerNotAvailable);
	}
	catch (...)
	{
	}
}

#define MAXLINE 500
void clsHTTPDownload::EnterRequesting()
{
	ReportState("enter requesting", this);

	int n;
	char line[MAXLINE];

	// This is complete voodoo - we have to convince the server that we
	// are a client it wants to talk to. There is apparently two key
	// things:
	// 1) We must claim to be HTTP/1.1
	// 2) We must provide the true host name of who we think
	//		we are talking to
	// Below are some other things browsers send to servers
	//\r\nHost: Keep-Alive
	//\r\nAccept-Language: en-us
	//\r\nUser-Agent: Mozilla/4.0 (compatible: MSIE 4.01; Windows NT)
	// mlh 8/18/98 the initial slash before the item path is critical;
	//   some server will not function without it e.g. MS-IIS
	// Wget/1.5.0

	n = _snprintf(line, sizeof(line), "GET /%s HTTP/1.1\r\nHost: %s\r\nAccept-Language: en-us\r\nUser-Agent: Mozilla/4.0 (compatible: MSIE 4.01; Windows NT)\r\nAccept: */*\r\n\r\n", 
		mItemName, mTrueHostName);

	int sent = send(mSock, line, n, 0);

	if (sent != n)
	{
		gHTTPDownloadStats.AddFailedSendRequest();
		SetError("send request problem", WSAGetLastError(), kGalleryServerNotAvailable);
		return;
	}

	mState = kRequesting;
	mLastActionTime = time(NULL);

	FD_SET(mSock, &mReadSetIn);

}

char* clsHTTPDownload::FindHeaderEnd(char* header, int maxChars)
{
	char* end = header + maxChars;

	char* headerEnd = strstr(header, "\r\n\r\n");
	if (headerEnd)
	{
		headerEnd += 4;
		if (headerEnd <= end)
			return headerEnd;
	}

	// Some servers terminate without the \r's
	// it isn't to spec, but...
	headerEnd = strstr(mBuffer, "\n\n"); 
	if (headerEnd)
	{
		headerEnd += 2;
		if (headerEnd <= end)
			return headerEnd;
	}

	return headerEnd;
}

void clsHTTPDownload::EnterRedirect(clsHTTPParser& parser)
{
	ReportState("redirecting", this);
	if (mRedirectCount >= mOptions.mMaxRedirects)
	{
		throw "exceeded redirect limit";
	}

	try
	{
		clsHTTPParser::Property* locationProperty =
			&clsHTTPParser::mProperties[clsHTTPParser::kLocation];
		clsUrlCracker cracker;

		int crackResult = cracker.Crack(locationProperty->value,
			strlen(locationProperty->value));

		if (crackResult)
			throw "bad redirect url";

		if (!cracker.host) 
			throw "bad redirect item url: no host";

		if (!cracker.absolute) 
			throw "bad redirect item url: not absolute";

		if (!cracker.access)
			throw "bad redirect item url: no access";

		if (strcmp(cracker.access, "http")) 
			throw "bad redirect access type";

		int closeSocketResult = closesocket(mSock);
		if (closeSocketResult)
			throw "close socket error on redirect";

		FD_CLR(mSock, &mWriteSetIn);
		FD_CLR(mSock, &mReadSetIn);

		if (mOutputFile)
			throw "open output file on redirect";

		if (strlen(cracker.host) > kMaxHostName) 
			throw "redirect host name too long";

		if (strlen(cracker.absolute) > kMaxItemName) 
			throw "redirect item name too long";

		strncpy(mTrueHostName, cracker.host, kMaxHostName);
		strncpy(mHostName, cracker.host, kMaxHostName);
		
		int httpStringCopyResult = HTTPStringCopy(mItemName, cracker.absolute, kMaxItemName);
		if (httpStringCopyResult)
			throw "redirect item name too long";

		mTotalToRead = 0;
		mReadSoFar = 0;
		mReadPos = 0;
		mWritePos = 0;
		mPayloadSize = 0;
		mPayloadWritten = 0;
		++mRedirectCount;

		EnterConnecting();
	}
	catch (char* errorString)
	{
		SetError(errorString, 0, kGalleryServerNotAvailable);
		gHTTPDownloadStats.AddFailedRedirect();
	}
	catch (...)
	{
	}
}


void clsHTTPDownload::DoRequesting()
{
	ReportState("do requesting", this);
	int error = 0;

	try
	{
		if (FD_ISSET(mSock, &mReadSetOut))
		{
			int read = recv(mSock, mBuffer + mWritePos, sizeof(mBuffer), 0);
			if (read == SOCKET_ERROR || read == 0)
				throw "request teminated";

			mWritePos += read;

			// Do we have the entire http header yet?
			char* headerEnd = FindHeaderEnd(mBuffer, mWritePos);
			if (!headerEnd)
				return;

#if 0 // Test code that writes out http headers
			char* hEnd = strstr(mBuffer, "\r\n\r\n");

			if (!hEnd)
			{
				hEnd = strstr(mBuffer, "\n\n"); // Some servers terminate this way
			}

			FILE* hOut = fopen("c:\\headers.bin", "a+b");

			int writeResult = fwrite(mBuffer, (size_t) (hEnd - mBuffer), 1, hOut);
			char* endHeader = "\r\n\r\n";
			writeResult = fwrite(mBuffer, strlen(endHeader), 1, hOut);

			fclose(hOut);

#endif

			clsHTTPParser parser;
			int parseResult = parser.ParseResponse(mBuffer);
			if (parseResult)
				throw "DoRequesting error - bad header";

			// The status code class is rounded down to the nearest 100
			// We do this as a quick way to get into the ballpark of
			// what to do next
			int httpStatusCodeClass = (parser.mStatusCode / 100) * 100;

			gHTTPDownloadStats.AddStatusResult(parser.mStatusCode);

			switch (parser.mStatusCode)
			{
			case clsHTTPParser::kOK:
				break;
#if 1
			case clsHTTPParser::kMovedPermanently:
			case clsHTTPParser::kMovedTemporarily:
				EnterRedirect(parser);
				return;
#endif
			default:
				error = parser.mStatusCode;
				throw "DoRequesting error - http";
			}

			if (!clsHTTPParser::mProperties[clsHTTPParser::kContentLength].value)
				throw "DoRequesting error - no content length";

			mPayloadSize = atoi(clsHTTPParser::mProperties[clsHTTPParser::kContentLength].value);
			mPayloadWritten = 0;

			mReadPos = headerEnd - mBuffer;

			if (mReadPos > mWritePos)
				throw "DoRequesting error - bad read position";

			mLastActionTime = time(NULL);

			EnterReading();
		}
		else if ((time(NULL) - mLastActionTime) > mOptions.mMaxRequestWaitTime)
		{
			throw "request timed out";
		}
	}
	catch (char* errorString)
	{
		gHTTPDownloadStats.AddFailedGet();
		SetError(errorString, error, kGalleryItemNotFound);
	}
	catch (...)
	{
	}
}

void clsHTTPDownload::EnterReading()
{
	ReportState("enter reading", this);

	mState = kReading;

	mLastActionTime = time(NULL);

	if (mReadPos < mWritePos)
	{
		DoReading();
	}
	else
	{
		mReadPos = 0;
		mWritePos = 0;
	}
}

void clsHTTPDownload::WritePayload()
{
	ReportState("write payload", this);

	try
	{
		if (!mOutputFile)
			mOutputFile = fopen(mDestinationName, "wb");

		if (!mOutputFile)
			throw "can't create destination file";

		size_t toWrite = mWritePos - mReadPos;

		size_t written = fwrite(&mBuffer[mReadPos], 1, toWrite, mOutputFile);
		if (written != toWrite)
			throw "writting destination file";

		mPayloadWritten += toWrite;

		mWritePos = 0;
		mReadPos = 0;
	}
	catch (char* errorString)
	{
		gHTTPDownloadStats.AddFailedWrittingFile();
		SetError(errorString, errno, kGalleryInternalError);
	}
	catch (...)
	{
	}
}

void clsHTTPDownload::DoReading()
{
	ReportState("do reading", this);
	int error = 0;

	try
	{
		if (mReadPos < mWritePos)
		{
			WritePayload();
			return;
		}

		if (mPayloadWritten >= mPayloadSize)
		{
			EnterDone();
			return;
		}

		if (FD_ISSET(mSock, &mReadSetOut))
		{
			int read = recv(mSock, mBuffer, sizeof(mBuffer), 0);
			if (read == SOCKET_ERROR || read == 0)
			{
				error = WSAGetLastError();
				throw "read teminated";
			}

			mWritePos += read;
			WritePayload();		

			mLastActionTime = time(NULL);
		}
		else if ((time(NULL) - mLastActionTime) > mOptions.mMaxReadWaitTime)
		{
			throw "read timed out";
		}
	}
	catch (char* errorString)
	{
		gHTTPDownloadStats.AddFailedReading();
		SetError(errorString, errno, kGalleryFailedDownload);
	}
	catch (...)
	{
	}
}

void clsHTTPDownload::EnterDone()
{
	gHTTPDownloadStats.AddSuccess();
	ReportState("enter done", this);

	if (mOutputFile)
	{
		fclose(mOutputFile);
		mOutputFile = NULL;
	}

	if (mCompletion)
		mCompletion->Complete(kGallerySuccess, 200, mDestinationName, mCallbackParam);

	mState = kDone;
}

void clsHTTPDownload::SetError(const char* errorString, int errorNum, GalleryResultCode code)
{
	if (mState == kError)
		return;

	if (mCompletion)
		mCompletion->Complete(code, 400, mDestinationName, mCallbackParam);

	if (mFailLog)
	{
		*mFailLog << mFailMessage << std::endl;
	}

	mState = kError;
	FD_CLR(mSock, &mReadSetIn);
	FD_CLR(mSock, &mWriteSetIn);

	sprintf(mErrorString, "Failed: %s error %d %s%s\n", errorString, errorNum, mHostName, mItemName);

	ReportState(errorString, this);
}

