// Events Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initializeFilterTabs();
    initializeSearch();
    initializeCardAnimations();
    initializeStickySearchBar();
});

// Filter Tab Functionality
function initializeFilterTabs() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const filterRadios = document.querySelectorAll('input[name="eventStatus"]');
    
    filterTabs.forEach((tab, index) => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            filterTabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Check the corresponding radio button
            if (filterRadios[index]) {
                filterRadios[index].checked = true;
            }
            
            // Apply filter
            filterEvents();
        });
    });
    
    // Also listen to radio button changes (for accessibility)
    filterRadios.forEach((radio, index) => {
        radio.addEventListener('change', function() {
            // Update active tab
            filterTabs.forEach(tab => tab.classList.remove('active'));
            if (filterTabs[index]) {
                filterTabs[index].classList.add('active');
            }
            
            filterEvents();
        });
    });
}

// Search Functionality
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    
    // Debounce function for better performance
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Real-time search with debounce
    const debouncedSearch = debounce(filterEvents, 300);
    searchInput.addEventListener('input', debouncedSearch);
    
    // Search on Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterEvents();
        }
    });
    
    // Clear search when input is empty
    searchInput.addEventListener('keyup', function() {
        if (this.value === '') {
            filterEvents();
        }
    });
}

// Main Filter Function
function filterEvents() {
    const selectedStatus = document.querySelector('input[name="eventStatus"]:checked').value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    const eventCards = document.querySelectorAll('.event-card');
    
    let visibleCount = 0;
    
    eventCards.forEach(card => {
        const cardStatus = card.getAttribute('data-status');
        const cardText = card.textContent.toLowerCase();
        
        // Check status filter
        const statusMatch = selectedStatus === 'all' || selectedStatus === cardStatus;
        
        // Check search term
        const searchMatch = searchTerm === '' || cardText.includes(searchTerm);
        
        // Show/hide card
        if (statusMatch && searchMatch) {
            showCard(card);
            visibleCount++;
        } else {
            hideCard(card);
        }
    });
    
    // Show no results message if needed
    handleNoResults(visibleCount);
}

// Card Animation Functions
function showCard(card) {
    card.classList.remove('hidden');
    card.style.display = 'block';
    
    // Animate in
    setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 10);
}

function hideCard(card) {
    card.style.opacity = '0';
    card.style.transform = 'translateY(-20px)';
    
    setTimeout(() => {
        card.classList.add('hidden');
        card.style.display = 'none';
    }, 300);
}

// Handle No Results State
function handleNoResults(visibleCount) {
    let noResultsDiv = document.querySelector('.no-results-message');
    
    if (visibleCount === 0) {
        if (!noResultsDiv) {
            noResultsDiv = document.createElement('div');
            noResultsDiv.className = 'no-results-message';
            noResultsDiv.innerHTML = `
                <div class="no-events">
                    <i class="bi bi-search"></i>
                    <h3>No Events Found</h3>
                    <p>No events match your current search criteria. Try adjusting your filters or search terms.</p>
                </div>
            `;
            
            const eventsContainer = document.querySelector('.events-container');
            eventsContainer.appendChild(noResultsDiv);
        }
        noResultsDiv.style.display = 'block';
    } else {
        if (noResultsDiv) {
            noResultsDiv.style.display = 'none';
        }
    }
}

// Card Hover Animations
function initializeCardAnimations() {
    const eventCards = document.querySelectorAll('.event-card');
    
    eventCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Utility function for smooth scrolling to events section
function scrollToEvents() {
    const eventsSection = document.querySelector('.events-grid');
    if (eventsSection) {
        eventsSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Add loading state functionality
function showLoading() {
    const eventsContainer = document.querySelector('.events-container');
    eventsContainer.innerHTML = `
        <div class="events-loading">
            <i class="bi bi-hourglass-split"></i>
            <p>Loading events...</p>
        </div>
    `;
}

// Sticky Search Bar Effect
function initializeStickySearchBar() {
    const searchSection = document.querySelector('.search-section');
    const eventsHeader = document.querySelector('.events-header');
    
    if (!searchSection || !eventsHeader) return;
    
    // Calculate when search bar becomes sticky
    const headerHeight = eventsHeader.offsetHeight;
    
    function handleScroll() {
        const scrollPosition = window.scrollY;
        
        // Add scrolled class when user scrolls past the header
        if (scrollPosition > headerHeight - 64) { // 64px is header height
            searchSection.classList.add('scrolled');
        } else {
            searchSection.classList.remove('scrolled');
        }
    }
    
    // Throttle scroll event for better performance
    let ticking = false;
    function optimizedScroll() {
        if (!ticking) {
            requestAnimationFrame(function() {
                handleScroll();
                ticking = false;
            });
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', optimizedScroll);
}

// Export functions for external use if needed
window.EventsPage = {
    filterEvents,
    scrollToEvents,
    showLoading
};