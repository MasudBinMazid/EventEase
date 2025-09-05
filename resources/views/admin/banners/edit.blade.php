@extends('admin.layout')

@section('title', 'Edit Banner')

@section('content')
<div class="admin-container">
  <!-- Enhanced Header -->
  <div class="header-section">
    <div class="header-breadcrumb">
      <nav class="breadcrumb">
        <a href="{{ route('admin.banners.index') }}" class="breadcrumb-link">
          <svg class="breadcrumb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H6M12 5l-7 7 7 7"/>
          </svg>
          Feature Banners
        </a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">Edit Banner</span>
      </nav>
    </div>
    
    <div class="header-content">
      <div class="header-left">
        <h1 class="page-title">
          <span class="title-icon">✏️</span>
          Edit Banner
        </h1>
        <p class="page-description">
          Update banner information and settings
        </p>
      </div>
      <div class="header-actions">
        <div class="banner-status">
          <span class="status-label">Current Status:</span>
          <span class="status-badge {{ $banner->is_active ? 'active' : 'inactive' }}">
            {{ $banner->is_active ? '✓ Active' : '✗ Inactive' }}
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Current Banner Preview -->
  <div class="current-banner-card">
    <div class="preview-header">
      <h3>Current Banner Preview</h3>
      <span class="preview-subtitle">This is how your banner currently appears</span>
    </div>
    <div class="banner-preview-large">
      <img src="{{ asset('storage/' . $banner->image) }}" 
           alt="{{ $banner->title }}" 
           class="current-banner-image">
      <div class="banner-info-overlay">
        <div class="banner-title-overlay">{{ $banner->title }}</div>
        @if($banner->link)
          <div class="banner-link-overlay">
            <svg class="link-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
              <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
            </svg>
            {{ Str::limit($banner->link, 40) }}
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Form Card -->
  <div class="form-card">
    <div class="form-header">
      <h3 class="form-title">Update Banner Information</h3>
      <p class="form-subtitle">Modify the details below to update your banner</p>
    </div>

    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="enhanced-form">
      @csrf
      @method('PUT')
      
      <div class="form-grid">
        <!-- Banner Title -->
        <div class="form-group full-width">
          <label for="title" class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 7h16M4 12h16M4 17h16"/>
            </svg>
            Banner Title <span class="required">*</span>
          </label>
          <input type="text" 
                 id="title" 
                 name="title" 
                 class="form-control @error('title') is-invalid @enderror" 
                 value="{{ old('title', $banner->title) }}" 
                 placeholder="Enter a compelling banner title"
                 required>
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Banner Image -->
        <div class="form-group full-width">
          <label for="image" class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
              <circle cx="9" cy="9" r="2"/>
              <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
            </svg>
            Update Banner Image
          </label>
          
          <div class="image-update-section">
            <div class="current-image-info">
              <div class="current-image-thumbnail">
                <img src="{{ asset('storage/' . $banner->image) }}" alt="Current image">
              </div>
              <div class="current-image-details">
                <p class="image-filename">{{ basename($banner->image) }}</p>
                <p class="image-meta">Current banner image</p>
              </div>
            </div>
            
            <div class="file-upload-area" id="fileUploadArea">
              <div class="file-upload-content">
                <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="7,10 12,15 17,10"/>
                  <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <div class="upload-text">
                  <p class="upload-title">Click to upload new image</p>
                  <p class="upload-subtitle">PNG, JPG, GIF up to 2MB • Leave empty to keep current</p>
                </div>
              </div>
              <input type="file" 
                     id="image" 
                     name="image" 
                     class="file-input @error('image') is-invalid @enderror" 
                     accept="image/*">
            </div>
            
            <div class="image-preview" id="imagePreview" style="display: none;">
              <div class="preview-header-small">
                <span>New Image Preview</span>
                <button type="button" class="remove-image" id="removeImage">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                  </svg>
                </button>
              </div>
              <img id="previewImg" src="" alt="New preview">
            </div>
          </div>
          
          <div class="form-help">
            <svg class="help-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <path d="M9,9a3,3,0,0,1,6,0c0,2-3,3-3,3"/>
              <circle cx="12" cy="17" r=".5"/>
            </svg>
            <span>Leave empty to keep current image. Upload a new image to replace it.</span>
          </div>
          
          @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Banner Link -->
        <div class="form-group">
          <label for="link" class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
              <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
            </svg>
            Banner Link
          </label>
          <input type="url" 
                 id="link" 
                 name="link" 
                 class="form-control @error('link') is-invalid @enderror" 
                 value="{{ old('link', $banner->link) }}" 
                 placeholder="https://example.com">
          <div class="form-help">
            <svg class="help-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <path d="M9,9a3,3,0,0,1,6,0c0,2-3,3-3,3"/>
              <circle cx="12" cy="17" r=".5"/>
            </svg>
            <span>Optional. Where users will be redirected when clicking the banner.</span>
          </div>
          @error('link')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Display Order -->
        <div class="form-group">
          <label for="sort_order" class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="8" y1="6" x2="21" y2="6"/>
              <line x1="8" y1="12" x2="21" y2="12"/>
              <line x1="8" y1="18" x2="21" y2="18"/>
              <line x1="3" y1="6" x2="3.01" y2="6"/>
              <line x1="3" y1="12" x2="3.01" y2="12"/>
              <line x1="3" y1="18" x2="3.01" y2="18"/>
            </svg>
            Display Order
          </label>
          <input type="number" 
                 id="sort_order" 
                 name="sort_order" 
                 class="form-control @error('sort_order') is-invalid @enderror" 
                 value="{{ old('sort_order', $banner->sort_order) }}" 
                 min="0"
                 placeholder="0">
          <div class="form-help">
            <svg class="help-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <path d="M9,9a3,3,0,0,1,6,0c0,2-3,3-3,3"/>
              <circle cx="12" cy="17" r=".5"/>
            </svg>
            <span>Lower numbers display first. Current order: {{ $banner->sort_order }}.</span>
          </div>
          @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Banner Status -->
        <div class="form-group full-width">
          <div class="toggle-section">
            <div class="toggle-header">
              <label class="form-label">
                <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 12l2 2 4-4"/>
                  <circle cx="12" cy="12" r="10"/>
                </svg>
                Banner Visibility
              </label>
            </div>
            
            <div class="toggle-control">
              <input type="checkbox" 
                     id="is_active" 
                     name="is_active" 
                     class="toggle-input" 
                     value="1" 
                     {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
              <label for="is_active" class="toggle-label">
                <span class="toggle-switch"></span>
                <span class="toggle-text">
                  <span class="toggle-text-active">Banner is visible on the website</span>
                  <span class="toggle-text-inactive">Banner is hidden from the website</span>
                </span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="form-actions">
        <div class="action-buttons">
          <button type="submit" class="btn btn-primary btn-lg btn-with-icon">
            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
              <polyline points="17,21 17,13 7,13 7,21"/>
              <polyline points="7,3 7,8 15,8"/>
            </svg>
            Update Banner
          </button>
          
          <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary btn-lg btn-with-icon">
            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 12H6M12 5l-7 7 7 7"/>
            </svg>
            Cancel
          </a>
        </div>
        
        <div class="form-meta">
          <h4>📊 Banner Information</h4>
          <div class="meta-grid">
            <div class="meta-item">
              <span class="meta-label">Created:</span>
              <span class="meta-value">{{ $banner->created_at->format('M j, Y g:i A') }}</span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Last Updated:</span>
              <span class="meta-value">{{ $banner->updated_at->format('M j, Y g:i A') }}</span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Status:</span>
              <span class="meta-value {{ $banner->is_active ? 'text-success' : 'text-muted' }}">
                {{ $banner->is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<style>
/* Enhanced Edit Styles */
.header-breadcrumb { margin-bottom: 1rem; }
.breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; }
.breadcrumb-link { display: flex; align-items: center; gap: 0.25rem; color: var(--primary); text-decoration: none; transition: color 0.2s ease; }
.breadcrumb-link:hover { color: var(--primary-dark); }
.breadcrumb-icon { width: 1rem; height: 1rem; }
.breadcrumb-separator { color: var(--text-muted); }
.breadcrumb-current { color: var(--text-light); }

.header-content { display: flex; justify-content: space-between; align-items: flex-start; gap: 2rem; }
.page-title { font-size: 2.25rem; font-weight: 700; color: var(--text); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.75rem; }
.title-icon { font-size: 2rem; }
.page-description { font-size: 1rem; color: var(--text-light); line-height: 1.6; margin: 0; max-width: 600px; }

.header-actions { display: flex; align-items: center; gap: 1rem; }
.banner-status { display: flex; align-items: center; gap: 0.5rem; }
.status-label { font-size: 0.875rem; color: var(--text-light); }
.status-badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
.status-badge.active { background: rgba(5, 150, 105, 0.1); color: var(--success); border: 1px solid rgba(5, 150, 105, 0.2); }
.status-badge.inactive { background: rgba(107, 114, 128, 0.1); color: var(--text-muted); border: 1px solid rgba(107, 114, 128, 0.2); }

.current-banner-card { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow); margin-bottom: 2rem; overflow: hidden; }
.preview-header { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid var(--border); padding: 1.5rem; text-align: center; }
.preview-header h3 { margin: 0 0 0.25rem 0; font-size: 1.25rem; font-weight: 600; color: var(--text); }
.preview-subtitle { color: var(--text-light); font-size: 0.875rem; }
.banner-preview-large { position: relative; aspect-ratio: 3/1; overflow: hidden; }
.current-banner-image { width: 100%; height: 100%; object-fit: cover; }
.banner-info-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 2rem; color: white; }
.banner-title-overlay { font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; }
.banner-link-overlay { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; opacity: 0.9; }
.banner-link-overlay .link-icon { width: 1rem; height: 1rem; }

.form-card { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow); overflow: hidden; }
.form-header { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid var(--border); padding: 2rem; text-align: center; }
.form-title { font-size: 1.5rem; font-weight: 600; color: var(--text); margin: 0 0 0.5rem 0; }
.form-subtitle { color: var(--text-light); margin: 0; font-size: 1rem; }
.enhanced-form { padding: 2rem; }
.form-grid { display: grid; gap: 2rem; grid-template-columns: 1fr 1fr; }
.form-group.full-width { grid-column: 1 / -1; }
.form-label { display: flex; align-items: center; gap: 0.5rem; font-weight: 600; margin-bottom: 0.75rem; color: var(--text); font-size: 0.875rem; }
.label-icon { width: 1rem; height: 1rem; color: var(--primary); }
.required { color: var(--danger); }
.form-control { width: 100%; padding: 0.875rem 1rem; border: 2px solid var(--border); border-radius: var(--radius); font-size: 1rem; transition: all 0.2s ease; background: var(--card); }
.form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1); }
.form-control.is-invalid { border-color: var(--danger); }

.image-update-section { display: grid; gap: 1.5rem; }
.current-image-info { display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--border-light); border: 1px solid var(--border); border-radius: var(--radius); }
.current-image-thumbnail { flex-shrink: 0; }
.current-image-thumbnail img { width: 4rem; height: 2.5rem; object-fit: cover; border-radius: var(--radius); border: 1px solid var(--border); }
.current-image-details { flex: 1; }
.image-filename { font-weight: 500; color: var(--text); margin: 0 0 0.25rem 0; font-size: 0.875rem; }
.image-meta { color: var(--text-muted); margin: 0; font-size: 0.75rem; }

.file-upload-area { position: relative; border: 2px dashed var(--border); border-radius: var(--radius); padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.2s ease; background: var(--border-light); }
.file-upload-area:hover { border-color: var(--primary); background: rgba(8, 145, 178, 0.05); }
.file-input { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
.upload-icon { width: 2rem; height: 2rem; color: var(--text-muted); margin-bottom: 0.75rem; }
.upload-title { font-size: 0.875rem; font-weight: 500; color: var(--text); margin: 0 0 0.25rem 0; }
.upload-subtitle { font-size: 0.75rem; color: var(--text-muted); margin: 0; }

.image-preview { border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; margin-top: 1rem; }
.preview-header-small { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: var(--border-light); border-bottom: 1px solid var(--border); font-size: 0.875rem; font-weight: 500; }
.image-preview img { width: 100%; height: 150px; object-fit: cover; }
.remove-image { background: var(--danger); color: white; border: none; border-radius: var(--radius); padding: 0.25rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s ease; }
.remove-image:hover { background: var(--danger); opacity: 0.8; }
.remove-image svg { width: 0.875rem; height: 0.875rem; }

.form-help { display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem; font-size: 0.75rem; color: var(--text-muted); }
.help-icon { width: 1rem; height: 1rem; flex-shrink: 0; }

.toggle-section { background: var(--border-light); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem; }
.toggle-control { display: flex; align-items: center; gap: 1rem; }
.toggle-input { display: none; }
.toggle-label { display: flex; align-items: center; gap: 1rem; cursor: pointer; user-select: none; }
.toggle-switch { position: relative; width: 3rem; height: 1.5rem; background: var(--text-muted); border-radius: 9999px; transition: background 0.2s ease; }
.toggle-switch::before { content: ''; position: absolute; top: 2px; left: 2px; width: 1.25rem; height: 1.25rem; background: white; border-radius: 50%; transition: transform 0.2s ease; }
.toggle-input:checked + .toggle-label .toggle-switch { background: var(--primary); }
.toggle-input:checked + .toggle-label .toggle-switch::before { transform: translateX(1.5rem); }
.toggle-text-inactive { display: block; }
.toggle-text-active { display: none; }
.toggle-input:checked + .toggle-label .toggle-text-active { display: block; }
.toggle-input:checked + .toggle-label .toggle-text-inactive { display: none; }

.form-actions { margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border); display: grid; grid-template-columns: 1fr auto; gap: 2rem; align-items: start; }
.action-buttons { display: flex; gap: 1rem; }
.btn-lg { padding: 0.875rem 1.5rem; font-size: 1rem; }
.btn-with-icon { display: inline-flex; align-items: center; gap: 0.5rem; }
.btn-icon { width: 1rem; height: 1rem; }

.form-meta { background: var(--border-light); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem; max-width: 300px; }
.form-meta h4 { margin: 0 0 1rem 0; font-size: 0.875rem; color: var(--text); }
.meta-grid { display: grid; gap: 0.75rem; }
.meta-item { display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; }
.meta-label { color: var(--text-muted); font-weight: 500; }
.meta-value { color: var(--text); font-weight: 400; }
.text-success { color: var(--success) !important; }
.text-muted { color: var(--text-muted) !important; }

.invalid-feedback { display: block; color: var(--danger); font-size: 0.875rem; margin-top: 0.5rem; }

/* Responsive Design */
@media (max-width: 768px) {
  .form-grid { grid-template-columns: 1fr; }
  .form-actions { grid-template-columns: 1fr; }
  .action-buttons { flex-direction: column; }
  .enhanced-form { padding: 1rem; }
  .form-header { padding: 1.5rem; }
  .header-content { flex-direction: column; align-items: stretch; gap: 1rem; }
  .current-image-info { flex-direction: column; text-align: center; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const fileUploadArea = document.getElementById('fileUploadArea');
  const fileInput = document.getElementById('image');
  const imagePreview = document.getElementById('imagePreview');
  const previewImg = document.getElementById('previewImg');
  const removeImage = document.getElementById('removeImage');

  // File upload handling
  fileUploadArea.addEventListener('click', function() {
    fileInput.click();
  });

  fileUploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    fileUploadArea.classList.add('dragover');
  });

  fileUploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    fileUploadArea.classList.remove('dragover');
  });

  fileUploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    fileUploadArea.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
      handleFile(files[0]);
    }
  });

  fileInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
      handleFile(this.files[0]);
    }
  });

  function handleFile(file) {
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        previewImg.src = e.target.result;
        imagePreview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  }

  removeImage.addEventListener('click', function() {
    fileInput.value = '';
    imagePreview.style.display = 'none';
  });
});
</script>
@endsection
