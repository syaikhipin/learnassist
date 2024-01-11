@extends(config('app.layout'))
@section('content')

    <div class="row">
        <div class="col-md-4">
            <div class="card overflow-auto overflow-x-hidden mb-4 mb-lg-0">
                <div class=" p-2 card-body-messages">
                    <div class="d-flex mb-4 pb-1 p-1">
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <p class="fw-bold">{{__('Chat History')}}</p>
                            <div class="user-progress">
                                <a href="/app/ai-chat" class=" text-end btn w-60 btn-rounded btn-dark mb-3"><i
                                            class="bi bi-plus-lg"></i> {{__('New Chat')}}</a>
                            </div>
                        </div>
                    </div>

                    <ul class="p-0 m-0">
                        @foreach($recent_chat_sessions as $recent_chat_session)

                            <a href="/app/ai-chat?session_id={{$recent_chat_session->uuid}}" class="">
                                <div class="d-flex mb-4 pb-1 p-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        @php
                                            $initial = $recent_chat_session->title[0];
                                            $bgColors = ['primary', 'secondary', 'success', 'warning', 'info', 'dark'];
                                            $bgIndex = array_search($initial, range('A', 'Z')) % count($bgColors);
                                            $bgColor = $bgColors[$bgIndex]
                                        @endphp
                                        <span class="avatar-initial rounded bg-{{ $bgColor }} text-white">{{ $initial }}</span>

                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0"> {{$recent_chat_session->title}}</h6>
                                            <small class="text-muted"> {{$recent_chat_session->created_at->diffForHumans()}}</small>
                                        </div>
                                        <div class="user-progress">
                                            <small class="fw-semibold"><a
                                                        class="text-end ms-4 btn btn-link text-dark px-3 mb-0"
                                                        data-delete-item="true"
                                                        href="{{$base_url}}/app/delete/chat-session/{{$recent_chat_session->uuid}}"><i
                                                            class="bi bi-trash3-fill"></i></a></small>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        @endforeach

                    </ul>

                </div>
            </div>
        </div>
        <div class="col-md-8 mb-4">
            <div class="overflow-x-hidden  overflow-hidden">
                <div class="card">
                    <div class="card-header border-bottom bg-white mb-4 ">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="app-avatar-text bg-info-gradient text-white">{{$user->first_name[0]}}{{$user->last_name[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 d-block  fw-bolder text-dark">{{$user->first_name}} {{$user->last_name}}</h6>
                                        <small class="text-muted">{{$today}}</small>


                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-body overflow-auto card-body-messages overflow-x-hidden" id="messages">

                        @if(count($chats) > 0)

                            @foreach($chats as $chat)

                                @if($chat->type == 'user')
                                    <div class="row justify-content-end mb-4">
                                        <div class="col-auto">
                                            <div class="card bg-primary-gradient">
                                                <div class="card-body  py-2 px-3">
                                                    <p class="mb-0 text-start text-white ">
                                                        {!! app_clean_html_content($chat->message) !!}
                                                    </p>
                                                    <div class="d-flex  text-start text-sm opacity-6">
                                                        <small class="text-white">
                                                            {{$chat->created_at->diffForHumans()}}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else

                                    <div class="row justify-content-start mb-4">
                                        <div class="col-auto">
                                            <div class="card bg-white">
                                                <div class="card-body  py-2 px-3">


                                                    <p class="mb-0 text-start text-dark ">
                                                        {!! app_clean_html_content($chat->message) !!}
                                                    </p>


                                                    <div class="d-flex  text-start text-sm opacity-6">
                                                        <small class="text-dark">
                                                            {{$chat->created_at->diffForHumans()}}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endif

                            @endforeach

                        @else

                            <div id="no_messages_div" class="row justify-content-center mb-4">
                                <div class="col-auto text-center">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor"
                                         class="bi bi-chat-dots-fill mt-4" viewBox="0 0 16 16">
                                        <path d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7zM5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                    </svg>

                                    <p class="mt-4">  {{__('Opps! No messages')}}</p>
                                    <a href="/app/ai-chat" class="btn w-60 btn-primary">{{__('Start New Chat')}}</a>

                                </div>
                            </div>

                        @endif

                    </div>
                    <div class="card-footer d-block mt-4">
                        <form class="align-items-center" id="form_chat_input">
                            <div class="d-flex">
                                <div class="input-group">
                                    <input type="text" autofocus id="message" name="message" class="form-control"
                                           placeholder="Type Message here" aria-label="Message example input">
                                </div>
                                <button id="send_message" class="btn btn-dark mb-0 ms-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-play" viewBox="0 0 16 16">
                                        <path d="M10.804 8 5 4.633v6.734L10.804 8zm.792-.696a.802.802 0 0 1 0 1.392l-6.363 3.692C4.713 12.69 4 12.345 4 11.692V4.308c0-.653.713-.998 1.233-.696l6.363 3.692z"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" id="chat_session_id" name="session_id"
                                   value="{{$active_chat_session->uuid ?? ''}}">
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        "use strict"
        window.addEventListener('DOMContentLoaded', () => {


            const form_chat_input = document.getElementById('form_chat_input');
            const message = document.getElementById('message');
            const messages = document.getElementById('messages');
            const no_messages_div = document.getElementById('no_messages_div');

            function escapeHtml(string) {
                let div = document.createElement('div');
                div.textContent = string
                return div.innerHTML;
            }

            messages.scrollTop = messages.scrollHeight;

            form_chat_input.addEventListener('submit', (e) => {
                e.preventDefault();

                if (no_messages_div) {
                    no_messages_div.remove();
                }

                let message_value = message.value;
                message_value = escapeHtml(message_value);

                if (message_value.trim() === '') {
                    return;
                }

                const formData = new FormData(form_chat_input);
                formData.append('message', message.value);
                formData.append('session_id', document.getElementById('chat_session_id').value);

                messages.insertAdjacentHTML(
                    'beforeend',
                    `
                    <div class="row justify-content-end mb-4">
                        <div class="col-auto">
                            <div class="card bg-primary-gradient">
                                <div class="card-body  py-2 px-3">
                                    <p class="mb-0 text-start text-white ">
                                        ${message_value}
                                    </p>
                                    <div class="d-flex  text-start text-sm opacity-6">
                                        <small class="text-white">
                                            ${new Date().toLocaleString()}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
`
                )

                axios.post('/app/chat', formData)
                    .then((response) => {
                        message.value = '';

                        let replied_message = response.data.message;
                        let replied_at = response.data.created_at;
                        // set the chat session id
                        document.getElementById('chat_session_id').value = response.data.session_id;
                        // convert to local time
                        replied_at = new Date(replied_at).toLocaleString();

                        messages.insertAdjacentHTML(
                            'beforeend',
                            `<div class="row justify-content-start mb-4">
                        <div class="col-auto">
                            <div class="card bg-gray-100">
                                <div class="card-body  py-2 px-3">
                                    <p class="mb-0 text-start text-dark ">
                                        ${replied_message}
                                    </p>
                                    <div class="d-flex  text-start text-sm opacity-6">
                                        <small class="text-dark">
                                            ${replied_at}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`
                        );

                        // scroll to the bottom
                        messages.scrollTop = messages.scrollHeight;

                    })
                    .catch((error) => {
                        console.log(error);
                    });
            });

        });
    </script>

@endsection
