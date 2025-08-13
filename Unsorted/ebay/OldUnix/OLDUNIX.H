// OLDUNIX.CPP - Implementation file for your Internet Server
//    OldUnix Filter


class COldUnixFilter : public CHttpFilter
{
public:
	COldUnixFilter();
	~COldUnixFilter();

	BOOL GetFilterVersion(PHTTP_FILTER_VERSION pVer);

	DWORD OnUrlMap(CHttpFilterContext* pCtxt,
		PHTTP_FILTER_URL_MAP pMapInfo);

	// TODO: Add your own overrides here
};
