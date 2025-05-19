<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Join Section</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,500;0,600;0,700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@700&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'IBM Plex Sans', sans-serif;
      background: #fff;
    }

    .join-section {
      padding: 40px 20px;
      text-align: center;
    }

    .join-title {
      font-size: 28px;
      font-weight: 600;
      color: black;
      margin-bottom: 30px;
    }

    .join-title .highlight {
      color: #0C1BA3;
      text-decoration: underline;
    }

    .join-steps {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 40px;
      margin-top: 40px;
      position: relative;
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      max-width: 220px;
      position: relative;
    }

    .icon-wrapper {
      position: relative;
      width: 100px;
      height: 100px;
    }

    .step-icon {
      width: 100%;
      height: 100%;
      border-radius: 50%;
    }

    .status-dot {
      position: absolute;
      bottom: -4px;
      right: -4px;
      width: 30px;
      height: 30px;
      background-color: #02FA72;
      border: 2px solid white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .step-number {
      font-size: 20px;
      font-weight: 700;
      color: #0C1BA3;
      font-family: 'IBM Plex Sans', sans-serif;
    }

    .line {
      display: none;
    }

    .vertical-line {
      display: block;
      width: 3px;
      height: 40px;
      background-color: #0C1BA3;
      margin: 10px 0;
    }

    .step-title {
      margin-top: 16px;
      font-size: 18px;
      font-weight: 700;
      color: black;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .step-desc {
      font-size: 16px;
      font-weight: 500;
      color: #333;
      margin-top: 8px;
    }

    .register-btn {
      margin-top: 40px;
      padding: 12px 24px;
      font-size: 16px;
      font-family: 'IBM Plex Sans Devanagari', sans-serif;
      font-weight: 700;
      color: #0C1BA3;
      border: 2px solid #0C1BA3;
      background: transparent;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      border-radius: 6px;
    }

    .register-btn img {
      width: 18px;
      height: 18px;
    }

    /* Desktop styles */
    @media (min-width: 768px) {
      .join-section {
        padding: 60px 20px;
      }

      .join-title {
        font-size: 38px;
        margin-bottom: 0;
      }

      .join-steps {
        flex-direction: row;
        justify-content: center;
        align-items: flex-start;
        gap: 40px;
        margin-top: 60px;
      }

      .step {
        max-width: 220px;
      }

      .icon-wrapper {
        width: 120px;
        height: 120px;
      }

      .status-dot {
        width: 40px;
        height: 40px;
      }

      .step-number {
        font-size: 26px;
      }

      .line {
        display: block;
        position: relative;
        top: 60px;
        width: 150px;
        height: 3px;
        background-color: #0C1BA3;
        flex-shrink: 0;
      }

      .vertical-line {
        display: none;
      }

      .step-title {
        margin-top: 24px;
        font-size: 20px;
      }

      .step-desc {
        font-size: 17px;
      }

      .register-btn {
        margin-top: 60px;
        padding: 14px 28px;
        font-size: 17px;
      }

      .register-btn img {
        width: 20px;
        height: 20px;
      }
    }
  </style>
</head>
<body>

  <section class="join-section">
    <h2 class="join-title">
      How to join the <span class="highlight">community?</span>
    </h2>

    <div class="join-steps">
      <div class="step">
        <div class="icon-wrapper">
          <img src="icon1.png" alt="Step 1" class="step-icon" />
          <div class="status-dot">
            <span class="step-number">1</span>
          </div>
        </div>
        <h3 class="step-title">Create an account</h3>
        <p class="step-desc">Complete the form and submit your documents.</p>
      </div>

      <div class="vertical-line"></div>

      <div class="line"></div>

      <div class="step">
        <div class="icon-wrapper">
          <img src="icon2.png" alt="Step 2" class="step-icon" />
          <div class="status-dot">
            <span class="step-number">2</span>
          </div>
        </div>
        <h3 class="step-title">Wait for verification</h3>
        <p class="step-desc">Our team will review your information within 24 hours.</p>
      </div>

      <div class="vertical-line"></div>

      <div class="line"></div>

      <div class="step">
        <div class="icon-wrapper">
          <img src="icon3.png" alt="Step 3" class="step-icon" />
          <div class="status-dot">
            <span class="step-number">3</span>
          </div>
        </div>
        <h3 class="step-title">Start connecting</h3>
        <p class="step-desc">Log in, and start engaging with others.</p>
      </div>
    </div>

    <button class="register-btn">
      Register now <img src="right-arrow.png" alt="arrow" />
    </button>
  </section>

</body>
</html>