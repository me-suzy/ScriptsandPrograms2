/* $Id: clsURL.cpp,v 1.2 1999/02/21 02:22:27 josh Exp $ */
//
// File: clsUrl
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: See clsUrl.h
//

#include <string.h>
#include <ctype.h>
#include <stdlib.h>
#include "clsUrl.h"

int strcasecomp (const char * a, const char * b)
{
    int diff;
    for( ; *a && *b; a++, b++) 
	{
		if ((diff = tolower(*a) - tolower(*b)))
			return diff;
    }

    if (*a) 
		return 1;			// a was longer than b
    
	if (*b) 
		return -1;			// a was shorter than b 

    return 0;				// Exact match 
}


static long StripPort(char* hostName)
{
	char* numPos = strchr(hostName, ':');
	
	if (!numPos)
		return 0;

	// Terminate the host name - use the spot occupied by the ':'
	*numPos = '\0';
	++numPos;
	if (*numPos && isdigit(*numPos))
	{
		long num = atol(numPos);
		return num;
	}

	return 0;
}

// Some users input a host like:
// http://peewee@sugar-river.net/~peewee/germanplatereligious.jpg
// ..notice the '@' in the middle of the host name. Well, that isn't legal
// however, some browsers are smart enough to try and get a host name out
// of it anyway. It appears that the browsers just strip the characters
// before and including the '@', which is what we are going to do here.
//
// ...also some user input a host like:
// http://members.aol.com./inteli960/braclet.jpg
// ...notice the final '.' after "com". Well we are going to get rid of 
// it too.
static void FixupHostName(char* hostName)
{
	int len = strlen(hostName);
	if (len && hostName[len-1] == '.')
		hostName[len-1] = '\0';

	char* atPos = strchr(hostName, '@');
	
	if (!atPos)
		return;

	int length = strlen(atPos + 1);
	if (!length)
		return;

	memmove(hostName, atPos + 1, length + 1);
}

int clsUrlCracker::Crack(const char* toCrack, size_t len)
{
	if (len > kMaxInput) return -1;
	access = NULL;
	host = NULL;
	absolute = NULL;
	relative = NULL;
	fragment = NULL;
	port = 0;
	memset(mInput, 0, sizeof(mInput));
	memcpy(mInput, toCrack, len);

	char* p;
	char* after_access = mInput;

	// Look for fragment identifier

	if ((p = strchr(mInput, '#')) != NULL) 
	{
		*p++ = '\0';
		fragment = p;
	}


	//if ((p = strchr(mInput, ' ')) != 0) *p++ = '\0';    

	for (p = mInput; *p; p++) 
	{
		// Look for any whitespace
		if (isspace((int) *p)) 
		{
			char *orig = p;
			char *dest = p+1;

			while ((*orig++ = *dest++)) ;
		
			--p;
		}

		if (*p == '/' || *p == '#' || *p == '?')
			break;

		if (*p==':') 
		{
			*p = '\0';
			access = after_access; // Scheme has been specified

			after_access = p+1;

			if (0 == strcasecomp("URL", access)) 
			{
				access = NULL;  // Ignore IETF's URL: pre-prefix
			} 
			else 
				break;
		}
	}

	// Some people try and use backslashes instead of forward slashes
	// Lets convert them
	{
		char* pp = after_access;
		
		while (*pp)
		{
			if (*pp == '\\')
				*pp = '/';

			++pp;
		}
	}

	p = after_access;

	if (*p == '/')
	{
		if (p[1]=='/') 
		{
			host = p+2;		// host has been specified
			*p = '\0';			// Terminate access 
			p = strchr(host,'/');	// look for end of host name if any

			if(p) 
			{
				*p = '\0';			// Terminate host
				absolute = p+1;	// Root has been found
			}
		} 
		else 
		{
			absolute = p+1;		// Root found but no host
		}	    
	} 
	else 
	{
		relative = (*after_access) ? after_access : NULL; // zero for ""
	}


	if (host)
	{
		port = StripPort(host);
		FixupHostName(host);
	}

	return 0;
}

