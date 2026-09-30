<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin - EventKu</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link href="{{ asset('admin/img/favicon.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('admin/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">

    <style>
        .chat-sidebar {
            border-right: 1px solid #ddd;
            height: 500px;
            overflow-y: auto;
        }

        .tenant-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.3s;
        }

        .tenant-item:hover {
            background-color: #f8f9fa;
        }

        .tenant-item.active {
            background-color: #007bff;
            color: white;
        }

        .unread-badge {
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
        }

        .message-item {
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 15px;
            max-width: 70%;
        }

        .message-sent {
            background-color: #007bff;
            color: white;
            margin-left: auto;
            text-align: right;
        }

        .message-received {
            background-color: #e9ecef;
            color: #333;
        }

        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3 d-flex align-items-center">
                    <i class="fa fa-ticket-alt me-2 text-primary fs-4"></i>
                    <h4 class="mb-0 text-primary">EventKu</h4>
                </a>

                <!-- User Info -->
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="{{ asset('admin/img/user.jpg') }}" alt=""
                            style="width: 40px; height: 40px;">
                        <div
                            class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <span>Admin</span>
                    </div>
                </div>

                <!-- Menu -->
                <div class="navbar-nav w-100">
                    <ul class="navbar-nav w-100">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-banner') }}"
                                class="nav-link {{ request()->routeIs('manage-banner') ? 'active' : '' }}">
                                <i class="fa fa-image me-2"></i>Manage Banner
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-event') }}"
                                class="nav-link {{ request()->routeIs('manage-event') ? 'active' : '' }}">
                                <i class="fa fa-calendar me-2"></i>Manage Event
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categori-booth') }}"
                                class="nav-link {{ request()->routeIs('categori-booth') ? 'active' : '' }}">
                                <i class="fa fa-th me-2"></i>Categori Booth
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('maps-booth') }}"
                                class="nav-link {{ request()->routeIs('maps-booth') ? 'active' : '' }}">
                                <i class="fa fa-map me-2"></i>Maps Booth
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-user') }}"
                                class="nav-link {{ request()->routeIs('manage-user') ? 'active' : '' }}">
                                <i class="fa fa-users me-2"></i>Manage User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('chat') }}"
                                class="nav-link {{ request()->routeIs('chat') ? 'active' : '' }}">
                                <i class="fa fa-envelope me-2"></i>Inbox
                            </a>
                        </li>

                        <!-- Dropdown Laporan -->
                        <li class="nav-item dropdown">
                            <a href="#"
                                class="nav-link dropdown-toggle {{ request()->routeIs('transaction') || request()->routeIs('admin.statistics*') || request()->is('laporan') ? 'active' : '' }}"
                                id="laporanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="far fa-file-alt me-2"></i>Laporan
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="laporanDropdown">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('transaction') ? 'active' : '' }}"
                                        href="{{ route('transaction') }}">Transaksi</a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.statistics*') ? 'active' : '' }}"
                                        href="{{ route('admin.statistics') }}">Statistik</a>
                                </li>
                                 <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.report*') ? 'active' : '' }}"
                                        href="{{ route('admin.report') }}">Rekap Laporan</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="{{ route('dashboard') }}" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>

                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>

                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-2" src="{{ asset('admin/img/user.jpg') }}" alt=""
                                style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 m-0">
                            <a href="{{ route('home') }}" class="dropdown-item">Lihat Website</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

            <!-- Chat Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-white rounded shadow-sm p-4">
                            <h6 class="mb-4">Chat Real-Time</h6>

                            <div class="row">
                                <!-- Sidebar Tenant List -->
                                <div class="col-md-4">
                                    <div class="chat-sidebar">
                                        <h6 class="p-3 mb-0 border-bottom">Daftar Tenant</h6>
                                        <div id="tenantList">
                                            <div class="text-center p-3">
                                                <div class="spinner-border spinner-border-sm" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="mb-0 mt-2">Memuat daftar tenant...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chat Area -->
                                <div class="col-md-8">
                                    <div id="chatHeader" class="border-bottom pb-2 mb-3" style="display: none;">
                                        <h6 class="mb-0">Chat dengan: <span id="selectedTenantName">-</span></h6>
                                    </div>

                                    <!-- Messages -->
                                    <div class="chat-box mb-3" id="chatBox"
                                        style="height: 350px; overflow-y: auto; border-radius: 10px; background-color: #f8f9fa; padding: 15px;">
                                        <div class="text-center text-muted">
                                            <p>Pilih tenant untuk memulai percakapan</p>
                                        </div>
                                    </div>

                                    <!-- Input Area -->
                                    <div class="d-flex" id="chatInput" style="display: none !important;">
                                        <input type="text" class="form-control me-2 border-0 rounded-pill shadow-sm"
                                            id="messageInput" placeholder="Ketik pesan..."
                                            style="border: 1px solid #ddd; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" />
                                        <button class="btn btn-primary rounded-pill" id="sendMessage"
                                            style="height: 40px; padding: 0 20px; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Chat End -->

            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Cresindo</a>, All Right Reserved.
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('admin/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('admin/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('admin/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('admin/js/main.js') }}"></script>

    <script>
        $(document).ready(function () {
            let selectedTenantId = null;
            let messageRefreshInterval = null;
            let isUserScrolling = false;
            let lastMessageCount = 0;

            // Setup AJAX headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Detect user scrolling
            $('#chatBox').on('scroll', function () {
                const chatBox = $(this)[0];
                const isAtBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 1;

                // User is scrolling if not at bottom
                isUserScrolling = !isAtBottom;

                // Clear scroll detection after 2 seconds of no scrolling
                clearTimeout(isUserScrolling.timeout);
                isUserScrolling.timeout = setTimeout(() => {
                    if (!isAtBottom) {
                        isUserScrolling = true;
                    }
                }, 2000);
            });

            // Load tenant list
            function loadTenantList() {
                $.get('/admin/chat/tenants', function (data) {
                    let html = '';
                    if (data.length === 0) {
                        html = '<div class="text-center p-3 text-muted"><p>Tidak ada tenant yang terdaftar</p></div>';
                    } else {
                        data.forEach(function (tenant) {
                            let unreadBadge = tenant.unread_count > 0 ?
                                `<span class="unread-badge float-end">${tenant.unread_count}</span>` : '';
                            let lastMessage = tenant.last_message ?
                                `<small class="text-muted d-block">${tenant.last_message.substring(0, 30)}${tenant.last_message.length > 30 ? '...' : ''}</small>` : '';

                            html += `
                        <div class="tenant-item" data-tenant-id="${tenant.id}" data-tenant-name="${tenant.name}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${tenant.name}</h6>
                                    <small class="text-muted">${tenant.email}</small>
                                    ${lastMessage}
                                </div>
                                <div class="text-end">
                                    ${unreadBadge}
                                    ${tenant.last_message_time ? `<small class="text-muted d-block mt-1">${tenant.last_message_time}</small>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                        });
                    }
                    $('#tenantList').html(html);
                }).fail(function () {
                    $('#tenantList').html('<div class="text-center p-3 text-danger"><p>Gagal memuat daftar tenant</p></div>');
                });
            }

            // Load messages with smart scrolling
            function loadMessages(tenantId, forceScrollToBottom = false) {
                const chatBox = $('#chatBox')[0];
                const wasAtBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 1;
                const currentScrollTop = chatBox.scrollTop;

                $.get(`/admin/chat/messages/${tenantId}`, function (data) {
                    let html = '';
                    if (data.length === 0) {
                        html = '<div class="text-center text-muted"><p>Belum ada pesan</p></div>';
                        lastMessageCount = 0;
                    } else {
                        data.forEach(function (message) {
                            let messageClass = message.is_sent ? 'message-sent' : 'message-received';
                            html += `
                        <div class="message-item ${messageClass}">
                            <div>${message.message}</div>
                            <div class="message-time">${message.time}</div>
                        </div>
                    `;
                        });
                    }

                    const newMessageCount = data.length;
                    const hasNewMessages = newMessageCount > lastMessageCount;

                    $('#chatBox').html(html);

                    // Smart scrolling logic
                    if (forceScrollToBottom || wasAtBottom || hasNewMessages) {
                        // Only scroll to bottom if:
                        // 1. Force scroll (initial load, new conversation)
                        // 2. User was already at bottom
                        // 3. There are new messages and user wasn't manually scrolling
                        if (!isUserScrolling || forceScrollToBottom) {
                            $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                        }
                    } else {
                        // Maintain scroll position if user was reading old messages
                        $('#chatBox').scrollTop(currentScrollTop);
                    }

                    lastMessageCount = newMessageCount;
                });
            }

            // Load messages for refresh (without forcing scroll)
            function refreshMessages(tenantId) {
                if (selectedTenantId === tenantId) {
                    loadMessages(tenantId, false);
                }
            }

            // Send message
            function sendMessage() {
                let message = $('#messageInput').val().trim();
                if (message === '' || selectedTenantId === null) return;

                let sendButton = $('#sendMessage');
                sendButton.prop('disabled', true);

                $.post('/admin/chat/send', {
                    receiver_id: selectedTenantId,
                    message: message
                }, function (response) {
                    if (response.success) {
                        $('#messageInput').val('');
                        // Force scroll to bottom when sending a message
                        loadMessages(selectedTenantId, true);
                        loadTenantList(); // Refresh tenant list to update last message
                        isUserScrolling = false; // Reset scrolling state
                    }
                }).fail(function () {
                    alert('Gagal mengirim pesan');
                }).always(function () {
                    sendButton.prop('disabled', false);
                });
            }

            // Event handlers
            $(document).on('click', '.tenant-item', function () {
                let tenantId = $(this).data('tenant-id');
                let tenantName = $(this).data('tenant-name');

                $('.tenant-item').removeClass('active');
                $(this).addClass('active');

                selectedTenantId = tenantId;
                $('#selectedTenantName').text(tenantName);
                $('#chatHeader').show();
                $('#chatInput').show();

                // Reset scrolling state for new conversation
                isUserScrolling = false;
                lastMessageCount = 0;

                // Force scroll to bottom for new conversation
                loadMessages(tenantId, true);

                // Start auto-refresh for this conversation with longer interval
                if (messageRefreshInterval) {
                    clearInterval(messageRefreshInterval);
                }
                messageRefreshInterval = setInterval(() => {
                    refreshMessages(tenantId);
                }, 5000); // Increased from 3 to 5 seconds
            });

            $('#sendMessage').click(sendMessage);

            $('#messageInput').keypress(function (e) {
                if (e.which === 13) {
                    sendMessage();
                }
            });

            // Add button to scroll to bottom manually
            function addScrollToBottomButton() {
                if ($('#scrollToBottom').length === 0) {
                    $('#chatBox').after(`
                <button id="scrollToBottom" class="btn btn-sm btn-secondary mt-2" style="display: none;">
                    <i class="fas fa-arrow-down"></i> Lihat pesan terbaru
                </button>
            `);

                    $('#scrollToBottom').click(function () {
                        $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                        isUserScrolling = false;
                        $(this).hide();
                    });
                }
            }

            // Show/hide scroll to bottom button
            $('#chatBox').on('scroll', function () {
                const chatBox = $(this)[0];
                const isAtBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 1;

                if (!isAtBottom && selectedTenantId) {
                    $('#scrollToBottom').show();
                } else {
                    $('#scrollToBottom').hide();
                }
            });

            // Initial load
            loadTenantList();
            addScrollToBottomButton();

            // Refresh tenant list every 30 seconds (reduced frequency)
            setInterval(loadTenantList, 30000);
        });
    </script>
</body>

</html>