// Global variables
let booths = [];
let selectedBooth = null;
let editingBoothId = null;
let isDragging = false;
let dragOffset = { x: 0, y: 0 };
let gridEnabled = false;
let currentEventId = null;

// CSRF Token setup for AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Initialize page
$(document).ready(function () {
    // Get event_id from URL params or form
    currentEventId = getEventIdFromURL() || $('#event_id').val();
    
    if (currentEventId) {
        loadBooths();
    } else {
        showAlert('Please select an event first', 'warning');
    }
    
    setupEventListeners();
});

// Get event ID from URL parameters
function getEventIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('event_id');
}

// Load booths from database
function loadBooths() {
    if (!currentEventId) {
        showAlert('No event selected', 'error');
        return;
    }

    showLoading();
    
    // Use the getBooths API endpoint from controller
    $.get('/admin/booths/api/get-booths', { event_id: currentEventId })
        .done(function (response) {
            if (response.success) {
                booths = response.booths || [];
                renderBooths();
                
                // Show event info
                if (response.event) {
                    console.log(`Loaded booths for: ${response.event.main_title}`);
                }
            } else {
                showAlert(response.message || 'Failed to load booths', 'error');
            }
        })
        .fail(function (xhr) {
            console.error('Error loading booths:', xhr);
            let errorMessage = 'Error loading booths';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            showAlert(errorMessage, 'error');
        })
        .always(function () {
            hideLoading();
        });
}

// Render booths on layout
function renderBooths() {
    const layout = document.getElementById('venueLayout');

    // Remove existing booths
    const existingBooths = layout.querySelectorAll('.booth');
    existingBooths.forEach(booth => booth.remove());

    // Add booths
    booths.forEach(booth => {
        const boothElement = createBoothElement(booth);
        layout.appendChild(boothElement);
    });
    
    console.log(`Rendered ${booths.length} booths`);
}

// Create booth element
function createBoothElement(booth) {
    const div = document.createElement('div');
    div.className = 'booth';
    div.id = `booth-${booth.id}`;
    div.innerHTML = booth.booth_id;

    // Set attributes
    div.setAttribute('data-id', booth.id);
    div.setAttribute('data-booth-id', booth.booth_id);
    div.setAttribute('data-section', booth.section);
    div.setAttribute('data-status', booth.status);
    div.setAttribute('data-price', booth.price);

    // Set position and size - handle null values
    div.style.left = (booth.position_x || 0) + 'px';
    div.style.top = (booth.position_y || 0) + 'px';
    div.style.width = (booth.width || 25) + 'px';
    div.style.height = (booth.height || 25) + 'px';

    // Add event listeners
    setupBoothEventListeners(div);

    return div;
}

// Setup event listeners for booth
function setupBoothEventListeners(boothElement) {
    // Mouse events for drag and drop
    boothElement.addEventListener('mousedown', startDrag);
    boothElement.addEventListener('click', selectBooth);
    boothElement.addEventListener('dblclick', editBooth);

    // Touch events for mobile
    boothElement.addEventListener('touchstart', startDrag);
}

// Setup global event listeners
function setupEventListeners() {
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', endDrag);
    document.addEventListener('touchmove', drag);
    document.addEventListener('touchend', endDrag);

    // Keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Delete' && selectedBooth) {
            deleteSelectedBooth();
        }
        if (e.key === 'Escape') {
            clearSelection();
        }
    });
}

// Start dragging
function startDrag(e) {
    if (e.target.getAttribute('data-status') === 'booked' && !e.target.classList.contains('selected')) {
        return; // Don't allow dragging booked booths unless selected
    }

    e.preventDefault();
    isDragging = true;
    selectedBooth = e.target;

    // Clear previous selections
    document.querySelectorAll('.booth.selected').forEach(b => b.classList.remove('selected'));
    selectedBooth.classList.add('selected', 'dragging');

    const rect = selectedBooth.getBoundingClientRect();
    const layoutRect = document.getElementById('venueLayout').getBoundingClientRect();

    const clientX = e.clientX || (e.touches && e.touches[0].clientX);
    const clientY = e.clientY || (e.touches && e.touches[0].clientY);

    dragOffset = {
        x: clientX - rect.left,
        y: clientY - rect.top
    };
}

// Drag movement
function drag(e) {
    if (!isDragging || !selectedBooth) return;

    e.preventDefault();

    const layoutRect = document.getElementById('venueLayout').getBoundingClientRect();
    const clientX = e.clientX || (e.touches && e.touches[0].clientX);
    const clientY = e.clientY || (e.touches && e.touches[0].clientY);

    let newX = clientX - layoutRect.left - dragOffset.x;
    let newY = clientY - layoutRect.top - dragOffset.y;

    // Snap to grid if enabled
    if (gridEnabled) {
        newX = Math.round(newX / 20) * 20;
        newY = Math.round(newY / 20) * 20;
    }

    // Boundary checking
    const maxX = 1600 - parseInt(selectedBooth.style.width);
    const maxY = 500 - parseInt(selectedBooth.style.height);

    newX = Math.max(0, Math.min(newX, maxX));
    newY = Math.max(0, Math.min(newY, maxY));

    selectedBooth.style.left = newX + 'px';
    selectedBooth.style.top = newY + 'px';
}

// End dragging
function endDrag(e) {
    if (!isDragging || !selectedBooth) return;

    isDragging = false;
    selectedBooth.classList.remove('dragging');

    // Update position in database
    const boothId = selectedBooth.getAttribute('data-id');
    const newX = parseInt(selectedBooth.style.left);
    const newY = parseInt(selectedBooth.style.top);

    updateBoothPosition(boothId, newX, newY);
}

// Update booth position in database
function updateBoothPosition(boothId, x, y) {
    $.post('/admin/booths/update-position', {
        booth_id: boothId,
        position_x: x,
        position_y: y
    })
        .done(function (response) {
            if (response.success) {
                // Update local data
                const booth = booths.find(b => b.id == boothId);
                if (booth) {
                    booth.position_x = x;
                    booth.position_y = y;
                }
                showAlert('Booth position updated', 'success');
            } else {
                showAlert(response.message || 'Failed to update position', 'error');
                // Revert position
                loadBooths();
            }
        })
        .fail(function (xhr) {
            console.error('Position update failed:', xhr);
            showAlert('Failed to update booth position', 'error');
            // Revert position
            loadBooths();
        });
}

// Select booth
function selectBooth(e) {
    if (isDragging) return;

    e.stopPropagation();

    // Clear previous selections
    document.querySelectorAll('.booth.selected').forEach(b => b.classList.remove('selected'));

    selectedBooth = e.target;
    selectedBooth.classList.add('selected');

    // Show booth info
    showBoothInfo(selectedBooth);
}

// Show booth information
function showBoothInfo(boothElement) {
    const boothId = boothElement.getAttribute('data-booth-id');
    const section = boothElement.getAttribute('data-section');
    const status = boothElement.getAttribute('data-status');
    const price = boothElement.getAttribute('data-price');

    console.log(`Selected: ${boothId} | Section: ${section} | Status: ${status} | Price: Rp ${price}`);
}

// Edit booth (double click)
function editBooth(e) {
    e.stopPropagation();

    const boothId = e.target.getAttribute('data-id');
    const booth = booths.find(b => b.id == boothId);

    if (booth) {
        editingBoothId = booth.id;
        populateBoothForm(booth);
        $('#boothModalLabel').text('Edit Booth');
        $('#deleteBooth').show();
        $('#boothModal').modal('show');
    }
}

// Clear selection
function clearSelection() {
    document.querySelectorAll('.booth.selected').forEach(b => b.classList.remove('selected'));
    selectedBooth = null;
}

// Add new booth
function addNewBooth() {
    if (!currentEventId) {
        showAlert('Please select an event first', 'error');
        return;
    }
    
    editingBoothId = null;
    clearBoothForm();
    $('#boothModalLabel').text('Add New Booth');
    $('#deleteBooth').hide();
    $('#boothModal').modal('show');
}

// Populate booth form
function populateBoothForm(booth) {
    $('#boothId').val(booth.booth_id);
    $('#boothName').val(booth.booth_name);
    $('#section').val(booth.section);
    $('#price').val(booth.price);
    $('#width').val(booth.width);
    $('#height').val(booth.height);
    $('#status').val(booth.status);
}

// Clear booth form
function clearBoothForm() {
    $('#boothForm')[0].reset();
    $('#width').val(25);
    $('#height').val(25);
    $('#status').val('available');
}

// Save booth
function saveBooth() {
    if (!currentEventId) {
        showAlert('No event selected', 'error');
        return;
    }

    // Collect form data
    const formData = {
        event_id: currentEventId,
        booth_id: $('#boothId').val().trim(),
        booth_name: $('#boothName').val().trim(),
        section: $('#section').val(),
        price: $('#price').val(),
        width: $('#width').val() || 25,
        height: $('#height').val() || 25,
        status: $('#status').val() || 'available'
    };

    // Add position for new booths
    if (!editingBoothId) {
        formData.position_x = 100;
        formData.position_y = 100;
    }

    // Validate required fields
    if (!formData.booth_id || !formData.booth_name || !formData.section || !formData.price) {
        showAlert('Please fill in all required fields', 'error');
        return;
    }

    // Use controller routes
    const url = editingBoothId ? `/admin/booths/${editingBoothId}` : '/admin/booths';
    const method = editingBoothId ? 'PUT' : 'POST';

    console.log('Saving booth:', { url, method, data: formData });

    showLoading();

    $.ajax({
        url: url,
        method: method,
        data: formData,
        dataType: 'json'
    })
        .done(function (response) {
            console.log('Save response:', response);
            if (response.success) {
                $('#boothModal').modal('hide');
                loadBooths();
                showAlert(response.message, 'success');
            } else {
                showAlert(response.message || 'Failed to save booth', 'error');
            }
        })
        .fail(function (xhr) {
            console.error('Save failed:', xhr);
            let errorMessage = 'Failed to save booth';

            if (xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    // Laravel validation errors
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join(', ');
                } else if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
            } else if (xhr.responseText) {
                try {
                    const errorData = JSON.parse(xhr.responseText);
                    errorMessage = errorData.message || errorMessage;
                } catch (e) {
                    errorMessage = `Server error (${xhr.status}): ${xhr.statusText}`;
                }
            }

            showAlert(errorMessage, 'error');
        })
        .always(function () {
            hideLoading();
        });
}

// Delete booth
function deleteBooth() {
    if (!editingBoothId) return;

    if (confirm('Are you sure you want to delete this booth?')) {
        showLoading();
        $.ajax({
            url: `/admin/booths/${editingBoothId}`,
            method: 'DELETE',
            dataType: 'json'
        })
            .done(function (response) {
                if (response.success) {
                    $('#boothModal').modal('hide');
                    loadBooths();
                    showAlert(response.message, 'success');
                } else {
                    showAlert(response.message || 'Failed to delete booth', 'error');
                }
            })
            .fail(function (xhr) {
                console.error('Delete failed:', xhr);
                let errorMessage = 'Failed to delete booth';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert(errorMessage, 'error');
            })
            .always(function () {
                hideLoading();
            });
    }
}

// Delete selected booth
function deleteSelectedBooth() {
    if (!selectedBooth) return;

    const boothId = selectedBooth.getAttribute('data-id');
    const boothName = selectedBooth.getAttribute('data-booth-id');

    if (confirm(`Are you sure you want to delete ${boothName}?`)) {
        showLoading();
        $.ajax({
            url: `/admin/booths/${boothId}`,
            method: 'DELETE',
            dataType: 'json'
        })
            .done(function (response) {
                if (response.success) {
                    loadBooths();
                    showAlert(response.message, 'success');
                } else {
                    showAlert(response.message || 'Failed to delete booth', 'error');
                }
            })
            .fail(function (xhr) {
                console.error('Delete failed:', xhr);
                let errorMessage = 'Failed to delete booth';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert(errorMessage, 'error');
            })
            .always(function () {
                hideLoading();
            });
    }
}

// Save layout
function saveLayout() {
    showAlert('Layout saved successfully!', 'success');
}

// Reset layout
function resetLayout() {
    if (confirm('Are you sure you want to reset the layout? This will reload all booths from the database.')) {
        loadBooths();
    }
}

// Initialize booths
function initializeBooths() {
    if (!currentEventId) {
        showAlert('Please select an event first', 'error');
        return;
    }

    if (confirm('This will create default booths for the selected event. Continue?')) {
        showLoading();
        $.post('/admin/booths/initialize', { event_id: currentEventId })
            .done(function (response) {
                if (response.success) {
                    loadBooths();
                    showAlert(`${response.count} booths initialized successfully!`, 'success');
                } else {
                    showAlert(response.message, 'warning');
                }
            })
            .fail(function (xhr) {
                console.error('Initialize failed:', xhr);
                let errorMessage = 'Failed to initialize booths';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert(errorMessage, 'error');
            })
            .always(function () {
                hideLoading();
            });
    }
}

// Change event
function changeEvent(eventId) {
    if (eventId) {
        currentEventId = eventId;
        // Update URL without reload
        const url = new URL(window.location);
        url.searchParams.set('event_id', eventId);
        window.history.pushState({}, '', url);
        
        loadBooths();
    }
}

// Toggle grid
function toggleGrid() {
    gridEnabled = !gridEnabled;
    const layout = document.getElementById('venueLayout');

    if (gridEnabled) {
        layout.style.backgroundImage = 'radial-gradient(circle at 1px 1px, rgba(0,0,0,0.15) 1px, transparent 0)';
        showAlert('Grid enabled', 'info');
    } else {
        layout.style.backgroundImage = 'radial-gradient(circle at 1px 1px, rgba(0,0,0,0.05) 1px, transparent 0)';
        showAlert('Grid disabled', 'info');
    }
}

// Search booth
function searchBooth() {
    const searchTerm = $('#searchBooth').val().toLowerCase();
    if (!searchTerm) {
        clearSelection();
        return;
    }

    const booth = document.querySelector(`.booth[data-booth-id*="${searchTerm.toUpperCase()}"]`);
    if (booth) {
        clearSelection();
        booth.classList.add('selected');
        selectedBooth = booth;

        // Scroll to booth
        booth.scrollIntoView({ behavior: 'smooth', block: 'center' });
        showBoothInfo(booth);
    } else {
        showAlert('Booth not found', 'warning');
    }
}

// Auto select cheapest booth
function autoSelectCheapestBooth() {
    const availableBooths = document.querySelectorAll('.booth[data-status="available"]');
    let minPrice = Infinity;
    let cheapest = null;

    availableBooths.forEach(booth => {
        const price = parseInt(booth.getAttribute('data-price'));
        if (price < minPrice) {
            minPrice = price;
            cheapest = booth;
        }
    });

    if (cheapest) {
        clearSelection();
        cheapest.classList.add('selected');
        selectedBooth = cheapest;

        cheapest.scrollIntoView({ behavior: 'smooth', block: 'center' });
        showAlert(`Cheapest booth selected: ${cheapest.getAttribute('data-booth-id')} - Rp ${minPrice.toLocaleString()}`, 'success');
    } else {
        showAlert('No available booths found', 'warning');
    }
}

// Utility functions
function showLoading() {
    $('#loadingOverlay').show();
}

function hideLoading() {
    $('#loadingOverlay').hide();
}

function showAlert(message, type = 'info') {
    // Simple alert for now - you can integrate with a proper notification library
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';

    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 10000; min-width: 300px;" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    $('body').append(alertHtml);

    // Auto dismiss after 3 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 3000);
}

// Click outside to deselect
document.addEventListener('click', function (e) {
    if (!e.target.closest('.booth') && !e.target.closest('.modal')) {
        clearSelection();
    }
});

// Enter key to search
$('#searchBooth').keypress(function (e) {
    if (e.which === 13) {
        searchBooth();
    }
});

// Handle event selection change
$('#event_id').change(function() {
    const eventId = $(this).val();
    if (eventId) {
        changeEvent(eventId);
    }
});