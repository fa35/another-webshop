<?php
require_once "../../classes/Utils.class.php";
$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}
require_once("../../header.inc.php");
?>
    <h1>Zahlungsarten verwalten</h1>
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
        require_once "../../classes/model/PaymentType.class.php";

        $paymentTypes = $utils->getPaymentTypes();
        if (is_array($paymentTypes)) {
            foreach ($paymentTypes as $key => $paymentType) {
                echo "<tr>";
                echo "<td>" . $paymentType->getTitle() . "</td>";
                echo "<form action=\"edit.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $paymentType->getId() . "\"/>"
                    . "<input type=\"hidden\" name=\"title\" value=\"" . $paymentType->getTitle() . "\"/>"
                    . "<input type=\"submit\" name=\"post-edit\" value=\"Bearbeiten\"/></td>"
                    . "</form>"
                    . "<form action=\"delete.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $paymentType->getId() . "\"/>"
                    . "<input type=\"hidden\" name=\"title\" value=\"" . $paymentType->getTitle() . "\"/>"
                    . "<input type=\"submit\" name=\"post-delete\" value=\"Löschen\"/></td>"
                    . "</form>";
                echo " </tr > ";
            }
        }
        ?>
    </table>
    <a href="add.php">Neue Bezahlungsart anlegen</a><br><br>
    <a href="../administration.php">Zurück</a>
<?php
require_once("../../footer.inc.php");
?>