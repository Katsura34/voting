@extends('layouts.admin')

@section('title', 'Settings')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Settings</h1>
            <p class="page-subtitle mb-0">Manage application and account settings</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>General Settings</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="#">
                @csrf
                
                <div class="mb-3">
                    <label for="site-name" class="form-label">Site Name</label>
                    <input type="text" id="site-name" name="site_name" class="form-control" placeholder="Voting System" value="{{ old('site_name', 'Voting System') }}">
                </div>

                <div class="mb-3">
                    <label for="timezone" class="form-label">Timezone</label>
                    <select id="timezone" name="timezone" class="form-select">
                        <option value="UTC" selected>UTC</option>
                        <option value="Asia/Manila">Asia/Manila</option>
                        <option value="America/New_York">America/New_York</option>
                        <option value="Europe/London">Europe/London</option>
                        <!-- Add more options here -->
                    </select>
                </div>

                <div class="mb-3">
                    <label for="date-format" class="form-label">Date Format</label>
                    <input type="text" id="date-format" name="date_format" class="form-control" placeholder="Y-m-d" value="{{ old('date_format', 'Y-m-d') }}">
                </div>

                <div class="mb-3">
                    <label for="time-format" class="form-label">Time Format</label>
                    <input type="text" id="time-format" name="time_format" class="form-control" placeholder="H:i" value="{{ old('time_format', 'H:i') }}">
                </div>

                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Account Settings</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="#">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="admin@example.com" value="{{ old('email', auth()->user()->email) }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Change Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="New password">
                </div>

                <button type="submit" class="btn btn-primary">Update Account</button>
            </form>
        </div>
    </div>
@endsection
