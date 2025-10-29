@extends('templates.basic.layouts.influencer')

@section('content')
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">@lang('user.profile')</h1>
                <p class="text-gray-600">@lang('Manage your influencer profile and personal information')</p>
            </div>

            <!-- Profile Navigation Tabs -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex ltr:space-x-8 rtl:space-x-reverse" aria-label="Tabs">
                    <button type="button"
                            class="tab-button active border-blue-500 text-blue-600 whitespace-nowrap border-b-2 py-2 px-1 text-sm font-medium"
                            data-tab="info">
                        @lang('Basic Information')
                    </button>
                    <button type="button"
                            class="tab-button border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 whitespace-nowrap border-b-2 py-2 px-1 text-sm font-medium"
                            data-tab="stats">
                        @lang('user.statistics')
                    </button>
                    <button type="button"
                            class="tab-button border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 whitespace-nowrap border-b-2 py-2 px-1 text-sm font-medium"
                            data-tab="social">
                        @lang('Social Networks')
                    </button>
                    <button type="button"
                            class="tab-button border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 whitespace-nowrap border-b-2 py-2 px-1 text-sm font-medium"
                            data-tab="education">
                        @lang('Education')
                    </button>
                    <button type="button"
                            class="tab-button border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 whitespace-nowrap border-b-2 py-2 px-1 text-sm font-medium"
                            data-tab="skills">
                        @lang('Skills')
                    </button>
                    <button type="button"
                            class="tab-button border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 whitespace-nowrap border-b-2 py-2 px-1 text-sm font-medium"
                            data-tab="password">
                        @lang('Password')
                    </button>
                </nav>
            </div>


        </div>

        @push('script')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all buttons and panes
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    tabPanes.forEach(pane => {
                        pane.classList.add('hidden');
                        pane.classList.remove('active');
                    });

                    // Add active class to clicked button
                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    // Show corresponding tab pane
                    const targetPane = document.getElementById(targetTab + '-tab');
                    if (targetPane) {
                        targetPane.classList.remove('hidden');
                        targetPane.classList.add('active');
                    }
                });
            });

            // Initialize Lucide icons
            lucide.createIcons();
        });
        </script>
        @endpush
@endsection
