<!-- <?php /* 
session_start();
include('../includes/db.php');
include('../components/navbar.php');

// Get ID and type from URL
$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? null;

if (!$id || !$type) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Invalid profile link.</div></div>";
    exit;
}

if ($type === 'startup') {
    $query = "SELECT * FROM startups WHERE user_id = $id";
    $backLink = 'startups_list.php';
} elseif ($type === 'institution') {
    $query = "SELECT * FROM public_institutions WHERE user_id = $id";
    $backLink = 'public_institions_list.php';
} else {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Unknown profile type.</div></div>";
    exit;
}

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Profile not found.</div></div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Masar Platform - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .back-arrow {
            display: inline-block;
            margin-bottom: 20px;
            font-size: 1.2rem;
            color: #0d6efd;
            text-decoration: none;
        }
        .back-arrow:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <a href="<?php echo $backLink; ?>" class="back-arrow">&larr; Back to <?php echo ($type === 'startup') ? 'Startups' : 'Public Institutions'; ?></a>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <?php echo ($type === 'startup') ? $data['startup_name'] . ' Profile' :  $data['institution_name'] . ' Profile'; ?> 
        </div>
        <div class="card-body">
            <?php if (!empty($data['logo'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($data['logo']); ?>" alt="Logo" class="img-fluid mb-3" style="max-height: 200px;">
            <?php endif; ?>
            <h3><?php echo ($type === 'startup') ? htmlspecialchars($data['startup_name']) : htmlspecialchars($data['institution_name']); ?></h3>
            <p>
                <?php
                if ($type === 'startup') {
                    echo nl2br(htmlspecialchars($data['about_section']));
                } else {
                    echo nl2br(htmlspecialchars($data['description']));
                }
                ?>
            </p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($data['contact_email']); ?></p>
            <a href="../chat/chat.php?id=<?php echo $id; ?>&type=<?php echo $type; ?>" class="btn btn-success">Start Chat</a>

           
        </div>
    </div>
</div>

<?php include('../components/footer.php'); ?>
</body>
</html>-->
