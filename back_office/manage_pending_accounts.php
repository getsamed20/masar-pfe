<?php
session_start();
include 'db.php';

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

$sql_pending = "SELECT * FROM pending_accounts WHERE validated = 0";
$result_pending = mysqli_query($conn, $sql_pending);

$pending_accounts = [];
while ($row = mysqli_fetch_assoc($result_pending)) {
    $pending_accounts[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Accounts - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #F2F6FF;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            overflow-x: hidden;
        }

        /* New wrapper to control overall content width and centering */
        .main-content-wrapper {
            max-width: 1200px; /* Adjust this value to control the overall width */
            margin: 20px auto; /* Centers the content wrapper and adds vertical spacing */
            padding: 20px; /* Padding inside the wrapper */
            background-color: transparent; /* No background for the wrapper itself */
        }

        /* --- Filter Section Styles --- */
        .filter-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
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

        /* Table Design */
        #pendingTable {
            width: 100%; /* Make table take full width of its parent (.main-content-wrapper) */
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        #pendingTable thead tr {
            background-color: white;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            margin-bottom: 10px;
            display: table-row;
        }

        #pendingTable thead th {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: black;
            padding: 15px 10px;
            text-align: left;
            background-color: transparent;
            border-bottom: none;
        }

        #pendingTable thead th:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        #pendingTable thead th:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        #pendingTable tbody tr {
            background-color: white;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            margin-bottom: 10px;
            display: table-row;
            cursor: pointer;
        }

        #pendingTable tbody tr td:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        #pendingTable tbody tr td:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        #pendingTable tbody td {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 500;
            font-size: 12px;
            color: black;
            padding: 15px 10px;
            border: none;
        }

        #pendingTable tbody td a {
            color: #0C1BA3;
            text-decoration: underline;
        }

        .action-buttons-container {
            display: flex;
            gap: 5px;
        }

        .action-buttons-container .btn {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700;
            font-size: 10px;
            color: #EFEFFF !important;
            border-radius: 4px;
            padding: 6px 10px;
            border: none;
            text-decoration: none !important;
        }

        .action-buttons-container .btn-success {
            background-color: #03A42B;
        }

        .action-buttons-container .btn-danger {
            background-color: #B70600;
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
            width: 250px;
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
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            background-image: url('images/search.png'); /* Corrected path */
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

        /* Hide the DataTables info text */
        .dataTables_wrapper .dataTables_info {
            display: none; /* This will hide the "Showing 1 to 10 of 26 entries" text */
        }

        .dataTables_wrapper .dataTables_paginate {
            /* float: right; This was pushing it right, remove to allow centering */
            margin-right: 0;
            margin-top: 20px;
            text-align: center; /* Center the pagination control itself */
            width: 100%; /* Take full width to allow centering */
        }

        .dataTables_wrapper .dataTables_paginate .pagination {
            display: flex;
            justify-content: center; /* Center the pagination items */
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
            background-color: white;
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
            background-color: transparent;
        }

        /* Custom styles for pagination arrows (text-based) */
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next {
            padding: 0;
            background-color: white !important;
            border: none !important;
            box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.3) !important;
            margin: 0 5px;
            border-radius: 4px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button a {
            color: #0C1BA3;
            font-size: 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled a {
            color: #ccc;
            cursor: not-allowed;
        }

        /* Ensure the pagination controls are in the bottom middle */
        .dataTables_wrapper .bottom {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center;
            margin-left: 0;
            margin-right: 550px;
            margin-top: 20px;
        }


        /* Mobile adjustments */
        @media (max-width: 767.98px) {
            .main-content-wrapper {
                padding: 15px;
                margin: 10px auto;
            }

            .filter-section {
                gap: 10px;
            }

            .custom-select {
                width: 100%;
                max-width: none;
            }

            .apply-filters-btn {
                width: 100%;
            }

            #pendingTable thead th,
            #pendingTable tbody td {
                padding: 8px;
                font-size: 14px;
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
                width: 100%;
            }

            .dataTables_wrapper .bottom {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="main-content-wrapper">
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
            <option value="institution">Institution</option>
        </select>
        <button class="apply-filters-btn">Apply Filters</button>
    </div>

    <table id="pendingTable" class="table">
        <thead>
            <tr>
                <th>Role</th>
                <th>Name</th>
                <th>Email</th>
                <th>Unique Identifier</th>
                <th>Commercial Register</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pending_accounts as $account) { ?>
                <tr data-role="<?php echo htmlspecialchars($account['role']); ?>">
                    <td><?php echo htmlspecialchars($account['role']); ?></td>
                    <td><?php echo htmlspecialchars($account['name']); ?></td>
                    <td><?php echo htmlspecialchars($account['email']); ?></td>
                    <td><?php echo htmlspecialchars($account['unique_identifier']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($account['commercial_register']); ?>" target="_blank">View File</a></td>
                    <td><?php echo htmlspecialchars($account['created_at']); ?></td>
                    <td>
                        <div class="action-buttons-container">
                            <a href="validate_account.php?id=<?php echo htmlspecialchars($account['id']); ?>" class="btn btn-success" onclick="return confirm('Are you sure you want to validate account?');">Validate</a>
                            <a href="reject_account.php?id=<?php echo htmlspecialchars($account['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject account?');">Reject</a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        var table = $('#pendingTable').DataTable({
            // 'dom' specifies the layout of DataTables elements:
            // l: length changing input control
            // f: filtering input
            // t: The table itself
            // i: Table information summary
            // p: Pagination control
            // <"bottom"pi> places info and pagination in a flex container at the bottom
            dom: '<"top"lf>rt<"bottom"p>', // Removed 'i' from 'dom' to hide info
            paging: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                search: "", // Remove default "Search:" text
                searchPlaceholder: "Search...", // Add placeholder
                paginate: {
                    previous: '&#x2190;', // Unicode left arrow
                    next: '&#x2192;'      // Unicode right arrow
                },
                info: "" // Explicitly set info to an empty string to remove it
            },
            responsive: true,
            columnDefs: [
                { "targets": 0, "visible": true }
            ],
            initComplete: function () {
                // Adjusting search input appearance
                $('#pendingTable_filter input').addClass('custom-search-input').wrap('<div class="search-bar-container"></div>');
                // Ensure the search icon path is correct
                $('#pendingTable_filter .search-bar-container').append('<img src="images/search.png" alt="Search Icon" class="search-icon">');
                $('#pendingTable_filter label').contents().filter(function(){
                    return this.nodeType === 3;
                }).remove(); // Remove default "Search:" text

                // Adjusting length menu appearance
                $('#pendingTable_length label').contents().filter(function(){
                    return this.nodeType === 3;
                }).replaceWith('<span class="show-entities-text">Show </span>'); // Replace "Show" text
                $('#pendingTable_length label').append('<span class="show-entities-text"> entities</span>'); // Add "entities" text
                $('#pendingTable_length select').addClass('custom-select show-entities-select'); // Add custom classes

                // Removed the line that appends .dataTables_info to .dataTables_wrapper .bottom
                // as we are now hiding the info text.

                // Add .page-item class to previous/next buttons for consistent styling
                $('.dataTables_paginate .paginate_button.previous').addClass('page-item');
                $('.dataTables_paginate .paginate_button.next').addClass('page-item');
            }
        });

        // --- Filter functionality (kept as is) ---
        $('#roleFilter').on('change', function () {
            var selectedRole = $(this).val();
            // This regex ensures only exact matches for selected roles are shown
            table.column(0).search(selectedRole ? '^' + selectedRole + '$' : '', true, false).draw();
        });

        $('.apply-filters-btn').on('click', function() {
            table.draw(); // Redraw the table to apply filters
        });
        // --- End Filter functionality ---
    });
</script>
</body>
</html>