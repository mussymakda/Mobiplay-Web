@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Make a Payment</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('payment.process') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="amount">Amount (MXN):</label>
                <input type="number" id="amount" name="amount" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Pay with PayPal</button>
        </form>
    </div>
@endsection
