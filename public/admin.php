
<?php
    define("MASTERPAGE", "true");    
    include("../app/views/admin/elements/head.php");
    include_once ("../app/models/admin/database.php");

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<body>
    <?php 
        // Kullanıcı giriş yapmış mı kontrol et
        if (isset($_SESSION["user"]) && isset($_SESSION["girisyapildimi"]) && $_SESSION["girisyapildimi"] === true) {
            include("../app/views/admin/elements/navbar.php");
            include("../app/views/admin/pages/homepage.php"); // Giriş yapmışsa homepage göster
            include("../app/views/admin/pages/inboxpage.php");
        } else {
            include("../app/views/admin/pages/login.php"); // Giriş yapmamışsa login göster
            include("../app/views/admin/pages/register.php"); // Giriş yapmamışsa login göster
        }        
        include("../app/views/admin/elements/script.php");
    ?>
</body>
</html>