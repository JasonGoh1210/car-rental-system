<?php
// user/home.php
include "include/header.php";
?>

<style>
/* =========================
   Hero Section
========================= */
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

/* Booking box */
.booking-box {
    background: rgba(255,255,255,0.95);
    padding: 20px;
    border-radius: 8px;
    color: #000;
}

/* =========================
   Featured Cars
========================= */
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
        <div class="row align-items-center">

            <!-- 左边文字 -->
            <div class="col-md-7">
                <p class="text-uppercase">New In Stock</p>
                <h1>Upgrade Your Driving Experience</h1>
                <p>
                    Rent premium cars at affordable prices with secure booking,
                    <strong>refundable deposit</strong> and real-time availability.
                </p>

                <a href="cars.php" class="btn btn-light me-2">Explore Vehicles</a>
            </div>

             <!-- 右边 Quick Booking -->
            <div class="col-md-5">
                <div class="booking-box">
                    <h5 class="mb-3">Quick Booking</h5>

                    <form action="carlist.php" method="GET" onsubmit="return validateBookingTime()">

                        <!-- 地点 -->
                        <div class="mb-2">
                            <label class="form-label">Pick-up Location (Melaka)</label>
                            <select name="location" class="form-select" required>
                                <option value="">-- Select Location --</option>
                                <option value="Melaka Tengah">Melaka Tengah</option>
                                <option value="Ayer Keroh">Ayer Keroh</option>
                                <option value="Batu Berendam">Batu Berendam</option>
                                <option value="Bukit Katil">Bukit Katil</option>
                                <option value="Klebang">Klebang</option>
                                <option value="Alor Gajah">Alor Gajah</option>
                                <option value="Jasin">Jasin</option>
                            </select>
                        </div>

                        <!-- Pick-up -->
                        <div class="mb-2">
                            <label class="form-label">Pick-up Date & Time</label>
                            <input type="datetime-local"
                                   name="start_datetime"
                                   id="start_datetime"
                                   class="form-control"
                                   required>
                        </div>

                        <!-- Return -->
                        <div class="mb-3">
                            <label class="form-label">Return Date & Time</label>
                            <input type="datetime-local"
                                   name="end_datetime"
                                   id="end_datetime"
                                   class="form-control"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Choose Car
                        </button>

                    </form>
                </div>
    </form>
</div>

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
            <!-- Car cards (不动) -->
        </div>
    </div>
</section>

<!-- ⏱ 时间限制 JS -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const startInput = document.getElementById("start_datetime");
    const endInput   = document.getElementById("end_datetime");

    // 现在时间 + 12 小时（最早可预订）
    const now = new Date();
    now.setHours(now.getHours() + 12);
    const minStart = now.toISOString().slice(0,16);

    startInput.min = minStart;
    endInput.min   = minStart;

    // 当 Pick-up 改变时
    startInput.addEventListener("change", function () {

        if (!startInput.value) return;

        const startDate = new Date(startInput.value);

        // Return = Pick-up + 24 小时
        const minReturn = new Date(startDate);
        minReturn.setHours(minReturn.getHours() + 24);

        endInput.min = minReturn.toISOString().slice(0,16);

        // 如果原本选的 Return 不合法，清掉
        if (endInput.value && new Date(endInput.value) < minReturn) {
            endInput.value = "";
        }
    });

});

// 最终防守（防手动改 HTML）
function validateBookingTime() {

    const start = new Date(document.getElementById("start_datetime").value);
    const end   = new Date(document.getElementById("end_datetime").value);

    // 12 小时规则
    const minStart = new Date();
    minStart.setHours(minStart.getHours() + 12);

    if (start < minStart) {
        alert("Booking must be made at least 12 hours in advance.");
        return false;
    }

    // 24 小时最少租期
    const minEnd = new Date(start);
    minEnd.setHours(minEnd.getHours() + 24);

    if (end < minEnd) {
        alert("Minimum rental period is 24 hours.");
        return false;
    }

    return true;
}
</script>

<?php
include "include/footer.php";
?>
