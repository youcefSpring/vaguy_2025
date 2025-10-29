<div class="col-xl-3">
    <div class="dash-sidebar">
        <button class="btn-close sidebar-close d-xl-none shadow-none"></button>
        <ul class="sidebar-menu">

            <li>
                <a href="{{ localized_route('influencer.home') }}" class="{{ menuActive('influencer.home') }}"><i class="las la-home"></i> @lang('لوحة التحكم')</a>
            </li>

            @php
                $pendingOrders = App\Models\Order::pending()->where('influencer_id', authInfluencerId())->count();
                $pendingHires = App\Models\Hiring::pending()->where('influencer_id', authInfluencerId())->count();
            @endphp

            <li class="{{ menuActive('influencer.service.*',2) }}">
                <a href="javascript:void(0)"><i class="las la-wallet"></i> @lang('الخدمات') @if($pendingOrders) <span class="text--danger"><i class="la la-exclamation-circle" aria-hidden="true"></i></span> @endif</a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ localized_route('influencer.service.create') }}" class="{{ menuActive('influencer.service.create') }}"><i class="la la-dot-circle"></i> @lang('انشاء خدمة جديدة')</a>
                    </li>

                    <li>
                        <a href="{{ localized_route('influencer.service.all') }}" class="{{ menuActive('influencer.service.all') }}"><i class="la la-dot-circle"></i> @lang('جميع الخدمات')</a>
                    </li>

                    <li>
                        <a href="{{ localized_route('influencer.service.order.index') }}" class="{{ menuActive('influencer.service.order.index') }}"><i class="la la-dot-circle"></i> @lang('الطلبات') @if($pendingOrders) <span class="text--danger"><i class="fas la-exclamation-circle" aria-hidden="true"></i></span>@endif</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ localized_route('influencer.hiring.index') }}" class="{{ menuActive('influencer.hiring*') }}">
                    <i class="las la-list-ol"></i> @lang('عروض العمل')
                    @if($pendingHires) <span class="text--danger"><i class="fas la-exclamation-circle" aria-hidden="true"></i></span>@endif
                </a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.campain.index') }}" class="{{ menuActive('influencer.campain*') }}">
                    <i class="las la-list-ol"></i> @lang('الحملات')
                    @php
                        $id=authInfluencerId();
                        $notifs=\App\Models\CampainOfferNotification::where('influencer_id',$id)->where('read_status_influencer',0)->count();

                    @endphp
                    @if($notifs > 0) <span class="text--danger"><i class="fas la-exclamation-circle" aria-hidden="true"></i></span>@endif
                </a>
            </li>

            <li class="{{ menuActive('influencer.withdraw*',2) }}">
                <a href="javascript:void(0)"><i class="las la-wallet"></i> @lang('السحب')</a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ localized_route('influencer.withdraw') }}" class="{{ menuActive('influencer.withdraw') }}"><i class="las la-dot-circle"></i> @lang('مبلغ السحب')</a></li>
                    <li><a href="{{ localized_route('influencer.withdraw.history') }}" class="{{ menuActive('influencer.withdraw.history') }}"><i class="las la-dot-circle"></i> @lang('سجل السحب')</a></li>
                </ul>
            </li>
            <li class="{{ menuActive('influencer.ticket.*',2) }}">
                <a href="javascript:void(0)"><i class="las la-ticket-alt"></i> @lang('بطاقة الدعم')</a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ localized_route('influencer.ticket.open') }}" class="{{ menuActive('influencer.ticket.open') }}"><i class="las la-dot-circle"></i> @lang('فتح بطاقة جديدة')</a></li>
                    <li><a href="{{ localized_route('influencer.ticket') }}" class="{{ menuActive('influencer.ticket') }}"><i class="las la-dot-circle"></i> @lang('بطاقاتي')</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ localized_route('influencer.conversation.index', ['', '']) }}" class="{{ menuActive('influencer.conversation*') }}"><i class="las la-sms"></i> @lang('المحادثات')</a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.transactions') }}" class="{{ menuActive('influencer.transactions') }}"><i class="las la-exchange-alt"></i> @lang('المعاملات')</a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.profile.setting') }}" class="{{ menuActive('influencer.profile.setting') }}"><i class="las la-user-alt"></i> @lang('تعديلات الحساب')</a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.change.password') }}" class="{{ menuActive('influencer.change.password') }}"><i class="las la-lock-open"></i> @lang('تغيير كلمة السر')</a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.twofactor') }}" class="{{ menuActive('influencer.twofactor') }}"><i class="las la-shield-alt"></i> @lang('2FA أمن')</a>
            </li>
            <li>
                <a href="{{ localized_route('influencer.logout') }}"><i class="las la-sign-in-alt"></i> @lang('تسجيل الخروج')</a>
            </li>
        </ul>
    </div>
</div>
