
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="loginModalLabel">Access Denied</h5>
      </div>
      <div class="modal-body">
        You must be logged in to view this page.
      </div>
      <div class="modal-footer">
        <a href="../authentication/login.php" class="btn btn-primary">Go to Login</a>
        <a href="../pages/home.php" class="btn btn-secondary">Back to Home</a>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var myModal = new bootstrap.Modal(document.getElementById('loginModal'));
        myModal.show();
    });
</script>
<?php exit(); endif; ?>
