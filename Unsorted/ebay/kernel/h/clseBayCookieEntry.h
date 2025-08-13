/*	$Id: clseBayCookieEntry.h,v 1.4 1998/09/30 02:58:57 josh Exp $	*/
//
//      File:           clseBayCookieEntry.h
//
//      Class:          clseBayCookieEntry
//
//      Author:         Wen Wen (wen@ebay.com)
//
//      Function:
//                      a basic class for contructing a cookie
//
//      Modifications:
//                      - 07/31/98 Wen - Created
//
#ifndef __CLSEBAYCOOKIEENTRY_INCLUDE__
#define __CLSEBAYCOOKIEENTRY_INCLUDE__

#define COOKIE_INT_VARIABLE(name)	\
protected:							\
	int		m##name;				\
public:								\
	int		Get##name() const;		\
	void	Set##name(int new_value);

#define COOKIE_BOOL_VARIABLE(name)	\
protected:							\
	bool	m##name;				\
public:								\
	bool	Get##name() const;		\
	void	Set##name(bool new_value);

#define COOKIE_TIME_VARIABLE(name)	\
protected:							\
	time_t	m##name;				\
public:								\
	time_t	Get##name() const;		\
	void	Set##name(time_t new_value);

#include <time.h>

class clseBayCookieEntry
{
public:
	clseBayCookieEntry();
	~clseBayCookieEntry();

	void	SetInfo(int Version,
					int Id,
					const char*	pValue,
					bool NeedCrypt,
					int	ExpirationTime
					);

	bool	IsValid();

	void	SetValue(const char* pOrgValue);
	const char*	GetValue() const;

	void	SetPackedValue(int Version, int id, const char* pExValue, int Length);
	const char*	GetPackedValue();

	bool IsExpired();
	bool NeedCrypt() { return mNeedCrypt; }


	COOKIE_INT_VARIABLE(Version);
	COOKIE_INT_VARIABLE(Id);
	COOKIE_INT_VARIABLE(Length);
	COOKIE_TIME_VARIABLE(CreationTime);
	COOKIE_TIME_VARIABLE(ExpirationTime);

protected:
	void	BuildPackedValue();
	void	ParsePackedValue();
	char*	GetSalt();
	void	SetExpirationForSessionCookie();

	char*	mpValue;
	char*	mpPackedValue;

	bool	mNeedCrypt;
};

#ifndef _NO_STL
typedef	vector<clseBayCookieEntry*>	CookieEntryVector;
#endif // _NO_STL

#endif // __CLSEBAYCOOKIEENTRY_INCLUDE__
