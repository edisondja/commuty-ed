<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px; border:1px solid #d1d1d1;">
      
      <!-- Header -->
      <div class="modal-header" style="background-color:#167b5d; color:white; border-bottom:none; border-top-left-radius:12px; border-top-right-radius:12px;">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Body -->
      <div class="modal-body" style="background-color:#fdfaf6; color:#333;">
        
        <table style="margin-bottom:10px;">
          <tr>
            <td><img src="{$foto_perfil}" style="height:50px;width:50px;border-radius:100px;"></td>
            <td style="padding-left:10px; font-weight:500;">{$user_session}</td>
          </tr>
        </table>
        <hr style="margin:10px 0; border-color:#d1d1d1;">
        
        <div class="card-body">
          <textarea class="form-control mac-input" id="board_title" rows="5" placeholder="What's on your mind?"></textarea>
        </div>
        <hr style="margin:10px 0; border-color:#d1d1d1;">
        <!-- Barra de progreso -->
        
        <div class="flex-container" style="margin-bottom:10px;">
          <progress id="file"  class="progress" style="display:none; width:100%;" max="100" value="0"></progress>
          <strong id="porcentaje" style="display:none; margin-left:5px;">0%</strong>
        </div>
        
        <div id="multimedia_view" class="flex-container"></div>
      </div>
      
      <!-- Footer -->
      <div class="modal-footer" style="background-color:#fdfaf6; border-top:none;">
        <input accept="image/png,image/jpeg,video/*,audio/*" type="file" id="upload_images" style="display:none" multiple name="imagenes[]" />

        <table class="table table-borderless mb-2" style="margin-bottom:5px;">
          <tr>
            <td style="width:5%; cursor:pointer;">
              <svg id="upload_image" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#20c997" class="bi bi-card-image" viewBox="0 0 16 16">
                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z"/>
              </svg>
            </td>
          </tr>
        </table>

        <button class="btn mac-btn" id="post" style="background-color:#046160; color:white; border-radius:8px;">POST</button>
      </div>
    </div>
  </div>
</div>

<style>
  body {
    background-color: #fdfaf6; /* blanco hueso */
  }
  
  .mac-btn {
    font-weight: 500;
    padding: 6px 16px;
    border: none;
    transition: 0.2s;
  }

  .mac-btn:hover {
    background-color: #17a589; /* un verde azulado más oscuro al pasar el mouse */
  }

  #upload_image:hover {
    fill: #17a589;
  }

  .mac-input {
    border-radius: 8px;
    border: 1px solid #d1d1d1;
    padding: 8px;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
  }

  .flex-container {
    display: flex;
    align-items: center;
  }
</style>
