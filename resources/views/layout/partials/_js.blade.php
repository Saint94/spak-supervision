<script src="{{ url('assets/vendors/js/core/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/vendors/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/vendors/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/vendors/js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/vendors/js/prism.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/vendors/js/jquery.matchHeight-min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/vendors/js/screenfull.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/vendors/js/pace/pace.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/vendors/js/chartist.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/js/app-sidebar.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/js/notification-sidebar.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/js/customizer.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/js/dashboard1.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/js/basic-inputs.js') }}" type="text/javascript"></script>


<script src="{{ url('assets/dataTables/jquery.dataTables.js') }}"></script>
<script src="{{ url('assets/dataTables/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('assets/dataTables/jszip.min.js') }}"></script>
<script src="{{ url('assets/dataTables/pdfmake.min.js') }}"></script>
<script src="{{ url('assets/dataTables/vfs_fonts.js') }}"></script>
<script src="{{ url('assets/dataTables/buttons.html5.min.js') }}"></script>
<script src="{{ url('assets/dataTables/buttons.colVis.min.js') }}"></script>
<script src="{{ url('assets/js/components-modal.min.js') }}" type="text/javascript"></script>

<script src="{{ url('assets/dateFormat/dateFormat.js') }}"></script>

<!-- Development Tippy -->
<script src="{{ url('assets/tippy/popper.min.js') }}"></script>
<script src="{{ url('assets/tippy/tippy-bundle.umd.js') }}"></script>

<!-- Filepond -->
<script src="{{ url('assets/filepond/filepond-plugin-image-preview.js') }}"></script>
<script src="{{ url('assets/filepond/filepond-plugin-file-validate-size.js') }}"></script>
<script src="{{ url('assets/filepond/filepond-plugin-file-validate-type.js') }}"></script>
<script src="{{ url('assets/filepond/filepond.js') }}"></script>

<script src="{{ url('assets/sweatalert/sweetalert.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/select2/dist/js/select2.min.js') }}" type="text/javascript"></script>


<script>
    $(document).ready(function() {
        $(".number").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            $("#errmsg").html("Veuillez saisir uniquement des chiffres").stop().show().fadeOut("slow");
            return false;
        }
        });
    });
</script>


<script>
    $(document).ready( function () {
        $('#myDataTable').DataTable({
            dom: 'Blfrtip',
            buttons: [
                'csv', 'excel', 'pdf',
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed columns',
                    collectionTitle: 'Column visibility control'
                }
            ],
            lengthMenu: [
                [50, 100, 250, 500, -1],
                [50, 100, 250, 500,'All']
            ],
            language: {
                processing:     "Traitement en cours...",
                search:         "Rechercher&nbsp;:",
                lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
                info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                infoPostFix:    "",
                loadingRecords: "Chargement en cours...",
                zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                emptyTable:     "Aucune donnée disponible dans le tableau",
                paginate: {
                    first:      "Premier",
                    previous:   "«",
                    next:       "»",
                    last:       "Dernier"
                },
                aria: {
                    sortAscending:  ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                },
                buttons: {
                    colvis: 'Selectionner les colonnes'
                }
            }
        });
    });
</script>


<script>
    $(document).ready( function () {
        $('#myDataTable2').DataTable({
            dom: 'Blfrtip',
            buttons: [
                'csv', 'excel', 'pdf',
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed columns',
                    collectionTitle: 'Column visibility control'
                }
            ],
            lengthMenu: [
                [50, 100, 250, 500, -1],
                [50, 100, 250, 500,'All']
            ],
            language: {
                processing:     "Traitement en cours...",
                search:         "Rechercher&nbsp;:",
                lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
                info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                infoPostFix:    "",
                loadingRecords: "Chargement en cours...",
                zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                emptyTable:     "Aucune donnée disponible dans le tableau",
                paginate: {
                    first:      "Premier",
                    previous:   "«",
                    next:       "»",
                    last:       "Dernier"
                },
                aria: {
                    sortAscending:  ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                },
                buttons: {
                    colvis: 'Selectionner les colonnes'
                }
            }
        });
    });
</script>

<script>
    $(document).ready( function () {
        $('#myDataTable').DataTable({
            //retrieve: true,
            paging: true
            responsive: true,
        });
    } );
</script>

<script>
    $(document).ready( function () {
        $('#myDataTable2').DataTable({
            //retrieve: true,
            paging: true
            responsive: true,
        });
    } );
</script>

<script>
    (function () {
      'use strict'

      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.querySelectorAll('.needs-validation')

      // Loop over them and prevent submission
      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            form.classList.add('was-validated')
          }, false)
        })
    })()
</script>


<script type="text/javascript">
    function getSelectedValue(name) {
        const radios = document.getElementsByName(name);
        let selectedValue;

        for (const radio of radios) {
            if (radio.checked) {
                selectedValue = radio.value;
                break;
            }
        }
        return selectedValue;
    }
</script>


<script>
    // Fonction pour formater la date au format YYYY-MM-DD
    function formatDate(date) {
        date = new Date(date); // accepte chaîne ISO, timestamp, etc.
        var year = date.getFullYear();
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');
        return day + '/' + month + '/' + year;
    }
</script>
