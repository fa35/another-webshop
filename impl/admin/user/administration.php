<?php
require_once "../../classes/Utils.class.php";

$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}

require_once("../../header.inc.php");
?>
    <h1>Benutzer verwalten</h1>
    <table>
        <tr>
            <th>
                e-Mail
            </th>
            <th>
                Name
            </th>
            <th>
                Vorname
            </th>
            <th colspan="2">
                Aktion
            </th>
        </tr>
        <?php
        require_once "../../classes/model/User.class.php";

        $users = $utils->getUsers();
        if (is_array($users)) {
            foreach ($users as $key => $user) {
                echo "<tr>";
                echo "<td>" . $user->getEmail() . "</td>";
                echo "<td>" . $user->getName() . "</td>";
                echo "<td>" . $user->getPrename() . "</td>";
                echo "<form action=\"edit.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $user->getId() . "\"/>"
                    . "<input type=\"hidden\" name=\"name\" value=\"" . $user->getName() . "\"/>"
                    . "<input type=\"hidden\" name=\"prename\" value=\"" . $user->getPrename() . "\"/>"
                    . "<input type=\"hidden\" name=\"street\" value=\"" . $user->getStreet() . "\"/>"
                    . "<input type=\"hidden\" name=\"streetNumber\" value=\"" . $user->getStreetNumber() . "\"/>"
                    . "<input type=\"hidden\" name=\"zip\" value=\"" . $user->getZip() . "\"/>"
                    . "<input type=\"hidden\" name=\"location\" value=\"" . $user->getLocation() . "\"/>"
                    . "<input type=\"hidden\" name=\"email\" value=\"" . $user->getEmail() . "\"/>"
                    . "<input type=\"hidden\" name=\"isAdmin\" value=\"" . $user->getIsAdmin() . "\"/>"
                    . "<input type=\"submit\" name=\"post-edit\" value=\"Bearbeiten\"/></td>"
                    . "</form>"
                    . "<form action=\"delete.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $user->getId() . "\"/>"
                    . "<input type=\"hidden\" name=\"name\" value=\"" . $user->getName() . "\"/>"
                    . "<input type=\"hidden\" name=\"prename\" value=\"" . $user->getPrename() . "\"/>"
                    . "<input type=\"hidden\" name=\"email\" value=\"" . $user->getEmail() . "\"/>"
                    . "<input type=\"submit\" name=\"post-delete\" value=\"Löschen\"/></td>"
                    . "</form>";
                echo " </tr > ";
            }
        }
        ?>
    </table>
    <a href="../../user/register.php">Neuer Benutzer anlegen</a><br><br>
    <a href="../administration.php">Zurück</a>
<?php
require_once("../../footer.inc.php");
?>