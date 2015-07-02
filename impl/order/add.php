<?php
require_once "../classes/Utils.class.php";
require_once "../classes/model/Article.class.php";

$utils = new Utils();

if (isset($_POST["action"]) && $_POST["action"] === "addToCart") {
    $id = $_POST["id"];
    $amount = $_POST["amount"];

    $article = $utils->getElementWithId($utils->getAllArticles(), $id);
    $utils->addToCart($article, $amount);

    echo $utils->getCartDescription();
} else {
    header("Location: " . $_SERVER["CONTEXT_PREFIX"] . "/index.php");
}
?>