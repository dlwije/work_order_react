<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{asset('img/squre_orions_white_only_logo.png')}}"
             alt="orions360 Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'orions360.com') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('img/default_user_img.png')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info"> <a href="#" class="d-block">{{ Auth::user()->name }}</a> </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                @role('System Admin')
                <li class="nav-item ">
                    <a href="{{route('home')}}" class="nav-link @if(end($site_url) == "home") active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>
                @endrole
                @role('Technician')
                <li class="nav-item ">
                    <a href="{{route('dashboard.technician')}}" class="nav-link @if(end($site_url) == "home") active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>
                @endrole

                <!-- Start:Company setup nav -->
                @role('System Admin')
                @if(
                     auth()->user()->can('user-list')
                     || auth()->user()->can('role-list')
                    )
                <li class="nav-item has-treeview
                    @if(
                            end($site_url) == "my-company"
                        || end($site_url) == "user"
                        || reset($site_url)== "user"
                        || end($site_url) == "edit"
                        || end($site_url) == "role"
                        || reset($site_url) == "role") menu-open @endif
                    ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Company Setup
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @role('System Admin')
                        <li class="nav-item">
                            <a href="{{route('myCompany')}}" class="nav-link @if(end($site_url) == "my-company") active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>My Company</p>
                            </a>
                        </li>
                        @endrole
                        @can('user-list')
                        <li class="nav-item">
                            <a href="{{route('user.index')}}" class="nav-link @if(end($site_url) == "user" || reset($site_url) == "user" || (reset($site_url) == "user" && end($site_url) == "edit")) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Users </p>
                            </a>
                        </li>
                        @endcan
                        @can('role-list')
                        <li class="nav-item">
                            <a href="{{route('role.index')}}" class="nav-link @if(end($site_url) == "role" || reset($site_url) == "role" || (reset($site_url) == "role" && end($site_url) == "edit")) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User Roles</p>
                            </a>
                        </li>
                        @endcan
                        {{--<li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Import Detail</p>
                            </a>
                        </li>--}}
                    </ul>
                </li>
                @endif
                @endrole
                <!-- End:Company setup nav -->

                @unlessrole('Technician')
                <!-- Start:Work order nav -->
                @if(
                     auth()->user()->can('workorder-list')
                     || auth()->user()->can('wworkorder-list')
                     || auth()->user()->can('estimate-list')
                    )
                <li class="nav-item has-treeview
                    @if(
                            end($site_url) == "workorder-list"
                        || end($site_url) == "workorder-create"
                        || end($site_url) == "workorder-edit"
                        || end($site_url) == "w-workorder-list"
                        || end($site_url) == "w-workorder-create"
                        || end($site_url) == "w-workorder-edit"
                        || end($site_url) == "estimate-list"
                        ) menu-open @endif
                ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-people-carry"></i>
                        <p>
                            Work Orders (WO)
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('workorder-list')
                            <li class="nav-item">
                                <a href="{{ route('workorder.list') }}" class="nav-link @if(end($site_url) == "workorder-list" || end($site_url) == "workorder-edit" || end($site_url) == "workorder-create") active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Work Order</p>
                                </a>
                            </li>
                        @endcan
                            @can('wworkorder-list')
                                <li class="nav-item">
                                    <a href="{{ route('w.workorder.list') }}" class="nav-link @if(end($site_url) == "w-workorder-list" || end($site_url) == "w-workorder-edit" || end($site_url) == "w-workorder-create") active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Warranty Work Order</p>
                                    </a>
                                </li>
                            @endcan
                            @can('estimate-list')
                                <li class="nav-item">
                                    <a href="{{ route('estimate.list') }}" class="nav-link @if(end($site_url) == "estimate-list") active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Estimate</p>
                                    </a>
                                </li>
                            @endcan
                    </ul>
                </li>
                @endif
                <!-- End:Work order nav -->

                <!-- Start:Job order nav -->
                @if(
                     auth()->user()->can('joborder-list')
                     || auth()->user()->can('joborder-list')
                     || auth()->user()->can('jo-cost-unapprove-list')
                     || auth()->user()->can('jo-work-unapprove-list')
                     || auth()->user()->can('jo-approved-list')
                    )
                <li class="nav-item has-treeview
                    @if(
                        end($site_url) == "joborder-list"
                        || end($site_url) == "joborder-create"
                        || end($site_url) == "joborder-edit"
                        || end($site_url) == "joborder-unapprove-cost-list"
                        || end($site_url) == "joborder-approved-list"
                        || end($site_url) == "joborder-assign-window"
                        ) menu-open @endif
                    ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            Job Order
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                        @can('joborder-list')
                    <ul class="nav nav-treeview">

                        <li class="nav-item ">
                            <a href="{{ route('joborder.list') }}" class="nav-link @if(end($site_url) == "joborder-list" || end($site_url) == "joborder-edit" || end($site_url) == "joborder-create") active @endif">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>
                        @can('jo-cost-unapprove-list')
                            <li class="nav-item">
                                <a href="{{ route('joborder.unapprove.cost.list') }}" class="nav-link @if(end($site_url) == "joborder-unapprove-cost-list") active @endif">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>To Be Approve(cost)</p>
                                </a>
                            </li>
                        @endcan
                        @can('jo-work-unapprove-listt')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>To Be Approve(work)</p>
                                </a>
                            </li>
                        @endcan
                        @can('jo-approved-list')
                            <li class="nav-item">
                                <a href="{{ route('joborder.approved.list') }}" class="nav-link @if(end($site_url) == "joborder-approved-list" || end($site_url) == "joborder-assign-window") active @endif">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Approved List</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                    @endcan
                </li>
                @endif
                <!-- End:Job order nav -->

                <!-- Start:Sales nav -->
                @if(auth()->user()->can('invoice-list'))
                <li class="nav-item has-treeview
                    @if(
                        end($site_url) == "invoice.list"
                        || end($site_url) == "invoice-create"
                        ) menu-open @endif
                    ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>
                            Sales
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('invoice-list')
                            <li class="nav-item">
                                <a href="{{ route('invoice.list') }}" class="nav-link @if(end($site_url) == "invoice-list" || end($site_url) == "invoice-create") active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Invoice</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endif
                <!-- End:Saless nav -->

                @endunlessrole
                <!-- Start:Labor's tasks nav -->
                @if(auth()->user()->can('labors-complete-list') || auth()->user()->can('labors-assign-list'))
                <li class="nav-item has-treeview
                    @if(
                        end($site_url) == "task-assigned-list"
                        || end($site_url) == "task-completed-list"
                        || end($site_url) == "service-completed-list"
                        ) menu-open @endif
                ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            Labor's tasks
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('labors-assign-list')
                        <li class="nav-item">
                            <a href="{{ route('task.assigned.list') }}" class="nav-link @if(end($site_url) == "task-assigned-list") active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Assigned labor task</p>
                            </a>
                        </li>
                        @endcan
                        @can('labors-complete-list')
                        <li class="nav-item">
                            <a href="{{ route('task.completed.list') }}" class="nav-link @if(end($site_url) == "task-completed-list") active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Completed labor task</p>
                            </a>
                        </li>
                        @endcan
                            @can('labors-complete-list')
                                <li class="nav-item">
                                    <a href="{{ route('service.completed.list') }}" class="nav-link @if(end($site_url) == "service-completed-list") active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Completed service packages</p>
                                    </a>
                                </li>
                            @endcan
                    </ul>
                </li>
                @endif
                <!-- End:Labor's tasks nav -->
                <!-- Start:Members nav -->
                @unlessrole('Technician')
                @if(auth()->user()->can('customer-list') || auth()->user()->can('employee-list'))
                    <li class="nav-item has-treeview
                    @if(
                        end($site_url) == "employee-list"
                        || end($site_url) == "employee-create"
                        || end($site_url) == "employee-edit"
                        || end($site_url) == "customer-list"

                        ) menu-open @endif
                    ">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Members
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('customer-list')
                            <li class="nav-item">
                                <a href="{{ route('customer.list') }}" class="nav-link @if(end($site_url) == "customer-list" || end($site_url) == "customer-edit" || end($site_url) == "customer-create") active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Customer</p>
                                </a>
                            </li>
                            @endcan
                            @can('employee-list')
                            <li class="nav-item">
                                <a href="{{route('employeeList')}}" class="nav-link @if(end($site_url) == "employee-list" || end($site_url) == "employee-edit" || end($site_url) == "employee-create") active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Employee</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                <!-- End:Members nav -->

                <!-- Start:Vehicles nav -->
                @if(auth()->user()->can('vehicle-type-list') || auth()->user()->can('vehicle-type'))
                    <li class="nav-item has-treeview
                    @if(
                        end($site_url) == "vehicle-list"
                        || end($site_url) == "vehicle-type-list"

                        ) menu-open @endif
                        ">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shuttle-van"></i>
                            <p>
                                Vehicles
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('vehicle-list')
                            <li class="nav-item">
                                <a href="{{ route('vehicle.list') }}" class="nav-link @if(end($site_url) == "vehicle-list") active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Vehicle</p>
                                </a>
                            </li>
                            @endcan
                            @can('vehicle-type-list')
                            <li class="nav-item">
                                <a href="{{ route('vehicle.type.list') }}" class="nav-link @if(end($site_url) == "vehicle-type-list") active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Vehicle Type</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                <!-- End:Vehicles nav -->
                <!-- Start:Service packages nav -->
                @if(
                       auth()->user()->can('service_pkg-list')
                    || auth()->user()->can('labortask-list')
                    || auth()->user()->can('task-category-list')
                    || auth()->user()->can('task-source-list')
                    || auth()->user()->can('task-type-list')
                    )
                <li class="nav-item has-treeview
                    @if(
                            end($site_url) == "labortask-list"
                        || end($site_url) == "labortask-create"
                        || end($site_url) == "labortask-edit"
                        || end($site_url) == "labortask-excel"
                        || end($site_url) == "task-category-list"
                        || end($site_url) == "task-source-list"
                        || end($site_url) == "task-type-list"
                        || end($site_url) == "service-package-list"

                        ) menu-open @endif
                ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>
                            Service Packages
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('service_pkg-list')
                        <li class="nav-item">
                            <a href="{{ route('service.package.list') }}" class="nav-link @if(end($site_url) == "service-package-list") active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Service Package</p>
                            </a>
                        </li>
                        @endcan
                        @can('labortask-list')
                        <li class="nav-item">
                            <a href="{{ route('labortask.list') }}" class="nav-link @if(end($site_url) == "labortask-list" || end($site_url) == "labortask-edit" || end($site_url) == "labortask-create" || end($site_url) == "labortask-excel") active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Labor Task</p>
                            </a>
                        </li>
                        @endcan
                        @can('task-category-list')
                        <li class="nav-item">
                            <a href="{{ route('task.category.list') }}" class="nav-link @if(end($site_url) == "task-category-list") active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Task Category</p>
                            </a>
                        </li>
                        @endcan
                        @can('task-source-list')
                        <li class="nav-item">
                            <a href="{{ route('task.source.list') }}" class="nav-link @if(end($site_url) == "task-source-list") active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Task Source</p>
                            </a>
                        </li>
                        @endcan
                        @can('task-type-list')
                        <li class="nav-item">
                            <a href="{{ route('task.type.list') }}" class="nav-link @if(end($site_url) == "task-type-list") active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Task Type</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif
                <!-- End:Service packages nav -->

                <!-- Start:Payments nav -->
                @if(
                       auth()->user()->can('cus-pay-list')
                    || auth()->user()->can('cus-depo-list')
                    )
                <li class="nav-item has-treeview
                    @if(
                        end($site_url) == "customer-deposit-list"
                        ) menu-open @endif
                    ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                            Payments
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('cus-pay-list')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Customer Payment</p>
                            </a>
                        </li>
                        @endcan
                        @can('cus-depo-list')
                        <li class="nav-item">
                            <a href="{{ route('customer.deposit.list') }}" class="nav-link @if(end($site_url) == "customer-deposit-list") active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Customer Deposit</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif
                <!-- End:Payments nav -->

                @endunlessrole
                @role('Parts Manager|System Admin')
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-dolly-flatbed"></i>
                        <p>
                            Inventory
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Technician's Parts Request</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Warranty's Parts Request</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                @role('System Admin')
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Report
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cash Collections</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profit & Loss</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profit & Loss Hierarchy</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stock Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daily Item Issues</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Customer Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Service Details</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
