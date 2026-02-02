<?php
// user/home.php
include "include/header.php";
?>

<style>
    /* Hero Section */
    .hero {
        background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                    url("assets/images/home.jpg") center/cover no-repeat;
        height: 80vh;
        color: #fff;
        display: flex;
        align-items: center;
    }

    .hero h1 {
        font-size: 3rem;
        font-weight: bold;
    }

    /* Featured Cars */
    .car-card img {
        height: 200px;
        object-fit: cover;
    }

    .badge-rented {
        position: absolute;
        top: 10px;
        left: 10px;
    }
</style>

<!-- 🔹 HERO SECTION -->
<section class="hero">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <p class="text-uppercase">New In Stock</p>
                <h1>Upgrade Your Driving Experience</h1>
                <p>
                    Rent premium cars at affordable prices with secure booking,
                    <strong>refundable deposit</strong> and real-time availability.
                </p>

                <a href="cars.php" class="btn btn-light me-2">Explore Vehicles</a>
                <a href="booking.php" class="btn btn-outline-light">Book Now</a>
            </div>
        </div>
    </div>
</section>

<!-- 🔹 FEATURED VEHICLES -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Featured Vehicles</h2>
                <p class="text-muted">Top rated cars chosen by our customers</p>
            </div>
            <a href="cars.php">View All →</a>
        </div>

        <div class="row g-4">

            <!-- Car 1 -->
            <div class="col-md-4">
                <div class="card car-card position-relative">
                    <img src="../assets/images/car1.jpg" class="card-img-top" alt="BMW X5">
                    <div class="card-body">
                        <h5>BMW X5</h5>
                        <p class="text-muted">2023</p>
                        <p><strong>$150</strong> / day</p>
                        <a href="booking.php?car_id=1" class="btn btn-dark w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Car 2 (Rented) -->
            <div class="col-md-4">
                <div class="card car-card position-relative">
                    <span class="badge bg-danger badge-rented">RENTED</span>
                    <img src="../assets/images/car2.jpg" class="card-img-top" alt="Mercedes C-Class">
                    <div class="card-body">
                        <h5>Mercedes C-Class</h5>
                        <p class="text-muted">2024</p>
                        <p><strong>$130</strong> / day</p>
                        <button class="btn btn-secondary w-100" disabled>
                            Unavailable
                        </button>
                    </div>
                </div>
            </div>

            <!-- Car 3 -->
            <div class="col-md-4">
                <div class="card car-card position-relative">
                    <img src="../assets/images/car3.jpg" class="card-img-top" alt="Tesla Model Y">
                    <div class="card-body">
                        <h5>Tesla Model Y</h5>
                        <p class="text-muted">2023</p>
                        <p><strong>$110</strong> / day</p>
                        <a href="booking.php?car_id=3" class="btn btn-dark w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
include "include/footer.php";
?>
