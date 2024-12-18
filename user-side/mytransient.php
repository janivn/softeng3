<?php
session_start();

include("db.php");

$query_images = "SELECT image, title, description, category, price_per_day, facilities, contact_email, facebook_link FROM uploads WHERE category = 'transient' ORDER BY created_at DESC"; 
if ($stmt_images = $conn->prepare($query_images)) {
    $stmt_images->execute();
    $result_images = $stmt_images->get_result();
} else {
    echo "Error fetching images: " . $conn->error;
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Puerto Princesa Traveloca</title>
    <link rel="icon" type="image/x-icon" href="assets/img/member/ivn.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@350;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/transient.css" rel="stylesheet">
</head>

<body id="page-top">
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand d-flex align-items-center" href="#page-top">
            <img src="assets/img/traveloca.png" height="50" class="d-inline-block align-top" alt="Logo">
            <h6>Puerto Princesa Traveloca</h6>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active actives" href="mytransient.php">Transient</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Pension.php">Pension</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Lodge.php">Lodge</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="myabout.html">About Us</a>

                <?php if (isset($_SESSION['user_data'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Profile
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="registration_form.php">Register Your Platform</a>
                                <a class="dropdown-item" href="profile.php">My Profile</a>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ml-3" href="login.php">Log In</a>
                    </li>
                <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid p-0 mt-5">
        <div class="pt-5 bg-light">
            <div class="container-fluid w-75 py-4">
                <div class="search-container d-flex justify-content-end pb-3">
                    <input type="text" class="form-control custom-search w-25" id="searchQuery" placeholder="Search">
                    <button class="btn btn-outline-success" type="button" onclick="search()">Search</button>
                    <script>
                        function search() {
                            var query = document.getElementById('searchQuery').value.toLowerCase().trim();
                            if (query.includes('transient') || query.includes('puerto princesa')) {
                                window.location.href = 'https://www.google.com/search?q=transient+houses+in+Puerto+Princesa';
                                
                            } else {
                                alert('No results found.');
                            }
                        }
                    </script>
                </div>

    <div class="container-fluid py-4">
        <?php 
        if ($result_images->num_rows > 0) {
            while ($image_row = $result_images->fetch_assoc()) : ?>
                <div class="transient-container w-100 py-4 mb-5 card bg-light rounded">
                    <div class="row justify-content-around align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <img src="uploads/<?= htmlspecialchars($image_row['image']); ?>" height="350" 
                                class="d-block w-100 rounded" alt="Image">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="details text-center">
                                <h1 class="fw-bold" style="font-weight: bold;"><?= htmlspecialchars($image_row['title']); ?></h1>
                                <p class="text-muted"><?= htmlspecialchars($image_row['description']); ?></p>
                                <hr class="divider w-75" style="border: 1px solid rgba(19, 93, 33, 0.648);">
                                <h3 class="my-3">Facilities</h3>
                                <ul class="list-unstyled text-muted">
                                    <li><?= htmlspecialchars($image_row['facilities']); ?></li>
                                </ul>
                                <hr class="divider w-75" style="border: 1px solid rgba(19, 93, 33, 0.648);">
                                <div class="price my-3">
                                    <p class="text-secondary">Price:</p>
                                    <h3 class="fw-bold text-primary">₱ <?= htmlspecialchars($image_row['price_per_day']); ?></h3>
                                </div>
                                <p class="text-secondary">Contact Us:</p>
                                <div class="d-flex justify-content-around">
                                    <?php if (isset($image_row['facebook_link']) && !empty($image_row['facebook_link'])) : ?>
                                        <a href="<?= htmlspecialchars($image_row['facebook_link']); ?>" target="_blank">
                                            <img src="assets/img/facebook.png" alt="Facebook" height="35">
                                        </a>
                                    <?php endif; ?>
                                    <a href="mailto:<?= htmlspecialchars($image_row['contact_email']); ?>" target="_blank">
                                        <img src="assets/img/gmail.png" alt="Email" height="35">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php endwhile; 
        } else {
            echo "<p class='text-muted text-center'></p>";
        }
        ?>
    </div>

                <div class="transient-container w-100 py-4 mb-5 card bg-light rounded">
                    <div class="row justify-content-around align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div id="aisCarousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="assets/img/Aigs Homestay/b1.png" height="350"
                                            class="d-block w-100 rounded" alt="">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="assets/img/Aigs Homestay/b2.png" height="350"
                                            class="d-block w-100 rounded" alt="">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="assets/img/Aigs Homestay/b3.png" height="350"
                                            class="d-block w-100 rounded" alt="">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#aisCarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#aisCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <div class="details d-flex justify-content-between px-3">
                                <div class="price pt-4">
                                    <p class="text-secondary">Price:</p>
                                    <h3 class="fw-bold text-primary">₱ 2,098.00</h3>
                                </div>
                                <div class="contact pt-4">
                                    <p class="text-secondary">Contact Us:</p>
                                    <div class="d-flex justify-content-around">
                                        <a href="https://m.facebook.com/tropicalkizzhomestay/" target="_blank">
                                            <img src="assets/img/facebook.png" alt="Facebook" height="35">
                                        </a>
                                        <a href="mailto:richkizzapartment@gmail.com" target="_blank">
                                            <img src="assets/img/gmail.png" alt="Email" height="35">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="details text-center">
                                <h1 class="fw-bold" style="font-weight: bold;">AIS'G HOMESTAY</h1>
                                <p class="text-muted">"Feel your home at Ais'G"</p>
                                <hr class="divider w-75" style="border: 1px solid rgba(19, 93, 33, 0.648);">
                                <h3 class="my-3">Facilities</h3>
                                <ul class="list-unstyled text-muted">
                                    <li>2-Bedroom Space</li>
                                    <li>Airport transfer</li>
                                    <li>Car park</li>
                                    <li>Shuttle service</li>
                                    <li>Free Wi-Fi in all rooms</li>
                                    <li>Spa</li>
                                    <li>BBQ facilities</li>
                                    <li>Express check-in/out</li>
                                    <li>Luggage storage</li>
                                </ul>
                                <hr class="divider w-75" style="border: 1px solid rgba(19, 93, 33, 0.648);">
                                <button class="btn btn-success my-2 w-75"><a
                                        href="https://www.google.com/maps/place/Ais%E2%80%99G+Homestay/@9.777788,118.7363761,704m/data=!3m2!1e3!4b1!4m9!3m8!1s0x33b5634652aeb847:0x567bef086d33e713!5m2!4m1!1i2!8m2!3d9.7777827!4d118.738951!16s%2Fg%2F11h282whv2?authuser=0&entry=ttu"
                                        class="text-light text-decoration-none">Location</a></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="transient-container w-100 py-4 mb-5 card bg-light rounded">
                    <div class="row justify-content-around align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="details text-center">
                                <h1 class="fw-bold" style="font-weight: bold;">RICHKIZZ TRANSIENT</h1>
                                <p class="text-muted">"Stay rich at Richkizz Transient"</p>
                                <hr class="divider w-75" style="border: 1px solid rgba(19, 93, 33, 0.648);">
                                <h3 class="my-3">Facilities</h3>
                                <ul class="list-unstyled text-muted">
                                    <li>2-Bedroom Space</li>
                                    <li>Airport transfer</li>
                                    <li>Car park</li>
                                    <li>Shuttle service</li>
                                    <li>Free Wi-Fi in all rooms</li>
                                    <li>Spa</li>
                                    <li>BBQ facilities</li>
                                    <li>Express check-in/out</li>
                                    <li>Luggage storage</li>
                                </ul>
                                <hr class="divider w-75" style="border: 1px solid rgba(19, 93, 33, 0.648);">
                                <button class="btn btn-success my-2 w-75"><a
                                        href="https://www.google.com/maps/place/Richkizz+Transient+Inn/@9.7486176,118.745282,20z/data=!4m14!1m7!3m6!1s0x2a7e7e9dd7587f77:0xf50312c9b30ac7ec!2sRichkizz+Transient+Inn!8m2!3d9.7484782!4d118.7454201!16s%2Fg%2F11l1nywgby!3m5!1s0x2a7e7e9dd7587f77:0xf50312c9b30ac7ec!8m2!3d9.7484782!4d118.7454201!16s%2Fg%2F11l1nywgby?authuser=0&entry=ttu"
                                        class="text-light text-decoration-none">Location</a></button>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div id="richCarousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="assets/img/TRANSIENT PIC_S/Richkizz.jpg" height="350"
                                            class="d-block w-100 rounded" alt="">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="assets/img/TRANSIENT PIC_S/514649280.jpg" height="350"
                                            class="d-block w-100 rounded" alt="">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="assets/img/TRANSIENT PIC_S/514649332.jpg" height="350"
                                            class="d-block w-100 rounded" alt="">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#richCarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#richCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <div class="details d-flex justify-content-between px-3">
                                <div class="price pt-4">
                                    <p class="text-secondary">Price:</p>
                                    <h3 class="fw-bold text-primary">₱ 2,098.00</h3>
                                </div>
                                <div class="contact pt-4">
                                    <p class="text-secondary">Contact Us:</p>
                                    <div class="d-flex justify-content-around">
                                        <a href="https://m.facebook.com/tropicalkizzhomestay/" target="_blank">
                                            <img src="assets/img/facebook.png" alt="Description of Image" height="35">
                                        </a>
                                        <a href="mailto:richkizzapartment@gmail.com" target="_blank">
                                            <img src="assets/img/gmail.png" alt="Description of Image" height="35">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="transient-container w-100 py-4 mb-5 card bg-light rounded">
                    <div class="row justify-content-around align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div id="justCarousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="assets/img/Justbelle Homes/144612731_1539235732947850_297093035919964500_n.jpg"
                                            height="350" class="d-block w-100 rounded" alt="">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="assets/img/Justbelle Homes/270630271_633818397936594_7298230933684418995_n.jpg"
                                            height="350" class="d-block w-100 rounded" alt="">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="assets/img/Justbelle Homes/269930230_1128541314557538_9197266554297780712_n.jpg"
                                            height="350" class="d-block w-100 rounded" alt="">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#justCarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#justCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <div class="details d-flex justify-content-between px-3">
                                <div class="price pt-4">
                                    <p class="text-secondary">Price:</p>
                                    <h3 class="fw-bold text-primary">₱ 2,098.00</h3>
                                </div>
                                <div class="contact pt-4">
                                    <p class="text-secondary">Contact Us:</p>
                                    <div class="d-flex justify-content-around">
                                        <a href="https://www.facebook.com/share/MP1uFsBdP2hy4q61/" target="_blank">
                                            <img src="assets/img/facebook.png" alt="Facebook" height="35">
                                        </a>
                                        <a href="tel: 09215505628 Mr. Agustin G. Cervantes (owner)" target="_blank">
                                            <img src="assets/img/2697655_apple_phone_call_cell_emergency_icon.png"
                                                alt="Email" height="35">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="details text-center">
                                <h1 class="fw-bold" style="font-weight: bold;">JUSTBELLE HOMESTAY</h1>
                                <p class="text-muted">"Feel your home at Justbelle"</p>
                                <hr class="divider w-75" style="border: 1px solid rgba(19, 93, 33, 0.648);">
                                <h3 class="my-3">Facilities</h3>
                                <ul class="list-unstyled text-muted">
                                    <li>Pets allowed</li>
                                    <li>Car park</li>
                                    <li>Smoking area</li>
                                    <li>Security [24-hour]</li>
                                    <li>Kitchen</li>
                                    <li>Refrigerator</li>
                                    <li>Kitchen basics (cookware, oils, condiments)</li>
                                    <li>Dishes and dining utensils</li>
                                </ul>
                                <hr class="divider w-75" style="border: 1px solid rgba(19, 93, 33, 0.648);">
                                <button class="btn btn-success my-2 w-75"><a
                                        href="https://www.google.com/maps/place/Justbelle+Homes/@9.7735882,118.7539586,454m/data=!3m1!1e3!4m6!3m5!1s0x33b563001f072bc3:0x1ff09b4d508223f3!8m2!3d9.7739801!4d118.7559605!16s%2Fg%2F11vm1k4f9m?authuser=0&entry=ttu"
                                        class="text-light text-decoration-none">Location</a></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="transient-container w-100 py-4 mb-5 card bg-light rounded">
                    <div class="row justify-content-around align-items-center">

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="details text-center">
                                <h1 class="fw-bold" style="font-weight: bold;">Jackie’s Homestay</h1>
                                <p class="text-muted">"Stay fresh at Jackie's Homestay"</p>
                                <hr class="divider w-75" style="border: 1px solid rgba(19, 93, 33, 0.648);">
                                <h3 class="my-3">Facilities</h3>
                                <ul class="list-unstyled text-muted">
                                    <li>2-Bedroom Space</li>
                                    <li>Airport transfer</li>
                                    <li>Car park</li>
                                    <li>Shuttle service</li>
                                    <li>Free Wi-Fi in all rooms</li>
                                    <li>Spa</li>
                                    <li>BBQ facilities</li>
                                    <li>Express check-in/out</li>
                                    <li>Luggage storage</li>
                                </ul>
                                <hr class="divider w-75" style="border: 1px solid rgba(19, 93, 33, 0.648);">
                                <button class="btn btn-success my-2 w-75"><a
                                        href="https://www.google.com/maps/place/Richkizz+Transient+Inn/@9.7486176,118.745282,20z/data=!4m14!1m7!3m6!1s0x2a7e7e9dd7587f77:0xf50312c9b30ac7ec!2sRichkizz+Transient+Inn!8m2!3d9.7484782!4d118.7454201!16s%2Fg%2F11l1nywgby!3m5!1s0x2a7e7e9dd7587f77:0xf50312c9b30ac7ec!8m2!3d9.7484782!4d118.7454201!16s%2Fg%2F11l1nywgby?authuser=0&entry=ttu"
                                        class="text-light text-decoration-none">Location</a></button>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div id="jackieCarousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="assets/img/member/ivn.png" height="350" class="d-block w-100 rounded"
                                            alt="">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="assets/img/member/joni.png" height="350" class="d-block w-100 rounded"
                                            alt="">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="assets/img/member/aubrey.jpg" height="350" class="d-block w-100 rounded"
                                            alt="">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#jackieCarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#jackieCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <div class="details d-flex justify-content-between px-3">
                                <div class="price pt-4">
                                    <p class="text-secondary">Price:</p>
                                    <h3 class="fw-bold text-primary">₱ 2,098.00</h3>
                                </div>
                                <div class="contact pt-4">
                                    <p class="text-secondary">Contact Us:</p>
                                    <div class="d-flex justify-content-around">
                                        <a href="https://m.facebook.com/tropicalkizzhomestay/" target="_blank">
                                            <img src="assets/img/facebook.png" alt="Description of Image" height="35">
                                        </a>
                                        <a href="mailto:richkizzapartment@gmail.com" target="_blank">
                                            <img src="assets/img/gmail.png" alt="Description of Image" height="35">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="bg-light py-3">
            <div class="container px-4 px-lg-5">
                <div class="small text-center text-muted">Copyright &copy; 2024 - Puerto Princesa Traveloca</div>
            </div>
        </footer>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js" defer></script>
</body>

</html>