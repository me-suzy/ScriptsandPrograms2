// By Claudio Sa de Abreu
function TestLen(fld,minlen,maxlen)
{
	if( fld.value!="" && (fld.value.length<minlen || fld.value.length>maxlen))
	{
		alert("Wrong length. The field " + fld.name  + " may have between " + minlen + " and " + maxlen + " chars.")
		fld.value=""
		fld.focus()
		return false
	}
	return true
}
function TestCar(fld)
{
	if( fld.value == "car" )
		alert("Your choice is " + fld.value)
	return true
}
