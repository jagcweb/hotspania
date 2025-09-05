@extends('layouts.admin')

@section('title') Reportes @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Reportes</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table mt-4">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Usuario que reporta</th>
                                <th>Sesión</th>
                                <th>Usuario reportado</th>
                                <th>Motivo</th>
                                <th>Detalles</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>
                                            @if($report->user)
                                                {{ $report->user->nickname }} (ID: {{ $report->user_id }})
                                            @else
                                                Sin usuario
                                            @endif
                                        </td>
                                        <td>{{ $report->session_id }}</td>
                                        <td>
                                            @if($report->reportedUser)
                                                {{ $report->reportedUser->nickname }} (ID: {{ $report->reported_user_id }})
                                            @else
                                                Sin usuario
                                            @endif
                                        </td>
                                        <td>{{ $report->reason }}</td>
                                        <td>
                                            <span 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailsModal{{ $report->id }}" 
                                                style="cursor: pointer;"
                                            >
                                                <i class="fas fa-eye"></i> Ver detalles
                                            </span>

                                            <!-- Modal -->
                                            <div class="modal fade" id="detailsModal{{ $report->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $report->id }}" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="detailsModalLabel{{ $report->id }}">Detalles del reporte</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <strong>Motivo reporte:</strong> {{ $report->reason }}<br>
                                                    {{ $report->details }}
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                        </td>
                                        <td>
                                            -
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