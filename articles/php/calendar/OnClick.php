<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Victor's click</title>
<link rel="stylesheet" type="text/css" href="calendar.css">
</head>
<body>
<? 
$arProp = array();
if (isset($_POST['filter'])) {$filter_set = 1;} else {$filter_set = 0;}
if (isset($_POST['SHOESTYPE'])) {$arProp['SHOESTYPE'] = $_POST['SHOESTYPE'];} else {$arProp['SHOESTYPE'] = "";}
if (isset($_POST['SIZETYPE'])) {$arProp['SIZETYPE'] = $_POST['SIZETYPE'];} else {$arProp['SIZETYPE'] = "";}
if (isset($_POST['MATERIALTOP'])) {$arProp['MATERIALTOP'] = $_POST['MATERIALTOP'];} else {$arProp['MATERIALTOP'] = "";}
if (isset($_POST['MATERIALBOTTOM'])) {$arProp['MATERIALBOTTOM'] = $_POST['MATERIALBOTTOM'];} else {$arProp['MATERIALBOTTOM'] = "";}
if (isset($_POST['SEASON'])) {$arProp['SEASON'] = $_POST['SEASON'];} else {$arProp['SEASON'] = "";}
if (isset($_POST['MANUFACTURER1'])) {$arProp['MANUFACTURER1'] = $_POST['MANUFACTURER1'];} else {$arProp['MANUFACTURER1'] = "";}
if (isset($_POST['MANUFACTURER2'])) {$arProp['MANUFACTURER2'] = $_POST['MANUFACTURER2'];} else {$arProp['MANUFACTURER2'] = "";}


// array_key_exists - ���������, ���������� �� � ������� ������ ������ ��� ����.
//$search_array = array("first" => 1, "second" => 4);
//if (array_key_exists("first", $search_array)) {
//    echo "The 'first' element is in the array";
//}
foreach ($arProp as $key => $value) {
    if (array_key_exists($key, $_POST)) {
    echo "The key ".$key." exists in the array POST<br>\n";
    }
}


print "<pre>"; print_r($_POST); print "</pre>";
?>
<!--<form name="test" action="<?php //echo $_SERVER['PHP_SELF']; ?>" method="POST">-->
<form method="POST" action="<? echo $_SERVER['PHP_SELF']; ?>" name="bfilter">
<table>
	<tr>
		<th colspan="2">������</th>
        </tr>
	<tr>
		<td>��� �����:</td>
		<td><select name="SHOESTYPE" size='4' >
				<option value="" selected><<�������>></option>
                                <option value="�������">�������</option>
                                <option value="��������">��������</option>
                                <option value="�������">�������</option>
		</select></td>
	</tr>
	<tr>
		<td>����������:</td>
		<td><select name="SIZETYPE">
				<option value="" selected><<�������>></option>
                                <option value="21-25">21-25</option>
                                <option value="21-28">21-28</option>
                                <option value="21-29">21-29</option>
		</select></td>
	</tr>
	<tr>
		<td>�������� �����:</td>
		<td>
			<select name="MATERIALTOP">
                                <option value="" selected><<�������>></option>
                                <option value="�����">�����</option>
                                <option value="������������� ����">������������� ����</option>
                                <option value="�����">�����</option>
                        </select>
		</td>
	</tr>
	<tr>
		<td>�������� ���������:</td>
		<td>
			<select name="MATERIALBOTTOM">
                                <option value="" selected><<�������>></option>
                                <option value="�����">�����</option>
                                <option value="����������� ���">����������� ���</option>
                        </select>
		</td>
	</tr>
        <tr>
		<td>�������������:</td>
		<td>
			<INPUT type="checkbox" value="Tico" name="MANUFACTURER1"><label> Tico</label>
                        <INPUT type="checkbox" value="Tiflani" name="MANUFACTURER2"><label> Tiflani</label>
 		</td>
	</tr>
        <tr>
        <td>�����:</td>
		<td><select name="SEASON" size='4' >
				<option value="" selected><<�������>></option>
                                <option value="�����-�����">�����-�����</option>
                                <option value="����">����</option>
                                <option value="����">����</option>
		</select></td>
        </tr>
        <tr>
		<th colspan="2">
			<input type="submit" name="filter" value="���������� ������">&nbsp;&nbsp;
			<input type="submit" name="del_filter" value="������� ������">
		</th>
	</tr>
</table>
</form>
</body>
</html>
