<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<ul class="nav">

<!-- USER SECTION -->
<li class="nav-item nav-profile">
    <div class="nav-link d-flex">
        <div class="profile-image">
            <img src="images/faces/face1.jpg" alt="image"/>
            <span class="online-status online"></span> <!--change class online to offline or busy as needed-->
        </div>
    
        <div class="profile-name">
            <p class="name">Daniel Russiel</p>
            <p class="designation">Senior Architect</p>
        </div>
    </div>
</li><!-- END USER SECTION -->



<li class="nav-item nav-category">
<span class="nav-link">Main</span>
</li>
<li class="nav-item">
<a class="nav-link" href="index.php">
<i class="icon-layout menu-icon"></i>
<span class="menu-title">Dashboard</span>
</a>
</li>

<!--  section headings -->

<li class="nav-item">
    <a class="nav-link" href="configuration.php">
    <i class="icon-layers menu-icon"></i>
    <span class="menu-title">Configuration</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" data-toggle="collapse" href="#page-layouts" aria-expanded="false" aria-controls="page-layouts">
    <i class="icon-head menu-icon"></i>
    <span class="menu-title">User Management</span>
    <i class="menu-arrow"></i>
    </a>
    <div class="collapse" id="page-layouts">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="view-user.php">View Users</a></li>
        </ul>
    </div>
</li>

<!--<li class="nav-item">
    <a class="nav-link" data-toggle="collapse" href="#page-module" aria-expanded="false" aria-controls="page-layouts">
    <i class="icon-head menu-icon"></i>
    <span class="menu-title">Module Management</span>
    <i class="menu-arrow"></i>
    </a>
    <div class="collapse" id="page-module">
        <ul class="nav flex-column sub-menu"><li class="nav-item"> <a class="nav-link" href="add-module.php">Add Module</a></li></ul>
        <ul class="nav flex-column sub-menu"><li class="nav-item"> <a class="nav-link" href="#">Add Pages</a></li></ul>
        <ul class="nav flex-column sub-menu"><li class="nav-item"> <a class="nav-link" href="#">View Pages</a></li></ul>
    </div>
</li>-->

<li class="nav-item">
    <a class="nav-link" href="logout.php">
    <i class="icon-unlock menu-icon"></i>
    <span class="menu-title">Logout </span>
    </a>
</li>


</ul>
</nav>