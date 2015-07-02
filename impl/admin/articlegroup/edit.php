<?php

require_once "../../classes/Utils.class.php";

$error = "";
$title = "";
$id = 0;
$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}

require_once("../../header.inc.php");

if (isset($_POST['submit-edit'])) {

    $id = (integer)$_POST["id"];
    $title = $_POST["title"];
    if (strlen($title) > 0) {
        $util = new Utils();
        if ($util->editArticleGroup($id, $title)) {
            header("Location: administration.php");
        } else {
            $error = "Artikelgruppe wurde nicht gefunden.";
        }
    } else {
        $error = "Geben Sie die Bezeichnung ein.";
    }
}
if (isset($_POST['post-edit'])) {
    $id = (integer)$_POST["id"];
    $title = $_POST["title"];
}
?>

    <h1>Artikelgruppe bearbeiten</h1>
    <form action="edit.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <table border="0" cellpadding="0" cellspacing="4">
            <tr>
                <td align="right">Bezeichnung:</td>
                <td><input type="text" name="title" value="<?php echo $title; ?>"/></td>
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
if ($error != "") {
    require_once("../../footer.inc.php");
    echo "<script>alert(\"" . $error . "\");</script>";
}
?>