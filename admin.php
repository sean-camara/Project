<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: sign-in.php");
    exit();
}

include 'connect.php'; // Make sure this connects to your database

// Get the user's info
$user_id = $_SESSION['user_id']; // You should store this during login
$query = "SELECT fname, lname, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($fname, $lname, $email);
$stmt->fetch();
$stmt->close();

// Fetch recent order with dishes and quantities
$recentOrderText = "No recent orders";
$sql = "
SELECT 
  o.order_id,
  DATE_FORMAT(o.order_time, '%l:%i %p') AS order_time_formatted,
  GROUP_CONCAT(CONCAT(d.dish_name, ' ', oi.quantity, 'x') SEPARATOR ', ') AS dishes_ordered
FROM orders o
JOIN order_items oi ON o.order_id = oi.order_id
JOIN dishes d ON oi.dish_id = d.dish_id
GROUP BY o.order_id
ORDER BY o.order_time DESC
LIMIT 1";

$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $recentOrderText = htmlspecialchars($row['dishes_ordered']) . ' ' . $row['order_time_formatted'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <link rel="stylesheet" href="admin-style.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins&family=Playfair+Display&display=swap"
        rel="stylesheet"
    />
</head>
<body>
    <div class="container">
        <div class="nav">
            <div class="logo-wt">
                <img id="logo" src="images/logo.jpg" alt="logo" />
                <h3 id="logo-text">Tonishen's Kitchen</h3>
            </div>

            <div class="profile">
                <i id="mail" class="fa-solid fa-envelope fa-3x"></i>
                <img id="profile-pic" src="images/user.png" alt="profile" />
                <div class="pro-des">
                    <p id="prof-name"><?php echo htmlspecialchars($fname . ' ' . $lname); ?></p>
                    <p>@<?php echo htmlspecialchars($email); ?></p>
                </div>
                <button id="logout-btn">Logout</button>
            </div>
        </div>

        <div class="category-con">
            <div class="category">
                <button id="dashboard">
                    <i class="fa-solid fa-table-columns"></i> Dashboard
                </button>
                <button id="menu-btn">
                    <i class="fa-solid fa-compass"></i> Menu
                </button>
                <button><i class="fa-solid fa-money-bill"></i> Sales Report</button>
                <button><i class="fa-solid fa-truck-fast"></i> Orders</button>
                <button><i class="fa-solid fa-chart-simple"></i> Top Selling</button>
                <button><i class="fa-solid fa-users"></i> User Account</button>
                <button><i class="fa-solid fa-toolbox"></i> Inventory</button>
                <button id="about-page-btn">
                    <i class="fa-solid fa-info-circle"></i> About Page
                </button>
            </div>

            <div class="welcome-page">
                <p id="welcome-user">Hi Sean,</p>
                <p id="overview">DashBoard Overview</p>

                <button id="add-dish-btn">
                    <i class="fa-solid fa-plus"></i> ADD NEW DISH
                </button>

                <div class="boxes">
                    <div class="total-sales">
                        <p class="title">Total Sales</p>
                        <p id="sales">₱578,943</p>
                    </div>

                    <div class="best-seller">
                        <p class="title">Best Seller Dish</p>
                        <p>Top 1</p>
                        <p id="best-dish">Kare-kare Overload</p>
                    </div>

                    <div class="total-orders">
                        <p class="title">Total Orders</p>
                        <p id="orders">19,045</p>
                    </div>

                    <div class="recent-order">
                        <p class="title">Recent Order</p>
                        <div class="recent-order-name">
                            <p><?php echo $recentOrderText; ?></p>
                        </div>
                    </div>

                    <div class="low-stock">
                        <p class="title">Low Stock Warning</p>
                        <table>
                            <tr>
                                <td>Spoon</td>
                                <td class="stock-count">12 left</td>
                            </tr>
                            <tr>
                                <td>Fork</td>
                                <td class="stock-count">12 left</td>
                            </tr>
                            <tr>
                                <td>Tissue</td>
                                <td class="stock-count">23 left</td>
                            </tr>
                        </table>
                    </div>

                    <div class="customer-fb">
                        <p class="title">Customer Feedback</p>
                        <div class="rating-prof">
                            <img id="user-image-fb" src="images/user.png" alt="user" />

                            <div class="rating-prof-details">
                                <p id="customer-fb-name">Bugoy na KoyKoy</p>
                                <p id="prof-email">@balagbag@gmail.com</p>
                                <div class="stars">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <div class="fb">
                            <p>
                                "Grabe napaka sarap yung kare-kare ang crispy
                                sheesh oorder ako ulit sobrang satisfied customer!!"
                            </p>
                        </div>
                    </div>

                    <div class="customerf">
                        <p class="title">Customer Favorites</p>
                        <p>
                            1. Kare-kare Overload
                            <span class="ratings">140 ratings</span>
                        </p>
                        <p>
                            2. Shawarma <span class="ratings">112 ratings</span>
                        </p>
                        <p>
                            3. Salted Egg Chicken
                            <span class="ratings">97 ratings</span>
                        </p>
                        <p>
                            4. Sisig <span class="ratings">78 ratings</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="admin.js"></script>

    <script>
        document.getElementById("logout-btn").addEventListener("click", function () {
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = "logout.php";
            }
        });
    </script>
</body>
</html>
