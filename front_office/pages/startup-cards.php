<?php

include('../includes/db.php');


$query = "SELECT * FROM startups ORDER BY startup_name ASC";
$result = mysqli_query($conn, $query);
$startups = [];
while ($row = mysqli_fetch_assoc($result)) {
    $startups[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Startups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .carousel-wrapper {
            position: relative;
            overflow: hidden;
            
        }
        .startup-cards-container {
            display: flex;
            width: 100%;
            gap: 0;
            transition: transform 0.5s ease;
            
        }
       .startup-card {
            flex: 0 0 calc((100% - 40px) / 3); 
            margin-right: 20px;
            margin-bottom:20px
           ;
        }
        .startup-card:last-child {
            margin-right: 0;
            margin-bottom:20px
        }

        
        .startup-card-inner {
            border-radius: 30px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            height: 500px;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card-top-bg {
            width: 100%;
            height: 198px;
            background-image: url('bg1.png');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .card-top-bg img {
            width: 125px;
            height: 125px;
            object-fit: cover;
            border-radius: 50%;
            position: absolute;
            bottom: -62px;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid #fff;
        }
        .startup-name {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: bold;
            font-size: 16px;
            color: #13132F;
            margin-top: 70px;
            text-align: center;
        }
        .startup-about {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 12px;
            color: #000;
            text-align: center;
            padding: 0 20px;
            flex-grow: 1;
        }
        .card-buttons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
            height:40px;
            width:300px;
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
        .carousel-arrow {
            position: absolute;
            top: 45%;
            transform: translateY(-50%);
            background: none;
            border: none;
            z-index: 2;
        }
        .carousel-arrow img {
            width: 30px;
            height: auto;
        }
        .arrow-left {
            left: 0;
        }
        .arrow-right {
            right: 0;
        }
        @media (max-width: 991px) {
            .startup-card {
                flex: 0 0 100%;
            }
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="text-center" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 700; font-size: 32px; color: #0C1BA3;">Our Startups</h2>
    <p class="text-center" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 500; font-size: 24px; color: #000000; width:50%; margin: 0 auto;">Startups in Masar develop and propose innovative solutions to road safety challenges in collaboration with public institutions.</p>

    <div class="carousel-wrapper mt-4 position-relative">
        <button class="carousel-arrow arrow-left" id="leftArrow" disabled style="opacity: 0.5;">
            <img src="left-arrow.png" alt="Left">
        </button>

        <div class="startup-cards-container" id="cardContainer">
            <?php foreach ($startups as $startup): ?>
                <div class="startup-card">
                    <div class="startup-card-inner">
                        <div class="card-top-bg">
                            <?php if (!empty($startup['logo'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($startup['logo']); ?>" alt="Logo">
                            <?php endif; ?>
                        </div>
                        <div class="startup-name"><?php echo htmlspecialchars((string)$startup['startup_name']); ?></div>
                        <div class="startup-about"><?php echo nl2br(htmlspecialchars((string)$startup['about_section'])); ?></div>
                        <div class="card-buttons">
                            <a href="<?php echo isset($_SESSION['user_id']) ? '..\chat\chat.php' : '#'; ?>" <?php if (!isset($_SESSION['user_id'])) echo 'data-bs-toggle="modal" data-bs-target="#loginModal"'; ?>>
                                <img src="../components/chat.png" alt="Message"> Message
                            </a>
                            <a href="view_startup_profile.php?id=<?php echo $startup['user_id']; ?>&type=startup">
                                <img src="profile.png" alt="Profile"> View Profile
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-arrow arrow-right" id="rightArrow">
            <img src="right-arrow.png" alt="Right">
        </button>
    </div>
</div>

<script>
    const container = document.getElementById('cardContainer');
    const leftBtn = document.getElementById('leftArrow');
    const rightBtn = document.getElementById('rightArrow');
    const cardCount = container.children.length;
    let currentIndex = 0;

    const updateArrows = () => {
        leftBtn.disabled = currentIndex === 0;
        rightBtn.disabled = currentIndex >= cardCount - 3;
        leftBtn.style.opacity = leftBtn.disabled ? '0.5' : '1';
        rightBtn.style.opacity = rightBtn.disabled ? '0.5' : '1';
    };

    const updatePosition = () => {
        const cardWidth = container.querySelector('.startup-card').offsetWidth + 20;
        container.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        updateArrows();
    };

    leftBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updatePosition();
        }
    });

    rightBtn.addEventListener('click', () => {
        if (currentIndex < cardCount - 3) {
            currentIndex++;
            updatePosition();
        }
    });

    window.addEventListener('resize', updatePosition);
    updateArrows();

    
</script>


</body>
</html>
