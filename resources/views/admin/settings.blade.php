@extends('layouts.admin')

@section('title', 'Settings')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Settings</h1>
            <p class="page-subtitle mb-0">Manage application and account settings</p>
        </div>
        <div>
            <button type="submit" form="appSettingsForm" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Save All
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Application</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="#" id="appSettingsForm">
                        @csrf
                        <div class="mb-3">
                            <label for="site-name" class="form-label">Site Name</label>
                            <input type="text" id="site-name" name="site_name" class="form-control" placeholder="Voting System" value="{{ old('site_name', 'Voting System') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select id="timezone" name="timezone" class="form-select">
                                    <option value="UTC" selected>UTC</option>
                                    <option value="Asia/Manila">Asia/Manila</option>
                                    <option value="America/New_York">America/New_York</option>
                                    <option value="Europe/London">Europe/London</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="date-format" class="form-label">Date Format</label>
                                <input type="text" id="date-format" name="date_format" class="form-control" placeholder="Y-m-d" value="{{ old('date_format', 'Y-m-d') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="time-format" class="form-label">Time Format</label>
                                <input type="text" id="time-format" name="time_format" class="form-control" placeholder="H:i" value="{{ old('time_format', 'H:i') }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-gear me-2"></i>Account</h5>
                    <button type="submit" form="accountForm" class="btn btn-outline-primary btn-sm"><i class="bi bi-save me-1"></i>Update</button>
                </div>
                <div class="card-body">
                    <form method="POST" action="#" id="accountForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="admin@example.com" value="{{ old('email', auth()->user()->email) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Display Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Admin" value="{{ old('name', auth()->user()->first_name ?? 'Admin') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="••••••••">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Tips</h6></div>
                <div class="card-body">
                    <small class="text-muted d-block mb-2">- Keep site name short and recognizable.</small>
                    <small class="text-muted d-block mb-2">- Use Asia/Manila for Philippine timezone.</small>
                    <small class="text-muted d-block">- Use 24h time format (H:i) for clarity.</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h6 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Security</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="twoFactor" checked>
                        <label class="form-check-label" for="twoFactor">Enable 2FA (placeholder)</label>
                    </div>
                    <small class="text-muted">Security settings will be wired to backend later.</small>
                </div>
            </div>
        </div>
    </div>
@endsection
