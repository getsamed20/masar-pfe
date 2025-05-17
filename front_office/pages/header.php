<?php
session_start();
include('../includes/db.php');

$the_username = 'Guest';
$role = 'guest';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        $role = $user['role'];

        if ($role == 'startup') {
            $startup_query = mysqli_query($conn, "SELECT startup_name FROM startups WHERE user_id = '{$user['user_id']}'");
            $startup = mysqli_fetch_assoc($startup_query);
            $the_username = $startup['startup_name'];
        } elseif ($role == 'institution') {
            $institution_query = mysqli_query($conn, "SELECT institution_name FROM public_institutions WHERE user_id = '{$user['user_id']}'");
            $institution = mysqli_fetch_assoc($institution_query);
            $the_username = $institution['institution_name'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Masar Platform - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
    }

    .header-container {
      background-image: url('masar.png');
      background-size: cover;
      background-position: top;
      background-repeat: no-repeat;
      height: 100vh;
      color: white;
      position: relative;
      overflow: hidden;
    }

/* Mobile override background */
@media (max-width: 768px) {
  .header-container {
    background-image: url('pg2.png');
  }
}

.custom-navbar {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 10;
}

/* Push content down inside the header */
.header-content {
  padding-top: 300px;
  max-width: 50%;
  color: white;
}

@media (max-width: 768px) {
  .header-content {
    padding-top: 200px;
    max-width: 100%;
    text-align: center;
  }
}


    .main-heading {
      font-family: 'IBM Plex Sans', sans-serif;
      font-weight: 700;
      font-size: 80px;
      line-height: 1.1;
      margin-bottom: 24px;
    }

    @media (max-width: 768px) {
      .main-heading {
        font-size: 42px;
      }
    }

    .career-text {
      color: #02FA72;
    }

    .sub-heading {
      font-family: 'IBM Plex Sans', sans-serif;
      font-weight: 700;
      font-size: 24px;
      margin-bottom: 40px;
    }

    @media (max-width: 768px) {
      .sub-heading {
        font-size: 18px;
        padding: 0 10px;
      }
    }

    .join-btn {
      background-color: #02FA72;
      color: #0C1BA3;
      font-family: 'IBM Plex Sans', sans-serif;
      font-weight: 700;
      font-size: 16px;
      padding: 12px 24px;
      border: none;
      border-radius: 4px;
      display: inline-flex;
      align-items: center;
    }

    .join-btn:hover {
      opacity: 0.9;
    }

    .arrow-icon {
      margin-left: 10px;
    }

    .supported-by {
      position: absolute;
      bottom: 40px;
      left: 0;
      right: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'IBM Plex Sans', sans-serif;
      font-weight: 700;
      font-size: 24px;
    }

    .supported-by span {
      color: #02FA72;
      margin-left: 4px;
    }

    .support-arrow {
      margin: 0 15px;
    }
  </style>
</head>
<body>
  <div class="custom-navbar">
    <?php include('navbar.php'); ?>
  </div>

  <div class="header-container">
    <div class="container">
      <div class="header-content">
        <h1 class="main-heading">Securing every <span class="career-text" id="changing-text">Career</span> Path</h1>
        <p class="sub-heading">Bridging startups and institutions to co-create impactful road safety solutions.</p>
        <button class="join-btn">
          Join the community <img src="arrow-right.png" alt="Arrow" class="arrow-icon">
        </button>
      </div>
    </div>

    <div class="supported-by">
      <img src="arrow1.png" alt="Arrow" class="support-arrow">
      <div>Supported by <span>ONSR</span></div>
      <img src="arrow2.png" alt="Arrow" class="support-arrow">
    </div>
  </div>

  <script>
    (() => {
      const texts = ["Career", "Road", "Project"];
      let index = 0;
      const el = document.getElementById("changing-text");

      setInterval(() => {
        index = (index + 1) % texts.length;
        el.textContent = texts[index];
      }, 2000);
    })();
  </script>
</body>
</html>
