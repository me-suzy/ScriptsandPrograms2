<script language="JavaScript">

function checkit()
{
var ErrMsg = "One or more problems exist with your information:";
var Err = 0;
var atsignPos = document.info.email.value.indexOf("@", 0)     /* check for @ */
  if (atsignPos == -1)  
    {
                ErrMsg = "Invalid Email;";
                Err = 1;
    
        }
  if (document.info.description.value =="")  /* check for blank description    */
    {
                ErrMsg = "Please create description";
                Err = 1;
    
        }
  if (document.info.email.value.indexOf(".", atsignPos) == -1)  /* check for . after @      */
    {
                ErrMsg = "Invalid Email";
                Err = 1;
    
        }
  if (document.info.nickname.value =="")  /* check for blank name     */
    {
                ErrMsg = "Please add name";
                Err = 1;
    
        }

if (Err != 0)
{
  alert(ErrMsg);
  return false;
 }
}                              
</script>