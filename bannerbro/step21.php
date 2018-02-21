<?php include 'header.php'; include 'bro.php'; include 'admin/try_function.php'; ?>
<script>
$(function() {
    if ($.browser.msie && $.browser.version.substr(0,1)<7)
    {
      $('.tooltip').mouseover(function(){
            $(this).children('span').show();
          }).mouseout(function(){
            $(this).children('span').hide();
          })
    }
  });
</script> 
<title>Оформление заказа ШАГ 2</title>
</head>
<body>
<div class="wrapper">
    <div class="steps_container">
        <table>
            <tr>
                <td >Шаг 1</td>
                <td class="active border">Шаг 2</td>
                <td>Шаг 3</td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>
<?php
error_set();
step21();
?>
<div class="clear"></div>
</div>
</div>
<?php include 'copyright.php'; ?>
<script>
function bannerbroUrl(url) {
window.open(url);
}
</script>
</body>
</html>