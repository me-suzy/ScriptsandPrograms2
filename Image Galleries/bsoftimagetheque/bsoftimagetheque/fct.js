NavName = navigator.appName.substring(0,3);
NavVersion = navigator.appVersion.substring(0,1);
if (NavName != "Mic" || NavVersion>=4)
{
	entree = new Date;
	entree = entree.getTime();
}
function tps_charge()
{
	if (NavName != "Mic" || NavVersion>=4)
	{
	fin = new Date;
	fin = fin.getTime();
	secondes = (fin-entree)/1000;
	var val = document.getElementById('tps_charg').value;
	val = val + 'Loading : ' + secondes + 's';
	document.getElementById('tps_charg').value=val;
	}
}


function makevisible(cur,which)
{
	if(document.getElementById)
	{
	    if (which==0)
	    {
            if(document.all)
				cur.filters.alpha.opacity=100
	        else
	            cur.style.setProperty("-moz-opacity", 1, "");
	    }
	    else
	    {
	    	if(document.all)
	        	cur.filters.alpha.opacity=50
	        else
	        	cur.style.setProperty("-moz-opacity", .5, "");
	    }
	}
}
