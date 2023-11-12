@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
@endsection

@section('content')
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <a href="users.php" class="back-icon">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <img src="{{ asset('assets/uploads/' . $friend->image) }}" alt="">
                <div class="details">
                    <span>{{ $friend->name }}</span>
                    <div>{{ $friend->status }}</div>
                </div>
            </header>
            <div class="chat-box">
                {!! $output !!}
            </div>
            <form method="POST" action="{{ url('/insert-chat/' . $friend->id) }}" >
                @csrf
                <input type="text" name="incoming_id" class="incoming_id" value="{{ $friend->id }}" id="" hidden>
                <input style="    width: 350px;margin-right: 20px;height: 35px;" type="text" name="message" class="input-field" placeholder="Nhập nội dung ở đây..."
                    autocomplete="off">
                {{-- <button>
                    <i class="fab fa-telegram-plane"></i>
                </button> --}}
                <button type="submit" style="height: 35px;background-color: dodgerblue;">Gui</button>
            </form>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="assets/chat-event.js"></script>
@endsection
