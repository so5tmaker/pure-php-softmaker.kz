
$(document).ready(function(){

    // hide #back-top first
    $("#back-top").hide();
    
    // fade in #back-top
    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').fadeOut();
            }
        });

        // scroll body to 0px on click
        $('#back-top a').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });
});

/* Скрипт для закрытия внешние ссылки от индексации*/
function GoTo(link, s){
    window.open(link.replace("_","http"+s+"://"));
}

/* Скрипт для создания pdf */
function pdf(link, name, rest){
    $('.pdf').html('<img src="'+rest+'/img/progress.gif">');
    $.ajax({
            type: "POST",
            url: rest+'/admin/pdf.php',
            data: {link: link, name: name},
            success: function(href) {
                $('.pdf').html('<a target=blank_ title= "Откроется в новом окне" href="'+href+'">СКАЧАТЬ ЗАМЕТКУ В PDF</a>');
//                window.location.href = href;
            }
       });

}
