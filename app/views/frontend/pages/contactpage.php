<?php
if (!defined("MASTERPAGE")) {
    die("Error");
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("app/models/admin/database.php"); // Database sınıfını içe aktar


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CSRF Token Kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Geçersiz istek! CSRF doğrulaması başarısız.");
    }

    // Rate Limiting (Sınırlı İstek Kontrolü)
    $ip = $_SERVER['REMOTE_ADDR'];

    if (!isset($_SESSION['form_requests'])) {
        $_SESSION['form_requests'] = [];
    }

    $_SESSION['form_requests'] = array_filter($_SESSION['form_requests'], function ($timestamp) {
        return $timestamp > time() - 60;
    });

    if (count($_SESSION['form_requests']) >= 5) {
        error_log("Rate limit aşıldı: IP - $ip", 0);
        die("Çok fazla istek gönderdiniz. Lütfen 1 dakika bekleyip tekrar deneyin.");
    }

    $_SESSION['form_requests'][] = time();

    // Formdan gelen verileri al ve XSS saldırılarına karşı temizle
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (empty($name) || empty($email) || empty($message)) {
        die("Lütfen tüm alanları doldurun.");
    }

    try {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            echo "Mesaj başarıyla gönderildi!";
        } else {
            echo "Mesaj gönderilirken hata oluştu.";
        }
    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }

    $db->close();
} 
?>

<section id="One" class="wrapper style3">
    <div class="inner">
        <header class="align-center">
            <p>Danışman Tercihim</p>
            <h2>Bize Ulaşın</h2>
        </header>
    </div>
</section><!-- Two -->


<section class="wrapper style2">
    <div class="container">
        <div class="align-center">
            <h2>Mail Adresim:</h2>
            <h4>elifokur@gmail.com</h4>
        </div>
</div>
    </div>
    <div class="container">
        <div class="align-center">
            <h2>Başvuru Formu Gönderin</h2>
        </div>
        <?php
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        ?>
        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="row uniform">
                <div class="6u 12u$(xsmall)">
                    <input type="text" name="name" id="name" value="" placeholder="İsim">
                </div>
                <div class="6u$ 12u$(xsmall)">
                    <input type="email" name="email" id="email" value="" placeholder="Email">
                </div>
                <div class="12u$">
                    <textarea name="message" id="message" placeholder="Mesajınızı Giriniz..." rows="6"></textarea>
                </div>
                <div class="12u$">
                    <ul class="actions">
                        <li><input type="submit" value="Mesaj Gönder"></li>
                    </ul>
                </div>
            </div>
        </form>
    </div>

</section>