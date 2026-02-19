<div class="modal fade" id="notificacionModal" tabindex="-1" aria-labelledby="notificacionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable notif-modal-dialog">
        <div class="modal-content notif-modal-content">
            <div class="notif-modal-header">
                <div class="notif-modal-header-inner">
                    <span class="notif-modal-icon-wrap"><i class="fa-solid fa-bell"></i></span>
                    <div>
                        <h5 class="notif-modal-title" id="notificacionModalLabel">Notificaciones</h5>
                        <p class="notif-modal-subtitle">{if $cantidad_notificacion}{$cantidad_notificacion} nueva(s){else}Sin notificaciones{/if}</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white notif-modal-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body notif-modal-body">
                <div id="lista_notificaciones" class="notif-list">
                    {if $notificaciones && count($notificaciones) > 0}
                    {foreach $notificaciones as $key}
                    <a href="{$dominio}/post/{$key['id_tablero']}" class="notif-item" data-bs-dismiss="modal">
                        <div class="notif-avatar">
                            <img src="{$dominio}/{$key['foto_url']}" alt="{$key['nombre']}" onerror="this.src='{$dominio}/assets/user_profile.png'">
                        </div>
                        <div class="notif-content">
                            <p class="notif-text"><strong>{$key['nombre']}</strong> te ha mencionado en una publicación.</p>
                            <span class="notif-time">{$key['fecha']}</span>
                        </div>
                        <i class="fa-solid fa-chevron-right notif-arrow"></i>
                    </a>
                    {/foreach}
                    {else}
                    <div class="notif-empty">
                        <div class="notif-empty-icon"><i class="fa-regular fa-bell-slash"></i></div>
                        <p class="notif-empty-title">Sin notificaciones</p>
                        <p class="notif-empty-text">Cuando alguien te mencione, aparecerá aquí.</p>
                    </div>
                    {/if}
                </div>
            </div>
            <div class="notif-modal-footer">
                <button type="button" class="notif-btn-close" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
.notif-modal-dialog { max-width: 420px; }
.notif-modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
}
.notif-modal-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #fff;
    padding: 1.25rem 1.25rem 1rem;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
}
.notif-modal-header-inner { display: flex; align-items: center; gap: 0.875rem; }
.notif-modal-icon-wrap {
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
}
.notif-modal-title {
    font-size: 1.125rem;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.02em;
}
.notif-modal-subtitle {
    font-size: 0.8125rem;
    opacity: 0.85;
    margin: 0.25rem 0 0;
    font-weight: 400;
}
.notif-modal-close {
    opacity: 0.85;
    filter: brightness(0) invert(1);
}
.notif-modal-close:hover { opacity: 1; }

.notif-modal-body {
    padding: 0;
    max-height: 60vh;
    overflow-y: auto;
}
.notif-list { padding: 0.5rem 0; }
.notif-item {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.875rem 1.25rem;
    color: inherit;
    text-decoration: none;
    transition: background 0.15s ease;
    border-bottom: 1px solid #f1f5f9;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover {
    background: #f8fafc;
}
.notif-avatar {
    flex-shrink: 0;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    overflow: hidden;
    background: #e2e8f0;
}
.notif-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.notif-content { flex: 1; min-width: 0; }
.notif-text {
    margin: 0;
    font-size: 0.9375rem;
    color: #334155;
    line-height: 1.4;
}
.notif-text strong { color: #0f172a; }
.notif-time {
    display: inline-block;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #94a3b8;
}
.notif-arrow {
    flex-shrink: 0;
    font-size: 0.75rem;
    color: #cbd5e1;
    transition: transform 0.2s ease;
}
.notif-item:hover .notif-arrow {
    color: #64748b;
    transform: translateX(3px);
}

.notif-empty {
    padding: 2.5rem 1.5rem;
    text-align: center;
}
.notif-empty-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 1rem;
    background: #f1f5f9;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #94a3b8;
}
.notif-empty-title {
    font-size: 1rem;
    font-weight: 600;
    color: #475569;
    margin: 0 0 0.25rem;
}
.notif-empty-text {
    font-size: 0.875rem;
    color: #94a3b8;
    margin: 0;
}

.notif-modal-footer {
    padding: 0.75rem 1.25rem 1rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}
.notif-btn-close {
    width: 100%;
    padding: 0.625rem 1rem;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9375rem;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    transition: background 0.2s ease, border-color 0.2s ease;
}
.notif-btn-close:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}
</style>
