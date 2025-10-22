@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
@includeWhen(isset($activeElection), 'admin.partials.live-results', ['chartData' => $chartData, 'activeElection' => $activeElection])
@include('admin.partials.stats-cards', ['stats' => $stats])
@include('admin.partials.recent-elections', ['recentElections' => $recentElections])
@endsection