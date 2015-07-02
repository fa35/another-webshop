<?php
require_once "../../classes/Utils.class.php";

$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}

require_once("../../header.inc.php");
?>
    <h1>Mehrwertsteuersätze verwalten</h1>
    <table>
        <tr>
            <th>
                Bezeichnung
            </th>
            <th>
                Satz
            </th>
            <th colspan="2">
                Aktion
            </th>
        </tr>
        <?php
        require_once "../../classes/model/Vat.class.php";

        $vats = $utils->getVats();
        if (is_array($vats)) {
            foreach ($vats as $key => $vat) {
                echo "<tr>";
                echo "<td>" . $vat->getTitle() . "</td>";
                echo "<td>" . number_format($vat->getValue(), 2, ',', '.') . "%</td>";
                echo "<form action=\"edit.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $vat->getId() . "\"/>"
                    . "<input type=\"hidden\" name=\"title\" value=\"" . $vat->getTitle() . "\"/>"
                    . "<input type=\"hidden\" name=\"value\" value=\"" . $vat->getValue() . "\"/>"
                    . "<input type=\"submit\" name=\"post-edit\" value=\"Bearbeiten\"/></td>"
                    . "</form>"
                    . "<form action=\"delete.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $vat->getId() . "\"/>"
                    . "<input type=\"hidden\" name=\"title\" value=\"" . $vat->getTitle() . "\"/>"
                    . "<input type=\"hidden\" name=\"value\" value=\"" . $vat->getValue() . "\"/>"
                    . "<input type=\"submit\" name=\"post-delete\" value=\"Löschen\"/></td>"
                    . "</form></td>";
                echo " </tr > ";
            }
        }
        ?>
    </table>
    <a href="add.php">Neuer Mehrwertsteuersatz anlegen</a><br><br>
    <a href="../administration.php">Zurück</a>
<?php
require_once("../../footer.inc.php");
?>