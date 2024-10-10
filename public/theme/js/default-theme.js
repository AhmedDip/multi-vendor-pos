
const handle_sidebar = (is_open, is_change = false) => {
    if (is_open == 2) {
        $('#side-bar-toggle').children('i').removeClass('fa-chevron-left').addClass('fa-chevron-right')
        setTimeout(()=>{
            $('#left_side_bar').css({
                width: '65px'
            })
        })

        $('.main-logo').css({display: "none"})
        $('#left_side_bar').children('nav').children('ul').children('li').css({borderBottom: "1px solid rgb(247 247 247)"})
        $('.menu-inline>a>span').css({display: "none", opacity:0})
        $('.sub-menu>li>.menu-inline>a>span').css('display', "contents")
        $('.single-li').children('a').children('span').css('display', "none")
        $('#main_content').css({
            width: 'calc(100% - 65px)'
        })
        $('.left-side-bar').css({padding: "0 10px"})

    } else {

        $('#side-bar-toggle').children('i').addClass('fa-chevron-left').removeClass('fa-chevron-right')
        $('#left_side_bar').css({
            width: '250px'
        })
        $('.main-logo').css({display: "unset"})
        setTimeout(()=>{
            $('.menu-inline>a>span').css({display: "unset", opacity:1})
        }, 250)
        $('#left_side_bar').children('nav').children('ul').children('li').css({borderBottom: "none"})
        $('.sub-menu>li>.menu-inline>a>span').css('display', "unset")
        $('.single-li').children('a').children('span').css('display', "unset")
        $('#main_content').css({
            width: 'calc(100% - 250px)'
        })
        $('.left-side-bar').css({padding: "0 20px"})
    }
}


let is_open = localStorage.is_open != undefined ? localStorage.is_open : 1 //open 2 = close
console.log(is_open, 'we')
handle_sidebar(is_open)
$('#side-bar-toggle').on('click', function () {
    is_open = localStorage.is_open //open 2 = close
    is_open = localStorage.is_open = is_open == 2 ? 1 : 2
    handle_sidebar(is_open, true)
})



