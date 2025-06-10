<?php
session_start();
include('../includes/db.php');

$error = "";
$registrationSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $unique_identifier = $_POST['unique_identifier'];

    if (!isset($_POST['termsCheckbox'])) {
        $error = "You must accept the Terms & Conditions.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $commercial_register = $_FILES['commercial_register']['name'];
        $target_dir = "../uploads/";
        $commercial_register_target = $target_dir . basename($commercial_register);
        $commercial_register_type = strtolower(pathinfo($commercial_register_target, PATHINFO_EXTENSION));

        $logo = $_FILES['logo']['name'];
        $logo_tmp = $_FILES['logo']['tmp_name'];
        $logo_ext = strtolower(pathinfo($logo, PATHINFO_EXTENSION));
        $allowed_image_types = ['jpg', 'jpeg', 'png'];
        $logo_file = "";

        if (empty($logo) || $_FILES['logo']['error'] != 0 || !in_array($logo_ext, $allowed_image_types)) {
            $error = "Please upload a valid logo (JPG, JPEG, PNG).";
        } else {
            $check_email = mysqli_query($conn, "SELECT * FROM pending_accounts WHERE email = '$email'");
            $check_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

            if (mysqli_num_rows($check_email) === 0 && mysqli_num_rows($check_users) === 0
                && $_FILES['commercial_register']['error'] == 0 && $commercial_register_type == "pdf") {

                if (move_uploaded_file($_FILES["commercial_register"]["tmp_name"], $commercial_register_target)) {
                    $logo_file = uniqid() . "." . $logo_ext;
                    $logo_target = $target_dir . $logo_file;
                    move_uploaded_file($logo_tmp, $logo_target);


                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $insert_pending = mysqli_query($conn,
                        "INSERT INTO pending_accounts (name, email, password, unique_identifier, commercial_register, role, logo)
                         VALUES ('$name', '$email', '$hashed_password', '$unique_identifier', '$commercial_register', '$role', '$logo_file')");

                    if ($insert_pending) {
                        $registrationSuccess = true;
                    } else {
                        $error = "Something went wrong while saving to pending accounts.";
                    }
                } else {
                    $error = "Failed to upload the PDF.";
                }

            } else {
                $error = "Email already exists or invalid file.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('../images/auth_bg.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-end; 
        }

        .register-container {
            width: 45%; /* Slightly smaller width on larger screens */
            background-color: rgba(255, 255, 255, 0.95);
            padding: 20px; /* Slightly reduced padding */
            box-shadow: -4px 0 15px rgba(0,0,0,0.1);
            height: 100vh;
            overflow-y: auto;
            border-radius: 60px 0 0 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-wrapper {
            width: 100%;
            max-width: 450px; /* Max width for form elements */
        }

        .form-control {
            height: 48px; /* Slightly smaller height */
            font-size: 16px; /* Slightly smaller font size */
            border-radius: 8px; /* Slightly smaller border-radius */
        }

        .center {
            text-align: center;
        }

        .error {
            color: red;
            font-size: 13px; /* Slightly smaller error font */
        }
        .custom-file-upload {
            position: relative;
            width: 100%; /* Make it responsive */
            height: 48px; /* Match form-control height */
            font-size: 16px; /* Match form-control font size */
            border-radius: 8px; /* Match form-control border-radius */
            overflow: hidden;
        }

        .custom-file-upload input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .custom-file-label {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            color: grey;
            border: 1px solid #ccc;
            border-radius: 8px; /* Match form-control border-radius */
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            box-sizing: border-box;
            pointer-events: none;
            z-index: 1;
        }

        .custom-file-label i {
            color: grey;
            font-size: 18px; /* Slightly smaller icon */
        }
        .hidden { display: none; }



        .logo-upload-label {
            display: inline-block;
            cursor: pointer;
        }

        .logo-preview {
            width: 100px; /* Smaller logo preview */
            height: 100px; /* Smaller logo preview */
            border-radius: 50%;
            border: 2px dashed #ccc;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            overflow: hidden;
            position: relative;
            background-size: cover;
            background-position: center;
        }

        .logo-preview .icon {
            font-size: 28px; /* Slightly smaller icon */
            color: #999;
        }

        .custom-select {
            width: 100%;
            height: 48px; /* Match form-control height */
            font-size: 16px; /* Match form-control font size */
            border-radius: 8px; /* Match form-control border-radius */
            color: grey;
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 10px 15px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="10" fill="grey"><path d="M0 0l7 7 7-7"/></svg>');
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }

        .custom-select:focus {
            outline: none;
            border-color: #888;
        }

        .custom-select option {
            color: black;
        }

        .custom-select option:disabled {
            color: grey;
        }

        /* Stepper Styles (remain unchanged) */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
            position: relative;
        }

        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .stepper-item::before {
            content: '';
            position: absolute;
            top: 15px; /* Adjust to align with circle center */
            left: -50%;
            width: 100%;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 0;
        }

        .stepper-item:first-child::before {
            content: none;
        }

        .stepper-counter {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e0e0e0;
            color: white;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
            margin-bottom: 5px;
        }

        .stepper-item.active .stepper-counter {
            background-color: #0C1BA3; /* Your brand blue */
        }

        .stepper-item.completed .stepper-counter {
            background-color: #28a745; /* Green for completed */
        }

        .stepper-label {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }

        .stepper-item.active .stepper-label {
            color: #0C1BA3;
            font-weight: bold;
        }

        /* Media Query for Smaller Screens */
        @media (max-width: 768px) {
            body {
                justify-content: center; /* Center the content on smaller screens */
            }

            .register-container {
                width: 100%; /* Take full width on smaller screens */
                border-radius: 0; /* Remove border-radius for full width on small screens */
                box-shadow: none; 
                padding: 15px; 
            }

            .custom-file-upload {
                width: 100%; /* Make custom file upload full width */
            }

            .form-wrapper {
                max-width: 100%; /* Allow form wrapper to take full width of container */
                padding: 0 15px; /* Add some horizontal padding inside form-wrapper */
            }
        }
    </style>
</head>
<body>

<div class="register-container">
    <div id="step1" class="<?php echo $registrationSuccess ? 'hidden' : ''; ?>">

        <div class="form-wrapper">
            <div class="center mb-4">
                <img src="../images/masar-logo.png" alt="Masar Logo" class="mb-3">
                <div class="stepper-wrapper">
                    <div class="stepper-item active">
                        <div class="stepper-counter">1</div>
                        <div class="stepper-label">Account Details</div>
                    </div>
                    <div class="stepper-item">
                        <div class="stepper-counter">2</div>
                        <div class="stepper-label">Review</div>
                    </div>
                    <div class="stepper-item">
                        <div class="stepper-counter">3</div>
                        <div class="stepper-label">Completion</div>
                    </div>
                </div>
                <h2>Welcome! First things first...</h2>
            </div>
            <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form id="signupForm" method="POST" enctype="multipart/form-data">
                <div class="mb-3 text-center">
                    <label for="logoUpload" class="logo-upload-label">
                        <div class="logo-preview" id="logoPreview">
                            <i class="bi bi-camera-fill icon"></i>
                        </div>
                        <input type="file" name="logo" id="logoUpload" accept="image/*" class="form-control d-none">
                    </label>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Organization Name" required>
                    </div>
                    <div class="col-md-6">
                        <select class="custom-select" id="role" name="role" required>
                            <option selected disabled>Organization Type</option>
                            <option value="startup">Startup</option>
                            <option value="institution">Public Institution</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="confirmPassword" class="form-control" placeholder="Confirm Password" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="unique_identifier" class="form-control" placeholder="Unique Identifier" required>
                </div>
                <div class="mb-3">
                    <div class="custom-file-upload">
                        <input type="file" name="commercial_register" accept=".pdf" required onchange="updateFileName(this)">
                        <div class="custom-file-label" id="fileLabel">
                            <span id="fileLabelText">Document</span>
                            <i class="fas fa-file-pdf"></i>
                        </div>
                    </div>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="termsCheckbox" name="termsCheckbox" required>
                    <label class="form-check-label" for="termsCheckbox">
                        I read and accept <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
            </form>

            <p class="mt-3 text-center" style="color: #9FA5B8;">Already have an account? <a href="login.php">Sign In</a></p>
        </div>
    </div>


    <div id="step2" class="<?php echo $registrationSuccess ? '' : 'hidden'; ?> text-center">
        <div class="center mb-4">
            <img src="../images/masar-logo.png" alt="Masar Logo" class="mb-3">
            <div class="stepper-wrapper">
                <div class="stepper-item completed">
                    <div class="stepper-counter">1</div>
                    <div class="stepper-label">Account Details</div>
                </div>
                <div class="stepper-item completed">
                    <div class="stepper-counter">2</div>
                    <div class="stepper-label">Review</div>
                </div>
                <div class="stepper-item active">
                    <div class="stepper-counter">3</div>
                    <div class="stepper-label">Completion</div>
                </div>
            </div>
        </div>
        <div class="center mb-4">
            <img src="../images/ok.png">
            <h1 style="color: #414141; font-size:34pt">Your Request Has Been Sent</h1>
            <p style="color: #818181; font-size:24pt">Our team is reviewing your information. You’ll hear back by email within 1 to 24 hours.</p>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $("#signupForm").validate({
            rules: {
                name: "required",
                role: "required",
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6 // Password must be at least 6 characters
                },
                confirmPassword: {
                    required: true,
                    equalTo: "#password"
                },
                unique_identifier: {
                    required: true,
                    minlength: 8,
                    maxlength: 8
                },
                commercial_register: "required",
                logo: "required", // Photo is required
                termsCheckbox: "required" // Terms & Conditions checkbox is required
            },
            messages: {
                name: "Please enter your organization name.",
                role: "Please select your organization type.",
                email: {
                    required: "Please enter your email address.",
                    email: "Please enter a valid email address."
                },
                password: {
                    required: "Please provide a password.",
                    minlength: "Your password must be at least 6 characters long."
                },
                confirmPassword: {
                    required: "Please confirm your password.",
                    equalTo: "Passwords don't match."
                },
                unique_identifier: {
                    required: "Please enter a unique identifier.",
                    minlength: "Your unique identifier must be 8 characters long.",
                    maxlength: "Your unique identifier must be 8 characters long."
                },
                commercial_register: "Please upload your commercial register document (PDF).",
                logo: "Please upload your organization's logo.", // Message for required logo
                termsCheckbox: "You must accept the Terms & Conditions." // Message for required terms
            },
            errorClass: "error", // This will apply the red color defined in your CSS
            // Highlight errors for file inputs and checkboxes properly
            highlight: function(element, errorClass, validClass) {
                if (element.type === "radio" || element.type === "checkbox") {
                    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                } else if (element.id === "logoUpload" || element.name === "commercial_register") {
                    $(element).closest('.custom-file-upload, .logo-upload-label').addClass(errorClass);
                } else {
                    $(element).addClass(errorClass).removeClass(validClass);
                }
            },
            unhighlight: function(element, errorClass, validClass) {
                if (element.type === "radio" || element.type === "checkbox") {
                    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
                } else if (element.id === "logoUpload" || element.name === "commercial_register") {
                    $(element).closest('.custom-file-upload, .logo-upload-label').removeClass(errorClass);
                } else {
                    $(element).removeClass(errorClass).addClass(validClass);
                }
            }
        });

        <?php if ($registrationSuccess): ?>
        $("#step1").addClass("hidden");
        $("#step2").removeClass("hidden");

        setTimeout(function () {
            window.location.href = '../authentication/login.php';
        }, 50000);
        <?php endif; ?>
    });

    // Function to update the logo preview
    document.getElementById('logoUpload').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const logoPreview = document.getElementById('logoPreview');
        const icon = document.querySelector('#logoPreview .icon');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                logoPreview.style.backgroundImage = `url(${event.target.result})`;
                icon.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            logoPreview.style.backgroundImage = 'none';
            icon.style.display = 'flex'; 
        }
    });

    function updateFileName(input) {
        const labelText = document.getElementById('fileLabelText');
        if (input.files.length > 0) {
            labelText.textContent = input.files[0].name;
            labelText.style.color = '#000'; 
        } else {
            labelText.textContent = 'Document';
            labelText.style.color = 'grey'; 
        }
    }
</script>


<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-3">Your Agreement</h6>
                <p><strong>Last Revised: April 16, 2025</strong></p>
                <p>Welcome to the official platform of Masar, designed to facilitate innovation and collaboration between startups and public institutions in the field of road safety.</p>
                <p>By creating an account or using the platform in any way, you agree to the following terms and conditions.</p>

                <ol>
                    <li><strong>Purpose of the Platform</strong><br>
                        The platform enables:<br>
                        - Public Institutions to post project needs, calls for collaboration, or events.<br>
                        - Startups to present innovative ideas, showcase projects, and propose solutions aligned with road safety objectives.
                    </li>
                    <li><strong>Eligibility</strong><br>
                        - Startups must be registered legal entities.<br>
                        - Public Institutions must be officially recognized government or public sector bodies.<br>
                        - Users must be at least 18 years old and have the legal authority to represent their entity.
                    </li>
                    <li><strong>Account Registration</strong><br>
                        - All accounts require manual approval by the platform administrator.<br>
                        - Institutions and startups must provide valid documentation upon registration.<br>
                        - You are responsible for maintaining the confidentiality of your login credentials.
                    </li>
                    <li><strong>User Responsibilities</strong><br>
                        You agree to:<br>
                        - Provide accurate and up-to-date information.<br>
                        - Only post content relevant to road safety.<br>
                        - Refrain from posting offensive, illegal, or misleading content.<br>
                        - Not misuse, copy, or exploit other users’ ideas without consent.
                    </li>
                    <li><strong>Intellectual Property</strong><br>
                        - Users retain full ownership of the content they post.<br>
                        - Masar does not guarantee the protection of startup ideas. Users are responsible for securing their intellectual property before publication.<br>
                        - By posting content, you grant Masar a non-exclusive license to display it on the platform.
                    </li>
                    <li><strong>Confidentiality and Idea Protection</strong><br>
                        - The platform is not responsible for protecting startup concepts or preventing idea theft.<br>
                        - Startups should take appropriate legal measures (e.g., NDAs, trademarks, patents) before sharing sensitive material.<br>
                        - Use of the platform is at your own risk regarding the exposure of intellectual property.
                    </li>
                    <li><strong>Communication Tools</strong><br>
                        The platform includes a messaging feature to facilitate direct communication. Users must:<br>
                        - Communicate respectfully and professionally.<br>
                        - Not use the platform to solicit funds or offer unrelated services.
                    </li>
                    <li><strong>Content Moderation</strong><br>
                        - Masar reserves the right to remove or reject any content or account that does not align with its mission or these terms.<br>
                        - Masar is not liable for user-generated content.
                    </li>
                    <li><strong>Limitation of Liability</strong><br>
                        Masar is not liable for:<br>
                        - Loss or misuse of data.<br>
                        - Unauthorized use of posted content.<br>
                        - Failure of institutions or startups to respond, collaborate, or follow through with posted opportunities.<br>
                        - Use of the platform is provided as-is, with no guarantees.
                    </li>
                    <li><strong>Termination</strong><br>
                        - Masar reserves the right to suspend or delete accounts at any time if these terms are violated.<br>
                        - Users may request account deletion by contacting the admin team.
                    </li>
                    <li><strong>Changes to the Terms</strong><br>
                        These terms may be updated. Continued use of the platform constitutes acceptance of the revised terms.
                    </li>
                </ol>
                <p>If you do not agree with these terms, do not use the platform.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('termsCheckbox').checked = true; $('#termsModal').modal('hide');">Accept</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>