@extends('layout.app')

@section('title', 'Liste des sites supervisés')

@section('content')

@include('layout.alert')

    <div id="map" style="height: 500px; width: 100%; margin-top: 20px;"></div>

    <!-- Inclure Leaflet CSS et JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Attendre que le DOM soit chargé
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer les coordonnées depuis votre fonction PHP
            const siteCoords = [
                {{ $site_oper_superv->ScLatitudeX ?? 0 }},
                {{ $site_oper_superv->ScLongitudeY ?? 0 }}
            ];

            const pointageCoords = [
                {{ $site_oper_superv->SoLatitudeX ?? 0 }},
                {{ $site_oper_superv->SoLongitudeY ?? 0 }}
            ];

            // Récupérer le nom du site
            const siteName = "{{ $site_oper_superv->ScNomSite ?? 'Site Client' }}";

            // Vérifier si les coordonnées sont valides
            if (!siteCoords[0] || !siteCoords[1] || !pointageCoords[0] || !pointageCoords[1]) {
                document.getElementById('map').innerHTML = `
                    <div style="text-align: center; padding: 50px; color: red;">
                        <h3>Coordonnées GPS non disponibles</h3>
                        <p>Les coordonnées du site ou du point de pointage sont manquantes</p>
                    </div>
                `;
                return;
            }

            // Initialiser la carte
            const map = L.map('map').setView([
                (siteCoords[0] + pointageCoords[0]) / 2,
                (siteCoords[1] + pointageCoords[1]) / 2
            ], 15);

            // Ajouter les tuiles OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Fonction pour calculer la distance
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371000; // Rayon de la Terre en mètres
                const φ1 = lat1 * Math.PI / 180;
                const φ2 = lat2 * Math.PI / 180;
                const Δφ = (lat2 - lat1) * Math.PI / 180;
                const Δλ = (lon2 - lon1) * Math.PI / 180;

                const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                        Math.cos(φ1) * Math.cos(φ2) *
                        Math.sin(Δλ/2) * Math.sin(Δλ/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

                return R * c; // Distance en mètres
            }

            // Calculer la distance
            const distance = calculateDistance(
                siteCoords[0], siteCoords[1],
                pointageCoords[0], pointageCoords[1]
            );

            // Formater la distance
            const distanceText = distance < 1000 ?
                `${distance.toFixed(2)} mètres` :
                `${(distance/1000).toFixed(2)} kilomètres`;

            // Marqueur du Site (vert) avec nom du site
            const siteIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `
                    <div style="position: relative;">
                        <!-- Point vert -->
                        <div style="background-color: #00FF00; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>
                        <!-- Étiquette avec nom du site -->
                        <div style="position: absolute; top: -30px; left: 50%; transform: translateX(-50%);
                                    background: white; padding: 2px 6px; border-radius: 3px; border: 1px solid #00FF00;
                                    font-size: 10px; font-weight: bold; white-space: nowrap; box-shadow: 0 1px 3px rgba(0,0,0,0.3);">
                            ${siteName}
                        </div>
                    </div>
                `,
                iconSize: [20, 50], // Augmenter la hauteur pour l'étiquette
                iconAnchor: [10, 10], // Ancrer au point
                popupAnchor: [0, -40] // Ajuster la position du popup
            });

            const siteMarker = L.marker(siteCoords, { icon: siteIcon }).addTo(map);
            siteMarker.bindPopup(`
                <div style="padding: 10px; max-width: 300px;">
                    <h4 style="margin: 0 0 10px 0; color: #00FF00;">📍 Site Client</h4>
                    <p><strong>Nom:</strong> ${siteName}</p>
                    <p><strong>Coordonnées:</strong></p>
                    <p>Latitude: ${siteCoords[0].toFixed(6)}<br>
                    Longitude: ${siteCoords[1].toFixed(6)}</p>
                </div>
            `);

            // Option alternative avec icône standard + étiquette
            // Si vous préférez une solution plus simple :
            /*
            const siteIconSimple = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: #00FF00; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            const siteMarkerSimple = L.marker(siteCoords, { icon: siteIconSimple }).addTo(map);

            // Ajouter une étiquette permanente
            L.marker(siteCoords, {
                icon: L.divIcon({
                    className: 'site-label',
                    html: `<div style="background: white; padding: 2px 6px; border-radius: 3px; border: 1px solid #00FF00;
                            font-size: 10px; font-weight: bold; margin-top: -35px; box-shadow: 0 1px 3px rgba(0,0,0,0.3);">
                            ${siteName}
                        </div>`,
                    iconSize: [100, 20],
                    iconAnchor: [50, 0]
                })
            }).addTo(map);
            */

            // Marqueur du Point de Pointage (rouge)
            const pointageIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: #FF0000; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            const pointageMarker = L.marker(pointageCoords, { icon: pointageIcon }).addTo(map);
            pointageMarker.bindPopup(`
                <div style="padding: 10px; max-width: 300px;">
                    <h4 style="margin: 0 0 10px 0; color: #FF0000;">📍 Point GPS de Pointage</h4>
                    <p><strong>Type:</strong> Pointage Opérationnel</p>
                    <p><strong>Coordonnées:</strong></p>
                    <p>Latitude: ${pointageCoords[0].toFixed(6)}<br>
                    Longitude: ${pointageCoords[1].toFixed(6)}</p>
                    <p><strong>Distance depuis le site:</strong> ${distanceText}</p>
                </div>
            `);

            // Dessiner une ligne entre les deux points
            const line = L.polyline([siteCoords, pointageCoords], {
                color: '#0000FF',
                weight: 3,
                opacity: 0.8,
                dashArray: '10, 10'
            }).addTo(map);

            // Ajouter une légende
            const legend = L.control({ position: 'topright' });
            legend.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'info legend');
                div.style.backgroundColor = 'white';
                div.style.padding = '10px';
                div.style.borderRadius = '5px';
                div.style.boxShadow = '0 2px 6px rgba(0,0,0,0.3)';

                div.innerHTML = `
                    <h4 style="margin: 0 0 10px 0; font-size: 14px;">Légende</h4>
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <div style="width: 15px; height: 15px; background: #00FF00; border-radius: 50%; border: 2px solid #000; margin-right: 10px;"></div>
                        <span style="font-size: 12px;">${siteName}</span>
                    </div>
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <div style="width: 15px; height: 15px; background: #FF0000; border-radius: 50%; border: 2px solid #000; margin-right: 10px;"></div>
                        <span style="font-size: 12px;">Point GPS de Pointage</span>
                    </div>
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #ddd;">
                        <strong style="font-size: 12px;">Distance:</strong>
                        <p style="margin: 5px 0 0 0; font-size: 12px; color: #0000FF;">${distanceText}</p>
                    </div>
                `;
                return div;
            };
            legend.addTo(map);

            // Ajuster la vue pour voir les deux points
            const bounds = L.latLngBounds([siteCoords, pointageCoords]);
            map.fitBounds(bounds, { padding: [50, 50] });

            // Ajouter un contrôle de mesure de distance
            L.control.scale({ imperial: false }).addTo(map);
        });
    </script>

    <style>
        .custom-div-icon {
            background: transparent;
            border: none;
        }
        .leaflet-popup-content {
            margin: 13px 19px;
            line-height: 1.4;
        }

        /* Style pour l'étiquette du site */
        .site-label {
            background: transparent;
            border: none;
            font-family: Arial, sans-serif;
        }

        /* Option: Ajouter une flèche vers le point */
        .custom-div-icon::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid white;
        }
    </style>

@endsection
