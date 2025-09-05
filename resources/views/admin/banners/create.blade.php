@extends('admin.layout')

@section('title', 'Add New Banner')

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
        <span class="breadcrumb-current">Add New Banner</span>
      </nav>
    </div>
    
    <div class="header-content">
      <div class="header-left">
        <h1 class="page-title">
          <span class="title-icon">✨</span>
          Add New Banner
        </h1>
        <p class="page-description">
          Create an engaging banner to showcase on your homepage and attract more visitors
        </p>
      </div>
    </div>
  </div>

  <!-- Form Card -->
  <div class="form-card">
    <div class="form-header">
      <h3 class="form-title">Banner Information</h3>
      <p class="form-subtitle">Fill in the details below to create your new banner</p>
    </div>

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="enhanced-form">
      @csrf
      
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
                 value="{{ old('title') }}" 
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
            Banner Image <span class="required">*</span>
          </label>
          
          <div class="file-upload-area" id="fileUploadArea">
            <div class="file-upload-content">
              <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7,10 12,15 17,10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              <div class="upload-text">
                <p class="upload-title">Click to upload or drag and drop</p>
                <p class="upload-subtitle">PNG, JPG, GIF up to 2MB</p>
              </div>
            </div>
            <input type="file" 
                   id="image" 
                   name="image" 
                   class="file-input @error('image') is-invalid @enderror" 
                   accept="image/*"
                   required>
          </div>
          
          <div class="image-preview" id="imagePreview" style="display: none;">
            <img id="previewImg" src="" alt="Preview">
            <button type="button" class="remove-image" id="removeImage">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
          
          <div class="form-help">
            <svg class="help-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <path d="M9,9a3,3,0,0,1,6,0c0,2-3,3-3,3"/>
              <circle cx="12" cy="17" r=".5"/>
            </svg>
            <span>Recommended size: 1200x400px for best quality. Max file size: 2MB.</span>
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
                 value="{{ old('link') }}" 
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
                 value="{{ old('sort_order', 0) }}" 
                 min="0"
                 placeholder="0">
          <div class="form-help">
            <svg class="help-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <path d="M9,9a3,3,0,0,1,6,0c0,2-3,3-3,3"/>
              <circle cx="12" cy="17" r=".5"/>
            </svg>
            <span>Lower numbers display first. Use 10, 20, 30 for easy reordering.</span>
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
                     {{ old('is_active', true) ? 'checked' : '' }}>
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
            Create Banner
          </button>
          
          <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary btn-lg btn-with-icon">
            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 12H6M12 5l-7 7 7 7"/>
            </svg>
            Cancel
          </a>
        </div>
        
        <div class="form-tips">
          <h4>💡 Pro Tips</h4>
          <ul>
            <li>Use high-quality images with 3:1 aspect ratio (1200x400px)</li>
            <li>Keep file sizes under 500KB for faster loading</li>
            <li>Use compelling titles that grab attention</li>
            <li>Test banner links before publishing</li>
          </ul>
        </div>
      </div>
    </form>
  </div>
</div>

<style>
/* Enhanced Form Styles */
.header-breadcrumb {
  margin-bottom: 1rem;
}

.breadcrumb {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
}

.breadcrumb-link {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: var(--primary);
  text-decoration: none;
  transition: color 0.2s ease;
}

.breadcrumb-link:hover {
  color: var(--primary-dark);
}

.breadcrumb-icon {
  width: 1rem;
  height: 1rem;
}

.breadcrumb-separator {
  color: var(--text-muted);
}

.breadcrumb-current {
  color: var(--text-light);
}

.form-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
  overflow: hidden;
}

.form-header {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-bottom: 1px solid var(--border);
  padding: 2rem;
  text-align: center;
}

.form-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--text);
  margin: 0 0 0.5rem 0;
}

.form-subtitle {
  color: var(--text-light);
  margin: 0;
  font-size: 1rem;
}

.enhanced-form {
  padding: 2rem;
}

.form-grid {
  display: grid;
  gap: 2rem;
  grid-template-columns: 1fr 1fr;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
  color: var(--text);
  font-size: 0.875rem;
}

.label-icon {
  width: 1rem;
  height: 1rem;
  color: var(--primary);
}

.required {
  color: var(--danger);
}

.form-control {
  width: 100%;
  padding: 0.875rem 1rem;
  border: 2px solid var(--border);
  border-radius: var(--radius);
  font-size: 1rem;
  transition: all 0.2s ease;
  background: var(--card);
}

.form-control:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1);
}

.form-control.is-invalid {
  border-color: var(--danger);
}

/* File Upload Styles */
.file-upload-area {
  position: relative;
  border: 2px dashed var(--border);
  border-radius: var(--radius);
  padding: 2rem;
  text-align: center;
  transition: all 0.2s ease;
  cursor: pointer;
  background: var(--border-light);
}

.file-upload-area:hover {
  border-color: var(--primary);
  background: rgba(8, 145, 178, 0.05);
}

.file-upload-area.dragover {
  border-color: var(--primary);
  background: rgba(8, 145, 178, 0.1);
}

.file-input {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
}

.upload-icon {
  width: 3rem;
  height: 3rem;
  color: var(--text-muted);
  margin-bottom: 1rem;
}

.upload-title {
  font-size: 1rem;
  font-weight: 500;
  color: var(--text);
  margin: 0 0 0.25rem 0;
}

.upload-subtitle {
  font-size: 0.875rem;
  color: var(--text-muted);
  margin: 0;
}

.image-preview {
  position: relative;
  margin-top: 1rem;
  border-radius: var(--radius);
  overflow: hidden;
  border: 1px solid var(--border);
}

.image-preview img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.remove-image {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  background: rgba(220, 38, 38, 0.9);
  color: white;
  border: none;
  border-radius: 50%;
  width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background 0.2s ease;
}

.remove-image:hover {
  background: rgba(220, 38, 38, 1);
}

.remove-image svg {
  width: 1rem;
  height: 1rem;
}

/* Toggle Styles */
.toggle-section {
  background: var(--border-light);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 1.5rem;
}

.toggle-control {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.toggle-input {
  display: none;
}

.toggle-label {
  display: flex;
  align-items: center;
  gap: 1rem;
  cursor: pointer;
  user-select: none;
}

.toggle-switch {
  position: relative;
  width: 3rem;
  height: 1.5rem;
  background: var(--text-muted);
  border-radius: 9999px;
  transition: background 0.2s ease;
}

.toggle-switch::before {
  content: '';
  position: absolute;
  top: 2px;
  left: 2px;
  width: 1.25rem;
  height: 1.25rem;
  background: white;
  border-radius: 50%;
  transition: transform 0.2s ease;
}

.toggle-input:checked + .toggle-label .toggle-switch {
  background: var(--primary);
}

.toggle-input:checked + .toggle-label .toggle-switch::before {
  transform: translateX(1.5rem);
}

.toggle-text-inactive {
  display: block;
}

.toggle-text-active {
  display: none;
}

.toggle-input:checked + .toggle-label .toggle-text-active {
  display: block;
}

.toggle-input:checked + .toggle-label .toggle-text-inactive {
  display: none;
}

/* Form Help */
.form-help {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 0.5rem;
  font-size: 0.75rem;
  color: var(--text-muted);
}

.help-icon {
  width: 1rem;
  height: 1rem;
  flex-shrink: 0;
}

/* Form Actions */
.form-actions {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid var(--border);
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 2rem;
  align-items: start;
}

.action-buttons {
  display: flex;
  gap: 1rem;
}

.btn-lg {
  padding: 0.875rem 1.5rem;
  font-size: 1rem;
}

.form-tips {
  background: rgba(99, 102, 241, 0.05);
  border: 1px solid rgba(99, 102, 241, 0.2);
  border-radius: var(--radius);
  padding: 1.5rem;
  max-width: 300px;
}

.form-tips h4 {
  margin: 0 0 1rem 0;
  font-size: 0.875rem;
  color: var(--indigo);
}

.form-tips ul {
  margin: 0;
  padding-left: 1rem;
  color: var(--text-light);
  font-size: 0.75rem;
  line-height: 1.5;
}

.form-tips li {
  margin-bottom: 0.5rem;
}

/* Invalid Feedback */
.invalid-feedback {
  display: block;
  color: var(--danger);
  font-size: 0.875rem;
  margin-top: 0.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    grid-template-columns: 1fr;
  }
  
  .action-buttons {
    flex-direction: column;
  }
  
  .enhanced-form {
    padding: 1rem;
  }
  
  .form-header {
    padding: 1.5rem;
  }
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
        fileUploadArea.style.display = 'none';
      };
      reader.readAsDataURL(file);
    }
  }

  removeImage.addEventListener('click', function() {
    fileInput.value = '';
    imagePreview.style.display = 'none';
    fileUploadArea.style.display = 'block';
  });
});
</script>
@endsection
