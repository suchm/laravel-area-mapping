import Draw from 'ol/interaction/Draw.js';
import Map from "ol/Map.js";
import View from "ol/View.js";
import TileLayer from "ol/layer/Tile.js";
import VectorSource from "ol/source/Vector";
import VectorLayer from "ol/layer/Vector";
import GeoJSON from 'ol/format/GeoJSON';
import { Modify, Select, defaults as defaultInteractions } from 'ol/interaction.js';
import { getCenter } from 'ol/extent';
import OSM from "ol/source/OSM.js";
import Dropzone from 'dropzone';

class GeoJsonMap {
    constructor(mapElementId, textareaId) {
        this.textarea = document.getElementById(textareaId);
        this.source = new VectorSource(this.getInitialGeoJson());
        this.vectorLayer = new VectorLayer({ source: this.source });

        this.select = new Select({ wrapX: false });
        this.modify = new Modify({ features: this.select.getFeatures() });

        this.view = new View({
            projection: "EPSG:4326",
            center: [0, 0],
            zoom: 2,
        });

        this.map = new Map({
            interactions: defaultInteractions().extend([this.select, this.modify]),
            target: mapElementId,
            layers: [
                new TileLayer({ source: new OSM() }),
                this.vectorLayer,
            ],
            view: this.view,
        });

        this.setupListeners();
        if (this.allowInteraction()) {
            this.addDrawingInteraction();
        } else {
            this.fitMapToSourceExtent();
        }
    }

    getInitialGeoJson() {
        return this.textarea.value.trim() === ''
            ? { wrapX: false }
            : { features: new GeoJSON().readFeatures(JSON.parse(this.textarea.value)) };
    }

    allowInteraction() {
        return this.textarea.value.trim() === '';
    }

    setupListeners() {
        this.textarea.addEventListener('keyup', this.onTextareaInput.bind(this));
        this.modify.on('modifyend', this.onModifyEnd.bind(this));
    }

    onTextareaInput() {
        let geoJson;
        try {
            geoJson = JSON.parse(this.textarea.value);
        } catch (e) {
            console.error("Invalid JSON");
            this.source.clear(); // Clear the source if JSON is invalid
            return;
        }

        if (this.validateGeoJson(geoJson)) {
            const newFeatures = new GeoJSON().readFeatures(geoJson);
            this.source.clear();
            this.source.addFeatures(newFeatures);
            this.fitMapToFeature(newFeatures[0]); // Fit map to the first feature
        } else {
            console.log('GeoJson is not valid');
        }
    }

    onModifyEnd(evt) {
        const coords = evt.features.item(0).getGeometry().getCoordinates('right');
        this.updateTextareaWithGeoJson(coords);
    }

    addDrawingInteraction() {
        this.draw = new Draw({
            source: this.source,
            type: 'Polygon',
        });
        this.map.addInteraction(this.draw);
        this.draw.on('drawend', this.onDrawEnd.bind(this));
    }

    onDrawEnd(evt) {
        const coords = evt.feature.getGeometry().getCoordinates('right');
        this.updateTextareaWithGeoJson(coords);
        this.map.removeInteraction(this.draw);
    }

    expandExtent(extent, padding) {
        const resolution = this.map.getView().getResolution();
        const paddingInMapUnits = padding * resolution; // Convert padding from pixels to map units

        const [minX, minY, maxX, maxY] = extent;
        return [
            minX - paddingInMapUnits,
            minY - paddingInMapUnits,
            maxX + paddingInMapUnits,
            maxY + paddingInMapUnits
        ];
    }

    fitMapToFeature(feature) {
        if (feature) {
            const extent = feature.getGeometry().getExtent();
            // Create a padded extent
            const paddedExtent = this.expandExtent(extent, 20);
            // Calculate the center of the padded extent
            this.map.getView().animate({
                center: getCenter(paddedExtent),
                duration: 500, // Animation duration in milliseconds
                zoom: this.map.getView().getZoomForResolution(this.map.getView().getResolutionForExtent(paddedExtent))
            });
        }
    }

    fitMapToSourceExtent() {
        const features = this.source.getFeatures();
        if (features.length > 0) {
            this.fitMapToFeature(features[0]); // Fit map to the first feature
        }
    }

    updateTextareaWithGeoJson(coords) {
        if (Array.isArray(coords)) {
            const geojsonData = this.createGeoJsonFromCoords(coords);
            this.textarea.value = JSON.stringify(geojsonData, null, 2);
            this.textarea.dispatchEvent(new Event('input'));
        }
    }

    createGeoJsonFromCoords(coords) {
        return {
            "type": "FeatureCollection",
            "features": [
                {
                    "type": "Feature",
                    "properties": {},
                    "geometry": {
                        "coordinates": coords,
                        "type": "Polygon"
                    }
                }
            ]
        };
    }

    validateGeoJson(data) {
        const format = new GeoJSON();
        try {
            const features = format.readFeatures(data);
            return features.length > 0;
        } catch (e) {
            console.error("Invalid GeoJSON according to OpenLayers:", e);
            return false;
        }
    }

    updateMapWithGeoJson(geoJson) {
        if (this.validateGeoJson(geoJson)) {
            const newFeatures = new GeoJSON().readFeatures(geoJson);
            this.source.clear();
            this.source.addFeatures(newFeatures);
            this.fitMapToFeature(newFeatures[0]); // Fit map to the first feature
            this.map.removeInteraction(this.draw);
            this.textarea.value = JSON.stringify(geoJson, null, 2); // Update the textarea with the new GeoJSON
            this.textarea.dispatchEvent(new Event('input'));
        } else {
            console.error('Invalid GeoJson data');
        }
    }

    clearMapFeaturesAndGeoJsonData() {
        this.source.clear();
        this.textarea.value = '';
    }

    populateFormFieldsFromGeoJson(geoJson) {
        if (geoJson && geoJson.features && geoJson.features.length > 0) {
            const feature = geoJson.features[0]; // Assuming you're working with the first feature
            const properties = feature.properties;

            // Extract properties if they exist and are not null
            const name = properties?.name?.trim() ?? "" !== "";
            const description = properties?.description?.trim() ?? "" !== "";
            const validFrom = properties?.begin?.trim() ?? "" !== "";
            const validTo = properties?.end?.trim() ?? "" !== "";

            const nameEl = document.getElementById('name');
            const descriptionEl = document.getElementById('description');
            const validfromEl = document.getElementById('valid_from');
            const validtoEl = document.getElementById('valid_to');

            if (name && this.isInputEmpty(nameEl.value)) {
                console.log(nameEl.value);
                nameEl.value = name;
                nameEl.dispatchEvent(new Event('input'));
            }
            if (description && this.isInputEmpty(descriptionEl.value)) {
                descriptionEl.value = description;
                descriptionEl.dispatchEvent(new Event('input'));
            }
            if (validFrom && this.isInputEmpty(validfromEl.value) && this.isISODateString(validFrom)) {
                validfromEl.value = this.formatDate(validFrom);
                validfromEl.dispatchEvent(new Event('input'));
            }
            if (validTo && this.isInputEmpty(validtoEl.value) && this.isISODateString(validTo)) {
                validtoEl.value = this.formatDate(validTo);
                validtoEl.dispatchEvent(new Event('input'));
            }
        } else {
            console.error("Invalid GeoJSON or missing features.");
        }
    }

    isInputEmpty(inputValue) {
        return inputValue === null || inputValue === undefined || inputValue === "";
    }

    isISODateString(str) {
        const isoDateRegex = /^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{1,3})?(?:Z|[\+\-]\d{2}:\d{2})?)|(\d{4}-\d{2}-\d{2})$/;
        return isoDateRegex.test(str);
    }

    formatDate(isoString) {
        const date = new Date(isoString);

        const day = String(date.getUTCDate()).padStart(2, '0');
        const month = String(date.getUTCMonth() + 1).padStart(2, '0'); // Months are 0-based, so add 1
        const year = date.getUTCFullYear();

        return `${year}-${month}-${day}`;
    }
}

window.onload = () => {
    const geoJsonMap = new GeoJsonMap('map', 'geojson');

    // Initialize Dropzone
    const dropZoneElement = document.getElementById('file-upload-form');

    if (dropZoneElement) {
        const dropzone = new Dropzone('#file-upload-form', {
            url: '#',
            autoProcessQueue: false,
            addRemoveLinks: true,
            maxFiles: 1, // Allow only a single file
            init: function () {

                this.on("addedfile", file => {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]); // Remove the previously uploaded file
                    }
                    // Get the default preview element
                    let previewElement = file.previewElement;

                    // Get the custom container
                    let customContainer = document.getElementById("dropzone-preview");

                    // Append the preview element to the custom container
                    customContainer.appendChild(previewElement);

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        try {
                            const jsonData = JSON.parse(e.target.result);
                            geoJsonMap.updateMapWithGeoJson(jsonData); // Update the map with the uploaded GeoJSON
                            geoJsonMap.populateFormFieldsFromGeoJson(jsonData);
                        } catch (error) {
                            alert('Error parsing JSON: ' + error.message);
                        }
                    };
                    reader.readAsText(file);
                });

                this.on("removedfile", file => {
                    geoJsonMap.clearMapFeaturesAndGeoJsonData();
                });

                const dropzoneElement = document.getElementById('file-dropzone');

                // Event listeners for drag enter and drag leave
                // Adds additional styling when hovered over
                if (dropzoneElement.length >= 1) {
                    this.on("dragenter", () => {
                        dropzoneElement.classList.add('dz-drag-hover');
                    });

                    this.on("dragleave", () => {
                        dropzoneElement.classList.remove('dz-drag-hover');
                    });

                    this.on("drop", () => {
                        dropzoneElement.classList.remove('dz-drag-hover');
                    });
                }
            },
        });

        Livewire.on('fileUploadComplete', () => {
            dropzone.removeAllFiles();
        });
    }

    const formTab = document.querySelector('#formTab');
    const formFields = document.querySelector('#formFields')
    const geojsonTab = document.querySelector('#geojsonTab');
    const geojsonFields = document.querySelector('#geojsonFields');

    const toggleTabVisibility = (showTab, hideTab, showFields, hideFields) => {
        showTab.classList.add('text-blue-600', 'hover:text-blue-600', 'dark:text-blue-500', 'dark:hover:text-blue-500', 'border-blue-600', 'dark:border-blue-500');
        showTab.classList.remove('hover:text-gray-600', 'hover:border-gray-300', 'dark:hover:text-gray-300');
        hideTab.classList.add('hover:text-gray-600', 'hover:border-gray-300', 'dark:hover:text-gray-300');
        hideTab.classList.remove('text-blue-600', 'hover:text-blue-600', 'dark:text-blue-500', 'dark:hover:text-blue-500', 'border-blue-600', 'dark:border-blue-500');
        showFields.classList.remove('hidden');
        hideFields.classList.add('hidden');
    };

    document.querySelector('#formTab').addEventListener('click', () => toggleTabVisibility(formTab, geojsonTab, formFields, geojsonFields));
    document.querySelector('#geojsonTab').addEventListener('click', () => toggleTabVisibility(geojsonTab, formTab, geojsonFields, formFields));
};
