var defaultEmptyOK = false
var whitespace = " \t\n\r";

function fMWarnEmpty(error)
{ // alert("2");
	
	
  if(!isWhitespace(error))
  {
    alert(error);
    return false;
  }
 // alert("1");
  return true;
}

function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}


function fMCheckEmail (obj, errorMsg, errorDesc )
{
	
  //makes sure the object is valid
  if(MM_findObj(obj)!=null)
  {
    var objVar=MM_findObj(obj);
   // alert(isEmail(objVar.value));

if( isEmail(objVar.value));else
   errorMsg=errorMsg+errorDesc+"\r";
   }else alert("no object");
   //alert(errorMsg);
   return errorMsg;
}



function fMCheckMinimumLenght( obj,length1, errorMsg, errorDesc )
{
	
  //makes sure the object is valid
  if(MM_findObj(obj)!=null)
  {
    var objVar=MM_findObj(obj);
    if(objVar.type=="text"||objVar.type=="textarea")
    {
    	//alert(objVar.value.length);
    	//alert(length1);
    	if(objVar.value.length<length1)
    	{
    		errorMsg=errorMsg+errorDesc+"\r";
    	}
    
     }
   }else alert("no object");
   //alert(errorMsg);
   return errorMsg;
}


//this will test whether there is object, get the errorMsg
//foucus the object
function fMCheckEmpty( obj, errorMsg, errorDesc )
{
  //makes sure the object is valid
  if(MM_findObj(obj)!=null)
  {
    var objVar=MM_findObj(obj);
    // alert("objVar.type"+objVar.type);
    //for text box, select mutltiple,text area

     // alert("objVar.value"+objVar.value);

    if(objVar.type=="text"
    ||objVar.type=="select-multiple"
    ||objVar.type=="textarea"
    ||objVar.type=="select-one")
    {

      //test if the value is empty or no value
    if (isWhitespace(objVar.value)
    ||objVar.value==FM_NO_VALUE)
   {
     	objVar.focus();
             //append the carriage return
   	errorMsg=errorMsg+errorDesc+"\r";
   }}// if(objVar.type=="text")
   else//this is for check box
   {
        if (!checkRadioButtonValue(objVar))
   {
     	//objVar.focus();
             //append the carriage return
   	errorMsg=errorMsg+errorDesc+"\r";
   }
   }





  }//if(MM_findObj(obj)!=null)
  return errorMsg;
}

function isEmail (s)
{   if (isEmpty(s))
       if (isEmail.arguments.length == 1) return defaultEmptyOK;
       else return (isEmail.arguments[1] == true);

    // is s whitespace?
    if (isWhitespace(s)) return false;

    // there must be >= 1 character before @, so we
    // start looking at character position 1
    // (i.e. second character)
    var i = 1;
    var sLength = s.length;

    // look for @
    while ((i < sLength) && (s.charAt(i) != "@"))
    { i++
    }

    if ((i >= sLength) || (s.charAt(i) != "@")) return false;
    else i += 2;

    // look for .
    while ((i < sLength) && (s.charAt(i) != "."))
    { i++
    }

    // there must be at least one character after the .
    if ((i >= sLength - 1) || (s.charAt(i) != ".")) return false;
    else return true;
}

// Returns true if string s is empty or
// whitespace characters only.

function isWhitespace (s)

{   var i;

    // Is s empty?
    if (isEmpty(s)) return true;

    // Search through string's characters one by one
    // until we find a non-whitespace character.
    // When we do, return false; if we don't, return true.

    for (i = 0; i < s.length; i++)
    {
        // Check that current character isn't whitespace.
        
        var c = s.charAt(i);

        if (whitespace.indexOf(c) == -1) return false;
    }

    // All characters are whitespace.
    return true;
}


// Check whether string s is empty.

function isEmpty(s)
{   return ((s == null) || (s.length == 0))
}