/*	$Id: clsUserCode.cpp,v 1.3 1998/06/30 09:11:44 josh Exp $	*/
//
//	File:	clsUserCode.cpp
//
//	Class:	clsUserCode
//
//	Author:	Craig Huang (chuang@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 11/20/97 Craig Huang - Created
//
#include "eBayKernel.h"
#include <stdio.h>

#define STRING_METHODS(variable)				\
char *clsUserCode::Get##variable()					\
{												\
	return mp##variable;						\
}												\
void clsUserCode::Set##variable(char *pNew)			\
{												\
	if (mp##variable)							\
		delete mp##variable;					\
	mp##variable = new char[strlen(pNew) + 1];	\
	strcpy(mp##variable, pNew);					\
	mDirty	= true;								\
	return;										\
}
#define INT_METHODS(variable)					\
int clsUserCode::Get##variable()					\
{												\
	return m##variable;							\
}												\
void clsUserCode::Set##variable(int newval)			\
{												\
	m##variable	= newval;						\
	mDirty	= true;								\
	return;										\
} 

INT_METHODS(QuestionID);			// Question ID
INT_METHODS(QuestionCode);			// Question Code
INT_METHODS(SortNumber);			// User Info Sort Number
INT_METHODS(UserCodeType);			// User Code Type
STRING_METHODS(Question);			// Question Description

clsUserCode::clsUserCode(int questionID, int questionCode, int SortNumber, int UserCodeType, char *pQuestion) 
{
	mQuestionID		= questionID;
	mQuestionCode	= questionCode;
	mSortNumber		= SortNumber;
	mUserCodeType	= UserCodeType;
	mpQuestion		= pQuestion;
	//mpQuestion		= new char[strlen(pQuestion) + 1];
	//strcpy(mpQuestion, pQuestion);
}

clsUserCode::~clsUserCode()
{
	delete mpQuestion;
	return;
}

