document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.slide-link');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const dotContainer = document.getElementById('sliderDots');
  let currentIndex = 0;
  let slideInterval;

  // Generate dot indicators
  slides.forEach((_, i) => {
    const dot = document.createElement('div');
    dot.classList.add('slider-dot');
    if (i === 0) dot.classList.add('active');
    dot.addEventListener('click', () => {
      stopAutoSlide();
      showSlide(i);
      startAutoSlide();
    });
    dotContainer.appendChild(dot);
  });

  const dots = document.querySelectorAll('.slider-dot');

  function showSlide(index) {
    slides.forEach(slide => {
      slide.classList.remove('active');
      slide.style.display = 'none';
    });
    slides[index].classList.add('active');
    slides[index].style.display = 'block';

    dots.forEach(dot => dot.classList.remove('active'));
    dots[index].classList.add('active');

    currentIndex = index;
  }

  function nextSlide() {
    const newIndex = (currentIndex + 1) % slides.length;
    showSlide(newIndex);
  }

  function prevSlide() {
    const newIndex = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(newIndex);
  }

  function startAutoSlide() {
    slideInterval = setInterval(nextSlide, 3000);
  }

  function stopAutoSlide() {
    clearInterval(slideInterval);
  }

  if (slides.length > 0) {
    showSlide(currentIndex);
    startAutoSlide();

    nextBtn.addEventListener('click', () => {
      stopAutoSlide();
      nextSlide();
      startAutoSlide();
    });

    prevBtn.addEventListener('click', () => {
      stopAutoSlide();
      prevSlide();
      startAutoSlide();
    });
  }

  // ======= PROMOTIONAL BANNER VIDEO HANDLING =======
  const promoVideo = document.querySelector('.promo-bg-video');
  
  if (promoVideo) {
    // Optimize video for mobile devices
    const isMobile = window.innerWidth <= 768;
    
    // Handle video loading and playback
    promoVideo.addEventListener('loadstart', () => {
      console.log('Video loading started');
    });
    
    promoVideo.addEventListener('canplay', () => {
      console.log('Video can start playing');
      // Ensure autoplay works on mobile with muted video
      promoVideo.muted = true;
      promoVideo.play().catch(e => {
        console.log('Video autoplay failed:', e);
        // Fallback: show static background if video fails
        showVideoFallback();
      });
    });
    
    promoVideo.addEventListener('error', (e) => {
      console.log('Video error:', e);
      showVideoFallback();
    });
    
    // Pause video when not in viewport (performance optimization)
    const observerOptions = {
      root: null,
      rootMargin: '0px',
      threshold: 0.25
    };
    
    const videoObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          promoVideo.play().catch(e => console.log('Video play failed:', e));
        } else {
          promoVideo.pause();
        }
      });
    }, observerOptions);
    
    videoObserver.observe(promoVideo);
    
    // Handle window resize for mobile optimization
    window.addEventListener('resize', () => {
      const newIsMobile = window.innerWidth <= 768;
      if (newIsMobile !== isMobile) {
        // Reload video with appropriate settings
        promoVideo.load();
      }
    });
    
    function showVideoFallback() {
      const promoBanner = document.querySelector('.promo-banner');
      if (promoBanner) {
        promoBanner.style.background = 'linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%)';
        promoVideo.style.display = 'none';
      }
    }
    
    // Preload optimization
    if ('IntersectionObserver' in window) {
      const preloadObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            promoVideo.load();
            preloadObserver.unobserve(promoVideo);
          }
        });
      });
      preloadObserver.observe(promoVideo);
    }
  }
});


//FAQ

document.addEventListener('DOMContentLoaded', () => {
  const faqItems = document.querySelectorAll('.faq-item');

  faqItems.forEach(item => {
    const question = item.querySelector('.faq-question');
    const answer = item.querySelector('.faq-answer');
    const icon = item.querySelector('.icon');

    question.addEventListener('click', () => {
      const isOpen = item.classList.contains('active');

      // Close all other FAQs
      faqItems.forEach(faq => {
        faq.classList.remove('active');
        faq.querySelector('.faq-answer').style.maxHeight = null;
        faq.querySelector('.faq-answer').style.opacity = 0;
        faq.querySelector('.icon').textContent = '+';
      });

      // Open this one if it wasn't already open
      if (!isOpen) {
        item.classList.add('active');
        answer.style.maxHeight = answer.scrollHeight + "px";
        answer.style.opacity = 1;
        icon.textContent = '–';
      }
    });
  });
});


document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.slide-link');
  const dotsContainer = document.getElementById('sliderDots');

  slides.forEach((_, index) => {
    const dot = document.createElement('span');
    dot.classList.add('dot');
    if (index === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(index));
    dotsContainer.appendChild(dot);
  });
});