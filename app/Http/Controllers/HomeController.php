<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id = auth()->id();

        $users = User::where('id', '<>', $id)
            ->orderBy('id', 'desc')
            ->get();

        $output = '';
        if ($users->isEmpty()) {
            $outp = "Không có bạn bè hoạt động";
        } else {
            $output .= $this->getFriendList($users);
        }
        return view('home', compact('output'));
    }

    public function search(Request $request)
    {
        $id = auth()->id();
        $name = $request->name;
        $users = User::where('id', '!=', $id)   
                    ->where(function ($query) use ($name) {
                        $query->where('name', 'like', '%' . $name . '%');
                    })
                    ->get();

        $output = "";
        if ($users->count() > 0) {
            $output .= $this->getFriendList($users);      
        } else {
            $output .= "Không tìm thấy người dùng liên quan đến từ khóa";
        }

        return view('home', compact('output'));
    }

    public function getFriendList($users)
    {
        $id = auth()->id();
        $output = '';
        foreach ($users as $friend) {
            $last_message = Message::where(function ($query) use ($friend, $id) {
                $query->where('incoming_msg_id', $friend->id)
                    ->orWhere('outgoing_msg_id', $friend->id);
            })
                ->where(function ($query) use ($friend, $id) {
                    $query->where('outgoing_msg_id', $id)
                        ->orWhere('incoming_msg_id', $id);
                })
                ->orderBy('id', 'desc')
                ->first();

            if ($last_message) {
                $last_mess = $last_message->msg;
            } else {
                $last_mess = "Không có tin nhắn";
            }

            if (strlen($last_mess) > 28) {
                $last_mess = substr($last_mess, 0, 28) . '...';
            }

            // Hiển thị trạng thái hoạt động của bạn bè
            $offline = $friend->status == "Không hoạt động" ? "offline" : "";


            // Hiển thị tên và hình ảnh của bạn bè và tin nhắn gần nhất
            $output .= '<a href="' . url('/getChat/'. $friend->id) . '">
              <div class="content">
                <div class="details">
                  <span>' . $friend->name . '</span>
                  <div>' . $last_mess . '</div>
                </div>
              </div>
              <div class="status-dot ' . $offline . '"><i class="fa fa-circle"></i></div>
            </a>';
        }
        return $output;
    }
}
