/*	$Id: eBayDebug.h,v 1.2 1998/06/23 04:29:10 josh Exp $	*/
//
//	File:		eBayDebug.h
//
// Class:	None
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Contains the DEBUG macro for eBay
//
// Modifications:
//				- 02/10/97 michael	- Created
//
#define DEBUGGING 1
#ifdef DEBUGGING
void eBayWhere(char flag, char *pFile, const int line);

#define EWHERE(flg) eBayWhere(flg, __FILE__, __LINE__);

#define EDEBUG eBayDebug
void eBayDebug(char flag, char *pFormat, ...);

#else
#define EWHERE 
#define EDEBUG
#endif /* DEBUG */ 
