{{--
    Dashboard Footer Component

    This partial provides the footer for dashboard pages.
    It's converted from the React Footer component to Bootstrap/Blade.

    Features:
    - Company information and links
    - Social media links
    - Contact information with click-to-copy functionality
    - Multilingual support
    - Responsive design
    - Copyright information
--}}

@php
    $currentLang = app()->getLocale();

    // Social media links
    $socialLinks = [
        [
            'name' => 'Facebook',
            'url' => 'https://www.facebook.com/VaguyDz',
            'icon' => 'facebook'
        ],
        [
            'name' => 'Instagram',
            'url' => 'https://www.instagram.com/vaguy.app/',
            'icon' => 'instagram'
        ],
        [
            'name' => 'WhatsApp',
            'url' => 'https://api.whatsapp.com/send?phone=%2B213658476719',
            'icon' => 'whatsapp'
        ]
    ];

    // Quick links
    $quickLinks = [
        [
            'title' => __('footer.contact_us'),
            'url' => '/contact'
        ]
    ];

    // Important links
    $importantLinks = [
        [
            'title' => __('footer.privacy_policy'),
            'url' => '/policy/syas-alkhsosy/42'
        ],
        [
            'title' => __('footer.terms_of_use'),
            'url' => '/policy/shrot-alkhdm/43'
        ],
        [
            'title' => __('footer.return_policy'),
            'url' => '/policy/syas-alastrgaaa/76'
        ]
    ];

    // Contact information
    $contactInfo = [
        [
            'type' => 'email',
            'value' => 'influencer@Vaguy.app',
            'icon' => 'envelope'
        ],
        [
            'type' => 'phone',
            'value' => '+213 775-452-419',
            'icon' => 'telephone'
        ],
        [
            'type' => 'phone',
            'value' => '+213 658-476-719',
            'icon' => 'telephone'
        ]
    ];
@endphp

{{-- Footer Section --}}
<footer class="bg-light border-top mt-auto" dir="{{ $currentLang === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container-fluid py-5">
        <div class="row g-4">
            {{-- Company Information --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="d-flex flex-column gap-3">
                    {{-- Logo --}}
                    <div class="mb-2">
                        <img
                            src="{{ asset('assets/logoIcon/logo_dark.png') }}"
                            alt="Vaguy"
                            class="img-fluid"
                            style="height: 5rem;"
                        >
                    </div>

                    {{-- Company Description --}}
                    <p class="text-muted small mb-3">
                        {{ __('footer.company_description') }}
                    </p>

                    {{-- Social Media Links --}}
                    <div class="d-flex gap-2">
                        @foreach($socialLinks as $social)
                            <a
                                href="{{ $social['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-dark btn-sm d-flex align-items-center justify-content-center"
                                style="width: 2.5rem; height: 2.5rem;"
                                data-bs-toggle="tooltip"
                                data-bs-title="{{ $social['name'] }}"
                            >
                                <i class="bi bi-{{ $social['icon'] }}" style="font-size: 1.1rem;"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-12 col-md-6 col-lg-3">
                <h6 class="fw-semibold text-primary mb-3">{{ __('footer.quick_links') }}</h6>
                <ul class="list-unstyled">
                    @foreach($quickLinks as $link)
                        <li class="mb-2">
                            <a
                                href="{{ $link['url'] }}"
                                class="text-decoration-none text-muted hover-text-primary"
                                onclick="window.showProgressBar()"
                            >
                                {{ $link['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Important Links --}}
            <div class="col-12 col-md-6 col-lg-3">
                <h6 class="fw-semibold text-primary mb-3">{{ __('footer.important_links') }}</h6>
                <ul class="list-unstyled">
                    @foreach($importantLinks as $link)
                        <li class="mb-2">
                            <a
                                href="{{ $link['url'] }}"
                                class="text-decoration-none text-muted hover-text-primary"
                                onclick="window.showProgressBar()"
                            >
                                {{ $link['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact Information --}}
            <div class="col-12 col-md-6 col-lg-3">
                <h6 class="fw-semibold text-primary mb-3">{{ __('footer.contact_us') }}</h6>
                <div class="d-flex flex-column gap-2">
                    @foreach($contactInfo as $contact)
                        <button
                            type="button"
                            class="btn btn-link text-start p-0 text-muted d-flex align-items-center gap-2"
                            onclick="copyToClipboard('{{ $contact['value'] }}')"
                            data-bs-toggle="tooltip"
                            data-bs-title="{{ __('footer.click_to_copy') }}"
                        >
                            <i class="bi bi-{{ $contact['icon'] }}" style="font-size: 1rem;"></i>
                            <span class="small">{{ $contact['value'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright Section --}}
    <div class="border-top py-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <small class="text-muted">
                        {{ __('footer.copyright', ['year' => date('Y')]) }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- Footer JavaScript --}}
@push('scripts')
<script>
    /**
     * Copy text to clipboard and show toast notification
     */
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success toast
            window.showToast(text + ' ' + window.translate('copied_to_clipboard'), 'success');
        }).catch(function() {
            // Show error toast
            window.showToast(text + ' ' + window.translate('not_copied_to_clipboard'), 'danger');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips for footer elements
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        // Add hover effects for links
        const hoverLinks = document.querySelectorAll('.hover-text-primary');
        hoverLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.classList.remove('text-muted');
                this.classList.add('text-primary');
            });

            link.addEventListener('mouseleave', function() {
                this.classList.remove('text-primary');
                this.classList.add('text-muted');
            });
        });
    });
</script>
@endpush

{{-- Footer Styles --}}
@push('styles')
<style>
    /* Footer specific styles */
    .hover-text-primary:hover {
        color: var(--bs-primary) !important;
        transition: color 0.2s ease;
    }

    /* RTL adjustments for footer */
    [dir="rtl"] footer .text-start {
        text-align: right !important;
    }

    [dir="rtl"] footer .me-2 {
        margin-right: 0 !important;
        margin-left: 0.5rem !important;
    }

    [dir="rtl"] footer .ms-2 {
        margin-left: 0 !important;
        margin-right: 0.5rem !important;
    }

    /* Footer link animations */
    footer a {
        transition: all 0.2s ease;
    }

    footer .btn-link:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        border-radius: 0.375rem;
    }

    /* Social media button hover effects */
    footer .btn-dark:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>
@endpush