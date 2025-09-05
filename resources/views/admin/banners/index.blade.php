@extends('admin.layout')

@section('title', 'Feature Banners')

@section('content')
<div class="admin-container">
  <!-- Enhanced Header Section -->
  <div class="header-section">
    <div class="header-content">
      <div class="header-left">
        <h1 class="page-title">
          <span class="title-icon">🎯</span>
          Feature Banners
        </h1>
        <p class="page-description">
          Manage dynamic banner sliders displayed on your homepage to attract visitors and promote events
        </p>
      </div>
      <div class="header-actions">
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-with-icon">
          <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="8" y1="12" x2="16" y2="12"></line>
          </svg>
          Add New Banner
        </a>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon active">📊</div>
      <div class="stat-content">
        <div class="stat-number">{{ $banners->count() }}</div>
        <div class="stat-label">Total Banners</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon success">✅</div>
      <div class="stat-content">
        <div class="stat-number">{{ $banners->where('is_active', true)->count() }}</div>
        <div class="stat-label">Active Banners</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon warning">⏸️</div>
      <div class="stat-content">
        <div class="stat-number">{{ $banners->where('is_active', false)->count() }}</div>
        <div class="stat-label">Inactive Banners</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon info">🔗</div>
      <div class="stat-content">
        <div class="stat-number">{{ $banners->whereNotNull('link')->count() }}</div>
        <div class="stat-label">With Links</div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M9 12l2 2 4-4"></path>
        <circle cx="12" cy="12" r="10"></circle>
      </svg>
      {{ session('success') }}
    </div>
  @endif

  <div class="card enhanced-card">
    <div class="card-header enhanced-header">
      <div class="header-left">
        <h3 class="card-title">Banner Management</h3>
        <span class="card-subtitle">{{ $banners->count() }} banners • Click and drag to reorder</span>
      </div>
      <div class="header-actions">
        <div class="filter-buttons">
          <button class="filter-btn active" data-filter="all">All</button>
          <button class="filter-btn" data-filter="active">Active</button>
          <button class="filter-btn" data-filter="inactive">Inactive</button>
        </div>
      </div>
    </div>

    @if($banners->count() > 0)
      <div class="banner-grid">
        @foreach($banners as $banner)
          <div class="banner-item {{ $banner->is_active ? 'active' : 'inactive' }}" data-status="{{ $banner->is_active ? 'active' : 'inactive' }}">
            <!-- Banner Preview -->
            <div class="banner-preview-container">
              <img src="{{ asset('storage/' . $banner->image) }}" 
                   alt="{{ $banner->title }}" 
                   class="banner-preview-image">
              <div class="banner-overlay">
                <div class="banner-status-badge {{ $banner->is_active ? 'active' : 'inactive' }}">
                  {{ $banner->is_active ? '✓ Active' : '✗ Inactive' }}
                </div>
                @if($banner->sort_order)
                  <div class="banner-order-badge">{{ $banner->sort_order }}</div>
                @endif
              </div>
            </div>

            <!-- Banner Info -->
            <div class="banner-info">
              <h4 class="banner-title">{{ $banner->title }}</h4>
              
              @if($banner->link)
                <div class="banner-link">
                  <svg class="link-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                  </svg>
                  <a href="{{ $banner->link }}" target="_blank" class="banner-link-text">
                    {{ Str::limit($banner->link, 40) }}
                  </a>
                </div>
              @endif

              <div class="banner-meta">
                <span class="meta-item">
                  <svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                  </svg>
                  {{ $banner->created_at->format('M j, Y') }}
                </span>
              </div>
            </div>

            <!-- Banner Actions -->
            <div class="banner-actions">
              <div class="action-group">
                <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST" class="toggle-form">
                  @csrf
                  <button type="submit" 
                          class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }} btn-with-icon"
                          title="{{ $banner->is_active ? 'Deactivate' : 'Activate' }} banner">
                    @if($banner->is_active)
                      <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                      </svg>
                    @else
                      <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                      </svg>
                    @endif
                  </button>
                </form>
                
                <a href="{{ route('admin.banners.edit', $banner) }}" 
                   class="btn btn-sm btn-outline-primary btn-with-icon"
                   title="Edit banner">
                  <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                  </svg>
                </a>
                
                <form action="{{ route('admin.banners.destroy', $banner) }}" 
                      method="POST" 
                      class="delete-form"
                      onsubmit="return confirm('Are you sure you want to delete this banner? This action cannot be undone.')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" 
                          class="btn btn-sm btn-outline-danger btn-with-icon"
                          title="Delete banner">
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3,6 5,6 21,6"></polyline>
                      <path d="M19,6V20a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6M8,6V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"></path>
                    </svg>
                  </button>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-state enhanced-empty">
        <div class="empty-illustration">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <circle cx="9" cy="9" r="2"></circle>
            <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
          </svg>
        </div>
        <h3 class="empty-title">No banners yet</h3>
        <p class="empty-description">
          Start creating beautiful banners to showcase on your homepage and attract more visitors to your events.
        </p>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-lg btn-with-icon">
          <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="8" y1="12" x2="16" y2="12"></line>
          </svg>
          Create Your First Banner
        </a>
      </div>
    @endif
  </div>
</div>

<style>
/* Enhanced Styles */
.header-section {
  margin-bottom: 2rem;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 2rem;
}

.page-title {
  font-size: 2.25rem;
  font-weight: 700;
  color: var(--text);
  margin: 0 0 0.5rem 0;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.title-icon {
  font-size: 2rem;
}

.page-description {
  font-size: 1rem;
  color: var(--text-light);
  line-height: 1.6;
  margin: 0;
  max-width: 600px;
}

.btn-with-icon {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-icon {
  width: 1rem;
  height: 1rem;
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: var(--shadow-sm);
  transition: all 0.2s ease;
}

.stat-card:hover {
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}

.stat-icon {
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
}

.stat-icon.active { background: rgba(8, 145, 178, 0.1); }
.stat-icon.success { background: rgba(5, 150, 105, 0.1); }
.stat-icon.warning { background: rgba(217, 119, 6, 0.1); }
.stat-icon.info { background: rgba(99, 102, 241, 0.1); }

.stat-number {
  font-size: 2rem;
  font-weight: 700;
  color: var(--text);
  line-height: 1;
}

.stat-label {
  font-size: 0.875rem;
  color: var(--text-light);
  margin-top: 0.25rem;
}

/* Enhanced Card */
.enhanced-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
  overflow: hidden;
}

.enhanced-header {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-bottom: 1px solid var(--border);
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--text);
  margin: 0;
}

.card-subtitle {
  font-size: 0.875rem;
  color: var(--text-light);
  margin-top: 0.25rem;
}

/* Filter Buttons */
.filter-buttons {
  display: flex;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
}

.filter-btn {
  padding: 0.5rem 1rem;
  border: none;
  background: transparent;
  color: var(--text-light);
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.filter-btn:hover,
.filter-btn.active {
  background: var(--primary);
  color: white;
}

/* Banner Grid */
.banner-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
  padding: 1.5rem;
}

.banner-item {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: var(--shadow-sm);
}

.banner-item:hover {
  box-shadow: var(--shadow-lg);
  transform: translateY(-4px);
}

.banner-item.inactive {
  opacity: 0.7;
}

.banner-preview-container {
  position: relative;
  overflow: hidden;
  aspect-ratio: 3/1;
}

.banner-preview-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.banner-item:hover .banner-preview-image {
  transform: scale(1.05);
}

.banner-overlay {
  position: absolute;
  top: 0.75rem;
  right: 0.75rem;
  display: flex;
  gap: 0.5rem;
}

.banner-status-badge {
  background: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.banner-status-badge.active {
  background: rgba(5, 150, 105, 0.9);
}

.banner-order-badge {
  background: rgba(99, 102, 241, 0.9);
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 50%;
  font-size: 0.75rem;
  font-weight: 600;
  min-width: 1.5rem;
  text-align: center;
}

.banner-info {
  padding: 1rem;
}

.banner-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--text);
  margin: 0 0 0.75rem 0;
  line-height: 1.4;
}

.banner-link {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
}

.link-icon {
  width: 1rem;
  height: 1rem;
  color: var(--primary);
}

.banner-link-text {
  color: var(--primary);
  text-decoration: none;
  font-size: 0.875rem;
  transition: color 0.2s ease;
}

.banner-link-text:hover {
  color: var(--primary-dark);
  text-decoration: underline;
}

.banner-meta {
  display: flex;
  gap: 1rem;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.75rem;
  color: var(--text-muted);
}

.meta-icon {
  width: 0.875rem;
  height: 0.875rem;
}

.banner-actions {
  padding: 0.75rem 1rem;
  background: var(--border-light);
  border-top: 1px solid var(--border);
}

.action-group {
  display: flex;
  gap: 0.5rem;
}

/* Enhanced Empty State */
.enhanced-empty {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-illustration {
  width: 120px;
  height: 120px;
  margin: 0 auto 2rem;
  opacity: 0.3;
}

.empty-illustration svg {
  width: 100%;
  height: 100%;
  color: var(--text-muted);
}

.empty-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--text);
  margin: 0 0 0.75rem 0;
}

.empty-description {
  font-size: 1rem;
  color: var(--text-light);
  line-height: 1.6;
  margin: 0 0 2rem 0;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}

/* Alert Styles */
.alert {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 1.25rem;
  border-radius: var(--radius);
  margin-bottom: 1.5rem;
  border: 1px solid transparent;
}

.alert-success {
  background-color: #d1fae5;
  color: #047857;
  border-color: #a7f3d0;
}

.alert-icon {
  width: 1.25rem;
  height: 1.25rem;
  flex-shrink: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    align-items: stretch;
  }
  
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .banner-grid {
    grid-template-columns: 1fr;
    padding: 1rem;
  }
  
  .enhanced-header {
    flex-direction: column;
    align-items: stretch;
    gap: 1rem;
  }
}

/* Filter functionality */
[data-status="inactive"].hidden {
  display: none;
}

[data-status="active"].hidden {
  display: none;
}
</style>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
  const filterButtons = document.querySelectorAll('.filter-btn');
  const bannerItems = document.querySelectorAll('.banner-item');
  
  filterButtons.forEach(button => {
    button.addEventListener('click', function() {
      // Update active button
      filterButtons.forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      
      // Filter banners
      const filter = this.dataset.filter;
      
      bannerItems.forEach(item => {
        if (filter === 'all') {
          item.classList.remove('hidden');
        } else {
          if (item.dataset.status === filter) {
            item.classList.remove('hidden');
          } else {
            item.classList.add('hidden');
          }
        }
      });
    });
  });
});
</script>
@endsection
