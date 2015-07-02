<!DOCTYPE html>
<?php

$basedir = dirname(realpath(__FILE__));

require_once $basedir . "/classes/Utils.class.php";
require_once $basedir . "/classes/model/ArticleGroup.class.php";
require_once $basedir . "/classes/model/Article.class.php";
require_once $basedir . "/classes/model/Vat.class.php";

$utils = new Utils();
if (isset($_GET["logout"]) && $utils->isLoggedIn()) {
    $utils->logout();
}

$articleGroups = $utils->getArticleGroups();
$articleGroup = null;

$articles = array();
if (isset($_GET["group"]) && ctype_digit($_GET["group"])) {
    $articleGroup = (integer)$_GET["group"];
    $articles = $utils->getArticlesOfGroup($articleGroup);
} else {
    $articles = $utils->getAllArticles();
}
?>
<html>
<head>
    <title>Himmelsrand-Versand</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo $_SERVER["CONTEXT_PREFIX"]; ?>/style.css"/>
    <script src="<?php echo $_SERVER["CONTEXT_PREFIX"]; ?>/jquery-2.1.3.min.js"></script>
    <script>
        function addToCart(form) {

            $.post("order/add.php",
                $(form).serialize(),
                function (data) {
                    $("#cartDescription").html(data);
                }
            );

            return false;
        }
    </script>
    <script type="text/javascript">
        // from http://alexapps.net/enlarge-thumbnail-image-mouse-click
        function showImage(imgName) {
            document.getElementById('largeImg').src = imgName;
            showLargeImagePanel();
            unselectAll();
        }
        function showLargeImagePanel() {
            document.getElementById('largeImgPanel').style.visibility = 'visible';
        }
        function unselectAll() {
            if (document.selection) document.selection.empty();
            if (window.getSelection) window.getSelection().removeAllRanges();
        }
        function hideMe(obj) {
            obj.style.visibility = 'hidden';
        }
    </script>
</head>
<body>
<div id="page">
    <div id="header">
        <div id="banner"><a href="<?php echo $_SERVER["CONTEXT_PREFIX"]; ?>/index.php">
                Himmelsrand-Versand</a></div>
        <div id="userBar">
            <?php
            if ($utils->isLoggedIn()) {
                echo $utils->getLoggedInUserName();
                echo " | <a href=\"" . $_SERVER["CONTEXT_PREFIX"] . "/index.php?logout\">Logout</a>";

                echo " | <a href=\"" . $_SERVER["CONTEXT_PREFIX"] . "/order/overview.php\">Bestellungen</a>";
                if ($utils->isAdmin()) {
                    echo " | <a href=\"" . $_SERVER["CONTEXT_PREFIX"] . "/admin/administration.php\">Administration</a>";
                }
            } else {
                echo "<a href=\"" . $_SERVER["CONTEXT_PREFIX"] . "/user/login.php\">Login</a>";
                echo " | <a href=\"" . $_SERVER["CONTEXT_PREFIX"] . "/user/register.php\">Registrieren</a>";
            }
            ?>
            <div id="cart">
                <a href="<?php echo $_SERVER["CONTEXT_PREFIX"]; ?>/order/cart.php">Ihr
                    Warenkorb<span
                        id="cartDescription"><?php echo $utils->getCartDescription(); ?></span></a>
            </div>
        </div>

    </div>
    <div id="main">
        <div id="content">