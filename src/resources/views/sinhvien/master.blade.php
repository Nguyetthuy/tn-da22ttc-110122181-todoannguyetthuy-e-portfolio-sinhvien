
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sinh Viên</title>

    <!-- Google Font: Source Sans Pro -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
    />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" />
    <!-- Ionicons -->
    <link
      rel="stylesheet"
      href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"
    />
    <!-- Tempusdominus Bootstrap 4 -->
    <link
      rel="stylesheet"
      href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"
    />
    <!-- iCheck -->
    <link
      rel="stylesheet"
      href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}"
    />
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}" />
    <!-- overlayScrollbars -->
    <link
      rel="stylesheet"
      href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}"
    />
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}" />
   <!-- DataTables -->
   <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
   <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
   <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  </head>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"
              ><i class="fas fa-bars"></i
            ></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ asset('sinh-vien/') }}" class="nav-link">Home</a>
          </li>
          {{-- <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
          </li> --}}
        </ul>
        

        <!-- SEARCH FORM -->
        <form class="form-inline ml-3">
          <div class="input-group input-group-sm">
            <input
              class="form-control form-control-navbar"
              type="search"
              placeholder="Search"
              aria-label="Search"
            />
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <!-- Messages Dropdown Menu -->
          <li class="nav-item">
            <a
              class="nav-link"
              data-widget="fullscreen"
              href="{{ asset('/dang-xuat') }}"
              role="button"
            >
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index.html" class="brand-link">
          <img
            src="{{ asset('dist/img/AdminLTELogo.png') }}"
            alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: 0.8"
          />
          <span class="brand-text font-weight-light">Sinh Viên</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <img
                src="{{ asset('dist/img/avatar3.png') }}"
                class="img-circle elevation-2"
                alt="User Image"
              />
            </div>
            <div class="info">
               <a href="#" class="d-block">{{ Session::get('tenSV') }}</a>
            </div>

          </div>

          <!-- SidebarSearch Form -->
         {{--  <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
              <input
                class="form-control form-control-sidebar"
                type="search"
                placeholder="Tìm kiếm...."
                aria-label="Search"
              /> 
              <div class="input-group-append">
                <button class="btn btn-sidebar">
                  <i class="fas fa-search fa-fw"></i>
                </button>
              </div>
            </div>
          </div>--}}

          <!-- Sidebar Menu -->
          <nav class="mt-2">
            <ul
              class="nav nav-pills nav-sidebar flex-column"
              data-widget="treeview" role="menu" data-accordion="false">
           
              <li class="nav-item" >
                <a href="{{ asset('/sinh-vien') }}" class="nav-link" >
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p >Bảng thông tin tổng hợp</p>
                </a>
              </li>

              {{-- <li class="nav-item">
                <a href="{{ asset('/sinh-vien') }}"  class="nav-link">
                  <i class="fa fa-sticky-note"></i>
                 <p>Học kì</p> 
                </a>
                <ul style="display: block;">
                  <li >
                    <a href="{{ asset('/sinh-vien/khao-sat-cdr3_ctdt') }}"  class="nav-link">
                      <i class="nav-icon fas fa-book-reader"></i>
                     <p>Khảo sát CDR3</p> 
                    </a>
                  </li>
                  <li > 
                    <a href="{{ asset('/sinh-vien/khao-sat-ctdt') }}"  class="nav-link">
                      <i class="nav-icon fas fa-book-reader"></i>
                     <p>Khảo sát Chuan Abet</p> 
                    </a>
                  </li>
                 
                </ul>
              </li> --}}
              

             {{--  <li class="nav-item">
                <a
                  href="#"
                  class="nav-link"
                >
                  <i class="nav-icon fas fa-gavel"></i>

                  <p>Assessment Planing</p>
                </a>
              </li> 

                <li class="nav-item">
                <a href="{{ asset('/sinh-vien/mon-hoc') }}" class="nav-link">
                  <i class="nav-icon fas fa-store-alt"></i>
                  <p>Môn Học</p>
                </a>
              </li>  --}}
              
              {{-- <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-user-friends"></i>
                  <p>Assessment Result</p>
                </a>
              </li> 

              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-balance-scale-left"></i>
                  <p>Thống kê</p>
                </a>
              </li>--}}
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      @include('sweet::alert')
      @yield('content')

      <footer class="main-footer">
        <strong>Copyright &copy; 2020-2021
          <a href=""> {{ __('C.A.P system') }}</a>.</strong>
      <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 1.0
      </div>
      </footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge("uibutton", $.ui.button);
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
     $(function () {
      $("#example2").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
      
    });
    </script>
    <style>
  /* table {
        border-collapse: collapse;
        display: block;
        width: 100%;

    }
    thead, tbody {
        display: block;      
        width: 100%; 
    }
    tbody {
        overflow-y: scroll;
        overflow-x: hidden;
        height: 400px;
        width: 100%;
        
    }
     th, td {
        height: 10px;
        width: 100%; 
    } 
    
    .td-center{
      text-align: center;
    }
    .tr{
      width: 12%;
    }
    .sorting_1{
      width: 11%;
    }
    
   .th{
    
    width: 23%;
  }
    .sorting_2{
    
      width: 22%;
    }
      .tr-hp{
      width: 23%; 
    } */
    /* td:nth-child(9){
        min-width: 60px;
    }
    td:nth-child(8){
        min-width: 60px;
    }
    td:nth-child(7){
        min-width: 60px;
    }
    td:nth-child(6){
        min-width: 60px;
    }
    td:nth-child(5){
        min-width: 60px;
    }
    td:nth-child(4){
        min-width: 60px;
    }
   td:nth-child(3){
        min-width: 300px;
    }
    td:nth-child(2){
        min-width: 70px;
    }
    td:nth-child(1){
        min-width: 50px;
    } */
    /* a .btn-outline-secondary:visited{
      display: none;
    } */
  </style>
  <!-- ================= GEMINI CHAT WIDGET ================= -->
<!-- Thư viện Marked.js hỗ trợ hiển thị Markdown từ AI đẹp hơn -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<!-- Nút bong bóng chat (Floating Button) -->
<button id="gemini-chat-toggle" class="gemini-fab">
  <i class="fas fa-robot"></i>
</button>

<!-- Hộp thoại chat ẩn/hiện (Chat Window) -->
<div id="gemini-chat-window" class="gemini-chat-container">
  <div class="gemini-chat-header">
    <div class="gemini-header-info">
      <div class="gemini-avatar-logo"><i class="fas fa-wand-magic-sparkles"></i></div>
      <div>
        <h4>Trợ lý Học tập</h4>
        <span class="gemini-status">Đang hoạt động</span>
      </div>
    </div>
    <button id="gemini-chat-close" class="gemini-close-btn">&times;</button>
  </div>

  <div id="gemini-chat-messages" class="gemini-chat-body">
    <div class="gemini-msg bot">
      <div class="gemini-msg-bubble">
        Chào bạn! Tôi có thể hỗ trợ gì cho bạn trong học tập hôm nay?
      </div>
    </div>
  </div>

  <div class="gemini-chat-footer">
    <form id="gemini-chat-form" onsubmit="sendGeminiMessage(event)">
      <div class="gemini-input-group">
        <input 
          type="text" 
          id="gemini-user-input" 
          placeholder="Nhập nội dung cần hỏi..." 
          required 
          autocomplete="off"
        />
        <button type="submit" id="gemini-send-btn">
          <i class="fas fa-paper-plane"></i>
        </button>
      </div>
    </form>
  </div>
</div>

<style>
  /* CSS cho nút bong bóng chat nổi */
  .gemini-fab {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border: none;
    outline: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  .gemini-fab:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(139, 92, 246, 0.6);
  }

  .gemini-fab-tooltip {
    position: absolute;
    right: 75px;
    background: rgba(17, 24, 39, 0.9);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  }

  .gemini-fab:hover .gemini-fab-tooltip {
    opacity: 1;
  }

  .gemini-fab.active .gemini-fab-tooltip {
    display: none !important;
  }

  /* Khung chat thiết kế Glassmorphism hiện đại */
  .gemini-chat-container {
    position: fixed;
    bottom: 95px;
    right: 25px;
    width: 380px;
    height: 520px;
    background: rgba(25, 30, 49, 0.95);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    z-index: 9998;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    opacity: 0;
    transform: scale(0.9) translateY(20px);
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  .gemini-chat-container.active {
    opacity: 1;
    transform: scale(1) translateY(0);
    pointer-events: auto;
  }

  /* Header */
  .gemini-chat-header {
    background: rgba(15, 23, 42, 0.6);
    padding: 14px 18px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .gemini-header-info {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .gemini-avatar-logo {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 14px;
  }

  .gemini-header-info h4 {
    color: #f3f4f6;
    font-size: 14px;
    font-weight: 600;
    margin: 0;
  }

  .gemini-status {
    color: #10b981;
    font-size: 11px;
    display: block;
  }

  .gemini-close-btn {
    background: none;
    border: none;
    color: #9ca3af;
    font-size: 24px;
    cursor: pointer;
    line-height: 1;
  }

  .gemini-close-btn:hover {
    color: white;
  }

  /* Body Chat */
  .gemini-chat-body {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: rgba(17, 24, 39, 0.2);
  }

  .gemini-msg {
    display: flex;
    max-width: 80%;
  }

  .gemini-msg.user {
    align-self: flex-end;
  }

  .gemini-msg.bot {
    align-self: flex-start;
  }

  .gemini-msg-bubble {
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 13.5px;
    line-height: 1.5;
  }

  .gemini-msg.user .gemini-msg-bubble {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
    color: white;
    border-top-right-radius: 2px;
  }

  .gemini-msg.bot .gemini-msg-bubble {
    background: rgba(55, 65, 81, 0.8);
    color: #e5e7eb;
    border-top-left-radius: 2px;
    border: 1px solid rgba(255, 255, 255, 0.05);
  }

  /* Định dạng Markdown bên trong khung chat */
  .gemini-msg-bubble p { margin-bottom: 6px; }
  .gemini-msg-bubble p:last-child { margin-bottom: 0; }
  .gemini-msg-bubble code {
    background: rgba(0, 0, 0, 0.4);
    padding: 2px 4px;
    border-radius: 3px;
    font-family: monospace;
    color: #f472b6;
  }
  .gemini-msg-bubble pre {
    background: rgba(0, 0, 0, 0.6);
    padding: 8px;
    border-radius: 6px;
    overflow-x: auto;
    margin: 6px 0;
  }
  .gemini-msg-bubble pre code { background: none; color: #34d399; }

  /* Input Footer */
  .gemini-chat-footer {
    padding: 12px 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(15, 23, 42, 0.5);
  }

  .gemini-input-group {
    display: flex;
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 4px 8px;
    align-items: center;
  }

  .gemini-input-group input {
    flex: 1;
    background: transparent;
    border: none;
    outline: none;
    color: white;
    font-size: 13.5px;
    padding: 6px;
  }

  .gemini-input-group input::placeholder {
    color: #6b7280;
  }

  .gemini-input-group button {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border: none;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: transform 0.2s;
  }

  .gemini-input-group button:hover {
    transform: scale(1.05);
  }

  /* Dot Loader khi AI đang trả lời */
  .gemini-loader {
    display: flex;
    gap: 3px;
    align-items: center;
    height: 15px;
  }
  .gemini-loader span {
    width: 5px;
    height: 5px;
    background: #9ca3af;
    border-radius: 50%;
    animation: geminiBounce 1.4s infinite ease-in-out both;
  }
  .gemini-loader span:nth-child(1) { animation-delay: -0.32s; }
  .gemini-loader span:nth-child(2) { animation-delay: -0.16s; }

  @keyframes geminiBounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1.0); }
  }

  /* Tự động ẩn cuộn mặc định của sidebar AdminLTE */
  .gemini-chat-body::-webkit-scrollbar {
    width: 4px;
  }
  .gemini-chat-body::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
  }
</style>

<script>
  const geminiToggle = document.getElementById('gemini-chat-toggle');
  const geminiClose = document.getElementById('gemini-chat-close');
  const geminiWindow = document.getElementById('gemini-chat-window');
  const geminiMessages = document.getElementById('gemini-chat-messages');
  const geminiInput = document.getElementById('gemini-user-input');
  const geminiForm = document.getElementById('gemini-chat-form');

  marked.setOptions({ breaks: true });

  // [Giải thích]: Lắng nghe sự kiện click vào nút biểu tượng Chatbot (Floating Button)
  // - Khi click: Bật/Tắt class 'active' của cửa sổ chat (geminiWindow) và nút bấm (geminiToggle).
  // - Nếu cửa sổ chat mở ra (active), tự động đưa con trỏ chuột tập trung vào ô nhập liệu (geminiInput).
  geminiToggle.addEventListener('click', () => {
    geminiWindow.classList.toggle('active');
    geminiToggle.classList.toggle('active');
    if (geminiWindow.classList.contains('active')) {
      geminiInput.focus();
    }
  });

  // [Giải thích]: Lắng nghe sự kiện click vào nút Đóng (x) trên header cửa sổ chat
  // - Khi click: Loại bỏ class 'active' để ẩn cửa sổ chat và tắt trạng thái hoạt động của nút biểu tượng.
  geminiClose.addEventListener('click', () => {
    geminiWindow.classList.remove('active');
    geminiToggle.classList.remove('active');
  });

  // Thêm tin nhắn vào khung chat
  function appendGeminiMsg(sender, content, isHtml = false) {
    const msgDiv = document.createElement('div');
    msgDiv.className = `gemini-msg ${sender}`;
    const bubble = document.createElement('div');
    bubble.className = 'gemini-msg-bubble';
    
    if (isHtml) {
      bubble.innerHTML = content;
    } else {
      bubble.textContent = content;
    }
    
    msgDiv.appendChild(bubble);
    geminiMessages.appendChild(msgDiv);
    geminiMessages.scrollTop = geminiMessages.scrollHeight;
  }

  // Xử lý gửi tin nhắn
  async function sendGeminiMessage(event) {
    event.preventDefault();
    const text = geminiInput.value.trim();
    if (!text) return;

    geminiInput.value = '';
    geminiInput.disabled = true;

    appendGeminiMsg('user', text);

    // Hiển thị trạng thái đang nhập (typing loader)
    const loader = document.createElement('div');
    loader.className = 'gemini-msg bot';
    loader.id = 'gemini-loader-ui';
    loader.innerHTML = `<div class="gemini-msg-bubble"><div class="gemini-loader"><span></span><span></span><span></span></div></div>`;
    geminiMessages.appendChild(loader);
    geminiMessages.scrollTop = geminiMessages.scrollHeight;

    try {
      const response = await fetch('{{ url("/sinh-vien/gemini/generate") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}' // Sử dụng CSRF Token của Blade Layout
        },
        body: JSON.stringify({ prompt: text })
      });

      const result = await response.json();
      document.getElementById('gemini-loader-ui').remove();

      if (result.success) {
        // Dịch Markdown thành HTML
        const htmlContent = marked.parse(result.data);
        appendGeminiMsg('bot', htmlContent, true);
      } else {
        appendGeminiMsg('bot', `<span style="color: #ef4444;">Lỗi: ${result.error}</span>`, true);
      }
    } catch (error) {
      document.getElementById('gemini-loader-ui').remove();
      appendGeminiMsg('bot', `<span style="color: #ef4444;">Lỗi kết nối Server</span>`, true);
    } finally {
      geminiInput.disabled = false;
      geminiInput.focus();
    }
  }
</script>
<!-- ================= END GEMINI CHAT WIDGET ================= -->
  
  </body>
</html>
