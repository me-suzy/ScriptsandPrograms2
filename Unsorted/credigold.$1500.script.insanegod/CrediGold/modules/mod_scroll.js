/*--------------------[      Scrollbars Module      ]----------------------*/

/*                                                                         */

/*   This JavaScript module is a copyright of Svetlin Staev. It could be   */

/*  copied, modified and/or reproduced in any form let it be private or    */

/*  public with the only means of recognition to its authors in the form   */

/*  of this copyright message.                                             */

/*                                                                         */

/*  Date   : 10/05/2001                                                    */

/*  Author : Svetlin Staev (svetlin@developer.bg)                            */

/*                                                                         */

/*     Copyright(c)2001,2002 Infinity Interactive. All rights reserved.    */

/*-------------------------------------------------------------------------*/

function scrollBar(line,face)

   {

      with(document.body.style)

         {

            scrollbarDarkShadowColor="f0f0f0";

            scrollbar3dLightColor="DEDEDE";

            scrollbarArrowColor=line;

            scrollbarBaseColor="EAEAEA";

            scrollbarFaceColor=face;

            scrollbarHighlightColor="F1F1F1";

            scrollbarShadowColor="909090";

            scrollbarTrackColor="EEEEEE";

         }

   }

function colorBar(){w=document.body.clientWidth;h=document.body.clientHeight;x=event.clientX;y=event.clientY;if((x>w&&x<w+16)||(y>h&&y<h+16))scrollBar('333333','E0E0E0');else scrollBar('909090','EEEEEE');}document.onmousemove=colorBar;