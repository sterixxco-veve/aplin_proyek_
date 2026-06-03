<!-- CERTIFICATE TEMPLATE EDITOR MODAL -->
<div class="modal fade" id="templateEditorModal" tabindex="-1" aria-hidden="true"
    data-event-id="{{ $event->id_event }}">
    <div class="modal-dialog modal-fullscreen" style="max-width: 1200px; margin: auto;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold">Certificate Template Editor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4" style="background-color: #f8f9fa;">
                <div class="row gap-3 h-100">
                    <!-- Canvas Area -->
                    <div class="col-lg-9">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 d-flex flex-column" style="height: 600px;">
                                <h6 class="text-muted mb-3 small">Preview & Edit (Drag to position text)</h6>
                                <div id="canvas-container"
                                    class="flex-grow-1 d-flex align-items-center justify-content-center"
                                    style="border: 2px dashed #dee2e6; border-radius: 8px; overflow: hidden; background-color: #fff;">
                                    <canvas id="fabricCanvas"
                                        style="display: block; border: none; cursor: move;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Control Panel -->
                    <div class="col-lg-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light border-0">
                                <h6 class="mb-0 fw-bold">Text Settings</h6>
                            </div>
                            <div class="card-body">
                                <!-- Text Content -->
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Preview Text</label>
                                    <input type="text" id="textContent" class="form-control"
                                        placeholder="e.g., John Doe" value="Participant Name">
                                    <small class="text-muted d-block mt-1">This will be replaced with actual participant
                                        names on generation.</small>
                                </div>

                                <!-- Font Size -->
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Font Size</label>
                                    <div class="input-group input-group-sm">
                                        <input type="range" id="fontSize" class="form-range" min="12" max="100"
                                            value="40">
                                        <span class="input-group-text" id="fontSizeValue">40px</span>
                                    </div>
                                </div>

                                <!-- Font Family -->
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Font Family</label>
                                    <select id="fontFamily" class="form-select form-select-sm">
                                        <option value="Arial">Arial</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Times New Roman">Times New Roman</option>
                                        <option value="Courier New">Courier New</option>
                                        <option value="Verdana">Verdana</option>
                                    </select>
                                </div>

                                <!-- Font Weight -->
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Font Weight</label>
                                    <select id="fontWeight" class="form-select form-select-sm">
                                        <option value="normal">Normal</option>
                                        <option value="bold" selected>Bold</option>
                                        <option value="lighter">Lighter</option>
                                    </select>
                                </div>

                                <!-- Text Color -->
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Text Color</label>
                                    <input type="color" id="textColor" class="form-control form-control-color"
                                        value="#000000">
                                </div>

                                <!-- Text Alignment -->
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Alignment</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="textAlign" value="left"
                                            id="alignLeft">
                                        <label class="btn btn-outline-secondary btn-sm" for="alignLeft">
                                            <i class="bi bi-text-left"></i>
                                        </label>

                                        <input type="radio" class="btn-check" name="textAlign" value="center"
                                            id="alignCenter" checked>
                                        <label class="btn btn-outline-secondary btn-sm" for="alignCenter">
                                            <i class="bi bi-text-center"></i>
                                        </label>

                                        <input type="radio" class="btn-check" name="textAlign" value="right"
                                            id="alignRight">
                                        <label class="btn btn-outline-secondary btn-sm" for="alignRight">
                                            <i class="bi bi-text-right"></i>
                                        </label>
                                    </div>
                                </div>

                                <hr>

                                <!-- Info -->
                                <div class="alert alert-info small mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>How to use:</strong>
                                    <ol class="mb-0 mt-2 ps-3">
                                        <li>Adjust text settings</li>
                                        <li>Drag text on canvas to position</li>
                                        <li>Click "Save Configuration" to save</li>
                                    </ol>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="addTextBox('recipient_name')">
                                        <i class="bi bi-person me-1"></i>
                                        Add Recipient Name
                                    </button>

                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addTextBox('recipient_email')">
                                        <i class="bi bi-envelope me-1"></i>
                                        Add Recipient Email
                                    </button>

                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="addTextBox('static')">
                                        <i class="bi bi-fonts me-1"></i>
                                        Add Static Text
                                    </button>

                                    <button type="button" class="btn btn-warning btn-sm" id="removeTextBtn">
                                        <i class="bi bi-trash me-1"></i>
                                        Remove Selected
                                    </button>

                                    <button type="button" class="btn btn-light btn-sm" id="resetTextBtn">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                                        Reset
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveConfigBtn">
                    <i class="bi bi-check-circle me-1"></i> Save Configuration
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Fabric.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

<script>
    let canvas;
    let selectedText = null;
    let textObjects = [];
    let originalImageDimensions = { width: null, height: null };
    let canvasScale = 1;

    document.addEventListener('DOMContentLoaded', function () {
        initializeFabricCanvas();
        setupEventListeners();
        loadTemplateImage();
    });

    // Reinitialize when modal is shown
    const modalElement = document.getElementById('templateEditorModal');
    if (modalElement) {
        modalElement.addEventListener('shown.bs.modal', function () {
            console.log('Modal shown, reloading template...');
            // Dispose old canvas and create new one
            if (canvas) {
                canvas.dispose();
            }
            setTimeout(() => {
                initializeFabricCanvas();
                loadTemplateImage();
            }, 200);
        });
    }

    function initializeFabricCanvas() {
        const container = document.getElementById('canvas-container');

        // Get actual container dimensions
        const width = container.clientWidth || 800;
        const height = container.clientHeight || 600;

        console.log('Canvas container dimensions:', width, 'x', height);

        canvas = new fabric.Canvas('fabricCanvas', {
            width: width,
            height: height,
            backgroundColor: '#f0f0f0'
        });

        // Selection events
        canvas.on('selection:created', (e) => {
            selectedText = e.selected[0];
            updateControlPanel();
        });

        canvas.on('selection:updated', (e) => {
            selectedText = e.selected[0];
            updateControlPanel();
        });

        canvas.on('selection:cleared', () => {
            selectedText = null;
            updateControlPanel();
        });

        canvas.on('object:modified', () => {
            saveCurrentConfiguration();
        });
    }

    function loadTemplateImage() {
        const templatePath = '{{ $templatePath ?? null }}';

        console.log('Template path from session:', templatePath);

        if (!templatePath) {
            console.warn('No template path available');
            addDefaultMessage();
            return;
        }

        // Build correct URL for the image
        const imageUrl = '/storage/' + templatePath;

        console.log('Loading template from URL:', imageUrl);

        // Test if image is accessible
        fetch(imageUrl, { method: 'HEAD' })
            .then(response => {
                if (!response.ok) {
                    console.error('Image fetch failed with status:', response.status);
                    addDefaultMessage();
                    return;
                }

                // Image exists, now load it with Fabric.js
                fabric.Image.fromURL(imageUrl, (img) => {
                    console.log('Image loaded successfully, original size:', img.width, 'x', img.height);

                    // Store original dimensions
                    originalImageDimensions = {
                        width: img.width,
                        height: img.height
                    };

                    // Clear canvas
                    canvas.clear();

                    const container = document.getElementById('canvas-container');
                    const maxWidth = container.offsetWidth || 900;
                    const maxHeight = container.offsetHeight || 600;

                    // Calculate scale to fit in container
                    const scaleX = maxWidth / img.width;
                    const scaleY = maxHeight / img.height;
                    const scale = Math.min(scaleX, scaleY, 1); // Don't scale up

                    // Store scale for later use
                    canvasScale = scale;

                    img.scale(scale);

                    // Update canvas size
                    const newWidth = img.width * scale;
                    const newHeight = img.height * scale;

                    canvas.setWidth(newWidth);
                    canvas.setHeight(newHeight);

                    // Set as background image
                    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                        top: canvas.height / 2,
                        left: canvas.width / 2,
                        originX: 'center',
                        originY: 'center'
                    });

                    console.log('Image details:', {
                        originalSize: originalImageDimensions,
                        canvasScale: canvasScale,
                        displaySize: { width: newWidth, height: newHeight }
                    });

                    canvas.renderAll();
                    console.log('Template image displayed successfully');
                }, null, {
                    crossOrigin: 'anonymous'
                });
            })
            .catch(error => {
                console.error('Error loading template image:', error);
                addDefaultMessage();
            });
    }

    function addDefaultMessage() {
        const text = new fabric.Text('Please upload a template first', {
            left: canvas.width / 2,
            top: canvas.height / 2,
            fontSize: 24,
            fill: '#999',
            textAlign: 'center',
            originX: 'center',
            originY: 'center'
        });

        canvas.add(text);
        canvas.renderAll();
    }

    function setupEventListeners() {
        // Add text button
        // document.getElementById('addTextBtn').addEventListener('click', addTextBox);

        // Remove text button
        document.getElementById('removeTextBtn').addEventListener('click', removeSelectedText);

        // Reset button
        document.getElementById('resetTextBtn').addEventListener('click', resetEditor);

        // Save configuration
        document.getElementById('saveConfigBtn').addEventListener('click', saveConfiguration);

        // Text content change
        document.getElementById('textContent').addEventListener('change', updateSelectedTextContent);

        // Font size
        document.getElementById('fontSize').addEventListener('input', (e) => {
            document.getElementById('fontSizeValue').textContent = e.target.value + 'px';
            updateSelectedTextProperty('fontSize', parseInt(e.target.value));
        });

        // Font family
        document.getElementById('fontFamily').addEventListener('change', (e) => {
            updateSelectedTextProperty('fontFamily', e.target.value);
        });

        // Font weight
        document.getElementById('fontWeight').addEventListener('change', (e) => {
            updateSelectedTextProperty('fontWeight', e.target.value);
        });

        // Text color
        document.getElementById('textColor').addEventListener('change', (e) => {
            updateSelectedTextProperty('fill', e.target.value);
        });

        // Text alignment
        document.querySelectorAll('input[name="textAlign"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                updateSelectedTextProperty('textAlign', e.target.value);
            });
        });
    }

    function addTextBox(type = 'static') {

        let defaultText = document.getElementById('textContent').value;

        // Dynamic placeholders
        if (type === 'recipient_name') {
            defaultText = '{NAMA}';
        }

        if (type === 'recipient_email') {
            defaultText = '{EMAIL}';
        }

        const text = new fabric.Text(defaultText, {
            left: canvas.width / 2,
            top: canvas.height / 2,
            fontSize: parseInt(document.getElementById('fontSize').value),
            fontFamily: document.getElementById('fontFamily').value,
            fontWeight: document.getElementById('fontWeight').value,
            fill: document.getElementById('textColor').value,
            textAlign: document.querySelector('input[name="textAlign"]:checked').value,
            originX: 'center',
            originY: 'center'
        });

        // IMPORTANT
        text.customType = type;

        canvas.add(text);
        canvas.setActiveObject(text);
        canvas.renderAll();

        textObjects.push(text);
    }

    function removeSelectedText() {
        if (selectedText) {
            canvas.remove(selectedText);
            textObjects = textObjects.filter(obj => obj !== selectedText);
            selectedText = null;
            canvas.renderAll();
        }
    }

    function updateSelectedTextContent() {
        if (selectedText && selectedText.type === 'text') {
            selectedText.set('text', document.getElementById('textContent').value);
            canvas.renderAll();
        }
    }

    function updateSelectedTextProperty(property, value) {
        if (selectedText && selectedText.type === 'text') {
            selectedText.set(property, value);
            canvas.renderAll();
        }
    }

    function updateControlPanel() {
        if (selectedText && selectedText.type === 'text') {
            document.getElementById('textContent').value = selectedText.text;
            document.getElementById('fontSize').value = selectedText.fontSize;
            document.getElementById('fontSizeValue').textContent = selectedText.fontSize + 'px';
            document.getElementById('fontFamily').value = selectedText.fontFamily;
            document.getElementById('fontWeight').value = selectedText.fontWeight;
            document.getElementById('textColor').value = selectedText.fill;
            document.querySelector(`input[value="${selectedText.textAlign}"]`).checked = true;
        }
    }

    function resetEditor() {
        if (confirm('Reset all text boxes?')) {
            canvas.forEachObject(obj => {
                if (obj.type === 'text') {
                    canvas.remove(obj);
                }
            });
            textObjects = [];
            canvas.renderAll();
        }
    }

    function saveCurrentConfiguration() {
        // Auto-save configuration to session        
        const config = {
            // Store original image dimensions for coordinate scaling
            imageWidth: originalImageDimensions.width,
            imageHeight: originalImageDimensions.height,
            // Store canvas dimensions
            canvasWidth: canvas.width,
            canvasHeight: canvas.height,
            // Store scale factor
            canvasScale: canvasScale,
            textBoxes: canvas.getObjects('text').map(obj => ({
                text: obj.text,
                type: obj.customType || 'static',
                left: obj.left,
                top: obj.top,
                fontSize: obj.fontSize,
                fontFamily: obj.fontFamily,
                fontWeight: obj.fontWeight,
                fill: obj.fill,
                textAlign: obj.textAlign
            }))
        };

        console.log('Configuration saved:', config);

        // Store in session storage for now
        sessionStorage.setItem('certificate_config', JSON.stringify(config));
    }

    function saveConfiguration() {
        saveCurrentConfiguration();

        // Send to server
        const config = JSON.parse(sessionStorage.getItem('certificate_config'));
        const eventId = document.getElementById('templateEditorModal').getAttribute('data-event-id');

        console.log('Event ID:', eventId);
        console.log('Sending config to server:', config);

        if (!eventId) {
            alert('Error: Event ID not found!');
            console.error('Event ID is missing!');
            return;
        }

        fetch(`/events/${eventId}/certificates/save-config`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(config)
        })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('SAVE RESPONSE:', data);
                if (data.success) {
                    alert('Configuration saved successfully!');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('templateEditorModal'));
                    modal.hide();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Save Error:', error);
                alert('Failed to save configuration: ' + error.message);
            });
    }
</script>