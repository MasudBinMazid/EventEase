@extends('admin.layout')
@section('title','Create Event')

@section('content')
<style>
  :root{
    /* Standard, accessible light palette */
    --bg:#ffffff;
    --surface:#ffffff;
    --surface-2:#f9fafb;
    --text:#111827;
    --muted:#6b7280;
    --border:#e5e7eb;
    --ring: rgba(37,99,235,.35);

    --primary:#2563eb;       /* blue-600 */
    --primary-600:#1d4ed8;
    --danger:#dc2626;

    --radius:14px;
    --shadow:0 10px 30px rgba(0,0,0,.06);
    --shadow-sm:0 3px 10px rgba(0,0,0,.05);
  }

  .grid{ padding: clamp(12px,1.8vw,20px); background: var(--bg); color: var(--text); }
  .card{
    background: var(--surface); border:1px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow);
    padding: clamp(16px,2vw,22px);
  }

  h2{ margin: 0 0 6px; font-weight: 800; letter-spacing:.2px; }
  .lead{ margin: 0 0 14px; color: var(--muted); }

  /* form */
  form{ display:grid; gap: 14px; }
  .form-row{
    display:grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 12px;
  }
  @media (max-width: 860px){
    .form-row{ grid-template-columns: 1fr; }
  }

  .field{ display:grid; gap: 6px; }
  .label{
    font-weight: 700; font-size: .95rem; display:inline-flex; align-items:center; gap:8px;
  }
  .req{ color: var(--danger); margin-left: 2px; }
  .hint{ color: var(--muted); font-size: .88rem; }

  .input{
    width: 100%; padding: 10px 12px; border-radius: 12px;
    border: 1px solid var(--border); background: var(--surface-2);
    color: var(--text); box-shadow: var(--shadow-sm);
    transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
  }
  textarea.input{ resize: vertical; min-height: 130px; }
  .input:focus{ outline: 3px solid var(--ring); outline-offset: 2px; background: #fff; }
  .input::file-selector-button{
    border: 1px solid var(--border); background: #fff; padding: 8px 10px; border-radius: 10px; margin-right: 10px; cursor: pointer;
  }

  /* messages */
  .errors{
    border:1px solid #fecaca; background:#fff1f2; color:#7f1d1d;
    padding:12px 14px; border-radius:12px; font-weight: 700;
  }
  .error{ color:#b91c1c; font-size:.88rem; }

  /* actions */
  .actions{ display:flex; gap:10px; flex-wrap:wrap; margin-top: 4px; }
  .btn{
    appearance:none; border:1px solid var(--border); border-radius: 999px;
    padding: 10px 14px; font-weight: 700; text-decoration: none; cursor: pointer;
    display:inline-flex; align-items:center; gap:.5rem; transition:.18s ease;
    background:#fff; color:var(--text); box-shadow: var(--shadow-sm);
  }
  .btn:hover{ transform: translateY(-1px); box-shadow: var(--shadow); }
  .btn:focus-visible{ outline: 3px solid var(--ring); outline-offset: 2px; }

  .btn-primary{ background: var(--primary); color:#fff; border-color: transparent; }
  .btn-primary:hover{ background: var(--primary-600); }
  .btn-ghost{ background: var(--surface-2); }

  /* compact helper layout for ID/date icons if you add them later */
  .inline-help{ display:flex; align-items:center; gap:8px; color: var(--muted); font-size:.9rem; }
  .ico{ display:inline-flex; }
</style>

<div class="grid">
  <div class="col-12">
    <div class="card">
      <h2>Create Event</h2>
      <p class="lead">Fill in the details below to publish a new event.</p>

      {{-- Validation errors (UI only, no logic change) --}}
      @if ($errors->any())
        <div class="errors">
          Please review the highlighted fields.
        </div>
      @endif

      <form method="post" enctype="multipart/form-data" action="{{ route('admin.events.store') }}">
        @csrf

        <div class="field">
          <label class="label">Title <span class="req">*</span></label>
          <input name="title" required class="input" value="{{ old('title') }}">
          @error('title') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label class="label">Description</label>
          <textarea name="description" rows="6" class="input">{{ old('description') }}</textarea>
          @error('description') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="field">
            <label class="label">Location</label>
            <input name="location" class="input" value="{{ old('location') }}">
            @error('location') <div class="error">{{ $message }}</div> @enderror
          </div>
          <div class="field">
            <label class="label">Venue</label>
            <input name="venue" class="input" value="{{ old('venue') }}">
            @error('venue') <div class="error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label class="label">Starts at <span class="req">*</span></label>
            <input type="datetime-local" name="starts_at" required class="input" value="{{ old('starts_at') }}">
            <div class="hint">Use your local timezone.</div>
            @error('starts_at') <div class="error">{{ $message }}</div> @enderror
          </div>
          <div class="field">
            <label class="label">Ends at</label>
            <input type="datetime-local" name="ends_at" class="input" value="{{ old('ends_at') }}">
            <div class="hint">Leave empty if it’s a single-session event.</div>
            @error('ends_at') <div class="error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label class="label">Capacity</label>
            <input type="number" name="capacity" min="0" class="input" value="{{ old('capacity') }}">
            @error('capacity') <div class="error">{{ $message }}</div> @enderror
          </div>
          <div class="field">
            <label class="label">Price</label>
            <input type="number" step="0.01" name="price" min="0" class="input" value="{{ old('price', 0) }}">
            @error('price') <div class="error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="field">
          <label class="label">Purchase option</label>
          <select name="purchase_option" class="input">
            <option value="both" {{ old('purchase_option','both')=='both'?'selected':'' }}>Both</option>
            <option value="pay_now" {{ old('purchase_option')=='pay_now'?'selected':'' }}>Pay now</option>
            <option value="pay_later" {{ old('purchase_option')=='pay_later'?'selected':'' }}>Pay later</option>
          </select>
          @error('purchase_option') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label class="label">Banner</label>
          <input type="file" name="banner" accept="image/*" class="input">
          <div class="hint">Recommended: 1600×900 (JPG or PNG, &lt; 2MB).</div>
          @error('banner') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label class="label" style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" name="featured_on_home" value="1" {{ old('featured_on_home') ? 'checked' : '' }}>
            Feature on Home Page
          </label>
          <div class="hint">Check this to display the event in the "Upcoming Events" section on the home page.</div>
          @error('featured_on_home') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="actions">
          <button type="submit" class="btn btn-primary">
            <span class="ico" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M4 12h16M12 4v16" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
            </span>
            Save
          </button>
          <a href="{{ route('admin.events.index') }}" class="btn btn-ghost">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
