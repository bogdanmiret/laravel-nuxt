<div id="view-trans" class="modal fade" tabindex="-1" role="dialog" ria-labelledby="ViewTrans">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="ViewTrans" class="modal-title">Translation</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-file">
                <input type="hidden" id="modal-name">
                <input type="hidden" id="tr-index">
                <div class="form-group">
                    <label for="modal-value">EN version</label>
                    <p id="en-version"></p>
                </div>
                <div class="form-group">
                    <label for="modal-value">Value</label>
                    <textarea id="modal-value" rows="5" class="form-control" style="resize: vertical;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="saveTrans" type="button" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->