<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MessageController extends Controller
{
    public function chat()
    {
        return view('chat');
    }

    public function insertChat(Request $request)
    {
        // dd($request->message);
        $outgoing_id = Auth::user()->id;
        $incoming_id = $request->incoming_id;
        $message = $request->message;

        if (!empty($message)) {
            Message::create([
                'incoming_msg_id' => $incoming_id,
                'outgoing_msg_id' => $outgoing_id,
                'msg' => $message,
            ]);
        }

        return Redirect::back();
    }

    public function getChat(Request $request)
    {
        $id = $request->id;
        $friend = User::where('id', $id)->first();

        ///////
        $outgoing_id = Auth::user()->id;
        $incoming_id = $request->id;
        $output = "";

        $messages = Message::select('messages.*', 'users.unique_id')
            ->leftJoin('users', 'users.unique_id', '=', 'messages.outgoing_msg_id')
            ->where(function ($query) use ($outgoing_id, $incoming_id) {
                $query->where('outgoing_msg_id', $outgoing_id)
                    ->where('incoming_msg_id', $incoming_id);
            })
            ->orWhere(function ($query) use ($outgoing_id, $incoming_id) {
                $query->where('outgoing_msg_id', $incoming_id)
                    ->where('incoming_msg_id', $outgoing_id);
            })
            ->orderBy('id')
            ->get();

        if ($messages->count() > 0) {
            foreach ($messages as $message) {
                if ($message->outgoing_msg_id == $outgoing_id) {
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>' . $message->msg . '</p>
                                </div>
                            </div>';
                } else {
                    $output .= '<div class="chat incoming">
                                <div class="details">
                                    <p>' . $message->msg . '</p>
                                </div>
                            </div>';
                }
            }
        } else {
            $output .= "<div class='text'>Không có tin nhắn. Khi bạn có, tin nhắn sẽ hiện tại đây.</div>";
        }


        return view('chat', compact('output', 'friend'));
    }
}
