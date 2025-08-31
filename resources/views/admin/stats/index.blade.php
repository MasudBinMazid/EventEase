@extends('admin.layout')
@section('title','Statistics')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"
        integrity="sha256-+i2m6w6s3pX8r0P9pQJwTNC3aSjcUjL/O0mH9M9m+eI=" crossorigin="anonymous"></script>
@endpush

@section('content')

<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Event Statistics</h1>
      <p class="admin-subtitle">Comprehensive analytics and performance metrics</p>
    </div>
    <div class="admin-actions">
      <a href="{{ route('admin.sales.export', ['group'=>'event','status'=>request('status','paid')]) }}" class="btn btn-success">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7,10 12,15 17,10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Export Data
      </a>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number">{{ array_sum($created->toArray()) }}</div>
      <div class="stat-label">Total Tickets Created</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ array_sum($sold->toArray()) }}</div>
      <div class="stat-label">Total Tickets Sold</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $events->count() }}</div>
      <div class="stat-label">Active Events</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ array_sum($created->toArray()) > 0 ? round((array_sum($sold->toArray()) / array_sum($created->toArray())) * 100, 1) : 0 }}%</div>
      <div class="stat-label">Sales Conversion</div>
    </div>
  </div>

  @if (!$events->count())
    <!-- Empty State -->
    <div class="admin-card">
      <div class="card-body" style="padding: 3rem; text-align: center;">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
          <line x1="18" y1="20" x2="18" y2="10"/>
          <line x1="12" y1="20" x2="12" y2="4"/>
          <line x1="6" y1="20" x2="6" y2="14"/>
        </svg>
        <p style="color: var(--text-light); margin: 0;"><strong>No event data available</strong></p>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">Create some events to view statistics.</p>
      </div>
    </div>
  @else
    <!-- Chart Section -->
    <div class="admin-card">
      <div class="card-header">
        <h3 class="card-title">Tickets Created vs. Sold</h3>
        <p class="card-subtitle">Performance comparison across all events</p>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="barChart" aria-label="Tickets created vs sold per event" role="img"></canvas>
        </div>
      </div>
    </div>

    <!-- Analytics Grid -->
    <div class="analytics-grid">
      <!-- Pie Chart -->
      <div class="admin-card">
        <div class="card-header">
          <h3 class="card-title">Sales Distribution</h3>
          <p class="card-subtitle">Top selling events breakdown</p>
        </div>
        <div class="card-body">
          <div class="chart-container" style="height: 300px;">
            <canvas id="pieChart" aria-label="Top selling events pie chart" role="img"></canvas>
          </div>
        </div>
      </div>

      <!-- Top Events Table -->
      <div class="admin-card">
        <div class="card-header">
          <h3 class="card-title">Top Performing Events</h3>
          <p class="card-subtitle">Ranked by tickets sold</p>
        </div>
        <div class="card-body" style="padding: 0;">
          <div style="overflow-x: auto;">
            <table class="admin-table">
              <thead>
                <tr>
                  <th style="width: 60px;">Rank</th>
                  <th>Event Name</th>
                  <th style="text-align: right;">Sold</th>
                  <th style="text-align: right;">Created</th>
                  <th style="text-align: right;">Buyers</th>
                  <th style="text-align: right;">Rate</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($top as $i => $r)
                  <tr>
                    <td>
                      <div style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%; background: var(--primary); color: white; font-weight: 600; font-size: 0.8rem;">
                        {{ $i + 1 }}
                      </div>
                    </td>
                    <td>
                      <div style="font-weight: 600; color: var(--text);">{{ $r->title }}</div>
                    </td>
                    <td style="text-align: right;">
                      <span class="badge badge-success">{{ (int)$r->tickets_sold }}</span>
                    </td>
                    <td style="text-align: right;">
                      <span class="badge badge-info">{{ (int)$r->tickets_created }}</span>
                    </td>
                    <td style="text-align: right;">
                      <span class="badge badge-outline">{{ (int)$r->unique_buyers }}</span>
                    </td>
                    <td style="text-align: right;">
                      @php
                        $rate = $r->tickets_created > 0 ? round(($r->tickets_sold / $r->tickets_created) * 100, 1) : 0;
                      @endphp
                      <span style="font-weight: 600; color: {{ $rate >= 80 ? 'var(--success)' : ($rate >= 50 ? 'var(--warning)' : 'var(--danger)') }};">
                        {{ $rate }}%
                      </span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  @endif

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

<style>
  .analytics-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
  }
  
  .chart-container {
    position: relative;
    width: 100%;
    height: 400px;
  }
  
  @media (max-width: 1024px) {
    .analytics-grid {
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }
    
    .chart-container {
      height: 300px;
    }
  }
</style>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const hasData = {{ (bool) $events->count() ? 'true': 'false' }};
    if (!hasData || !window.Chart) return;

    // Color palette
    const COLORS = {
      primary: '#3b82f6',
      success: '#10b981',
      warning: '#f59e0b',
      danger: '#ef4444',
      info: '#06b6d4',
      purple: '#8b5cf6',
      rose: '#f43f5e',
      emerald: '#059669',
      orange: '#ea580c',
      indigo: '#6366f1',
      primaryLight: '#93c5fd',
      successLight: '#6ee7b7',
      border: '#e5e7eb',
      text: '#111827',
      muted: '#6b7280'
    };

    const labels = @json($labels, JSON_UNESCAPED_UNICODE);
    const created = @json($created);
    const sold = @json($sold);

    // Truncate long labels
    const truncateLabel = (str, maxLen = 20) => {
      return str && str.length > maxLen ? str.slice(0, maxLen - 1) + '…' : str;
    };

    const barLabels = labels.map(label => truncateLabel(label));

    // Bar Chart Configuration
    const barCtx = document.getElementById('barChart');
    if (barCtx) {
      new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: barLabels,
          datasets: [
            {
              label: 'Tickets Created',
              data: created,
              backgroundColor: COLORS.primaryLight,
              borderColor: COLORS.primary,
              borderWidth: 2,
              borderRadius: 8,
              maxBarThickness: 50
            },
            {
              label: 'Tickets Sold',
              data: sold,
              backgroundColor: COLORS.successLight,
              borderColor: COLORS.success,
              borderWidth: 2,
              borderRadius: 8,
              maxBarThickness: 50
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false,
          },
          scales: {
            x: {
              grid: {
                display: false,
                borderColor: COLORS.border
              },
              ticks: {
                color: COLORS.text,
                font: {
                  size: 12,
                  weight: '500'
                }
              }
            },
            y: {
              beginAtZero: true,
              grid: {
                color: COLORS.border + '80',
                borderColor: COLORS.border
              },
              ticks: {
                precision: 0,
                color: COLORS.text,
                font: {
                  size: 12
                }
              }
            }
          },
          plugins: {
            legend: {
              position: 'top',
              align: 'end',
              labels: {
                color: COLORS.text,
                usePointStyle: true,
                pointStyle: 'circle',
                font: {
                  size: 13,
                  weight: '600'
                },
                padding: 20
              }
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.8)',
              titleColor: '#fff',
              bodyColor: '#fff',
              cornerRadius: 8,
              displayColors: true,
              callbacks: {
                title: function(context) {
                  return labels[context[0].dataIndex] || '';
                },
                label: function(context) {
                  return `${context.dataset.label}: ${context.parsed.y}`;
                }
              }
            }
          }
        }
      });
    }

    // Pie Chart Configuration
    const topData = @json($top->pluck('tickets_sold')->map(fn($v) => (int)$v));
    const topLabels = @json($top->pluck('title'), JSON_UNESCAPED_UNICODE);

    const pieCtx = document.getElementById('pieChart');
    if (pieCtx && topData.length > 0) {
      const pieColors = [
        COLORS.primary,
        COLORS.success,
        COLORS.warning,
        COLORS.purple,
        COLORS.rose,
        COLORS.info,
        COLORS.emerald,
        COLORS.orange,
        COLORS.indigo
      ];

      new Chart(pieCtx, {
        type: 'doughnut',
        data: {
          labels: topLabels.map(label => truncateLabel(label, 15)),
          datasets: [{
            data: topData,
            backgroundColor: pieColors.slice(0, topData.length),
            borderColor: '#ffffff',
            borderWidth: 3,
            hoverBorderWidth: 5
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '40%',
          plugins: {
            legend: {
              position: 'right',
              labels: {
                color: COLORS.text,
                usePointStyle: true,
                pointStyle: 'circle',
                font: {
                  size: 12,
                  weight: '500'
                },
                padding: 15,
                generateLabels: function(chart) {
                  const data = chart.data;
                  if (data.labels.length && data.datasets.length) {
                    return data.labels.map((label, i) => ({
                      text: `${label} (${data.datasets[0].data[i]})`,
                      fillStyle: data.datasets[0].backgroundColor[i],
                      hidden: false,
                      index: i
                    }));
                  }
                  return [];
                }
              }
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.8)',
              titleColor: '#fff',
              bodyColor: '#fff',
              cornerRadius: 8,
              callbacks: {
                title: function(context) {
                  return topLabels[context[0].dataIndex] || '';
                },
                label: function(context) {
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = ((context.parsed * 100) / total).toFixed(1);
                  return `${context.parsed} tickets (${percentage}%)`;
                }
              }
            }
          }
        }
      });
    }
  });
</script>
@endpush
@endsection
