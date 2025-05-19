<?php
include 'db.php';

$admin_email = '';
$user_id = $_SESSION['user_id'];
$sql = "SELECT email FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $admin_email = $row['email'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@500&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #0C1BA3;
            --active-bg: #02FA72;
            --hover-bg: rgba(2, 250, 114, 0.2);
            --active-text: #0C1BA3;
            --text-color: #FFFFFF;
            --logout-color: #FF4444;
            --transition-speed: 0.3s;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            transition: margin-left var(--transition-speed);
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--text-color);
            transition: width var(--transition-speed);
            overflow: visible;
            z-index: 1000;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-collapsed {
            width: 80px;
        }
        
        
        .logo-container {
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80px;
            box-sizing: border-box;
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo {
            height: 40px;
            width: auto;
            object-fit: contain;
            transition: all var(--transition-speed);
        }
        
        .logo-expanded {
            width: 180px;
        }
        
        .logo-collapsed {
            width: 40px;
            display: none;
        }
        
        .sidebar-collapsed .logo-expanded {
            display: none;
        }
        
        .sidebar-collapsed .logo-collapsed {
            display: block;
        }
        
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        
        .nav-item {
            position: relative;
            margin: 5px 15px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 99px;
            transition: all var(--transition-speed);
            white-space: nowrap;
            font-size: 14px;
        }
        
        .nav-link:hover {
            background: var(--hover-bg);
        }
        
        .nav-link.active {
            background: var(--active-bg);
            color: var(--active-text);
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            transition: margin var(--transition-speed);
        }
        
        .sidebar-collapsed .nav-icon {
            margin-right: 0;
        }
        
        .nav-text {
            transition: opacity var(--transition-speed);
        }
        
        .sidebar-collapsed .nav-text {
            opacity: 0;
        }
        
        .user-info {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            padding: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all var(--transition-speed);
            overflow: hidden;
            text-align: center;
        }
        
        .user-email {
            font-size: 12px;
            margin-bottom: 10px;
            white-space: nowrap;
            opacity: 1;
            transition: opacity var(--transition-speed);
            width: 100%;
            text-align: center;
        }
        
        .sidebar-collapsed .user-email {
            opacity: 0;
        }
        
        .logout-btn {
            background: transparent;
            border: 1px solid var(--logout-color);
            color: var(--logout-color);
            padding: 8px 15px;
            border-radius: 99px;
            cursor: pointer;
            font-size: 12px;
            transition: all var(--transition-speed);
            width: auto;
            white-space: nowrap;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logout-btn:hover {
            background: rgba(255, 68, 68, 0.1);
        }
        
        .logout-icon {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }
        
        .sidebar-collapsed .logout-btn {
            width: 40px;
            padding: 8px;
            justify-content: center;
        }
        
        .sidebar-collapsed .logout-icon {
            margin-right: 0;
        }
        
        .sidebar-collapsed .logout-text {
            display: none;
        }
        
        .toggle-btn {
            position: absolute;
            top: 30px;
            right: -15px;
            width: 40px;
            height: 40px;
            background: var(--sidebar-bg);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            border: 2px solid white;
            z-index: 1001;
            transition: all var(--transition-speed);
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            transform: translateX(0);
        }
        
        .toggle-btn:hover {
            transform: scale(1.1);
        }
        
        .toggle-icon {
            width: 20px;
            height: 20px;
            transition: opacity var(--transition-speed);
        }
        
        .toggle-icon-collapsed {
            position: absolute;
            opacity: 0;
        }
        
        .sidebar-collapsed .toggle-icon-expanded {
            opacity: 0;
        }
        
        .sidebar-collapsed .toggle-icon-collapsed {
            opacity: 1;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left var(--transition-speed);
        }
        
        .sidebar-collapsed + .main-content {
            margin-left: 80px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            
            .sidebar:not(.sidebar-collapsed) {
                width: 250px;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .sidebar:not(.sidebar-collapsed) + .main-content {
                margin-left: 250px;
            }
            
            .nav-text, .user-email {
                opacity: 0;
            }
            
            .sidebar:not(.sidebar-collapsed) .nav-text,
            .sidebar:not(.sidebar-collapsed) .user-email {
                opacity: 1;
            }
            
            .sidebar:not(.sidebar-collapsed) .nav-icon {
                margin-right: 15px;
            }
            
            .sidebar:not(.sidebar-collapsed) .logout-btn {
                width: auto;
                padding: 8px 15px;
            }
            
            .sidebar:not(.sidebar-collapsed) .logout-text {
                display: inline;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo-container">
            <img src="images/logo-expanded.png" alt="Logo" class="logo logo-expanded">
            <img src="images/logo-collapsed.png" alt="Logo" class="logo logo-collapsed">
            
            <button class="toggle-btn" id="toggleBtn">
                <img src="images/toggle-expanded.png" alt="Toggle" class="toggle-icon toggle-icon-expanded">
                <img src="images/toggle-collapsed.png" alt="Toggle" class="toggle-icon toggle-icon-collapsed">
            </button>
        </div>
        
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="manage_accounts.php" class="nav-link">
                    <img src="images/users-icon.png" alt="Users" class="nav-icon">
                    <span class="nav-text">Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="upload_documents.php" class="nav-link">
                    <img src="images/upload-icon.png" alt="Upload" class="nav-icon">
                    <span class="nav-text">Upload Documents</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="manage_pending_accounts.php" class="nav-link">
                    <img src="images/pending-icon.png" alt="Pending" class="nav-icon">
                    <span class="nav-text">Pending Accounts</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="admin_reported_posts.php" class="nav-link">
                    <img src="images/reported-icon.png" alt="Reported" class="nav-icon">
                    <span class="nav-text">Reported Content</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="admin_publish_success.php" class="nav-link">
                    <img src="images/publish-icon.png" alt="Publish" class="nav-icon">
                    <span class="nav-text">Publish Stories</span>
                </a>
            </li>
        </ul>
        
        <div class="user-info">
            <span class="user-email"><?php echo htmlspecialchars($admin_email); ?></span>
            <a href="logout.php" class="logout-btn">
                <img src="images/logout-icon.png" alt="Logout" class="logout-icon">
                <span class="logout-text">Logout</span>
            </a>
        </div>
    </div>
    
    <div class="main-content">
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleBtn');
            
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
            });
            
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                const linkHref = link.getAttribute('href');
                if (currentPage === linkHref) {
                    link.classList.add('active');
                    const icon = link.querySelector('.nav-icon');
                    const iconSrc = icon.src;
                    icon.src = iconSrc.replace('.png', '-active.png');
                }
            });
            
            function handleResize() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('sidebar-collapsed');
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                }
            }
            
            window.addEventListener('resize', handleResize);
            handleResize();
        });
    </script>
</body>
</html>