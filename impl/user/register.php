<?php

require_once "../classes/Utils.class.php";

$error = "";
$name = "";
$preName = "";
$street = "";
$streetNumber = "";
$zip = "";
$location = "";
$email = "";
$password = "";
$passwordConfirmation = "";
$isAdmin = false;

$utils = new Utils();

require_once("../header.inc.php");

if (isset($_POST['submit-register'])) {

    $name = $_POST["name"];
    $preName = $_POST["prename"];
    $street = $_POST["street"];
    $streetNumber = $_POST["streetNumber"];
    $zip = $_POST["zip"];
    $location = $_POST["location"];
    $email = $_POST["email"];
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $password = $_POST["password"];
        $passwordConfirmation = $_POST["passwordConfirmation"];

        if (strlen($password) > 0 && strlen($passwordConfirmation) > 0 && $password === $passwordConfirmation) {
            $passwordHash = hash("sha256", $password, false);

            $isAdmin = isset($_POST["isAdmin"]) && ($_POST["isAdmin"] === "on" || $_POST["isAdmin"] === "true" || $_POST["isAdmin"] === "1" || $_POST["isAdmin"]);

            if ($utils->registerUser($name, $preName, $street, $streetNumber, $zip, $location, $email, $passwordHash, $isAdmin)) {
                header("Location: /index.php");
            } else {
                $error = "Benutzername ist schon vergeben.";
            }
        } else {
            $error = "Geben Sie ein Passwort ein und bestätigen Sie es.";
        }
    } else {
        $error = "Bitte geben Sie ihre Email-Adresse ein.";
    }
}
?>
    <h1>Benutzerregistrierung:</h1>
    <form action="register.php" method="post">
        <table border="0" cellpadding="0" cellspacing="4">
            <tr>
                <td align="right">Name:</td>
                <td><input type="text" name="name" value="<?php echo $name; ?>"/></td>
            </tr>
            <tr>
                <td align="right">Vorname:</td>
                <td><input type="text" name="prename" value="<?php echo $preName; ?>"/></td>
            </tr>
            <tr>
                <td align="right">Straße:</td>
                <td><input type="text" name="street" value="<?php echo $street; ?>"/>
                    <input type="text" name="streetNumber" value="<?php echo $streetNumber; ?>"/></td>
            </tr>
            <tr>
                <td align="right">Ort:</td>
                <td><input type="text" name="zip" value="<?php echo $zip; ?>" maxlength="10"/>
                    <input type="text" name="location" value="<?php echo $location; ?>"/></td>
            </tr>
            <tr>
                <td align="right">e-Mail:</td>
                <td><input type="text" name="email" value="<?php echo $email; ?>"/></td>
            </tr>
            <tr>
                <td align="right">Passwort:</td>
                <td><input type="password" name="password" value="<?php echo $password; ?>"/></td>
            </tr>
            <tr>
                <td align="right">Bestätigung:</td>
                <td><input type="password" name="passwordConfirmation"
                           value="<?php echo $passwordConfirmation; ?>"/></td>
            </tr>
            <?php
            if ($utils->isAdmin()) {
                echo "<tr>";
                echo "<td align=\"right\">Administrator:</td>";
                echo "<td><input type=\"checkbox\" name=\"isAdmin\"";
                echo "checked=\"" . ($isAdmin) ? "on" : "off" . "\"/>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
            <tr>
                <td/>
                <td>
                    <input type="submit" value="Registrieren" name="submit-register"/>
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