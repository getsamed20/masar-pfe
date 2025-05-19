<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Footer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@700&display=swap"
    rel="stylesheet"
  />

  <style>
    body {
      margin: 0;
      padding: 0;
    }

    .footer {
      background-color: #0c1ba3;
      color: white;
      font-family: 'IBM Plex Sans', sans-serif;
      padding: 40px 0 20px;
    }

    .footer h6 {
      font-size: 24px;
      font-weight: 700;
      letter-spacing: 2px;
      margin-bottom: 20px;
    }

    .footer ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .footer ul li a {
      color: white;
      text-decoration: none;
      font-size: 18px;
      font-weight: 700;
      display: block;
      margin-bottom: 10px;
      transition: text-decoration 0.2s ease-in-out;
    }

    .footer ul li a:hover {
      text-decoration: underline;
    }

    .social-icons {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
    }

    @media (max-width: 767.98px) {
      .social-icons {
        flex-direction: row;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
      }
      .social-icons a {
        margin: 0 10px 10px 0;
      }
    }

    .social-icons a {
      margin-bottom: 15px;
      display: inline-block;
    }

    .social-icons img {
      width: 30px;
      height: 30px;
      vertical-align: middle;
    }

    .footer-logo {
      max-width: 160px;
      height: auto;
      margin-bottom: 10px;
    }

    .tagline {
      font-size: 16px;
      font-weight: 700;
      margin-top: 10px;
    }

    .copyright {
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 1px;
      text-align: center;
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <footer class="footer">
    <div class="container">
      <div class="row gy-4">
        <div class="col-12 col-md-4 text-center text-md-start">
          <a href="../pages/home.php">
            <img src="logo.png" alt="Masar Logo" class="footer-logo" />
          </a>
          <div class="tagline">Securing Every Path</div>
        </div>

        <div class="col-6 col-md-2">
          <h6>INFO</h6>
          <ul>
            <li><a href="../pages/home.php">Home</a></li>
            <li><a>FAQ</a></li>
            <li><a>Services</a></li>
            <li><a href="../pages/documents.php">Knowledge Hub</a></li>
          </ul>
        </div>

        <div class="col-6 col-md-3">
          <h6>COMMUNITY</h6>
          <ul>
            <li><a href="../pages/startups_list.php">Startups</a></li>
            <li><a href="../pages/public_institutions_list.php">Public Institutions</a></li>
            <li><a href="../pages/challenges_list.php">Challenges</a></li>
            <li><a href="../pages/events_list.php">Events</a></li>
          </ul>
        </div>

        <div class="col-12 col-md-3 social-icons">
          <a href="#"><img src="facebook.png" alt="Facebook" /></a>
          <a href="#"><img src="whatsapp.png" alt="WhatsApp" /></a>
          <a href="#"><img src="linkedin.png" alt="LinkedIn" /></a>
          <a href="#"><img src="instagram.png" alt="Instagram" /></a>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-12">
          <div class="copyright">© 2025. ALL RIGHTS RESERVED.</div>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>
