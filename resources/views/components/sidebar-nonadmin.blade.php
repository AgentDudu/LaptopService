<div class="d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 250px; height: 100vh; background-color: #067D40">
    <style>
        .nav-link:hover, .nav-link.active, .nav-link-button:hover, .nav-link-button.active {
            background-color: rgba(255, 255, 255, 0.2); 
        }

        .bg-custom {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .nav-link, .nav-link-button {
            color: white !important; 
            font-size: 1rem; 
            padding: 0.6rem 1.25rem; 
        }

        .nav-link:visited {
            color: white !important; 
        }

        .nav-link-button {
            background-color: transparent;
            border: none;
            text-align: left;
            width: 100%;
            font-weight: bold; 
        }

        .bi-chevron-right {
            transition: transform 0.3s ease;
        }

        .bi-chevron-right.rotate {
            transform: rotate(90deg);
        }
        .nav-link svg, .nav-link-button svg {
            width: 24px; 
            height: 24px; 
            margin-right: 8px; 
        }
    </style>

    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <x-images.logo-circle alt="Laptop Cafe Jogjakarta" class="img-fluid rounded-circle" width="75" style="margin-left: 8px"/>
        <h5 style="text-align: center; margin-left: -10px; margin-top: 15px;">Laptop Cafe Jogjakarta</h5>
    </a>
    <br>

    {{-- Menu Side Nav --}}
    <ul class="nav nav-link-secondary flex-column fw-bold gap-2">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link text-white ps-4 {{ Route::is('dashboard.*') ? 'text-white bg-custom rounded active' : 'inactive' }}">
                @include('components.icons.svg-dashboard')
                Dashboard
            </a>
        </li>
        <li>
            <a href="#transaksi-submenu" class="nav-link text-white d-flex justify-content-between align-items-center {{ Route::is('transaksi.*') ? 'inactive' : '' }}" onclick="toggleCollapse('transaksi-submenu', this)">
                <span>
                    @include('components.icons.svg-transaksi')
                    Transaksi
                </span>
                <i class="bi bi-chevron-right"></i>
            </a>
            <div id="transaksi-submenu" class="collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li class="nav-item">
                        <a href="{{ route('transaksi_servis.index') }}" class="nav-link ps-4 {{ Route::is('servis.*') ? 'text-white bg-custom rounded active' : 'inactive' }}">
                            @include('components.icons.svg-transServis')
                            Servis
                        </a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('transaksi_sparepart.index') }}" class="nav-link ps-4 {{ Route::is('sparepart.*') ? 'text-white bg-custom rounded active' : 'inactive' }}">
                     @include('components.icons.svg-transSparepart')
                            Sparepart
                        </a>
                    </li>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <a href="#database-submenu" class="nav-link text-white d-flex justify-content-between align-items-center {{ Route::is('database.*') ? 'inactive' : '' }}" onclick="toggleCollapse('database-submenu', this)">
                <span>
                    @include('components.icons.svg-database')
                    Database
                </span>
                <i class="bi bi-chevron-right"></i>
            </a>
            <div id="database-submenu" class="collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="{{ route('pelanggan.index') }}" class="nav-link text-white ps-4 {{ Route::is('customers.*') ? 'text-white bg-custom rounded active' : 'inactive' }}">
                        @include('components.icons.svg-pelanggan')
                        Pelanggan</a></li>
                    <li><a href="{{ route('laptop.index') }}" class="nav-link text-white ps-4 {{ Route::is('laptops.*') ? 'text-white bg-custom rounded active' : 'inactive' }}">
                        @include('components.icons.svg-laptop')
                        Laptop</a></li>
                    <li><a href="{{ route('jasaServis.index') }}" class="nav-link text-white ps-4 {{ Route::is('jasaServis.*') ? 'text-white bg-custom rounded active' : 'inactive' }}">
                        @include('components.icons.svg-jasaServis')
                        Jasa Servis</a></li>
                </ul>
            </div>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link nav-link-button {{ Route::is('logout.*') ? 'active' : 'inactive' }}">
                    @include('components.icons.svg-logout')
                    Logout
                </button>
            </form>
        </li>
    </ul>
</div>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

{{-- JS --}}
<script>
    function toggleCollapse(id, element) {
        const submenu = document.getElementById(id);
        const isCollapsed = submenu.classList.contains('show');
        if (isCollapsed) {
            submenu.classList.remove('show');
        } else {
            submenu.classList.add('show');
        }
        // Save submenu state in local storage
        localStorage.setItem(id, submenu.classList.contains('show'));

        // Rotate the arrow
        const arrow = element.querySelector('.bi-chevron-right');
        arrow.classList.toggle('rotate', !isCollapsed);
    }

    // Load the state of the submenus from local storage
    document.addEventListener('DOMContentLoaded', function () {
        const submenus = ['transaksi-submenu', 'database-submenu'];
        submenus.forEach(function (id) {
            const submenu = document.getElementById(id);
            const isOpen = localStorage.getItem(id) === 'true';
            if (isOpen) {
                submenu.classList.add('show');
                // Rotate the arrow
                const arrow = document.querySelector(`a[href="#${id}"] .bi-chevron-right`);
                if (arrow) {
                    arrow.classList.add('rotate');
                }
            }
        });
    });

    // Highlight the active link
    document.querySelectorAll('.nav-link, .nav-link-button').forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
    });
</script>
