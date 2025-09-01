@extends('admin.layout')

@section('title', 'Notice Management')

@section('extra-css')
<style>
    .notice-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .notice-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }
    
    .settings-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .priority-badge {
        position: relative;
        overflow: hidden;
    }
    
    .priority-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s;
    }
    
    .priority-badge:hover::before {
        left: 100%;
    }
    
    .action-btn {
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .edit-btn {
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    
    .delete-btn {
        background: linear-gradient(45deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
    }
    
    .create-btn {
        background: linear-gradient(45deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 6px 20px rgba(86, 171, 47, 0.3);
        transition: all 0.3s ease;
    }
    
    .create-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(86, 171, 47, 0.4);
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(240, 147, 251, 0.3);
    }
    
    .notice-table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .table-row:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 100%);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-10">
        <div class="space-y-2">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                📢 Notice Management
            </h1>
            <p class="text-gray-600 text-lg">Create stunning scrolling announcements for your events</p>
        </div>
        <a href="{{ route('admin.notices.create') }}" 
           class="create-btn text-decoration-none">
            ✨ Create Notice
        </a>
    </div>

    @if (session('success'))
        <div class="mb-8 p-4 bg-gradient-to-r from-green-400 to-blue-500 text-white rounded-2xl shadow-lg">
            <div class="flex items-center space-x-3">
                <span class="text-2xl">🎉</span>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Notice Bar Settings --}}
    <div class="settings-card p-8 mb-10">
        <div class="flex items-center space-x-4 mb-6">
            <span class="text-3xl">⚙️</span>
            <h2 class="text-2xl font-bold">Notice Bar Settings</h2>
        </div>
        
        <form action="{{ route('admin.notices.settings') }}" method="POST" class="space-y-6">
            @csrf
            
            {{-- Main Toggle --}}
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-6">
                <label class="flex items-center space-x-4 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" id="is_enabled" name="is_enabled" value="1" 
                               {{ $settings->is_enabled ? 'checked' : '' }}
                               class="sr-only">
                        <div class="toggle-bg w-14 h-8 bg-white/20 rounded-full shadow-inner transition-colors duration-300"></div>
                        <div class="toggle-dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300"></div>
                    </div>
                    <span class="text-xl font-semibold">
                        Enable Notice Bar
                    </span>
                </label>
            </div>

            {{-- Settings Grid --}}
            <div class="grid md:grid-cols-3 gap-6">
                {{-- Scroll Speed --}}
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4">
                    <label class="block text-white font-semibold mb-3">🏃‍♂️ Scroll Speed</label>
                    <select name="scroll_speed" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white focus:ring-2 focus:ring-white/50">
                        <option value="slow" {{ $settings->scroll_speed == 'slow' ? 'selected' : '' }} style="color: #333;">🐌 Slow</option>
                        <option value="normal" {{ $settings->scroll_speed == 'normal' ? 'selected' : '' }} style="color: #333;">🚶‍♂️ Normal</option>
                        <option value="fast" {{ $settings->scroll_speed == 'fast' ? 'selected' : '' }} style="color: #333;">🏃‍♂️ Fast</option>
                    </select>
                </div>

                {{-- Background Color --}}
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4">
                    <label class="block text-white font-semibold mb-3">🎨 Background</label>
                    <div class="flex space-x-3">
                        <input type="color" name="background_color" value="{{ $settings->background_color }}"
                               class="w-12 h-12 rounded-lg border-2 border-white/30 cursor-pointer">
                        <input type="text" value="{{ $settings->background_color }}" readonly
                               class="flex-1 px-3 py-2 bg-white/20 border border-white/30 rounded-lg text-white">
                    </div>
                </div>

                {{-- Text Color --}}
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4">
                    <label class="block text-white font-semibold mb-3">✍️ Text Color</label>
                    <div class="flex space-x-3">
                        <input type="color" name="text_color" value="{{ $settings->text_color }}"
                               class="w-12 h-12 rounded-lg border-2 border-white/30 cursor-pointer">
                        <input type="text" value="{{ $settings->text_color }}" readonly
                               class="flex-1 px-3 py-2 bg-white/20 border border-white/30 rounded-lg text-white">
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="text-center">
                <button type="submit" class="bg-white text-purple-600 px-8 py-3 rounded-xl font-bold text-lg hover:bg-gray-100 transition-colors duration-300 shadow-lg">
                    💾 Save Settings
                </button>
            </div>
        </form>

        {{-- Status Indicator --}}
        <div class="mt-6 text-center">
            @if($settings->is_enabled)
                <div class="inline-flex items-center space-x-2 bg-green-500 text-white px-6 py-3 rounded-full">
                    <span class="w-3 h-3 bg-white rounded-full animate-pulse"></span>
                    <span class="font-semibold">Notice Bar is LIVE</span>
                </div>
            @else
                <div class="inline-flex items-center space-x-2 bg-red-500 text-white px-6 py-3 rounded-full">
                    <span class="w-3 h-3 bg-white rounded-full opacity-50"></span>
                    <span class="font-semibold">Notice Bar is DISABLED</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Notices List --}}
    <div class="notice-card">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">📋 All Notices</h2>
                <span class="bg-white/20 px-4 py-2 rounded-full text-sm font-semibold">
                    {{ $notices->count() }} Total
                </span>
            </div>
        </div>

        @if($notices->count() > 0)
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($notices as $notice)
                        <div class="bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 space-y-3">
                                    {{-- Title & Priority --}}
                                    <div class="flex items-center space-x-4">
                                        <h3 class="text-lg font-bold text-gray-800">{{ $notice->title }}</h3>
                                        <span class="priority-badge status-badge
                                            {{ $notice->priority >= 80 ? 'bg-gradient-to-r from-red-500 to-pink-500 text-white' : 
                                               ($notice->priority >= 50 ? 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white' : 'bg-gradient-to-r from-green-400 to-blue-500 text-white') }}">
                                            @if($notice->priority >= 80)
                                                🔥 Critical
                                            @elseif($notice->priority >= 50)
                                                ⚡ Important
                                            @else
                                                📝 Normal
                                            @endif
                                        </span>
                                        <span class="status-badge
                                            {{ $notice->is_active ? 'bg-gradient-to-r from-green-400 to-green-600 text-white' : 'bg-gradient-to-r from-red-400 to-red-600 text-white' }}">
                                            {{ $notice->is_active ? '🟢 Live' : '⭕ Paused' }}
                                        </span>
                                    </div>
                                    
                                    {{-- Content Preview --}}
                                    <div class="bg-gray-100 p-4 rounded-lg">
                                        <p class="text-gray-700 text-sm">{{ Str::limit($notice->content, 100) }}</p>
                                    </div>
                                    
                                    {{-- Dates --}}
                                    <div class="flex space-x-6 text-sm text-gray-500">
                                        <span>📅 Start: {{ $notice->start_date ? $notice->start_date->format('M d, Y') : 'No limit' }}</span>
                                        <span>📅 End: {{ $notice->end_date ? $notice->end_date->format('M d, Y') : 'No limit' }}</span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex space-x-3 ml-6">
                                    <a href="{{ route('admin.notices.edit', $notice) }}" 
                                       class="action-btn edit-btn text-decoration-none">
                                        ✏️ Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.notices.destroy', $notice) }}" 
                                          method="POST" class="inline-block"
                                          onsubmit="return confirm('🗑️ Delete this notice permanently?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="text-6xl mb-4">📢</div>
                <h3 class="text-2xl font-bold mb-4">No notices yet!</h3>
                <p class="text-lg mb-8 opacity-90">Create your first scrolling notice to engage your visitors</p>
                <a href="{{ route('admin.notices.create') }}" 
                   class="inline-block bg-white text-purple-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-colors duration-300 shadow-lg text-decoration-none">
                    🚀 Create First Notice
                </a>
            </div>
        @endif
    </div>
</div>

<script>
// Toggle Switch Animation
document.addEventListener('DOMContentLoaded', function() {
    const toggleInput = document.getElementById('is_enabled');
    const toggleBg = document.querySelector('.toggle-bg');
    const toggleDot = document.querySelector('.toggle-dot');
    
    function updateToggle() {
        if (toggleInput.checked) {
            toggleBg.classList.add('bg-green-500');
            toggleBg.classList.remove('bg-white/20');
            toggleDot.style.transform = 'translateX(24px)';
        } else {
            toggleBg.classList.remove('bg-green-500');
            toggleBg.classList.add('bg-white/20');
            toggleDot.style.transform = 'translateX(0)';
        }
    }
    
    // Initialize
    updateToggle();
    
    // Handle changes
    toggleInput.addEventListener('change', updateToggle);
    
    // Handle color input changes
    document.querySelectorAll('input[type="color"]').forEach(colorInput => {
        colorInput.addEventListener('change', function() {
            const textInput = this.parentNode.querySelector('input[type="text"]');
            textInput.value = this.value;
        });
    });
});
</script>
@endsection
