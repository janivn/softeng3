<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_data'])) {
    header("Location: login.php");
    exit();
}

$form_id = $_SESSION['user_data'];
$upload_id = $_GET['id'] ?? null;

if (!$upload_id) {
    echo "Invalid upload ID.";
    exit();
}

// Fetch upload data
$query = "SELECT * FROM uploads WHERE id = ? AND form_id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("ii", $upload_id, $form_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $upload_data = $result->fetch_assoc();
    $stmt->close();
}

if (!$upload_data) {
    echo "Upload not found or you don't have permission to edit this item.";
    exit();
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price_per_day = $_POST['price_per_day'];
    $facilities = is_array($_POST['facilities']) ? implode(", ", $_POST['facilities']) : $_POST['facilities'];
    $contact_email = $_POST['contact_email'];
    $facebook_link = $_POST['facebook_link'];

    $new_image = null;
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp = $image['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if (in_array($image_ext, $allowed_ext)) {
            $new_image = uniqid('', true) . '.' . $image_ext;
            move_uploaded_file($image_tmp, "uploads/" . $new_image);
        } else {
            echo "<script>alert('Invalid image file type.');</script>";
        }
    }

    $query = "UPDATE uploads SET title = ?, description = ?, category = ?, price_per_day = ?, 
              facilities = ?, contact_email = ?, facebook_link = ?";
    if ($new_image) {
        $query .= ", image = ?";
    }
    $query .= " WHERE id = ? AND form_id = ?";

    if ($stmt = $conn->prepare($query)) {
        if ($new_image) {
            $stmt->bind_param("sssdsissii", $title, $description, $category, $price_per_day, $facilities, 
                $contact_email, $facebook_link, $new_image, $upload_id, $form_id);
        } else {
            $stmt->bind_param("sssdsisii", $title, $description, $category, $price_per_day, $facilities, 
                $contact_email, $facebook_link, $upload_id, $form_id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Update successful!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Error updating data.');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Upload</title>
        <link href="css/edit.css" rel="stylesheet">
    </head>
    <body>
        <h2>Edit Upload</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($upload_data['title']); ?>" required>
            <br>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($upload_data['description']); ?></textarea>
            <br>
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="lodge" <?= $upload_data['category'] === 'lodge' ? 'selected' : ''; ?>>Lodge</option>
                <option value="pension" <?= $upload_data['category'] === 'pension' ? 'selected' : ''; ?>>Pension</option>
                <option value="transient" <?= $upload_data['category'] === 'transient' ? 'selected' : ''; ?>>Transient</option>
            </select>
            <br>
            <label for="price_per_day">Price Per Day:</label>
            <input type="number" name="price_per_day" id="price_per_day" step="0.01" value="<?= htmlspecialchars($upload_data['price_per_day']); ?>" required>
            <br>
            <label for="facilities">Facilities:</label>
            <textarea name="facilities"><?= htmlspecialchars($upload_data['facilities']); ?></textarea>
            <br>
            <label for="contact_email">Contact Email:</label>
            <input type="email" name="contact_email" id="contact_email" value="<?= htmlspecialchars($upload_data['contact_email']); ?>" required>
            <br>
            <label for="facebook_link">Facebook Link:</label>
            <input type="url" name="facebook_link" id="facebook_link" value="<?= htmlspecialchars($upload_data['facebook_link']); ?>" required>
            <br>
            <label for="image">Replace Image:</label>
            <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
            <br>
            <button type="submit">Save Changes</button>
        </form>
    </body>
</html>
