@extends('layouts.admin')

@section('title') Chats @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Chats</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table mt-4">
                            <thead>
                              <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            @php

                            @endphp
                            <tbody>
                                @foreach($files as $file)
                                    @php
                                        $basename = basename($file); // solo el nombre del archivo
                                        $parts = explode('_', $basename); // ['chat', 'user', '42', '20250912', '103030.txt']

                                        // Usuario
                                        $userId = $parts[2] === 'null' ? 'Invitado' : $parts[2];
                                        $user = $parts[2] === 'null' ? 'Invitado' : \App\Models\User::find($parts[2]);
                                        $userName = is_object($user) ? $user->nickname : 'Invitado';
                                        // Fecha y hora
                                        $datePart = $parts[3]; // YYYYMMDD
                                        $timePart = str_replace('.txt', '', $parts[4]); // HHMMSS
                                        $formattedDate = \Carbon\Carbon::createFromFormat('YmdHis', $datePart . $timePart)
                                                            ->format('d/m/Y H:i:s');
                                    @endphp
                                    <tr>
                                        <td>{{$formattedDate}}</td>
                                        <td>{{$userName}}</td>
                                        <td>
                                            <a href="{{ route('chat.transcript.view', $file) }}" target="_blank">
                                                <i class="fas fa-eye text-dark"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<!-- Bootstrap Tables js -->

@endsection

@section('css')



@endsection