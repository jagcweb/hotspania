<style>
    .notifications-modal .modal-dialog {
        max-width: 600px;
        margin: 1.75rem auto;
    }

    .notifications-modal .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .notifications-modal .modal-header {
        background: linear-gradient(135deg, #F76E08 0%, #e55a00 100%);
        color: white;
        border: none;
        padding: 1.5rem 2rem;
        position: relative;
    }

    .notifications-modal .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.05"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .notifications-modal .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .notifications-modal .modal-title i {
        margin-right: 0.5rem;
        font-size: 1.3rem;
    }

    .notifications-modal .close {
        position: relative;
        z-index: 1;
        color: white;
        opacity: 0.8;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }

    .notifications-modal .close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }

    .notifications-modal .modal-body {
        padding: 2rem;
        max-height: 70vh;
        overflow-y: auto;
        background: linear-gradient(180deg, #fff5f0 0%, #ffffff 100%);
    }

    .notifications-modal .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .notifications-modal .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .notifications-modal .modal-body::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #F76E08, #e55a00);
        border-radius: 10px;
    }

    .mark-all-read {
        background: linear-gradient(135deg, #F76E08 0%, #e55a00 100%);
        border: none;
        border-radius: 25px;
        padding: 0.6rem 1.5rem;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(247, 110, 8, 0.4);
    }

    .mark-all-read:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(247, 110, 8, 0.6);
        color: white;
    }

    .notification-item {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e8ecf4;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .notification-item::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        background: rgba(247, 110, 8, 0.05);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #F76E08;
    }

    .notification-item:hover::before {
        opacity: 1;
    }

    .notification-item.unread {
        background: linear-gradient(135deg, #fff 0%, #fff5f0 100%);
        border-color: #F76E08;
    }

    .notification-item.unread::before {
        opacity: 1;
        background: rgba(247, 110, 8, 0.08);
    }

    .notification-item.read {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-color: #dee2e6;
        opacity: 0.7;
    }

    .notification-item.read .notification-subject {
        color: #6c757d;
    }

    .notification-item.read .notification-subject i {
        color: #adb5bd;
    }

    .notification-item.read .notification-text {
        color: #6c757d;
    }

    .notification-item.read .notification-time {
        color: #adb5bd;
    }

    .notification-item.read:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .notification-subject {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .notification-subject i {
        margin-right: 0.5rem;
        color: #F76E08;
        font-size: 1rem;
    }

    .notification-text {
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 0.8rem;
    }

    .notification-time {
        color: #718096;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .notification-time i {
        margin-right: 0.3rem;
    }

    .notification-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-details {
        background: linear-gradient(135deg, #F76E08 0%, #e55a00 100%);
        border: none;
        border-radius: 20px;
        padding: 0.4rem 1rem;
        color: white;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-details:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(247, 110, 8, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-images {
        background: linear-gradient(135deg, #F76E08 0%, #e55a00 100%);
        border: none;
        border-radius: 20px;
        padding: 0.4rem 1rem;
        color: white;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-images:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(247, 110, 8, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-mark-read {
        background: linear-gradient(135deg, #F76E08 0%, #e55a00 100%);
        border: none;
        border-radius: 20px;
        padding: 0.4rem 1rem;
        color: white;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-mark-read:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(247, 110, 8, 0.4);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        border-radius: 20px;
        padding: 0.4rem 1rem;
        color: white;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        color: white;
    }

    .delete-all-btn {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        border-radius: 25px;
        padding: 0.6rem 1.5rem;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        margin-left: 0.5rem;
    }
    .delete-all-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
        color: white;
    }

    .unread-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, #F76E08, #e55a00);
        color: white;
        border-radius: 10px;
        padding: 0.2rem 0.6rem;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notification-item {
        animation: slideIn 0.3s ease forwards;
    }

    .notification-item:nth-child(1) { animation-delay: 0.1s; }
    .notification-item:nth-child(2) { animation-delay: 0.2s; }
    .notification-item:nth-child(3) { animation-delay: 0.3s; }
    .notification-item:nth-child(4) { animation-delay: 0.4s; }
    .notification-item:nth-child(5) { animation-delay: 0.5s; }
</style>

<div class="modal fade notifications-modal" id="ver-notificaciones" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">
                    <i class="fas fa-bell"></i>
                    Ver Notificaciones
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled">
                    @if(count($notifications) > 0)
                        <div class="text-right mb-3">
                            <form action="{{ route('admin.notifications.markAllAsRead') }}" method="GET" class="d-inline">
                                @csrf
                                <button type="submit" class="mark-all-read">
                                    <i class="fas fa-check-double"></i>
                                    Marcar todas como leídas
                                </button>
                            </form>
                            <form action="{{ route('admin.notifications.deleteAll') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="delete-all-btn" onclick="return confirm('¿Estás seguro de que quieres borrar todas las notificaciones?')">
                                    <i class="fas fa-trash-alt"></i>
                                    Borrar todas
                                </button>
                            </form>
                        </div>
                    @endif
                    @foreach ($notifications as $notification)
                        <li class="notification-item {{ !$notification->readed ? 'unread' : 'read' }}">
                            @if(!$notification->readed)
                                <div class="unread-badge">Nueva</div>
                            @endif
                            <div class="notification-subject">
                                @switch($notification->type)
                                    @case('image')
                                        <i class="fas fa-image mr-1"></i>
                                        @break
                                    @case('user')
                                        <i class="fas fa-user-plus mr-1"></i>
                                        @break
                                    @case('report')
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        @break
                                    @case('system')
                                        <i class="fas fa-cog mr-1"></i>
                                        @break
                                    @default
                                        <i class="fas fa-bell mr-1"></i>
                                @endswitch
                                {{ $notification->subject }}
                            </div>
                            <div class="notification-text">{{ $notification->text }}</div>
                            <div class="notification-time">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                            <div class="notification-actions">
                                @if($notification->urls)
                                    <a href="{{ $notification->urls }}" class="btn-details">
                                        <i class="fas fa-eye"></i> Ver detalles
                                    </a>
                                @endif
                                @switch($notification->type)
                                    @case('user')
                                        <a href="{{ route('admin.users.getPending') }}" class="btn-details">
                                            <i class="fas fa-users"></i> Ver usuario pendiente
                                        </a>
                                        @break
                                    @case('image')
                                        @php $user = \App\Models\User::find($notification->type_id); @endphp
                                        <a href="{{ route('admin.images.getFilter', ['id' => $user->id, 'name' => $user->nickname, 'filter' => 'pendientes']) }}" class="btn-images">
                                            <i class="fas fa-images"></i> Ver imágenes
                                        </a>
                                        @break
                                        @case('report')
                                            <a href="#" class="btn-details">
                                                <i class="fas fa-exclamation-triangle"></i> Ver reporte
                                            </a>
                                        @break
                                    @default
                                       
                                @endswitch
                                @if(!$notification->readed)
                                    <form action="{{ route('admin.notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-mark-read">
                                            <i class="fas fa-check"></i> Marcar como leída
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.notifications.delete', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-delete" onclick="return confirm('¿Estás seguro de que quieres borrar esta notificación?')">
                                        <i class="fas fa-trash-alt"></i> Borrar
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>