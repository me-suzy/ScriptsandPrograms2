#include "clsHTTPParser.h"
#include <string.h>
#include <ctype.h>
#include <stdlib.h>

#define PROPERTY(name, id) {name, id, 0}


clsHTTPParser::Property clsHTTPParser::mProperties[] = 
{
	PROPERTY("accept", kAccept),
	PROPERTY("accept-charset", kAcceptCharset),
	PROPERTY("accept-encoding", kAcceptEncoding),
	PROPERTY("accept-language", kAcceptLanguage),
	PROPERTY("accept-ranges", kAcceptRanges),
	PROPERTY("age", kAge),
	PROPERTY("allow", kAllow),
	PROPERTY("authorization", kAllow),
	PROPERTY("cache-control", kCacheControl),
	PROPERTY("connection", kConnection),
	PROPERTY("content-encoding", kContentEncoding),
	PROPERTY("content-language", kContentLanguage),
	PROPERTY("content-length", kContentLength),
	PROPERTY("content-location", kContentLocation),
	PROPERTY("content-md5", kContentMd5),
	PROPERTY("content-range", kContentRange),
	PROPERTY("content-transfer-encoding", kContentTransferEncoding),
	PROPERTY("content-type", kContentType),
	PROPERTY("date", kDate),
	PROPERTY("digest-messagedigest", kDigestMessageDigest),
	PROPERTY("etag", kEtag),
	PROPERTY("expires", kExpires),
	PROPERTY("extension-header", kExtensionHeader),
	PROPERTY("http", kHTTP),
	PROPERTY("keep-alive", kKeepAlive),
	PROPERTY("last-modified", kLastModified),
	PROPERTY("link", kLink),
	PROPERTY("location", kLocation),
	PROPERTY("max-forwards", kMaxForwards),
	PROPERTY("mime-version", kMimeVersion),
	PROPERTY("pragma", kPragma),
	PROPERTY("protocol", kProtocol),
	PROPERTY("protocol-info", kProtocolInfo),
	PROPERTY("protocol-request", kProtocolRequest),
	PROPERTY("proxy-authenticate", kProxyAuthenticate),
	PROPERTY("proxy-authorization", kProxyAuthorization),
	PROPERTY("public", kPublic),
	PROPERTY("range", kRange),
	PROPERTY("referer", kReferer),
	PROPERTY("retry-after", kRetryAfter),
	PROPERTY("server", kServer),
	PROPERTY("trailer", kTrailer),
	PROPERTY("transfer-encoding", kTransferEncoding),
	PROPERTY("upgrade", kUpgrade),
	PROPERTY("user-agent", kUserAgent),
	PROPERTY("vary", kVary),
	PROPERTY("via", kVia),
	PROPERTY("warning", kWarning),
	PROPERTY("www-authenticate", kWWWAuthenticate)
};




clsHTTPParser::clsHTTPParser()
{
}

char* clsHTTPParser::FindHeaderEnd(char* header)
{
	char* headerEnd = strstr(header, "\r\n\r\n");
	if (headerEnd)
	{
		headerEnd += 4;
		return headerEnd;
	}

	// Some servers terminate without the \r's
	// it isn't to spec, but...
	headerEnd = strstr(mBuffer, "\n\n"); 
	if (headerEnd)
	{
		headerEnd += 2;
		return headerEnd;
	}

	return headerEnd;
}

int clsHTTPParser::GetChar()
{
	if (mNextChar >= mHeaderEnd)
		return -1;
	else
		return *mNextChar++;
}

int clsHTTPParser::PeekChar()
{
	if (mNextChar >= mHeaderEnd)
		return -1;
	else
		return *mNextChar;
}

int clsHTTPParser::UngetChar(int c)
{
	if (mNextChar <= mBuffer)
		return -1;
	else
		*--mNextChar = c;

	return 0;
}

int clsHTTPParser::EatSpace()
{
	int c = PeekChar();

	while (c > 0)
	{
		if (isspace(c) && c != '\n')
			GetChar();	// Eat white space
		else
			return 0;

		c = PeekChar();
	}

	return -1;
}

int clsHTTPParser::GetToken()
{

	return 0;
}

int clsHTTPParser::ParseResponse(char* header)
{
	memcpy(mBuffer, header, kMaxBuffer);
	mBuffer[kMaxBuffer-1] = '\0';

	mHeaderEnd = FindHeaderEnd(mBuffer);

	if (!mHeaderEnd)
		return -1;

	mNextChar = mBuffer;

	// Lowercase for string compares
//	for (char*p = mBuffer; p < mHeaderEnd; ++p)
//		*p = tolower(*p);

	for (int i = 0; i < kPropertyCount; ++i)
		mProperties[i].value = '\0';		

	int parseStatusLineResult = ParseStatusLine();
	if (parseStatusLineResult != 0)
		return -1;
	
	while(ParseProperty() == 0)
		;

	return 0;
}

int clsHTTPParser::ParseStatusLine()
{
   // We are trying to parse something that looks like this:
   // HTTP/1.0 200 Got it\r\n

	if (tolower(GetChar()) == 'h' &&
	   tolower(GetChar()) == 't' &&
	   tolower(GetChar()) == 't' &&
	   tolower(GetChar()) == 'p')
	{
		int c = 0;

		// Some servers don't bother with the version info so we will check
		// for it. I bet you want to know who would be so stupid...well
		// how about Microsoft IIS 1.5.
		if (PeekChar() == '/')
		{
			// Eat the slash
			GetChar();

			// Get major number
			mMajorVersion = 0;
			c = GetChar();
			while (c != -1)
			{
				if (isdigit(c))
				{
					mMajorVersion = 10 * mMajorVersion + (c - '0');
					c = GetChar();
				}
				else
				{
					break;
				}
			}

			if (mMajorVersion == 0)
				return -1;

			if (c != '.')
				return -1;

			// Get minor number
			mMinorVersion = 0;
			c = GetChar();
			while (c != -1)
			{
				if (isdigit(c))
				{
					mMinorVersion = 10 * mMinorVersion + (c - '0');
					c = GetChar();
				}
				else
				{
					break;
				}
			}

			if (c == -1)
				return -1;
		}

		EatSpace();

		// Get result code
		mStatusCode = 0;
		c = GetChar();

		while (c != -1)
		{
			if (!isdigit(c))
				break;

			mStatusCode *= 10;
			mStatusCode += c - '0';

			c = GetChar();
		}

		if (mStatusCode == 0)
			return -1;

		// Get optional reason phrase

		EatSpace();
		int reasonPhraseCharPosition = 0;
		while (c != -1)
		{
			if (c == '\n')
				break;
			
			if (reasonPhraseCharPosition >= kMaxReasonPhrase)
				break;

			mReasonPhrase[reasonPhraseCharPosition++] = c;

			c = GetChar();
		}
		mReasonPhrase[reasonPhraseCharPosition] = '\0';
	}
	else
	{
		return -1;
	}

	
	return 0;
}

int compare( const void *elem1, const void *elem2 )
{
	// elem1 is a pointer to the name of our property
	// elem2 is a pointer to a property in mProperties
	const char* propertyName = 
		reinterpret_cast<const char*>(const_cast<void *>(elem1));
	const clsHTTPParser::Property* property =
		reinterpret_cast<const clsHTTPParser::Property*>(const_cast<void *>(elem2));

	return _stricmp(propertyName, property->name);
}

int clsHTTPParser::ParseProperty()
{
	// Terminate the property name with a 0
	// Properties must have a ':' at the end of the name
	// We replace that ':' with a 0
	const char* propertyName = mNextChar;
	bool foundPropertyName = false;
	int c = PeekChar();
	while (c != -1)
	{
		if (c == ':')
		{
			*mNextChar++ = '\0';
			foundPropertyName = true;
			break;
		}

		if (c == '\n')
			return -1;

		GetChar();
		c = PeekChar();
	}
	
	if (!foundPropertyName)
		return -1;

	// Finds the property in our property list
	// returns back the pointer to the item as a void*
	// which is cast into a property
	void* bsearchResult = bsearch(propertyName, mProperties, kPropertyCount, sizeof(Property), compare);
	if (bsearchResult == NULL)
	{
		// Unknown property found - read until eol
		
		while (c != -1)
		{
			if (c == '\n')
				return 0;

			c = GetChar();
		}

		return -1;
	}

	Property* property = reinterpret_cast<Property*>(bsearchResult);
	
	EatSpace();

	char* propertyValue = mNextChar;
	bool foundPropertyValue = false;
	c = PeekChar();
	while (c != -1)
	{
		if (c == '\n')
		{
			*mNextChar++ = '\0';
			foundPropertyValue = true;
			break;
		}
		else if (c == '\r')
			*mNextChar = '\0';

		GetChar();
		c = PeekChar();
	}

	if (foundPropertyValue)
		property->value = propertyValue;
	else
		property->value = NULL;

	return 0;
}