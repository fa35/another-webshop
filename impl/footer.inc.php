</div>
<div id="navlist">
    <nav>
        <ul>
            <?php
            if ($articleGroup == null && strpos($_SERVER["REQUEST_URI"], "index") !== false) {
                echo "<li><a class=\"selected\" href=\"" . $_SERVER["CONTEXT_PREFIX"] . "/index.php\">Alle Produktgruppen</a></li>";
            } else {
                echo "<li><a href=\"" . $_SERVER["CONTEXT_PREFIX"] . "/index.php\">Alle Produktgruppen</a></li>";
            }
            foreach ($articleGroups as $key => $_articleGroup) {
                if ($_articleGroup->getId() == $articleGroup && strpos($_SERVER["REQUEST_URI"], "index") !== false) {
                    echo "<li><a class=\"selected\" href=\"" . $_SERVER["CONTEXT_PREFIX"] . "/index.php?group=" . $_articleGroup->getId() . "\">" . $_articleGroup->getTitle() . "</a></li>";
                } else {
                    echo "<li><a href=\"" . $_SERVER["CONTEXT_PREFIX"] . "/index.php?group=" . $_articleGroup->getId() . "\">" . $_articleGroup->getTitle() . "</a></li>";
                }
            }
            ?>
        </ul>
    </nav>
</div>
</div>
<div id="footer">
    <div style="width: auto; float: left;">
        Himmelsrand-Versand by nj, 2015
    </div>
    <div style="width: 50%; float: right; text-align: right">
        Proudly made with <?php echo $_SERVER["SERVER_SOFTWARE"] ?>
    </div>
</div>
</div>
<div id="largeImgPanel" onclick="hideMe(this);">
    <img id="largeImg"/>
</div>
</body>
</html>