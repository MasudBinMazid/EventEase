@extends('admin.layout')
@section('title', 'Maintenance Mode')

@section('extra-css')
<style>
.maintenance-container {
    max-width: 800px;
    margin: 0 auto;
    background: var(--card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.maintenance-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    text-align: center;
}

.maintenance-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.maintenance-header p {
    opacity: 0.9;
    font-size: 1.1rem;
}

.maintenance-content {
    padding: 2rem;
}

.status-card {
    background: var(--bg);
    border: 2px solid var(--border);
    border-radius: var(--radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}

.status-card.enabled {
    border-color: var(--danger);
    background: #fef2f2;
}

.status-card.disabled {
    border-color: var(--success);
    background: #f0fdf4;
}

.status-indicator {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin: 0 auto 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.status-indicator.enabled {
    background: var(--danger);
    color: white;
}

.status-indicator.disabled {
    background: var(--success);
    color: white;
}

.status-text {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.status-text.enabled {
    color: var(--danger);
}

.status-text.disabled {
    color: var(--success);
}

.quick-toggle {
    background: none;
    border: 2px solid currentColor;
    padding: 0.75rem 2rem;
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.quick-toggle.enabled {
    color: var(--success);
}

.quick-toggle.enabled:hover {
    background: var(--success);
    color: white;
}

.quick-toggle.disabled {
    color: var(--danger);
}

.quick-toggle.disabled:hover {
    background: var(--danger);
    color: white;
}

.form-section {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-section h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--text);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 1rem;
    transition: border-color 0.2s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: var(--focus-ring);
}

.form-group textarea {
    min-height: 120px;
    resize: vertical;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.checkbox-group input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: var(--primary);
}

.btn-primary {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}

.alert {
    padding: 1rem;
    border-radius: var(--radius);
    margin-bottom: 1.5rem;
    border: 1px solid;
}

.alert-success {
    background: #f0fdf4;
    border-color: #16a34a;
    color: #15803d;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-light);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
    margin-bottom: 1.5rem;
}

.back-link:hover {
    color: var(--primary);
}

.back-link svg {
    width: 16px;
    height: 16px;
}

.info-box {
    background: #eff6ff;
    border: 1px solid #3b82f6;
    border-radius: var(--radius);
    padding: 1rem;
    margin-top: 1rem;
}

.info-box h4 {
    color: #1e40af;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.info-box p {
    color: #1e40af;
    font-size: 0.9rem;
    margin: 0;
}

@media (max-width: 768px) {
    .maintenance-container {
        margin: 1rem;
    }
    
    .maintenance-content {
        padding: 1rem;
    }
    
    .maintenance-header {
        padding: 1.5rem;
    }
    
    .maintenance-header h1 {
        font-size: 1.5rem;
    }
}
</style>
@endsection

@section('content')
<div class="maintenance-container">
    <div class="maintenance-header">
        <h1>⚙️ Maintenance Mode</h1>
        <p>Control site accessibility during maintenance periods</p>
    </div>

    <div class="maintenance-content">
        <a href="{{ route('admin.index') }}" class="back-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Dashboard
        </a>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <!-- Current Status -->
        <div class="status-card {{ $settings->is_enabled ? 'enabled' : 'disabled' }}">
            <div class="status-indicator {{ $settings->is_enabled ? 'enabled' : 'disabled' }}">
                @if($settings->is_enabled)
                    🔧
                @else
                    ✅
                @endif
            </div>
            <div class="status-text {{ $settings->is_enabled ? 'enabled' : 'disabled' }}">
                Maintenance Mode: {{ $settings->is_enabled ? 'ENABLED' : 'DISABLED' }}
            </div>
            <p style="margin-bottom: 1.5rem; opacity: 0.8;">
                @if($settings->is_enabled)
                    Your site is currently in maintenance mode. Only admins and managers can access it.
                @else
                    Your site is accessible to all users.
                @endif
            </p>
            <button onclick="toggleMaintenance()" class="quick-toggle {{ $settings->is_enabled ? 'enabled' : 'disabled' }}">
                {{ $settings->is_enabled ? 'Disable Maintenance' : 'Enable Maintenance' }}
            </button>
        </div>

        <!-- Settings Form -->
        <form action="{{ route('admin.maintenance.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h3>🎯 Basic Settings</h3>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_enabled" name="is_enabled" value="1" 
                               {{ $settings->is_enabled ? 'checked' : '' }}>
                        <label for="is_enabled">Enable Maintenance Mode</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="title">Maintenance Page Title</label>
                    <input type="text" id="title" name="title" 
                           value="{{ old('title', $settings->title) }}" 
                           placeholder="Site Under Maintenance">
                    @error('title')
                    <span style="color: var(--danger); font-size: 0.9rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message">Maintenance Message</label>
                    <textarea id="message" name="message" 
                              placeholder="We are currently performing maintenance on our website. We will be back online shortly!">{{ old('message', $settings->message) }}</textarea>
                    @error('message')
                    <span style="color: var(--danger); font-size: 0.9rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3>⏰ Advanced Settings</h3>
                
                <div class="form-group">
                    <label for="estimated_completion">Estimated Completion Time (Optional)</label>
                    <input type="datetime-local" id="estimated_completion" name="estimated_completion" 
                           value="{{ old('estimated_completion', $settings->estimated_completion ? $settings->estimated_completion->format('Y-m-d\TH:i') : '') }}">
                    @error('estimated_completion')
                    <span style="color: var(--danger); font-size: 0.9rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="allowed_ips">Allowed IP Addresses (Optional)</label>
                    <input type="text" id="allowed_ips" name="allowed_ips" 
                           value="{{ old('allowed_ips', $settings->allowed_ips ? implode(', ', $settings->allowed_ips) : '') }}" 
                           placeholder="192.168.1.1, 203.0.113.0, etc.">
                    <div class="info-box">
                        <h4>💡 How it works:</h4>
                        <p>Enter IP addresses separated by commas. These IPs will have access to the site even during maintenance. Admin and manager users always have access regardless of IP restrictions.</p>
                    </div>
                    @error('allowed_ips')
                    <span style="color: var(--danger); font-size: 0.9rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-primary">
                💾 Save Settings
            </button>
        </form>

        @if($settings->updated_by)
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border); color: var(--text-light); font-size: 0.9rem;">
            Last updated by {{ $settings->updatedBy->name ?? 'Unknown' }} on {{ $settings->updated_at->format('F j, Y \a\t g:i A') }}
        </div>
        @endif
    </div>
</div>

<script>
function toggleMaintenance() {
    if (confirm('Are you sure you want to toggle maintenance mode?')) {
        fetch('{{ route("admin.maintenance.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unable to toggle maintenance mode'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: Unable to toggle maintenance mode');
        });
    }
}
</script>
@endsection
