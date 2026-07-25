@extends('layouts.app')

@section('title', 'Settings - ' . config('app.name'))

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">HAMORA</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </div>
            <h4 class="page-title">Settings</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link active" data-tab="profile">
                            <i class="ti ti-user me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tab="security">
                            <i class="ti ti-lock me-1"></i> Security
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tab="billing">
                            <i class="ti ti-credit-card me-1"></i> Billing
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card" id="tab-profile">
            <div class="card-body">
                <h4 class="header-title mb-3">Preferences</h4>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Language</label>
                    <p class="text-muted mb-2">Select your preferred language</p>
                    <select class="form-select" style="width: auto;">
                        <option>English (US)</option>
                        <option>English (UK)</option>
                        <option>Spanish</option>
                        <option>French</option>
                        <option>German</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Timezone</label>
                    <p class="text-muted mb-2">Set your local timezone</p>
                    <select class="form-select" style="width: auto;">
                        <option>UTC -08:00 (Pacific)</option>
                        <option>UTC -05:00 (Eastern)</option>
                        <option>UTC +00:00 (London)</option>
                        <option>UTC +01:00 (Berlin)</option>
                        <option>UTC +09:00 (Tokyo)</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary">Save Changes</button>
                    <button class="btn btn-secondary ms-1">Cancel</button>
                </div>
            </div>
        </div>

        <div class="card d-none" id="tab-security">
            <div class="card-body">
                <h4 class="header-title mb-3">Change Password</h4>
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-control" placeholder="Enter current password">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" placeholder="Enter new password">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" placeholder="Confirm new password">
                    </div>
                </div>

                <h4 class="header-title mb-3 mt-4">Two-Factor Authentication</h4>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="enable2fa">
                    <label class="form-check-label" for="enable2fa">Enable 2FA - Add an extra layer of security to your account</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="smsAuth" checked>
                    <label class="form-check-label" for="smsAuth">SMS Authentication - Receive codes via SMS</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="authApp">
                    <label class="form-check-label" for="authApp">Authenticator App - Use Google Authenticator or similar</label>
                </div>

                <h4 class="header-title mb-3 mt-4">Active Sessions</h4>
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                    <div>
                        <strong>Chrome on MacOS</strong>
                        <p class="text-muted mb-0 small">San Francisco, CA - Current session</p>
                    </div>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                    <div>
                        <strong>Safari on iPhone</strong>
                        <p class="text-muted mb-0 small">San Francisco, CA - 2 hours ago</p>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary">Revoke</button>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                    <div>
                        <strong>Firefox on Windows</strong>
                        <p class="text-muted mb-0 small">New York, NY - 3 days ago</p>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary">Revoke</button>
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary">Update Password</button>
                    <button class="btn btn-secondary ms-1">Cancel</button>
                </div>
            </div>
        </div>

        <div class="card d-none" id="tab-billing">
            <div class="card-body">
                <h4 class="header-title mb-3">Current Plan</h4>
                <div class="p-4 border rounded mb-4" style="background: rgba(85, 110, 229, 0.05);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">Pro Plan</h5>
                            <p class="text-muted mb-0">Billed monthly</p>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold" style="font-size: 32px;">$29</span>
                            <span class="text-muted">/month</span>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-primary">Upgrade Plan</button>
                        <button class="btn btn-secondary ms-1">Cancel Subscription</button>
                    </div>
                </div>

                <h4 class="header-title mb-3">Payment Method</h4>
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 48px; height: 32px; background: linear-gradient(135deg, #1a1f71, #00579f); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                            <span style="color: white; font-size: 10px; font-weight: bold;">VISA</span>
                        </div>
                        <div>
                            <strong>Visa ending in 4242</strong>
                            <p class="text-muted mb-0 small">Expires 12/2026</p>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <button class="btn btn-secondary mb-4">
                    <i class="ti ti-plus me-1"></i> Add Payment Method
                </button>

                <h4 class="header-title mb-3">Billing History</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-light">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jan 1, 2025</td>
                                <td>Pro Plan - Monthly</td>
                                <td>$29.00</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td><a href="#" class="text-primary">Download</a></td>
                            </tr>
                            <tr>
                                <td>Dec 1, 2024</td>
                                <td>Pro Plan - Monthly</td>
                                <td>$29.00</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td><a href="#" class="text-primary">Download</a></td>
                            </tr>
                            <tr>
                                <td>Nov 1, 2024</td>
                                <td>Pro Plan - Monthly</td>
                                <td>$29.00</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td><a href="#" class="text-primary">Download</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
