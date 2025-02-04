<div class="modal fade" id="datos-vision-ia-{{ $image->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Datos de la imagen {{ $image->id }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                @php 
                    $data = json_decode($image->vision_data, true); 
                    $visionData = $data["vision"] ?? [];
                    $vertexData = $data["vertex"] ?? [];
                    $opencvData = $data["opencv"] ?? [];
                @endphp

                @if($visionData != [])
                    <div style="background:#f3f3f3; padding:20px;">
                        <h3>Vision AI</h3>
                        <p class="w-100 mt-3" style="font-weight:bold;">Etiquetas</p>
                        <ul>
                            @foreach ($visionData['labels'] as $label)
                                <li>{{ $label }}</li>
                            @endforeach
                        </ul>

                        <p class="w-100 mt-3" style="font-weight:bold;">Texto Detectado</p>
                        <ul>
                            @foreach ($visionData['text'] as $text)
                                <li>{{ $text }}</li>
                            @endforeach
                        </ul>

                        <p class="w-100 mt-3" style="font-weight:bold;">Búsqueda Segura</p>
                        <ul>
                            @foreach ($visionData['safeSearch'] as $key => $value)
                                <li>{{ ucfirst($key) }}: {{ $value }}</li>
                            @endforeach
                        </ul>

                        <p class="w-100 mt-3" style="font-weight:bold;">Propiedades de la imagen</p>
                        @foreach ($visionData['imageProperties'] as $property)
                            <div class="mt-2" style="background:#e2e2e2;">
                                <p><strong>Score:</strong> {{ $property['score'] }}</p>
                                <p><strong>Pixel Fraction:</strong> {{ $property['pixelFraction'] }}</p>
                            </div>
                        @endforeach

                        <p class="w-100 mt-3" style="font-weight:bold;">Caras Detectadas</p>
                        @if (!empty($visionData['faces']))
                            <p>{{ count($visionData['faces']) }} cara(s) detectada(s).</p>
                        @else
                            <p>No se detectaron caras.</p>
                        @endif

                        <p class="w-100 mt-3" style="font-weight:bold;">Objetos Detectados</p>
                        @if (!empty($visionData['objects']))
                            <ul>
                                @foreach ($visionData['objects'] as $object)
                                    <li>{{ $object }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>No se detectaron objetos.</p>
                        @endif

                        <p class="w-100 mt-3" style="font-weight:bold;">Lugares de Referencia</p>
                        @if (!empty($visionData['landmarks']))
                            <ul>
                                @foreach ($visionData['landmarks'] as $landmark)
                                    <li>{{ $landmark }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>No se detectaron lugares de referencia.</p>
                        @endif

                        <p class="w-100 mt-3" style="font-weight:bold;">Logos</p>
                        @if (!empty($visionData['logos']))
                            <ul>
                                @foreach ($visionData['logos'] as $logo)
                                    <li>{{ $logo }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>No se detectaron logos.</p>
                        @endif
                    </div>
                @endif

                @if($vertexData != [])
                    <div style="background:#f3f3f3; padding:20px;">
                        <h3>Vertex AI</h3>
                    </div>
                @endif

                @if($opencvData != [])
                    <div style="background:#f3f3f3; padding:20px;">
                        <h3>OpenCV</h3>
                    </div>
                @endif

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->