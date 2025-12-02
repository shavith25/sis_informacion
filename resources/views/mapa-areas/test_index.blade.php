@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Test: mapa-areas/test_index.blade.php</h1>
        <ul>
            <li>
                <strong>Test 1:</strong> The view renders without errors.
                @if(View::exists('mapa-areas.test_index'))
                    <span style="color:green;">Passed</span>
                @else
                    <span style="color:red;">Failed</span>
                @endif
            </li>
            <li>
                <strong>Test 2:</strong> The view extends the correct layout.
                @if(View::getSections()['content'] ?? false)
                    <span style="color:green;">Passed</span>
                @else
                    <span style="color:red;">Failed</span>
                @endif
            </li>
        </ul>
    </div>
@endsection