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
    if ($utils->deleteArticle($id)) {
        header("Location: administration.php");
    } else {
        $error = "Artikel konnte nicht gelöscht werden.";
    }
}

if (isset($_POST['post-delete'])) {
    $id = (integer)$_POST["id"];
    $title = $_POST["title"];
}
?>

    <h1>Artikel löschen</h1>
    Möchten Sie die Artikel <?php echo $title . " wirklich löschen?"; ?>

    <form action="delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <input type="submit" value="Artikel löschen" name="submit-delete"/>
    </form>
<?php
require_once("../../footer.inc.php");
if ($error != "") {
    echo "<script>alert(\"" . $error . "\");</script>";
}
?>