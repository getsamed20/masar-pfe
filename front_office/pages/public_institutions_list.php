<?php
session_start();
include('../includes/db.php');

// Handle search
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
</head>
<body>

<?php include('../components/navbar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Public Institutions Directory</h2>

    <form method="GET" action="" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search public institutions..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="row">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($institution = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="profile-info mb-4 shadow" style="height: 477px; position: relative; border-radius: 30px;">
                        <div class="top-child text-center" style="height: 198px; width: 100%; background-color: rgb(53, 121, 188); position: relative; border-radius: 30px 30px 0 0;">
                            <?php if (!empty($institution['logo'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($institution['logo']); ?>" 
                                     class="rounded-circle" 
                                     alt="Logo"
                                     style="
                                        width: 120px; 
                                        height: 120px; 
                                        object-fit: cover; 
                                        position: absolute; 
                                        bottom: -60px; 
                                        left: 50%; 
                                        transform: translateX(-50%);
                                     ">
                            <?php endif; ?>
                        </div>
                        <div class="card-body text-center mt-5 pt-4 px-3">
                            <h5 class="card-title"><?php echo htmlspecialchars($institution['institution_name']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars(mb_strimwidth($institution['description'], 0, 100, '...'))); ?></p>
                            <a href="view_institution_profile.php?id=<?php echo $institution['user_id']; ?>&type=institution" class="btn btn-outline-primary">View Profile</a>
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
</body>
</html>
