// Example: mobile nav toggle or other dynamic features
document.addEventListener("DOMContentLoaded", () => {
  console.log("EventEase ready");
});

// Example: mobile nav toggle or other dynamic features
document.addEventListener("DOMContentLoaded", () => {
  console.log("EventEase ready");
});

// Modern Sidebar Toggle Functions
function toggleSidebar() {
  const sidebar = document.getElementById('mobileSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const hamburger = document.querySelector('.mobile-hamburger');
  
  sidebar.classList.toggle('active');
  overlay.classList.toggle('active');
  hamburger.classList.toggle('active');
  
  // Prevent body scroll when sidebar is open
  if (sidebar.classList.contains('active')) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
}

function closeSidebar() {
  const sidebar = document.getElementById('mobileSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const hamburger = document.querySelector('.mobile-hamburger');
  
  sidebar.classList.remove('active');
  overlay.classList.remove('active');
  hamburger.classList.remove('active');
  document.body.style.overflow = '';
}

// Close sidebar when clicking on a link (except logout)
document.addEventListener('DOMContentLoaded', function() {
  const sidebarLinks = document.querySelectorAll('.sidebar-link:not(.logout-link)');
  sidebarLinks.forEach(link => {
    link.addEventListener('click', function() {
      // Add a small delay to allow navigation to start
      setTimeout(closeSidebar, 100);
    });
  });
});

// Close sidebar on window resize if screen becomes large
window.addEventListener('resize', function() {
  if (window.innerWidth > 768) {
    closeSidebar();
  }
});

// Legacy function for backward compatibility (keeping it just in case)
function toggleMenu() {
  toggleSidebar();
}








  function openAuthModal() {
  document.getElementById('authModal').style.display = 'flex';
}
function closeAuthModal() {
  document.getElementById('authModal').style.display = 'none';
}
function switchAuthTab(tab) {
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');
  if (tab === 'login') {
    loginForm.style.display = 'block';
    registerForm.style.display = 'none';
  } else {
    loginForm.style.display = 'none';
    registerForm.style.display = 'block';
  }
}













document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.querySelector('.dropdown-toggle');
  const menu = document.querySelector('.dropdown-menu');

  if (toggle && menu) {
    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      this.parentElement.classList.toggle('show');
    });

    window.addEventListener('click', function (e) {
      if (!toggle.contains(e.target) && !menu.contains(e.target)) {
        toggle.parentElement.classList.remove('show');
      }
    });
  }
});




// View Details Toggle
document.querySelectorAll('.toggle-details').forEach(button => {
  button.addEventListener('click', function () {
    const details = this.closest('.event-card').querySelector('.event-details');
    if (details.style.display === 'block') {
      details.style.display = 'none';
      this.innerHTML = `<i class="bi bi-eye"></i> View Details`;
    } else {
      details.style.display = 'block';
      this.innerHTML = `<i class="bi bi-eye-slash"></i> Hide Details`;
    }
  });
});

