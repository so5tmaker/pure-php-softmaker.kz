<form action="welcome.php" method="post">
<p align="center" class="inputtext"><? echo get_foreign_equivalent("ID пользователя"); ?>:<br >
<input name="id" type="text" id="id" maxlength="20" class="forminput" value=""></p>

<p align="center" class="inputtext"><? echo get_foreign_equivalent("Код активации"); ?>:<br >
<input name="uid" type="text" id="uid" class="forminput" maxlength="40" value=""></p>

<input name="CODE" id="CODE" type="hidden" value="05">
<input name="error" id="error" type="hidden" value="Registration">
<p align="center"><input type="submit" name="submit-activation" value="<? echo get_foreign_equivalent("Активировать"); ?>" class="formbutton" ></p>
</form>