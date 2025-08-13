/*	$Id: clsUserValidation.cpp,v 1.5 1998/12/06 05:32:27 josh Exp $	*/
#include "eBayKernel.h"
#include "clseBayCookie.h"
#include "clsEnvironment.h"

// Constructor.
// We set soft validation to unknown
// because there are external conditions which
// soft validate us.
// We never have external conditions hard validate
// us, though, so we set it to unvalidated.
clsUserValidation::clsUserValidation() :
	mSoftValidation(unknownvalidation),
	mHardValidation(unvalidated),
	mpUser(NULL),
	mpUserId(NULL)
{
}

// The destructor. We own the mpUser,
// and everyone should know that we own it,
// so here we get rid of it.
clsUserValidation::~clsUserValidation()
{
	delete mpUser;
	delete [] mpUserId;
}

// Deletes the mpUser object if the userid is different than
// the validated userid.
// This function has the effect of setting soft validation
// to unknown if the userid is different.
// Whatever the userid, if hard validation is validated, it
// will set soft validation the same, on the theory that
// if you are hard validated, you're soft validated, but
// not vice versa. (Or there would be no difference between
// the two).
void clsUserValidation::SetHardValidation(bool on, const char *pUserId)
{
	if (mpUserId && !strcmp(pUserId, mpUserId))
	{
		mHardValidation = on ? validated : unvalidated;
		if (on)
			mSoftValidation = validated;
		return;
	}

	if (mpUserId)
	{
		// No leaks.
		delete mpUser;
		mpUser = NULL;
		delete [] mpUserId;
		mpUserId = NULL;

		// Clear leftover validations.
		mSoftValidation = unknownvalidation;
	}

	if (pUserId)
	{
		mpUserId = new char [strlen(pUserId) + 1];
		strcpy(mpUserId, pUserId);
	}
	else
		mpUserId = NULL;

	mHardValidation = on ? validated : unvalidated;

	// If we're hard validated, we're soft validated.
	if (on)
		mSoftValidation = validated;
}

// Deletes the mpUser object if the userid is different than
// the validated userid.
void clsUserValidation::SetSoftValidation(bool on, const char *pUserId)
{
	if (mpUserId && !strcmp(pUserId, mpUserId))
	{
		mSoftValidation = on ? validated : unvalidated;
		if (!on)
			mHardValidation = unvalidated;
		return;
	}

	if (mpUserId)
	{
		// No leaks.
		delete mpUser;
		mpUser = NULL;

		delete [] mpUserId;
		mpUserId = NULL;

		// We're no longer validated if we switched ids.
		mHardValidation = unvalidated;
	}

	if (pUserId)
	{
		mpUserId = new char [strlen(pUserId) + 1];
		strcpy(mpUserId, pUserId);
	}
	else
		mpUserId = NULL;

	mSoftValidation = on ? validated : unvalidated;
}

// Returns the validated user if there is one --
// if we haven't done soft validation then we do
// it here.
// Once we've done that, if we have a user id, we'll
// fetch a user object (and take ownership of it)
// if we don't already have one.
clsUser *clsUserValidation::GetValidatedUser() const
{
	if (mSoftValidation == unknownvalidation)
		AttemptValidation();

	if (!mpUser && mpUserId)
		mpUser = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers()->GetUser(mpUserId);

	return mpUser;
}

// Attempts soft validation if it hasn't been attempted.
const char *clsUserValidation::GetValidatedUserId() const
{
	if (mSoftValidation == unknownvalidation)
		AttemptValidation();

	return mpUserId;
}

// Attempts soft validation if it hasn't been attempted.
bool clsUserValidation::IsSoftValidated() const
{
	if (mSoftValidation == unknownvalidation)
		AttemptValidation();

	return (mSoftValidation == validated) ? true : false;
}

// Makes no attempt at validation -- we can't
// be hard validated by external events -- we have
// to be notified directly that we are hard validated.
bool clsUserValidation::IsHardValidated() const
{
	return (mHardValidation == validated) ? true : false;
}

// Looks at the environment and cookie to try soft validation.
void clsUserValidation::AttemptValidation() const
{

	const char *pUserId;
	mpUserId = NULL;
	mSoftValidation = unvalidated;

	clsEnvironment *pEnvironment = gApp->GetEnvironment();
	if (pEnvironment)
	{
		clseBayCookie theCookie;
		theCookie.SetCookiesFromClient(pEnvironment->GetCookie());

		pUserId = theCookie.GetCookie(COOKIE_USERID);

		if (pUserId)
		{
			mpUserId = new char [strlen(pUserId) + 1];
			strcpy(mpUserId, pUserId);
			mSoftValidation = validated;
		}
	}
}

void clsUserValidation::ClearValidation()
{
	delete [] mpUserId;
	delete mpUser;

	mpUserId = NULL;
	mpUser = NULL;

	mSoftValidation = unknownvalidation;
	mHardValidation = unvalidated;
}