@extends('admin.layout')
@section('title','Edit Blog')

@section('content')

<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Edit Blog Post</h1>
      <p class="admin-subtitle">Update the blog post content and settings</p>
    </div>
    <div class="admin-actions">
      <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15,18 9,12 15,6"/>
        </svg>
        Back to Posts
      </a>
    </div>
  </div>

  <!-- Edit Form -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Post Details</h3>
      <p class="card-subtitle">Edit the blog post information and content</p>
    </div>
    <div class="card-body">
      {{-- Validation Errors --}}
      @if ($errors->any())
        <div class="alert alert-error">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/>
            <line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
          <div>
            <strong>Please review the highlighted fields:</strong>
            <ul style="margin-top: 0.5rem; list-style-type: disc; margin-left: 1.5rem;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data" class="form-grid">
        @csrf 
        @method('PUT')

        <!-- Basic Information Section -->
        <div class="form-section">
          <h4 class="section-title">Basic Information</h4>
          
          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="title">Post Title</label>
              <input 
                id="title" 
                type="text" 
                name="title" 
                value="{{ old('title', $blog->title) }}" 
                required 
                class="form-input {{ $errors->has('title') ? 'error' : '' }}"
                placeholder="Enter the blog post title"
              >
              @error('title')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group">
              <label class="form-label" for="author">Author</label>
              <input 
                id="author" 
                type="text" 
                name="author" 
                value="{{ old('author', $blog->author) }}" 
                required 
                class="form-input {{ $errors->has('author') ? 'error' : '' }}"
                placeholder="Author name"
              >
              @error('author')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <!-- Content Section -->
        <div class="form-section">
          <h4 class="section-title">Content</h4>
          
          <div class="form-group">
            <label class="form-label" for="short_description">Short Description</label>
            <textarea 
              id="short_description" 
              name="short_description" 
              rows="3" 
              required 
              class="form-input {{ $errors->has('short_description') ? 'error' : '' }}" 
              maxlength="500"
              placeholder="Brief description or excerpt (max 500 characters)"
            >{{ old('short_description', $blog->short_description) }}</textarea>
            <div class="form-help">
              <span id="short-desc-count">0 / 500</span> characters used
            </div>
            @error('short_description')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="full_content">Full Content</label>
            <textarea 
              id="full_content" 
              name="full_content" 
              rows="12" 
              required 
              class="form-input {{ $errors->has('full_content') ? 'error' : '' }}"
              placeholder="Write the complete blog post content here..."
            >{{ old('full_content', $blog->full_content) }}</textarea>
            @error('full_content')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Image Section -->
        <div class="form-section">
          <h4 class="section-title">Featured Image</h4>
          
          @if($blog->image)
            <div class="form-group">
              <label class="form-label">Current Image</label>
              <div class="image-preview-container">
                <img src="{{ asset('storage/'.$blog->image) }}" alt="Current blog image" class="image-preview">
                <div class="image-overlay">
                  <span class="image-badge">Current Image</span>
                </div>
              </div>
            </div>
          @else
            <div class="form-group">
              <div class="empty-state" style="padding: 2rem; text-align: center; border: 2px dashed var(--border); border-radius: 12px; background: var(--surface-light);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                  <circle cx="9" cy="9" r="2"/>
                  <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                </svg>
                <p style="color: var(--text-light); margin: 0;">No image uploaded</p>
              </div>
            </div>
          @endif

          <div class="form-group">
            <label class="form-label" for="image">Replace Image (Optional)</label>
            <input 
              id="image" 
              type="file" 
              name="image" 
              accept=".jpg,.jpeg,.png" 
              class="form-input {{ $errors->has('image') ? 'error' : '' }}"
            >
            <div class="form-help">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              Recommended: 1200×630px. Maximum file size: 2MB. Formats: JPG, PNG
            </div>
            <div id="new-image-preview" class="image-preview-container" style="display: none; margin-top: 1rem;">
              <img id="preview-image" class="image-preview" alt="New image preview">
              <div class="image-overlay">
                <span class="image-badge badge-success">New Image</span>
              </div>
            </div>
            @error('image')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="m9 12 2 2 4-4"/>
              <path d="m21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c2.12 0 4.07.74 5.61 1.97"/>
            </svg>
            Update Blog Post
          </button>
          <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 6 6 18"/>
              <path d="m6 6 12 12"/>
            </svg>
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .image-preview-container {
    position: relative;
    display: inline-block;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
  }
  
  .image-preview {
    width: 100%;
    max-width: 400px;
    height: 200px;
    object-fit: cover;
    display: block;
  }
  
  .image-overlay {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
  }
  
  .image-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 999px;
    background: var(--primary);
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
  }
  
  .image-badge.badge-success {
    background: var(--success);
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Character counter for short description
    const shortDesc = document.getElementById('short_description');
    const counter = document.getElementById('short-desc-count');
    
    if (shortDesc && counter) {
      function updateCounter() {
        const length = shortDesc.value.length;
        counter.textContent = `${length} / 500`;
        
        if (length > 450) {
          counter.style.color = 'var(--warning)';
        } else if (length > 500) {
          counter.style.color = 'var(--danger)';
        } else {
          counter.style.color = 'var(--text-light)';
        }
      }
      
      shortDesc.addEventListener('input', updateCounter);
      updateCounter(); // Initialize counter
    }
    
    // Image preview functionality
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('new-image-preview');
    const previewImage = document.getElementById('preview-image');
    
    if (imageInput && previewContainer && previewImage) {
      imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (!file) {
          previewContainer.style.display = 'none';
          return;
        }
        
        // Check file size (2MB limit)
        if (file.size > 2 * 1024 * 1024) {
          alert('File size exceeds 2MB limit. Please choose a smaller image.');
          imageInput.value = '';
          previewContainer.style.display = 'none';
          return;
        }
        
        const reader = new FileReader();
        reader.onload = function(event) {
          previewImage.src = event.target.result;
          previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
      });
    }
  });
</script>
@endsection
