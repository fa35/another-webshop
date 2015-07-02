<?php
require_once "../classes/Utils.class.php";
$utils = new Utils();

$sumPrice = 0.0;

if (isset($_GET['clear'])) {
    $utils->clearCart();
} else
    if (isset($_POST['submit-remove'])) {
        $id = (integer)$_POST["id"];
        $amount = (integer)$_POST["amount"];

        if ($id > 0 && $amount > 0) {
            $utils->removeFromCart($id, $amount);
        }
    }

require_once("../header.inc.php");
?>
<h1>Warenkorb</h1>
<table>
    <tr>
        <th>
            Artikel
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
        <th>
            Aktion
        </th>
    </tr>
    <?php
    require_once "../classes/model/OrderDetails.class.php";
    require_once "../classes/model/ArticleGroup.class.php";
    require_once "../classes/model/Article.class.php";
    require_once "../classes/model/Vat.class.php";

    $articles = $utils->getAllArticles();
    $articleGroups = $utils->getArticleGroups();
    $vats = $utils->getVats();
    $orderDetails = array();
    if (isset($_SESSION["cart"])) {
        $orderDetails = $_SESSION["cart"];
    }

    foreach ($orderDetails as $key => $_orderDetail) {

        if ($_orderDetail != null && $_orderDetail->getArticle() != null) {
            $article = $_orderDetail->getArticle();

            $vat = $utils->getElementWithId($vats, $article->getVat());

            $singlePrice = $utils->calculateBruttoPrice($_orderDetail->getNettoPrice() / (float)$_orderDetail->getAmount(), $_orderDetail->getVat());
            $price = $utils->calculateBruttoPrice($_orderDetail->getNettoPrice(), $_orderDetail->getVat());
            $sumPrice += (float)$price;

            echo "<tr>";
            echo "<td>" . $_orderDetail->getArticle()->getTitle() . "</td>";
            echo "<td>" . $_orderDetail->getAmount() . "</td>";
            echo "<td>" . number_format($singlePrice, 2, ",", ".") . CURRENCY . "</td>";
            echo "<td>" . number_format($price, 2, ",", ".") . CURRENCY . "</td>";
            echo "<form action=\"cart.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $article->getId() . "\"/>";
            if ($_orderDetail->getAmount() > 1) {
                echo "<input type=\"number\" name=\"amount\" value=\"1\" min=\"1\" max=\"" . $_orderDetail->getAmount() . "\" step=\"1\"/>";
            } else {
                echo "<input type=\"hidden\" name=\"amount\" value=\"" . $_orderDetail->getAmount() . "\"/>";
            }
            echo "<input type=\"submit\" name=\"submit-remove\" value=\"Entfernen\"/></td>"
                . "</form>";
            echo " </tr> ";
        }
    }
    if (count($orderDetails) == 0) {
        echo "<tr><td colspan=\"5\">Ihr Warenkorb ist leer.</td></tr>";
    } else {
        echo "<tr><td colspan=\"3\" align=\"right\">Summe:</td><td colspan=\"2\">" . number_format($sumPrice, 2, ",", ".") . CURRENCY . "</td></tr>";
    }
    ?>
</table>
<?php
if (count($orderDetails) > 0) {
    if ($utils->isLoggedIn()) {
        echo "<a href=\"recipient.php\">Bestellen</a> | <a href=\"cart.php?clear\">Warenkorb leeren</a>";
    } else {
        echo "Bitte melden Sie sich zum Bestellen an oder registrieren Sie sich neu.<br>";
        echo "<a href=\"cart.php?clear\">Warenkorb leeren</a>";
    }
}
require_once("../footer.inc.php");
?>
