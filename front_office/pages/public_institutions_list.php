<?php
session_start();
include('../includes/db.php');
// include('startmain.php'); // Assuming this is not needed for institutions list, uncomment if it contains universal includes/functions

$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $query = "SELECT * FROM public_institutions WHERE institution_name LIKE '%$search%' OR description LIKE '%$search%' ORDER BY institution_name ASC";
} else {
    $query = "SELECT * FROM public_institutions ORDER BY institution_name ASC";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masar Platform - Public Institutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #F2F6FF;
            font-family: 'IBM Plex Sans', sans-serif;
        }
        .input-group .form-control {
            border-radius: 7px 0 0 7px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
            border-color: #ced4da;
        }
        .input-group .btn-primary {
            background-color: #0C1BA3;
            border-color: #0C1BA3;
            border-radius: 0 7px 7px 0;
            width: 100px;
            min-width: fit-content;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
        }
        .input-group .btn-primary:hover {
            background-color: #0C1BA3;
            border-color: #0C1BA3;
        }
        .input-group:focus-within {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Institution Card Styles (adapted from startup cards) */
        .institution-card {
            flex: 0 0 calc((100% - 40px) / 3); /* For potential future grid layout if needed */
            margin-bottom: 20px; /* Space between cards */
        }
        .institution-card-inner {
            border-radius: 30px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            height: 500px; /* Fixed height for consistent card size */
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card-top-bg {
            width: 100%;
            height: 198px;
            background-image: url('bg1.png'); /* Adjust path if necessary */
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .card-top-bg img {
            width: 125px; /* Slightly larger for consistency with startup cards */
            height: 125px; /* Slightly larger for consistency with startup cards */
            object-fit: cover;
            border-radius: 50%;
            position: absolute;
            bottom: -62px; /* Adjusted to center with 125px image */
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid #fff;
        }
        .institution-name {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: bold;
            font-size: 16px;
            color: #13132F;
            margin-top: 70px; /* Adjusted for image placement */
            text-align: center;
        }
        .institution-about {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 12px;
            color: #000;
            text-align: center;
            padding: 0 20px;
            flex-grow: 1; /* Allows description to take up available space */
        }
        .card-buttons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
            height: 40px;
            width: 300px;
        }
        .card-buttons a {
            background-color: #02FA72;
            color: #0C1BA3;
            border-radius: 10px;
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: bold;
            font-size: 12px;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        @media (max-width: 991px) {
            .institution-card {
                flex: 0 0 100%;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>

<?php include('../components/navbar.php'); ?>

<div class="container py-5">
      <h2 class="mb-4 text-center" style="color: #0C1BA3">Public Institutions</h2>

    <p class="text-center text-muted mb-4">Meet the public institutions partnering on Masar to drive innovation and <br> safer mobility.</p>

<br>

    <form method="GET" action="" class="mb-4 d-flex justify-content-center">
        <div class="input-group w-50">
            <input type="text" name="search" class="form-control" placeholder="Search public institutions..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="row justify-content-center">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($institution = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="institution-card-inner">
                        <div class="card-top-bg">
                            <?php if (!empty($institution['logo'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($institution['logo']); ?>" alt="Logo">
                            <?php endif; ?>
                        </div>
                        <div class="institution-name"><?php echo htmlspecialchars((string)$institution['institution_name']); ?></div>
                        <div class="institution-about"><?php echo nl2br(htmlspecialchars((string)$institution['description'])); ?></div>
                        <div class="card-buttons">
                            <a href="<?php echo isset($_SESSION['user_id']) ? "../chat/chat.php?id=". htmlspecialchars($institution['user_id'])." &type=institution" : "#"; ?>" <?php if (!isset($_SESSION['user_id'])) echo 'data-bs-toggle="modal" data-bs-target="#loginModal"'; ?>>
                            <img src="icons/message-outline.png" alt="Message" style="width:18px" > Message
                            </a>
                            <a href="view_institution_profile.php?id=<?php echo $institution['user_id']; ?>&type=institution">
                                <img src="profile.png" alt="Profile"> View Profile
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">
                No public institutions<?php echo $search ? ' found for "' . htmlspecialchars($search) . '"' : ''; ?>.
            </p>
        <?php endif; ?>
    </div>
</div>

<?php include('../components/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>