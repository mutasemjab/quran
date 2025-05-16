<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Quran</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                @if (
                $user->can('class-table') ||
                $user->can('class-add') ||
                $user->can('class-edit') ||
                $user->can('class-delete'))
                <li class="nav-item">
                    <a href="{{ route('class.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.class')}} </p>
                    </a>
                </li>
                @endif

                @if (
                $user->can('student-table') ||
                $user->can('student-add') ||
                $user->can('student-edit') ||
                $user->can('student-delete'))
                <li class="nav-item">
                    <a href="{{ route('students.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.students')}} </p>
                    </a>
                </li>
                @endif

              @if (
                $user->can('teacher-table') ||
                $user->can('teacher-add') ||
                $user->can('teacher-edit') ||
                $user->can('teacher-delete'))
                <li class="nav-item">
                    <a href="{{ route('teachers.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.teachers')}} </p>
                    </a>
                </li>
                @endif

          @if (
                $user->can('parentStudent-table') ||
                $user->can('parentStudent-add') ||
                $user->can('parentStudent-edit') ||
                $user->can('parentStudent-delete'))
                <li class="nav-item">
                    <a href="{{ route('parentStudents.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.parentStudents')}} </p>
                    </a>
                </li>
                @endif


                @if (
                $user->can('lecture-table') ||
                $user->can('lecture-add') ||
                $user->can('lecture-edit') ||
                $user->can('lecture-delete'))
                <li class="nav-item">
                    <a href="{{ route('lectures.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.lectures')}} </p>
                    </a>
                </li>
                @endif

                @if (
                $user->can('athkar-table') ||
                $user->can('athkar-add') ||
                $user->can('athkar-edit') ||
                $user->can('athkar-delete'))
                <li class="nav-item">
                    <a href="{{ route('athkars.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.athkars')}} </p>
                    </a>
                </li>
                @endif



                @if (
                    $user->can('seera-table') ||
                    $user->can('seera-add') ||
                    $user->can('seera-edit') ||
                    $user->can('seera-delete'))
                    <li class="nav-item">
                        <a href="{{ route('seeras.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> {{__('messages.seeras')}} </p>
                        </a>
                    </li>
                    @endif


                @if (
                $user->can('attendance-table') ||
                $user->can('attendance-add') ||
                $user->can('attendance-edit') ||
                $user->can('attendance-delete'))
                <li class="nav-item">
                    <a href="{{ route('attendances.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.attendances')}} </p>
                    </a>
                </li>
                @endif


              @if (
                $user->can('exam-table') ||
                $user->can('exam-add') ||
                $user->can('exam-edit') ||
                $user->can('exam-delete'))
                <li class="nav-item">
                    <a href="{{ route('exams.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.exams')}} </p>
                    </a>
                </li>
                @endif

              @if (
                $user->can('grade-table') ||
                $user->can('grade-add') ||
                $user->can('grade-edit') ||
                $user->can('grade-delete'))
                <li class="nav-item">
                    <a href="{{ route('grades.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.grades')}} </p>
                    </a>
                </li>
                @endif





                @if (
                $user->can('game-table') ||
                $user->can('game-add') ||
                $user->can('game-edit') ||
                $user->can('game-delete'))
                <li class="nav-item">
                    <a href="{{ route('games.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.games')}} </p>
                    </a>
                </li>
                @endif


                @if (
                $user->can('notification-table') ||
                $user->can('notification-add') ||
                $user->can('notification-edit') ||
                $user->can('notification-delete'))
                <li class="nav-item">
                    <a href="{{ route('notifications.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.notifications')}} </p>
                    </a>
                </li>
                @endif




                {{-- <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            {{ __('messages.reports') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory_report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.inventory_report_with_costs') }} </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('order_report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.order_report') }} </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product_move') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.product_move') }} </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tax_report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.tax_report') }} </p>
                            </a>
                        </li>

                    </ul>
                </li> --}}

                @if (
                    $user->can('page-table') ||
                        $user->can('page-add') ||
                        $user->can('page-edit') ||
                        $user->can('page-delete'))
                    <li class="nav-item">
                        <a href="{{ route('pages.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{__('messages.Pages')}} </p>
                        </a>
                    </li>
                    @endif




                <li class="nav-item">
                    <a href="{{ route('admin.setting.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{__('messages.Settings')}} </p>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="{{ route('admin.login.edit',auth()->user()->id) }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{__('messages.Admin_account')}} </p>
                    </a>
                </li>

                @if ($user->can('role-table') || $user->can('role-add') || $user->can('role-edit') ||
                $user->can('role-delete'))
                <li class="nav-item">
                    <a href="{{ route('admin.role.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <span>{{__('messages.Roles')}} </span>
                    </a>
                </li>
                @endif

                @if (
                $user->can('employee-table') ||
                $user->can('employee-add') ||
                $user->can('employee-edit') ||
                $user->can('employee-delete'))
                <li class="nav-item">
                    <a href="{{ route('admin.employee.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <span> {{__('messages.Employee')}} </span>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
