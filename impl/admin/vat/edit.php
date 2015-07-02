<?php

require_once "../../classes/Utils.class.php";

$error = "";
$title = "";
$value = "";
$id = 0;
$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}

require_once("../../header.inc.php");

if (isset($_POST['submit-edit'])) {

    $id = (integer)$_POST["id"];
    $title = $_POST["title"];
    $value = ($_POST["value"] == "") ? "" : (float)$_POST["value"];
    if (strlen($title) > 0) {
        if (is_float($value) && $value >= 0.0) {
            if ($utils->editVat($id, $title, $value)) {
                header("Location: administration.php");
            } else {
                $error = "Mehrwertsteuersatz wurde nicht gefunden.";
            }
        } else {
            $error = "Geben Sie den Satz ein.";
        }
    } else {
        $error = "Geben Sie die Bezeichnung ein.";
    }
}
if (isset($_POST['post-edit'])) {
    $id = (integer)$_POST["id"];
    $title = $_POST["title"];
    $value = (float)$_POST["value"];
}
?>

    <h1>Mehrwertsteuersatz bearbeiten</h1>
    <form action="edit.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
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
                    <input type="submit" value="Übernehmen" name="submit-edit"/>
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