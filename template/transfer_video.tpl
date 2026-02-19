<div class="modal fade" id="transferVideo" tabindex="-1" aria-labelledby="transferVideoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered transfer-modal-dialog">
        <div class="modal-content transfer-modal-content">
            <div class="transfer-modal-header">
                <span class="transfer-modal-icon"><i class="fa-solid fa-cloud-arrow-down"></i></span>
                <h5 class="transfer-modal-title" id="transferVideoLabel">Transferir video</h5>
                <p class="transfer-modal-subtitle">Desde Reddit, VDE o Twitter (X)</p>
                <button type="button" class="btn-close btn-close-white transfer-modal-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body transfer-modal-body">
                <div class="transfer-field">
                    <label class="transfer-label" for="platformSelect">Plataforma</label>
                    <div class="transfer-input-wrap">
                        <i class="fa-solid fa-layer-group transfer-input-icon"></i>
                        <select class="transfer-select" id="platformSelect">
                            <option value="">Selecciona la plataforma</option>
                            <option value="vde">VDE</option>
                            <option value="reddit">Reddit</option>
                            <option value="twitter">Twitter (X)</option>
                        </select>
                    </div>
                </div>
                <div class="transfer-field">
                    <label class="transfer-label" for="url_video">URL del video o del post</label>
                    <div class="transfer-input-wrap">
                        <i class="fa-solid fa-link transfer-input-icon"></i>
                        <input type="text" id="url_video" class="transfer-input" placeholder="https://..." autocomplete="off">
                    </div>
                    <small class="transfer-hint d-none" id="url_video_hint_reddit">Pega el enlace completo del post (reddit.com/r/.../comments/...)</small>
                </div>
                <div class="transfer-field">
                    <label class="transfer-label" for="video_txt">Descripción (opcional)</label>
                    <textarea class="transfer-textarea" id="video_txt" rows="3" placeholder="Añade una descripción para la publicación..."></textarea>
                </div>
                <div id="transferTracking" class="transfer-tracking d-none">
                    <p class="transfer-tracking-title">Progreso</p>
                    <ul class="transfer-steps" id="transferStepsList">
                        <li class="transfer-step" id="transferStep1" data-step="1">
                            <span class="transfer-step-icon step-icon"><i class="fa-solid fa-circle fa-fw small"></i></span>
                            <span class="transfer-step-label step-label">Obteniendo URL del video...</span>
                            <span class="transfer-step-extra step-extra d-none"></span>
                        </li>
                        <li class="transfer-step" id="transferStep2" data-step="2">
                            <span class="transfer-step-icon step-icon"><i class="fa-solid fa-circle fa-fw small"></i></span>
                            <span class="transfer-step-label step-label">Guardando en tu tablero...</span>
                            <span class="transfer-step-extra step-extra d-none"></span>
                        </li>
                        <li class="transfer-step" id="transferStep3" data-step="3">
                            <span class="transfer-step-icon step-icon"><i class="fa-solid fa-circle fa-fw small"></i></span>
                            <span class="transfer-step-label step-label">Listo. Se procesará en segundo plano.</span>
                            <span class="transfer-step-extra step-extra d-none"></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="transfer-modal-footer">
                <button type="button" class="transfer-btn transfer-btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark me-2"></i> Cancelar
                </button>
                <button type="button" class="transfer-btn transfer-btn-primary" id="btnTransferVideo">
                    <i class="fa-solid fa-cloud-arrow-up me-2"></i> Transferir
                </button>
            </div>
        </div>
    </div>
</div>
<style>
.transfer-modal-dialog { max-width: 440px; }
.transfer-modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.2);
}
.transfer-modal-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #fff;
    padding: 1.5rem 1.25rem;
    position: relative;
    text-align: center;
}
.transfer-modal-icon {
    width: 48px; height: 48px;
    background: rgba(255,255,255,0.12);
    border-radius: 14px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
}
.transfer-modal-title { font-size: 1.125rem; font-weight: 700; margin: 0; letter-spacing: -0.02em; }
.transfer-modal-subtitle { font-size: 0.8125rem; opacity: 0.85; margin: 0.25rem 0 0; }
.transfer-modal-close { position: absolute; top: 1rem; right: 1rem; opacity: 0.85; filter: brightness(0) invert(1); }
.transfer-modal-close:hover { opacity: 1; }
.transfer-modal-body { padding: 1.25rem 1.25rem 1.5rem; }
.transfer-field { margin-bottom: 1rem; }
.transfer-label { font-size: 0.8125rem; font-weight: 600; color: #475569; margin-bottom: 0.35rem; display: block; }
.transfer-input-wrap { position: relative; display: flex; align-items: center; }
.transfer-input-icon {
    position: absolute; left: 1rem; color: #94a3b8; font-size: 0.9375rem;
}
.transfer-input, .transfer-select {
    width: 100%;
    padding: 0.65rem 1rem 0.65rem 2.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9375rem;
    background: #f8fafc;
    color: #0f172a;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.transfer-select { padding-left: 2.5rem; cursor: pointer; }
.transfer-input::placeholder { color: #94a3b8; }
.transfer-input:hover, .transfer-select:hover { border-color: #cbd5e1; background: #fff; }
.transfer-input:focus, .transfer-select:focus {
    outline: none; border-color: #0f172a; background: #fff;
    box-shadow: 0 0 0 3px rgba(15,23,42,0.08);
}
.transfer-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9375rem;
    background: #f8fafc;
    resize: vertical;
    min-height: 80px;
}
.transfer-textarea:focus { outline: none; border-color: #0f172a; background: #fff; }
.transfer-hint { font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem; display: block; }
.transfer-tracking { margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #e2e8f0; }
.transfer-tracking-title { font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.5rem; }
.transfer-steps { list-style: none; padding: 0; margin: 0; }
.transfer-step {
    display: flex; align-items: center; gap: 0.65rem;
    padding: 0.5rem 0;
    font-size: 0.875rem;
    color: #64748b;
}
.transfer-step-icon { flex-shrink: 0; }
.transfer-step-label { flex: 1; }
.transfer-step-extra { font-size: 0.75rem; color: #dc2626; }
.transfer-step.list-group-item-success .transfer-step-icon { color: #16a34a; }
.transfer-step.list-group-item-success .transfer-step-label { color: #15803d; }
.transfer-step.list-group-item-danger .transfer-step-icon { color: #dc2626; }
.transfer-step.list-group-item-danger .transfer-step-label { color: #b91c1c; }
.transfer-step.list-group-item-warning .transfer-step-icon { color: #2563eb; }
.transfer-modal-footer {
    display: flex; gap: 0.75rem; justify-content: flex-end;
    padding: 1rem 1.25rem 1.25rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}
.transfer-btn {
    display: inline-flex; align-items: center; justify-content: center;
    padding: 0.6rem 1.1rem;
    border-radius: 12px;
    font-size: 0.9375rem;
    font-weight: 500;
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.15s;
}
.transfer-btn-secondary {
    background: #fff;
    border: 1px solid #e2e8f0;
    color: #475569;
}
.transfer-btn-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; }
.transfer-btn-primary {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border: none;
    color: #fff;
}
.transfer-btn-primary:hover { box-shadow: 0 4px 14px rgba(15,23,42,0.25); transform: translateY(-1px); }
.transfer-btn-primary:active { transform: translateY(0); }
</style>
<script>
(function(){
    var sel = document.getElementById('platformSelect');
    var hint = document.getElementById('url_video_hint_reddit');
    if (sel && hint) {
        function toggle() { hint.classList.toggle('d-none', sel.value !== 'reddit'); }
        sel.addEventListener('change', toggle);
        toggle();
    }
})();
</script>
