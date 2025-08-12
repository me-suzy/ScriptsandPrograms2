/*
 * fckeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: fckeditorapi.js
 * 	Create the fckeditorAPI object that is available as a global object in
 * 	the page where the editor is placed in.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

var fckeditorAPI ;

function fckeditorAPI_GetInstance( instanceName )
{
	return this.__Instances[ instanceName ] ;
}

if ( !window.parent.fckeditorAPI )
{
	// Make the fckeditorAPI object available in the parent window.
	fckeditorAPI = window.parent.fckeditorAPI = new Object() ;
	fckeditorAPI.__Instances = new Object() ;

	// Set the current version.
	fckeditorAPI.Version = '2.0 FC' ;

	// Function used to get a instance of an existing editor present in the 
	// page.
	fckeditorAPI.GetInstance = fckeditorAPI_GetInstance ;
}
else
	fckeditorAPI = window.parent.fckeditorAPI ;

// Add the current instance to the fckeditorAPI's instances collection.
fckeditorAPI.__Instances[ FCK.Name ] = FCK ;