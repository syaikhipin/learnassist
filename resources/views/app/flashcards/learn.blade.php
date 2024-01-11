@extends(config('app.layout'))
@section('head')
    <link rel="stylesheet" href="/assets/lib/reveal/reveal.css">
    <link rel="stylesheet" href="/assets/lib/reveal/theme/white.css">
@endsection
@section('content')

    <a href="/app/flashcards/shuffle?uuid={{$collection->uuid}}" class="btn btn-primary"
       id="btn_add_flashcard">{{__('Shuffle')}}</a>
    <button class="btn btn-primary" id="fullscreenBtn">{{__('Fullscreen')}}</button>

    <div class="reveal">
        <div class="slides">
            @foreach($cards as $card)
                <section>
                    <div class="card">
                        <div class="card-body front">
                            {{$card->title}}
                        </div>
                        <div class="card-body back">
                            {!! app_clean_html_content($card->description) !!}
                        </div>
                    </div>
                </section>
            @endforeach
        </div>
    </div>

@endsection

@section('scripts')
    <script src="/assets/lib/reveal/reveal.js" crossorigin="anonymous"></script>
    <script>
        (function () {
            "use strict";
            document.addEventListener('DOMContentLoaded', () => {

                Reveal.initialize({
                    controls: true,
                    progress: true,
                    center: true,
                    hash: true,
                    transition: 'slide',
                    controlsTutorial: true,
                    controlsLayout: 'edges',
                    controlsBackArrows: 'visible',
                    slideNumber: true,
                });

                document.querySelectorAll('.reveal .slides section').forEach(section => {
                    let card = section.querySelector('.card');
                    card.addEventListener('click', () => {
                        card.classList.toggle('flipped');
                    });
                });

                document.querySelector('#fullscreenBtn').addEventListener('click', () => {
                    let slidesElement = document.querySelector('.reveal .slides');
                    if (!document.fullscreenElement) {
                        if (slidesElement.requestFullscreen) {
                            slidesElement.requestFullscreen();
                        } else if (slidesElement.mozRequestFullScreen) { /* Firefox */
                            slidesElement.mozRequestFullScreen();
                        } else if (slidesElement.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
                            slidesElement.webkitRequestFullscreen();
                        } else if (slidesElement.msRequestFullscreen) { /* IE/Edge */
                            slidesElement.msRequestFullscreen();
                        }
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.mozCancelFullScreen) { /* Firefox */
                            document.mozCancelFullScreen();
                        } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
                            document.webkitExitFullscreen();
                        } else if (document.msExitFullscreen) { /* IE/Edge */
                            document.msExitFullscreen();
                        }
                    }
                });

            });
        })();
    </script>
@endsection
