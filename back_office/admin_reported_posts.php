<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Function to get poster name
function getPosterName($postOwner, $postId, $postInstitutionId, $conn) {
    if ($postOwner === 'startup') {
        $query = "SELECT s.startup_name 
                    FROM posts p 
                    JOIN startups s ON p.startup_id = s.startup_id 
                    WHERE p.post_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['startup_name'];
        }
    } else {
        $query = "SELECT i.institution_name 
                    FROM posts_institution p 
                    JOIN public_institutions i ON p.institution_id = i.institution_id 
                    WHERE p.post_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $postId); 
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['institution_name'];
        }
    }
    return "Unknown";
}

// Get all distinct reasons from reports
$reasonsQuery = "SELECT DISTINCT reason FROM reports ORDER BY reason";
$reasonsResult = $conn->query($reasonsQuery);
$reasons = [];
while ($row = $reasonsResult->fetch_assoc()) {
    $reasons[] = $row['reason'];
}

// Build the base query for reported posts
$query = "SELECT 
            post_id, 
            post_institution_id, 
            post_owner, 
            COUNT(*) as report_count,
            GROUP_CONCAT(DISTINCT reason SEPARATOR ', ') as reasons
          FROM reports 
          WHERE 1=1";

// Add reason filter if selected
$params = [];
if (isset($_GET['reason']) && !empty($_GET['reason'])) {
    $query .= " AND reason = ?";
    $params[] = $_GET['reason'];
}

// Complete the query
$query .= " GROUP BY post_id, post_institution_id, post_owner";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param("s", $params[0]);
}
$stmt->execute();
$result = $stmt->get_result();

$reportedPosts = [];
while ($row = $result->fetch_assoc()) {
    $row['posted_by'] = getPosterName($row['post_owner'], $row['post_id'], $row['post_institution_id'], $conn);
    $reportedPosts[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reported Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #F2F6FF;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* New wrapper to control overall content width and centering */
        .main-content-wrapper {
            max-width: 1200px; /* Set your desired maximum width here */
            margin: 20px auto; /* Centers the content wrapper and adds vertical spacing */
            padding: 20px; /* Padding inside the wrapper */
            background-color: transparent; /* No background for the wrapper itself */
        }

        /* Adjustments for existing .main-content and .container to be inside the new wrapper */
        .main-content {
            margin-left: 0 !important; /* Ensure no unwanted margin */
            padding: 0; /* Remove padding here as it's handled by .main-content-wrapper */
        }
        .container {
            max-width: 100%; /* Allow Bootstrap container to take full width of .main-content-wrapper */
            margin: 0; /* Remove margin as it's handled by .main-content-wrapper */
            padding: 0; /* Remove padding as it's handled by .main-content-wrapper */
        }
        
        /* Custom Styles for Filters */
        .filter-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-label {
            color: #0C1BA3;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .filter-label img {
            margin-right: 5px;
        }

        .custom-select {
            background-color: white;
            box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.3);
            border: none;
            border-radius: 4px;
            color: #0C1BA3;
            font-size: 12px;
            padding: 8px 12px;
            width: auto; /* Allow content to dictate width, or set a min-width */
            min-width: 120px; /* Ensures a minimum width */
            max-width: 250px; /* Prevents it from getting too wide */
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%230C1BA3"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 12px;
        }

        .custom-select option {
            color: #0C1BA3;
            font-size: 12px;
        }

        .apply-filters-btn {
            background-color: #0C1BA3;
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 8px 15px;
            border-radius: 4px;
            box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.3);
            border: none;
            cursor: pointer;
        }

        /* Show Entities & Search Bar */
        .data-table-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        /* Removed .show-entities-text as it's no longer used for adding text */
        /* .show-entities-text { ... } */

        /* Removed .show-entities-select as it's now directly styled */
        /* .show-entities-select { ... } */

         .search-bar-container {
            position: relative;
            width: 250px;
            margin-right:-50px;
        }

        .custom-search-input {
            width: 100%;
            padding: 8px 35px 8px 12px;
            border: none;
            border-radius: 4px;
            box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.3);
            background-color: white;
            font-size: 12px;
            color: grey;
        }

        .search-icon {
            position: absolute;
            right: 60px !important;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            pointer-events: none;
        }

        /* Table Design */
        #reportedPostsTable {
            width: 100%; /* Make table take full width of its parent (.main-content-wrapper) */
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        #reportedPostsTable thead tr {
            background-color: white;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            margin-bottom: 10px;
            display: table-row;
        }

        #reportedPostsTable thead th {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: black; /* Changed to black for consistency */
            padding: 12px 10px;
            text-align: left;
            background-color: transparent;
            border-bottom: none;
        }

        #reportedPostsTable tbody tr {
            background-color: white;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            margin-bottom: 8px;
            display: table-row;
        }

        #reportedPostsTable tbody td {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 500;
            font-size: 12px;
            color: black; /* Changed to black for consistency */
            padding: 12px 10px;
            border: none;
            white-space: nowrap;
        }

        .view-details-btn {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700;
            font-size: 10px;
            background-color: #02FA72;
            color: #0C1BA3 !important;
            border-radius: 4px;
            padding: 6px 10px;
            border: none;
            text-decoration: none !important;
            display: inline-block;
        }
        
        /* Remove extra space in table cells */
        #reportedPostsTable tbody td:last-child {
            width: 1%;
            white-space: nowrap;
        }
        
        /* Compact table layout */
        .dataTables_wrapper .dataTables_scroll {
            margin-bottom: 0;
        }
        
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 10px;
        }

        /* DataTables Customizations */
        .dataTables_wrapper .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .dataTables_wrapper .dataTables_filter {
            position: relative;
            margin-left: auto; /* Push to the right */
            order: 2; /* Ensure it's on the right if other elements are added */
        }

        .dataTables_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            margin: 0;
        }

        /* Apply custom search input styles to the DataTables filter input */
        .dataTables_wrapper .dataTables_filter input {
            width: 250px;
            padding: 8px 35px 8px 12px;
            border: none;
            border-radius: 4px;
            box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.3);
            background-color: white;
            font-size: 12px;
            color: grey;
        }

        /* Apply custom search icon to the DataTables filter */
        .dataTables_wrapper .dataTables_filter .search-icon {
            position: absolute;
            right: 10px; /* Adjust as needed */
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            background-image: url('images/search.png'); /* Corrected path to be consistent */
            background-size: contain;
            background-repeat: no-repeat;
            pointer-events: none;
        }

        .dataTables_wrapper .dataTables_length {
            display: flex;
            align-items: center;
            color: #0C1BA3; /* Apply color to the whole label */
            font-size: 16px;
            font-weight: 600;
            order: 1;
        }

        /* Style the select element directly within the DataTables length control */
        .dataTables_wrapper .dataTables_length select {
            width: 80px;
            background-color: white;
            box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.3);
            border: none;
            border-radius: 4px;
            color: #0C1BA3;
            font-size: 12px;
            padding: 8px 12px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%230C1BA3"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 12px;
            margin: 0 5px; /* Adjust margin around the select box */
        }

        .dataTables_wrapper .dataTables_info {
            display: none; /* Hide the "Showing X to Y of Z entries" text */
        }

        .dataTables_wrapper .dataTables_paginate {
            text-align: center; /* Center the pagination */
            width: 100%; /* Take full width to allow centering */
            margin-right: 0;
            margin-top: 20px;
        }

        .dataTables_wrapper .dataTables_paginate .pagination {
            display: flex;
            justify-content: center; /* Center pagination items */
            align-items: center;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-item {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
            box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.3);
            border-radius: 4px;
            background-color: white; /* Ensure background for number pages */
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-item.active .page-link {
            background-color: #0C1BA3;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-link {
            color: #0C1BA3;
            font-weight: 600;
            border: none;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            background-color: transparent; /* Changed to transparent, parent takes care of bg */
        }

        /* Custom styles for pagination arrows (text-based) */
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next {
            padding: 0;
            background-color: white !important; /* Ensure background for arrow buttons */
            border: none !important;
            box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.3) !important; /* Add shadow to arrow buttons */
            margin: 0 5px;
            border-radius: 4px; /* Consistent border-radius */
            width: 30px; /* Set a fixed width for the arrow buttons */
            height: 30px; /* Set a fixed height for the arrow buttons */
            display: flex; /* Use flexbox to center content */
            align-items: center; /* Center content vertically */
            justify-content: center; /* Center content horizontally */
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button a {
            color: #0C1BA3; /* Color for active arrows */
            font-size: 16px; /* Adjust size as needed */
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled a {
            color: #ccc; /* Color for disabled arrows */
            cursor: not-allowed;
        }

        /* Ensure the pagination information and pagination controls are on the same line */
        .dataTables_wrapper .bottom {
            display: flex;
            justify-content: center; /* Center the entire bottom section */
            align-items: center;
            margin-right:500px;
            margin-top: 20px; /* Add some top margin to separate from the table */
        }

        /* Mobile adjustments */
        @media (max-width: 767.98px) {
            .main-content-wrapper {
                padding: 15px; /* Adjust padding for smaller screens */
                margin: 10px auto; /* Smaller vertical margin */
            }

            .filter-section {
                flex-direction: column; /* Stack filters vertically */
                align-items: flex-start;
                gap: 10px;
            }

            .filter-section form {
                width: 100%; /* Make the form take full width */
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .custom-select {
                width: 100%;
                max-width: none;
            }

            .apply-filters-btn, .btn-outline-secondary {
                width: 100%;
            }

            .data-table-controls {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            /* Removed .show-entities-text as it's no longer used for adding text */
            /* .show-entities-text, */
            .search-bar-container {
                width: 100%;
            }

            #reportedPostsTable thead th,
            #reportedPostsTable tbody td {
                padding: 8px;
                font-size: 14px;
            }

            .dataTables_wrapper .top {
                flex-direction: column;
                align-items: flex-start;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                width: 100%;
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                text-align: center;
                float: none;
                width: 100%; /* Ensure they take full width on mobile */
            }

            .dataTables_wrapper .bottom {
                flex-direction: column; /* Stack info and pagination on mobile */
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <?php include('admin_navbar.php'); ?>
    
    <div class="main-content-wrapper"> 
        <div class="main-content"> 
            <div class="container py-5">
                <h3 class="mb-4">Reported Posts</h3>
                
                <div class="filter-section">
                    <div class="filter-label">
                        <img src="images/filter.png" alt="Filter Icon" width="16" height="16">
                        Filter by:
                    </div>
                    <form id="filterForm" method="get" class="d-flex align-items-center gap-3">
                        <select name="reason" class="custom-select" id="reasonFilter">
                            <option value="">All Reasons</option>
                            <?php foreach ($reasons as $reason): ?>
                                <option value="<?= htmlspecialchars($reason) ?>" <?= isset($_GET['reason']) && $_GET['reason'] === $reason ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($reason) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="apply-filters-btn">Apply Filters</button>
                        </form>
                </div>
                
                <table id="reportedPostsTable" class="table">
                    <thead>
                        <tr>
                            <th>Posted By</th>
                            <th>Reports</th>
                            <th>Reason(s)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportedPosts as $post): ?>
                            <tr>
                                <td><?= htmlspecialchars($post['posted_by']) ?></td>
                                <td><?= $post['report_count'] ?></td>
                                <td><?= htmlspecialchars($post['reasons']) ?></td>
                                <td>
                                    <a href="report_details.php?post_id=<?= $post['post_id'] ?>&post_institution_id=<?= $post['post_institution_id'] ?>&post_owner=<?= $post['post_owner'] ?>" 
                                       class="view-details-btn">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#reportedPostsTable').DataTable({
                dom: '<"top"lf>rt<"bottom"p>',
                paging: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    paginate: {
                        previous: '&#x2190;',
                        next: '&#x2192;'
                    },
                    info: ""
                },
                responsive: true,
                autoWidth: false,
                scrollX: false,
                columnDefs: [
                    { "width": "25%", "targets": 0 },
                    { "width": "15%", "targets": 1 },
                    { "width": "45%", "targets": 2 },
                    { "width": "15%", "targets": 3 }
                ],
                initComplete: function() {
                    $('#reportedPostsTable_filter input')
                        .addClass('custom-search-input')
                        .wrap('<div class="search-bar-container"></div>');
                    
                    $('#reportedPostsTable_filter .search-bar-container').append('<img src="images/search.png" alt="Search Icon" class="search-icon">');
                    
                    $('#reportedPostsTable_filter label')
                        .contents()
                        .filter(function(){
                            return this.nodeType === 3;
                        })
                        .remove();

                    $('#reportedPostsTable_length select').addClass('custom-select');
                    
                    $('.dataTables_paginate .paginate_button.previous').addClass('page-item');
                    $('.dataTables_paginate .paginate_button.next').addClass('page-item');
                }
            });
            
            // Removed the instant submission of the filter form,
            // so the "Apply Filters" button is now required.
            // DataTables' search functionality remains instant.
        });
    </script>
</body>
</html>