function Validate_FrmAddURL() {



  if (document.FrmAddURL.txtSiteName.value == "")

  {

    alert("Please enter a value for the \"Site Name\" field.");

    document.FrmAddURL.txtSiteName.focus();

    return (false);

  }



  if (document.FrmAddURL.txtURL.value == "")

  {

    alert("Please enter a value for the \"Site URL\" field.");

    document.FrmAddURL.txtURL.focus();

    return (false);

  }



  if (document.FrmAddURL.txtDescription.value == "")

  {

    alert("Please enter a value for the \"Description\" field.");

    document.FrmAddURL.txtDescription.focus();

    return (false);

  }



  if (document.FrmAddURL.txtDescription.value > 250)

  {

    alert("Please enter at most 250 characters in the \"Description\" field.");

    document.FrmAddURL.txtDescription.focus();

    return (false);

  }

  

  if (document.FrmAddURL.txtEmail.value == "")

  {

    alert("Please enter a value for the \"Email address\" field.");

    document.FrmAddURL.txtEmail.focus();

    return (false);

  }

 

  

  if (document.FrmAddURL.txtLinkURL.value == "")

  {

    alert("Please enter a value for the \"Link URL\" field.");

    document.FrmAddURL.txtLinkURL.focus();

    return (false);

  }

  

  

  return (true);

 }