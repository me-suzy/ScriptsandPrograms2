/*	$Id: clsWidgetContext.h,v 1.2 1998/10/16 01:00:44 josh Exp $	*/
//
// Class Name:		clsWidgetContext
//
// Description:		A context object for widgets, stored in the widget handler.
//
// Author:			Chad Musick
//
#ifndef CLSWIDGETCONTEXT_INCLUDED
#define CLSWIDGETCONTEXT_INCLUDED

#include "vector.h"

// This macro makes GetX() and SetX() functions in the class.
#define NAMECONTEXT_VALUE(type, name)								\
	type Get##name() { return (type) GetOpaqueValue(wcv##name); }	\
	void Set##name(type x) { SetOpaqueValue(wcv##name, (void *) x); }

// The enum names of the things.
typedef enum
{
	wcvBDTallyObject, // A tally list object, used in BDReports for clseBayBD*Widget
	wcvCategories,	  // A clsCategories object, used anywhere.
	wcvBDPageName,	  // A const char * of the current page 'name'.
	wcvCurrentURL,	  // A const char * of the current URL.
    wcvNumViews,      // A long * of the number of views on this page.
    wcvUser,          // A clsUser * of the current user.
} WidgetContextValues;

// Class forward references for all classes used in NAMECONTEXT_VALUE as type
class clsBDTallyLists;
class clsCategories;
class clsUser;

class clsWidgetContext
{
public:
	clsWidgetContext();
	~clsWidgetContext();

	// SetOpaqueValue and GetOpaqueValue use common storage
	// for all objects -- that is, nothing is stored in a special
	// type, everything is void * in a vector, and referrered to
	// by enum. The NAMECONTEXT_VALUE macro will make casting
	// functions for getting and setting.
	inline void SetOpaqueValue(WidgetContextValues type, void *data);
	inline void *GetOpaqueValue(WidgetContextValues type);

	NAMECONTEXT_VALUE(clsBDTallyLists *, BDTallyObject);
	NAMECONTEXT_VALUE(clsCategories *, Categories);
	NAMECONTEXT_VALUE(const char *, BDPageName);
	NAMECONTEXT_VALUE(const char *, CurrentURL);
    NAMECONTEXT_VALUE(long *, NumViews);
    NAMECONTEXT_VALUE(clsUser *, User);

private:
	vector<void *> *mpvValues;
};

inline void clsWidgetContext::SetOpaqueValue(WidgetContextValues type,
											 void *data)
{
	int i;

	if (mpvValues->size() <= (int) type)
	{
		i = (int) type - mpvValues->size() + 1;
		while (i--)
			mpvValues->push_back(NULL);
	}

	(*mpvValues)[(int) type] = data;
}

inline void *clsWidgetContext::GetOpaqueValue(WidgetContextValues type)
{
	if (mpvValues->size() <= (int) type || (int) type < 0)
		return NULL;

	return (*mpvValues)[(int) type];
}

inline clsWidgetContext::clsWidgetContext()
{
	mpvValues = new vector<void *>;
}

// The destructor. Does not delete any stored objects, which are
// the responsibility of the person who set them.
inline clsWidgetContext::~clsWidgetContext()
{
	if (mpvValues->size())
		mpvValues->erase(mpvValues->begin(), mpvValues->end());

	delete mpvValues;
}

#endif /* CLSWIDGETCONTEXT_INCLUDED */