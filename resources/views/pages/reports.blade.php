<!-- resources/views/reports.blade.php -->

@extends('layouts.app')

@section('title', 'Moderation Reports')

@section('content')
    <h1>Moderation Reports</h1>

    <section>
        <h2>User Reports</h2>
        @foreach ($userReports as $report)
            <div>
                <p>Report ID: {{ $report->id }}</p>
                <p>Reported User ID: {{ $report->user_reported_id }}</p>
                <p>Reporter User ID: {{ $report->user_who_repoted_id }}</p>
                <p>Date: {{ $report->date }}</p>
            </div>
        @endforeach
    </section>

    <section>
        <h2>Post Reports</h2>
        @foreach ($postReports as $report)
            <div>
                <p>Report ID: {{ $report->id }}</p>
                <p>Reported Post ID: {{ $report->post_id }}</p>
                <p>Reporter User ID: {{ $report->user_id }}</p>
                <p>Date: {{ $report->date }}</p>
            </div>
        @endforeach
    </section>

    <section>
        <h2>Group Reports</h2>
        @foreach ($groupReports as $report)
            <div>
                <p>Report ID: {{ $report->id }}</p>
                <p>Reported Group ID: {{ $report->reported_id }}</p>
                <p>Reporter User ID: {{ $report->user_id }}</p>
                <p>Date: {{ $report->created_at }}</p>
            </div>
        @endforeach
    </section>
@endsection
