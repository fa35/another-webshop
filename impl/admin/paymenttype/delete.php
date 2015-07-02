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

if (isset($_POST['submit-delete'])) {
    $id = (integer)$_POST["id"];
    if ($utils->deletePaymentType($id)) {
        header("Location: administration.php");
    } else {
        $error = "Zahlungsart konnte nicht gelöscht werden.";
    }
}

if (isset($_POST['post-delete'])) {
    $id = (integer)$_POST["id"];
    $title = $_POST["title"];
}
?>

    <h1>Zahlungsart löschen</h1>
    Möchten Sie die Artikelgruppe <?php echo $title . " wirklich löschen?"; ?>

    <form action="delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <input type="submit" value="Zahlungsart löschen" name="submit-delete"/>
    </form>
<?php
require_once("../../footer.inc.php");
if ($error != "") {
    echo "<script>alert(\"" . $error . "\");</script>";
}
?>