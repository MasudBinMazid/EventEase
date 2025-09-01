@extends('admin.layout')

@section('title', 'Edit Notice')

@section('extra-css')
<style>
    .form-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .form-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
        border-color: #f59e0b;
        background: white;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
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
    
    .update-btn {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
    }
    
    .update-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
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
    
    .delete-btn {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 16px 32px;
        border: none;
        border-radius: 16px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .delete-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
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
    
    .status-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: 2px solid #93c5fd;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 16px;
    }
    
    .status-item {
        background: white;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }
    
    @media (max-width: 768px) {
        .date-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 12px;
        }
        
        .action-buttons .update-btn,
        .action-buttons .delete-btn,
        .action-buttons .cancel-btn {
            width: 100%;
            text-align: center;
        }
    }

    @keyframes scroll-left {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Back Navigation -->
    <div class="mb-8">
        <a href="{{ route('admin.notices.index') }}" 
           class="inline-flex items-center space-x-2 text-amber-600 hover:text-amber-800 font-semibold text-lg transition-colors">
            <span>←</span>
            <span>Back to Notice Management</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="max-w-4xl mx-auto">
        <div class="form-card">
            <!-- Header -->
            <div class="form-header">
                <div class="text-6xl mb-4">✏️</div>
                <h1 class="text-3xl font-bold mb-2">Edit Notice</h1>
                <p class="text-lg opacity-90">Update your scrolling announcement details</p>
            </div>

            <!-- Status Info -->
            <div class="p-8 pb-0">
                <div class="status-info">
                    <div class="text-lg font-semibold text-blue-800 mb-2">
                        📊 Notice Status Information
                    </div>
                    <div class="status-grid">
                        <div class="status-item">
                            <div class="font-medium text-gray-600">Current Status</div>
                            <div class="text-lg font-bold {{ $notice->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $notice->is_active ? '🟢 Active' : '🔴 Inactive' }}
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="font-medium text-gray-600">Priority Level</div>
                            <div class="text-lg font-bold">
                                @if($notice->priority >= 80)
                                    🔴 High ({{ $notice->priority }})
                                @elseif($notice->priority >= 50)
                                    🟡 Medium ({{ $notice->priority }})
                                @else
                                    🟢 Low ({{ $notice->priority }})
                                @endif
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="font-medium text-gray-600">Created</div>
                            <div class="text-sm text-gray-700">{{ $notice->created_at->format('M d, Y \a\t g:i A') }}</div>
                        </div>
                        <div class="status-item">
                            <div class="font-medium text-gray-600">Last Updated</div>
                            <div class="text-sm text-gray-700">{{ $notice->updated_at->format('M d, Y \a\t g:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form action="{{ route('admin.notices.update', $notice) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Title Field -->
                    <div class="form-group">
                        <label for="title" class="form-label">
                            📝 Notice Title (Internal Reference)
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $notice->title) }}"
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
                                  required>{{ old('content', $notice->content) }}</textarea>
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
                            <option value="0" {{ old('priority', $notice->priority) < 30 ? 'selected' : '' }}>
                                🟢 Low Priority - General Information
                            </option>
                            <option value="50" {{ old('priority', $notice->priority) >= 30 && old('priority', $notice->priority) < 80 ? 'selected' : '' }}>
                                🟡 Medium Priority - Important Updates
                            </option>
                            <option value="80" {{ old('priority', $notice->priority) >= 80 ? 'selected' : '' }}>
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
                                   {{ old('is_active', $notice->is_active) ? 'checked' : '' }}>
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
                                       value="{{ old('start_date', $notice->start_date ? $notice->start_date->format('Y-m-d\TH:i') : '') }}"
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
                                       value="{{ old('end_date', $notice->end_date ? $notice->end_date->format('Y-m-d\TH:i') : '') }}"
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

                    <!-- Styling Options -->
                    <div class="form-group">
                        <label class="form-label">
                            🎨 Visual Styling
                        </label>
                        
                        <!-- Colors -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                            <div>
                                <label for="bg_color" class="block text-sm font-medium text-gray-700 mb-2">
                                    🎨 Background Color
                                </label>
                                <div style="display: flex; gap: 12px; align-items: center;">
                                    <input type="color" 
                                           id="bg_color" 
                                           name="bg_color" 
                                           value="{{ old('bg_color', $notice->bg_color ?? '#f59e0b') }}"
                                           style="width: 50px; height: 40px; border-radius: 12px; border: 2px solid #e2e8f0; cursor: pointer;">
                                    <input type="text" 
                                           id="bg_color_text" 
                                           value="{{ old('bg_color', $notice->bg_color ?? '#f59e0b') }}"
                                           class="form-input" 
                                           placeholder="#f59e0b"
                                           style="flex: 1;">
                                </div>
                                @error('bg_color')
                                    <div class="error-message">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="text_color" class="block text-sm font-medium text-gray-700 mb-2">
                                    ✏️ Text Color
                                </label>
                                <div style="display: flex; gap: 12px; align-items: center;">
                                    <input type="color" 
                                           id="text_color" 
                                           name="text_color" 
                                           value="{{ old('text_color', $notice->text_color ?? '#ffffff') }}"
                                           style="width: 50px; height: 40px; border-radius: 12px; border: 2px solid #e2e8f0; cursor: pointer;">
                                    <input type="text" 
                                           id="text_color_text" 
                                           value="{{ old('text_color', $notice->text_color ?? '#ffffff') }}"
                                           class="form-input" 
                                           placeholder="#ffffff"
                                           style="flex: 1;">
                                </div>
                                @error('text_color')
                                    <div class="error-message">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Font Settings -->
                        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                            <div>
                                <label for="font_family" class="block text-sm font-medium text-gray-700 mb-2">
                                    📝 Font Family
                                </label>
                                <select id="font_family" name="font_family" class="form-input priority-select">
                                    <option value="Inter, sans-serif" {{ old('font_family', $notice->font_family ?? 'Inter, sans-serif') == 'Inter, sans-serif' ? 'selected' : '' }}>Inter (Default)</option>
                                    <option value="Arial, sans-serif" {{ old('font_family', $notice->font_family ?? '') == 'Arial, sans-serif' ? 'selected' : '' }}>Arial</option>
                                    <option value="Helvetica, sans-serif" {{ old('font_family', $notice->font_family ?? '') == 'Helvetica, sans-serif' ? 'selected' : '' }}>Helvetica</option>
                                    <option value="Georgia, serif" {{ old('font_family', $notice->font_family ?? '') == 'Georgia, serif' ? 'selected' : '' }}>Georgia</option>
                                    <option value="Times New Roman, serif" {{ old('font_family', $notice->font_family ?? '') == 'Times New Roman, serif' ? 'selected' : '' }}>Times New Roman</option>
                                    <option value="Courier New, monospace" {{ old('font_family', $notice->font_family ?? '') == 'Courier New, monospace' ? 'selected' : '' }}>Courier New</option>
                                    <option value="Roboto, sans-serif" {{ old('font_family', $notice->font_family ?? '') == 'Roboto, sans-serif' ? 'selected' : '' }}>Roboto</option>
                                    <option value="Open Sans, sans-serif" {{ old('font_family', $notice->font_family ?? '') == 'Open Sans, sans-serif' ? 'selected' : '' }}>Open Sans</option>
                                    <option value="Poppins, sans-serif" {{ old('font_family', $notice->font_family ?? '') == 'Poppins, sans-serif' ? 'selected' : '' }}>Poppins</option>
                                    <option value="Montserrat, sans-serif" {{ old('font_family', $notice->font_family ?? '') == 'Montserrat, sans-serif' ? 'selected' : '' }}>Montserrat</option>
                                </select>
                                @error('font_family')
                                    <div class="error-message">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="font_size" class="block text-sm font-medium text-gray-700 mb-2">
                                    📏 Size (px)
                                </label>
                                <input type="number" 
                                       id="font_size" 
                                       name="font_size" 
                                       value="{{ old('font_size', $notice->font_size ?? 16) }}"
                                       class="form-input"
                                       min="10" 
                                       max="48"
                                       placeholder="16">
                                @error('font_size')
                                    <div class="error-message">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="font_weight" class="block text-sm font-medium text-gray-700 mb-2">
                                    💪 Weight
                                </label>
                                <select id="font_weight" name="font_weight" class="form-input priority-select">
                                    <option value="300" {{ old('font_weight', $notice->font_weight ?? '500') == '300' ? 'selected' : '' }}>Light</option>
                                    <option value="400" {{ old('font_weight', $notice->font_weight ?? '500') == '400' ? 'selected' : '' }}>Normal</option>
                                    <option value="500" {{ old('font_weight', $notice->font_weight ?? '500') == '500' ? 'selected' : '' }}>Medium</option>
                                    <option value="600" {{ old('font_weight', $notice->font_weight ?? '500') == '600' ? 'selected' : '' }}>Semi Bold</option>
                                    <option value="700" {{ old('font_weight', $notice->font_weight ?? '500') == '700' ? 'selected' : '' }}>Bold</option>
                                    <option value="800" {{ old('font_weight', $notice->font_weight ?? '500') == '800' ? 'selected' : '' }}>Extra Bold</option>
                                </select>
                                @error('font_weight')
                                    <div class="error-message">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="text_style" class="block text-sm font-medium text-gray-700 mb-2">
                                    🎭 Style
                                </label>
                                <select id="text_style" name="text_style" class="form-input priority-select">
                                    <option value="normal" {{ old('text_style', $notice->text_style ?? 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="italic" {{ old('text_style', $notice->text_style ?? 'normal') == 'italic' ? 'selected' : '' }}>Italic</option>
                                </select>
                                @error('text_style')
                                    <div class="error-message">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Preview -->
                        <div style="margin-bottom: 16px;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                👁️ Live Preview
                            </label>
                            <div id="notice-preview" 
                                 style="padding: 12px 20px; border-radius: 16px; border: 2px solid #e2e8f0; min-height: 50px; display: flex; align-items: center; overflow: hidden; position: relative; background: {{ old('bg_color', $notice->bg_color ?? '#f59e0b') }};">
                                <div id="preview-text" 
                                     style="white-space: nowrap; animation: scroll-left 10s linear infinite; color: {{ old('text_color', $notice->text_color ?? '#ffffff') }}; font-family: {{ old('font_family', $notice->font_family ?? 'Inter, sans-serif') }}; font-size: {{ old('font_size', $notice->font_size ?? 16) }}px; font-weight: {{ old('font_weight', $notice->font_weight ?? '500') }}; font-style: {{ old('text_style', $notice->text_style ?? 'normal') }};">
                                    {{ old('content', $notice->content) }}
                                </div>
                            </div>
                        </div>

                        <div class="hint-text">
                            <span>✨</span>
                            <span>Customize the appearance of your scrolling notice to match your brand</span>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-8 border-t border-gray-200 action-buttons">
                        <!-- Delete Button -->
                        <button type="button" 
                                onclick="confirmDelete()"
                                class="delete-btn">
                            🗑️ Delete Notice
                        </button>

                        <!-- Update & Cancel Buttons -->
                        <div class="flex space-x-4">
                            <a href="{{ route('admin.notices.index') }}" class="cancel-btn">
                                Cancel
                            </a>
                            <button type="submit" class="update-btn">
                                💾 Update Notice
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Delete Form (Hidden) -->
                <form id="delete-form" action="{{ route('admin.notices.destroy', $notice) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
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
        updatePreview(); // Update preview when content changes
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

    // Color picker synchronization
    const bgColorPicker = document.getElementById('bg_color');
    const bgColorText = document.getElementById('bg_color_text');
    const textColorPicker = document.getElementById('text_color');
    const textColorText = document.getElementById('text_color_text');

    if (bgColorPicker && bgColorText) {
        bgColorPicker.addEventListener('input', function() {
            bgColorText.value = this.value;
            updatePreview();
        });
        
        bgColorText.addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                bgColorPicker.value = this.value;
                updatePreview();
            }
        });
    }

    if (textColorPicker && textColorText) {
        textColorPicker.addEventListener('input', function() {
            textColorText.value = this.value;
            updatePreview();
        });
        
        textColorText.addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                textColorPicker.value = this.value;
                updatePreview();
            }
        });
    }

    // Font and style change handlers
    const fontFamily = document.getElementById('font_family');
    const fontSize = document.getElementById('font_size');
    const fontWeight = document.getElementById('font_weight');
    const textStyle = document.getElementById('text_style');

    [fontFamily, fontSize, fontWeight, textStyle].forEach(element => {
        if (element) {
            element.addEventListener('change', updatePreview);
        }
    });

    // Live preview function
    function updatePreview() {
        const preview = document.getElementById('notice-preview');
        const previewText = document.getElementById('preview-text');
        
        if (!preview || !previewText) return;

        const content = contentInput ? contentInput.value || 'Your notice content will appear here...' : 'Your notice content will appear here...';
        const bgColor = bgColorText ? bgColorText.value : '#f59e0b';
        const textColor = textColorText ? textColorText.value : '#ffffff';
        const fontFam = fontFamily ? fontFamily.value : 'Inter, sans-serif';
        const fontSz = fontSize ? fontSize.value + 'px' : '16px';
        const fontWt = fontWeight ? fontWeight.value : '500';
        const textSt = textStyle ? textStyle.value : 'normal';

        preview.style.backgroundColor = bgColor;
        previewText.style.color = textColor;
        previewText.style.fontFamily = fontFam;
        previewText.style.fontSize = fontSz;
        previewText.style.fontWeight = fontWt;
        previewText.style.fontStyle = textSt;
        previewText.textContent = content;
    }

    // Initial preview update
    updatePreview();
});

function confirmDelete() {
    if (confirm('🗑️ Are you sure you want to delete this notice?\n\nThis action cannot be undone and the notice will be permanently removed from your events page.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
