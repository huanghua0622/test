$.fn.tanchu = function(msg){
    var o = $(this);

    var div = $('<div></div>');
    div.text(msg);
    var body = $('body');
    var left = ($(window).width() - 150) / 2;
    var top = ($(window).height() - 60) / 2;
    //alert(left)
    div.appendTo(body);
    div.css('background-color', 'black')
        .css('opacity', 0.8)
        .css('color', '#FFFFFF')
        .css('width', '150px')
        .css('height', '60px')
        .css('line-height', '60px')
        .css('text-align', 'center')
        .css('position', 'absolute')
        .css('left', left)
        .css('top', top);

    setTimeout(function(){
        div.fadeOut(1000);
    },500)

    return o;
}