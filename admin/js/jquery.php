
<? 
header('Content-type: text/html; charset="windows-1251"');
include ("../lock.php"); 
$tbla[sec] = 'sections';
$tbla[cat] = 'categories';
$tbla[id] = 'data';
$area = filter_input(INPUT_POST, 'area');
if (isset($area)){
    $result = $db1->select($tbla[$area], "id<>0 ORDER BY title ASC", 'name, title');
    foreach($result as $k => $area) {
        $value .= "<option value='$area[name] 
        '>$area[title]
        </option>";
    }
    echo $value;
}
?>
