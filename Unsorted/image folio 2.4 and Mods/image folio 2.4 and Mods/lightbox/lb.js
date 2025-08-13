// lightbox v 1.0
// add-on to imageFolio written by Dirk Koppers, lightbox@imagefolio.com
// Available from http://www.ImageFolio.com
// ---------------------------------------------------------------------------
// JAVASCRIPT NAME : lb.js 
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

var today = new Date();
var expiry = new Date(today.getTime() + 90 * 24 * 60 * 60 * 1000);
var agt=navigator.userAgent.toLowerCase();
var is_major = parseInt(navigator.appVersion); 
var is_minor = parseFloat(navigator.appVersion); 
var is_ie   = (agt.indexOf("msie") != -1); 
var is_ie3  = (is_ie && (is_major < 4)); 
var is_ie4  = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.0")==-1) ); 
var is_ie5up  = (is_ie  && !is_ie3 && !is_ie4); 


function showItems() {
		index = document.cookie.indexOf(cookiename);
		countbegin = (document.cookie.indexOf("=", index) + 1);
        	countend = document.cookie.indexOf(";", index);
        	if (countend == -1) {
            		countend = document.cookie.length;
                        
        	}
		fulllist = document.cookie.substring(countbegin, countend);
              
                okay=0;
		itemlist = 0;
		for (var i = 0; i <= fulllist.length; i++) {
			if (fulllist.substring(i,i+1) == '[') {
				itemstart = i+1;
				thisitem = 1;
			} else if (fulllist.substring(i,i+1) == ']') {
				itemend = i;
				thequantity = fulllist.substring(itemstart, itemend);
                    		itemlist=itemlist+1;
                                
                            
{                        compare=path + imageid + extension;
                         compareto=thePath + theImageid + theExtension;
                         if (compare==compareto) { 
                            okay=1;
                            which=itemlist;}
}
			} else if (fulllist.substring(i,i+1) == '|') {
				if (thisitem==1) theImageid = fulllist.substring(itemstart, i);
                                if (thisitem==2) thePath = fulllist.substring(itemstart, i);
                                if (thisitem==3) theExtension = fulllist.substring(itemstart, i);
                                thisitem++;
				itemstart=i+1;
			}
		}

div='div';
layerdiv=path+imageid+extension+div;


if (is_ie5up){
 document.write('<div id="'+layerdiv+'" align="center"></div>');}
   

if (okay==1){
	if (is_ie5up){ document.all[layerdiv].innerHTML = '<A HREF="javascript:removeItem(\''+imageid+'\',\''+path+'\',\''+extension+'\')" onMouseOver="Status(\'Remove from '+lightbox_name+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/'+lightbox_yes_gif+'" border=0 alt="Remove from '+lightbox_name+'"></a>';}
        else {
document.writeln('<div align="'+image_alignment+'"><a href="javascript:removeItem(\''+imageid+'\',\''+path+'\',\''+extension+'\')" onMouseOver="Status(\'Remove from '+lightbox_name+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/'+lightbox_yes_gif+'" border=0 alt="Remove from '+lightbox_name+'"></a></div>');}}
else {
if (is_ie5up){ document.all[layerdiv].innerHTML = '<A HREF="javascript:addtolightbox(\''+imageid+'\',\''+path+'\',\''+extension+'\')" onMouseOver="Status(\'Add to '+lightbox_name+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/'+lightbox_no_gif+'" border=0 alt="Add to '+lightbox_name+'"></a>';}
        else {
document.writeln('<div align="'+image_alignment+'"><a href="javascript:addtolightbox(\''+imageid+'\',\''+path+'\',\''+extension+'\')" onMouseOver="Status(\'Add to '+lightbox_name+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/'+lightbox_no_gif+'" border=0 alt="Add to '+lightbox_name+'"></A></div>');}}
		
	
	}

	function removeItem(newImageid, newPath, newExtension) {
                newlocation = location.href;
                newlocationarray=newlocation.split("&lightbox");
                newstr=location.search.substring(1);
                newarray=newstr.split("=");
                if (newarray[0]=='action'){ 
                lightbox = '&lightbox=1';
                newtogo=newlocationarray[0]+lightbox;}
                else {newtogo=location.href;}
                index = document.cookie.indexOf(cookiename);
		countbegin = (document.cookie.indexOf("=", index) + 1);
        	countend = document.cookie.indexOf(";", index);
        	if (countend == -1) {
            		countend = document.cookie.length;
                        
        	}
		fulllist = document.cookie.substring(countbegin, countend);
                newItemList = null;
		itemlist = 0;
                
		for (var i = 0; i <= fulllist.length; i++) {
			if (fulllist.substring(i,i+1) == '[') {
				itemstart = i+1;
                                thisitem = 1;
			} else if (fulllist.substring(i,i+1) == ']') {
				itemend = i;
				theitem = fulllist.substring(itemstart, itemend);
				itemlist=itemlist+1;
                                compare=newPath + newImageid + newExtension;
                                compareto=thePath + theImageid + theExtension;
                                if (compare!=compareto) {
					newItemList = newItemList+'['+theImageid+'|'+thePath+'|'+theExtension+'|]';
				}

			}else if (fulllist.substring(i,i+1) == '|') {
				if (thisitem==1) theImageid = fulllist.substring(itemstart, i);
                                if (thisitem==2) thePath = fulllist.substring(itemstart, i);
                                if (thisitem==3) theExtension = fulllist.substring(itemstart, i);
                                thisitem++;
				itemstart=i+1;
			}
		}


		index = document.cookie.indexOf(cookiename);
		document.cookie=""+cookiename+"="+newItemList+"; expires="+expiry.toGMTString()+"; path=/";

newstr=location.search.substring(1);
if (newstr=='lightbox=view'){location.href = location.href;}
else if (is_ie5up){
div='div';
layerdiv=newPath+newImageid+newExtension+div;
document.all[layerdiv].innerHTML = '<A HREF="javascript:addtolightbox(\''+newImageid+'\',\''+newPath+'\',\''+newExtension+'\')" onMouseOver="Status(\'Add to '+lightbox_name+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/'+lightbox_no_gif+'" border=0 alt="Add to '+lightbox_name+'"></a>';}
           else {    location.href = location.href;}
	}


	 
	function addtolightbox(newImageid, newPath, newExtension) {
            newlocation = location.href;
                newlocationarray=newlocation.split("&lightbox");
                newstr=location.search.substring(1);
                newarray=newstr.split("=");
                if (newarray[0]=='action'){ 
                lightbox = '&lightbox=1';
                newtogo=newlocationarray[0]+lightbox;}
                else {newtogo=location.href;}
             	index = document.cookie.indexOf(cookiename);
		countbegin = (document.cookie.indexOf("=", index) + 1);
        	countend = document.cookie.indexOf(";", index);
        	if (countend == -1) {
            		countend = document.cookie.length;
                        
        	}
		fulllist = document.cookie.substring(countbegin, countend);
                itemlist = 0;
		for (var i = 0; i <= fulllist.length; i++) {
			if (fulllist.substring(i,i+1) == '[') {
				itemstart = i+1;
				thisitem = 1;
			} else if (fulllist.substring(i,i+1) == ']') {
				itemend = i;
				thequantity = fulllist.substring(itemstart, itemend);
                    		itemlist=itemlist+1;}}

if (fulllist.length<3500){closeto=3501;}
else{
rawitemsize=fulllist.length/itemlist;
exactitemsize=Math.round(rawitemsize);
itemsize=exactitemsize+50;
closeto=4096-itemsize;}
if (fulllist.length>closeto){alert('WARNING:\r\r----------------------------------\r\rYour '+lightbox_text+' contains '+itemlist+' files and is full.\rYou have to remove some files from your '+lightbox_text+' before you can add more.\r\r----------------------------------');}
else { 
				index = document.cookie.indexOf(cookiename);
				countbegin = (document.cookie.indexOf("=", index) + 1);
        			countend = document.cookie.indexOf(";", index);
	        		if (countend == -1) {
        	    			countend = document.cookie.length;
	        		}
		                document.cookie=""+cookiename+"="+document.cookie.substring(countbegin, countend)+"["+newImageid+"|"+newPath+"|"+newExtension+"|]"+"; expires="+expiry.toGMTString()+"; path=/";
           if (is_ie5up){
div='div';
layerdiv=newPath+newImageid+newExtension+div;
document.all[layerdiv].innerHTML = '<A HREF="javascript:removeItem(\''+newImageid+'\',\''+newPath+'\',\''+newExtension+'\')" onMouseOver="Status(\'Remove from '+lightbox_name+'\');return true" onMouseOut="Status(\'\');return true"><img src="'+if_images_directory+'/'+lightbox_yes_gif+'" border=0 alt="Remove from '+lightbox_name+'"></a>';}
           else {    location.href = location.href;}}
}


function Status(text){window.status = text;}

