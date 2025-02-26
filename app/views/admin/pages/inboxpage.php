<?php 
if(!defined("MASTERPAGE")){
	die("Error");
};
?>
<?php
// Veritabanı sınıfını dahil ediyoruz
include_once("../app/models/admin/database.php");

// Database sınıfını başlatıyoruz
$database = new Database();
$conn = $database->connect();

// Eğer bir mesaj silme isteği varsa, mesajı veritabanından siliyoruz
if (isset($_POST['delete_message_id'])) {
    $message_id = $_POST['delete_message_id'];

    // Mesajı silme SQL sorgusu
    $sql = "DELETE FROM messages WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $message_id, PDO::PARAM_INT);
    $stmt->execute();
}

// SQL sorgusuyla verileri çekiyoruz
$sql = "SELECT id, name, email, message, created_at FROM messages";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Verileri çekiyoruz
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bağlantıyı kapatıyoruz
$database->close();
?>
<script src="../public/js/admin/demo-theme.min.js?1738096684" defer></script>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>İsim</th>
                                    <th>Mail</th>
                                    <th>Mesaj</th>
                                    <th>Gönderim Zamanı</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($message['name']); ?></td>
                                        <td class="text-secondary">
                                            <?php echo htmlspecialchars($message['email']); ?>
                                        </td>
                                        <td class="text-secondary">
                                            <a href="#"
                                                class="text-reset"><?php echo htmlspecialchars($message['message']); ?></a>
                                        </td>
                                        <td class="text-secondary">
                                            <?php echo htmlspecialchars($message['created_at']); ?>
                                        </td>
                                        <td>
                                            <!-- Silme işlemi için form -->
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="delete_message_id" value="<?php echo $message['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

