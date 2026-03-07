// Initiate superfish on nav menu
$('.nav-menu').superfish({
   animation: {
       opacity: 'show'
   },
   speed: 400
});



/* aside menu dropdown */
$(".aside-menu a").click(function() {
   let link = $(this);
   let closest_ul = link.closest("ul");
   let parallel_active_links = closest_ul.find(".active")
   let closest_li = link.closest("li");
   let link_status = closest_li.hasClass("active");
   let count = 0;
   closest_ul.find("ul").slideUp(function() {
       if (++count == closest_ul.find("ul").length)
           parallel_active_links.removeClass("active");
   });
   if (!link_status) {
       closest_li.children("ul").slideDown();
       closest_li.addClass("active");
   }
});




/* topbar */
$('li.dropdown > a').on('click', function(event) {
   event.preventDefault()
   $(this).parent().find('ul').first().toggle(300);
   $(this).parent().siblings().find('ul').hide(200);
   $(this).parent().find('ul').mouseleave(function() {
       var thisUI = $(this);
       $('body').click(function() {
           thisUI.hide();
           $('body').unbind('click');
       });
   });
});




$('ul.sub-menu li.active').parent().css('display', 'block');




//     /* side menu toggle */

$('#togglemenu').on('click', function(e) {
   e.preventDefault();
   if (window.matchMedia('(min-width: 1200px)').matches) {
       $('body').toggleClass('hide-sidebar');
      //  $('.report-sidebar').toggleClass('close');
   } else {
       $('body').toggleClass('show-sidebar');
      //  $('.report-sidebar').toggleClass('show');
   }
   $('.dataTables_scrollHeadInner').width($(window).width() - 70);
});



var removeClass = true;
$(".sidebar-menu").click(function() {
   $(".toggle-menu").toggleClass('toggled');
   $(".sidebar-menu").toggleClass("active");
   removeClass = true;
});

$(".toggle-menu").click(function() {
   removeClass = false;
});


$(".sidebar-menu").mouseleave(function() {
   $('body').click(function() {
       if (removeClass) {
           $(".toggle-menu").removeClass('toggled');
           $(".sidebar-menu").removeClass("active");
           $('body').unbind('click');
       }
       removeClass = true;
   });
});


$('button').click(function() {
   $(".top-bar .dropdown .dropdown-menu ").hide();
   $('body').unbind('click');
});




/**************** Modal Close onbody Click *************************/

$('.edit-task-modal').on('show.bs.modal', function(e) {
   $('body').addClass("example-open");
});



/**************************   Clear Add Deal form cache   ********************** */

function formReset() {
   document.getElementById("msform").reset();
};


/********************************   Add class on hover sidebar   *********************************/

$(".sidebar .aside-menu li.nav-link").hover(
  function () {
    $(this).addClass("show");
  },
  function () {
    $(this).removeClass("show");
  }
);


/********************************   Task Draggable   *********************************/
function drag(ev) {
   ev.dataTransfer.setData("text", ev.target.id);
}

function allowDrop(ev) {
   ev.preventDefault();
}

function drop(ev) {
   ev.preventDefault();
   var data = ev.dataTransfer.getData("text");
   ev.currentTarget.appendChild(document.getElementById(data));
}


/********************************   Add class on  sidebar mobile  *********************************/

// jQuery(document).ready(function($) {
//    var alterClass = function() {
//      var ww = document.body.clientWidth;
//      if (ww < 320) {
//        $('.report-sidebar').removeClass('close');
//      } else if (ww >= 768) {
//        $('.report-sidebar').addClass('close');
//      };
//    };
//    $(window).resize(function(){
//      alterClass();
//    });
//    //Fire it when the page first loads:
//    alterClass();
//  });



/********************************  Tooltip  *********************************/

$(function () {
   $('[data-toggle="tooltip"]').tooltip()
 });



 var toast = document.getElementById('toast-fix');

function closeToaster() {
	toast.classList.remove('open');
	toast.classList.add('close');
	// setTimeout(function(){
	// 	toast.classList.remove('close');
	// 	toast.classList.add('open');
	// }, 2000);
}

$('.modal').click(function(event){
   $(event.target).modal('hide');
});   
