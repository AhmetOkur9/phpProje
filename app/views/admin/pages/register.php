<?php 
if(!defined("MASTERPAGE")){
	die("Error");
};
?>
<?php
    include_once ("../app/models/admin/database.php");

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $errors =[];

    // Veritabanı bağlantısını alıyoruz
    $database = new Database();
    $pdo = $database->connect();  // PDO bağlantısını al

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])) {
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $name = $_POST["name"];
        $password = $_POST["password"];

        // Hataları kontrol et
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Hatalı e-posta";
        }
        if (empty($name)) {
            $errors["name"] = "İsim boş olamaz";
        }
        if (empty($password)) {
            $errors["password"] = "Şifre boş olamaz";
        }

        // Kullanıcı var mı kontrolü
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(["email" => $email]);
        if ($stmt->fetch()) {
            $errors["user_exist"] = "E-posta zaten kullanılıyor";
        }

        // Hatalar varsa geri dön
        if (!empty($errors)) {
            $_SESSION["errors"] = $errors;
            header("Location: register.php");
            exit();
        }

        // Şifreyi hash'le
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Yeni kullanıcıyı veritabanına ekle
        $stmt = $pdo->prepare("INSERT INTO users (email, password, name) VALUES (:email, :password, :name)");
        $stmt->execute([
            "email" => $email,
            "password" => $hashedPassword,
            "name" => $name
        ]);

        // Başarılı işlem sonrası yönlendir
        header("Location: index.php");
        exit();
    }
?>

<div class="page">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="." class="navbar-brand navbar-brand-autodark">
                </a>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Kayıt Ol</h2>


                <form  method="POST" autocomplete="off" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Ad</label>
                        <input type="text" class="form-control" name="name" placeholder="Adınız" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-Posta</label>
                        <input type="email" class="form-control" name="email" placeholder="eposta@email.com" autocomplete="off" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Şifre</label>
                        <div class="input-group input-group-flat">
                            <input type="password" class="form-control" name="password"" placeholder="Şifreniz" autocomplete="off" required>
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                                    </a>
                            </span>
                        </div>
                    </div>
                    <div class="form-footer">
                        <button name="register" type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php 
    if(isset($_SESSION["errors"])){
        unset($_SESSION["errors"]);
    }


?>