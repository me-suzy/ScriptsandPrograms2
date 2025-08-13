// lightbox v 1.0
// add-on to imageFolio written by Dirk Koppers, lightbox@imagefolio.com
// Available from http://www.ImageFolio.com
// ---------------------------------------------------------------------------
// JAVASCRIPT NAME : bb.js 
// VERSION : 1.0
// LAST MODIFIED : 12/10/2000
// ===========================================================================
// COPYRIGHT NOTICE :
//
// Copyright (c) 1999-2000 Dirk Koppers, Inc. All Rights Reserved.
// Selling the code for this program without prior written consent is
// expressly forbidden.
//
// Obtain written permission before redistributing this software over the 
// Internet or in any other medium.  In all cases copyright and header must
// remain intact.
//
// Feel free to modify the code of this program to suit your likings. 
//
// Although this program has been thoroughly tested on my server, I
// do not warrant that it works on all servers and will not be held liable
// for anything, including but not limited to, misusage, error, or loss of data.
//
// Use at your own risk!
// ===========================================================================
// Do not modify below this line unless you know what you are doing.
// ===========================================================================



function noslidesalert(){alert('NO IMAGE FILES!\r\rYou have no images in your '+lightbox_text+'. A slide show of your '+lightbox_text+' can not be played unless you add some images.');}

function nosendlightboxalert(){alert('NO FILES SELECTED!\r\rYou have no files in your '+lightbox_text+'. E-mailing of your '+lightbox_text+' can not be done unless you add some files.');}

function nodownloadlightboxalert(){alert('NO FILES SELECTED!\r\rYou have no files in your '+lightbox_text+'. Downloading of your '+lightbox_text+' can not be done unless you add some files.');}


function clearLightbox() {
		if (confirm('Are you sure you wish to clear the '+lightbox_text+'')) {
			index = document.cookie.indexOf(cookiename);
			document.cookie=""+cookiename+"=.; path=/";
			location.href = location.href;
		}
	}


function showButton() {
		index = document.cookie.indexOf(cookiename);
		countbegin = (document.cookie.indexOf("=", index) + 1);
        	countend = document.cookie.indexOf(";", index);
        	if (countend == -1) {
            		countend = document.cookie.length;
                        
        	}
		fulllist = document.cookie.substring(countbegin, countend);

                total = 0;
		for (var i = 0; i <= fulllist.length; i++) {
			if (fulllist.substring(i,i+1) == '[') {
				itemstart = i+1;
				thisitem = 1;
			} else if (fulllist.substring(i,i+1) == ']') {
				itemend = i;
				thequantity = fulllist.substring(itemstart, itemend);
                    		total=total+1;}}

str=location.search.substring(1);
array=str.split("+");
selection=array[0];
if (selection=='lightbox=view'){
                selection=0;}               
if (total==0) {
     document.writeln('');}
else {
      if (selection>0){document.writeln('');}
       else {
     document.writeln('<td width="14%"><div align="center"><a href="javascript:clearLightbox()"><img src="'+if_images_directory+'/'+lightbox_clear_gif+'" border=0 hspace=0 vspace=0></a></div></td>');}}

}


function popup(url, name, size) {
popwin = window.open(url, name, size);
if (popwin.opener == null) popwin.opener = self;
}

function showprevnext(){
                var agt=navigator.userAgent.toLowerCase();
                var is_win   = ( (agt.indexOf("win")!=-1) || (agt.indexOf("16bit")!=-1) );
                var is_mac    = (agt.indexOf("mac")!=-1);
                index = document.cookie.indexOf(cookiename);
		              countbegin = (document.cookie.indexOf("=", index) + 1);
               	countend = document.cookie.indexOf(";", index);
               	if (countend == -1) {
              		countend = document.cookie.length;
                                   	}
		              fulllist = document.cookie.substring(countbegin, countend);
                str=location.search.substring(1);
                array=str.split("+");
                selection=array[0];
                mode=array[1];
                time=array[2];
            if (selection=='lightbox=view'){
                selection=0;}
                

	        itemlist = 0;
                steps =0;
                stepsarray = new Array ();
                stepsarray[0] = itemlist;
                rowitems = '-1';
		              for (var i = 0; i <= fulllist.length; i++) {
		             	if (fulllist.substring(i,i+1) == '[') {
		             		itemstart = i+1;
		             		thisitem = 1;
		          	} else if (fulllist.substring(i,i+1) == ']') {
		             		itemend = i;
		             		thequantity = fulllist.substring(itemstart, itemend);
               		                itemlist=itemlist+1;
                                                                                            
                                
 {
var lowerext = theExtension.toLowerCase();
if (lowerext=='jpg'||lowerext=='gif'||lowerext=='png'){
     steps=steps+1;
     stepsarray[steps] = itemlist; }
                 }
 }


                           else if (fulllist.substring(i,i+1) == '|') {
                                if (thisitem==1) theImageid = fulllist.substring(itemstart, i);
                                if (thisitem==2) thePath = fulllist.substring(itemstart, i);
                                if (thisitem==3) theExtension = fulllist.substring(itemstart, i);
                                thisitem++;
		                itemstart=i+1;
			}}

togo=stepsarray.length-1;

if (selection==0){
      document.write('<a href="'+homelink+'"><img src="'+if_images_directory+'/home.gif" border=0 alt="Return to Home Page"></a>');
        if (slideshow==1){
        if (togo>=1){
        document.write('<a href="javascript:;" onClick=popup(\''+lightbox_url+'/slideshow.html?'+stepsarray[1]+'+play+7\',\'slideshow\',\'height='+slideshow_window_height+',width='+slideshow_window_width+'\') onMouseOver="Status(\'Start the Slide Show\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/slideshow.gif" border=0 alt="Start the Slide Show"></a>');}
        else {
        document.write('<a href="javascript:;" onClick=noslidesalert() onMouseOver="Status(\'Start the Slide Show\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/slideshow.gif" border=0 alt="Start the Slide Show"></a>');}}
        if (emailsave==1){
        if (itemlist>0){
      document.write('<a href="javascript:;" onClick="popup(\''+lightbox_send_url+'\',\'Send\',\'height=410,width=330\')" onMouseOver="Status(\'Email this '+lightbox_text+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/lightbox_email.gif" border=0 alt="Email this '+lightbox_text+'"></a>');}
        else {
      document.write('<a href="javascript:;" onClick="nosendlightboxalert()" onMouseOver="Status(\'Email this '+lightbox_text+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/lightbox_email.gif" border=0 alt="Email this '+lightbox_text+'"></a>');}
                }
        if (downloadsave==1){
          if (itemlist>0){
            if (is_mac){
             lightbox_zip_url_mac = lightbox_zip_url + '?mac';
        document.write('<a href="javascript:;" onClick="popup(\''+lightbox_zip_url_mac+'\',\'Zip\',\'height=250,width=350\')"  onMouseOver="Status(\'Download all files from this '+lightbox_text+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/lightbox_download.gif" border=0 alt="Download all files from this '+lightbox_text+'"></a>');}
            else {
      document.write('<a href="'+lightbox_zip_url+'" onMouseOver="Status(\'Download all files from this '+lightbox_text+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/lightbox_download.gif" border=0 alt="Download all files from this '+lightbox_text+'"></a>');}}
        else {
      document.write('<a href="javascript:;" onClick="nodownloadlightboxalert()" onMouseOver="Status(\'Download all files from this '+lightbox_text+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/lightbox_download.gif" border=0 alt="Download all files from this '+lightbox_text+'"></a>');}
                }
          }


    else {
     page = "slideshow.html?";
     speed = new Array ();
     speed[0] = 3;
     speed[1] = 7;
     speed[2] = 15;
     speed[3] = 30;
     speed[4] = 60;
     speed[5] = 120;
     speed[6] = 300;
 for (var i = 0; i <= 6; i++) {
           if (speed[i]==time){
                if (i==0){
                 faster=0;
                 slower=i+1;}
                else if (i==6){
                 faster=i-1;
                 slower=6;}
                else{
                 faster=i-1;
                 slower=i+1;}
                 fasterspeed = speed[faster];
                 speedfasterlink=page+selection+'+'+mode+'+'+fasterspeed; 
                 slowerspeed = speed[slower];
                 speedslowerlink=page+selection+'+'+mode+'+'+slowerspeed;
                 }}
     if (mode=='play'){
     controllink=page+selection+'+stop+'+time;
     controlimage="stop.gif";
     controlalt='Stop this SlideShow';}
     else if (mode=='stop'){
     controllink=page+selection+'+play+'+time;
     controlimage="slideshow.gif";
     controlalt='Start this SlideShow';}
     for (var i = 1; i <= togo; i++) {
           if (stepsarray[i]==selection){
                     if (i==1) {min=togo;}
                     else {min=i-1;}
                     if (i==togo){plus=1;}
                     else {plus=i+1;}
                     prevpage = stepsarray[min];
                     nextpage = stepsarray[plus];
                     prevlink = page+prevpage+'+'+mode+'+'+time;
                     nextlink = page+nextpage+'+'+mode+'+'+time;


if (document.all && is_win){ 
document.write('<table border="0" cellspacing=0 cellpadding=0><tr><td align="center" width="102" nowrap><font face="Verdana,Arial,Helvtica" color="#000000" size=1>&nbsp;<br>'+time+' sec delay</font></td>');}
else { 
document.write('<table border="0" cellspacing=0 cellpadding=0><tr><td><div align="center"><font face="Verdana,Arial,Helvtica" color="#000000" size=1>'+time+' sec<br>delay</font></div></td>');}

                     document.write('<td><div align="center"><font face="Verdana,Arial,Helvtica" color="#999999" size=1>&nbsp;');
                     if (time>speed[0]){
                     document.write('<a href="'+speedfasterlink+'" onClick="stopTimer()">faster</a>');}
                     else {
                     document.write('faster');}
                     document.write('&nbsp;<br>&nbsp;');
                     if (time<speed[6]){
                     document.write('<a href="'+speedslowerlink+'" onClick="stopTimer()">slower</a>');}
                     else {
                     document.write('slower');}
                     document.write('&nbsp;</font></div></td><td><a href="'+prevlink+'" onClick="stopTimer()"><img src="'+if_images_directory+'/previous.gif" border=0 alt="Previous Image"></a><a href="'+controllink+'" onClick="stopTimer()"><img src="'+if_images_directory+'/'+controlimage+'" border=0 alt="'+controlalt+'"></a><a href="'+nextlink+'" onClick="stopTimer()"><img src="'+if_images_directory+'/next.gif" border=0 alt="Next Image"></a></td></tr></table>');
                     }}
           
}
        }
