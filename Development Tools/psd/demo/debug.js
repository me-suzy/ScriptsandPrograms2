// Atrise PHP Script Debugger 1.1.0

// Settings

// Enable fixed size of debug sections
var DebugScrollSections = false;

// Size of debug sections
var DebugScrollSectionHeight = '300px';

// Code

function DebugHidingClick(Name)
{
  var x = document.getElementById(Name);
  if (x.style.visibility == 'visible')
  {
    x.style.visibility = 'hidden';
    x.style.overflow = 'hidden';
    x.style.height = '1px';
  }
  else
  {  
    x.style.visibility = 'visible';
	if (DebugScrollSections)
	{
      x.style.overflow = 'auto';
      x.style.height = DebugScrollSectionHeight;
	}
	else
	{
      x.style.overflow = 'hidden';
      x.style.height = 'auto';
    }
  }
}
