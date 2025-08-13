function DoSpell(formname, subject, body)
{
document.SPELLDATA.formname.value=formname
document.SPELLDATA.subjectname.value=subject
document.SPELLDATA.messagebodyname.value=body
document.SPELLDATA.companyID.value="custom\\www.bid-war.com"
document.SPELLDATA.language.value=1033
document.SPELLDATA.opener.value="http://www.bid-war.com/cgi-bin/auction/sproxy.cgi"
document.SPELLDATA.formaction.value="http://www.spellchecker.com/spell/startspelling.asp "
window.open("http://www.bid-war.com/auction/initspell.htm","Spell",
"toolbar=no,directories=no,resizable=yes,width=620,height=600,top=100,left=100")
}
