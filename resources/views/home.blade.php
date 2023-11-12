@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
    <div class="wrapper">
        <section class="users">
            {{-- <header>
                <div class="content">
                    <img src="https://media-assets.wired.it/photos/62a04ae548e3c6dcf76f1bba/1:1/w_1439,h_1439,c_limit/joker.jpg"
                        alt="">
                    <div class="details">
                        <span>Hiepppp</span>
                        <p>Dang hoat dong</p>
                    </div>
                </div>
                <a href="" class="logout">Đăng xuất</a>
            </header> --}}
            <form action="{{ url('/search') }}" method="post">
                @csrf
                <div class="" style="margin-bottom: 30px;">
                    {{-- <span class="text">Lựa chọn bạn bè để trò chuyện</span> --}}
                    <input type="text" name="name" id="" placeholder="Nhập tên để tìm kiếm" style="width: 350px;">
                    <button style="margin-left: 20px;"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <div class="users-list">
                {!!  $output !!}
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="assets/users-event.js"></script>
@endsection
