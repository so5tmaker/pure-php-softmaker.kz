$(document).ready(function(){ 
//    $("#psections").hide(); 
//    $("#pcategories").hide(); 
//    $("#pdata").hide(); 
//    $("#dele").click(function(){
//    alert("!!!")});
});
function mode(mode,link){
    $.ajax({
        type: "POST",
        url: link,
        data: {mode: mode}
   });
};

function changecomm(data, link){
    arr = data.split("-");
    id = arr[0]; // id комментария в таблице comments
    mode = arr[1]; // режим 1-удалить, 2-редактировать, 3-обновить
    if (mode==='2'){
        // запоминаю старое значение внутренностей div
        div_old = $('#' + id).html(); 
        // создаю поле для редактирования и добавляю в него текст комментария
        textarea = '<textarea id="edit_comment" cols="50" rows="5">'+$('#p' + id).html()+'</textarea>';
        // заменяю div на поле для редактирования      
        $('#' + id).html(textarea);
        // запоминаю старые кнопки удаления и редактирования
        btn_old = $( "#btn"+id ).html();
        // создаю новую кнопку и замещаю старые
        btn = '<input class="commentbtn" type="submit" value="Сохранить" onClick="changecomm(\''+id+'-3\',\''+link+'\')"><br/>';
        $( "#btn"+id ).html(btn);
//        alert(link);
        return true;
    }
    if (mode==='3'){
        // получаю изменненный текст из поля для редактирования
        text = $('#edit_comment').val();
        // вставляю старое значение внутренностей div
        $('#' + id).html(div_old);
        // добавляю новый текст комментария 
        $('#p' + id).html(text);
        // вставляю старые кнопки
        $( "#btn"+id ).html(btn_old);
//        data = data+'-\''+text+'\'';
        data = data+'-'+text;
//        data = data+'-777';
//        alert(link);
    }
    if (mode==='1'){
         
        answer = confirm("Bы действительно хотите удалить этот комментарий?");
        if (answer){
            $('#' + id).removeClass('post_div');
            $('#' + id).addClass('post_div_load');
        }else{
            return true;
        }
        
    }
    // посылаю post-запрос в link (view_post.php)
    $.ajax({
        type: "POST",
        url: link,
        data: {delcom: data},
        success: function() {
            if (mode==='1'){
                $( "#"+id ).remove();
                $( "#btn"+id ).remove();
            }
        }
   });
};
function changecomm1(){
    alert("!!!!!");
};

function post(area, toId, link, where, size){
    $.ajax({
            type: "POST",
            url: link,
            data: {area: area, where: where},
            success: function(data) {
                  $('#' + toId).html(data);  
                  if (size){
                    // получаю массив из количества элементов селекта
                    var dtt = jQuery('#' + toId + ' > option').toArray();
                    $("#" + toId).attr('size', dtt.length);
                  }
            }
       });
}

function miltiple(){
    var areas = ['sections', 'categories', 'data'];
    for (var i = 0; i < areas.length; i++){
        $("#" + areas[i]).attr('multiple', false);
        $("#" + areas[i]).attr('name', areas[i]);
        $("#" + areas[i]).attr('size', 1);
    }
}

function domult(nextarea, area){
    if (nextarea===area){
        $("#" + area).attr('multiple', true);
        $("#" + area).attr('name', area + '[]');
        return true;
    }
    return false;
}

function fill(id, link){
    var area, val = $('#'+id).val();
    area = $('#area').val();
    if (val==='empty'){ 
        return;
    }
    var nextarea;
    if (id==='area'){
        miltiple();
    }
    if (id==='area'){ 
        nextarea = 'sections';
        $("#pcategories").hide(); 
        $("#pdata").hide();
    }
    if (id==='sections' & area!=='sections'){
        nextarea = 'categories';
        $("#pdata").hide();
    }
    if (id==='categories' & area!=='categories'){
        nextarea = 'data';
    }
    var where = val;
    if (id==='area'){
        where = '';
    }
    post(nextarea,nextarea, link, where, domult(nextarea, area));
    $("#p" + nextarea).show();
    $('#value').attr('value', val);
};

function fillArea(page){
    var areas = {empty: '< Не выбрано >', sections : 'Секция', 
        categories : 'Категория', data : 'Статья'};
    var ret = '';
    if (page === 'articles'){
        for (var opt in areas) {
            ret = ret + "<option value='" + opt + "'>" + areas[opt] + "</option>"; 
        }   
    } else {
        ret = "<option value='empty'>< Не выбрано ></option>"; 
    }
    $('#area').html(ret);
}

function changePage(){
    var id = '#page', val = $(id).val();
    if (val[0] !== 'articles'){
        // получаю массив из количества элементов селекта
        var dtt = jQuery(id + ' > option').toArray();
        $(id).attr('multiple', true);
        $(id).attr('size', dtt.length);
    } else {
        $(id).attr('multiple', false);
        $(id).attr('size', 1);
    }
    fillArea(val[0]);
}