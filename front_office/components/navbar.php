<?php
include('../includes/db.php');
$loggedIn = false;
$profileLink = '#';
$messagesLink = '#';
$newMessages = false;

if (isset($_SESSION['email'])) {
    $loggedIn = true;
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($query);
    $role = $user['role'];
    $userId = $user['user_id'];
    $profilePicture = '../images/default-profile.png';

    if ($role == 'startup') {
        $profileQuery = mysqli_query($conn, "SELECT logo FROM startups WHERE user_id = '$userId'");
        $profileData = mysqli_fetch_assoc($profileQuery);
        if (!empty($profileData['logo'])) {
            $profilePicture = '../uploads/' . $profileData['logo'];
        }
    } else {
        $profileQuery = mysqli_query($conn, "SELECT logo FROM public_institutions WHERE user_id = '$userId'");
        $profileData = mysqli_fetch_assoc($profileQuery);
        if (!empty($profileData['logo'])) {
            $profilePicture = '../uploads/' . $profileData['logo'];
        }
    }

    $messageQuery = mysqli_query($conn, "SELECT * FROM messages WHERE receiver_id = '$userId' AND seen = 0");
    if (mysqli_num_rows($messageQuery) > 0) {
        $newMessages = true;
    }

    $profileLink = ($role == 'startup') 
        ? '../startup_profile/startup_profile.php' 
        : '../public_institution_profile/public_institution_profile.php';
    $messagesLink = "../chat/chat.php?id=$userId&type=$role";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Navbar Responsive Right Slide</title>

<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />

<style>
  body {
    font-family: 'IBM Plex Sans', sans-serif;
    margin: 0;
    background: #222;
  }

  .navbar {
    padding: 15px 0;
    background: transparent;
    z-index: 1000;
  }

  .navbar-brand img {
    height: 40px;
  }

  .nav-link {
    font-size: 16px;
    font-weight: 500;
    color: #000 !important;
    margin: 0 8px;
    padding: 8px 12px !important;
    cursor: pointer;
    position: relative;
    transition: color 0.3s ease;
  }

  .nav-link.active {
    color: #0C1BA3 !important;
  }

  .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 12px;
    right: 12px;
    height: 2px;
    background-color: #0C1BA3;
    transform: scaleX(1);
    transition: transform 0.3s ease;
  }

  .nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 12px;
    right: 12px;
    height: 2px;
    background-color: #0C1BA3;
    transform: scaleX(0);
    transition: transform 0.3s ease;
  }

  .nav-link:hover {
    color: #0C1BA3 !important;
  }

  .nav-link:hover::after {
    transform: scaleX(1);
  }

  .profile-pic {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #0C1BA3;
    display: block;
    cursor: pointer;
  }

  .message-icon {
    width: 32px;
    height: 32px;
    cursor: pointer;
    transition: transform 0.2s ease;
  }

  .message-icon:hover {
    transform: scale(1.1);
  }

  .notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    transform: translate(25%, -25%);
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #dc3545;
    border: 2px solid #222;
  }

  .mobile-notification-badge {
    position: absolute;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #dc3545;
  }

  .message-item {
    position: relative;
    display: flex;
    align-items: center;
    margin-right: 15px;
  }

  .mobile-message-item {
    position: relative;
    display: inline-block;
    margin-right: 10px;
  }

  .profile-dropdown {
    position: absolute;
    top: 55px;
    right: 0;
    background: #333;
    border: 1px solid #444;
    border-radius: 6px;
    width: 150px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    display: none;
    z-index: 1050;
  }

  .profile-dropdown a {
    display: block;
    padding: 10px 15px;
    color: #eee;
    text-decoration: none;
    font-weight: 500;
  }

  .profile-dropdown a:hover {
    background: #0C1BA3;
    color: #111;
  }

  .nav-item.profile-container {
    position: relative;
    margin-left: 5px;
  }

  .navbar-nav {
    display: flex;
    align-items: center;
  }

  .offcanvas-custom {
    position: fixed;
    top: 0;
    right: -300px;
    height: 100%;
    width: 300px;
    background-color:rgb(14, 7, 25);
    z-index: 2000;
    padding: 20px;
    transition: right 0.3s ease-in-out;
    overflow-y: auto;
  }

  .offcanvas-custom.show {
    right: 0;
  }

  .offcanvas-custom .close-btn {
    color: #fff;
    font-size: 28px;
    font-weight: bold;
    background: none;
    border: none;
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
    transition: transform 0.3s ease;
  }

  .offcanvas-custom .close-btn:hover {
    transform: rotate(90deg);
  }

  .offcanvas-custom ul {
    padding: 60px 0 0 0;
    list-style: none;
  }

  .offcanvas-custom .nav-link {
    color: #fff !important;
    font-size: 18px;
    display: block;
    margin-bottom: 12px;
    padding: 8px 0 !important;
    position: relative;
  }

  .offcanvas-custom .nav-link.active {
    color: #0C1BA3 !important;
  }

  .offcanvas-custom .nav-link.active::after {
    left: 0;
    right: 0;
  }

  .custom-toggler {
    width: 30px;
    height: 24px;
    position: relative;
    transform: rotate(0deg);
    transition: .5s ease-in-out;
    cursor: pointer;
    border: none;
    background: transparent;
    padding: 0;
    outline: none;
    display: none;
  }

  .custom-toggler span {
    display: block;
    position: absolute;
    height: 3px;
    width: 100%;
    background: #0C1BA3;
    border-radius: 3px;
    opacity: 1;
    left: 0;
    transform: rotate(0deg);
    transition: .25s ease-in-out;
  }

  .custom-toggler span:nth-child(1) {
    top: 0px;
  }

  .custom-toggler span:nth-child(2),
  .custom-toggler span:nth-child(3) {
    top: 10px;
  }

  .custom-toggler span:nth-child(4) {
    top: 20px;
  }

  .custom-toggler.open span:nth-child(1) {
    top: 10px;
    width: 0%;
    left: 50%;
  }

  .custom-toggler.open span:nth-child(2) {
    transform: rotate(45deg);
  }

  .custom-toggler.open span:nth-child(3) {
    transform: rotate(-45deg);
  }

  .custom-toggler.open span:nth-child(4) {
    top: 10px;
    width: 0%;
    left: 50%;
  }

  @media (max-width: 991.98px) {
    .custom-toggler {
      display: block;
    }
    
    .navbar-collapse {
      display: none !important;
    }
  }

  @media (min-width: 992px) {
    .offcanvas-custom {
      display: none !important;
    }
  }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container">
    <a class="navbar-brand" href="../pages/home.php">
      <img src="logo2.png" alt="Masar Platform Logo" />
    </a>

    <button class="custom-toggler" type="button" id="menuToggle" aria-expanded="false" aria-label="Toggle navigation">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>" href="../pages/home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'startups_list.php' ? 'active' : ''; ?>" href="../pages/startups_list.php">Startups</a></li>
        <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'public_institutions_list.php' ? 'active' : ''; ?>" href="../pages/public_institutions_list.php">Public Institutions</a></li>
        <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'challenges_list.php' ? 'active' : ''; ?>" href="../pages/challenges_list.php">Challenges</a></li>
        <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'events_list.php' ? 'active' : ''; ?>" href="../pages/events_list.php">Events</a></li>
        <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'documents.php' ? 'active' : ''; ?>" href="../pages/documents.php">Knowledge Hub</a></li>
      </ul>

      <ul class="navbar-nav align-items-center">
        <li class="nav-item message-item">
          <a class="nav-link p-0" href="<?php echo $loggedIn ? $messagesLink : '#'; ?>" <?php if (!$loggedIn) echo 'data-bs-toggle="modal" data-bs-target="#loginModal"'; ?>>
            <img src="chats.png" class="message-icon" alt="Messages" />
            <?php if ($newMessages): ?><span class="notification-badge"></span><?php endif; ?>
          </a>
        </li>
        <?php if ($loggedIn): ?>
          <li class="nav-item profile-container">
            <img src="<?php echo $profilePicture; ?>" alt="Profile" class="profile-pic" id="profileToggle" onerror="this.onerror=null;this.src='../images/default-profile.png';" />
            <div class="profile-dropdown" id="profileDropdownMenu">
              <a href="<?php echo $profileLink; ?>">View Profile</a>
              <a href="../authentication/logout.php">Log Out</a>
            </div>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link btn btn-success ms-2" href="../authentication/login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="offcanvas-custom" id="offcanvasMenu">
  <button class="close-btn" id="closeMenu">&times;</button>
  <ul class="navbar-nav">
    <li><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>" href="../pages/home.php">Home</a></li>
    <li><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'startups_list.php' ? 'active' : ''; ?>" href="../pages/startups_list.php">Startups</a></li>
    <li><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'public_institutions_list.php' ? 'active' : ''; ?>" href="../pages/public_institutions_list.php">Public Institutions</a></li>
    <li><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'challenges_list.php' ? 'active' : ''; ?>" href="../pages/challenges_list.php">Challenges</a></li>
    <li><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'events_list.php' ? 'active' : ''; ?>" href="../pages/events_list.php">Events</a></li>
    <li><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'documents.php' ? 'active' : ''; ?>" href="../pages/documents.php">Knowledge Hub</a></li>
    <?php if ($loggedIn): ?>
      <li class="mobile-message-item">
        <a class="nav-link d-inline-block" href="<?php echo $messagesLink; ?>">
          Messages
          <?php if ($newMessages): ?><span class="mobile-notification-badge"></span><?php endif; ?>
        </a>
      </li>
      <li><a class="nav-link" href="<?php echo $profileLink; ?>">View Profile</a></li>
      <li><a class="nav-link" href="../authentication/logout.php">Log Out</a></li>
    <?php else: ?>
      <li><a class="nav-link" href="../authentication/login.php">Login</a></li>
    <?php endif; ?>
  </ul>
</div>

<div class="modal fade" id="loginModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">Access Denied</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        You must be <strong>logged in</strong> to access this feature.
      </div>
      <div class="modal-footer">
        <a href="../authentication/login.php" class="btn btn-primary">Go to Login</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Stay Here</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const profileToggle = document.getElementById('profileToggle');
  const dropdownMenu = document.getElementById('profileDropdownMenu');
  profileToggle?.addEventListener('click', () => {
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.profile-container') && dropdownMenu) {
      dropdownMenu.style.display = 'none';
    }
  });

  const menuToggle = document.getElementById('menuToggle');
  const offcanvasMenu = document.getElementById('offcanvasMenu');
  const closeMenu = document.getElementById('closeMenu');
  let menuOpen = false;

  menuToggle.addEventListener('click', () => {
    menuOpen = !menuOpen;
    menuToggle.classList.toggle('open');
    menuToggle.setAttribute('aria-expanded', menuOpen);
    offcanvasMenu.classList.toggle('show');
  });

  closeMenu.addEventListener('click', () => {
    menuOpen = false;
    menuToggle.classList.remove('open');
    menuToggle.setAttribute('aria-expanded', 'false');
    offcanvasMenu.classList.remove('show');
  });
</script>
</body>
</html>