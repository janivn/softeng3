<?php
session_start();

if (!isset($_SESSION['user_data'])) {
    header("Location: login.php");
    exit();
}

$form_id = $_SESSION['user_data'];

include("db.php");

$query = "SELECT f.email, 
          r.business_name, r.business_type, r.business_address, 
          r.city, r.state, r.reg_number, 
          r.num_rooms, r.description, r.owner_name, r.contact_number, 
          r.email AS business_email, r.agreement
          FROM form AS f
          JOIN registration_form AS r ON f.id = r.form_id
          WHERE f.id = ?";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $form_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo '<div class="alert alert-warning">
                <strong>Your registration is incomplete.</strong> Please complete your registration to access all features.
                <a href="registration_form.php" class="btn btn-primary">Complete Registration</a>
              </div>';
        exit();
    }
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
    exit();
    
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price_per_day = $_POST['price_per_day'];
    $facilities = isset($_POST['facilities']) ? implode(", ", $_POST['facilities']) : '';
    $contact_email = $_POST['contact_email'];
    $facebook_link = $_POST['facebook_link'];

    $images = $_FILES['image'];
    $upload_success = true;
    $image_names = [];

    foreach ($images['name'] as $index => $image_name) {
        $image_tmp = $images['tmp_name'][$index];
        $image_error = $images['error'][$index];

        if ($image_error == 0) {
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png'];

            if (!in_array(strtolower($image_ext), $allowed_ext)) {
                echo "<script>alert('Invalid file type for image {$image_name}. Only JPG, JPEG, PNG are allowed.');</script>";
                $upload_success = false;
                continue;
            }

            $image_new_name = uniqid('', true) . '.' . $image_ext;
            $image_target = 'uploads/' . $image_new_name;

            if (move_uploaded_file($image_tmp, $image_target)) {
                $image_names[] = $image_new_name;
            } else {
                echo "<script>alert('Failed to upload image {$image_name}.');</script>";
                $upload_success = false;
            }
        }
    }

    if ($upload_success && count($image_names) > 0) {
        $image_names_string = implode(", ", $image_names);

        $query = "INSERT INTO uploads (form_id, image, title, description, category, price_per_day, facilities, contact_email, facebook_link) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("issssdsss", $form_id, $image_names_string, $title, $description, $category, $price_per_day, $facilities, $contact_email, $facebook_link);

            if ($stmt->execute()) {
                echo "<script>alert('Upload successful!'); window.location.href='profile.php';</script>";
            } else {
                echo "<script>alert('Error: Could not save data.');</script>";
            }
        } else {
            echo "<script>alert('Error preparing query: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('There was an issue uploading your images.');</script>";
    }
}

$query_images = "SELECT * FROM uploads WHERE form_id = ? ORDER BY created_at DESC";
if ($stmt_images = $conn->prepare($query_images)) {
    $stmt_images->bind_param("i", $form_id);
    $stmt_images->execute();
    $result_images = $stmt_images->get_result();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">
</head>
<body>

<div class="profile-container">
    <h2>User Profile</h2>
    <div class="profile-item"><span>Email:</span> <?= htmlspecialchars($row['email']); ?></div>
    <h3>Business Information</h3>
    <div class="profile-item"><span>Business Name:</span> <?= htmlspecialchars($row['business_name']); ?></div>
    <div class="profile-item"><span>Business Type:</span> <?= htmlspecialchars($row['business_type']); ?></div>
    <div class="profile-item"><span>Address:</span> <?= htmlspecialchars($row['business_address']); ?></div>
    <div class="profile-item"><span>City:</span> <?= htmlspecialchars($row['city']); ?></div>
    <div class="profile-item"><span>Province:</span> <?= htmlspecialchars($row['state']); ?></div>
    <div class="profile-item"><span>Registration Number:</span> <?= htmlspecialchars($row['reg_number']); ?></div>
    <div class="profile-item"><span>Number of Rooms:</span> <?= htmlspecialchars($row['num_rooms']); ?></div>
    <div class="profile-item"><span>Description:</span> <?= htmlspecialchars($row['description']); ?></div>
    <h3>Contact Information</h3>
    <div class="profile-item"><span>Owner Name:</span> <?= htmlspecialchars($row['owner_name']); ?></div>
    <div class="profile-item"><span>Contact Number:</span> <?= htmlspecialchars($row['contact_number']); ?></div>
    <div class="profile-item"><span>Business Email:</span> <?= htmlspecialchars($row['business_email']); ?></div>
</div>
<table border="1" cellspacing="0" cellpadding="10">
    <tr>
        <th>Create a post:</th>
    </tr>
    <form action="profile.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <tr>
            <td><label for="title">Title:</label></td>
            <td><textarea name="title" id="title" required></textarea></td>
        </tr>

        <tr>
            <td><label for="description">Description:</label></td>
            <td><textarea name="description" id="description" required></textarea></td>
        </tr>

        <tr>
            <td><label for="category">Category:</label></td>
            <td>
                <select name="category" id="category" required>
                    <option value="lodge">Lodge</option>
                    <option value="pension">Pension</option>
                    <option value="transient">Transient</option>
                </select>
            </td>
        </tr>

        <tr>
            <td><label for="price_per_day">Price Per Day:</label></td>
            <td><input type="number" name="price_per_day" id="price_per_day" step="0.01" required></td>
        </tr>

        <tr>
            <td><label for="facilities">Facilities:</label></td>
            <td>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="facilities[]" value="Wi-Fi"> Wi-Fi</label><br><br>
                    <label><input type="checkbox" name="facilities[]" value="Parking"> Parking</label>
                    <label><input type="checkbox" name="facilities[]" value="Room Service"> Room Service</label>
                    <label><input type="checkbox" name="facilities[]" value="Laundry"> Laundry</label>
                    <label><input type="checkbox" name="facilities[]" value="Air Conditioning"> Air Conditioning</label>
                    <label><input type="checkbox" name="facilities[]" value="Kitchenette"> Kitchenette</label>
                    <label><input type="checkbox" name="facilities[]" value="Swimming Pool"> Swimming Pool</label>
                    <label><input type="checkbox" name="facilities[]" value="Gym"> Gym</label>
                </div>
            </td>
        </tr>

        <tr>
            <td><label for="image">Images:</label></td>
            <td><input type="file" name="image[]" id="image" accept=".jpg, .jpeg, .png" multiple required></td>
        </tr>

        <tr>
            <td><label for="contact_email">Contact Email:</label></td>
            <td><input type="email" name="contact_email" id="contact_email" placeholder="@gmail.com" required></td>
        </tr>

        <tr>
            <td><label for="facebook_link">Facebook Link:</label></td>
            <td><input type="url" name="facebook_link" id="facebook_link" placeholder="https://facebook.com/yourpage" required></td>
        </tr>

        <tr>
            <td colspan="2"><button type="submit" name="submit">Submit</button></td>
        </tr>
    </form>
</table>

<h3>Uploaded Images</h3>
<table border="1" cellspacing="0" cellpadding="10">
<thead>
    <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Category</th>
        <th>Description</th>
        <th>Price Per Day</th>
        <th>Facilities</th>
        <th>Email</th>
        <th>Facebook</th>
        <th>Created At</th>
    </tr>
</thead>
<tbody>
    <?php
    if ($result_images && $result_images->num_rows > 0) {
        while ($image_row = $result_images->fetch_assoc()) {
            $image_path = 'uploads/' . htmlspecialchars($image_row['image']);
            $image_exists = file_exists($image_path);
            ?>
            <tr>
                <td>
                    <?php if ($image_exists) : ?>
                        <img src="<?= $image_path; ?>" alt="Image" width="100">
                    <?php else : ?>
                        <span style="color: red;">Image not found</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($image_row['title']); ?></td>
                <td><?= htmlspecialchars($image_row['category']); ?></td>
                <td><?= htmlspecialchars($image_row['description']); ?></td>
                <td><?= htmlspecialchars($image_row['price_per_day']); ?></td>
                <td><?= htmlspecialchars($image_row['facilities']); ?></td>
                <td><?= htmlspecialchars($image_row['contact_email']); ?></td>
                <td>
                    <?php if (!empty($image_row['facebook_link'])) : ?>
                        <a href="<?= htmlspecialchars($image_row['facebook_link']); ?>" target="_blank">
                            <?= htmlspecialchars($image_row['facebook_link']); ?>
                        </a>
                    <?php else : ?>
                        Not provided
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($image_row['created_at']); ?></td> 
                <td>
                    
                    <a href="edit_upload.php?id=<?= $image_row['id']; ?>" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='9'>No images uploaded yet.</td></tr>";
    }
    ?>
</tbody>
</table>
</div>
</body>
</html>
