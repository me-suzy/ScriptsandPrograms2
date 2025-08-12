// Copyright (c) the partners of MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

function popUp( url, width, height, left, top ) {
      var popupName = popUpIdent( url, width, height, left, top );
}

function popUpIdent( url, width, height, left, top ) {
      var rnum = Math.random()*200; rnum = Math.round(rnum);
      this['win'+rnum] =  window.open(url,
        "pup"+rnum,
        "width="+width+",height="+height+",left="+left+",top="+top+",resizable=yes,menubar=no,directories=no,toolbar=no,scrollbars=yes"
      );
      return 'pup'+rnum;
}

function popUpWindow(url, width, height, left, top ) {

      var newWindow;
      newWindow = window.open(url,
        "poppedUpWindow",
        "width="+width+",height="+height+",left="+left+",top="+top+",resizable=yes,menubar=yes,directories=no,toolbar=no,scrollbars=yes"
      );
}

function popUpWindowAutoSizePos( anUrl) {
      var newWindow;
      newWindow = window.open(anUrl,
        "poppedUpWindow",
        "resizable=yes,menubar=yes,directories=no,toolbar=no,scrollbars=yes"
      );
}

function popUpNoScroll( url, width, height, left, top ) {

      var newWindow;
      newWindow = window.open(url,
        "newGebruikersnaamWin",
        "width="+width+",height="+height+",left="+left+",top="+top+",resizable=yes,menubar=no,directories=no,toolbar=no,scrollbars=no"
      );
}
