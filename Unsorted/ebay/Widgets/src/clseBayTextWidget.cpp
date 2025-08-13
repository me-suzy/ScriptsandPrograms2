/*	$Id: clseBayTextWidget.cpp,v 1.3 1998/12/06 05:23:17 josh Exp $	*/
#include "widgets.h"
#include "clseBayTextWidget.h"

#include <string.h>

clseBayTextWidget::clseBayTextWidget(clsMarketPlace *pMarketPlace,
									 clsApp *pApp) :
	clseBayWidget(pMarketPlace, pApp), mpText(NULL)
{
	return;
}

clseBayTextWidget::~clseBayTextWidget()
{
	delete [] mpText;
}

bool clseBayTextWidget::EmitHTML(ostream *pStream)
{
	if (!mpText)
		return false;

	*pStream << mpText;
	return true;
}

// The setter for things retrieved from the database.
void clseBayTextWidget::SetParams(const void *pData, const char * /* pTextBase */, bool /* mFixBytes */)
{
	delete [] mpText;

	mpText = new char [strlen((const char *) pData) + 1];
	strcpy(mpText, (const char *) pData);
	return;
}

void clseBayTextWidget::SetParams(int length, const void *data)
{
	delete [] mpText;

	mpText = new char [length];
	memcpy(mpText, data, length);

	return;
}

void clseBayTextWidget::GetBlob(int *pLength, void **ppData)
{
	if (!mpText)
	{
		*pLength = 0;
		*ppData = NULL;
		return;
	}

	*pLength = strlen(mpText) + 1;
	*ppData = (void *) new char [*pLength];
	memcpy(*ppData, mpText, *pLength);

	return;
}