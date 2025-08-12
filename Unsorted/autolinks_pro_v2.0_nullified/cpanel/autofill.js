
  function autofilltag( form )
  {
    if( form.type.value == "text" )
    {
      if( form.orderby.value == "hitsin" || form.orderby.value == "clicks" )
      {
	form.numlinks.value = 15;
        form.numcolumns.value = 1;
	form.minhits.value = 1;
      }
      if( form.orderby.value == "added" )
      {
	form.numlinks.value = 15;
        form.numcolumns.value = 1;
	form.minhits.value = 0;
      }
      if( form.orderby.value == "name" )
      {
	form.numlinks.value = 999;
        form.numcolumns.value = 4;
	form.minhits.value = 0;
      }
      if( form.orderby.value == "random" )
      {
        form.numlinks.value = 1;
        form.numcolumns.value = 1;
	form.minhits.value = 0;
      }
	  
      form.padding.value = 0;
    }
    else
    {
      if( form.type.value == "banner" )
      {
	form.numlinks.value = 1;
        form.numcolumns.value = 1;
      }
      if( form.type.value == "button" )
      {
        form.numlinks.value = 5;
        form.numcolumns.value = 1;
      }
      if( form.type.value == "thumb" )
      {
        form.numlinks.value = 8;
        form.numcolumns.value = 4;
      }
	
      if( form.orderby.value == "hitsin" || form.orderby.value == "clicks" )
      {
	form.minhits.value = 1;
      }
      else
      {
	form.minhits.value = 0;
      }

      form.padding.value = 5;
    }
  }

  function autofillsettings( form )
  {
    form.alurl.value = form.url.value + "/autolinks/";
  }