<section class="impact-section py-5">
  <div class="container text-center">
    <h2 class="mb-4 impact-title">Our Impact</h2>
    <p class="mb-5 impact-subtitle">
      Masar aligns with key SDGs by enabling inclusive, innovative, and sustainable solutions.
    </p>

    <div class="impact-carousel position-relative">
      <button class="carousel-arrow left-arrow" onclick="impactPrev()">
        <img src="left-arrow.png" alt="Left Arrow">
      </button>

      <div class="impact-cards-wrapper">
        <!-- Cards will be inserted here by JavaScript -->
      </div>

      <button class="carousel-arrow right-arrow" onclick="impactNext()">
        <img src="right-arrow.png" alt="Right Arrow">
      </button>
    </div>
  </div>
</section>

<style>
  .impact-title {
    font-family: 'IBM Plex Sans', sans-serif; 
    font-weight: 700; 
    font-size: 32px; 
    color: #0C1BA3;
  }
  
  .impact-subtitle {
    font-family: 'IBM Plex Sans', sans-serif; 
    font-weight: 500; 
    font-size: 24px; 
    color: #000000; 
    width: 50%; 
    margin: 0 auto;
  }

  .impact-card {
    width: 320px;
    transition: all 0.5s ease;
    border-radius: 30px;
    overflow: hidden;
    background-color: white;
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
    opacity: 0.5;
    transform: scale(0.9);
  }

  .impact-card img {
    width: 100%;
    height: 270px;
    object-fit: cover;
    border-top-left-radius: 30px;
    border-top-right-radius: 30px;
  }

  .impact-card p {
    padding: 15px;
    margin: 0;
    font-family: 'IBM Plex Sans', sans-serif;
  }

  .impact-card.active {
    margin: 50px 0;
    height: 410px;
    transform: scale(1.1);
    opacity: 1;
    z-index: 2;
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
  }

  .impact-card.side {
    filter: brightness(50%);
    z-index: 1;
    height: 395px;
  }

  .impact-cards-wrapper {
    display: flex;
    gap: 30px;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    overflow: hidden;
  }

  .carousel-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    width: 50px;
    height: 50px;
    padding: 0;
    border: none;
    background: none;
    outline: none;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
  }

  .left-arrow {
    left: 50px;
  }

  .right-arrow {
    right: 50px;
  }

  .carousel-arrow img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .impact-carousel {
    position: relative;
    height: 500px;
    width: 100%;
  }

  /* Mobile styles - single card */
  @media (max-width: 992px) {
    .impact-subtitle {
      width: 80%;
    }
    
    .impact-card {
      width: 280px;
      opacity: 0;
      transform: scale(0.9) translateX(-50%);
      position: absolute;
      left: 50%;
      top: 0;
      display: none;
    }
    
    .impact-card img {
      height: 220px;
    }
    
    .impact-card.active {
      height: 380px;
      transform: translateX(-50%) scale(1);
      opacity: 1;
      display: block;
      margin: 0;
    }
    
    .impact-card.side {
      display: none;
    }
    
    .impact-cards-wrapper {
      max-width: 280px;
      height: 400px;
    }
    
    .carousel-arrow {
      width: 40px;
      height: 40px;
    }
    
    .left-arrow {
      left: 30px;
    }
    
    .right-arrow {
      right: 30px;
    }
    
    .impact-carousel {
      height: 400px;
    }
  }

  /* Adjustments for medium screens */
  @media (max-width: 1200px) and (min-width: 993px) {
    .impact-cards-wrapper {
      max-width: 900px;
    }
    
    .left-arrow {
      left: 30px;
    }
    
    .right-arrow {
      right: 30px;
    }
  }
</style>

<script>
  (() => {
    const impactItems = [
      { img: 'impact1.png', text: 'We support job creation by connecting startups with real public sector opportunities.' },
      { img: 'impact2.png', text: 'We promote innovative solutions to strengthen public services & smart infrastructure.' },
      { img: 'impact3.png', text: 'We ensure equal access to government opportunities for all qualified startups.' },
      { img: 'impact4.png', text: 'We ensure equal access to government opportunities for all qualified startups.' },
      { img: 'impact5.png', text: 'We build bridges between startups and public institutions to drive shared impact.' }
    ];

    let impactIndex = 0;
    let isMobile = window.matchMedia("(max-width: 992px)").matches;

    function renderImpactCards() {
      const wrapper = document.querySelector('.impact-cards-wrapper');
      wrapper.innerHTML = '';

      if (isMobile) {
        // Mobile - single card
        const card = document.createElement('div');
        card.className = 'impact-card active';
        card.innerHTML = `
          <img src="${impactItems[impactIndex].img}" alt="Impact">
          <p>${impactItems[impactIndex].text}</p>
        `;
        wrapper.appendChild(card);
      } else {
        // Desktop - three cards (prev, current, next)
        const prev = (impactIndex - 1 + impactItems.length) % impactItems.length;
        const next = (impactIndex + 1) % impactItems.length;

        [prev, impactIndex, next].forEach((i, idx) => {
          const card = document.createElement('div');
          card.className = 'impact-card';
          if (idx === 1) card.classList.add('active');
          else card.classList.add('side');

          card.innerHTML = `
            <img src="${impactItems[i].img}" alt="Impact">
            <p>${impactItems[i].text}</p>
          `;
          wrapper.appendChild(card);
        });
      }
    }

    function handleResize() {
      const newIsMobile = window.matchMedia("(max-width: 992px)").matches;
      if (newIsMobile !== isMobile) {
        isMobile = newIsMobile;
        renderImpactCards();
      }
    }

    window.impactNext = function () {
      impactIndex = (impactIndex + 1) % impactItems.length;
      renderImpactCards();
    };

    window.impactPrev = function () {
      impactIndex = (impactIndex - 1 + impactItems.length) % impactItems.length;
      renderImpactCards();
    };

    // Add touch support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    function handleTouchStart(e) {
      touchStartX = e.changedTouches[0].screenX;
    }
    
    function handleTouchEnd(e) {
      touchEndX = e.changedTouches[0].screenX;
      handleSwipe();
    }
    
    function handleSwipe() {
      if (isMobile) {
        if (touchEndX < touchStartX - 50) {
          impactNext(); // Swipe left
        }
        if (touchEndX > touchStartX + 50) {
          impactPrev(); // Swipe right
        }
      }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
      renderImpactCards();
      window.addEventListener('resize', handleResize);
      
      // Add touch event listeners
      const carousel = document.querySelector('.impact-cards-wrapper');
      carousel.addEventListener('touchstart', handleTouchStart, false);
      carousel.addEventListener('touchend', handleTouchEnd, false);
    });
  })();
</script>