/*	$Id: clsPSHeaderWidget.h,v 1.2 1999/05/19 02:34:07 josh Exp $	*/
//
//	File:	clsPSHeaderWidget.h
//
//	Class:	clsPSHeaderWidget
//
//	Author:	Wen Wen
//
//	Function:
//		This class displays a NetMind Logo
//							
// Modifications:
//				- 2/2/99	Wen - Created
//
#ifndef CLSPSHEADERWIDGET_INCLUDED
#define CLSPSHEADERWIDGET_INCLUDED

class clsPSHeaderWidget
{
public:

	// Hot item widget requires having access to the marketplace
	clsPSHeaderWidget(clsMarketPlace *pMarketPlace);
	~clsPSHeaderWidget();

	void EmitHTML(ostream* pStream, const char* pHeader);

protected:
	clsMarketPlace*	mpMarketPlace;
};

#endif // CLSPSHEADERWIDGET_INCLUDED
