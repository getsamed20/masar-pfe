<style>
  .services-section {
    background-color: #F2F6FF;
  }

  .service-card {
    background: none;
    box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.3);
    border: none;
    border-radius: 30px;
    width: 280px;
    height: 500px;
    padding: 20px;
    text-align: center;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
  }

  .service-icon img {
    width: 50px;
    height: 50px;
  }


  @media (max-width: 991.98px) {

    .col-md-6 {
      max-width: 50% !important;
      flex: 0 0 50% !important;
    }
  }

  @media (max-width: 575.98px) {
    .col-12 {
      max-width: 100% !important;
      flex: 0 0 100% !important;
    }

    .service-card {
      width: 90vw; 
      height: auto;
    }
  }
</style>

<section class="services-section py-5">
  <div class="container text-center">
    <h2 class="services-title mb-5" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 700; font-size: 32px; color: #0C1BA3;">
      Our Services
    </h2>
    <div class="row g-4 justify-content-center">
      <div class="col-12 col-md-6 col-lg-3 d-flex justify-content-center">
        <div class="card service-card">
          <h3 class="service-title" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 700; font-size: 20px; color: #0C1BA3;">
            Direct Communication
          </h3>
          <div class="service-icon mb-3">
            <img src="service1.png" alt="Service 1">
          </div>
          <p class="service-desc" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 400; font-size: 14px; color: black;">
            A built-in chat system lets startups and institutions connect, exchange ideas, and share documents in real-time.
          </p>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3 d-flex justify-content-center">
        <div class="card service-card">
          <h3 class="service-title" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 700; font-size: 20px; color: #0C1BA3;">
            Posts & Proposals
          </h3>
          <div class="service-icon mb-3">
            <img src="service2.png" alt="Service 2">
          </div>
          <p class="service-desc" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 400; font-size: 14px; color: black;">
            Startups can showcase their ideas, share project updates, and directly propose solutions to public institutions, all in one place.
          </p>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3 d-flex justify-content-center">
        <div class="card service-card">
          <h3 class="service-title" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 700; font-size: 20px; color: #0C1BA3;">
            Verified Access
          </h3>
          <div class="service-icon mb-3">
            <img src="service3.png" alt="Service 3">
          </div>
          <p class="service-desc" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 400; font-size: 14px; color: black;">
            All accounts go through a strict verification process to ensure secure, trustworthy collaboration.
          </p>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3 d-flex justify-content-center">
        <div class="card service-card">
          <h3 class="service-title" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 700; font-size: 20px; color: #0C1BA3;">
            Project Matchmaking
          </h3>
          <div class="service-icon mb-3">
            <img src="service4.png" alt="Service 4">
          </div>
          <p class="service-desc" style="font-family: 'IBM Plex Sans', sans-serif; font-weight: 400; font-size: 14px; color: black;">
            We connect public institutions with startups that offer innovative solutions tailored to their real needs.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
