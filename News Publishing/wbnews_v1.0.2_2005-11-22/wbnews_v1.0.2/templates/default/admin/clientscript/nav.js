var navCurrentOpen = null;
var navHomepage = "index.php"; //change this

window.onload = nav_init;

/* onload method */
function nav_init()
{
    var url = document.URL; // we need to get the current URL
        
    var position = url.lastIndexOf("/");
    var strLength = url.length;
    var substr;
        
    if ((strLength - 1) >= position)
    {
        substr = url.substring(position + 1);
    }
    else
    {
        alert("Error");
        return;
    }
    
    nav_changeSection(nav_getSection(substr));
        
}
    
/* onload method returning section name */
function nav_getSection(file)
{
    var pos;
        
    // remove ?
    if ((pos = file.indexOf("?")) !== -1)
    {
        file = file.substring(0, pos);
    }
    
    // remove #
    if ((pos = file.indexOf("#")) != -1)
    {
        file = file.substring(0, pos);
    }
    
    if (file.length === 0)
    {
        file = navHomepage; //change this
    }
    
    var section;
        
    switch (file)
    {
    case "index.php":
    case "update.php":
        return "acp";
    case "newsconfig.php":
    case "database.php":
        return "configuration";
    case "sendmsg.php":
    case "emoticons.php":
        return "misc";
    case "news.php":
    case "comment.php":
        return "news";
    case "category.php":
        return "categories";
    case "usergroup.php":
    case "user.php":
        return "users";
    case "themes.php":
        return "themes";
    }
    
}
    
function nav_changeSection(section)
{
        
    //check if section is the same as before
    if (section === navCurrentOpen)
    {
        return; // we wont nothing to do with it
    }
    
    var mainElement, sectionLinks;
        
    if (navCurrentOpen !== null)
    {
        // close old section
        mainElement = document.getElementById(navCurrentOpen);
        sectionLinks = document.getElementById("nav-" + navCurrentOpen);
            
        mainElement.style.background = "#eee";
        sectionLinks.style.display = "none";
    }
        
    navCurrentOpen = section;
    
    mainElement = document.getElementById(navCurrentOpen);
    sectionLinks = document.getElementById("nav-" + navCurrentOpen);
    
    mainElement.style.background = "#ddd";
    sectionLinks.style.display = 'block';
        
}
