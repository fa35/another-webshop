<?php
require_once "../classes/Utils.class.php";

$utils = new Utils();
if (!$utils->isAdmin()) {
    header("Location: ../index.php");
}

require_once("../header.inc.php");
?>
    <h1>Administration</h1>
    <div id="adminList">
        <nav>
            <ul>
                <li><a href="user/administration.php">Benutzer verwalten</a></li>
                <li><a href="paymenttype/administration.php">Zahlungsarten verwalten</a></li>
                <li><a href="vat/administration.php">Mehrwertsteuersätze verwalten</a></li>
                <li><a href="articlegroup/administration.php">Artikelgruppen verwalten</a></li>
                <li><a href="article/administration.php">Artikel verwalten</a></li>
                <li><a href="../index.php">Zurück</a></li>
            </ul>
        </nav>
    </div>
<?php
require_once("../footer.inc.php");
?>