@extends('layouts.app')

@section('title', 'Vote Confirmation')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0"><i class="bi bi-check-circle"></i> Vote Successfully Submitted!</h3>
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-ballot-fill text-success" style="font-size: 4rem;"></i>
                    <h2 class="text-success mt-3 mb-4">Thank You for Voting!</h2>
                    
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="alert alert-info">
                                <h5><i class="bi bi-info-circle"></i> What Happens Next?</h5>
                                <ul class="text-start mb-0">
                                    <li>Your vote has been securely recorded</li>
                                    <li>Results will be available after the election closes</li>
                                    <li>You will be notified when winners are announced</li>
                                    <li>You cannot change your vote once submitted</li>
                                </ul>
                            </div>
                            
                            @if($election)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5>{{ $election->name }}</h5>
                                        <p class="text-muted mb-2">{{ $election->description }}</p>
                                        <p class="mb-0">
                                            <strong>Election ends:</strong> {{ $election->end_date->format('M d, Y H:i A') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-house"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection