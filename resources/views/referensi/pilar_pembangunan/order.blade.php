<div style="display: flex;justify-content: center;align-items: center;">
    <div id="kt_docs_jstree_dragdrop"></div>
</div>
<div class="text-center pt-15">
    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" data-kt-roles-modal-action="cancel">Discard</button>
    <button id="submit" type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
        <span class="indicator-label">Submit</span>
        <span class="indicator-progress">Please wait...
        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
    </button>
</div>

<script>
    $(document).ready(function() {
        var data = "{{ $data }}"
        const decodedString = $('<textarea />').html(data).text(); // decode HTML entities
        const actualArray = JSON.parse(decodedString);
        $("#kt_docs_jstree_dragdrop").jstree({
            "core" : {
                "themes" : {
                    "responsive": false
                },
                // so that create works
                "check_callback" : function(operation, node, parent, position, more) {
                    if (operation === 'move_node' && parent.id !== '#') {
                        return false; // Prevent moving to a different parent node
                    }
                    return true; // Allow all other operations
                },
                'data' : actualArray
            },
            "types" : {
                "default" : {
                    "icon" : "bi bi-folder-fill text-success"
                },
                "file" : {
                    "icon" : "ki-solid ki-file  text-success"
                }
            },
            "state" : { "key" : "demo2" },
            "plugins" : [ "dnd", "state", "types" ]
        });

        $("#submit").on('click', function() {
            // Get the jsTree instance
            var tree = $('#kt_docs_jstree_dragdrop').jstree(true);

            // Get all data
            var data = tree.get_json('#', {flat: true});

            // Log the data
            const result = data.map(function(val) {
                return val.text
            })

            $.blockUI({
                theme: true,
                baseZ: 2000
            }) 

            $.ajax({
                url: urleditordersubmit,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    data: result                   
                },
                dataType: 'json',
                success: function(data){
                    $.unblockUI();

                    swal.fire({
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,
                            buttonsStyling: true,
                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                    });	                   

                    if(data.flag == 'success') {
                        $('#winform').modal('hide');
                    }
                },
                error: function(jqXHR, exception){
                    $.unblockUI();
                    var msgerror = '';
                    if (jqXHR.status === 0) {
                        msgerror = 'jaringan tidak terkoneksi.';
                    } else if (jqXHR.status == 404) {
                        msgerror = 'Halaman tidak ditemukan. [404]';
                    } else if (jqXHR.status == 500) {
                        msgerror = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msgerror = 'Requested JSON parse gagal.';
                    } else if (exception === 'timeout') {
                        msgerror = 'RTO.';
                    } else if (exception === 'abort') {
                        msgerror = 'Gagal request ajax.';
                    } else {
                        msgerror = 'Error.\n' + jqXHR.responseText;
                    }
                    swal.fire({
                            title: "Error System",
                            html: msgerror+', coba ulangi kembali !!!',
                            icon: 'error',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                    });	                               
                }
            })
        })
    })
</script>