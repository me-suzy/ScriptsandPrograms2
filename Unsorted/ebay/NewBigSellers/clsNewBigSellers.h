// clsNewBigSellers.h: interface for the clsNewBigSellers class.
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_CLSNEWBIGSELLERS_H__476ACB0C_9589_11D2_96CA_00C04F990638__INCLUDED_)
#define AFX_CLSNEWBIGSELLERS_H__476ACB0C_9589_11D2_96CA_00C04F990638__INCLUDED_

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000

class clsNewBigSellers : public clsApp  
{
public:
	static void usage();
	
	int Run();
	clsNewBigSellers();
	virtual ~clsNewBigSellers();

	static int mAgeOfUsers;
	static int mNumberOfAuctions;

private:
	clsDatabase* mpDatabase;
	clsMarketPlaces* mpMarketPlaces;
	clsMarketPlace* mpMarketPlace;
	clsUsers * mpUsers;
	clsItems* mpItems;
};

#endif // !defined(AFX_CLSNEWBIGSELLERS_H__476ACB0C_9589_11D2_96CA_00C04F990638__INCLUDED_)
