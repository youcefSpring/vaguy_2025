@php
$influencerContent = getContent('top_influencer.content', true);
$influencers = App\Models\Influencer::with('socialLink')->where('completed_order','>',0)->orderBy('completed_order','desc')
    ->take(4)
    ->get();

$favorite    = App\Models\Favorite::where('user_id', auth()->id())->select('influencer_id')->pluck('influencer_id');
$influencersId = json_decode($favorite);
@endphp

@if ($influencers->count() > 0)
    <section class="influencer-section pt-80 pb-80 bg--light top_influencers_background">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xxl-6">
                    <div class="section-header text-center">
                        <h2 class="section-header__title">{{ __(@$influencerContent->data_values->heading) }}</h2>
                    </div>
                </div>
            </div>
            <div class="row gy-4 justify-content-center">
                @foreach ($influencers as $influencer)
                    <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-10">
                        <div class="bg-white position-relative p-4" style="border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: all 0.2s;">
                            <!-- Favorite Button -->
                            @auth
                            <div class="position-absolute top-0 end-0 p-3">
                                @if (in_array($influencer->id, @$influencersId))
                                <button class="favoriteBtn active border-0 bg-transparent" style="color: #9ca3af;" data-influencer_id="{{ $influencer->id }}">
                                    <i class="las la-heart fs-4"></i>
                                </button>
                                @else
                                <button class="favoriteBtn border-0 bg-transparent" style="color: #9ca3af;" data-influencer_id="{{ $influencer->id }}">
                                    <i class="lar la-heart fs-4"></i>
                                </button>
                                @endif
                            </div>
                            @endauth

                            <!-- Profile Image -->
                            <div class="text-center mb-3 mt-2">
                                <div class="position-relative d-inline-block">
                                    <img src="{{ getImage(getFilePath('influencerProfile') . '/' . @$influencer->image, getFileSize('influencerProfile'), true) }}"
                                         alt="{{ __($influencer->fullname) }}"
                                         class="rounded-circle"
                                         style="width: 96px; height: 96px; object-fit: cover; border: 2px solid #f3f4f6;">
                                    @if ($influencer->isOnline())
                                    <span class="position-absolute bg-success rounded-circle border border-2 border-white"
                                          style="width: 16px; height: 16px; bottom: 4px; right: 4px;"></span>
                                    @endif
                                </div>
                            </div>

                            <!-- Name and Profession -->
                            <div class="text-center mb-3">
                                <h5 class="fw-bold mb-1 text-truncate" style="font-size: 1.125rem; color: #1f2937;">{{ __($influencer->fullname) }}</h5>
                                <p class="mb-0 text-truncate" style="font-size: 0.875rem; color: #6b7280;">{{ __($influencer->profession) }}</p>
                            </div>

                            <!-- Rating -->
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <span class="fw-bold me-1" style="font-size: 1rem; color: #1f2937;">{{ number_format($influencer->rating, 1) }}</span>
                                <span style="font-size: 0.875rem; color: #6b7280;">({{ $influencer->total_review ?? 0 }})</span>
                            </div>

                            <!-- Social Icons -->
                            <div class="d-flex justify-content-center gap-3 mb-4" style="min-height: 32px;">
                                @if($influencer->socialLink && $influencer->socialLink->count() > 0)
                                    @foreach($influencer->socialLink->take(4) as $social)
                                    <a href="{{ $social->url }}" target="_blank" style="text-decoration: none; color: #6b7280; font-size: 1.25rem; transition: color 0.2s;">
                                        @php echo $social->social_icon @endphp
                                    </a>
                                    @endforeach
                                @endif
                            </div>

                            <!-- View Profile Button -->
                            <div class="d-grid">
                                <a href="{{ localized_route('influencer.profile', $influencer->id) }}"
                                   class="btn text-white fw-semibold"
                                   style="background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%); border-radius: 12px; padding: 12px 24px; box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3); border: none;">
                                    @lang('View Profile')
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center">
                <a href="{{ localized_route('influencers') }}" class="cmn--btn mt-5">@lang('View More')</a>
            </div>
        </div>
    </section>
@endif
<style>
.top_influencers_background{
    background-image: url("./assets/backgroud/2em-min-min.png");
    /* border-radius: 20px;

    width: 1600px; */
}

</style>
