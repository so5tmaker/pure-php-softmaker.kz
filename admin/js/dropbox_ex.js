$(document).ready(function() {
  var areas;
/*
�������� ������ �� ����� listUsers.php
*/
  $.getJSON("../listUsers.php", function(json) {
        areas = eval(json);
      });

  $("#area").onchange(function() {
/*
������� ������
*/
    $("#value").html("");
/*
setTimeout ��������� ������ ��� ���� ����� ������ ������������. ������� ��������� ������ �������������,
� ����� ������ ����������� ��������� ������.
*/
//    setTimeout(function() {
      for (i = 0; i < areas.length; i++) {
        slovo = $("#area").val();
/*
����� �� ���������� ��������� �������  � ����� �� ����������� ��������� ��������  ����� � �������.
��� ��� ��������� � ���� �������.
*/

/*
�� � ������� �� ����� ������������.
*/
          $("<option value='" + areas[i].name 
              + "'>" + areas[i].title
              + "</option>")
              .appendTo("#value");
        }

//    }, 10);
  });
});