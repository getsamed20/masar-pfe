<?php
session_start();
include('../includes/db.php');
include('../components/navbar.php');

$today = date('Y-m-d');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$date_from = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';
$show_past = isset($_GET['show_past']) && $_GET['show_past'] === '1';

$query = "SELECT events.*, public_institutions.institution_name, public_institutions.logo
          FROM events
          JOIN public_institutions ON events.institution_id = public_institutions.institution_id
          WHERE 1=1";

if (!empty($search)) {
    $safeSearch = mysqli_real_escape_string($conn, $search);
    $query .= " AND (title LIKE '%$safeSearch%' OR description LIKE '%$safeSearch%' OR location LIKE '%$safeSearch%')";
}

if (!empty($type)) {
    $safeType = mysqli_real_escape_string($conn, $type);
    $query .= " AND event_type = '$safeType'";
}

if (!empty($date_from)) {
    $safeDateFrom = mysqli_real_escape_string($conn, $date_from);
    $query .= " AND date >= '$safeDateFrom'";
}

if (!empty($date_to)) {
    $safeDateTo = mysqli_real_escape_string($conn, $date_to);
    $query .= " AND date <= '$safeDateTo'";
}

if (!$show_past && empty($date_from) && empty($date_to)) {
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
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
      body {
            background-color: #F2F6FF !important;
  font-family: 'IBM Plex Sans', sans-serif  !important;;
}
    <style>
    .filter-label {
        color: #0C1BA3;
        font-weight: bold;
        margin-left: 8px;
        font-size: 18px;
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
    }

        .card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            padding: 20px; 
            width: 100%;
            border: none !important; 
        }

    .card-img-top {
        height: 300px !important;
        object-fit: cover;
        border-radius: 10px;
    }

    .event-header {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        position: relative;
    }

    .institution-info {
        display: flex;
        align-items: center;
        z-index: 1;
    }

    .card-title-centered {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        font-size: 1.25rem;
        font-weight: bold;
        width: 100%;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-title-centered small {
        font-weight: normal;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .card-body-content p {
        margin-left: 0;
        padding-left: 0;
    }

    .card-body-content > .d-flex.align-items-center.mb-2:has(p) {
        margin-left: 0;
        padding-left: 0;
    }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4" style="color: #0C1BA3">All Events</h1>
    <p class="text-center text-muted mb-4">Discover upcoming events and initiatives organized by public institutions in the road safety ecosystem.</p>
<br>
    <form method="GET" action="" class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="Search events..." value="<?php echo htmlspecialchars($search); ?>" style="color:#0C1BA3; max-width: 300px; margin: auto; box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3) !important;">
    </form>

    <form method="GET" action="" class="d-flex flex-wrap align-items-end justify-content-center gap-3 mb-4 filter-inputs">
        <div class="filter-icon-label">
            <img src="icons/filter.png" alt="Filter" style="height: 24px;">
            <span class="filter-label">Filter by: </span>
        </div>
        <label  class="filter-label"> Type</label>
        <select name="type" class="form-select">
            <option value="">All</option>
            <option value="online" <?= $type === 'online' ? 'selected' : '' ?>>Online</option>
            <option value="offline" <?= $type === 'offline' ? 'selected' : '' ?>>Offline</option>
        </select>
        <label class="filter-label"> From</label>
        <input type="date" name="date_from" class="form-control" value="<?php echo htmlspecialchars($date_from); ?>">
        <label  class="filter-label"> To</label>
        <input type="date" name="date_to" class="form-control" value="<?php echo htmlspecialchars($date_to); ?>">

        <div class="form-check" style="margin-top: 6px;">
            <input type="checkbox" name="show_past" value="1" class="form-check-input" id="showPast" <?= $show_past ? 'checked' : '' ?>>
            <label for="showPast" class="form-check-label"  >Show past events</label>
        </div>

        <button class="btn btn-primary" type="submit" style="background-color: #0C1BA3;">Apply Filters</button>
    </form>

    <?php if (mysqli_num_rows($events_query) > 0): ?>
        <div class="row">
            <?php while ($event = mysqli_fetch_assoc($events_query)): ?>
                <div class="col-md-12 mb-4">
                    <div class="card  h-100">
                        <div class="event-header">
                            <div class="institution-info">
                                <img src="../uploads/<?php echo htmlspecialchars($event['logo']); ?>" alt="Logo" width="40" height="40" class="me-2 rounded-circle" style="object-fit: cover;">
                                <div>
                                    <span><?php echo htmlspecialchars($event['institution_name']); ?></span>
                                    <small class="text-muted d-block">Created on <?php echo date("F j, Y", strtotime($event['created_at'])); ?></small>
                                </div>
                            </div>
                            <h5 class="card-title-centered">
                                <?php echo htmlspecialchars($event['title']); ?>
                            </h5>
                        </div>

                        <?php if (!empty($event['cover_image'])): ?>
                            <img src="../public_institution_profile/<?php echo htmlspecialchars($event['cover_image']); ?>" class="card-img-top mb-3" alt="Event Cover">
                        <?php endif; ?>

                        <div class="card-body-content">
                            <p class="card-text">
                                <?php
                                    $desc = htmlspecialchars($event['description']);
                                    if (mb_strlen($desc) > 200): ?>
                                        <span class="short-desc"><?php echo mb_substr($desc, 0, 200); ?>...</span>
                                        <span class="full-desc d-none"><?php echo nl2br($desc); ?></span>
                                        <a href="#" class="see-more-link text-primary">See more</a>
                                <?php else: ?>
                                    <?php echo nl2br($desc); ?>
                                <?php endif; ?>
                            </p>

                            <div class="d-flex align-items-center mb-2">
                                <img src="icons/pin.png" alt="Location Icon" width="20" class="me-2">
                                <span><?php echo htmlspecialchars($event['location']); ?></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="icons/date.png" alt="Time Icon" width="20" class="me-2">
                                <span><?php echo htmlspecialchars($event['date']) . ' at ' . htmlspecialchars($event['time']); ?></span>
                            </div>
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.see-more-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const card = link.closest('.card-body-content');
            card.querySelector('.short-desc').classList.add('d-none');
            card.querySelector('.full-desc').classList.remove('d-none');
            link.remove();
        });
    });
});
</script>

</body>
</html>