<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Controllers\Controller;
use App\Traits\SupportTicketManager;

class TicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
        $this->layout = 'frontend';

        $this->redirectLink = 'influencer.ticket.view';
        $this->userType     = 'influencer';
        $this->column       = 'influencer_id';
    }

    protected function getAuthenticatedUser()
    {
        return authInfluencer();
    }

    public function supportTicket()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return redirect()->route('influencer.login');
        }

        $this->user = $user;
        $pageTitle = "Support Tickets";
        $supports = \App\Models\SupportTicket::where($this->column, $user->id)->with('messages')->orderBy('id', 'desc')->paginate(getPaginate());

        return view('templates.basic.influencer.tickets.tickets', compact('pageTitle', 'supports'));
    }

    public function openSupportTicket(\Illuminate\Http\Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return redirect()->route('influencer.login');
        }

        $this->user = $user;
        $campain_offer_id = $request->get('id', '');
        $pageTitle = "Open Ticket";

        return view('templates.basic.influencer.tickets.create', compact('pageTitle', 'user', 'campain_offer_id'));
    }

    public function viewTicket($ticket)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return redirect()->route('influencer.login');
        }

        $this->user = $user;
        $pageTitle = "View Ticket";
        $userId = $user->id;

        $myTicket = \App\Models\SupportTicket::where('ticket', $ticket)->where($this->column, $userId)->orderBy('id', 'desc')->firstOrFail();
        $messages = \App\Models\SupportMessage::where('support_ticket_id', $myTicket->id)->with('ticket', 'admin', 'attachments')->orderBy('id', 'desc')->get();

        return view('templates.basic.influencer.tickets.view', compact('pageTitle', 'myTicket', 'messages', 'user'));
    }
}
