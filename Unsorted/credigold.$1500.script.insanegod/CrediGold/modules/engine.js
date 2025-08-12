function button_over(eButton)

   {

   eButton.style.backgroundColor = "#B5BDD6";

   eButton.style.borderColor = "darkblue darkblue darkblue darkblue";

   }

function button_out(eButton)

   {

   eButton.style.backgroundColor = "threedface";

   eButton.style.borderColor = "threedface";

   }

function button_down(eButton)

   {

   eButton.style.backgroundColor = "#8494B5";

   eButton.style.borderColor = "darkblue darkblue darkblue darkblue";

   }

function button_up(eButton)

   {

   eButton.style.backgroundColor = "#B5BDD6";

   eButton.style.borderColor = "darkblue darkblue darkblue darkblue";

   eButton = null;

   }

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~



var isHTMLMode=false



function document.onreadystatechange()

   {

   idContent.document.designMode="On"

   }

function cmdExec(cmd,opt)

   {

   if (isHTMLMode){alert("Please uncheck 'Edit HTML'");return;}

   idContent.document.execCommand(cmd,"",opt);idContent.focus();

   }

function newBookmark()

   {

      if (y = prompt("Write the name of the bookmark you want:",""))

         {

            cmdExec('CreateBookmark','#'+y);

         }

   }

function setMode(bMode)

   {

   var sTmp;

   isHTMLMode = bMode;

   if (isHTMLMode){sTmp=idContent.document.body.innerHTML;idContent.document.body.innerText=sTmp;}

   else {sTmp=idContent.document.body.innerText;idContent.document.body.innerHTML=sTmp;}

   idContent.focus();

   }

function createLink()

   {

   if (isHTMLMode){alert("Please uncheck 'Edit HTML'");return;}

   cmdExec("CreateLink");

   }

function insertImage()

   {

   if (isHTMLMode){alert("Please uncheck 'Edit HTML'");return;}

   var sImgSrc=prompt("Insert an image from an URL: ", "http://");

   if(sImgSrc!=null) cmdExec("InsertImage",sImgSrc);

   }

function Save()

   {

   if (isHTMLMode){alert("Please uncheck 'Edit HTML'");return;}

   var sImgTag = idContent.document.body.all.tags("IMG");

   var oImg;

   for (var i = sImgTag.length - 1; i >= 0; i--)

      {

      oImg = sImgTag[i];

      //alert("Add your code to Upload local image file here. Image Inserted : " + oImg.src );

      }

   tras();

   window.close();

   }

function foreColor()

   {

      var arr = showModalDialog("../modules/setcolor.htm","","font-family:Verdana; font-size:12; dialogWidth:30em; dialogHeight:34em" );

      if (arr != null) cmdExec("ForeColor",arr);

   }



/*

Find In Page Script-

By Mike Hall (MHall75819@aol.com)

Permission granted to Dynamicdrive.com to feature script in archive

For full source code, visit http://dynamicdrive.com

*/



var NS4 = (document.layers);    // Which browser?

var IE4 = (document.all);



var win = window;    // window to search.

var n   = 0;



function findInPage(str, rep) {



  var txt, i, found;



if (!rep || rep == ""){

  if (str == "")

    return false;



  // Find next occurance of the given string on the page, wrap around to the

  // start of the page if necessary.



  if (NS4) {



    // Look for match starting at the current point. If not found, rewind

    // back to the first match.



    if (!win.find(str))

      while(win.find(str, false, true))

        n++;

    else

      n++;



    // If not found in either direction, give message.



    if (n == 0)

      alert("Not found.");

  }



  if (IE4) {

    txt = idContent.document.body.createTextRange();



    // Find the nth match from the top of the page.



    for (i = 0; i <= n && (found = txt.findText(str)) != false; i++) {

      txt.moveStart("character", 1);

      txt.moveEnd("textedit");

    }



    // If found, mark it and scroll it into view.



    if (found) {

      txt.moveStart("character", -1);

      txt.findText(str);

      txt.select();

      txt.scrollIntoView();

      n++;

    }



    // Otherwise, start over at the top of the page and find first match.



    else {

      if (n > 0) {

        n = 0;

        findInPage(str);

      }



      // Not found anywhere, give message.



      else

        alert("Not found.");

    }

  }



  return false;

   }

else

   {

      new_values = idContent.document.body.innerHTML.split(str);

      if (new_values.length > 1)

         {

            tester = "";

            for(i=0;i<new_values.length;i++)

               {

                  tester = tester+new_values[i]+rep;

               }

            tester = tester.substr(0, tester.length-rep.length);

            idContent.document.body.innerHTML = tester;

            alert(new_values.length+' matche(s) replaced successfully!');

            return false;

         }

      else

         {

            alert('No matches!');

            return false;

         }

      return false;

   }

}

function adderIMG(what)

   {

      cmdExec("InsertImage","../images/"+what);

   }

function adderLINK(what)

   {

      cmdExec("createLink",what);

   }

function phpCode()

   {

      var ddd = showModalDialog("php.htm","","font-family:Verdana; font-size:12; dialogWidth:32em; dialogHeight:24em" );

      if (ddd != null) idContent.document.body.innerHTML += "[php_start]"+ddd+"[php_end]";

   }