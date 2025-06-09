<?php  
session_start();
include '../includes/db.php';

$today = date('Y-m-d');
mysqli_query($conn, "UPDATE challenges SET status = 'closed' WHERE deadline < '$today' AND status = 'open'");

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : 'open'; 
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
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Hebrew:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
 body {
            background-color: #F2F6FF;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            
        }
        </style>
    <style>
        .filter-label {
            color: #0C1BA3;
            font-weight: bold;
            font-size: 18px;
            margin-right: 8px;
        }
        .challenge-title {
            color: #0C1BA3;
            font-weight: bold;
            font-size: 18px;
            margin-right: 8px;
        }

        .filter-inputs input,
        .filter-inputs select {
            max-width: 150px;
            color: #0C1BA3;
        font-weight: bold;
         box-shadow: 0 3px 3px rgba(0, 0, 0, 0.3) !important;
    }
     

        .filter-icon-label {
            display: flex;
            align-items: center;
            =box-shadow:none !important;

        }

        .filter-icon-label img {
            height: 24px;
            margin-right: 6px;
        }

        .masar-filter-btn {
            background-color: #0C1BA3;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
        }

        .masar-filter-btn:hover {
            background-color: #091470;
        }

        .masar-view-btn {
            display: block;
            margin: 0 auto;
            background-color: #02FA72;
            color: #0C1BA3;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            width: 200px;

        }

        .masar-view-btn:hover {
            background-color: #01db62;
            color: #091470;
        }



        .card-challenge {
            background: white;
            border-radius: 30px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            padding: 20px; 
            width: 100%;
            border: none !important; 
        }
    </style>
</head>
<body>
<?php include('../components/navbar.php'); ?>

<div class="container mt-5">
    <h2 class="text-center mb-4" style="color: #0C1BA3">All Challenges</h2>
    <p class="text-center text-muted mb-4">Explore current challenges and opportunities to contribute with your innovative road safety solutions.</p>
    <br>

    <form method="GET" action="" class="search-bar mb-4">
        <input type="text" name="search" class="form-control" placeholder="Search by title or description" value="<?php echo htmlspecialchars($search); ?>" style="color:#0C1BA3; max-width: 300px; margin: auto; box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3) !important;">
    </form>

    <form method="GET" action="" class="d-flex flex-wrap align-items-end justify-content-center gap-3 mb-5 filter-inputs">
        <div class="filter-icon-label">
            <img src="icons/filter.png" alt="Filter Icon">
            <span class="filter-label">Filter by:</span>
        </div>

        <label class="filter-label">Category</label>
        <select name="category" class="form-select">
            <option value="">All</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category == $cat ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
            <?php endforeach; ?>
        </select>

        <label class="filter-label">Status</label>
        <select name="status" class="form-select">
            <option value="">All</option>
            <option value="open" <?php echo $status == 'open' ? 'selected' : ''; ?>>Open</option>
            <option value="closed" <?php echo $status == 'closed' ? 'selected' : ''; ?>>Closed</option>
        </select>

        <label class="filter-label">From</label>
        <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($start_date); ?>">

        <label class="filter-label">To</label>
        <input type="date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>">

        <button type="submit" class="masar-filter-btn">Apply Filters</button>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-challenge  h-100">
                        <div class="card-body">
                            <h5 class="challenge-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p><strong>Description:</strong><?php echo htmlspecialchars(mb_strimwidth($row['description'], 0, 100, '...')); ?></p>
                            
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                            <p><strong>Deadline:</strong> <?php echo htmlspecialchars($row['deadline']); ?></p>
                            <p><strong>Created on:</strong> <?php echo date('F j, Y', strtotime($row['created_at'])); ?></p>
                            <a href="challenge_details.php?challenge_id=<?php echo $row['challenge_id']; ?>" class="masar-view-btn">View Challenge</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No challenges found.</p>
    <?php endif; ?>
</div>

<?php include('../components/footer.php'); ?>
</body>
</html>