{include file="head.tpl"}
<body>
<div id="container">
    <div id="header">
		<h1>{$title}</h1>
    </div>
    <div id="wrapper">
        <div id="content" style="padding-left:10px; padding-top:10px;">
<form>
<table border="0">
<tr>
	<td>Username:</td>
	<td><input type="text" name="username" /></td>
</tr>
<tr>
	<td>Email:</td>
	<td><input type="email" /></td>
</tr>
<tr>
	<td>Passwort:</td>
	<td><input type="password" /></td>
</tr>
<tr>
	<td><input type="reset" value="Zurücksetzen" name="reset"/></td>
	<td><input type="submit" value="Absenden" name="submit"/></td>
</tr>
</table>

</form>
        </div>
    </div>
{include file="navigation.tpl"}