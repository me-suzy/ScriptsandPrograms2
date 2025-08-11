   var divElements = document.getElementsByTagName("tr");

   function hideAdvancedOptions() {
      var divElements = document.getElementsByTagName("div");
      var i = 0;
      for(i=0;i<divElements.length;i++) {
         if(divElements.item(i).className == "advancedOptions") {
            divElements.item(i).style.display="none";
         }
         if(divElements.item(i).className == "hideAdvancedOptionsButton") {
            divElements.item(i).style.display="none";
         }
         if(divElements.item(i).className == "showAdvancedOptionsButton") {
            divElements.item(i).style.display="inline";
         }
      }
      for(i=0;i<divElements.length;i++) {
         if(divElements.item(i).className == "advancedOptions") {
            divElements.item(i).style.display="none";
         }
      }
      hideDefaultButtons();
   }
   function showAdvancedOptions() {
      var divElements = document.getElementsByTagName("div");
      var i = 0;
      for(i=0;i<divElements.length;i++) {
         if(divElements.item(i).className == "advancedOptions") {
            divElements.item(i).style.display="inline";
         }
         if(divElements.item(i).className == "showAdvancedOptionsButton") {
            divElements.item(i).style.display="none";
         }
         if(divElements.item(i).className == "hideAdvancedOptionsButton") {
            divElements.item(i).style.display="inline";
         }
      }
      for(i=0;i<divElements.length;i++) {
         if(divElements.item(i).className == "advancedOptions") {
            divElements.item(i).style.display="block";
         }
      }
      var cookieValue = getCookie('advancedOptions');
      if(cookieValue == 'true') {
         showHideDefaultButton();
      } else {
         showShowDefaultButton();
      }
      try {
          showHiddenEditors();
      } catch (e) {}
   }

   function showHideDefaultButton() {
      var divElements = document.getElementsByTagName("div");
      var i = 0;
      for(i=0;i<divElements.length;i++) {
         if(divElements.item(i).className == "showShowDefaultButton") {
            divElements.item(i).style.display="none";
         }
         if(divElements.item(i).className == "showHideDefaultButton") {
            divElements.item(i).style.display="inline";
         }
      }
   }

   function showShowDefaultButton() {
      var divElements = document.getElementsByTagName("div");
      for(i=0;i<divElements.length;i++) {
         if(divElements.item(i).className == "showShowDefaultButton") {
            divElements.item(i).style.display="inline";
         }
         if(divElements.item(i).className == "showHideDefaultButton") {
            divElements.item(i).style.display="none";
         }
      }
   }

   function hideDefaultButtons() {
      var divElements = document.getElementsByTagName("div");
      for(i=0;i<divElements.length;i++) {
         if(divElements.item(i).className == "showShowDefaultButton") {
            divElements.item(i).style.display="none";
         }
         if(divElements.item(i).className == "showHideDefaultButton") {
            divElements.item(i).style.display="none";
         }
      }
   }

   /*
     Cookie handling code from http://webreference.com/js/column8/functions.html
     name - name of the desired cookie
     return string containing value of specified cookie or null
     if cookie does not exist
   */

   function getCookie(name) {
     var dc = document.cookie;
     var prefix = name + "=";
     var begin = dc.indexOf("; " + prefix);
     if (begin == -1) {
       begin = dc.indexOf(prefix);
       if (begin != 0) return null;
     } else
       begin += 2;
     var end = document.cookie.indexOf(";", begin);
     if (end == -1)
       end = dc.length;
     return unescape(dc.substring(begin + prefix.length, end));
   }

   /*
     Cookie handling code from http://webreference.com/js/column8/functions.html
      name - name of the cookie
      value - value of the cookie
      [expires] - expiration date of the cookie
        (defaults to end of current session)
      [path] - path for which the cookie is valid
        (defaults to path of calling document)
      [domain] - domain for which the cookie is valid
        (defaults to domain of calling document)
      [secure] - Boolean value indicating if the cookie transmission requires
        a secure transmission
      * an argument defaults when it is assigned null as a placeholder
      * a null placeholder is not required for trailing omitted arguments
   */

   function setCookie(name, value, expires, path, domain, secure) {
     var curCookie = name + "=" + escape(value) +
         ((expires) ? "; expires=" + expires.toGMTString() : "") +
         ((path) ? "; path=" + path : "") +
         ((domain) ? "; domain=" + domain : "") +
         ((secure) ? "; secure" : "");
     document.cookie = curCookie;
   }


   /*
     Cookie handling code from http://webreference.com/js/column8/functions.html
      name - name of the cookie
      [path] - path of the cookie (must be same as path used to create cookie)
      [domain] - domain of the cookie (must be same as domain used to
        create cookie)
      path and domain default if assigned null or omitted if no explicit
        argument proceeds
   */

   function deleteCookie(name, path, domain) {
     if (getCookie(name)) {
       document.cookie = name + "=" +
       ((path) ? "; path=" + path : "") +
       ((domain) ? "; domain=" + domain : "") +
       "; expires=Thu, 01-Jan-70 00:00:01 GMT";
     }
   }

   function loadAdvancedOptions() {
      var cookieValue = getCookie('advancedOptions');
      var loadingDiv = false;
      if(cookieValue == 'true') {
         showAdvancedOptions();
      } else {
         hideAdvancedOptions();
      }
      loadingDiv = document.getElementById('stillLoading');
      if (loadingDiv)
          loadingDiv.style.display='none';
   }

   function setAdvancedOptionsCookie(rootDomain) {
      //  Set the cookie with an expiry time of five years.  That should
      // be sufficient.
      var expiryDate = new Date();
      expiryDate.setTime(expiryDate.getTime() + (1000*60*60*24*365* 5));
      setCookie('advancedOptions','true',expiryDate, '/', rootDomain, '');
      showHideDefaultButton();
   }

   function removeAdvancedOptionsCookie(rootDomain) {
      deleteCookie('advancedOptions','/',rootDomain);
      showShowDefaultButton();
   }

