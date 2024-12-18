<?php
session_start();
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@350;400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
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
                    <a class="nav-link active actives" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mytransient.php">Transient</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pension.php">Pension</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="lodge.php">Lodge</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="myabout.html">About Us</a>
                </li>

                <?php if (isset($_SESSION['user_data'])): ?>
                    <button id="profileButton">Profile</button>
                    <div id="profilePopup" style="display: none;">
                        <ul>
                            <li><a href="registration_form.php">Register Your Platform</a></li>
                            <li><a href="profile.php">My Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ml-3" href="login.php">Log In</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>


    <div class="container-fluid p-0 mt-5">
        <section id="welcome1" class="full-height section1 d-flex flex-column align-items-center justify-content-between text-white bg-light">
            <div class="carousel slide" data-ride="carousel" id="heroCarousel">
                <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/img/3.jpg" class="d-block w-100" alt="...">
                </div>
                </div>
            </div>
        </section>
    </div>

            <section id="pictures" class="container mt-5">
                <h2>Explore Puerto Princesa</h2>
                <div class="row">
                    <div class="col-md-4 mb-3">
                    <img src="assets/img/traveloca.png" class="img-fluid" alt="Image 1">
                    </div>
                    <div class="col-md-4 mb-3">
                    <img src="assets/img/suitcase.png" class="img-fluid" alt="Image 2">
                    </div>
                    <div class="col-md-4 mb-3">
                    <img src="assets/img/takol.jpg" class="img-fluid" alt="Image 3">
                    </div>
                </div>
            </section>

            <section id="about" class="container mt-5">
                <h2>About Puerto Princesa Traveloca</h2>
                <p>
                    Puerto Princesa Traveloca is a leading online platform connecting travelers with a wide range of accommodation options in Puerto Princesa City, Palawan. We offer a user-friendly search experience to help you find the perfect place to stay, from budget-friendly transient houses to luxurious hotels and resorts. 
                </p>
                <p>
                    Our platform provides detailed information on each property, including photos, amenities, and guest reviews. You can also easily book your accommodation online with secure payment options. 
                </p>
            </section>

    <div class="text-container d-flex flex-column align-items-center" style="color: black;">
                    <h1 style="font-weight: bold;">Discover your best stay</h1>
                    <h4>At Puerto Princesa Traveloca</h4>
                    <a href="#about" class="pt-3"><button class="btn btn-success">Find out more</button></a>
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
