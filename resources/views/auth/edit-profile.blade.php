@extends('layouts.app')

@section('title', 'Edit Profile')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
@endsection

@section('content')
<div class="modern-profile-container">
  <div class="profile-header">
    <div class="profile-header-content">
      <h1 class="profile-title">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
          <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
        </svg>
        Edit Your Profile
      </h1>
      <p class="profile-subtitle">Manage your personal information and preferences</p>
    </div>
  </div>

  <div class="profile-form-container">
    <form method="POST" action="{{ route('profile.update') }}" class="modern-edit-form" enctype="multipart/form-data">
      @csrf
      @method('PATCH')

      <!-- Profile Picture Section -->
      <div class="form-section">
        <h3 class="section-title">Profile Picture</h3>
        <div class="profile-picture-section">
          <div class="current-avatar">
            @if($user->profile_picture)
              <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-preview">
            @else
              <div class="avatar-placeholder-large">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif
            <div class="avatar-overlay">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2v11z" stroke="currentColor" stroke-width="2"/>
                <circle cx="12" cy="13" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
              </svg>
            </div>
          </div>
          <div class="upload-section">
            <input type="file" name="profile_picture" accept="image/*" class="file-input" id="profilePicture">
            <label for="profilePicture" class="upload-btn">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="2"/>
              </svg>
              Choose Photo
            </label>
            <p class="upload-hint">JPG, PNG or GIF (max 2MB)</p>
          </div>
        </div>
      </div>

      <!-- Personal Information Section -->
      <div class="form-section">
        <h3 class="section-title">Personal Information</h3>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input">
          </div>
          
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
          </div>
          
          <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="Optional">
          </div>
        </div>
      </div>

      <!-- Security Section -->
      <div class="form-section">
        <h3 class="section-title">Security</h3>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" name="password" placeholder="Leave blank to keep current" class="form-input">
          </div>
          
          <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm new password" class="form-input">
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-actions">
        <button type="submit" class="save-btn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2"/>
            <polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="2"/>
            <polyline points="7,3 7,8 15,8" stroke="currentColor" stroke-width="2"/>
          </svg>
          Save Changes
        </button>
        <a href="{{ route('dashboard') }}" class="cancel-btn">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
