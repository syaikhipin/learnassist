@extends(config('app.layout'))
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-dark fw-bolder">{{__('Contacts')}}</h4>
        <div>
            <a href="{{$base_url}}/app/contact" class="btn btn-dark">{{__('Add Contact')}}</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="app-data-table" class="table table-light">
                <thead>
                <tr>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Title')}}</th>
                    <th>{{__('Owner')}}</th>
                    <th>{{__('Email')}}</th>
                    <th>{{__('Phone')}}</th>

                    <th class="text-end">{{__('Manage')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contacts as $contact)
                    <tr>
                        <td>
                            <div class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">

                                    @if($contact->first_name['0'] == 'A')


                                        <span class="avatar-initial rounded bg-primary text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'B')
                                        <span class="avatar-initial rounded bg-warning text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'C')
                                        <span class="avatar-initial rounded bg-success text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'D')
                                        <span class="avatar-initial rounded bg-danger text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'E')
                                        <span class="avatar-initial rounded bg-warning text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'F')
                                        <span class="avatar-initial rounded bg-info text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'G')
                                        <span class="avatar-initial rounded bg-dark text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'H')
                                        <span class="avatar-initial rounded bg-light text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'I')
                                        <span class="avatar-initial rounded bg-primary text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'J')
                                        <span class="avatar-initial rounded bg-secondary text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'K')
                                        <span class="avatar-initial rounded bg-success text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'L')
                                        <span class="avatar-initial rounded bg-secondary text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'M')
                                        <span class="avatar-initial rounded bg-success text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'N')
                                        <span class="avatar-initial rounded bg-info text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'O')
                                        <span class="avatar-initial rounded bg-dark text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'P')
                                        <span class="avatar-initial rounded bg-warning text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'Q')

                                        <span class="avatar-initial rounded bg-info text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'R')
                                        <span class="avatar-initial rounded bg-secondary text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'S')

                                        <span class="avatar-initial rounded bg-success text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'T')
                                        <span class="avatar-initial rounded bg-danger text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'U')
                                        <span class="avatar-initial rounded bg-warning text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'V')
                                        <span class="avatar-initial rounded bg-info text-white">{{$contact->first_name['0']}}</span>

                                    @elseif($contact->first_name['0'] == 'W')
                                        <span class="avatar-initial rounded bg-dark text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'X')
                                        <span class="avatar-initial rounded bg-light text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'Y')
                                        <span class="avatar-initial rounded bg-primary text-white">{{$contact->first_name['0']}}</span>
                                    @elseif($contact->first_name['0'] == 'Z')
                                        <span class="avatar-initial rounded bg-secondary text-white">{{$contact->first_name['0']}}</span>
                                    @else
                                        <span class="avatar-initial rounded bg-success text-white">A</span>

                                    @endif
{{--                                    <span class="avatar-initial rounded bg-label-primary">{{$contact->first_name['0']}}</span>--}}
                                </div>
                                <a class="fw-bold text-dark mt-1" href="{{$base_url}}/app/contact?uuid={{$contact->uuid}}"><strong>{{$contact->first_name}} {{$contact->last_name}}</strong>
                                </a>

                            </div>

                        </td>
                        <td>
                            {{$contact->title}}
                        </td>
                        <td>
                            @if(!empty($users[$contact->user_id]))
                                {{$users[$contact->user_id]->first_name}} {{$users[$contact->user_id]->last_name}}
                            @endif
                        </td>
                        <td class="fw-bold">
                            {{$contact->email}}
                        </td>
                        <td>
                            {{$contact->phone ?? '--'}}
                        </td>

                        <td class="text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-dark btn-sm btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end" style="">

                                    <li><a class="dropdown-item" href="{{$base_url}}/app/view-contact?uuid={{$contact->uuid}}">{{__('Open')}}</a></li>
                                    <li><a class="dropdown-item" href="{{$base_url}}/app/contact?uuid={{$contact->uuid}}">{{__('Edit')}}</a></li>
                                    <li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" data-delete-item="true" href="{{$base_url}}/app/delete/contact/{{$contact->uuid}}">{{__('Delete')}}</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
