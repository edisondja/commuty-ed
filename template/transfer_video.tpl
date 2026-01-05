<div class="modal fade" id="transferVideo" tabindex="-1" aria-labelledby="transferVideoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">

            <!-- Header -->
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title d-flex align-items-center gap-2" id="transferVideoLabel">
                    <i class="fa-solid fa-video"></i> Transferir video
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">

                <!-- URL Video -->
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-link me-1 text-primary"></i> URL del video
                </label>

                
                <input type="text" id="url_video" class="form-control" placeholder="https://...">

                <hr/>

                <textarea class='form-control' id='video_txt'>

                </textarea>
                <hr/>
                <!-- Plataforma -->
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-layer-group me-1 text-success"></i> Plataforma
                </label>
                <select class="form-select" id="platformSelect">
                    <option value="">Seleccione una opción</option>
                    <option value="vde">📹 VDE</option>
                    <option value="reddit">👽 Reddit</option>
                    <option value="twitter">🐦 Twitter (X)</option>
                </select>

                <!-- Reddit -->
                <div class="mt-3 d-none" id="redditFields">
                    <label class="form-label">
                        <i class="fa-brands fa-reddit text-danger me-1"></i>
                        ID del post de Reddit
                    </label>
                    <input type="text" class="form-control" id="redditId" placeholder="t3_abc123">
                </div>

                <!-- Twitter -->
                <div class="mt-3 d-none" id="twitterFields">
                    <label class="form-label">
                        <i class="fa-brands fa-x-twitter text-dark me-1"></i>
                        ID del tweet
                    </label>
                    <input type="text" class="form-control" id="twitterId" placeholder="1748392018392">
                </div>

                <!-- VDE -->
                <div class="mt-3 d-none" id="vdeFields">
                    <label class="form-label">
                        <i class="fa-solid fa-film text-warning me-1"></i>
                        URL del video VDE
                    </label>
                    <input type="text" class="form-control" id="vdeUrl"
                           placeholder="https://videosegg.com/playvideo/...">
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnTransferVideo">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Transferir
                </button>
            </div>

        </div>
    </div>
</div>
