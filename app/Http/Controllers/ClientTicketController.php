<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientTicket;
use App\Models\ClientAttachment;
use App\Models\ClientChat;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        return view('ticket/chat',[
            "tickets" => $tickets,
            "chats" => $chats,
            "ct" => $t,
            "cc" => $c,
        ]);
    }

    public function createTicket(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'app' => 'required|exists:applications,id',
            'description' => 'required|string|max:10000',
            'attachments' => 'nullable|array|max:4',
            'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,docx,webp' //Only these are supported to prevent malicious but thankfully stupid actors.
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
                $file->storeAs('client_attachments', $finalFileName);
                ClientAttachment::create([
                    'clientChatID' => $chat->id,
                    'name' => $finalFileName,
                    'extension' => $extension
                ]);
            }
        }

        return redirect()->route('chat', ['t' => $ticket->id]);
    }

    public function addChat(Request $request, ClientTicket $t)
    {
        $request->validate([
            'response' => 'nullable|string|max:10000',
            'attachments.*' => 'file|mimes:png,jpg,jpeg,webp,pdf,docx|max:10240', //Only these are supported to prevent malicious but thankfully stupid actors.
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
                $file->storeAs('client_attachments', $finalFileName);
                ClientAttachment::create([
                    'clientChatID' => $chat->id,
                    'name' => $finalFileName,
                    'extension' => $extension
                ]);
            }
        }

        return back();
    }

    public function closeChat(Request $request, ClientTicket $t)
    {
        $t->status = "DITUTUP";
        $t->save();
        return back();
    }
}
