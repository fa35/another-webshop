<?php

require_once "../classes/Utils.class.php";

$error = "";
$username = "";
$password = "";

require_once("../header.inc.php");

//check to see if they've submitted the login form
if (isset($_POST['submit-login'])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $utils = new Utils();
    if ($utils->login($username, $password)) {
        header("Location: " . $_SERVER["CONTEXT_PREFIX"] . "/index.php");
    } else {
        $error = "Falscher Benutzername oder Passwort. Bitte versuche Sie es nochmal.";
    }
}
?>
<h1>Login</h1>
<form action="login.php" method="post">
    <table border="0" cellpadding="0" cellspacing="4">
        <tr>
            <td align="right">Benutzername:</td>
            <td><input type="text" name="username" value="<?php echo $username; ?>"/></td>
        </tr>
        <tr>
            <td align="right">Passwort:</td>
            <td><input type="password" name="password" value="<?php echo $password; ?>"/></td>
        </tr>
        <tr>
            <td/>
            <td>
                <input type="submit" value="Login" name="submit-login"/>
            </td>
        </tr>
    </table>
</form>
<?php
require_once("../footer.inc.php");
if ($error != "") {
    echo "<script>alert(\"" . $error . "\");</script>";
}
?>

