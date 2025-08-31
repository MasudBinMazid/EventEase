@extends('admin.layout')
@section('title','Create Blog Post')

@section('content')
<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Create Blog Post</h1>
      <p class="admin-subtitle">Write and publish a new blog post</p>
    </div>
    <div class="admin-actions">
      <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15,18 9,12 15,6"/>
        </svg>
        Back to Blogs
      </a>
    </div>
  </div>

  <!-- Form Card -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Blog Post Information</h3>
      <p class="card-subtitle">Fill in the details below to create a new blog post</p>
    </div>
    
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/>
            <line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
          Please review the highlighted fields below.
        </div>
      @endif

      <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data" class="admin-form">
        @csrf

        <div class="form-section">
          <h4 class="section-title">Basic Information</h4>
          
          <div class="form-group">
            <label class="form-label" for="title">
              Blog Title
              <span class="required">*</span>
            </label>
            <input type="text" name="title" id="title" class="form-input" value="{{ old('title') }}" required placeholder="Enter an engaging blog title">
            @error('title')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="author">
              Author Name
              <span class="required">*</span>
            </label>
            <input type="text" name="author" id="author" class="form-input" value="{{ old('author') }}" required placeholder="Enter author name">
            @error('author')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="short_description">
              Short Description
              <span class="required">*</span>
            </label>
            <textarea name="short_description" id="short_description" class="form-input form-textarea" rows="3" required maxlength="500" placeholder="Write a brief description that will appear in blog listings and previews">{{ old('short_description') }}</textarea>
            <div class="form-hint">Maximum 500 characters. This appears on listings and previews.</div>
            <div class="char-counter" id="sd-counter">0 / 500</div>
            @error('short_description')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="form-section">
          <h4 class="section-title">Content</h4>
          
          <div class="form-group">
            <label class="form-label" for="full_content">
              Full Blog Content
              <span class="required">*</span>
            </label>
            <textarea name="full_content" id="full_content" class="form-input form-textarea" rows="12" required placeholder="Write the full blog post content here. You can use HTML formatting if needed.">{{ old('full_content') }}</textarea>
            <div class="form-hint">Write your complete blog post. HTML formatting is supported.</div>
            @error('full_content')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="form-section">
          <h4 class="section-title">Featured Image</h4>
          
          <div class="form-group">
            <label class="form-label" for="image">
              Blog Image
              <span class="required">*</span>
            </label>
            <input type="file" name="image" id="image" class="form-input" accept=".jpg,.jpeg,.png" required>
            <div class="form-hint">Recommended size: 1200×630px (JPG or PNG, max 2MB)</div>
            @error('image')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="image-preview" id="imagePreview" style="display: none;">
            <label class="form-label">Preview</label>
            <div class="preview-container">
              <img id="previewImg" src="" alt="Blog image preview" class="preview-image">
              <button type="button" class="remove-preview" id="removePreview">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <line x1="18" y1="6" x2="6" y2="18"/>
                  <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
            Create Blog Post
          </button>
          <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 12H5m7-7l-7 7 7 7"/>
            </svg>
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .char-counter {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-align: right;
    margin-top: 0.25rem;
    font-family: monospace;
  }
  
  .image-preview {
    margin-top: 1rem;
  }
  
  .preview-container {
    position: relative;
    display: inline-block;
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--border);
    background: var(--border-light);
  }
  
  .preview-image {
    width: 100%;
    max-width: 400px;
    height: auto;
    display: block;
  }
  
  .remove-preview {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: rgba(239, 68, 68, 0.9);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .remove-preview:hover {
    background: rgba(239, 68, 68, 1);
    transform: scale(1.1);
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Character counter for short description
    const shortDesc = document.getElementById('short_description');
    const counter = document.getElementById('sd-counter');
    
    if (shortDesc && counter) {
      function updateCounter() {
        const length = shortDesc.value.length;
        counter.textContent = length + ' / 500';
        counter.style.color = length > 450 ? 'var(--warning)' : 'var(--text-muted)';
      }
      
      shortDesc.addEventListener('input', updateCounter);
      updateCounter(); // Initial call
    }
    
    // Image preview functionality
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removePreview = document.getElementById('removePreview');
    
    if (imageInput && imagePreview && previewImg) {
      imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (!file) {
          imagePreview.style.display = 'none';
          return;
        }
        
        // Check file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
          alert('Selected file exceeds 2MB. Please choose a smaller file.');
          imageInput.value = '';
          imagePreview.style.display = 'none';
          return;
        }
        
        // Check file type
        if (!file.type.match('image.*')) {
          alert('Please select a valid image file (JPG or PNG).');
          imageInput.value = '';
          imagePreview.style.display = 'none';
          return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      });
      
      // Remove preview functionality
      if (removePreview) {
        removePreview.addEventListener('click', function() {
          imageInput.value = '';
          imagePreview.style.display = 'none';
        });
      }
    }
  });
</script>
@endsection
