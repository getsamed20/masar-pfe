<?php
include('../includes/db.php');

$startup_id = $_SESSION['startup_id'];

$query = "SELECT s.*, c.title AS challenge_title, pi.institution_name
          FROM solutions s
          JOIN challenges c ON s.challenge_id = c.challenge_id
          JOIN public_institutions pi ON c.institution_id = pi.institution_id
          WHERE s.startup_id = '$startup_id'
          ORDER BY s.submitted_at DESC";

$result = mysqli_query($conn, $query);
?>

<?php if (mysqli_num_rows($result) > 0): ?>
    <div style="background: white; border-radius: 30px; box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3); padding: 20px; width: 100%;">
        <div class="table-responsive">
            <table class="table" style="border-collapse: separate; border-spacing: 0 10px;">
                <thead>
                    <tr>
                        <th style="color: #343a40; border-top: none; border-bottom: none;">Project Name & Date</th>
                        <th style="color: #343a40; border-top: none; border-bottom: none;">Posted By</th>
                        <th style="color: #343a40; border-top: none; border-bottom: none;">Project Details</th>
                        <th style="color: #343a40; border-top: none; border-bottom: none;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($solution = mysqli_fetch_assoc($result)): ?>
                        <tr style="background-color: #f9f9f9; border-radius: 15px;">
                            <td style="border-top: none; border-bottom: none;">
                                <strong><?php echo htmlspecialchars($solution['proposal_title']); ?></strong><br>
                                <small class="text-muted"><?php echo date('F j, Y', strtotime($solution['submitted_at'])); ?></small>
                            </td>
                            <td style="border-top: none; border-bottom: none;">
                                <span style="background-color: #477DFF; color: white; padding: 3px 12px; border-radius: 8px; font-size: 0.85em; display: inline-block; min-width: 50px; max-width: 120px; text-align: center;">
                                    <?php echo htmlspecialchars($solution['institution_name']); ?>
                                </span>
                            </td>
                            <td style="border-top: none; border-bottom: none;">
                                <?php
                                $description = htmlspecialchars($solution['proposal_description']);
                                echo nl2br(strlen($description) > 250 ? substr($description, 0, 250) . '...' : $description);
                                ?>
                            </td>
                            <td style="border-top: none; border-bottom: none;">
                                <?php
                                $status_text = ucfirst($solution['status']);
                                $status_bg_color = '';
                                $status_text_color = '';

                                switch ($solution['status']) {
                                    case 'pending':
                                        $status_bg_color = '#CCFFD0';
                                        $status_text_color = '#0C1BA3';
                                        break;
                                    case 'under review':
                                        $status_bg_color = '#ADD8E6';
                                        $status_text_color = '#000080';
                                        break;
                                    case 'rejected':
                                        $status_bg_color = '#FF0004';
                                        $status_text_color = 'white';
                                        break;
                                    case 'selected':
                                        $status_bg_color = '#64C40C';
                                        $status_text_color = 'white';
                                        break;
                                    default:
                                        $status_bg_color = '#CCCCCC';
                                        $status_text_color = '#333333';
                                        break;
                                }
                                ?>
                                <span style="background-color: <?php echo $status_bg_color; ?>; color: <?php echo $status_text_color; ?>; padding: 3px 12px; border-radius: 8px; font-size: 0.85em; display: inline-block; min-width: 90px; text-align: center;">
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <p class="text-muted">You haven't proposed any solutions yet.</p>
<?php endif; ?>