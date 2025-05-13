<?php
session_start();
include('../includes/db.php');

$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $query = "SELECT * FROM startups WHERE startup_name LIKE '%$search%' OR about_section LIKE '%$search%' ORDER BY startup_name ASC";
} else {
    $query = "SELECT * FROM startups ORDER BY startup_name ASC";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masar Platform - Startups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
</head>
<body>

<?php include('../components/navbar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Startups Directory</h2>

    <form method="GET" action="" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search startups..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="row">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($startup = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 d-flex flex-column shadow-sm">
                        <?php if (!empty($startup['logo'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($startup['logo']); ?>" class="card-img-top" alt="Logo" style="height: 200px; object-fit: contain;">
                        <?php endif; ?>
                        <div class="card-body flex-grow-1 d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($startup['startup_name']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo nl2br(htmlspecialchars(mb_strimwidth($startup['about_section'], 0, 100, '...'))); ?></p>
                            <div class="mt-auto text-end">
                                <a href="view_startup_profile.php?id=<?php echo $startup['user_id']; ?>&type=startup" class="btn btn-outline-primary">View Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">
                No startups<?php echo $search ? ' found for "' . htmlspecialchars($search) . '"' : ''; ?>.
            </p>
        <?php endif; ?>
    </div>
</div>

<?php include('../components/footer.php'); ?>

</body>
</html>
