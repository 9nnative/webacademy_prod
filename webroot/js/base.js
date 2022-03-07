$('.message .close')
.on('click', function() {
    $(this)
    .closest('.message')
    .transition('fade')
    ;
});

function popSideBar(){
  $('.ui.sidebar')
    .sidebar('toggle')
  ;
}

$('.ui.sticky')
  .sticky({
    context: '#st122'
  })
;

$('.ui.dropdown')
.dropdown()
;


var lasttimeseen = setInterval(lasttimeseen, 10000);

function lasttimeseen() {
    $.ajax({
        url: "/update_lasttimeseen",
        type: "POST",
        data: {},
        success: function (msg) {},
        })
}

$('.menu .item')
  .tab()
;
$('.item.disabled')
  .popup()
;

$('.success.message')
  .transition({
    duration   : '8s',
  })
;

$('.errorPrompt.message')
  .transition({
    duration   : '8s',
  })
;


// CREATORDASHBOARD


// ClassicEditor
// .create( document.querySelector( '.editor' ) )
// .then( editor => {
//         console.log( editor );
// } )
// .catch( error => {
//         console.error( error );
// } );

$('.button')
  .popup()
;


// DETAIL ELEARNING

$('.ui.rating')
  .rating()
;

$('.image.diffac .image')
  .dimmer({
    on: 'hover'
  })
;