<?php

require_once "../../classes/Utils.class.php";

$error = "";
$title = "";
$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}

require_once("../../header.inc.php");

if (isset($_POST['submit-add'])) {

    $title = $_POST["title"];
    if (strlen($title) > 0) {
        if ($utils->addPaymentType($title)) {
            header("Location: administration.php");
        } else {
            $error = "Fehler beim Anlegen der Zahlungsart.";
        }
    } else {
        $error = "Geben Sie die Bezeichnung ein.";
    }
}
?>
    <h1>Zahlungsart hinzufügen</h1>
    <form action="add.php" method="post">
        <table border="0" cellpadding="0" cellspacing="4">
            <tr>
                <td align="right">Bezeichnung:</td>
                <td><input type="text" name="title" value="<?php echo $title; ?>"/></td>
            </tr>
            <tr>
                <td/>
                <td>
                    <input type="submit" value="Hinzufügen" name="submit-add"/>
                </td>
            </tr>
        </table>
    </form>
<?php
require_once("../../footer.inc.php");
if ($error != "") {
    echo "<script>alert(\"" . $error . "\");</script>";
}
?>