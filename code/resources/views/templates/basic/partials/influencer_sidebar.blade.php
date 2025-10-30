<div class="col-xl-3">
    <div class="dash-sidebar">
        <button class="btn-close sidebar-close d-xl-none shadow-none"></button>
        <ul class="sidebar-menu">

            <li>
                <a href="{{ localized_route('influencer.home') }}" class="{{ menuActive('influencer.home') }}"><i class="las la-home"></i> @lang('navbar.dashboard')</a>
            </li>

            @php
                $pendingOrders = App\Models\Order::pending()->where('influencer_id', authInfluencerId())->count();
                $pendingHires = App\Models\Hiring::pending()->where('influencer_id', authInfluencerId())->count();
            @endphp

            <li class="{{ menuActive('influencer.service.*',2) }}">
                <a href="javascript:void(0)"><i class="las la-wallet"></i> @lang('navbar.services') @if($pendingOrders) <span class="text--danger"><i class="la la-exclamation-circle" aria-hidden="true"></i></span> @endif</a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ localized_route('influencer.service.create') }}" class="{{ menuActive('influencer.service.create') }}"><i class="la la-dot-circle"></i> @lang('navbar.create_new_service')</a>
                    </li>

                    <li>
                        <a href="{{ localized_route('influencer.service.all') }}" class="{{ menuActive('influencer.service.all') }}"><i class="la la-dot-circle"></i> @lang('navbar.all_services')</a>
                    </li>

                    <li>
                        <a href="{{ localized_route('influencer.service.order.index') }}" class="{{ menuActive('influencer.service.order.index') }}"><i class="la la-dot-circle"></i> @lang('navbar.orders') @if($pendingOrders) <span class="text--danger"><i class="fas la-exclamation-circle" aria-hidden="true"></i></span>@endif</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ localized_route('influencer.hiring.index') }}" class="{{ menuActive('influencer.hiring*') }}">
                    <i class="las la-list-ol"></i> @lang('navbar.job_offers')
                    @if($pendingHires) <span class="text--danger"><i class="fas la-exclamation-circle" aria-hidden="true"></i></span>@endif
                </a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.campain.index') }}" class="{{ menuActive('influencer.campain*') }}">
                    <i class="las la-list-ol"></i> @lang('navbar.campaigns')
                    @php
                        $id=authInfluencerId();
                        $notifs=\App\Models\CampainOfferNotification::where('influencer_id',$id)->where('read_status_influencer',0)->count();

                    @endphp
                    @if($notifs > 0) <span class="text--danger"><i class="fas la-exclamation-circle" aria-hidden="true"></i></span>@endif
                </a>
            </li>

            <li class="{{ menuActive('influencer.withdraw*',2) }}">
                <a href="javascript:void(0)"><i class="las la-wallet"></i> @lang('navbar.withdrawals')</a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ localized_route('influencer.withdraw') }}" class="{{ menuActive('influencer.withdraw') }}"><i class="las la-dot-circle"></i> @lang('navbar.withdrawal_amount')</a></li>
                    <li><a href="{{ localized_route('influencer.withdraw.history') }}" class="{{ menuActive('influencer.withdraw.history') }}"><i class="las la-dot-circle"></i> @lang('navbar.withdrawal_history')</a></li>
                </ul>
            </li>
            <li class="{{ menuActive('influencer.ticket.*',2) }}">
                <a href="javascript:void(0)"><i class="las la-ticket-alt"></i> @lang('navbar.support_tickets')</a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ localized_route('influencer.ticket.open') }}" class="{{ menuActive('influencer.ticket.open') }}"><i class="las la-dot-circle"></i> @lang('navbar.open_new_ticket')</a></li>
                    <li><a href="{{ localized_route('influencer.ticket') }}" class="{{ menuActive('influencer.ticket') }}"><i class="las la-dot-circle"></i> @lang('navbar.my_tickets')</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ localized_route('influencer.conversation.index', ['', '']) }}" class="{{ menuActive('influencer.conversation*') }}"><i class="las la-sms"></i> @lang('navbar.conversations')</a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.transactions') }}" class="{{ menuActive('influencer.transactions') }}"><i class="las la-exchange-alt"></i> @lang('navbar.transactions')</a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.profile.setting') }}" class="{{ menuActive('influencer.profile.setting') }}"><i class="las la-user-alt"></i> @lang('navbar.profile_settings')</a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.change.password') }}" class="{{ menuActive('influencer.change.password') }}"><i class="las la-lock-open"></i> @lang('navbar.change_password')</a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.twofactor') }}" class="{{ menuActive('influencer.twofactor') }}"><i class="las la-shield-alt"></i> @lang('navbar.two_factor_security')</a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.logout') }}"><i class="las la-sign-in-alt"></i> @lang('navbar.logout')</a>
            </li>
        </ul>
    </div>
</div>
