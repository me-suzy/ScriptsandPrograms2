body
{
        background-color: #ccc;a
        color: {fontcolor};
        font-family: {fontfamily};
        font-size: {fontsize};
        height:100%;
}

a:link                {color: {link}; text-decoration: none}
a:visited        {color: {linkvisited}; text-decoration: none}
a:hover                {color: {linkhover}; text-decoration: underline}
a:active        {color: {link};}

#wrapper
{
        width: 100%;
        text-align:left;
        vertical-align:top;
        background-color: {bgcolor};
        height:100%;
}

#header
{
        background-color: #fff;
        padding:4px;
        margin:2px;
        font-size:35pt;
        font-family: Trebuchet MS;
        color: #fff;
}

#header .slogan
{
        font-size:13px;
        font-family: Trebuchet MS;
        font-weight:bold;
}

#navbar
{
        background-color: #E96A33;
         background-image: url(img/nav_grad.gif);
        padding:4px;
        height:20px;
         font-size: 13px;
}

#navbar a:link,#navbar a:active,#navbar a:visited,#navbar a:hover
{
        color: white;
}


#footer
{
        background-color: {thirdalt};
        padding:4px;
        margin:2px;
        text-align:right;
}

#content
{
        padding:4px;
        margin:2px;
        vertical-align:top;
}

#nav_left
{
        background-color: {firstalt};
        padding:4px;
        float:left;
        vertical-align:top;
        height:100%;
        z-index:10;
        overflow:visible;

}

#nav_left .stitle
{
        font-family: Trebuchet MS, Arial, Verdana, Helvetica, sans-serif;
        text-decoration:none;
        font-size: 10pt;
        font-weight: bold;
        line-height: 26px;
        color:{subfontcolor};
        border-bottom: 2px dotted {tblborder};
        padding:5px;
        background-color: {secondalt};
        margin-bottom:5px;

}

#navlinks
{
        font-size:12px;
        font-weight: bold;
        padding-left: 6px;
}

#subcat
{
        font-size:12px;
        font-weight:bold;
        width:45%;
        margin:4px;
        padding:3px;
        float:left;
}

.article_loop
{
        background-color:{firstalt};
        margin:2px;
        padding:4px;
}

.article_loop .subject
{
        font-family: Trebuchet MS, Arial, Verdana, Helvetica, sans-serif;
        text-decoration:none;
        font-size: 12pt;
        font-weight: bold;
        color:{subfontcolor};
}

.article_loop .summary
{
        font-size:12px;
}

.article_loop .author, .article_loop .category
{
        font-weight:bold;
}

.article_loop .category
{
        text-decoration: underline;
}


.title
{
        font-family: Georgia,Trebuchet MS, Arial, Verdana, Helvetica, sans-serif;
        text-decoration:none;
        font-size: 10pt;
        font-weight: bold;
        line-height: 26px;
        color:{subfontcolor};
}

h1,h2,h3,h4,h5,h6
{
        font-family: Georgia, Arial, Verdana, Helvetica, sans-serif;
        color:{subfontcolor};
        margin:2px;
}

h1.box
{
        padding:2px;
}

tr,td,p,li,ul,ol,form
{
        font-family: verdana,arial,helvetica,sans-serif;
        font-size: {fontsize}
}

hr
{
        color:#000000
}

blockquote
{
        font-family: verdana,arial,helvetica,sans-serif;
        color: #666666
}

img
{
        border-style: none;
}

b
{
        color:{subfontcolor};
}

select
{
        font-family: verdana,arial,helvetica,sans-serif;
        color: {fontcolor};
        font-size: 10px;
        border: 1px solid {tblborder};
        background-color: {secondalt};
}


input, textarea ,option
{
        font-family: verdana,arial,helvetica,sans-serif;
        color: {fontcolor};
        font-size: 7.5pt;
        background-color: {secondalt};
}

.firstalt
{
        background-color: {firstalt};
        color: {fontcolor};
}

.secondalt
{
        background-color: {secondalt}; color: {fontcolor};
}

.thrdalt    { background-color: {thirdalt}; color: {fontcolor};}

.tblhead        { color:{tabletitlefontcolor}; text-decoration:none; font-size: 7.5pt; font-weight: bold; background-color: {tabletitlebgcolor};}

.tblborder  { background-color: {tblborder};}

.highlight { background-color: yellow; padding: 1px; color: black}
.red { color: red }
.green {color: green}

/*-- article font size --*/
#article
{
        font-size:12px;
}

/*-- author profile box --*/
#authorbox
{
        padding:4px;
        font-size:11px;
        width: 180px;
         float:right;
        border-left: 1px solid {tblborder};
         border-bottom: 1px solid {tblborder};
        background-color: {secondalt};
}

/*-- rate article box --*/
#ratebox, #supportbox, .custombox
{
        padding:4px;
        width: 95%;
        margin:3px;
        border: 1px solid {tblborder};
        background-color: {secondalt};
}

/*--  comments loop --*/
.comments_box
{
        padding:4px;
        width: 95%;
        margin:4px;
        border-bottom: 2px dotted {tblborder};
        background-color: {firstalt};
}

/*-- misc crap. --*/
.border_bottom
{
        border-bottom: 1px solid {tblborder};
}

.htmlbox
{
        font-family: courier new;
}

.hightlight
{
        background-color: yellow;
}
