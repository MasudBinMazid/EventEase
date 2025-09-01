@extends('admin.layout')

@section('title', 'Create Notice')

@section('extra-css')
<style>
    .form-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    
    .form-group {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .form-input {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        font-size: 16px;
        background: #f8fafc;
        transition: all 0.3s ease;
        outline: none;
    }
    
    .form-input:focus {
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 16px;
    }
    
    .form-textarea {
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
    }
    
    .priority-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 40px;
    }
    
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        background: linear-gradient(135deg, #f0fff4 0%, #dcfce7 100%);
        border: 2px solid #bbf7d0;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .checkbox-wrapper:hover {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.2);
    }
    
    .checkbox-input {
        width: 20px;
        height: 20px;
        accent-color: #22c55e;
    }
    
    .date-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .submit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px 32px;
        border: none;
        border-radius: 16px;
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }
    
    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }
    
    .cancel-btn {
        background: #6b7280;
        color: white;
        padding: 16px 32px;
        border: none;
        border-radius: 16px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .cancel-btn:hover {
        background: #4b5563;
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }
    
    .hint-text {
        color: #6b7280;
        font-size: 14px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 14px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fef2f2;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #fecaca;
    }
    
    @media (max-width: 768px) {
        .date-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Back Navigation -->
    <div class="mb-8">
        <a href="{{ route('admin.notices.index') }}" 
           class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800 font-semibold text-lg transition-colors">
            <span>←</span>
            <span>Back to Notice Management</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="max-w-4xl mx-auto">
        <div class="form-card">
            <!-- Header -->
            <div class="form-header">
                <div class="text-6xl mb-4">📢</div>
                <h1 class="text-3xl font-bold mb-2">Create New Notice</h1>
                <p class="text-lg opacity-90">Design an engaging scrolling announcement for your events</p>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form action="{{ route('admin.notices.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Title Field -->
                    <div class="form-group">
                        <label for="title" class="form-label">
                            📝 Notice Title (Internal Reference)
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               class="form-input"
                               placeholder="e.g., Summer Festival Promotion"
                               required>
                        @error('title')
                            <div class="error-message">
                                <span>⚠️</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                        <div class="hint-text">
                            <span>💡</span>
                            <span>This title is for your reference only - visitors won't see it</span>
                        </div>
                    </div>

                    <!-- Content Field -->
                    <div class="form-group">
                        <label for="content" class="form-label">
                            📄 Scrolling Content
                        </label>
                        <textarea id="content" 
                                  name="content" 
                                  class="form-input form-textarea"
                                  placeholder="🎉 Don't miss our spectacular Summer Music Festival! Early bird tickets now available with 30% discount. Book now and secure your spot for the biggest event of the year!"
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="error-message">
                                <span>⚠️</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                        <div class="hint-text">
                            <span>✨</span>
                            <span>This text will scroll horizontally across your events page. Use emojis and engaging language!</span>
                        </div>
                    </div>

                    <!-- Priority Field -->
                    <div class="form-group">
                        <label for="priority" class="form-label">
                            🎯 Priority Level
                        </label>
                        <select id="priority" 
                                name="priority" 
                                class="form-input priority-select">
                            <option value="0" {{ old('priority') == '0' ? 'selected' : '' }}>
                                🟢 Low Priority - General Information
                            </option>
                            <option value="50" {{ old('priority') == '50' ? 'selected' : '' }}>
                                🟡 Medium Priority - Important Updates
                            </option>
                            <option value="80" {{ old('priority') == '80' ? 'selected' : '' }}>
                                🔴 High Priority - Critical Announcements
                            </option>
                        </select>
                        @error('priority')
                            <div class="error-message">
                                <span>⚠️</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                        <div class="hint-text">
                            <span>🎲</span>
                            <span>Higher priority notices appear first in the scrolling sequence</span>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="form-group">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   class="checkbox-input"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <div class="flex-1">
                                <div class="font-semibold text-lg text-green-800">✅ Activate Notice</div>
                                <div class="text-sm text-green-600">Notice will be visible in the scrolling bar when enabled</div>
                            </div>
                        </label>
                        @error('is_active')
                            <div class="error-message">
                                <span>⚠️</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Date Range -->
                    <div class="form-group">
                        <label class="form-label">📅 Schedule (Optional)</label>
                        <div class="date-grid">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    🟢 Start Date & Time
                                </label>
                                <input type="datetime-local" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date') }}"
                                       class="form-input">
                                @error('start_date')
                                    <div class="error-message">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    🔴 End Date & Time
                                </label>
                                <input type="datetime-local" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date') }}"
                                       class="form-input">
                                @error('end_date')
                                    <div class="error-message">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="hint-text">
                            <span>🕒</span>
                            <span>Leave empty for permanent notices. Set dates to show notice only during specific periods</span>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                        <a href="{{ route('admin.notices.index') }}" class="cancel-btn">
                            Cancel
                        </a>
                        <button type="submit" class="submit-btn">
                            🚀 Create Notice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea
    const textarea = document.getElementById('content');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
    
    // Character count for content
    const contentInput = document.getElementById('content');
    const maxLength = 200;
    
    function updateCharCount() {
        const remaining = maxLength - contentInput.value.length;
        let countEl = document.getElementById('char-count');
        
        if (!countEl) {
            countEl = document.createElement('div');
            countEl.id = 'char-count';
            countEl.className = 'hint-text mt-2';
            contentInput.parentNode.appendChild(countEl);
        }
        
        if (remaining < 50) {
            countEl.innerHTML = `<span>⚠️</span><span style="color: #ef4444;">${remaining} characters remaining</span>`;
        } else {
            countEl.innerHTML = `<span>📝</span><span>${contentInput.value.length} characters entered</span>`;
        }
    }
    
    contentInput.addEventListener('input', updateCharCount);
    updateCharCount();
});
</script>
@endsection
