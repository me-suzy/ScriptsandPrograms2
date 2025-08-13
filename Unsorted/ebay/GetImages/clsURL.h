/* $Id: clsURL.h,v 1.2 1999/02/21 02:22:28 josh Exp $ */
//
// File: clsUrl
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: Breaks an url into its component parts
//

#ifndef clsUrl_h
#define clsUrl_h

struct clsUrlCracker
{
    char* access;
    char* host;
	long port;
    char* absolute;
    char* relative;
    char* fragment;

	enum { kMaxInput = 255 };

	char mInput[kMaxInput + 1];

	int Crack(const char* toCrack, size_t len);
};

#endif