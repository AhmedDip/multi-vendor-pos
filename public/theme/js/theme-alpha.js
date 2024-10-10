let topbar = $('.top-bar')
let left_sidebar = $('.left-side-bar')
let logo_area = left_sidebar.children('#dashboard_link')
let inline_menu =$('.menu-inline')
$('.body-wrapper').prepend(topbar)
$('.main-content').children('.top-bar').remove()


topbar.children('.row').children('.col-lg-8').removeClass('col-lg-8').addClass('col-lg-4')
topbar.children('.row').prepend(logo_area).children('a').addClass('col-lg-4')
left_sidebar.children('.logo-area').remove()

inline_menu.children('i').removeClass('fa-chevron-right').addClass('fa-chevron-down').css({paddingLeft: '3px'})
