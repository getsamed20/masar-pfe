<?php   
include('../includes/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success Stories | Masar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
        }
        .story-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            height: 380px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            width: 100%;
            margin: 0 auto;
        }
        .story-title {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: #0C1BA3;
            margin-bottom: 16px;
        }
        .story-description {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 400;
            font-size: 14px;
            color: #000000;
            margin-bottom: 16px;
            flex-grow: 1;
            overflow: hidden;
            position: relative;
            line-height: 1.5;
            max-width: 100%;
            word-wrap: break-word;
            display: -webkit-box;
            -webkit-line-clamp: 10;
            -webkit-box-orient: vertical;
        }
        .story-description::after {
            content: '...';
            position: absolute;
            bottom: 0;
            right: 0;
            background: white;
            padding-left: 5px;
        }
        .learn-more-btn {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 500;
            font-size: 14px;
            color: #0C1BA3;
            border: 1px solid #0C1BA3;
            border-radius: 4px;
            padding: 10px 20px;
            display: inline-flex;
            align-items: center;
            width: fit-content;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: auto;
        }
        .learn-more-btn:hover {
            background-color: #0C1BA3;
            color: white;
        }
        .arrow-icon {
            margin-left: 8px;
        }
        .load-more-btn {
            background-color: #0C1BA3;
            color: white;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700;
            font-size: 16px;
            border-radius: 4px;
            padding: 12px 24px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            border: none;
            margin-top: 40px;
        }
        .section-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .section-subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 50px;
        }
        .story-item {
            padding: 8px;
            margin-bottom: 16px;
        }

        @media (max-width: 992px) {
            .story-card {
                max-width: 350px;
            }
        }
        
        @media (max-width: 768px) {
            .section-title {
                font-size: 24px;
            }
            .section-subtitle {
                font-size: 16px;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="section-title">Stories</h1>
            <p class="section-subtitle">Real stories from startups making impact through Masar.</p>
        </div>

        <div class="row g-3 justify-content-center" id="stories-container">
            <?php
            $stories = mysqli_query($conn, "SELECT * FROM success_stories ORDER BY created_at DESC");
            $counter = 0;
            
            while ($story = mysqli_fetch_assoc($stories)) {
                $story_id = $story['story_id'];
                $title = htmlspecialchars($story['title']);
                $content = htmlspecialchars($story['content']);
                
                $sentences = preg_split('/(?<=[.!?])\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);
                
                $preview = implode(' ', array_slice($sentences, 0, 10));
                
                if (count($sentences) > 10) {
                    $preview = rtrim($preview, '.!?') . '...';
                }
                
                $hidden_class = $counter >= 3 ? 'd-none' : '';
                
                echo '<div class="col-xl-4 col-lg-6 col-md-6 story-item '.$hidden_class.'">';
                echo '<div class="story-card">';
                echo '<h3 class="story-title">'.$title.'</h3>';
                echo '<p class="story-description">'.$preview.'</p>';
                echo '<a href="story_detail.php?id='.$story_id.'" class="learn-more-btn">Learn more <span class="arrow-icon">→</span></a>';
                echo '</div></div>';
                
                $counter++;
            }
            ?>
        </div>

        <?php if(mysqli_num_rows($stories) > 3): ?>
        <div class="text-center">
            <button id="load-more-btn" class="load-more-btn">Load more</button>
        </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreBtn = document.getElementById('load-more-btn');
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function() {
                    const hiddenStories = document.querySelectorAll('.story-item.d-none');
                    const storiesToShow = Array.from(hiddenStories).slice(0, 3);
                    
                    storiesToShow.forEach(story => {
                        story.classList.remove('d-none');
                    });
                    
                    if (document.querySelectorAll('.story-item.d-none').length === 0) {
                        loadMoreBtn.classList.add('d-none');
                    }
                });
            }
        });
    </script>
</body>
</html>
