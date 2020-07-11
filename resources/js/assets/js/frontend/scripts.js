// $(function () {

//     // ===== Scroll to Top ====
//     $(window).scroll(function() {
//         if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
//             $('#return-to-top').fadeIn(200);    // Fade in the arrow
//         } else {
//             $('#return-to-top').fadeOut(200);   // Else fade out the arrow
//         }
//     });
//     $('#return-to-top').click(function() {      // When arrow is clicked
//         $('body,html').animate({
//             scrollTop : 0                       // Scroll to top of body
//         }, 500);
//     });


//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-Token': $('meta[name="_token"]').attr('content')
//         }
//     });
// });

// //
// // $.extend( true, $.fn.dataTable.defaults, {
// //     ajax: {
// //         data:{
// //             '_token':$('meta[name="_token"]').attr('content')
// //         },
// //         type: "POST"
// //     }
// // } );

// $(window).bind('keydown', function (event) {
//     if (event.ctrlKey || event.metaKey) {
//         switch (String.fromCharCode(event.which).toLowerCase()) {
//             case 's':
//                 event.preventDefault();
//                 var $focused = $(':focus');
//                 $focused.parent('form').trigger("click");
//                 break;
//         }
//     }
// });

// var allowDisallowLocate = false;
// function getLatLongByDeviceOrIp(callback) {
//     if (navigator.geolocation && location.protocol == 'https:') {
//         navigator.geolocation.getCurrentPosition(function (position) {
//             allowDisallowLocate = true;
//             callback([position.coords.latitude, position.coords.longitude]);
//         }, function () {
//             allowDisallowLocate = true;
//             $.post(laroute.route('ajax.get-lat-lng'), {}, function (coordinates) {
//                 callback([coordinates[0], coordinates[1]]);
//             });
//         }, {timeout: 2000});
//         setTimeout(function () {
//             if (!allowDisallowLocate) {
//                 $.post(laroute.route('ajax.get-lat-lng'), {}, function (coordinates) {
//                     callback([coordinates[0], coordinates[1]]);
//                 });
//             }
//         }, 3000);
//     } else {
//         $.post(laroute.route('ajax.get-lat-lng'), {}, function (coordinates) {
//             callback([coordinates[0], coordinates[1]]);
//         });
//     }
// }

// ////////////////////////////////////////////////////////////////
// //                         Blog Search                        //
// ////////////////////////////////////////////////////////////////

// $('body').on('submit', '#searchform', function (e) {
//     e.preventDefault();
//     $(this).trigger('submitFrm');
// });

// $('body').on('click', '.searchByTag', function (e) {
//     e.preventDefault();
//     if ($(this).hasClass("checked")) {
//         $(this).removeClass('checked');
//     } else {
//         $(this).addClass('checked');
//     }
//     $(this).trigger('submitFrm');
// });

// $('body').on('click', '.searchByCategory', function (e) {
//     e.preventDefault();
//     if ($(this).hasClass("checked")) {
//         $(this).removeClass('checked');
//     } else {
//         $(this).addClass('checked');
//     }
//     $(this).trigger('submitFrm');

// });

// $('body').on('submitFrm', '.searchform', function (e) {
//     var tags = [];
//     var categories = [];
//     var key = $(this).find("#key").val();
//     // var url = BASE_URL + '/blog/do-search'
//     var url =  $('.searchform').attr('action');
//     $('.searchByTag.checked').each(function () {
//         var tag_slug = $(this).data('tag_slug');
//         tags.push(tag_slug);
//     });
//     $('.searchByCategory.checked').each(function () {
//         var cat_slug = $(this).data('slug');
//         categories.push(cat_slug);
//     });

//     console.log(categories);


//     $.post(url, {_method: "POST", tags: tags, key: key, categories: categories}, function (data) {
//         window.location.href = data.url;
//     }, 'json');
// });




// function ToggleMap()
// {
//     var $content = $('#toggle_me');
//     $content.fadeToggle( "fast", function() {
//         if($content.hasClass('toggle_display'))
//         {
//             $content.toggleClass('toggle_display');
//             if($content.hasClass('init_map'))
//             {
//                 initRestMap();
//                 $content.removeClass('init_map');
//             }


//         }else {
//             $content.toggleClass('toggle_display');

//         }

//     });
// }








