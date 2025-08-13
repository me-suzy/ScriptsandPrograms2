//
//      File:           clseBayCookie.h
//
//      Class:          clseBayCookie
//
//      Author:         Wen Wen (wen@ebay.com)
//
//      Function:
//                              class for constructing and parsing cookie
//
//      Modifications:
//                              - 05/05/98 Wen - Created
//
#ifndef __CLSNAMEVALUEPAIR_INCLUDE__
#define __CLSNAMEVALUEPAIR_INCLUDE__

#include <string.h>
//
// class for name value passed to login dialog
//
class clsNameValuePair
{
public:
	clsNameValuePair() {mpName=NULL; mpValue=NULL;}
	~clsNameValuePair() {delete mpName; delete mpValue;}

	void Set(const char* pName, const char* pValue)
	{
		SetName(pName);
		SetValue(pValue);
	}

	void SetName(const char* pName) 
	{ 
		delete mpName; 
		mpName = new char[strlen(pName) + 1];
		strcpy(mpName, pName);
	}

	void SetValue(const char* pValue) 
	{ 
		delete mpValue; 
		mpValue = new char[strlen(pValue) + 1];
		strcpy(mpValue, pValue);
	}

	const char* GetName() const  {return mpName;}
	const char* GetValue() const {return mpValue;}

protected:
	char* mpName;
	char* mpValue;
};

#endif // __CLSNAMEVALUEPAIR_INCLUDE__