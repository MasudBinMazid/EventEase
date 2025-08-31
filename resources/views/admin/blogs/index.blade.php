@extends('admin.layout')
@section('title','Blogs Management')

@section('content')
<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Blogs Management</h1>
      <p class="admin-subtitle">Create, edit, and manage blog posts</p>
    </div>
    <div class="admin-actions">
      <div class="badge badge-info">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        {{ $blogs->count() }} Blog Posts
      </div>
      <a class="btn btn-primary" href="{{ route('admin.blogs.create') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 5v14M5 12h14"/>
        </svg>
        New Blog Post
      </a>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number">{{ $blogs->count() }}</div>
      <div class="stat-label">Total Posts</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $blogs->where('created_at', '>=', now()->subDays(30))->count() }}</div>
      <div class="stat-label">This Month</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $blogs->where('created_at', '>=', now()->subDays(7))->count() }}</div>
      <div class="stat-label">This Week</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $blogs->unique('author')->count() }}</div>
      <div class="stat-label">Authors</div>
    </div>
  </div>

  <!-- Blogs Table -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">All Blog Posts</h3>
    </div>
    <div class="card-body" style="padding: 0;">
      @if($blogs->isEmpty())
        <div style="padding: 3rem; text-align: center; color: var(--text-light);">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14,2 14,8 20,8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10,9 9,9 8,9"/>
          </svg>
          <p>No blog posts found. Click <strong>New Blog Post</strong> to create one.</p>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th style="width: 80px;">#</th>
                <th>Post Details</th>
                <th>Author</th>
                <th>Featured Image</th>
                <th>Published</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($blogs as $blog)
                <tr>
                  <td>
                    <span style="font-weight: 600; color: var(--primary);">#{{ $blog->id }}</span>
                  </td>
                  <td>
                    <div>
                      <div style="font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">{{ $blog->title }}</div>
                      @if($blog->content)
                        <div style="font-size: 0.85rem; color: var(--text-light); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                          {{ Str::limit(strip_tags($blog->content), 100) }}
                        </div>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--success); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.85rem;">
                        {{ strtoupper(substr($blog->author ?? 'U', 0, 1)) }}
                      </div>
                      <div style="font-weight: 600; color: var(--text);">{{ $blog->author ?? 'Unknown' }}</div>
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; align-items: center; justify-content: center;">
                      @if($blog->image)
                        <img src="{{ asset('storage/'.$blog->image) }}" alt="Blog image" style="width: 60px; height: 40px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-light);">
                      @else
                        <div style="width: 60px; height: 40px; background: var(--bg-light); border: 1px dashed var(--border-light); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.8rem;">
                          No Image
                        </div>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                      <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M7 2v4M17 2v4M3 9h18M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5A2 2 0 0 0 3 7v12a2 2 0 0 0 2 2Z"/>
                        </svg>
                        <span style="color: var(--text); font-size: 0.85rem;">{{ $blog->created_at?->format('M j, Y') }}</span>
                      </div>
                      <div style="font-size: 0.8rem; color: var(--text-light); padding-left: 1.25rem;">{{ $blog->created_at?->format('g:i A') }}</div>
                      <div style="font-size: 0.75rem; color: var(--text-muted); padding-left: 1.25rem;">{{ $blog->created_at?->diffForHumans() }}</div>
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                      <a class="btn btn-outline" href="{{ route('admin.blogs.edit', $blog) }}" title="Edit Blog Post">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                          <path d="m18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit
                      </a>
                      <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Are you sure you want to delete this blog post? This action cannot be undone.')" style="display: inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" type="submit" title="Delete Blog Post" style="padding: 0.5rem 0.75rem;">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3,6 5,6 21,6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                          </svg>
                          Delete
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  <!-- Action Buttons -->
  <div style="display: flex; gap: 1rem; justify-content: flex-start; margin-top: 2rem;">
    <a href="{{ route('admin.index') }}" class="btn btn-outline">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="15,18 9,12 15,6"/>
      </svg>
      Back to Dashboard
    </a>
  </div>
</div>
@endsection
