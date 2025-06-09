<?php
session_start();
include 'db.php'; // Assuming db.php is in the same directory as admin_dashboard.php, or '../db.php' if it's one level up. If 'db.php' is in 'masar-pfe/' then it should be `../../db.php`

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

$admin_email = '';
$user_id = $_SESSION['user_id'];
$sql = "SELECT email FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $admin_email = $row['email'];
}

$sql_users = "SELECT
                u.user_id,
                u.email,
                u.role,
                u.status,
                COALESCE(s.startup_name, pi.institution_name) AS name,
                COALESCE(s.logo, pi.logo) AS logo_filename, -- Renamed to avoid confusion with the full path variable
                COALESCE(s.about_section, pi.description) AS about_section
              FROM users u
              LEFT JOIN startups s ON u.user_id = s.user_id AND u.role = 'startup'
              LEFT JOIN public_institutions pi ON u.user_id = pi.user_id AND u.role = 'institution'
              WHERE u.role IN ('startup', 'institution')";

$result_users = mysqli_query($conn, $sql_users);

$users = [];
while ($row = mysqli_fetch_assoc($result_users)) {
    $row['name'] = !empty($row['name']) ? $row['name'] : 'Unknown';

    // IMPORTANT: Corrected path for uploaded files
    // From 'back-office/admin_dashboard.php', go up two levels to 'masar-pfe/' (../../)
    // Then down into 'front-office/uploads/'
    $uploaded_logo_full_path = '../../front-office/uploads/' . $row['logo_filename'];

    // Check if the uploaded file exists and is not empty
    if (!empty($row['logo_filename']) && file_exists($uploaded_logo_full_path)) {
        $row['logo_display_path'] = $uploaded_logo_full_path;
    } else {
        // Corrected path for default profile image
        // From 'back-office/admin_dashboard.php', 'images/' is in the same directory
        $row['logo_display_path'] = 'images/default-profile.png';
    }

    $row['about_section'] = !empty($row['about_section']) ? $row['about_section'] : 'No information available';
    $users[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Hebrew:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #F2F6FF;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            overflow-x: hidden;
        }

        .container-fluid {
            padding: 20px;
        }

        .row {
            display: flex;
            flex-wrap: nowrap;
            gap: 20px;
            align-items: flex-start;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            transition: all 0.3s;
            min-width: 0;
            margin-top: 0;
        }

        @media (max-width: 1199.98px) {
            .row {
                flex-wrap: wrap;
            }
            .main-content {
                min-width: auto;
                padding: 15px;
            }
            .profile-sidebar {
                position: relative !important;
                width: 100% !important;
                margin-top: 20px;
                margin-left: 0 !important;
            }
        }

        /* --- Filter Section Styles --- */
        .filter-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            margin-left: 0;
            flex-wrap: wrap;
            margin-top: 0;
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
            width: auto;
            min-width: 120px;
            max-width: 250px;
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
            margin-left: 0;
            margin-right: 0;
        }

        .show-entities-text {
            color: #0C1BA3;
            font-size: 16px;
            font-weight: 600;
        }

        .show-entities-select {
            width: 80px;
        }

        .search-bar-container {
            position: relative;
            width: 250px;
            margin-right:300px;
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
        #userTable {
            width: 1200px;
            border-collapse: separate;
            border-spacing: 0 10px;
            margin-left: 0;
            margin-right: auto;
        }

        #userTable thead tr {
            background-color: white;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            margin-bottom: 10px;
            display: table-row;
        }

        #userTable thead th {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: black;
            padding: 15px 10px;
            text-align: left;
            background-color: transparent;
            border-bottom: none;
        }

        #userTable thead th:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        #userTable thead th:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        #userTable tbody tr {
            background-color: white;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            margin-bottom: 10px;
            display: table-row;
            cursor: pointer;
        }

        #userTable tbody tr td:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        #userTable tbody tr td:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        #userTable tbody td {
            font-family: 'IBM Plex Sans Hebrew', sans-serif;
            font-weight: 500;
            font-size: 12px;
            color: black;
            padding: 15px 10px;
            border: none;
        }

        #userTable tbody td a {
            color: #0C1BA3;
            text-decoration: underline;
        }

        .status-active {
            color: #19BC19 !important;
        }

        .status-inactive {
            color: #CC0000 !important;
        }

        #userTable tbody tr.selected-row {
            background-color: #02FA72;
        }

        #userTable tbody tr.selected-row td {
            color: #0C1BA3;
        }

        .profile-sidebar {
            position: fixed;
            top: 90px;
            right: 60px;
            width: 300px;
            background-color: white;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            border-radius: 20px;
            padding: 25px;
            display: none;
            z-index: 1000;
            max-height: calc(100vh - 110px);
            overflow-y: auto;
            flex-shrink: 0;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: block;
            object-fit: cover;
        }

        .profile-name {
            color: #0C1BA3;
            font-weight: 700;
            font-size: 18px;
            text-align: center;
            margin-bottom: 25px;
        }

        .profile-about {
            color: #0C1BA3;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .profile-description {
            color: black;
            font-weight: 500;
            font-size: 12px;
            margin-bottom: 30px;
            height: 200px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .status-btn {
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 12px;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3);
            display: block;
            margin: 0 auto;
        }

        .activate-btn {
            background-color: #19BC19;
            color: white;
        }

        .deactivate-btn {
            background-color: #CC0000;
            color: white;
        }

        /* DataTables Customizations */
        .dataTables_wrapper .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            margin-left: 0;
            margin-right: 0;
        }

        .dataTables_wrapper .dataTables_filter {
            position: relative;
            margin-left: auto;
            order: 2;
        }

        .dataTables_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            margin: 0;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 100%; /* Make input take full width of its container */
            padding: 8px 35px 8px 12px;
            border: none;
            border-radius: 4px;
            box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.3);
            background-color: white;
            font-size: 12px;
            color: grey;
        }

        .dataTables_wrapper .dataTables_filter .search-icon {
            position: absolute;
            right: 10px; /* Adjust as needed */
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            background-image: url('images/search.png'); /* Corrected path: image is in back-office/images/ */
            background-size: contain;
            background-repeat: no-repeat;
            pointer-events: none;
        }

        .dataTables_wrapper .dataTables_length {
            display: flex;
            align-items: center;
            color: #0C1BA3;
            font-size: 16px;
            font-weight: 600;
            order: 1;
        }

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
            margin: 0 5px;
        }

        /* Hide the DataTables information text */
        .dataTables_wrapper .dataTables_info {
            display: none;
        }

        .dataTables_wrapper .dataTables_paginate {
            display: flex; /* Use flexbox for centering */
            justify-content: center; /* Center horizontally */
            width: 100%; /* Take full width to allow centering */
            margin-top: 20px;
            margin-left: 0; /* Remove default margin-left */
            margin-right: 0; /* Remove default margin-right */
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
            justify-content: center; /* Center the pagination */
            align-items: center;
            margin-left: 0;
            margin-right: 350px;
            margin-top: 20px; /* Add some top margin to separate from the table */
        }

        /* Mobile adjustments */
        @media (max-width: 767.98px) {
            .filter-section {
                gap: 10px;
            }

            .custom-select {
                width: 100%;
                max-width: none;
            }

            .status-filter-container {
                width: 100%;
                justify-content: space-between;
            }

            .apply-filters-btn {
                width: 100%;
            }

            #userTable thead th,
            #userTable tbody td {
                padding: 8px;
                font-size: 14px;
            }

            .profile-sidebar {
                position: fixed !important;
                top: 70px !important;
                right: 10px !important;
                left: 10px !important;
                width: auto !important;
                height: auto;
                padding: 15px;
                z-index: 1050;
            }

            .profile-description {
                height: 150px;
            }

            .dataTables_wrapper .top {
                flex-direction: column;
                align-items: flex-start;
                margin-left: 0;
                margin-right: 0;
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
                margin-left: 0;
                margin-right: 0;
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

<?php include('admin_navbar.php'); // Assuming admin_navbar.php is in the same directory ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-auto main-content">
            <div class="filter-section">
                <div class="filter-label">
                    <img src="images/filter.png" alt="Filter Icon" width="16" height="16">
                    Filter by:
                </div>
                <div class="filter-label">
                    Role:
                </div>
                <select id="roleFilter" class="custom-select">
                    <option value="">All</option>
                    <option value="startup">Startup</option>
                    <option value="institution">Public Institution</option>
                </select>

                <div class="filter-label">
                    Status:
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input status-filter" type="checkbox" id="activeCheckbox" value="active" checked>
                    <label class="form-check-label status-text" for="activeCheckbox">Active</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input status-filter" type="checkbox" id="inactiveCheckbox" value="inactive" checked>
                    <label class="form-check-label status-text" for="inactiveCheckbox">Inactive</label>
                </div>

                <button class="apply-filters-btn">Apply Filters</button>
            </div>

            <table id="userTable" class="user-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr data-user-id="<?php echo $user['user_id']; ?>"
                            data-name="<?php echo htmlspecialchars($user['name']); ?>"
                            data-logo="<?php echo htmlspecialchars($user['logo_display_path']); ?>"
                            data-about="<?php echo htmlspecialchars($user['about_section']); ?>"
                            data-status="<?php echo $user['status']; ?>">
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <img src="<?php echo htmlspecialchars($user['logo_display_path']); ?>" alt="Profile" width="40" height="40" style="border-radius: 50%; object-fit: cover;">
                                    <span><?php echo htmlspecialchars($user['name']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="<?php echo $user['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo htmlspecialchars($user['status']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="col-lg-auto">
            <div class="profile-sidebar" id="profileSidebar">
                <img src="images/default-profile.png" alt="Profile" class="profile-pic" id="profilePic">
                <div class="profile-name" id="profileName"></div>
                <div class="profile-about">About</div>
                <div class="profile-description" id="profileDescription"></div>
                <button class="status-btn" id="profileStatusBtn"></button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        const table = $('#userTable').DataTable({
            // 'dom' specifies the layout of DataTables elements:
            // l: length changing input control
            // f: filtering input
            // t: The table itself
            // i: Table information summary
            // p: Pagination control
            // <"bottom"pi> places info and pagination in a flex container at the bottom
            dom: '<"top"lf>rt<"bottom"p>', // Removed 'i' from dom to hide the info text
            paging: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                search: "", // Remove default "Search:" text
                searchPlaceholder: "", // You can add a placeholder here if needed
                paginate: {
                    previous: '&#x2190;', // Unicode left arrow
                    next: '&#x2192;'      // Unicode right arrow
                },
                info: "" // Set info to an empty string to completely remove the text
            },
            responsive: true,
            initComplete: function() {
                // Adjusting search input appearance
                $('#userTable_filter input').addClass('custom-search-input').wrap('<div class="search-bar-container"></div>');
                // Ensure the search icon path is correct
                $('#userTable_filter .search-bar-container').append('<img src="images/search.png" alt="Search Icon" class="search-icon">');
                $('#userTable_filter label').contents().filter(function(){
                    return this.nodeType === 3;
                }).remove(); // Remove default "Search:" text

                // Adjusting length menu appearance
                $('#userTable_length label').contents().filter(function(){
                    return this.nodeType === 3;
                }).replaceWith('<span class="show-entities-text">Show </span>'); // Replace "Show" text
                $('#userTable_length label').append('<span class="show-entities-text"> entities</span>'); // Add "entities" text
                $('#userTable_length select').addClass('custom-select show-entities-select'); // Add custom classes

                // No need to append .dataTables_info to .bottom anymore since it's hidden
            }
        });

        // --- Filter functionality (kept as is) ---
        $('#roleFilter').on('change', function () {
            const role = this.value;
            table.column(2).search(role ? '^' + role + '$' : '', true, false).draw();
        });

        function updateStatusFilter() {
            let filters = $('.status-filter:checked').map(function() {
                return this.value;
            }).get();

            // This regex ensures only exact matches for selected statuses are shown
            table.column(3).search(
                filters.length ? '^(' + filters.join('|') + ')$' : 'ImpossibleToMatchString',
                true, // Use regex
                false // Don't use smart filtering (full word matches)
            ).draw();
        }
        $('.status-filter').on('change', updateStatusFilter);

        $('.apply-filters-btn').on('click', function() {
            table.draw(); // Redraw the table to apply filters
        });

        // Initial filter application
        updateStatusFilter();
        // --- End Filter functionality ---

        // Handle row selection
        $('#userTable tbody').on('click', 'tr', function () {
            const $row = $(this);
            const data = $row.data();

            // Remove selected class from all rows
            $('#userTable tbody tr').removeClass('selected-row');
            // Reset text color for all cells to default (black)
            $('#userTable tbody td').css('color', 'black');

            // Add selected class to the clicked row
            $row.addClass('selected-row');
            // Change text color for cells in the selected row
            $row.find('td').css('color', '#0C1BA3');

            // Reapply specific status text colors for the selected row to override the general selected-row color
            $row.find('td.status-active').css('color', '#19BC19');
            $row.find('td.status-inactive').css('color', '#CC0000');

            // Display profile sidebar
            $('#profileSidebar').show();
            // Populate profile sidebar with data from the clicked row
            $('#profilePic').attr('src', data.logo);
            $('#profileName').text(data.name);
            $('#profileDescription').text(data.about);

            const btn = $('#profileStatusBtn');
            // Update the status button text and action based on the user's current status
            if (data.status === 'active') {
                btn.removeClass('activate-btn').addClass('deactivate-btn')
                    .text('Deactivate').attr('onclick', `changeStatus(${data.userId}, 'inactive')`);
            } else {
                btn.removeClass('deactivate-btn').addClass('activate-btn')
                    .text('Activate').attr('onclick', `changeStatus(${data.userId}, 'active')`);
            }
        });
    });

    // Function to change user status via AJAX or page redirect
    function changeStatus(userId, status) {
        if (confirm(`Are you sure you want to ${status === 'active' ? 'activate' : 'deactivate'} this account?`)) {
            window.location.href = `change_status.php?user_id=${userId}&status=${status}`;
        }
    }
</script>
</body>
</html>