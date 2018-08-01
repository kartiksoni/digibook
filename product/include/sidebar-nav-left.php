<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<ul class="nav">
<?php 
if($_SESSION['auth']['type'] == "PHARMACY"){
?>
<!-- USER SECTION -->
<li class="nav-item nav-profile">
    <div class="nav-link d-flex">
        <div class="profile-image">
            <img src="images/faces/face1.jpg" alt="image"/>
            <span class="online-status online"></span> <!--change class online to offline or busy as needed-->
        </div>
        <div class="profile-name">
            <p class="name"><?php echo ucfirst(strtolower($_SESSION['auth']['name'])); ?></p>
            <p class="designation"><?php echo ucfirst(strtolower($_SESSION['auth']['type'])); ?></p>
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
<span class="badge badge-primary badge-pill">1</span>
</a>
</li>

<!--  section headings -->

<!--<li class="nav-item nav-category">
<span class="nav-link">Layouts</span>
</li>-->


<li class="nav-item">
<a class="nav-link" data-toggle="collapse" href="#page-layouts" aria-expanded="false" aria-controls="page-layouts">
<i class="icon-layers menu-icon"></i>
<span class="menu-title">Configuration</span>
<i class="menu-arrow"></i>
</a>
<div class="collapse" id="page-layouts">
    <ul class="nav flex-column sub-menu">
        <li class="nav-item"> <a class="nav-link" href="vendor-management.php">Vendor Management</a></li>
        <li class="nav-item"> <a class="nav-link" href="customer-managment.php">Customer Management</a></li>
        <li class="nav-item"> <a class="nav-link" href="product-type-master.php">Product Type Master</a></li>
        <li class="nav-item"> <a class="nav-link" href="product-category-master.php">Product Category Master</a></li>
        <li class="nav-item"> <a class="nav-link" href="product-master.php">Product Master</a></li>
        <li class="nav-item"> <a class="nav-link" href="service-master.php">Service Master</a></li>
        <li class="nav-item"> <a class="nav-link" href="financial-year.php">Financial Year Management</a></li>
        <li class="nav-item"> <a class="nav-link" href="pharmacy-profile.php">Pharmacy Profile</a></li>
        <li class="nav-item"> <a class="nav-link" href="#">Notification Master</a></li>
        <li class="nav-item"> <a class="nav-link" href="vendor-bank-managment.php">Bank Management</a></li>
    </ul>
</div>
</li>

<li class="nav-item">
    <a class="nav-link" href="inventory.php">
    <i class="icon-server menu-icon"></i>
    <span class="menu-title">Inventory </span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="purchase.php">
    <i class="icon-bag menu-icon"></i>
    <span class="menu-title">Purchase </span>
    </a>
</li>

<li class="nav-item">
<a class="nav-link" data-toggle="collapse" href="#delievery-challan" aria-expanded="false" aria-controls="delievery-challan">
<i class="icon-layers menu-icon"></i>
<span class="menu-title">Delievery Challan</span>
<i class="menu-arrow"></i>
</a>
<div class="collapse" id="delievery-challan">
    <ul class="nav flex-column sub-menu">
        <li class="nav-item"> <a class="nav-link" href="#">Add challan</a></li>
        <li class="nav-item"> <a class="nav-link" href="#">View challan</a></li>
    </ul>
</div>
</li>


<li class="nav-item">
    <a class="nav-link" href="#">
    <i class="icon-paper menu-icon"></i>
    <span class="menu-title">Sell </span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="#">
    <i class="icon-repeat menu-icon"></i>
    <span class="menu-title">Transfer </span>
    </a>
</li>


<li class="nav-item">
    <a class="nav-link" href="#">
    <i class="icon-briefcase menu-icon"></i>
    <span class="menu-title">Leads </span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="order.php">
    <i class="icon-monitor menu-icon"></i>
    <span class="menu-title">Order Place </span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="#">
    <i class="icon-pie-graph menu-icon"></i>
    <span class="menu-title">Reports </span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="#">
    <i class="icon-link menu-icon"></i>
    <span class="menu-title">Branch </span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="#">
    <i class="icon-cog menu-icon"></i>
    <span class="menu-title">Accounts </span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="#">
    <i class="icon-head menu-icon"></i>
    <span class="menu-title">Users </span>
    </a>
</li>
<?php } ?>

<li class="nav-item">
    <a class="nav-link" href="logout.php">
    <i class="icon-unlock menu-icon"></i>
    <span class="menu-title">Logout </span>
    </a>
</li>


</ul>
</nav>