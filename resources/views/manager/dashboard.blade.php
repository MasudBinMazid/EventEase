@extends('manager.layout')

@section('title', 'Manager Dashboard')

@section('content')
<div class="admin-page">
    <!-- Page Header -->
    <div class="admin-header">
        <div>
            <h1 class="admin-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Manager Dashboard
            </h1>
            <p class="admin-subtitle">Overview and management tools with read-only access to user administration</p>
        </div>
        <div class="admin-actions">
            <span class="badge badge-info">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                {{ auth()->user()->name }} - Manager
            </span>
        </div>
    </div>

    <!-- Manager Access Notice -->
    <div class="alert alert-info">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 0.5rem; flex-shrink: 0;">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
        </svg>
        <strong>Manager Access:</strong> You have full access to all admin functions except user deletion and role management. Contact an admin for user role changes.
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['total_events']) }}</div>
            <div class="stat-label">Total Events</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['total_tickets']) }}</div>
            <div class="stat-label">Total Tickets</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">৳{{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['total_blogs']) }}</div>
            <div class="stat-label">Total Blogs</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['total_messages']) }}</div>
            <div class="stat-label">Messages</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['pending_requests']) }}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['recent_users']) }}</div>
            <div class="stat-label">New Users (7 days)</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <h3 class="card-title">Quick Actions</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="{{ route('manager.users.index') }}" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Manage Users
                </a>
                <a href="{{ route('admin.events.index') }}" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Manage Events
                </a>
                <a href="{{ route('admin.requests.index') }}" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10,9 9,9 8,9"/>
                    </svg>
                    Event Requests
                </a>
                <a href="{{ route('admin.sales.index') }}" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    Sales Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <!-- Recent Tickets -->
        <div class="admin-card">
            <div class="card-header">
                <h3 class="card-title">Recent Tickets</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($recent_tickets->isEmpty())
                    <div style="padding: 2rem; text-align: center; color: var(--text-light);">
                        <p>No recent tickets found.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>User</th>
                                    <th>Event</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_tickets as $ticket)
                                <tr>
                                    <td>
                                        <span style="font-weight: 600; color: var(--primary);">#{{ $ticket->id }}</span>
                                    </td>
                                    <td>{{ $ticket->user->name }}</td>
                                    <td>{{ $ticket->event->title }}</td>
                                    <td>৳{{ number_format($ticket->total_amount, 2) }}</td>
                                    <td>
                                        @if($ticket->payment_status === 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Event Requests -->
        <div class="admin-card">
            <div class="card-header">
                <h3 class="card-title">Recent Event Requests</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($recent_requests->isEmpty())
                    <div style="padding: 2rem; text-align: center; color: var(--text-light);">
                        <p>No recent event requests found.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Request</th>
                                    <th>User</th>
                                    <th>Event Title</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_requests as $request)
                                <tr>
                                    <td>
                                        <span style="font-weight: 600; color: var(--primary);">#{{ $request->id }}</span>
                                    </td>
                                    <td>{{ $request->creator->name ?? 'N/A' }}</td>
                                    <td>{{ $request->title }}</td>
                                    <td>
                                        @if($request->status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($request->status === 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('M j') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="admin-card">
        <div class="card-header">
            <h3 class="card-title">Recent Messages</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($recent_messages->isEmpty())
                <div style="padding: 2rem; text-align: center; color: var(--text-light);">
                    <p>No recent messages found.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_messages as $message)
                            <tr>
                                <td>
                                    <div>
                                        <div style="font-weight: 600;">{{ $message->name }}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-light);">{{ $message->email }}</div>
                                    </div>
                                </td>
                                <td>{{ $message->subject }}</td>
                                <td>{{ Str::limit($message->message, 50) }}</td>
                                <td>{{ $message->created_at->format('M j, Y') }}</td>
                                <td>
                                    @if($message->replied_at)
                                        <span class="badge badge-success">Replied</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    font-weight: 500;
    display: flex;
    align-items: flex-start;
}

.alert-info {
    background: #dbeafe;
    border: 1px solid #bfdbfe;
    color: #1d4ed8;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}
</style>
@endsection
