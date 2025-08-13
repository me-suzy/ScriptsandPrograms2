//
//	File:		clsCategoryMessages.h
//
// Class:	clsCategoryMessages
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//			Manages clsCategoryMessage objects
//
// Modifications:
//				- 04/13/99 mila		- Created
//

#ifndef CLSCATEGORYMESSAGES_INCLUDED

#include "clsCategory.h"
#include "clsMessage.h"
#include "clsCategoryMessage.h"


class clsCategoryMessages
{

public:

	// Default constructor
	clsCategoryMessages(clsMarketPlace *pMarketPlace)
		: mpMarketPlace(pMarketPlace)
	{
	}

	// Destructor
	virtual ~clsCategoryMessages()
	{
	}

	//
	// AddCategoryMessage
	//
	bool		AddCategoryMessage(clsCategoryMessage * const pCategoryMessage) const;

	bool		AddCategoryMessage(CategoryId categoryId,
								   MessageId messageId) const;

	bool		AddCategoryMessage(CategoryId categoryId,
								   const char *pMessageName) const;

	//
	// DeleteCategoryMessage
	//
	void		DeleteCategoryMessage(CategoryId categoryId,
									  MessageId messageId) const;

	//
	// UpdateCategoryMessage
	//
	bool		UpdateCategoryMessage(CategoryId categoryId,
									  MessageId messageId,
									  clsCategoryMessage * const pCategoryMessage) const;

	//
	// GetCategoryMessage
	//
	clsCategoryMessage *GetCategoryMessage(CategoryId categoryId,
										   MessageId messageId) const;

	//
	// GetCategoryMessagesByCategoryId
	//
	void		GetCategoryMessagesByCategoryId(CategoryId categoryId,
												CategoryMessageVector * const pvCategoryMessages) const;

	//
	// GetCategoryMessagesByMessageId
	//
	void		GetCategoryMessagesByMessageId(MessageId messageId,
											   CategoryMessageVector * const pvCategoryMessages) const;
	//
	// GetMessage
	//
	clsMessage *GetMessage(CategoryId categoryId,
						   MessageId messageId) const;

	//
	// GetMessages
	//
	void		GetMessages(CategoryId categoryId,
							MessageType messageType,
							MessageVector *const pvMessages,
							bool useCache = false) const;

	//
	// GetMessageText
	//
	void		GetMessageText(CategoryId categoryId,
							   MessageType messageType,
							   vector<char *> *pvMessageText,
							   bool useCache = false) const;

protected:

private:

	clsMarketPlace *	mpMarketPlace;	// for navigation

};


#define CLSCATEGORYMESSAGES_INCLUDED
#endif // CLSCATEGORYMESSAGES_INCLUDED

