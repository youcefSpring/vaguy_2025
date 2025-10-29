@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Campain Name')</th>
                                <th>@lang('Client')</th>
                                 <th>@lang('Objective')</th>
                                <th>@lang('Details')</th>
                                {{--<th>@lang('Delivery Date')</th>
                                @if(request()->routeIs('admin.hiring.index'))
                                <th>@lang('Status')</th>
                                @endif --}}
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($hirings as $hiring)
                                <tr>
                                    <td data-label="@lang('Hiring Number')">
                                        <span class="fw-bold">{{ $hiring->campain_name }}</span>
                                    </td>

                                    <td data-label="@lang('User')">
                                        <span class="small">
                                            <a href="{{ localized_route('admin.users.detail', $hiring->user_id) }}"><span>@</span>{{ @$hiring->user->username }}</a>
                                        </span>
                                    </td>

                                    <td data-label="@lang('Campain Objective')">
                                        <span class="small">
                                            <span class="fw-bold">{{ $hiring->campain_objective }}</span>
                                        </span>
                                    </td>

                                    <td data-label="@lang('Campain Details')">
                                        <span class="small">
                                            <span class="fw-bold">{{ $hiring->campain_details }}</span>
                                        </span>
                                    </td>


                                    <td data-label="@lang('Action')">
                                        <a href="{{ localized_route('admin.campain.detail', $hiring->id) }}" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-edit text--shadow"></i> @lang('Details')
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($hirings->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($hirings) }}
                </div>
                @endif
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end">
        <form action="" method="GET" class="form-inline">
            <div class="input-group justify-content-end">
                <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Search influencer or category')" value="{{ request()->search }}">
                <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </div>
@endpush
