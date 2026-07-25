@extends('layouts.app')

@section('title', 'Analytics - ' . config('app.name'))

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">HAMORA</a></li>
                    <li class="breadcrumb-item active">Analytics</li>
                </ol>
            </div>
            <h4 class="page-title">Analytics</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-9">
                        <p class="text-dark mb-0 fw-semibold">Page Views</p>
                        <h3 class="my-1 font-20 fw-bold">1,284,521</h3>
                        <p class="mb-0 text-truncate text-muted"><span class="text-success">+24.5%</span></p>
                    </div>
                    <div class="col-3 align-self-center">
                        <div class="d-flex justify-content-center align-items-center thumb-md bg-light-alt rounded-circle mx-auto">
                            <i class="ti ti-eye font-24 align-self-center text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-9">
                        <p class="text-dark mb-0 fw-semibold">Unique Visitors</p>
                        <h3 class="my-1 font-20 fw-bold">452,892</h3>
                        <p class="mb-0 text-truncate text-muted"><span class="text-success">+18.3%</span></p>
                    </div>
                    <div class="col-3 align-self-center">
                        <div class="d-flex justify-content-center align-items-center thumb-md bg-soft-warning rounded-circle mx-auto">
                            <i class="ti ti-users font-24 align-self-center text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-9">
                        <p class="text-dark mb-0 fw-semibold">Bounce Rate</p>
                        <h3 class="my-1 font-20 fw-bold">32.8%</h3>
                        <p class="mb-0 text-truncate text-muted"><span class="text-danger">+5.2%</span></p>
                    </div>
                    <div class="col-3 align-self-center">
                        <div class="d-flex justify-content-center align-items-center thumb-md bg-soft-danger rounded-circle mx-auto">
                            <i class="ti ti-trending-up font-24 align-self-center text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-9">
                        <p class="text-dark mb-0 fw-semibold">Avg. Session</p>
                        <h3 class="my-1 font-20 fw-bold">4:32</h3>
                        <p class="mb-0 text-truncate text-muted"><span class="text-success">+12.1%</span></p>
                    </div>
                    <div class="col-3 align-self-center">
                        <div class="d-flex justify-content-center align-items-center thumb-md bg-soft-success rounded-circle mx-auto">
                            <i class="ti ti-clock font-24 align-self-center text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-9">
        <div class="card chart-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Traffic Overview</h4>
                        <p class="text-muted mb-0">Daily visitors and page views</p>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary">7 Days</button>
                        <button class="btn btn-sm btn-primary">30 Days</button>
                        <button class="btn btn-sm btn-outline-secondary">90 Days</button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <div class="chart-container">
                        <div class="chart-y-axis">
                            <span class="y-value">50K</span>
                            <span class="y-value">40K</span>
                            <span class="y-value">30K</span>
                            <span class="y-value">20K</span>
                            <span class="y-value">10K</span>
                            <span class="y-value">0</span>
                        </div>
                        <div class="chart-placeholder">
                            <div class="chart-bar-group"><div class="chart-bar bar-emerald" style="height: 80px;"></div><span class="chart-label">1</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-emerald" style="height: 95px;"></div><span class="chart-label">2</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-emerald" style="height: 70px;"></div><span class="chart-label">3</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-emerald" style="height: 110px;"></div><span class="chart-label">4</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-emerald" style="height: 130px;"></div><span class="chart-label">5</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-gold" style="height: 145px;"></div><span class="chart-label">6</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-gold" style="height: 120px;"></div><span class="chart-label">7</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-gold" style="height: 100px;"></div><span class="chart-label">8</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-gold" style="height: 135px;"></div><span class="chart-label">9</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-gold" style="height: 155px;"></div><span class="chart-label">10</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-coral" style="height: 140px;"></div><span class="chart-label">11</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-coral" style="height: 125px;"></div><span class="chart-label">12</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-coral" style="height: 160px;"></div><span class="chart-label">13</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-coral" style="height: 175px;"></div><span class="chart-label">14</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-coral" style="height: 150px;"></div><span class="chart-label">15</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-teal" style="height: 165px;"></div><span class="chart-label">16</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-teal" style="height: 145px;"></div><span class="chart-label">17</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-teal" style="height: 130px;"></div><span class="chart-label">18</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-teal" style="height: 155px;"></div><span class="chart-label">19</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-teal" style="height: 180px;"></div><span class="chart-label">20</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-amber" style="height: 170px;"></div><span class="chart-label">21</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-amber" style="height: 160px;"></div><span class="chart-label">22</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-amber" style="height: 185px;"></div><span class="chart-label">23</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-amber" style="height: 175px;"></div><span class="chart-label">24</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-amber" style="height: 165px;"></div><span class="chart-label">25</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-emerald" style="height: 190px;"></div><span class="chart-label">26</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-emerald" style="height: 175px;"></div><span class="chart-label">27</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-emerald" style="height: 195px;"></div><span class="chart-label">28</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-emerald" style="height: 185px;"></div><span class="chart-label">29</span></div>
                            <div class="chart-bar-group"><div class="chart-bar bar-emerald" style="height: 200px;"></div><span class="chart-label">30</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card activity-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Top Pages</h4>
                        <p class="text-muted mb-0">Most visited pages</p>
                    </div>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-avatar" style="background: linear-gradient(135deg, rgba(85, 110, 229, 0.7), rgba(85, 110, 229, 1));">1</div>
                        <div class="activity-content">
                            <p class="activity-text"><strong>/dashboard</strong></p>
                            <span class="activity-time">45,234 views</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background: linear-gradient(135deg, rgba(234, 179, 8, 0.7), rgba(234, 179, 8, 1));">2</div>
                        <div class="activity-content">
                            <p class="activity-text"><strong>/products</strong></p>
                            <span class="activity-time">32,891 views</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background: linear-gradient(135deg, rgba(220, 38, 38, 0.7), rgba(234, 179, 8, 1));">3</div>
                        <div class="activity-content">
                            <p class="activity-text"><strong>/pricing</strong></p>
                            <span class="activity-time">28,456 views</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.7), rgba(34, 197, 94, 1));">4</div>
                        <div class="activity-content">
                            <p class="activity-text"><strong>/about</strong></p>
                            <span class="activity-time">19,234 views</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background: linear-gradient(135deg, rgba(234, 179, 8, 0.7), rgba(234, 179, 8, 1));">5</div>
                        <div class="activity-content">
                            <p class="activity-text"><strong>/contact</strong></p>
                            <span class="activity-time">12,567 views</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Devices</h4>
                        <p class="text-muted mb-0">Traffic by device type</p>
                    </div>
                </div>
                <div class="donut-container">
                    <div class="donut-chart">
                        <svg width="140" height="140" viewBox="0 0 140 140">
                            <circle class="donut-bg" cx="70" cy="70" r="54"/>
                            <circle class="donut-segment" cx="70" cy="70" r="54" stroke="rgba(85, 110, 229, 0.7)" stroke-dasharray="186.6 339.3" stroke-dashoffset="0"/>
                            <circle class="donut-segment" cx="70" cy="70" r="54" stroke="rgba(234, 179, 8, 0.7)" stroke-dasharray="118.8 339.3" stroke-dashoffset="-186.6"/>
                            <circle class="donut-segment" cx="70" cy="70" r="54" stroke="rgba(220, 38, 38, 0.7)" stroke-dasharray="33.9 339.3" stroke-dashoffset="-305.4"/>
                        </svg>
                        <div class="donut-center">
                            <div class="donut-value">100%</div>
                            <div class="donut-label">Total</div>
                        </div>
                    </div>
                    <div class="donut-legend">
                        <div class="legend-item"><span class="legend-color" style="background: rgba(85, 110, 229, 0.7);"></span><span>Mobile (55%)</span></div>
                        <div class="legend-item"><span class="legend-color" style="background: rgba(234, 179, 8, 0.7);"></span><span>Desktop (35%)</span></div>
                        <div class="legend-item"><span class="legend-color" style="background: rgba(220, 38, 38, 0.7);"></span><span>Tablet (10%)</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card progress-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Browsers</h4>
                        <p class="text-muted mb-0">Traffic by browser</p>
                    </div>
                </div>
                <div class="progress-item">
                    <div class="progress-header"><span class="progress-label">Chrome</span><span class="progress-value">64%</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width: 64%; background: rgba(85, 110, 229, 0.7);"></div></div>
                </div>
                <div class="progress-item">
                    <div class="progress-header"><span class="progress-label">Safari</span><span class="progress-value">22%</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width: 22%; background: rgba(234, 179, 8, 0.7);"></div></div>
                </div>
                <div class="progress-item">
                    <div class="progress-header"><span class="progress-label">Firefox</span><span class="progress-value">8%</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width: 8%; background: rgba(220, 38, 38, 0.7);"></div></div>
                </div>
                <div class="progress-item">
                    <div class="progress-header"><span class="progress-label">Edge</span><span class="progress-value">6%</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width: 6%; background: rgba(85, 110, 229, 0.7);"></div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card progress-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Countries</h4>
                        <p class="text-muted mb-0">Top traffic sources</p>
                    </div>
                </div>
                <div class="progress-item">
                    <div class="progress-header"><span class="progress-label">United States</span><span class="progress-value">38%</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width: 38%; background: rgba(85, 110, 229, 0.7);"></div></div>
                </div>
                <div class="progress-item">
                    <div class="progress-header"><span class="progress-label">United Kingdom</span><span class="progress-value">18%</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width: 18%; background: rgba(234, 179, 8, 0.7);"></div></div>
                </div>
                <div class="progress-item">
                    <div class="progress-header"><span class="progress-label">Germany</span><span class="progress-value">12%</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width: 12%; background: rgba(220, 38, 38, 0.7);"></div></div>
                </div>
                <div class="progress-item">
                    <div class="progress-header"><span class="progress-label">Canada</span><span class="progress-value">9%</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width: 9%; background: rgba(85, 110, 229, 0.7);"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
