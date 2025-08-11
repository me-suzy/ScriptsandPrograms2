if (window != top) 
    top.location.href = location.href

function w(s)
{
    document.write(s)
}

function addToFavorites(urlAddress, pageName)
{
	if (window.external)
        window.external.AddFavorite(urlAddress,pageName)
	else 
        alert('Sorry! Your browser does not support this function.')
}

