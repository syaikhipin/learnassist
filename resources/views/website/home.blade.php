@extends('website.layout')
@section('content')

    <!-- Hero -->
    <div id="home" class="relative isolate overflow-hidden  py-24 sm:py-32">
        <div aria-hidden="true" class="flex absolute -top-96 left-1/2 transform -translate-x-1/2">
            <div class="bg-gradient-to-r from-violet-300/50 to-purple-100 blur-3xl w-[25rem] h-[44rem] rotate-[-60deg] transform -translate-x-[10rem]"></div>

        </div>
        <div class="relative z-10 ">
            <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="max-w-3xl text-center mx-auto">
                    <h1 class="text-4xl font-bold tracking-tight sm:text-6xl bg-gradient-to-r from-green-300 via-blue-500 to-purple-600 bg-clip-text text-4xl font-extrabold text-transparent
">
                        {{$post->settings['hero_title'] ?? ''}}
                    </h1>
                    <p class="mt-3 text-lg text-gray-800">{{$post->settings['hero_subtitle'] ?? ''}}</p>
                </div>

                <div class=" mt-10 max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8  ">
                    <div class=" relative max-w-5xl mx-auto ">
                        @if(!empty($post->settings['hero_video']))
                            <video controls loop class="object-fit-fill  img-fluid rounded-lg" autoplay muted>
                                <source src="{{getUploadsUrl()}}/{{$post->settings['hero_video']}}"
                                        type="video/mp4">
                            </video>
                        @endif
                        <div class="absolute inset-0 w-full h-full">
                            <div class="flex flex-col justify-center items-center w-full h-full">
                                <a href="{{getUploadsUrl()}}/{{$post->settings['hero_video']}}"
                                   class="inline-flex glightbox justify-center items-center gap-x-1.5 text-center text-sm bg-white text-gray-800 hover:text-gray-600 rounded-full transition focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-white transition py-3 px-4"
                                   href="#">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M10.804 8 5 4.633v6.734L10.804 8zm.792-.696a.802.802 0 0 1 0 1.392l-6.363 3.692C4.713 12.69 4 12.345 4 11.692V4.308c0-.653.713-.998 1.233-.696l6.363 3.692z"/>
                                    </svg>
                                    {{__(' Play the video')}}
                                </a>
                            </div>
                        </div>

                        <div class="absolute bottom-12 -left-20 -z-[1] w-48 h-48 bg-gradient-to-b from-orange-500 to-white p-px rounded-lg">
                            <div class="bg-white w-48 h-48 rounded-lg"></div>
                        </div>

                        <div class="absolute -top-12 -right-20 -z-[1] w-48 h-48 bg-gradient-to-t from-blue-600 to-cyan-400 p-px rounded-full">
                            <div class="bg-white w-48 h-48 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Hero -->

    <!-- Hero -->

    <div id="about" class="bg-white mb-5 ">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="mx-auto max-w-4xl lg:text-center">
                <h1 class="text-base font-semibold leading-7 text-indigo-600">{{$post->settings['feature_section_name'] ?? ''}}</h1>
                <p class="mt-3 text-2xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                    {{$post->settings['feature_highlight_title'] ?? ''}}
                </p>
                <p class="mt-3 text-gray-600 dark:text-gray-400">
                    {{$post->settings['feature_highlight_subtitle'] ?? ''}}
                </p>
            </div>


            <!-- Icon Blocks -->
            <div class="max-w-[85rem] px-4  sm:px-6 lg:px-8 lg:py-10 mx-auto">
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 items-center gap-12">
                    <!-- Icon Block -->
                    <div class="text-center">
                        <div class="flex justify-center items-center w-12 h-12 bg-gray-50 border border-gray-200 rounded-full mx-auto ">
                            {!! $post->settings['feature_highlight_feature_1_icon'] ?? '' !!}
                        </div>
                        <div class="mt-3">
                            <h3 class="text-lg font-semibold text-gray-800"> {{$post->settings['feature_highlight_feature_1_title'] ?? ''}}</h3>
                            <p class="mt-1 text-gray-800">
                                {{$post->settings['feature_highlight_feature_1_subtitle'] ?? ''}}
                            </p>
                        </div>
                    </div>
                    <!-- End Icon Block -->

                    <!-- Icon Block -->
                    <div class="text-center">
                        <div class="flex justify-center items-center w-12 h-12 bg-gray-50 border border-gray-200 rounded-full mx-auto ">
                            {!! $post->settings['feature_highlight_feature_2_icon'] ?? '' !!}
                        </div>
                        <div class="mt-3">
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{$post->settings['feature_highlight_feature_2_title'] ?? ''}}
                            </h3>
                            <p class="mt-1 text-gray-800">
                                {{$post->settings['feature_highlight_feature_2_subtitle'] ?? ''}}
                            </p>
                        </div>
                    </div>
                    <!-- End Icon Block -->

                    <!-- Icon Block -->
                    <div class="text-center">
                        <div class="flex justify-center items-center w-12 h-12 bg-gray-50 border border-gray-200 rounded-full mx-auto">
                            {!! $post->settings['feature_highlight_feature_3_icon'] ?? '' !!}
                        </div>
                        <div class="mt-3">
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{$post->settings['feature_highlight_feature_3_title'] ?? ''}}
                            </h3>
                            <p class="mt-1 text-gray-800">
                                {{$post->settings['feature_highlight_feature_3_subtitle'] ?? ''}}
                            </p>
                        </div>
                    </div>
                    <!-- End Icon Block -->

                    <!-- Icon Block -->
                    <div class="text-center">
                        <div class="flex justify-center items-center w-12 h-12 bg-gray-50 border border-gray-200 rounded-full mx-auto">
                            {!! $post->settings['feature_highlight_feature_4_icon'] ?? '' !!}
                        </div>
                        <div class="mt-3">
                            <h3 class="text-lg font-semibold text-gray-800 ">{{$post->settings['feature_highlight_feature_4_title'] ?? ''}}</h3>
                            <p class="mt-1 text-gray-800">
                                {{$post->settings['feature_highlight_feature_4_subtitle'] ?? ''}}
                            </p>
                        </div>
                    </div>
                    <!-- End Icon Block -->
                </div>
            </div>
            <!-- End Icon Blocks -->
            <!-- End Icon Blocks -->

        </div>
    </div>



    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-10 mx-auto">
        <div class="relative p-6 md:p-16">
            <!-- Grid -->
            <div class="relative z-10 lg:grid lg:grid-cols-12 lg:gap-16 lg:items-center">
                <div class="mb-10 lg:mb-0 lg:col-span-6 lg:col-start-8 lg:order-2">
                    <h2 class="text-2xl text-gray-800 font-bold sm:text-3xl">
                        {{$post->settings['feature_title'] ?? ''}}
                    </h2>

                    <!-- Tab Navs -->
                    <nav class="grid gap-4 mt-5 md:mt-10" aria-label="Tabs" role="tablist">
                        <button type="button"
                                class="hs-tab-active:bg-white hs-tab-active:shadow-md hs-tab-active:hover:border-transparent text-left hover:bg-gray-200 p-4 md:p-5 rounded-xl  active"
                                id="tabs-with-card-item-1" data-hs-tab="#tabs-with-card-1"
                                aria-controls="tabs-with-card-1" role="tab">
            <span class="flex">
                  {!! $post->settings['feature_1_icon'] ?? '' !!}

              <span class="grow ml-6">
                <span class="block text-lg font-semibold hs-tab-active:text-blue-600 text-gray-800 dark:hs-tab-active:text-blue-500">  {!! $post->settings['feature_1_title'] ?? '' !!}</span>
                <span class="block mt-1 text-gray-800">{!! $post->settings['feature_1_subtitle'] ?? '' !!}</span>
              </span>
            </span>
                        </button>

                        <button type="button"
                                class="hs-tab-active:bg-white hs-tab-active:shadow-md hs-tab-active:hover:border-transparent text-left hover:bg-gray-200 p-4 md:p-5 rounded-xl"
                                id="tabs-with-card-item-2" data-hs-tab="#tabs-with-card-2"
                                aria-controls="tabs-with-card-2" role="tab">
            <span class="flex">
                  {!! $post->settings['feature_2_icon'] ?? '' !!}

              <span class="grow ml-6">
                <span class="block text-lg font-semibold hs-tab-active:text-blue-600 text-gray-800 dark:hs-tab-active:text-blue-500"> {!! $post->settings['feature_2_title'] ?? '' !!}</span>
                <span class="block mt-1 text-gray-800"> {!! $post->settings['feature_2_subtitle'] ?? '' !!}</span>
              </span>
            </span>
                        </button>

                        <button type="button"
                                class="hs-tab-active:bg-white hs-tab-active:shadow-md hs-tab-active:hover:border-transparent text-left hover:bg-gray-200 p-4 md:p-5 rounded-xl"
                                id="tabs-with-card-item-3" data-hs-tab="#tabs-with-card-3"
                                aria-controls="tabs-with-card-3" role="tab">
            <span class="flex">
                  {!! $post->settings['feature_3_icon'] ?? '' !!}

              <span class="grow ml-6">
                <span class="block text-lg font-semibold hs-tab-active:text-blue-600 text-gray-800"> {!! $post->settings['feature_3_title'] ?? '' !!}</span>
                <span class="block mt-1 text-gray-800 "> {!! $post->settings['feature_3_subtitle'] ?? '' !!}</span>
              </span>
            </span>
                        </button>
                    </nav>
                    <!-- End Tab Navs -->
                </div>
                <!-- End Col -->

                <div class="lg:col-span-6">
                    <div class="relative">
                        <!-- Tab Content -->
                        <div>
                            <div id="tabs-with-card-1" role="tabpanel" aria-labelledby="tabs-with-card-item-1">

                                @if(!empty($post->settings['feature_1_image']))
                                    <img  src="{{getUploadsUrl()}}/{{$post->settings['feature_1_image']}}"class="shadow-xl shadow-gray-200 rounded-xl dark:shadow-gray-900/[.2]"
                                            alt="Image"
                                    />
                                @endif

                            </div>

                            <div id="tabs-with-card-2" class="hidden" role="tabpanel"
                                 aria-labelledby="tabs-with-card-item-2">
                                @if(!empty($post->settings['feature_2_image']))
                                    <img
                                            src="{{getUploadsUrl()}}/{{$post->settings['feature_2_image']}}"
                                            alt="{{$post->title ?? ''}}"
                                            class="shadow-xl shadow-gray-200 rounded-xl dark:shadow-gray-900/[.2]"/>
                                @endif
                            </div>

                            <div id="tabs-with-card-3" class="hidden" role="tabpanel"
                                 aria-labelledby="tabs-with-card-item-3">
                                @if(!empty($post->settings['feature_3_image']))
                                    <img
                                            src="{{getUploadsUrl()}}/{{$post->settings['feature_3_image']}}"
                                            alt="{{$post->title ?? ''}}"
                                            class="shadow-xl shadow-gray-200 rounded-xl dark:shadow-gray-900/[.2]"
                                    />
                                @endif
                            </div>
                        </div>
                        <!-- End Tab Content -->

                        <!-- SVG Element -->
                        <div class="hidden absolute top-0 right-0 translate-x-20 md:block lg:translate-x-20">
                            <svg class="w-16 h-auto text-orange-500" width="121" height="135" viewBox="0 0 121 135"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 16.4754C11.7688 27.4499 21.2452 57.3224 5 89.0164" stroke="#000"
                                      stroke-width="10" stroke-linecap="round"/>
                                <path d="M33.6761 112.104C44.6984 98.1239 74.2618 57.6776 83.4821 5" stroke="#5046E5"
                                      stroke-width="10" stroke-linecap="round"/>
                                <path d="M50.5525 130C68.2064 127.495 110.731 117.541 116 78.0874" stroke="#000"
                                      stroke-width="10" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <!-- End SVG Element -->
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->

            <!-- Background Color -->
            <div class="absolute inset-0 grid grid-cols-12 w-full h-full">
                <div class="col-span-full lg:col-span-7 lg:col-start-6 bg-gray-100 w-full h-5/6 rounded-xl sm:h-3/4 lg:h-full dark:bg-white/[.075]"></div>
            </div>
            <!-- End Background Color -->
        </div>
    </div>



    <!-- ====== About Section End -->

    <div class="max-w-2xl mx-auto text-center py-10 ">

        <h2 class="text-2xl font-bold md:text-4xl md:leading-tight">
            {{$post->settings['about_section_title'] ?? ''}}
        </h2>
        <p class="mt-2 text-gray-600">      {{$post->settings['about_section_subtitle'] ?? ''}}</p>
    </div>

    <div class="bg-white">
        <div class="mx-auto max-w-7xl sm:px-6 sm:py-20 lg:px-8">

            <div class="relative isolate overflow-hidden bg-dark px-6 shadow-2xl sm:rounded-3xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0">


                <div class="mx-auto max-w-md text-center text-white lg:mx-0 lg:flex-auto lg:py-32 lg:text-left">

                    @if(!empty($post->settings['signup_reasons']))
                        @foreach($post->settings['signup_reasons'] as $reason)

                            <div class="relative pl-9 mb-2 mt-3">

                                <dt class="inline text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#AD52FF"
                                         class="absolute top-1 left-1 h-5 w-5 text-indigo-600" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg> {{$reason}}</dt>
                            </div>

                        @endforeach
                    @endif
                    <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
                        <a href="#signup"
                           class="rounded-md bg-primary text-white px-3.5 py-2.5 text-sm font-semibold shadow-sm  focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">{{__('Get started')}}</a>
                        <a href="#pricing" class="text-sm text-white font-semibold leading-6">{{__('See Pricing')}}
                            <span aria-hidden="true">→</span></a>
                    </div>
                </div>
                <div class="relative mt-16 h-80 lg:mt-8">

                    @if(!empty($post->settings['about_section_image']))
                        <img
                                src="{{getUploadsUrl()}}/{{$post->settings['about_section_image']}}"
                                alt="{{$post->title ?? ''}}"
                                class="absolute left-0 top-0 w-[57rem] max-w-none rounded-md bg-white/5 ring-1 ring-white/10"
                                width="1824" height="1080"
                        />
                    @endif

                </div>
            </div>
        </div>
    </div>


    <!-- ====== Pricing Section Start ====== -->
    <section id="pricing" class="relative z-20 overflow-hidden bg-white py-20 pb-12">
        <div class="container">

            <div class="-mx-4 flex flex-wrap">
                <div class="w-full px-4">

                    <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">

                        <h2 class="text-2xl font-bold md:text-4xl md:leading-tight">
                            {{$post->settings['pricing_section_title'] ?? ''}}
                        </h2>
                        <p class="mt-1 text-gray-600">    {{$post->settings['pricing_section_subtitle'] ?? ''}}</p>
                    </div>

                </div>
            </div>

            <div class="flex flex-wrap items-center justify-center">

                @foreach($subscription_plans as $subscription_plan)

                    <div class="w-full md:w-1/2 lg:w-1/3">
                        <div class="relative z-10 mb-10 overflow-hidden rounded-xl {{$subscription_plan->is_featured ? 'bg-dark from-dark to-[#179BEE]' : 'border border-dark border-opacity-20'}} bg-white py-10 px-8 text-center shadow-pricing sm:p-12 lg:py-10 lg:px-6 xl:p-12">

                            @if($subscription_plan->is_featured)
                                <span class="mb-5 inline-block rounded-full  bg-info py-2 px-6 text-base font-semibold uppercase text-white">{{$subscription_plan->featured_text ?? __('Popular')}}</span>
                            @endif


                            <span class="mb-2 block text-base font-medium uppercase {{$subscription_plan->is_featured ? 'text-white' : 'text-dark'}}">{{$subscription_plan->name ?? ''}}</span>


                            @if($subscription_plan->price_monthly && $subscription_plan->price_monthly > 0)
                                <div class="flex justify-center items-baseline my-8">
                                    <span class="mr-2 text-5xl font-extrabold {{$subscription_plan->is_featured ? 'text-white' : 'text-dark'}}">{{formatCurrency($subscription_plan->price_monthly ?? '',getWorkspaceCurrency($super_settings), true)}}</span>
                                    <span class="text-gray-500 dark:text-gray-400">/{{__('month')}}</span>
                                </div>

                            @elseif($subscription_plan->price_yearly && $subscription_plan->price_yearly > 0)
                                <span class="mr-2 text-5xl font-extrabold {{$subscription_plan->is_featured ? 'text-white' : 'text-dark'}}">{{formatCurrency($subscription_plan->price_yearly ?? '',getWorkspaceCurrency($super_settings), true)}}</span>
                                <span class="text-gray-500 dark:text-gray-400">/{{__('year')}}}}</span>

                            @endif



                            @if(!empty($subscription_plan->features))
                                <ul role="list" class="mb-8 space-y-4 text-left">
                                    @foreach($subscription_plan->features as $feature)
                                        <li class="flex items-center space-x-3 text-sm {{$subscription_plan->is_featured ? 'text-white' : 'text-body-color'}}">
                                            <!-- Icon -->
                                            <svg class="flex-shrink-0 w-5 h-5 text-primary dark:text-green-400"
                                                 fill="currentColor" viewBox="0 0 20 20"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                            <span>{{$feature}}</span>
                                        </li>
                                    @endforeach


                                </ul>

                            @endif

                            <div class="w-full">
                                <a href="#signup"
                                   class="{{$subscription_plan->is_featured ? 'inline-block rounded-full border border-white bg-white py-4 px-11 text-center text-base font-medium text-dark transition duration-300 ease-in-out hover:border-dark hover:bg-dark hover:text-white' : 'inline-block rounded-full border border-[#D4DEFF] bg-transparent py-4 px-11 text-center text-base font-medium text-primary transition duration-300 ease-in-out hover:border-primary hover:bg-primary hover:text-white'}}">
                                    {{__('Free Trial')}}
                                </a>
                            </div>

                            </span>
                        </div>
                    </div>

                @endforeach


            </div>
        </div>
    </section>
    <!-- ====== Pricing Section End -->

    <!-- ====== Faq Section Start -->
    <!-- FAQ -->
    <div id="faq" class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <!-- Title -->
        <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
            {{$post->settings['faq_section_name'] ?? __('FAQ')}}
            <h2 class="text-2xl font-bold md:text-4xl md:leading-tight">
                {{$post->settings['faq_section_title'] ?? ''}}
            </h2>
            <p class="mt-1 text-gray-600">  {{$post->settings['faq_section_subtitle'] ?? ''}}</p>
        </div>
        <!-- End Title -->

        <div class="max-w-2xl mx-auto">
            <!-- Accordion -->
            <div class="hs-accordion-group">

                @if(!empty($post->settings['faq_questions']))
                    @foreach($post->settings['faq_questions'] as $key => $question)
                        <div class="hs-accordion @if($loop->iteration == 1) active @endif hs-accordion-active:bg-gray-100 rounded-xl p-6 "
                             id="hs-basic-with-title-and-arrow-stretched-heading-{{$key}}">
                            <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-left text-gray-800 transition hover:text-gray-500"
                                    aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-{{$key}}">
                                {{$question}}
                                <svg class="hs-accordion-active:hidden block w-3 h-3 text-gray-600 group-hover:text-gray-500 "
                                     width="16" height="16" viewBox="0 0 16 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <svg class="hs-accordion-active:block hidden w-3 h-3 text-gray-600 group-hover:text-gray-500 "
                                     width="16" height="16" viewBox="0 0 16 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 11L8.16086 5.31305C8.35239 5.13625 8.64761 5.13625 8.83914 5.31305L15 11"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                            <div id="hs-basic-with-title-and-arrow-stretched-collapse-{{$key}}"
                                 class="hs-accordion-content @if($loop->iteration > 1) hidden @endif  w-full overflow-hidden transition-[height] duration-300"
                                 aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-{{$key}}">
                                <p class="text-gray-800">
                                    {{$post->settings['faq_answers'][$key] ?? ''}}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
            <!-- End Accordion -->
        </div>
    </div>
    <!-- End FAQ -->

    <section id="customers" class="pt-20 md:pt-[120px]">
        <div class="container px-4">

            <div class="flex flex-wrap">
                <div class="mx-4 w-full">
                    <div class="mx-auto mb-[60px] max-w-[620px] text-center lg:mb-20">

                        <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">

                            <h2 class="text-2xl font-bold md:text-4xl md:leading-tight">
                                {{$post->settings['testimonials_section_title'] ?? ''}}
                            </h2>
                            <p class="mt-1 text-gray-600">   {{$post->settings['testimonials_section_subtitle'] ?? ''}}</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="flex flex-wrap">

                @if(!empty($post->settings['testimonials']))

                    @foreach($post->settings['testimonials'] as $key => $testimonial)

                        <div class="w-full px-4 md:w-1/2 lg:w-1/3">
                            <div class="ud-single-testimonial mb-12 bg-white p-8 shadow-testimonial">

                                <div class="relative mt-8 flex items-center gap-x-4">
                                    <div class="mr-2 mb-2 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg bg-info bg-opacity-5 text-white">
                                        {{$post->settings['testimonials_author'][$key]['0'] ?? ''}}
                                    </div>
                                    <div class="leading-4">
                                        <p class="font-bold text-gray-900 mb-1">
                                            <a href="#">
                                                <span class="absolute inset-0"></span>
                                                {{$post->settings['testimonials_author'][$key] ?? ''}}
                                            </a>
                                        </p>
                                        <p class="text-gray-600 text-sm">{{$post->settings['testimonials_author_title'][$key] ?? ''}}</p>
                                    </div>
                                </div>
                                <div class="ud-testimonial-content mb-6">

                                    <p class="mt-5 line-clamp-3 text-base leading-6 text-gray-600">
                                        {{$testimonial}}
                                    </p>
                                </div>


                            </div>
                        </div>

                    @endforeach

                @endif

            </div>
        </div>
    </section>

    <div class="relative overflow-hidden" id="signup">
        <div class="mx-auto max-w-screen-md py-12 px-4 sm:px-6 md:max-w-screen-xl md:py-20 lg:py-32 md:px-8">
            <div class="md:pr-8 md:w-1/2 xl:pr-0 xl:w-5/12">
                <!-- Title -->
                <h1 class="text-3xl text-gray-800 font-bold md:text-4xl md:leading-tight lg:text-3xl lg:leading-tight">
                    {{$post->settings['signup_subtitle'] ?? ''}}
                </h1>

                <p class="mt-3 text-base text-gray-500">
                    {{$post->settings['signup_title'] ?? ''}}
                </p>
                <!-- End Title -->
                <div class="mb-3 mt-3 text-gray-500">
                    <label>{{__('Already have an account?')}} <a class="text-blue-600 decoration-2 hover:underline font-medium" href="{{$base_url}}/app/login">{{__('Sign in')}}</a></label>
                </div>

                <!-- Form -->
                <form method="post" action="{{$base_url}}/signup">
                    @if ($errors->any())
                        <div class="mb-4" id="has_signup_errors">
                            @foreach ($errors->all() as $error)
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-2 rounded relative"
                                     role="alert">{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif


                    <div class="grid grid-cols-2 gap-4">
                        <div class="mt-4">
                            <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">{{__('First Name')}}</label>
                            <input type="text" name="first_name" id="first_name"class="border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-3" value="{{old('first_name')}}" placeholder="{{__('Enter your first name')}}" required="">
                        </div>
                        <div class="mt-4">
                            <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">{{__('Last Name')}}</label>
                            <input type="text"  name="last_name" id="last_name" class="border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-3" value="{{old('last_name')}}" placeholder="{{__('Enter your last name')}}" required="">
                        </div>
                    </div>

                        <div class="mt-4">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900"> {{__('Email address')}}</label>
                            <input type="email" name="email" id="email" class=" border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-3" value="{{old('email')}}" placeholder="{{__('Your email address')}}" required="">
                        </div>

                        <div class="mt-4">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900"> {{__('Password')}}</label>
                            <input type="password" name="password" id="password" class=" border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-3" placeholder="{{__('Choose a password')}}" required="">
                        </div>
                        <div class="mt-4 mb-5">
                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900"> {{__('Confirm Password')}}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class=" border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-3" placeholder="{{__('Confirm password')}}" required="">
                        </div>
                        @csrf
                    <div class="grid">
                        <button type="submit"
                                class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-primary text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800 sm:p-4">{{__('Sign up')}}</button>
                    </div>
                    <div class="mb-2 mt-2 ">
                        <label class="text-xs sm:text-sm text-gray-600">{{__('By submitting this form I have read and acknowledged the ')}}<a class="text-blue-600 decoration-2 hover:underline font-medium" href="{{$base_url}}/privacy-policy">{{__('Privacy Policy')}}</a> & <a class="text-blue-600 decoration-2 hover:underline font-medium" href="{{$base_url}}/terms-of-service">{{__('Terms of Service')}}</a> </label>
                    </div>
                </form>
                <!-- End Form -->
            </div>
        </div>
        @if(!empty($post->settings['signup_image']))
            <div class="hidden md:block md:absolute md:top-0 md:left-1/2 md:right-0 h-full bg-no-repeat bg-center bg-cover"
                 style="background-image: url('{{getUploadsUrl()}}/{{$post->settings['signup_image']}}')"></div>
    @endif
    <!-- End Col -->
    </div>
@endsection
