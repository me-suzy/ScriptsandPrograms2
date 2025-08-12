/* Coded by Thaal-Rasha (c) Octobre 2000 (thaal-rasha@lords-zodiac.com) */
function Verif()
{
    if (document.Formulaire.nom.value == "")
    {
        alert("Veuillez saisir un nom s'il vous plait.");
        document.Formulaire.nom.focus();
     }
     else if (document.Formulaire.nom.value.length > 20)
     {
         alert("Le nom ne peut pas excéder 20 caractères !");
         document.Formulaire.nom.focus();
     }
     else if (document.Formulaire.email.value == "" )
     {
         alert("Veuillez saisir un e-mail s'il vous plait.");
         document.Formulaire.email.focus();
     }
     else if (document.Formulaire.titre.value == "")
     {
         alert("Veuillez saisir un titre s'il vous plait");
         document.Formulaire.titre.focus();
     }
     else if (document.Formulaire.titre.value.length>50)
     {
         alert("Le titre ne peut pas excéder 50 caractères !");
         document.Formulaire.titre.focus();
     }
     else if (document.Formulaire.message.value=="")
     {
         alert("Veuillez saisir un texte s'il vous plait");
         document.Formulaire.message.focus();
     }
     else
     {
         document.Formulaire.method = "post";
         document.Formulaire.action = "index.php?affiche=Forum-ajouter";
         document.Formulaire.submit();
     }
}