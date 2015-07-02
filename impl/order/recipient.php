<?php
require_once "../classes/Utils.class.php";
require_once "../classes/model/User.class.php";

$utils = new Utils();

if (!$utils->isLoggedIn() || !(isset($_SESSION["cart"]) && is_array($_SESSION["cart"]) && count($_SESSION["cart"]) > 0)) {
    header("Location: ../index.php");
}

$name = "";
$prename = "";
$streetNumber = "";
$zipLocation = "";

$user = $utils->getLoggedInUser();
if ($user != null) {
    $name = $user->getName();
    $prename = $user->getPrename();
    $streetNumber = $user->getStreet() . " " . $user->getStreetNumber();
    $zipLocation = $user->getZip() . " " . $user->getLocation();
}

require_once("../header.inc.php");

if (isset($_POST["post_recipient"])) {
    $name = $_POST["name"];
    $prename = $_POST["prename"];
    $streetNumber = $_POST["streetNumber"];
    $zipLocation = $_POST["zipLocation"];

    if (strlen($name) > 0 && strlen($prename) > 0 && strlen($streetNumber) > 0 && strlen($zipLocation) > 0) {
        $recipient = array();

        $recipient["name"] = $name;
        $recipient["prename"] = $prename;
        $recipient["streetNumber"] = $streetNumber;
        $recipient["zipLocation"] = $zipLocation;

        $_SESSION["recipient"] = $recipient;
        header("Location: order.php");
    }
}

?>
    <h1>Lieferadresse eingeben</h1>

    <form action="recipient.php" method="post">
        <table border="0" cellpadding="0" cellspacing="4">
            <tr>
                <td align="right">Name:</td>
                <td><input type="text" name="name" value="<?php echo $name; ?>"/></td>
            </tr>
            <tr>
                <td align="right">Vorname:</td>
                <td><input type="text" name="prename" value="<?php echo $prename; ?>"/></td>
            </tr>
            <tr>
                <td align="right">Straße:</td>
                <td><input type="text" name="streetNumber" value="<?php echo $streetNumber; ?>"/>
                </td>
            </tr>
            <tr>
                <td align="right">Ort:</td>
                <td><input type="text" name="zipLocation" value="<?php echo $zipLocation; ?>"/></td>
            </tr>
            <tr>
                <td/>
                <td>
                    <input type="submit" value="Weiter" name="post_recipient"/>
                </td>
            </tr>
        </table>
    </form>
<?php
require_once("../footer.inc.php");
?>