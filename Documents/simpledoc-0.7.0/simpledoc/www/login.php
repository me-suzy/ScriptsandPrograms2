<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | login.php                                                          |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

require 'shared/prepend.php';

$username = post('username');
$password = post('password');
$remember = post('remember');

if ($username && $password) {
    if ($username == $CONFIG['username'] && $password == $CONFIG['password']) {
        setcookie('username', $username, $remember ? time()+3600*24*365 : 0);
        setcookie('password', md5($password), $remember ? time()+3600*24*365 : 0);
        redirect('index.php');
    }
}

?>
<?php
$TITLE = 'Login';
include ROOT.'/shared/header.tpl';
?>

    <h1>SimpleDoc - administration panel</h1>

    <?php if ($username && $password) echo '<p class="error">Wrong username or password</p>'; ?>

    <p>
        <form action="login.php" method="post">
        <table>
        <tr>
            <td>Username:</td>
            <td><input type="text" name="username" value="<?php echo $username; ?>"></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" value=""></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="login" onclick="return validateForm(this.form);">
                <input type="checkbox" name="remember" value="1" <?php if ($remember) echo 'checked="checked"'; ?>>
                remember me
            </td>
        </tr>
        </table>
        </form>

        <script type="text/javascript" src="shared/form.js"></script>
        <script type="text/javascript">
        function validateForm(form) {
            var username = form.elements['username'];
            var password = form.elements['password'];
            username.value = username.value.trim();
            password.value = password.value.trim();
            if (!username.value.length) { alert("Username is empty"); return false; }
            if (!password.value.length) { alert("Password is empty"); return false; }
            return true;
        }
        </script>
    </p>

<?php
include ROOT.'/shared/footer.tpl';
?>