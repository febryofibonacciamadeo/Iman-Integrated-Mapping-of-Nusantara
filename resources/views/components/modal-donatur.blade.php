<div class="modal fade" id="{{ $id }}" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header tw-bg-gradient-to-r tw-from-[#0c3f50] tw-to-[#2a9d8f]">
                <h4 class="modal-title tw-font-bold tw-text-white tw-text-lg" id="judul_modal"></h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="tw-text-white">&times;</span>
                </button>
            </div>

            <form action="{{ route('donatur.updateOrCreate') }}" method="post" id="{{ $idForm }}">
                
                <div class="modal-body tw-text-sm">
                    <div class="row justify-content-center">
                        <!-- Nama Bidang -->
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="nama_bidang" class="col-form-label">Nama Bidang<span class="text-danger">*</span></label>
                                <input type="text" class="tw-border tw-border-gray-300 tw-text-gray-900 tw-text-sm tw-rounded-lg focus:tw-ring-blue-500 focus:tw-border-blue-500 tw-block tw-w-full" id="nama_bidang" placeholder="Cth : Marketing Communication" name="nama_bidang">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success" id="btn_save">Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->