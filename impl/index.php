<?php
require_once "classes/Utils.class.php";
require("header.inc.php");

$vats = $utils->getVats();

if (isset($_POST["addToCart"])) {
    $_amount = (integer)$_POST["amount"];
    $_id = (integer)$_POST["id"];
    $_utils = new Utils();
    $_article = $_utils->getElementWithId($_utils->getAllArticles(), $_id);
    $_utils->addToCart($_article, $_amount);
}

if ($articleGroup == null) {
    echo "<h1>Alle Produktgruppen</h1>";
} else if ($articleGroup > 0) {
    $_articleGroup = $utils->getElementWithId($articleGroups, $articleGroup);
    echo "<h1>" . $_articleGroup->getTitle() . "</h1>";
}
if (count($articles) == 0) {
    echo "Keine Artikel in dieser Produktgruppe vorhanden.";
} else {
    foreach ($articles as $key => $_article) {
        echo "<form>";
        echo "<input type=\"hidden\" name=\"id\" value=\"" . $_article->getId() . "\"/>";
        echo "<input type=\"hidden\" name=\"action\" value=\"addToCart\"/>";
        echo "<div id=\"" . $_article->getId() . "\" class=\"article\">";
        echo "<div class=\"articleName\">" . $_article->getTitle() . "</div>";
        if ($_article->getPicture() != null) {
            echo "<img class=\"articleImage\" src=\"pictures/thumb_" . $_article->getPicture() . "\" onclick=\"showImage('pictures/" . $_article->getPicture() . "');\"/>";
        }
        echo "<div class=\"leftOfArticle\">";
        echo "<div class=\"articleDescription\">Produktbeschreibung:</div>";
        echo "<div>" . $_article->getDescription() . "</div>";

        echo "</div>";
        echo "<div class=\"rightOfArticle\"><div class=\"articleDescription\">Preis:</div>";
        echo "<div class=\"articlePrice\">" . number_format($utils->calculateBruttoPrice($_article->getNettoPrice(), $_article->getVat()), 2, ',', '.') . CURRENCY . "</div>";
        echo "<div class=\"articleDescription\">inkl. MwSt. und</div>";
        echo "<div class=\"articleDescription\">zzgl. Versandkosten</div></div>";

        echo "<div class=\"articleOrder\">";
        echo "<div class=\"leftOfArticleFooter\" align=\"right\">Menge: <input type=\"number\" name=\"amount\" value=\"1\" min=\"1\"/></div>";
        echo "<div class=\"rightOfArticle\"><input type=\"button\" value=\"In den Warenkorb\" onclick=\"addToCart(\$(this).closest('form'))\"/></div>";
        echo "</div>";
        echo "</div></form>";
    }
}
require("footer.inc.php");
?>