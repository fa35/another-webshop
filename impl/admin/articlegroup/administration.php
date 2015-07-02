<?php
require_once "../../classes/Utils.class.php";
$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}
require_once("../../header.inc.php");
?>
    <h1>Artikelgruppen verwalten</h1>
    <table>
        <tr>
            <th>
                Bezeichnung
            </th>
            <th colspan="2">
                Aktion
            </th>
        </tr>
        <?php
        require_once "../../classes/model/ArticleGroup.class.php";

        $articleGroups = $utils->getArticleGroups();
        if (is_array($articleGroups)) {
            foreach ($articleGroups as $key => $articleGroup) {
                echo "<tr>";
                echo "<td>" . $articleGroup->getTitle() . "</td>";
                echo "<form action=\"edit.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $articleGroup->getId() . "\"/>"
                    . "<input type=\"hidden\" name=\"title\" value=\"" . $articleGroup->getTitle() . "\"/>"
                    . "<input type=\"submit\" name=\"post-edit\" value=\"Bearbeiten\"/></td>"
                    . "</form>"
                    . "<form action=\"delete.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $articleGroup->getId() . "\"/>"
                    . "<input type=\"hidden\" name=\"title\" value=\"" . $articleGroup->getTitle() . "\"/>"
                    . "<input type=\"submit\" name=\"post-delete\" value=\"Löschen\"/></td>"
                    . "</form>";
                echo " </tr > ";
            }
        }
        ?>
    </table>
    <a href="add.php">Neue Artikelgruppe anlegen</a><br><br>
    <a href="../administration.php">Zurück</a>
<?php
require_once("../../footer.inc.php");
?>