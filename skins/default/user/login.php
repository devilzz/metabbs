<? if (isset($flash)) { ?>
<p><?=$flash?></p>
<? } ?>
<form method="post">
<p>
	<label>ID:</label>
	<input type="text" name="user" />
</p>
<p>
	<label>Password:</label>
	<input type="password" name="password" />
</p>
<p><input type="submit" value="Login" /> <a href="<?=url_for('user', 'signup')?>">Sign up</a></p>
</form>