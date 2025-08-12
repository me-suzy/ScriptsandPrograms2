/*
Auto center window script- Eric King (http://redrival.com/eak/index.shtml)
Permission granted to Dynamic Drive to feature script in archive
For full source, usage terms, and 100's more DHTML scripts, visit http://dynamicdrive.com
*/
var pa_wnd_params = '';
function OpenWindow_build_settings(w, h) {
	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	settings ='height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition;
	return settings;
}

function OpenWindow(mypage, w, h) {
	if (win) { win.close();	}
	settings = OpenWindow_build_settings(w, h);
	mypage = 'photoalbum/wnd_' + mypage + '.php?lang=' + pa_lang_code + pa_wnd_params;
	win = window.open(mypage, 'SBphotoAlbum_window', settings)
	win.focus();
}

function OpenWindowExt(mypage, w, h) {
	if (win) { win.close();	}
	settings = OpenWindow_build_settings(w, h);
	win = window.open(mypage, 'SBphotoAlbum_Extwindow', settings)
	win.focus();
}
