<?php

require_once "../../classes/Utils.class.php";
require_once "../../classes/model/ArticleGroup.class.php";
require_once "../../classes/model/Vat.class.php";

$error = "";
$title = "";
$articleGroup = 0;
$nettoPrice = "";
$vat = 0;
$description = "";
$id = 0;
$picture = null;
$pictureName = null;
$deletePicture = false;

$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}

require_once("../../header.inc.php");

$articleGroups = $utils->getArticleGroups();
$vats = $utils->getVats();

if (isset($_POST['submit-edit'])) {

    $id = (integer)$_POST["id"];
    $title = $_POST["title"];
    $articleGroup = (integer)$_POST["articleGroup"];
    $nettoPrice = (integer)((float)$_POST["nettoPrice"] * 100.0);
    $vat = (integer)$_POST["vat"];
    $description = $_POST["description"];
    $picture = $_FILES["picture"];
    $deletePicture = isset($_POST["deletePicture"]) && ($_POST["deletePicture"] === "on" || $_POST["deletePicture"] === "true" || $_POST["deletePicture"] === "1" || $_POST["deletePicture"]);

    if (strlen($title) > 0) {
        if ($articleGroup > 0) {
            if ($nettoPrice >= 0) {
                if ($vat > 0) {
                    $pictureName = $utils->createArticleImage($picture);

                    if ($utils->editArticle($id, $title, $articleGroup, $nettoPrice, $vat, $description, $pictureName, $deletePicture)) {
                        header("Location: administration.php");
                    } else {
                        $error = "Artikel wurde nicht gefunden.";
                    }
                } else {
                    $error = "Geben Sie den Nettopreis ein.";
                }
            } else {
                $error = "Wählen Sie den Mehrwertsteuersatz aus.";
            }
        } else {
            $error = "Wählen Sie die Artikelgruppe aus.";
        }
    } else {
        $error = "Geben Sie die Bezeichnung ein.";
    }
}
if (isset($_POST['post-edit'])) {
    $id = (integer)$_POST["id"];
    $title = $_POST["title"];
    $articleGroup = (integer)$_POST["articleGroup"];
    $nettoPrice = (float)$_POST["nettoPrice"] / 100.0;
    $vat = (integer)$_POST["vat"];
    $description = $_POST["description"];
    $pictureName = $_POST["picture"];
}
?>
    <h1>Artikel hinzufügen</h1>
    <form action="edit.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="1000000"/>
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <table border="0" cellpadding="0" cellspacing="4">
            <tr>
                <td align="right">Bezeichnung:</td>
                <td><input type="text" name="title" value="<?php echo $title; ?>"/></td>
            </tr>
            <tr>
                <td align="right">Artikelgruppe:</td>
                <td><select name="articleGroup" size="5">
                        <?php
                        foreach ($articleGroups as $key => $_articleGroup) {
                            if ($articleGroup == $_articleGroup->getId()) {
                                echo "<option selected value=\"" . $_articleGroup->getId() . "\">" . $_articleGroup->getTitle() . "</option>";
                            } else {
                                echo "<option value=\"" . $_articleGroup->getId() . "\">" . $_articleGroup->getTitle() . "</option>";
                            }
                        }
                        ?>
                    </select></td>
            </tr>
            <tr>
                <td align="right">Nettopreis:</td>
                <td><input type="number" name="nettoPrice" step="0.01" min="0.0"
                           value=<?php echo "\"" . $nettoPrice . "\"/>" . CURRENCY; ?>
                           </td>
            </tr>
            <tr>
                <td align="right">Mehrwertsteuersatz:
                </td>
                <td><select name="vat" size="3">
                        <?php
                        foreach ($vats as $key => $_vat) {
                            if ($vat == $_vat->getId()) {
                                echo "<option selected value=\"" . $_vat->getId() . "\">" . $_vat->getTitle() . "(" . $_vat->getValue() . "%)</option>";
                            } else {
                                echo "<option value=\"" . $_vat->getId() . "\">" . $_vat->getTitle() . "(" . $_vat->getValue() . "%)</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Beschreibung:</td>
                <td><textarea name="description" cols="30" rows="5"
                              wrap="hard"><?php echo $description; ?></textarea>
                </td>
            </tr>
            <tr>
                <td align="right">Bild:</td>
                <td>
                    <?php
                    if ($pictureName != null) {
                        echo "<img class=\"articleImage\" src=\"" . "../../pictures/thumb_" . $pictureName . "\"  onclick=\"showImage('../../pictures/" . $pictureName . "');\"/>";
                        echo "</td></tr><tr><td></td><td>";
                        echo "<input type=\"checkbox\" name=\"deletePicture\" />Bild löschen";
                        echo "</td></tr><tr><td></td><td>";
                    }
                    ?>
                    <input type="file" accept="image/jpeg; image/png" name="picture"
                           id="picture_id"/>
                </td>
            </tr>
            <tr>
                <td></td>
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