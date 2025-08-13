// lightbox v 1.0
// add-on to imageFolio written by Dirk Koppers, lightbox@imagefolio.com
// Available from http://www.ImageFolio.com
// ---------------------------------------------------------------------------
// JAVASCRIPT NAME : ss.js 
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

function imageLoad(targetimage) {
    if (document.images) {
        img1 = new Image(); img1.src = targetimage;
  
    }
}

function showSlideShow() {
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
                if (selection==''){
                selection=0;}
                itemlist = 0;
                next = 0;
                total = 0;
                nextimage = 0;
                notfirst = 0;
                rowitems = '-1';
		              for (var i = 0; i <= fulllist.length; i++) {
		             	if (fulllist.substring(i,i+1) == '[') {
		             		itemstart = i+1;
		             		thisitem = 1;
		          	} else if (fulllist.substring(i,i+1) == ']') {
		             		itemend = i;
		             		thequantity = fulllist.substring(itemstart, itemend);
               		                itemlist=itemlist+1;
                                        total=total+1;                                                    
                                
{if (selection>0){
     var lowerext = theExtension.toLowerCase();
     if (lowerext=='jpg'||lowerext=='gif'||lowerext=='png'){
     if (notfirst==0){
     firstimage =''+imageurl+'/'+thePath+'/'+theImageid+'.'+theExtension+'';
     firstslide=itemlist;
     notfirst = 1;}
     if (next!=2){
       if (next==1){         
         nextimage =''+imageurl+'/'+thePath+'/'+theImageid+'.'+theExtension+'';
         nextslide=itemlist;
         next = 2;}
                   
       if (selection==itemlist) { 
          next = 1; }
                                 }
                    }}}

}

                           else if (fulllist.substring(i,i+1) == '|') {
                                if (thisitem==1) theImageid = fulllist.substring(itemstart, i);
                                if (thisitem==2) thePath = fulllist.substring(itemstart, i);
                                if (thisitem==3) theExtension = fulllist.substring(itemstart, i);
                                thisitem++;
		                itemstart=i+1;
			}}

if (nextimage==0){
nextimage=firstimage;
nextslide=firstslide;}
             
if (total==0) {
    document.writeln('<tr bgcolor="'+thumb_table_bgcolor+'"><td><br><br><center><font face="Verdana,Arial,Helvtica" size=2><b><font color="#ff0000">There are no files in your '+lightbox_text+' so the slide show can not play.</font></b><br>To add files go <a href="'+imagefolio_url+'">here</a>.</font></center><br><br><br></td></tr>');}
else {         
            if (selection==0){
                document.writeln('<tr bgcolor="'+thumb_table_bgcolor+'"><td><br><br><center><font face="Verdana,Arial,Helvtica" size=2 color="#ff0000"><b>Please, stop this slideshow.</b></font></center><br><br><br></td></tr>');}

		itemlist = 0;
                rowitems = 0;
		for (var i = 0; i <= fulllist.length; i++) {
			if (fulllist.substring(i,i+1) == '[') {
				itemstart = i+1;
				thisitem = 1;
			} else if (fulllist.substring(i,i+1) == ']') {
				itemend = i;
				thequantity = fulllist.substring(itemstart, itemend);
                    		row=rowitems/columns;
                                itemlist=itemlist+1;
                                rowitems=rowitems+1;
                                leftover=total-itemlist;
                                                              
                                
{if (selection>0){
     if (selection==itemlist) { 
var lowerext = theExtension.toLowerCase();
if (lowerext=='jpg'||lowerext=='gif'||lowerext=='png'){
thisImage=''+imageurl+'/'+thePath+'/'+theImageid+'.'+theExtension+'';}

ns4 = (document.layers)? true:false
ie4 = (document.all)? true:false
var agt=navigator.userAgent.toLowerCase();
var is_win   = ( (agt.indexOf("win")!=-1) || (agt.indexOf("16bit")!=-1) );
var is_mac    = (agt.indexOf("mac")!=-1);

winH = (ns4)? window.innerHeight-97 : document.body.offsetHeight-101

document.write('<tr bgcolor="'+thumb_table_bgcolor+'"><td height="'+winH+'" align="center"><IMG src="'+thisImage+'"');
if (ie4 && is_win){
document.write(' onLoad="imageLoad(\''+nextimage+'\');slide(\''+nextslide+'\')');
document.write('" onerror=');
document.write('"imageLoad(\''+nextimage+'\');slide(\''+nextslide+'\')');}
else{
document.write(' onLoad="imageLoad(\''+nextimage+'\');gotimeout(\''+nextslide+'\')');
document.write('" onerror=');
document.write('"imageLoad(\''+nextimage+'\');gotimeout(\''+nextslide+'\')');}



document.write('"></td></tr>');
}}



			}} else if (fulllist.substring(i,i+1) == '|') {
                                if (thisitem==1) theImageid = fulllist.substring(itemstart, i);
                                if (thisitem==2) thePath = fulllist.substring(itemstart, i);
                                if (thisitem==3) theExtension = fulllist.substring(itemstart, i);
                                thisitem++;
				itemstart=i+1;
			}
		}
		
	}}

var timerRunning = 0;
var myTimer = null;

var r=0;

function slide(nextslide) {
  timerRunning = 0;
  str=location.search.substring(1);
  array=str.split("+");
  mode=array[1];
  time=array[2];
  if (mode=='play'){
    if (r<100) {
        if (time==3){
           speed=time*60
           r += 6;}
        else if (time==7){
           speed=time*30
           r += 3;}
        else {
           speed=time*10
           r += 1;}
        changeit();
        myTimer = setTimeout('slide('+nextslide+')',speed);
        timerRunning = 1;
    }
    else {
        timerRunning = 0;
        page = "slideshow.html?";
        next = page+nextslide+'+'+mode+'+'+time;
        location.href = next;}
}    }

function gotimeout (followpage){
  str=location.search.substring(1);
  array=str.split("+");
  time=array[2];
  slidetime = time*1000;
  myTimer = setTimeout('go('+followpage+')',slidetime);
  timerRunning = 1;
}

function go (nextpage) {
  timerRunning = 0;
  str=location.search.substring(1);
  array=str.split("+");
  mode=array[1];
  time=array[2];
  if (mode=='play'){
  page = "slideshow.html?";
  next = page+nextpage+'+'+mode+'+'+time;
  location.href = next;}
}

function changeit() {
    if (document.all) 
        document.all('blue').style.clip = 'rect(0 '+r+' 10 0)';
    else if (document.layers) {
        document.layers['blue'].clip.top = 0;
        document.layers['blue'].clip.right = r;
        document.layers['blue'].clip.bottom = 10;
        document.layers['blue'].clip.left = 0;
    }
}

function stopTimer() {
    if (timerRunning==1)
        clearTimeout(myTimer); 
}

function showcloseButton(){
     document.writeln('<td width="14%"><div align="center"><a href="javascript:window.close()"><img src="'+if_images_directory+'/lightbox_close_slideshow.gif" border=0 hspace=0 vspace=0></a></div></td>');}

function showCount() {
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

if (total==1){              
document.writeln(''+total+' file in your '+lightbox_text+'');}
else{
document.writeln(''+total+' files in your '+lightbox_text+'');}
}

function showcurrent(){
                str=location.search.substring(1);
                array=str.split("+");
                selection=array[0];
                
            if (selection=='lightbox=view'){
                selection=0;}
            if (selection>0){
                  document.write('<br>Displaying file '+selection+'');}
                    }

function showtopifimage(){
               document.write('<tr bgcolor="#eeeeee"><td align=center nowrap valign=bottom background="'+if_images_directory+'/topback.gif"><table border=0 cellspacing=0 cellpadding=0 width="100%"><tr><td align=center><img src="'+if_images_directory+'/topimage.gif" alt="" border=0 hspace=0 vspace=0></td></tr></table></td></tr>');
                    }

function showbottomifimage(){
               document.write('<tr bgcolor="#eeeeee"><td align=center valign=bottom background="'+if_images_directory+'/botback.gif"><table border=0 cellspacing=0 cellpadding=0 width="100%"><tr><td width=1><img src="'+if_images_directory+'/left.gif" alt="" border=0 hspace=0 vspace=0></td><td align=center width="100%"><a href="http://www.imagefolio.com" target="_blank"><img src="'+if_images_directory+'/folio.gif" alt="Powered by ImageFolio" border=0 hspace=0 vspace=0></a></td><td align=right width=1><img src="'+if_images_directory+'/right.gif" alt="" border=0 hspace=0 vspace=0></td></tr></table></td></tr>');
                    }

