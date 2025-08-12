//FORM VALIDATION 
//modified by 100jan, v1.0

//<![CDATA[

<!-- Hide the script from old browsers
  
//===========================================================
//CONFIGURATION
//===========================================================
var error_class=true; //true changes classes, false does not
var error_msg=true; //true pops-up alert box, false does not
var good_class="formfields"; //class if all good
var bad_class="formfields_error"; //class if error

//===========================================================
// A utility function that returns true if a string 
// contains only whitespace characters.
//===========================================================

function isanything(s)
{
  for(var i = 0; i < s.length; i++)
  {
     var c = s.charAt(i);
     if ((c != ' ') &&
         (c != '\n') &&
         (c != '\t'))
        return false;
  }
  return true;
}

//===========================================================
// This is the function that performs <form> validation.  
// It will be invoked from the onSubmit() event handler.
// The handler should return whatever value this function
// returns.
//===========================================================

function checkform(f)
{
  var msg;
  var empty_fields = "";
  var errors = "";
  var whyreq = false;

  // Loop through the elements of the form, looking for all
  // text and textarea elements that don't have an
  // "optional" property defined.  Then, check for fields
  // that are empty and make a list of them.
  // Also, if any of these elements have a "min" or a "max"
  // property defined, then verify that they are numbers 
  // and that they are in the right range.
  // Put together error messages for fields that are wrong.

for(var i = 0; i < f.length; i++)
  {
     var e = f.elements[i];

     if ((e.type == "text") ||
         (e.type == "textarea") ||
         (e.type == "password") ||		 
         (e.type == "select-one") ||		 
         (e.state))
     {


if (e.alt=="anything") //if anything
{

        if ((e.value == null) ||
            (e.value == "") ||
            isanything(e.value))
        {
           empty_fields += "- \"" + e.emsg + "\" can not be empty\n";
           //bad class
		   if (error_class==true) {e.className=bad_class;}
		   continue;
        }
		else 
		{
		//good class
		if (error_class==true) {e.className=good_class;}
		}
} //endif anything

//===========================================================
        // Now check for fields that are supposed 
        // to be numeric.
//===========================================================

        if (e.alt=="numeric")
        {
        //check if field is optional
		if (!(e.optional=="true" && e.value.length==0)) {
           
		if (isNaN(e.value)==false)  {
		
		   var v = parseFloat(e.value);
//           var v = e.value;
           if (isNaN(v) ||
              ((e.min != null) && (v < e.min)) ||
              ((e.max != null) && (v > e.max)))
           {
              errors += "- \"" +
                        e.emsg +
                        "\" must contain a number";
              if (e.min != null)
                 errors += " that is equal or greater than " +
                           e.min;

              if (e.max != null &&
                  e.min != null)
                 errors += " and equal or less than " +
                           e.max;

              else if (e.max != null)
                 errors += " that is equal or less than " +
                           e.max;

				errors += "\n";

		  	//bad class
			if (error_class==true) {e.className=bad_class;}
           }
		   else {
		  	//good class
			if (error_class==true) {e.className=good_class;}
		   		}
		}
        else {
		              errors += "- \"" +
                        e.emsg +
                        "\" must contain a number\n";
		//bad class
		if (error_class==true) {e.className=bad_class;}
			}
	}//optional
	else {
			  	//good class
			if (error_class==true) {e.className=good_class;}
	}
} //end

//===========================================================
// Check field length
//===========================================================

        if (e.alt=="length")
		   {
		   		   
        //check if field is optional
		if (!(e.optional=="true" && e.value.length==0)) {
		
           if (((e.min != null) && (e.value.length < e.min)) || ((e.max != null) && (e.value.length > e.max)))
           {
              errors += "- \"" +
                        e.emsg +
                        "\" must contain";
              if (e.min != null)
                 errors += " at least " +
                           e.min;						   

              if (e.max != null &&
                  e.min != null)
                 errors += " and not more than " +
                           e.max;

              else if (e.max != null)
                 errors += " not more than " +
                           e.max;

              errors += " characters \n";

		  	//bad class
			if (error_class==true) {e.className=bad_class;}
           }
		   else 
		   {
		  	//good class
			if (error_class==true) {e.className=good_class;}
		   }		   
		   
		   } //optional
		   else {
		   		  	//good class
			if (error_class==true) {e.className=good_class;}
		   }

		}


//===========================================================
        // Now check for fields that are supposed to be emails.
        // Not exactly as described in RFC 2822, as
        // the strict regexp for legal email addresses
        // runs about 2 printed pages.		
//===========================================================

        if (e.alt=="email")
        {

        //check if field is optional
		if (!(e.optional=="true" && e.value.length==0)) {
		
			var filter =/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

				if (! e.value.match(filter)) {
				    errors += "- \"" + e.emsg + "\" must contain a valid email address\n";
		  			//bad class
					if (error_class==true) {e.className=bad_class;}
				}
				else {
		   		  	//good class
					if (error_class==true) {e.className=good_class;}
				}

		   } //optional
		   else {
		   		  	//good class
					if (error_class==true) {e.className=good_class;}
		   }


        } //main


//===========================================================
//checking form
    }

  } 


//===========================================================
	// Now check for fields that are supposed 
	// not to have spaces
//===========================================================	

        if (e.nospaces)
        {
           var seenAt = false;
           var append = "";

           for(var j = 0; j < e.value.length; j++)
           {
              var c = e.value.charAt(j);

              if ((c == ' ') ||
                  (c == '\n') ||
                  (c == '\t'))
                 errors += "- The field " + e.description +
                           " must not contains white-space";
           }
        }

//===========================================================
  // Now, if there were any errors, then display the
  // messages, and return false to prevent the form from
  // being submitted.  Otherwise return true.
//===========================================================  

  if (!empty_fields && !errors) 
     return true;

  msg  = "The following error(s) occurred:\n\n";

  if (empty_fields)
  {
     msg += empty_fields ;

  }
  msg += errors;
  if (error_msg==true) {alert(msg);}
  return false;
}

// end hiding -->  

//]]>