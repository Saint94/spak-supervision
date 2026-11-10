    // Démarrer automatiquement au chargement de la page
    $(document).ready(function() {
        // Délai pour laisser le temps à la page de charger
        setTimeout(() => {
            autoRequestLocation();
        }, 1000);
    });

    function autoRequestLocation() {
        if (!navigator.geolocation) {
            showStatus('Votre navigateur ne supporte pas la géolocalisation.', 'error');
            return;
        }

        showStatus('Demande de localisation en cours...', 'loading');

        // Le navigateur affiche automatiquement sa propre popup de permission
        navigator.geolocation.getCurrentPosition(
            // Succès
            function(position) {
                showStatus('Localisation autorisée !', 'success');
                displayLocation(position);
                sendToServer(position.coords);
            },
            // Erreur
            function(error) {
                handleGeolocationError(error);
            },
            // Options
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    function requestLocation() {
        autoRequestLocation();
    }

    function displayLocation(position) {
        // Mettre à jour les spans (affichage)
        $('#latitude').val(position.coords.latitude.toFixed(6).replace('.', ','));
        $('#longitude').val(position.coords.longitude.toFixed(6).replace('.', ','));
        $('#accuracy').val(Math.round(position.coords.accuracy));

        // Optionnel: déclencher un événement personnalisé
        $(document).trigger('location:updated', [position.coords]);
    }

    function showStatus(message, type) {
        // Créer l'élément s'il n'existe pas
        if ($('#locationStatus').length === 0) {
            $('body').append('<div id="locationStatus"><span id="statusText"></span></div>');
        }

        const statusDiv = $('#locationStatus');

        // Supprimer toutes les classes de type précédentes
        statusDiv.removeClass('success error loading');
        statusDiv.show();

        // Cacher après 5 secondes pour les messages de succès
        if (type === 'success') {
            setTimeout(() => {
                statusDiv.hide();
            }, 5000);
        }
    }

    function handleGeolocationError(error) {
        let message = '';

        switch(error.code) {
            case error.PERMISSION_DENIED:
                message = 'Vous avez refusé l\'accès à votre position.';
                break;
            case error.POSITION_UNAVAILABLE:
                message = 'Les informations de localisation ne sont pas disponibles.';
                break;
            case error.TIMEOUT:
                message = 'La demande de localisation a expiré.';
                break;
            default:
                message = 'Une erreur inconnue est survenue.';
                break;
        }

        showStatus(message, 'error');

        // Optionnel: mettre des valeurs par défaut ou vider les champs
        $('#latitude').val('');
        $('#longitude').val('');
        $('#accuracy').val('');
    }


    // Vérifier l'état des permissions (optionnel)
    if (navigator.permissions && navigator.permissions.query) {
        navigator.permissions.query({name: 'geolocation'})
            .then(permissionStatus => {
                console.log('État actuel de la permission:', permissionStatus.state);

                // Écouter les changements de permission
                permissionStatus.onchange = function() {
                    console.log('Permission changée:', this.state);
                    if (this.state === 'granted') {
                        // Si l'utilisateur active après coup, on redemande
                        autoRequestLocation();
                    }
                };
            });
    }

    // Fonction pour forcer la demande si nécessaire
    function forceLocationRequest() {
        if (navigator.geolocation) {
            // D'abord, vérifier si on a déjà la permission
            if (navigator.permissions && navigator.permissions.query) {
                navigator.permissions.query({name: 'geolocation'})
                    .then(permissionStatus => {
                        if (permissionStatus.state === 'granted') {
                            // Si déjà autorisé, récupérer directement
                            autoRequestLocation();
                        } else {
                            // Si refusé ou pas encore demandé, on demande
                            // Note: certains navigateurs ne redemandent pas si déjà refusé
                            autoRequestLocation();
                        }
                    });
            } else {
                // Fallback pour les navigateurs qui ne supportent pas permissions API
                autoRequestLocation();
            }
        }
    }
