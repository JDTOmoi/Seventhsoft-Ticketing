<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientTicket;
use App\Models\ClientAttachment;
use App\Models\ClientChat;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Kreait\Firebase\Factory;

class ClientTicketController extends Controller
{
    public function index()
    {
        
        $tickets = ClientTicket::where('userID', Auth::id())->orderBy('updated_at', 'desc')->get();
        $ticketIDs = $tickets->pluck('id');
        $chats = ClientChat::whereIn('ticketID', $ticketIDs)->get();

        return view('ticket/chat',[
            "tickets" => $tickets,
            "chats" => $chats
        ]);
    }

    public function viewCreateTicket()
    {
        $apps = Application::all();
        return view('ticket/create', compact('apps'));
    }

    public function viewChat(ClientTicket $t)
    {
        if ($t->userID !== Auth::id()) { //Automatically blocks access to tickets that do not belong to the current User.
            return redirect('/tickets');
        }

        $tickets = ClientTicket::where('userID', $t->userID)->orderBy('updated_at', 'desc')->get();
        $ticketIDs = $tickets->pluck('id');
        $chats = ClientChat::whereIn('ticketID', $ticketIDs)->get();
        $c = ClientChat::where('ticketID', $t->id)->with('attachments')->get()->groupBy( function ($chat) { return Carbon::parse($chat->created_at)->format('Y-m-d'); } );

        $timezone = session('user_timezone', config('app.timezone')); // automatic timezone setter, for the travelling seventhsoft users

        $lastChat = ClientChat::where('ticketID', $t->id)->latest('created_at')->first();

        //timezone thing makes sure that the time is in the correct time zone.
        $lastChatDateLocal = $lastChat ? Carbon::parse($lastChat->created_at)->timezone($timezone)->toDateString() : null;

        return view('ticket/chat',[
            "tickets" => $tickets,
            "chats" => $chats,
            "ct" => $t,
            "cc" => $c,
            "lastChatLocalDate" => $lastChatDateLocal
        ]);
    }

    public function createTicket(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'app' => 'required|exists:applications,id',
            'description' => 'required|string|max:10000',
            'attachments' => 'nullable|array|max:4',
            'attachments.*' => 'file|max:2048|mimes:jpg,jpeg,png,pdf,docx,webp' //Only these are supported to prevent malicious but thankfully stupid actors.
        ], [
            "title.required" => 'Mohon isi judul tiket Anda.',
            "app.required" => 'Mohon isi nama app yang Anda mempunyai masalah.',
            "description.required" => 'Mohon isi deskripsi tentang masalah yang Anda sedang mengalami terkait aplikasi tersebut.',
        ]);

        $ticket = ClientTicket::create([
            'userID' => Auth::id(),
            'appID' => $request->app,
            'title' => $request->title,
        ]);

        $chat = ClientChat::create([
            'ticketID' => $ticket->id,
            'type' => 'client',
            'response' => $request->description,
        ]);

        if ($request->hasFile('attachments')) {
            $filenames = [];
            foreach ($request->file('attachments') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $newFileName = $chat->id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName) . '.' . $extension;
                $finalFileName = '';
                $matchCount = 0;
                if (count($filenames) != 0) {
                    foreach($filenames as $filename) {
                        if (strtolower($newFileName) === $filename) {
                            $matchCount++;
                        }
                    }
                }
                if ($matchCount > 0) {
                    $finalFileName = $chat->id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName) . ' (' . $matchCount . ')' .'.' . $extension;
                } else {
                    $finalFileName = $newFileName;
                }
                array_push($filenames, strtolower($newFileName));
                $file->storeAs('client_attachments', $finalFileName, 'public');
                ClientAttachment::create([
                    'clientChatID' => $chat->id,
                    'name' => $finalFileName,
                    'extension' => $extension
                ]);
            }
        }

        $this->pushChatToFirebase($chat);

        return redirect()->route('chat', ['t' => $ticket->id]);
    }

    public function addChat(Request $request, ClientTicket $t)
    {
        $request->validate([
            'response' => 'nullable|string|max:10000',
            'attachments.*' => 'file|mimes:png,jpg,jpeg,webp,pdf,docx|max:2048', //Only these are supported to prevent malicious but thankfully stupid actors.
        ], [
            'attachments.*.mimes' => 'Hanya PNG, JPG, WEBP, PDF, atau DOCX diperbolehkan.',
            'attachments.*.max' => 'Ukuran file maximum adalah 10MB.',
        ]);

        if (!$request->filled('response') && !$request->hasFile('attachments')) {
            return back()->withErrors(['response' => 'Message or attachments are required.']);
        }

        $chat = ClientChat::create([
            'ticketID' => $t->id,
            'type' => 'client',
            'response' => $request->response,
        ]);

        if ($request->hasFile('attachments')) {
            $filenames = [];
            foreach ($request->file('attachments') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $newFileName = $chat->id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName) . '.' . $extension;
                $finalFileName = '';
                $matchCount = 0;
                if (count($filenames) != 0) {
                    foreach($filenames as $filename) {
                        if (strtolower($newFileName) === $filename) {
                            $matchCount++;
                        }
                    }
                }
                if ($matchCount > 0) {
                    $finalFileName = $chat->id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName) . ' (' . $matchCount . ')' .'.' . $extension;
                } else {
                    $finalFileName = $newFileName;
                }
                array_push($filenames, strtolower($newFileName));
                $file->storeAs('client_attachments', $finalFileName, 'public');
                ClientAttachment::create([
                    'clientChatID' => $chat->id,
                    'name' => $finalFileName,
                    'extension' => $extension
                ]);
            }
        }

        $this->pushChatToFirebase($chat);

        return back();
    }

    public function closeChat(Request $request, ClientTicket $t)
    {
        $t->status = "DITUTUP";
        $t->save();
        return back();
    }

    private function pushChatToFirebase(ClientChat $chat)
    {   
        //Database URI is used since this machine is using an old version of firebase and always defaults to the old link.
        $factory = (new Factory)->withServiceAccount(base_path(config('firebase.credentials.file')))->withDatabaseUri(config('firebase.credentials.url')); 
        $db = $factory->createDatabase();

        $ticketId = $chat->ticketID;
        $attachments = $chat->attachments->map(function ($a) {
            return [
                'name' => $a->name,
                'extension' => $a->extension,
                'url' => asset('storage/client_attachments/' . $a->name)
            ];
        });

        $db->getReference("tickets/{$ticketId}/{$chat->id}")
        ->set([
                'id' => $chat->id,
                'response' => $chat->response,
                'type' => $chat->type,
                'created_at' => $chat->created_at->toDateTimeString(),
                'attachments' => $attachments,
            ]);
        
        //Immediately remove chat cuz we just need it to store the real time thing.
        $db->getReference("tickets/{$ticketId}/{$chat->id}")->remove();
    }
}
