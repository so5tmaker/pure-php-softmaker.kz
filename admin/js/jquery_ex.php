<html>
<head>
<? include ("lock.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<script src="../js/jquery-1.8.2.min.js"></script>
<script type="text/javascript">
function typeOfPage(cId){
    var page = $('#'+cId).val();
//    var page, cId;
//       $('.check').on('change', function(){
//            page = $(this).val(),
//            cId = $(this).attr('id');
//            if(cVal.length > 2){
//                // ��� ��� ajax-������,
//                // � �� ������ ������ ������
//                $('#output').html('<p>��������� ������ �� ������: ' + params[cId].url + '</p>');
////                // ��� ������� ���������� - �������� ��� ������ ������
//                params[cId].func();
//            }
//        });
//        page = $('#'+cId).val();
               $.ajax({
                            type: "POST",
                            url: "<?php
                            if (strrchr($_SERVER['REQUEST_URI'], "?")) echo $_SERVER['REQUEST_URI']."&ajax";
                            else echo $_SERVER['REQUEST_URI']."?ajax";?>",
                            data: {page: page},
                            success: function(data) {
                                  $('#ajax_reciever').html(data);
                            }
           });
};
</script>
</head>
<body  onload="typeOfPage();">
<?php
header('Content-type: text/html; charset="windows-1251"');
if (!isset($_GET['ajax'])){
?>
<form method="post" >
<select name="page" id="page_type" onchange="typeOfPage('page_type');">
<option value="pages">��������</option>
<option value="goods">�����</option>
</select>
</form>
    <div class="check" id="ajax_reciever"></div>
<?php }
else {
        if (isset($_POST['page'])){
                if($_POST['page']=='goods'){
                    $q = '�����';
                    echo $q;
                }
                if($_POST['page']=='pages') echo "��������";
        }
}
?>
</body>
</html>