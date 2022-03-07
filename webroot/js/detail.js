$('.menu .item')
.tab()
;

$('.ui.rating')
.rating()
;

$('.favorite')
  .popup()
;

$( document ).ready(function() {
  $('.tiny.modal')
  .modal('show')
;
});

function Start(){
  $( ".start" ).addClass( "dnone" );
  $(".begin").removeClass("dnone");
  
  console.log("K");
}

$(document).ready(function() {
$(".tabLink").each(function(){
    $(this).click(function(){
    localStorage.selectedTab = $(this).index() + 1;
    tabeId = $(this).attr('id');
    $(".tabLink").removeClass("activeLink");
    $(this).addClass("activeLink");
    $(".tabcontent").addClass("hide");
    $("#"+tabeId+"-1").removeClass("hide")   
    return false;     
    });
});  
    
// search for local storage
if (localStorage.selectedTab) {
    $(".tabLink:eq(" + (localStorage.selectedTab - 1) + ")").click();
}
});
