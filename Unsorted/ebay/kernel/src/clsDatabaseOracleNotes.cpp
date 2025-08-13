/*	$Id: clsDatabaseOracleNotes.cpp,v 1.4 1999/02/21 02:47:29 josh Exp $	*/
//
//	File:	clsDatabaseOracleNotes.cpp
//
//	Class:	clsDatabaseOracle
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 07/07/97 michael	- Created

#include "eBayKernel.h"

//
// LoadNotes
//

// Sigh. We could "create" the following SQL statements on the fly,
// but it doesn't seem like the right thing to do. For now. we'll
// just have them

const char *SQL_LoadNotesFrom =
"select	n.about,										\
		n.user_from,									\
		n.user_to,										\
		n.user_cc,										\
		n.aboutUser,									\
		n.aboutItem,									\
		TO_CHAR(n.when,									\
				'YYYY-MM-DD HH24:MI:SS'),				\
		TO_CHAR(n.expiration,							\
				'YYYY-MM-DD HH24:MI:SS'),				\
		n.textid,										\
		nt.textlen,										\
		nt.text											\
 from	ebay_notes n,									\
		ebay_notes_text nt,								\
 where	user_from = :ufrom								\
 and	nt.textid = n.textid(+)							\
 order by when desc";

const char *SQL_LoadNotesTo =
"select	n.id,											\
		n.about_type,									\
		n.from_type,									\
		n.visibility,									\
		n.user_from,									\
		n.user_to,										\
		n.user_cc,										\
		n.user_about,									\
		n.item_about,									\
		TO_CHAR(n.when,									\
				'YYYY-MM-DD HH24:MI:SS'),				\
		TO_CHAR(n.expiration,							\
				'YYYY-MM-DD HH24:MI:SS'),				\
		n.subject,										\
		nt.text_len,									\
		nt.text											\
 from	ebay_notes n,									\
		ebay_notes_text nt								\
 where	n.user_to = :uto								\
 and	n.id = nt.id(+)									\
 order by when desc";

const char *SQL_LoadNotesToAboutUser =
"select	n.id,											\
		n.about_type,									\
		n.from_type,									\
		n.visibility,									\
		n.user_from,									\
		n.user_to,										\
		n.user_cc,										\
		n.user_about,									\
		n.item_about,									\
		TO_CHAR(n.when,									\
				'YYYY-MM-DD HH24:MI:SS'),				\
		TO_CHAR(n.expiration,							\
				'YYYY-MM-DD HH24:MI:SS'),				\
		n.subject,										\
		nt.text_len,									\
		nt.text											\
 from	ebay_notes n,									\
		ebay_notes_text nt								\
 where	n.user_to = :uto								\
 and	n.user_about = :auser							\
 and	n.id = nt.id(+)									\
 order by when desc";

const char *SQL_LoadNotesToAboutItem =
"select	n.id,											\
		n.about_type,									\
		n.from_type,									\
		n.visibility,									\
		n.user_from,									\
		n.user_to,										\
		n.user_cc,										\
		n.user_about,									\
		n.item_about,									\
		TO_CHAR(n.when,									\
				'YYYY-MM-DD HH24:MI:SS'),				\
		TO_CHAR(n.expiration,							\
				'YYYY-MM-DD HH24:MI:SS'),				\
		n.subject,										\
		nt.text_len,									\
		nt.text											\
 from	ebay_notes n,									\
		ebay_notes_text nt								\
 where	n.user_to = :uto								\
 and	n.item_about = :aitem							\
 and	n.id = nt.id(+)									\
 order by when desc";

#define ORA_NOTES_ARRAYSIZE		50
#define ORA_NOTES_SUBJECTSIZE	255
#define ORA_NOTES_TEXTSIZE		2048

void clsDatabaseOracle::LoadNotes(unsigned int addressFilter,
								  unsigned int aboutFilter,
								  unsigned int categoryFilter,
								  clsNoteAddressList *pFrom,
								  clsNoteAddressList *pTo,
								  clsNoteAddressList *pCC,
								  clsNoteAddressList *pAbout,
								  clsNoteList *plNotes)
{	
	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					i;
	int					rc;

	// Arrays to read things into
	int					ids[ORA_NOTES_ARRAYSIZE];
	int					categories[ORA_NOTES_ARRAYSIZE];
	int					fromTypes[ORA_NOTES_ARRAYSIZE];
	int					visibilities[ORA_NOTES_ARRAYSIZE];
	int					froms[ORA_NOTES_ARRAYSIZE];
	int					tos[ORA_NOTES_ARRAYSIZE];
	int					ccs[ORA_NOTES_ARRAYSIZE];
	sb2					ccs_ind[ORA_NOTES_ARRAYSIZE];
	int					aboutUsers[ORA_NOTES_ARRAYSIZE];
	sb2					aboutUsers_ind[ORA_NOTES_ARRAYSIZE];
	int					aboutItems[ORA_NOTES_ARRAYSIZE];
	sb2					aboutItems_ind[ORA_NOTES_ARRAYSIZE];
	char				whens[ORA_NOTES_ARRAYSIZE][32];
	char				expirations[ORA_NOTES_ARRAYSIZE][32];
	sb2					expirations_ind[ORA_NOTES_ARRAYSIZE];
	char				subjects[ORA_NOTES_ARRAYSIZE][ORA_NOTES_SUBJECTSIZE];
	sb2					subjects_ind[ORA_NOTES_ARRAYSIZE];
	int					textLens[ORA_NOTES_ARRAYSIZE];
	sb2					textLens_ind[ORA_NOTES_ARRAYSIZE];
	unsigned char		texts[ORA_NOTES_ARRAYSIZE][ORA_NOTES_TEXTSIZE];
	sb2					texts_ind[ORA_NOTES_ARRAYSIZE];

	clsNoteAddressList	*pNoteToAddressList		= NULL;
	clsNoteAddressList	*pNoteFromAddressList	= NULL;
	clsNoteAddressList	*pNoteCCAddressList		= NULL;
	clsNoteAddressList	*pNoteAboutAddressList	= NULL;

	clsNoteAddress		*pNoteAddress			= NULL;

	// These are the bind varibles.
	//
	// ** NOTE **
	// We don't support address lists of anything other than one
	// user right now, so the "from", "to", etc are simple ids
	// ** NOTE **
	//
	int			to;
	int			from;
	// int		cc;				// Avoid unreferenced variable.
	int			aboutUser;
	int			aboutItem;

	// Itcherator for clsNoteAddress for fetching the contents 
	// of the first address entry
	clsNoteAddressList::iterator	iAddress;

	// Time conversion
	time_t		theWhen;
	time_t		theExpiration;

	// Enum conversion
	eClsNoteFromTypes	theFromType;

	// Subject storage
	int			theSubjectLen;
	char		*pTheSubject;

	// Text storage
	int			theTextLen;
	char		*pTheText;

	// The clsNote
	clsNote		*pNote;

	// Let's figure out which cursor to open
	if (addressFilter & clsNotes::eClsNotesFilterNotesTo)
	{
		if (aboutFilter & eClsNoteAboutUser)
		{
			OpenAndParse(&mpCDAOneShot, SQL_LoadNotesToAboutUser);
		}
		else if (aboutFilter & eClsNoteAboutItem)
		{
			OpenAndParse(&mpCDAOneShot, SQL_LoadNotesToAboutItem);
		}
		else 
		{
			OpenAndParse(&mpCDAOneShot, SQL_LoadNotesTo);
		}
	}
	else if (addressFilter & clsNotes::eClsNotesFilterNotesFrom)
	{
		OpenAndParse(&mpCDAOneShot, SQL_LoadNotesFrom);
	}
	else
	{
		return;
	}

	//
	// The Defines are always the same
	//
	Define(1, ids);
	Define(2, categories);
	Define(3, (int *)fromTypes);
	Define(4, visibilities);
	Define(5, froms);
	Define(6, tos);
	Define(7, ccs, ccs_ind);
	Define(8, aboutUsers, aboutUsers_ind);
	Define(9, aboutItems, aboutItems_ind);
	Define(10, whens[0], sizeof(whens[0]));
	Define(11, expirations[0], sizeof(expirations[0]), expirations_ind);
	Define(12, subjects[0], sizeof(subjects[0]), subjects_ind);
	Define(13, textLens, textLens_ind);
	DefineLongRaw(14, texts[0], sizeof(texts[0]), texts_ind); 

	// The binds, however ;-)
	if (addressFilter & clsNotes::eClsNotesFilterNotesTo)
	{
		iAddress	= pTo->begin();
		to	= (*iAddress).GetAddressUser();
		Bind(":uto", &to);

		if (aboutFilter & eClsNoteAboutUser)
		{
			iAddress	= pAbout->begin();
			aboutUser	= (*iAddress).GetAddressUser();

			Bind(":auser", &aboutUser);
		}
		else if (aboutFilter & eClsNoteAboutItem)
		{
			iAddress	= pAbout->begin();
			aboutItem	= (*iAddress).GetAddressItem();

			Bind(":aitem", &aboutItem);
		}
	}
	else if (addressFilter & clsNotes::eClsNotesFilterNotesFrom)
	{
		iAddress	= pFrom->begin();
		to	= (*iAddress).GetAddressUser();

		Bind(":ufrom", &from);
	}

	// Execute...
	Execute();

	// Loop around, fetching until we drop
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,
				  ORA_NOTES_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_NOTES_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			if (categoryFilter != 0				&&
				categories[i] != categoryFilter)
			{
				continue;
			}

			// Time Conversion
			ORACLE_DATEToTime(whens[i], &theWhen);
			if (expirations_ind[i] != -1)
				ORACLE_DATEToTime(expirations[i], &theExpiration);
			else
				theExpiration	= 0;

			// FromType conversion
			theFromType	= (eClsNoteFromTypes)fromTypes[i];

			// The subject
			if (subjects_ind[i] != -1)
			{
				theSubjectLen	= strlen(subjects[i]);
				pTheSubject		= new char[theSubjectLen + 1];
				memcpy(pTheSubject, subjects[i], theSubjectLen);
				*(pTheSubject + theSubjectLen) = '\0';
			}
			else
				pTheSubject		= NULL;

			// We'll need to copy the text
			if (textLens_ind[i] != -1 &&
				texts_ind[i] != -1)
			{
				theTextLen	= textLens[i];
				pTheText	= new char[theTextLen + 1];
				memcpy(pTheText, texts[i], theTextLen);
				*(pTheText + theTextLen) = '\0';
			}
			else
			{
				theTextLen	= 0;
				pTheText	= NULL;
			}

			// Use the right CTOR depending on the "about"
			pNoteToAddressList		= new clsNoteAddressList();
			pNoteAddress			= new clsNoteAddress();
			pNoteAddress->SetAddressUser(tos[i]);
			pNoteToAddressList->push_back(*pNoteAddress);
			delete pNoteAddress;

			pNoteFromAddressList	= new clsNoteAddressList();
			pNoteAddress			= new clsNoteAddress();
			pNoteAddress->SetAddressUser(froms[i]);
			pNoteFromAddressList->push_back(*pNoteAddress);
			delete pNoteAddress;

			if (ccs_ind[i] != -1 && ccs[i] != 0)
			{
				pNoteCCAddressList	= new clsNoteAddressList();
				pNoteAddress		= new clsNoteAddress();
				pNoteAddress->SetAddressUser(ccs[i]);
				pNoteCCAddressList->push_back(*pNoteAddress);
				delete pNoteAddress;
			}

			
			if (aboutUsers_ind[i] != -1 	||
				aboutItems_ind[i] != -1			)
			{
				pNoteAboutAddressList	= new clsNoteAddressList();

				if (aboutUsers_ind[i] != -1)
				{
					pNoteAddress		= new clsNoteAddress();
					pNoteAddress->SetAddressUser(aboutUsers[i]);
					pNoteAboutAddressList->push_back(*pNoteAddress);
					delete pNoteAddress;
				}

				if (aboutItems_ind[i] != -1)
				{
					pNoteAddress		= new clsNoteAddress();
					pNoteAddress->SetAddressItem(aboutItems[i]);
					pNoteAboutAddressList->push_back(*pNoteAddress);
					delete pNoteAddress;
				}
			}

			pNote = new clsNote(pNoteToAddressList,
								pNoteFromAddressList,
								pNoteCCAddressList,
								pNoteAboutAddressList,
								theFromType,
								categories[i],
								visibilities[i],
								theWhen,
								theExpiration,
								pTheSubject,
								pTheText);


			plNotes->push_back(pNote);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GetNextNoteTextSequence
//
const char *SQL_GetNextNoteSequence =
"select	ebay_notes_sequence.nextval	"
"from	dual";

int clsDatabaseOracle::GetNextNoteSequence()
{
	int		theSequence;

	OpenAndParse(&mpCDAOneShot, SQL_GetNextNoteSequence);
	
	Define(1, &theSequence);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return theSequence;
}

//
// AddNote
//
const char *SQL_AddNote =
"insert	into ebay_notes						"
"(											"
"	id,										"
"	about_type,								"
"	from_type,								"
"	visibility,								"
"	user_from,								"
"	user_to,								"
"	user_cc,								"
"	user_about,								"
"	item_about,								"
"	when,									"
"	expiration,								"
"	subject									"
")											"
"values										"
"(											"
"	:id,									"
"	:about,									"
"	:fromtype,								"
"	:vis,									"
"	:ufrom,									"
"	:uto,									"
"	:ucc,									"
"	:aUser,									"
"	:aItem,									"
"	TO_DATE(:when,'YYYY-MM-DD HH24:MI:SS'),	"
"	TO_DATE(:exp, 'YYYY-MM-DD HH24:MI:SS'),	"
"	:subject								"
")";

const char *SQL_AddNoteWithoutExpiration =
"insert	into ebay_notes						"
"(											"
"	id,										"
"	about_type,								"
"	from_type,								"
"	visibility,								"
"	user_from,								"
"	user_to,								"
"	user_cc,								"
"	user_about,								"
"	item_about,								"
"	when,									"
"	expiration,								"
"	subject									"
")											"
"values										"
"(											"
"	:id,									"
"	:about,									"
"	:fromtype,								"
"	:vis,									"
"	:ufrom,									"
"	:uto,									"
"	:ucc,									"
"	:aUser,									"
"	:aItem,									"
"	TO_DATE(:when,'YYYY-MM-DD HH24:MI:SS'),	"
"	NULL,									"
"	:subject								"
")";

const char *SQL_AddNoteText	=
"insert into ebay_notes_text				"
"(											"
"	id,										"
"	text_len,								"
"	text									"
")											"
"values										"
"(											"
"	:id,									"
"	:tlen,									"
"	:text									"
")";

void clsDatabaseOracle::AddNote(clsNote *pNote)
{
	// Itcherator for clsNoteAddress for fetching the contents 
	// of the first address entry for from, to, etc. We only
	// support single addresses now.
	clsNoteAddressList::iterator	iAddress;

	// Places to store things extracted from the object
	int					id;
	int					type;
	int					aboutType;
	int					fromType;
	int					visibility;
	int					from;
	int					to;
	int					cc;
	sb2					cc_ind;
	int					aUser;
	sb2					aUser_ind;
	int					aItem;
	sb2					aItem_ind;
	time_t				when;
	char				cWhen[32];
	time_t				expiration;
	char				cExpiration[32];
	sb2					cExpiration_ind;
	char				*pSubject;
	int					textLen;
	char				*pText;

	// Let's get all kinds of things
	fromType	= pNote->GetFromType();
	visibility	= pNote->GetVisibility();

	from	= pNote->GetUserIdFrom();
	to		= pNote->GetUserIdTo();
	cc		= pNote->GetUserIdCC();
	type	= pNote->GetType();

	// Set CC to NULL if it's not present to avoid
	// constraint violations.
	if (cc == 0)
		cc_ind	= -1;
	else
		cc_ind	= 0;

	//
	// Now, we have to figure out what the note is "About". This
	// is done by examining the "about" vector of note addresses,
	// and seeing whether they're users, or items, or both.
	//

	// We start off with the note being about NEITHER, and both
	// fields are null

	aUser		= 0;
	aUser_ind	= -1;
	aItem		= 0;
	aItem_ind	= -1;
	aboutType	= 0;
	for (iAddress = pNote->GetAbout()->begin();
		 iAddress != pNote->GetAbout()->end();
		 iAddress++)
	{
		if ((*iAddress).GetType() == eClsNoteAddressUser)
		{
			aboutType	= aboutType | eClsNoteAboutUser;
			aUser		= (*iAddress).GetAddressUser();
			aUser_ind	= 0;
			continue;
		}

		if ((*iAddress).GetType() == eClsNoteAboutItem)
		{
			aboutType	= aboutType | eClsNoteAboutItem;
			aItem		= (*iAddress).GetAddressItem();
			aItem_ind	= 0;
			continue;
		}
	}


	when		= pNote->GetWhen();
	TimeToORACLE_DATE(when, cWhen);

	expiration	= pNote->GetExpiration();
	if (expiration != 0)
	{
		TimeToORACLE_DATE(expiration, cExpiration);
		cExpiration_ind	= 0;
	}
	else
	{
		cExpiration_ind	= -1;
	}

	pSubject	= pNote->GetSubject();

	pText		= pNote->GetText();
	textLen		= strlen(pText);

	//
	// We'll need a textid for the text
	//
	id	= GetNextNoteSequence();

	// 
	// First, we put in the note itself
	//
	if (expiration != 0)
		OpenAndParse(&mpCDAOneShot, SQL_AddNote);
	else
		OpenAndParse(&mpCDAOneShot, SQL_AddNoteWithoutExpiration);

	Bind(":id", &id);
	Bind(":about", &type);
	Bind(":fromtype", &fromType);
	Bind(":vis", &visibility);
	Bind(":ufrom", &from);
	Bind(":uto", &to);
	Bind(":ucc", &cc, &cc_ind);
	Bind(":aUser", &aUser, &aUser_ind);
	Bind(":aItem", &aItem, &aItem_ind);
	Bind(":when", cWhen);
	
	if (expiration != 0)
		Bind(":exp", cExpiration, cExpiration_ind);
	
	Bind(":subject", pSubject);

	Execute();

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	//
	// Now, the text
	//
	OpenAndParse(&mpCDAOneShot, SQL_AddNoteText);

	Bind(":id", &id);
	Bind(":tlen", &textLen);
	BindLongRaw(":text", (unsigned char *)pText, textLen);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

}
