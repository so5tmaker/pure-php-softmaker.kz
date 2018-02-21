$(document).ready(function() {
  var areas;
/*
получаем данные из файла listUsers.php
*/
  $.getJSON("../listUsers.php", function(json) {
        areas = eval(json);
      });

  $("#area").onchange(function() {
/*
очищаем список
*/
    $("#value").html("");
/*
setTimeout необходим потому что имел место эффект запаздывания. Сначала выбирался список пользователей,
а потом только определялся введенный символ.
*/
//    setTimeout(function() {
      for (i = 0; i < areas.length; i++) {
        slovo = $("#area").val();
/*
Здесь мы сравниваем введенные символы  с таким же количеством начальных символов  имени и фамилии.
Все это приведено в один регистр.
*/

/*
Ну и выводим на экран пользователя.
*/
          $("<option value='" + areas[i].name 
              + "'>" + areas[i].title
              + "</option>")
              .appendTo("#value");
        }

//    }, 10);
  });
});