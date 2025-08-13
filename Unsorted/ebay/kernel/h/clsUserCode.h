/*	$Id: clsUserCode.h,v 1.2 1998/06/23 04:28:27 josh Exp $	*/
//
//	File:	clsUserCode.h
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
#ifndef CLSUSERCODE_INCLUDED
#include "eBayTypes.h"

#include <vector.h>
typedef enum
{	
	USERCODEQSELECTTYPE	,
	USERCODEQINPUTTYPE	,
	USERCODEQRADIOBUTTONTYPE	,
	USERCODEATYPE	,	
	USERCODEADEFAULTTYPE,		
	USERCODECATEGORYTYPE = 9		
} eUserCodeType;


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


class clsUserCode
{
   
  public:	
	clsUserCode(int questionID, int questionCode, int SortNumber, int UserCodeType, char *pQuestion); 
	~clsUserCode();	

//	typecode char

  private:
	bool			mDirty;  
	INT_VARIABLE(QuestionID);
	INT_VARIABLE(QuestionCode);
	INT_VARIABLE(SortNumber);
	INT_VARIABLE(UserCodeType);
	STRING_VARIABLE(Question);
};

typedef vector<clsUserCode *> UserCodeVector;
#define CLSUSERCODE_INCLUDED 1
#endif