<!DOCTYPE HTML>
<html>
<?php
define("MASTERPAGE", "true");

// $route kontrol ediliyor
$route = isset($_GET["route"]) ? strtolower(trim($_GET["route"])) : "anasayfa"; // Default homepage
?>

<?php
include("../app/views/frontend/elements/head.php");
?>

<body>
    <?php
    // Navbar dahil ediliyor
    include("../app/views/frontend/elements/navbar.php");

    // Geçerli route'lar
    $allowedRoutes = [
        "anasayfa" => [
            "content" => "../app/views/frontend/pages/homepage.php"
        ],
        "hakkimizda" => "../app/views/frontend/pages/aboutpage.php",
        "iletisim" => "../app/views/frontend/pages/contactpage.php",
        "admin"=> "./admin.php"
    ];

    // Route doğrulaması
    if (array_key_exists($route, $allowedRoutes)) {
        // Eğer banner içeren bir sayfa ise
        if (is_array($allowedRoutes[$route])) {
            include($allowedRoutes[$route]["content"]);
        } else {
            include($allowedRoutes[$route]);
        }
    } else {
        // Geçersiz route => Hata sayfası
        include("../app/views/frontend/pages/errorpage.php");
    }

    // Footer ve script dahil ediliyor
    include("../app/views/frontend/elements/footer.php");
    include("../app/views/frontend/elements/script.php");
    ?>
</body>

</html>
