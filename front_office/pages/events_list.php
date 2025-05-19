<?php
session_start();
include('../includes/db.php');
include('../components/navbar.php');

$today = date('Y-m-d');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$date_filter = isset($_GET['date']) ? trim($_GET['date']) : '';
$show_past = isset($_GET['show_past']) && $_GET['show_past'] === '1';

$query = "SELECT * FROM events WHERE 1=1";

if (!empty($search)) {
    $safeSearch = mysqli_real_escape_string($conn, $search);
    $query .= " AND (title LIKE '%$safeSearch%' OR description LIKE '%$safeSearch%' OR location LIKE '%$safeSearch%')";
}

if (!empty($type)) {
    $safeType = mysqli_real_escape_string($conn, $type);
    $query .= " AND event_type = '$safeType'";
}

if (!empty($date_filter)) {
    $safeDate = mysqli_real_escape_string($conn, $date_filter);
    $query .= " AND date >= '$safeDate'";
}

if (!$show_past) {
    $query .= " AND date >= '$today'";
}

$query .= " ORDER BY date ASC";
$events_query = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Events - Masar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">All Events</h1>

    <form method="GET" action="" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search events..." value="<?php echo htmlspecialchars($search); ?>">
        </div>

        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="online" <?= $type === 'online' ? 'selected' : '' ?>>Online</option>
                <option value="offline" <?= $type === 'offline' ? 'selected' : '' ?>>Offline</option>
            </select>
        </div>

        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date_filter); ?>">
        </div>

        <div class="col-md-2 form-check mt-2">
            <input type="checkbox" name="show_past" value="1" class="form-check-input" id="showPast" <?= $show_past ? 'checked' : '' ?>>
            <label for="showPast" class="form-check-label">Show past events</label>
        </div>

        <div class="col-md-1">
            <button class="btn btn-primary w-100" type="submit">Filter</button>
        </div>
    </form>

    <?php if (mysqli_num_rows($events_query) > 0): ?>
        <div class="row">
            <?php while ($event = mysqli_fetch_assoc($events_query)): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <?php if (!empty($event['cover_image'])): ?>
                            <img src="<?php echo htmlspecialchars($event['cover_image']); ?>" class="card-img-top" alt="Event Cover" style="max-height: 250px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars(mb_strimwidth($event['description'], 0, 120, '...'))); ?></p>
                            <p class="mb-1"><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?> at <?php echo htmlspecialchars($event['time']); ?></p>
                            <p class="mb-1"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                            <p class="mb-1"><strong>Type:</strong> <?php echo ucfirst(htmlspecialchars($event['event_type'])); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">
            No events<?php echo $search ? ' found for "' . htmlspecialchars($search) . '"' : ''; ?>.
        </p>
    <?php endif; ?>
</div>

<?php include('../components/footer.php'); ?>
</body>
</html>
