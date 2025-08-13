/* $Id: clsHTTPParser.h,v 1.2 1999/02/21 02:22:24 josh Exp $ */
//
// File: clsHTTPParser
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: Parses an http response.
//		IMPORTANT: Only one object can be instantiated at a time
//			FIXME MLH 8/11/98 - gota fix this - this sucks
//

#ifndef clsHTTPParser_h
#define clsHTTPParser_h

struct clsHTTPParser
{
	clsHTTPParser();

	enum HTTPStatusCode
	{
		kContinue = 100,
		kSwitchingProtocols = 101,
		kOK = 200,
		kCreated = 201,
		kAccepted = 202,
		kNonAuthoritativeInformation = 203,
		kNoContent = 204,
		kResetContent = 205,
		kPartialContent = 206,
		kMultipleChoices = 300,
		kMovedPermanently = 301,
		kMovedTemporarily = 302,
		kSeeOther = 303,
		kNotModified = 304,
		kUseProxy = 305,
		kBadRequest = 400,
		kUnauthorized = 401,
		kPaymentRequired = 402,
		kForbidden = 403,
		kNotFound = 404,
		kMethodNotAllowed = 405,
		kNotAcceptable = 406,
		kProxyAuthenticationRequired = 407,
		kRequestTimeOut = 408,
		kConflict = 409,
		kGone = 410,
		kLengthRequired = 411,
		kPreconditionFailed = 412,
		kRequestEntityTooLarge = 413,
		kRequestURITooLarge = 414,
		kUnsupportedMediaType = 415,
		kInternalServerError = 500,
		kNotImplemented = 501,
		kBadGateway = 502,
		kServiceUnavailable = 503,
		kGatewayTimeOut = 504,
		kHTTPVersionNotSupported = 505
	};

	enum HTTPStatusCodeClass
	{
		kInformational = 100,
		kSuccess = 200,
		kRedirection = 300,
		kClientError = 400,
		kServerError = 500
	};


	struct Property
	{
		const char* name;
		int id;
		char* value;
	};

	enum PropertyIDs
	{
		kAccept,
		kAcceptCharset,
		kAcceptEncoding,
		kAcceptLanguage,
		kAcceptRanges,
		kAge,
		kAllow,
		kAuthorization,
		kCacheControl,
		kConnection,
		kContentEncoding,
		kContentLanguage,
		kContentLength,
		kContentLocation,
		kContentMd5,
		kContentRange,
		kContentTransferEncoding,
		kContentType,
		kDate,
		kDigestMessageDigest,
		kEtag,
		kExpires,
		kExtensionHeader,
		kHTTP,
		kKeepAlive,
		kLastModified,
		kLink,
		kLocation,
		kMaxForwards,
		kMimeVersion,
		kPragma,
		kProtocol,
		kProtocolInfo,
		kProtocolRequest,
		kProxyAuthenticate,
		kProxyAuthorization,
		kPublic,
		kRange,
		kReferer,
		kRetryAfter,
		kServer,
		kTrailer,
		kTransferEncoding,
		kUpgrade,
		kUserAgent,
		kVary,
		kVia,
		kWarning,
		kWWWAuthenticate,
		kPropertyCount 
	};

	enum
	{
		kMaxBuffer = 2048,
		kMaxReasonPhrase = 255
	};

	static Property mProperties[kPropertyCount];

	char mBuffer[kMaxBuffer+1];
	char* mNextChar;
	char* mHeaderEnd;
	int mMajorVersion;
	int mMinorVersion;
	int mStatusCode;
	char mReasonPhrase[kMaxReasonPhrase + 1];

	char* FindHeaderEnd(char* header);
	int GetChar();
	int PeekChar();
	int UngetChar(int c);
	int EatSpace();
	int GetToken();
	int ParseStatusLine();
	int ParseResponse(char* header);
	int ParseProperty();

};

#endif // clsHTTPParser_h