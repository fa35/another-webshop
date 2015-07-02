<?php

require_once "Database.class.php";
require_once "model/Article.class.php";
require_once "model/ArticleGroup.class.php";
require_once "model/Order.class.php";
require_once "model/PaymentType.class.php";
require_once "model/OrderDetails.class.php";
require_once "model/User.class.php";
require_once "model/Vat.class.php";
require_once "config.inc.php";

class Utils
{
    private static $database;

    static function init()
    {
        self::$database = new Database();
        session_start();
    }

    public function login($username, $password)
    {
        if (self::$database != null && self::$database->isConnected()) {
            $rowSet = self::$database->query("select * from nutzer where email like \"" . $username . "\" and password like \"" . hash("sha256", $password, false) . "\";");
            if ($rowSet != null && $rowSet->num_rows == 1) {
                if ($user = $rowSet->fetch_assoc()) {

                    $_SESSION["username"] = $user["email"];
                    $_SESSION["userId"] = (integer)$user["id"];
                    $_SESSION["userAdmin"] = (boolean)$user["is_admin"];

                    $rowSet->close();
                    return true;
                }
            } else {
                $rowSet->close();
            }
        }
        return false;
    }

    public function logout()
    {
        unset($_SESSION["username"]);
        unset($_SESSION["userId"]);
        unset($_SESSION["userAdmin"]);
        unset($_SESSION["cart"]);
        unset($_SESSION["recipient"]);
        session_destroy();
    }

    public function isLoggedIn()
    {
        $isLoggedIn = isset($_SESSION["username"]) && filter_var($_SESSION["username"], FILTER_VALIDATE_EMAIL) && isset($_SESSION["userId"]) && (integer)$_SESSION["userId"] > 0;
        return $isLoggedIn;
    }

    public function isAdmin()
    {
        $isAdmin = $this->isLoggedIn() && isset($_SESSION["userAdmin"]) && (boolean)$_SESSION["userAdmin"] == true;
        return $isAdmin;
    }

    public function getLoggedInUserId()
    {
        if ($this->isLoggedIn()) {
            return $_SESSION["userId"];
        }
        return null;
    }

    public function getLoggedInUserName()
    {
        if ($this->isLoggedIn()) {
            return $_SESSION["username"];
        }
        return null;
    }

    public function getLoggedInUser()
    {
        if ($this->isLoggedIn()) {
            return $this->getElementWithId($this->getUsers(), $_SESSION["userId"]);
        }
        return null;
    }

    public function getUsers()
    {
        if (self::$database != null && self::$database->isConnected()) {
            $rowSet = self::$database->query("select * from nutzer;");
            if ($rowSet != null) {
                $users = array();
                while ($data = $rowSet->fetch_assoc()) {
                    $users[] = new User($data["id"], $data["name"], $data["vorname"],
                        $data["strasse"], $data["strasse_nr"], $data["plz"],
                        $data["ort"], $data["email"], $data["password"], (boolean)$data["is_admin"]);
                }
                return $users;
            }
        }
        return null;
    }

    public function registerUser($name, $preName, $street, $streetNumber, $zip, $location, $email, $password, $isAdmin)
    {
        if (self::$database != null && self::$database->isConnected() && !($this->containsUser($email))) {
            $sql = "insert into nutzer(id, name, vorname, strasse, strasse_nr, plz, ort, email, password, is_admin) values (";
            $sql .= "null, ";
            $sql .= "\"" . $name . "\", ";
            $sql .= "\"" . $preName . "\", ";
            $sql .= "\"" . $street . "\", ";
            $sql .= "\"" . $streetNumber . "\", ";
            $sql .= "\"" . $zip . "\", ";
            $sql .= "\"" . $location . "\", ";
            $sql .= "\"" . $email . "\", ";
            $sql .= "\"" . $password . "\", ";
            $sql .= ($isAdmin ? "1" : "0") . ");";

            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function editUser($name, $preName, $street, $streetNumber, $zip, $location, $email, $password, $isAdmin, $id)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "update nutzer set ";
            $sql .= "name=\"" . $name . "\", ";
            $sql .= "vorname=\"" . $preName . "\", ";
            $sql .= "strasse=\"" . $street . "\", ";
            $sql .= "strasse_nr=\"" . $streetNumber . "\", ";
            $sql .= "plz=\"" . $zip . "\", ";
            $sql .= "ort=\"" . $location . "\", ";
            $sql .= "email=\"" . $email . "\", ";
            if ($password != null) {
                $sql .= "password=\"" . $password . "\", ";
            }
            $sql .= "is_admin=" . ($isAdmin ? "1" : "0");
            $sql .= " where id=" . $id . ";";

            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function deleteUser($id)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "delete from nutzer where id=" . $id . ";";
            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    private function containsUser($username)
    {
        if (self::$database != null && self::$database->isConnected()) {
            $rowSet = self::$database->query("select * from nutzer where email like " . $username . ";");
            $found = false;

            if ($rowSet != null) {
                while ($user = $rowSet->fetch_assoc()) {
                    if ($user["email"] === $username) {
                        $found = true;
                    }
                }
                $rowSet->close();
            }
            return $found;
        }
        return true;
    }

    public function getArticleGroups()
    {
        if (self::$database != null && self::$database->isConnected()) {
            $rowSet = self::$database->query("select * from artikel_gruppen;");
            if ($rowSet != null) {
                $articleGroups = array();
                while ($data = $rowSet->fetch_assoc()) {
                    $articleGroups[] = new ArticleGroup($data["id"], $data["titel"]);
                }
                return $articleGroups;
            }
        }
        return null;
    }

    public function addArticleGroup($title)
    {
        if (self::$database != null && self::$database->isConnected()) {
            $sql = "insert into artikel_gruppen(id, titel) values (null, \"" . $title . "\");";

            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function editArticleGroup($id, $title)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "update artikel_gruppen set titel=\"" . $title . "\" where id=" . $id . ";";
            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function deleteArticleGroup($id)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "delete from artikel_gruppen where id=" . $id . ";";
            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function getAllArticles()
    {
        if (self::$database != null && self::$database->isConnected()) {
            $rowSet = self::$database->query("select * from artikel;");
            if ($rowSet != null) {
                $articles = array();
                while ($data = $rowSet->fetch_assoc()) {
                    $articles[] = new Article($data["id"], $data["titel"], $data["artikel_gruppen_id"], $data["netto_preis"], $data["mwst_satz"], $data["beschreibung"], $data["bild_name"]);
                }
                return $articles;
            }
        }
        return null;
    }

    public function getArticlesOfGroup($articleGroup)
    {
        if (self::$database != null && self::$database->isConnected()) {
            $rowSet = self::$database->query("select * from artikel where artikel_gruppen_id=" . $articleGroup . ";");
            if ($rowSet != null) {
                $articles = array();
                while ($data = $rowSet->fetch_assoc()) {
                    $articles[] = new Article($data["id"], $data["titel"], $data["artikel_gruppen_id"], $data["netto_preis"], $data["mwst_satz"], $data["beschreibung"], $data["bild_name"]);
                }
                return $articles;
            }
        }
        return null;
    }

    public function addArticle($title, $articleGroup, $nettoPrice, $vat, $description, $picture)
    {
        if (self::$database != null && self::$database->isConnected()) {
            $sql = "insert into artikel(id, titel, artikel_gruppen_id, netto_preis, mwst_satz, beschreibung, bild_name) values (";
            $sql .= "null, \"";
            $sql .= $title . "\", ";
            $sql .= $articleGroup . ", ";
            $sql .= $nettoPrice . ", ";
            $sql .= $vat . ", \"";
            $sql .= $description . "\", ";
            $sql .= ($picture != null ? ("\"" . $picture . "\"") : "null") . ");";

            echo $sql;

            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function editArticle($id, $title, $articleGroup, $nettoPrice, $vat, $description, $picture, $deletePicture)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "update artikel set ";
            $sql .= "titel=\"" . $title . "\", ";
            $sql .= "artikel_gruppen_id=\"" . $articleGroup . "\", ";
            $sql .= "netto_preis=\"" . $nettoPrice . "\", ";
            $sql .= "mwst_satz=\"" . $vat . "\", ";
            $sql .= "beschreibung=\"" . $description . "\"";
            if ($picture != null) {
                $sql .= ", bild_name=\"" . $picture . "\"";
            } else if ($deletePicture) {
                $sql .= ", bild_name=null";
            }
            $sql .= " where id=" . $id . ";";

            echo $sql;

            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function deleteArticle($id)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "delete from artikel where id=" . $id . ";";
            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function getVats()
    {
        if (self::$database != null && self::$database->isConnected()) {
            $rowSet = self::$database->query("select * from steuersaetze;");
            if ($rowSet != null) {
                $vats = array();
                while ($data = $rowSet->fetch_assoc()) {
                    $vats[] = new Vat($data["id"], $data["titel"], $data["steuersatz"]);
                }
                return $vats;
            }
        }
        return null;
    }

    public function addVat($title, $value)
    {
        if (self::$database != null && self::$database->isConnected()) {
            $sql = "insert into steuersaetze(id, titel, steuersatz) values (";
            $sql .= "null, \"";
            $sql .= $title . "\", ";
            $sql .= $value . ");";

            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function editVat($id, $title, $value)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "update steuersaetze set ";
            $sql .= "titel=\"" . $title . "\", ";
            $sql .= "steuersatz=" . $value . " ";
            $sql .= "where id=" . $id . ";";
            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function deleteVat($id)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "delete from steuersaetze where id=" . $id . ";";
            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function getElementWithId($array, $id)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if ($value->getId() == $id) {
                    return $value;
                }
            }
        }
        return null;
    }

    public function calculateBruttoPrice($nettoPrice, $vatId, $amount = 1)
    {
        $utils = new Utils();
        $vat = $utils->getElementWithId($this->getVats(), $vatId);
        return round((($vat != null) ? ($vat->getValue() / 100.0 + 1.0) : 1.0) * ($nettoPrice / 100.0), 2) * (float)$amount;
    }

    public function calculateVat($nettoPrice, $vatId, $amount = 1)
    {
        $utils = new Utils();
        $vat = $utils->getElementWithId($this->getVats(), $vatId);
        return round((($vat != null) ? ($vat->getValue() / 100.0) : 0.0) * ($nettoPrice / 100.0), 2) * (float)$amount;
    }

    public function addToCart($article, $amount)
    {
        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = array();
        }
        $orderDetail = $this->getOrderDetailOfArticle($article);
        if ($orderDetail != null) {
            $_amount = $orderDetail->getAmount() + $amount;
            $_nettoPrice = $orderDetail->getNettoPrice() + ($orderDetail->getArticle()->getNettoPrice() * $amount);
            $orderDetail->setAmount((integer)$_amount);
            $orderDetail->setNettoPrice($_nettoPrice);
        } else {
            $orderDetail = new OrderDetails(null, null, $article, $article->getNettoPrice() * $amount, (integer)$article->getVat(), (integer)$amount);
            $_SESSION["cart"][] = $orderDetail;
        }
    }

    public function getCartDescription()
    {
        $amount = 0;
        $price = 0.0;

        if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) {
            foreach ($_SESSION["cart"] as $key => $orderDetail) {
                $amount += $orderDetail->getAmount();
                $price += $this->calculateBruttoPrice($orderDetail->getNettoPrice(), $orderDetail->getVat());
            }
            return ": " . $amount . " Artikel, im Wert von: " . number_format($price, 2, ',', '.') . CURRENCY;
        }
        return " ist leer.";
    }

    private function getArticleOfOrderDetails($id)
    {
        if (isset($_SESSION["cart"])) {
            foreach ($_SESSION["cart"] as $key => $orderDetail) {
                if ($orderDetail->getArticle()->getId() == $id) {
                    return $orderDetail->getArticle();
                }
            }
        }
        return null;
    }

    private function getOrderDetailOfArticle($article)
    {
        if (isset($_SESSION["cart"])) {
            foreach ($_SESSION["cart"] as $key => $orderDetail) {
                if ($orderDetail->getArticle() == $article) {
                    return $orderDetail;
                }
            }
        }
        return null;
    }

    private function getKeyOfOrderDetail($orderDetail)
    {
        if (isset($_SESSION["cart"])) {
            foreach ($_SESSION["cart"] as $key => $_orderDetail) {
                if ($_orderDetail == $orderDetail) {
                    return $key;
                }
            }
        }
        return null;
    }

    public function removeFromCart($id, $amount)
    {
        $orderDetail = $this->getOrderDetailOfArticle($this->getArticleOfOrderDetails($id));
        if ($orderDetail != null) {
            $_amount = $orderDetail->getAmount();
            if ($_amount <= $amount) {
                $key = $this->getKeyOfOrderDetail($orderDetail);
                unset($_SESSION["cart"][$key]);
            } else {
                $orderDetail->setAmount($_amount - $amount);
            }
        }
    }

    public function clearCart()
    {
        unset($_SESSION["cart"]);
    }

    public function getOrders($userId = null)
    {
        if (self::$database != null && self::$database->isConnected()) {
            $rowSet = self::$database->query("select * from bestellungen" . (($userId != null) ? (" where nutzer_id = " . $userId) : "") . " order by id desc;");
            if ($rowSet != null) {
                $orders = array();
                while ($data = $rowSet->fetch_assoc()) {
                    $orders[] = new Order($data["id"], $data["nutzer_id"], $data["datum"], $data["zahl_art"],
                        $data["r_name"], $data["r_vorname"], $data["r_strasse_nr"], $data["r_plz_ort"], $data["bezahlt"]);
                }
                return $orders;
            }
        }
        return null;
    }

    public function addOrder($userId, $paymentType, $recipientName, $recipientPrename, $recipientStreetNr, $recipientZipLocation)
    {
        if (self::$database != null && self::$database->isConnected()) {
            $sql = "insert into bestellungen(id, nutzer_id, datum, zahl_art, r_name, r_vorname, r_strasse_nr, r_plz_ort, bezahlt) values (";
            $sql .= "null, ";
            $sql .= $userId . ", \"";
            $sql .= $date = date("Y-d-m H:i:s", time()) . "\", ";
            $sql .= $paymentType . ", \"";
            $sql .= $recipientName . "\", \"";
            $sql .= $recipientPrename . "\", \"";
            $sql .= $recipientStreetNr . "\", \"";
            $sql .= $recipientZipLocation . "\", ";
            $sql .= "0);";
            $result = self::$database->insert($sql);
            return (is_integer($result) ? (integer)$result : false);
        } else {
            return false;
        }
    }

    public function setPaymentOfOrder($id, $payed)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "update bestellungen set ";
            $sql .= "bezahlt=" . ($payed ? "1" : "0");
            $sql .= " where id=" . $id . ";";

            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function getOrderDetails($orderId)
    {
        if (self::$database != null && self::$database->isConnected()) {
            $rowSet = self::$database->query("select * from bestellungs_details where bestellungen_id = " . $orderId . ";");
            if ($rowSet != null) {
                $orders = array();
                while ($data = $rowSet->fetch_assoc()) {
                    $orders[] = new OrderDetails($data["id"], $data["bestellungen_id"], $data["artikel_id"], $data["netto_preis"],
                        $data["mwst"], $data["anzahl"]);
                }
                return $orders;
            }
        }
        return null;
    }

    public function addOrderDetails($orderDetails)
    {
        $result = true;

        if (self::$database != null && self::$database->isConnected() && is_array($orderDetails) && count($orderDetails) > 0) {
            foreach ($orderDetails as $key => $orderDetail) {
                $order = is_integer($orderDetail->getOrder()) ? $orderDetail->getOrder() : $orderDetail->getOrder()->getId();
                if ($order > 0) {
                    $sql = "insert into bestellungs_details(id, bestellungen_id, artikel_id, netto_preis, mwst, anzahl) values (";
                    $sql .= "null, ";
                    $sql .= $order . ", ";
                    $sql .= (is_integer($orderDetail->getArticle()) ? $orderDetail->getArticle() : $orderDetail->getArticle()->getId()) . ", ";
                    $sql .= $orderDetail->getNettoPrice() . ", ";
                    $sql .= (is_integer($orderDetail->getVat()) ? $orderDetail->getVat() : $orderDetail->getVat()->getId()) . ", ";
                    $sql .= $orderDetail->getAmount() . ");";
                    $_result = self::$database->query($sql);
                    $result &= (is_bool($_result) && $_result == true);
                } else {
                    return false;
                }
            }
            return $result;
        } else {
            return false;
        }
    }

    public function getPaymentTypes()
    {
        if (self::$database != null && self::$database->isConnected()) {
            $rowSet = self::$database->query("select * from zahlungsart;");
            if ($rowSet != null) {
                $paymentTypes = array();
                while ($data = $rowSet->fetch_assoc()) {
                    $paymentTypes[] = new PaymentType($data["id"], $data["bezeichnung"]);
                }

                return $paymentTypes;
            }
        }
        return null;
    }

    public function addPaymentType($title)
    {
        if (self::$database != null && self::$database->isConnected()) {
            $sql = "insert into zahlungsart(id, bezeichnung) values (";
            $sql .= "null, \"";
            $sql .= $title . "\");";

            var_dump($sql);

            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function editPaymentType($id, $title)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "update zahlungsart set ";
            $sql .= "bezeichnung=\"" . $title . "\", ";
            $sql .= "where id=" . $id . ";";
            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function deletePaymentType($id)
    {
        if (self::$database != null && self::$database->isConnected() && is_integer($id) && $id != 0) {
            $sql = "delete from zahlungsart where id=" . $id . ";";
            $result = self::$database->query($sql);
            return (is_bool($result) && $result == true);
        } else {
            return false;
        }
    }

    public function createArticleImage($picture)
    {
        if (is_array($picture) && $picture["error"] == 0 && $picture["size"] > 0 && $picture["size"] < (integer)($_POST["MAX_FILE_SIZE"])) {
            $pictureName = hash_file("SHA256", $picture["tmp_name"]) . ".png";

            if (!file_exists("../../pictures/" . $pictureName)) {
                $image = imagecreatefromstring(file_get_contents($picture["tmp_name"]));

                if (imagepng($image, "../../pictures/" . $pictureName, 9, PNG_NO_FILTER)) {
                    $image = imagecreatefromstring(file_get_contents("../../pictures/" . $pictureName));

                    $imageWidth = imagesx($image);
                    $imageHeight = imagesy($image);
                    $thumbWidth = $imageWidth;
                    $thumbHeight = $imageHeight;

                    if ($thumbWidth > THUMBNAIL_WIDTH) {
                        $factor = THUMBNAIL_WIDTH / $thumbWidth;
                        $thumbWidth *= $factor;
                        $thumbHeight *= $factor;
                    }

                    if ($thumbHeight > THUMBNAIL_HEIGHT) {
                        $factor = THUMBNAIL_HEIGHT / $thumbHeight;
                        $thumbWidth *= $factor;
                        $thumbHeight *= $factor;
                    }

                    $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

                    imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $imageWidth, $imageHeight);

                    imagepng($thumb, "../../pictures/thumb_" . $pictureName);
                    imagedestroy($thumb);
                    imagedestroy($image);
                }
            }

            if (file_exists("../../pictures/" . $pictureName))
                return $pictureName;
        } else {
            return null;
        }
    }
}

Utils::init();
