/*	$Id: clsAnnouncement.h,v 1.2.718.1 1999/08/01 03:02:04 barry Exp $	*/
//
//	File:	clsAnnouncement.h
//
//	Class:	clsAnnouncement
//
//	Author:	tini (tini@ebay.com)
//
//	Function:
//
//		Represents an announcement
//
// Modifications:
//				- 04/02/97 michael	- Created
//
#ifndef CLSANNOUNCEMENT_INCLUDED

#include "eBayTypes.h"


// Some convienent macros
#define ANSTRING_VARIABLE(name)				\
private:									\
	char	*mp##name;						\
public:										\
	char	*Get##name();					\
	void	Set##name(char *pNew);

#define ANINT_VARIABLE(name)				\
private:									\
	int		m##name;						\
public:										\
	int		Get##name();					\
	void	Set##name(int new_value);

#define  ANLONG_VARIABLE(name)				\
private:									\
	long	m##name;						\
public:										\
	long	Get##name();					\
	void	Set##name(long new_value);


class clsAnnouncement
{
	public:
	// default partner is PARTNER_EBAY
	clsAnnouncement(MarketPlaceId marketplace, int id, int where, long moddate,
					char *pType, char *pDesc, int partnerId=1, int siteId=0);

	~clsAnnouncement();

	char *GetModDateAsString();

	ANINT_VARIABLE(MarketPlaceId);
	ANINT_VARIABLE(Id);
	ANINT_VARIABLE(Location);
	ANLONG_VARIABLE(LastModified);
	ANSTRING_VARIABLE(Code);
	ANSTRING_VARIABLE(Desc);
	ANINT_VARIABLE(PartnerId);
	ANINT_VARIABLE(SiteId);

};

// Convienent Typedefs
typedef vector<clsAnnouncement *> AnnouncementVector;

#define CLSANNOUNCEMENT_INCLUDED
#endif /* CLSANNOUNCEMENT_INCLUDED */



