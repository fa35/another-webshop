<?php
require_once "../classes/Utils.class.php";
require_once("../header.inc.php");

$utils = new Utils();
if (!$utils->isLoggedIn()) {
    header("Location: ../index.php");
}

if ($utils->isAdmin() && isset($_POST['sumbit_payed'])) {
    $id = (integer)$_POST["id"];
    $payed = isset($_POST["payed"]) && ($_POST["payed"] === "on" || $_POST["payed"] === "true" || $_POST["payed"] === "1" || $_POST["payed"]);

    $utils->setPaymentOfOrder($id, $payed);
}

$orders = array();
$users = array();
if ($utils->isAdmin()) {
    $orders = $utils->getOrders();
    $users = $utils->getUsers();
} else {
    $orders = $utils->getOrders($utils->getLoggedInUserId());
}
$articles = $utils->getAllArticles();
$vats = $utils->getVats();
$paymentTypes = $utils->getPaymentTypes();

?>
<h1>Bestellungen</h1>

<?php
if (count($orders) == 0) {
    echo "Keine Bestellungen vorliegend.";
} else {
    foreach ($orders as $_orderKey => $_order) {

        echo "Bestellung vom " . Datetime::createFromFormat("Y-d-m H:i:s", $_order->getDate())->format("d.m.Y, H:i") . " Uhr<br>";

        if ($utils->isAdmin()) {
            $user = $utils->getElementWithId($users, $_order->getUser());
            echo "Kunde: " . $user->getName() . ", " . $user->getPrename() . " (" . $user->getEmail() . ")<br>";
        }

        $paymentType = $utils->getElementWithId($paymentTypes, (integer)$_order->getPaymentType());

        echo "Zahlungsart: " . ($paymentType != null ? $paymentType->getTitle() : "unbekannt");

        $payed = (boolean)$_order->getPayed();
        if ($utils->isAdmin()) {
            echo "<form action=\"overview.php\" method=\"post\">";
            echo "<input type=\"hidden\" name=\"id\" value=\"" . $_order->getId() . "\"/>";
            echo "<input type=\"checkbox\" name=\"payed\" " . ($payed ? "checked=\"on\"" : "") . "/>";
            echo "<input type=\"submit\" name=\"sumbit_payed\" value=\"Ist bezahlt\"/>";
            echo "</form>";
        } else {
            echo ", " . ($payed ? "Zahlung eingangen" : "Zahlung noch nicht eingangen");
        }

        $orderDetails = $utils->getOrderDetails($_order->getId());
        echo "<table><tr><th>Artikel</th><th>Umsatzsteuer</th><th>Menge</th><th>Einzelpreis</th><th>Gesamtpreis</th></tr>";

        $sumNetto = 0.0;
        $sumPrice = 0.0;
        $sumVats = array();
        foreach ($orderDetails as $_detailKey => $_orderDetail) {
            $article = $utils->getElementWithId($articles, $_orderDetail->getArticle());
            $vat = $utils->getElementWithId($vats, $_orderDetail->getVat());
            $amount = (integer)$_orderDetail->getAmount();
            $price = $utils->calculateBruttoPrice($_orderDetail->getNettoPrice(), $_orderDetail->getVat());
            $sumPrice += (float)$price;
            $sumNetto += ((float)$_orderDetail->getNettoPrice() / 100.0);

            if (isset($sumVats[$_orderDetail->getVat()])) {
                $sumVats[$_orderDetail->getVat()] += (float)$utils->calculateVat($_orderDetail->getNettoPrice(), $_orderDetail->getVat());
            } else {
                $sumVats[$_orderDetail->getVat()] = (float)$utils->calculateVat($_orderDetail->getNettoPrice(), $_orderDetail->getVat());
            }

            echo "<tr><td>" . $article->getTitle() . "</td>";
            echo "<td>" . $vat->getTitle() . " (" . number_format($vat->getValue(), 2, ',', '.') . "%)</td>";
            echo "<td>" . $amount . "</td>";
            echo "<td>" . number_format($price / (float)$amount, 2, ',', '.') . CURRENCY . "</td>";
            echo "<td>" . number_format($price, 2, ',', '.') . CURRENCY . "</td></tr>";
        }
        echo "<tr><td colspan=\"4\" align=\"right\">Nettowert:</td><td>" . number_format($sumNetto, 2, ",", ".") . CURRENCY . "</td></tr>";
        foreach ($sumVats as $_key => $_vat) {
            $vat = $utils->getElementWithId($vats, $_key);
            echo "<tr><td colspan=\"4\" align=\"right\">Umsatzsteuer (" . number_format($vat->getValue(), 2, ',', '.') . "%):</td><td>" . number_format($_vat, 2, ",", ".") . CURRENCY . "</td></tr>";
        }
        echo "<tr><td colspan=\"4\" align=\"right\">Endbetrag:</td><td>" . number_format($sumPrice, 2, ",", ".") . CURRENCY . "</td></tr>";

        echo "</table><br>";
    }
}
?>

<?php
require_once("../footer.inc.php");
?>
