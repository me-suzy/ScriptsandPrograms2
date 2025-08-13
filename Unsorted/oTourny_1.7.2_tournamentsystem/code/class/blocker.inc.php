<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/

 /*

  Blocker - Keepout unwanted browsers

 */



 $browsers = array(

  "Agentware",



  "Bullseye",



  "CherryPicker",

  "Crescent Internet ToolPak HTTP OLE Control",

  "Cyberdog",

  "Chimera",

  "Charlotte",

  "CacheFlow",

  "CacheBlaster",



  "ExtractorPro",

  "EmailWolf",

  "EmailSiphon",

  "EchO",

  "EmailWolf",

  "EmailCollector",

  "EirGrabber",

  "Excalibur",



  "Lycos_Spider",

  "Lotus-Notes",



  "Gulliver",

  "Go-Get-It",

  "Go-Ahead-Got-It",

  "GetRight",

  "GAIS Robot",



  "HotJava",

  "Harvest",



  "InfoSeek",

  "Informant",

  "ia_archiver",



  "fido",



  "MSProxy",

  "MS FrontPage",



  "NetMechanic",

  "NICErsPRO",

  "NEWT",

  "NETCOMplete",

  "None of ya f**king business",



  "Spyglass",

  "SiteSnagger",

  "Science Traveller International",

  "SPRY",

  "Spider",

  "Spoofer",



  "Teleport Pro",



  "Powermarks",

  "PlanetWeb",

  "Phantom",



  "Quarterdeck",

  "QNX Voyager",



  "webbandit",

  "WebZIP",

  "Wget",

  "WebCompass",



  "xChaos",

 );



 for($i = 0; $i < count($browsers); $i++)

  if(stristr($HTTP_USER_AGENT, $browsers[$i]))

   die("<center>Your Browser is not allowed, Please Try Mozilla or IE.</center>");



 unset($browsers); unset($i);

?>