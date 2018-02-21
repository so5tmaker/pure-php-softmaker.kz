<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
      <style type="text/css">
       
      </style>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  </head>
  <body>
    <?php //43�20' 77�00'- ��������� ������
    //  43�12' - North + 76�52' - East +  -  ��� ������� � �������
    $date = strtotime("2011-04-17");
    $latitude  = 43.12;
    $longitude = 76.52;
    $sun_info = date_sun_info($date, $latitude, $longitude);
    foreach ($sun_info as $key => $val) {           // ��������� ������
        echo "$key: " . date("H:i:s", $val) . "<br>\n";
    }
    ?>
    <?php
    /* calculate the sunset time for Lisbon, Portugal
    Latitude: 43�15' - 43.15 North +
    Longitude: 76�57' - 76.57 East +
    Zenith ~= 90
    offset: +6 GMT
    */
    echo date("D M d Y"). ', sunset time : ' .date_sunset($date, SUNFUNCS_RET_STRING, $latitude, $longitude, 90, 6);
    ?>
    <?
    
    $sunrise = date_sunrise($date, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude);
    $sunset  = date_sunset($date, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude);

    echo 'Sunrise: '.date('H:i:s', $sunrise);
    echo '<br>';
    echo 'Sunset: '.date('H:i:s', $sunset);
    echo '<br>';

    $day_length   = $sunset - $sunrise;
    echo date("H:i:s", $day_length);
?>
<?

$sun_info = date_sun_info($date, $latitude, $longitude);

foreach ($sun_info as $key => $val) {

    if ($key == 'sunrise') {

        $sunrise = $val;
        echo '<br>������: '.date("H:i:s", $sunrise).'<br>';

    }

    if ($key == 'sunset') {

        $sunset = $val;
        $day_length   = $sunset - $sunrise;
        echo '<br>�����: '.date("H:i:s", $sunset).'<br>';
        echo '������� ���: '.gmdate("H:i:s", $day_length).'<br>';

    }

    if ($key == 'transit') {
        echo '� ������: '.date("H:i:s", $val).'<br>';
    }

    if ($key == 'civil_twilight_begin') {
        echo '������ �������� �������: '.date("H:i:s", $val).'<br>';
    }

    if ($key == 'civil_twilight_end') {
        echo '����� �������� �������: '.date("H:i:s", $val).'<br>';
    }

}

?>
  </body>
</html>