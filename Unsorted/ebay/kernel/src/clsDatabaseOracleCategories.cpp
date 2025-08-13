/*	$Id: clsDatabaseOracleCategories.cpp,v 1.12.22.1.104.2 1999/08/04 16:51:29 nsacco Exp $	*/
//
//	File:	clsDatabaseOracleCategories.cc
//
//	Class:	clsDatabaseOracleCategories
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/09/97 michael	- Created
//				- 06/13/97 tini     - split off category related calls from 
//                                    clsDatabaseOracle
//				- 08/01/97 tini		- used separate cda's for getCategoryVector
//				- 09/02/97 wen		- added functions for retrieving
//									  category ids
//				- 11/19/97 charles	- added GetItemsCountByCategory()
//				- 04/22/99 mila		- added new methods and flags column for
//									  legal buddies project
//				- 05/11/99 jnace	- added region ID parameter to GetCategoryCountsFromOpenItems
//				- 06/04/99 petra	- added siteId parameter all over the place,
//							  added *_res_id fields to retrieve transtated text from
//							  resources,
//							  and cleaned up unused functions
//				- 07/27/99 nsacco	- added shipping_option, ship_region_flags, 
//										desc_lang, site_id to GetCategoryItems()

#include "eBayKernel.h"

// *********
// Categories
// *********

static const char *SQL_GetCategoryById =
 "select name,								\
	     description,						\
		 adult,								\
		 isleaf,							\
		 isexpired,							\
		 level1,							\
		 level2,							\
		 level3,							\
		 level4,							\
		 name1,								\
		 name2,								\
		 name3,								\
		 name4,								\
		 prevcategory,						\
		 nextcategory,						\
		 featuredCost,						\
		 TO_CHAR(created,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 fileReference,						\
		 TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 flags,								\
		 name_res_id,	\
		 name1_res_id,	\
		 name2_res_id,	\
		 name3_res_id,  \
		 name4_res_id	\
	from ebay_categories,					\
		ebay_categories_site				\
	where	marketplace = :marketplace		\
	and		id = :id			\
	and		category_id = :id		\
	and		site_id = :siteid";

clsCategory *clsDatabaseOracle::GetCategoryById(
							MarketPlaceId marketplace,
							CategoryId category,
							int siteId)
{
	char			description[256];
	char			name[51];
	char			*pName;
	char			*pDescription;
	char			adult[2];
	char			isleaf[2];
	bool			isLeaf;
	bool			isExpired;
	char			isexpired[2];
	CategoryId		lev1;
	CategoryId		lev2;
	CategoryId		lev3;
	CategoryId		lev4;
	char			*pName1;
	char			*pName2;
	char			*pName3;
	char			*pName4;
	char			name1[51];
	char			name2[51];
	char			name3[51];
	char			name4[51];
	sb2				name1_ind;
	sb2				name2_ind;
	sb2				name3_ind;
	sb2				name4_ind;
	CategoryId		prevcategory;
	CategoryId		nextcategory;
	float			featuredCost;
	char			ccreate_date[32];
	time_t			create_date;
	char			cmodified_date[32];
	time_t			modified_date;
	clsCategory		*pCategory;
	char			fileRef[256];
	sb2				fileRef_ind;
	char			*pFileRef;
	char			nullString	= '\0';
	int			name_res_id;
	int			name1_res_id;
	int			name2_res_id;
	int			name3_res_id;
	int			name4_res_id;
	sb2			name1_res_id_ind;
	sb2			name2_res_id_ind;
	sb2			name3_res_id_ind;
	sb2			name4_res_id_ind;

	unsigned int	flags;
	sb2				flags_ind;

	bool			maskBidders;
	bool			screenItems;

	// Open + Parse
	OpenAndParse(&mpCDAGetCategoryById, 
				 SQL_GetCategoryById);

	// Bind input variables
	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", (int *)&category);
	Bind(":siteid",	(int *)&siteId);

	// Define
	Define(1, name, sizeof(name));
	Define(2, description,
		   sizeof(description));
	Define(3, adult, sizeof(adult));
	Define(4, isleaf, sizeof(isleaf));
	Define(5, isexpired, sizeof(isexpired));
	Define(6, (int *)&lev1);
	Define(7, (int *)&lev2);
	Define(8, (int *)&lev3);
	Define(9, (int *)&lev4);
	Define(10, name1, sizeof(name1), &name1_ind);
	Define(11, name2, sizeof(name2), &name2_ind);
	Define(12, name3, sizeof(name3), &name3_ind);
	Define(13, name4, sizeof(name4), &name4_ind);
	Define(14, (int *)&prevcategory);
	Define(15, (int *)&nextcategory);
	Define(16, &featuredCost);
	Define(17, ccreate_date, sizeof(ccreate_date));
	Define(18, fileRef, sizeof(fileRef), &fileRef_ind);
	Define(19, cmodified_date, sizeof(cmodified_date));
	Define(20, (int *)&flags, &flags_ind);
	Define(21, (int *)&name_res_id);
	Define(22, (int *)&name1_res_id, &name1_res_id_ind);
	Define(23, (int *)&name2_res_id, &name2_res_id_ind);
	Define(24, (int *)&name3_res_id, &name3_res_id_ind);
	Define(25, (int *)&name4_res_id, &name4_res_id_ind);

	// Get it!
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		// We're done with the cursor
		Close(&mpCDAGetCategoryById);
		SetStatement(NULL);
		return 0;
	};

	// We're done with the cursor
	Close(&mpCDAGetCategoryById);
	SetStatement(NULL);


	// Build a nice object for our caller.
	pDescription	
		= new char[strlen(description) + 1];
	strcpy(pDescription, description);

// petra ***************************************** change this
	pName 
		= new char[strlen(name) + 1];
	strcpy(pName, name);
// petra ***************************************** to this
//	pName = clsResources::GetResourceFromId (name_res_id, siteId);
// petra *****************************************

	if (name1_ind == -1)
	{
		pName1 = new char[1];
		strcpy(pName1, &nullString);
	//	pName1 = &nullString;
	}
	else
	{
// petra ***************************************** change this
		pName1	= new char[strlen(name1) + 1];
		strcpy(pName1, name1);
// petra ***************************************** to this
//		pName1 = clsResources::GetResourceFromId (name1_res_id, siteId);
// petra *****************************************
	}

	if (name2_ind == -1)
	{
		pName2 = new char[1];
		strcpy(pName2, &nullString);
		// pName2	= &nullString;
	}
	else
	{
// petra ***************************************** change this
		pName2	= new char[strlen(name2) + 1];
		strcpy(pName2, name2);
// petra ***************************************** to this
//		pName2 = clsResources::GetResourceFromId (name2_res_id, siteId);
// petra ***************************************** 
	}
	
	if (name3_ind == -1)
	{
		pName3 = new char[1];
		strcpy(pName3, &nullString);
		// pName3	= &nullString;
	}
	else
	{
// petra ***************************************** change this
		pName3	= new char[strlen(name3) + 1];
		strcpy(pName3, name3);
// petra ***************************************** to this
//		pName3 = clsResources::GetResourceFromId (name3_res_id, siteId);
// petra ***************************************** change this
	}
	
	if (name4_ind == -1)
	{
		pName4 = new char[1];
		strcpy(pName4, &nullString);
		// pName4	= &nullString;
	}
	else
	{
// petra ***************************************** change this
		pName4	= new char[strlen(name4) + 1];
		strcpy(pName4, name4);
// petra ***************************************** to this
//		pName4 = clsResources::GetResourceFromId (name4_res_id, siteId);
// petra *****************************************
	}
	
	if (fileRef_ind == -1)
	{
		pFileRef = new char[1];
		strcpy(pFileRef, &nullString);
		// pFileRef	= &nullString;
	}
	else
	{
		pFileRef	= new char[strlen(fileRef) + 1];
		strcpy(pFileRef, fileRef);
	}

	ORACLE_DATEToTime(ccreate_date, &create_date);
	ORACLE_DATEToTime(cmodified_date, &modified_date);

	if (isleaf[0] == '1')
		isLeaf	= true;
	else
		isLeaf	= false;

	if (isexpired[0] == '1')
		isExpired	= true;
	else
		isExpired	= false;

	if (flags_ind == -1)
	{
		maskBidders = false;
		screenItems = false;
	}
	else
	{
		if (flags & CategoryFlagMaskBidders)
			maskBidders = true;
		else
			maskBidders = false;

		if (flags & CategoryFlagScreenItems)
			screenItems = true;
		else
			screenItems = false;
	}

	pCategory	= new clsCategory(marketplace,
								  category,
								  pName,
								  pDescription,
								  adult[0],
								  isLeaf,
								  isExpired,
								  lev1,
								  lev2,
								  lev3,
								  lev4,
								  pName1,
								  pName2,
								  pName3,
								  pName4,
								  prevcategory,
								  nextcategory,
								  featuredCost,
								  create_date,
								  pFileRef,
								  modified_date,
								  maskBidders,
								  screenItems,
								  siteId);

	return pCategory;
}



// petra removed *SQL_GetCategoryByName, clsCategory *clsDatabaseOracle::GetCategoryByName(
//							MarketPlaceId marketplace,
//							char *pName)
//		(see E117 to restore)




//
// GetCategoryFirst
//
// Gets first category in the hierarchy
//
static const char *SQL_GetCategoryFirstTop =
 "select id,								\
		 name,								\
	     description,						\
		 adult,								\
		 isleaf,							\
		 isexpired,							\
		 level1,							\
		 level2,							\
		 level3,							\
		 level4,							\
		 name1,								\
		 name2,								\
		 name3,								\
		 name4,								\
		 prevcategory,						\
		 nextcategory,						\
		 featuredCost,						\
		 TO_CHAR(created,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 fileReference,						\
		 TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 flags,								\
		 name_res_id,	\
		 name1_res_id,	\
	  	 name2_res_id,	\
		 name3_res_id,	\
		 name4_res_id	\
	from ebay_categories,					\
		ebay_categories_site				\
	where	marketplace = :marketplace		\
	and		level1 = 0						\
	and		prevCategory = 0			\
	and		id = category_id			\
	and 		site_id = :siteid";

// get first sibling in the category
static const char *SQL_GetCategoryFirstSibling =
 "select id,								\
		 name,								\
	     description,						\
		 adult,								\
		 isleaf,							\
		 isexpired,							\
		 level1,							\
		 level2,							\
		 level3,							\
		 level4,							\
		 name1,								\
		 name2,								\
		 name3,								\
		 name4,								\
		 prevcategory,						\
		 nextcategory,						\
		 featuredCost,						\
		 TO_CHAR(created,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 fileReference,						\
		 TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 flags,								\
		 name_res_id,	\
		 name1_res_id,	\
		 name2_res_id, 	\
		 name3_res_id,	\
		 name4_res_id	\
	from ebay_categories,					\
		ebay_categories_site				\
	where	marketplace = :marketplace		\
	and		level1 = :id					\
	and		prevCategory = 0				\
	and		site_id = :siteId				\
	and		id = category_id";

clsCategory *clsDatabaseOracle::GetCategoryFirst(
							MarketPlaceId marketplace,
							CategoryId category,
							int QueryCode,
							int siteId				  )
{
	char			description[256];
	CategoryId		pId;
	char			*pDescription;
	char			adult[2];
	char			isleaf[2];
	bool			isLeaf;
	bool			isExpired;
	char			isexpired[2];
	CategoryId		lev1;
	CategoryId		lev2;
	CategoryId		lev3;
	CategoryId		lev4;
	char			*pName1;
	char			*pName2;
	char			*pName3;
	char			*pName4;
	char			*pName;
	char			name[51];
	char			name1[51];
	char			name2[51];
	char			name3[51];
	char			name4[51];
	sb2				name1_ind;
	sb2				name2_ind;
	sb2				name3_ind;
	sb2				name4_ind;
	CategoryId		prevcategory;
	CategoryId		nextcategory;
	float			featuredCost;
	char			ccreate_date[32];
	time_t			create_date;
	char			cmodified_date[32];
	time_t			modified_date;
	clsCategory		*pCategory;
	char			fileRef[256];
	sb2				fileRef_ind;
	char			*pFileRef;
	char			nullString	= '\0';
	int 			name_res_id;
	int				name1_res_id;
	int 			name2_res_id;
	int				name3_res_id;
	int				name4_res_id;
	sb2				name1_res_id_ind;
	sb2				name2_res_id_ind;
	sb2				name3_res_id_ind;
	sb2				name4_res_id_ind;

	unsigned int	flags;
	sb2				flags_ind;

	bool			maskBidders;
	bool			screenItems;

	// Open + Parse
	// and bind input variables
	switch (QueryCode) 
	{
	case 1: 
		{	// first category, top level
			OpenAndParse(&mpCDAGetCategoryFirst, 
				 SQL_GetCategoryFirstTop);
			// bind common input variable
			Bind(":marketplace", (int *)&marketplace);
			Bind(":siteId", (int *)&siteId);
			break;
		}
	case 2:
		{	// first category, siblings
			OpenAndParse(&mpCDAGetCategoryFirst,
				SQL_GetCategoryFirstSibling);
			// bind common input variable
			Bind(":marketplace", (int *)&marketplace);
			Bind(":id", (int *)&category);
			Bind(":siteId",	(int *)&siteId);

			break;
		}
	default:
		{ // currently assume 1
			OpenAndParse(&mpCDAGetCategoryFirst,
				SQL_GetCategoryFirstTop);
			// bind common input variable
			Bind(":marketplace", (int *)&marketplace);
			Bind(":siteId",	(int *)&siteId);
			break;
		}
	}


	// Define
	Define(1, (int *)&pId);
	Define(2, name, sizeof(name));
	Define(3, description, sizeof(description));
	Define(4, adult, sizeof(adult));
	Define(5, isleaf, sizeof(isleaf));
	Define(6, isexpired, sizeof(isexpired));
	Define(7, (int *)&lev1);
	Define(8, (int *)&lev2);
	Define(9, (int *)&lev3);
	Define(10, (int *)&lev4);
	Define(11, name1, sizeof(name1), &name1_ind);
	Define(12, name2, sizeof(name2), &name2_ind);
	Define(13, name3, sizeof(name3), &name3_ind);
	Define(14, name4, sizeof(name4), &name4_ind);
	Define(15, (int *)&prevcategory);
	Define(16, (int *)&nextcategory);
	Define(17, &featuredCost);
	Define(18, ccreate_date, sizeof(ccreate_date));
	Define(19, fileRef, sizeof(fileRef), &fileRef_ind);
	Define(20, cmodified_date, sizeof(cmodified_date));
	Define(21, (int *)&flags, &flags_ind);
	Define(21, (int *)&name_res_id);
	Define(22, (int *)&name1_res_id, &name1_res_id_ind);
	Define(23, (int *)&name2_res_id, &name2_res_id_ind);
	Define(24, (int *)&name3_res_id, &name3_res_id_ind);
	Define(25, (int *)&name4_res_id, &name4_res_id_ind);

	// Get it!
	ExecuteAndFetch();


	if (CheckForNoRowsFound())
	{
		// We're done with the cursor
		Close(&mpCDAGetCategoryFirst, true);
		SetStatement(NULL);
		return 0;
	};

	// Build a nice object for our caller.
// petra ***************************************** change this
	pName	
		= new char[strlen(name) + 1];
	strcpy(pName, name);
// petra ***************************************** to this
//	pName = clsResources::GetResourceFromId (name_res_id, siteId);
// petra ***************************************** 

	pDescription	
		= new char[strlen(description) + 1];
	strcpy(pDescription, description);

	if (name1_ind == -1)
	{
		pName1 = new char[1];
		strcpy(pName1, &nullString);
		// pName1	= &nullString;
	}
	else
	{
// petra ***************************************** change this
		pName1	= new char[strlen(name1) + 1];
		strcpy(pName1, name1);
// petra ***************************************** to this
//		pName1 = clsResources::GetResourceFromId(name1_res_id, siteId);
// petra ***************************************** 
	}

	if (name2_ind == -1)
	{
		pName2 = new char[1];
		strcpy(pName2, &nullString);
		// pName2	= &nullString;
	}
	else
	{
// petra ***************************************** change this
		pName2	= new char[strlen(name2) + 1];
		strcpy(pName2, name2);
// petra ***************************************** to this
//		pName1 = clsResources::GetResourceFromId(name2_res_id, siteId);
// petra ***************************************** 
	}
	
	if (name3_ind == -1)
	{
		pName3 = new char[1];
		strcpy(pName3, &nullString);
		// pName3	= &nullString;
	}
	else
	{
// petra ***************************************** change this
		pName3	= new char[strlen(name3) + 1];
		strcpy(pName3, name3);
// petra ***************************************** to this
//		pName1 = clsResources::GetResourceFromId(name3_res_id, siteId);
// petra ***************************************** 
	}
	
	if (name4_ind == -1)
	{
		pName4 = new char[1];
		strcpy(pName4, &nullString);
		// pName4	= &nullString;
	}
	else
	{
// petra ***************************************** change this
		pName4	= new char[strlen(name4) + 1];
		strcpy(pName4, name4);
// petra ***************************************** to this
//		pName1 = clsResources::GetResourceFromId(name4_res_id, siteId);
// petra ***************************************** 
	}
	
	if (fileRef_ind == -1)
	{
		pFileRef = new char[1];
		strcpy(pFileRef, &nullString);
		// pFileRef	= &nullString;
	}
	else
	{
		pFileRef	= new char[strlen(fileRef) + 1];
		strcpy(pFileRef, fileRef);
	}

	ORACLE_DATEToTime(ccreate_date, &create_date);
	ORACLE_DATEToTime(cmodified_date, &modified_date);

	if (isleaf[0] == '1')
		isLeaf	= true;
	else
		isLeaf	= false;

	if (isexpired[0] == '1')
		isExpired	= true;
	else
		isExpired	= false;

	if (flags_ind == -1)
	{
		maskBidders = false;
		screenItems = false;
	}
	else
	{
		if (flags & CategoryFlagMaskBidders)
			maskBidders = true;
		else
			maskBidders = false;

		if (flags & CategoryFlagScreenItems)
			screenItems = true;
		else
			screenItems = false;
	}
	
	pCategory	= new clsCategory(marketplace,
								  pId,
								  pName,
								  pDescription,
								  adult[0],
								  isLeaf,
								  isExpired,
								  lev1,
								  lev2,
								  lev3,
								  lev4,
								  pName1,
								  pName2,
								  pName3,
								  pName4,
								  prevcategory,
								  nextcategory,
								  featuredCost,
								  create_date,
								  pFileRef,
								  modified_date,
								  maskBidders,
								  screenItems,
								  siteId);

	// We're done with the cursor
	Close(&mpCDAGetCategoryFirst, true);
	SetStatement(NULL);

	return pCategory;
}

// petra removed *SQL_AddCategory, void clsDatabaseOracle::AddCategory(clsCategory *pCategory)
//		(see E117 to restore)

// petra removed *SQL_UpdateCategory, void clsDatabaseOracle::UpdateCategory(clsCategory *pCategory)
//		(see E117 to restore)

// petra removed *SQL_UpdateChildrenCategory, void clsDatabaseOracle::UpdateDescendantCategory(clsCategory *pCategory)
//		(see E117 to restore)

// petra removed *SQL_DeleteCategory, void clsDatabaseOracle::DeleteCategory(clsCategory *pCategory)
//		(see E117 to restore)


// Using generic GetCategoryVector Database calls

#define ORA_CATEGORY_ARRAYSIZE 20

// GetCategoryAll
// Returns all categories
static const char *SQL_GetCategoryAll =
 "select id,								\
	     name,								\
		 description,						\
		 adult,								\
		 isleaf,							\
		 isexpired,							\
		 level1,							\
		 level2,							\
		 level3,							\
		 level4,							\
		 name1,								\
		 name2,								\
		 name3,								\
		 name4,								\
		 prevcategory,						\
		 nextcategory,						\
		 featuredCost,						\
		 TO_CHAR(created,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 fileReference,						\
		 TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
		flags,								\
		name_res_id,	\
		name1_res_id,	\
		name2_res_id,	\
		name3_res_id,	\
		name4_res_id	\
	from ebay_categories,					\
		ebay_categories_site				\
	where	marketplace = :marketplace		\
	and 	site_id = :siteId			\
	and	id = category_id			\
	order by order_no";

// GetCategoryTopLevel
// Returns all top level categories (no parents)
static const char *SQL_GetCategoryTopLevel =
 "select id,								\
	     name,								\
		 description,						\
		 adult,								\
		 isleaf,							\
		 isexpired,							\
		 level1,							\
		 level2,							\
		 level3,							\
		 level4,							\
		 name1,								\
		 name2,								\
		 name3,								\
		 name4,								\
		 prevcategory,						\
		 nextcategory,						\
		 featuredCost,						\
		 TO_CHAR(created,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 fileReference,						\
		 TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
		flags,								\
		name_res_id,	\
		name1_res_id,	\
		name2_res_id,	\
		name3_res_id,	\
		name4_res_id	\
	from ebay_categories,					\
		ebay_categories_site				\
	where	marketplace = :marketplace		\
	and		level1 = 0						\
	and		site_id = :siteId		\
	and		id = category_id		\
	order by order_no";


// GetCategoryChildren - finds all children of a category
// need to sort the resulting categoryVector according to prevCategory
// and nextCategory

static const char *SQL_GetCategoryChildren =
 "select id,								\
	     name,								\
		 description,						\
		 adult,								\
		 isleaf,							\
		 isexpired,							\
		 level1,							\
		 level2,							\
		 level3,							\
		 level4,							\
		 name1,								\
		 name2,								\
		 name3,								\
		 name4,								\
		 prevcategory,						\
		 nextcategory,						\
		 featuredCost,						\
		 TO_CHAR(created,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 fileReference,						\
		 TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
		flags,								\
		name_res_id,	\
		name1_res_id,	\
		name2_res_id,	\
		name3_res_id,	\
		name4_res_id	\
	from ebay_categories,					\
		ebay_categories_site				\
	where	marketplace = :marketplace		\
	and		level1 = :id					\
	and		site_id = :siteId				\
	and		id = category_id				\
	order by order_no";

// GetCategoryDescendants - finds all descendants of a category
//
// need to sort the resulting categoryVector according to siblingOrder
// may fold this into GetCategoryChildren with a flag to get children or descendants
//
static const char *SQL_GetCategoryDescendants =
 "select id,								\
	     name,								\
		 description,						\
		 adult,								\
		 isleaf,							\
		 isexpired,							\
		 level1,							\
		 level2,							\
		 level3,							\
		 level4,							\
		 name1,								\
		 name2,								\
		 name3,								\
		 name4,								\
		 prevcategory,						\
		 nextcategory,						\
		 featuredCost,						\
		 TO_CHAR(created,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 fileReference,						\
		 TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 flags,								\
		name_res_id,	\
		name1_res_id,	\
		name2_res_id,	\
		name3_res_id,	\
		name4_res_id	\
	from ebay_categories,					\
		ebay_categories_site				\
	where	marketplace = :marketplace		\
	and		(level1 = :id or level2 = :id   \
	or		level3 = :id or level4 = :id)	\
	and		site_id = :siteId		\
	and		id = category_id		\
	order by order_no";

// petra removed *SQL_GetCategorySiblings
//		(see E117 to restore)

//
// GetCategoryLeaves
//
static const char *SQL_GetCategoryLeaves =
 "select id,								\
	     name,								\
		 description,						\
		 adult,								\
		 isleaf,							\
		 isexpired,							\
		 level1,							\
		 level2,							\
		 level3,							\
		 level4,							\
		 name1,								\
		 name2,								\
		 name3,								\
		 name4,								\
		 prevcategory,						\
		 nextcategory,						\
		 featuredCost,						\
		 TO_CHAR(created,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 fileReference,						\
		 TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 flags,								\
		name_res_id,	\
		name1_res_id,	\
		name2_res_id,	\
		name3_res_id,	\
		name4_res_id	\
	from ebay_categories,					\
		ebay_categories_site				\
	where	marketplace = :marketplace		\
	and		isleaf = '1'					\
	and		site_id = :siteId				\
	and		id = category_id				\
	order by order_no";

// GetCategoryChildren - finds all children of a category
// need to sort the resulting categoryVector according to prevCategory
// and nextCategory

static const char *SQL_GetCategoryChildrenSorted =
 "select id,								\
	     name,								\
		 description,						\
		 adult,								\
		 isleaf,							\
		 isexpired,							\
		 level1,							\
		 level2,							\
		 level3,							\
		 level4,							\
		 name1,								\
		 name2,								\
		 name3,								\
		 name4,								\
		 prevcategory,						\
		 nextcategory,						\
		 featuredCost,						\
		 TO_CHAR(created,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 fileReference,						\
		 TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
		 flags,								\
		name_res_id,	\
		name1_res_id,	\
		name2_res_id,	\
		name3_res_id,	\
		name4_res_id	\
	from ebay_categories,					\
		ebay_categories_site				\
	where	marketplace = :marketplace		\
	and	site_id = :siteId			\
	and	id = category_id			\
	start with ((level1 = :id) and (prevcategory = 0)) \
	connect by prior nextcategory = id";

void clsDatabaseOracle::GetCategoryVector(
									MarketPlaceId marketplace,
									CategoryId pId,
									int QueryCode,
									CategoryVector *pvCategories,
									int siteId)
{
	// Temporary slots for things to live in
	CategoryId		resId[ORA_CATEGORY_ARRAYSIZE];
	char			description[ORA_CATEGORY_ARRAYSIZE][256];
	char			name[ORA_CATEGORY_ARRAYSIZE][51];
	char			*pName;
	char			*pDescription;
	char			adult[ORA_CATEGORY_ARRAYSIZE][2];
	char			isleaf[ORA_CATEGORY_ARRAYSIZE][2];
	bool			isLeaf[ORA_CATEGORY_ARRAYSIZE];
	bool			isExpired[ORA_CATEGORY_ARRAYSIZE];
	char			isexpired[ORA_CATEGORY_ARRAYSIZE][2];
	CategoryId		lev1[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		lev2[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		lev3[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		lev4[ORA_CATEGORY_ARRAYSIZE];
	char			*pName1;
	char			*pName2;
	char			*pName3;
	char			*pName4;
	char			name1[ORA_CATEGORY_ARRAYSIZE][51];
	char			name2[ORA_CATEGORY_ARRAYSIZE][51];
	char			name3[ORA_CATEGORY_ARRAYSIZE][51];
	char			name4[ORA_CATEGORY_ARRAYSIZE][51];
	sb2				name1_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2				name2_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2				name3_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2				name4_ind[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		prevcategory[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		nextcategory[ORA_CATEGORY_ARRAYSIZE];
	float			featuredCost[ORA_CATEGORY_ARRAYSIZE];
	char			ccreate_date[ORA_CATEGORY_ARRAYSIZE][32];
	time_t			create_date[ORA_CATEGORY_ARRAYSIZE];
	char			cmodified_date[ORA_CATEGORY_ARRAYSIZE][32];
	time_t			modified_date[ORA_CATEGORY_ARRAYSIZE];
	char			fileRef[ORA_CATEGORY_ARRAYSIZE][256];
	sb2				fileRef_ind[ORA_CATEGORY_ARRAYSIZE];
	char			*pFileRef;

	unsigned int	flags[ORA_CATEGORY_ARRAYSIZE];
	sb2				flags_ind[ORA_CATEGORY_ARRAYSIZE];
	int			name_res_id[ORA_CATEGORY_ARRAYSIZE];
	int			name1_res_id[ORA_CATEGORY_ARRAYSIZE];
	int			name2_res_id[ORA_CATEGORY_ARRAYSIZE];
	int			name3_res_id[ORA_CATEGORY_ARRAYSIZE];
	int			name4_res_id[ORA_CATEGORY_ARRAYSIZE];
	sb2			name1_res_id_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2			name2_res_id_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2			name3_res_id_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2			name4_res_id_ind[ORA_CATEGORY_ARRAYSIZE];

	bool			maskBidders;
	bool			screenItems;

	int				rowsFetched;
	int				rc;
	int				i,n;
	char			nullString	= '\0';
	unsigned char **ppTempCDA;

	clsCategory		*pCategory;

	// Open + Parse
	// and bind input variables
	switch (QueryCode) 
	{
	case 1: 
		{	ppTempCDA = &mpCDAGetCategoryAll;
			OpenAndParse(ppTempCDA, 
				SQL_GetCategoryAll);
			// Bind input variables
			Bind(":marketplace", (int *)&marketplace);
			Bind(":siteId", (int *)&siteId);
			break;
		}
	case 2:
		{	ppTempCDA = &mpCDAGetCategoryTopLevel;
			OpenAndParse(ppTempCDA,
				SQL_GetCategoryTopLevel);
			// Bind input variables
			Bind(":marketplace", (int *)&marketplace);
			Bind(":siteId",	(int *)&siteId);
			break;
		}
	case 3:
		{	ppTempCDA = &mpCDAGetCategoryChildren;
			OpenAndParse(ppTempCDA,
				SQL_GetCategoryChildren);
			// Bind input variables
			Bind(":marketplace", (int *)&marketplace);
			Bind(":id", (int *)&pId);
			Bind(":siteId", (int *)&siteId);
			break;
		}
	case 4:
		{	ppTempCDA = &mpCDAGetCategoryDescendants;
			OpenAndParse(ppTempCDA,
				SQL_GetCategoryDescendants);
			// Bind input variables
			Bind(":marketplace", (int *)&marketplace);
			Bind(":id", (int *)&pId);
			Bind(":siteId", (int *)&siteId);
			break;
		}
// petra removed 5 - was SQL_GetCategorySiblings
	case 6:
		{	ppTempCDA = &mpCDAGetCategoryLeaves;
			OpenAndParse(ppTempCDA,
				SQL_GetCategoryLeaves);
			// Bind input variables
			Bind(":marketplace", (int *)&marketplace);
			Bind(":siteId", (int *)&siteId);
			break;
		}
	case 7:
		{	ppTempCDA = &mpCDAGetCategoryChildrenSorted;
			OpenAndParse(ppTempCDA,
				SQL_GetCategoryChildrenSorted);
			// Bind input variables
			Bind(":marketplace", (int *)&marketplace);
			Bind(":id", (int *)&pId);
			Bind(":siteId", (int *)&siteId);
			break;
		}
	default:
		{ // currently assume 1
			ppTempCDA = &mpCDAGetCategoryVector;
			OpenAndParse(ppTempCDA,
				SQL_GetCategoryAll);
			// Bind input variables
			Bind(":marketplace", (int *)&marketplace);
			Bind(":siteId", (int *)&siteId);
			break;
		}
	}

	// Define
	Define(1, resId);
	Define(2, name[0], sizeof(name[0]));
	Define(3, description[0], sizeof(description[0]));
	Define(4, adult[0], sizeof(adult[0]));
	Define(5, isleaf[0], sizeof(isleaf[0]));
	Define(6, isexpired[0], sizeof(isexpired[0]));
	Define(7, (int *)lev1);
	Define(8, (int *)lev2);
	Define(9, (int *)lev3);
	Define(10, (int *)lev4);
	Define(11, name1[0], sizeof(name1[0]), name1_ind);
	Define(12, name2[0], sizeof(name2[0]), name2_ind);
	Define(13, name3[0], sizeof(name3[0]), name3_ind);
	Define(14, name4[0], sizeof(name4[0]), name4_ind);
	Define(15, (int *)prevcategory);
	Define(16, (int *)nextcategory);
	Define(17, featuredCost);
	Define(18, ccreate_date[0], sizeof(ccreate_date[0]));
	Define(19, fileRef[0], sizeof(fileRef[0]), fileRef_ind);
	Define(20, cmodified_date[0], sizeof(cmodified_date[0]));
	Define(21, (int *)flags, flags_ind);
	Define(22, (int *)name_res_id);
	Define(23, (int *)name1_res_id, name1_res_id_ind);
	Define(24, (int *)name2_res_id, name2_res_id_ind);
	Define(25, (int *)name3_res_id, name3_res_id_ind);
	Define(26, (int *)name4_res_id, name4_res_id_ind);

	// Execute...
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(ppTempCDA,true);
		SetStatement(NULL);
		return;
	}

	// Fetch till we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_CATEGORY_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(ppTempCDA,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. Let's make copies of the title
			// and location for the category

			// Build a nice object for our caller.
			pDescription	
				= new char[strlen(description[i]) + 1];
			strcpy(pDescription, description[i]);
// petra ***************************** change this
			pName 
				= new char[strlen(name[i]) + 1];
			strcpy(pName, name[i]);
// petra ***************************** to this
//			pName = clsResources::GetResourceFromId(name_res_id[i], siteId);
// petra *****************************

			ORACLE_DATEToTime(ccreate_date[i], &create_date[i]);
			ORACLE_DATEToTime(cmodified_date[i], &modified_date[i]);

			if (isleaf[i][0] == '1')
				isLeaf[i]	= true;
			else
				isLeaf[i]	= false;
		
			if (isexpired[i][0] == '1')
				isExpired[i]	= true;
			else
				isExpired[i]	= false;
	
			if (name1_ind[i] == -1)
			{
				pName1 = new char[1];
				strcpy(pName1, &nullString);
				// pName1	= &nullString;
			}
			else
			{
// petra ***************************** change this
				pName1	= new char[strlen(name1[i]) + 1];
				strcpy(pName1, name1[i]);
// petra ***************************** to this
//				pName1 = clsResources::GetResourceFromId(name1_res_id[i], siteId);
// petra *****************************
			}

			if (name2_ind[i] == -1)
			{
				pName2 = new char[1];
				strcpy(pName2, &nullString);
				// pName2	= &nullString;
			}
			else
			{
// petra ***************************** change this
				pName2	= new char[strlen(name2[i]) + 1];
				strcpy(pName2, name2[i]);
// petra ***************************** to this
//				pName2 = clsResources::GetResourceFromId(name2_res_id[i], siteId);
// petra *****************************
			}
	
			if (name3_ind[i] == -1)
			{
				pName3 = new char[1];
				strcpy(pName3, &nullString);
				// pName3	= &nullString;
			}
			else
			{
// petra ***************************** change this
				pName3	= new char[strlen(name3[i]) + 1];
				strcpy(pName3, name3[i]);
// petra ***************************** to this
//				pName3 = clsResources::GetResourceFromId(name3_res_id[i], siteId);
// petra *****************************
			}
	
			if (name4_ind[i] == -1)
			{
				pName4 = new char[1];
				strcpy(pName4, &nullString);
				// pName4	= &nullString;
			}
			else
			{
// petra ***************************** change this
				pName4	= new char[strlen(name4[i]) + 1];
				strcpy(pName4, name4[i]);
// petra ***************************** to this
//				pName4 = clsResources::GetResourceFromId(name4_res_id[i], siteId);
// petra *****************************
			}
	
			if (fileRef_ind[i] == -1)
			{
				pFileRef = new char[1];
				strcpy(pFileRef, &nullString);
				// pFileRef	= &nullString;
			}
			else
			{
				pFileRef	= new char[strlen(fileRef[i]) + 1];
				strcpy(pFileRef, fileRef[i]);
			}

			if (flags_ind[i] == -1)
			{
				maskBidders	= false;
				screenItems = false;
			}
			else
			{
				if (flags[i] & CategoryFlagMaskBidders)
					maskBidders = true;
				else
					maskBidders = false;

				if (flags[i] & CategoryFlagScreenItems)
					screenItems = true;
				else
					screenItems = false;
			}
	
			pCategory	= new clsCategory(
								  marketplace,
								  resId[i],
								  pName,
								  pDescription,
								  adult[i][0],
								  isLeaf[i],
								  isExpired[i],
								  lev1[i],
								  lev2[i],
								  lev3[i],
								  lev4[i],
								  pName1,
								  pName2,
								  pName3,
								  pName4,
								  prevcategory[i],
								  nextcategory[i],
								  featuredCost[i],
								  create_date[i],
								  pFileRef,
								  modified_date[i],
								  maskBidders,
								  screenItems,
								  siteId);

			pvCategories->push_back(pCategory);
		}

	} while (!CheckForNoRowsFound());

	// clean up
	Close(ppTempCDA);
	SetStatement(NULL);
	return;
}



//
// GetNextCategoryId
//
// Retrieves the next available category id. Whether
// this is done with a sequence, or a column in
// a table is irrelevant
//
static const char *SQL_GetNextCategoryId =
 "select ebay_categories_sequence.nextval from dual";

int clsDatabaseOracle::GetNextCategoryId()
{
	int			nextId;

	// Not used often, so we don't need a persistent
	// cursor

	OpenAndParse(&mpCDAOneShot, 
				 SQL_GetNextCategoryId);

	Define(1, &nextId);

	// Execute
	ExecuteAndFetch();

	// Close and Clean
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return nextId;
}


//
// Item and Category related calls
//

//
// GetCategoryItems - all items in a category with a specified ending date
//
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetCategoryItemsAfter =
 "select	id,									\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		sale_end > 	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :catId or					\
		  level1 = :catId or					\
		  level2 = :catId or					\
		  level3 = :catId or					\
		  level4 = :catId) and					\
		  isleaf = '1')						\
			order by sale_start desc";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetCategoryItemsBefore =
 "select	id,									\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		sale_end <=	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :catId or					\
		  level1 = :catId or					\
		  level2 = :catId or					\
		  level3 = :catId or					\
		  level4 = :catId) and					\
		  isleaf = '1')						\
			order by sale_start desc";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetCategoryItemsAfterEnded =
 "select	id,									\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id,							\
	from ebay_items_ended						\
	where	marketplace = :marketplace			\
	and		sale_end > 	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :catId or					\
		  level1 = :catId or					\
		  level2 = :catId or					\
		  level3 = :catId or					\
		  level4 = :catId) and					\
		  isleaf = '1')						\
			order by sale_start desc";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetCategoryItemsBeforeEnded =
 "select	id,									\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items_ended						\
	where	marketplace = :marketplace			\
	and		sale_end <=	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :catId or					\
		  level1 = :catId or					\
		  level2 = :catId or					\
		  level3 = :catId or					\
		  level4 = :catId) and					\
		  isleaf = '1')						\
			order by sale_start desc";


// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetCategoryItems(MarketPlaceId marketplace, CategoryId catId,
										 int QueryCode,
									     time_t enddate, ItemVector *pvItems,
						   ItemListSortEnum SortCode, /* = SortItemsByUnknown */
						   bool ended)
{
	// Temporary slots for things to live in
	int					id;
	AuctionTypeEnum		saleType;
	char				title[255];
	char				location[255];
	int					seller;
	int					owner;
	int					password;
	int					category;
	int					quantity;
	int					bidcount;
	char				sale_start[32];
	time_t				sale_start_time;
	char				sale_end[32];
	time_t				sale_end_time;
	int					sale_status;
	float				current_price;
	float				start_price;
	float				reserve_price;
	int					high_bidder;
	sb2					high_bidder_ind;

	char				featured[2];
	char				superFeatured[2];
	char				boldTitle[2];
	char				privateSale[2];
	char				registeredOnly[2];
	char				host[65];
	sb2					host_ind;
	char				*pHost;
	int					visitcount = 0;

	char				pictureURL[256];
	sb2					pictureURL_ind;
	char				*pPictureURL;

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	bool				isPrivate;
	bool				isRegisteredOnly;

	char				*pLocation;
	char				*pTitle;

	time_t				last_modified_time;
	char				last_modified[32];
	char				cEndDate[64];

	clsItem				*pItem;
	struct tm			*pEndDate;

	// nsacco 07/27/99
	int					shipping_option;
	long				ship_region_flags;
	int					desc_lang;
	int					site_id;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)

	switch (QueryCode) 
	{
	case 1: 
		{
			if (ended)
				OpenAndParse(&mpCDAOneShot, 
					 SQL_GetCategoryItemsAfterEnded);
			else
				OpenAndParse(&mpCDAOneShot, 
					 SQL_GetCategoryItemsAfter);

			break;
		}
	case 2:
		{
			if (ended)
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsBeforeEnded);
			else
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsBefore);

			break;
		}
	default:
		{ // currently assume 1
			if (ended)
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsAfterEnded);
			else
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsAfter);

			break;
		}
	}

	pEndDate	= localtime(&enddate);

	TM_STRUCTToORACLE_DATE(pEndDate,
						   cEndDate);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":catId", (int *)&catId);
	Bind(":enddate", cEndDate);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, &id);
	Define(2, (int *)&saleType);
	Define(3, title, sizeof(title));
	Define(4, location, sizeof(location));
	Define(5, &seller);
	Define(6, &owner);
	Define(7, &password);
	Define(8, &category);
	Define(9, &quantity);
	Define(10, &bidcount);
	Define(11, sale_start, sizeof(sale_start));
	Define(12, sale_end, sizeof(sale_end));
	Define(13, &sale_status);
	Define(14, &current_price);
	Define(15, &start_price);
	Define(16, &reserve_price);
	Define(17, &high_bidder, &high_bidder_ind);
	Define(18, featured, sizeof(featured));
	Define(19, superFeatured, sizeof(superFeatured));
	Define(20, boldTitle, sizeof(boldTitle));
	Define(21, privateSale, sizeof(privateSale));
	Define(22, registeredOnly, sizeof(registeredOnly));
	Define(23, host, sizeof(host), &host_ind);
	Define(24, pictureURL, sizeof(pictureURL), &pictureURL_ind);
	Define(25, last_modified, sizeof(last_modified));
	// nsacco 07/27/99
	Define(26, &shipping_option);
	Define(27, &ship_region_flags);
	Define(28, &desc_lang);
	Define(29, &site_id);

	// Let's do the SQL
	Execute();

	// And fetch the rows
	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
		{
			break;
		}

		// Now everything is where it's supposed
		// to be. Let's make copies of the title
		// and location for the item
		pTitle		= new char[strlen(title) + 1];
		strcpy(pTitle, (char *)title);
		pLocation	= new char[strlen(location) + 1];
		strcpy(pLocation, (char *)location);

		// Time Conversions
		ORACLE_DATEToTime(sale_start, &sale_start_time);
		ORACLE_DATEToTime(sale_end, &sale_end_time);
		ORACLE_DATEToTime(last_modified, &last_modified_time);
		// Handle null high bidder
		if (high_bidder_ind == -1)
			high_bidder = 0;

		// Transform flags.
		if (featured[0] == '1')
			isFeatured	= true;
		else
			isFeatured	= false;

		if (superFeatured[0] == '1')
			isSuperFeatured	= true;
		else
			isSuperFeatured	= false;

		if (boldTitle[0] == '1')
			isBold	= true;
		else
			isBold	= false;

		if (privateSale[0] == '1')
			isPrivate	= true;
		else
			isPrivate	= false;

		if (registeredOnly[0] == '1')
			isRegisteredOnly	= true;
		else
			isRegisteredOnly	= false;

		if (host_ind == -1)
		{
			pHost	= NULL;
		}
		else
		{
			pHost	= new char[strlen(host) + 1];
			strcpy(pHost, host);
		}
		
		if (pictureURL_ind == -1)
		{
			pPictureURL	= NULL;
		}
		else
		{
			pPictureURL	= new char[strlen(pictureURL) + 1];
			strcpy(pPictureURL, pictureURL);
		}

		// nsacco 07/27/99 handle nulls for new params
		if (shipping_option == -1)
		{
			if (password & ShippingInternationally)
			{
				// handle old items
				shipping_option = Worldwide;
				password = password & ~ShippingInternationally;
			}
			else
			{
				shipping_option = SiteOnly;
			}
		}

		if (ship_region_flags == -1)
		{
			ship_region_flags = ShipRegion_None;
		}

		if (desc_lang == -1)
		{
			desc_lang = English;
		}

		if (site_id == -1)
		{
			site_id = SITE_EBAY_MAIN;
		}

		// Fill in the item
		pItem	= new clsItem;
		// nsacco 07/27/99
		pItem->Set(marketplace,
				   id,
				   saleType,
				   pTitle,
				   NULL,
				   pLocation,
				   seller,
				   owner,
				   category,
				   bidcount,
				   quantity,
				   sale_start_time,
				   sale_end_time,
				   sale_status,
				   current_price,
				   start_price,
				   reserve_price,
				   high_bidder,
				   isFeatured,
				   isSuperFeatured,
				   isBold,
				   isPrivate, 
				   isRegisteredOnly,
				   pHost,
				   visitcount,
				   pPictureURL,
				   NULL,			// category name
				   NULL,			// seller user id
				   0,				// seller user state
				   0,				// seller user flags
				   NULL,			// high bidder user id
				   0,				// high bidder user state
				   0,				// high bidder user flags
				   0,				// seller feedback score
				   0,				// high bidder feedback score
				   0,				// seller id last change
				   0,				// high bidder id last change
				   last_modified_time,
				   NULL, //seller email
				   NULL, // bidder email
				   0, //password
				   NULL, //rowid
				   long(0), //delta
				   NULL, // icon flags
				   NULL, // gallery URL
				   NoneGallery,
				   kGalleryNotProcessed,
				   Country_None, // country id
				   Currency_USD, // currency
				   ended,
				   NULL,		// zip
				   Currency_USD,	// billing currency
				   shipping_option,
				   ship_region_flags,
				   desc_lang,
				   site_id
				   );

		pvItems->push_back(pItem);

	}

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	// Sort
	this->SortItems(pvItems, SortCode);
	if (!ended)
		GetCategoryItems(marketplace, catId, QueryCode, enddate, pvItems,
						   SortCode,true);
	return;
}

// 10/21/97 removed
//	and	marketplace = :marketplace				\
//  for speed reasons
static const char *SQL_GetCategoryItemsCountAfter =
 "select	count(*)							\
	from ebay_items								\
	where		sale_end >= 	TO_DATE(:enddate,	\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :category or					\
		  level1 = :category or					\
		  level2 = :category or					\
		  level3 = :category or					\
		  level4 = :category) and				\
		  isleaf='1')";	

static const char *SQL_GetCategoryItemsCountBefore =
 "select	count(*)							\
	from ebay_items								\
	where		sale_end < TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :category or					\
		  level1 = :category or					\
		  level2 = :category or					\
		  level3 = :category or					\
		  level4 = :category) and				\
		  isleaf='1')";		

static const char *SQL_GetCategoryItemsCountStartAfter =
 "select	count(*)							\
	from ebay_items								\
	where		sale_start >= TO_DATE(:startdate,	\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :category or					\
		  level1 = :category or					\
		  level2 = :category or					\
		  level3 = :category or					\
		  level4 = :category) and				\
		  isleaf='1')";	


static const char *SQL_GetCategoryItemsCountBetween =
 "select	count(*)							\
	from ebay_items								\
	where		sale_end > TO_DATE(:startdate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		sale_end < TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :category or					\
		  level1 = :category or					\
		  level2 = :category or					\
		  level3 = :category or					\
		  level4 = :category) and				\
		  isleaf='1')";	

static const char *SQL_GetCategoryItemsCountStillOpen =
 "select	count(*)							\
	from ebay_items								\
	where		sale_end > TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :category or				\
		  level1 = :category or					\
		  level2 = :category or					\
		  level3 = :category or					\
		  level4 = :category) and				\
		  isleaf='1')";

static const char *SQL_GetCategoryItemsCountAfterEnded =
 "select	count(*)							\
	from ebay_items_ended								\
	where		sale_end >= TO_DATE(:enddate,	\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :category or				\
		  level1 = :category or					\
		  level2 = :category or					\
		  level3 = :category or					\
		  level4 = :category) and				\
		  isleaf='1')";	

static const char *SQL_GetCategoryItemsCountBeforeEnded =
 "select	count(*)							\
	from ebay_items_ended								\
	where		sale_end < TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :category or					\
		  level1 = :category or					\
		  level2 = :category or					\
		  level3 = :category or					\
		  level4 = :category) and				\
		  isleaf='1')";		

static const char *SQL_GetCategoryItemsCountStartAfterEnded =
 "select	count(*)							\
	from ebay_items_ended								\
	where		sale_start >= TO_DATE(:startdate,	\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :category or					\
		  level1 = :category or					\
		  level2 = :category or					\
		  level3 = :category or					\
		  level4 = :category) and				\
		  isleaf='1')";	


static const char *SQL_GetCategoryItemsCountBetweenEnded =
 "select	count(*)							\
	from ebay_items_ended								\
	where		sale_end > TO_DATE(:startdate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		sale_end < TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :category or					\
		  level1 = :category or					\
		  level2 = :category or					\
		  level3 = :category or					\
		  level4 = :category) and				\
		  isleaf='1')";	

static const char *SQL_GetCategoryItemsCountStillOpenEnded =
 "select	count(*)							\
	from ebay_items_ended								\
	where		sale_end > TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		category in							\
	(select id from ebay_categories				\
		where (id = :category or				\
		  level1 = :category or					\
		  level2 = :category or					\
		  level3 = :category or					\
		  level4 = :category) and				\
		  isleaf='1')";

int clsDatabaseOracle::GetCategoryItemsCount(MarketPlaceId /* marketplace */, 
											 CategoryId category,
											 int QueryCode,
											 time_t startdate,
											 time_t enddate, bool ended)
{
	// Temporary slots for things to live in
	int					count;
	char				cEndDate[64] = {'\0'};
	char				cStartDate[64] = {'\0'};
	struct tm			*pEndDate;	
	struct tm			*pStartDate;
	bool				endedNotNeeded = false;
	time_t				nowTime = time(0);

	if ((enddate > nowTime) && ((QueryCode == 1) || (QueryCode == 2) || (QueryCode == 4) ||
		(QueryCode == 5)))
		endedNotNeeded = true;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)

	if ((QueryCode == 1) || (QueryCode == 2) || (QueryCode == 4) ||
		(QueryCode == 5))
	{
		pEndDate	= localtime(&enddate);
		TM_STRUCTToORACLE_DATE(pEndDate,
						   cEndDate);
	};

	if ((QueryCode == 3) || (QueryCode == 4))
	{
		pStartDate  = localtime(&startdate);
		TM_STRUCTToORACLE_DATE(pStartDate,
						   cStartDate);
	};

	switch (QueryCode) 
	{
	case 1: 
		{
			if (endedNotNeeded || !ended)
				OpenAndParse(&mpCDAOneShot, 
					 SQL_GetCategoryItemsCountAfter);
			else
				OpenAndParse(&mpCDAOneShot, 
					 SQL_GetCategoryItemsCountAfterEnded);
			Bind(":enddate", cEndDate);

			break;
		}
	case 2:
		{
			if (endedNotNeeded || !ended)
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsCountBefore);
			else
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsCountBeforeEnded);
			Bind(":enddate", cEndDate);

			break;
		}
	case 3:
		{
			if (endedNotNeeded || !ended)
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsCountStartAfter);
			else
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsCountStartAfterEnded);
			Bind(":startdate", cStartDate);
			break;
		}
	case 4:
		{
			if (endedNotNeeded || !ended)
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsCountBetween);
			else
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsCountBetweenEnded);
			Bind(":enddate", cEndDate);
			Bind(":startdate", cStartDate);
			break;
		}
	case 5:
		{
			OpenAndParse(&mpCDAOneShot,
				SQL_GetCategoryItemsCountStillOpen);
			Bind(":enddate", cEndDate);
			break;
		}
	default:
		{ // currently assume 1
			if (endedNotNeeded || !ended)
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsCountStillOpen);
			else
				OpenAndParse(&mpCDAOneShot,
					SQL_GetCategoryItemsCountStillOpenEnded);
			Bind(":enddate", cEndDate);
			break;
		}
	}
	

	// Bind the rest of the input variables
//	Bind(":marketplace", (int *)&marketplace);
	Bind(":category", (int *)&category);

	
	// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		// do nothing?
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	if (endedNotNeeded || ended)
		return count;
	else
		return count += GetCategoryItemsCount(0, category, QueryCode, startdate,
											enddate, true);
}

// petra removed *SQL_SetCategoryItems, void clsDatabaseOracle::SetCategoryItems(MarketPlaceId marketplace, 
//											CategoryId oldCategory, 
//											CategoryId newCategory)
//		(see E117 to restore)


// petra removed *SQL_SetCategoryUsersInterest1,
//		 *SQL_SetCategoryUsersInterest2,
//		 *SQL_SetCategoryUsersInterest3,
//		 *SQL_SetCategoryUsersInterest4, void clsDatabaseOracle::SetCategoryUsersInterests(CategoryId oldCategory, 
//											CategoryId newCategory)
//		(see E117 to restore)

static const char *SQL_GetCategoryCount =
 "select	count(*)							\
	from ebay_categories						\
	where	marketplace = :marketplace";	

int clsDatabaseOracle::GetCategoryCount(MarketPlaceId marketplace)
{
	// Temporary slots for things to live in
	int					count;

	OpenAndParse(&mpCDAOneShot, 
				 SQL_GetCategoryCount);

	// Bind the rest of the input variables
	Bind(":marketplace", (int *)&marketplace);
	
	// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		// do nothing?
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return count;
}

static const char *SQL_GetCategoryCountInCategory =
 "select	count(*)							\
	from ebay_categories						\
	where marketplace = :marketplace and		\
		  (id = :catId or						\
		  level1 = :catId or					\
		  level2 = :catId or					\
		  level3 = :catId or					\
		  level4 = :catId)";	

int clsDatabaseOracle::GetCategoryCountInCategory(MarketPlaceId marketplace, CategoryId Id)
{
	// Temporary slots for things to live in
	int					count;

	OpenAndParse(&mpCDAOneShot, 
				 SQL_GetCategoryCountInCategory);

	// Bind the rest of the input variables
	Bind(":marketplace", (int *)&marketplace);
	Bind(":catId", (int *)&Id);
	
	// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		// do nothing?
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return count;
}



// for category admin, get exclusive lock on table
// DO NOT USE
static const char *SQL_LockCategoryTable =
 "lock table ebay_categories in exclusive mode nowait";

void clsDatabaseOracle::LockCategoryTable()
{
	OpenAndParse(&mpCDAOneShot, 
				 SQL_LockCategoryTable);

	// Let's do the SQL
	Execute();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

}


//
// GetMaxCategoryId
//
static const char *SQL_GetMaxCategoryId =
 "select	MAX(id)									\
	from	ebay_categories							\
	where	marketplace = :marketplace";

int clsDatabaseOracle::GetMaxCategoryId(MarketPlaceId marketplace)
{
	int		id;

	OpenAndParse(&mpCDAOneShot, SQL_GetMaxCategoryId);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);

	Define(1, &id);

	Execute();

	Fetch();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return id;

}


//
// GetNumberOfChildLeafCategories
// (include the current id if it is leaf)
//
static const char *SQL_GetNumberOfChildLeafCategories =
 "select	count(*)								\
	from	ebay_categories							\
	where	marketplace = :marketplace				\
	and		isleaf = '1'							\
	and		(id		= :id or						\
			 level1 = :id or						\
			 level2 = :id or						\
			 level3 = :id or						\
			 level4 = :id)";

int clsDatabaseOracle::GetNumberOfChildLeafCategories(MarketPlaceId marketplace, int CatId)
{
	int		count;

	OpenAndParse(&mpCDAOneShot, SQL_GetNumberOfChildLeafCategories);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", (int*)&CatId);

	Define(1, &count);

	Execute();

	Fetch();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return count;

}


//
// GetChildLeafCategoryIds
// (include the current id if it is leaf)
//
static const char *SQL_GetChildLeafCategoryIds =
 "select	id										\
	from	ebay_categories							\
	where	marketplace = :marketplace				\
	and		isleaf = '1'							\
	and		(id		= :id or						\
			 level1 = :id or						\
			 level2 = :id or						\
			 level3 = :id or						\
			 level4 = :id)";

void clsDatabaseOracle::GetChildLeafCategoryIds(MarketPlaceId marketplaceid,
														  int  CatId,
														  int** pCategoryIds)
{
	int	id;
	int	i;
	int	NumberOfCategories = GetNumberOfChildLeafCategories(marketplaceid, CatId);
	int*	pCatIds;

	if (NumberOfCategories == 0)
	{
		*pCategoryIds = NULL;
		
		return;
	}

	pCatIds = new int[NumberOfCategories+1];

	OpenAndParse(&mpCDAOneShot, SQL_GetChildLeafCategoryIds);

	Bind(":marketplace", (int *)&marketplaceid);
	Bind(":id", (int *)&CatId);

	Define(1, &id);

	// Let's do the SQL
	Execute();

	// And fetch the rows
	i = 0;
	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
		{
			break;
		}

		pCatIds[i++] = id;
	}

	// set the terminator
	pCatIds[i] = -1;

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	// set the return value
	*pCategoryIds = pCatIds;

	return;
}

//
// Get first two level category count
//
//
static const char *SQL_GetFirstTwoLevelCategoryCount =
 "select	count(*)								\
	from	ebay_categories							\
	where	marketplace = :marketplace				\
	and		level2 = 0 ";

int clsDatabaseOracle::GetFirstTwoLevelCategoryCount(MarketPlaceId marketplace)
{
	int		count;

	OpenAndParse(&mpCDAOneShot, SQL_GetFirstTwoLevelCategoryCount);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);

	Define(1, &count);

	Execute();

	Fetch();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return count;
}

// petra removed *SQL_UpdateCategoryCounter, void clsDatabaseOracle::UpdateCategoryCounter(
//							clsCategory *pCategory,
//							int count)
// 		(see E117 to restore)


//
// GetNotLeafCategoryIds
// (include the current id if it is leaf)
//
static const char *SQL_GetNotLeafCategoryIds =
 "select	id										\
	from	ebay_categories							\
	where	marketplace = :marketplace				\
	and		isleaf = '0'";

void clsDatabaseOracle::GetNotLeafCategoryIds(MarketPlaceId marketplaceid,
										vector<int>* pvCategoryIds)
{
	int	id;

	OpenAndParse(&mpCDAOneShot, SQL_GetNotLeafCategoryIds);

	Bind(":marketplace", (int *)&marketplaceid);

	Define(1, &id);

	// Let's do the SQL
	Execute();

	// And fetch the rows
	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
		{
			break;
		}

		pvCategoryIds->push_back(id);
	}

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

static const char *SQL_GetHighestCategoryNumber =
 "select max(id) from ebay_categories";

static const char *SQL_GetCategoryIdsFromOpenItems =
 "select category, count(*) from ebay_items where sale_end > sysdate group by category";

// same as SQL_GetCategoryIdsFromOpenItems but for a specific non-zero region
static const char *SQL_GetCategoryIdsFromOpenItemsByRegion =
 "select category, count(*) from ebay_items								  \
    where sale_end > sysdate											  \
	and zip in (select zip from ebay_regions where region_id = :regionId) \
	group by category";

void clsDatabaseOracle::GetCategoryCountsFromOpenItems(vector<int> **ppvCounts, int iRegionID /*=0*/)
{
	int highest_category;
	int count;
	int id;
	vector<int> *pvCounts;

	OpenAndParse(&mpCDAOneShot, SQL_GetHighestCategoryNumber);

	Define(1, &highest_category);

	Execute();

	Fetch();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	// The * 2 is definitely paranoia, but it might just save us
	// sometime.
	pvCounts = new vector<int>(highest_category * 2, 0);

	// include all regions if region ID is 0
	if (iRegionID == 0)
		OpenAndParse(&mpCDAOneShot, SQL_GetCategoryIdsFromOpenItems);
	else
	{
		OpenAndParse(&mpCDAOneShot, SQL_GetCategoryIdsFromOpenItemsByRegion);
		Bind(":regionId", &iRegionID);
	}

	Define(1, &id);
	Define(2, &count);

	// Let's do the SQL
	Execute();

	// And fetch the rows
	while(1)
	{
		Fetch();

		// And here, we just increment the counter every time we see one.
		if (CheckForNoRowsFound())
		{
			break;
		}
		(*pvCounts)[id] = count;
	}

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	*ppvCounts = pvCounts;

	return;
}



//
// 11/19/1997 Added by Charles
//
static const char *SQL_GetCategoryItemsCurrentCount =
 "select	count(id)								\
	from ebay_items									\
	where		sale_end >= 	TO_DATE(:enddate,	\
				'YYYY-MM-DD HH24:MI:SS')			\
	and		category = :category ";

static const char *SQL_GetCategoryItemsEndCount =
 "select	count(id)								\
	from ebay_items									\
	where		sale_end < TO_DATE(:startdate,		\
				'YYYY-MM-DD HH24:MI:SS')			\
	and		category = :category ";

static const char *SQL_GetCategoryItemsStartCount =
 "select	count(id)								\
	from ebay_items									\
	where		sale_start >= TO_DATE(:startdate,	\
				'YYYY-MM-DD HH24:MI:SS')			\
	and		category = :category ";

int clsDatabaseOracle::GetItemsCountByCategory( CategoryId category,
												int QueryCode,
												time_t startdate,
												time_t enddate)
{
	// Temporary slots for things to live in
	int					count = 0;
	char				cEndDate[64] = {'\0'};
	char				cStartDate[64]= {'\0'};
	struct tm			*pEndDate;	
	struct tm			*pStartDate;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)

	if (QueryCode == 1)
	{
		pEndDate	= localtime(&enddate);
		TM_STRUCTToORACLE_DATE(pEndDate,
						   cEndDate);
	}
	else
	{
		//
		// Here the start date is the current date
		//
		if(QueryCode == 2)
		{
			// startDate - 24 hours
			startdate = startdate - (24 * 60 * 60);
		}
		//
		// Here the start date is the current date
		//
		if(QueryCode == 3)
		{
			// startDate + 24 hours
			startdate = startdate + (24 * 60 * 60);
		}

		if(QueryCode == 5)
		{
			// startDate + 3 hours
			startdate = startdate + (3 * 60 * 60);
		}

		pStartDate  = localtime(&startdate);
		TM_STRUCTToORACLE_DATE(pStartDate,
						   cStartDate);
	};

	switch (QueryCode) 
	{
	case 1: 
		{
			OpenAndParse(&mpCDAOneShot, 
				 SQL_GetCategoryItemsCurrentCount);
			Bind(":enddate", cEndDate);

			break;
		}

	case 2:
		{
			OpenAndParse(&mpCDAOneShot,
				SQL_GetCategoryItemsStartCount);
			Bind(":startdate", cStartDate);

			break;
		}

	case 3:
	case 4:
	case 5:
		{
			OpenAndParse(&mpCDAOneShot,
				SQL_GetCategoryItemsEndCount);
			Bind(":startdate", cStartDate);
			break;
		}
	default:
		{ // currently assume 1
			OpenAndParse(&mpCDAOneShot,
				SQL_GetCategoryItemsCurrentCount);
			Bind(":enddate", cEndDate);
			break;
		}
	}
	

	// Bind the rest of the input variables
	Bind(":category", (int *)&category);

	
	// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		// do nothing?
	}

	//
	// Now everything is where it's supposed to be.
	//
	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return count;
}

static const char *SQL_GetAdultCategoryIds =
"select    id "
"  from    ebay_categories "
"  where   adult='1' "
"  and     marketplace=:marketPlace";

void clsDatabaseOracle::GetAdultCategoryIds(clsMarketPlace *pMarketPlace, 
										vector<CategoryId> *pvAdultCategories)
{
	int	id;
	int	marketPlaceId;

	OpenAndParse(&mpCDAGetAdultCategoryIds, SQL_GetAdultCategoryIds);

	marketPlaceId = pMarketPlace->GetId();
	Bind(":marketPlace", (int *)&marketPlaceId);

	// Define the output variable
	Define(1, &id);

	// Let's do the SQL
	Execute();

	// And fetch the rows
	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
		{
			break;
		}

		pvAdultCategories->push_back(id);
	}

 	Close (&mpCDAGetAdultCategoryIds);
	SetStatement(NULL);

	return;
}


//
// MaskCategory
//

static const char *SQL_MaskCategory =
"update ebay_categories "
"	set flags = :flags "
"	where marketplace = :marketplace "
"	and		id = :id";

bool clsDatabaseOracle::MaskCategory(MarketPlaceId marketplace,
									 unsigned int categoryId,
									 bool on)
{
	clsCategory *	pCategory = NULL;
	bool			success = false;
	unsigned int	flags = 0;

	pCategory = GetCategoryById(marketplace, categoryId, 0); // default site Id
	if (pCategory == NULL)
		return false;

	flags = pCategory->GetIsFlagged();
	if (on)
		flags |= CategoryFlagMaskBidders;
	else
		flags &= ~CategoryFlagMaskBidders;

	OpenAndParse(&mpCDAMaskCategory, SQL_MaskCategory);

	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", (int *)&categoryId);
	Bind(":flags", (int *)&flags);

	Execute();

	if (!CheckForNoRowsUpdated())
	{
		Commit();
		success = true;
	}

	Close(&mpCDAMaskCategory);
	SetStatement(NULL);

	return success;
}


#if 0
//
// GetMaskedCategories
//

static const char *SQL_GetMaskedCategories =
"select marketplace, "
"		id, "
"		name, "
"		description, "
"		adult, "
"		isleaf,	 "
"		isexpired, "
"		level1, "
"		level2, "
"		level3, "
"		level4, "
"		name1, "
"		name2, "
"		name3, "
"		name4, "
"		prevcategory, "
"		nextcategory, "
"		featuredCost, "
"		TO_CHAR(created, "
"				'YYYY-MM-DD HH24:MI:SS'), "
"		 fileReference, "
"		TO_CHAR(last_modified, "
"				'YYYY-MM-DD HH24:MI:SS'), "
"		flags "
"	from ebay_categories "
"	where	marketplace = :marketplace "
"	and		flags is not null";

void clsDatabaseOracle::GetMaskedCategories(MarketPlaceId marketplace,
											CategoryVector *pvCategories)
{
	// Temporary slots for things to live in
	CategoryId		resId[ORA_CATEGORY_ARRAYSIZE];
	char			description[ORA_CATEGORY_ARRAYSIZE][256];
	char			name[ORA_CATEGORY_ARRAYSIZE][51];
	char			*pName;
	char			*pDescription;
	char			adult[ORA_CATEGORY_ARRAYSIZE][2];
	char			isleaf[ORA_CATEGORY_ARRAYSIZE][2];
	bool			isLeaf[ORA_CATEGORY_ARRAYSIZE];
	bool			isExpired[ORA_CATEGORY_ARRAYSIZE];
	char			isexpired[ORA_CATEGORY_ARRAYSIZE][2];
	CategoryId		lev1[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		lev2[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		lev3[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		lev4[ORA_CATEGORY_ARRAYSIZE];
	char			*pName1;
	char			*pName2;
	char			*pName3;
	char			*pName4;
	char			name1[ORA_CATEGORY_ARRAYSIZE][51];
	char			name2[ORA_CATEGORY_ARRAYSIZE][51];
	char			name3[ORA_CATEGORY_ARRAYSIZE][51];
	char			name4[ORA_CATEGORY_ARRAYSIZE][51];
	sb2				name1_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2				name2_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2				name3_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2				name4_ind[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		prevcategory[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		nextcategory[ORA_CATEGORY_ARRAYSIZE];
	float			featuredCost[ORA_CATEGORY_ARRAYSIZE];
	char			ccreate_date[ORA_CATEGORY_ARRAYSIZE][32];
	time_t			create_date[ORA_CATEGORY_ARRAYSIZE];
	char			cmodified_date[ORA_CATEGORY_ARRAYSIZE][32];
	time_t			modified_date[ORA_CATEGORY_ARRAYSIZE];
	char			fileRef[ORA_CATEGORY_ARRAYSIZE][256];
	sb2				fileRef_ind[ORA_CATEGORY_ARRAYSIZE];
	char			*pFileRef;
	unsigned int	flags[ORA_CATEGORY_ARRAYSIZE];
	sb2				flags_ind[ORA_CATEGORY_ARRAYSIZE];
	bool			maskBidders;
	bool			screenItems;
	int				rowsFetched;
	int				rc;
	int				i,n;
	char			nullString	= '\0';

	clsCategory		*pCategory;

	if (pvCategories == NULL)
		return;

	OpenAndParse(&mpCDAGetMaskedCategories, SQL_GetCategoryAll);

	// Bind input variables
	Bind(":marketplace", (int *)&marketplace);

	// Define
	Define(1, resId);
	Define(2, name[0], sizeof(name[0]));
	Define(3, description[0], sizeof(description[0]));
	Define(4, adult[0], sizeof(adult[0]));
	Define(5, isleaf[0], sizeof(isleaf[0]));
	Define(6, isexpired[0], sizeof(isexpired[0]));
	Define(7, (int *)lev1);
	Define(8, (int *)lev2);
	Define(9, (int *)lev3);
	Define(10, (int *)lev4);
	Define(11, name1[0], sizeof(name1[0]), name1_ind);
	Define(12, name2[0], sizeof(name2[0]), name2_ind);
	Define(13, name3[0], sizeof(name3[0]), name3_ind);
	Define(14, name4[0], sizeof(name4[0]), name4_ind);
	Define(15, (int *)prevcategory);
	Define(16, (int *)nextcategory);
	Define(17, featuredCost);
	Define(18, ccreate_date[0], sizeof(ccreate_date[0]));
	Define(19, fileRef[0], sizeof(fileRef[0]), fileRef_ind);
	Define(20, cmodified_date[0], sizeof(cmodified_date[0]));
	Define(21, (int *)flags, flags_ind);

	// Execute...
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetMaskedCategories, true);
		SetStatement(NULL);
		return;
	}

	// Fetch till we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_CATEGORY_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetMaskedCategories, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. Let's make copies of the name
			// and description for the category

			// Build a nice object for our caller.
			pDescription	
				= new char[strlen(description[i]) + 1];
			strcpy(pDescription, description[i]);
			pName 
				= new char[strlen(name[i]) + 1];
			strcpy(pName, name[i]);

			ORACLE_DATEToTime(ccreate_date[i], &create_date[i]);
			ORACLE_DATEToTime(cmodified_date[i], &modified_date[i]);

			if (isleaf[i][0] == '1')
				isLeaf[i]	= true;
			else
				isLeaf[i]	= false;
		
			if (isexpired[i][0] == '1')
				isExpired[i]	= true;
			else
				isExpired[i]	= false;
	
			if (flags_ind == -1)
			{
				maskBidders	= false;
				screenItems = false
			}
			else
			{
				if (flags & CategoryFlagMaskBidders)
					maskBidders	= true;
				else
					maskBidders	= false;

				if (flags & CategoryFlagScreenItems)
					screenItems	= true;
				else
					screenItems	= false;
			}
	
	
			if (name1_ind[i] == -1)
			{
				pName1 = new char[1];
				strcpy(pName1, &nullString);
				// pName1	= &nullString;
			}
			else
			{
				pName1	= new char[strlen(name1[i]) + 1];
				strcpy(pName1, name1[i]);
			}

			if (name2_ind[i] == -1)
			{
				pName2 = new char[1];
				strcpy(pName2, &nullString);
				// pName2	= &nullString;
			}
			else
			{
				pName2	= new char[strlen(name2[i]) + 1];
				strcpy(pName2, name2[i]);
			}
	
			if (name3_ind[i] == -1)
			{
				pName3 = new char[1];
				strcpy(pName3, &nullString);
				// pName3	= &nullString;
			}
			else
			{
				pName3	= new char[strlen(name3[i]) + 1];
				strcpy(pName3, name3[i]);
			}
	
			if (name4_ind[i] == -1)
			{
				pName4 = new char[1];
				strcpy(pName4, &nullString);
				// pName4	= &nullString;
			}
			else
			{
				pName4	= new char[strlen(name4[i]) + 1];
				strcpy(pName4, name4[i]);
			}
	
			if (fileRef_ind[i] == -1)
			{
				pFileRef = new char[1];
				strcpy(pFileRef, &nullString);
				// pFileRef	= &nullString;
			}
			else
			{
				pFileRef	= new char[strlen(fileRef[i]) + 1];
				strcpy(pFileRef, fileRef[i]);
			}

			pCategory	= new clsCategory(
								  marketplace,
								  resId[i],
								  pName,
								  pDescription,
								  adult[i][0],
								  isLeaf[i],
								  isExpired[i],
								  lev1[i],
								  lev2[i],
								  lev3[i],
								  lev4[i],
								  pName1,
								  pName2,
								  pName3,
								  pName4,
								  prevcategory[i],
								  nextcategory[i],
								  featuredCost[i],
								  create_date[i],
								  pFileRef,
								  modified_date[i],
								  maskBidders,
								  screenItems);

			pvCategories->push_back(pCategory);
		}

	} while (!CheckForNoRowsFound());

	// clean up
	Close(&mpCDAGetMaskedCategories);
	SetStatement(NULL);
	return;
}
#endif


//
// FlagCategory
//

static const char *SQL_FlagCategory =
"update ebay_categories "
"	set flags = :flags "
"	where marketplace = :marketplace "
"	and		id = :id";

bool clsDatabaseOracle::FlagCategory(MarketPlaceId marketplace,
									 unsigned int categoryId,
									 bool on)
{
	clsCategory *	pCategory = NULL;
	bool			success = false;
	unsigned int	flags = 0;

	pCategory = GetCategoryById(marketplace, categoryId, 0);	// default site id
	if (pCategory == NULL)
		return false;

	flags = pCategory->GetMaskBidders();
	if (on)
		flags |= CategoryFlagScreenItems;
	else
		flags &= ~CategoryFlagScreenItems;

	OpenAndParse(&mpCDAFlagCategory, SQL_FlagCategory);

	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", (int *)&categoryId);
	Bind(":flags", (int *)&flags);

	Execute();

	if (!CheckForNoRowsUpdated())
	{
		Commit();
		success = true;
	}

	Close(&mpCDAFlagCategory);
	SetStatement(NULL);

	return success;
}


#if 0
//
// GetFlaggedCategories
//
static const char *SQL_GetFlaggedCategories =
"select marketplace, "
"		id, "
"		name, "
"		description, "
"		adult, "
"		isleaf,	 "
"		isexpired, "
"		level1, "
"		level2, "
"		level3, "
"		level4, "
"		name1, "
"		name2, "
"		name3, "
"		name4, "
"		prevcategory, "
"		nextcategory, "
"		featuredCost, "
"		TO_CHAR(created, "
"				'YYYY-MM-DD HH24:MI:SS'), "
"		 fileReference, "
"		TO_CHAR(last_modified, "
"				'YYYY-MM-DD HH24:MI:SS'), "
"		flags "
"	from ebay_categories "
"	where	marketplace = :marketplace "
"	and		flags is not null";

void clsDatabaseOracle::GetFlaggedCategories(MarketPlaceId marketplace,
											 CategoryVector *pvCategories)
{
	// Temporary slots for things to live in
	CategoryId		resId[ORA_CATEGORY_ARRAYSIZE];
	char			description[ORA_CATEGORY_ARRAYSIZE][256];
	char			name[ORA_CATEGORY_ARRAYSIZE][51];
	char			*pName;
	char			*pDescription;
	char			adult[ORA_CATEGORY_ARRAYSIZE][2];
	char			isleaf[ORA_CATEGORY_ARRAYSIZE][2];
	bool			isLeaf[ORA_CATEGORY_ARRAYSIZE];
	bool			isExpired[ORA_CATEGORY_ARRAYSIZE];
	char			isexpired[ORA_CATEGORY_ARRAYSIZE][2];
	CategoryId		lev1[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		lev2[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		lev3[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		lev4[ORA_CATEGORY_ARRAYSIZE];
	char			*pName1;
	char			*pName2;
	char			*pName3;
	char			*pName4;
	char			name1[ORA_CATEGORY_ARRAYSIZE][51];
	char			name2[ORA_CATEGORY_ARRAYSIZE][51];
	char			name3[ORA_CATEGORY_ARRAYSIZE][51];
	char			name4[ORA_CATEGORY_ARRAYSIZE][51];
	sb2				name1_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2				name2_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2				name3_ind[ORA_CATEGORY_ARRAYSIZE];
	sb2				name4_ind[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		prevcategory[ORA_CATEGORY_ARRAYSIZE];
	CategoryId		nextcategory[ORA_CATEGORY_ARRAYSIZE];
	float			featuredCost[ORA_CATEGORY_ARRAYSIZE];
	char			ccreate_date[ORA_CATEGORY_ARRAYSIZE][32];
	time_t			create_date[ORA_CATEGORY_ARRAYSIZE];
	char			cmodified_date[ORA_CATEGORY_ARRAYSIZE][32];
	time_t			modified_date[ORA_CATEGORY_ARRAYSIZE];
	char			fileRef[ORA_CATEGORY_ARRAYSIZE][256];
	sb2				fileRef_ind[ORA_CATEGORY_ARRAYSIZE];
	char			*pFileRef;
	unsigned int	flags[ORA_CATEGORY_ARRAYSIZE];
	sb2				flags_ind[ORA_CATEGORY_ARRAYSIZE];
	bool			maskBidders;
	bool			screenItems;
	int				rowsFetched;
	int				rc;
	int				i,n;
	char			nullString	= '\0';

	clsCategory		*pCategory;

	if (pvCategories == NULL)
		return;

	OpenAndParse(&mpCDAGetMaskedCategories, SQL_GetCategoryAll);

	// Bind input variables
	Bind(":marketplace", (int *)&marketplace);

	// Define
	Define(1, resId);
	Define(2, name[0], sizeof(name[0]));
	Define(3, description[0], sizeof(description[0]));
	Define(4, adult[0], sizeof(adult[0]));
	Define(5, isleaf[0], sizeof(isleaf[0]));
	Define(6, isexpired[0], sizeof(isexpired[0]));
	Define(7, (int *)lev1);
	Define(8, (int *)lev2);
	Define(9, (int *)lev3);
	Define(10, (int *)lev4);
	Define(11, name1[0], sizeof(name1[0]), name1_ind);
	Define(12, name2[0], sizeof(name2[0]), name2_ind);
	Define(13, name3[0], sizeof(name3[0]), name3_ind);
	Define(14, name4[0], sizeof(name4[0]), name4_ind);
	Define(15, (int *)prevcategory);
	Define(16, (int *)nextcategory);
	Define(17, featuredCost);
	Define(18, ccreate_date[0], sizeof(ccreate_date[0]));
	Define(19, fileRef[0], sizeof(fileRef[0]), fileRef_ind);
	Define(20, cmodified_date[0], sizeof(cmodified_date[0]));
	Define(21, (int *)flags, flags_ind);

	// Execute...
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetMaskedCategories, true);
		SetStatement(NULL);
		return;
	}

	// Fetch till we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_CATEGORY_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetMaskedCategories, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. Let's make copies of the title
			// and location for the category

			// Build a nice object for our caller.
			pDescription	
				= new char[strlen(description[i]) + 1];
			strcpy(pDescription, description[i]);
			pName 
				= new char[strlen(name[i]) + 1];
			strcpy(pName, name[i]);

			ORACLE_DATEToTime(ccreate_date[i], &create_date[i]);
			ORACLE_DATEToTime(cmodified_date[i], &modified_date[i]);

			if (isleaf[i][0] == '1')
				isLeaf[i]	= true;
			else
				isLeaf[i]	= false;
		
			if (isexpired[i][0] == '1')
				isExpired[i]	= true;
			else
				isExpired[i]	= false;

			if (flags_ind == -1)
			{
				maskBidders	= false;
				screenItems	= false;
			}
			else
			{
				if (flags & CategoryFlagMaskBidders)
					maskBidders	= true;
				else
					maskBidders	= false;
		
				if (flags & CategoryFlagScreenItems)
					screenItems	= true;
				else
					screenItems	= false;
			}
	
			if (name1_ind[i] == -1)
			{
				pName1 = new char[1];
				strcpy(pName1, &nullString);
				// pName1	= &nullString;
			}
			else
			{
				pName1	= new char[strlen(name1[i]) + 1];
				strcpy(pName1, name1[i]);
			}

			if (name2_ind[i] == -1)
			{
				pName2 = new char[1];
				strcpy(pName2, &nullString);
				// pName2	= &nullString;
			}
			else
			{
				pName2	= new char[strlen(name2[i]) + 1];
				strcpy(pName2, name2[i]);
			}
	
			if (name3_ind[i] == -1)
			{
				pName3 = new char[1];
				strcpy(pName3, &nullString);
				// pName3	= &nullString;
			}
			else
			{
				pName3	= new char[strlen(name3[i]) + 1];
				strcpy(pName3, name3[i]);
			}
	
			if (name4_ind[i] == -1)
			{
				pName4 = new char[1];
				strcpy(pName4, &nullString);
				// pName4	= &nullString;
			}
			else
			{
				pName4	= new char[strlen(name4[i]) + 1];
				strcpy(pName4, name4[i]);
			}
	
			if (fileRef_ind[i] == -1)
			{
				pFileRef = new char[1];
				strcpy(pFileRef, &nullString);
				// pFileRef	= &nullString;
			}
			else
			{
				pFileRef	= new char[strlen(fileRef[i]) + 1];
				strcpy(pFileRef, fileRef[i]);
			}

			pCategory	= new clsCategory(
								  marketplace,
								  resId[i],
								  pName,
								  pDescription,
								  adult[i][0],
								  isLeaf[i],
								  isExpired[i],
								  lev1[i],
								  lev2[i],
								  lev3[i],
								  lev4[i],
								  pName1,
								  pName2,
								  pName3,
								  pName4,
								  prevcategory[i],
								  nextcategory[i],
								  featuredCost[i],
								  create_date[i],
								  pFileRef,
								  modified_date[i],
								  maskBidders,
								  screenItems);

			pvCategories->push_back(pCategory);
		}

	} while (!CheckForNoRowsFound());

	// clean up
	Close(&mpCDAGetMaskedCategories);
	SetStatement(NULL);
	return;
}

#endif