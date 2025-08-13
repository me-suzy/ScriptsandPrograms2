/*	$Id: clsGiftOccasion.h,v 1.2 1998/12/06 05:31:18 josh Exp $	*/
//
//	File:		clsGiftOccasion.h
//
// Class:	clsGiftOccasion
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents a gift occasion
//
// Modifications:
//				- 10/27/98 mila		- Created
//

#ifndef CLSGIFTOCCASION_INCLUDED

#include "eBayTypes.h"

typedef enum
{
	GiftOccasionFlagActive			= 0x1,
} GiftOccasionFlag; 



// Some convienent macros
#define STRING_VARIABLE(name)		\
private:							\
	char	*mp##name;				\
public:								\
	char	*Get##name();			\
	void	Set##name(char *pNew);	

#define INT_VARIABLE(name)			\
private:							\
	int		m##name;				\
public:								\
	int		Get##name();			\
	void	Set##name(int new_value);

#define BOOL_VARIABLE(name)			\
private:							\
	bool	m##name;				\
public:								\
	bool	Get##name();			\
	void	Set##name(bool new_value);

#define LONG_VARIABLE(name)			\
private:							\
	long	m##name;				\
public:								\
	long	Get##name();			\
	void	Set##name(long new_value);


class clsGiftOccasion
{
	public:
		// Vanilla CTOR and DTOR, as required
		clsGiftOccasion();

		// changed to include ebay_user_info table also
		~clsGiftOccasion();

		//
		// Stupid Constructor
		//
		clsGiftOccasion(int id);

		// Short constructor - from ebay_users table only
		clsGiftOccasion(MarketPlaceId marketplace,
						int id,
						char *pName,
						char *pGreeting,
						char *pHeader,
						char *pFooter,
						int flags);
		
		void	Set(MarketPlaceId marketplace,
					int id,
					char *pName,
					char *pGreeting,
					char *pHeader,
					char *pFooter,
					int flags);

		// Dirty
		bool	IsDirty();
		void	SetDirty(bool dirty);

		void	UpdateGiftOccasion();

		//
		// The functions to set the user flags always
		// return the old flags.
		int GetAllGiftOccasionFlags();
		int SetAllGiftOccasionFlags(int flags);

		bool clsGiftOccasion::IsActive();

		bool GetOneGiftOccasionFlag(GiftOccasionFlag bit);
		int SetSomeGiftOccasionFlags(bool on, int mask);

private:

		INT_VARIABLE(MarketPlaceId);		// marketplace id
		INT_VARIABLE(Id);					// type of occasion
		STRING_VARIABLE(Name);				// name of occasion
		STRING_VARIABLE(Greeting);			// greeting for occasion
		STRING_VARIABLE(Header);			// header image filename
		STRING_VARIABLE(Footer);		    // footer image filename
		INT_VARIABLE(Flags);				// flags for status et al.

		void	ClearAll();

		//
		// mDirty tells us if we've been modified
		//
		bool			mDirty;

};

//
// This thing is used for STL
//
class clsGiftOccasionPtr
{
	public:
		//
		// CTOR
		//
		clsGiftOccasionPtr()
		{
			mpOccasion	=	NULL;
		}

		clsGiftOccasionPtr(clsGiftOccasion *pOccasion)
		{
			mpOccasion	=	pOccasion;
		}

		~clsGiftOccasionPtr()
		{
			return;
		}

		clsGiftOccasion		*mpOccasion;
};

typedef list<clsGiftOccasionPtr> GiftOccasionList;
typedef vector<clsGiftOccasion *> GiftOccasionVector;


#undef STRING_VARIABLE
#define CLSGIFTOCCASION_INCLUDED 1
#endif /* CLSGIFTOCCASION_INCLUDED */



