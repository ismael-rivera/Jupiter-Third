
<table>
<tr><td>

<?
/**
 * User not logged in, display the login form.
 * If user has already tried to login, but errors were
 * found, display the total number of errors.
 * If errors occurred, they will be displayed.
 */
if($form->num_errors > 0){
   echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
}
?>


<div class="container">

      <form action="process.php" id="login_form" class="form-signin" method="POST">
        <h2 class="form-signin-heading">Please sign in</h2>
<table align="left" border="0" cellspacing="0" cellpadding="3">        
        <tr>
            <td><input type="text" name="user" maxlength="30" class="input-block-level" placeholder="Username" value="<? echo $form->value("user"); ?>"></td>
            <td><? echo $form->error("user"); ?></td>
        </tr>
        <tr>
            <td><input type="password" name="pass" class="input-block-level" maxlength="30" value="<? echo $form->value("pass"); ?>" placeholder="Password"></td>
            <td><? echo $form->error("pass"); ?></td>
        </tr>
        <tr>
        <td colspan="2" align="left">
        <label class="checkbox">
          <input type="checkbox" value="remember-me" name="remember" <? if($form->value("remember") != ""){ echo "checked"; } ?>>Remember me
        </label>
        <input type="hidden" name="sublogin" value="1">
        <input class="btn btn-large btn-primary" type="submit" value="Sign in">
        </td>
        </tr>
        <tr>
        <td colspan="2" align="left">
        <p><font size="2">[<a href="forgotpass.php">Forgot Password?</a>]</font></p>
        </td>
        </tr> 
        <tr><td colspan="2" align="left"><br>Not registered? <a href="register.php">Sign-Up!</a>
        </td></tr>
        </table>
      </form>

    </div> <!-- /container -->


</td></tr>
</table>

<?php include('backend_footer.php'); ?>
