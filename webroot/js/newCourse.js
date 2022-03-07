function returnToInfos() {
    $.tab('change tab', 'infos');
    $('.infosStep').addClass('active').removeClass('completed')
    $('.optionsStep').addClass('disabled').removeClass('active') 
    $('.infos').removeClass('dnone')
    $('.sections').addClass('dnone')
    $('.options').addClass('dnone')
    $('.ui.form').removeClass('success')
  }
  
  function goToOptions() {
    $.tab('change tab', 'options');
    $('.sectionsStep').addClass('disabled')
    $('.optionsStep').removeClass('disabled').addClass('active').removeClass('completed')
    $('.sections').addClass('dnone')
    $('.infos').addClass('dnone')
    $('.options').removeClass('dnone')
  }
  
  function goToSections() {
    $.tab('change tab', 'sections');
    $('.sectionsStep').addClass('active').removeClass('disabled')
    $('.optionsStep').removeClass('active').addClass('completed') 
    $('.sections').removeClass('dnone')
    $('.infos').addClass('dnone')
    $('.options').addClass('dnone')
  }
  
  function returnToSections() {
    $.tab('change tab', 'sections');
    $('.sectionsStep').removeClass('completed').addClass('active')
    $('.optionsStep').removeClass('active').addClass('disabled') 
    $('.sections').removeClass('dnone')
  }

  function newCategoryModal(){
    $('.ui.modal')
    .modal('show')
  ;
  }