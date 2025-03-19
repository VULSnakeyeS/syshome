<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('dashboard') }}">
        <img src="../assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="...">
        <span class="ms-3 font-weight-bold">Gestionale Casa</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('dashboard') ? 'active' : '') }}" href="{{ url('dashboard') }}">
          <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-primary text-white">
            <i class="fas fa-home"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      <li class="nav-item mt-2">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Casa</h6>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('servizi') ? 'active' : '') }}" href="{{ url('servizi') }}">
          <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-info text-white">
            <i class="fas fa-concierge-bell"></i>
          </div>
          <span class="nav-link-text ms-1">Servizi</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('prodotti') ? 'active' : '') }}" href="{{ url('prodotti') }}">
          <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-success text-white">
            <i class="fas fa-box"></i>
          </div>
          <span class="nav-link-text ms-1">Prodotti</span>
        </a>
      </li>
      
      <!-- Nuevo elemento para Lista de Compras -->
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('shopping-list') ? 'active' : '') }}" href="{{ url('shopping-list') }}">
          <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-warning text-white">
            <i class="fas fa-shopping-cart"></i>
          </div>
          <span class="nav-link-text ms-1">Lista della Spesa
            @if(isset($shopping_items_count) && $shopping_items_count > 0)
              <span class="badge bg-danger ms-2">{{ $shopping_items_count }}</span>
            @endif
          </span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('compiti') ? 'active' : '') }}" href="{{ url('compiti') }}">
            <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-primary text-white">
                <i class="fas fa-tasks"></i>
            </div>
            <span class="nav-link-text ms-1">
                Compiti 
                @if(isset($compiti_pendenti_count) && $compiti_pendenti_count > 0)
                    <span class="badge bg-danger ms-2">{{ $compiti_pendenti_count }}</span>
                @endif
            </span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('animali') ? 'active' : '') }}" href="{{ url('animali') }}">
            <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-warning text-dark">
                <i class="fas fa-paw"></i>
            </div>
            <span class="nav-link-text ms-1">Animali</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('users') ? 'active' : '') }}" href="{{ url('users') }}">
          <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-danger text-white">
            <i class="fas fa-users-cog"></i>
          </div>
          <span class="nav-link-text ms-1">Gestione Utenti</span>
        </a>
      </li>

    </ul>
  </div>
</aside>



