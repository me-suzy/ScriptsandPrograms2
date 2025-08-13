/*	$Id: clseBayUserDemoInfoWidget.h,v 1.2 1998/10/16 01:01:18 josh Exp $	*/
//
//	File:	clseBayUserDemoInfoWidget.h
//
//	Class:	clseBayUserDemoInfoWidget
//
//	Author:	Craig Huang (chuang@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 11/20/97 Craig Huang - Created
//
#ifndef CLSEBAYUSERDEMOINFOWIDGET_INCLUDED
#define CLSEBAYUSERDEMOINFOWIDGET_INCLUDED


#include "clseBayTableWidget.h"
#include <vector.h>
#include "clsCategories.h"

class clsUsers;
class clsUser;
class clsUserCodes;
class clsCategories;
class clseBayUserDemoInfoWidget : public clseBayWidget
{
public:

	// Stats widget requires having access to the marketplace and the app
	//  (for querying the database of items).
	clseBayUserDemoInfoWidget(clsMarketPlace *pMarketPlace, clsApp *pApp, clsCategories	*pclsCategory, CategoryVector *vCategories);

	// Empty dtor.
	virtual ~clseBayUserDemoInfoWidget();
	virtual bool EmitHTML(ostream *pStream);
	bool EmitHTML(ostream *pStream, clsUser * pUser);
	

protected:
	// Get the stats by querying the database
	virtual bool Initialize();

	// Emit the HTML for cell n, including the <TD> and </TD> tags.
	

private:
	clsUsers	*mpUsers;	
//	clsUser		*mpUser;	
	clsUserCodes	*mpUserCodes;
	clsCategories	*mpCategories;
	CategoryVector  *mvCategories;
};

#define CLSEBAYUSERDEMOINFOWIDGET 1
#endif // CLSEBAYUSERDEMOINFOWIDGET_INCLUDED
