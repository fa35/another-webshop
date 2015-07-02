<?php
require_once("../header.inc.php");

if (!$utils->isLoggedIn() || !(((isset($_SESSION["recipient"]) && is_array($_SESSION["recipient"]))
            || isset($_POST['submit_order'])) &&
        isset($_SESSION["cart"]) && is_array($_SESSION["cart"]) && count($_SESSION["cart"]) > 0)
) {
    header("Location: ../index.php");
}

require_once "../classes/model/OrderDetails.class.php";
require_once "../classes/model/ArticleGroup.class.php";
require_once "../classes/model/PaymentType.class.php";
require_once "../classes/model/Article.class.php";
require_once "../classes/model/Vat.class.php";

$name = "";
$prename = "";
$streetNumber = "";
$zipLocation = "";

$orderDetails = array();
if (isset($_SESSION["cart"])) {
    $orderDetails = $_SESSION["cart"];
}

$orderSuccessful = false;

$paymentTypes = $utils->getPaymentTypes();
$paymentType = (is_array($paymentTypes) && count($paymentTypes) > 0) ? $paymentTypes[0] : null;

if ((isset($_SESSION["recipient"]) && is_array($_SESSION["recipient"])) || isset($_POST["submit_order"])) {
    $recipient = $_SESSION["recipient"];

    $name = $recipient["name"];
    $prename = $recipient["prename"];
    $streetNumber = $recipient["streetNumber"];
    $zipLocation = $recipient["zipLocation"];
}

if (!isset($_POST["submit_order"])) {
    echo "<h1>Angaben überprüfen</h1>";
} else
    if (isset($_POST["submit_order"]) && isset($_SESSION["cart"]) && is_array($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) {

        if (isset($_POST["paymentType"])) {
            $paymentType = $utils->getElementWithId($paymentTypes, (integer)$_POST["paymentType"]);

            $order = $utils->addOrder($utils->getLoggedInUserId(), $paymentType->getId(), $name, $prename, $streetNumber, $zipLocation);
            if (is_integer($order) && $order > 0) {
                foreach ($orderDetails as $_key => $_orderDetail) {
                    $_orderDetail->setOrder($order);
                }
                $orderSuccessful = $utils->addOrderDetails($orderDetails);

                if ($orderSuccessful) {
                    unset($_SESSION["recipient"]);
                }
            }
        }

        if ($orderSuccessful) {
            echo "<h1>Bestellung erfolgreich aufgegeben</h1>";
            echo "<p>Vielen Dank für ihre Bestellung!</p>";
        } else {
            echo "<h1>Bestellung nicht erfolgreich</h1>";
        }
    }
?>
    <h1>Lieferadresse</h1>
    <table border="0" cellpadding="0" cellspacing="4">
        <tr>
            <td align="right">Name:</td>
            <td><?php echo $name; ?></td>
        </tr>
        <tr>
            <td align="right">Vorname:</td>
            <td><?php echo $prename; ?></td>
        </tr>
        <tr>
            <td align="right">Straße:</td>
            <td><?php echo $streetNumber; ?></td>
        </tr>
        <tr>
            <td align="right">Ort:</td>
            <td><?php echo $zipLocation; ?></td>
        </tr>
    </table>

<?php
if ((isset($_SESSION["recipient"]) && is_array($_SESSION["recipient"])) || !$orderSuccessful) {
    echo "<h1>Bitte wählen Sie die Zahlungsart aus:</h1>";
    echo "<form action=\"order.php\" method=\"post\">";
    echo "<fieldset>";
    foreach ($paymentTypes as $_key => $_paymentType) {
        if ($_paymentType == $paymentType) {
            echo "<input type=\"radio\" name=\"paymentType\" value=\"" . $_paymentType->getId() . "\" checked />" . $_paymentType->getTitle() . "<br>";
        } else {
            echo "<input type=\"radio\" name=\"paymentType\" value=\"" . $_paymentType->getId() . "\"/>" . $_paymentType->getTitle() . "<br>";
        }
    }
    echo "</fieldset>";
} else {
    echo "<h1>Zahlungsart:</h1>";
    echo $paymentType->getTitle();
}
?>
    <br>
    <h1>Artikel</h1>
    <table>
        <tr>
            <th>
                Artikel
            </th>
            <th>
                Umsatzsteuer
            </th>
            <th>
                Menge
            </th>
            <th>
                Einzelpreis
            </th>
            <th>
                Gesamtpreis
            </th>
        </tr>
        <?php
        $articles = $utils->getAllArticles();
        $articleGroups = $utils->getArticleGroups();
        $vats = $utils->getVats();
        $sumNetto = 0.0;
        $sumPrice = 0.0;
        $sumVats = array();

        foreach ($orderDetails as $_key => $_orderDetail) {

            if ($_orderDetail != null && $_orderDetail->getArticle() != null) {
                $article = $_orderDetail->getArticle();

                $vat = $utils->getElementWithId($vats, $article->getVat());

                $priceSingle = $utils->calculateBruttoPrice($_orderDetail->getNettoPrice() / (float)$_orderDetail->getAmount(), $_orderDetail->getVat());
                $price = $utils->calculateBruttoPrice($_orderDetail->getNettoPrice(), $_orderDetail->getVat());

                $sumNetto += ((float)$_orderDetail->getNettoPrice() / 100.0);
                $sumPrice += (float)$price;
                if (isset($sumVats[$_orderDetail->getVat()])) {
                    $sumVats[$_orderDetail->getVat()] += (float)$utils->calculateVat($_orderDetail->getNettoPrice(), $_orderDetail->getVat());
                } else {
                    $sumVats[$_orderDetail->getVat()] = (float)$utils->calculateVat($_orderDetail->getNettoPrice(), $_orderDetail->getVat());
                }

                echo "<tr>";
                echo "<td>" . $_orderDetail->getArticle()->getTitle() . "</td>";
                echo "<td>" . number_format($vat->getValue(), 2, ',', '.') . "%</td>";
                echo "<td>" . $_orderDetail->getAmount() . "</td>";
                echo "<td>" . number_format($priceSingle, 2, ",", "") . CURRENCY . "</td>";
                echo "<td>" . number_format($price, 2, ",", "") . CURRENCY . "</td>";
            }
        }

        echo "<tr><td colspan=\"4\" align=\"right\">Nettowert:</td><td>" . number_format($sumNetto, 2, ",", ".") . CURRENCY . "</td></tr>";
        foreach ($sumVats as $_key => $_vat) {
            $vat = $utils->getElementWithId($vats, $_key);
            echo "<tr><td colspan=\"4\" align=\"right\">Umsatzsteuer (" . number_format($vat->getValue(), 2, ',', '.') . "%):</td><td>" . number_format($_vat, 2, ",", ".") . CURRENCY . "</td></tr>";
        }
        echo "<tr><td colspan=\"4\" align=\"right\">Endbetrag:</td><td>" . number_format($sumPrice, 2, ",", ".") . CURRENCY . "</td></tr>";

        if ((isset($_SESSION["recipient"]) && is_array($_SESSION["recipient"])) || !$orderSuccessful) {
            echo "<input type=\"hidden\" name=\"name\" value=\"" . $name . "\"/>";
            echo "<input type=\"hidden\" name=\"prename\" value=\"" . $prename . "\"/>";
            echo "<input type=\"hidden\" name=\"streetNumber\" value=\"" . $streetNumber . "\"/>";
            echo "<input type=\"hidden\" name=\"zipLocation\" value=\"" . $zipLocation . "\"/>";
            echo "<tr><td colspan=\"4\"/><td><input type=\"submit\" value=\"Bestellung aufgeben\" name=\"submit_order\"/></td></tr>";
            echo "</form>";
        } else if (isset($_POST["submit_order"])) {
            $utils->clearCart();
            echo "<tr></tr><tr><td colspan=\"4\"/><td><a href=\"../index.php\">Weiter</a></td></tr>";
        }
        ?>
    </table>
<?php
require_once("../footer.inc.php");
?>