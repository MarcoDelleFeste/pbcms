<form id="login-user" action="" method="post">
<label for="login-username">Indirizzo e-mail</label>
<input type="text" name="login-username" id="login-username" value="<?php echo $user->username; ?>" />
<label for="login-password">Password</label>
<input type="password" name="login-password" id="login-password" />
<input type="submit" value="Login" />
</form>