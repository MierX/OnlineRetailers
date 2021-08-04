$(".tron").mouseover(function () {
    // 修改这个tr里每个td的背景色
    $(this).find('td').css('backgroundColor', '#DEE7F5');
}).mouseout(function () {
    $(this).find('td').css('backgroundColor', '#FFF');
});