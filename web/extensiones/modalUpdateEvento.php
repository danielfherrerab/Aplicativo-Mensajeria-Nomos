<div class="modal" id="modalUpdateEvento" data-id="modalUpdateEvento" tabindex="-1" role="dialog">
    <div class="modal_content" id="contentInterior">
      <div class="modal-header">
        <h4>Ver encargo</h4>
        <button type="button" class="close" id="quitar_encargo">
          <span>&times;</span>
        </button>
      </div>
        <p>
        <input type="hidden" class="form-control" name="idEvento" id="idEvento">
        Descripcion del Evento
        <textarea class="control" name="evento" placeholder="Nombre del Evento" required readonly></textarea>
        </p>
        <p>
        Mensajero
        <input type="text" class="form-control" name="mensajero" id="mensajero" placeholder="Fecha Inicio" readonly>
        </p>
        <p>
        Fecha para realizacion
        <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" placeholder="Fecha Inicio" readonly>
        </p>
    </div>
</div>