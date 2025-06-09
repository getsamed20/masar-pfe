<?php
$id = $_GET['id'] ?? null;

require_once '../includes/db.php';

$startup = mysqli_query($conn, "SELECT * FROM startups WHERE user_id = $id");
$startup_data = mysqli_fetch_assoc($startup);
$startup_id = $startup_data['startup_id'];

$ideas = mysqli_query($conn, "SELECT * FROM ideas WHERE startup_id = $startup_id");


while ($i = mysqli_fetch_assoc($ideas)) {
    $idea_id = $i['idea_id'];
    $media = mysqli_query($conn, "SELECT * FROM media WHERE idea_id = '$idea_id'");
    $images = [];
    $videos = [];

    while ($m = mysqli_fetch_assoc($media)) {
        if ($m['media_type'] === 'image') {
            $images[] = $m['file_path'];
        } elseif ($m['media_type'] === 'video') {
            $videos[] = $m['file_path'];
        }
    }

    echo '<div class="card mb-4 p-3">';
    echo '<h5>' . htmlspecialchars($i['title']) . '</h5>';
    echo '<p>' . nl2br(htmlspecialchars($i['description'])) . '</p>';

    if (!empty($images)) {
        $carouselId = "carouselIdea" . $idea_id;
        echo '<div id="' . $carouselId . '" class="carousel slide mb-3" data-bs-ride="carousel">';
        echo '<div class="carousel-inner">';

        foreach ($images as $index => $img) {
            $activeClass = $index === 0 ? 'active' : '';
            echo '<div class="carousel-item ' . $activeClass . '">';
            echo '<img src="../uploads/' . $img . '" class="d-block w-100 rounded">';
            echo '</div>';
        }

        echo '</div>';
        if (count($images) > 1) {
            echo '<button class="carousel-control-prev" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>';
            echo '<button class="carousel-control-next" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>';
        }
        echo '</div>';
    }

    foreach ($videos as $vid) {
        echo '<video controls class="w-100 rounded mb-2">
                <source src="../uploads/' . $vid . '" type="video/mp4">
                Your browser does not support the video tag.
              </video>';
    }

    echo '</div>'; 
}

echo '</div>';
?>
<style>
.card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            height: 380px;
            padding: 20px;
            width: 100%;
            margin: 0 auto;
            border: none;
        }

</style>
