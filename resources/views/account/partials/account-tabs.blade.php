<!-- ==================== SECCIONES CON TABS BOOTSTRAP 4 ==================== -->
<style>
    /* ==================== ESTILOS TABS BOOTSTRAP 4 ==================== */

    .nav-tabs {
        border-bottom: 3px solid rgba(255, 255, 255, 0.1);
        background-color: transparent;
        padding: 0;
        border-radius: 0;
        display: flex;
        justify-content: space-around;
        width: 100%;
        gap: 0;
        margin: 0;
    }

    .nav-tabs .nav-item {
        flex: 1;
        text-align: center;
        padding: 0;
        margin: 0;
    }

    /* Estilos para el BUTTON */
    .nav-tabs .nav-link {
        padding: 15px 0 !important;
        margin: 0 !important;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 100%;
        height: auto;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        border: none !important;
        background-color: transparent !important;
        border-radius: 0 !important;
        border-bottom: 4px solid transparent !important;
        outline: none !important;
    }

    /* Estilos para el ICONO (SVG y/o I) dentro del button */
    .nav-tabs .nav-link svg,
    .nav-tabs .nav-link i {
        font-size: 32px !important;
        width: 32px !important;
        height: 32px !important;
        color: #fff !important;
        fill: #fff !important;
        opacity: 0.6 !important;
        transition: all 0.3s ease !important;
        pointer-events: none !important; /* El icono no debe capturar eventos */
    }

    /* Hover del BUTTON afecta al icono SVG/I */
    .nav-tabs .nav-link:hover svg,
    .nav-tabs .nav-link:hover i {
        opacity: 1 !important;
        color: #f36e00 !important;
        fill: #f36e00 !important;
    }

    /* Button activo */
    .nav-tabs .nav-link.active {
        border: none !important;
        border-bottom: 4px solid #fff !important;
        background-color: transparent !important;
    }

    /* Icono del button activo (SVG/I) */
    .nav-tabs .nav-link.active svg,
    .nav-tabs .nav-link.active i {
        color: #fff !important;
        fill: #fff !important;
        opacity: 1 !important;
    }

    /* Forzar que solo el active tenga el border blanco */
    .nav-tabs .nav-link:not(.active) {
        border-bottom-color: transparent !important;
    }

    /* Eliminar outline al hacer focus */
    .nav-tabs .nav-link:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .tab-content {
        background-color: rgba(255, 255, 255, 0.01);
        border: 1px solid rgba(243, 110, 0, 0.2);
        border-top: none;
        border-radius: 0;
        padding: 30px;
        min-height: 500px;
    }

    .tab-pane {
        animation: fadeIn 0.3s ease-in;
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Estilos para secciones vacías */
    .section-empty-content {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 400px;
        width: 100%;
    }

    .empty-placeholder {
        text-align: center;
        color: rgba(255, 255, 255, 0.4);
    }

    .empty-placeholder i,
    .empty-placeholder svg {
        color: #f36e00;
        fill: #f36e00;
        margin-bottom: 20px;
        opacity: 0.6;
    }

    .empty-placeholder p {
        font-size: 16px;
        margin-top: 15px;
    }

    /* Responsive */
    @media screen and (max-width: 768px) {
        .nav-tabs .nav-link {
            padding: 12px 0 !important;
        }

        .nav-tabs .nav-link svg,
        .nav-tabs .nav-link i {
            font-size: 28px !important;
            width: 28px !important;
            height: 28px !important;
        }

        .tab-content {
            padding: 20px;
            min-height: 300px;
        }
    }

    @media screen and (max-width: 480px) {
        .nav-tabs .nav-link {
            padding: 10px 0 !important;
        }

        .nav-tabs .nav-link svg,
        .nav-tabs .nav-link i {
            font-size: 24px !important;
            width: 24px !important;
            height: 24px !important;
        }

        .tab-content {
            padding: 15px;
            min-height: 250px;
        }
    }

    /* ==================== FIN ESTILOS TABS ==================== */
</style>

<div class="mt-5">
    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" id="seccionesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-panel" type="button" data-tab="contenido-panel" aria-selected="true">
                <i class="fas fa-th-large"></i>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-editar" type="button" data-tab="contenido-editar" aria-selected="false">
                <i class="fas fa-pen"></i>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-camara" type="button" data-tab="contenido-camara" aria-selected="false">
                <i class="fas fa-camera"></i>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-precio" type="button" data-tab="contenido-precio" aria-selected="false">
                <i class="fas fa-euro-sign"></i>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-stats" type="button" data-tab="contenido-stats" aria-selected="false">
                <i class="fas fa-chart-bar"></i>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="seccionesTabContent">
        
        <!-- TAB 1: PANEL -->
        <div class="tab-pane active" id="contenido-panel" role="tabpanel" aria-labelledby="tab-panel">
            <div class="section-images-content">
                <!-- AQUÍ VA TU GALERÍA DE IMÁGENES -->
                <h2 class="w-100 text-center text-white" style="font-size: 20px;">Aquí solo aparecerán las imágenes aprobadas.</h2>
                <div class="gallery" id="gallery">
                    @foreach ($images->take(8) as $i=>$image)
                        @php
                            $mimeType = \Storage::disk(\App\Helpers\StorageHelper::getDisk('images'))->mimeType($image->route);
                        @endphp
                        @if ($mimeType && strpos($mimeType, 'image/') === 0)
                            @php
                                $width = \App\Helpers\StorageHelper::getSize($image, 'images')["width"];
                                $height = \App\Helpers\StorageHelper::getSize($image, 'images')["height"];
                            @endphp
                            <div class="gallery-item-container">
                                <div class="gallery-item image-hover-zoom" tabindex="0">

                                    <img src="{{ route('home.imageget', ['filename' => $image->route]) }}"
                                        class="gallery-image" alt="" loading="lazy">

                                    @if(!is_null($image->frontimage))
                                    <div class="gallery-item-type">

                                        <span class="visually-hidden">Portada</span><i class="fa-solid fa-star" aria-hidden="true"></i>

                                    </div>
                                    @endif

                                    <div class="gallery-item-info">

                                        <ul>
                                            <li class="gallery-item-likes"><span class="visually-hidden">Vistas:</span><i
                                                    class="fas fa-eye" aria-hidden="true"></i> {{ $image->visits ?? 0 }}</li>
                                            <li class="gallery-item-comments"><span class="visually-hidden">Likes:</span><i
                                                class="fas fa-heart" aria-hidden="true"></i> {{ \App\Models\ImageLike::where('image_id', $image->id)->count() }}</li>
                                            <li class="gallery-item-points">
                                                <span class="visually-hidden">Points:</span>
                                                <i class="fas fa-bullseye" aria-hidden="true"></i> {{$totalPoints}}
                                            </li>
                                        </ul>

                                    </div>


                                </div>
                                <div class="gallery-item-buttons">
                                    @if(is_null($image->visible))
                                        <a title="Hacer imagen visible" href="{{ route('account.images.visible', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-primary" style="background:#f36e00!important; color:#fff;"><i class="fa-regular fa-eye"></i></a>
                                    @else
                                        <a title="Hacer imagen invisible" href="{{ route('account.images.invisible', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-primary" style="background:#f36e00!important; color:#fff;"><i class="fa-regular fa-eye-slash"></i></a>
                                    @endif
                                    
                                    @if($image->frontimage === 1)
                                        <a title="Imagen portada" href="javascript:void(0)" class="btn btn-primary" style="background:#f36e00!important; color:#fff;"><i class="fa-solid fa-star"></i></a>
                                    @endif

                                    @if(is_null($image->frontimage))
                                        @if(!is_null($height) && $height > $width)
                                            <a title="Hacer imagen portada" href="{{ route('account.images.setfront', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-secondary" style="background:#f36e00!important; color:#fff;"><i class="fa-regular fa-image"></i></a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @elseif ($mimeType && strpos($mimeType, 'video/') === 0)
                            @if(!is_null($image->route_gif))
                                @php list($width, $height) = getimagesize(\Storage::disk(\App\Helpers\StorageHelper::getDisk('videogif'))->path($image->route_gif)); @endphp
                                <div class="gallery-item-container">
                                    <div class="gallery-item image-hover-zoom" tabindex="0">

                                        <img src="{{ route('home.gifget', ['filename' => $image->route_gif]) }}"
                                            class="gallery-image" alt="">

                                        @if(!is_null($image->frontimage))
                                        <div class="gallery-item-type">

                                            <span class="visually-hidden">Portada</span><i class="fa-solid fa-star" aria-hidden="true"></i>

                                        </div>
                                        @endif

                                        <div class="gallery-item-info">

                                            <ul>
                                                <li class="gallery-item-likes"><span class="visually-hidden">Vistas:</span><i
                                                        class="fas fa-eye" aria-hidden="true"></i> {{ $image->visits ?? 0 }}</li>
                                                <li class="gallery-item-comments"><span class="visually-hidden">Likes:</span><i
                                                        class="fas fa-heart" aria-hidden="true"></i> {{ $image->likes ?? 0 }}</li>
                                            </ul>

                                        </div>

                                    </div>
                                    <div class="gallery-item-buttons">
                                        @if(is_null($image->visible))
                                            <a title="Hacer imagen visible" href="{{ route('account.images.visible', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-primary" style="background:#f36e00!important;"><i class="fa-regular fa-eye"></i></a>
                                        @else
                                            <a title="Hacer imagen invisible" href="{{ route('account.images.invisible', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-primary" style="background:#f36e00!important;"><i class="fa-regular fa-eye-slash"></i></a>
                                        @endif
                                        @if($image->frontimage === 1)
                                            <a title="Imagen portada" href="javascript:void(0)" class="btn btn-primary" style="background:#f36e00!important;"><i class="fa-regular fa-image"></i></a>
                                        @endif

                                        @if(is_null($image->frontimage) && !is_null($height) && $height > $width)
                                            <a title="Hacer imagen portada" href="{{ route('account.images.setfront', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-secondary"><i class="fa-regular fa-image"></i></a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach

                </div>
                <div id="loading" style="display: block; text-align: center; padding: 20px; margin: 20px 0;">
                    <div class="modern-loader"></div>
                </div>
            </div>
        </div>

        <!-- TAB 2: EDITAR -->
        <div class="tab-pane" id="contenido-editar" role="tabpanel" aria-labelledby="tab-editar">
            <div class="section-empty-content">
                <div class="empty-placeholder">
                    <i class="fas fa-pen fa-3x"></i>
                    <p style="margin-bottom: 30px;">Actualiza tu información personal y profesional</p>
                    
                    <!-- Enlace atractivo -->
                    <a href="{{ route('account.edit-data') }}" class="edit-data-link">
                        <span class="edit-data-icon">
                            <i class="fa-solid fa-user-pen"></i>
                        </span>
                        <span class="edit-data-text">
                            <strong>Modificar Datos</strong>
                            <small>Edita tu perfil, fotos y más</small>
                        </span>
                        <span class="edit-data-arrow">
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <style>
            /* Estilos para el enlace de editar datos */
            .edit-data-link {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: linear-gradient(135deg, #f36e00 0%, #ff8c42 100%);
                padding: 25px 35px;
                border-radius: 15px;
                text-decoration: none;
                color: #fff !important;
                transition: all 0.3s ease;
                max-width: 500px;
                width: 100%;
                box-shadow: 0 8px 25px rgba(243, 110, 0, 0.3);
                position: relative;
                overflow: hidden;
                margin-top: 20px;
            }

            .edit-data-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s ease;
            }

            .edit-data-link:hover::before {
                left: 100%;
            }

            .edit-data-link:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 35px rgba(243, 110, 0, 0.5);
                text-decoration: none;
                color: #fff !important;
            }

            .edit-data-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 12px;
                font-size: 28px;
                flex-shrink: 0;
                transition: all 0.3s ease;
            }

            /* Asegurar que los SVG e iconos no tengan margen */
            .edit-data-icon svg,
            .edit-data-icon i {
                margin: 0 !important;
                color: #fff !important;
                fill: #fff !important;
            }

            .edit-data-arrow svg,
            .edit-data-arrow i {
                margin: 0 !important;
                color: #fff !important;
                fill: #fff !important;
            }

            .edit-data-link:hover .edit-data-icon {
                background: rgba(255, 255, 255, 0.3);
                transform: rotate(10deg) scale(1.1);
            }

            .edit-data-text {
                display: flex;
                flex-direction: column;
                flex-grow: 1;
                margin: 0 20px;
                text-align: left;
            }

            .edit-data-text strong {
                font-size: 20px;
                font-weight: 600;
                margin-bottom: 5px;
                display: block;
                color: #fff !important;
            }

            .edit-data-text small {
                font-size: 14px;
                opacity: 0.9;
                font-weight: 300;
                color: #fff !important;
            }

            .edit-data-arrow {
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                transition: transform 0.3s ease;
            }

            .edit-data-link:hover .edit-data-arrow {
                transform: translateX(5px);
            }

            /* Responsive */
            @media screen and (max-width: 768px) {
                .edit-data-link {
                    padding: 20px 25px;
                    max-width: 100%;
                }

                .edit-data-icon {
                    width: 50px;
                    height: 50px;
                    font-size: 24px;
                }

                .edit-data-text strong {
                    font-size: 18px;
                }

                .edit-data-text small {
                    font-size: 13px;
                }
            }

            @media screen and (max-width: 480px) {
                .edit-data-link {
                    padding: 15px 20px;
                }

                .edit-data-icon {
                    width: 45px;
                    height: 45px;
                    font-size: 20px;
                }

                .edit-data-text {
                    margin: 0 15px;
                }

                .edit-data-text strong {
                    font-size: 16px;
                }

                .edit-data-text small {
                    font-size: 12px;
                }

                .edit-data-arrow {
                    font-size: 18px;
                }
            }
        </style>

        <!-- TAB 3: CÁMARA -->
        <div class="tab-pane" id="contenido-camara" role="tabpanel" aria-labelledby="tab-camara">
            <div class="section-empty-content">
                <div class="empty-placeholder">
                    <i class="fas fa-camera fa-3x"></i>
                    <p style="margin-bottom: 30px;">Sube y gestiona tus fotos y videos</p>
                    
                    <!-- Enlace atractivo para subir fotos -->
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#subir-fotos-{{ $u->id }}" class="upload-photos-link">
                        <span class="upload-photos-icon">
                            <i class="fa-solid fa-upload"></i>
                        </span>
                        <span class="upload-photos-text">
                            <strong>Subir Fotos</strong>
                            <small>Añade nuevas imágenes a tu galería</small>
                        </span>
                        <span class="upload-photos-arrow">
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        @include('modals.admin.fotos.modal_subir_fotos')

        <style>
            /* Estilos para el enlace de subir fotos */
            .upload-photos-link {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: linear-gradient(135deg, #f36e00 0%, #ff8c42 100%);
                padding: 25px 35px;
                border-radius: 15px;
                text-decoration: none;
                color: #fff !important;
                transition: all 0.3s ease;
                max-width: 500px;
                width: 100%;
                box-shadow: 0 8px 25px rgba(243, 110, 0, 0.3);
                position: relative;
                overflow: hidden;
                margin-top: 20px;
                cursor: pointer;
            }

            .upload-photos-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s ease;
            }

            .upload-photos-link:hover::before {
                left: 100%;
            }

            .upload-photos-link:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 35px rgba(243, 110, 0, 0.5);
                text-decoration: none;
                color: #fff !important;
            }

            .upload-photos-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 12px;
                font-size: 28px;
                flex-shrink: 0;
                transition: all 0.3s ease;
            }

            /* Asegurar que los SVG e iconos no tengan margen */
            .upload-photos-icon svg,
            .upload-photos-icon i {
                margin: 0 !important;
                color: #fff !important;
                fill: #fff !important;
            }

            .upload-photos-arrow svg,
            .upload-photos-arrow i {
                margin: 0 !important;
                color: #fff !important;
                fill: #fff !important;
            }

            .upload-photos-link:hover .upload-photos-icon {
                background: rgba(255, 255, 255, 0.3);
                transform: rotate(10deg) scale(1.1);
            }

            .upload-photos-text {
                display: flex;
                flex-direction: column;
                flex-grow: 1;
                margin: 0 20px;
                text-align: left;
            }

            .upload-photos-text strong {
                font-size: 20px;
                font-weight: 600;
                margin-bottom: 5px;
                display: block;
                color: #fff !important;
            }

            .upload-photos-text small {
                font-size: 14px;
                opacity: 0.9;
                font-weight: 300;
                color: #fff !important;
            }

            .upload-photos-arrow {
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                transition: transform 0.3s ease;
            }

            .upload-photos-link:hover .upload-photos-arrow {
                transform: translateX(5px);
            }

            /* Responsive */
            @media screen and (max-width: 768px) {
                .upload-photos-link {
                    padding: 20px 25px;
                    max-width: 100%;
                }

                .upload-photos-icon {
                    width: 50px;
                    height: 50px;
                    font-size: 24px;
                }

                .upload-photos-text strong {
                    font-size: 18px;
                }

                .upload-photos-text small {
                    font-size: 13px;
                }
            }

            @media screen and (max-width: 480px) {
                .upload-photos-link {
                    padding: 15px 20px;
                }

                .upload-photos-icon {
                    width: 45px;
                    height: 45px;
                    font-size: 20px;
                }

                .upload-photos-text {
                    margin: 0 15px;
                }

                .upload-photos-text strong {
                    font-size: 16px;
                }

                .upload-photos-text small {
                    font-size: 12px;
                }

                .upload-photos-arrow {
                    font-size: 18px;
                }
            }
        </style>

        <!-- TAB 4: PRECIO -->
        <div class="tab-pane" id="contenido-precio" role="tabpanel" aria-labelledby="tab-precio">
            <div class="section-empty-content">
                <div class="empty-placeholder">
                    <i class="fas fa-euro-sign fa-3x"></i>
                    <p style="margin-bottom: 30px;">Impulsa tu visibilidad con nuestros paquetes</p>
                    
                    <!-- Enlace atractivo para anunciarse -->
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#asignar-paquete-{{ $u->id }}" class="advertise-link">
                        <span class="advertise-icon">
                            <i class="fa-solid fa-rocket"></i>
                        </span>
                        <span class="advertise-text">
                            <strong>Comprar paquete de anuncio</strong>
                            <small>Anúnciate en inicio y visibiliza tu cuenta</small>
                        </span>
                        <span class="advertise-arrow">
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        @include('modals.admin.modal_asignar_paquete')

        <style>
            /* Estilos para el enlace de anunciarse */
            .advertise-link {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: linear-gradient(135deg, #f36e00 0%, #ff8c42 100%);
                padding: 25px 35px;
                border-radius: 15px;
                text-decoration: none;
                color: #fff !important;
                transition: all 0.3s ease;
                max-width: 500px;
                width: 100%;
                box-shadow: 0 8px 25px rgba(243, 110, 0, 0.3);
                position: relative;
                overflow: hidden;
                margin-top: 20px;
                cursor: pointer;
            }

            .advertise-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s ease;
            }

            .advertise-link:hover::before {
                left: 100%;
            }

            .advertise-link:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 35px rgba(243, 110, 0, 0.5);
                text-decoration: none;
                color: #fff !important;
            }

            .advertise-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 12px;
                font-size: 28px;
                flex-shrink: 0;
                transition: all 0.3s ease;
            }

            /* Asegurar que los SVG e iconos no tengan margen */
            .advertise-icon svg,
            .advertise-icon i {
                margin: 0 !important;
                color: #fff !important;
                fill: #fff !important;
            }

            .advertise-arrow svg,
            .advertise-arrow i {
                margin: 0 !important;
                color: #fff !important;
                fill: #fff !important;
            }

            .advertise-link:hover .advertise-icon {
                background: rgba(255, 255, 255, 0.3);
                transform: rotate(10deg) scale(1.1);
            }

            .advertise-text {
                display: flex;
                flex-direction: column;
                flex-grow: 1;
                margin: 0 20px;
                text-align: left;
            }

            .advertise-text strong {
                font-size: 20px;
                font-weight: 600;
                margin-bottom: 5px;
                display: block;
                color: #fff !important;
            }

            .advertise-text small {
                font-size: 14px;
                opacity: 0.9;
                font-weight: 300;
                color: #fff !important;
            }

            .advertise-arrow {
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                transition: transform 0.3s ease;
            }

            .advertise-link:hover .advertise-arrow {
                transform: translateX(5px);
            }

            /* Responsive */
            @media screen and (max-width: 768px) {
                .advertise-link {
                    padding: 20px 25px;
                    max-width: 100%;
                }

                .advertise-icon {
                    width: 50px;
                    height: 50px;
                    font-size: 24px;
                }

                .advertise-text strong {
                    font-size: 18px;
                }

                .advertise-text small {
                    font-size: 13px;
                }
            }

            @media screen and (max-width: 480px) {
                .advertise-link {
                    padding: 15px 20px;
                }

                .advertise-icon {
                    width: 45px;
                    height: 45px;
                    font-size: 20px;
                }

                .advertise-text {
                    margin: 0 15px;
                }

                .advertise-text strong {
                    font-size: 16px;
                }

                .advertise-text small {
                    font-size: 12px;
                }

                .advertise-arrow {
                    font-size: 18px;
                }
            }
        </style>

        <!-- TAB 5: ESTADÍSTICAS -->
        <div class="tab-pane" id="contenido-stats" role="tabpanel" aria-labelledby="tab-stats">
            <div class="section-empty-content">
                <div class="empty-placeholder">
                    <i class="fas fa-chart-bar fa-3x"></i>
                    <p>Próximamente...</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// SISTEMA DE TABS COMPLETAMENTE MANUAL - SIN BOOTSTRAP
(function() {
    'use strict';
    
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTabs);
    } else {
        initTabs();
    }
    
    function initTabs() {
        console.log('Inicializando sistema de tabs manual...');
        
        // Obtener todos los botones de tabs
        const tabButtons = document.querySelectorAll('#seccionesTabs .nav-link');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        if (tabButtons.length === 0) {
            console.error('No se encontraron botones de tabs');
            return;
        }
        
        // Función para cambiar de tab
        function switchTab(clickedButton) {
            const targetId = clickedButton.getAttribute('data-tab');
            console.log('Cambiando a tab:', targetId);
            
            // 1. Remover TODAS las clases active
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            });
            
            // 2. Remover TODAS las clases active de paneles
            tabPanes.forEach(pane => {
                pane.classList.remove('active');
            });
            
            // 3. Activar el botón clickeado
            clickedButton.classList.add('active');
            clickedButton.setAttribute('aria-selected', 'true');
            
            // 4. Activar el panel correspondiente
            const activePane = document.getElementById(targetId);
            if (activePane) {
                activePane.classList.add('active');
                console.log('Tab activado correctamente:', targetId);
            } else {
                console.error('No se encontró el panel:', targetId);
            }
        }
        
        // Agregar event listeners a cada botón
        tabButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('Click en tab:', this.getAttribute('data-tab'));
                switchTab(this);
            });
        });
        
        console.log('Sistema de tabs manual inicializado correctamente');
    }
})();
</script>

<!-- ==================== FIN SECCIONES CON TABS ==================== -->