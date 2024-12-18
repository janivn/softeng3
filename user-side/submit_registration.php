<?php
session_start();

include("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $form_id = $_SESSION['user_data'];

    $check_form_query = "SELECT id FROM form WHERE id = ?";
    if ($stmt = $conn->prepare($check_form_query)) {
        $stmt->bind_param("i", $form_id);
        $stmt->execute();
        $stmt->bind_result($form_exists);
        $stmt->fetch();
        $stmt->close();

        if (empty($form_exists)) {
            echo "Error: form_id does not exist in the form table.";
            exit();
        }
    }

    $business_name = $_POST['business_name'];
    $business_type = $_POST['business_type'];
    $business_address = $_POST['business_address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $reg_number = $_POST['reg_number'];
    $num_rooms = $_POST['num_rooms'];
    $description = $_POST['description'];
    $owner_name = $_POST['owner_name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];

    if (isset($_POST['agreement']) && $_POST['agreement'] === 'on') {
        $agreement = 1;
    } else {
        $agreement = 0;
    }


    $sql = "INSERT INTO registration_form (
        form_id, business_name, business_type, business_address, 
        city, state, reg_number, num_rooms, 
        description, owner_name, contact_number, email, agreement
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            "isssssssssssi",
            $form_id,
            $business_name,
            $business_type,
            $business_address,
            $city,
            $state,
            $reg_number,
            $num_rooms,
            $description,
            $owner_name,
            $contact_number,
            $email,
            $agreement
        );

        if ($stmt->execute()) {
            header("Location: landingpage.html"); 
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
