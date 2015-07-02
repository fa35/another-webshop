<?php

require_once "../../classes/Utils.class.php";

$error = "";
$name = "";
$preName = "";
$email = "";
$id = 0;
$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}

require_once("../../header.inc.php");

if (isset($_POST['submit-delete'])) {
    $id = (integer)$_POST["id"];
    if ($utils->deleteUser($id)) {
        header("Location: administration.php");
    } else {
        $error = "Benutzer konnte nicht gelöscht werden.";
    }
}

if (isset($_POST['post-delete'])) {
    $id = (integer)$_POST["id"];
    $name = $_POST["name"];
    $preName = $_POST["prename"];
    $email = $_POST["email"];
}
?>

    <h1>Benutzer löschen</h1>
    Möchten Sie den Benutzer <?php echo $name . ", " . $preName . " <" . $email . "> wirklich löschen?"; ?>

    <form action="delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <input type="submit" value="Benutzer löschen" name="submit-delete"/>
    </form>
<?php
require_once("../../footer.inc.php");
if ($error != "") {
    echo "<script>alert(\"" . $error . "\");</script>";
}
?>