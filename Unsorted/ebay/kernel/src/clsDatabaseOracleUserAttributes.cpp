/*	$Id: clsDatabaseOracleUserAttributes.cpp,v 1.6 1998/12/06 05:32:03 josh Exp $	*/
//
//	File:	clsDatabaseUserAttributes.cpp
//
//	Class:	clsDatabaseOracle
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//		Methods relating to user attributes
//
// Modifications:
//				- 10/25/97 michael	- Created

#include "eBayKernel.h"

static const char *SQL_GetUserAttribute =
 "select	boolean_value,					\
			number_value,					\
			text_value						\
	from	ebay_user_attributes			\
	where		user_id = :id				\
		and		attribute_id = :aid";


void clsDatabaseOracle::GetUserAttribute(int user_id,
										 int attribute_id,
										 bool *pGotBoolResponse,
										 bool *pBoolResponse,
										 bool *pGotNumberResponse,
										 float *pNumberResponse,
										 bool *pGotTextResponse,
										 char **ppTextResponse)
{
	char		boolResponse[2];
	sb2			boolResponse_ind		= 0;
	float		numberResponse;
	sb2			numberResponse_ind		= 0;
	char		textResponse[257];
	sb2			textResponse_ind		= 0;

	// Do our cursor thing
	OpenAndParse(&mpCDAOneShot, SQL_GetUserAttribute);

	// Now, the binds
	Bind(":id", &user_id);
	Bind(":aid", &attribute_id);

	Define(1, boolResponse, sizeof(boolResponse),
			&boolResponse_ind);
	Define(2, &numberResponse, &numberResponse_ind);
	Define(3, textResponse, sizeof(textResponse),
				  &textResponse_ind);

	// Git it
	ExecuteAndFetch();

	// check for no rows found!
	if (CheckForNoRowsFound())
	{
		*pGotBoolResponse	= false;
		*pGotNumberResponse	= false;
		*pGotTextResponse	= false;

		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// See what we got
	if (boolResponse_ind == -1)
	{
		*pGotBoolResponse	= false;
	}
	else
	{
		*pGotBoolResponse	= true;
		*pBoolResponse		= boolResponse[0] ? true : false;
	}

	if (numberResponse_ind == -1)
	{
		*pGotNumberResponse	= false;
	}
	else
	{
		*pGotNumberResponse	= true;
		*pNumberResponse	= numberResponse;
	}

	if (textResponse_ind == -1)
	{
		*pGotTextResponse	= false;
	}
	else
	{
		*pGotTextResponse		= true;
		*ppTextResponse			= new char[strlen(textResponse) + 1];
		memcpy(*ppTextResponse, textResponse, strlen(textResponse));
		*(*ppTextResponse + strlen(textResponse))	= '\0';
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);	

	return;
}


//
// SetUserAttribute (Boolean)
//
//	This routine will insert, or, if the row is already present,
//	update it. Sooo, the implication here is that if multiple
//	responses aren't allowed, it's up to the client to enforce
//	that.
//
static const char *SQL_InsertBooleanAttribute = 
"insert into ebay_user_attributes					\
	(	user_id,									\
		attribute_id,								\
		first_entered,								\
		last_updated,								\
		boolean_value								\
	)												\
 values												\
	(	:id,										\
		:aid,										\
		sysdate,									\
		sysdate,									\
		:response									\
	)";

static const char *SQL_UpdateBooleanAttribute = 
"update ebay_user_attributes						\
	set		boolean_value	=	:response,			\
			last_updated		= sysdate			\
	and		user_id				= :id				\
	and		attribute_id		= :aid";

void clsDatabaseOracle::SetUserAttributeValue(int user_id,
										 int attribute_id,
										 bool response)
{
	int		rc;
	char	charResponse[2];


	// Set up the bool, fool
	if (response)
	{
		charResponse[0]	= '1';
	}
	else
	{
		charResponse[0]	= '0';
	}

	charResponse[1]	= '\0';


	// Open....
	OpenAndParse(&mpCDAOneShot,
				 SQL_InsertBooleanAttribute);

	// Bind
	// Now, the binds
	Bind(":id", &user_id);
	Bind(":aid", &attribute_id);
	Bind(":response", charResponse);

	// Let's try it...
	// Call oexn directly
	rc	= oexec((cda_def *)mpCDACurrent);

	// If we got a rc == -9 then it's probably an integrity
	// violation, and we need to UPDATE a row, instead of 
	// adding it.
	if (rc == -9)
	{
		// Close the cursor
		Close(&mpCDAOneShot);
		SetStatement(NULL);

		// Open a new one
		OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateBooleanAttribute);

		// Binds
		Bind(":id", &user_id);
		Bind(":aid", &attribute_id);
		Bind(":response", charResponse);

		// Do it
		Execute();
	}
	else
	{
		Check(rc);
	}
	// Make the commitment
	Commit();

	// Close the cursor
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return;
}

//
// SetUserAttribute(Number)
//
//	This routine will insert, or, if the row is already present,
//	update it. Sooo, the implication here is that if multiple
//	responses aren't allowed, it's up to the client to enforce
//	that.
//
static const char *SQL_InsertNumberAttribute = 
"insert into ebay_user_attributes					\
	(	user_id,									\
		attribute_id,								\
		first_entered,								\
		last_updated,								\
		number_value								\
	)												\
 values												\
	(	:id,										\
		:aid,										\
		sysdate,									\
		sysdate,									\
		:response									\
	)";

static const char *SQL_UpdateNumberAttribute = 
"update ebay_user_attributes						\
	set		number_value		= :response,		\
			last_updated		= sysdate			\
	where	user_id				= :id				\
	and		attribute_id		= :aid";

void clsDatabaseOracle::SetUserAttributeValue(int user_id,
										 int attribute_id,
										 float response)
{
	int		rc;

	// Open....
	OpenAndParse(&mpCDAInsertNumberAttribute,
				 SQL_InsertNumberAttribute);

	// Bind
	// Now, the binds
	Bind(":id", &user_id);
	Bind(":aid", &attribute_id);
	Bind(":response", &response);

	// Let's try it...
	// Call oexn directly
	rc	= oexec((cda_def *)mpCDACurrent);

	// If we got a rc == -9 then it's probably an integrity
	// violation, and we need to UPDATE a row, instead of 
	// adding it.
	if (rc == -9)
	{
		// Close the cursor
		Close(&mpCDAInsertNumberAttribute);
		SetStatement(NULL);

		// Open a new one
		OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateNumberAttribute);

		// Binds
		Bind(":id", &user_id);
		Bind(":aid", &attribute_id);
		Bind(":response", &response);

		// Do it
		Execute();
		// Make the commitment
		Commit();

		// Close the cursor
		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}
	else
	{
	// This Check works for both the insert and update
	// case (though it's redundant in the latter);
		Check(rc);

		// Make the commitment
		Commit();

		// Close the cursor
		Close(&mpCDAInsertNumberAttribute);
		SetStatement(NULL);
	}

	return;
}


void clsDatabaseOracle::SetUserAttributeValue(int user_id,
										 int attribute_id,
										 int response)
{
	int		rc;

	// Open....
	OpenAndParse(&mpCDAInsertNumberAttribute,
				 SQL_InsertNumberAttribute);

	// Bind
	// Now, the binds
	Bind(":id", &user_id);
	Bind(":aid", &attribute_id);
	Bind(":response", &response);

	// Let's try it...
	// Call oexn directly
	rc	= oexec((cda_def *)mpCDACurrent);

	// If we got a rc == -9 then it's probably an integrity
	// violation, and we need to UPDATE a row, instead of 
	// adding it.
	if (rc == -9)
	{
		// Close the cursor
		Close(&mpCDAOneShot);
		SetStatement(NULL);

		// Open a new one
		OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateNumberAttribute);

		// Binds
		Bind(":id", &user_id);
		Bind(":aid", &attribute_id);
		Bind(":response", &response);

		// Do it
		Execute();
		// Make the commitment
		Commit();

		// Close the cursor
		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}
	else
	{
	// This Check works for both the insert and update
	// case (though it's redundant in the latter);
		Check(rc);

		// Make the commitment
		Commit();

		// Close the cursor
		Close(&mpCDAInsertNumberAttribute);
		SetStatement(NULL);
	}

	return;
}

//
// SetUserAttribute (Text)
//
//	This routine will insert, or, if the row is already present,
//	update it. Sooo, the implication here is that if multiple
//	responses aren't allowed, it's up to the client to enforce
//	that.
////(	:marketplace,								
static const char *SQL_InsertTextAttribute = 
"insert into ebay_user_attributes					\
	(	user_id,									\
		attribute_id,								\
		first_entered,								\
		last_updated,								\
		text_value									\
	)												\
 values												\
	(	:id,										\
		:aid,										\
		sysdate,									\
		sysdate,									\
		:response									\
	)";

static const char *SQL_UpdateTextAttribute = 
"update ebay_user_attributes						\
	set		text_value		= :response,			\
			last_updated		= sysdate			\
	where	user_id				= :id				\
	and		attribute_id		= :aid";

void clsDatabaseOracle::SetUserAttributeValue(int user_id,
											  int attribute_id,
											  char *pResponse)
{
	int		rc;
	sb2		response_ind;
	char	*pNullStr = NULL;

	if (!pResponse)
		response_ind	= -1;
	else
		response_ind	= 0;

	// Open....
	OpenAndParse(&mpCDAOneShot,
				 SQL_InsertTextAttribute);
	// Bind
	// Now, the binds
	Bind(":id", &user_id);
	Bind(":aid", &attribute_id);

	// Bind(":response", pResponse, &response_ind);
	if (pResponse)
		Bind(":response", pResponse);
	else
		Bind(":response", (char *)&pNullStr, &response_ind);

	// Let's try it...
	// Call oexn directly
	rc	= oexec((cda_def *)mpCDACurrent);

	// If we got a rc == -9 then it's probably an integrity
	// violation, and we need to UPDATE a row, instead of 
	// adding it.
	if (rc == -9)
	{
		// Close the cursor
		Close(&mpCDAOneShot);
		SetStatement(NULL);

		// Open a new one
		OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateTextAttribute);

		// Binds
		Bind(":id", &user_id);
		Bind(":aid", &attribute_id);
		if (pResponse)
			Bind(":response", pResponse);
		else
			Bind(":response", (char *)&pNullStr, &response_ind);
		
		// Do it
		Execute();
	}
	else
	// This Check works for both the insert and update
	// case (though it's redundant in the latter);
		Check(rc);

	// Make the commitment
	Commit();

	// Close the cursor
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return;
}

