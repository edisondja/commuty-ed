<link rel="stylesheet" href="{$dominio}/css/backoffice.css">

<div class="col-md-9 col-12 mx-auto bo-module">
    <input type="hidden" id="modulo_select" value="mails">
    
    <div class="bo-module-header">
        <h2><i class="fa-solid fa-envelope"></i> Correo Masivo</h2>
    </div>
    
    <div class="bo-card">
        <div class="bo-card-header">
            <h5><i class="fa-solid fa-paper-plane"></i> Enviar mensaje a todos los usuarios</h5>
        </div>
        <div class="bo-card-body">
            <div class="bo-alert bo-alert-info">
                <i class="fa-solid fa-info-circle"></i>
                <span>El mensaje será enviado a todos los usuarios registrados en la plataforma.</span>
            </div>
            
            <div class="mb-4">
                <label class="bo-label">Asunto del correo</label>
                <input type="text" id="asunto" class="form-control" placeholder="Escribe el asunto del correo...">
            </div>
            
            <div class="mb-4">
                <label class="bo-label">Mensaje</label>
                <textarea class="form-control bo-textarea" id="texto_correo" rows="10" placeholder="Escribe el contenido del mensaje..."></textarea>
            </div>
            
            <div class="text-center">
                <button class="bo-btn bo-btn-primary" id="send_mail">
                    <i class="fa-solid fa-paper-plane"></i> Enviar Correo Masivo
                </button>
            </div>
        </div>
    </div>
    
    <div class="bo-card mt-4">
        <div class="bo-card-header">
            <h5><i class="fa-solid fa-history"></i> Historial de Envíos</h5>
        </div>
        <div class="bo-card-body">
            <div class="bo-empty">
                <i class="fa-solid fa-inbox"></i>
                <p>No hay correos enviados recientemente</p>
            </div>
        </div>
    </div>
</div>

<script src="{$dominio}/js/bk_modulo_mail.js"></script>
