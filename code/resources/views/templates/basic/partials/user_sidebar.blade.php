<div class="col-xl-3">
    <div class="dash-sidebar">
        <button class="btn-close sidebar-close d-xl-none shadow-none"></button>
        <ul class="sidebar-menu">

            <li>
                <a href="{{ localized_route('user.home') }}" class="{{ menuActive('user.home') }}"><i class="las la-home"></i> @lang('لوحة التحكم')</a>
            </li>

            <li class="{{ menuActive('user.deposit*',2) }} {{ menuActive('user.transactions',2) }}">
                <a href="javascript:void(0)"><i class="las la-wallet"></i> @lang('المالية')</a>

                <ul class="sidebar-submenu  }}">
                    <li><a href="{{ localized_route('user.deposit') }}" class="{{ menuActive('user.deposit') }}"><i class="las la-dot-circle"></i> @lang('الإيداع النقدي')</a></li>
                    <li><a href="{{ localized_route('user.deposit.history') }}" class="{{ menuActive('user.deposit.history') }}"><i class="las la-dot-circle"></i> @lang('سجل الإيداع')</a></li>
                    <li><a href="{{ localized_route('user.transactions') }}" class="{{ menuActive('user.transactions') }}"><i class="las la-dot-circle"></i> @lang('المعاملات')</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ localized_route('user_campaign') }}" class="{{ menuActive('client.campaign*') }}"><i class="las la-wallet"></i> @lang('الحملات')

                    @php
                        $id=auth()->user()->id;
                        // $notifs_count=$user->campains->campain_offers->campain_notifs->count();
                        $notifs_count=\App\Models\CampainOfferNotification::where('user_id',$id)->where('read_status_user',0)->count();

                    @endphp
                       @if($notifs_count > 0)
                    <span class="menu-badge pill aa ms-auto" style="color:red;">
                        <i class="fa fa-exclamation"></i>
                    </span>
                    @endif

                </a>
            </li>
            <li>
                <a href="{{ localized_route('user.order.all') }}" class="{{ menuActive('user.order.*') }}"><i class="las la-list"></i> @lang('الطلبات')</a>
            </li>

            <li>
                <a href="{{ localized_route('user.hiring.history') }}" class="{{ menuActive('user.hiring.*') }}"> <i class="las la-list-ol"></i> @lang('عروض العمل')</a>
            </li>

            <li class="{{ menuActive('ticket*',2) }}">
                <a href="javascript:void(0)"><i class="las la-ticket-alt"></i> @lang('بطاقة الدعم')</a>
                <ul class="sidebar-submenu {{ menuActive('ticket*') }}">
                    <li><a href="{{ localized_route('ticket.open') }}" class="{{ menuActive('ticket.open') }}"><i class="las la-dot-circle"></i> @lang('فتح بطاقة جديدة')</a></li>
                    <li><a href="{{ localized_route('ticket') }}" class="{{ menuActive('ticket') }}"><i class="las la-dot-circle"></i> @lang('بطاقاتي')</a></li>
                </ul>
            </li>

            <li>
                <a href="{{ localized_route('user.conversation.index', ['', '']) }}" class="{{ menuActive('user.conversation.*') }}"><i class="las la-sms"></i> @lang('المحادثات')</a>
            </li>

            <li>
                <a href="{{ localized_route('user.favorite.list') }}" class="{{ menuActive('user.favorite.list') }}"><i class="lar la-heart"></i> @lang('قائمة المفضلة')</a>
            </li>

            <li class="{{ menuActive('user.review*',2) }}">
                <a href="javascript:void(0)"><i class="la la-star-o"></i> @lang('الآراء')</a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ localized_route('user.review.order.index') }}" class="{{ menuActive('user.review.order.index') }}">
                            <i class="las la-dot-circle"></i> @lang('الآراء حول الطلبات')
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_route('user.review.hiring.index') }}" class="{{ menuActive('user.review.hiring.index') }}">
                            <i class="las la-dot-circle"></i> @lang('الآراء حول عروض العمل')
                        </a>
                    </li>
                </ul>
            </li>


            <li>
                <a href="{{ localized_route('user.profile.setting') }}" class="{{ menuActive('user.profile.setting') }}"><i class="las la-user-alt"></i> @lang('تعديلات الحساب')</a>
            </li>

            <li>
                <a href="{{ localized_route('user.change.password') }}" class="{{ menuActive('user.change.password') }}"><i class="las la-lock-open"></i> @lang('تغيير كلمة السر')</a>
            </li>

            <li>
                <a href="{{ localized_route('user.twofactor') }}" class="{{ menuActive('user.twofactor') }}"><i class="las la-shield-alt"></i> @lang('2FA أمن')</a>
            </li>

            <li>
                <a href="{{ localized_route('user.logout') }}"><i class="las la-sign-in-alt"></i>  @lang('تسجيل الخروج')</a>
            </li>
        </ul>
    </div>
</div>
