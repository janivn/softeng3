<?php
session_start();

if (!isset($_SESSION['user_data'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Registration Form</title>
    <link href="css/registration.css" rel="stylesheet">
</head>
<body>

<div class="registration-form">
    <h2>Business Registration Form</h2>
    <form action="submit_registration.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="form_id" value="<?= $user_id; ?>">

        <div class="form-group">
            <label for="business-name">Business Name</label>
            <input type="text" id="business-name" name="business_name" required>
        </div>

        <div class="form-group">
            <label for="business-type">Business Type</label>
            <select id="business-type" name="business_type" required>
                <option value="transient">Transient</option>
                <option value="lodge">Lodge</option>
                <option value="pension">Pension</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="business-address">Business Address</label>
            <input type="text" id="business-address" name="business_address" required>
        </div>

        <div class="form-group">
            <label for="city">City</label>
            <input type="text" id="city" name="city" required>
        </div>

        <div class="form-group">
            <label for="state">Province</label>
            <input type="text" id="state" name="state" required>
        </div>

        <div class="form-group">
            <label for="reg-number">Business Registration Number</label>
            <input type="number" id="reg-number" name="reg_number" required>
        </div>
        <div class="form-group">
            <label for="rooms">Number of Rooms Available</label>
            <input type="number" id="rooms" name="num_rooms" required>
        </div>

        <div class="form-group">
            <label for="description">Description of the Business</label>
            <textarea id="description" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="owner-name">Owner's Full Name</label>
            <input type="text" id="owner-name" name="owner_name" required>
        </div>

        <div class="form-group">
            <label for="contact-number">Contact Number</label>
            <input type="number" id="contact-number" name="contact_number" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div>
        <label>
            <input type="checkbox" name="agreement" required>
            I agree to the terms and conditions
        </label>
    </div>
    <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
