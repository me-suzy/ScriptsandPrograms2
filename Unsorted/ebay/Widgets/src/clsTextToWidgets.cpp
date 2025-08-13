/* $Id: clsTextToWidgets.cpp,v 1.4 1999/02/21 02:28:43 josh Exp $ */
// Translates a page from text into parsed format (data dictionary).
#include "widgets.h"
#include "clsTextToWidgets.h"

// Ends the entire token, including any attributes.
static const char sEndToken = '>';
// Ends just the named portion of the token.
static const char *sEndTag = " \t\n\r>";

clsTextToWidgets::clsTextToWidgets(widgetDesignator *pAcceptableWidgets, unsigned long numAcceptable,
								   const char *pStartToken) 
	: mpOriginalText(NULL), 
	mpDataDictionary(NULL), 
	mDataDictionaryLength(0),
	mpMarketPlace(gApp->GetMarketPlaces()->GetCurrentMarketPlace()), 
	mWidgetHandler(mpMarketPlace, gApp),
	mpWidgetContext(mWidgetHandler.GetWidgetContext()), 
	mReverseBytes(false), 
	mpStartToken(pStartToken),
	mpAcceptable(pAcceptableWidgets),
	mNumAcceptable(numAcceptable)
{
}

clsTextToWidgets::~clsTextToWidgets()
{
	// The only pointer we own is the data dictionary.
	delete [] mpDataDictionary;
	mpOriginalText = NULL;
	mpMarketPlace = NULL;
	mpWidgetContext = NULL;
	mpStartToken = NULL;
	mpAcceptable = NULL;
}

// Helper function to compare two tags -- basically the same as
// strncasecmp, were such a function to exist portably.
#ifndef _MSC_VER
extern "C" int tolower(int);
#endif
static int compare_tags(const char *p1, const char *p2, unsigned long length)
{
    while (length--)
    {
        if (tolower(*p1) != tolower(*p2))
            return (int) tolower(*p1) - tolower(*p2);
        ++p1; ++p2;
    }

    return 0;
}

bool clsTextToWidgets::SetText(const char *pText)
{

	// If we already have text, or aren't given text, we can't do this.
	if (mpOriginalText || !pText)
		return false;

	mpOriginalText = pText;
	return true;
}

// This is where we do the bulk of our work, maybe.
const char *clsTextToWidgets::GetDataDictionary(int *pStoreLength)
{
    // Validate ourselves before doing this.
    // If we have no text, we can't do it.
	if (!mpOriginalText)
    {
        *pStoreLength = -1;
		return NULL;
    }

    // If we already have it done, don't bother doing it again.
    if (mpDataDictionary)
    {
        *pStoreLength = mDataDictionaryLength;
        return mpDataDictionary;
    }

    // Okay, _now_ we do some serious parsing.
    list<widgetEntry> lWidgets;
    clsDataPool lDataBlob;
    widgetHeader theWidgetHeader;

    const char *pCurrent;
    const char *pWalker;
    const char *pLastText;
    char c;
    eBayKnownWidgets theWidgetType;
    widgetEntry theWidget;
    vector<char *> theAttrList;
    clseBayWidget *pWidget;
    unsigned long textLength;

	const char *pLooper; // For finding the size of tags.
	char *pTagBuffer = NULL;
	char *pTagBufferSave = NULL;
	bool dummy;

    pLastText = mpOriginalText;
    pCurrent = mpOriginalText;
    c = mpStartToken[0];

    // Now we loop until we're all done.
    for ( ; ; )
    {
        // Find the potential beginning of a tag.
        pCurrent = strchr(pCurrent, c);

        // If we have nothing here, we're done, so breakout!
        if (!pCurrent)
            break;

        // Before we do anything, let's set pWalker to indicate
        // we might have a live on here, in which case we'll need
        // to save the text...
        pWalker = pCurrent;

        // Verify its taggyness
        if (compare_tags(pCurrent, mpStartToken, strlen(mpStartToken)))
        {
            // It's not a start tag for us, so, we increment to get past
            // the beginning, and then continue.
            ++pCurrent;
            continue;
        }

        // Okay, so we have a tag. Let's increment to get past the start
        // token, and then we'll do some interesting things with this...
        pCurrent += strlen(mpStartToken);

        // Now, we know that we're in a tag (almost certainly), what we
        // have to do now is match it against a known tag from our
        // acceptable list.
        if (!FindTokenType(pCurrent, &theWidgetType))
        {
            // If we're here, then it wasn't a tag after all,
            // surprisingly enough.
            // So, we just continue, but first we back up as if we
            // hadn't matched the start token at all.
            pCurrent -= strlen(mpStartToken);
            // Of course, now we have to increment to get past the beginning,
            // lest we loop.
            ++pCurrent;
            continue;
        }

        // Now, we know we're in a tag. So... let's first save whatever
        // text existed, if any, and then proceed.
        // At the moment, pWalker points to the beginning of this tag,
        // and pLastText points to the first unsaved character of text.

        // Find the length.
        textLength = pWalker - pLastText;
        // If we have no length, don't bother making a widget.
        if (textLength)
        {
            // We do have some length, though, so record the widget.
            theWidget.widgetType = (int32_t) wtTextWidget;
            theWidget.blobOffset = (int32_t) lDataBlob.AddStringN(pLastText,
                textLength);
            // And put it back into lWidgets.
            lWidgets.push_back(theWidget);
        }

        // Now we know we're in a tag, and we also know what type of tag
        // it is. So, now we separate out the attributes.
        // Because of the positioning, we're now on top of the tag name --
        // that's okay, because we have specified that this is always
        // the first attribute in a list.

		// First, we find out how long it is, in total.
		pLooper = pCurrent;
		while (FindTagEnd(&pLooper, &dummy)) ;

		// And allocate space for the attributes and copy the string in.
		pTagBuffer = new char [pLooper - pCurrent + 1];
		pTagBufferSave = pTagBuffer;
		memcpy(pTagBuffer, pCurrent, pLooper - pCurrent);
		pTagBuffer[pLooper - pCurrent] = '\0';

		// FindAttribute will fill in our list for us, but not allocate
		// anything...
        // When it returns false, we're out of attributes, and we should
		// go sit on pLooper.
        // We pass it the list to put the attribute strings there.
        while (FindAttribute(&pTagBuffer, &theAttrList)) ;
		pCurrent = pLooper;

        // Now, we're done with that tag. Let's convert it to blobbiness.
        pWidget = mWidgetHandler.GetWidget(theWidgetType);

        // Set the arguments...
        pWidget->SetParams(&theAttrList);
        // Fill the widget structure...
        theWidget.widgetType = (int32_t) theWidgetType;
        // Make a blob...
        theWidget.blobOffset = (int32_t) pWidget->GetBlob(&lDataBlob, mReverseBytes);
        // And push the widget back into lWidgets.
        lWidgets.push_back(theWidget);

        // And release the widget.
        mWidgetHandler.ReleaseWidget(pWidget);

        // And release the attributes.
		delete [] pTagBufferSave;
        theAttrList.clear();

        // Now, we're done with that tag, let's go on to the next, shall we?
        // First, set the first unsaved text character...
        pLastText = pCurrent;
    }

    // Now that we're done with that, if we have any text left, add
    // another text widget.
    if (*pLastText)
    {
        theWidget.widgetType = (int32_t) wtTextWidget;
        theWidget.blobOffset = (int32_t) lDataBlob.AddString(pLastText);
        lWidgets.push_back(theWidget);
    }

    // Well, we now have all of the information necessary to build a data
    // dictionary.

    // First, fill the header.
    theWidgetHeader.numWidgets = (int32_t) lWidgets.size();
    theWidgetHeader.widgetOffset = (int32_t) sizeof (theWidgetHeader);
    theWidgetHeader.textOffset = (int32_t) (theWidgetHeader.widgetOffset +
        (int32_t) (theWidgetHeader.numWidgets * sizeof (widgetEntry)));
    theWidgetHeader.originalText = (int32_t) lDataBlob.AddString(mpOriginalText);
    theWidgetHeader.byteOrder = (int32_t) 0x01;

    // Now we should know the size.
    mDataDictionaryLength = sizeof (theWidgetHeader) +
        (theWidgetHeader.numWidgets * sizeof (widgetEntry)) +
        lDataBlob.GetSafeWriteSize();

    // Now we can allocate and fill it.
    mpDataDictionary = new char [mDataDictionaryLength];

    memcpy(mpDataDictionary, &theWidgetHeader, sizeof (theWidgetHeader));

    // Do the widgets.
    list<widgetEntry>::iterator i;
    char *pLoc;
    pLoc = mpDataDictionary + sizeof (theWidgetHeader);
    for (i = lWidgets.begin(); i != lWidgets.end(); ++i)
    {
        memcpy(pLoc, &(*i), sizeof (widgetEntry));
        pLoc += sizeof (widgetEntry);
    }

    // And copy the data blob.
    memcpy(pLoc, lDataBlob.GetBuffer(), lDataBlob.GetSafeWriteSize());

    // And it's done, so return the data dictionary.
    *pStoreLength = mDataDictionaryLength;
    return mpDataDictionary;
}

bool clsTextToWidgets::FindTokenType(const char *pStr, 
                                     eBayKnownWidgets *pWidgetType) const
{
    int length;
    const char *pWalk;
    const widgetDesignator *pCurrentWidget;
    unsigned long i;

    // First of all, if we never end this tag, it's not a valid tag at all.
    if (!strchr(pStr, sEndToken))
        return false;

    // Now, find how long our little token is.
    pWalk = strpbrk(pStr, sEndTag);
	assert(pWalk);
    length = pWalk - pStr;

    // Now we can just walk our designators and try to find it.
    for (i = 0, pCurrentWidget = mpAcceptable; i < mNumAcceptable; ++i, ++pCurrentWidget)
    {
        // If they're not the same length, they obviously can't
        // match.
        if (pCurrentWidget->stringLength != length)
            continue;

        if (!compare_tags(pCurrentWidget->widgetString, pStr, length))
        {
            // Hey, they matched!
            *pWidgetType = pCurrentWidget->widgetType;
            return true;
        }
    }

    return false;
}

bool clsTextToWidgets::FindTagEnd(const char **ppStart, bool *pfixQuotes /* OUT */) const
{
	const char *pEnd;
	const char *pQuote;

	bool fixQuotes = false;

	// First, test for endness.
	if (**ppStart == sEndToken)
	{
		*ppStart = *ppStart + 1;
		return false;
	}

	// Now, test for exist.
	if (!(**ppStart))
		return false;

    // Okay, so we're not at the end.  Let's find the end of this token, then.
    pEnd = strpbrk(*ppStart, sEndTag);
	assert(pEnd);
    pQuote = strchr(*ppStart, '\"');

    // If we have a quote, we just end at the quote after this, but we escape any backslashed quotes.
    if (pQuote && pQuote < pEnd)
	{
        pEnd = strchr((pQuote + 1), '\"');
		if (pEnd)
		{
			const char *pLastLook = NULL;
			while (*(pEnd - 1) == '\\')
			{
				fixQuotes = true;
				pLastLook = pEnd;
				pEnd = strchr(pEnd + 1, '\"');
			}
			if (!pEnd)
				pEnd = pLastLook;
			else
				++pEnd;

			++pEnd;
			if (*pEnd == '\"')
				++pEnd; // If we got here, we had a backslash quote just before our last quote,
				// so we neglected to increment far enough...
		}
	}
	
    // Oh my. Now, this is a problem -- we're supposed to be assured that
    // this can't happen. Hmm.
    // Well, let's just make this all an attribute and pretend we
    // didn't notice...
    if (!pEnd)
        pEnd = *ppStart + strlen(*ppStart);

    // Set the end.
    if (!*pEnd || *pEnd == sEndToken)
        *ppStart = pEnd;
    else
        *ppStart = pEnd + 1;

	*pfixQuotes = fixQuotes;

    return true;
}

// We know here, because of our agreement with the caller of this
// function, that what we're getting is going to be inside a tag.
// We aren't allowed to set *ppStr unless we find a complete attribute.
// Attributes are separated by characters in sEndTag, unless they have
// a quote, in which case they don't end until the quote is closed.
// If we find that **ppStr is sEndToken, we've promised to
// increment *ppStr 1, and return false.
// Otherwise, we push into pArgList, and return true.
bool clsTextToWidgets::FindAttribute(char **ppStr, vector<char *> *pArgList) const
{
    char *pEnd;
    char *pAttribute;
    int length;
	bool fixQuotes = false;

	pEnd = *ppStr;

	if (!FindTagEnd((const char **) &pEnd, &fixQuotes))
	{
		*ppStr = pEnd;
		return false;
	}

    length = pEnd - *ppStr - 1;
	if (*pEnd == sEndToken)
		++length;

    if (length)
    {
        // Now, we set up pAttribute.
        pAttribute = *ppStr;
		pAttribute[length] = '\0';
		if (fixQuotes)
		{
			const char *pWalk = *ppStr;
			const char *pLastCopy;

			pWalk = *ppStr;
			pLastCopy = pWalk;
			
			// We know there's at least one quote because of fixQuotes.
			while ((pWalk = strchr(pWalk + 1, '\"')))
			{
				if (*(pWalk - 1) == '\\')
				{
					memmove(pAttribute, pLastCopy, (pWalk - 1) - pLastCopy);
					pAttribute += (pWalk - 1) - pLastCopy;
					*pAttribute = '\"';
					++pAttribute;
					pLastCopy = pWalk + 1;
				}
			}
			memmove(pAttribute, pLastCopy, strlen(pLastCopy) + 1);
		}

        // Push it into the arguments list.
        pArgList->push_back(*ppStr);
    }

	*ppStr = pEnd;

    return true;
}
