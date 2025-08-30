@extends('admin.layout')
@section('title','Events')

@section('content')
<style>
  :root{
    /* Standard, accessible light palette */
    --bg: #ffffff;
    --surface: #f9fafb;        /* card/table background */
    --surface-2: #f3f4f6;      /* subtle stripes */
    --text: #111827;           /* near-black text */
    --muted: #6b7280;          /* secondary text */
    --border: #e5e7eb;         /* soft gray borders */
    --ring: rgba(37, 99, 235, .35);

    --primary: #2563eb;        /* blue-600 */
    --primary-600: #1d4ed8;    /* hover */
    --danger: #dc2626;         /* red-600 */
    --danger-600: #b91c1c;
    --success: #16a34a;

    --radius: 14px;
    --shadow: 0 10px 30px rgba(0,0,0,.06);
    --shadow-sm: 0 2px 10px rgba(0,0,0,.05);
  }

  /* page grid baseline (keeps your outer structure) */
  .grid{ padding: clamp(12px, 1.8vw, 20px); background: var(--bg); color: var(--text); }
  .card{
    background: #fff; border: 1px solid var(--border); border-radius: var(--radius);
    box-shadow: var(--shadow); padding: clamp(16px, 2vw, 22px);
  }

  /* header row */
  .page-head{
    display:flex; align-items:flex-start; justify-content:space-between; gap: 12px; flex-wrap: wrap;
    margin-bottom: 16px;
  }
  .page-title{ margin: 0; font-weight: 800; letter-spacing: .2px; }
  .help{ margin: 4px 0 0; color: var(--muted); }

  /* actions */
  .btn{
    appearance: none; border: 1px solid var(--border); border-radius: 999px;
    padding: 10px 14px; font-weight: 700; text-decoration: none; cursor: pointer;
    display:inline-flex; align-items:center; gap:.5rem; transition: .18s ease;
    background: #fff; color: var(--text); box-shadow: var(--shadow-sm);
  }
  .btn:hover{ transform: translateY(-1px); box-shadow: var(--shadow); }
  .btn:focus-visible{ outline: 3px solid var(--ring); outline-offset: 2px; }

  .btn-primary{ background: var(--primary); color: #fff; border-color: transparent; }
  .btn-primary:hover{ background: var(--primary-600); }
  .btn-ghost{ background: var(--surface); }
  .btn-sm{ padding: 8px 10px; font-weight: 700; border-radius: 10px; }
  .btn-danger{ background: #fee2e2; color: var(--danger); border-color: #fecaca; }
  .btn-danger:hover{ background: #fecaca; color: var(--danger-600); }

  /* table wrapper */
  .table-wrap{
    margin-top: 12px; border: 1px solid var(--border); border-radius: 12px; overflow: hidden;
    background: var(--surface);
  }
  table{ width: 100%; border-collapse: separate; border-spacing: 0; font-size: .96rem; }
  thead th{
    text-align: left; padding: 12px 14px; background: #fff; border-bottom: 1px solid var(--border);
    font-weight: 800; color: var(--muted);
  }
  tbody td{ padding: 12px 14px; border-bottom: 1px solid var(--border); }
  tbody tr:nth-child(even){ background: var(--surface-2); }
  tbody tr:hover{ background: #fff; }

  .col-id{ width: 70px; color: var(--muted); }
  .col-actions{ width: 220px; text-align: right; }

  .toolbar{
    display:flex; align-items:center; gap: 8px; flex-wrap: wrap; margin-top: 12px;
  }
  .input{
    background: #fff; border:1px solid var(--border); border-radius: 10px; padding: 10px 12px;
    box-shadow: var(--shadow-sm); color: var(--text); min-width: 200px;
  }

  /* empty state */
  .empty{
    margin-top: 16px; padding: 22px; border: 1px dashed var(--border); border-radius: 12px;
    background: var(--surface); color: var(--muted); text-align: center;
  }

  /* responsive stack for small screens */
  @media (max-width: 860px){
    .col-actions{ width: 1%; }
    .table-wrap{ border-radius: 10px; overflow: hidden; }
    table, thead, tbody, th, td, tr{ display: block; }
    thead{ display: none; }
    tbody tr{ border-bottom: 1px solid var(--border); padding: 8px 10px; }
    tbody td{
      border: none; display: grid; grid-template-columns: 120px 1fr; gap: 8px;
      padding: 8px 0;
    }
    tbody td::before{
      content: attr(data-label);
      font-weight: 700; color: var(--muted);
    }
    .row-actions{ margin-top: 8px; display:flex; gap: 8px; justify-content: flex-start; }
  }

  /* subtle “caps” badge style for capacity */
  .caps-badge{
    display:inline-flex; align-items:center; gap:6px; padding: 6px 10px; border-radius: 999px;
    background: #eef2ff; color: #3730a3; border: 1px solid #e0e7ff; font-weight: 700; font-size: .85rem;
  }

  /* “when” inline chip */
  .when{
    display:inline-flex; align-items:center; gap:6px; padding: 6px 10px; border-radius: 999px;
    background: #ecfeff; color: #155e75; border: 1px solid #cffafe; font-weight: 700; font-size: .85rem;
  }

  /* location pill */
  .loc{
    display:inline-flex; align-items:center; gap:6px; padding: 6px 10px; border-radius: 999px;
    background: #fef9c3; color: #854d0e; border:1px solid #fde68a; font-weight: 700; font-size: .85rem;
  }

  /* icon baseline */
  .ico{ display:inline-flex; }
</style>

<div class="grid">
  <div class="col-12">
    <div class="card">
      <!-- Header -->
      <div class="page-head">
        <div>
          <h2 class="page-title">Events</h2>
          <p class="help">Create, edit, and manage events. Events marked as "Featured" will appear in the "Upcoming Events" section on the home page.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.events.create') }}">
          <span class="ico" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
          </span>
          New Event
        </a>
      </div>

              <!-- Optional UI helpers (no logic change) -->

      @if($events->isEmpty())
        <div class="empty">
          No events yet. Click <strong>New Event</strong> to create one.
        </div>
      @else
        <div class="table-wrap" role="region" aria-label="Events table">
          <table>
            <thead>
              <tr>
                <th class="col-id">ID</th>
                <th>Title</th>
                <th>When</th>
                <th>Location</th>
                <th>Cap.</th>
                <th title="Featured on Home Page">Home</th>
                <th class="col-actions" style="text-align:right">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($events as $e)
              <tr>
                <td class="col-id" data-label="ID">#{{ $e->id }}</td>
                <td data-label="Title">
                  <strong>{{ $e->title }}</strong>
                </td>
                <td data-label="When">
                  <span class="when">
                    <svg class="ico" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 2v4M17 2v4M3 9h18M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5A2 2 0 0 0 3 7v12a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    {{ $e->starts_at }} — {{ $e->ends_at }}
                  </span>
                </td>
                <td data-label="Location">
                  <span class="loc">
                    <svg class="ico" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s7-4.35 7-11a7 7 0 0 0-14 0c0 6.65 7 11 7 11Z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.6"/></svg>
                    {{ $e->location }}
                  </span>
                </td>
                <td data-label="Cap.">
                  <span class="caps-badge">
                    <svg class="ico" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M5 20h14a1 1 0 0 0 1-1v-8H4v8a1 1 0 0 0 1 1Z" stroke="currentColor" stroke-width="1.6"/></svg>
                    {{ $e->capacity }}
                  </span>
                </td>
                <td data-label="Home">
                  @if($e->featured_on_home)
                    <span style="color: #16a34a; font-weight: 700;">✓ Featured</span>
                  @else
                    <span style="color: #6b7280;">—</span>
                  @endif
                </td>
                <td class="col-actions" data-label="Actions" style="text-align:right">
                  <div class="row-actions" style="display:inline-flex; gap:8px;">
                    <a class="btn btn-sm" href="{{ route('admin.events.edit',$e) }}">Edit</a>
                    <form style="display:inline" method="POST" action="{{ route('admin.events.destroy',$e) }}" onsubmit="return confirm('Delete event?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif

      <div style="margin-top:14px">
        <a href="{{ route('admin.index') }}" class="btn btn-ghost">
          ← Back
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
