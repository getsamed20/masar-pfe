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

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Masar Platform</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="../pages/home.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="../pages/startups_list.php">Startups</a></li>
            <li class="nav-item"><a class="nav-link" href="../pages/public_institutions_list.php">Public Institutions</a></li>
            <li class="nav-item"><a class="nav-link" href="../pages/events_list.php">Events</a></li>
            <li class="nav-item"><a class="nav-link" href="../pages/challenges_list.php">Challenges</a></li>
            <li class="nav-item"><a class="nav-link" href="../pages/documents.php">Documents</a></li>

            <li class="nav-item">
                <a class="nav-link" 
                   href="<?php echo $loggedIn ? $profileLink : '#'; ?>" 
                   <?php if (!$loggedIn) echo 'data-bs-toggle="modal" data-bs-target="#loginModal"'; ?>>
                   Profile
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" 
                   href="<?php echo $loggedIn ? $messagesLink : '#'; ?>" 
                   <?php if (!$loggedIn) echo 'data-bs-toggle="modal" data-bs-target="#loginModal"'; ?>>
                   Messages 
                   <?php if ($newMessages): ?>
                       <span class="badge bg-danger"></span> 
                   <?php endif; ?>
                </a>
            </li>

            <?php if ($loggedIn): ?>
                <li class="nav-item"><a class="nav-link" href="../authentication/logout.php">Log Out</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="../authentication/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
        </div>
    </div>
</nav>

<!--login modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="loginModalLabel">Access Denied</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
