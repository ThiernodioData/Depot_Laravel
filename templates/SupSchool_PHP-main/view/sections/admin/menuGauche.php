  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="admin">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Etudiant</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="listeEtudiant">
              <i class="bi bi-circle"></i><span>Interface</span>
            </a>
          </li>
            <a href="listeEtudiant">
              <i class="bi bi-circle"></i><span>Outils</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Evaluations</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="listeEvaluation">
              <i class="bi bi-circle"></i><span>Interface</span>
            </a>
          </li>
          <li>
            <a href="listeEvaluation">
              <i class="bi bi-circle"></i><span>Outils</span>
            </a>
          </li>

        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Notes</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="listeNote">
              <i class="bi bi-circle"></i><span>Interface</span>
            </a>
          </li>
          <li>
            <a href="listeNote">
              <i class="bi bi-circle"></i><span>Outils</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Users</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="listeUser">
              <i class="bi bi-circle"></i><span>Interface</span>
            </a>
          </li>
          <li>
            <a href="listeUser">
              <i class="bi bi-circle"></i><span>Outils</span>
            </a>
          </li>
          
        </ul>
      </li><!-- End Charts Nav -->



      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="admin">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->


      <li class="nav-item">
        <a class="nav-link collapsed" href="userMainController?logout=1">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Deconnexion</span>
        </a>
      </li>
      <!-- End Login Page Nav -->


    </ul>

  </aside>
  <!-- End Sidebar-->