<?php

require_once "../../classes/Utils.class.php";

$error = "";
$title = "";
$value = 0.0;
$id = 0;
$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}

require_once("../../header.inc.php");

if (isset($_POST['submit-delete'])) {
    $id = (integer)$_POST["id"];
    if ($utils->deleteVat($id)) {
        header("Location: administration.php");
    } else {
        $error = "Mehrwertsteuersatz konnte nicht gelöscht werden.";
    }
}

if (isset($_POST['post-delete'])) {
    $id = (integer)$_POST["id"];
    $title = $_POST["title"];
    $value = (float)$_POST["value"];
}
?>

    <h1>Mehrwertsteuersatz löschen</h1>
    Möchten Sie den
    Mehrwertsteuersatz <?php echo $title . " (" . number_format($value, 2, ',', '.') . "%) wirklich löschen?"; ?>

    <form action="delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <input type="submit" value="Mehrwertsteuersatz löschen" name="submit-delete"/>
    </form>
<?php
require_once("../../footer.inc.php");
if ($error != "") {
    echo "<script>alert(\"" . $error . "\");</script>";
}
?>