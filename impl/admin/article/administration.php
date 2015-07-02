<?php
require_once "../../classes/Utils.class.php";

$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../../index.php");
}
require_once("../../header.inc.php");
?>
    <h1>Artikel verwalten</h1>
    <table>
        <tr>
            <th>
                Artikelbild
            </th>
            <th>
                Bezeichnung
            </th>
            <th>
                Artikelgruppe
            </th>
            <th>
                Nettopreis
            </th>
            <th>
                Mehrwertsteuersatz
            </th>
            <th colspan="2">
                Aktion
            </th>
        </tr>
        <?php
        require_once "../../classes/model/ArticleGroup.class.php";
        require_once "../../classes/model/Article.class.php";
        require_once "../../classes/model/Vat.class.php";

        $articles = $utils->getAllArticles();
        $articleGroups = $utils->getArticleGroups();
        $vats = $utils->getVats();

        if (is_array($articles)) {
            foreach ($articles as $key => $article) {
                $articleGroup = $utils->getElementWithId($articleGroups, $article->getArticleGroup());
                $vat = $utils->getElementWithId($vats, $article->getVat());

                echo "<tr>";
                echo "<td>";
                if ($article->getPicture() != null) {
                    echo "<img class=\"articleImage\" src=\"" . "../../pictures/thumb_" . $article->getPicture() . "\" onclick=\"showImage('../../pictures/" . $article->getPicture() . "');\"/>";
                }
                echo "</td>";
                echo "<td>" . $article->getTitle() . "</td>";
                echo "<td>" . $articleGroup->getTitle() . "</td>";
                echo "<td>" . number_format((float)$article->getNettoPrice() / 100, 2, ",", "") . CURRENCY . "</td>";
                echo "<td>" . $vat->getTitle() . " (" . number_format($vat->getValue(), 2, ',', '.') . "%)</td>";
                echo "<form action=\"edit.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $article->getId() . "\"/>"
                    . "<input type=\"hidden\" name=\"title\" value=\"" . $article->getTitle() . "\"/>"
                    . "<input type=\"hidden\" name=\"articleGroup\" value=\"" . $article->getArticleGroup() . "\"/>"
                    . "<input type=\"hidden\" name=\"nettoPrice\" value=\"" . $article->getNettoPrice() . "\"/>"
                    . "<input type=\"hidden\" name=\"vat\" value=\"" . $article->getVat() . "\"/>"
                    . "<input type=\"hidden\" name=\"description\" value=\"" . $article->getDescription() . "\"/>"
                    . "<input type=\"hidden\" name=\"picture\" value=\"" . $article->getPicture() . "\"/>"
                    . "<input type=\"submit\" name=\"post-edit\" value=\"Bearbeiten\"/></td>"
                    . "</form>"
                    . "<form action=\"delete.php\" method=\"post\"><td><input type=\"hidden\" name=\"id\" value=\"" . $article->getId() . "\"/>"
                    . "<input type=\"hidden\" name=\"title\" value=\"" . $article->getTitle() . "\"/>"
                    . "<input type=\"submit\" name=\"post-delete\" value=\"Löschen\"/></td>"
                    . "</form>";
                echo " </tr > ";
            }
        }
        ?>
    </table>
    <a href="add.php">Neuer Artikel anlegen</a><br><br>
    <a href="../administration.php">Zurück</a>
<?php
require_once("../../footer.inc.php");
?>