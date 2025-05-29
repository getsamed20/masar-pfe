<?php 
session_start();
include '../includes/db.php';

$today = date('Y-m-d');
mysqli_query($conn, "UPDATE challenges SET status = 'closed' WHERE deadline < '$today' AND status = 'open'");

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : 'open'; // default = open
$start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

$whereClauses = [];

if (!empty($search)) {
    $safeSearch = mysqli_real_escape_string($conn, $search);
    $whereClauses[] = "(title LIKE '%$safeSearch%' OR description LIKE '%$safeSearch%')";
}

if (!empty($category)) {
    $safeCategory = mysqli_real_escape_string($conn, $category);
    $whereClauses[] = "category = '$safeCategory'";
}

if (!empty($status)) {
    $safeStatus = mysqli_real_escape_string($conn, $status);
    $whereClauses[] = "status = '$safeStatus'";
}

if (!empty($start_date)) {
    $whereClauses[] = "deadline >= '$start_date'";
}

if (!empty($end_date)) {
    $whereClauses[] = "deadline <= '$end_date'";
}

$whereSQL = '';
if (!empty($whereClauses)) {
    $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
}

$query = "SELECT * FROM challenges $whereSQL ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$categories = [
    'Operations',
    'Design & Planning',
    'Land Use & Urban Planning',
    'Vehicles',
    'Automated Enforcement',
    'ITS & Data Utilization',
    'Police Enforcement',
    'Legislation & Regulations',
    'Training, Awareness & Education',
    'Other'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Challenges List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('../components/navbar.php'); ?>

<div class="container mt-5">
    <h1 class="mb-4">List of Challenges</h1>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
        </div>

        <div class="col-md-2">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat; ?>" <?php echo $category == $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="open" <?php echo $status == 'open' ? 'selected' : ''; ?>>Open</option>
                <option value="closed" <?php echo $status == 'closed' ? 'selected' : ''; ?>>Closed</option>
            </select>
        </div>

        <div class="col-md-2">
            <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($start_date); ?>" placeholder="Start Date">
        </div>

        <div class="col-md-2">
            <input type="date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>" placeholder="End Date">
        </div>

        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text">
                                <?php echo htmlspecialchars(mb_strimwidth($row['description'], 0, 100, '...')); ?>
                            </p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                            <p><strong>Deadline:</strong> <?php echo htmlspecialchars($row['deadline']); ?></p>
                            <p class="text-muted"><small>Created on: <?php echo date('F j, Y', strtotime($row['created_at'])); ?></small></p>
                            <a href="challenge_details.php?challenge_id=<?php echo $row['challenge_id']; ?>" class="btn btn-primary">View Challenge</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">No challenges found.</p>
    <?php endif; ?>
</div>

<?php include('../components/footer.php'); ?>
</body>
</html>
