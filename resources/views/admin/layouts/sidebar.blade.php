<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="bx bx-grid-alt"></i>
                        <span key="t-chat">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-list-ul"></i>
                        <span key="t-dashboards">Manage Categories</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('category') }}" key="t-tui-calendar">Categories</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route("product") }}" class="waves-effect">
                        <i class="bx bx-package"></i>
                        <span key="t-chat">Add Products</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route("productCategory") }}" class="waves-effect">
                        <i class="bx bx-collection"></i>
                        <span key="t-chat">Product Category</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>