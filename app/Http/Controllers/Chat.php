<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\UserChatTable;
use App\ChatTable;
use App\User;
use App\ChatModel;
use App\Events\ChatEvent;

class Chat extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = ChatTable::where('chat_table.creator_id', Auth::id())
        ->leftJoin('user_chat_table', 'chat_table.chat_id', '=', 'user_chat_table.chat_id')
        ->orWhere('user_chat_table.user_id', Auth::id())
        ->select('chat_table.*')->get();

        return view('home', ['chats' => $res]);
    }

    public function addChat(Request $request)
    {
        $res = ChatTable::where('name', $request->chatName)->first();

        if($res) {
            return response()->json([
                'error' => 'Chat already exist'
            ]);
        } else {
            $chatTable = ChatTable::create(
                ['creator_id' => Auth::id(), 'name' => $request->chatName, 'chat_id' => (string) Str::uuid()]
            );

            $chatTable->save();

            return response()->json([
                'success' => $chatTable
            ]);
        }
    }

    public function searchUser(Request $request)
    {
        if(trim($request->value)) {
            $users = User::where('name', 'like', "%{$request->value}%")->Where('id', '!=', Auth::id())->get();
        } else {
            $users = [];
        }

        return response()->json($users);
    }

    public function addUserToChat(Request $request)
    {
        $res = UserChatTable::updateOrCreate(
            ['chat_id' => $request->chatId, 'user_id' => (int)$request->userId],
            ['chat_id' => $request->chatId, 'user_id' => (int)$request->userId]
        );

        return response()->json(['success' => true]);
    }

    public function addMessage(Request $request)
    {
        $chat = new ChatModel();
        $chat->sender_id = Auth::id();
        $chat->text = htmlentities(htmlspecialchars($request->text));
        $chat->chat_id = $request->chatId;
        $chat->save();

        $res = ChatModel::where('chat.id', $chat->id)
        ->leftJoin('users', 'chat.sender_id', '=', 'users.id')
        ->select('chat.*', 'users.name', \DB::raw('IF(chat.sender_id = ' . Auth::id() . ', true, false) AS me'), \DB::raw('LEFT(users.name, 1) AS user'))->first();
        $res->text = html_entity_decode(htmlspecialchars_decode($res->text));

        broadcast(new ChatEvent($request->chatId, $res));

        return response()->json($res);
    }

    public function getMessages(Request $request)
    {
        $res = ChatModel::where('chat.chat_id', (string)$request->chatId)
        ->leftJoin('users', 'chat.sender_id', '=', 'users.id')
        ->select('chat.*', 'users.name', \DB::raw('IF(chat.sender_id = ' . Auth::id() . ', true, false) AS me'), \DB::raw('LEFT(users.name, 1) AS user'))->get();

        foreach ($res as &$value) {
            $value->text = html_entity_decode(htmlspecialchars_decode($value->text));
        }
        
        return response()->json($res);
    }
}
