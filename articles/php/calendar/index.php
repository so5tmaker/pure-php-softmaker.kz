<html>
<head>
</head>

<body>
<form name="frmMain" method="post">
Date: <input type="text" name="txtDate" value="mm/dd/yyyy" size="15" maxlength="10"> 
<input type="button" name="cmdCal" value="Launch Calendar" onClick='javascript:window.open("calendar.php?form=frmMain&field=txtDate","","top=50,left=400,width=175,height=140,menubar=no,toolbar=no,scrollbars=no,resizable=no,status=no"); return false;'>
</form>
</body>
</html>