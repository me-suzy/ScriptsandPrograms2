function updateJour(form,champjour,champmois,champannee) {
	eval("var dateTmp = new Date(document."+form+".elements['"+champannee+"'].value,document."+form+".elements['"+champmois+"'].value-1,1);");	
	for(var nbJoursMois=31;nbJoursMois>=28;nbJoursMois--) {
		dateTmp.setDate(nbJoursMois);
		if (dateTmp.getMonth()==eval("document."+form+".elements['"+champmois+"'].value-1")) 
			break;
		else	
			dateTmp.setMonth(eval("document."+form+".elements['"+champmois+"'].value-1"));
	}
	for(var i=1;i<=31;i++) {
		if (i<=nbJoursMois) {
			eval("document."+form+".elements['"+champjour+"'].options["+(i-1)+"].text = '"+i+"'");
			eval("document."+form+".elements['"+champjour+"'].options["+(i-1)+"].value = '"+i+"'");
		} else {
			eval("document."+form+".elements['"+champjour+"'].options["+(i-1)+"].text = ''");
			eval("document."+form+".elements['"+champjour+"'].options["+(i-1)+"].value = '1'");		
		}
	}
}
function modifierAction(form, valeur) {
	eval("document."+form+".action.value = '"+valeur+"';");
}
function submitForm(form) {
	eval("document."+form+".submit();");
}
function promptNomConfiguration(form, config) {
	var valeur = '';
	if(valeur = prompt("Nom de la configuration: ", config)) {
		eval("document."+form+".nomconfignew.value = '"+valeur+"';");
		submitForm(form);
	}
	return false;
}