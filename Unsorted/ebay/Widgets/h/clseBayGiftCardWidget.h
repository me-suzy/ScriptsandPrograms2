/*	$Id: clseBayGiftCardWidget.h,v 1.2 1998/12/06 05:22:33 josh Exp $	*/
//
//	File:	clseBayGiftCardWidget.h
//
//	Class:	clseBayGiftCardWidget
//
//	Author:	Mila Bird
//
//	Function:
//			Shows gift card, including greeting, graphic of wrapped gift,
//			and sender's name.
//			This widget was derived from clseBayWidget by overriding
//			the following routines:
//				* EmitHTML()
//
//			Example code of how to invoke the clseBayGiftCardWidget:
//
//				clseBayGiftCardWidget *idw = new clseBayGiftCardWidget(mpMarketPlace);
//				idw->SetColor("#FFECEA");
//				idw->EmitHTML(mpStream);
//				delete idw;
//
// Modifications:
//				- 10/24/98	mila	- Created
//				- 11/03/98	mila	- changed type of mOccasion to int from
//									  GiftOccasionEnum
//
#ifndef CLSEBAYGIFTCARDWIDGET_INCLUDED
#define CLSEBAYGIFTCARDWIDGET_INCLUDED

#include <time.h>

#include "clsWidgetHandler.h"
#include "clseBayWidget.h"

class clseBayGiftCardWidget : public clseBayWidget
{

public:

	// Needs marketplace
	clseBayGiftCardWidget(clsMarketPlace *pMarketPlace);
	clseBayGiftCardWidget(clsWidgetHandler *pWidgetHandler, clsMarketPlace *pMarketPlace);

	// Empty dtor
	virtual ~clseBayGiftCardWidget() {};

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayGiftCardWidget(pMarketPlace); }
	
	// Emit the HTML for this widget to the specified stream.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

//	void SetOccasion(clsGiftOccasion *pOccasion)	{ mpOccasion = pOccasion; }
	void SetOccasion(int occasion)					{ mOccasion = occasion; }
	void SetGreeting(char *pGreeting)				{ mpGreeting = pGreeting; }
	void SetColor(char *pColor)						{ strncpy(mpColor, pColor, sizeof(mpColor) - 1); }
	void SetSenderName(char *pSenderName)			{ mpSenderName = pSenderName; }
	void SetRecipientName(char *pRecipientName)		{ mpRecipientName = pRecipientName; }
	void SetSenderUserId(char *pUserId)				{ mpSenderUserId = pUserId; }
	void SetItem(int item)							{ mItem = item; }
	void SetOpenDate(time_t openDate)				{ mOpenDate = openDate; }
	void SetImageFilename(char *filename)			{ mpFilename = filename; }
	void SetEncodeItemInURL(bool encode)			{ mEncodeItemInURL = encode; }
	void SetEncodeOpenDateInURL(bool encode)		{ mEncodeOpenDateInURL = encode; }

	// set parameters using a vector of strings, with the first string being
	// the widget tagname.
	// the convention is that this routine should handle any parameters it
	// understands, erase (and delete) them from the vector, then call the parent
	// class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);

protected:
	virtual bool EmitHTML(ostream *pStream, clsWidgetHandler *)
	{ return EmitHTML(pStream); }

private:
//	clsGiftOccasion		*mpOccasion;		// occasion for card
	char				mpColor[32];		// background color; default = ""
	int					mOccasion;			// occasion for giving card
	char *				mpGreeting;			// greeting for recipient
	char *				mpSenderUserId;		// user id of sender
	char *				mpSenderName;		// card sender's name
	char *				mpRecipientName;	// card recipient's name
	int					mItem;				// item number for accompanying gift, if any
	time_t				mOpenDate;			// earliest date to open accompanying gift, if any
	char *				mpFilename;			// filename for gift pic
	bool				mEncodeItemInURL;	// if true, encode item # in gift item URL
	bool				mEncodeOpenDateInURL;	// if true, encode open date in gift item URL
};

#endif // CLSEBAYGIFTCARDWIDGET_INCLUDED
