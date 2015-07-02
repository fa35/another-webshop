<?php

require_once "../../classes/Utils.class.php";

$error = "";
$title = "";
$value = "";
$replaceCount = 1;
$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}

require_once("../../header.inc.php");

if (isset($_POST['submit-add'])) {

    $title = $_POST["title"];
    $value = ($_POST["value"] == "") ? "" : (float)$_POST["value"];
    if (strlen($title) > 0) {
        if (is_float($value) && $value >= 0.0) {
            if ($utils->addVat($title, $value)) {
                header("Location: administration.php");
            } else {
                $error = "Fehler beim Anlegen des Mehrwertsteuersatzes.";
            }
        } else {
            $error = "Geben Sie den Satz ein.";
        }
    } else {
        $error = "Geben Sie die Bezeichnung ein.";
    }
}
?>

    <h1>Mehrwertsteuersatz hinzufügen</h1>
    <form action="add.php" method="post">
        <table border="0" cellpadding="0" cellspacing="4">
            <tr>
                <td align="right">Bezeichnung:</td>
                <td><input type="text" name="title" value="<?php echo $title; ?>"/></td>
            </tr>
            <tr>
                <td align="right">Satz:</td>
                <td><input type="number" name="value" step="0.01" value="<?php echo $value; ?>"/>%</td>
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